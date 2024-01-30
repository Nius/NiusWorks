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

$classID = $_POST['action'];

//
// NEW
//

if(strcmp($classID,'new') === 0)
{

	// Check class type; fail if not valid.
	$type = $_POST['type'];
	switch($type)
	{
		case 'C':
			$type = 1;
			break;
		case 'M':
			$type = 2;
			break;
		case 'T':
			$type = 3;
			break;
		default:
			die('0');
	}

	// Sanitize class name.
	$name = $DB_LINK->escape($_POST['name']);
	if(strlen($name) < 1)
		die('0');

	// Sanitize pattern string.
	$pattern = $DB_LINK->escape(json_decode($_POST['pattern']));
	if(strlen($pattern) < 1)
		die('0');

	// Create new class.
	$query = "INSERT INTO $FPJ_CLASSES_TABLE VALUES(null,$type,'$name','$pattern')";

	$result = $DB_LINK->query($query);
	if($result !== false)
		die('1');
	else
		die('2');

}

//
// MODIFY
//

$classID += 0;

// Verify that the class already exists
$query = "SELECT * FROM $FPJ_CLASSES_TABLE WHERE ID = $classID";
if(count($DB_LINK->query($query)->result_r()) !== 1)
	die('0');

// Update the class with the new pattern
$query = "UPDATE $FPJ_CLASSES_TABLE SET PATTERN = '".$DB_LINK->escape(json_decode($_POST['pattern']))."' ".
	 "WHERE ID = $classID";
$DB_LINK->query($query);

// Get all CURRENT subclasses; they will be compared with the NEW set of subclasses.
$query = "SELECT * FROM $FPJ_SUBCLASS_TABLE WHERE PARENT = $classID";
$current_subs = $DB_LINK->query($query)->result_r();

$remove = '(';
$add = array_flip($_POST['subs']);

foreach($current_subs as $current_sub)
{
	$id = $current_sub['CHILD'];

	// There are three possible cases when comparing the new list of subclasses with the existing one:
	// 1. Class is in both lists: is already a subclass and should remain one.
	// 2. Class is in the new list only: is not a subclass and should become one.
	// 3. Class is in the old list only: is a subclass and should stop being one.

	// Case 1
	// Remove sub from the list of new additions
	// but do not add it to the list of removals.
	if(array_key_exists($id,$add))
		unset($add[$id]);

	// Case 3
	// Add the sub to the list of removals.
	else
		$remove .= $id.',';

	// Case 2
	// Sub is in the new list only already; no changes necessary.
}
$remove = substr_replace($remove,')',-1);

// Remove all subclasses slated for removal
$query = "DELETE FROM $FPJ_SUBCLASS_TABLE WHERE PARENT = $classID AND CHILD IN $remove";
$DB_LINK->query($query);

// Add all subclasses slated for addition
$query = "INSERT INTO $FPJ_SUBCLASS_TABLE VALUES(null,$classID,";
foreach($add as $key => $_)
	$DB_LINK->query($query.$key.')');

die('1');
