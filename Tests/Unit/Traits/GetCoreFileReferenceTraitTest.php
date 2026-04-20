<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\Tests\Unit\Traits;

use JWeiland\VideoShariff\Traits\GetCoreFileReferenceTrait;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;

#[CoversTrait(GetCoreFileReferenceTrait::class)]
final class GetCoreFileReferenceTraitTest extends TestCase
{
    /**
     * Anonymous class exposing the protected static method via a public proxy so it
     * can be tested without going through a ViewHelper.
     */
    private object $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new class () {
            use GetCoreFileReferenceTrait;

            public function invoke(FileReference|ExtbaseFileReference $fileReference): FileReference
            {
                return self::getCoreFileReference($fileReference);
            }
        };
    }

    #[Test]
    public function coreFileReferenceIsReturnedUnchanged(): void
    {
        $coreFileReference = $this->createMock(FileReference::class);

        self::assertSame($coreFileReference, $this->subject->invoke($coreFileReference));
    }

    #[Test]
    public function extbaseFileReferenceIsUnwrappedToItsCoreFileReference(): void
    {
        $coreFileReference = $this->createMock(FileReference::class);
        $extbaseFileReference = new ExtbaseFileReference();
        $extbaseFileReference->setOriginalResource($coreFileReference);

        self::assertSame($coreFileReference, $this->subject->invoke($extbaseFileReference));
    }
}
