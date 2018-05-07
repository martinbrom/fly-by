<?php

namespace App\Http\Controllers\Admin;

use App\Aircraft;
use App\AircraftImage;
use App\Http\Controllers\AdminController;
use App\Http\Requests\AircraftImageStoreRequest;
use App\Http\Requests\AircraftStoreRequest;
use App\Http\Requests\AircraftUpdateRequest;

class AircraftController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $aircrafts = Aircraft::all();
        return view('admin.aircrafts.index', compact('aircrafts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.aircrafts.create');
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
        return redirect()->route('admin.aircrafts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $aircraft = Aircraft::findOrFail($id);
        $image = $aircraft->image()->first();
        return view('admin.aircrafts.show', compact('aircraft', 'image'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $aircraft = Aircraft::findOrFail($id);
        return view('admin.aircrafts.edit', compact('aircraft'));
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

        return redirect()->route('admin.aircrafts.show', $id);
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
        return redirect()->route('admin.aircrafts.index');
    }

	/**
	 * Show the form for editing aircraft image
	 *
	 * @param   int $id
	 * @return  \Illuminate\Http\Response
	 */
    public function editImage($id) {
    	$aircraft = Aircraft::findOrFail($id);
    	$image = $aircraft->image()->first();
    	return view('admin.aircrafts.edit-image', compact('aircraft', 'image'));
    }

	/**
	 * Store a new aircraft image
	 *
	 * @param   AircraftImageStoreRequest $request
	 * @param   int $id
	 * @return  \Illuminate\Http\RedirectResponse
	 */
    public function storeImage(AircraftImageStoreRequest $request, $id) {
    	$aircraft = Aircraft::findOrFail($id);
	    $image = new AircraftImage();

	    if (!$image->saveFromRequest($request)) {
		    return redirect()
			    ->back()
			    ->withErrors('Ukládání obrázku selhalo');
	    }

	    $aircraft->image()->associate($image);
	    $aircraft->save();
    	return redirect()->route('admin.aircrafts.show', $aircraft->id);
    }

	/**
	 * Set aircraft image to the default value (NULL)
	 *
	 * @param   int $id
	 * @return  \Illuminate\Http\Response
	 */
    public function defaultImage($id) {
    	$aircraft = Aircraft::findOrFail($id);
    	$aircraft->image()->dissociate();
    	$aircraft->save();
    	return redirect()->route('admin.aircrafts.show', $aircraft->id);
    }
}
