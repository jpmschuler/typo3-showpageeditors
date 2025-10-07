<?php

namespace JPMSchuler\ShowPageEditors\Utility;

use Symfony\Component\Console\Output\OutputInterface;

class GroupMemberHelper
{
    public static function getList(int $groupID, int $verbosity = OutputInterface::VERBOSITY_NORMAL): array
    {
        $inherited = BeUserUtility::fetchBeGroupsWhichInherit($groupID);

        $output = [];
        $originalGroup = BeUserUtility::fetchBeGroup($groupID)[0];
        $output[] = 'Group members of group ' .
            $originalGroup['uid'] . ' (' . $originalGroup['title'] . ')' .
            ($inherited ? ' and other groups inheriting from it' : '') . ':';
        if ($originalGroup) {
            array_unshift($inherited, $originalGroup);
        }
        foreach ($inherited as $group) {
            $output[] = OutputHelper::generateGroupLine($group, $verbosity);
            foreach (BeUserUtility::fetchBeUsers($group['uid']) as $user) {
                $output[] = OutputHelper::generateUserLine($user, $verbosity);
            }
        }
        return $output;
    }
}

