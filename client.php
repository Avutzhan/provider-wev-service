<?php

/**
 * bad client.php
 * Использовал для тестирования постман, разные curl запросы, и этот клиент
 * Так как не работал раньше с soap работал только rest, mvc, event bus, command
 * решил не рисковать и дебажить на всем
 */

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

class GetInformationRequest extends GenericArguments
{
    public $parameters;
    public $serviceId;

    public function __construct($parameters, $serviceId, $password, $username)
    {
        $this->parameters = $parameters;
        $this->serviceId = $serviceId;
        $this->password = $password;
        $this->username = $username;
    }
}

class ChangePasswordRequest extends GenericArguments
{
    public $newPassword;

    public function __construct($newPassword, $password, $username)
    {
        $this->newPassword = $newPassword;
        $this->password = $password;
        $this->username = $username;
    }
}

class GetStatementRequest extends GenericArguments
{
    public $dateFrom;
    public $dateTo;
    public $serviceId;
    public $onlyTransactionId;
    public $parameters;

    public function __construct($dateFrom, $dateTo, $serviceId, $onlyTransactionId, $parameters, $password, $username)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->serviceId = $serviceId;
        $this->onlyTransactionId = $onlyTransactionId;
        $this->parameters = $parameters;
        $this->password = $password;
        $this->username = $username;
    }

}

class CheckTransactionRequest extends GenericArguments
{
    public $serviceId;
    public $transactionId;
    public $transactionTime;
    public $parameters;

    public function __construct($serviceId, $transactionId, $transactionTime, $parameters, $password, $username)
    {
        $this->serviceId = $serviceId;
        $this->transactionId = $transactionId;
        $this->transactionTime = $transactionTime;
        $this->parameters = $parameters;
        $this->password = $password;
        $this->username = $username;
    }

}

class GenericArguments
{
    public $password;
    public $username;

}

class GenericParam
{
    public $paramKey;
    public $paramValue;

    public function __construct($paramKey, $paramValue)
    {
        $this->paramKey = $paramKey;
        $this->paramValue = $paramValue;

    }
}

$genParams = new GenericParam('key1', 'value1');
$req = new CheckTransactionRequest(11,22,'1000-01-01 00:00:0', $genParams,
    'pass', 'user');

$options = array(
    'soap_version' => SOAP_1_2,
    'cache_wsdl' => WSDL_CACHE_NONE,
    'trace' => 1
);

$req3 = new GetStatementRequest('1000-01-01 00:00:0', '1000-01-01 00:00:0', 1, 1,
    $genParams, 'pass', 'user');
$req4 = new ChangePasswordRequest('newpass', 'pass', 'user');
$req5 = new GetInformationRequest($genParams, 1, 'pass', 'user');

$client = new SoapClient( "http://uws.provider.com/wsdl/ProviderWebService.wsdl", $options);
$client->__setLocation('http://uws.provider.com/');
//$result = $client->__soapCall('CheckTransaction', array($req));
//$result2 = $client->__soapCall('CancelTransaction', array($req));
//$result3 = $client->__soapCall('GetStatement', array($req3));
//$result4 = $client->__soapCall('ChangePassword', array($req4));

$result = $client->__doRequest('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
    <soapenv:Body>
        <ns1:GetInformationArguments xmlns:ns1="http://uws.provider.com/"
                                     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns1:
GetInformationArguments">
            <password>pwd</password>
            <username>user</username>
            <parameters>
                <paramKey>client_id</paramKey>
                <paramValue>634247</paramValue>
            </parameters><serviceId>3</serviceId>
        </ns1:GetInformationArguments>
    </soapenv:Body>
</soapenv:Envelope>', 'http://uws.provider.com/', 'GetInformation', SOAP_1_2 );

//$client->__setCookie('XDEBUG_SESSION', 'PHPSTORM');
//var_dump($result);
//var_dump($result2);
//var_dump($result3);
//
//echo "====== REQUEST HEADERS =====" . PHP_EOL;
//var_dump($client->__getLastRequestHeaders());
//echo "========= REQUEST ==========" . PHP_EOL;
//var_dump($client->__getLastRequest());
//echo "========= RESPONSE =========" . PHP_EOL;
//var_dump($result);

include_once 'Service/Configs/DB.php';

$new_transaction_id = DB::createTransaction(1, 1, 1, 1, 'in progress');
DB::updateTransactionStatus($new_transaction_id, 'done');

var_dump($new_transaction_id);