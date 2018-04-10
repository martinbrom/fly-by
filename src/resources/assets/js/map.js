var map = null;

function mapInit() {
    var map = L.map('map').setView([51, 0], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
}

function switchToMap() {
    $('#map-bottom-control-panel').show();
    $('#map-top-wrapper').height(100);
}

function switchToPanel() {
    $('#map-bottom-control-panel').hide();
    $('#map-top-wrapper').height('100%');
}

$(document).ready(function () {
    mapInit();

    $('#map-bottom-buttons').find('button').click(function (event) {
        switchToMap();
    });

    $('#map').click(function (event) {
        switchToPanel();
    });
});