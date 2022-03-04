<?php

include_once 'Service/Response/CheckTransactionResponse.php';
include_once 'Service/Response/CancelTransactionResponse.php';
include_once 'Service/Response/GetStatementResponse.php';
include_once 'Service/Response/ChangePasswordResponse.php';
include_once 'Service/Response/GetInformationResponse.php';
include_once 'Service/Response/PerformTransactionResponse.php';
include_once 'Service/Generics/TransactionStatement.php';
include_once 'Service/Generics/GenericParam.php';
include_once 'Service/Configs/DB.php';
include_once 'Service/Configs/Auth.php';
include_once 'Service/Configs/Validator.php';

class Provider
{
    public function PerformTransaction($args)
    {
        if (Auth::isNotValid($args->password, $args->username)) {
            return new PerformTransactionResponse([], 0,'not valid user', 208,
                gmdate('Y-m-d h:i:s', time()));
        }

        if (Validator::isPhoneNotValid($args->parameters[0]->paramValue)) {
            return new PerformTransactionResponse([], 0,$args->parameters[0]->paramValue . ' not valid phone number', 208,
                gmdate('Y-m-d h:i:s', time()));
        }

        if (Validator::isCardNotValid($args->parameters[1]->paramValue)) {
            return new PerformTransactionResponse([], 0,$args->parameters[1]->paramValue . ' not valid card number', 208,
                gmdate('Y-m-d h:i:s', time()));
        }

        //Клиент инициирует проведение транзакции для сервиса номер 1 с идентификатором в системе
        //клиента 437 на сумму 1500 сум для клиента с номером 6324357 по карте с пином 12345678.

        //begin transaction
        $new_transaction_id = DB::createTransaction($args->transactionId, $args->parameters[2]->paramValue, $args->amount, $args->serviceId, 'in progress');
        DB::performTransaction($args->transactionId, $args->parameters[2]->paramValue, $args->amount);
        DB::updateTransactionStatus($new_transaction_id, 'done');
        // end transaction

        $current_balance = DB::getWallet($args->transactionId);
        $service = DB::getService($args->serviceId);
        $transaction_date = DB::getTransaction($new_transaction_id);

        $genParams = [
            new GenericParam('balance', $current_balance['balance']),
            new GenericParam('traffic', $service['traffic']),
            new GenericParam('date', $service['timestamp']),
        ];

        return new PerformTransactionResponse($genParams, $new_transaction_id, 'success', 200,
            $transaction_date['created_at']);
    }

    /**
     * @return CheckTransactionResponse
     */
    public function CheckTransaction()
    {
        return new CheckTransactionResponse(11, 2, 3,
            'no err', 'no err', 2, '1000-01-01 00:00:0');
    }

    /**
     * @return CancelTransactionResponse
     */
    public function CancelTransaction()
    {
        return new CancelTransactionResponse(2, 'no err', 2,
            '1000-01-01 00:00:0');
    }

    /**
     * @param $args
     * @return GetInformationResponse
     */
    public function GetInformation($args)
    {
        if (Auth::isNotValid($args->password, $args->username)) {
            return new GetInformationResponse([], 'not valid user', 208,
                gmdate('Y-m-d h:i:s', time()));
        }

        $wallet = DB::getWallet($args->parameters->paramValue);
        $service = DB::getService($args->serviceId);
        $client = DB::findById($args->parameters->paramValue);

        $genParams = [
            new GenericParam('card_number', $wallet['card_number']),
            new GenericParam('service', $service['title']),
            new GenericParam('limit', $wallet['limit']),
            new GenericParam('balance', $wallet['balance']),
            new GenericParam('name', $client['name'])
        ];

        return new GetInformationResponse($genParams, 'success', 200,
            gmdate('Y-m-d h:i:s', $service['timestamp']));
    }

    /**
     * @return GetStatementResponse
     */
    public function GetStatement()
    {
        $statements = new TransactionStatement(1, 2, 1,
            '1000-01-01 00:00:0');
        return new GetStatementResponse($statements, 'no err', 2, '1000-01-01 00:00:0');
    }

    /**
     * @return ChangePasswordResponse
     */
    public function ChangePassword()
    {
        return new ChangePasswordResponse('no err', 2, '1000-01-01 00:00:0');
    }
}

