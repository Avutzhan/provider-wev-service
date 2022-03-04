<?php

include_once 'Service/Provider.php';
/**
 * server.php
 */
header('Cache-Control: no-store, no-cache');
header('Expires: '.date('r'));
ini_set("soap.wsdl_cache_enabled", "0"); // отключаем кеширование WSDL-файла для тестирования

$server = new SoapServer( "wsdl/ProviderWebService.wsdl" );
$server->setClass('Provider');
$server->handle();
