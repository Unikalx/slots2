<?php
require_once 'models/logger.php';
require_once 'actions/Init.php';
require_once 'actions/PayTable.php';
require_once 'actions/Spin.php';
require_once 'actions/ReloadBalance.php';

ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);

class Server
{
    private $log;
    private $Init;
    private $PayTable;
    private $Spin;
    private $ReloadBalance;

    function __construct()
    {
        $this->log = new Logger;
        $this->Init = new Init;
        $this->PayTable = new PayTable;
        $this->Spin = new Spin;
        $this->ReloadBalance = new ReloadBalance;
    }

    public function actions()
    {
        if (!$_REQUEST['action'] || !$_REQUEST['sessid']) {
            $this->log->e('Not sessid or action (server.php)');
            die();
        }
        switch ($_REQUEST['action']) {
            case ('init'):
                $this->Init->init($_REQUEST);
                break;
            case ('paytable'):
                $this->PayTable->payTable($_REQUEST);
                break;
            case ('spin'):
                $this->Spin->spin($_REQUEST);
                break;
            case ('reloadbalance'):
                $this->ReloadBalance->reloadBalance($_REQUEST);
                break;
        }
    }
}

$server = new Server();
$server->actions();