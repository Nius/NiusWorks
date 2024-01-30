<?php
$PAGE_TITLE = 'Financial Projection';
?>

<?php

require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');
require_once('/web/serve/niusworks.com/resources/modules/header.php');
requirePermission('FPJ');

require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
require_once('/web/serve/niusworks.com/resources/libraries/FPJ-config.php');

?>
<link type='text/css' rel='stylesheet' href='/css/services/FPJ/main.css'></link>
<script type='text/javascript' src='/js/services/FPJ/main.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

<?php
$query = "SELECT COUNT(ID) AS QTY FROM $FPJ_STAGE_TABLE";
$count = number_format($DB_LINK->query($query)->result_r()[0]['QTY']);
?>

<table id='navTable'><tbody>
	<tr>
		<td><a href='/ui/services/FPJ/upload.php'><button class='btn btn-inverse' id='uploadB'><span class='glyphicon glyphicon-cloud-upload'></span> Upload Transactions</button></a></td>
		<td><a href='/ui/services/FPJ/accounts.php'><button class='btn btn-inverse' id='accountsB'><span class='glyphicon glyphicon-th-list'></span> Accounts</button></a></td>
		<td><a href='/ui/services/FPJ/review.php'><button class='btn btn-inverse' id='reviewB'><span class='glyphicon glyphicon-search'></span> Review (<?=$count ?>)</button></a></td>
		<td><a href='/ui/services/FPJ/reviewClasses.php'><button class='btn btn-inverse' id='modifyB'><span class='glyphicon glyphicon-edit'></span> Review Classes</button></a></td>
	</tr>
</tbody></table>

</div>
</div> <?php /* End Row 0 */ ?>

</div> <?php /* End container */ ?>

</body>
</html>
