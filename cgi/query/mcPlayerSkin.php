<?php

// Requires library: php70-gd

require_once('/web/serve/niusworks.com/cgi/session/session.php');

// Check whether the minecraft skin is already on file within the
//	last hour. If it is, use that rather than querying Minecraft's
//	servers. This limits Minecraft server queries to one per hour, (per
//	user) preventing retaliation from Minecraft due to attack on NiusWorks.
$IMGS_DIR = 'resources/images/minecraftHeads/';
$LOCAL_FILE = '/web/serve/niusworks.com/'.$IMGS_DIR.$_REQUEST['playerName'].'.png';
if(file_exists($LOCAL_FILE) &&
   time() - filemtime($LOCAL_FILE) < 3600)
   $URL = 'https://niusworks.com/'.$IMGS_DIR.$_REQUEST['playerName'].'.png';
else
{
	// Get player UUID
	$URL = 'https://api.mojang.com/users/profiles/minecraft/'.$_REQUEST['playerName'];
	$UUID = json_decode(file_get_contents($URL),true)['id'];

	// Get URL to player skin
	$URL = 'https://sessionserver.mojang.com/session/minecraft/profile/'.$UUID;
	$URL = json_decode(base64_decode(json_decode(file_get_contents($URL),true)['properties'][0]['value']),true)['textures']['SKIN']['url'];

	require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
	$DB_LINK->query('INSERT INTO QRY_Callouts VALUES(null,'.$_SESSION['user']['uid'].',\''.__FILE__.'\',\''.$URL.'\',null)');

	$needToReFile = true;
}

$SIZE = isset($_REQUEST['size']) ? $_REQUEST['size'] : 80;

$img = imagecreatefrompng($URL);

// Refile if necessary (see top of script)
if(isset($needToReFile))
	imagepng($img,$LOCAL_FILE);

if($img === false)
{
	$img = imagecreatefrompng($FALLBACK);
}
else if($_REQUEST['seek'] == 'head')
{
	$dims = ['x'	  => 8,
		 'y'	  => 8,
		 'width'  => 8,
		 'height' => 8	];
	$img = imagecrop($img,$dims);
	$newimg = imagecreate($SIZE,$SIZE);
	imagecopyresized($newimg,$img,0,0,0,0,$SIZE,$SIZE,8,8);
	$img = $newimg;
}

// Output
header('Content-Type: image/png');
imagepng($img);

?>
