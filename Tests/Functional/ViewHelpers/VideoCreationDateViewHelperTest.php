<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\Tests\Functional\ViewHelpers;

use JWeiland\VideoShariff\Traits\GetCoreFileReferenceTrait;
use JWeiland\VideoShariff\ViewHelpers\VideoCreationDateViewHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesTrait;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

#[CoversClass(VideoCreationDateViewHelper::class)]
#[UsesTrait(GetCoreFileReferenceTrait::class)]
final class VideoCreationDateViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/video_shariff',
    ];

    #[Test]
    public function renderReturnsContentCreationDateWhenAvailable(): void
    {
        $this->assertRenderedValueForProperties(
            [
                'content_creation_date' => 1683100800,
                'creation_date' => 0,
                'crdate' => 0,
            ],
            '1683100800',
        );
    }

    #[Test]
    public function renderFallsBackToCreationDateWhenContentCreationDateIsEmpty(): void
    {
        $this->assertRenderedValueForProperties(
            [
                'content_creation_date' => 0,
                'creation_date' => 1682928000,
                'crdate' => 0,
            ],
            '1682928000',
        );
    }

    #[Test]
    public function renderFallsBackToCrdateWhenOtherDatesAreEmpty(): void
    {
        $this->assertRenderedValueForProperties(
            [
                'content_creation_date' => 0,
                'creation_date' => 0,
                'crdate' => 1683014400,
            ],
            '1683014400',
        );
    }

    #[Test]
    public function renderReturnsZeroWhenAllDatePropertiesAreEmpty(): void
    {
        $this->assertRenderedValueForProperties(
            [
                'content_creation_date' => 0,
                'creation_date' => 0,
                'crdate' => 0,
            ],
            '0',
        );
    }

    #[Test]
    public function renderReturnsZeroWhenFileReferenceIsNotAFileReferenceObject(): void
    {
        $view = $this->buildTemplateView($this->getTemplateSource());
        $view->assign('file', new \stdClass());

        self::assertSame('0', trim($view->render()));
    }

    /**
     * Renders the template against both a core and an Extbase FileReference built from
     * the given property map, asserting that the rendered timestamp matches $expected
     * in both cases.
     *
     * @param array<string, int> $properties
     */
    private function assertRenderedValueForProperties(array $properties, string $expected): void
    {
        $propertyMap = [];
        foreach ($properties as $key => $value) {
            $propertyMap[] = [$key, $value];
        }

        $file = $this->createMock(File::class);
        $file->method('getUid')->willReturn(1);
        $file->method('getProperty')->willReturnMap($propertyMap);

        $coreFileReference = $this->createMock(FileReference::class);
        $coreFileReference->method('getOriginalFile')->willReturn($file);

        $extbaseFileReference = new ExtbaseFileReference();
        $extbaseFileReference->setOriginalResource($coreFileReference);

        foreach ([$coreFileReference, $extbaseFileReference] as $fileReference) {
            $view = $this->buildTemplateView($this->getTemplateSource());
            $view->assign('file', $fileReference);
            self::assertSame($expected, trim($view->render()));
        }
    }

    private function buildTemplateView(string $templateSource): TemplateView
    {
        $request = new ServerRequest('http://localhost/', 'GET');
        $context = $this->get(RenderingContextFactory::class)->create([], $request);
        $context->getTemplatePaths()->setTemplateSource($templateSource);

        return new TemplateView($context);
    }

    private function getTemplateSource(): string
    {
        return implode(' ', [
            '<html lang="en" xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"',
            'xmlns:jw="http://typo3.org/ns/JWeiland/VideoShariff/ViewHelpers"',
            'data-namespace-typo3-fluid="true">',
            '{jw:videoCreationDate(fileReference: \'{file}\')}',
            '</html>',
        ]);
    }
}
