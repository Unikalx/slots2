<?php
class DatabaseConnection
{
    public $db;

    function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=gamingso_slots_db;charset=utf8";
        $user = 'gamingsoft';
        $pass = 'fyI8Z11LWqm49qRlscEK';

        $this->db = new PDO($dsn, $user, $pass);
    }
    
}