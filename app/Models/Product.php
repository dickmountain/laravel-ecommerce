<?php

namespace App\Models;

use App\Models\Traits\CanBeScoped;
use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
	use CanBeScoped, HasPrice;

	protected $fillable = [
		'name',
		'slug',
		'price',
		'description'
	];

	public function getRouteKeyName()
	{
		return 'slug';
    }

	public function isInStock()
	{
		return $this->getStockCount() > 0;
	}

	public function getStockCount()
	{
		return $this->variations->sum(function ($variation) {
			return $variation->getStockCount();
		});
	}

	public function categories()
	{
		return $this->belongsToMany(Category::class);
	}

	public function variations()
	{
		return $this->hasMany(ProductVariation::class)->orderBy('order', 'asc');
	}
}
