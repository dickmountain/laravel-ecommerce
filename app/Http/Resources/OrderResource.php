<?php

namespace App\Http\Resources;

use App\Models\ProductVariation;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
        	'id' => $this->id,
	        'status' => $this->status,
	        'created_at' => $this->created_at->toDateTimeString(),
	        'subtotal' => $this->subtotal,
	        'products' => ProductVariationResource::collection(
	        	$this->whenLoaded('products')
	        ),
	        'shipping_method' => new ShippingMethodResource(
		        $this->whenLoaded('shipping_method')
	        )
        ];
    }
}
