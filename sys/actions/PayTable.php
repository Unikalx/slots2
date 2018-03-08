<?php
require_once 'controller/Controller.php';

class PayTable extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function PayTable($request)
    {
        $sessid = $request['sessid'];
        $gameId = $_REQUEST['gameId'];

        $user = $this->User->getUserBySession($sessid);

        if ($user === FALSE) {
            return print($this->Error->sendError(20));
        }
        $convertedBalance = $this->MoneyManager->convertBalance($user['balance']);

        $payTableResponse = include('config/' . $gameId . '/payTable.config.php');

        $payTableResponse['playercurrencyiso'] = $user['playercurrency'];
        $payTableResponse['jackpotcurrencyiso'] = $user['playercurrency'];
        $payTableResponse['jackpotcurrency'] = $user['playercurrency'];
        $payTableResponse['playercurrency'] = $user['playercurrency'];
        $payTableResponse['credit'] = $convertedBalance['cents'];
        $payTableResponse['gamesoundurl'] = 'gamesoundurl';

        return print(http_build_query($payTableResponse));
    }
}