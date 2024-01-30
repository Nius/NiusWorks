<?php
/*
Session.php

This library manages all session operations.
This file is included on all webpages, by /resources/modules/header.php

*/

session_start();

// Default user
if(!isset($_SESSION['user']))
{
	$_SESSION['user'] = array (	'name'	=> 'Guest',
					'uid'	=> -1,
					'uname'	=> 'Guest',
					'pname' => 'Guest',
					'permissions' => 0	);
}

function requireLogin($redirect = '/ui/home.php')
{
	if(!isLoggedIn())
		header('Location: '.$redirect);
}

function forbidLogin($redirect = '/ui/home.php')
{
	if(isLoggedIn())
		header('Location: '.$redirect);
}

function isLoggedIn()
{
	return ($_SESSION['user']['uid'] != -1);
}

?>
