@extends('layouts.admin')

@section('content')

    <h1 class="text-center">Vytvořit předdefinovanou trasu</h1>
    <div class="col-md-10 offset-md-1">
        <div id="route-map" class="route-map" style="height: 420px;"></div>
        <div class="pull-right">
            <button id="save" type="button" class="btn btn-primary m-2">{{ __('Vytvořit') }}</button>
        </div>
    </div>

    <form id='route-form' class="d-none" method="post" action="{{ route('admin.routes.store') }}">
        {{ csrf_field() }}
        {{ $errors }}

        @include('components.form.input', ['name' => 'route', 'value' => old('route')])
        @include('components.form.input', ['name' => 'is_predefined', 'value' => '1'])
        @include('components.form.input', ['name' => 'airport_from_id', 'value' => old('airport_from_id')])
        @include('components.form.input', ['name' => 'airport_to_id', 'value' => old('airport_to_id')])

    </form>

@endsection

@push('scripts')
<script>
    let route = $('route').val();
    route = route ? JSON.parse(route) : [];

    let startAirport = $('airport_from_id').val();
    startAirport = startAirport ? startAirport : null;

    let endAirport = $('airport_to_id').val();
    endAirport = endAirport ? endAirport : null;

    let airports = {!! json_encode($airports) !!};

    $(document).ready(function () {
        let map = window.mm = new Flb.Map('route-map');

        for (let i = 0; i < airports.length; i++) {
            map.addAirport(airports[i].id, airports[i].name, new L.LatLng(airports[i].lat, airports[i].lon));
        }

        map.chooseStartAirport(startAirport);
        map.chooseEndAirport(endAirport);

        let points = route;

        for (let i = 0; i < points.length; i++) {
            map.route.addWayPoint(points[i]);
        }

        if (route.length) {
            map.map.fitBounds(map.route.line.getBounds(), {padding: [20, 20]});
        }

        $('#save').click(function (e) {
            e.preventDefault();
            let start = map.route.startAirport;
            let end = map.route.endAirport;
            let route = JSON.stringify(map.route.getLatLngs());

            if (!start) {
                alert('Nebylo vybráno startovní letište');
                return;
            }
            if (!end) {
                end = start;
            }
            if (!route.length) {
                alert('Nebyl vybrán žádný bod trasy');
                return;
            }

            let form = $('#route-form');

            form.find('#route').val(route);
            form.find('#airport_from_id').val(start.id);
            form.find('#airport_to_id').val(end.id);

            form.submit();
        });
    });
</script>

@endpush