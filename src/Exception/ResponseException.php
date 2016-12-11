<?php namespace Smsc\Exception;

/**
 * Class ResponseException
 * @package Smsc\Exception
 */
class ResponseException extends SmscException
{

	/**
	 * @var array
	 */
	protected $originalResponseException;

	/**
	 * ResponseException constructor.
	 *
	 * @param array $error
	 */
	public function __construct(array $error)
	{
		$message    = $error['error'] ?? 'No message text';
		$code       = $error['error_code'] ?? 0;
		$this->originalResponseException = $error;
		parent::__construct($message, $code);
	}

	/**
	 * Method getOriginalResponseException description.
	 *
	 * @return array
	 */
	public function getOriginalResponseException()
	{
		return $this->originalResponseException;
	}
}