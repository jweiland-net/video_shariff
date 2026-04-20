<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\Tests\Unit\ViewHelpers;

use JWeiland\VideoShariff\Traits\GetCoreFileReferenceTrait;
use JWeiland\VideoShariff\ViewHelpers\VideoCreationDateViewHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;

#[CoversClass(VideoCreationDateViewHelper::class)]
#[UsesTrait(GetCoreFileReferenceTrait::class)]
final class VideoCreationDateViewHelperTest extends TestCase
{
    /**
     * @return iterable<string, array{array<string, int>, int}>
     */
    public static function datePropertyResolutionProvider(): iterable
    {
        yield 'content_creation_date wins when populated' => [
            ['content_creation_date' => 1683100800, 'creation_date' => 1682928000, 'crdate' => 1683014400],
            1683100800,
        ];

        yield 'falls back to creation_date when content_creation_date is zero' => [
            ['content_creation_date' => 0, 'creation_date' => 1682928000, 'crdate' => 1683014400],
            1682928000,
        ];

        yield 'falls back to crdate when content_creation_date and creation_date are zero' => [
            ['content_creation_date' => 0, 'creation_date' => 0, 'crdate' => 1683014400],
            1683014400,
        ];

        yield 'returns zero when every date property is empty' => [
            ['content_creation_date' => 0, 'creation_date' => 0, 'crdate' => 0],
            0,
        ];
    }

    #[Test]
    #[DataProvider('datePropertyResolutionProvider')]
    public function renderReturnsFirstNonEmptyDatePropertyForCoreFileReference(array $properties, int $expected): void
    {
        $fileReference = $this->buildFileReference($properties);

        $viewHelper = new VideoCreationDateViewHelper();
        $viewHelper->setArguments(['fileReference' => $fileReference]);

        self::assertSame($expected, $viewHelper->render());
    }

    #[Test]
    #[DataProvider('datePropertyResolutionProvider')]
    public function renderReturnsFirstNonEmptyDatePropertyForExtbaseFileReference(array $properties, int $expected): void
    {
        $coreFileReference = $this->buildFileReference($properties);

        $extbaseFileReference = new ExtbaseFileReference();
        $extbaseFileReference->setOriginalResource($coreFileReference);

        $viewHelper = new VideoCreationDateViewHelper();
        $viewHelper->setArguments(['fileReference' => $extbaseFileReference]);

        self::assertSame($expected, $viewHelper->render());
    }

    #[Test]
    public function renderReturnsZeroWhenFileReferenceIsAnArbitraryObject(): void
    {
        $viewHelper = new VideoCreationDateViewHelper();
        $viewHelper->setArguments(['fileReference' => new \stdClass()]);

        self::assertSame(0, $viewHelper->render());
    }

    #[Test]
    public function renderReturnsZeroWhenFileReferenceIsNull(): void
    {
        $viewHelper = new VideoCreationDateViewHelper();
        $viewHelper->setArguments(['fileReference' => null]);

        self::assertSame(0, $viewHelper->render());
    }

    #[Test]
    public function renderReturnsZeroWhenFileReferenceIsAString(): void
    {
        $viewHelper = new VideoCreationDateViewHelper();
        $viewHelper->setArguments(['fileReference' => 'not-an-object']);

        self::assertSame(0, $viewHelper->render());
    }

    /**
     * @param array<string, int> $properties
     */
    private function buildFileReference(array $properties): FileReference
    {
        $propertyMap = [];
        foreach ($properties as $key => $value) {
            $propertyMap[] = [$key, $value];
        }

        $file = $this->createMock(File::class);
        $file->method('getProperty')->willReturnMap($propertyMap);

        $fileReference = $this->createMock(FileReference::class);
        $fileReference->method('getOriginalFile')->willReturn($file);

        return $fileReference;
    }
}
