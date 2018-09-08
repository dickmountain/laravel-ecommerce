<?php

namespace App\Scoping\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface ScopeContract
{
	public function apply(Builder $builder, string $value);
}