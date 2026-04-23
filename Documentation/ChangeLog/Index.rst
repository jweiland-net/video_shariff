..  include:: /Includes.rst.txt


..  _changelog:

=========
ChangeLog
=========

Version 5.1.2
=============

*   [BUGFIX] Fixed arguments missing in translation files

Version 5.1.1
=============

*   [BUGFIX] Update XLIFF files

Version 5.1.0
=============

*   [TASK] Update .editorconfig to refine rules for file types
*   [TASK] Update composer.json for TYPO3 14.3 compatibility and bump version to 5.1.0
*   [TASK] Update CGL config for TYPO3 14.3 compatibility adjustments
*   [TASK] Migrate XLIFF files to version 2.0 format for VideoShariff
*   [TASK] Restructure TypoScript defaultThumbnail configuration for improved clarity

Version 5.0.0
=============

*   [FEATURE] Added Site Set setting ``videoShariff.defaultThumbnail`` (category
    ``videoShariff``) so the fallback thumbnail used when no provider-specific
    preview image can be fetched is now configurable per-site from the backend
    Settings editor or from a site package's :file:`config.yaml`, replacing the
    previous hard-coded ``lib.video_shariff.defaultThumbnail`` constant lookup.
*   [TASK] Compatibility fixes for TYPO3 14.
*   [TASK] Replaced deprecated ``renderStatic()`` with instance ``render()`` method in all ViewHelpers.
*   [TASK] Constructor injection of ``OnlineMediaHelperRegistry`` in ``VideoPreviewImageViewHelper`` and ``VideoPublicUrlViewHelper``.
*   [TASK] Replaced deprecated ``StandaloneView`` with ``RenderingContextFactory`` + ``TemplateView`` in functional tests.
*   [TASK] Updated ``GetOnlineMediaHelperTrait`` — deprecated in favour of direct constructor injection.
*   [TASK] Bumped ``phpstan/phpstan`` requirement to ``^2.0``.
*   [TASK] Bumped ``typo3/coding-standards`` requirement to ``^0.9``.
*   [TASK] Updated PHPUnit XML schema reference to version 11.5.
*   [TASK] Added PHP 8.4 to CI matrix.

Version 4.0.0
=============

*   [TASK] Compatibiliy fixes for TYPO3 13 LTS.
*   [TASK] Documentation settings changed from settings.cfg to guides.xml

Version 3.2.0
=============

*   [TASK] Fixed Broken badge url
*   [TASK] Documentation ChangeLog page added
*   [BUGFIX] Prevent TypeError when returning public

Version 3.1.0
=============

*   [FEATURE] Add VH argument fallbackThumbnailFile
*   [TASK] Remove TypoScript access from VH
*   [BUGFIX] Prevent duplicate EXT: parsing
*   [DOCS] Please read update instructions before updating. Maybe you have to update your templates!!!
