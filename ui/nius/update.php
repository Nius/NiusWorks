<?php
$PAGE_TITLE = 'NW Update';
?>

<?php

require_once('/web/serve/niusworks.com/resources/modules/header.php');
require_once('/web/serve/niusworks.com/resources/libraries/sql.php');

if($_SESSION['user']['uid'] != 1000)
	requirePermission(-1);

?>
<link type='text/css' rel='stylesheet' href='/css/nius/update.css'></link>
<link type='text/css' rel='stylesheet' href='/css/nius/viewPost.css'></link>
<script type='text/javascript' src='/js/nius/update.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-12'>
<ul class='nav nav-tabs' role='tablist'>
	<li role='presentation' class=''><a href='#shortPost' role='tab' data-toggle='tab'>Short Post</a></li>
	<li role='presentation' class='active'><a href='#longPost' role='tab' data-toggle='tab'>Long Post</a></li>
</ul>
<div class='tab-content'>
<div role='tabpanel' class='tab-pane' id='shortPost'>
<?php /* SHORT POST */ ?>
	<textarea id='spi' class='form-control html-assist' placeholder=':' rows='5'></textarea>
	<p id='spDateP' class='repel-top'>&nbsp;</p>
	<div id='sp_echo' class='stretch'>&nbsp;</div>
	<div class='right stretch repel-top'><span class='repel-right' id='sp_count'>0 / 512</span><button id='sp_submitB' class='btn btn-inverse'>Submit</button></div>
</div> <?php /* End Short Post */ ?>
<div role='tabpanel' class='tab-pane active' id='longPost'>
<?php /* LONG POST */ ?>
<table id='lp_articles' class='stretch'>
<thead><tr>
	<th>Title</th><th>Date</th><th colspan='2'></th>
</tr></thead><tbody>
<tr>
	<td class='italic' colspan='2'>New...</td>
	<td><span class='glyphicon glyphicon-edit'></span></td>
	<td></td>
</tr>
<?php
	$result = $DB_LINK->query('SELECT * FROM NAX_Posts WHERE Flags >= 1 AND Flags <= 2 ORDER BY Date DESC');
	$articles = $result->result_r();
	foreach($articles as $article){$draft = $article['Flags'] == '2';?>
	<tr <?=($draft ? 'class=\'draft\'':'')?> articleID='<?=$article['ID']?>'>
		<td><?=$article['Content']?></td>
		<td><?=$article['Date']?></td>
		<?php if($draft){ ?>
		<td><span class='glyphicon glyphicon-edit'></span></td>
		<td><span class='glyphicon glyphicon-trash'></span></td>
		<?php } else { ?>
		<td colspan='2'></td>
		<?php } ?>
	</tr>
	<?php }
?>
</tbody></table>
<div id='lp_edit' hidden>
	<h3 id='lp_title'>Loading...</h3>
	<textarea id='lpi' class='form-control html-assist' placeholder=':' rows='5'></textarea>
	<p id='lpDateP' class='repel-top'>&nbsp;</p>
	<div id='lp_echo' class='stretch'>&nbsp;</div>
	<div class='right stretch repel-top'>
		<span class='repel-right dark'>[unlimited]</span>
		<button id='lp_draftB' class='btn btn-inverse'>Update Draft</button>
		<button id='lp_submitB' class='btn btn-warning'>Publish</button>
	</div>
</div>
</div> <?php /* End Long Post */ ?>
</div> <?php /* End tab-content */ ?>
</div>
</div> <?php /* End Row 0 */ ?>

</div> <?php /* End container */ ?>

<div class='modal fade' id='lp_naM' tabindex='-1' role='dialog'>
 <div class='modal-dialog'>
  <div class='modal-content'>
   <div class='modal-header'>
    <button type='button' class='close' data-dismiss='modal'>&times;<span class='sr-only'>Close</span></button>
    <h4 class='modal-title'>New Article</h4>
   </div>
   <div class='modal-body'>
    <div class='form-group'>
     <label class='control-label' for='lp_naM_title'>Title</label>
     <div class='input-group'>
      <span class='input-group-addon'><span class='glyphicon glyphicon-edit'></span></span>
      <input type='text' class='form-control' id='lp_naM_title' />
     </div>
    </div>
    <p id='naM_errP' class='bold'>&nbsp;</p>
   </div>
   <div class='modal-footer'>
    <button type='button' class='btn btn-success' id='naM_executeB'>Create</button>
    <button type='button' class='btn btn-danger' id='naM_cancelB' data-dismiss='modal'>Cancel</button>
   </div>
  </div>
 </div>
</div>

</body>
</html>
