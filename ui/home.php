<?php
$PAGE_TITLE = 'NW Home';
?>

<?php
require_once('/web/serve/niusworks.com/resources/modules/header.php');

$result = $DB_LINK->query('SELECT * FROM NAX_Posts WHERE Flags <= 1 ORDER BY Date DESC LIMIT 1');
$latest = $result->result_r()[0];
?>
<link type='text/css' rel='stylesheet' href='/css/home.css'></link>
<link type='text/css' rel='stylesheet' href='/resources/fonts/minecraftfont.css'></link>
<script type='text/javascript' src='/js/home.js'></script>
</head>

<?php require_once('/web/serve/niusworks.com/resources/libraries/github-card/webcomponent/github-card.html'); ?>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
	<div id='statusD' class='col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-4 card'>
		<table id='s_ht'><tbody><tr>
			<td>
				<p class='hp'>Latest Blog Post</p>
			</td><td class='right'>
				<a href='/ui/nius/activity.php'>[ see all ]</a><?php if($_SESSION['user']['uid'] == 1000){ ?> <a href='/ui/nius/update.php'>[ update ]</a><?php } ?>
			</td>
		</tr></tbody></table>
		<?php if($latest['Flags'] == 1){?>
		<a class='lpLink' href='/ui/nius/viewPost.php?post=<?=$latest['ID']?>'><div class='stretch'><?php }?>
		<p id='statusDateP'><?=$latest['Date']?></p>
		<p id='statusP'><?php if($latest['Flags'] == 1){?><span class='glyphicon glyphicon-file'></span> <?php }?><?=$latest['Content']?></p>
		<?php if($latest['Flags'] == 1){?></div></a><?php }?>
	</div>
</div>

<?php /* Row 1 */ ?>
<div class='row'>
	<div id='hubD' class='col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-4 card center'>
		<p class='hp'>Connect</p>
		<table class='table table-condensed'><tbody>
			<tr>
				<th id='gmail' class='center cn_cell'><img hidden class='thi' src='/resources/images/icons/gmail.png'></img>Gmail</th>
				<th id='steam' class='center cn_cell'><img hidden class='thi' src='/resources/images/icons/Steam.png'></img>Steam</th>
				<th id='reddit' class='center cn_cell'><img class='thi' src='/resources/images/icons/reddit.png'></img>Reddit</th>
				<th id='minecraft' class='center cn_cell'><img hidden class='thi' src='/resources/images/icons/Minecraft.ico'></img>Minecraft</th>
				<?php if(hasPermission('NPH')){ ?>
				<th id='github' class='center cn_cell'><img class='thi' src='/resources/images/icons/github.png'></img>Github</th>
				<th id='facebook' class='center cn_cell'><img class='thi' src='/resources/images/icons/facebook.png'></img>Facebook</th>
				<?php } /* end NPH conditional */ ?>
			</tr>
			<tr><td id='ec_cell' colspan='5'>
			</td></tr>
		</tbody></table>
		<?php if(false){ ?>
		<div class='row'>
			<div id='cn_minecraft' class='col-xs-6'>
				<p class='hp'>Minecraft</p>
			</div>
		</div>
		<div class='row'>
			<div id='cn_github' class='col-xs-6'>
				<p class='hp'>GitHub</p>
				<github-card user='Nius'></github-card>
			</div>
			<div id='cn_facebook' class='col-xs-6'>
				<p class='hp'>Facebook</p>
			</div>
		</div>
		<?php } ?>
	</div>
</div><?php /* End row 1 */ ?>

<?php /* Row 2 */ ?>
<div class='row'>
	<div id='endorseD' class='col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-4 card'>
		<p class='hp'>Endorsement <span class='dark italic non-bold small'>(not affiliated with NiusWorks; see <a href='/ui/account/tos.php'>ToS</a>.)</span></p>
		<?php

$ends = glob('/web/serve/niusworks.com/resources/files/endorsements/*');
$ends_filtered = Array();
foreach($ends as $end)
	if(strcmp(substr($end,-5),'.html') === 0)
		$ends_filtered[] = $end;
$end = $ends_filtered[array_rand($ends_filtered)];
include($end);

		?>
	</div>
</div><?php /* End row 2 */ ?>

</div><?php /* End Container-Fluid */ ?>

</body>
<div id='ec_gmail' hidden>
	<table class='table table-condensed'><tbody><tr>
		<?php if(hasPermission('NPH')){ ?>
		<td><a href='mailto:nicholas.p.harrell@gmail.com'>Nicholas.P.Harrell@gmail.com</a><br><span class='italic'>General Purpose</span></td>
		<?php } /* end NPH conditional */ ?>
		<td><a href='mailto:listenernius@gmail.com'>ListenerNius@gmail.com</a><br><span class='italic'>Gaming &amp; Web Communications</td>
	</tr></tbody></table>
</div>
<div id='ec_steam' hidden>
	<?php /*<a href='http://steamcommunity.com/id/ListenerNius/'><img src="http://steamsignature.com/status/english/76561198047351851.png"/></a> */ ?>
	<a href='https://steamcommunity.com/id/ListenerNius'><img src='https://badges.steamprofile.com/profile/default/steam/76561198047351851.png' /></a>
</div>
<div id='ec_reddit' hidden>
	<table class='table table-condensed'><tbody><tr><td class='right'>
		<img id='redditAvatar' src='/resources/images/avatars/1000.png' />
	</td><td class='justify'>
		<a href='https://www.reddit.com/user/ListenerNius'>u/ListenerNius</a>
	</td></tr></tbody></table>
</div>
<div id='ec_minecraft' hidden>
	<table class='table table-condensed'><tbody><tr><td class='right'>
		<img hidden id='mchead' src='/cgi/query/mcPlayerSkin.php?playerName=SirNius&seek=head&size=42' />
		<img id='mcflag' src='/resources/images/MCBanner.png' />
	</td><td class='justify'>
		<span class='green mcf'>SirNius</span>
		<br><span class='mcf'>( Nius Atreides )</span>
	</td></tr></tbody></table>
</div>
<?php if(hasPermission('NPH')){ ?>
<div id='ec_github' hidden>
	<span class='italic'>Coming soon...</span>
</div>
<div id='ec_facebook' hidden>
	<a href='https://www.facebook.com/nick.harrell.56'>Nicholas Harrell on Facebook</a>
	<br><span class='italic'>More coming soon...</span>
</div>
<?php } /* end NPH conditional */ ?>
</html>
