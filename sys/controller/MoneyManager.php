<?php
require_once "Logger.php";

class MoneyManager
{
    public $denominationStandard = 5;
    public $denominations;

    public $betLevelStandard = 1;
    public $betLevels;

//    public $betStandard = 20;
    public $betStandard = 25;

    public $Log;

    public function __construct()
    {
        $this->Log = new Logger;

        $this->denominations = [1, 2, 5, 10, 20, 50];
        $this->betLevels = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    }


    public function setDenomination($d)
    {
        $d = intval($d);
        if (in_array($d, $this->denominations)) return $d;
        else return $this->denominationStandard;
    }

    public function setBetLevel($betBetLevel, $betDenomination = 1)
    {
        $betBetLevel = intval($betBetLevel);

        if (in_array($betBetLevel, $this->betLevels)) {
            $result['betlevel'] = $betBetLevel;
            $result['bet'] = $this->betStandard * $betBetLevel;
        } else {
            $result['betlevel'] = $this->betLevelStandard;
            $result['bet'] = $this->betStandard;
        }
        $result['betPrice'] = 0.25 * $betDenomination * $betBetLevel;
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
        $balanceResult = $coins / 100 * $d;

        $result['balanceCoins'] = $coins;
        $result['balanceCents'] = $cents;
        $result['balance'] = $balanceResult;
        return $result;
    }

    public function convertBalance($main, $denomination = null)
    {
        if ($denomination == null) $denomination = $this->denominationStandard;
        if (in_array($denomination, $this->denominations)) {
            $converted['coins'] = intval($main * 100 / $denomination);
            $converted['cents'] = intval($main * 100);
            $converted['denominBalance'] = intval($main * $denomination);
            return $converted;
        }
    }
}