<?php

namespace App\Http\Controllers;

class AdminController extends LoggedOnlyController
{
	/**
	 *
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return view('admin.index');
	}
}
