<?php
require_once 'models/sql.php';

ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);

class View
{
    private $Sql;

    function __construct()
    {
        $this->Sql = new Sql;
    }

    public function startGame()
    {
        $this->Sql->deleteUserSessions();

        if (!$_REQUEST['method'] || $_REQUEST['method'] != 'start') {
            $this->locationHeader();
            die();
        }
        if (!$_REQUEST['uid'] || !$_REQUEST['gameId'] || $_REQUEST['gameId'] != 'twin_spin') {
            $this->locationHeader();
            die();
        }
        $uid = intval($_REQUEST['uid']);

        $user = $this->Sql->getUser($uid);

        if ($user == FALSE) {
            header('HTTP/1.0 404 Not Found', true, 404);
            die();
        }

        $sessId = md5($_REQUEST['gameId'] . $user['uid'] . time());

        $update = $this->Sql->updateStatus($uid);

        if ($update == FALSE) {
            $this->Sql->log->e('Session statuses wasn\'t nulled (view.php)');
            $this->locationHeader();
            die();
        }

        $insertSession = $this->Sql->insertUserSessions($uid, $sessId);

        if ($insertSession == FALSE) {
            $this->Sql->log->e('Session id wasn\'t inserted to db (view.php)');
            $this->locationHeader();
            die();
        }

        $startParams = [
            'gameId' => $_REQUEST['gameId'],
            'server' => 'http://gaming-soft.info/netent/twinSpin/src/server.php',
            'operatorId' => 'netent',
            'lang' => 'en',
            'sessId' => $sessId
        ];

        header('Location: ../game/index.php?' . http_build_query($startParams));
    }
    private function locationHeader()
    {
        header('Location: /netent/access.php');
    }
}

$view = new View();
$view->startGame();