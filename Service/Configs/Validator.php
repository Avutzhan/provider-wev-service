<?php

include_once 'Service/Configs/DB.php';

class Validator
{
    /**
     * @param $phone
     * @return bool
     * Valid numbers:
     * +7(903)888-88-88
     *  8(999)99-999-99
     * +380(67)777-7-777
     *  001-541-754-3010
     *  +1-541-754-3010
     *  19-49-89-636-48018
     *  +233 205599853
     */
    static function isPhoneNotValid($phone)
    {
        if (preg_match("/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/", $phone)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $s
     * @return bool
     * Luhn Algorithm
     * 4111 1111 1111 1234 не правильный номер карты
     * 4111 1111 1111 1111 правильный номер карты
     */
    static function isCardNotValid($s)
    {
        $s = strrev(preg_replace('/[^\d]/','',$s));
        $sum = 0;
        for ($i = 0, $j = strlen($s); $i < $j; $i++) {
            if (($i % 2) == 0) {
                $val = $s[$i];
            } else {
                $val = $s[$i] * 2;
                if ($val > 9)  $val -= 9;
            }
            $sum += $val;
        }
        return (($sum % 10) != 0);
    }

    /**
     * @param $sender
     * @param $amount
     * @return bool
     * Eсли в балансе не хватает денег вернет true
     */
    public static function isMoneyNotEnough($sender, $amount)
    {
        $wallet = DB::getWallet($sender);
        if ($wallet['balance'] - $amount < 0) {
            return true;
        } else {
            return false;
        }
    }
}