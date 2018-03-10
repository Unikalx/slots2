<?php
require_once 'controller/Controller.php';

class ReloadBalance extends Controller
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
        
        $convertedBalance = $this->MoneyManager->convertBalance($user['balance']);

        $reloadBalanceResponse = [
            'clientaction'      => $request['action'],
            'credit'            => $convertedBalance['cents'],
            'playercurrency'    => $user['playercurrency'],
            'playercurrencyiso' => $user['playercurrency'],
        ];

        return print(urldecode(http_build_query($reloadBalanceResponse)));
    }
}