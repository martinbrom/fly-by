@extends('layouts/admin')

@section('content')

    <h1>{{ ucfirst($state) }} orders</h1>

    @if($state == 'unconfirmed')
        <a href="#order-confirm-all-form" onclick="event.preventDefault(); document.getElementById('order-confirm-all-form').submit();">
            Confirm all orders</a>
        <form id="order-confirm-all-form"
              action="{{ route('admin.orders.confirm-all') }}" method="POST"
              style="display: none;">
            {{ csrf_field() }}
        </form>
    @endif

    @if(count($orders) > 0)
        <table class="table table-striped table-responsive-md w-100">
            <thead class="thead-dark w-100">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Code</th>
                <th scope="col">Email</th>
                <th scope="col">Display</th>
                @if($state == 'unconfirmed')<th scope="col">Confirm</th>@endif
                @if($state == 'uncompleted')<th scope="col">Complete</th>@endif
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td class="order-id">{{ $order->id }}</td>
                    <td class="order-code">{{ $order->code }}</td>
                    <td class="order-email">{{ $order->email }}</td>
                    <td class="order-show"><a href="{{ route('admin.orders.show', $order->id) }}">Display</a></td>
                    @if($state == 'unconfirmed')
                        <td class="order-confirm-one">
                            <a href="#order-confirm-one-form-{{ $order->id }}"
                                    onclick="event.preventDefault(); document.getElementById('order-confirm-one-form-{{ $order->id }}').submit();">
                                    Confirm</a>
                            <form id="order-confirm-one-form-{{ $order->id }}"
                                    action="{{ route('admin.orders.confirm-one', $order->id) }}" method="POST"
                                    style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </td>
                    @elseif($state == 'uncompleted')
                        <td class="order-complete">
                            <a href="#order-complete-form-{{ $order->id }}"
                               onclick="event.preventDefault(); document.getElementById('order-complete-form-{{ $order->id }}').submit();">
                                Complete</a>
                            <form id="order-complete-form-{{ $order->id }}"
                                  action="{{ route('admin.orders.complete', $order->id) }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif


@endsection
