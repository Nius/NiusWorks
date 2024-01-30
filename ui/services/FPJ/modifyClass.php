<?php
$PAGE_TITLE = 'Fin-Projection Classifier';
?>

<?php

require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');
require_once('/web/serve/niusworks.com/resources/modules/header.php');
require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
requirePermission('FPJ');

// Verify that the necessary page args are present.
if(!isset($_REQUEST['referenceEntityID']) || !isset($_REQUEST['classType']) || !isset($_REQUEST['refTrxLocation']) || !isset($_REQUEST['action']))
	header("Location: http://niusworks.com/ui/services/FPJ/main.php");

$refEntID = $_REQUEST['referenceEntityID'] + 0;
$classType = $_REQUEST['classType'];
$action = $_REQUEST['action'];

// Verify that the action is valid
$modifying = strcmp($action,'modify') == 0;

// Verify that the specified class type is valid.
// Can be omitted if modifying an existing class.
if(strcmp($action,'modify') != 0)
	if(!in_array($classType,['Category','Merchant','Tracker']))
		header("Location: http://niusworks.com/ui/services/FPJ/main.php");


require_once('/web/serve/niusworks.com/resources/libraries/FPJ-config.php');

// Get the initial list of matching transactions:
// - If creating a class, it's any transaction whose description matches
//   that of the reference transaction.
// - If modifying a class, it's any transaction already matching the
//   reference class.

if(!$modifying)
{
	$refTrxLocation = strcmp($_REQUEST['refTrxLocation'],'stage') == 0 ? $FPJ_STAGE_TABLE : $FPJ_TRX_TABLE;
	$query = "SELECT * FROM $refTrxLocation WHERE ID = $refEntID";
	$trx = $DB_LINK->query($query)->result_r()[0];

	// Escape regex characters in the original trigger transaction description
	// so that things like parentheses and backslashes don't get interpreted
	// as regex operators.
	$pattern = preg_quote($trx['DESCRIPTION']);

	// Get the initial matches for the initial pattern

	$where = "WHERE DESCRIPTION REGEXP '".$DB_LINK->escape($pattern)."'";
	$query = "(SELECT * FROM $FPJ_STAGE_TABLE $where) ".
		 "UNION ALL ".
		 "(SELECT * FROM $FPJ_TRX_TABLE $where) ORDER BY TRXDATE";
	$matches = $DB_LINK->query($query)->result_r();
}
else // if($modifying)
{
	$query = "SELECT * FROM $FPJ_CLASSES_TABLE WHERE ID = $refEntID";
	$refClass = $DB_LINK->query($query)->result_r()[0];
	$pattern = $refClass['PATTERN'];
	$classType = FPJ_resolve_type_to_name($refClass['TYPE']);
	// 1 = Category, 2 = Merchant, 3 = Tracker

	// Get all subclasses of the reference class.
	$query = "SELECT * FROM $FPJ_SUBCLASS_TABLE WHERE PARENT = $refEntID";
	$refClassSubs = $DB_LINK->query($query)->result_r();

	$matches = FPJ_get_child_trxs($refEntID);
}

// Get all classes for the subcategorization tool.
// If modifying an existing class (rather than creating a new one)
// skip the existing class because it cannot be a subclass of itself.
//
// Skip this step entirely if the class type is a merchant; those cannot
// have subclasses.
if(strcmp($classType,'Merchant') != 0)
{
	$query = "SELECT * FROM $FPJ_CLASSES_TABLE".
		 ($modifying ? " WHERE ID != $refEntID" : '');
	$possibleSubclasses = $DB_LINK->query($query)->result_r();

	foreach($possibleSubclasses as $sub)
	{
		// Unindexed array for the autocomplete plugin
		$listOpts[] =
		[
			'label'=>
				$sub['NAME'].
				" <span class='dark-gray'>(".
				FPJ_resolve_type_to_name($sub['TYPE']).
				")</span>",
			'value'=>$sub['ID']
		];
		//$listOpts[] = ['label'=>$sub['NAME']." ($classType)", 'value'=>$sub['ID']];
		//$listOpts[] = '['.$sub['ID'].'] '.$sub['NAME'].' (/'.$sub['PATTERN'].'/)';

		// Indexed array for everything else
		$listOptsIndexed[$sub['ID']] = $sub;
	}
?>

<script>
	var listOpts = <?=json_encode($listOpts)?>;
	var listOptsIndexed = <?=json_encode($listOptsIndexed)?>;
</script>

<?php } ?>

<link type='text/css' rel='stylesheet' href='/css/services/FPJ/modifyClass.css'></link>
<script type='text/javascript' src='/resources/libraries/jquery-ui/autocomplete-HTML.js'></script>
<script type='text/javascript' src='/resources/libraries/jquery-ui/autocomplete-alias.js'></script>
<script type='text/javascript' src='/js/services/FPJ/modifyClass.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<?php if($modifying){ ?><div hidden id='modifying'><?=$refClass['ID']?></div><?php }?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

	<h3 id='modeLabelP'>Class Type</h3>
	<select class='form-control' id='modeSel' <?=($modifying ? 'disabled' : '')?>>
		<option value='C' <?=(strcmp($classType,'Category')==0 ? 'selected' : '')?>>Category</option>
		<option value='M' <?=(strcmp($classType,'Merchant')==0 ? 'selected' : '')?>>Merchant</option>
		<option value='T' <?=(strcmp($classType,'Tracker')==0 ? 'selected' : '')?>>Tracker</option>
	</select>

</div>
</div> <?php /* End Row 0 */ ?>

<?php /* Row 1 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

	<h3 id='nameLabelP'>Name</h3>
	<input type='text' id='className' class='form-control' placeholder='Enter <?=$classType?> name...' <?=($modifying ? 'disabled' : '')?> value='<?=($modifying ? $refClass['NAME'] : '')?>'></input>

</div>
</div> <?php /* End Row 1 */ ?>

<?php /* Row 2 */ ?>
<?php if(!$modifying){ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

	<h3 id='triggerHeadP'>Reference Transaction</h3>
	<p id='triggerBodyP'><span class='dark-gray'><?=$trx['TRXDATE'].' | '.$trx['VALUE'].' | </span>'.$trx['DESCRIPTION']?></p>

</div>
</div> <?php } /* End Row 2 */ ?>

<?php /* Row 3 */ ?>
<?php if(strcmp($classType,'Merchant') != 0){ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

	<h3 id='subsHeadP'>Sub-Classes</h3>
	<table id='addSubTbl'><tbody><tr>
		<td>
			Add Subclass:
		</td><td>
			<div class='ui-widget'>
				<input type='text' id='addSubTxt' class='form-control' placeholder='Enter class name...'></input>
			</div>
		</td><td>
			<button id='addSubB' class='btn btn-inverse'><span class='glyphicon glyphicon-plus'></span></button>
		</td>
	</tr></tbody></table>

	<div id='subClasses'>
		<div id='subClassTemplate' class='subClass' hidden>
			<table><tbody><tr>
				<td class='subClassName'></td>
				<td class='subClassX'><button class='close'>&times;</button></td>
			</tr></tbody></table>
		</div>
	</div>

	<script><?php

	foreach($refClassSubs as $sub)
		echo 'addSub('.$sub['CHILD'].');';

	?></script>

</div>
</div> <?php } /* End Row 3 */ ?>

<?php /* Row 4 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

	<h3 id='patternLabel'>Search Pattern</h3>
	<input id='patternT' type='textbox' class='form-control' value="<?=str_replace('"','&quot;',$pattern)?>"></input>
	<button id='requery' class='btn btn-inverse'>Re-Evaluate...</button>
	<button id='commit' target='<?=(strcmp($_REQUEST['refTrxLocation'],'stage')==0 ? 'review' : 'home')?>' class='btn btn-success pull-right'>Commit</button>

</div>
</div> <?php /* End Row 4 */ ?>

<?php /* Row 5 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

	<h3 id='matchesLabel'>Matching Transactions</h3>

	<table id='matchesT'>
	<thead>
		<tr>
			<th class='date-td'>Date</th>
			<th class='account-td'>Account</th>
			<th class='amount-td'>Amount</th>
			<th class='descr-td'>Description</th>
		</tr>
	</thead>
	<tbody>
		<?php
if(count($matches) < 1)
	echo '<tr><td colspan="4">No transactions to show.</td></tr>';
else
{

$query = "SELECT ID, Name FROM FPJ_Accounts WHERE Owner = ".$_SESSION['user']['uid'];
$result = $DB_LINK->query($query);
$accts_raw = $result->result_r();
$accounts = Array();
foreach($accts_raw as $act)
	$accounts[$act['ID']] = $act['Name'];

foreach($matches as $trx)
{
	echo '<tr>'.
	  '<td class="date-td">'.$trx['TRXDATE'].'</td>'.
	  '<td class="account-td">'.$accounts[$trx['ACCOUNT']].'</td>'.
	  '<td class="amount-td">'.$trx['VALUE'].'</td>'.
	  '<td class="descr-td">'.$trx['DESCRIPTION'].'</td>'.
	     '</tr>';
}
}?>

</div>
</div> <?php /* End Row 5 */ ?>

</div> <?php /* End container */ ?>

</body>
</html>
