<?php

namespace Tests\Feature\Countries;

use App\Models\Country;
use Tests\TestCase;

class CountryIndexTest extends TestCase
{
	public function test_returns_countries()
    {
        $country = factory(Country::class)->create();

        $this->json('GET', 'api/countries')
            ->assertJsonFragment([
            	'id' => $country->id
            ]);
    }
}
