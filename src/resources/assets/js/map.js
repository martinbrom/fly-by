function haversineDistance(latitudeFrom, longitudeFrom, latitudeTo, longitudeTo)
{
    var earthRadius = 6371000; // in meters

    var latFrom = deg2rad(latitudeFrom);
    var lonFrom = deg2rad(longitudeFrom);
    var latTo   = deg2rad(latitudeTo);
    var lonTo   = deg2rad(longitudeTo);

    var latDelta = latTo - latFrom;
    var lonDelta = lonTo - lonFrom;

    var angle = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(latDelta / 2), 2) + Math.cos(latFrom) * Math.cos(latTo) * Math.pow(Math.sin(lonDelta / 2), 2)));

    return angle * earthRadius;
}

function deg2rad(degrees) {
    return degrees * (Math.PI / 180);
}

//-------------
// Map
//-------------
function Map(element, interactive) {
    if (typeof element !== 'string') {
        throw 'Element selector must be an id as a string';
    }

    map = $('#' + element);

    if (!map.length) {
        throw 'Element ' + element + ' does not exist';
    }


    this.interactive = true;

    if (interactive === false) {
        this.interactive = false;
    }

    this.map = L.map(element, {
        contextmenu: true
    });
    this.map.setView(new L.LatLng(49.7384, 13.3736), 10);
    this.map.setMinZoom(6);

    this.map.setMaxBounds(
        [
            [52, 10],
            [47, 20]
        ]
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(this.map);

    this.dangerZoneColor = 'red';

    this.route = new Route(null, null, [], this.map, this, this.interactive);
    this.route.addTo(this.map);

    this.airports = {};
    this.zones = [];

    this.disabled = false;

    this.startAirportChange = null;
    this.endAirportChange = null;

    let t = this;

    this.helpModal = {
        content: '<ol class="h6">' +
        '<li class="m-1">Kliknutím na značku na mapě vyberte startovní letiště</li>' +
        '<li class="m-1">Kliknutím na tlačítko <strong>+</strong> nebo na segment trasy přidejte body trasy</li>' +
        '<li class="m-1">Body trasy přesuňte dle potřeby</li>' +
        '<li class="m-1">Pokud chcete přistát na jiném letišti než na startovním, kliknětě pravým tlačítkem (na dotykové obrazovce dlouze přidržet) na toto letiště a vyberte <strong>Nastavit jako cíl</strong></li>' +
        '</ol>',

        closeTitle: 'Zavřít',
        zIndex: 10000,
        transitionDuration: 300,

        template: '{content}',
    };

    if (this.interactive) {
        this.helpButton = L.easyButton(
            'fa-question',
            function (btn, map) {
                t.map.openModal(t.helpModal);
            },
            'Nápověda'
        ).addTo(this.map);

        this.routeReverseButton = L.easyButton(
            'fa-exchange',
            function (btn, map) {
                t.route.reverse();
                t.map.fireEvent('click');
            },
            'Obrátit směr trasy'
        ).addTo(this.map);

        this.wayPointAddButton = L.easyButton(
            'fa-plus',
            function (btn, map) {
                t.route.addWayPoint(t.map.getCenter());
                t.map.fireEvent('click');
            },
            'Přidat bod'
        ).addTo(this.map);
    }
}

Map.prototype.addAirport = function (id, name, latlng) {
    let airport = new Airport(id, name, latlng, this, this.interactive);

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
    else if (options.type === 'poly') {
        zone = L.polygon(options.shape, {
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
        old: this.route.startAirport,
        new: airport
    };

    this.route.setStartAirport(airport);

    if (this.startAirportChange) {
        this.startAirportChange(event);
    }

    this.map.fireEvent('click');
};

Map.prototype.chooseEndAirport = function (airport) {
    if (typeof airport === 'number') {
        airport = this.airports[airport];
    }

    let event = {
        old: this.route.endAirport,
        new: airport
    };

    this.route.setEndAirport(airport);

    if (this.endAirportChange) {
        this.endAirportChange(event);
    }

    this.map.fireEvent('click');
};


//-------------
// Route
//-------------
Route = function (startAirport, endAirport, latlngs, map, map_wrapper, interactive) {
    let t = this;

    this.interactive = true;

    if (interactive === false) {
        this.interactive = false;
    }

    this.startAirport = startAirport;
    this.endAirport = endAirport;

    this.map = map;
    this.map_wrapper = map_wrapper
    this.wayPoints = [];

    this.line = L.polyline(latlngs, {
        color: '#0066a2',
        interactive: this.interactive,
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

Route.prototype.distance = function () {
    var points = this.getLatLngs();

    if (this.startAirport == null) {
        return -1;
    }

    points = _.concat(this.startAirport.getLatLng(), points, this.endAirport == null
        ? this.startAirport.getLatLng()
        : this.endAirport.getLatLng());
    var distance = 0;

    var point_count = points.length;
    for (var i = 0; i < point_count-1; i++) {
        distance += haversineDistance(points[i].lat, points[i].lng, points[i+1].lat, points[i+1].lng);
    }

    distance = parseInt(distance / 1000);
    return distance;
};

Route.prototype.setStartAirport = function (startAirport) {
    if (this.startAirport) {
        this.startAirport.setStart(false);
    }

    this.startAirport = startAirport;

    if (this.startAirport) {
        this.startAirport.setStart(true);
    }

    this.refreshLine();
};

Route.prototype.setEndAirport = function (endAirport) {
    if (this.endAirport) {
        this.endAirport.setEnd(false);
    }

    this.endAirport = endAirport;

    if (this.endAirport) {
        this.endAirport.setEnd(true);
    }

    if (!this.startAirport && this.endAirport) {
        this.setStartAirport(this.endAirport);
    }

    this.refreshLine();
};

Route.prototype.addWayPoint = function (latlng, index) {
    let t = this;

    if (typeof index === 'undefined') {
        index = this.wayPoints.length;
    }

    let wayPoint = new WayPoint(latlng, index + 1, this, this.interactive);

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

            if (clampX !== null) {
                containerPoint.x = clampX;
            }
            if (clampY !== null) {
                containerPoint.y = clampY;
            }
            this.setLatLng(t.map.containerPointToLatLng(containerPoint));
        }

        t.refreshLine();
    });

    wayPoint.marker.on('dragend', function (event) {
        t.map.fireEvent('click');
    });

    this.wayPoints.splice(index, 0, wayPoint);

    if (this.map) {
        wayPoint.addTo(this.map);
    }

    this.refreshLine();
    this.refreshNumbers();
    t.map.fireEvent('click');
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
    this.map.fireEvent('click');
};

Route.prototype.refreshNumbers = function () {
    for (let i = 0; i < this.wayPoints.length; i++) {
        this.wayPoints[i].setNumber(i + 1);
    }
};

Route.prototype.refreshLine = function () {
    let latLngs = [];
    let startLatLng = [];

    if (this.startAirport) {
        startLatLng = this.startAirport.getLatLng();
        latLngs = _.concat(startLatLng, latLngs);
    }

    if (this.wayPoints.length) {
        let wayPointsLatLngs = this.wayPoints.map(function (point) {
            return point.marker.getLatLng();
        });

        latLngs = _.concat(latLngs, wayPointsLatLngs);
    }

    if (this.endAirport) {
        latLngs = _.concat(latLngs, this.endAirport.getLatLng());
    }
    else {
        latLngs = _.concat(latLngs, startLatLng);
    }

    if (latLngs.length < 2 ||
        (latLngs.length === 2 && latLngs[0] === latLngs[1])) {
        latLngs = [];
    }

    this.line.setLatLngs(latLngs);
};

Route.prototype.getLatLngs = function () {
    let latlngs = [];

    for (let i = 0; i < this.wayPoints.length; i++) {
        latlngs.push(this.wayPoints[i].marker.getLatLng());
    }

    return latlngs;
};

Route.prototype.getLatLngsFloat = function () {
    let latlngs = [];

    for (let i = 0; i < this.wayPoints.length; i++) {
        let wayPointLatLng = this.wayPoints[i].marker.getLatLng();
        latlngs.push([wayPointLatLng['lat'].toFixed(6), wayPointLatLng['lng'].toFixed(6)]);
    }

    return latlngs;
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

    let startAirport = this.startAirport;
    let endAirport = this.endAirport;

    if (startAirport && endAirport) {
        this.setEndAirport(startAirport);
        this.setStartAirport(endAirport);
    }
    else if (endAirport) {
        this.setStartAirport(endAirport);
        this.setEndAirport(null);
    }

    this.refreshNumbers();

    if (this.map_wrapper.startAirportChange) {
        let event = {
            old: startAirport,
            new: endAirport
        };
        this.map_wrapper.startAirportChange(event);
    }
    if (this.map_wrapper.endAirportChange) {
        let event = {
            old: endAirport,
            new: startAirport
        };
        this.map_wrapper.endAirportChange(event);
    }
};


//-------------
// WayPoint
//-------------
WayPoint = function (latlng, number, route, interactive) {
    this.route = route;
    let t = this;
    this.interactive = true;

    if (interactive === false) {
        this.interactive = false;
    }

    this.marker = L.marker(latlng, {
        icon: L.ExtraMarkers.icon({
            icon: 'fa-number',
            markerColor: 'blue',
            shape: 'circle'
        }),
        draggable: true,
        interactive: interactive,
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

WayPoint.prototype.bringToFront = function () {
    this.marker.setZIndexOffset(40000);
};
WayPoint.prototype.bringToBack = function () {
    this.marker.setZIndexOffset(30000);
};

WayPoint.prototype.setNumber = function (number) {
    let icon = L.ExtraMarkers.icon({
        icon: 'fa-number',
        markerColor: 'blue',
        shape: 'circle',
        number: number
    });

    this.marker.setIcon(icon);
};

WayPoint.prototype.addTo = function (map) {
    this.marker.addTo(map);
};

WayPoint.prototype.removeFrom = function (map) {
    this.marker.removeFrom(map);
};


//-------------
// Airport
//-------------
Airport = function (id, name, latlng, map, interactive) {
    this.id = id;
    this.name = name;
    this.start = false;
    this.end = false;
    this.map = map;
    this.interactive = true;

    if (interactive === false) {
        this.interactive = false;
    }

    let t = this;

    this.marker = L.marker(latlng, {
        icon: Airport.iconInactive,
        interactive: this.interactive,
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

        this.bringToFront();
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

        this.bringToFront();
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

        this.bringToBack();
    }
};

Airport.prototype.onclick = function (callback) {
    this.marker.on('click', L.bind(callback, this));
};

Airport.prototype.distanceTo = function (lat, lng) {
    var latLng = this.getLatLng();
    return haversineDistance(lat, lng, latLng.lat, latLng.lng);
};

exports.Map = Map;
exports.Airport = Airport;
exports.Route = Route;
exports.WayPoint = WayPoint;
exports.haversineDistance = haversineDistance;
