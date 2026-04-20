..  include:: /Includes.rst.txt


..  _sitesets:

=========
Site Sets
=========

Starting with TYPO3 v13, TYPO3 introduced *Site Sets* as the modern
replacement for the classic "Include static (from extensions)" mechanism.
`video_shariff` ships its own Site Set which bundles the TypoScript,
TSconfig and settings the extension needs at runtime. Registering this
Site Set with your site configuration is the recommended way to enable
`video_shariff` on TYPO3 v13 and later.

..  note::

    The Site Set key for this extension is ``jweiland/video-shariff``.
    It is defined in :file:`EXT:video_shariff/Configuration/Sets/video-shariff/config.yaml`.

Why the Site Set must be added
==============================

Adding the Site Set is **mandatory** for `video_shariff` to work correctly
on TYPO3 v13+ sites. Without it, none of the extension's TypoScript is
loaded, which has the following consequences:

*   The Shariff-wrapped video output (two-click privacy layer) is not
    rendered - videos from YouTube and Vimeo would be embedded directly,
    defeating the entire purpose of `video_shariff`.
*   The preview image rendering (thumbnail + overlay text) is not
    initialized, so the ``VideoPublicUrlViewHelper`` and
    ``VideoPreviewImageViewHelper`` have no configuration to fall back to.
*   Constants such as ``lib.video_shariff.defaultThumbnail`` and the
    language overrides for the preview text are not available.
*   The plugin's frontend assets (JavaScript, CSS) are not included.

In short: if the Site Set is not added, the extension is installed but
**silently inactive** on the frontend. Adding the Site Set is therefore a
required step, not an optional one.

Adding the Site Set via the backend
===================================

..  rst-class:: bignums

1.  Open Site Management

    In the TYPO3 backend, navigate to the :guilabel:`Site Management`
    module on the left and choose :guilabel:`Sites`.

2.  Edit the site configuration

    Select the site for which `video_shariff` should be activated and open
    it for editing.

3.  Switch to the "Sets for this Site" tab

    Within the site configuration, switch to the tab :guilabel:`Sets for
    this Site` (in some installations labelled :guilabel:`Site Sets`).

4.  Select *Video Shariff*

    From the list of available Site Sets, activate :guilabel:`Video
    Shariff` (internal key ``jweiland/video-shariff``). The label is
    defined by the extension in :file:`Configuration/Sets/video-shariff/config.yaml`.

    ..  figure:: ../../Images/SiteSetConfiguration.png
        :width: 600px
        :alt: Adding the video_shariff Site Set in the TYPO3 site configuration

        Enabling the *Video Shariff* Site Set for a site in the TYPO3
        Site Management module.

5.  Save

    Save the site configuration. TYPO3 will flush the relevant caches so
    the Site Set takes effect immediately on the next frontend request.

Adding the Site Set via YAML
============================

If you manage your site configuration in version control, you can add
`video_shariff` as a dependency directly in the site's
:file:`config.yaml`:

..  code-block:: yaml

    dependencies:
      - jweiland/video-shariff

This is equivalent to selecting the Site Set in the backend and is the
preferred approach for site packages that are deployed via Composer.

Available settings
==================

The Site Set exposes its configuration through
:file:`Configuration/Sets/video-shariff/settings.definitions.yaml`. The
settings are grouped under the ``videoShariff`` category and can be
edited from the TYPO3 backend via :guilabel:`Site Management` →
:guilabel:`Settings`, or overridden per-site in the site's
:file:`config.yaml` / :file:`settings.yaml`.

..  confval:: videoShariff.defaultThumbnail

    :type: string
    :Default: ``EXT:video_shariff/Resources/Public/Images/DefaultThumbnail.png``

    Fallback thumbnail that is rendered in the Shariff preview overlay
    whenever no video-specific thumbnail can be fetched from the provider
    (for example when YouTube or Vimeo does not return a preview image,
    or when the upstream request fails). The value is an
    :ref:`EXT: path <t3coreapi:typoscript-syntax-file-includes>` resolved
    at render time by :php:`VideoPreviewImageViewHelper` via its
    ``fallbackThumbnailFile`` argument.

    The setting is consumed by the TypoScript constant
    ``lib.contentElement.settings.video_shariff.defaultThumbnail`` for
    ``fluid_styled_content`` and by
    ``plugin.tx_news.settings.video_shariff.defaultThumbnail`` for the
    ``news`` partial, both wired up in
    :file:`Configuration/TypoScript/setup.typoscript`.

    **Overriding it**

    Either adjust the value in the backend Settings editor, or set it in
    your site package's :file:`config.yaml`:

    ..  code-block:: yaml

        settings:
          videoShariff:
            defaultThumbnail: 'EXT:my_sitepackage/Resources/Public/Images/VideoFallback.jpg'

Relationship to the legacy TypoScript include
=============================================

On TYPO3 v12 and earlier, `video_shariff` was activated by adding the
static template *Video shariff (video_shariff)* to a TypoScript template
record - see :ref:`typoscript`. On TYPO3 v13+ the Site Set replaces that
include; you should **not** add both, as the Site Set already imports
the same TypoScript via:

..  code-block:: typoscript

    @import 'EXT:video_shariff/Configuration/TypoScript/setup.typoscript'

Verifying the Site Set is active
================================

After saving the site configuration you can verify that the Site Set is
active by:

*   Opening the :guilabel:`Template` module, selecting a page within the
    site and checking that ``lib.video_shariff`` constants appear in the
    Constant Editor.
*   Rendering a page that contains a YouTube or Vimeo video - the video
    must now be wrapped by the Shariff preview image instead of being
    embedded directly.
