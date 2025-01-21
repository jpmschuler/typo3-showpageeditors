<?php

namespace JPMSchuler\ShowPageEditors\Utility;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BeUserUtility
{
    /**
     * @throws Exception
     */
    public static function fetchBeGroup(int $groupId): ?array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');

        return $queryBuilder
            ->select('uid', 'title')
            ->from('be_groups')
            ->andWhere(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($groupId, Connection::PARAM_INT)),
            )
            ->executeQuery()->fetchAllAssociative() ?: [
                ['uid' => $groupId, 'title' => $groupId == 0 ? 'Admin users' : 'ERROR: group not found'],
            ];
    }

    public static function fetchBeUsers(int $groupId): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');

        if ($groupId === 0) {
            /** @var Result $result */
            $result = $queryBuilder
                ->select('uid', 'username')
                ->from('be_users')
                ->andWhere(
                    $queryBuilder->expr()->eq('admin', $queryBuilder->createNamedParameter(1)),
                )
                ->executeQuery();
            $rows = $result->fetchAllAssociative();
        } else {
            /** @var Result $result */
            $result = $queryBuilder
                ->select('uid', 'username')
                ->from('be_users')
                ->orWhere(
                    $queryBuilder->expr()->eq('usergroup', $queryBuilder->createNamedParameter($groupId)),
                    $queryBuilder->expr()->like(
                        'usergroup',
                        $queryBuilder->createNamedParameter('%,' . $queryBuilder->escapeLikeWildcards($groupId) . ',%'),
                    ),
                    $queryBuilder->expr()->like(
                        'usergroup',
                        $queryBuilder->createNamedParameter('%,' . $queryBuilder->escapeLikeWildcards($groupId)),
                    ),
                    $queryBuilder->expr()->like(
                        'usergroup',
                        $queryBuilder->createNamedParameter($queryBuilder->escapeLikeWildcards($groupId) . ',%'),
                    ),
                )
                ->executeQuery();
            $rows = $result->fetchAllAssociative();
        }

        return $rows;
    }

    /**
     * @throws Exception
     */
    public static function fetchBeGroupsWhichInherit(int $groupId): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');

        /** @var Result $result */
        $result = $queryBuilder
            ->select('uid', 'title')
            ->from('be_groups')
            ->andWhere(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq('subgroup', $queryBuilder->createNamedParameter($groupId)),
                    $queryBuilder->expr()->like(
                        'subgroup',
                        $queryBuilder->createNamedParameter(
                            '%,' . $queryBuilder->escapeLikeWildcards($groupId)
                            . ',%',
                        ),
                    ),
                    $queryBuilder->expr()->like(
                        'subgroup',
                        $queryBuilder->createNamedParameter('%,' . $queryBuilder->escapeLikeWildcards($groupId)),
                    ),
                    $queryBuilder->expr()->like(
                        'subgroup',
                        $queryBuilder->createNamedParameter($queryBuilder->escapeLikeWildcards($groupId) . ',%'),
                    ),
                ),
            )
            ->executeQuery();
        $rows = $result->fetchAllAssociative();
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $newRows = static::fetchBeGroupsWhichInherit($row['uid']);
                foreach ($newRows as $newRow) {
                    $rows[] = $newRow;
                }
            }
        }
        return $rows;
    }

    /**
     * @throws Exception
     */
    public static function fetchBeGroupsForDbMountpoint($pageId): array
    {
        if ($pageId === 0) {
            return [
                ['uid' => 0, 'title' => 'Admin users'],
            ];
        }
        $groups = static::fetchBeGroupsWithMatchingDbMountpoint($pageId);
        foreach ($groups as $group) {
            $inherited = self::fetchBeGroupsWhichInherit($group['uid']);
            if (count($inherited) > 0) {
                foreach ($inherited as $newGroup) {
                    $groups[] = $newGroup;
                }
            }
        }

        return $groups;
    }

    /**
     * @throws Exception
     */
    public static function fetchBeGroupsWithMatchingDbMountpoint(int $pageId): ?array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');
        return $queryBuilder
            ->select('uid', 'title')
            ->from('be_groups')->andWhere(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq('db_mountpoints', $queryBuilder->createNamedParameter($pageId)),
                    $queryBuilder->expr()->like(
                        'db_mountpoints',
                        $queryBuilder->createNamedParameter('%,' . $queryBuilder->escapeLikeWildcards($pageId) . ',%'),
                    ),
                    $queryBuilder->expr()->like(
                        'db_mountpoints',
                        $queryBuilder->createNamedParameter('%,' . $queryBuilder->escapeLikeWildcards($pageId)),
                    ),
                    $queryBuilder->expr()->like(
                        'db_mountpoints',
                        $queryBuilder->createNamedParameter($queryBuilder->escapeLikeWildcards($pageId) . ',%'),
                    ),
                ),
            )->executeQuery()->fetchAllAssociative();
    }
}
