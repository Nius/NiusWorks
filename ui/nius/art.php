<?php
$PAGE_TITLE = 'NW Artwork';
?>

<?php

require_once('/web/serve/niusworks.com/resources/modules/header.php');
//requirePermission('ADM',2,403);

?>
<link type='text/css' rel='stylesheet' href='/css/nius/art.css'></link>
<script type='text/javascript' src='/js/nius/art.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1 col-md-4 col-md-offset-4'>

I used to be a full-time night auditor at a Best Western hotel. One of my daily tasks was to assemble a large envelope full of reports, receipts, and signed documents to be reviewed by upper management and then sent to cold storage. This envelope, usually containing about 200 pages, was called the <i>audit packet</i>.
<br><br>
I now work at a Holiday Inn Express as a daytime front desk agent (FDA, or GSR for Guest Service Representative). In addition to my daytime duties I take turns with one other desk agent doing <i>relief audit</i>, which is where one of us works the overnight shift while our full-time auditor takes their weekend. This results in me doing about four audits per month.
<br><br>
The envelopes we use to prepare our audit packets start off blank. We are required to write "Night Audit", the date, and our initials on it. When I started doing relief audits here I resolved to do something creative with each packet and to do something different every time.
<br><br>
These are the results.

</div>
</div> <?php /* End Row 0 */ ?>

<div class='row'>
<div class='col-xs-12 center' id='pics-div'>

<?php
$DIR = '/web/serve/niusworks.com/resources/images/art';
if(file_exists($DIR))
{
	$files = scandir($DIR);
	foreach($files as $file)
	{
		$full_path = $DIR.'/'.$file;
		if(is_dir($full_path))
			continue;
		?>
		<div class='pic-card'>
		<img src='/resources/images/art/<?=$file?>' />
		</div>
		<?php
	}
}
?>

</div>
</div>

</div> <?php /* End container */ ?>

<div class='modal fade' id='viewer' tabindex='-1' role='dialog'>
 <div class='modal-dialog'>
  <div class='modal-content'>
   <div class='modal-header'>
    <button type='button' class='close' data-dismiss='modal'>&times;<span class='sr-only'>Close</span></button>
   </div>
   <div class='modal-body'>
    <img id='viewer_img' />
   </div>
  </div>
 </div>
</div>

</body>
</html>
