<?php

if (!defined('SQLITE3_ASSOC')) {
    define('SQLITE3_ASSOC', 1);
}

class MySQL_Bridge {
    private $mysqli;

    public function __construct($host, $user, $pass, $db) {
        $this->mysqli = @new mysqli($host, $user, $pass, $db);
        if ($this->mysqli->connect_error) {
            die("Connection not successful: " . $this->mysqli->connect_error);
        }
    }

    public function exec($query) {
        $res = $this->mysqli->query($query);
        return $res !== false;
    }

    public function query($query) {
        $result = $this->mysqli->query($query);
        if ($result === false) {
            return false;
        }
        if ($result === true) {
            return true;
        }
        return new MySQL_Bridge_Result($result);
    }

    public function querySingle($query) {
        $result = $this->mysqli->query($query);
        if ($result && $row = $result->fetch_row()) {
            return $row[0];
        }
        return null;
    }

    public function escapeString($string) {
        return $this->mysqli->real_escape_string($string);
    }

    public function lastErrorMsg() {
        return $this->mysqli->error;
    }
}

class MySQL_Bridge_Result {
    private $result;

    public function __construct($result) {
        $this->result = $result;
    }

    public function fetchArray($mode = SQLITE3_ASSOC) {
        return $this->result->fetch_assoc();
    }
}

// Connect to local MySQL database using credentials from config file
require_once __DIR__ . '/db_config.php';
$con = new MySQL_Bridge(DB_HOST, DB_USER, DB_PASS, DB_NAME);

?>
