
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

    this.route = new Route(null, null, [], this.map);
    this.route.addTo(this.map);

    this.airports = {};
    this.zones = [];
    this.startAirport = null;
    this.endAirport = null;

    this.startAirportChange = null;
    this.endAirportChange = null;
}

Map.prototype.addAirport = function (id, name, latlng) {
    let airport = new Airport(id, name, latlng, map);

    let t = this;
    airport.onclick(function () {
        t.chooseStartAirport(this);
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

Map.prototype.onStartAirportChange = function (callback) {
    this.startAirportChange = L.bind(callback, this);
};

Map.prototype.onEndAirportChange = function (callback) {
    this.endAirportChange = L.bind(callback, this);
};

Map.prototype.chooseStartAirport = function (airport) {
    if (typeof airport === 'number') {
        airport = this.airports[airport];
    }

    let event = {
        old: this.startAirport,
        new: airport
    };

    if (this.startAirport) {
        this.startAirport.setStart(false);
        this.startAirport.bringToBack();
    }

    this.startAirport = airport;

    if (this.startAirport) {
        this.startAirport.setStart(true);
        this.startAirport.bringToFront();
    }

    if (this.startAirportChange) {
        this.startAirportChange(event);
    }

    this.route.setStartAirport(airport);

    if (!this.endAirport) {
        this.route.setEndAirport(airport);
    }
};

Map.prototype.chooseEndAirport = function (airport) {
    if (typeof airport === 'number') {
        airport = this.airports[airport];
    }

    let event = {
        old: this.endAirport,
        new: airport
    };

    if (this.endAirport) {
        this.endAirport.setEnd(false);
        this.endAirport.bringToBack();
    }

    this.endAirport = airport;

    if (this.endAirport) {
        this.endAirport.setEnd(true);
        this.endAirport.bringToFront();
    }

    if (this.endAirportChange) {
        this.endAirportChange(event);
    }

    this.route.setEndAirport(airport);

    if (!this.endAirport) {
        this.route.setEndAirport(this.startAirport);
    }
};



//-------------
// Route
//-------------
Route = function (startAirport, endAirport, latlngs, map) {
    let t = this;

    this.startAirport = startAirport;
    this.endAirport = endAirport;

    this.map = map;
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
        if (t.startAirport) {
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

Route.prototype.setStartAirport = function (startAirport) {
    this.startAirport = startAirport;
    this.refreshLine();
};

Route.prototype.setEndAirport = function (endAirport) {
    this.endAirport = endAirport;
    this.refreshLine();
};

Route.prototype.addWayPoint = function (latlng, index) {
    let t = this;

    if (typeof index === 'undefined') {
        index = this.wayPoints.length;
    }

    let wayPoint = new Waypoint(latlng, index + 1, this);

    wayPoint.marker.on('drag', function (event) {

        // disable dragging outside the map
        let containerPoint = t.map.latLngToContainerPoint(event.target.getLatLng()),
            clampX = null,
            clampY = null,
            MARKER_MARGIN = 10;

        let mapContainerBounds = t.map.getContainer().getBoundingClientRect();

        if (containerPoint.x - MARKER_MARGIN < 0) {

            clampX = MARKER_MARGIN;
        } else if (containerPoint.x + MARKER_MARGIN > mapContainerBounds.width) {
            clampX = mapContainerBounds.width - MARKER_MARGIN;
        }
        if (containerPoint.y - MARKER_MARGIN < 0) {

            clampY = MARKER_MARGIN;
        } else if (containerPoint.y + MARKER_MARGIN > mapContainerBounds.height) {
            clampY = mapContainerBounds.height - MARKER_MARGIN;
        }
        if (clampX !== null || clampY !== null) {

            if (clampX !== null) { containerPoint.x = clampX; }
            if (clampY !== null) { containerPoint.y = clampY; }
            this.setLatLng(t.map.containerPointToLatLng(containerPoint));
        }

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
    let startAirport = null;
    let endAirport = null;

    if (this.startAirport) {
        startAirport = this.startAirport.getLatLng();
    }
    if (this.endAirport) {
        endAirport = this.endAirport.getLatLng();
    }


    if (latlngs.length) {
        if (startAirport) {
            latlngs = _.concat(this.startAirport.getLatLng(), latlngs);
        }

        if (endAirport) {
            latlngs = _.concat(latlngs, this.endAirport.getLatLng());
        }
    }
    else if (startAirport && endAirport && endAirport !== startAirport) {
        latlngs = _.concat(startAirport, endAirport);
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

Route.prototype.reverse = function () {
    this.wayPoints.reverse();

    let temp = this.startAirport;
    this.startAirport = this.endAirport;
    this.endAirport = temp;

    this.startAirport.setEnd(false);
    this.startAirport.setStart(true);

    this.endAirport.setEnd(true);
    this.endAirport.setStart(false);

    this.refreshNumbers();
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
Airport = function (id, name, latlng, map) {
    this.id = id;
    this.name = name;
    this.active = false;
    this.end = false;
    this.map = map;

    let t = this;

    this.marker = L.marker(latlng, {
        icon: Airport.iconInactive,
        contextmenu: true,
        contextmenuInheritItems: false
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

    this.refresh();
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

Airport.iconStart = L.ExtraMarkers.icon({
    icon: 'fa-plane',
    prefix: 'fa',
    markerColor: 'green-light'
});

Airport.iconEnd = L.ExtraMarkers.icon({
    icon: 'fa-flag-checkered',
    prefix: 'fa',
    markerColor: 'yellow'
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

Airport.prototype.setStart = function (start) {
    this.start = start;
    this.refresh();
};

Airport.prototype.setEnd = function (end) {
    this.end = end;
    this.refresh();
};

Airport.prototype.refresh = function () {
    let t = this;

    this.marker.options.contextmenuItems = [];

    if (this.start) {
        this.marker.setIcon(Airport.iconStart);
    }
    else if (this.end) {
        this.marker.setIcon(Airport.iconEnd);
        this.marker.options.contextmenuItems.push({
            text: 'Zrušit jako cíl',
            iconCls: 'fa fa-times',
            callback: function () {
                t.map.chooseEndAirport(null);
            }
        });
    }
    else {
        this.marker.setIcon(Airport.iconInactive);
        this.marker.options.contextmenuItems.push({
            text: 'Nastavit jako cíl',
            iconCls: 'fa fa-flag-checkered',
            callback: function () {
                t.map.chooseEndAirport(t);
            }
        });
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

        M.chooseStartAirport(airport);
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
    map.onStartAirportChange(function (event) {
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