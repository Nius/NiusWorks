<?php

/* Match TRX

Given a pattern and (optionally) a set of subclass ID's,
find all matching transactions.

RESPONSE DICTIONARY
-1 - No matches
0  - Invalid data (before parsing)

*/

require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');
require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
requirePermission('FPJ');

require_once('/web/serve/niusworks.com/resources/libraries/FPJ-config.php');

if(!isset($_REQUEST['pattern']))
	die('0');

$pattern = $DB_LINK->escape(json_decode($_REQUEST['pattern']));
$classIDs = $_REQUEST['subKeys'];

/*
$where = "WHERE DESCRIPTION REGEXP '$pattern'";
$query = "(SELECT * FROM $FPJ_TRX_TABLE $where) ".
	 "UNION ALL ".
	 "(SELECT * FROM $FPJ_STAGE_TABLE $where) ORDER BY TRXDATE";
$matches = $DB_LINK->query($query)->result_r();
*/

$matches = FPJ_get_matches_with_pattern_and_classIDs($pattern,$classIDs);

// Get account names for the current user and associate them with their names
$query = 'SELECT ID, Name FROM FPJ_Accounts WHERE Owner = '.$_SESSION['user']['uid'];
$accts_raw = $DB_LINK->query($query)->result_r();
$accounts = Array();
foreach($accts_raw as $act)
	$accounts[$act['ID']] = $act['Name'];

// Replace account numbers in matched transactions with their names
foreach($matches as &$match)
	$match['ACCOUNT'] = $accounts[$match['ACCOUNT']];

if(count($matches) < 1)
	die('-1');

echo json_encode($matches);

?>
