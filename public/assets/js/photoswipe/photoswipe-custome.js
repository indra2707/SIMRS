// Initialize an empty array to hold the images
var items = [];

// Loop through each image in the table and build an array for PhotoSwipe
$("table a").each(function () {
    var imageLink = $(this).attr("href");
    var imageCaption = $(this).next().attr("alt"); // Using the alt text as caption
    items.push({
        src: imageLink,
        w: 0, // PhotoSwipe will automatically calculate image width
        h: 0, // PhotoSwipe will automatically calculate image height
        title: imageCaption,
    });
});

// Bind the gallery open event to the table images
$("table a").on("click", function (e) {
    e.preventDefault();

    var index = $(this).data("index"); // Get the index from data attribute
    var pswpElement = document.querySelectorAll(".pswp")[0];

    // Initialize PhotoSwipe
    var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, {
        index: index,
        showHideOpacity: true,
        history: false,
        focus: false,
    });

    gallery.init();
});
