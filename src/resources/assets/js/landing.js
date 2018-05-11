function scrollToPageID(page_id) {
    $('body,html').animate({scrollTop: window.innerHeight * page_id}, 750);
}

$(document).ready(function () {
    var map;

    $("a.scroll-link").click(function () {
        scrollToPageID($(this).attr("data-scroll-to"));
    });

    map = L.map('airport-map');
    map.setView(new L.LatLng(49.7384, 13.3736), 8);
    map.setMinZoom(7);

    map.setMaxBounds([
        [51.080430, 12.057014],
        [48.518948, 18.919228]
    ]);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    $.getJSON('ajax/airports', function (result) {
        result.forEach(function (airport) {
            // TODO: Add marker for each of airports
        });
    });
});
