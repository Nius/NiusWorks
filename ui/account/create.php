<?php
$PAGE_TITLE = 'NW Signup';
?>

<?php
require_once('/web/serve/niusworks.com/resources/modules/header.php');
forbidLogin();
?>
<link type='text/css' rel='stylesheet' href='/css/account/create.css'></link>
<script type='text/javascript' src='/js/account/create.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-12'>
<p id='bannerP' class='h3 center'>
Create a NiusWorks Account
</p>
<p id='subBannerP' class='italic center'>
Enrollment constitutes total, permanent consent to our <a href='/ui/account/tos.php'>Terms of Service</a>.
</p>
<p id='construction' class='center yellow'>Because NiusWorks is still in the early stages of construction, account creation is currently disabled.</p>
</div>
</div> <?php /* End Row 0 */ ?>

<?php /* Row 1 */ ?>
<div class='row'>
<div class='col-xs-12'>
	<div class='area'>
	<p>Logistical</p>
	<div class='form-group'>
		<label class='control-label' for='username'>Username</label>
		<div class='input-group'>
			<span class='input-group-addon'><span class='glyphicon glyphicon-user'></span></span>
			<input type='text' class='form-control' id='username' placeholder='CPilru'>
		</div>
	</div>
	<div class='form-group'>
		<label class='control-label' for='email'>Email Address</label>
		<div class='input-group'>
			<span class='input-group-addon'><span class='glyphicon glyphicon-envelope'></span></span>
			<input type='email' class='form-control' id='email' placeholder='Cammar.V.Pilru@ixmail.com'>
		</div>
	</div>
	<div class='form-group'>
		<label class='control-label' for='password'>Create Password</label>
		<div class='input-group'>
			<span class='input-group-addon'><span class='glyphicon glyphicon-lock'></span></span>
			<input type='password' class='form-control' id='password' placeholder='spiceboyz10196'>
		</div>
	</div>
	<div class='form-group'>
		<label class='input-group' for='xpassword'>Confirm Password</label>
		<div class='input-group'>
			<span class='input-group-addon'><span class='glyphicon glyphicon-lock'></span></span>
			<input type='password' class='form-control' id='xpassword' placeholder='spiceboyz10196'>
		</div>
	</div>
	</div>
	<div class='area'>
	<p>Personal</p>
	<div class='form-group'>
		<label for='name'>Your Name</label>
		<input type='text' class='form-control' id='name' placeholder='Cammar Pilru'>
	</div>
	</div>
	<div class='area'>
		<p id='errP'></p>
		<table><tr>
		<td>
			<button id='submit' class='btn btn-success'><span class='glyphicon glyphicon-ok-circle'></span> Sign Up</button>
			<button id='tosbutton' class='btn btn-info'><span class='glyphicon glyphicon-new-window'></span> View our ToS</button>
		</td><td>
		<button id='clearB' class='btn btn-danger'><span class='glyphicon glyphicon-remove-circle'></span> Clear Form</button>
		</tr></table>
	</div>
</div>
</div> <?php /* End Row 1 */ ?>

</div> <?php /* End container */ ?>

</body>
<div hidden id='unpc'>
Create a username to use throughout the NiusWorks system.
<br>This will be the name you use to log in, and the name by which other users know you.
<br><span id='unpc_char' class='red'><span class='glyphicon glyphicon-remove'></span> Must be at least 4 characters long</span>
<br><span id='unpc_reg' class='green'><span class='glyphicon glyphicon-ok'></span> Must contain only letters, numbers, and underscores</span>
<br><span id='unpc_max' class='green'><span class='glyphicon glyphicon-ok'></span> Must be no more than 15 characters long</span>
</div>
<div hidden id='empc'>
Choose the email address with which you'd like us to communicate.
<br>We'll use this address for notifications, password recovery, and other services as you see fit.
<br>When you sign up, we'll send you a verificaion email with an account activation link.
<br><span id='empc_vem' class='red'><span class='glyphicon glyphicon-remove'></span> Must be a valid email address</span>
<br><span id='empc_max' class='green'><span class='glyphicon glyphicon-ok'></span> Must be no more than 30 characters long</span>
</div>
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
<div hidden id='pnpc'>
Let us address you by name.
<br><span id='pnpc_token' class='red'><span class='glyphicon glyphicon-remove'></span> Must have your first and last name</span>
<br><span id='pnpc_char' class='red'><span class='glyphicon glyphicon-remove'></span> Each name must be at least two characters</span>
<br><span id='pnpc_max' class='green'><span class='glyphicon glyphicon-ok'></span> Must be no more than 25 characters long</span>
</div>
</html>
