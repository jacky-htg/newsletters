<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class DB {
	public $dbh = null;
	public $config;
	public $tablename;
	public $where;
	public $order;
	public $pnumber;
	public $group;
	public $parameters = '*';
	public $page;

	public function __construct($config){
		$this->config = $config;
		$this->openConnection();
	}

	function __destruct() {
        if (isset($this->dbh)){             
            $this->closeConnection();  
        }
    }

	public function openConnection(){
		$config = $this->config;
		
		if (is_null($this->dbh)) {
            $this->dbh = new mysqli($config["host"], $config["user"], $config["passwd"], $config["name"]);
			
            if (mysqli_connect_errno()) {
                $this->dbh = null;
				throw new Exception("Error connect: " . mysqli_connect_error());
				
            } else {
                mysqli_report(MYSQLI_REPORT_ERROR);
            }
			
			if ($config["charset"] != '') { 
				$this->dbh->query("SET NAMES ".$config["charset"]."");
			}
        }
        return $this->dbh;
	}
	
	public function closeConnection() {
		if (!is_null($this->dbh)) {
			$this->dbh->close();
		}
	}
	
	public function getTableName($tbl) {
		$config = $this->config;
        return ".`" . $config["prefix"] . $tbl . "`";
    }
	
	public function get_page()
	{
		if (empty($this->page)) $this->page = 1;
		$total = $this->get_total();
		$number = (int)($total / $this->pnumber);
		if ((float)($total / $this->pnumber) - $number != 0) $number++;
		if ($this->page <= 0 || $this->page > $number) return 0;

		$arr = array();
		$first = ($this->page - 1) * $this->pnumber;

		$query = "SELECT " . $this->parameters . " FROM " . $this->tablename . "
					" . $this->where . "
					" . $this->group . "
					" . $this->order . "
					LIMIT " . $first . ", " . $this->pnumber . "";

		$result = $this->dbh->query($query);

		if (!$result) {
			throw new ExceptionSQL($this->dbh->error, $query, "Error executing SQL query!");
		}

		if ($result->num_rows){
			while($arr[] = $result->fetch_array());
		}
		
		$result->close();

		unset($arr[count($arr) - 1]);

		return $arr;
	}
	
	public function get_total()
	{
		$query = "SELECT COUNT(*) FROM " . $this->tablename . "
									   " . $this->where . "
									   " . $this->order . "";

		$tot = $this->dbh->query($query);

		if (!$tot) {
			throw new ExceptionSQL($this->dbh->error, $query, "Error executing SQL query!");
		}

		$count = $tot->fetch_row();

		return $count[0];
	}
	
	public function escape($str)
	{
		return $this->dbh->real_escape_string($str);
	}
	
	public function getRecordCount($result){
		return $result->num_rows;
	}
	
	public function getRow($result, $mode = 'array') {
		if ($result) {
			if ($mode == 'array') {
				return $result->fetch_array();
			}
			elseif ($mode == 'assoc') {
				return $result->fetch_assoc();
			}
			elseif ($mode == 'row') {
				return $result->fetch_row();
			} else {
				return false;
			}
		}
	}
	
	public function select($parameters = '*', $from, $where = '', $group = '', $order = '', $limit = '') {
		$query = "SELECT " . $parameters . " FROM " . $from . " 
					" . $where . "
					" . $group . "
					" . $order . "
					" . $limit . "";
					
		$result = $this->dbh->query($query);
		
		if (!$result) {
			throw new ExceptionSQL($this->dbh->error, $query, "Error executing SQL query!");
		}
		
		return $result;
	}
	
	public function getColumnArray($result)	{
		$arr = array();
		
		if ($this->getRecordCount($result) > 0)	{
			while($arr[] = $this->getRow($result));
		}
		
		unset($arr[count($arr) - 1]);
		
		return $arr;
	}
		
	public function update($fields, $table, $where = "") {
	
		if (!$table && !is_null($this->dbh))
			return false;
		else {
			if (!is_array($fields))
				$flds = $fields;
			else {
				$flds = '';
				
				foreach ($fields as $key => $value) {
					if (!empty ($flds))
						$flds .= ",";
					$flds .= $key . "=";
					$value = $this->escape($value);
					$flds .= "'" . $value . "'";
				}
			}
			
			$where = ($where != "") ? "WHERE " . $where : "";
			$query = "UPDATE " . $table . " SET " . $flds . " " . $where . "";
			
			if($this->dbh->query($query))
				return true;
			else
				throw new ExceptionSQL($this->dbh->error, $query, "Error executing SQL query!");
		}
	}
	
	public function querySQL($query) {
		if ($query) {
			$result = $this->dbh->query($query);
			if (!$result)
				throw new ExceptionSQL($this->dbh->error, $query, "Error executing SQL query!");
			else
				return $result;
		}
		else
			return false;
	}
	
	public function insert($data, $table) {
        $columns = "";
        $values = "";
		
        foreach ($data as $column => $value) {
			$value = $this->escape($value);
            $columns .= $columns ? ', ' : '';
            $columns .= "`$column`";
            $values  .= $values 	? ', ' : '';
            $values  .= "'$value'";
        }
		
        $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")";
        if (!$this->dbh->query($sql)) throw new ExceptionSQL($this->dbh->error, $query, "Error executing SQL query!");

        return $this->dbh->insert_id;
    }
	
	public function delete($table, $where = '', $fields = '') {
		if (!$table)
			return false;
		else {
			$where = ($where != "") ? "WHERE ".$where."" : "";
			
			$query = "DELETE " . $fields . " FROM " . $table . " " . $where . "";
			$result = $this->dbh->query($query);
			
			if (!$result)
				throw new ExceptionSQL($this->dbh->error, $query, "Error executing SQL query!");
			else
				return true;			
		}
	}
}