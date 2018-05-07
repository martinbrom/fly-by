
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

    this.map = L.map('map', {
        contextmenu: true
    });
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
    this.choosedAirport = null;

    this.airportChange = null;
}

Map.prototype.addAirport = function (id, name, latlng) {
    let airport = new Airport(id, name, latlng);

    let t = this;
    airport.onclick(function () {
        t.chooseAirport(this);
    });

    this.airports[id] = airport;
    airport.addTo(this.map);
};

Map.prototype.clearAirports = function () {
    $.each(this.airports, function (airport) {
        airport.removeFrom(this.map);
    });
    this.airports = {};
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

Map.prototype.onAirportChange = function (callback) {
    this.airportChange = L.bind(callback, this);
};

Map.prototype.chooseAirport = function (airport) {
    if (typeof airport === 'number') {
        airport = this.airports[airport];
    }

    let event = {
        old: this.choosedAirport,
        new: airport
    };

    if (this.choosedAirport) {
        this.choosedAirport.setActive(false);
        this.choosedAirport.bringToBack();
    }

    this.choosedAirport = airport;

    if (this.choosedAirport) {
        this.choosedAirport.setActive(true);
        this.choosedAirport.bringToFront();
    }

    if (this.airportChange) {
        this.airportChange(event);
    }

    this.route.setFrom(airport);
    this.route.setTo(airport);
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
        if (t.from) {
            i -= 1;
        }
        t.addWayPoint(event.latlng, i);
    });

    for (let i = 0; i < latlngs.length; i++) {
        this.addWayPoint(latlngs[i]);
    }

    this.refreshLine();
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
    this.refreshLine();
};

Route.prototype.setTo = function (to) {
    this.to = to;
    this.refreshLine();
};

Route.prototype.addWayPoint = function (latlng, index) {
    let t = this;

    if (typeof index === 'undefined') {
        index = this.wayPoints.length;
    }

    let wayPoint = new Waypoint(latlng, index + 1, this);

    wayPoint.marker.on('drag', function () {
        t.refreshLine();
    });

    this.wayPoints.splice(index, 0, wayPoint);

    if (this.map) {
        wayPoint.addTo(this.map);
    }

    this.refreshLine();
    this.refreshNumbers();
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
    this.refreshLine();

    if (this.map) {
        waypoint.removeFrom(this.map);
    }

    this.refreshLine();
    this.refreshNumbers();
};

Route.prototype.refreshNumbers = function () {
    for (let i = 0; i < this.wayPoints.length; i++) {
        this.wayPoints[i].setNumber(i + 1);
    }
};

Route.prototype.refreshLine = function () {
    let latlngs = this.wayPoints.map(function (point) {
        return point.marker.getLatLng();
    });
    let from = null;
    let to = null;

    if (this.from) {
        from = this.from.getLatLng();
    }
    if (this.to) {
        to = this.to.getLatLng();
    }


    if (latlngs.length) {
        if (from) {
            latlngs = _.concat(this.from.getLatLng(), latlngs);
        }

        if (to) {
            latlngs = _.concat(latlngs, this.to.getLatLng());
        }
    }
    else if (from && to && to !== from) {
        latlngs = _.concat(from, to);
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
// Waypoint
//-------------
Waypoint = function (latlng, number, route) {
    this.route = route;
    let t = this;

    this.marker = L.marker(latlng, {
        icon: L.ExtraMarkers.icon({
            icon: 'fa-number',
            markerColor: 'blue',
            shape: 'circle'
        }),
        draggable: true,
        contextmenu: true,
        contextmenuInheritItems: false,
        contextmenuItems: [
            {
                text: 'Odstranit',
                iconCls: 'fa fa-times',
                callback: function () {
                    route.removeWayPoint(t);
                }
            }
        ],
    });

    this.bringToFront();

    this.setNumber(number);
};

Waypoint.prototype.bringToFront = function () {
    this.marker.setZIndexOffset(40000);
};
Waypoint.prototype.bringToBack = function () {
    this.marker.setZIndexOffset(30000);
};

Waypoint.prototype.setNumber = function (number) {
    let icon = L.ExtraMarkers.icon({
        icon: 'fa-number',
        markerColor: 'blue',
        shape: 'circle',
        number: number
    });

    this.marker.setIcon(icon);
};

Waypoint.prototype.addTo = function (map) {
    this.marker.addTo(map);
};

Waypoint.prototype.removeFrom = function (map) {
    this.marker.removeFrom(map);
};


//-------------
// Airport
//-------------
Airport = function (id, name, latlng) {
    this.id = id;
    this.name = name;
    this.active = false;

    this.marker = L.marker(latlng, {
        icon: Airport.iconInactive,
    });

    this.marker.bindPopup(name, {
        closeButton: false
    });

    this.marker.on('mousemove', function () {
        this.openPopup();
    });

    this.marker.on('mouseout', function () {
        this.closePopup();
    });
};

Airport.prototype.bringToFront = function () {
    this.marker.setZIndexOffset(20000);
};
Airport.prototype.bringToBack = function () {
    this.marker.setZIndexOffset(10000);
};

Airport.iconInactive = L.ExtraMarkers.icon({
    icon: 'fa-plane',
    prefix: 'fa',
    iconColor: 'green',
    markerColor: 'white'
});

Airport.iconActive = L.ExtraMarkers.icon({
    icon: 'fa-plane',
    prefix: 'fa',
    markerColor: 'green-light'
});

Airport.prototype.getLatLng = function () {
    return this.marker.getLatLng();
};

Airport.prototype.addTo = function (map) {
    this.marker.addTo(map);
};

Airport.prototype.removeFrom = function (map) {
    this.marker.removeFrom(map);
};

Airport.prototype.setActive = function (active) {
    this.active = active;
    if (active) {
        this.marker.setIcon(Airport.iconActive);
    }
    else {
        this.marker.setIcon(Airport.iconInactive);
    }
};

Airport.prototype.onclick = function (callback) {
    this.marker.on('click', L.bind(callback, this));
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
            window.M.addAirport(airport.id, airport.name, L.latLng(airport.lat, airport.lon));
        });
    });
}

function airportsInit() {
    $('#airport_id').change(function () {
        let id = parseInt($(this).val());
        let airport = M.airports[id.toString()];

        M.chooseAirport(airport);
        reloadAircrafts(airport.id);
    });

    reloadAirports();
}

function reloadAircrafts(airport_id) {
    let aircraft_select = $('#aircraft_id');

    if (airport_id === null || airport_id === 'undefined' || airport_id === 'false' || airport_id === '') {
        aircraft_select.html('');
        aircraft_select.append($('<option>', {
            value: '',
            disabled: 'disabled',
            hidden: 'hidden',
            selected: 'selected'
        }));
        return;
    }

    $.get('ajax/aircrafts', {'airport_id': airport_id}, function(result){
        aircraft_select.html('');
        aircraft_select.append($('<option>', {
            value: '',
            disabled: 'disabled',
            hidden: 'hidden',
            selected: 'selected'
        }));

        $.each(result, function(i, aircraft){
            aircraft_select.append($('<option>', {
                value: aircraft.id,
                text: aircraft.name,
            }));
        });
    })
}

$(document).ready(function () {
    map = window.M = new Map('map');
    map.onAirportChange(function (event) {
        let id = event.new ? event.new.id : '';

        $('#airport_id').val(id);
        reloadAircrafts(id);
    });

    airportsInit();

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