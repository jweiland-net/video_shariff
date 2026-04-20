<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\ViewHelpers;

use JWeiland\VideoShariff\Traits\GetCoreFileReferenceTrait;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class VideoPublicUrlViewHelper
 */
final class VideoPublicUrlViewHelper extends AbstractViewHelper
{
    use GetCoreFileReferenceTrait;

    public function __construct(
        private readonly OnlineMediaHelperRegistry $onlineMediaHelperRegistry,
    ) {}

    public function initializeArguments(): void
    {
        $this->registerArgument(
            'fileReference',
            'object',
            'FileReference to be used for creating the preview image',
        );
    }

    /**
     * Returns the public URL of the online media resource.
     *
     * @throws \UnexpectedValueException
     */
    public function render(): string
    {
        $publicUrl = '';

        // Early return, if object is not allowed
        if (
            $this->arguments['fileReference'] instanceof FileReference
            || $this->arguments['fileReference'] instanceof ExtbaseFileReference
        ) {
            $fileReference = self::getCoreFileReference($this->arguments['fileReference']);
        } else {
            return $publicUrl;
        }

        $file = $fileReference->getOriginalFile();
        $helper = $this->onlineMediaHelperRegistry->getOnlineMediaHelper($file);
        if ($helper instanceof OnlineMediaHelperInterface) {
            $publicUrl = $helper->getPublicUrl($file) ?? '';
        }

        return $publicUrl;
    }
}
