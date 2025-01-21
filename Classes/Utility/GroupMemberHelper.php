<?php

namespace JPMSchuler\ShowPageEditors\Utility;

class GroupMemberHelper
{
    public static function showList(int $groupID): array
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
            $output[] = 'GID_' . $group['uid'] . ' ' . $group['title'];
            foreach (BeUserUtility::fetchBeUsers($group['uid']) as $user) {
                $output[] = 'UID_' . $user['uid'] . ' ' . $user['username'];
            }
        }
        return $output;
    }
}
