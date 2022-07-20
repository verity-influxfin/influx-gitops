<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class WorkLoanShareRequest extends FormRequest
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
            'user_id' => ['required', 'max:20'],
            'experience' => ['required', 'max:100']
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute必填',
            'max' => ':attribute字數不可大於:max',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => '使用者編號',
            'experience' => '借款體驗'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'msg' => '表單驗證失敗，請檢查後重新送出！',
            'data' => $validator->errors()
        ], 400));
    }
}
