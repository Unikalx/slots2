<?php
require_once "controller/Logger.php";
require_once "DatabaseConnection.php";


class Transaction
{
    private $db;
    private $Log;

    function __construct()
    {
        $dbClass = new DatabaseConnection;
        $this->db = $dbClass->db;

        $this->Log = new Logger;
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
            
            $this->Log->e($action . ' transaction was not saved ##### code: ' . $stmt->errorInfo()[0] . ' - ' . $stmt->errorInfo()[2]);
            
            return FALSE; 

        }
    }

    /*twinspin_transactions*/
    public function saveTransaction($saveTransaction)
    {
        $sql = ' INSERT INTO `twinspin_transactions` (uid, sessid, action, bet, betlevel, playerBalanceCents, playerBalanceCoins, denomination, preCombination, totalWinCents, totalWinCoins,  balance, calculBigWin) ' .
            ' VALUES (:uid, :sessid, :action, :bet, :betlevel, :playerBalanceCents, :playerBalanceCoins, :denomination, :preCombination, :totalWinCents, :totalWinCoins, :balance, :calculBigWin ) ';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uid', $saveTransaction["uid"], PDO::PARAM_INT);
        $stmt->bindParam(':sessid', $saveTransaction["sessid"], PDO::PARAM_STR);
        $stmt->bindParam(':action', $saveTransaction["action"], PDO::PARAM_STR);
        $stmt->bindParam(':bet', $saveTransaction["bet"], PDO::PARAM_INT);
        $stmt->bindParam(':betlevel', $saveTransaction["betlevel"], PDO::PARAM_INT);
        $stmt->bindParam(':playerBalanceCents', $saveTransaction["playerBalanceCents"], PDO::PARAM_INT);
        $stmt->bindParam(':playerBalanceCoins', $saveTransaction["playerBalanceCoins"], PDO::PARAM_INT);
        $stmt->bindParam(':denomination', $saveTransaction["denomination"], PDO::PARAM_INT);
        $stmt->bindParam(':preCombination', $saveTransaction["preCombination"], PDO::PARAM_STR);
        $stmt->bindParam(':totalWinCents', $saveTransaction["totalWinCents"], PDO::PARAM_INT);
        $stmt->bindParam(':totalWinCoins', $saveTransaction["totalWinCoins"], PDO::PARAM_INT);
        $stmt->bindParam(':balance', $saveTransaction["balance"], PDO::PARAM_INT);
        $stmt->bindParam(':calculBigWin', $saveTransaction["calculBigWin"], PDO::PARAM_INT);

        return ($stmt->execute()) ? TRUE : FALSE;
    }
    public function getUserTransactionsInit($sessid)
    {
        $sql = "SELECT t.calculBigWin FROM `twinspin_transactions` AS t JOIN `twinspin_user_sessions` AS s_user_sess ON t.uid = s_user_sess.uid WHERE t.sessid=:sessid AND s_user_sess.status = 1 AND `action` = 'init' ORDER by t.id DESC Limit 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':sessid', $sessid, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return ($stmt->execute()) ? $stmt->fetch() : FALSE;
    }
    public function updateUserTransactionsInit($sessid, $calculBigWin)
    {
        $sql = "UPDATE `twinspin_transactions` SET calculBigWin=:calculBigWin WHERE sessid=:sessid AND `action` = 'init' ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':sessid', $sessid, PDO::PARAM_STR);
        $stmt->bindParam(':calculBigWin', $calculBigWin, PDO::PARAM_INT);
        return ($stmt->execute()) ? TRUE : FALSE;
    }
    public function getUserTransactionsSpin($sessid)
    {
        $result = [];
        $sql = "SELECT t.balance FROM `twinspin_transactions` AS t JOIN `twinspin_user_sessions` AS s_user_sess ON t.uid = s_user_sess.uid WHERE t.sessid= '" . $sessid . "' AND s_user_sess.status = 1 AND `action` = 'spin' ORDER by t.id DESC Limit 5";
        $stmt = $this->db->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        while ($row = $stmt->fetch()) {
            array_push($result, $row);
        }
        return ($result == []) ? false : $result;
    }
    /*end_twinspin_transactions*/
}