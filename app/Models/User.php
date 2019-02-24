<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'gateway_customer_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	public static function boot()
	{
		parent::boot();

		static::creating(function ($user) {
			$user->password = bcrypt($user->password);
		});
    }

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier()
	{
		return $this->id;
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}

	public function cart()
	{
		return $this->belongsToMany(ProductVariation::class, 'cart_user')
			->withPivot('quantity')
			->withTimestamps();
	}

	public function addresses()
	{
		return $this->hasMany(Address::class);
	}

	public function paymentMethods()
	{
		return $this->hasMany(PaymentMethod::class);
	}

	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}
