<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasketItemRequest;
use App\Http\Resources\BasketItemCollection;
use App\Models\BasketItem;
use App\Models\Basket;

class APIBasketController extends Controller
{
    private function getBasket($session_id) {
        $b = Basket::where('session_id', '=', $session_id)->first();
        if ( !$b) {
            $id = Basket::create(['session_id' => $session_id])->id;
        } else
            $id = $b->id;
        return $id;
    }

    public function apiIndex(BasketItemRequest $request){
        $session_id = $request->request->get('key');
        $b = Basket::where('session_id', '=', $session_id)->first();
        if (! $b) {
            return ['NoBasketYet' => true];
        } else {
            $bi = BasketItem::where('basket_id', $b->id)->get();
            return new BasketItemCollection($bi);
        }
    }

    public function apiClear(BasketItemRequest $request){
        $session_id = $request->request->get('key');
        $b = Basket::where('session_id', $session_id)->first();
        if (!$b) {
            return ['NoBasketYet' => true];
        } else {
            BasketItem::where('basket_id', $b->id)->delete();
            return ['data' => []];
        }
    }

    public function apiAdd(BasketItemRequest $request){
        $id = $this->getBasket($request->request->get('key'));
        BasketItem::create(
            [
                'basket_id' => $id,
                'material_id' => $request->request->getInt('material'),
                'product_id' => $request->request->getInt('product'),
                'item_id' => $request->request->getInt('item'),
                'length' => $request->request->getInt('length'),
                'amount' => $request->request->getInt('amount')
            ]);
        return new BasketItemCollection(
                      BasketItem::where('basket_id', $id)->get());
    }

    public function apiDel(BasketItemRequest $request){
        $id = $this->getBasket($request->request->get('key'));
        BasketItem::where('basket_id', $id)
                  ->where('id', $request->request->getInt('id'))
                  ->delete();
        return new BasketItemCollection(
            BasketItem::where('basket_id', $id)->get());
    }
}
