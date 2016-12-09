<?php namespace Smsc\Endpoints;

/**
 * Interface MessageInterface
 * @package Smsc\Endpoints
 */
interface MessageInterface
{
	/**
	 * MessageInterface constructor.
	 *
	 * @param array $phones
	 */
	public function __construct($phones = []);

	/**
	 * Method getParams description.
	 *
	 * @return array
	 */
	public function getParams();

	/**
	 * Method setMessage description.
	 *
	 * @param $message
	 *
	 * @return self
	 */
	public function setMessage($message);

	/**
	 * Method getMessage description.
	 *
	 * @return mixed
	 */
	public function getMessage();

	/**
	 * Method getPhones description.
	 *
	 * @return array
	 */
	public function getPhones();

	/**
	 * Method addPhones description.
	 *
	 * @param $phones
	 *
	 * @return mixed
	 */
	public function addPhones($phones);


}