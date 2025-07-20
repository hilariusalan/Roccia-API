<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required'],
            'shipping_address_id' => ['required'],
            'billing_address.first_name' => ['required', 'max:100'],
            'billing_address.last_name' => ['required', 'max:100'],
            'billing_address.address' => ['required', 'max:200'],
            'billing_address.appartment_suite' => ['nullable', 'max:200'],
            'billing_address.city' => ['required', 'max:100'],
            'billing_address.province' => ['required', 'max:100'],
            'billing_address.postal_code' => ['required', 'max:100'],
            'billing_address.country' => ['required', 'max:100'],
            'shipping_method_id' => ['required'],
            'total_price' => ['required'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_variant_id' => ['required', 'exist:product,id'],
            'items.*.quantity' => ['required', 'min:1'],
            'items.*.total_price' => ['required', 'numeric']
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ]));
    }
}
