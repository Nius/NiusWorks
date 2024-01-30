<?php

require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
require_once('/web/serve/niusworks.com/cgi/session/session.php');

// AJAX calls execute login.
// Note that password sterilization is not necessary because
//	passwords are hashed before being queried.
$i = $DB_LINK->escape($_REQUEST['un']);
$p = $_REQUEST['pw'];
if(executeLogin($i,$p))
	echo 1;
else
	echo 0;

//All login operations pass through this function.
//	: Session and user setup occur here.
//	: Where applicable, password validation occurs here.
//	: Identifier detection and validation occur here.
//This function can be called by external scripts, usually for
//	administrative purposes. This is currently done by:
//		: [none]
//Passing NULL and FALSE as the second and third arguments will
//	circumvent password validation.
function executeLogin($i,$pw,$verify = true)
{
	global $DB_LINK;

	$i = $DB_LINK->escape($i);

	//
	// Determine the login key type.
	//

	//If the login key is an email, query the Contacts
	//	table for a matching email address.
	//The address must be active and not removed.
	if(filter_var($i,FILTER_VALIDATE_EMAIL) !== false)
	{
		$result = $DB_LINK->query('SELECT UID, Email FROM USR_Contacts WHERE Email = \''.$i.'\' AND Flags != 0');
		$rows = $result->result_r();
		if(count($rows) < 1)
			return logAttempt($i,0);
		$uid = $rows[0]['uid'];
		if($rows[0]['Flags'] == 2)
			$eaddress = $rows[0]['Email'];
		$where = 'ID = '.$uid;
	}
	//ID Number
	else if(is_numeric($i))
		$where = 'UID = '.$i;
	//Username
	else
		$where = 'Username = \''.$i.'\'';

	//
	// Create and execute user information queries.
	//

	$result = $DB_LINK->query('SELECT ID, Username, Flags, Perms, Name FROM USR_Accounts WHERE '.$where);
	$rows = $result->result_r();
	if(count($rows) != 1)
		return logAttempt($i,0);
	$row = $rows[0];

	//Get the user's email address, if it isn't already found.
	//If the login was attempted using an email address AND the
	//	email address had a flag of 2 (indicating primary)
	//	then the email address has already been found.
	if(!isset($eaddress))
	{
		$emResult = $DB_LINK->query('SELECT Email FROM USR_Contacts WHERE UID = '.$row['ID'].' AND Flags = 2');
		$emRows = $emResult->result_r();
		$eaddress = $emRows[0]['Email'];
	}

	$pwResult = $DB_LINK->query('SELECT Hash FROM USR_Passwords WHERE UID = '.$row['ID']);
	$pwRows = $pwResult->result_r();
	$pwRow = $pwRows[0];
	$hash = $pwRow['Hash'];

	if($verify)
		if(!password_verify($pw,$hash))
			return logAttempt($i,0);

	//
	// Commit user information to the session.
	//

	$_SESSION['user'] = array(
		'uid'		=> $row['ID'],
		'uname'		=> $row['Username'],
		'pname'		=> $row['Name'],
		'flags'		=> $row['Flags'],
		'email'		=> $eaddress,
		'permissions'	=> $row['Perms']		);

	// Finish

	logAttempt($i,1);
	return true;
}

function logAttempt($i,$success)
{
	global $DB_LINK;

	$unused = $DB_LINK->query('INSERT INTO USR_Logins VALUES(null,\''.$i.'\',null,'.$success.')');
	return false;
}

?>
