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
        // для условной проверки факта присутствия штучного товара в справочнике
        $validator = parent::getValidatorInstance();
        $validator->sometimes('item', 'required|exists:items,id', function($input)
        {
            return $input->product === 10;
        });
        return $validator;
    }

    public function rules()
    {
        // key - идентификатор сессии. Обязателен всегда, но может быть еще не использован
        // при первом добавлении товара, при запросе цены и первоначальной загрузке корзины (пустой).
        // При удалении и обновлении элемента, и удалении всей корзины - должен уже
        // существовать на момент запроса.
        if (   $this->is('api/basket/update')
            || $this->is('api/basket/delete')
            || $this->is('api/basket/clear')) {
            // при обновлении строки или удалении строки/корзины последняя уже должна быть создана
            $rules = ['key' => 'required|string|exists:baskets,session_id'];
        } else {
            // сюда попадаем при: запросе корзины, добавлении новой строки, запросе цены
            // для добавления элемента корзина может еще не быть создана,
            // но уникальный номер сессии должен быть обязательно сгенерирован
            if ($this->is('api/basket')) {
                $rules = ['key' => 'required|string|min:30|max:60'];
            } else {
                // при добавлении в корзину/запросе цены обязательны параметры товара
                $rules = ['key' => 'required|string|min:30|max:60'] + $this->rulesAdd();
            }
        }
        if ($this->is('api/basket/delete') || $this->is('api/basket/update')) {
            // при удалении или обновлении строки обязателен номер сессии (уже есть в правилах)
            // и номер строки
            $rules += ['id' => 'required|integer|exists:basket_items'];
        }
        return $rules;
    }
}
