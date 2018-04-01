<h1>Orders</h1>

<button class="order-confirm-all">Confirm all orders</button>

@foreach($orders as $order)
    <div class="order">
        <p class="order-code">{{ $order->code }}</p>
        <p class="order-email">{{ $order->email }}</p>
        <p class="order-confirmed-state">{{ $order->confirmed_at }}</p>
        <button class="order-confirm">Confirm order</button>
    </div>
@endforeach