<?php

require_once 'Logger.php';
require_once 'MoneyManager.php';
require_once "models/User.php";
require_once "models/Error.php";

//require_once "models/Transaction.php";


class Controller
{
    public $Log;
    public $MoneyManager;
//    public $Transaction;
    public $User;
    public $Error;


    function __construct()
    {
        $this->Log = new Logger;
        $this->MoneyManager = new MoneyManager;
//        $this->Transaction = new Transaction;
        $this->User = new User;
        $this->Error = new Error;
    }
}