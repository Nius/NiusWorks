<?php

require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');

/* RESPONSE DICTIONARY

0 - Invalid input or corrupted transmission
1 - Success
2 - Username taken
3 - General error
4 - Email taken
5 - Signups Closed

*/

//Temporary account creation blockade
error(5);

//Retrieve variables.
$email = $DB_LINK->escape($_REQUEST['email']);
$username = $DB_LINK->escape($_REQUEST['username']);
$name = $DB_LINK->escape($_REQUEST['name']);
$password = $_REQUEST['password'];

//Validate inputs.
validateInputs($email,$username,$password,$name);

//Check availability of username.
checkUsernameAvail($username);

//Check availability of email address.
checkEmailAvail($email);

//Execute the creation of a new user.
createNewUser($username,$password,$email,$name);

function createNewUser($u,$p,$e,$n)
{
	global $DB_LINK;

	//PASSWORD PROCESSING
	$options = ['cost' => 13];
	$output = password_hash($p,PASSWORD_BCRYPT,$options);

	$result = $DB_LINK->query('INSERT INTO USR_Accounts VALUES(null,\''.$u.'\',\''.$n.'\','.generateDefaultPerms().',null,DEFAULT)');

	$id = $result->insert_id();
	if($id + 0 < 1000)
		error(3);

	$pwres = $DB_LINK->query('INSERT INTO USR_Passwords VALUES('.$id.',\''.$output.'\',null)');

	$emres = $DB_LINK->query('INSERT INTO USR_Contacts VALUES(null,'.$id.',\''.$e.'\',null,DEFAULT)');

	//Success
	error(1);
}

function generateDefaultPerms()
{
	global $PERMS;
	$pstring = '';

	foreach($PERMS as $perm)
		$pstring = $perm['@attributes']['default'].$pstring;

	return $pstring;
}

function checkUsernameAvail($u)
{
	global $DB_LINK;

	$result = $DB_LINK->query('SELECT uname FROM nw_accounts WHERE uname = \''.$u.'\'');
	$rows = $result->result_r();
	if(count($rows) > 0)
		error(2);
}

function checkEmailAvail($e)
{
	global $DB_LINK;

	$result = $DB_LINK->query('SELECT address FROM nw_contacts WHERE address = \''.$e.'\'');
	$rows = $result->result_r();
	if(count($rows) > 0)
		error(4);
}

function validateInputs($e,$u,$p,$n)
{
	//Validates form contents before registration.

	$regMatch = '/^\w{0,}$/';

	//Check for blank or too-short fields.
	if(strlen($e) < 1 || strlen($u) < 4 ||
	   strlen($p) < 5 || strlen($n) < 5 )
		error(0);

	//Check for too-long fields.
	if(strlen($e) > 254 || strlen($u) > 15 ||
	   strlen($n) > 25 )
		error(0);
	
	//Verify that the username contains only valid characters.
	if(preg_match($regMatch,$u) != 1)
		error(0);

	//Verify that the name contains only valid characters.
	if(preg_match('/^\w{2,} \w{2,}$/',$n) != 1)
		error(0);
	
	//Check validity of email address.
	if(filter_var($e,FILTER_VALIDATE_EMAIL) === false)
		error(0);
}

function error($message)
{
	echo $message;
	exit;
}

?>
