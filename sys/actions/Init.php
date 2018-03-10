<?php
require_once 'controller/Controller.php';

class Init extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function handle($request)
    {
        $sessid = $request['sessid'];
        $user = $this->User->getBySession($sessid);

        $gameId = $request['gameId'];
        $gameFolder = $this->gameIdArray[$gameId];

        if ($user === FALSE) {
            return print($this->Error->sendError(20));
        }
        $convertedBalance = $this->MoneyManager->convertBalance($user['balance']);

        if ($this->Transaction->saveTransaction($gameId, intval($user['uid']), $sessid, $request['action'], 0, 0, 0, 0, 0, '', 0, 0, $user['balance']) === FALSE) {
            return print($this->Error->sendError(0));
        }
        $initResponse = include('config/' . $gameFolder . '/init.config.php');

        $initResponse['playercurrencyiso'] = $user['playercurrency'];
        $initResponse['jackpotcurrencyiso'] = $user['playercurrency'];
        $initResponse['jackpotcurrency'] = $user['playercurrency'];
        $initResponse['playercurrency'] = $user['playercurrency'];
        $initResponse['gamesoundurl'] = $gameId;
        $initResponse['staticsharedurl'] = "http://gaming-soft.info/slots/games/" . $gameId . "/game/current";
        $initResponse['credit'] = $convertedBalance['cents'];

        return print(http_build_query($initResponse));
    }
}