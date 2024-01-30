<?php
$PAGE_TITLE = 'Nius\'s Blog Post';
?>

<?php

require_once('/web/serve/niusworks.com/resources/modules/header.php');
require_once('/web/serve/niusworks.com/resources/libraries/sql.php');
$id = $_REQUEST['post'] + 0;
if($id < 0)
	requirePermission(-1);
$result = $DB_LINK->query('SELECT date, content, flags FROM NAX_Posts WHERE id = '.$id);
$meta = $result->result_r()[0];
$title = $meta['content'];
$date = $meta['date'];
$flags = $meta['flags'] + 0;

if($flags > 1)
	requirePermission(-1,1,403);

$path = '/web/serve/niusworks.com/resources/files/nius/longPosts/'.$id;
if(file_exists($path.'.html'))
	$path .= '.html';
elseif(file_exists($path.'.php'))
	$path .= '.php';

#$file = fopen($path,'r') or requirePermission(-1);
#$contents = fread($file,filesize($path));
#fclose($file);

?>
<link type='text/css' rel='stylesheet' href='/css/nius/viewPost.css'></link>
<script type='text/javascript' src='/js/nius/viewPost.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>
	<p class='h3'><?=$title?></p>
	<p id='date'><?=$date?></p>
	<div id='content'>
		<?php require_once($path); ?>
	</div>
</div>
</div> <?php /* End Row 0 */ ?>

<div class='row'>
<div class='col-xs-12'>
	<center><br />&copy; Copyright 2019</center>
</div>
</div>

</div> <?php /* End container */ ?>

</body>
</html>
