<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

// DB abstraction
class Database
{

    public $username;

    public $password;

    public $hostname;

    public $database;

    public $pdo;

    public $pdo_statement;

    public $row = [];

    public $pos;

    public $error;

    public static $started_transaction = false;

    public static $stop_doing_queries = false;

    public function __construct($username = "", $password = "", $hostname = "", $database = "")
    {
        // Constructor
        $this->username = $username;
        $this->password = $password;
        $this->hostname = $hostname;
        $this->database = $database;
    }

    /**
     * Connects to the database using PDO if not already connected, or if $force
     * is set to true.
     *
     * @param bool $force Optional. If true, a new connection will be
     * established even if one already exists. Default is false.
     * @return bool True if the connection is successful, false otherwise.
     */
    public function connect($force = false): bool
    {
        if ($force || $this->pdo === null) {
            try {
                $host = $this->hostname ? sprintf('host=%s;', $this->hostname) : "";
                $dbname = $this->database ? sprintf('dbname=%s;', $this->database) : "";

                $this->pdo = new PDO(
                    sprintf('mysql:%s%scharset=UTF8', $host, $dbname),
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    ]
                );

                return true;
            } catch (PDOException $e) {
                $this->error(sprintf('Unable to connect to database: %s', $e->getMessage()));
                return false;
            }
        }

        return false;
    }

    public function start_transaction()
    {
        $this->connect();
        $this->pdo->beginTransaction();
        self::$started_transaction = true;
    }

    public function commit()
    {
        if (self::$started_transaction && is_object($this->pdo)) {
            $rtn = $this->pdo->commit();
            if (!$rtn) {
                $this->error("Couldn't commit db transaction.");
            }
        }
    }

    public function rollback()
    {
        if (self::$started_transaction) {
            self::$started_transaction = false;
            try {
                $this->pdo->rollBack();
            } catch (Exception $e) {
                return false;
            }
        }
    }

    public function error($msg = false, $errno = false)
    {
        $m = null;
        if ($errno == 1451 || $errno == 1217) {
            $m = "Error: " . $errno . " There are other records in the database that depend on the item you just tried to delete.
            Remove those other records first and then try to delete this item again.
            <br><br>" . $msg;
        } elseif ($errno == 1216) {
            $m = "Error: " . $errno . " The parent record of the item you just tried to create does not exist in the database.
            Create that other record first and then try to create this item again.
            <br><br>" . $msg;
        } elseif (preg_match("/(ALLOC ERROR:([^']*)')/m", $msg, $matches)) {
            $m = "Error: " . $matches[2];
        } elseif ($msg) {
            $m = "Error: " . $msg;
        }

        if ($m !== null) {
            alloc_error($m);
        }

        $this->error = $msg;
    }

    public function get_error(): string
    {
        return trim($this->error);
    }

    public function get_insert_id()
    {
        return $this->pdo->lastInsertId();
    }

    public function esc($str)
    {
        if (is_numeric($str)) {
            return $str;
        }

        if ($this->pdo === null) {
            $this->connect();
        }

        $v = $this->pdo->quote($str);
        if (substr($v, -1) == "'") {
            $v = substr($v, 0, -1);
        }

        if (substr($v, 0, 1) == "'") {
            return substr($v, 1);
        }

        return $v;
    }

    /**
     * Executes a SQL query and returns a single row of the result set
     *
     * @deprecated Don't use this function, use PDO::prepare() and friends
     * instead.
     *
     * @param mixed ...$params Query parameters to be escaped before execution
     * @return mixed|null Returns a single row of the result set or null if the
     *                    query returned no results
     */
    public function qr(...$params)
    {
        $query = $this->get_escaped_query_str($params);
        $id = $this->query($query);
        return $this->row($id);
    }

    private function _query($query)
    {
        if (!self::$stop_doing_queries || $query == "ROLLBACK") {
            try {
                return $this->pdo->query($query);
            } catch (PDOException $e) {
                $this->error("Error executing query: " . $e->getMessage());
            }
        }
    }

    public function query(...$args)
    {
        $rtn = null;
        $current_user = &singleton("current_user");
        $this->connect();
        $query = $this->get_escaped_query_str($args);

        if ($query && !self::$stop_doing_queries) {
            if (is_object($current_user) && method_exists($current_user, "get_id") && $current_user->get_id()) {
                $this->_query(unsafe_prepare("SET @personID = %d", $current_user->get_id()));
            } else {
                $this->_query("SET @personID = NULL");
            }

            $rtn = $this->_query($query);

            if (!$rtn) {
                $info = $this->pdo->errorInfo();
                $this->error("Query failed: " . $info[0] . " " . $info[1] . "\n" . $query, $info[2]);
                $this->rollback();
                unset($this->pdo_statement);
            } else {
                $this->pdo_statement = $rtn;
                $this->error();
            }
        }

        return $rtn;
    }

    public function num($pdo_statement = "")
    {
        $pdo_statement || ($pdo_statement = $this->pdo_statement);
        return $pdo_statement->rowCount();
    }

    public function num_rows($pdo_statement = "")
    {
        return $this->num($pdo_statement);
    }

    /**
    * Fetches a row from the result set using the specified fetch style.
    *
    * @deprecated This function is deprecated. Use PDOStatement::fetch()
    *
     * @param PDOStatement|null $pdoStatement Optional. The PDOStatement object
                                       to fetch the row from. If not
                                       provided, the method uses the
                                       current instance's pdo_statement.
    * @param int $method Optional. The fetch style to use. Default is
    *                    PDO::FETCH_ASSOC.
    * @return array|object|false|null The fetched row, or false if there are no
    *                                 more rows, or null if an error occurs.
    */
    public function row(PDOStatement $pdoStatement = null, int $method = PDO::FETCH_ASSOC)
    {
        if (!$pdoStatement instanceof \PDOStatement && empty($this->pdo_statement)) {
            return [];
        }

        if (!self::$stop_doing_queries) {
            if (!$pdoStatement instanceof \PDOStatement) {
                $pdoStatement = $this->pdo_statement;
            }

            if ($pdoStatement) {
                unset($this->row);
                if ($this->pos !== null) {
                    $this->row = $pdoStatement->fetch($method, PDO::FETCH_ORI_ABS, $this->pos);
                    unset($this->pos);
                } else {
                    $this->row = $pdoStatement->fetch($method, PDO::FETCH_ORI_NEXT);
                }

                return $this->row;
            }
        }
    }

    /**
     * Fetches the next row from the result set as an associative array.
     *
     * @deprecated This function is deprecated. Use PDOStatement::fetch()
     *
     * @return array|null The next row from the result set, or null if there are
     *                    no more rows.
     */
    public function next_record()
    {
        return $this->row();
    }

    /**
     * Retrieves the value from the given column name in the current row.
     *
     * @deprecated This function is deprecated. Use PDOStatement::fetch()
     *
     * @param string $name The name of the column to retrieve the value from.
     * @return string The value of the specified column
     */
    public function f(string $name): string
    {
        return $this->row[$name] ?? "";
    }

    public function get_table_fields($table)
    {
        static $fields;

        if ($fields[$table]) {
            return $fields[$table];
        }

        $database = $this->database;
        if (strstr($table, ".")) {
            [$database, $table] = explode(".", $table);
        }

        $this->query("SHOW COLUMNS FROM " . $table);
        while ($row = $this->row()) {
            $fields[$table][] = $row["Field"];
        }

        $fields[$table] || ($fields[$table] = []);
        return $fields[$table];
    }

    public function get_table_keys($table)
    {
        static $keys;
        if ($keys[$table]) {
            return $keys[$table];
        }

        $this->query(["SHOW KEYS FROM %s", $table]);
        while ($row = $this->row()) {
            if (!$row["Non_unique"]) {
                $keys[$table][] = $row["Column_name"];
            }
        }

        return $keys[$table];
    }

    public function save($table, $row = [], $_ = 0)
    {
        $q = null;
        $keys = [];
        $do_update = null;
        ($table_keys = $this->get_table_keys($table)) || ($table_keys = []);
        foreach ($table_keys as $table_key) {
            $row[$table_key] && ($do_update = true);
            $keys[$table_key] = $row[$table_key];
        }

        $row = $this->unset_invalid_field_names($table, $row, $keys);

        if ($do_update !== null) {
            $q = sprintf(
                "UPDATE %s SET %s WHERE %s",
                $table,
                $this->get_update_str($row),
                $this->get_update_str($keys, " AND ")
            );
            (is_countable($row) ? count($row) : 0) && $this->query($q);
            reset($keys);
            return current($keys);
        }

        $q = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table,
            $this->get_insert_str_fields($row),
            $this->get_insert_str_values($row)
        );
        (is_countable($row) ? count($row) : 0) && $this->query($q);
        return $this->get_insert_id();
    }

    public function delete($table, $row = [], $_ = 0)
    {
        $row = $this->unset_invalid_field_names($table, $row);
        $q = sprintf(
            "DELETE FROM %s WHERE %s",
            $table,
            $this->get_update_str($row, " AND ")
        );
        if ((is_countable($row) ? count($row) : 0) !== 0) {
            $pdo_statement = $this->query($q);
            return $pdo_statement->rowCount();
        }
    }

    public function get_insert_str_fields($row)
    {
        $rtn = "";
        $commar = "";
        foreach ($row as $fieldname => $value) {
            $rtn .= $commar . $fieldname;
            $commar = ", ";
        }

        return $rtn;
    }

    public function get_insert_str_values($row)
    {
        $rtn = "";
        $commar = "";
        foreach ($row as $fieldname => $value) {
            $rtn .= $commar . $this->esc($value);
            $commar = ", ";
        }

        return $rtn;
    }

    public function get_update_str($row, $glue = ", ")
    {
        $rtn = "";
        $commar = "";
        foreach ($row as $fieldname => $value) {
            $rtn .= $commar . " " . $fieldname . " = " . $this->esc($value);
            $commar = $glue;
        }

        return $rtn;
    }

    public function unset_invalid_field_names($table, $row, $keys = [])
    {
        $valid_field_names = $this->get_table_fields($table);
        $keys = array_keys($keys);

        foreach ($row as $field_name => $v) {
            if (!in_array($field_name, $valid_field_names) || in_array($field_name, $keys)) {
                unset($row[$field_name]);
            }
        }

        $row || ($row = []);
        return $row;
    }

    public function get_escaped_query_str($args)
    {
        return unsafe_prepare(...$args);
    }

    public function get_encoding()
    {
        $this->query("SHOW VARIABLES LIKE 'character_set_client'");
        $row = $this->row();
        return $row["Value"];
    }
}
