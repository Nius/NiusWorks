<?php

//require_once('/web/serve/niusworks.com/cgi/session/session.php');
require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');
requirePermission('SFD');

$file = basename($_REQUEST['filename']);
$uid = $_SESSION['user']['uid'];
$path = '/web/serve/niusworks.com/resources/files/SFD/'.$uid.'/'.$file;
if(!is_readable($path) || is_dir($path))
{
	header('HTTP/1.1 404 Not Found');
	require('/web/serve/niusworks.com/ui/error.php');
	exit;
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=\''.$file.'\'');
readfile($path);
?>
