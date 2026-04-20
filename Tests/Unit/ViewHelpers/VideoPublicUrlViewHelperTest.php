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
use JWeiland\VideoShariff\ViewHelpers\VideoPublicUrlViewHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;

#[CoversClass(VideoPublicUrlViewHelper::class)]
#[UsesTrait(GetCoreFileReferenceTrait::class)]
final class VideoPublicUrlViewHelperTest extends TestCase
{
    #[Test]
    public function renderReturnsEmptyStringWhenFileReferenceIsNotAFileReferenceObject(): void
    {
        $registry = $this->createMock(OnlineMediaHelperRegistry::class);
        $registry->expects(self::never())->method('getOnlineMediaHelper');

        $viewHelper = new VideoPublicUrlViewHelper($registry);
        $viewHelper->setArguments(['fileReference' => new \stdClass()]);

        self::assertSame('', $viewHelper->render());
    }

    #[Test]
    public function renderReturnsEmptyStringWhenFileReferenceIsNull(): void
    {
        $registry = $this->createMock(OnlineMediaHelperRegistry::class);
        $registry->expects(self::never())->method('getOnlineMediaHelper');

        $viewHelper = new VideoPublicUrlViewHelper($registry);
        $viewHelper->setArguments(['fileReference' => null]);

        self::assertSame('', $viewHelper->render());
    }

    #[Test]
    public function renderReturnsEmptyStringWhenNoOnlineMediaHelperMatchesTheFile(): void
    {
        $file = $this->createMock(File::class);
        $fileReference = $this->createMock(FileReference::class);
        $fileReference->method('getOriginalFile')->willReturn($file);

        $registry = $this->createMock(OnlineMediaHelperRegistry::class);
        // OnlineMediaHelperRegistry::getOnlineMediaHelper() returns `false` when no helper is found.
        $registry->method('getOnlineMediaHelper')->with($file)->willReturn(false);

        $viewHelper = new VideoPublicUrlViewHelper($registry);
        $viewHelper->setArguments(['fileReference' => $fileReference]);

        self::assertSame('', $viewHelper->render());
    }

    #[Test]
    public function renderReturnsEmptyStringWhenHelperReturnsNullPublicUrl(): void
    {
        $file = $this->createMock(File::class);
        $fileReference = $this->createMock(FileReference::class);
        $fileReference->method('getOriginalFile')->willReturn($file);

        $helper = $this->createMock(OnlineMediaHelperInterface::class);
        $helper->method('getPublicUrl')->with($file)->willReturn(null);

        $registry = $this->createMock(OnlineMediaHelperRegistry::class);
        $registry->method('getOnlineMediaHelper')->with($file)->willReturn($helper);

        $viewHelper = new VideoPublicUrlViewHelper($registry);
        $viewHelper->setArguments(['fileReference' => $fileReference]);

        self::assertSame('', $viewHelper->render());
    }

    #[Test]
    public function renderReturnsHelperProvidedPublicUrlForCoreFileReference(): void
    {
        $url = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
        $file = $this->createMock(File::class);
        $fileReference = $this->createMock(FileReference::class);
        $fileReference->method('getOriginalFile')->willReturn($file);

        $registry = $this->registryReturningHelperWithPublicUrl($file, $url);

        $viewHelper = new VideoPublicUrlViewHelper($registry);
        $viewHelper->setArguments(['fileReference' => $fileReference]);

        self::assertSame($url, $viewHelper->render());
    }

    #[Test]
    public function renderUnwrapsExtbaseFileReferenceBeforeCallingTheHelper(): void
    {
        $url = 'https://vimeo.com/76979871';
        $file = $this->createMock(File::class);
        $coreFileReference = $this->createMock(FileReference::class);
        $coreFileReference->method('getOriginalFile')->willReturn($file);

        $extbaseFileReference = new ExtbaseFileReference();
        $extbaseFileReference->setOriginalResource($coreFileReference);

        $registry = $this->registryReturningHelperWithPublicUrl($file, $url);

        $viewHelper = new VideoPublicUrlViewHelper($registry);
        $viewHelper->setArguments(['fileReference' => $extbaseFileReference]);

        self::assertSame($url, $viewHelper->render());
    }

    private function registryReturningHelperWithPublicUrl(File $file, string $url): OnlineMediaHelperRegistry
    {
        $helper = $this->createMock(OnlineMediaHelperInterface::class);
        $helper->method('getPublicUrl')->with($file)->willReturn($url);

        $registry = $this->createMock(OnlineMediaHelperRegistry::class);
        $registry->method('getOnlineMediaHelper')->with($file)->willReturn($helper);

        return $registry;
    }
}
