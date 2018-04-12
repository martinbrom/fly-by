<?php

namespace App\Http\Controllers;

class AdminController extends LoggedOnlyController
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
