<?php namespace Smsc\Endpoints;

/**
 * Class ViberMessage
 * @package Smsc\Endpoints
 */
class ViberMessage extends AbstractPhoneMessage
{

	/**
	 * Method getParams description.
	 *
	 * @return array
	 */
	public function getParams()
	{
		$params = parent::getParams();
		$params['viber'] = 1;
		return $params;
	}

}