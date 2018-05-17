<?php

namespace App\Http\Controllers\Common;

use App\AircraftAirport;
use App\Airport;
use App\Http\Controllers\CommonController;
use App\Http\Requests\OrderStoreRequest;
use App\Order;
use App\Route;
use DB;

/**
 * Class OrderController
 *
 * @package App\Http\Controllers\Common
 * @author  Martin Brom
 */
class OrderController extends CommonController
{
    /**
     * Display the specified resource.
     *
     * @param string $code
     *
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        $order    = Order::with(['route', 'aircraftAirport'])->where('code', '=', $code)->firstOrFail();
        $airports = Airport::all();

        return response()->view('common.orders.show', compact('order', 'airports'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderStoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(OrderStoreRequest $request)
    {
        DB::beginTransaction();
        $route = new Route(
            [
            'route' => $request->input('route'),
            'airport_from_id' => $request->input('airport_from_id'),
            'airport_to_id' => $request->input('airport_to_id'),
            ]
        );
        $route->saveOrFail();

        $aircraftAirport = AircraftAirport::find($request->input('aircraft_airport_id'));

        if (!$aircraftAirport->canFly($route->distance)) {
            DB::rollBack();

            return response()->json(['message' => 'Route is too long']);
        }

        $order = new Order(
            [
            'email' => $request->input('email'),
            'user_note' => $request->input('user_note'),
            ]
        );
        $order->route()->associate($route);
        $order->aircraftAirport()->associate($aircraftAirport);
        $order->saveOrFail();
        DB::commit();

        return response()->json(route('orders.show', $order->code));
    }

    /**
     * Downloads a order PDF coupon
     *
     * @param $code
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadCoupon($code)
    {
        $order = Order::where('code', '=', $code)->firstOrFail();

        return \PDF::loadView('pdf.coupon', compact('order'))->download('kupon.pdf');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function testCreate()
    {
        $id                = 3;
        $airport           = Airport::findOrFail($id);
        $aircraft_airports = AircraftAirport::where('airport_id', '=', $id)->with('aircraft')->get();

        return response()->view('common.orders.test-create', compact('airport', 'aircraft_airports'));
    }
}
