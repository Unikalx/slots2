<?php
require_once "logger.php";

class MoneyManager
{
    public $denominationStandard = 50;
    public $denominations;
    public $denominationsWins;
    public $betLevels;
    public $log;

    public function __construct()
    {
        $this->denominationsWins = [
            50 => 100,
            20 => 40,
            10 => 20,
            5 => 10,
            2 => 4,
            1 => 2,
        ];
        $this->denominations = [
            1 => 100,
            2 => 50,
            5 => 20,
            10 => 10,
            20 => 5,
            50 => 2];

        $this->betLevels = 0.25;

        $this->log = new Logger;
    }

    public function convertBalance($main, $denomination = null)
    {
        $denomination = ($denomination == null) ? $denomination = $this->denominationStandard : $denomination;

        $converted['cash'] = intval($main * 100);
        $converted['cents'] = intval($main * $denomination);
        $converted['coins'] = intval($main * $this->denominations[$denomination]);

        return $converted;
    }
    public function betLevels($betLevels, $denomination)
    {
        return $this->betLevels * $betLevels * $denomination;
    }
}