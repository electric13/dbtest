<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BasketItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    private function rulesAdd(){
        return [
            'material' => 'required|integer|exists:materials,id',
            'product'  => 'required|integer|exists:products,id',
            'item' => 'required|integer|min:0|max:0',
            'length' => 'required|integer|min:200|max:10000',
            'amount' => 'required|integer|min:1|max:99000',
        ];
    }


    public function rules()
    {
        if ($this->is('api/basket/add')) {
            // для добавления элемента корзина может еще не быть создана,
            // но уникальный номер сессии должен быть обязательно сгенерирован
            $rules = $this->rulesAdd();
        } else {
            $rules = ['key' => 'required|string|exists:baskets,session_id'];
        }
        if ($this->is('api/basket/del')) {
            // при удалении обязателен номер сессии (уже есть в правилах) и номер строки
            $rules += [
                'id' => 'required|integer|exists:basket_items'
            ];
        }
        return $rules;
    }
}
