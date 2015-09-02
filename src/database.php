<?php

    class Database
    {
        private $mysqli;

        public function __construct($hostname, $username, $password, $database)
        {
            $this->mysqli = new mysqli($hostname, $username, $password);

            if ($this->mysqli->connect_error) {
                trigger_error('Error: Could not make a database link (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
            } else if (isset($database) && !empty($database)) {
                $this->mysqli->select_db($database);
            }

            $this->mysqli->query("SET NAMES 'utf8'");
            $this->mysqli->query("SET CHARACTER SET utf8");
            $this->mysqli->query("SET CHARACTER_SET_CONNECTION=utf8");
            $this->mysqli->query("SET SQL_MODE = ''");
        }

        public function create_db($db)
        {
            $result = true;
            if ($this->mysqli->query('CREATE DATABASE ' . $db)) {
                if (!$this->mysqli->select_db($db)) {
                    $result = $this->mysqli->error;
                }
            } else {
                $result = $this->mysqli->error;
            }
            return $result;
        }

        public function query($sql)
        {
            $result = $this->mysqli->query($sql);

            if ($result && $this->mysqli->errno === 0) {
                if (isset($result->num_rows) && $result->num_rows > 0) {
                    $i = 0;

                    $data = array();

                    while ($row = $result->fetch_object()) {
                        $data[$i] = $row;
                        $i++;
                    }

                    $result->close();

                    $query = new stdClass();
                    $query->row = isset($data[0]) ? $data[0] : array();
                    $query->rows = $data;
                    $query->num_rows = $result->num_rows;

                    unset($data);
                    return $query;
                } else if ($result === true) {
                    return true;
                }

            } else {
                return $this->mysqli->error;
            }
        }

        public function escape($value)
        {
            return $this->mysqli->real_escape_string($value);
        }

        public function countAffected()
        {
            return $this->mysqli->affected_rows;
        }

        public function getLastId()
        {
            return $this->mysqli->insert_id;
        }

        public function __destruct()
        {
            $this->mysqli->close();
        }

    }