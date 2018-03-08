<?php
require_once "logger.php";
require_once "databaseConnection.php";

class Sql
{
    public $log;
    public $db;

    function __construct()
    {
        $this->log = new Logger;
        $dbClass = new DatabaseConnection;
        $this->db = $dbClass->db;
    }

    public function deleteUserSessions()
    {
        $sql = 'DELETE FROM `twinspin_user_sessions` WHERE `twinspin_user_sessions`.`uid` = 1';
        $delete = $this->db->prepare($sql);
        $delete->execute();
    }
    public function insertUserSessions($uid, $sessId)
    {
        $sql = 'INSERT INTO `twinspin_user_sessions` (uid, sessid) VALUES (:uid, :sessid)';
        $insert = $this->db->prepare($sql);

        $insert->bindValue(':uid', $uid, PDO::PARAM_INT);
        $insert->bindValue(':sessid', $sessId, PDO::PARAM_STR);

        return ($insert->execute()) ? TRUE : FALSE;
    }
    public function updateStatus($uid)
    {
        $sql = 'UPDATE `twinspin_user_sessions` SET status=0 WHERE uid=:uid';
        $update = $this->db->prepare($sql);
        $update->bindValue(':uid', $uid, PDO::PARAM_INT);
        $update->execute();

        return ($update->execute()) ? TRUE : FALSE;
    }
    public function getUser($uid)
    {
        $sql = "SELECT * FROM `users` WHERE uid=:uid";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return ($stmt->execute()) ? $stmt->fetch() : FALSE;
    }
    public function getUserData($sessid)
    {
        $sql = 'SELECT c.*, s_user_sess.sessid FROM `users` AS c JOIN `twinspin_user_sessions` AS s_user_sess ON c.uid = s_user_sess.uid WHERE s_user_sess.sessid=:sessid AND s_user_sess.status = 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':sessid', $sessid, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return ($stmt->execute()) ? $stmt->fetch() : FALSE;
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
    public function userSave($user, $balance)
    {
        $sql = 'UPDATE `users` SET balance=:balance WHERE uid=:uid';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uid', $user['uid'], PDO::PARAM_INT);
        $stmt->bindParam(':balance', $balance);

        return ($stmt->execute()) ? TRUE : FALSE;
    }
}