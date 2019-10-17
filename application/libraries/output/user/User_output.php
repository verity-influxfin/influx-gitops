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

    public function toOne()
    {
        if (!$this->user) {
            return [];
        }
        return $this->map($this->user);
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

    public function map($user)
    {
        return [
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
    }
}
