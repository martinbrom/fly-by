function scrollToPageID(page_id) {
    $('body,html').animate({scrollTop: window.innerHeight * page_id}, 750);
}

$(document).ready(function () {
    let map = new Flb.Map('airport-map', false);

    $("a.scroll-link").click(function () {
        scrollToPageID($(this).attr("data-scroll-to"));
    });

    map.map.setView(new L.LatLng(49.7384, 13.3736), 8);
    map.map.setMinZoom(7);

    for (let i = 0; i < airports.length; i++) {
        map.addAirport(airports[i].id, airports[i].name, new L.LatLng(airports[i].lat, airports[i].lon));
    }
});
