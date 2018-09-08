<?php

namespace App\Scoping\Scopes;

use App\Scoping\Contracts\ScopeContract;
use Illuminate\Database\Eloquent\Builder;

class CategoryScope implements ScopeContract
{
	public function apply(Builder $builder, string $value)
	{
		return $builder->whereHas('categories', function (Builder $builder) use($value) {
			$builder->where('slug', $value);
		});
	}
}