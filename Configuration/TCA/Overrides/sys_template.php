<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'video_shariff',
    'Configuration/TypoScript/',
    'Video shariff'
);
