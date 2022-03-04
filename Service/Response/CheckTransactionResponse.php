<?php

include_once 'Service/Generics/GenericResult.php';

class CheckTransactionResponse extends GenericResult
{
    public $providerTrnId;
    public $transactionState;
    public $transactionStateErrorStatus;
    public $transactionStateErrorMsg;

    public function __construct($providerTrnId, $transactionState, $transactionStateErrorStatus,
                                $transactionStateErrorMsg, $errorMsg, $status, $timeStamp)
    {
        $this->providerTrnId = $providerTrnId;
        $this->transactionState = $transactionState;
        $this->transactionStateErrorStatus = $transactionStateErrorStatus;
        $this->transactionStateErrorMsg = $transactionStateErrorMsg;
        $this->errorMsg = $errorMsg;
        $this->status = $status;
        $this->timeStamp = $timeStamp;
    }
}