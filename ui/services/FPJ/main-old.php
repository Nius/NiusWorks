<?php
$PAGE_TITLE = 'Financial Projection';
?>

<?php

require_once('/web/serve/niusworks.com/cgi/permissions/permissions.php');
require_once('/web/serve/niusworks.com/resources/modules/header.php');
requirePermission('FPJ');

require_once('/web/serve/niusworks.com/cgi/query/finsheet.php');
$FPJ_CHARGES = fpj_getCharges();
if($FPJ_CHARGES === false)
	die('Error loading charges.');

$FPJ_INCOME = fpj_getIncome();
if($FPJ_INCOME === false)
	die('Error loading income.');

?>
<link type='text/css' rel='stylesheet' href='/css/services/FPJ/main.css'></link>
<script type='text/javascript' src='/js/services/FPJ/main.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1'>

<p id='tips'>Click on a charge or income to temporarily ignore it.</p>

<table id='refreshT'><tbody><tr>
<td><button class='btn btn-inverse' id='refreshB'>Refresh Income &amp; Expenses</button></td>
<td><span id='errP'></span></td>
</tr></tbody></table>

<table id='calendar'><tbody>
<tr>
	<th>Sunday</th>
	<th>Monday</th>
	<th>Tuesday</th>
	<th>Wednesday</th>
	<th>Thursday</th>
	<th>Friday</th>
	<th>Saturday</th>
</tr>
<tr>
<?php

$startval = file_get_contents('/web/backend-storage/finsheet-startval.txt');
$startval = $startval ? trim($startval) + 0 : '';

$timeCursor = time();
$today_num = date('w',$timeCursor);

$index = 0;
$RANGE = 180;

for(; $index < $today_num; $index ++)
	echo '<td></td>';

$index --; $timeCursor = strtotime('-1 day',$timeCursor);
$first = true;
for(; $RANGE > 0; $RANGE --)
{
	$changes = false;

	$index ++;
	if($index == 7)
	{
		echo '</tr><tr>';
		$index = 0;
	}
	$timeCursor = strtotime('+1 day',$timeCursor);

	echo '<td>';

	$today_num = date('d',$timeCursor);
	echo '<p class=\'mday\'>'.date('F',$timeCursor).' '.$today_num.'</p>';

	if($first)
	{
		echo '<input type=\'number\' id=\'startBal\' placeholder=\'Starting Balance\' value=\''.$startval.'\'></input>';
		echo '<p id=\'pmin\'></p>';
		echo '<p id=\'fneg\'></p>';
		echo '<p id=\'high\'></p>';
		$first = false;
	}

	foreach($FPJ_CHARGES as $charge)
		if($charge->due == $today_num)
		{
			echo '<table class=\'charge\'><tbody><tr><td class=\'left\'>'.$charge->label.'</td>';
			echo '<td class=\'right\'>'.$charge->amount.'</td></tr></tbody></table>';
			$changes = true;
		}

	foreach($FPJ_INCOME as $income)
		if(strcmp($income->due,'15 & 30') == 0 && ($today_num == 15 || $today_num == 30))
		{
			echo '<table class=\'income\'><tbody><tr><td class=\'left\'>'.$income->label.'</td>';
			echo '<td class=\'right\'>'.$income->amount.'</td></tr></tbody></table>';
			$changes = true;
		}
		elseif
		(
			($income->due == 'EOF' && date('w',$timeCursor) == 5) ||
			($income->due == 'EOW' && date('w',$timeCursor) == 3) ||
			($income->due == 'EOT' && date('w',$timeCursor) == 2)
		)
		{
			$i = $timeCursor;
			$weekcount = 0;
			$latest = strtotime($income->latest);
			while($i > $latest)
			{
				$weekcount ++;
				$i = strtotime('-1 week',$i);
			}
			if($weekcount % 2 == 1)
			{
				echo '<table class=\'income\'><tbody><tr><td class=\'left\'>'.$income->label.'</td>';
				echo '<td class=\'right\'>'.$income->amount.'</td></tr></tbody></table>';
				$changes = true;
			}
		}

	if($changes)
		echo '<p class=\'total\'></p>';
	
	echo '</td>';
}

?>

</tbody></table>

</div>
</div> <?php /* End Row 0 */ ?>

</div> <?php /* End container */ ?>

</body>
</html>
