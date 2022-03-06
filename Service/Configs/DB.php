<?php

/**
 * TODO:: нужно реализовать так чтобы конектиться к бд один раз
 * TODO:: создать .env вытягивать секреты в bd.config и отсюда вытягивать к connect()
 * TODO:: Repository Pattern реализовать
 */
class DB
{
    public static function connect()
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

    public static function findById($id)
    {
        $conn = DB::connect();
        $sql = "SELECT * FROM users WHERE id = {$id}";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    public static function getService($serviceId)
    {
        $conn = DB::connect();
        $sql = "SELECT * FROM services WHERE id = {$serviceId}";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    /**
     * @param $clientId
     * @return array|null
     * Сделал one-liner здесь если будет время нужно отсальные функции так отрефакторить
     */
    public static function getWallet($clientId)
    {
        return self::connect()
            ->query("SELECT * FROM wallets WHERE client_id = {$clientId}")
            ->fetch_assoc();
    }

    public static function findByUsername($username)
    {
        $conn = self::connect();
        $sql = "SELECT * FROM users WHERE username = '{$username}'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    public static function getTransaction($id)
    {
        $conn = DB::connect();
        $sql = "SELECT * FROM transactions WHERE id = '{$id}'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    public static function performTransaction($sender_id, $recipient_id, $amount)
    {
        if (is_object($decrement = self::decrement($sender_id, $amount))) {
            return $decrement;
        }

        if (is_object($increment = self::increment($recipient_id, $amount))) {
            return $increment;
        }
    }

    /**
     * @param $sender_id
     * @param $amount
     * Можно:
     *  разделить эту функцию так как она имеет две ответсвенности
     *  убрать повторяющийся код
     *  кастомные exceptions
     *  возращать сообщение об ошибке на каком либо этапе
     *  если останется время отрефакторю
     */
    public static function decrement($sender_id, $amount)
    {
        $conn = self::connect();

        try {
            // Start transaction
            $conn->begin_transaction();
            $result = $conn->query("SELECT * FROM wallets WHERE client_id = '{$sender_id}'");
            // Commit changes
            $conn->commit();

        } catch (Exception $e) {
            // Something went wrong. Rollback
            $conn->rollback();
            throw $e;
        }

        try {
            $col = $result->fetch_assoc();
            // Start transaction
            $conn->begin_transaction();
            $total_sender_balance = $col['balance'] - $amount;
            $sql_balance = "UPDATE wallets SET balance = {$total_sender_balance} WHERE client_id = '{$sender_id}'";
            $conn->query($sql_balance);
            // Commit changes
            $conn->commit();

        } catch (Exception $e) {
            // Something went wrong. Rollback
            $conn->rollback();
            throw $e;
        }
    }

    /**
     * @param $recipient_id
     * @param $amount
     * Нужно отрефакторить этот кусок точно так же как decrement и улучшить дальше как описано выше
     */
    public static function increment($recipient_id, $amount)
    {
        $conn = self::connect();
        $sql2 = "SELECT * FROM wallets WHERE client_id = '{$recipient_id}'";
        $result2 = $conn->query($sql2);
        $col2 = $result2->fetch_assoc();
        $total_recipient_balance = $col2['balance'] + $amount;
        $sql_balance = "UPDATE wallets SET balance = {$total_recipient_balance} WHERE client_id = '{$recipient_id}'";
        $conn->query($sql_balance);
    }

    public static function createTransaction($sender, $recipient, $amount, $service_id, $status)
    {
        $conn = DB::connect();
        $sql = "INSERT INTO transactions (otpravlyalshik, poluchalshik, amount, status, service_id, created_at) VALUES ({$sender}, {$recipient}, {$amount}, '{$status}', {$service_id}, now())";
        $conn->query($sql);
        return $conn->insert_id;
    }

    public static function updateTransactionStatus($new_transaction_id, $status)
    {
        $conn = DB::connect();
        $sql_balance = "UPDATE transactions SET status = '{$status}' WHERE id = {$new_transaction_id}";
        $conn->query($sql_balance);
    }
}