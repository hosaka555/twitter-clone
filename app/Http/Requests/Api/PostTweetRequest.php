<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostTweetRequest extends FormRequest
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
    public function rules()
    {
        return [
            "message" => "required | max:140",
            "images" => [
                "array",
                function ($attribute, $images, $fail) {
                    if (count($images) > 4) {
                        return $fail("投稿できる画像は4個までです");
                    }
                }
            ],
            "images.*" => "mimes:jpg,jpeg,png"
        ];
    }

    /**
     * [override] バリデーション失敗時ハンドリング
     * @param Validator $validator
     * @throw HttpResponseException
     * @see FormRequest::failedValidation()
     */
    protected function failedValidation(Validator $validator)
    {
        $response['errors']  = $validator->errors()->toArray();

        throw new HttpResponseException(
            response()->json($response, 422)
        );
    }
}
