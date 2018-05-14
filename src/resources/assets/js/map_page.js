let map = null;
let selected_aircraft_airport = null;

function reloadAirports() {
    $.getJSON('ajax/airports', function (result) {
        $('#start_airport_id').html('');
        map.clearAirports();

        $('#start_airport_id').append($('<option>', {
            value: '',
            disabled: 'disabled',
            hidden: 'hidden',
            selected: 'selected'
        }));

        $.each(result, function (i, airport) {
            $('#start_airport_id').append($('<option>', {
                value: airport.id,
                text: airport.name
            }));
            map.addAirport(airport.id, airport.name, L.latLng(airport.lat, airport.lon));
        });
    });
}

function airportsInit() {
    $('#start_airport_id').change(function () {
        let id = parseInt($(this).val());
        let airport = map.airports[id.toString()];

        map.chooseStartAirport(airport);
        reloadAircrafts(airport.id);
    });

    reloadAirports();
}

function reloadAircrafts(airport_id) {
    if (airport_id === null || airport_id === 'undefined' || airport_id === 'false' || airport_id === '') {
        return;
    }

    $.get('ajax/aircrafts', {'airport_id': airport_id}, function (result) {
        refreshAircraftPanel();
        var airport_aircrafts = $('#airport-aircrafts');
        var template = $('#aircraft-airport-template').html();

        $.each(result, function (i, aa) {
            var item_template = $(template).clone();
            $(item_template).attr('data-aircraft-airport-id', aa.id);
            $(item_template).find('.aircraft-name').html(aa.aircraft.name);
            $(item_template).find('.airport-name').html('vybrané letiště');
            airport_aircrafts.append(item_template);
        });

        $('#display-other-aircrafts-btn').show();
    })
}

function refreshAircraftPanel() {
    $('#airport-aircrafts').html('');
    $('#display-other-aircrafts-btn').hide();
    $('#other-aircrafts').html('');
    $('#other-aircrafts').hide();
}

function reloadOtherAircrafts() {
    if (map.route.startAirport === null) {
        return;
    }

    $.get('ajax/other-aircrafts', {'airport_id': map.route.startAirport.id}, function (result) {
        var other_aircrafts = $('#other-aircrafts');
        var template = $('#aircraft-airport-template').html();

        $.each(result, function (i, aa) {
            var item_template = $(template).clone();
            $(item_template).attr('data-aircraft-airport-id', aa.id);
            $(item_template).find('.aircraft-name').html(aa.aircraft.name);
            $(item_template).find('.airport-name').html(aa.airport.name);
            other_aircrafts.append(item_template);
        });

        other_aircrafts.show();
        $('#display-other-aircrafts-btn').hide();
    })
}

function selectAircraftAirport(aircraft_airport_id) {
    $('.aircraft-panel-item').removeClass('selected');
    $('[data-aircraft-airport-id=' + aircraft_airport_id + ']').addClass('selected');
    map.map.fireEvent('click');
    $.get('ajax/aircraft-airports/' + aircraft_airport_id, function (result) {
        selected_aircraft_airport = result;
    });
}

function recalculateFlightVariables() {
    if (selected_aircraft_airport == null || map.route.startAirport == null || map.route.endAirport == null) {
        // TODO: Alerts
        return;
    }

    if (map.route.wayPoints.length == 0) {
        // TODO: Alerts
        return;
    }

    var aircraft = selected_aircraft_airport.aircraft;
    var airport  = selected_aircraft_airport.airport;

    var distance = map.route.distance();
    var duration = parseInt(60 * distance / aircraft.speed);
    var flight_price = parseInt(distance * aircraft.cost);

    if (distance > aircraft.range) {
        // TODO: Alerts
        return;
    }

    $('.order-distance .value').html(distance);
    $('.order-duration .value').html(duration);
    $('.order-flight-price .value').html(flight_price);

    var selected_airport_id = selected_aircraft_airport.airport_id;
    if (selected_airport_id == map.route.startAirport.id && selected_airport_id == map.route.endAirport.id) {
        $('.order-transport-price').hide();
        $('.order-total-price').hide();
    } else {
        var startAirport = map.route.startAirport.getLatLng();
        var endAirport   = map.route.endAirport.getLatLng();
        var airportCoordinates = airport;

        var distance_to_start = Flb.haversineDistance(
            startAirport.lat,
            startAirport.lng,
            airportCoordinates.lat,
            airportCoordinates.lon
        );

        var distance_from_end = Flb.haversineDistance(
            endAirport.lat,
            endAirport.lng,
            airportCoordinates.lat,
            airportCoordinates.lon
        );

        var transport_price = parseInt(parseInt((distance_to_start + distance_from_end) / 1000) * aircraft.cost);
        $('.order-transport-price .value').html(transport_price);
        $('.order-total-price .value').html((flight_price + transport_price));
    }

    $('.order-aircraft .value').html(aircraft.name);
    $('.order-starting-airport .value').html(map.route.startAirport.name);
    $('.order-ending-airport .value').html(map.route.endAirport.name);

    $('#order-form').show();
    $('#order-information').show();
}

function invalidateFlightVariables() {
    $('#order-form').hide();
    $('#order-information').hide();
}

function sendOrderForm() {
    var form = $('#order-form-form');
    $.post('orders', {
        '_token': csrf_token_value,
        'user_note': form.find('#user_note').val(),
        'email': form.find('#email').val(),
        'route': JSON.stringify(map.route.getLatLngsFloat()),
        'airport_from_id': map.route.startAirport.id,
        'airport_to_id': map.route.endAirport == null ? map.route.startAirport.id : map.route.endAirport.id,
        'aircraft_airport_id': selected_aircraft_airport.id
    }, function (result) {
        window.location.href = result;
    }).fail(function (result) {
        console.log(result.responseText);
        // TODO: Alerts
    });
}

$(document).ready(function () {
    window.map = map = new Flb.Map('map');

    for (let i = 0; i < zones.length; i++) {
        map.addZone(zones[i]);
    }

    map.onStartAirportChange(function (event) {
        let id = event.new ? event.new.id : '';

        $('#start_airport_id').val(id);
        reloadAircrafts(id);
        selected_aircraft_airport = null;
    });

    airportsInit();

    function switchToMap() {
        $('#map-control-panel').hide();
        $('#bottom-button').html('<i class="fa fa-arrow-up"></i>');
        map.map.invalidateSize();
    }

    function switchToPanel() {
        $('#map-control-panel').show();
        $('#bottom-button').html('<i class="fa fa-arrow-down"></i>');
        map.map.invalidateSize();
    }

    $('#bottom-button').click(function (event) {
        if ($("#map-control-panel").is(':hidden')) {
            switchToPanel();
        }
        else {
            switchToMap();
        }
    });

    $('#display-other-aircrafts-btn').click(function (event) {
       reloadOtherAircrafts();
    });

    $(document).on('click', '.aircraft-panel-item', function (event) {
        selectAircraftAirport($(this).attr('data-aircraft-airport-id'));
    });

    $('#calculate-order-variables').click(function (event) {
        recalculateFlightVariables();
    });

    $('#map').click(function (event) {
        switchToMap()
    });

    $('#create-order').click(function (event) {
        event.preventDefault();
        sendOrderForm();
    });

    map.map.on('click', function () {
        invalidateFlightVariables();
    });

    let routeForm = $('#route-form');
    let endAirportSelectGroup = routeForm.find('#end_airport_id-form-group');
    let differentAirportsCheckbox = routeForm.find('#different_airports');

    differentAirportsCheckbox.change(function () {
        if (this.checked) {
            endAirportSelectGroup.slideDown();
            endAirportSelectGroup.prop('disabled', false);
        }
        else {
            endAirportSelectGroup.slideUp();
            endAirportSelectGroup.prop('disabled', true);
        }
    });
});


