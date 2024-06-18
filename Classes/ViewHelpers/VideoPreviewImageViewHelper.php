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
use JWeiland\VideoShariff\Traits\GetTypoScriptSetupTrait;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
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
    use GetTypoScriptSetupTrait;

    public function initializeArguments(): void
    {
        $this->registerArgument(
            'fileReference',
            'object',
            'FileReference to be used for creating the preview image'
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
        RenderingContextInterface $renderingContext
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
                    // Sometimes OnlineMediaHelperInterface::getPreviewImage() returns a file that does not exist!
                    $publicFile = static::getDefaultThumbnailFile();
                }
            }
        }

        return $publicFile;
    }

    protected static function getDefaultThumbnailFile(): string
    {
        $filename = self::getTypoScriptSetup()['lib.']['video_shariff.']['defaultThumbnail'] ?? '';

        return $filename ? PathUtility::getPublicResourceWebPath($filename) : '';
    }
}
