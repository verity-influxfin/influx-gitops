<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkLoanContactRequest;
use App\Http\Requests\WorkLoanShareRequest;
use App\Models\WorkLoanContact;
use App\Models\WorkLoanShare;

class WorkLoanController extends Controller
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

    public function save_contact(WorkLoanContactRequest $request)
    {
        try {
            $input = $request->validated();
            WorkLoanContact::create([
                'name' => $input['name'],
                'phone' => $input['phone'],
                'gender' => $input['gender'],
                'line' => $input['line'] ?? null,
                'email' => $input['email'],
                'reason' => $input['reason'],
                'contact_time' => $input['contact_time'],
                'created_ip' => $request->ip(),
            ]);
            return $this->return_success(['name' => $input['name']], '送出成功', 201);
        } catch (\Exception $e) {
            return $this->return_failed($e->getMessage(), null, 500);
        }
    }

    public function save_share(WorkLoanShareRequest $request)
    {
        try {
            $input = $request->validated();
            WorkLoanShare::create([
                'user_id' => $input['user_id'],
                'experience' => $input['experience'],
                'created_ip' => $request->ip(),
            ]);
            return $this->return_success(['user_id' => $input['user_id']], '送出成功', 201);
        } catch (\Exception $e) {
            return $this->return_failed($e->getMessage(), null, 500);
        }
    }
}
