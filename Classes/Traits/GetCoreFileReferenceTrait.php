<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\Traits;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;

trait GetCoreFileReferenceTrait
{
    /**
     * @param FileReference|ExtbaseFileReference $fileReference
     */
    protected static function getCoreFileReference($fileReference): FileReference
    {
        if ($fileReference instanceof ExtbaseFileReference) {
            return $fileReference->getOriginalResource();
        }

        return $fileReference;
    }
}
