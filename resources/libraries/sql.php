<?php

/*

sql.php
A mysqli-based PHP library for easily facilitating
SQL database operations.

Originally developed by:
- Cody Creager	(rigel314)
- Ethan Reesor	(firelizzard18)

Minor modifications by:
- Nicholas Harrell	(Nius Atreides)

*/

$DB_LINK = new SQLConnection('localhost','webuser','webpassword','NiusWeb');

function readFlag($haystack,$needle)
{
	if($needle > strlen($haystack) - 1 || $needle < 0)
		return 0;
	return substr(strrev($haystack),$needle,1) + 0;
}

function writeFlag($haystack,$index,$value)
{
	$rev = strrev($haystack);
	while($index > strlen($rev) - 1)
		$rev = $rev.'0';
	$rev = substr($rev,0,$index).$value.
		($index < strlen($rev) - 1 ? substr($rev,$index + 1) : '');
	return strrev($rev);
}

class SQLConnection {
	private $link, $host, $user, $pass, $dbname, $new, $flags;

	function __construct($host,
						 $user,
						 $pass,
						 $dbname = "",
						 $new = false,
						 $flags = 0,
						 $select = true,
						 $connect = true)
	{	
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->dbname = $dbname;
		$this->new = $new;
		$this->flags = $flags;

		if ($connect || $select)
			$this->connect();
		if ($select && $dbname)
			$this->selectdb();
	}
	function __destruct() {
		$this->disconnect();
	}
	function __toString() {
		return $this->link."";
	}

	function connect() {
		if (!$this->link) {
			$this->link = mysqli_connect($this->host, $this->user, $this->pass, $this->new, $this->flags);
			mysqli_set_charset($this->link,"utf8");
			$err = SQLError::stack("mysql_connect", $this->link, (!$this->link) ? SQLError::OPEN_FAILED : SQLError::NO_ERROR);
		} else
			$err = SQLError::stack("mysql_connect", false, SQLError::EXISTING_LINK);
		return !$err->isError();
	}
	function disconnect() {
		if (!$this->link)
			$err = SQLError::stack("mysql_close", false, SQLError::MISSING_LINK);
		else {
			$result = mysqli_close($this->link);
			$err = SQLError::stack("mysql_close", (!$result) ? $this->link : false, (!$result) ? SQLError::OPEN_FAILED : SQLError::NO_ERROR);
		}
		return !$err->isError();
	}
	function database($dbname, $select = true) {
		$this->dbname = $dbname;

		if ($select)
			return $this->selectdb();
		else
			return true;
	}
	function selectdb() {
		if (!$this->link)
			$err = SQLError::stack("mysql_select_db", false, SQLError::MISSING_LINK);
		else {
			$selected = mysqli_select_db($this->link, $this->dbname);
			$err = SQLError::stack("mysql_select_db", $this->link, (!$selected) ? SQLError::SELECT_FAILED : SQLError::NO_ERROR);
		}
		return !$err->isError();
	}
	function query($qstr, $exec = true) {
		return new SQLQuery($this->link, $qstr, $exec);
	}

	function escape($str) {
		return mysqli_real_escape_string($this->link, $str);
	}
}

class SQLQuery {
	public $link, $qstr, $result;
//	function getResult($row = 0, $field = 0) { return mysql_result($this->result, $row, $field); }

	function __construct($link, $qstr, $exec = true) {
		$this->link = $link;
		$this->qstr = $qstr;

		if ($exec)
			$this->execute();
	}
	function __destruct() {
		if ($this->result && $this->result !== true)
			mysqli_free_result($this->result);
	}

	function execute() {
		if ($this->result)
			mysqli_free_result($this->result);

		if (!$this->link)
			$err = SQLError::stack("mysql_query", false, SQLError::MISSING_LINK);
		else {
			$this->result = mysqli_query($this->link, $this->qstr);
			$err = SQLError::stack("mysql_query", $this->link, (!$this->result) ? SQLError::QUERY_FAILED : SQLError::NO_ERROR);
		}
		return !$err->isError();
	}

	function insert_id() {
		return mysqli_insert_id($this->link);
	}
	
	function result() {
		if (!$this->result) {
			$err = SQLError::stack("mysql_fetch", false, SQLError::FETCH_FAILED);
			return null;
		}
//		mysql_data_seek($this->result, 0);
		return $this->result;
	}
	function field_c() {
		if (($result = $this->result()) == null)
			return null;
		return mysqli_field_count($this->result);
	}
	function field_r($i = -1) {
		if (($result = $this->result()) == null)
			return null;
		$arr = array(); $count = $this->field_c();
		for ($i = 0; $i < $count; $i++)
			array_push($arr, mysqli_fetch_field_direct($this->result, $i));
		return ($i == -1) ? $arr : $arr[$i];
	}
	
	//NOT FUNCTIONING FOR SOME REASON
	function result_c() {
		if (($result = $this->result()) == null)
			return -1;
		return mysqli_stmt_num_rows($result);
	}
	//NOT FUNCTIONING FOR SOME REASON
	
	function result_r($i = -1) {
		if (($result = $this->result()) == null)
			return null;
		$arr = array();
		while ($row = mysqli_fetch_assoc($result))
			array_push($arr, $row);
		return ($i == -1) ? $arr : $arr[$i];
	}
}

class SQLError {
	private $op, $time, $num, $msg, $code;

	const EXISTING_LINK	= -2;
	const MISSING_LINK	= -1;
	const NO_ERROR		=  0;
	const OPEN_FAILED	=  1;
	const CLOSE_FAILED	=  2;
	const SELECT_FAILED	=  3;
	const QUERY_FAILED	=  4;
	const FETCH_FAILED	=  5;
	static $codes = array(
		SQLError::NO_ERROR		=> "MySQL Error or No Error",
		SQLError::OPEN_FAILED	=> "Attempting to open a connection did not return a link",
		SQLError::CLOSE_FAILED	=> "Attempting to close the connection failed",
		SQLError::SELECT_FAILED	=> "Selecting a database failed",
		SQLError::QUERY_FAILED	=> "SQL query failed",
		SQLError::FETCH_FAILED	=> "There is no query result to fetch",
		SQLError::EXISTING_LINK	=> "There is already an active connection",
		SQLError::MISSING_LINK	=> "There is no active connection"
	);

	function __construct($op, $link, $code = 0) {
		$this->op = $op;
		$this->time = time();
		$this->code = $code;
		if ($link) {
			$this->num = mysqli_errno($link);
			$this->msg = mysqli_error($link);
		}
	}
	function __destruct() {
		// Nothing to do
	}
	function __toString() {
		$str = "[$this->op:$this->time] ";
		if (!$this->isError())
			return $str."no error\n";
		else {
			if ($this->code)
				$str .= "<$this->code:".SQLError::$codes[$this->code]."> ";
			if ($this->num)
				$str .= "$this->num:$this->msg";
			return $str."\n";
		}
	}

	function operation() {
		return $this->op;
	}
	function errNum() {
		return $this->num;
	}
	function errMsg() {
		return $this->msg;
	}
	function errCode() {
		return $this->code;
	}
	function errStr() {
		return self::$codes[$this->code];
	}
	function isError() {
		return $this->num || $this->msg || $this->code;
	}

	static $stack = array();
	static function stack($op, $link, $code = 0) {
		$err = new SQLError($op, $link, $code);
		if ($err->isError()) array_push(SQLError::$stack, $err);
		return $err;
	}
	static function last() {
		return $stack[count($stack) - 1];
	}
	static function display($pre = false) {
		if ($pre) echo "<pre>";
		echo "SQL Error Stack: \n";
		foreach (SQLError::$stack as $num => $err)
			echo "$num - $err";
		if ($pre) echo "</pre>";
	}
}

?>
