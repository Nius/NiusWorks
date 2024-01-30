<?php

require_once('/web/serve/niusworks.com/cgi/session/session.php');
require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');
require_once('/web/serve/niusworks.com/resources/libraries/sql.php');

requirePermission('FPJ');

$FPJ_LOCAL_ADDR_CHARGES = '/web/backend-storage/finsheet-charges.csv';
$FPJ_LOCAL_ADDR_INCOME = '/web/backend-storage/finsheet-income.csv';
$FPJ_SHEET_URL_CHARGES = 'REDACTED Google Sheets URL';
$FPJ_SHEET_URL_INCOME = 'REDACTED Google Sheets URL';

function fpj_verifySheet()
{
	global $FPJ_LOCAL_ADDR_CHARGES;

	if(file_exists($FPJ_LOCAL_ADDR_CHARGES))
	{
		if(time()-filemtime($FPJ_LOCAL_ADDR_CHARGES) > 24 * 3600) //24 hours
			return fpj_updateSheet();
		else
			return 1;
	}
	else
		return fpj_updateSheet();
}

function fpj_updateSheet()
{
	global $FPJ_SHEET_URL_CHARGES;
	global $FPJ_SHEET_URL_INCOME;
	global $FPJ_LOCAL_ADDR_CHARGES;
	global $FPJ_LOCAL_ADDR_INCOME;

	global $DB_LINK;
	$result = $DB_LINK->query('INSERT INTO QRY_Callouts VALUES(null,1000,\''.__FILE__.'\',\''.$FPJ_SHEET_URL_CHARGES.'\',NOW())');
	if(!copy($FPJ_SHEET_URL_CHARGES,$FPJ_LOCAL_ADDR_CHARGES))
		return 0;

	$result = $DB_LINK->query('INSERT INTO QRY_Callouts VALUES(null,1000,\''.__FILE__.'\',\''.$FPJ_SHEET_URL_INCOME.'\',NOW())');
	if(!copy($FPJ_SHEET_URL_INCOME,$FPJ_LOCAL_ADDR_INCOME))
		return 0;

	return 1;
}

function fpj_getIncome()
{
	if(fpj_verifySheet() == 0)
		return false;

	global $FPJ_LOCAL_ADDR_INCOME;
	$stream = fopen($FPJ_LOCAL_ADDR_INCOME,'r');
	if($stream === false)
		die('Error reading file.');
	$FPJ_INCOME = Array();
	while(($line = fgetcsv($stream,0,',','"')) !== false)
	{
		$label = $line[0];
		$amount = $line[3];
		$due = $line[1];
		$latest = $line[2];

		$amount = str_replace(',','',substr($amount,1));
		if(!is_numeric($amount))
			continue;

		$FPJ_INCOME[] = new Charge($label,$amount / 2,$due,$latest);
	}
	fclose($stream);

	return $FPJ_INCOME;
}

function fpj_getCharges()
{
	if(fpj_verifySheet() == 0)
		return false;

	global $FPJ_LOCAL_ADDR_CHARGES;
	$stream = fopen($FPJ_LOCAL_ADDR_CHARGES,'r');
	if($stream === false)
		die('Error reading file.');
	$FPJ_CHARGES = Array();
	while(($line = fgetcsv($stream,0,',','"')) !== false)
	{
		$label = $line[1];
		$amount = $line[2];
		$due = $line[3];

		if(strlen($due) == 0 || !is_numeric($due))
			continue;

		$amount = str_replace(',','',substr($amount,1));
		$FPJ_CHARGES[] = new Charge($label,$amount,$due);
	}
	fclose($stream);

	return $FPJ_CHARGES;
}

class Charge
{
	public $label; public $amount; public $due; public $latest;

	function __construct($label,$amount,$due,$latest = null)
	{
		$this->label = $label;
		$this->amount = $amount;
		$this->due = $due;
		$this->latest = $latest;
	}
}

//
//	AJAX
//	

if($_SERVER['SCRIPT_NAME'] === '/cgi/query/finsheet.php' && isset($_POST['fn']))
{
	switch($_POST['fn'])
	{
		case('refresh'):
			echo fpj_updateSheet();
			break;
		case('updef'):
			$val = $_POST['val'] + 0;
			file_put_contents('/web/backend-storage/finsheet-startval.txt',$val);
			break;
		default:
			echo 0;
			break;
	}
}

?>
