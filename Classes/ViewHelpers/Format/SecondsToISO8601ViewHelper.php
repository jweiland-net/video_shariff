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
 * ViewHelper to convert seconds into ISO 8601 date
 */
class SecondsToISO8601ViewHelper extends AbstractViewHelper
{
    /**
     * Convert seconds into ISO 8601 date
     *
     * @throws \UnexpectedValueException
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext,
    ): string {
        $seconds = (int)$renderChildrenClosure();
        if ($seconds === 0) {
            return 'P0S';
        }

        return self::formatSecondsIntoISO8601($seconds);
    }

    protected static function formatSecondsIntoISO8601(int $seconds): string
    {
        $days = floor($seconds / (3600 * 24));
        $hours = floor(($seconds % (3600 * 24)) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf(
            'P%s%s%s%s%s',
            $days ? $days . 'D' : '',
            $hours || $minutes || $seconds ? 'T' : '',
            $hours ? $hours . 'H' : '',
            $minutes ? $minutes . 'M' : '',
            $seconds ? $seconds . 'S' : '',
        );
    }
}
