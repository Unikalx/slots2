<?php

class Logger
{

    private $log_file;

    function __construct()
    {
        $log_file = "../storage/debug.log";
        date_default_timezone_set('Europe/Kiev');
        $this->log_file = $log_file;

        if (!file_exists($log_file)) {
            touch($log_file);
        }

        if (!is_writable($log_file)) {
            new Exception("LOGGER ERROR: Can't write to log", 1);
        }
    }

    public function d($message)
    {
        $this->writeToLog("DEBUG", $message);
    }
    public function e($message)
    {
        $this->writeToLog("ERROR", $message);
    }
    public function w($message)
    {
        $this->writeToLog("WARNING", $message);
    }
    public function i($message)
    {
        $this->writeToLog("INFO", $message);
    }
    public function graphic($message)
    {
        $this->graphicToLog($message);
    }
    private function writeToLog($status, $message)
    {
        $date = date('[Y-m-d H:i:s]');
        $msg = "$date: [$status] -\n $message" . PHP_EOL;
        file_put_contents($this->log_file, $msg, FILE_APPEND);
    }
    private function graphicToLog($message)
    {
        $msg = "{\"column\":" . "\"$message\"" . "}," . PHP_EOL;
        file_put_contents($this->log_file, $msg, FILE_APPEND);
    }
}
