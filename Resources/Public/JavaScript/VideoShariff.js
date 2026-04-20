/*
 * This file is part of the package jweiland/video-shariff.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

(() => {
  'use strict';

  const PLAY_SELECTOR = '.video-shariff-play';

  /**
   * Replaces the clicked preview link with the real embed markup stored in
   * its `data-video` attribute (JSON-encoded player HTML).
   *
   * @param {MouseEvent} event
   */
  const handlePreviewClick = (event) => {
    // 1. Check if the click happened inside our target element FIRST
    const previewLink = event.target.closest(PLAY_SELECTOR);
    if (!previewLink) {
      return; // Exit immediately if they clicked somewhere else
    }

    // 2. NOW prevent the default action (e.g., following an href)
    event.preventDefault();

    const payload = previewLink.dataset.video;
    if (typeof payload !== 'string' || payload === '') {
      return;
    }

    try {
      previewLink.outerHTML = JSON.parse(payload);
    } catch (error) {
      // Intentionally swallow: a malformed data-video attribute should
      // never break the surrounding page.
      console.error('[video_shariff] Unable to decode data-video payload', error);
    }
  };

  // Attach a single listener to the document.
  document.addEventListener('click', handlePreviewClick);
})();
