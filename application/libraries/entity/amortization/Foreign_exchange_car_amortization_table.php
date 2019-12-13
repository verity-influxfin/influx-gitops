<?php

class Foreign_exchange_car_amortization_table
{
	/**
	* @var array
	*/
	protected $rows;

	/**
	* @var int
	*/
	protected $length;

	/**
	* @var int
	*/
	protected $costs;

    /**
     * Get the value of Rows
     *
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Set the value of Rows
     *
     * @param array $rows
     *
     * @return self
     */
    public function setRows(array $rows)
    {
        $this->rows = $rows;

        return $this;
    }

	public function addRow(Foreign_exchange_car_row $row)
	{
		$this->rows[] = $row;
	}

    /**
     * Get the value of Length
     *
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set the value of Length
     *
     * @param int $length
     *
     * @return self
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get the value of Costs
     *
     * @return int
     */
    public function getCosts()
    {
        return $this->costs;
    }

    /**
     * Set the value of Costs
     *
     * @param int $costs
     *
     * @return self
     */
    public function setCosts($costs)
    {
        $this->costs = $costs;

        return $this;
    }
}
