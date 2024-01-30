<?php /* JAVASCRIPTS */ ?>
<script type='text/javascript' src='/resources/libraries/jquery.js'></script>
<script type='text/javascript' src='/resources/libraries/BootStrap/js/bootstrap.min.js'></script>
<script type='text/javascript' src='/resources/libraries/jquery-plugins/jquery.color-2.1.2.min.js'></script>
<script type='text/javascript' src='/resources/libraries/jquery-ui/jquery-ui.min.js'></script>

<?php /* STYLESHEETS */ ?>
<link rel='stylesheet' type='text/css' href='/resources/libraries/BootStrap/css/bootstrap.min.css'></link>
<link rel='stylesheet' type='text/css' href='/resources/libraries/BootStrap/css/bootstrap-theme.min.css'></link>

<?php /* SQL */ ?>
<?php include_once('/web/serve/niusworks.com/resources/libraries/sql.php');?>

<?php /*    OTHER    */ ?>
<?php $PAGE_ICON = (isset($PAGE_ICON) ? $PAGE_ICON : '/resources/images/banner.png'); ?>
<link rel='shortcut-icon' href='<?=$PAGE_ICON?>'></link>
<link rel='icon' href='<?=$PAGE_ICON?>'></link>
