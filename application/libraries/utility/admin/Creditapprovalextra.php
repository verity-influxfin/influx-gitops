<?php

class Creditapprovalextra
{
	/**
	 * @var integer
	 */
	protected $extraPoints;

	/**
	 * @var bool
	 */
	protected $skipInsertion;
    /**
     * @var array
     */
    private $specialInfo;
    /**
     * @var int
     */
    protected $fixed_amount;

    /**
	 * @return int
	 */
	public function getExtraPoints(): int
	{
		return $this->extraPoints;
	}

	/**
	 * @param int $extraPoints
	 */
	public function setExtraPoints(int $extraPoints): void
	{
		$this->extraPoints = $extraPoints;
	}

	/**
	 * @return bool
	 */
	public function shouldSkipInsertion(): bool
	{
		return $this->skipInsertion;
	}

	/**
	 * @param bool $skipInsertion
	 */
	public function setSkipInsertion(bool $skipInsertion): void
	{
		$this->skipInsertion = $skipInsertion;
	}

    /**
     * @param array $infos
     */
    public function setSpecialInfo(array $info): void
    {
        $this->specialInfo = $info;
    }

    /**
     * @return array
     */
    public function getSpecialInfo(): array
    {
        return $this->specialInfo;
    }

    public function set_fixed_amount(int $amount): void
    {
        $this->fixed_amount = $amount;
    }

    public function get_fixed_amount(): int
    {
        return $this->fixed_amount ?? 0;
    }
}
