@extends('layouts.base')

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
        <div id="map-control-panel" class="">
            <h5 class="bg-secondary text-center text-light w-100 p-2">{{ __('Plánování vyhlídkového letu') }}</h5>
            <button id="btn-add-waypoint" class="btn btn-success">{{ __('Přidat bod na trase') }}</button>
        </div>
    </div>
@endsection