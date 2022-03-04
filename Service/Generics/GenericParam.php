<?php

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