var map = null;

function mapInit() {
    var map = L.map('map').setView([51, 0], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
}

function switchToMap() {
    $('#map-control-panel').hide();
}

function switchToPanel() {
    $('#map-control-panel').show();
}

$(document).ready(function () {
    mapInit();

    $('#map-bottom-buttons').find('button').click(function (event) {
        switchToPanel();
    });

    $('#map').click(function (event) {
        switchToMap()
    });
});