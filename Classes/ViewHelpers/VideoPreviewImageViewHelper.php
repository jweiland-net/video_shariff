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
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to get preview image for video. If video is unavailable or private
 * return a fallback image configured in lib.video_shariff.defaultThumbnail
 */
class VideoPreviewImageViewHelper extends AbstractViewHelper
{
    use GetCoreFileReferenceTrait;
    use GetOnlineMediaHelperTrait;

    private const FALLBACK_THUMBNAIL_FILE = 'EXT:video_shariff/Resources/Public/Images/DefaultThumbnail.png';

    public function initializeArguments(): void
    {
        $this->registerArgument(
            'fileReference',
            'object',
            'FileReference to be used for creating the preview image',
        );
        $this->registerArgument(
            'fallbackThumbnailFile',
            'string',
            'This file will be used as fallback if video thubnail could not be retrieved because of unavailable or private video',
            false,
            self::FALLBACK_THUMBNAIL_FILE,
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
        $publicFile = '';

        // Early return, if object is not allowed
        if (
            $arguments['fileReference'] instanceof FileReference
            || $arguments['fileReference'] instanceof ExtbaseFileReference
        ) {
            $fileReference = self::getCoreFileReference($arguments['fileReference']);
        } else {
            return $publicFile;
        }

        $file = $fileReference->getOriginalFile();

        $helper = self::getOnlineMediaHelper($file);
        if ($helper instanceof OnlineMediaHelperInterface) {
            $privateFile = $helper->getPreviewImage($file);
            $publicDirectory = Environment::getPublicPath() . '/typo3temp/assets/tx_videoshariff/';
            $publicFile = $publicDirectory . substr($privateFile, strrpos($privateFile, '/') + 1);

            // check if file has already been copied
            if (!is_file($publicFile)) {
                // check if public directory exists
                if (!is_dir($publicDirectory)) {
                    GeneralUtility::mkdir_deep($publicDirectory);
                }

                if (is_file($privateFile)) {
                    copy($privateFile, $publicFile);
                } else {
                    $publicFile = $arguments['fallbackThumbnailFile'] ?? self::FALLBACK_THUMBNAIL_FILE;
                }
            }
        }

        return $publicFile;
    }
}
