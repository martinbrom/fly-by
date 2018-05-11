@extends('layouts.admin')

@section('content')

    <h1 class="text-center">Upravit objednávku</h1>
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('admin.orders.update', $order->id) }}">
            {{ csrf_field() }}
            {{ method_field('put') }}

            <div class="form-group">
                <label class="control-label" for="admin_note">Poznámka pro uživatele</label>
                <textarea class="form-control" rows="6" name="admin_note"
                          id="admin_note">{{ $order->admin_note }}</textarea>
            </div>

            <input type="submit" value="Upravit" class="btn btn-primary">
        </form>
    </div>

@endsection