<?php

include_once 'Service/Generics/GenericResult.php';

class CancelTransactionResponse extends GenericResult
{
    public $transactionState;

    public function __construct($transactionState, $errorMsg, $status, $timeStamp)
    {
        $this->transactionState = $transactionState;
        $this->errorMsg = $errorMsg;
        $this->status = $status;
        $this->timeStamp = $timeStamp;
    }

}