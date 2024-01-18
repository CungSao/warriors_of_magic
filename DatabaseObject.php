<?php
/**
 * Database.php
 * DatabaseObject to handle managing database connection and queries
 */
    class DatabaseObject {
        private $con;

        public function __construct($host, $username, $password, $db) {
            $this->con = mysqli_connect($host, $username, $password, $db, 3306);
            if (!$this->con) {
                throw new Exception('Error connecting to db!');
            }
        }

        public function clean($data) {
            return mysqli_real_escape_string($this->con, $data);
        }

        public function error($error) {
            die($error);
        }

        public function query($query) {
            $result = mysqli_query($this->con, $query) or $this->error(mysqli_error());
            return $result;
        }

        public function fetch($result) {
            return mysqli_fetch_assoc($result);
        }

        public function num_rows($result) {
            return mysqli_num_rows($result);
        }

        public function affected_rows() {
            return mysqli_affected_rows($this->con);
        }
    }
?>