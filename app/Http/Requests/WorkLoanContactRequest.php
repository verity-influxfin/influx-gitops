<?php

namespace App\Http\Requests;

use App\Rules\mobile;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class WorkLoanContactRequest extends FormRequest
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
            'name' => ['required', 'max:20'],
            'phone' => ['required', new mobile()],
            'gender' => ['required', 'in:1,2'],
            'line' => ['max:40', 'regex:/^\w*$/i'],
            'email' => ['required', 'email'],
            'reason' => ['required', 'max:100'],
            'contact_time' => ['required', 'in:1,2,3']
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute必填',
            'email' => ':attribute格式錯誤',
            'in' => ':attribute選項有誤',
            'max' => ':attribute字數不可大於:max',
            'regex' => ':attribute格式錯誤'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '您的尊稱',
            'phone' => '手機號碼',
            'gender' => '身分證性別',
            'line' => 'Line帳號',
            'email' => '電子信箱',
            'reason' => '資金需求原因',
            'contact_time' => '聯絡時間'
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
