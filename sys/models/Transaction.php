<?php

require_once "classes/logger.class.php";
require_once "classes/databaseConnection.class.php";


class Transaction
{
    private $db;
    private $log;

    function __construct()
    {
        $dbClass = new DatabaseConnection;
        $this->db = $dbClass->db;

        $this->log = new Logger;
    }

    public function save($uid, $sessid, $action, $betLevel, $d, $bet, $totalWinCoins, $totalWinCents, $totalAmount, $balance, $params, $type = null)
    {
        $paramsJson = json_encode($params);

        $sql = ' INSERT INTO `neon_transactions` ( uid, sessid, action, betlevel, denomination, bet, total_win_coins, total_win_cents, total_amount, balance, params, type ) '.
                ' VALUES (:uid, :sessid, :action, :betlevel, :denomination, :bet, :total_win_coins, :total_win_cents, :total_amount, :balance, :params, :type ) ';
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':uid',                $uid, PDO::PARAM_INT);
        $stmt->bindParam(':sessid',             $sessid, PDO::PARAM_STR);
        $stmt->bindParam(':action',             $action, PDO::PARAM_STR);
        $stmt->bindParam(':betlevel',           $betLevel, PDO::PARAM_INT);
        $stmt->bindParam(':denomination',       $d, PDO::PARAM_INT);
        $stmt->bindParam(':bet',                $bet, PDO::PARAM_INT);
        $stmt->bindParam(':total_win_coins',    $totalWinCoins, PDO::PARAM_INT);
        $stmt->bindParam(':total_win_cents',    $totalWinCents, PDO::PARAM_INT);
        $stmt->bindParam(':total_amount',       $totalAmount, PDO::PARAM_INT);
        $stmt->bindParam(':balance',            $balance, PDO::PARAM_INT);
        $stmt->bindParam(':params',             $paramsJson, PDO::PARAM_STR);
        $stmt->bindParam(':type',               $type, PDO::PARAM_STR);

        if ( $stmt->execute() ) return TRUE;
        else {
            
            $this->log->e($action . ' transaction was not saved ##### code: ' . $stmt->errorInfo()[0] . ' - ' . $stmt->errorInfo()[2]);
            
            return FALSE; 

        }
    }
}