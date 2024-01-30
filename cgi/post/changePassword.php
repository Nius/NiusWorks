<?php

/* RESPONSE DICTIONARY

-1 Not currently logged in
 1 Success
 2 Current password authentication failed
 3 Invalid new password

*/

require_once('/web/serve/niusworks.com/cgi/session/session.php');
require_once('/web/serve/niusworks.com/resources/libraries/sql.php');

if(!isLoggedIn())
	exit('-1');

$op = $_REQUEST['obsolete'];
$np = $_REQUEST['requested'];

//Verify current password
$result = $DB_LINK->query('SELECT Hash FROM USR_Passwords WHERE UID = '.$_SESSION['user']['uid']);
$rows = $result->result_r();
$row = $rows[0];
$hash = $row['Hash'];

if(!password_verify($op,$hash))
	exit('2');

//Validate new password
if(strlen($np) < 5)
	exit('3');

//Install new password
$options = ['cost' => 13];
$output = password_hash($np,PASSWORD_BCRYPT,$options);

$result = $DB_LINK->query('UPDATE USR_Passwords SET Hash = \''.$output.'\', Updated = NOW() WHERE UID = '.$_SESSION['user']['uid']);

	exit('1');

?>
