<?php

class DB
{
    static function connect()
    {
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "e_wallet";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    static function findById($id)
    {
        $conn = DB::connect();
        $sql = "SELECT * FROM users WHERE id = {$id}";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    static function getService($serviceId)
    {
        $conn = DB::connect();
        $sql = "SELECT * FROM services WHERE id = {$serviceId}";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    static function getWallet($clietnId)
    {
        $conn = DB::connect();
        $sql = "SELECT * FROM wallets WHERE client_id = {$clietnId}";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    static function findByUsername($username)
    {
        $conn = DB::connect();
        $sql = "SELECT * FROM users WHERE username = '{$username}'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    static function getTransaction($id)
    {
        $conn = DB::connect();
        $sql = "SELECT * FROM transactions WHERE id = '{$id}'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    static function performTransaction($sender_id, $recipient_id, $amount)
    {
        //по идее тут надо делать кучу проверок и если вдруг один sql запрос не пройдет или же какая то проверка не
        //пройдет то нужно выдать ошибку что трансакция не удалась и изменить статус транзакции типа failed
        //было бы больше времени и команда то можно было бы подумать что тут можно написать еще
        //а так я написал самый наивный performTransaction который подразумевает что большая часть данныч и условий правильные
        $conn = DB::connect();
        //decrement
        $sql = "SELECT * FROM wallets WHERE client_id = '{$sender_id}'";
        $result = $conn->query($sql);
        $col = $result->fetch_assoc();
        $total_sender_balance = $col['balance'] - $amount;
        $sql_balance = "UPDATE wallets SET balance = {$total_sender_balance} WHERE client_id = '{$sender_id}'";
        $conn->query($sql_balance);
        //increment
        $sql2 = "SELECT * FROM wallets WHERE client_id = '{$recipient_id}'";
        $result2 = $conn->query($sql2);
        $col2 = $result2->fetch_assoc();
        $total_recipient_balance = $col2['balance'] + $amount;
        $sql_balance = "UPDATE wallets SET balance = {$total_recipient_balance} WHERE client_id = '{$recipient_id}'";
        $conn->query($sql_balance);
        return true;
    }

    static function createTransaction($sender, $recipient, $amount, $service_id, $status)
    {
        $conn = DB::connect();
        $sql = "INSERT INTO transactions (otpravlyalshik, poluchalshik, amount, status, service_id, created_at) VALUES ({$sender}, {$recipient}, {$amount}, '{$status}', {$service_id}, now())";
        $conn->query($sql);
        return $conn->insert_id;
    }

    static function updateTransactionStatus($new_transaction_id, $status)
    {
        $conn = DB::connect();
        $sql_balance = "UPDATE transactions SET status = '{$status}' WHERE id = {$new_transaction_id}";
        $conn->query($sql_balance);
    }
}