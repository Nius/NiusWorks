<?php
$PAGE_TITLE = 'Fin-Projection Accounts';
?>

<?php

require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');
require_once('/web/serve/niusworks.com/resources/modules/header.php');
require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
requirePermission('FPJ');

require_once('/web/serve/niusworks.com/resources/libraries/FPJ-config.php');

?>
<link type='text/css' rel='stylesheet' href='/css/services/FPJ/accounts.css'></link>
<script type='text/javascript' src='/js/services/FPJ/accounts.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

<table id='headerT'><tbody><tr>
	<td><p id='titleP'>Current Accounts</p></td>
	<td><button id='showCreateB' class='btn btn-inverse'><span class='glyphicon glyphicon-plus'></span> Add Account</button></td>
</tr></tbody></table>

<table id='accountsT'><tbody>
<?php

$query = 'SELECT ID, Type, Name FROM FPJ_Accounts WHERE Owner = '.$_SESSION['user']['uid'];
$result = $DB_LINK->query($query);
$accounts = $result->result_r();

if(empty($accounts))
	echo '<tr><td>No accounts to show.</td></tr>';
else
	foreach($accounts as $account)
	{
		echo "<tr><td account_id='".$account['ID']."'>".$account['Name']."</td></tr>";
	}

?>
</tbody></table>

</div>
</div> <?php /* End Row 0 */ ?>

</div> <?php /* End container */ ?>

<div class='modal fade' id='newAccountM' tabindex='-1' role='dialog'>
 <div class='modal-dialog'>
  <div class='modal-content'>
   <div class='modal-header'>
    <button type='button' class='close' data-dismiss='modal'>&times;<span class='sr-only'>Close</span></button>
    <h4 class='modal-title'>Create Bank Account</h4>
   </div>
   <div class='modal-body'>
    <form id='ngForm' method='post' enctype='multipart/form-data'>
     <select id='accType' class='form-control' name='type'>
	<?php
	foreach($FPJ_ACCOUNT_TYPES as $index => $type)
		echo "<option value='$index'>".$type['name']."</option>";
	?>
     </select>
     <input type='text' class='form-control' id='accName' name='accName' placeholder='Account Name'></input>
    </form>
   </div>
   <div class='modal-footer'>
    <table id='mft'><tbody><tr>
     <td id='mferr'>&nbsp;</td>
     <td><button type='button' class='btn btn-success' id='accCreateB'>Create</button></td>
     <td><button type='button' class='btn btn-danger' id='accCancelB' data-dismiss='modal'>Cancel</button></td>
    </tr></tbody></table>
   </div>
  </div>
 </div>
</div>

</body>
</html>
