<?php

/**
 * Response represents an HTTP response.
 *
 * @author Dautov Avutzhan <dautov92@list.ru>
 */

class Response
{
    const HTTP_OK = 200;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_NOT_VALID_PHONE_NUMBER = 425;
    const HTTP_NOT_VALID_CARD_NUMBER = 426;
    const HTTP_NOT_ENOUGH_MONEY = 427;

    /**
     * Status codes translation table.
     *
     * The list of codes is complete according to the
     * {@link https://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml Hypertext Transfer Protocol (HTTP) Status Code Registry}
     * (last updated 2016-03-01).
     *
     * Unless otherwise noted, the status code is defined in RFC2616.
     *
     * @var array
     */
    public static $statusTexts = [
        200 => 'OK',
        401 => 'Unauthorized',
        425 => 'Not Valid Phone Number',
        426 => 'Not Valid Card Number',
        427 => 'Not Enough Money',
    ];

    public static function makeResponseText($statusText, $string)
    {
        return 'This is ' . $statusText . ': ' . $string;
    }
}