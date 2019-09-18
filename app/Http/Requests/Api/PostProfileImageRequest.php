<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostProfileImageRequest extends FormRequest
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
            'header_icon' => 'file|mimes:jpg,jpeg,png',
            'profile_icon' => 'file|mimes:jpg,jpeg,png'
        ];
    }

    public function validationData()
    {
        if ($this->fileNotExists($this->header_icon) && $this->fileNotExists($this->profile_icon)) {
            return $this->except(['header_icon', 'profile_icon']);
        } elseif ($this->fileNotExists($this->header_icon)) {
            return $this->except('header_icon');
        } elseif ($this->fileNotExists($this->profile_icon)) {
            return $this->except('profile_icon');
        }
        return $this->all();
    }

    private function fileNotExists($file)
    {
        return !$file;
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
