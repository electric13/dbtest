<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasketItem extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            'material' => $this->material_id,
            'product' => $this->product_id,
            'item' => $this->item_id,
            'length' => $this->length,
            'amount' => $this->amount,
            'price' => $this->product_id === 10 ? 125 : (125 * $this->length / 1000)
        ];
    }
}
