<?php

namespace App\Http\Controllers;

use App\Models\OrderLine;
use App\Models\Basket;
use App\Models\BasketItem;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function order(Request $request, $id) {

        $ols = OrderLine::where('order_id', (int)$id)->get();
        return view('welcome', [ 'ols' => $ols ]);
    }

    public function showBasket(Request $request){

        $bsk_coll = Basket::where('session_id', $request->session()->getId());
        $basket_empty = false;
        if (!isset($bsk_coll->first()->id)) {
            $basket_empty = true;
            $bi = null;
        } else {
            $bi = BasketItem::where(
                'basket_id',  $bsk_coll->first()->id)->get();
            if ( !isset($bi->first()->id)) {
                $basket_empty = true;
            }
        }
        return view('basket', [ 'empty' => $basket_empty, 'basket' => $bi]);
    }
}
