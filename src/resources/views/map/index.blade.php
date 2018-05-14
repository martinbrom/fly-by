@extends('layouts.app')

@section('body')
    <div id="map-page-wrapper" class="w-100 h-100 d-flex flex-column flex-md-row">
        <div id="map-wrapper" class="bg-secondary">
            <div id="map" class="w-100 h-100"></div>
            <div id="bottom-button-wrapper" class="w-100 p-3 btn-up d-md-none">
                <button id="bottom-button" class="w-100 btn btn-primary"><i class="fa fa-arrow-up"></i></button>
            </div>
        </div>
        <div id="map-control-panel" class="p-2">
            <ul class="nav nav-pills mb-3 nav-justified">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="pill" href="#aircraft-panel">{{ __('Výběr letadla') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#order-panel">{{ __('Objednávka') }}</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane show active" id="aircraft-panel">1</div>
                <div class="tab-pane" id="order-panel">3</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let zones = {!! json_encode($zones) !!};
    </script>

    <script src="{{ asset('js/map_page.js') }}"></script>
@endpush