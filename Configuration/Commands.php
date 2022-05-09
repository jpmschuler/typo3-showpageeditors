<?php

use JPMSchuler\ShowPageEditors\Command\ShowBackendVisibilityOfPage;

return [
    'page:showVisibilityFor' => [
        'class' => ShowBackendVisibilityOfPage::class,
        'schedulable' => false,
    ],
];
