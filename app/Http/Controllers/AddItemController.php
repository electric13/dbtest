<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\BasketItem;
use Illuminate\Http\Request;

class AddItemController extends Controller
{
    public function add(Request $request){
        $bsk_coll = Basket::where('session_id', $request->session()->getId());
        if (!isset($bsk_coll->first()->id)) {
            $new_basket = Basket::create(['session_id' => $request->session()->getId()]);
            $bsk_id = $new_basket->id;
        } else {
            $bsk_id = $bsk_coll->first()->id;
        }
        BasketItem::create(
            [
                'basket_id' => $bsk_id,
                'material_id' => random_int(1,6),
                'product_id' => 1,
                'item_id' => 0,
                'length' => random_int(10,50) * 100,
                'amount' => random_int(1,10)
            ]
        );
    }

    public function clear(Request $request){
        $bsk_coll = Basket::where('session_id', $request->session()->getId());
        if ( isset($bsk_coll->first()->id)) {
            $bsk_id = $bsk_coll->first()->id;
            BasketItem::where('basket_id', $bsk_id)->delete();
            Basket::destroy($bsk_id);
        }
    }
}
