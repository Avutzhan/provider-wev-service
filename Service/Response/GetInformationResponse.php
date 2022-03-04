<?php

include_once 'Service/Generics/GenericResult.php';

class GetInformationResponse extends GenericResult
{
    public $parameters;

    public function __construct($parameters, $errorMsg, $status, $timeStamp)
    {
        $this->parameters = $parameters;
        $this->errorMsg = $errorMsg;
        $this->status = $status;
        $this->timeStamp = $timeStamp;
    }
}