<?php

namespace Smsc;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Smsc\Endpoints\AbstractPhoneMessage;
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

    /** @var array $customParams */
    protected $customParams = [];

    /**
     * SmscClient constructor.
     *
     * @param string $login
     * @param string $password
     * @param array  $customParams
     * @param bool   $passwordIsHash
     */
    public function __construct($login, $password, array $customParams = [], $passwordIsHash = false)
    {
        $this->setLogin($login);
        $this->setPassword($password, $passwordIsHash);
        $this->setCustomParams($customParams);

        $this->httpClient = new Client();
    }

    /**
     * Method setLogin description.
     *
     * @param $login
     *
     * @return SmscClient
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
     * @return SmscClient
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
     * @param array $params
     *
     * @return SmscClient
     */
    public function setCustomParams(array $params)
    {
        $this->customParams = array_merge($this->customParams, $params);

        return $this;
    }

    /**
     * @return array
     */
    public function getCustomParams()
    {
        return $this->customParams;
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
            'fmt'     => 3,       // JSON Response
            'pp'      => 462245,   // Client PP ( you can't change him ^_^ )
            'charset' => 'utf-8'
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
            Constant::SMSC_MESSAGE => $message->getMessage(),
            Constant::SMSC_PHONES  => implode(';', $message->getPhones())
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
    public function sendRequest(string $method, array $params = [])
    {
        if (false === Constant::checkMethod($method)) {
            throw new SmscException("Invalid Method '" . $method . "'");
        }

        $params[Constant::SMSC_LOGIN] = $this->getLogin();
        $params[Constant::SMSC_PASSWORD] = $this->getPassword();

        $requestParams = $this->prepareRequestParams($params);

        $url = Constant::SMSC_URL . '/' . $method . ".php?" . http_build_query($requestParams);

        $request = new Request('GET', $url);
        $response = $this->getHttpClient()->send($request);

        $bodyJson = $response->getBody()->getContents();
        $resArray = json_decode($bodyJson, true);

        if (isset($resArray['error'])) {
            throw new ResponseException($resArray);
        }

        return $resArray;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected final function prepareRequestParams(array $params = [])
    {
        return array_merge($params, $this->getDefaultParams(), $this->getCustomParams());
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