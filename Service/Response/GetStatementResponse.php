<?php

include_once 'Service/Generics/GenericResult.php';

class GetStatementResponse extends GenericResult
{
    public $statements;

    public function __construct($statements, $errorMsg, $status, $timeStamp)
    {
        $this->statements = $statements;
        $this->errorMsg = $errorMsg;
        $this->status = $status;
        $this->timeStamp = $timeStamp;
    }
}