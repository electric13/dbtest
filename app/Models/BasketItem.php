<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasketItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'basket_id', 'material_id','product_id', 'item_id', 'length', 'amount'
    ];

    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
