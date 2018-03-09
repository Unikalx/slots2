<?php

require_once "controller/Logger.php";
require_once "DatabaseConnection.php";

class User
{
    private $db;
    private $Log;

    function __construct()
    {
        $dbClass = new DatabaseConnection;

        $this->db = $dbClass->db;

        $this->Log = new Logger;
    }

    public function getUserBySession($sessid)
    {
        $sql = 'SELECT c.*, us.sessid FROM `users` AS c JOIN `user_sessions` AS us ON c.uid = us.uid WHERE us.sessid=:sessid AND us.status = 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':sessid', $sessid, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($stmt->execute()) return $stmt->fetch();
        else {

            $this->Log->e('##### code: ' . $stmt->errorInfo()[0] . ' - ' . $stmt->errorInfo()[2]);

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

            $this->Log->e('uid - ' . $uid . ' ##### code: ' . $stmt->errorInfo()[0] . ' - ' . $stmt->errorInfo()[2]);

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

            $this->Log->e('uid - ' . $uid . ' session statuses weren\'t nulled ##### code: ' . $update->errorInfo()[0] . ' - ' . $update->errorInfo()[2]);

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

            $this->Log->e('uid - ' . $uid . ' session id wasn\'t inserted to db ##### code: ' . $result->errorInfo()[0] . ' - ' . $result->errorInfo()[2]);

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

            $this->Log->e($uid . ' - user was not updated ##### code: ' . $stmt->errorInfo()[0] . ' - ' . $stmt->errorInfo()[2]);

            return FALSE;

        }
    }
}