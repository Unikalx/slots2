<?php
require_once "models/logger.php";
require_once "models/moneyManager.php";
require_once "models/sql.php";
require_once "models/model.php";

class Init
{
    private $gameSoundUrl;
    private $sql;
    private $log;
    private $model;
    private $moneyManager;

    function __construct()
    {
        $this->log = new Logger;
        $this->model = new Model;
        $this->sql = new Sql;

        $this->moneyManager = new MoneyManager;
        $this->gameSoundUrl = $this->model->gameSoundUrl;
        return;
    }

    public function init($request)
    {
        $user = $this->sql->getUserData($request['sessid']);
        if ($user === FALSE) {
            $this->log->e('Your session is ended, open the game window again (actions.php/init)');
            die();
        }
        $convertedBalance = $this->moneyManager->convertBalance($user['balance']);

        $saveTransaction = [
            'uid' => $user['uid'],
            'sessid' => $request['sessid'],
            'action' => $request['action'],
            'bet' => 0,
            'betlevel' => 0,
            'playerBalanceCents' => 0,
            'playerBalanceCoins' => 0,
            'denomination' => 0,
            'preCombination' => '',
            'totalWinCents' => 0,
            'totalWinCoins' => 0,
            'balance' => $user['balance'],
            'calculBigWin' => $user['balance']
        ];

        if ($this->sql->saveTransaction($saveTransaction) === FALSE) {
            $this->log->e($request['action'] . ' transaction was not saved');
            die();
        }
        $this->log->d('1$convertedBalance' . json_encode($convertedBalance));
        $this->log->d('1cash ' . json_encode($convertedBalance['cash']));
        $initResponse = [
            "gamesoundurl" => $this->gameSoundUrl,
            "staticsharedurl" => "http://gaming-soft.info/netent/twinSpin/game/current",

            "rs.i0.r.i0.strip" => "SYM10,SYM10,SYM10,SYM7,SYM9,SYM9,SYM5,SYM8,SYM6,SYM11,SYM6,SYM11,SYM8,SYM8,SYM8,SYM11,SYM11,SYM11,SYM7,SYM7,SYM5,SYM8,SYM12,SYM12,SYM12,SYM3,SYM11,SYM4,SYM11,SYM12,SYM9,SYM12,SYM9,SYM8,SYM9,SYM6,SYM12,SYM12,SYM12,SYM7,SYM10,SYM13,SYM13,SYM13,SYM9,SYM12,SYM12,SYM6,SYM11,SYM9,SYM9,SYM9,SYM7,SYM7,SYM7,SYM11,SYM12,SYM7,SYM6,SYM7",
            "rs.i0.r.i1.strip" => "SYM7,SYM10,SYM5,SYM9,SYM5,SYM10,SYM5,SYM10,SYM8,SYM9,SYM10,SYM6,SYM6,SYM6,SYM11,SYM11,SYM11,SYM7,SYM12,SYM13,SYM3,SYM11,SYM11,SYM11,SYM4,SYM5,SYM6,SYM12,SYM12,SYM12,SYM10,SYM5,SYM10,SYM5,SYM11,SYM13,SYM13,SYM13,SYM11,SYM5,SYM9,SYM9,SYM9,SYM11,SYM1,SYM8,SYM5,SYM9,SYM8,SYM8,SYM8,SYM6,SYM11,SYM11,SYM11,SYM4,SYM13,SYM5,SYM5,SYM12,SYM12,SYM12,SYM10,SYM8,SYM8,SYM6,SYM11,SYM11,SYM11,SYM13,SYM13,SYM13,SYM11,SYM5,SYM9,SYM9,SYM9,SYM12,SYM12,SYM5,SYM9",
            "rs.i0.r.i2.strip" => "SYM5,SYM10,SYM10,SYM10,SYM4,SYM9,SYM9,SYM9,SYM5,SYM5,SYM5,SYM8,SYM13,SYM6,SYM10,SYM13,SYM13,SYM13,SYM7,SYM13,SYM13,SYM13,SYM11,SYM3,SYM13,SYM4,SYM9,SYM4,SYM9,SYM11,SYM11,SYM11,SYM12,SYM12,SYM10,SYM10,SYM5,SYM13,SYM8,SYM8,SYM8,SYM11,SYM13,SYM8,SYM6,SYM8,SYM3,SYM3,SYM13,SYM11,SYM11,SYM11,SYM6,SYM4,SYM5,SYM11,SYM13,SYM11,SYM12,SYM4,SYM9,SYM4,SYM9,SYM11,SYM11,SYM11,SYM6,SYM12,SYM12,SYM12,SYM13,SYM13,SYM13,SYM5,SYM13,SYM13,SYM8,SYM13,SYM13,SYM6,SYM5,SYM11,SYM10,SYM11,SYM6,SYM13,SYM11,SYM13,SYM1,SYM9,SYM10,SYM6,SYM12",
            "rs.i0.r.i3.strip" => "SYM12,SYM11,SYM4,SYM4,SYM4,SYM9,SYM3,SYM3,SYM3,SYM13,SYM13,SYM13,SYM6,SYM8,SYM8,SYM8,SYM7,SYM13,SYM11,SYM11,SYM11,SYM13,SYM12,SYM12,SYM12,SYM11,SYM13,SYM4,SYM13,SYM5,SYM10,SYM11,SYM10,SYM11,SYM13,SYM13,SYM13,SYM4,SYM8,SYM8,SYM8,SYM9,SYM9,SYM9,SYM13,SYM5,SYM10,SYM10,SYM10,SYM4,SYM4,SYM4,SYM9,SYM3,SYM3,SYM3,SYM11,SYM11,SYM11,SYM6,SYM8,SYM8,SYM8,SYM7,SYM13,SYM11,SYM11,SYM11,SYM13,SYM12,SYM12,SYM12,SYM11,SYM13,SYM4,SYM13,SYM10,SYM5,SYM10,SYM13,SYM13,SYM13,SYM4,SYM8,SYM8,SYM8,SYM9,SYM9,SYM9,SYM13,SYM1,SYM12,SYM11",
            "rs.i0.r.i4.strip" => "SYM7,SYM10,SYM10,SYM10,SYM9,SYM6,SYM9,SYM13,SYM13,SYM13,SYM11,SYM5,SYM6,SYM9,SYM7,SYM11,SYM8,SYM10,SYM11,SYM6,SYM11,SYM6,SYM11,SYM7,SYM8,SYM8,SYM8,SYM11,SYM11,SYM11,SYM7,SYM9,SYM6,SYM9,SYM12,SYM12,SYM12,SYM3,SYM11,SYM11,SYM11,SYM4,SYM12,SYM12,SYM12,SYM5,SYM10,SYM5,SYM11,SYM9,SYM10,SYM10,SYM10,SYM13,SYM13,SYM13,SYM8,SYM6,SYM8,SYM7,SYM12,SYM6,SYM7,SYM8,SYM9,SYM10,SYM5,SYM11,SYM12,SYM9,SYM9,SYM9,SYM7,SYM12,SYM7,SYM12",
            "rs.i0.r.i5.strip" => "SYM7,SYM10,SYM4,SYM9,SYM6,SYM10,SYM11,SYM6,SYM7,SYM10,SYM5,SYM9,SYM6,SYM11,SYM8,SYM12,SYM7,SYM10,SYM6,SYM13,SYM9,SYM9,SYM9,SYM5,SYM10,SYM5,SYM8,SYM8,SYM8,SYM10,SYM11,SYM6,SYM11,SYM6,SYM11,SYM11,SYM11,SYM10,SYM10,SYM10,SYM7,SYM12,SYM7,SYM13,SYM13,SYM13,SYM3,SYM5,SYM11,SYM11,SYM11,SYM4,SYM4,SYM12,SYM12,SYM12,SYM3,SYM10,SYM6,SYM8,SYM6,SYM13,SYM13,SYM7,SYM9,SYM9,SYM9,SYM7,SYM10,SYM4,SYM10,SYM9,SYM6,SYM9,SYM9,SYM9,SYM5,SYM10,SYM5,SYM8,SYM8,SYM8,SYM6,SYM11,SYM6,SYM11,SYM11,SYM11,SYM10,SYM10,SYM10,SYM7,SYM7,SYM13,SYM13,SYM13,SYM11,SYM11,SYM11,SYM4,SYM8,SYM4,SYM12,SYM12,SYM12,SYM10,SYM6,SYM10,SYM8,SYM7,SYM6,SYM5,SYM13,SYM13,SYM8,SYM6,SYM7,SYM5,SYM11,SYM8,SYM4,SYM9,SYM9,SYM9,SYM13,SYM4,SYM11,SYM1",
            "rs.i0.r.i6.strip" => "SYM3,SYM3,SYM3,SYM10,SYM10,SYM10,SYM4,SYM4,SYM4,SYM9,SYM9,SYM9,SYM5,SYM5,SYM5,SYM8,SYM8,SYM8,SYM6,SYM13,SYM13,SYM13,SYM7,SYM13,SYM13,SYM13,SYM11,SYM11,SYM11,SYM12,SYM12,SYM12,SYM10,SYM10,SYM10,SYM5,SYM8,SYM8,SYM8,SYM6,SYM11,SYM7,SYM10,SYM13,SYM13,SYM13,SYM7,SYM10,SYM10,SYM10,SYM7,SYM10,SYM11,SYM4,SYM4,SYM4,SYM9,SYM9,SYM9,SYM5,SYM5,SYM5,SYM8,SYM8,SYM8,SYM6,SYM13,SYM13,SYM13,SYM7,SYM6,SYM13,SYM11,SYM11,SYM11,SYM12,SYM12,SYM12,SYM10,SYM10,SYM10,SYM5,SYM6,SYM11,SYM8,SYM8,SYM8,SYM6,SYM6,SYM7,SYM8,SYM1,SYM12,SYM11,SYM12",
            "rs.i0.r.i7.strip" => "SYM13,SYM3,SYM10,SYM10,SYM10,SYM13,SYM4,SYM9,SYM10,SYM4,SYM9,SYM9,SYM9,SYM13,SYM11,SYM5,SYM11,SYM6,SYM11,SYM6,SYM13,SYM7,SYM13,SYM8,SYM8,SYM8,SYM11,SYM11,SYM11,SYM13,SYM7,SYM13,SYM5,SYM12,SYM12,SYM12,SYM6,SYM13,SYM3,SYM9,SYM13,SYM11,SYM11,SYM11,SYM4,SYM8,SYM10,SYM11,SYM10,SYM12,SYM12,SYM12,SYM5,SYM10,SYM11,SYM5,SYM10,SYM10,SYM10,SYM7,SYM8,SYM4,SYM10,SYM6,SYM12,SYM7,SYM13,SYM8,SYM10,SYM10,SYM7,SYM8,SYM12,SYM5,SYM11,SYM11,SYM8,SYM8,SYM4,SYM11,SYM9,SYM9,SYM12,SYM12,SYM8,SYM6,SYM9,SYM8,SYM6,SYM13,SYM9,SYM8,SYM6,SYM5,SYM10,SYM7,SYM13,SYM8,SYM12,SYM6,SYM13,SYM9,SYM7,SYM13,SYM13,SYM12,SYM12,SYM11,SYM11,SYM8,SYM12,SYM12,SYM11,SYM11,SYM7,SYM11,SYM11,SYM13,SYM13,SYM13,SYM10,SYM10,SYM6,SYM7,SYM13,SYM9,SYM9,SYM9,SYM7,SYM12,SYM7,SYM4,SYM13,SYM8,SYM13,SYM13,SYM13",
            "rs.i0.r.i8.strip" => "SYM3,SYM8,SYM9,SYM3,SYM10,SYM13,SYM13,SYM4,SYM9,SYM10,SYM4,SYM9,SYM9,SYM9,SYM5,SYM10,SYM12,SYM5,SYM8,SYM8,SYM8,SYM6,SYM11,SYM6,SYM11,SYM11,SYM11,SYM10,SYM10,SYM10,SYM13,SYM13,SYM7,SYM13,SYM12,SYM7,SYM13,SYM13,SYM13,SYM3,SYM11,SYM11,SYM11,SYM4,SYM13,SYM13,SYM5,SYM13,SYM12,SYM12,SYM12,SYM10,SYM10,SYM10,SYM8,SYM8,SYM8,SYM6,SYM7,SYM9,SYM9,SYM9,SYM3,SYM8,SYM9,SYM3,SYM10,SYM13,SYM13,SYM4,SYM9,SYM10,SYM4,SYM9,SYM9,SYM9,SYM5,SYM10,SYM12,SYM5,SYM8,SYM8,SYM8,SYM6,SYM11,SYM6,SYM11,SYM11,SYM11,SYM10,SYM10,SYM10,SYM13,SYM7,SYM13,SYM4,SYM13,SYM12,SYM13,SYM13,SYM13,SYM3,SYM11,SYM11,SYM11,SYM4,SYM13,SYM13,SYM5,SYM13,SYM12,SYM12,SYM12,SYM10,SYM10,SYM10,SYM8,SYM8,SYM8,SYM6,SYM7,SYM9,SYM9,SYM9,SYM1,SYM12",
            "rs.i0.r.i9.strip" => "SYM13,SYM13,SYM13,SYM8,SYM8,SYM8,SYM3,SYM8,SYM10,SYM10,SYM10,SYM4,SYM9,SYM9,SYM9,SYM4,SYM9,SYM9,SYM9,SYM13,SYM13,SYM6,SYM11,SYM6,SYM8,SYM8,SYM8,SYM11,SYM11,SYM11,SYM7,SYM6,SYM5,SYM7,SYM6,SYM5,SYM9,SYM8,SYM5,SYM4,SYM8,SYM11,SYM6,SYM8,SYM12,SYM12,SYM12,SYM3,SYM11,SYM4,SYM12,SYM12,SYM11,SYM11,SYM11,SYM12,SYM12,SYM12,SYM11,SYM11,SYM11,SYM5,SYM13,SYM11,SYM6,SYM10,SYM10,SYM10,SYM5,SYM12,SYM12,SYM12,SYM10,SYM10,SYM10,SYM8,SYM8,SYM8,SYM13,SYM9,SYM4,SYM5,SYM6,SYM13,SYM13,SYM13,SYM6,SYM9,SYM9,SYM9,SYM5,SYM6,SYM13,SYM7,SYM12,SYM7,SYM13,SYM13,SYM11,SYM10,SYM11,SYM5,SYM13,SYM12,SYM4,SYM8,SYM7,SYM8,SYM4,SYM13,SYM8,SYM12,SYM4,SYM12,SYM10,SYM7,SYM12,SYM4,SYM11,SYM6,SYM10,SYM9,SYM10,SYM4,SYM12,SYM11,SYM12,SYM11,SYM12,SYM3,SYM8,SYM8,SYM8,SYM10,SYM3,SYM10,SYM10,SYM10,SYM4,SYM5,SYM6,SYM9,SYM9,SYM9,SYM4,SYM9,SYM9,SYM9,SYM6,SYM11,SYM6,SYM8,SYM8,SYM8,SYM11,SYM11,SYM11,SYM7,SYM6,SYM5,SYM7,SYM6,SYM5,SYM9,SYM8,SYM13,SYM5,SYM4,SYM12,SYM12,SYM12,SYM3,SYM13,SYM13,SYM3,SYM12,SYM11,SYM12,SYM4,SYM8,SYM8,SYM4,SYM11,SYM11,SYM11,SYM12,SYM12,SYM12,SYM11,SYM11,SYM11,SYM5,SYM6,SYM7,SYM10,SYM10,SYM10,SYM5,SYM6,SYM7,SYM12,SYM12,SYM12,SYM10,SYM10,SYM10,SYM12,SYM8,SYM8,SYM8,SYM4,SYM5,SYM6,SYM13,SYM13,SYM13,SYM6,SYM12,SYM7,SYM11,SYM9,SYM9,SYM9,SYM7,SYM12,SYM7,SYM8,SYM9,SYM10,SYM11,SYM10,SYM11,SYM7,SYM4,SYM13,SYM13,SYM12,SYM12,SYM13,SYM13,SYM12,SYM12,SYM5,SYM11,SYM8,SYM8,SYM9,SYM9,SYM10,SYM6,SYM7,SYM5,SYM10,SYM4,SYM11,SYM6",

            "rs.i1.r.i0.strip" => "SYM10,SYM4,SYM10,SYM9,SYM5,SYM8,SYM6,SYM8,SYM11,SYM7,SYM11,SYM12,SYM3,SYM7,SYM12,SYM4,SYM12,SYM12,SYM12,SYM7,SYM5,SYM5,SYM10,SYM9,SYM9,SYM7,SYM7,SYM7,SYM13,SYM13,SYM7,SYM10,SYM10,SYM9,SYM7,SYM8,SYM6,SYM8,SYM11,SYM7,SYM11,SYM12,SYM7,SYM12,SYM7,SYM10,SYM12,SYM12,SYM12,SYM7,SYM10,SYM9,SYM7,SYM9,SYM7,SYM13,SYM13,SYM13",
            "rs.i1.r.i1.strip" => "SYM11,SYM3,SYM13,SYM4,SYM13,SYM9,SYM8,SYM6,SYM6,SYM6,SYM11,SYM11,SYM7,SYM7,SYM6,SYM11,SYM11,SYM12,SYM6,SYM11,SYM11,SYM11,SYM6,SYM12,SYM5,SYM12,SYM10,SYM11,SYM10,SYM6,SYM11,SYM9,SYM7,SYM9,SYM13,SYM13,SYM13,SYM7,SYM5,SYM11,SYM9,SYM13,SYM13,SYM9,SYM8,SYM8,SYM11,SYM6,SYM11,SYM12,SYM6,SYM11,SYM11,SYM6,SYM12,SYM12,SYM10,SYM11,SYM10,SYM6,SYM11,SYM9,SYM7,SYM9,SYM6,SYM11,SYM13,SYM13,SYM1,SYM11,SYM10",
            "rs.i1.r.i2.strip" => "SYM9,SYM4,SYM9,SYM5,SYM10,SYM5,SYM5,SYM5,SYM8,SYM6,SYM8,SYM11,SYM7,SYM6,SYM10,SYM3,SYM11,SYM5,SYM11,SYM12,SYM5,SYM12,SYM10,SYM10,SYM10,SYM8,SYM6,SYM5,SYM13,SYM13,SYM13,SYM5,SYM10,SYM9,SYM5,SYM9,SYM5,SYM5,SYM5,SYM8,SYM6,SYM8,SYM11,SYM12,SYM5,SYM12,SYM8,SYM6,SYM5,SYM10,SYM5,SYM10,SYM10,SYM10,SYM5,SYM8,SYM10,SYM6,SYM13,SYM13,SYM13,SYM11,SYM6,SYM1,SYM11,SYM8",
            "rs.i1.r.i3.strip" => "SYM10,SYM3,SYM10,SYM4,SYM4,SYM4,SYM12,SYM9,SYM3,SYM10,SYM8,SYM6,SYM8,SYM11,SYM5,SYM11,SYM12,SYM4,SYM12,SYM11,SYM7,SYM7,SYM3,SYM12,SYM13,SYM5,SYM13,SYM10,SYM8,SYM6,SYM8,SYM9,SYM9,SYM9,SYM13,SYM13,SYM13,SYM8,SYM13,SYM8,SYM10,SYM3,SYM10,SYM4,SYM4,SYM9,SYM3,SYM3,SYM8,SYM13,SYM8,SYM11,SYM12,SYM4,SYM12,SYM11,SYM12,SYM13,SYM5,SYM13,SYM10,SYM6,SYM8,SYM11,SYM3,SYM13,SYM6,SYM12,SYM8,SYM6,SYM8,SYM9,SYM9,SYM9,SYM13,SYM13,SYM13,SYM1,SYM12",
            "rs.i1.r.i4.strip" => "SYM3,SYM3,SYM3,SYM10,SYM7,SYM12,SYM9,SYM4,SYM9,SYM8,SYM6,SYM8,SYM12,SYM13,SYM7,SYM13,SYM7,SYM12,SYM6,SYM8,SYM5,SYM9,SYM7,SYM11,SYM3,SYM11,SYM13,SYM12,SYM4,SYM12,SYM10,SYM5,SYM10,SYM8,SYM8,SYM8,SYM6,SYM8,SYM13,SYM13,SYM13,SYM5,SYM9,SYM7,SYM10,SYM4,SYM4,SYM4,SYM12,SYM9,SYM4,SYM9,SYM8,SYM6,SYM8,SYM11,SYM3,SYM12,SYM13,SYM7,SYM13,SYM12,SYM11,SYM3,SYM11,SYM12,SYM4,SYM12,SYM10,SYM5,SYM10,SYM8,SYM8,SYM8,SYM6,SYM5,SYM9,SYM13,SYM13,SYM13,SYM1,SYM11,SYM6",

            "rs.i0.r.i0.syms" => "SYM10,SYM10,SYM10",
            "rs.i0.r.i1.syms" => "SYM7,SYM10,SYM5",
            "rs.i0.r.i2.syms" => "SYM5,SYM10,SYM10",
            "rs.i0.r.i3.syms" => "SYM12,SYM11,SYM4",
            "rs.i0.r.i4.syms" => "SYM7,SYM10,SYM10",
            "rs.i0.r.i5.syms" => "SYM7,SYM10,SYM4",
            "rs.i0.r.i6.syms" => "SYM3,SYM3,SYM3",
            "rs.i0.r.i7.syms" => "SYM13,SYM3,SYM10",
            "rs.i0.r.i8.syms" => "SYM3,SYM8,SYM9",
            "rs.i0.r.i9.syms" => "SYM13,SYM13,SYM13",

            "rs.i1.r.i0.syms" => "SYM10,SYM4,SYM10",
            "rs.i1.r.i1.syms" => "SYM11,SYM3,SYM13",
            "rs.i1.r.i2.syms" => "SYM9,SYM4,SYM9",
            "rs.i1.r.i3.syms" => "SYM10,SYM3,SYM10",
            "rs.i1.r.i4.syms" => "SYM3,SYM3,SYM3",

            "rs.i0.r.i0.pos" => "0",
            "rs.i0.r.i1.pos" => "0",
            "rs.i0.r.i2.pos" => "0",
            "rs.i0.r.i3.pos" => "0",
            "rs.i0.r.i4.pos" => "0",
            "rs.i0.r.i5.pos" => "0",
            "rs.i0.r.i6.pos" => "0",
            "rs.i0.r.i7.pos" => "0",
            "rs.i0.r.i8.pos" => "0",
            "rs.i0.r.i9.pos" => "0",

            "rs.i1.r.i0.pos" => "0",
            "rs.i1.r.i1.pos" => "0",
            "rs.i1.r.i2.pos" => "0",
            "rs.i1.r.i3.pos" => "0",
            "rs.i1.r.i4.pos" => "0",

            "rs.i0.r.i0.hold" => 'false',
            "rs.i0.r.i1.hold" => 'false',
            "rs.i0.r.i2.hold" => 'false',
            "rs.i0.r.i3.hold" => 'false',
            "rs.i0.r.i4.hold" => 'false',
            "rs.i0.r.i5.hold" => 'false',
            "rs.i0.r.i6.hold" => 'false',
            "rs.i0.r.i7.hold" => 'false',
            "rs.i0.r.i8.hold" => 'false',
            "rs.i0.r.i9.hold" => 'false',

            "rs.i1.r.i0.hold" => 'false',
            "rs.i1.r.i1.hold" => 'false',
            "rs.i1.r.i2.hold" => 'false',
            "rs.i1.r.i3.hold" => 'false',
            "rs.i1.r.i4.hold" => 'false',

            "rs.i0.r.i0.id" => "twinreel-0-1",
            "rs.i0.r.i1.id" => "twinreel-1-2",
            "rs.i0.r.i2.id" => "twinreel-2-3",
            "rs.i0.r.i3.id" => "twinreel-3-4",
            "rs.i0.r.i4.id" => "twinreel-0-1-2",
            "rs.i0.r.i5.id" => "twinreel-1-2-3",
            "rs.i0.r.i6.id" => "twinreel-2-3-4",
            "rs.i0.r.i7.id" => "twinreel-0-1-2-3",
            "rs.i0.r.i8.id" => "twinreel-1-2-3-4",
            "rs.i0.r.i9.id" => "twinreel-0-1-2-3-4",

            "rs.i1.r.i0.id" => "basic-0",
            "rs.i1.r.i1.id" => "basic-1",
            "rs.i1.r.i2.id" => "basic-2",
            "rs.i1.r.i3.id" => "basic-3",
            "rs.i1.r.i4.id" => "basic-4",

            "rs.i0.id" => "twinreels",
            "bl.i0.line" => "0/1/2,0/1/2,0/1/2,0/1/2,0/1/2",
            "bl.i0.coins" => "25",
            "bl.i0.id" => "0",
            "bl.i0.reelset" => "ALL",
            "rs.i1.id" => "basic",
            "bl.standard" => "0",
            "autoplay" => "10,25,50,75,100,250,500,750,1000",
            "denomination.all" => "1,2,5,10,20,50",
            "betlevel.all" => "1,2,3,4,5,6,7,8,9,10",
            "gameEventSetters.enabled" => 'false',
            "gamestate.current" => "basic",
            "confirmBetMessageEnabled" => 'false',
            "game.win.cents" => "0",
            "betlevel.standard" => "1",
            "clientaction" => "init",
            "playercurrencyiso" => "EUR",
            "historybutton" => 'false',
            "nextaction" => "spin",
            "game.win.coins" => "0",
            "totalwin.cents" => "0",
            "multiplier" => "1",
            "gameover" => 'true',
            "wavecount" => "1",
            "restore" => 'false',
            "game.win.amount" => "null",
            "isJackpotWin" => 'false',
            "jackpotcurrencyiso" => "EUR",
            "jackpotcurrency" => "€",
            "playercurrency" => "€",
            "denomination.standard" => "5",
            "g4mode" => 'false',
            "autoplayLossLimitEnabled" => 'false',
            "nearwinallowed" => 'true',
            "iframeEnabled" => 'true',
            "playforfun" => 'true',
            "totalwin.coins" => "0",
            "credit" => $convertedBalance['cash']
        ];
        return print(http_build_query($initResponse));
    }
}