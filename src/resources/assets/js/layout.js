$(document).ready(function (event) {
    $('.toggle').click(function (event) {
        var target = $($(this).attr('href'));
        target.toggleClass('closed');
    });
});