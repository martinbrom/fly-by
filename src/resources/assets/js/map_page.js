let map = null;
let selected_aircraft_airport = null;

function displayAlert(type, message) {
    var alert_container = $('#alert-container');
    var alert_template = $('#alert-template').html();

    var alert = $(alert_template).clone();
    $(alert).attr('class', 'alert alert-dismissible fade show alert-' + type);
    $(alert).find('.message').html(message);
    alert.delay(5000).hide(500);

    alert_container.append($(alert));
}

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
            $(item_template).find('.img').attr('src', 'storage/' + aa.aircraft.image.path).attr('alt', aa.aircraft.image.description);
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
            $(item_template).find('.img').attr('src', 'storage/' + aa.aircraft.image.path).attr('alt', aa.aircraft.image.description);
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
    if (selected_aircraft_airport == null) {
        displayAlert('danger', 'Nebylo vybráno letadlo');
        return;
    }

    if (map.route.startAirport == null) {
        displayAlert('danger', 'Nebylo vybráno startovní letiště');
        return;
    }

    if (map.route.wayPoints.length == 0) {
        displayAlert('danger', 'Nebyl vybrán žádný bod trasy');
        return;
    }

    var aircraft = selected_aircraft_airport.aircraft;
    var airport  = selected_aircraft_airport.airport;

    var distance = map.route.distance();
    var duration = parseInt(60 * distance / aircraft.speed);
    var flight_price = parseInt(distance * aircraft.cost);

    if (distance > aircraft.range) {
        displayAlert('danger', 'Trasa je delší než dolet letadla');
        return;
    }

    $('.order-distance .value').html(distance);
    $('.order-duration .value').html(duration);
    $('.order-flight-price .value').html(flight_price);

    var selected_airport_id = selected_aircraft_airport.airport_id;
    var start_airport_id = map.route.startAirport.id;
    var end_airport_id   = map.route.endAirport == null ? map.route.startAirport.id : map.route.endAirport.id;
    if (selected_airport_id == start_airport_id && selected_airport_id == end_airport_id) {
        $('.order-transport-price').hide();
        $('.order-total-price').hide();
    } else {
        var startAirport = map.route.startAirport.getLatLng();
        var endAirport   = map.route.endAirport == null
            ? map.route.startAirport.getLatLng()
            : map.route.endAirport.getLatLng();
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
    $('.order-ending-airport .value').html(map.route.endAirport == null ? map.route.startAirport.name : map.route.endAirport.name);

    $('#order-form').show();
    $('#order-information').show();
}

function invalidateFlightVariables() {
    $('#order-form').hide();
    $('#order-information').hide();
}

function sendOrderForm() {
    var form = $('#order-form-form');
    $.ajax({
        url: 'orders',
        method: 'post',
        data: {
            '_token': csrf_token_value,
            'user_note': form.find('#user_note').val(),
            'email': form.find('#email').val(),
            'route': JSON.stringify(map.route.getLatLngsFloat()),
            'airport_from_id': map.route.startAirport.id,
            'airport_to_id': map.route.endAirport == null ? map.route.startAirport.id : map.route.endAirport.id,
            'aircraft_airport_id': selected_aircraft_airport.id
        }
    }).done(function (result) {
        window.location.href = result;
    }).fail(function (result) {
        var error_groups = JSON.parse(result.responseText).errors;
        for (var k in error_groups) {
            if (error_groups.hasOwnProperty(k)) {
                var errors = error_groups[k];
                var error_count = errors.length;
                for (var i = 0; i < error_count; i++) {
                    displayAlert('danger', errors[i]);
                }
            }
        }
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
});


