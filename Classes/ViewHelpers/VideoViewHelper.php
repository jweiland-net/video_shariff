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

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class VideoViewHelper
 * @deprecated will be removed with video_shariff 2.0.0. Please update your custom templates and embed the video directly using f:format.json
 */
class VideoViewHelper extends AbstractViewHelper
{
    /**
     * Adds the current video HTML code to as a setting to the page renderer.
     * This makes it possible to render the video after confirmation dialog
     * without using AJAX.
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string the id of the JavaScript setting
     * @deprecated will be removed with video_shariff 2.0.0. Please update your custom templates and embed the video directly using f:format.json
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        trigger_error(
            'VideoViewHelper::renderStatic() will be removed with video_shariff 2.0.0. '
            . 'Please update your custom templates and embed the video directly using f:format.json.',
            E_USER_DEPRECATED
        );
        $tempId = StringUtility::getUniqueId();
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addInlineSetting('video_shariff.video', $tempId, $renderChildrenClosure());
        return $tempId;
    }
}
