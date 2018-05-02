@extends('layouts.app')

@section('body')
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-wrapper">
                <i class="fa fa-4x fa-plane"></i>
                <h1 id="company-name">Fly-By</h1>
                <h2 id="company-slogan">User friendly planning of scenic flights</h2>
                <a id="how-does-it-work-scroll-link" class="scroll-link" href="#" data-scroll-to="1">How does it work?</a>
            </div>
        </div>
        <div class="scroll-down-container">
            <a class="scroll-link" data-scroll-to="1"><i class="fa fa-2x fa-chevron-down"></i></a>
        </div>
    </section>

    <section class="hero-section white">
        <div class="hero-container">
            <div class="hero-wrapper">
                <h2 class="how-does-it-work">How does it work?</h2>
                <div class="how-does-it-work-container row">
                    <div class="wrapper col-6 col-md-3">
                        <div class="item">
                            <i class="fa fa-map-marker"></i>
                            <h3 class="d-none d-sm-block">1.</h3>
                            <p><b>Create an order with your custom route</b></p>
                        </div>
                    </div>
                    <div class="wrapper col-6 col-md-3">
                        <div class="item">
                            <i class="fa fa-check"></i>
                            <h3 class="d-none d-sm-block">2.</h3>
                            <p><b>Wait for your order to get confirmed</b></p>
                        </div>
                    </div>
                    <div class="wrapper col-6 col-md-3">
                        <div class="item">
                            <i class="fa fa-calendar"></i>
                            <h3 class="d-none d-sm-block">3.</h3>
                            <p><b>Call and set a date</b></p>
                        </div>
                    </div>
                    <div class="wrapper col-6 col-md-3">
                        <div class="item">
                            <i class="fa fa-plane"></i>
                            <h3 class="d-none d-sm-block">4.</h3>
                            <p><b>Enjoy your flight</b></p>
                        </div>
                    </div>
                </div>
                <a id="map-link" href="{{ route('map') }}">Plan your own flight</a>
            </div>
        </div>
        <div class="scroll-down-container">
            <a class="scroll-link dark" data-scroll-to="2"><i class="fa fa-2x fa-chevron-down"></i></a>
        </div>
    </section>

    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-wrapper">
                <h2 id="where-can-you-find-us">Where can you find us?</h2>
                <div id="airport-map"></div>
            </div>
        </div>
        <div class="scroll-down-container">
            <a class="scroll-link" data-scroll-to="3"><i class="fa fa-2x fa-chevron-down"></i></a>
        </div>
    </section>

    <section class="hero-section white">
        <div class="hero-container">
            <div class="hero-wrapper">
                <h2 class="contacts">Have a question?</h2>
                <p id="contact-us-email-address">Contact us at:
                    <a href="mailto:test{{ '@' }}test.cz">test{{ '@' }}test.cz</a>
                </p>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/landing.js') }}"></script>
@endpush
