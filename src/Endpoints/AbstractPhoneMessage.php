<?php namespace Smsc\Endpoints;

/**
 * Class AbstractPhoneMessage
 * @package Smsc\Endpoints
 */
abstract class AbstractPhoneMessage implements MessageInterface
{

	/**
	 * @var array
	 */
	protected $phones = [];

	/**
	 * @var array
	 */
	protected $message;

	/**
	 * @var array
	 */
	protected $params = [];

	/**
	 * SmsMessage constructor.
	 *
	 * @param array  $phones
	 * @param string $message
	 */
	public function __construct($phones = [], $message = '')
	{
		$this->phones = is_array($phones) ? $phones : [$phones];
		$this->message = $message;
	}

	/**
	 * Method getPhones description.
	 *
	 * @return array
	 */
	public function getPhones()
	{
		return $this->phones;
	}

	/**
	 * Method getMessage description.
	 *
	 * @return array
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Method getParams description.
	 *
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * Method addPhones description.
	 *
	 * @param array $phones
	 *
	 * @return $this
	 */
	public function addPhones($phones)
	{
		$this->phones = array_merge($this->phones, is_array($phones) ? $phones : $phones);
		return $this;
	}

	/**
	 * Method setMessage description.
	 *
	 * @param $message
	 *
	 * @return $this
	 */
	public function setMessage($message)
	{
		$this->message = $message;
		return $this;
	}

}