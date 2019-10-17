<?php

class Json_output
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $statusMessage;

    /**
     * @var int
     */
    protected $errorCode;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @var array
     */
    protected $response;

    public function __construct()
    {
        header('Content-Type: application/json');
        $this->response = [];
    }

    public function toArray()
    {
        $response = [];
        $response["status"] = [
            "code" => intval($this->statusCode),
            "message" => strval($this->statusMessage),
        ];
        if ($this->errorCode) {
            $response["error"] = [
                "code" => intval($this->errorCode),
                "message" => strval($this->errorMessage),
            ];
        }
        $response["response"] = $this->response;
        return $response;
    }

    public function send()
    {
        $response = $this->toArray();
        echo json_encode($response);
        die();
    }

    /**
     * Set the value of Status Code
     *
     * @param int statusCode
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Set the value of Status Message
     *
     * @param string statusMessage
     *
     * @return self
     */
    public function setStatusMessage($statusMessage)
    {
        $this->statusMessage = $statusMessage;

        return $this;
    }

    /**
     * Set the value of Error Code
     *
     * @param int errorCode
     *
     * @return self
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Set the value of Error Message
     *
     * @param string errorMessage
     *
     * @return self
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * Set the value of Response
     *
     * @param array response
     *
     * @return self
     */
    public function setResponse(array $response)
    {
        $this->response = $response;

        return $this;
    }
}
