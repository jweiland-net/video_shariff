..  include:: /Includes.rst.txt


..  _typoscript:

==========
TypoScript
==========

`video_shariff` needs some basic TypoScript configuration. To do so you have
to add an +ext template to either the root page of your website or to a
specific page where you want to use the video_shariff functionality.

..  rst-class:: bignums

1.  Locate page

    You have to decide where you want to insert the TypoScript template. Either
    root page or page with the video output.

2.  Create TypoScript template

    Switch to template module and choose the specific page from above in the
    pagetree. Choose `Click here to create an extension template` from the
    right frame. In the TYPO3 community it is also known as "+ext template".

3.  Add static template

    Choose `Info/Modify` from the upper selectbox and then click on `Edit the
    whole template record` button below the little table. On tab `Includes`
    locate the section `Include static (from extension)`. Use the search below
    `Available items` to search for `video_shariff`. Hopefully just one record
    is visible below. Choose it, to move that record to the left.

    ..  figure:: ../Images/BasicConfiguration.png
        :width: 300px
        :alt: Configuration

4.  Save

    If you want you can give that template a name on tab "General", save and
    close it.

5.  Constants Editor

    Choose `Constant Editor` from the upper selectbox.

    ..  figure:: ../Images/CustomThumbnailConstantEditor.png
        :width: 300px
        :alt: Constant editor

6.  `video_shariff` constants

    Choose `lib.video_shariff` from the category selectbox to show just
    `video_shariff` related constants

7.  Configure constants

    Adapt the constants to your needs.

8.  Configure TypoScript

    As constants will only allow modifiying a fixed selection of TypoScript you
    also switch to `Info/Modify` again and click on `Setup`. Here you have
    the possibility to configure all `video_shariff` related configuration.

Lib
===

video_shariff.defaultThumbnail
------------------------------

Default: Value from Constants *EXT:video_shariff/Resources/Public/Images/DefaultThumbnail.png*

The default thumbnail is rendered when no video specific thumbnail can be
fetched.

Example:

..  code-block:: typoscript

    lib.video_shariff.defaultThumbnail = EXT:site_package/Resources/Public/Images/FunnySunnyThumbnail.png*

Language
========

Override preview image text
---------------------------

You can override the default preview image text and adding new languages via
TypoScript setup.

..  code-block:: typoscript

    plugin.tx_videoshariff._LOCAL_LANG.default.preview\.text = I am a custom preview text...
    plugin.tx_videoshariff._LOCAL_LANG.de.preview\.text = Ich bin ein angepasster Vorschautext...
