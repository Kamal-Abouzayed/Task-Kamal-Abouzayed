<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class MasterApiRequest extends FormRequest
{

    public function expectsJson()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [

                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),

            ],
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        )); // 422
    }
}
