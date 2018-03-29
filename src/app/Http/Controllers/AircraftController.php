<?php

namespace App\Http\Controllers;

use App\Aircraft;
use App\Http\Requests\AircraftStoreRequest;
use App\Http\Requests\AircraftUpdateRequest;

class AircraftController extends LoggedOnlyController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // TODO: Pagination
        $aircrafts = Aircraft::all();
        return view('aircrafts.index', compact('aircrafts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('aircrafts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AircraftStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AircraftStoreRequest $request) {
        $aircraft = new Aircraft($request->all());
        $aircraft->save();
        return redirect()->route('aircrafts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $aircraft = Aircraft::findOrFail($id);
        return view('aircrafts.show', compact('aircraft'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $aircraft = Aircraft::findOrFail($id);
        return view('aircrafts.edit', compact('aircraft'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AircraftUpdateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AircraftUpdateRequest $request, $id) {
        $aircraft = Aircraft::findOrFail($id);
        $aircraft->fill($request->all());
        $aircraft->save();

        // TODO: Decide where to redirect after updating aircraft
        return redirect()->route('aircrafts.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $aircraft = Aircraft::findOrFail($id);
        $aircraft->delete();
        return redirect()->route('aircrafts.index');
    }
}
