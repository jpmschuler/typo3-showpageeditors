<?php

namespace JPMSchuler\ShowPageEditors\Utility;

use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

class PageVisibleHelper
{
    public static function getList(int $pageId, int $verbosity = OutputInterface::VERBOSITY_NORMAL): array
    {
        $pages = static::getPageTreeForPid($pageId);
        $output = [];

        $output[] = 'Showing all pages up to TYPO3 root and all groups and users';
        $output[] = 'which have access on each level (and thus below).';
        $output[] = 'This does not consider page access, but only db mounts.';
        $output[] = '';
        $output[] = 'Page visibility for pid ' . $pageId . ':';

        foreach ($pages as $item) {
            $output[] = 'PID_' . $item['uid'] . ' ' . $item['title'];
            $groupList = BeUserUtility::fetchBeGroupsForDbMountpoint($item['uid']);
            if (count($groupList) > 0) {
                foreach ($groupList as $group) {
                    $output[] = OutputHelper::generateGroupLine($group, $verbosity);
                    foreach (BeUserUtility::fetchBeUsers($group['uid']) as $user) {
                        $output[] = OutputHelper::generateUserLine($user, $verbosity);
                    }
                }
            }
        }

        return $output;
    }

    public static function getPageTreeForPid(int $pid)
    {
        $rootline = GeneralUtility::makeInstance(RootlineUtility::class, $pid);
        $rootlinePages = $rootline->get();
        $rootlinePages[] = ['uid' => 0, 'title' => 'TYPO3 root'];

        return array_reverse($rootlinePages);
    }
}
