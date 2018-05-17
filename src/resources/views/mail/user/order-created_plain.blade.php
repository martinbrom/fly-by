Vaše objednávka #{{ $order->id }} byla úspěšně vytvořena!

Než budete moci vyrazit na svůj vysněný let, musí být vaše
objednávka potvrzena jedním z našich pilotů.

To by nemělo trvat dlouho, ale pokud si chcete zkrátit
čas, můžete si prohlédnout detaily vaší objednávky na {{ route('orders.show', $order->code) }}