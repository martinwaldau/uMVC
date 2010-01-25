<?php
Class Framework_Model_Database {
    
    private $db_connect="";				// Datenbankverbindung offen
    private $db_close="";				// Datenbankverbindung geschlossen
    private $db_select_db="";			// Angabe der Datenbank die connectiert wird
    private $db_query="";				// Datenbankquery
    private $db_fetch_array="";			// Datenarry
    private $db_num_rows="";			// Datenreihen
    
    private $host;						// Host (meist localhost)
    private $database;					// Name der Datenbank
    private $user;						// Name des connctierten users
    private $password;					// Passwort des connectierten users
    private $port;						// gegebenenfalls der port zum connceten
    private $database_type;				// Datenbank Typ
    private $dsn;
    
    private $debug=false; 				// debug mode on (true) / off (false)
    private $log=FALSE;					// log mode on (true) / off (false)
    
    private $sql = '';					// SQL-Statement
    
    private $con; 						// variable for connection id
    var $con_string; 					// variable for connection string
    var $query_id; 						// variable for query id
    
    var $errors; 						// variable for error messages
    var $error_count=0; 				// variable for counting errors
    var $error_nr;
    var $error;
    
    /**
     * @desc Constructor for database class
     * @param $database_type
     * @param $host
     * @param $database
     * @param $user the
     * @param $password
     */
    public function __construct($port=false, $dsn=false) {
    
        $this->host = DATABASE_HOST;
        $this->database = DATABASE_NAME;
        $this->user = DATABASE_USER;
        $this->password = DATABASE_PASSWORD;
        if ($port == '') $this->port = FALSE;
        else $this->port=$port;
        $this->dsn=$dsn;
        
        // Setting database type and connect to database
		$this->database_type="mysql";
            
		$this->db_connect=$this->database_type."_connect";
		$this->db_close=$this->database_type."_close";
		$this->db_select_db=$this->database_type."_select_db";
		
		$this->db_query=$this->database_type."_query";
		$this->db_fetch_array=$this->database_type."_fetch_array";
		$this->db_insert_id=$this->database_type."_insert_id";
		$this->db_affected_rows = $this->database_type."_affected_rows";
		$this->db_num_rows=$this->database_type."_num_rows";

		return $this->connect();       
    }
    
    
	public static function &getInstance() {	
		// Singleton-Pattern
		static $instance;
		if (!is_object($instance)) {
			$instance = new Framework_Model_Database;
			try {
				$instance->connect();
			}
			catch (Exception $e) {
				Framework_Utility_Exception::handle($e);
			}
		}
		return $instance;
	}
    
	
    /**
    * @desc establishs the connection to the database
    * @return boolean $is_connected Returns true if connection was successful otherwise false
    */
	protected function connect(){
        // Selecting connection function and connecting
		if($this->con=='') {
			$this->logError('connect');
			
			if($this->port !== FALSE) @$this->con=call_user_func($this->db_connect,$this->host.":".$this->port,$this->user,$this->password);
			else @$this->con=call_user_func($this->db_connect,$this->host,$this->user,$this->password);
		

			if ($this->con) $this->query('SET NAMES utf8');		
			if(!$this->con) {
				throw new Exception('Database connection not established.', 200);
				$this->halt("Wrong connection data! Can't establish connection to host.");
				$this->logError('Wrong connection data! Cant establish connection to host');
				return false;
            } elseif(!@call_user_func($this->db_select_db,$this->database,$this->con)) {
				$this->halt("Wrong database data! Can't select database.");
				$this->logError("Wrong database data! Can't select database.");
				return false;
			} else return true;
			
        } else {
            $this->halt("Already connected to database.");
            $this->logError('attempt to connect while connected');
            return false;
        }	
	}
	
	/**
	 * uses an active mysql-connection to escape the given string
	 * @param string $string
	 * @return escaped string
	 */
	public function escape($string) {
		return mysql_real_escape_string($string);
	}
    
    
    /**
    * @desc This function queries the database
    * @param string $sql_statement the sql statement
    * @return boolean $successfull returns false on errors otherwise true
    */
	public function query($sql_statement){
		//$this->logError('try to query: '.$sql_statement);
		$this->sql=$sql_statement;
		if($this->debug) {
			printf("<br />SQL statement: %s\n\r",$this->sql);
		}
		
		if(!$this->query_id=call_user_func($this->db_query, $this->sql, $this->con)){
			if ($mysql_error = mysql_error()) {
                $this->logError("MYSQL-ERROR: ".$mysql_error." | ".$sql_statement);
				if($this->debug) {
					printf("<br />MYSQL-ERROR: %s\n\r",$mysql_error);
				}
        	}
			return FALSE;
		} else {
			switch(strtolower(substr($sql_statement, 0, strpos($sql_statement, ' ')))) {
				case 'select':
					return $this->getRows();
				break;
				
				case 'update':
				case 'replace':
					return $this->lastAffect();
				break;
				
				case 'insert':
					return $this->lastInsert();
				break;
				
				default:
					return TRUE;
				break;
			}
		}
	}
    
	
    /**
    * @desc This function returns a row of the resultset
    * @return array $row the row as array or false if there is no more row
    */
	protected function getRow(){
		if ($mysql_error = mysql_error()) {
			return array();
        } else {
			$row=call_user_func($this->db_fetch_array,$this->query_id,MYSQL_ASSOC);
			return $row;
        }
	}
	
	
	protected function getRows() {
		if ($mysql_error = mysql_error()) {
			return FALSE;
        } else {
			while ($row = $this->getRow()) {
				$rows[] = $row;
			}
			
			if (!empty($rows)) return $rows;
			else return FALSE;
        }
	}
	
	
    public function getResource($query) {
    	return mysql_query($query, $this->con);
    }
    
    
	public function foundRows() {
		$this->query_id = call_user_func($this->db_query, 'SELECT FOUND_ROWS();', $this->con);
    	return $this->get_row();
    	
    }
	
    /**
    * @desc This function returns number of rows in the resultset
    * @return int $row_count the nuber of rows in the resultset
    */
	protected function countRows() {
		$row_count=call_user_func($this->db_num_rows,$this->query_id);
		if($row_count>=0) {
			return $row_count;
		} else {
			$this->halt("Can't count rows before query was made");
			return false;
		}
	}
	
	
    /**
    * @desc This function returns all tables of the database in an array
    * @return array $tables all tables of the database in an array
    */
	public function getTables() {
		$tables = "";
		$sql="SHOW TABLES";
		$this->query_id($sql);
		for($i=0;$data=$this->get_row();$i++){
			$tables[$i]=$data['Tables_in_'.$this->database];
		}
		return $tables;
	}
    
	
    /**
     * @desc Show Last Index
     */
	protected function lastInsert() {
		return call_user_func($this->db_insert_id);
	}

	
    /**
     * @desc Show affected rows
     */
	protected function lastAffect() {
		return call_user_func($this->db_affected_rows);
	}
    
    /**
    * @desc Returns all occurred errors
    * @param string $message all occurred errors as array
    */
	private function halt($message){
		if($this->debug) {
			printf("Database error: %s\n", $message);
			if($this->error_nr!="" && $this->error!=""){
				printf("MySQL Error: %s (%s)\n",$this->error_nr,$this->error);
			}
			die ("Session halted.");
		}
	}
	
    
    /**
    * @desc Switches to debug mode
    * @param boolean $switch
    */
    public function debug_mode($debug=true){
        $this->debug=$debug;
    }
    
    
    public function log_mode($log=TRUE) {
    	$this->log = $log;
    }
    
    
    private function logError($message) {
    	if ($this->log) {
	    	$f = fopen(_CONFIG_DATA_PATH.'/db_log.log', 'a');
			if ($f) {
				fwrite($f, date("Y-m-d H:i:s")." \t$message\n\r");
				fclose($f);
			}
    	}
    }
}