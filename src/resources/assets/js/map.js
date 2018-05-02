
//-------------
// Map
//-------------
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

    this.dangerZoneColor = 'red';

    this.route = new Route(null, null, []);
    this.route.addTo(this.map);

    this.airports = {};
    this.zones = [];
}

Map.prototype.addAirport = function (id, latlng) {
    let airport = new Airport(id, latlng);

    this.airports[id] = airport;
    airport.addTo(this.map);
};

Map.prototype.clearAirports = function () {
    $.each(this.airports, function (airport) {
        airport.removeFrom(this.map);
    });
    this.airports = [];
};

Map.prototype.addZone = function (options) {
    let zone = null;
    if (options.type === 'circle') {
        zone = L.circle(options.center, {
            color: this.dangerZoneColor,
            fillColor: this.dangerZoneColor,
            fillOpacity: 0.5,
            radius: options.radius
        }).addTo(this.map);
    }
    else if (options.type === 'polygon') {
        zone = L.polygon(options.latlngs, {
            color: this.dangerZoneColor,
            fillColor: this.dangerZoneColor,
            fillOpacity: 0.5
        }).addTo(this.map);
    }
    else {
        throw "Invalid zone type";
    }

    this.zones.push(zone);
};


//-------------
// Route
//-------------
Route = function (from, to, latlngs) {
    let t = this;

    this.from = from;
    this.to = to;

    this.map = null;
    this.wayPoints = [];

    this.line = L.polyline(latlngs, {
        color: '#0066a2',
        weight: 5
    });
    this.line.bindPopup('Přidat bod', {
        closeButton: false
    });
    this.line.on('mousemove', function (event) {
        this.openPopup(event.latlng);
    });

    this.line.on('mouseout', function (event) {
        this.closePopup();
    });
    this.line.on('click', function (event) {
        let i = t.closestSegmentIndex(event.latlng) + 1;
        t.addWayPoint(event.latlng, i);
    });

    for (let i = 0; i < latlngs.length; i++) {
        this.addWayPoint(latlngs[i]);
    }

    this.marker = L.AwesomeMarkers.icon({
        icon: 'crosshairs',
        prefix: 'fa',
        markerColor: 'darkblue'
    });

    this.refresh();
};

Route.prototype.closestSegmentIndex = function (latlng) {
    let point = this.map.latLngToLayerPoint(latlng);
    let linepoints = this.line.getLatLngs();

    for (let i = 0; i < linepoints.length; i++) {
        linepoints[i] = this.map.latLngToLayerPoint(linepoints[i]);
    }

    let min = Number.MAX_VALUE;
    let minI = 0;

    for (let i = 0; i < linepoints.length - 1; i++) {
        let dist = L.LineUtil.pointToSegmentDistance(point, linepoints[i], linepoints[i + 1]);
        if (dist < min) {
            min = dist;
            minI = i;
        }
    }

    return minI;
};

Route.prototype.setFrom = function (from) {
    this.from = from;
    this.refresh();
};

Route.prototype.setTo = function (to) {
    this.to = to;
    this.refresh();
};

Route.prototype.addWayPoint = function (latlng, index) {
    let wayPoint = L.marker(latlng, {
        icon: this.marker,
        draggable: true
    });

    let t = this;
    wayPoint.bindContextMenu({
        contextmenu: true,
        contextmenuItems: [
            {
                text: 'Odstranit',
                iconCls: 'fa fa-times',
                callback: function () {
                    t.removeWayPoint(wayPoint);
                }
            }
        ],
    });

    wayPoint.on('drag', function () {
        t.refresh();
    });

    if (typeof index === 'undefined') {
        index = this.wayPoints.length;
    }

    if (this.from) {
        index -= 1;
    }

    this.wayPoints.splice(index, 0, wayPoint);

    this.refresh();

    if (this.map) {
        wayPoint.addTo(this.map);
    }
};

Route.prototype.removeWayPoint = function (point) {
    let waypoint = null;

    if (typeof point === 'number') {
        waypoint = this.wayPoints[point];
    }
    else {
        waypoint = point;
        point = this.wayPoints.indexOf(waypoint);
    }

    this.wayPoints.splice(point, 1);
    this.refresh();

    if (this.map) {
        waypoint.removeFrom(this.map);
    }

    this.refresh();
};

Route.prototype.refresh = function () {
    let latlngs = this.wayPoints.map(function (point) {
        return point.getLatLng();
    });

    if (this.from) {
        latlngs = _.concat(this.from.getLatLng(), latlngs);
    }

    if (this.to) {
        latlngs = _.concat(latlngs, this.to.getLatLng());
    }

    this.line.setLatLngs(latlngs);
};

Route.prototype.getLatLngs = function () {
    return this.line.getLatLngs();
};

Route.prototype.addTo = function (map) {
    for (let i = 0; i < this.wayPoints.length; i++) {
        this.wayPoints[i].addTo(map);
    }
    this.line.addTo(map);

    this.map = map;
};

Route.prototype.removeFrom = function (map) {
    for (let i = 0; i < this.wayPoints.length; i++) {
        this.wayPoints[i].removeFrom(map);
    }
    this.line.removeFrom(map);
    this.map = null;
};


//-------------
// Airport
//-------------
Airport = function (id, latlng) {
    this.id = id;

    icon = L.AwesomeMarkers.icon({
        icon: 'plane',
        prefix: 'fa',
        markerColor: 'green'
    });

    this.marker = L.marker(latlng, {
        icon: icon,
    });
};

Airport.prototype.getLatLng = function () {
    return this.marker.getLatLng();
};

Airport.prototype.addTo = function (map) {
    this.marker.addTo(map);
};

Airport.prototype.removeFrom = function (map) {
    this.marker.removeFrom(map);
};




function reloadAirports() {
    $.getJSON('ajax/airports', function(result){
        $('#airport_id').html('');
        window.M.clearAirports();

        $('#airport_id').append($('<option>', {
            value: '',
            disabled: 'disabled',
            hidden: 'hidden',
            selected: 'selected'
        }));

        $.each(result, function(i, airport){
            $('#airport_id').append($('<option>', {
                value: airport.id,
                text: airport.name
            }));
            window.M.addAirport(airport.id, L.latLng(airport.lat, airport.lon));
        });
    });
}

function airportsInit() {
    $('#airport_id').change(function () {
        id = $(this).val();
        reloadAircrafts(id);
        window.M.route.setFrom(M.airports[id]);
        window.M.route.setTo(M.airports[id]);
    });

    reloadAirports();
}

function reloadAircrafts(airport_id) {
    $.get('ajax/aircrafts', {'airport_id': airport_id}, function(result){
        $('#aircraft_id').html('');
        $('#aircraft_id').append($('<option>', {
            value: '',
            disabled: 'disabled',
            hidden: 'hidden',
            selected: 'selected'
        }));

        $.each(result, function(i, aircraft){
            $('#aircraft_id').append($('<option>', {
                value: aircraft.id,
                text: aircraft.name,
            }));
        });
    })
}

function init() {
    airportsInit();
}

$(document).ready(function () {

    map = window.M = new Map('map');

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
        map.route.addWayPoint(M.map.getCenter());
    });
});