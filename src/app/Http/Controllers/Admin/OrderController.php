<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrderUpdateRequest;
use App\Order;

class OrderController extends AdminController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
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
		$order = Order::findOrFail($id);
		return view('admin.orders.show', compact('order'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$order = Order::findOrFail($id);
		return view('admin.orders.edit', compact('order'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  OrderUpdateRequest $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(OrderUpdateRequest $request, $id) {
		$order = Order::findOrFail($id);
		$order->admin_note = $request->input('admin_note');
		$order->save();

		return redirect()->route('admin.orders.index');
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
		// TODO: Send email
		return redirect()->route('admin.orders.index');
	}

	/**
	 * Confirms a given unconfirmed order
	 *
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function confirmOne($id) {
		$order = Order::findOrFail($id);

		if (!$order->isUnconfirmed()) {
			return redirect()
				->back(400)
				->withErrors('Order is already confirmed!');
		}

		return $this->confirm([$order]);
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
