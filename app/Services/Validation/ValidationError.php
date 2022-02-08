<?php

namespace App\Services\Validation;

/**
 *
 */
class ValidationError
{
	private $message;
	private $code;

	function __construct($message, $code)
	{
		$this->message = $message;
		$this->code = $code;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function __toString()
	{
		return $this->message;
	}
}
