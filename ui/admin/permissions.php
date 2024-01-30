<?php
$PAGE_TITLE = 'NW ADM-Perms';
?>

<?php

require_once('/web/serve/niusworks.com/resources/modules/header.php');
requirePermission('ADM',2,403);

?>
<link type='text/css' rel='stylesheet' href='/css/admin/permissions.css'></link>
<script type='text/javascript' src='/js/admin/permissions.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-12'>
<table id='htbl'><tbody>
<tr>
	<td colspan='2' class='center'>
		<button id='recache' class='btn btn-inverse yellow'>Reload Permissions From File</button>
		<button id='resession' class='btn btn-inverse lightBlue'>Refresh My Permissions</button>
	</td>
</tr><tr>
	<td>
		Currently Selected User:&emsp;<span id='selected'><?=$_SESSION['user']['uid']?>:<?=$_SESSION['user']['uname']?> (<?=$_SESSION['user']['pname']?>)</span>
	</td><td class='right'>
		<button class='btn btn-inverse'>Change User</button>
	</td>
</tr>
</tbody></table>
</div>
</div> <?php /* End Row 0 */ ?>

<?php /* Row 1 */ ?>
<div class='row'>
<div class='col-xs-12'>
<table id='ctbl'><tbody>
<tr><th>Index</th><th>Alias</th><th>Role</th><th colspan='2'>Current</th></tr>
<?php
for($i = 0; $i < count($PERMS); $i ++)
{
	$lvl = getPrivilegeLevel($i);
	?><tr><?php
	$perm = $PERMS[$i];
	?><td><?=$i?></td>
	<td><?=$perm['@attributes']['alias']?></td>
	<td><?=$perm['summary']?></td>
	<td><?=$lvl?> - <?=$perm['level'][$lvl]['@attributes']['role']?></td>
	</tr><?php
}

?>
</tbody></table>
</div>
</div> <?php /* End Row 1 */ ?>

</div> <?php /* End container */ ?>

</body>
</html>
