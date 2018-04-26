<?php

namespace App\Http\Controllers\Admin;

use App\Order;

class OrderController extends AdminController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		// TODO: Pagination
		$orders = Order::unconfirmed()->get();
		return view('admin.orders.index', compact('orders'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$order = Order::unconfirmed()->findOrFail($id);
		return view('admin.orders.show', compact('order'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$order = Order::findOrFail($id);
		$order->delete();
		return redirect()->route('admin.orders.index');
	}

	/**
	 * Confirms a given unconfirmed order
	 *
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function confirmOne($id) {
		$orders = [Order::unconfirmed()->findOrFail($id)];
		return $this->confirm($orders);
	}

	/**
	 * Confirms all currently unconfirmed orders
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function confirmAll() {
		$orders = Order::unconfirmed()->get();
		return $this->confirm($orders);
	}

	/**
	 * Confirms all given orders and redirects back
	 * to the order overview
	 *
	 * @param $orders
	 * @return \Illuminate\Http\RedirectResponse
	 */
	private function confirm($orders) {
		foreach ($orders as $order) {
			$order->confirm();
		}

		return redirect()->route('admin.orders.index');
	}
}
