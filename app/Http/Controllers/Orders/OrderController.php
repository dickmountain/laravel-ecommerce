<?php

namespace App\Http\Controllers\Orders;

use App\Http\Requests\Orders\OrderStoreRequest;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth:api']);
	}

	public function store(OrderStoreRequest $request)
	{

    }
}
