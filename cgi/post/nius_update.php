<?php

require_once('/web/serve/niusworks.com/cgi/session/session.php');
require_once('/web/serve/niusworks.com/resources/libraries/sql.php');

if($_SESSION['user']['uid'] != 1000)
	exit('0');

$ARTICLES_DIR = '/web/serve/niusworks.com/resources/files/nius/longPosts/';

$content = $DB_LINK->escape($_POST['content']);

switch($_POST['mode']){

/////////////
case 'short':

	$result = $DB_LINK->query('INSERT INTO NAX_Posts VALUES(null,null,\''.$content.'\',0)');
break;

////////////////
case 'long_new':

	$title = $DB_LINK->escape($_POST['title']);

	// Check for title in use
	$result = $DB_LINK->query('SELECT content FROM NAX_Posts WHERE Content = \''.$title.'\'');
	if(count($result->result_r()) > 0)
		exit('title');

	// Create file
	$result = $DB_LINK->query('INSERT INTO NAX_Posts VALUES(null,null,\''.$title.'\',2)');
	$fid = $result->insert_id();
	touch($ARTICLES_DIR.$fid.'.html') or exit('file');
	exit((string)$fid);
	break;

//////////////////
case 'long_query':

	$aid = $_POST['aid'] + 0;
	$package['title'] = $DB_LINK->query('SELECT Content FROM NAX_Posts WHERE id = '.$aid)->result_r()[0]['content'];
	$package['content'] = file_get_contents($ARTICLES_DIR.$aid.'.html');
	exit(json_encode($package));

////////////////////
case 'long_publish':
case 'long_update':
	$aid = $_POST['aid'] + 0;
	$file = $ARTICLES_DIR.$aid.'.html';
	if(!file_exists($file))
		exit('0');
	if(file_put_contents($file,$_POST['content']) === false)
		exit('0');
	if(strcmp($_POST['mode'],'long_publish') == 0)
		$DB_LINK->query('UPDATE NAX_Posts SET Date = NOW(), Flags = 1 WHERE ID = '.$aid);
	break;

////////
default:
	exit('0');
}

exit('1');

?>
