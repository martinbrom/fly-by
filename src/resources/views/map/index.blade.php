@extends('layouts.app')

@section('body')
    <div id="alert-container"></div>
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
            <div class="tab-content">
                <div class="tab-pane show active" id="aircraft-panel">
                    <h2 class="h4 bg-secondary text-center text-light w-100 p-2">{{ __('Výběr letadla') }}</h2>
                    <div id="airport-aircrafts"></div>
                    <button class="btn btn-primary"
                            id="display-other-aircrafts-btn">{{ __('Zobrazit letadla z ostatních letišť') }}</button>
                    <div id="other-aircrafts"></div>
                </div>
                <div class="tab-pane" id="order-panel">
                    <h2 class="h4 bg-secondary text-center text-light w-100 p-2">{{ __('Objednávka') }}</h2>
                    <div id="order-information" class="order-information">
                        <p class="order-distance"><b>Vzdálenost: </b><span class="value"></span> km</p>
                        <p class="order-duration"><b>Doba letu: </b><span class="value"></span> min</p>
                        <p class="order-flight-price"><b>Cena letu: </b> <span class="value"></span> Kč</p>
                        <p class="order-transport-price"><b>Cena přepravy: </b> <span class="value"></span> Kč</p>
                        <p class="order-total-price"><b>Celková cena: </b> <span class="value"></span> Kč</p>
                        <p class="order-aircraft"><b>Letadlo: </b> <span class="value"></span></p>
                        <p class="order-starting-airport"><b>Startovní letiště: </b> <span class="value"></span></p>
                        <p class="order-ending-airport"><b>Přistávací letiště: </b> <span class="value"></span></p>
                    </div>
                    <button id="calculate-order-variables" class="btn btn-primary">Vypočítat informace o letu</button>

                    <div id="order-form" class="order-form">
                        <form id="order-form-form" method="post" action="{{ route('orders.store') }}">
                            {{ csrf_field() }}

                            @include('components.form.input', ['type' => 'email', 'name' => 'email', 'label' => 'Emailová adresa'])
                            <div class="form-group">
                                <label class="control-label" for="user_note">Poznámka pro majitele</label>
                                <textarea class="form-control" rows="4" name="user_note"
                                          id="user_note"></textarea>
                            </div>

                            <button id="create-order" class="btn btn-primary">Vytvořit objednávku</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let zones = {!! json_encode($zones) !!};
    let csrf_token_value = "{!! csrf_token() !!}";
</script>

<script id="alert-template" type="text/x-custom-template">
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="message">Default message</span>
    </div>
</script>

<script id="aircraft-airport-template" type="text/x-custom-template">
    <div class="aircraft-panel-item">
        <div class="aircraft-panel-item-head">
            <img class="img img-responsive" src="images/default-aircraft-image.png">
        </div>
        <div class="aircraft-panel-item-body">
            <h4 class="aircraft-name">Letadlo</h4>
            <p class="airport-name">Letiště</p>
        </div>
    </div>
</script>

<script src="{{ asset('js/map_page.js') }}"></script>
@endpush