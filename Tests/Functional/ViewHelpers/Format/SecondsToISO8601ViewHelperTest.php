<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\Tests\Functional\ViewHelpers\Format;

use JWeiland\VideoShariff\ViewHelpers\Format\SecondsToISO8601ViewHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

#[CoversClass(SecondsToISO8601ViewHelper::class)]
final class SecondsToISO8601ViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/video_shariff',
    ];

    /**
     * @return array<string, array{int, string}>
     */
    public static function secondsDataProvider(): array
    {
        return [
            // Zero short-circuits to the ISO 8601 "zero duration" notation.
            '0 seconds will return P0S' => [0, 'P0S'],

            // Sub-minute durations.
            '14 seconds will return PT14S' => [14, 'PT14S'],
            '59 seconds will return PT59S' => [59, 'PT59S'],

            // Minute boundary and combinations.
            '60 seconds will return PT1M' => [60, 'PT1M'],
            '61 seconds will return PT1M1S' => [61, 'PT1M1S'],
            '250 seconds will return PT4M10S' => [250, 'PT4M10S'],

            // Hour boundary.
            '3600 seconds will return PT1H' => [3600, 'PT1H'],
            '3700 seconds will return PT1H1M40S' => [3700, 'PT1H1M40S'],
            '7200 seconds will return PT2H' => [7200, 'PT2H'],
            '43200 seconds will return PT12H' => [43200, 'PT12H'],

            // Day boundary and multi-unit combinations.
            '86400 seconds will return P1D' => [86400, 'P1D'],
            '86600 seconds will return P1DT3M20S' => [86600, 'P1DT3M20S'],
            '90060 seconds will return P1DT1H1M' => [90060, 'P1DT1H1M'],

            // Multi-day duration.
            '172800 seconds will return P2D' => [172800, 'P2D'],
        ];
    }

    #[Test]
    #[DataProvider('secondsDataProvider')]
    public function formatSecondsToISO8601(int $seconds, string $expected): void
    {
        $view = $this->buildTemplateView();
        $view->assign('seconds', $seconds);

        self::assertSame($expected, trim($view->render()));
    }

    private function buildTemplateView(): TemplateView
    {
        $templateSource = implode(' ', [
            '<html lang="en" xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"',
            'xmlns:jw="http://typo3.org/ns/JWeiland/VideoShariff/ViewHelpers"',
            'data-namespace-typo3-fluid="true">',
            '{seconds -> jw:format.secondsToISO8601()}',
            '</html>',
        ]);

        $request = new ServerRequest('http://localhost/', 'GET');
        $context = $this->get(RenderingContextFactory::class)->create([], $request);
        $context->getTemplatePaths()->setTemplateSource($templateSource);

        return new TemplateView($context);
    }
}
