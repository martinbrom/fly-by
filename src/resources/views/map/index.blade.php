@extends('layouts.app')

@section('body')
    <div id="map-page-wrapper" class="w-100 h-100 d-flex flex-column flex-md-row">
        <div id="map-wrapper" class="bg-secondary">
            <div id="map" class="w-100 h-100"></div>
            <div id="map-bottom-buttons" class="w-100 d-md-none">
                <div class="btn-group btn-group-lg d-flex flex-row m-2">
                    <button type="button" class="btn btn-primary"><i class="fa fa-road icon"></i> {{ __('Letiště') }}</button>
                    <button type="button" class="btn btn-primary"><i class="fa fa-plane"></i> {{ __('Letadlo') }}</button>
                    <button type="button" class="btn btn-primary"><i class="fa fa-map-marker"></i> {{ __('Trasa') }}</button>
                </div>
            </div>
        </div>
        <div id="map-control-panel" class="p-2">
            <h5 class="bg-secondary text-center text-light w-100 p-2">{{ __('Plánování vyhlídkového letu') }}</h5>

            <form id="route-form">
                <div class="form-group">
                    <label for="airport_id" class="control-label">Aircraft</label>
                    <select name="airport_id" id="airport_id" class="form-control">
                    </select>
                </div>

                <div class="form-group">
                    <label for="aircraft_id" class="control-label">Aircraft</label>
                    <select name="aircraft_id" id="aircraft_id" class="form-control">
                    </select>
                </div>
            </form>

            <button id="btn-add-waypoint" class="btn btn-success">{{ __('Přidat bod na trase') }}</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/map.js') }}"></script>
@endpush