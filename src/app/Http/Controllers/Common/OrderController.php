<?php

namespace App\Http\Controllers\Common;

use App\AircraftAirport;
use App\Airport;
use App\Http\Requests\OrderStoreRequest;
use App\Order;
use App\Route;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends CommonController
{
	/**
	 * Display the specified resource.
	 *
	 * @param   string $code
	 * @return  \Illuminate\Http\Response
	 */
	public function show($code) {
	    $order = Order::with(['route', 'aircraftAirport'])->where('code', '=', $code)->first();

	    // TODO: Maybe request validation
	    if (empty($order)) {
	        throw new ModelNotFoundException();
	    }

	    return response()->view('common.orders.show', compact('order'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param OrderStoreRequest $request
	 * @return \Illuminate\Http\Response
	 * @throws \Exception
	 */
	public function store(OrderStoreRequest $request) {
		DB::beginTransaction();
		$route = new Route([
			'route' => $request->input('route'),
		]);
		$airportFrom = Airport::find($request->input('airport_from_id'));
		$airportTo = Airport::find($request->input('airport_to_id'));
		$route->airportFrom()->associate($airportFrom);
		$route->airportTo()->associate($airportTo);
		$route->saveOrFail();

		$aircraftAirport = AircraftAirport::find($request->input('aircraft_airport_id'));
		$aircraft = $aircraftAirport->aircraft;

		if (!$aircraft->canFly($route->distance)) {
			// TODO: Aircraft cannot fly the distance
			DB::rollBack();
			throw new \Exception($route->distance);
		}

		$order = new Order(['email' => $request->input('email')]);
		$order->route()->associate($route);
		$order->aircraftAirport()->associate($aircraftAirport);
		$order->saveOrFail();
		DB::commit();

		return redirect()->route('orders.test-create');
	}

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function testCreate() {
		$id = 3;
		$airport = Airport::findOrFail($id);
		$aircraft_airports = AircraftAirport::where('airport_id', '=', $id)->with('aircraft')->get();
	    return response()->view('common.orders.test-create', compact('airport', 'aircraft_airports'));
	}
}
