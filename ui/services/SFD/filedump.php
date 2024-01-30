<?php
$PAGE_TITLE = 'NW FileDump';
?>

<?php

require_once('/web/serve/niusworks.com/resources/modules/header.php');
requirePermission('SFD');
require_once('/web/serve/niusworks.com/resources/libraries/filesize.php');

?>
<link type='text/css' rel='stylesheet' href='/css/services/SFD/filedump.css'></link>
<script type='text/javascript' src='/js/services/SFD/filedump.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-12'>

This service is skeletal and is therefore intended only for administrative use.
<br>It will likely be upgraded or replaced later.
<br>
<label class='btn btn-inverse repel-top' for='file-selector'>
	<input id='file-selector' type='file' style='display:none;'>
	Choose file...
</label>
<label class='btn btn-inverse repel-top' for='executeB'>
	<input id='executeB' type='button' style='display:none;'>
	Execute upload
</label>
<p id='errP' class='repel-top'>&nbsp;</p>
</div>
</div> <?php /* End Row 0 */ ?>

<?php /* Row 1 */ ?>
<div class='row'>
<div class='col-xs-12'>
<table id='filesT' class='stretch'><tbody>
<tr><th>FileName</th><th>Size</th><th>Date</th><th></th><th></th></tr>
<?php
$DIR = '/web/serve/niusworks.com/resources/files/SFD/'.$_SESSION['user']['uid'];
if(file_exists($DIR))
{
	$files = scandir($DIR);
	foreach($files as $file)
	{
		$fullPath = $DIR .'/'.$file;
		if(is_dir($fullPath))
			continue;
		?><tr>	<td><?=$file?></td>
			<td><?=human_filesize(filesize($fullPath))?></td>
			<td><?=date('F d Y H:i:s',filemtime($fullPath))?></td>
			<td><a href='download.php?filename=<?=$file?>'><span class='glyphicon glyphicon-download-alt'></span></a></td>
			<td><span class='glyphicon glyphicon-remove'></span></td>
		  </tr>
	<?php }
	if(count($files) == 2){ ?>
		<tr><td colspan=5><span class='italic'>No files to show.</span></td></tr>
	<?php }
}
?>
</div>
</div> <?php /* End Row 1 */ ?>

</div> <?php /* End container */ ?>

</body>
</html>
