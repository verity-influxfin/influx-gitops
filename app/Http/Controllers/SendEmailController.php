<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\Notice;
use Exception;

use Illuminate\Routing\Controller as BaseController;

class SendEmailController extends BaseController
{
    public function sendNoticeMail($data)
    {
        $to = collect([
            ['name' => $data['name'], 'email' => $data['email']]
        ]);

        $params = [
            'name' => $data['name']
        ];

        try {
            Mail::to($to)->send(new Notice($params));
            return true;
        } catch (Exception $e) {
            die($e);
            return false;
        }
    }
}
