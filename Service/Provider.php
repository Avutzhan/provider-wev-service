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
include_once 'Service/Response/Response.php';


class Provider
{
    const NOW = '1000-01-01 00:00:0';

    /**
     * @param $args
     * @return PerformTransactionResponse|void
     * Клиент инициирует проведение транзакции для сервиса номер 1 с идентификатором в системе
     * клиента 437 на сумму 1500 сум для клиента с номером 6324357 по карте с пином 12345678.
     */
    public function PerformTransaction($args)
    {
        if (is_object($validator = Validator::bigValidator($args))) {
            return $validator;
        }

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

        return new PerformTransactionResponse($genParams, $new_transaction_id, Response::$statusTexts[Response::HTTP_OK],
            Response::HTTP_OK, $transaction_date['created_at']);
    }

    /**
     * @return CheckTransactionResponse
     */
    public function CheckTransaction()
    {
        return new CheckTransactionResponse(1, 1, 1,
            'success', Response::$statusTexts[Response::HTTP_OK],
            Response::HTTP_OK, self::NOW);
    }

    /**
     * @return CancelTransactionResponse
     */
    public function CancelTransaction()
    {
        return new CancelTransactionResponse(1, Response::$statusTexts[Response::HTTP_OK],
            Response::HTTP_OK, self::NOW);
    }

    /**
     * @param $args
     * @return GetInformationResponse
     */
    public function GetInformation($args)
    {
        if (Auth::isNotValid($args->password, $args->username)) {
            return new GetInformationResponse([], Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
                Response::HTTP_UNAUTHORIZED, gmdate('Y-m-d h:i:s', time()));
        }

        $wallet = DB::getWallet($args->parameters->paramValue);
        $service = DB::getService($args->serviceId);
        $client = DB::findById($args->parameters->paramValue);

        //можно использовать паттерн Фабрика если успею реализую
        $genParams = [
            new GenericParam('card_number', $wallet['card_number']),
            new GenericParam('service', $service['title']),
            new GenericParam('limit', $wallet['limit']),
            new GenericParam('balance', $wallet['balance']),
            new GenericParam('name', $client['name'])
        ];

        return new GetInformationResponse($genParams, Response::$statusTexts[Response::HTTP_OK],
            Response::HTTP_OK, gmdate('Y-m-d h:i:s', $service['timestamp']));
    }

    /**
     * @return GetStatementResponse
     */
    public function GetStatement()
    {
        $statements = new TransactionStatement(1, 1, 1,
            self::NOW);
        return new GetStatementResponse($statements, Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK,
            self::NOW);
    }

    /**
     * @return ChangePasswordResponse
     */
    public function ChangePassword()
    {
        return new ChangePasswordResponse(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK,
            self::NOW);
    }
}

