# TYPO3 Extension `video_shariff`

[![Packagist][packagist-logo-stable]][extension-packagist-url]
[![Latest Stable Version][extension-build-shield]][extension-ter-url]
[![Total Downloads][extension-downloads-badge]][extension-packagist-url]
[![Monthly Downloads][extension-monthly-downloads]][extension-packagist-url]
[![TYPO3 14.2][TYPO3-shield]][TYPO3-14-url]

![Build Status][extension-ci-shield]

This extensions adds a video local hosted preview to videos that has been embedded with fluid_styled_content

## 1 Features

* Add preview to videos embedded by fluid_styled_content

## 2 Usage

### 2.1 Installation

#### Installation using Composer

The recommended way to install the extension is using Composer.

Run the following command within your Composer based TYPO3 project:

```
composer require jweiland/video-shariff
```

#### Installation as extension from TYPO3 Extension Repository (TER)

Download and install `video_shariff` with the extension manager module.

### 2.2 Minimal setup

1) Include the static TypoScript of the extension.
2) Clear Cache.

## 3 Development

### 3.1 JavaScript build

The frontend script that swaps the preview image for the real player lives at
`Resources/Public/JavaScript/VideoShariff.js`. The minified bundle that
TypoScript loads — `Resources/Public/JavaScript/VideoShariff.min.js` — is
generated from that source with [esbuild](https://esbuild.github.io/) and
**committed to the repository** so that TER installs and Composer-based
projects can use the extension without a Node.js toolchain.

Requirements: Node.js &ge; 20 (see `engines` in `package.json`).

Install dev dependencies once:

```bash
npm install
```

Available scripts:

| Script              | What it does                                                                 |
| ------------------- | ---------------------------------------------------------------------------- |
| `npm run build:js`  | Bundle + minify `VideoShariff.js` into `VideoShariff.min.js` (ES2020 target). |
| `npm run watch:js`  | Same as `build:js`, re-running on every source change.                       |
| `npm run verify:js` | Rebuild and fail if the committed `VideoShariff.min.js` is out of date.      |

Whenever you edit `VideoShariff.js`, rebuild and commit the regenerated
`VideoShariff.min.js` in the same commit. CI runs `npm run verify:js` on every
pull request, so a forgotten rebuild will fail the build.

The minified bundle is marked `linguist-generated=true` in `.gitattributes`,
which collapses it in GitHub's diff UI and excludes it from repository
language statistics.

## 4 Support

Free Support is available via [GitHub Issue Tracker](https://github.com/jweiland-net/video_shariff/issues).

For commercial support, please contact us at [support@jweiland.net](support@jweiland.net).

[extension-build-shield]: https://poser.pugx.org/jweiland/video-shariff/v/stable.svg?style=for-the-badge

[extension-ci-shield]: https://github.com/jweiland-net/video_shariff/actions/workflows/ci.yml/badge.svg

[extension-downloads-badge]: https://poser.pugx.org/jweiland/video-shariff/d/total.svg?style=for-the-badge

[extension-monthly-downloads]: https://poser.pugx.org/jweiland/video-shariff/d/monthly?style=for-the-badge

[extension-ter-url]: https://extensions.typo3.org/extension/video_shariff/

[extension-packagist-url]: https://packagist.org/packages/jweiland/video-shariff/

[packagist-logo-stable]: https://img.shields.io/badge/--grey.svg?style=for-the-badge&logo=packagist&logoColor=white

[TYPO3-14-url]: https://get.typo3.org/version/14

[TYPO3-shield]: https://img.shields.io/badge/TYPO3-14.2-green.svg?style=for-the-badge&logo=typo3
