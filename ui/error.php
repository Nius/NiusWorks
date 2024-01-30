<?php
$PAGE_TITLE = 'NW Error';
?>

<?php

require_once('/web/serve/niusworks.com/resources/modules/header.php');
//requirePermission('ADM',2,403);

$blurb = array(
404 =>
'The page you requested does not exist. If this is the result of a broken link, please let us know.',
403 =>
'The page you requested is private.',
500 =>
'The server screwed up somehow. Retrying probably won\'t help; the best thing to do would be to let us know there\'s a problem.'
);

?>
<?php /* <link type='text/css' rel='stylesheet' href='/css/home.css'></link> */ ?>
<?php /* <script type='text/javascript' src='/js/home.js'></script> */ ?>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

<h1><?=http_response_code()?></h1>
<?=$blurb[http_response_code()]?>
<br>
<a href='/ui/home.php'>Go Home</a>

</div>
</div> <?php /* End Row 0 */ ?>

</div> <?php /* End container */ ?>

</body>
</html>
