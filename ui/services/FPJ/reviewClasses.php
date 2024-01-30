<?php
$PAGE_TITLE = 'Modify a Class';
?>

<?php

require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');
require_once('/web/serve/niusworks.com/resources/modules/header.php');
requirePermission('FPJ');

require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
require_once('/web/serve/niusworks.com/resources/libraries/FPJ-config.php');

$query = "SELECT * FROM $FPJ_CLASSES_TABLE";
$classes = $DB_LINK->query($query)->result_r();

?>

<link type='text/css' rel='stylesheet' href='/css/services/FPJ/reviewClasses.css'></link>
<script type='text/javascript' src='/js/services/FPJ/reviewClasses.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

<table id='classesT'>
<thead>
	<tr>
		<th></th>
		<th>Type</th>
		<th>Name</th>
		<th>Pattern</th>
	</tr>
</thead>
<tbody>
<?php

foreach($classes as $class)
{
	echo '<tr classID='.$class['ID'].'>';
	echo 
		'<td class="controlTD">'.
		'<a href="/ui/services/FPJ/modifyClass.php?'.
			'referenceEntityID='.$class['ID'].'&'.
			'classType=inherit&'.
			'refTrxLocation=nil&'.
			'action=modify">'.
		'<button class="btn btn-inverse"><span class="glyphicon glyphicon-edit"></span></button></a></td>';
	echo '<td class="typeTD">'.FPJ_resolve_type_to_name($class['TYPE']).'</td>';
	echo '<td class="nameTD">'.$class['NAME'].'</td>';
	echo '<td class="patternTD">'.$class['PATTERN'].'</td>';
	echo '</tr>';
}

?>
</tbody></table>

</div>
</div> <?php /* End Row 0 */ ?>

</div> <?php /* End container */ ?>

</body>
</html>
