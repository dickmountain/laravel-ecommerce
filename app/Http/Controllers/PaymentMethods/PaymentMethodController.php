<?php

namespace App\Http\Controllers\PaymentMethods;

use App\Ecommerce\Payments\Gateway;
use App\Http\Requests\PaymentMethods\PaymentMethodStoreRequest;
use App\Http\Resources\PaymentMethodResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{
	protected $gateway;

	public function __construct(Gateway $gateway)
	{
		$this->middleware(['auth:api']);

		$this->gateway = $gateway;
	}

	public function index(Request $request)
	{
		return PaymentMethodResource::collection(
			$request->user()->paymentMethods
		);
    }

	public function store(PaymentMethodStoreRequest $request)
	{
		$card = $this->gateway->withUser($request->user())
			->createCustomer()
			->addCart($request->token);

		return new PaymentMethodResource($card);
    }
}
