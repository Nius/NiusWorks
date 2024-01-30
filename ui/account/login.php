<?php
$PAGE_TITLE = 'NW Login';
?>

<?php
require_once('/web/serve/niusworks.com/resources/modules/header.php');
forbidLogin();
?>
<link type='text/css' rel='stylesheet' href='/css/account/login.css'></link>
<script type='text/javascript' src='/js/account/login.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-12'>
<p id='banner' class='center h3'>Log In to NiusWorks</p>
</div> <?php /* End Row 0 */ ?>

<?php /* Row 1 */ ?>
<div class='row'>
<div class='col-xs-8 col-xs-offset-2'>
<div class='form-group'>
	<label class='control-label' for='username'>Username</label>
	<div class='input-group'>
		<span class='input-group-addon'><span class='glyphicon glyphicon-user'></span></span>
		<input type='text' class='form-control' id='username'>
	</div>
</div>
<div class='form-group'>
	<label class='control-label' for='password'>Password</label>
	<div class='input-group'>
		<span class='input-group-addon'><span class='glyphicon glyphicon-lock'></span></span>
		<input type='password' class='form-control' id='password'>
	</div>
</div>

</div> <?php /* End Row 1 */ ?>

<?php /* Row 2 */ ?>
<div class='row'>
<div class='col-xs-8 col-xs-offset-2'>
<p id='errP' class='center'>&nbsp;</p>
<p class='center'><button id='loginB' class='btn btn-success'>Log In</button></p>
</div> <?php /* End Row 2 */ ?>


</div> <?php /* End container */ ?>

</body>
</html>
