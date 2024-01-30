<?php

require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');
requirePermission('SFD');

$uid = $_SESSION['user']['uid'];
$DIR = '/web/serve/niusworks.com/resources/files/SFD/'.$uid;

if(!file_exists($DIR))
	mkdir($DIR);

foreach($_FILES as $FILE)
{
	$newPath = $DIR.'/'.$FILE['name'][0];
	while(file_exists($newPath))
		$newPath .= '.copy';

	$newLocal = fopen($newPath,'w');
	fwrite($newLocal,file_get_contents($FILE['tmp_name'][0]));
	fclose($newLocal);
}

?>
