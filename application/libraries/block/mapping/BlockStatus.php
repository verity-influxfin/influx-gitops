<?php

class BlockStatus
{
    const UNBLOCKED = 'unblocked';
    const BLOCKED = 'blocked';
    const TEMP_BLOCKED = 'temp_blocked';
    const SYSTEM_BLOCKED = 'system_blocked';

    /**
     * @var array
     */
    protected $domainToDBMapping;

    /**
     * @var string
     */
    protected $status;

    public function __construct($params)
    {
        $this->generateDomainToDB();
        if (!isset($params)) {
            throw new OutOfBoundsException("Params passed to constructor are invalid.");
        }
        $this->setStatus($params["status"]);
    }

    public function setStatus($status)
    {
        if (!isset($this->domainToDBMapping[$status])) {
            throw new OutOfBoundsException("Status is not found.");
        }
        $this->status = $status;
    }

    public function getValueInDB()
    {
        return $this->domainToDBMapping[$this->status];
    }

    public function getValueInDomain()
    {
        return $this->status;
    }

    public function isBlocked()
    {
        return $this->status != self::UNBLOCKED;
    }

    private function generateDomainToDB()
    {
        $this->domainToDBMapping = [
            self::UNBLOCKED => 0,
            self::BLOCKED => 1,
            self::TEMP_BLOCKED => 2,
            self::SYSTEM_BLOCKED => 3,
        ];
    }
}
