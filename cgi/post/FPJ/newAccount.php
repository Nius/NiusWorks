<?php

require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');

/* RESPONSE DICTIONARY

0 - Invalid input or corrupted transmission
1 - Success
2 - SQL error

*/

requirePermission('FPJ');

require_once('/web/serve/niusworks.com/resources/libraries/FPJ-config.php');

// Check account type; fail if not found in config.
$type = $_POST['type'] + 0;
if(!array_key_exists($type,$FPJ_ACCOUNT_TYPES))
	die('0');

// Sanitize account name.
$name = $DB_LINK->escape($_POST['accName']);
if(strlen($name) < 1)
	die('0');

$id = $_SESSION['user']['uid'];

// Create account record.
$query = "INSERT INTO FPJ_Accounts VALUES(null,$type,'$name',$id)";

$result = $DB_LINK->query($query);
if($result !== false)
	die('1');
else
	die('2');
