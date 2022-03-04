<?php

class TransactionStatement
{
    public $amount;
    public $providerTrnId;
    public $transactionId;
    public $transactionTime;

    public function __construct($amount, $providerTrnId, $transactionId, $transactionTime)
    {
        $this->amount = $amount;
        $this->providerTrnId = $providerTrnId;
        $this->transactionId = $transactionId;
        $this->transactionTime = $transactionTime;
    }
}