<?php

// DEPENDENCY: package APCu

// This file is included on all webpages, by /resources/modules/header.php
//
// This script has several AJAX functions, at the end of the file.
//

//
//	SCRIPT SETUP & CONFIG
//

// Execute standardized session setup
require_once('/web/serve/niusworks.com/cgi/session/session.php');

// Where to look for sessioned permissions information
$_USER_PERMS =& $_SESSION['user']['permissions'];

// Load permissions library from cache
$PERMS = apcu_fetch('permissions');
if($PERMS === false) // Permissions not yet loaded to cache
{
	$PERMS = simplexml_load_file('/web/serve/niusworks.com/cgi/permissions/permissions.xml') or $PERMS = false;
	//print_r($PERMS->children());
	$PERMS = json_decode(json_encode($PERMS),true)['permission'];
	apcu_store('permissions',$PERMS);
}

//
//	LIBRARY
//

// Print out all permissions information, with information relating
//	to this user.
function printPermInfo()
{
	global $PERMS;
	global $_USER_PERMS;

	echo '<style>body{color:white;background-color:black;font-family:courier;}</style>';

	echo 'DEFINED PERMISSIONS<br><br>';
	for($i = 0; $i < strlen($_USER_PERMS); $i ++)
		echo $i.'&emsp;'.$PERMS[$i]['@attributes']['alias'].':'.getPrivilegeLevel($i).
			'&emsp;'.getPrivilege($i)['@attributes']['role'].'<br>';

	echo '<br>UNDEFINED PERMISSIONS<br><br>';
	for($i = strlen($_USER_PERMS); $i < count($PERMS); $i ++)
		echo $i.'&emsp;'.$PERMS[$i]['@attributes']['alias'].':'.getPrivilegeLevel($i).
			'&emsp;'.getPrivilege($i)['@attributes']['role'].'<br>';
}

// Return whether the current user has the specified permission(s)
//	at or above the specified level(s).
// If is_array($1) then each permission will be tested against
//	the minimum.
// The minimum is $2, or 1 if $2 is omitted.
// If is_array($2) then each $1 will be tested against
//	the corresponding $2.
// Return -1 if is_array($1) and is_array($2) and count($1) !=
//	count($2).
//
// ALL permissions must meet their minimum to return true.
// If ($3) == 'OR' then only any one permission must meet its
//	minimum to return true.
function hasPermission($i,$min = 1,$andor = 'AND')
{
	$lvls = getPrivilegeLevel($i);
	if(!is_array($lvls))
		return $lvls >= $min;

	if(is_array($min) && count($min) != count($lvls))
		return -1;

	for($j = 0; $j < count($lvls); $j ++)
	{
		if(is_array($min))
			$pass = $lvls[$j] >= $min[$j];
		else
			$pass = $lvls[$j] >= $min;
		if($andor == 'OR')
		{
			if($pass == true)
				return true;
		}
		else
			if($pass == false)
				return false;
	}
	return $andor != 'OR';
}

// Deny access to the page if not hasPermission($1,$2 = 1).
// If the third argument is an int, the corresponding HTTP
//	status code will be returned.
// If the third argument is a string, a redirect will
//	execute to the specified address.
// The third argument defaults to 404.
function requirePermission($i,$min = 1,$action = 404)
{
	if(hasPermission($i,$min))
		return;
	if(!is_numeric($action))
	{
		header('Location: '.$action);
		exit();
	}
	else
	{
		http_response_code($action);
		include('/web/serve/'.$_SERVER['HTTP_HOST'].'/ui/error.php');
		exit();
	}
}

// Return the privilege information (not just the level index) for
//	the given permission index or alias.
function getPrivilege($i)
{
	global $PERMS;

	if(!is_numeric($i))
		$i = getIndexForAlias($i);

	$lvl = getPrivilegeLevel($i);
	return ($lvl == -1) ? false : $PERMS[$i]['level'][$lvl];
}

// Return the index of the permission specified by the provided alias.
// Return -1 if no permission is found with the given alias.
function getIndexForAlias($alias)
{
	global $PERMS;

	for($i = 0; $i <= count($PERMS); $i ++)
		if(strcasecmp($PERMS[$i]['@attributes']['alias'],$alias) == 0)
			return $i;
	return -1;
}
	

// Returns the numeric value of the current user's privilege level
//	for the specified permission(s).
//
// getPrivilegeLevel(array)	Returns an array of integers representing
//				the permission levels of each of the perm
//				identifiers in the provided array.
// getPrivilegeLevel(string)	Returns an integer representing the
//				permission level of the provided permission
//				alias.
// getPrivilegeLevel(int)	Returns an integer representing the
//				permission level of the provided permission
//				index.
//
// A value of -1 indicates that no permission could be found which
//	matched a specified alias.
//
function getPrivilegeLevel($i)
{
	global $_USER_PERMS;

	// ARRAY
	//	For each array element, make a separate
	//	getPrivilege() call with that element.
	//	Return an array of the ordered results
	//	of those recursive calls.
	if(is_array($i))
	{
		$ret = array();
		foreach($i as $ie)
			$ret[] = getPrivilegeLevel($ie);
		return $ret;
	}

	// ALIAS
	//	Search the list of permissions for a matching
	//	alias, and call getPrivilege() with the index
	//	of the first matching permission.
	//	Return -1 if no permission found.
	if(!is_numeric($i))
	{
		$ni = getIndexForAlias($i);
		return ($ni == -1) ? -1 : getPrivilegeLevel($ni);
	}

	// INT (base case)
	//	Return the user permission level at the
	//	specified index.
	if(is_numeric($i))
	{
		if($i > strlen($_USER_PERMS) || $i < 0)
			return 0;
		return substr(strrev($_USER_PERMS),$i,1) + 0;
	}
}

//
//	AJAX
//

if($_SERVER['SCRIPT_NAME'] === '/cgi/permissions/permissions.php' && isset($_POST['fn']))
{
	if(!hasPermission('ADM',2))
	{
		echo 0;
		exit();
	}
	switch($_POST['fn']){
	case('recache'):
		apcu_delete('permissions');
		echo 1;
		break;
	case('resession'):
		require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
		$result = $DB_LINK->query('SELECT Perms FROM USR_Accounts WHERE ID='.$_SESSION['user']['uid']);
		$_SESSION['user']['permissions'] = $result->result_r()[0]['Perms'];
		echo 1;
		break;
	default:
		echo 0;
	}
}

?>
