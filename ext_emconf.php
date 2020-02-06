<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Video shariff',
    'description' => 'This extension provides more privacy when embedding videos in frontend.',
    'category' => 'plugin',
    'state' => 'stable',
    'author' => 'Pascal Rinker',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'version' => '1.3.2',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-10.1.99',
            'fluid_styled_content' => '8.7.0-0.0.0'
        ],
        'conflicts' => [],
        'suggests' => [],
    ]
];
