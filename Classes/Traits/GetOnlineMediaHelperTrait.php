<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\Traits;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

trait GetOnlineMediaHelperTrait
{
    protected static function getOnlineMediaHelper(File $file): ?OnlineMediaHelperInterface
    {
        $onlineMediaHelper = self::getOnlineMediaHelperRegistry()->getOnlineMediaHelper($file);

        return $onlineMediaHelper ?: null;
    }

    protected static function getOnlineMediaHelperRegistry(): OnlineMediaHelperRegistry
    {
        return GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class);
    }
}
