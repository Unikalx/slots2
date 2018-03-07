<?php

require_once "controller/Logger.php";
require_once "DatabaseConnection.php";

class User
{
    private $db;
    private $log;

    function __construct()
    {
        $dbClass = new DatabaseConnection;

        $this->db = $dbClass->db;

        $this->log = new Logger;
    }

    public function test()
    {
        return 'testFunction';
    }

    public function getUserBySession($sessid)
    {
        $sql = 'SELECT c.*, us.sessid FROM `users` AS c JOIN `user_sessions` AS us ON c.uid = us.uid WHERE us.sessid=:sessid AND us.status = 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':sessid', $sessid, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($stmt->execute()) return $stmt->fetch();
        else {

            $this->log->e('##### code: ' . $stmt->errorInfo()[0] . ' - ' . $stmt->errorInfo()[2]);

            return FALSE;

        }

    }

    public function getByUid($uid)
    {
        $sql = "SELECT * FROM `users` WHERE uid=:uid";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($stmt->execute()) return $stmt->fetch();
        else {

            $this->log->e('uid - ' . $uid . ' ##### code: ' . $stmt->errorInfo()[0] . ' - ' . $stmt->errorInfo()[2]);

            return FALSE;

        }
    }

    public function resetSessionStatuses($uid)
    {
        $sql = 'UPDATE `user_sessions` SET status=0 WHERE uid=:uid';
        $update = $this->db->prepare($sql);
        $update->bindValue(':uid', $uid, PDO::PARAM_INT);

        if ($update->execute()) return TRUE;
        else {

            $this->log->e('uid - ' . $uid . ' session statuses weren\'t nulled ##### code: ' . $update->errorInfo()[0] . ' - ' . $update->errorInfo()[2]);

            return FALSE;

        }

    }

    public function createSession($uid, $sessid)
    {
        $sql = 'INSERT INTO `user_sessions` (uid, sessid) VALUES (:uid, :sessid)';
        $result = $this->db->prepare($sql);
        $result->bindValue(':uid', $uid, PDO::PARAM_INT);
        $result->bindValue(':sessid', $sessid, PDO::PARAM_STR);

        if ($result->execute()) return TRUE;
        else {

            $this->log->e('uid - ' . $uid . ' session id wasn\'t inserted to db ##### code: ' . $result->errorInfo()[0] . ' - ' . $result->errorInfo()[2]);

            return FALSE;

        }

    }

    public function save($uid, $balance)
    {
        $sql = 'UPDATE `users` SET balance=:balance WHERE uid=:uid';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stmt->bindParam(':balance', $balance);

        if ($stmt->execute()) return TRUE;
        else {

            $this->log->e($uid . ' - user was not updated ##### code: ' . $stmt->errorInfo()[0] . ' - ' . $stmt->errorInfo()[2]);

            return FALSE;

        }
    }
    /*twinspin_sql*/
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
    /*end_twinspin_sql*/
}