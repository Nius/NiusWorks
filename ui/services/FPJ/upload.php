<?php
$PAGE_TITLE = 'Fin-Projection Uploader';
?>

<?php

require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');
require_once('/web/serve/niusworks.com/resources/modules/header.php');
require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
requirePermission('FPJ');

require_once('/web/serve/niusworks.com/resources/libraries/FPJ-config.php');

?>
<link type='text/css' rel='stylesheet' href='/css/services/FPJ/upload.css'></link>
<script type='text/javascript' src='/js/services/FPJ/upload.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

<form id='uploadForm' method='post' enctype='multipart/form-data'>
	<select id='accountSel' class='form-control' name='account'>
		<?php

$query = 'SELECT * FROM FPJ_Accounts WHERE Owner = '.$_SESSION['user']['uid'];
$result = $DB_LINK->query($query);
$accounts = $result->result_r();
$accTypesPresent = Array();
foreach($accounts as $row)
{
	echo '<option accType="'.$row['Type'].'" value="'.$row['ID'].'">'.$row['Name'].'</option>';
	$accTypesPresent[$row['Type']] = true;
}

		?>
	</select>
	<?php

foreach($accTypesPresent as $typeKey => $_)
{
	echo '<select id="formatSelectForType'.$typeKey.'" class="form-control" name="format">';
	foreach($FPJ_ACCOUNT_TYPES[$typeKey]['formats'] as $format)
		echo '<option fileType="'.$format['filetype'].'" value="'.$format['value'].'">'.$format['name'].'</option>';

	echo '</select>';
}

	?>
	<input type='file' class='form-control' id='inputFile' accept='.txt' name='trxs'></input>
	<table><tbody><tr>
		<td><label id='inputFile-label' class='btn btn-inverse' for='inputFile'>Choose File...</label></td>
		<td><p id='errP'>&nbsp;</p></td>
	</tr></tbody></table>
</form>

</div>
</div> <?php /* End Row 0 */ ?>

</div> <?php /* End container */ ?>

</body>
</html>
