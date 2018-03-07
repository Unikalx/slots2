<?php
require_once 'actions/Init.php';
require_once 'actions/PayTable.php';
require_once 'actions/Spin.php';
require_once 'actions/ReloadBalance.php';

class Actions
{
    public $Init;
    public $PayTable;
    public $Spin;
    public $ReloadBalance;


    function __construct()
    {
        $this->Init = new Init;
        $this->PayTable = new PayTable;
        $this->Spin = new Spin;
        $this->ReloadBalance = new ReloadBalance;
    }
}