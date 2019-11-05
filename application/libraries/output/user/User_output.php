<?php

class User_output
{
    protected $users;
    protected $user;

    public function __construct($params)
    {
        if (!isset($params["data"])) {
            throw new OutOfBoundsException("Data to construct user output is not found");
        }

        if (is_array($params["data"])) {
            $this->users = $params["data"];
        } else {
            $this->user = $params["data"];
        }
    }

    public function toOne($withSensitiveInfo = false)
    {
        if (!$this->user) {
            return [];
        }
        return $this->map($this->user, $withSensitiveInfo);
    }

    public function toMany()
    {
        if (!$this->users || !is_array($this->users)) {
            return [];
        }

        foreach ($this->users as $user) {
            $users[] = $this->map($user);
        }
        return $users;
    }

    public function map($user, $withSensitiveInfo = false)
    {
        $output = [
            "id" => intval($user->id),
            "name" => strval($user->name),
            "phone" => strval($user->phone),
            "sex" => strval($user->sex),
            "email" => strval($user->email),
            "status" => strval($user->status),
            "investor_status" => strval($user->investor_status),
            "block_status" => strval($user->block_status),
            "promote_code" => strval($user->promote_code),
            "created_at" => intval($user->created_at),
        ];

        if ($withSensitiveInfo) {
			$output["id_card"] = [
				"id" => $user->id_number,
				"issued_at" => $user->id_card_date,
				"issued_place" => $user->id_card_place,
			];

			$output["birthday"] = $user->birthday;
			$output["address"] = $user->address;
			if ($user->profile) {
				$output["profile_image"] = $user->profile->id_card_person;
			}
			if ($user->profile && $user->profile->emergency_relationship && $user->profile->emergency_relationship == "配偶") {
				$output["marriage"] = [
					'name' => $user->profile->emergency_name,
					'phone' => $user->profile->emergency_phone,
				];
			}
		}

        if ($user->school) {
			$ci =& get_instance();
			$ci->load->library('output/user/school_output', ["data" => $user->school]);
			$output["school"] = $ci->school_output->toOne();
		}

        if ($user->instagram) {
			$ci =& get_instance();
			$ci->load->library('output/user/instagram_output', ["data" => $user->instagram]);
			$output["instagram"] = $ci->instagram_output->toOne();
		}

        if ($user->facebook) {
			$ci =& get_instance();
			$ci->load->library('output/user/facebook_output', ["data" => $user->facebook]);
			$output["facebook"] = $ci->facebook_output->toOne();
		}

        return $output;
    }
}
