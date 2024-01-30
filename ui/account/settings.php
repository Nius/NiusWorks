<?php
$PAGE_TITLE = 'NW Account Settings';
?>

<?php

require_once('/web/serve/niusworks.com/resources/modules/header.php');
requireLogin();

$whr =	'Identifier = '.$_SESSION['user']['uid'].' OR '.
	'Identifier = \''.$_SESSION['user']['email'].'\' OR '.
	'Identifier = \''.$_SESSION['user']['uname'].'\'';
$result = $DB_LINK->query('SELECT Date FROM USR_Logins WHERE '.$whr.' AND Result = 1 ORDER BY Date DESC LIMIT 2');
$logins = $result->result_r();
$thisLogin = $logins[0]['Date'];
$lastLogin = $logins[1]['Date'];
if(!isset($lastLogin))
	$lastLogin = '[never]';
$result = $DB_LINK->query('SELECT Updated FROM USR_Passwords WHERE UID = '.$_SESSION['user']['uid']);
$pwdate = $result->result_r();
$pwdate = $pwdate[0]['Updated'];

?>
<link type='text/css' rel='stylesheet' href='/css/account/settings.css'></link>
<script type='text/javascript' src='/js/account/settings.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-12'>

<h4>Account</h4>
<blockquote>Your account number is <span class='white'><?=$_SESSION['user']['uid']?></span>.
<br><br>Your most recent login was at this time: <span class='white'><?=$thisLogin?></span>.
<br>Your previous login was at this time: <span class='white'><?=$lastLogin?></span>.
<br>Your password was most recently changed at this time: <span class='white'><?=$pwdate?></span>.
<br><button id='cpB' class='repel-top btn btn-inverse'>Change Password</button>
</blockquote>

</div>
</div> <?php /* End Row 0 */ ?>

</div> <?php /* End container */ ?>

<?php
	//
	// CHANGE PASSWORD MODAL
	//
?>

<div class='modal fade' id='changePasswordM' tabindex='-1' role='dialog'>
 <div class='modal-dialog'>
  <div class='modal-content'>
   <div class='modal-header'>
    <button type='button' class='close' data-dismiss='modal'>&times;<span class='sr-only'>Close</span></button>
    <h4 class='modal-title'>Change Password</h4>
   </div>
   <div class='modal-body' id='CPMbody'>
    <p id='CPMinstruct'>You are about to change your NiusWorks.com account password.
    </p>
    <div class='form-group'>
     <label for='CPM_old'>Current Password</label>
     <input type='password' class='form-control' id='CPM_old' placeholder='Enter your old password here.' />
    </div>
    <div class='form-group'>
     <label for='password'>New Password</label>
     <input type='password' class='form-control' id='password' placeholder='Enter your new password here.' />
    </div>
    <div class='form-group'>
     <label for='xpassword'>Confirm New Password</label>
     <input type='password' class='form-control' id='xpassword' placeholder='Re-enter your new password.' />
    </div>
    <p id='CPMerrP'>&nbsp;</p>
   </div>
   <div class='modal-footer'>
    <button type='button' class='btn btn-success' id='CPMexecuteB'>Change Password</button>
    <button type='button' class='btn btn-danger' id='CPMcancelB' data-dismiss='modal'>Cancel</button>
   </div>
  </div>
 </div>
</div>

</body>

<div hidden id='pwpc'>
Create a password to use for your NiusWorks account.
<br>Passwords are case-sensitive.
<br>Your password is private, and will never be accessible to other users or to web or system administrators.
<br><span id='pwpc_char' class='red'><span class='glyphicon glyphicon-remove'></span> Must contain at least 5 characters</span>
</div>
<div hidden id='xppc'>
Confirm the password you just created by typing it again.
<br><span id='xppc_match' class='red'><span class='glyphicon glyphicon-remove'></span> Must match your original password</span>
</div>

</html>
