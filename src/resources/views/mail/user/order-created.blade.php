@extends('layouts.email')

@section('content')

    <p>
        Vaše objednávka <b>#{{ $order->id }}</b> byla úspěšně vytvořena!
    </p>

    <p>
        Než budete moci vyrazit na svůj vysněný let, musí být vaše
        objednávka potvrzena jedním z našich pilotů.
    </p>

    <p>
        To by nemělo trvat dlouho, ale pokud si chcete zkrátit
        čas, můžete si prohlédnout detaily vaší objednávky
        <b><a href="{{ route('orders.show', $order->code) }}">zde</a></b>.
    </p>

@endsection