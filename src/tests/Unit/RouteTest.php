<?php

namespace Tests\Unit;

use App\Route;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RouteTest extends TestCase
{
	use DatabaseTransactions;

	/**
	 * Test saving of valid and invalid model
	 */
	public function testCreate() {
		$route = new Route();
		$this->assertFalse($route->save());

		$route = factory(\App\Route::class)->create();
		$this->assertTrue($route->save());
	}

	/**
	 * Test validation of distance attribute
	 */
	public function testDistanceValidation() {
		$route = factory(\App\Route::class)->create();
		$route->distance = null;
		$this->assertFalse($route->save());

		$route->distance = 'a';
		$this->assertFalse($route->save());

		$route->distance = 0.5;
		$this->assertFalse($route->save());

		$route->distance = - 3;
		$this->assertFalse($route->save());

		$route->distance = 50;
		$this->assertTrue($route->save());
	}

	/**
	 * Test validation of route attribute
	 */
	public function testRouteValidation() {
		$route = factory(\App\Route::class)->create();
		$route->route = null;
		$this->assertFalse($route->save());

		$route->route = 'a';
		$this->assertFalse($route->save());

		$route->route = [[1,2],[1,2]];
		$this->assertFalse($route->save());

		$route->route = "[]";
		$this->assertFalse($route->save());

		$route->route = "[[1,1],[1]]";
		$this->assertFalse($route->save());

		$route->route = "[[1,2],[1,2]]";
		$this->assertTrue($route->save());

		$route->route = "[[1.2,2.3],[89.9,179.9]]";
		$this->assertTrue($route->save());
	}

	/**
	 * Test relation between route and order models
	 */
	public function testRouteOrderRelation() {
		$route = factory(\App\Route::class)->create();
		$order  = $this->getValidOrder();
		$order2 = $this->getValidOrder();
		$this->assertEquals(0, $route->orders()->count());

		$order->saveOrFail();
		$route->orders()->save($order);
		$this->assertEquals(1, $route->orders()->count());
		$route->orders()->save($order2);
		$this->assertEquals(2, $route->orders()->count());
		$this->assertTrue($route->orders()->first()->is($order));
	}
}
