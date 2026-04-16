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

/**
 * @deprecated This trait is no longer used. Inject OnlineMediaHelperRegistry directly via constructor injection.
 *             Will be removed in a future version.
 */
trait GetOnlineMediaHelperTrait
{
    protected function getOnlineMediaHelper(
        File $file,
        OnlineMediaHelperRegistry $onlineMediaHelperRegistry,
    ): ?OnlineMediaHelperInterface {
        $onlineMediaHelper = $onlineMediaHelperRegistry->getOnlineMediaHelper($file);

        return $onlineMediaHelper ?: null;
    }
}
