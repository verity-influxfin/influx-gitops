<?php

namespace App\Http\Requests;

use App\Rules\mobile;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CampusAmbassadorGroup2022SignupRequest extends FormRequest
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
            'leader' => ['required', 'boolean'],
            'group_name' => ['required', 'max:20'],
            'name' => ['required'],
            'birthday' => ['required', 'date'],
            'phone' => ['required', new mobile(), 'unique:campus_ambassador_2022,phone'],
            'email' => ['required', 'email'],
            'school' => ['required'],
            'major' => ['required'],
            'grade' => ['required', 'in:1,2,3,4,5,6,7'],
            'school_city' => ['required'],
            'social' => ['required', 'url'],
            'image' => ['required', 'max:3072', 'mimes:jpeg'],
            'introduction_brief' => ['required', 'max:20'],
            'introduction' => ['required', 'between:200,500'],
            'qa_1' => ['required', 'in:1,2,3'],
            'qa_2' => ['required', 'in:1,2,3'],
            'qa_3' => ['required', 'in:1,2,3'],
            'proposal' => ['required_if:leader,1', 'max:10240', 'mimes:pdf'],
            'portfolio' => ['max:5120', 'mimes:pdf'],
            'video' => ['max:30720', 'mimes:mp4'],
            'video_link' => ['url'],
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute必填',
            'required_if' => ':attribute必填',
            'email' => ':attribute格式錯誤',
            'url' => ':attributeURL格式錯誤',
            'date' => ':attribute日期格式錯誤',
            'mimes' => ':attribute檔案格式錯誤',
            'max' => ':attribute檔案過大',
            'between' => ':attribute字數需介於:min-:max',
            'unique' => ':attribute重複報名',
            'in' => ':attribute選項有誤',
            'boolean' => ':attribute格式錯誤',
            //
            'photo.max' => ':attribute檔案過大',
            'introduction_brief.max' => ':attribute字數不可大於:size',
            'proposal.max' => ':attribute檔案過大',
            'portfolio.max' => ':attribute檔案過大',
            'video.max' => ':attribute檔案過大',
        ];
    }

    public function attributes()
    {
        return [
            'leader' => '是否為隊長',
            'group_name' => '團體名稱',
            'name' => '姓名',
            'birthday' => '生日',
            'phone' => '手機',
            'email' => 'Email',
            'school' => '就讀學校',
            'major' => '科系',
            'grade' => '年級',
            'school_city' => '學校所在地',
            'social' => '社群連結',
            'photo' => '生活照',
            'introduction_brief' => '一句話形容你自己',
            'introduction' => '自我介紹',
            'qa_1' => '普匯知多少第 1 題',
            'qa_2' => '普匯知多少第 2 題',
            'qa_3' => '普匯知多少第 3 題',
            'proposal' => '校園推廣企劃提案',
            'portfolio' => '作品集',
            'video' => '影片上傳',
            'video_link' => '影片連結',
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
