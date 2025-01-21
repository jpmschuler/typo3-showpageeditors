<?php
$EM_CONF['showpageeditors'] = [
    'title' => 'ShowPageEditors',
    'description' => 'CLI command to retrieve every editor who can see a given PID in the backend',
    'version' => '2.0.0',
    'category' => 'misc',
    'author' => 'J. Peter M. Schuler',
    'author_email' => 'j.peter.m.schuler@uni-due.de',
    'state' => 'stable',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.25-12.4.99',
        ],
    ]
];
