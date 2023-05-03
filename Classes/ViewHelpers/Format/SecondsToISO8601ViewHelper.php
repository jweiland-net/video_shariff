<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class SecondsToISO8601ViewHelper
 */
class SecondsToISO8601ViewHelper extends AbstractViewHelper
{
    /**
     * Returns the absolute web path to the preview image.
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     * @throws \UnexpectedValueException
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $seconds = (int)$renderChildrenClosure();
        $intervals = [
            'D' => 60 * 60 * 24,
            'H' => 60 * 60,
            'M' => 60,
            'S' => 1,
        ];

        $pt = 'P';
        $result = '';
        foreach ($intervals as $tag => $divisor) {
            $qty = floor($seconds / $divisor);
            if (!$qty && $result === '') {
                $pt = 'T';
                continue;
            }
            $seconds -= $qty * $divisor;
            $result .= $qty . $tag;
        }

        if ($result === '') {
            $result = '0S';
        }

        return $pt . $result;
    }
}
