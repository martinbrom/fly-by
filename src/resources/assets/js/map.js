var map = null;
var wayPointMarker = null;
var wayPoints = [];

function mapInit() {
    map = L.map('map').setView([51, 0], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    wayPointMarker = L.AwesomeMarkers.icon({
        icon: 'crosshairs',
        prefix: 'fa',
        markerColor: 'red'
    });
}

function addWayPoint() {
    var wayPoint = L.marker(map.getCenter(), {
        icon: wayPointMarker,
        draggable: true
    });
    wayPoints.push(wayPoint);
    wayPoint.addTo(map);
}

function switchToMap() {
    $('#map-control-panel').hide();
    map.invalidateSize();
}

function switchToPanel() {
    $('#map-control-panel').show();
    map.invalidateSize();
}

$(document).ready(function () {
    mapInit();

    $('#map-bottom-buttons').find('button').click(function (event) {
        switchToPanel();
    });

    $('#map').click(function (event) {
        switchToMap()
    });

    $('#btn-add-waypoint').click(function (event) {
        addWayPoint();
    });
});