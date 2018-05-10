<?php

namespace App\Http\Controllers\Admin;

use App\Airport;
use App\Http\Controllers\AdminController;
use App\Http\Requests\RouteStoreRequest;
use App\Http\Requests\RouteUpdateRequest;
use App\Route;

class RouteController extends AdminController
{
	/**
	 * Display a listing of predefined routes.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$routes = Route::predefined()->with('airportFrom', 'airportTo')->get();
		$state = 'predefined';
		return view('admin.routes.index', compact('routes', 'state'));
	}

	/**
	 * Display a listing of common routes.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function indexCommon() {
		$routes = Route::common()->with('airportFrom', 'airportTo')->get();
		$state = 'common';
		return view('admin.routes.index', compact('routes', 'state'));
	}

	/**
	 * Display the specified predefined route.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$route = Route::predefined()->findOrFail($id);
		$state = 'predefined';
		$airports = Airport::all();
		return view('admin.routes.show', compact('route', 'state', 'airports'));
	}

	/**
	 * Display the specified common route.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function showCommon($id) {
		$route = Route::common()->findOrFail($id);
		$state = 'common';
		$airports = Airport::all();
		return view('admin.routes.show', compact('route', 'state', 'airports'));
	}

	/**
	 * Show the form for editing the specified predefined route.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$route = Route::predefined()->findOrFail($id);
		$airports = Airport::all();
		return view('admin.routes.edit', compact('route', 'airports'));
	}

	/**
	 * Update the specified predefined route in storage.
	 *
	 * @param RouteUpdateRequest $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(RouteUpdateRequest $request, $id) {
		$route = Route::predefined()->findOrFail($id);
		$route->route = $request->input('route');
		$route->airport_from_id = $request->input('airport_from_id');
		$route->airport_to_id   = $request->input('airport_to_id');
		$route->saveOrFail();

		return redirect()->route('admin.routes.show', $id);
	}

	/**
	 * Show the form for creating a new predefined route.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$airports = Airport::all();
		return view('admin.routes.create', compact('airports'));
	}

	/**
	 * Store a newly created predefined route in storage.
	 *
	 * @param RouteStoreRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(RouteStoreRequest $request) {
		$route = new Route([
			'is_predefined' => $request->input('is_predefined'),
			'airport_from_id' => $request->input('airport_from_id'),
			'airport_to_id' => $request->input('airport_to_id'),
			'route' => $request->input('route')
		]);
		$route->saveOrFail();

		return redirect()->route('admin.routes.show', $route->id);
	}

	/**
	 * Remove the predefined state from specified predefined route.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$route = Route::predefined()->findOrFail($id);
		$route->is_predefined = 0;
		$route->save();

		return redirect()->route('admin.routes.index');
	}
}
