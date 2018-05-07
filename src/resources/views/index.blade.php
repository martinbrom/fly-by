@extends('layouts.app')

@section('body')
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-wrapper">
                <i class="fa fa-4x fa-plane"></i>
                <h1 id="company-name">Fly-By</h1>
                <h2 id="company-slogan">Plánování vyhlídkových letů</h2>
                <a id="how-does-it-work-scroll-link" class="scroll-link" href="#" data-scroll-to="1">Jak to funguje?</a>
            </div>
        </div>
        <div class="scroll-down-container">
            <a class="scroll-link" data-scroll-to="1"><i class="fa fa-2x fa-chevron-down"></i></a>
        </div>
    </section>

    <section class="hero-section white">
        <div class="hero-container">
            <div class="hero-wrapper">
                <h2 class="how-does-it-work">Jak to funguje?</h2>
                <div class="how-does-it-work-container row">
                    <div class="wrapper col-6 col-md-3">
                        <div class="item">
                            <i class="fa fa-map-marker"></i>
                            <h3 class="d-none d-sm-block">1.</h3>
                            <p><b>Vytvořte objednávku kudykoli se Vám zachce</b></p>
                        </div>
                    </div>
                    <div class="wrapper col-6 col-md-3">
                        <div class="item">
                            <i class="fa fa-check"></i>
                            <h3 class="d-none d-sm-block">2.</h3>
                            <p><b>Vyčkejte na schválení Vaší objednávky</b></p>
                        </div>
                    </div>
                    <div class="wrapper col-6 col-md-3">
                        <div class="item">
                            <i class="fa fa-calendar"></i>
                            <h3 class="d-none d-sm-block">3.</h3>
                            <p><b>Domluvte si po telefonu termín letu</b></p>
                        </div>
                    </div>
                    <div class="wrapper col-6 col-md-3">
                        <div class="item">
                            <i class="fa fa-plane"></i>
                            <h3 class="d-none d-sm-block">4.</h3>
                            <p><b>Vychutnejte si Váš let</b></p>
                        </div>
                    </div>
                </div>
                <a id="map-link" href="{{ route('map') }}">Naplánujte si vlastní let</a>
            </div>
        </div>
        <div class="scroll-down-container">
            <a class="scroll-link dark" data-scroll-to="2"><i class="fa fa-2x fa-chevron-down"></i></a>
        </div>
    </section>

    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-wrapper">
                <h2 id="where-can-you-find-us">Kde nás najdete?</h2>
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
                <h2 class="contacts">Máte dotaz?</h2>
                <p id="contact-us-email-address">Kontaktujte nás na:
                    <a href="mailto:test{{ '@' }}test.cz">test{{ '@' }}test.cz</a>
                </p>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/landing.js') }}"></script>
@endpush
