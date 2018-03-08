<?php
require_once 'controller/Controller.php';

class Spin extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function Spin($request)
    {
        if (!$request['bet_denomination'] || !$request['bet_betlevel'] || !$request['gameId']) {
            return print($this->Error->sendError(0));
        }
        $gameId = $request['gameId'];
        require_once '../games/' . $gameId . '/src/GameSpin.php';
        $GameSpin = new GameSpin;

        return print(urldecode(http_build_query($GameSpin->Spin($request))));
    }
}