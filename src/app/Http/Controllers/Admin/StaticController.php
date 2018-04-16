<?php

namespace App\Http\Controllers\Admin;

class StaticController extends AdminController
{
	/**
	 * Display the administration homepage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return view('admin.index');
	}
}
