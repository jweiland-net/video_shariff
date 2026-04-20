<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\Tests\Functional\ViewHelpers\Format;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class SecondsToISO8601ViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/video_shariff',
    ];

    protected function setUp(): void
    {
        if (!defined('ORIGINAL_ROOT')) {
            define('ORIGINAL_ROOT', $_ENV['TYPO3_PATH_ROOT']);
        }

        parent::setUp();
    }

    public static function secondsDataProvider(): array
    {
        return [
            '0 seconds will return P0S' => [0, 'P0S'],
            '14 seconds will return PT14S' => [14, 'PT14S'],
            '59 seconds will return PT59S' => [59, 'PT59S'],
            '60 seconds will return PT1M' => [60, 'PT1M'],
            '61 seconds will return PT1M1S' => [61, 'PT1M1S'],
            '250 seconds will return PT4M10S' => [250, 'PT4M10S'],
            '3600 seconds will return PT1H' => [3600, 'PT1H'],
            '3700 seconds will return PT1H1M40S' => [3700, 'PT1H1M40S'],
            '43200 seconds will return PT12H' => [43200, 'PT12H'],
            '86400 seconds will return P1D' => [86400, 'P1D'],
            '86600 seconds will return P1DT3M20S' => [86600, 'P1DT3M20S'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider secondsDataProvider
     */
    public function formatSecondsToISO8601(int $seconds, string $expected)
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplateSource(implode(' ', [
            '<html lang="en" xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"',
            'xmlns:jw="http://typo3.org/ns/JWeiland/VideoShariff/ViewHelpers"',
            'data-namespace-typo3-fluid="true">',
            '{seconds -> jw:format.secondsToISO8601()}',
            '</html>',
        ]));

        $view->assign('seconds', $seconds);

        self::assertSame(
            $expected,
            trim($view->render()),
        );
    }
}
