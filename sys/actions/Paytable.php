<?php
require_once 'controller/Controller.php';

class Paytable extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function handle($request)
    {
        $user = $this->User->getBySession($request['sessid']);

        if ($user === FALSE) {

            return print($this->Error->sendError(20));

        }
        $gameFolder = $this->gameIdArray[$_REQUEST['gameId']];

        $convertedBalance = $this->MoneyManager->convertBalance($user['balance']);

        $paytableResponse = include('config/' . $gameFolder . '/paytable.config.php');

        $paytableResponse['credit'] = $convertedBalance['cents'];
        $paytableResponse['gamesoundurl'] = $this->getGameUrl($gameFolder);
        $paytableResponse['jackpotcurrency'] = $user['playercurrency'];
        $paytableResponse['jackpotcurrencyiso'] = $user['playercurrency'];
        $paytableResponse['playercurrency'] = $user['playercurrency'];
        $paytableResponse['playercurrencyiso'] = $user['playercurrency'];

        return print(urldecode(http_build_query($paytableResponse)));
    }
}