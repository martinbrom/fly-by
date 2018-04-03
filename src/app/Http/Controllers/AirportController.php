<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Http\Requests\AirportStoreRequest;
use App\Http\Requests\AirportUpdateRequest;

class AirportController extends LoggedOnlyController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // TODO: Pagination
        $airports = Airport::all();
        return response()->view('admin.airports.index', compact('airports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return response()->view('admin.airports.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AirportStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AirportStoreRequest $request) {
        $airport = new Airport($request->all());
        $airport->save();
        return redirect()->route('admin.airports.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $airport = Airport::findOrFail($id);
        $aircrafts = $airport->aircrafts()->get();
        return response()->view('admin.airports.show', compact('airport', 'aircrafts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $airport = Airport::findOrFail($id);
        return response()->view('admin.airports.edit', compact('airport'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AirportUpdateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AirportUpdateRequest $request, $id) {
        $airport = Airport::findOrFail($id);
        $airport->fill($request->all());
        $airport->save();

        // TODO: Decide where to redirect after updating airport
        return redirect()->route('admin.airports.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $airport = Airport::findOrFail($id);
        $airport->delete();
        return redirect()->route('admin.airports.index');
    }
}
