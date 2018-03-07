<?php
/**
 * Created by PhpStorm.
 * User: RedSoft_2
 * Date: 07.03.2018
 * Time: 10:37
 */

class Error
{
    private $errors;
    function __construct()
    {

        $this->errors = [
            0 => 'TECHNICAL_ERROR',
            10 => 'NOT_ENOUGH_MONEY',
            13 => 'PAY_LIMIT',
            20 => 'SESSION_TIMEOUT',
        ];
    }
    public function sendError($code, $error = null)
    {
        $response = [
            'errordata' => 'GameServerErrorCode',
            'error' => is_null($error) ? $this->errors[$code] : strtoupper($error),
            'errorcode' => $code,
        ];
        return urldecode(http_build_query($response));

    }
}