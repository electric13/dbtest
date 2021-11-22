<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasketItemRequest;
use App\Http\Resources\BasketItemCollection;
use App\Models\BasketItem;
use App\Models\Basket;

class APIBasketController extends Controller
{
    private function getBasket($session_id) {
        // заменить на findOrCreate!!!
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
            return new BasketItemCollection(
                BasketItem::where('basket_id', $b->id)->orderBy('id')->get());
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
        $basket_id = $this->getBasket($request->request->get('key'));
        $item = BasketItem::create(
            [
                'basket_id' => $basket_id,
                'material_id' => $request->request->getInt('material'),
                'product_id' => $request->request->getInt('product'),
                'item_id' => $request->request->getInt('item'),
                'length' => $request->request->getInt('length'),
                'amount' => $request->request->getInt('amount')
            ]);

        return new BasketItemCollection(
            BasketItem::where('basket_id', $basket_id)
                      ->where('id', $item->id)
                      ->get());
    }

    public function apiUpd(BasketItemRequest $request){
        //получаем идентификатор корзины из ключа
        $basket_id = $this->getBasket($request->request->get('key'));
        //получаем код строки для редактирования
        $id = $request->request->getInt('id');
        //обновляем строку в корзине, проверяя и ключ, и принадлежность к корзине,
        //чтобы нельзя было редактировать строки в чужих корзинах.
        //В дальнейшем надо прикрутить проверку связи корзины с пользователем

        //если вновь задано кол-во 0, то вызываем удаление элемента
        if ( $request->request->getInt('amount') == 0) {
            return $this->apiDel($request);
        }

        BasketItem::where('basket_id', $basket_id)
                  ->where('id', $id)
                  ->update(
            [   'material_id' => $request->request->getInt('material'),
                'product_id' => $request->request->getInt('product'),
                'item_id' => $request->request->getInt('item'),
                'length' => $request->request->getInt('length'),
                'amount' => $request->request->getInt('amount')
            ]);

        return new BasketItemCollection(
            BasketItem::where('basket_id', $basket_id)
                      ->where('id', $id)
                      ->orderBy('id')->get());
    }


    public function apiDel(BasketItemRequest $request){
        $b = Basket::where('session_id', $request->request->get('key'))->first();
        if (!$b) {
            return ['NoBasketYet' => true];
        }
        $basket_id = $b->id;
        BasketItem::where('basket_id', $basket_id)
                  ->where('id', $request->request->getInt('id'))
                  ->delete();
        return ['Deleted' => $request->request->getInt('id')];
    }
}
