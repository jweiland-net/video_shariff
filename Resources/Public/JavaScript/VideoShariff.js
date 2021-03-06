function replaceVideo(event) {
    var TYPO3 = TYPO3 || {};
    var useDeprecatedWay = false;
    var previewLink = event.target;

    while (previewLink.className !== 'video-shariff-play') {
        previewLink = previewLink.parentElement;
    }
    try {
        if (TYPO3.hasOwnProperty('settings') && TYPO3.settings['video_shariff']['video'][previewLink.dataset.video]) {
            useDeprecatedWay = true
        }
    } catch (exception) {
    }
    if (useDeprecatedWay) {
        // Support deprecated templates with VideoViewHelper call until v2.0.0
        previewLink.outerHTML = TYPO3.settings['video_shariff']['video'][previewLink.dataset.video];
    } else {
        previewLink.outerHTML = JSON.parse(previewLink.dataset.video);
    }
}

var videos = document.getElementsByClassName('video-shariff-play'), i = 0;
for (i; i < videos.length; i++) {
    videos[i].onclick = function(event){event.preventDefault(); replaceVideo(event);};
}
