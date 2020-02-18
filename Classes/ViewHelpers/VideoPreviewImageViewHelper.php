<?php
declare(strict_types=1);
namespace JWeiland\VideoShariff\ViewHelpers;

/*
 * This file is part of the video_shariff project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class VideoPreviewImageViewHelper
 */
class VideoPreviewImageViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument(
            'fileReference',
            FileReference::class,
            'FileReference to be used for creating the preview image'
        );
    }

    /**
     * Returns the absolute web path to the preview image.
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string
    {
        $pathSite = version_compare(TYPO3_version, '9.0.0', '<') ? PATH_site : Environment::getPublicPath();
        $publicDirectory = $pathSite . '/typo3temp/assets/tx_videoshariff/';
        /** @var FileReference $fileReference */
        $fileReference = $arguments['fileReference'];
        $file = $fileReference->getOriginalFile();
        $helper = OnlineMediaHelperRegistry::getInstance()->getOnlineMediaHelper($file);
        if ($helper) {
            $privateFile = $helper->getPreviewImage($file);
            $publicFile = $publicDirectory . substr($privateFile, strrpos($privateFile, '/') + 1);
            // check if file has already been copied
            if (!is_file($publicFile)) {
                // check if public directory exists
                if (!is_dir($publicDirectory)) {
                    GeneralUtility::mkdir_deep($publicDirectory);
                }
                copy($privateFile, $publicFile);
            }
        } else {
            $publicFile = '';
        }
        
        $tempId = StringUtility::getUniqueId();
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addInlineSetting('video_shariff.video', $tempId, $renderChildrenClosure());
        
        return $publicFile;
    }
}
