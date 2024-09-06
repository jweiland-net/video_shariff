..  include:: /Includes.rst.txt


..  _update:

======
Update
======

Update to version 3.1.0
=======================

With version 3.1.0 we have removed the TypoScript access (`TSFE`) from
`VideoPreviewImageViewHelper`. In our opineon all needed data should be
transferred as ViewHelper arguments. That's why we have added a new argument
`fallbackThumbnailFile` with following default value:

..  code-block:: typoscript

    EXT:video_shariff/Resources/Public/Images/DefaultThumbnail.png

If you want to use your fallback thumbnail in your own fluid templates use
VH as following:

..  code-block:: html

    <jw:videoPreviewImage fileReference="{mediaElement}" fallbackThumbnailFile="EXT:site_package/Resources/Public/Icons/FallbackIcon.png"/>

We have kept the TypoScript constant to be more compatible with earlier
`video_shariff` versions. So, it's still possible to define a fallback
thumbnail globally or on a page base:

..  code-block:: typoscript

    lib.video_shariff.defaultThumbnail = EXT:video_shariff/Resources/Public/Images/DefaultThumbnail.png

The only difference is, that we made the constant available as setting in
`FLUIDTEMPLATE` of `fluid_styled_content` and `news`:

..  code-block:: typoscript

    lib.contentElement {
      settings.video_shariff.defaultThumbnail = {$lib.video_shariff.defaultThumbnail}
    }

    plugin.tx_news {
      settings.video_shariff.defaultThumbnail = {$lib.video_shariff.defaultThumbnail}
    }

We have updated fluid templates of `video_shariff` already to set that new
setting as argument for `VideoPreviewImageViewHelper`. Please make sure to also
update your own fluid templates to get the new implementation working.
