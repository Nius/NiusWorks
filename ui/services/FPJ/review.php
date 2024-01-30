<?php
$PAGE_TITLE = 'Fin-Projection Reviewer';
?>

<?php

require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');
require_once('/web/serve/niusworks.com/resources/modules/header.php');
require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
requirePermission('FPJ');

require_once('/web/serve/niusworks.com/resources/libraries/FPJ-config.php');

?>
<link type='text/css' rel='stylesheet' href='/css/services/FPJ/review.css'></link>
<script type='text/javascript' src='/js/services/FPJ/review.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

<table id='opts-tbl'><tbody><tr>
	<td>
		<input type='checkbox' id='showCatsCB' checked />
		<label for='showCatsCB'>Show Transactions Without Categories</label>
	</td><td>
		<input type='checkbox' id='showMerchCB' checked />
		<label for='showMerchCB'>Show Transactions Without Merchants</label>
	</td>
</tr></tbody></table>

<table id='trxs-tbl'>
	<thead>
		<tr>
			<th class='date-td'>Date</th>
			<th class='account-td'>Account</th>
			<th class='amount-td'>Amount</th>
			<th class='descr-td'>Description</th>
		</tr>
	</thead>
		<?php
$query = "SELECT ID, ACCOUNT, TRXDATE, VALUE, DESCRIPTION FROM $FPJ_STAGE_TABLE";
$result = $DB_LINK->query($query);
$trxs = $result->result_r();

if(count($trxs) < 1)
	echo 'No transactions to show.';

else
{

$accounts = FPJ_get_account_names();

FPJ_attempt_class_matching($trxs);

function FPJ_display_classes($trx, $key, $glyph)
{
	$matched = array_key_exists($key,$trx);
	$rowspan = $matched ? count($trx[$key]) : 1;
	
	echo "<tr><td class='category-td ".($matched ? "matched" : "")."' rowspan=$rowspan>";
	echo "<span class='glyphicon glyphicon-$glyph'></span>&nbsp;";
	if(!$matched)
		echo "No matches found.</td></tr>";
	else
		foreach($trx[$key] as $class)
			echo $class['NAME']."</td></tr><tr><td class='category-td matched'>";
	echo "</td></tr>";
}

foreach($trxs as $trx)
{
	if($trx['ID'] == 4 || $trx['ID'] == 36)
	error_log(print_r($trx,true));
	echo '<tbody><tr>'.
	  '<td class="date-td" rowspan=0>'.$trx['TRXDATE'].'</td>'.
	  '<td class="account-td" rowspan=0>'.$accounts[$trx['ACCOUNT']].'</td>'.
	  '<td class="amount-td" rowspan=0>'.$trx['VALUE'].'</td>'.
	  '<td class="descr-td">'.$trx['DESCRIPTION'].'</td>';
	?>
	<td class="controls-td" rowspan=0>
		<div class='dropdown'>
			<button class='btn btn-inverse dropdown-toggle' data-toggle='dropdown'>
				<span class='glyphicon glyphicon-edit'></span>
			</button>
			<ul class='dropdown-menu'>
				<li><a href='/ui/services/FPJ/newClass.php?trigger=<?=$trx['ID']?>&mode=Category&source=stage'>
					<span class='glyphicon glyphicon-list-alt'></span> Categorize Like This...
				</a></li>
				<li><a href='/ui/services/FPJ/newClass.php?trigger=<?=$trx['ID']?>&mode=Merchant&source=stage'>
					<span class='glyphicon glyphicon-shopping-cart'></span> Create Merchant...
				</a></li>
				<li><a href='/ui/services/FPJ/newClass.php?trigger=<?=$trx['ID']?>&mode=Tracker&source=stage'>
					<span class='glyphicon glyphicon-briefcase'></span> Track Like This...
				</a></li>
			</ul>
		</div>
	</td>

	</tr>
	<?php

	FPJ_display_classes($trx,'_CATEGORIES','list-alt');
	FPJ_display_classes($trx,'_MERCHANTS','user');
	FPJ_display_classes($trx,'_TRACKERS','star');

	echo '</tbody>';
}
		}?>
</table>

</div>
</div> <?php /* End Row 0 */ ?>

</div> <?php /* End container */ ?>

</body>
</html>
