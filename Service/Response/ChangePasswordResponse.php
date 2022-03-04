<?php

include_once 'Service/Generics/GenericResult.php';

class ChangePasswordResponse extends GenericResult
{
    public function __construct($errorMsg, $status, $timeStamp)
    {
        $this->errorMsg = $errorMsg;
        $this->status = $status;
        $this->timeStamp = $timeStamp;
    }
}