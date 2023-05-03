<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\VideoShariff\Traits;

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

trait GetTypoScriptSetupTrait
{
    protected static function getTypoScriptSetup(): array
    {
        $typoScript = [];

        $typo3Version = GeneralUtility::makeInstance(Typo3Version::class);
        if (version_compare($typo3Version->getBranch(), '12.1', '>')) {
            $frontendTypoScript = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript');
            if ($frontendTypoScript instanceof FrontendTypoScript) {
                $typoScript = $frontendTypoScript->getSetupArray();
            }
        } else {
            $typoScript = self::getTypoScriptFrontendController()->tmpl->setup;
        }

        return $typoScript;
    }

    protected static function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
