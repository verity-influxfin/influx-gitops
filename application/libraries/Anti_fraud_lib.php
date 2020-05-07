<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Anti_fraud_lib{

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function related_users($userId)
    {
        if ($userId <= 0) {
            return [];
        }

        $this->CI->load->model('mongolog/user_login_log_model');

        $usersWithSameDeviceId = [];
        $userIds = $this->CI->user_login_log_model->findUserIdsHavingSameDeviceIdsWith($userId);
        if ($userIds && $userIds[0]->users) {
            $usersWithSameDeviceId = $this->CI->user_model->get_many_by(["id" => $userIds[0]->users]);
        }

        $usersWithSameIp = [];
        $timeBefore = 1564102800;
        $userWithIps = $this->CI->user_login_log_model->findUserLoginIps($userId, $timeBefore);
        if ($userWithIps) {
            $user = $this->CI->user_login_log_model->findUserIdsByIps($userWithIps->created_ips, $timeBefore);
            $userIdsWithSameIp = $this->CI->user_model->get_many_by(["id" => $user->users]);
        }

        $usersWithSameEmergencyContact = $this->CI->user_meta_model->get_users_with_same_emergency_contact($userId);

        $emergencyContact = $this->CI->user_meta_model->get_emergency_contact_who_is_member($userId);

        $this->CI->load->model('user/user_certification_model');
        $certificationRequests = $this->CI->user_certification_model->get_many_by([
            'user_id' => $userId,
            'certification_id' => [1,3,6],
        ]);

        $addresses = [];
        $bankAccounts = [];
        $idCardNumbers = [];
        $emails = [];
        $this->CI->load->model('user/user_bankaccount_model');
        $user_bankaccount 	= $this->CI->user_bankaccount_model->get_by([
            'user_id' => $userId,
            'investor' => 0,
        ]);
        if($user_bankaccount){
            $bankAccounts[] = $user_bankaccount->bank_account;
        }
        foreach ($certificationRequests as $certificationRequest) {
            $content = json_decode($certificationRequest->content);
            $certificationId = $certificationRequest->certification_id;
            if ($certificationId == 1) {
                $idCardNumbers[] = $content->id_number;
                $addresses[] = $content->address;
            } elseif ($certificationId == 3) {
                $bankAccounts[] = isset($content->bank_account)?$content->bank_account:false;
            } elseif ($certificationId == 6) {
                $emails[] = $content->email;
            }
        }

        $usersWithSameBankAccount = $this->CI->user_certification_model->get_users_with_same_value($userId, 'bank_account', $bankAccounts);
        $usersWithSameIdNumber = $this->CI->user_certification_model->get_users_with_same_value($userId, 'id_number', $idCardNumbers);

        $potentialPhoneNumbers = [];
        foreach ($emails as $email) {
            preg_match('/[0|886][0-9]{8,9}/', $email, $matches);
            foreach ($matches as $phone) {
                $potentialPhoneNumbers[] = $phone;
            }
        }
        $usersWithSamePhoneNumber = [];
        if ($potentialPhoneNumbers) {
            $usersWithSamePhoneNumber = $this->CI->user_model->get_many_by([
                'phone' => $potentialPhoneNumbers,
                'id !=' => $userId
            ]);
        }

        $usersWithSameAddress = $this->CI->user_certification_model->get_users_with_same_value($userId, 'address', $addresses);

        $currentUser = $this->CI->user_model->get($userId);
        $introducer = [];
        if ($currentUser->promote_code) {
            $introducer =$this->CI->user_model->get_by(['promote_code' => $currentUser->promote_code]);
        }

        if (
            !$usersWithSameDeviceId
            && !$usersWithSameIp
            && !$usersWithSameEmergencyContact
            && !$emergencyContact
            && !$usersWithSameBankAccount
            && !$usersWithSameIdNumber
            && !$usersWithSamePhoneNumber
            && !$usersWithSameAddress
            && !$introducer
        ) {
            return [];
        }

        $data = new stdClass();
        $data->same_device_id = $usersWithSameDeviceId;
        $data->same_ip = $usersWithSameIp;
        $data->samp_contact = $usersWithSameEmergencyContact;
        $data->emergency_contact = $emergencyContact;
        $data->same_bank_account = $usersWithSameBankAccount;
        $data->same_id_number = $usersWithSameIdNumber;
        $data->same_phone_number = $usersWithSamePhoneNumber;
        $data->same_address = $usersWithSameAddress;
        $data->introducer = $introducer;

        return $data;
	}
}