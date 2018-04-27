let M = null;

function Map (element) {
    if (typeof element !== 'string') {
        throw 'Element selector must be an id as a string';
    }

    map = $('#' + element);

    if (!map.length){
        throw 'Element ' + element + ' does not exist';
    }

    this.map = L.map('map');
    this.map.setView(new L.LatLng(49.7384, 13.3736), 10);
    this.map.setMinZoom(8);

    this.map.setMaxBounds(
        [
            [51.080430, 12.057014],
            [48.518948, 18.919228]
        ]
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(this.map);

    this.wayPointMarker = L.AwesomeMarkers.icon({
        icon: 'crosshairs',
        prefix: 'fa',
        markerColor: 'red'
    });

    this.airportMarker = L.AwesomeMarkers.icon({
        icon: 'plane',
        prefix: 'fa',
        markerColor: 'green'
    });

    this.wayPoints = [];
    this.route = L.polyline([], {color: 'red'}).addTo(this.map);

    this.airports = [];
    this.zones = [];
}

Map.prototype.addWayPoint = function () {
    let latlng = this.map.getCenter();

    let wayPoint = L.marker(latlng, {
        icon: this.wayPointMarker,
        draggable: true
    });

    wayPoint.on('drag', $.proxy( function () {
        this.route.setLatLngs(this.wayPoints.map(function (point) {
            return point.getLatLng()
        }));
    }), this);

    this.route.addLatLng(latlng);

    this.wayPoints.push(wayPoint);
    wayPoint.addTo(this.map);
};

Map.prototype.addAirport = function (latlng) {
    if (!(latlng instanceof L.LatLng)) {
        throw 'latlng can only be a LatLng instance'
    }

    let airport = L.marker(latlng, {
        icon: this.airportMarker,
    });

    // airportPoint.on('drag', function (event) {
    //     route.setLatLngs(wayPoints.map(function (point) {
    //         return point.getLatLng()
    //     }));
    // });

    console.log(latlng);
    
    this.airports.push(airport);
    airport.addTo(this.map);
};

Map.prototype.clearAirports = function () {
    $.each(this.airports, function (airport) {
        airport.removeFrom(this.map);
    });
    this.airports = [];
};

// function mapInit() {
//     map = L.map('map').setView([51, 0], 13);
//
//     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//         attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
//     }).addTo(map);
//
//     wayPointMarker = L.AwesomeMarkers.icon({
//         icon: 'crosshairs',
//         prefix: 'fa',
//         markerColor: 'red'
//     });
//
//     airportMarker = L.AwesomeMarkers.icon({
//         icon: 'airplane',
//         prefix: 'fa',
//         markerColor: 'green'
//     });
//
//     route = L.polyline([], {color: 'red'}).addTo(map);
// }

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

function reloadAirports() {
    $.getJSON('ajax/airports', function(result){
        $('#airport_id').html('');
        M.clearAirports();

        $.each(result, function(i, airport){
            $('#airport_id').append($('<option>', {
                value: airport.id,
                text: airport.name
            }));
            M.addAirport(L.latLng(airport.lat, airport.lon));
        });
    }).done(function () {
        reloadAircrafts($('#airport_id').val());
    });
}

function airportsInit() {
    reloadAirports();
}

function reloadAircrafts(airport_id) {
    $.get('ajax/aircrafts', {'airport_id': airport_id}, function(result){
        $('#aircraft_id').html('');

        $.each(result, function(i, aircraft){
            $('#aircraft_id').append($('<option>', {
                value: aircraft.id,
                text: aircraft.name
            }));
        });
    })
}

function aircraftsInit() {
    $('#airport_id').change(function () {
        reloadAircrafts($(this).val());
    });
}

function init() {
    airportsInit();
    aircraftsInit();
}

$(document).ready(function () {
    let map = M = new Map('map');
    init();

    function switchToMap() {
        $('#map-control-panel').hide();
        map.map.invalidateSize();
    }

    function switchToPanel() {
        $('#map-control-panel').show();
        map.map.invalidateSize();
    }

    $('#map-bottom-buttons').find('button').click(function (event) {
        switchToPanel();
    });

    $('#map').click(function (event) {
        switchToMap()
    });

    $('#btn-add-waypoint').click(function (event) {
        map.addWayPoint();
    });
});