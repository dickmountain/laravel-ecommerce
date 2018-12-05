<?php

namespace App\Http\Controllers\Countries;

use App\Http\Resources\CountryResource;
use App\Http\Controllers\Controller;
use App\Models\Country;

class CountryController extends Controller
{
	public function index()
	{
		return CountryResource::collection(Country::get());
    }
}
