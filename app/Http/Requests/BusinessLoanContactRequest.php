<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BusinessLoanContactRequest extends FormRequest
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
            'name' => ['required', 'max:40'],
            'gender' => ['required', 'in:1,2'],
            'company_name' => ['required', 'max:40'],
            'email' => ['required', 'email'],
            'contact_time' => ['required', 'in:1,2,3'],
            'reason' => ['required', 'max:40']
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute必填',
            'email' => ':attribute格式錯誤',
            'in' => ':attribute選項有誤',
            'max' => ':attribute字數不可大於:max'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '姓名',
            'gender' => '身分性別',
            'company_name' => '公司名稱',
            'email' => '電子信箱',
            'contact_time' => '聯絡時間',
            'reason' => '資金原因'
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
