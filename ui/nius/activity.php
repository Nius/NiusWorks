<?php
$PAGE_TITLE = 'Nius\' Blog';
?>

<?php

require_once('/web/serve/niusworks.com/resources/modules/header.php');

$result = $DB_LINK->query('SELECT * FROM NAX_Posts WHERE flags <= 1 ORDER BY DATE DESC');
$entries = $result->result_r();

?>
<link type='text/css' rel='stylesheet' href='/css/nius/activity.css'></link>
<script type='text/javascript' src='/js/nius/activity.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1 repel-bottom'>

<p class='h3 lightBlue'>Nius' Blog</p>
<?php if($_SESSION['user']['uid'] == 1000){ ?>
	<a href='/ui/nius/update.php'>[update]</a>
<?php } ?>

</div>
</div> <?php /* End Row 0 */ ?>

<?php /* Row 1 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>
<?php
	foreach($entries as $entry)
	{
		if($entry['Flags'] == 1){?>
		<a class='lpLink' href='/ui/nius/viewPost.php?post=<?=$entry['ID']?>'><div class='stretch'><?php } ?>
		<p class='date'><?=$entry['Date']?></p>
		<p class='body'><?=$entry['Content']?></p>
	<?php if($entry['Flags'] == 1){?></div></a><?php }}
?>
</div>
</div> <?php /* End Row 1 */ ?>

</div> <?php /* End container */ ?>

</body>
</html>
