@extends('layouts.base')

@section('body')
    <div class="layout fit-page">
        <div id="top-panel" class="top bg-warning closed">
            Top
        </div>

        <div class="middle">
            <div id="left-panel" class="left bg-primary">
                Left
            </div>
            <div class="center bg-secondary">
                Center
                <button href="#left-panel" class="toggle">L</button>
                <button href="#right-panel" class="toggle">R</button>
                <button href="#bottom-panel" class="toggle">B</button>
                <button href="#top-panel" class="toggle">T</button>
            </div>
            <div id="right-panel" class="right bg-primary closed">
                Right
            </div>
        </div>

        <div id="bottom-panel" class="bottom bg-success closed">
            Bottom
        </div>
    </div>

    {{--<div id="map-page-wrapper" class="w-100 h-100 d-flex flex-row flex-wrap">--}}
        {{--<div id="map-side-panel" class="d-none d-md-block">--}}
            {{--<h5 class="bg-secondary text-center text-light w-100 p-2">{{ __('Plánování vyhlídkového letu') }}</h5>--}}
        {{--</div>--}}
        {{--<div id="map-wrapper">--}}
            {{--<div id="map" class="w-100 h-100"></div>--}}

            {{--<div id="map-bottom-buttons" class="w-100 d-md-none">--}}
                {{--<div class="btn-group btn-group-lg d-flex flex-row p-2">--}}
                    {{--<button type="button" class="btn btn-primary"><i class="fa fa-road icon"></i> {{ __('Letiště') }}</button>--}}
                    {{--<button type="button" class="btn btn-primary"><i class="fa fa-plane"></i> {{ __('Letadlo') }}</button>--}}
                    {{--<button type="button" class="btn btn-primary"><i class="fa fa-map-marker"></i> {{ __('Trasa') }}</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div id="map-bottom-panel" class="">--}}
            {{--<div class="">--}}
                {{--Hello--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection