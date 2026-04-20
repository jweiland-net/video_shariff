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
use JWeiland\VideoShariff\ViewHelpers\VideoPreviewImageViewHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;

#[CoversClass(VideoPreviewImageViewHelper::class)]
#[UsesTrait(GetCoreFileReferenceTrait::class)]
final class VideoPreviewImageViewHelperTest extends TestCase
{
    private string $tempPublicPath = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->tempPublicPath = sys_get_temp_dir() . '/video_shariff_test_' . uniqid('', true);
        mkdir($this->tempPublicPath, 0o777, true);

        // Environment::initialize() only has public-path effects relevant to this ViewHelper,
        // so we can safely fake the values we do not care about.
        Environment::initialize(
            Environment::getContext(),
            true,
            false,
            $this->tempPublicPath,
            $this->tempPublicPath,
            $this->tempPublicPath . '/var',
            $this->tempPublicPath . '/config',
            $this->tempPublicPath . '/index.php',
            PHP_OS_FAMILY === 'Windows' ? 'WINDOWS' : 'UNIX',
        );
    }

    protected function tearDown(): void
    {
        if ($this->tempPublicPath !== '' && is_dir($this->tempPublicPath)) {
            $this->recursivelyRemoveDirectory($this->tempPublicPath);
        }

        parent::tearDown();
    }

    #[Test]
    public function renderReturnsEmptyStringWhenFileReferenceIsNotAFileReferenceObject(): void
    {
        $registry = $this->createMock(OnlineMediaHelperRegistry::class);
        $registry->expects(self::never())->method('getOnlineMediaHelper');

        $viewHelper = new VideoPreviewImageViewHelper($registry);
        $viewHelper->setArguments([
            'fileReference' => new \stdClass(),
            'fallbackThumbnailFile' => 'EXT:video_shariff/Resources/Public/Images/DefaultThumbnail.png',
        ]);

        self::assertSame('', $viewHelper->render());
    }

    #[Test]
    public function renderReturnsEmptyStringWhenNoOnlineMediaHelperMatchesTheFile(): void
    {
        $file = $this->createMock(File::class);
        $fileReference = $this->createMock(FileReference::class);
        $fileReference->method('getOriginalFile')->willReturn($file);

        $registry = $this->createMock(OnlineMediaHelperRegistry::class);
        $registry->method('getOnlineMediaHelper')->with($file)->willReturn(false);

        $viewHelper = new VideoPreviewImageViewHelper($registry);
        $viewHelper->setArguments([
            'fileReference' => $fileReference,
            'fallbackThumbnailFile' => 'EXT:video_shariff/Resources/Public/Images/DefaultThumbnail.png',
        ]);

        self::assertSame('', $viewHelper->render());
    }

    #[Test]
    public function renderCopiesPreviewImageIntoPublicTypo3TempAndReturnsPublicPath(): void
    {
        // Seed a "private" preview image we expect the helper to return.
        $privateDir = $this->tempPublicPath . '/var/private-preview';
        mkdir($privateDir, 0o777, true);
        $privateFile = $privateDir . '/youtube_abcdef.jpg';
        file_put_contents($privateFile, 'PREVIEW-BYTES');

        $file = $this->createMock(File::class);
        $fileReference = $this->createMock(FileReference::class);
        $fileReference->method('getOriginalFile')->willReturn($file);

        $helper = $this->createMock(OnlineMediaHelperInterface::class);
        $helper->method('getPreviewImage')->with($file)->willReturn($privateFile);

        $registry = $this->createMock(OnlineMediaHelperRegistry::class);
        $registry->method('getOnlineMediaHelper')->with($file)->willReturn($helper);

        $viewHelper = new VideoPreviewImageViewHelper($registry);
        $viewHelper->setArguments([
            'fileReference' => $fileReference,
            'fallbackThumbnailFile' => 'EXT:video_shariff/Resources/Public/Images/DefaultThumbnail.png',
        ]);

        $expectedPublicFile = $this->tempPublicPath . '/typo3temp/assets/tx_videoshariff/youtube_abcdef.jpg';
        self::assertSame($expectedPublicFile, $viewHelper->render());
        self::assertFileExists($expectedPublicFile);
        self::assertSame('PREVIEW-BYTES', file_get_contents($expectedPublicFile));
    }

    #[Test]
    public function renderReusesAlreadyCopiedPreviewImageWithoutCopyingAgain(): void
    {
        $privateFile = $this->tempPublicPath . '/var/private-preview/vimeo_xyz.jpg';
        // Pre-populate the public destination only, so the "already copied" branch is exercised.
        $publicDirectory = $this->tempPublicPath . '/typo3temp/assets/tx_videoshariff/';
        mkdir($publicDirectory, 0o777, true);
        $publicFile = $publicDirectory . 'vimeo_xyz.jpg';
        file_put_contents($publicFile, 'ALREADY-COPIED');

        $file = $this->createMock(File::class);
        $fileReference = $this->createMock(FileReference::class);
        $fileReference->method('getOriginalFile')->willReturn($file);

        $helper = $this->createMock(OnlineMediaHelperInterface::class);
        $helper->method('getPreviewImage')->with($file)->willReturn($privateFile);

        $registry = $this->createMock(OnlineMediaHelperRegistry::class);
        $registry->method('getOnlineMediaHelper')->with($file)->willReturn($helper);

        $viewHelper = new VideoPreviewImageViewHelper($registry);
        $viewHelper->setArguments([
            'fileReference' => $fileReference,
            'fallbackThumbnailFile' => 'EXT:video_shariff/Resources/Public/Images/DefaultThumbnail.png',
        ]);

        self::assertSame($publicFile, $viewHelper->render());
        self::assertSame('ALREADY-COPIED', file_get_contents($publicFile));
    }

    #[Test]
    public function renderReturnsFallbackThumbnailWhenPrivatePreviewImageIsMissing(): void
    {
        $file = $this->createMock(File::class);
        $fileReference = $this->createMock(FileReference::class);
        $fileReference->method('getOriginalFile')->willReturn($file);

        $helper = $this->createMock(OnlineMediaHelperInterface::class);
        // Nonexistent on-disk file - exercises the "file is_file() is false" branch.
        $helper->method('getPreviewImage')
            ->with($file)
            ->willReturn($this->tempPublicPath . '/var/private-preview/does-not-exist.jpg');

        $registry = $this->createMock(OnlineMediaHelperRegistry::class);
        $registry->method('getOnlineMediaHelper')->with($file)->willReturn($helper);

        $fallback = 'EXT:site_package/Resources/Public/Images/CustomFallback.png';
        $viewHelper = new VideoPreviewImageViewHelper($registry);
        $viewHelper->setArguments([
            'fileReference' => $fileReference,
            'fallbackThumbnailFile' => $fallback,
        ]);

        self::assertSame($fallback, $viewHelper->render());
    }

    #[Test]
    public function renderUnwrapsExtbaseFileReferenceBeforeLookingUpTheHelper(): void
    {
        $privateDir = $this->tempPublicPath . '/var/private-preview';
        mkdir($privateDir, 0o777, true);
        $privateFile = $privateDir . '/extbase_wrapped.jpg';
        file_put_contents($privateFile, 'EXTBASE-BYTES');

        $file = $this->createMock(File::class);
        $coreFileReference = $this->createMock(FileReference::class);
        $coreFileReference->method('getOriginalFile')->willReturn($file);

        $extbaseFileReference = new ExtbaseFileReference();
        $extbaseFileReference->setOriginalResource($coreFileReference);

        $helper = $this->createMock(OnlineMediaHelperInterface::class);
        $helper->method('getPreviewImage')->with($file)->willReturn($privateFile);

        $registry = $this->createMock(OnlineMediaHelperRegistry::class);
        $registry->method('getOnlineMediaHelper')->with($file)->willReturn($helper);

        $viewHelper = new VideoPreviewImageViewHelper($registry);
        $viewHelper->setArguments([
            'fileReference' => $extbaseFileReference,
            'fallbackThumbnailFile' => 'EXT:video_shariff/Resources/Public/Images/DefaultThumbnail.png',
        ]);

        $expectedPublicFile = $this->tempPublicPath . '/typo3temp/assets/tx_videoshariff/extbase_wrapped.jpg';
        self::assertSame($expectedPublicFile, $viewHelper->render());
    }

    private function recursivelyRemoveDirectory(string $directory): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );
        foreach ($iterator as $file) {
            /** @var \SplFileInfo $file */
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
        rmdir($directory);
    }
}
