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
            'product'  => 'required|integer|exists:products,id',
            'material' => 'exclude_if:product,10|required|integer|exists:materials,id',
            'length' => 'exclude_if:product,10|required|integer|min:200|max:10000',
            'amount' => 'required|integer|min:1|max:99000',
        ];
    }

    protected function getValidatorInstance(){
        $validator = parent::getValidatorInstance();

        // required_if:product,10|integer|exists:items,id
        $validator->sometimes('item', 'required|exists:items,id', function($input)
        {
            return $input->product === 10;
        });

        return $validator;
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
