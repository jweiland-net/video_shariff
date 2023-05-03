<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\ViewHelpers;

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
    protected VideoCreationDateViewHelper $subject;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new VideoCreationDateViewHelper();
    }

    public function fileDataProvider(): array
    {
        $file1 = $this->createMock(File::class);
        $file1
            ->expects(self::atLeastOnce())
            ->method('getUid')
            ->willReturn(1);
        $file1
            ->expects(self::atLeastOnce())
            ->method('getProperty')
            ->with(self::identicalTo('content_creation_date'))
            ->willReturn(1683100800);
        $file1
            ->expects(self::never())
            ->method('getProperty')
            ->with(self::identicalTo('creation_date'));
        $file1
            ->expects(self::never())
            ->method('getProperty')
            ->with(self::identicalTo('crdate'));

        $coreFileReference1 = $this->createMock(FileReference::class);
        $coreFileReference1
            ->expects(self::atLeastOnce())
            ->method('getOriginalFile')
            ->willReturn($file1);

        $extbaseFileReference1 = new ExtbaseFileReference();
        $extbaseFileReference1->setOriginalResource($coreFileReference1);

        $file2 = $this->createMock(File::class);
        $file2
            ->expects(self::atLeastOnce())
            ->method('getUid')
            ->willReturn(1);
        $file2
            ->expects(self::atLeastOnce())
            ->method('getProperty')
            ->with(self::identicalTo('content_creation_date'))
            ->willReturn(0);
        $file2
            ->expects(self::atLeastOnce())
            ->method('getProperty')
            ->with(self::identicalTo('creation_date'))
            ->willReturn(1682928000);
        $file2
            ->expects(self::never())
            ->method('getProperty')
            ->with(self::identicalTo('crdate'));

        $coreFileReference2 = $this->createMock(FileReference::class);
        $coreFileReference2
            ->expects(self::atLeastOnce())
            ->method('getOriginalFile')
            ->willReturn($file2);

        $extbaseFileReference2 = new ExtbaseFileReference();
        $extbaseFileReference2->setOriginalResource($coreFileReference2);

        $file3 = $this->createMock(File::class);
        $file3
            ->expects(self::atLeastOnce())
            ->method('getUid')
            ->willReturn(1);
        $file3
            ->expects(self::atLeastOnce())
            ->method('getProperty')
            ->with(self::identicalTo('content_creation_date'))
            ->willReturn(0);
        $file3
            ->expects(self::atLeastOnce())
            ->method('getProperty')
            ->with(self::identicalTo('creation_date'))
            ->willReturn(0);
        $file3
            ->expects(self::atLeastOnce())
            ->method('getProperty')
            ->with(self::identicalTo('crdate'))
            ->willReturn(1683014400);

        $coreFileReference3 = $this->createMock(FileReference::class);
        $coreFileReference3
            ->expects(self::atLeastOnce())
            ->method('getOriginalFile')
            ->willReturn($file3);

        $extbaseFileReference3 = new ExtbaseFileReference();
        $extbaseFileReference3->setOriginalResource($coreFileReference3);

        return [
            'Extbase FileReference with content_creation_date returns 1683100800' => [$file1, 1683100800],
            'Core FileReference with content_creation_date returns 1683100800' => [$file1, 1683100800],
            'Extbase FileReference with content_creation_date returns 1682928000' => [$file2, 1682928000],
            'Core FileReference with content_creation_date returns 1682928000' => [$file2, 1682928000],
            'Extbase FileReference with content_creation_date returns 1683014400' => [$file3, 1683014400],
            'Core FileReference with content_creation_date returns 1683014400' => [$file3, 1683014400],
        ];
    }

    /**
     * @test
     *
     * @dataProvider fileDataProvider
     */
    public function videoCreateDateReturnContentCreationDate($file, int $expects): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->assign('file', $file);
        $view->setTemplateSource('
            <html lang="en"
                  xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
                  xmlns:jw="http://typo3.org/ns/JWeiland/VideoShariff/ViewHelpers"
                  data-namespace-typo3-fluid="true">
                  {jw:videoCreationDate(fileReference: \'{file}\')}
            </html>
        ');

        self::assertSame(
            $expects,
            $view->render()
        );
    }
}
