<?php
require_once 'controller/Controller.php';

class ReloadBalance extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function ReloadBalance($request)
    {
        $sessid = $request['sessid'];
        $user = $this->User->getUserBySession($sessid);

        if ($user === FALSE) {
            return print($this->Error->sendError(20));
        }
        $convertedBalance = $this->MoneyManager->convertBalance($user['balance']);

        $response = [
            'playercurrencyiso' => 'EUR',
            'clientaction' => $request['action'],
            'playercurrency' => '€',
            'credit' => $convertedBalance['cents']
        ];
        return print(urldecode(http_build_query($response)));
    }
}