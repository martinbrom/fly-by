<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;

/**
 * Class StaticController
 * Contains functions to display all
 * application static pages such as
 * landing page or contacts page
 *
 * @package App\Http\Controllers
 */
class StaticController extends Controller
{
	public function index() {
		return view('index');
	}
}
