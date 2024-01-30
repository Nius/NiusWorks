<?php

// Set up account types.
$FPJ_ACCOUNT_TYPES =
[
	1 =>
	[
		'name' => 'Bank of America Checking',
		'formats' =>
		[
			['name'=>'BoA Text File','filetype'=>'.txt','value'=>'BOATXT']
		]
	],
	
	2 =>
	[
		'name' => 'Chase Freedom Credit',
		'formats' =>
		[
			['name'=>'Undefined Format','filetype'=>'.txt','value'=>'???']
		]
	]
];

// Convenience variables

$FPJ_TRX_TABLE = 'FPJ_Transactions_'.$_SESSION['user']['uid'];
$FPJ_STAGE_TABLE = 'FPJ_Staging_'.$_SESSION['user']['uid'];

$FPJ_CLASSES_TABLE = 'FPJ_Classes_'.$_SESSION['user']['uid'];
$FPJ_SUBCLASS_TABLE = 'FPJ_Subclass_'.$_SESSION['user']['uid'];

$FPJ_REL_C_TABLE = 'FPJ_Rel_Categories_'.$_SESSION['user']['uid'];
$FPJ_REL_M_TABLE = 'FPJ_Rel_Merchants_'.$_SESSION['user']['uid'];
$FPJ_REL_T_TABLE = 'FPJ_Rel_Trackers_'.$_SESSION['user']['uid'];

// Ensure that all the necessary tables exist.
require_once('/web/serve/niusworks.com/resources/libraries/sql.php');

$qys[] = 'CREATE TABLE IF NOT EXISTS '.$FPJ_TRX_TABLE.'('.
	 'ID INT PRIMARY KEY AUTO_INCREMENT, '.
	 'ACCOUNT INT NOT NULL, '.
	 'TRXDATE DATE NOT NULL, '.
	 'VALUE DECIMAL(7,2) NOT NULL, '.
	 'RTOTAL DECIMAL(7,2) NOT NULL, '.
	 'DESCRIPTION VARCHAR(255) NOT NULL'.
	 ') engine=innoDB';

$qys[] = 'CREATE TABLE IF NOT EXISTS '.$FPJ_STAGE_TABLE.'('.
	 'ID INT PRIMARY KEY AUTO_INCREMENT, '.
	 'ACCOUNT INT NOT NULL, '.
	 'TRXDATE DATE NOT NULL, '.
	 'VALUE DECIMAL(7,2) NOT NULL, '.
	 'RTOTAL DECIMAL(7,2) NOT NULL, '.
	 'DESCRIPTION VARCHAR(255) NOT NULL'.
	 ') engine=innoDB';

$qys[] = 'CREATE TABLE IF NOT EXISTS '.$FPJ_CLASSES_TABLE.'('.
	 'ID INT PRIMARY KEY AUTO_INCREMENT, '.
	 'TYPE INT NOT NULL, '.
	 'NAME VARCHAR(32) NOT NULL, '.
	 'PATTERN VARCHAR(255) NOT NULL'.
	 ') engine=innoDB';

$qys[] = 'CREATE TABLE IF NOT EXISTS '.$FPJ_SUBCLASS_TABLE.'('.
	 'ID INT PRIMARY KEY AUTO_INCREMENT, '.
	 'PARENT INT NOT NULL, '.
	 'CHILD INT NOT NULL'.
	 ') engine=innoDB';

$qys[] = 'CREATE TABLE IF NOT EXISTS '.$FPJ_REL_C_TABLE.'('.
	 'ID INT PRIMARY KEY AUTO_INCREMENT, '.
	 'TRX_ID INT NOT NULL, '.
	 'CAT_ID INT NOT NULL'.
	 ') engine=innoDB';

$qys[] = 'CREATE TABLE IF NOT EXISTS '.$FPJ_REL_M_TABLE.'('.
	 'ID INT PRIMARY KEY AUTO_INCREMENT, '.
	 'TRX_ID INT NOT NULL, '.
	 'MERCHANT_ID INT NOT NULL'.
	 ') engine=innoDB';

$qys[] = 'CREATE TABLE IF NOT EXISTS '.$FPJ_REL_T_TABLE.'('.
	 'ID INT PRIMARY KEY AUTO_INCREMENT, '.
	 'TRX_ID INT NOT NULL, '.
	 'TRACK_ID INT NOT NULL'.
	 ') engine=innoDB';

foreach($qys as $query)
	$_ = $DB_LINK->query($query);

function FPJ_get_account_names()
{
	global $DB_LINK;

	$query = "SELECT ID, Name FROM FPJ_Accounts WHERE Owner = ".$_SESSION['user']['uid'];
	$result = $DB_LINK->query($query);
	$accts_raw = $result->result_r();
	$accounts = Array();
	foreach($accts_raw as $act)
		$accounts[$act['ID']] = $act['Name'];
	return $accounts;
}

/* Process the given list of transactions, applying any matching
   classes and those classes' parents.
*/
function FPJ_attempt_class_matching(&$trxs)
{
	global $DB_LINK;
	global $FPJ_SUBCLASS_TABLE;
	global $FPJ_CLASSES_TABLE;

	// Load Subclass Relationships
	$subs = Array();
	$result = $DB_LINK->query("SELECT PARENT, CHILD FROM $FPJ_SUBCLASS_TABLE")->result_r();
	foreach($result as $row)
		$subs[$row['CHILD']][] = $row['PARENT'];

	// Load classes
	$classes = Array();
	$result = $DB_LINK->query("SELECT * FROM $FPJ_CLASSES_TABLE")->result_r();
	foreach($result as $row)
	{
		// Add this class to an array keyed by ID
		$classes[$row['ID']] = $row;

		// If this class has a parent, add that to the class
		if(array_key_exists($row['ID'],$subs))
			$classes[$row['ID']]['PARENTS'] = $subs[$row['ID']];
	}

	// Process matches
	foreach($trxs as &$trx)
		foreach($classes as $class)
			if(preg_match('/'.$class['PATTERN'].'/',$trx['DESCRIPTION']) === 1)
				FPJ_add_class_to_trx($classes, $class, $trx);
}

/* Get all transactions that match the provided class or its children, recursively.
   Transactions match the class if they meet either of these conditions:
	- Their description matches the class's search pattern
	- They match a class that is a descendent of the provided class
*/
function FPJ_get_child_trxs($class_id, &$matches = Array())
{
	global $DB_LINK;
	global $FPJ_CLASSES_TABLE;
	global $FPJ_SUBCLASS_TABLE;

	$class_id += 0;
	$query = "SELECT PATTERN FROM $FPJ_CLASSES_TABLE WHERE ID = $class_id";
	$class = $DB_LINK->query($query)->result_r()[0];

	FPJ_get_matches_with_pattern($class['PATTERN'], $matches);

	// Get any child classes.
	$query = "SELECT CHILD FROM $FPJ_SUBCLASS_TABLE WHERE PARENT = ".$class_id;
	$children = $DB_LINK->query($query)->result_r();
	foreach($children as $child)
	{
		FPJ_get_child_trxs($child['CHILD'], $matches);
	}

	return $matches;
}

// Get all transactions that match the provided regex pattern.
function FPJ_get_matches_with_pattern($pattern, &$matches = Array())
{
	global $DB_LINK;
	global $FPJ_STAGE_TABLE;
	global $FPJ_TRX_TABLE;

	// Get matches to the provided pattern.
	$where = "WHERE DESCRIPTION REGEXP '".$DB_LINK->escape($pattern)."'";
	$query = "(SELECT * FROM $FPJ_STAGE_TABLE $where) ".
		 "UNION ALL ".
		 "(SELECT * FROM $FPJ_TRX_TABLE $where) ORDER BY TRXDATE";
	$matches = array_merge($matches,$DB_LINK->query($query)->result_r());

	return $matches;
}

// Get all transactions that match the provided regex pattern
// or are children of the provided classID's or their subclasses.
function FPJ_get_matches_with_pattern_and_classIDs($pattern, $classIDs)
{
	global $DB_LINK;
	global $FPJ_CLASSES_TABLE;

	$matches = FPJ_get_matches_with_pattern($pattern);

	if(is_array($classIDs))
		foreach($classIDs as $id)
			FPJ_get_child_trxs($id,$matches);

	return $matches;
}

/* Add a class to a transaction.

   $classes
	The array of all classes, passed down so it doesn't
	have to be defined globally.
   $class
	The class to add. This is the actual class, not just
	its ID.
   &$trx
	The transaction to which to add the class.
*/
function FPJ_add_class_to_trx($classes, $class, &$trx)
{
	switch($class['TYPE'])
	{
		case 1: // Category
			$trx['_CATEGORIES'][] = $class;
			break;
		case 2: // Merchant
			$trx['_MERCHANTS'][] = $class;
			break;
		case 3: // Tracker
			$trx['_TRACKERS'][] = $class;
			break;
	}

	// If this class has parent classes, add them immediately.
	if(isset($class['PARENTS']))
		foreach($class['PARENTS'] as $parent_id)
			FPJ_add_class_to_trx($classes, $classes[$parent_id], $trx);
	
}

function FPJ_resolve_type_to_name($typenum, $caps = false)
{
	switch($typenum)
	{
		case 1: $out = 'Category'; break;
		case 2: $out = 'Merchant'; break;
		case 3: $out = 'Tracker'; break;
	}
	return $caps ? strtoupper($out) : $out;
}
