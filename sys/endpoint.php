<?php
require_once 'controller/Actions.php';
require_once 'controller/Controller.php';

ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);

class Endpoint extends Controller
{
    private $Actions;

    function __construct()
    {
        parent::__construct();
        $this->Actions = new Actions;
    }

    public function actions()
    {
        if (empty($_REQUEST['action']) && empty($_REQUEST['sessid']) && empty($_REQUEST['gameId'])) {
            return print($this->Error->sendError(0));
        }
        switch ($_REQUEST['action']) {
            case ('init'):
                $this->Actions->Init->Init($_REQUEST);
                break;
            case ('paytable'):
                $this->Actions->PayTable->PayTable($_REQUEST);
                break;
            case ('spin'):
                $this->Actions->Spin->Spin($_REQUEST);
                break;
            case ('reloadbalance'):
                $this->Actions->ReloadBalance->ReloadBalance($_REQUEST);
                break;
        }
    }
}

$endpoint = new Endpoint;
$endpoint->actions();