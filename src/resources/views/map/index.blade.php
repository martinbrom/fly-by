@extends('layouts.app')

@section('body')
    <div id="map-page-wrapper" class="w-100 h-100 d-flex flex-column flex-md-row">
        <div id="map-wrapper" class="bg-secondary">
            <div id="map" class="w-100 h-100"></div>
            <div id="map-bottom-buttons" class="w-100 d-md-none">
                <div class="btn-group btn-group-lg d-flex flex-row m-2">
                    <button type="button" class="btn btn-primary"><i class="fa fa-road icon"></i> {{ __('Letiště') }}
                    </button>
                    <button type="button" class="btn btn-primary"><i class="fa fa-plane"></i> {{ __('Letadlo') }}
                    </button>
                    <button type="button" class="btn btn-primary"><i class="fa fa-map-marker"></i> {{ __('Trasa') }}
                    </button>
                </div>
            </div>
        </div>
        <div id="map-control-panel" class="p-2">
            <h5 class="bg-secondary text-center text-light w-100 p-2">{{ __('Plánování vyhlídkového letu') }}</h5>

            <form id="route-form">

                <div class="form-group">
                    <label for="start_airport_id" class="control-label">{{ __('Startovní letiště') }}</label>
                    <select name="start_airport_id" id="start_airport_id" class="form-control">
                        <option disabled selected hidden value=""></option>
                    </select>
                </div>

                <div class="form-group form-check">
                    <input id="different_airports" type="checkbox" class="form-check-input" autocomplete="off">
                    <label for="different_airports"
                           class="form-check-label">{{ __('Přistání na jiném letišti') }}</label>
                </div>

                <div class="form-group" id="end_airport_id-form-group">
                    <label for="end_airport_id" class="control-label">{{ __('Cílové letiště') }}</label>
                    <select name="end_airport_id" id="end_airport_id" class="form-control">
                        <option disabled selected hidden value=""></option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="aircraft_id" class="control-label">{{ __('Letadlo') }}</label>
                    <select name="aircraft_id" id="aircraft_id" class="form-control">
                        <option disabled selected hidden value=""></option>
                    </select>
                </div>

            </form>

            <button id="btn-add-waypoint" class="btn btn-success">{{ __('Přidat bod na trase') }}</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let zones = {!! json_encode($zones) !!};
    </script>

    <script src="{{ asset('js/map_page.js') }}"></script>
@endpush