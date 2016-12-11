<?php namespace Smsc;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Smsc\Endpoints\AbstractPhoneMessage;
use Smsc\Endpoints\MessageInterface;
use Smsc\Endpoints\SmsMessage;
use Smsc\Exception\ResponseException;
use Smsc\Exception\SmscException;

/**
 * Class SmscClient
 * @package Smsc
 */
class SmscClient
{
	/**
	 * @var string
	 */
	protected $login;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var Client
	 */
	protected $httpClient;

	/**
	 * Smsc constructor.
	 *
	 * @param      $login
	 * @param      $password
	 * @param bool $passwordIsHash
	 */
	public function __construct($login, $password, $passwordIsHash = false)
	{
		$this->setLogin($login);
		$this->setPassword($password, $passwordIsHash);

		$this->httpClient = new Client();
	}

	/**
	 * Method setLogin description.
	 *
	 * @param $login
	 *
	 * @return $this
	 */
	public function setLogin($login)
	{
		$this->login = $login;
		return $this;
	}

	/**
	 * Method setPassword description.
	 *
	 * @param      $password
	 * @param bool $isHash
	 *
	 * @return $this
	 */
	public function setPassword($password, $isHash = false)
	{
		$this->password = strtolower($isHash ? $password : md5($password));
		return $this;
	}

	/**
	 * Method getLogin description.
	 *
	 * @return string
	 */
	public function getLogin()
	{
		return $this->login;
	}

	/**
	 * Method getPassword description.
	 *
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Method getDefaultParams description.
	 *
	 * @return array
	 */
	private function getDefaultParams()
	{
		return [
			'fmt'   => 3,       // JSON Response
			'pp'    => 462245   // Client PP ( you can't change him ^_^ )
		];
	}

	/**
	 * Method getHttpClient description.
	 *
	 * @return Client
	 */
	public function getHttpClient()
	{
		return $this->httpClient;
	}


	/**
	 * Method sendMessage description.
	 *
	 * @param AbstractPhoneMessage $message
	 *
	 * @return array
	 * @throws ResponseException
	 * @throws SmscException
	 */
	public function sendMessage(AbstractPhoneMessage $message)
	{
		$params = [
			Constant::SMSC_MESSAGE  => $message->getMessage(),
			Constant::SMSC_PHONES   => implode(';', $message->getPhones())
		];

		$params = array_merge($message->getParams(), $params);

		return $this->sendRequest(Constant::SMSC_METHOD_SEND, $params);
	}


	/**
	 * Method sendRequest description.
	 *
	 * @param       $method
	 * @param array $params
	 *
	 * @return array
	 * @throws ResponseException
	 * @throws SmscException
	 */
	public function sendRequest($method, $params = [])
	{
		if( false === Constant::checkMethod($method) )
		{
			throw new SmscException("Invalid Method '" . $method . "'");
		}
		
		$params[Constant::SMSC_LOGIN]       = $this->getLogin();
		$params[Constant::SMSC_PASSWORD]    = $this->getPassword();

		$responseParams = array_merge($params, $this->getDefaultParams());
		
		$url = Constant::SMSC_URL . '/' . $method . ".php?" . http_build_query($responseParams);
		
		$request = new Request('GET', $url);
		$response = $this->getHttpClient()->send($request);

		$bodyJson = $response->getBody()->getContents();
		$resArray = json_decode($bodyJson, true);

		if( isset($resArray['error']) )
		{
			throw new ResponseException($resArray);
		}

		return $resArray;
	}

	/**
	 * Method smsMessage description.
	 *
	 * @param $phone
	 * @param $message
	 *
	 * @return array
	 */
	public final function smsMessage($phone, $message)
	{
		return $this->sendMessage(new SmsMessage($phone, $message));
	}

}