<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\Tests\Unit\ViewHelpers\Format;

use JWeiland\VideoShariff\ViewHelpers\Format\SecondsToISO8601ViewHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SecondsToISO8601ViewHelper::class)]
final class SecondsToISO8601ViewHelperTest extends TestCase
{
    /**
     * @return iterable<string, array{int, string}>
     */
    public static function secondsDataProvider(): iterable
    {
        // Zero short-circuits to the ISO 8601 "zero duration" notation.
        yield 'zero seconds returns P0S' => [0, 'P0S'];

        // Sub-minute.
        yield '14 seconds returns PT14S' => [14, 'PT14S'];
        yield '59 seconds returns PT59S' => [59, 'PT59S'];

        // Minute boundary.
        yield '60 seconds returns PT1M' => [60, 'PT1M'];
        yield '61 seconds returns PT1M1S' => [61, 'PT1M1S'];
        yield '250 seconds returns PT4M10S' => [250, 'PT4M10S'];

        // Hour boundary.
        yield '3600 seconds returns PT1H' => [3600, 'PT1H'];
        yield '3700 seconds returns PT1H1M40S' => [3700, 'PT1H1M40S'];
        yield '7200 seconds returns PT2H' => [7200, 'PT2H'];
        yield '43200 seconds returns PT12H' => [43200, 'PT12H'];

        // Day boundary and combinations.
        yield '86400 seconds returns P1D' => [86400, 'P1D'];
        yield '86600 seconds returns P1DT3M20S' => [86600, 'P1DT3M20S'];
        yield '90060 seconds returns P1DT1H1M' => [90060, 'P1DT1H1M'];
        yield '172800 seconds returns P2D' => [172800, 'P2D'];

        // A real-world video duration: 1h 23m 45s.
        yield '5025 seconds returns PT1H23M45S' => [5025, 'PT1H23M45S'];
    }

    #[Test]
    #[DataProvider('secondsDataProvider')]
    public function renderConvertsChildrenIntoIso8601Duration(int $seconds, string $expected): void
    {
        $viewHelper = new SecondsToISO8601ViewHelper();
        $viewHelper->setRenderChildrenClosure(static fn(): int => $seconds);

        self::assertSame($expected, $viewHelper->render());
    }

    #[Test]
    public function renderCoercesStringChildrenToIntegerBeforeFormatting(): void
    {
        $viewHelper = new SecondsToISO8601ViewHelper();
        $viewHelper->setRenderChildrenClosure(static fn(): string => '3600');

        self::assertSame('PT1H', $viewHelper->render());
    }

    #[Test]
    public function renderTreatsNonNumericChildrenAsZeroDuration(): void
    {
        $viewHelper = new SecondsToISO8601ViewHelper();
        $viewHelper->setRenderChildrenClosure(static fn(): string => 'not-a-number');

        self::assertSame('P0S', $viewHelper->render());
    }
}
