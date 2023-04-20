<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\ViewHelpers;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class VideoPreviewImageViewHelper
 */
class VideoPreviewImageViewHelper extends AbstractViewHelper
{
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
        $publicDirectory = Environment::getPublicPath() . '/typo3temp/assets/tx_videoshariff/';
        /** @var FileReference|ExtbaseFileReference $fileReference */
        $fileReference = $arguments['fileReference'];

        // get Resource Object (non ExtBase version)
        if (is_callable([$fileReference, 'getOriginalResource'])) {
            // We have a domain model, so we need to fetch the FAL resource object from there
            $fileReference = $fileReference->getOriginalResource();
        }

        if (!($fileReference instanceof FileInterface || $fileReference instanceof AbstractFileFolder)) {
            throw new \UnexpectedValueException(
                'Supplied file object type ' . get_class($fileReference) . ' must be FileInterface or AbstractFileFolder.',
                1454252193
            );
        }

        $file = $fileReference->getOriginalFile();

        $helper = self::getOnlineMediaHelperRegistry()->getOnlineMediaHelper($file);
        if ($helper) {
            $privateFile = $helper->getPreviewImage($file);
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
        } else {
            $publicFile = '';
        }

        return $publicFile;
    }

    protected static function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    protected static function getOnlineMediaHelperRegistry(): OnlineMediaHelperRegistry
    {
        return GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class);
    }

    protected static function getDefaultThumbnailFile(): string
    {
        // ToDo: Change while removing TYPO3 11 compatibility
        $filename = static::getTypoScriptFrontendController()->tmpl->setup['lib.']['video_shariff.']['defaultThumbnail'];
        if (strpos($filename, 'EXT:') === 0) {
            $file = GeneralUtility::getFileAbsFileName($filename);
        } else {
            $file = PathUtility::getAbsoluteWebPath($filename);
        }
        return $file;
    }
}
