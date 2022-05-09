<?php
$EM_CONF['showpageeditors'] = [
    'title' => 'ShowPageEditors',
    'description' => 'Retrieve every editor who can see a given PID in the backend',
    'version' => '1.0.0',
    'category' => 'misc',
    'author' => 'J. Peter M. Schuler',
    'author_email' => 'j.peter.m.schuler@uni-due.de',
    'state' => 'excludeFromUpdates',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'constraints' => [
        'depends' => [
            'typo3' => '*',
        ],
    ]
];
