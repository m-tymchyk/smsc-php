<?php namespace Smsc;

/**
 * Class Constant
 * @package Smsc
 */
abstract class Constant
{
	const SMSC_URL = 'https://smsc.ru/sys';

	const SMSC_METHOD_SEND              = 'send';
	const SMSC_METHOD_BALANCE           = 'balance';

//	const SMSC_METHOD_TMPL              = 'templates';
//	const SMSC_METHOD_JOBS              = 'jobs';
//	const SMSC_METHOD_STATUS            = 'status';
//	const SMSC_METHOD_PHONES            = 'phones';
//	const SMSC_METHOD_USERS             = 'users';
//	const SMSC_METHOD_INFO              = 'info';
//	const SMSC_METHOD_GET               = 'get';
//	const SMSC_METHOD_GET_MNP           = 'get_mnp';
//	const SMSC_METHOD_RECEIVE_PHONES    = 'receive_phones';
//	const SMSC_METHOD_SENDERS           = 'senders';


	const SMSC_LOGIN    = 'login';
	const SMSC_PASSWORD = 'psw';
	const SMSC_MESSAGE  = 'mes';
	const SMSC_PHONES   = 'phones';

	/**
	 * @var array
	 */
	protected static $methods = [
		self::SMSC_METHOD_SEND,
	    self::SMSC_METHOD_BALANCE
	];


	/**
	 * Method checkMethod description.
	 *
	 * @param $method
	 *
	 * @return bool
	 */
	public static function checkMethod($method)
	{
		return in_array($method, self::$methods);
	}

}