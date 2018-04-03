<h1>Display a specific order</h1>

<p class="order-code">{{ $order->code }}</p>
<p class="order-email">{{ $order->email }}</p>
<p class="order-confirmed-state">{{ $order->confirmed_at ?: 'not confirmed yet' }}</p>

<!-- TODO: Form with POST -->
<button class="order-confirm">Confirm order</button>