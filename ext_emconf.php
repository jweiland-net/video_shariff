<?php

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'Video Shariff',
    'description' => 'This extension provides more privacy when embedding videos in frontend.',
    'category' => 'plugin',
    'state' => 'stable',
    'author' => 'Stefan Froemken',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'version' => '3.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.37-13.4.99',
            'fluid_styled_content' => '11.5.37-0.0.0',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
