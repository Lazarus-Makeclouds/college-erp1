<?php
class DB {
    private static $_instance = null;
    private $_pdo,
            $_query, 
            $_error = false,
            $_results,
            $_count = 0; // Use _count to match private visibility

    private function __construct(){
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'),
                                  Config::get('mysql/username'), Config::get('mysql/password'));
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Optionally set error mode
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }
    public function getConnection() {
        return $this->_pdo;
    }


    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    public function query($sql, $params = array()) {
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if(count($params)) {
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
                }
        }
        return $this;
    }

    public function action($action, $table, $where= array(), $orderby=null, $ASC=false) {
        if(count($where) === 3) {
            $operators = array('=','>','<','>=','<=');

            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];  

            if(!empty($orderby)){
                if ($ASC){
                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? ORDER BY {$orderby} ASC";
                    if($this->query($sql, array($value))) {
                        return $this;
                    } 
                } else {
                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? ORDER BY {$orderby} DESC";
                    if($this->query($sql, array($value))) {
                        return $this;
                    }
                }
            } else {
                if(in_array($operator, $operators)){
                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
    
                    if($this->query($sql, array($value))) {
                        return $this;
                    }
                }
            }
        } else if (empty($where)) {
            if(!empty($orderby)){
                if ($ASC){
                    $sql = "{$action} FROM `{$table}` ORDER BY {$orderby} ASC";

                    if($this->query($sql)) {
                        return $this;
                    } 
                } else {
                    $sql = "{$action} FROM `{$table}` ORDER BY {$orderby} DESC";

                    if($this->query($sql)) {
                        return $this;
                    }
                }
            }
            else {
            $sql = "{$action} FROM `{$table}`";
            
            if($this->query($sql)){
                return $this;
            }
            }
        }
    }

    public function get($table, $where, $orderby=null, $ASC=false) {
        return $this->action('SELECT *', $table, $where, $orderby, $ASC);
    }

    public function get_all($table, $orderby=null, $ASC=false) {
        return $this->action('SELECT *', $table, array(), $orderby, $ASC);
    }

    public function delete($table, $where) {
        return $this->action('DELETE', $table, $where);
    }

    public function insert($table, $fields= array()){
        if(count($fields)) {
            $keys = array_keys($fields);
            $values = null;
            $x = 1; 

            foreach($fields as $field){
                $values .= "?";
                if($x<count($fields)){
                   $values .=", ";
                }
                $x++;
            }
            $sql = "INSERT INTO {$table} (`".implode('`,`', $keys)."`) VALUES ({$values})";

            if(!$this->query($sql, $fields)->error()){
                return true;
            }
        }
        return false;
    }

    public function update($table, $id, $id_name, $fields){
        $set = '';
        $x = 1;
        foreach($fields as $name => $value){
            $set .= "`{$name}` = ?";
            if($x<count($fields)) {
                $set .= ', ';
            }
            $x++;
        }
        $sql = "UPDATE {$table} SET {$set} WHERE `{$id_name}` = '{$id}';";
        
        if(!$this-> query($sql, $fields)->error()){
            return true;
        }
        return false;
    }

    public function create($table, $fields){
        $set = "";
        $x = 1;
        foreach ($fields as $key=>$value){
            $set .= "{$key} {$value}";
            if($x<count($fields)){
                $set .= ", ";
            }
            $x++;
        }
        $sql = "CREATE TABLE `{$table}` ({$set});";
        if(!$this-> query($sql)->error()){
            return true;
        }
        return false;
    }

    public function drop($table){
        $sql = "DROP TABLE IF EXISTS `{$table}`;";
        if(!$this->query($sql)->error()){
            return true;
        }
        return false;
    }

    public function table_exists($table_name){
        $sql = "SHOW TABLES LIKE '$table_name';";
        if($this->query($sql)->count()){
            return true;
        } else {
            return false;
        }
    }

    public function results(){
        return $this->_results;
    }

    public function first(){
        return $this->_results[0];
    }
    public function error(){
        return $this->_error;
    }

    public function count(){
        return $this->_count;
    }
}
