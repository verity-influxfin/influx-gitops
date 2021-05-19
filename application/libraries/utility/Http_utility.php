<?php

class Http_utility
{
	private $header;

	private $url;

	private $body;

	private $shouldWait;

	private $waitingTime;

	public function __construct()
	{
		$this->header = [];
		$this->url = '';
		$this->body = [];
		$this->shouldWait = true;
		$this->waitingTime = 30000;
	}

	public function get()
	{
		$curl = curl_init($this->url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		if(!$this->header) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
		}

		if (!$this->shouldWait) {
			curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1);
		} else {
			curl_setopt($curl, CURLOPT_TIMEOUT_MS, $this->waitingTime);
		}

		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}

	public function put()
	{
		$curl = curl_init($this->url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");

		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->body);

		if(!$this->header) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
		}

		if (!$this->shouldWait) {
			curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1);
		} else {
			curl_setopt($curl, CURLOPT_TIMEOUT_MS, $this->waitingTime);
		}

		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}

	public function setUrl($url)
	{
		$this->url = $url;
	}

	public function setHeader($header)
	{
		$this->header = $header;
	}

	public function setBody($body)
	{
		$this->body = $body;
	}

	public function setShouldWait($shouldWait)
	{
		$this->shouldWait = $shouldWait;
	}

	public function setWaitingTime($milliseconds)
	{
		$this->waitingTime = $milliseconds;
	}
}
