<?php

namespace JPMSchuler\ShowPageEditors\Utility;

use Doctrine\DBAL\Statement;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

class PageVisibleHelper
{
    public static function showList(int $pageId): array
    {
        $pages = static::getPageTreeForPid($pageId);
        $output = [];

        $output[] = 'Analysing page ' . $pageId . ':';
        foreach ($pages as $item) {
            $output[] = str_pad($item['uid'], 5, '0', STR_PAD_LEFT) . ' ' . $item['title'];
            $groupList = static::fetchBeGroupsForDbMountpoint($item['uid']);
            if (count($groupList) > 0) {
                foreach ($groupList as $group) {
                    $output[] = ' group-' . $group['uid'] . ' ' . $group['title'];
                    foreach (static::fetchBeUsers($group['uid']) as $user) {
                        $output[] = '  user-' . $user['uid'] . ' ' . $user['username'];
                    }
                }
            }
        }

        return $output;
    }

    public static function fetchBeUsers(int $groupId): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');

        if (0 === $groupId) {
            /** @var Statement $result */
            $result = $queryBuilder
                ->select('uid', 'username')
                ->from('be_users')
                ->andWhere(
                    $queryBuilder->expr()->eq('admin', $queryBuilder->createNamedParameter(1))
                )
                ->execute();
            $rows = $result->fetchAll();
        } else {
            /** @var Statement $result */
            $result = $queryBuilder
                ->select('uid', 'username')
                ->from('be_users')
                ->orWhere(
                    $queryBuilder->expr()->eq('usergroup', $queryBuilder->createNamedParameter($groupId)),
                    $queryBuilder->expr()->like(
                        'usergroup',
                        $queryBuilder->createNamedParameter('%,' . $queryBuilder->escapeLikeWildcards($groupId) . ',%')
                    ),
                    $queryBuilder->expr()->like(
                        'usergroup',
                        $queryBuilder->createNamedParameter('%,' . $queryBuilder->escapeLikeWildcards($groupId))
                    ),
                    $queryBuilder->expr()->like(
                        'usergroup',
                        $queryBuilder->createNamedParameter($queryBuilder->escapeLikeWildcards($groupId) . ',%')
                    )
                )
                ->execute();
            $rows = $result->fetchAll();
        }

        return $rows;
    }

    public static function fetchBeGroupsForDbMountpoint($pageId): array
    {
        if (0 === $pageId) {
            return [
                ['uid' => 0, 'title' => 'Admin-flagged users'],
            ];
        }
        $groups = static::fetchBeGroupsWithMatchingDbMountpoint($pageId);
        foreach ($groups as $group) {
            $inherited = static::fetchBeGroupsWhichInherit($group['uid']);
            if (count($inherited) > 0) {
                foreach ($inherited as $newGroup) {
                    $groups[] = $newGroup;
                }
            }
        }

        return $groups;
    }

    public static function fetchBeGroupsWhichInherit(int $groupId): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');

        /** @var Statement $result */
        $result = $queryBuilder
            ->select('uid', 'title')
            ->from('be_groups')
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('subgroup', $queryBuilder->createNamedParameter($groupId)),
                    $queryBuilder->expr()->like(
                        'subgroup',
                        $queryBuilder->createNamedParameter('%,' . $queryBuilder->escapeLikeWildcards($groupId) . ',%')
                    ),
                    $queryBuilder->expr()->like(
                        'subgroup',
                        $queryBuilder->createNamedParameter('%,' . $queryBuilder->escapeLikeWildcards($groupId))
                    ),
                    $queryBuilder->expr()->like(
                        'subgroup',
                        $queryBuilder->createNamedParameter($queryBuilder->escapeLikeWildcards($groupId) . ',%')
                    )
                )
            )
            ->execute();
        $rows = $result->fetchAll();
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

    public static function fetchBeGroupsWithMatchingDbMountpoint(int $pageId): ?array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');

        /** @var Statement $result */
        $result = $queryBuilder
            ->select('uid', 'title')
            ->from('be_groups')
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('db_mountpoints', $queryBuilder->createNamedParameter($pageId)),
                    $queryBuilder->expr()->like(
                        'db_mountpoints',
                        $queryBuilder->createNamedParameter('%,' . $queryBuilder->escapeLikeWildcards($pageId) . ',%')
                    ),
                    $queryBuilder->expr()->like(
                        'db_mountpoints',
                        $queryBuilder->createNamedParameter('%,' . $queryBuilder->escapeLikeWildcards($pageId))
                    ),
                    $queryBuilder->expr()->like(
                        'db_mountpoints',
                        $queryBuilder->createNamedParameter($queryBuilder->escapeLikeWildcards($pageId) . ',%')
                    )
                )
            )
            ->execute();
        return $result->fetchAll();
    }

    public static function getPageTreeForPid(int $pid)
    {
        $rootline = GeneralUtility::makeInstance(RootlineUtility::class, $pid);
        $rootlinePages = $rootline->get();
        $rootlinePages[] = ['uid' => 0, 'title' => 'TYPO3 root'];

        return array_reverse($rootlinePages);
    }
}
