<?php

namespace App\Http\Controllers\Admin;

use App\Aircraft;
use App\AircraftAirport;
use App\Airport;
use App\Http\Controllers\AdminController;
use App\Http\Requests\AirportStoreRequest;
use App\Http\Requests\AirportUpdateRequest;

/**
 * Class AirportController
 *
 * @package App\Http\Controllers\Admin
 * @author  Martin Brom
 */
class AirportController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $airports = Airport::all();

        return response()->view('admin.airports.index', compact('airports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('admin.airports.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AirportStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AirportStoreRequest $request)
    {
        $airport = new Airport($request->all());
        $airport->save();

        return redirect()->route('admin.airports.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $airport           = Airport::findOrFail($id);
        $airports          = Airport::allOther($id)->get();
        $aircraft_airports = AircraftAirport::where('airport_id', '=', $id)->with('aircraft')->get();

        return response()->view('admin.airports.show', compact('airport', 'airports', 'aircraft_airports'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $airport = Airport::findOrFail($id);

        return response()->view('admin.airports.edit', compact('airport'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AirportUpdateRequest $request
     * @param int                  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(AirportUpdateRequest $request, $id)
    {
        $airport = Airport::findOrFail($id);
        $airport->fill($request->all());
        $airport->save();

        return redirect()->route('admin.airports.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $airport = Airport::findOrFail($id);
        $airport->delete();

        return redirect()->route('admin.airports.index');
    }

    /**
     * Show the form to add an aircraft to a given airport.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function addAircraft($id)
    {
        $airport   = Airport::findOrFail($id);
        $aircrafts = Aircraft::all();

        return response()->view('admin.airports.add-aircraft', compact('airport', 'aircrafts'));
    }
}
