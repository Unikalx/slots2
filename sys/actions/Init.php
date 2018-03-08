<?php
require_once 'controller/Controller.php';

class Init extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function Init($request)
    {
        $sessid = $request['sessid'];
        $gameId = $request['gameId'];

        $user = $this->User->getUserBySession($sessid);

        if ($user === FALSE) {
            return print($this->Error->sendError(20));
        }

        $convertedBalance = $this->MoneyManager->convertBalance($user['balance']);

        $saveTransaction = [
            'uid' => $user['uid'],
            'sessid' => $sessid,
            'action' => $request['action'],
            'bet' => 0,
            'betlevel' => 0,
            'playerBalanceCents' => 0,
            'playerBalanceCoins' => 0,
            'denomination' => 0,
            'preCombination' => '',
            'totalWinCents' => 0,
            'totalWinCoins' => 0,
            'balance' => $user['balance'],
            'calculBigWin' => $user['balance']
        ];

        if ($this->Transaction->saveTransaction($saveTransaction) === FALSE) {
            return print($this->Error->sendError(0));
        }
        $initResponse = include('config/' . $gameId . '/init.config.php');

        $initResponse['playercurrencyiso'] = $user['playercurrency'];
        $initResponse['jackpotcurrencyiso'] = $user['playercurrency'];
        $initResponse['jackpotcurrency'] = $user['playercurrency'];
        $initResponse['playercurrency'] = $user['playercurrency'];
        $initResponse['gamesoundurl'] = $gameId;
        $initResponse['staticsharedurl'] = "http://gaming-soft.info/slots/games/".$gameId."/game/current";
        $initResponse['credit'] = $convertedBalance['cents'];

        return print(http_build_query($initResponse));
    }
}