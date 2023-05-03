<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\ViewHelpers;

use JWeiland\VideoShariff\Traits\GetCoreFileReferenceTrait;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to extract date values from given FileReference
 */
class VideoCreationDateViewHelper extends AbstractViewHelper
{
    use GetCoreFileReferenceTrait;

    public function initializeArguments(): void
    {
        $this->registerArgument(
            'fileReference',
            'object',
            'FileReference to be used for creating the preview image'
        );
    }

    /**
     * Returns date of given FileReference
     *
     * @throws \UnexpectedValueException
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): int {
        // Early return, if object is not allowed
        if (
            $arguments['fileReference'] instanceof FileReference
            || $arguments['fileReference'] instanceof ExtbaseFileReference
        ) {
            $fileReference = self::getCoreFileReference($arguments['fileReference']);
        } else {
            return 0;
        }

        $file = $fileReference->getOriginalFile();
        if ($file->getProperty('content_creation_date')) {
            return $file->getProperty('content_creation_date');
        }

        if ($file->getProperty('creation_date')) {
            return $file->getProperty('creation_date');
        }

        if ($file->getProperty('crdate')) {
            return $file->getProperty('crdate');
        }

        return 0;
    }
}
