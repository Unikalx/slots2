<?php
require_once "Logger.php";

class MoneyManager
{
    public $denomination_standart = 5;
    public $denominations;

    public $betlevel_standart = 1;
    public $betlevels;

    public $bet_standart = 20;

    public $log;

    public function __construct()
    {
        $this->log = new Logger;

        $this->denominations = [1, 2, 5, 10, 20, 50];
        $this->betlevels = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    }

    public function convertBalance($main, $denomination = null)
    {
        if ($denomination == null) $denomination = $this->denomination_standart;

        if (in_array($denomination, $this->denominations)) {

            $converted['coins'] = intval($main * 100 / $denomination);

            $converted['cents'] = intval($main * 100);

            return $converted;
        }
    }

    public function setDenomination($d)
    {
        $d = intval($d);

        if (in_array($d, $this->denominations)) return $d;

        else return $this->denomination_standart;

    }

    public function setBetLevel($l)
    {
        $l = intval($l);

        if (in_array($l, $this->betlevels)) {

            $result['betlevel'] = $l;

            $result['bet'] = $this->bet_standart * $l;

        } else {

            $result['betlevel'] = $this->betlevel_standart;

            $result['bet'] = $this->bet_standart;

        }

        return $result;
    }

    public function changeBalance($balance, $betlevel, $d, $gain = null)
    {
        $convertedBalance = $this->convertBalance($balance, $d);

        if (!is_null($gain)) {

            $coins = $convertedBalance['coins'] - $betlevel['bet'] + $gain['sum'];

            $result = [];
            $result['bet'] = $betlevel['bet'];
            $result['betlevel'] = $betlevel['betlevel'];
            $result['gameWinAmount'] = $gain['sum'] / 100 * $d;
            $result['totalwinCoins'] = $gain['sum'];
            $result['totalwinCents'] = $gain['sum'] * $d;

            unset($gain['sum']);
            foreach ($gain as $k => $value) {

                $result['symbols'][$k]['coins'] = $value;
                $result['symbols'][$k]['cents'] = $value * $d;

            }


        } else {

            $bet = $this->setBetlevel($betlevel);

            $coins = $convertedBalance['coins'] - $bet['bet'];

            $result = [
                'bet' => $bet['bet'],
                'betlevel' => $bet['betlevel'],
            ];

        }

        $cents = $coins * $d;
        $balance = $coins / 100 * $d;

        $result['balanceCoins'] = $coins;
        $result['balanceCents'] = $cents;
        $result['balance'] = $balance;

        return $result;
    }

    public function convertBalanceTwinSpin($main, $denomination = null)
    {
        $denominationsArray = [
            1 => 100,
            2 => 50,
            5 => 20,
            10 => 10,
            20 => 5,
            50 => 2];
        $denominationStandard = 50;
        $denomination = ($denomination == null) ? $denomination = $denominationStandard : $denomination;

        $converted['cash'] = intval($main * 100);
        $converted['cents'] = intval($main * $denomination);
        $converted['coins'] = intval($main * $denominationsArray[$denomination]);

        return $converted;
    }

}