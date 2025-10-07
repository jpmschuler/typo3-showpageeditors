<?php

namespace JPMSchuler\ShowPageEditors\Utility;

use Symfony\Component\Console\Output\OutputInterface;

class OutputHelper
{
    public static function generateGroupLine(array $group, int $verbosity = OutputInterface::VERBOSITY_NORMAL): string
    {
        return 'GID_' . $group['uid'] . ' ' . $group['title'];
    }
    public static function generateUserLine(array $user, int $verbosity = OutputInterface::VERBOSITY_NORMAL): string
    {
        $line = 'UID_' . $user['uid'] . ' ' . $user['username'];
        if ($verbosity >= OutputInterface::VERBOSITY_VERBOSE) {
            $line .= ' (email: ' . $user['email'] . ' - last login: ' . static::timeAgo($user['tstamp']) . ')';
        }
        return $line;
    }

    public static function timeAgo(int $tstamp): string
    {
        try {
            $time1 = new \DateTime('@' . $tstamp);
            $now = new \DateTime();
            $interval = $time1->diff($now, true);

            if ($interval->y) {
                return $interval->y . ' years ago';
            }
            if ($interval->m) {
                return $interval->m . ' months ago';
            }
            if ($interval->d) {
                return $interval->d . ' days ago';
            }
            if ($interval->h) {
                return $interval->h . ' hours ago';
            }
            if ($interval->i) {
                return $interval->i . ' minutes ago';
            }
            return 'less than 1 minute ago';

        } catch (\Exception $e) {
            return $tstamp . '';
        }
    }
}
