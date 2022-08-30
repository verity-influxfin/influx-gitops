<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusinessLoanContactRequest;
use App\Models\BusinessLoanContact;

class BusinessLoanController extends Controller
{
    public $gender_list = [
        1 => '男',
        2 => '女'
    ];
    public $contact_time_list = [
        1 => '隨時',
        2 => '上午(09:00~12:00)',
        3 => '下午(13:30~18:00)'
    ];

    public function save_contact(BusinessLoanContactRequest $request)
    {
        try {
            $input = $request->validated();
            BusinessLoanContact::create([
                'name' => $input['name'],
                'gender' => $input['gender'],
                'company_name' => $input['company_name'],
                'email' => $input['email'],
                'contact_time' => $input['contact_time'],
                'reason' => $input['reason'],
                'created_ip' => $request->ip(),
            ]);
            return $this->return_success(['name' => $input['name']], '送出成功', 201);
        } catch (\Exception $e) {
            return $this->return_failed($e->getMessage(), null, 500);
        }
    }
}
