let map = null;

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

    $.get('ajax/aircrafts', {'airport_id': airport_id}, function (result) {
        aircraft_select.html('');
        aircraft_select.append($('<option>', {
            value: '',
            disabled: 'disabled',
            hidden: 'hidden',
            selected: 'selected'
        }));

        $.each(result, function (i, aircraft) {
            aircraft_select.append($('<option>', {
                value: aircraft.id,
                text: aircraft.name,
            }));
        });
    })
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

    $('#map').click(function (event) {
        switchToMap()
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


