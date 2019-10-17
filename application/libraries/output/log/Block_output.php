<?php

class Block_output
{
    protected $blocks;
    protected $block;

    public function __construct($params)
    {
        if (!isset($params["data"])) {
            throw new OutOfBoundsException("Data to construct block output is not found");
        }

        if (is_array($params["data"])) {
            $this->blocks = $params["data"];
        } else {
            $this->block = $params["data"];
        }
    }

    public function toOne()
    {
        if (!$this->block) {
            return [];
        }
        return $this->map($this->block);
    }

    public function toMany()
    {
        if (!$this->blocks || !is_array($this->blocks)) {
            return [];
        }

        foreach ($this->blocks as $block) {
            $blocks[] = $this->map($block);
        }
        return $blocks;
    }

    public function map($block)
    {
        return [
            "id" => intval($block->id),
            "block_status" => strval($block->block_status),
            "reason" => strval($block->reason),
            "created_at" => intval($block->created_at),
            "updated_at" => intval($block->updated_at),
            "user" => [
                "id" => $block->user_id,
                "name" => $block->user_name,
                "phone" => $block->phone,
                "status" => strval($block->status),
                "investor_status" => strval($block->investor_status),
            ],
            "admin" => [
                "id" => $block->admin_id,
                "name" => $block->admin_name,
            ]
        ];
    }
}
