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

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class VideoPreviewImageViewHelper
 */
class VideoPreviewImageViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument(
            'fileReference',
            FileReference::class,
            'FileReference to be used for creating the preview image'
        );
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        /** @var FileReference $fileReference */
        $fileReference = $arguments['fileReference'];
        $file = $fileReference->getOriginalFile();
        $helper = OnlineMediaHelperRegistry::getInstance()->getOnlineMediaHelper($file);
        $privateFile = $helper->getPreviewImage($file);
        $publicFile = '/typo3temp/assets/images/' . substr($privateFile, strrpos($privateFile, '/') + 1);
        if (!is_file($publicFile)) {
            copy($privateFile, PATH_site . $publicFile);
        }
        return $publicFile;
    }

}
