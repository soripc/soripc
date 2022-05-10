<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentLinkRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        
        return [
            'payment_link_type_id' => [
                'required',
            ],
            'payment_id' => [
                'required',
            ],
            'instance_type' => [
                'required',
            ],
            'total' => [
                'required',
                'gt:0',
            ],
        ];

    }

}