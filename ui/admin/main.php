<?php
$PAGE_TITLE = 'NW Admin';
?>

<?php

require_once('/web/serve/niusworks.com/resources/modules/header.php');
requirePermission('ADM',1,403);

?>
<link type='text/css' rel='stylesheet' href='/css/admin/main.css'></link>
<script type='text/javascript' src='/js/admin/main.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

<a href='/ui/admin/permissions.php'><span class='glyphicon glyphicon-list'></span> Permissions</a>

</div>
</div> <?php /* End Row 0 */ ?>

</div> <?php /* End container */ ?>

</body>
</html>
