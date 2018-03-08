<?php
require_once "models/logger.php";
require_once "models/moneyManager.php";
require_once "models/sql.php";


class ReloadBalance
{
    public $sql;
    public $log;
    public $moneyManager;

    function __construct()
    {
        $this->log = new Logger;
        $this->sql = new Sql;

        $this->moneyManager = new MoneyManager;
        return;
    }

    public function reloadBalance($request)
    {
        $user = $this->sql->getUserData($request['sessid']);

        if ($user === FALSE) {
            $this->log->e($request['action'] . ' user is false');
            die();
        }
        $balance = $this->moneyManager->convertBalance($user['balance']);

        $response = [
            'playercurrencyiso' => 'EUR',
            'clientaction' => $request['action'],
            'playercurrency' => '€',
            'credit' => $balance
        ];
        return print(urldecode(http_build_query($response)));
    }
}