@extends('layouts.admin')

@section('content')

    <h1>Upravit obrázek letadla</h1>
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('admin.aircrafts.store-image', $aircraft->id) }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            @include('components.form.input', ['type' => 'file', 'name' => 'image', 'label' => 'Obrǎzek'] )
            @include('components.form.input', ['name' => 'description', 'label' => 'Popis obrǎzku'] )

            <input type="submit" value="Upravit" class="btn btn-primary">
        </form>

        <form method="post" action="{{ route('admin.aircrafts.default-image', $aircraft->id) }}">
            {{ csrf_field() }}

            <input type="submit" value="Nastavit základní obrázek" class="btn btn-primary">
        </form>
    </div>

@endsection