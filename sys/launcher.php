<?php
require_once 'controller/Controller.php';


class Launcher extends Controller
{
    private $lang = 'en'; // en, tr

    function __construct()
    {
        parent::__construct();
    }

    public function startGame()
    {
        $method = $_REQUEST['method'];
        $uid = intval($_REQUEST['uid']);
        $gameId = $_REQUEST['gameId'];

        if (!$method || $method != 'start' || !$uid || !$gameId || !in_array($gameId, $this->gameIdArray)) {
            return print($this->Error->sendError(0));
        }
        $user = $this->User->getByUid($uid);

        if (empty($user) || $user === FALSE) {
            header('HTTP/1.0 404 Not Found', true, 404);
        }

        $sessid = md5($gameId . $user['uid'] . time());
        $resetSessions = $this->User->resetSessionStatuses($uid);

        if ($resetSessions !== TRUE) {
            return print($this->Error->sendError(0));
        }
        $createSession = $this->User->createSession($uid, $sessid);
        if ($createSession !== TRUE) {
            return print($this->Error->sendError(0));
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