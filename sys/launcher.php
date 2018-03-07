<?php
require_once "models/User.php";
require_once "controller/Logger.php";

class Launcher
{
    private $lang = 'en'; // en, tr
    private $user;

    function __construct()
    {
        $this->user = new User;
        $this->Log = new Logger;
    }

    public function startGame()
    {
        $method = $_REQUEST['method'];
        $uid = intval($_REQUEST['uid']);
        $gameId = $_REQUEST['gameId'];

        if (!$method || $method != 'start' || !$uid || !$gameId) {
            return;
        }
        $user = $this->user->getByUid($uid);

        if (empty($user) || $user === FALSE) {
            header('HTTP/1.0 404 Not Found', true, 404);
        }

        $sessid = md5($gameId . $user['uid'] . time());
        $resetSessions = $this->user->resetSessionStatuses($uid);

        if ($resetSessions !== TRUE) {
            return;
        }
        $createSession = $this->user->createSession($uid, $sessid);
        if ($createSession !== TRUE) {
            return;
        }

        $startParams = [
            'gameId' => $gameId,
            'server' => 'http://gaming-soft.info/slots/sys/endpoint.php',
            'lang' => $this->lang,
            'sessId' => $sessid,
            'operatorId' => 'default'
        ];
        header('Location: ../games/' . $gameId . '/game/index.php?' . http_build_query($startParams) . '&v=' . time());

    }
}

$launcher = new Launcher;
$launcher->startGame();