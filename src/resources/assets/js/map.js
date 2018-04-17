var map = null;
var wayPointMarker = null;
var wayPoints = [];
var route = null;
var zones = [];

function initForbiddenZones() {

}

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

    route = L.polyline([], {color: 'red'}).addTo(map);

    initForbiddenZones();
}

function addWayPoint() {
    var latlng = map.getCenter();

    var wayPoint = L.marker(latlng, {
        icon: wayPointMarker,
        draggable: true
    });

    wayPoint.on('drag', function (event) {
        route.setLatLngs(wayPoints.map(function (point) {
            return point.getLatLng()
        }));
    });

    route.addLatLng(latlng);

    console.log(latlng);

    wayPoints.push(wayPoint);
    wayPoint.addTo(map);
}

function addZone(options) {
    if (options.type === 'circle') {
        var circle = L.circle(options.center, {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: options.radius
        }).addTo(map);
        zones.push(circle);
    }
    else if (options.type === 'polygon') {
        var polygon = L.polygon(options.latlngs, {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: options.radius
        }).addTo(map);
        zones.push(polygon);
    }
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