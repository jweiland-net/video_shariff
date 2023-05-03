<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\ViewHelpers;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class VideoCreationDateViewHelper
 */
class VideoCreationDateViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments(): void
    {
        $this->registerArgument(
            'fileReference',
            'object',
            'FileReference to be used for creating the preview image'
        );
    }

    /**
     * Returns the absolute web path to the preview image.
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return int
     * @throws \UnexpectedValueException
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): int {
        /** @var FileReference|ExtbaseFileReference $fileReference */
        $fileReference = $arguments['fileReference'];

        // get Resource Object (non ExtBase version)
        if (is_callable([$fileReference, 'getOriginalResource'])) {
            // We have a domain model, so we need to fetch the FAL resource object from there
            $fileReference = $fileReference->getOriginalResource();
        }
        if (!($fileReference instanceof FileInterface || $fileReference instanceof AbstractFileFolder)) {
            throw new \UnexpectedValueException('Supplied file object type ' . get_class($fileReference) . ' must be FileInterface or AbstractFileFolder.', 1454252193);
        }
        $file = $fileReference->getOriginalFile();
        if ($file->getProperty('content_creation_date')) {
            return $file->getProperty('content_creation_date');
        }
        if ($file->getProperty('creation_date')) {
            return $file->getProperty('creation_date');
        }
        if ($file->getProperty('crdate')) {
            return $file->getProperty('crdate');
        }
        return 0;
    }
}
