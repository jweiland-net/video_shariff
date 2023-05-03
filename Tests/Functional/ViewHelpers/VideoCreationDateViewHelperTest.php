<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\Tests\Functional\ViewHelpers;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class VideoCreationDateViewHelperTest extends FunctionalTestCase
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

    /**
     * @test
     */
    public function videoCreateDateReturnContentCreationDate(): void
    {
        $file = $this->createMock(File::class);
        $file
            ->expects(self::atLeastOnce())
            ->method('getUid')
            ->willReturn(1);
        $file
            ->expects(self::atLeastOnce())
            ->method('getProperty')
            ->willReturnMap([
                ['content_creation_date', 1683100800],
                ['creation_date', 0],
                ['crdate', 0],
            ]);

        $coreFileReference = $this->createMock(FileReference::class);
        $coreFileReference
            ->expects(self::atLeastOnce())
            ->method('getOriginalFile')
            ->willReturn($file);

        $extbaseFileReference = new ExtbaseFileReference();
        $extbaseFileReference->setOriginalResource($coreFileReference);

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplateSource(implode(' ', [
            '<html lang="en" xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"',
            'xmlns:jw="http://typo3.org/ns/JWeiland/VideoShariff/ViewHelpers"',
            'data-namespace-typo3-fluid="true">',
            '{jw:videoCreationDate(fileReference: \'{file}\')}',
            '</html>',
        ]));

        $view->assign('file', $coreFileReference);
        self::assertSame(
            '1683100800',
            trim($view->render())
        );

        $view->assign('file', $extbaseFileReference);
        self::assertSame(
            '1683100800',
            trim($view->render())
        );
    }

    /**
     * @test
     */
    public function videoCreateDateReturnCreationDate(): void
    {
        $file = $this->createMock(File::class);
        $file
            ->expects(self::atLeastOnce())
            ->method('getUid')
            ->willReturn(1);
        $file
            ->expects(self::atLeastOnce())
            ->method('getProperty')
            ->willReturnMap([
                ['content_creation_date', 0],
                ['creation_date', 1682928000],
                ['crdate', 0],
            ]);

        $coreFileReference = $this->createMock(FileReference::class);
        $coreFileReference
            ->expects(self::atLeastOnce())
            ->method('getOriginalFile')
            ->willReturn($file);

        $extbaseFileReference = new ExtbaseFileReference();
        $extbaseFileReference->setOriginalResource($coreFileReference);

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplateSource(implode(' ', [
            '<html lang="en" xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"',
            'xmlns:jw="http://typo3.org/ns/JWeiland/VideoShariff/ViewHelpers"',
            'data-namespace-typo3-fluid="true">',
            '{jw:videoCreationDate(fileReference: \'{file}\')}',
            '</html>',
        ]));

        $view->assign('file', $coreFileReference);
        self::assertSame(
            '1682928000',
            trim($view->render())
        );

        $view->assign('file', $extbaseFileReference);
        self::assertSame(
            '1682928000',
            trim($view->render())
        );
    }

    /**
     * @test
     */
    public function videoCreateDateReturnCrdate(): void
    {
        $file = $this->createMock(File::class);
        $file
            ->expects(self::atLeastOnce())
            ->method('getUid')
            ->willReturn(1);
        $file
            ->expects(self::atLeastOnce())
            ->method('getProperty')
            ->willReturnMap([
                ['content_creation_date', 0],
                ['creation_date', 0],
                ['crdate', 1683014400],
            ]);

        $coreFileReference = $this->createMock(FileReference::class);
        $coreFileReference
            ->expects(self::atLeastOnce())
            ->method('getOriginalFile')
            ->willReturn($file);

        $extbaseFileReference = new ExtbaseFileReference();
        $extbaseFileReference->setOriginalResource($coreFileReference);

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplateSource(implode(' ', [
            '<html lang="en" xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"',
            'xmlns:jw="http://typo3.org/ns/JWeiland/VideoShariff/ViewHelpers"',
            'data-namespace-typo3-fluid="true">',
            '{jw:videoCreationDate(fileReference: \'{file}\')}',
            '</html>',
        ]));

        $view->assign('file', $coreFileReference);
        self::assertSame(
            '1683014400',
            trim($view->render())
        );

        $view->assign('file', $extbaseFileReference);
        self::assertSame(
            '1683014400',
            trim($view->render())
        );
    }
}
