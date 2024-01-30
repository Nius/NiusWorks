<?php

require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');
require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
requirePermission('FPJ');

require_once('/web/serve/niusworks.com/resources/libraries/FPJ-config.php');

/* RESPONSE DICTIONARY
-1 - Success
0  - Invalid data (before parsing)
n  - Any positive integer: invalid data on that line in the input file
*/

if(!isset($_FILES) || empty($_FILES))
	die('0');

$file = $_FILES['trxs'];
$path = $file['tmp_name'];
$account = $_POST['account'] + 0;

switch($_POST['format'])
{
	case 'BOATXT': die(parseBOATXT().'');
}

function parseBOATXT()
{
	global $path;
	global $DB_LINK;
	global $FPJ_TRX_TABLE;
	global $FPJ_STAGE_TABLE;
	global $account;

	$lines = explode(PHP_EOL, file_get_contents($path));

	/* BOA Text Parsing
	 
	   The BOA text file is formatted as follows:
	   - 5 lines of unnecessary data
	   - 2 empty lines
	   - 1 line of headers
	   - 1 line of unnecessary data
	   - n lines of payload data
	   - 1 empty line

	   Each transaction is on its own line and is formatted as follows:

	   MM/DD/YYYY   DESCRIPTION   -9,999.99   -9,999.99

	   Note that neither number has a dollar sign. The first number is the value
	   of the transaction and the second number is the running total after the
	   transaction. The running total will be used in conjunction with other
	   transaction data to determine whether two transactions are the same
	   individual transaction, since two transactions could have the same value,
	   date, and description all at once. The chances of two distinct
	   transactions havig identical data including the running total are
	   vanishingly small.
	*/

	$trxs = Array();

	// Keep track of the earliest and latest date of encountered transactions, to
	// limit the quantity of transactions we have to check against.
	$mindate;
	$maxdate;

	// Parse each transaction. If an error is encountered, return the line number.
	foreach($lines as $index => $line)
	{
		// Skip the first 9 lines of non-data
		if($index < 9 || strlen(trim($line)) == 0)
			continue;		

		$pattern = '/^(\d{2}\/\d{2}\/\d{4})\s+(.*[^\s])\s+(-?[\d\,]+\.\d{2})\s+(-?[\d\,]+\.\d{2})[\r\n]+$/';

		$matches = Array();
		if(!preg_match($pattern,$line,$matches))
			return $index;

		$date = $matches[1];
		$description = $DB_LINK->escape($matches[2]);
		$value = str_replace(',','',$matches[3]) + 0;
		$runTotal = str_replace(',','',$matches[4]) + 0;

		if(!$date_obj = date_create_immutable($date))
			return $index;
		if(!isset($mindate) || $date_obj < $mindate)
			$mindate = $date_obj;
		if(!isset($maxdate) || $date_obj > $maxdate)
			$maxdate = $date_obj;
		$date = date_format($date_obj,'Y-m-d');

		// Transactions are stored in an array keyed by date for quick
		// comparison against stored transactions.
		$trxs[$date][] =
		[
			'date' => $date,
			'descr' => $description,
			'value' => $value,
			'rtotal' => $runTotal
		];
	}


	// Read transactions from the transaction tables for comparison with the parsed
	// transactions from file. Only select transactions between the earliest and
	// latest dates in the file.
	$mindate = date_format($mindate, 'Y-m-d');
	$maxdate = date_format($maxdate, 'Y-m-d');
	$query = "SELECT TRXDATE, VALUE, RTOTAL, DESCRIPTION FROM $FPJ_TRX_TABLE WHERE ".
		 "ACCOUNT = $account AND ".
		 "DATE(TRXDATE) BETWEEN '$mindate' AND '$maxdate' ".
		 "UNION ".
		 "SELECT TRXDATE, VALUE, RTOTAL, DESCRIPTION FROM $FPJ_STAGE_TABLE WHERE ".
		 "ACCOUNT = $account AND ".
		 "DATE(TRXDATE) BETWEEN '$mindate' AND '$maxdate'";
	$result = $DB_LINK->query($query);
	$trxs_db = $result->result_r();
	
	// Compare transactions. Two transactions are the same if they have the same
	// date, value, running total, and description. Parsed transactions that are
	// the same as a stored transaction are removed from the parsed transaction
	// list, so they are effectively ignored.
	foreach($trxs_db as $stored)
	{
		$date = $stored['TRXDATE'];

		if(isset($trxs[$date]) && !empty($trxs[$date]))
			foreach($trxs[$date] as $key => $incoming)
				if(
					$incoming['value'] == $stored['VALUE'] &&
					$incoming['rtotal'] == $stored['RTOTAL'] &&

					// The stored description must be escaped here because it is being compared
					// to the incoming description which is escaped.
					strcmp($incoming['descr'],$DB_LINK->escape($stored['DESCRIPTION'])) == 0
				)
					unset($trxs[$date][$key]);
	}

	// Commit remaining transactions to the staging table.
	$count = 0;
	foreach($trxs as $fiscal_day)
		foreach($fiscal_day as $trx)
		{
			$count ++;
			$query =
				"INSERT INTO $FPJ_STAGE_TABLE VALUES(".
				'null,'.
				$account.','.
				"'".$trx['date']."',".
				$trx['value'].','.
				$trx['rtotal'].','.
				"'".$trx['descr']."')";
			$result = $DB_LINK->query($query);
		}

	if($count == 0)
		return -2;
	else
		return -1;
}

?>
