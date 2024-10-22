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
use JWeiland\VideoShariff\Traits\GetOnlineMediaHelperTrait;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class VideoPublicUrlViewHelper
 */
class VideoPublicUrlViewHelper extends AbstractViewHelper
{
    use GetCoreFileReferenceTrait;
    use GetOnlineMediaHelperTrait;

    public function initializeArguments(): void
    {
        $this->registerArgument(
            'fileReference',
            'object',
            'FileReference to be used for creating the preview image',
        );
    }

    /**
     * Returns the absolute web path to the preview image.
     *
     * @throws \UnexpectedValueException
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext,
    ): string {
        $publicUrl = '';

        // Early return, if object is not allowed
        if (
            $arguments['fileReference'] instanceof FileReference
            || $arguments['fileReference'] instanceof ExtbaseFileReference
        ) {
            $fileReference = self::getCoreFileReference($arguments['fileReference']);
        } else {
            return $publicUrl;
        }

        $file = $fileReference->getOriginalFile();
        $helper = self::getOnlineMediaHelper($file);
        if ($helper instanceof OnlineMediaHelperInterface) {
            $publicUrl = $helper->getPublicUrl($file) ?? '';
        }

        return $publicUrl;
    }
}
