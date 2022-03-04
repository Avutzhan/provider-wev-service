<?php

include_once 'Service/Generics/GenericResult.php';

class PerformTransactionResponse extends GenericResult
{
    public $parameters;
    public $providerTrnId;

    public function __construct($parameters = null, $providerTrnId = null, $errorMsg = null, $status = null,
                                $timeStamp = null)
    {
        $this->parameters = $parameters;
        $this->providerTrnId = $providerTrnId;
        $this->errorMsg = $errorMsg;
        $this->status = $status;
        $this->timeStamp = $timeStamp;
    }
}