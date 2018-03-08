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
        $gameId = $request['gameId'];
        require_once '../games/' . $gameId . '/src/GameSpin.php';
        $GameSpin = new GameSpin;
        
        return print(urldecode(http_build_query($GameSpin->Spin($request))));
    }
}