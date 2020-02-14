function replaceVideo(event) {
    var previewLink = event.target;
    while (previewLink.className !== 'video-shariff-play') {
        previewLink = previewLink.parentElement;
    }
    previewLink.outerHTML = JSON.parse(previewLink.dataset.video);
}

var videos = document.getElementsByClassName('video-shariff-play'), i = 0;
for (i; i < videos.length; i++) {
    videos[i].onclick = function(event){event.preventDefault(); replaceVideo(event);};
}
