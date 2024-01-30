<?php
require_once('/web/serve/niusworks.com/cgi/session/session.php');
?>
<link type='text/css' rel='stylesheet' href='/css/modules/navbar.css'></link>
<nav class='navbar navbar-inverse' role='navigation'>
	<div class='navbar-header'>
		<button type='button' id='brand' class='navbar-toggle' data-toggle='collapse' data-target='#navbar-collapsed'>
			<span class='icon-bar'></span>
			<span class='icon-bar'></span>
			<span class='icon-bar'></span>
		</button>
		<a id='brand' class='navbar-brand dropdown-toggle' data-toggle='dropdown'>
			<?php $imgp = '/resources/images/avatars/'.$_SESSION['user']['uid'].'.png'; ?>
			<img id='brandHead' src='<?=(file_exists('/web/serve/niusworks.com'.$imgp)?$imgp:'/resources/images/Undefined.png')?>'></img>
			<?=explode(' ',$_SESSION['user']['pname'])[0]?>
		</a>
		<ul class='dropdown-menu'>
			<li><a href='/ui/account/tos.php'><span class='glyphicon glyphicon-list-alt'></span>Terms of Service</a></li>
			<?php if(isLoggedIn()){ ?>
			<li class='divider' />
			<li><a href='/ui/account/settings.php'><span class='glyphicon glyphicon-cog'></span>Settings</a></li>
			<?php } ?>
			<li class='divider' />
			<?php if(!isLoggedIn()) {?>
			<li><a href='/ui/account/login.php'><span class='glyphicon glyphicon-log-in'></span>Log In</a></li>
			<li><a href='/ui/account/create.php'><span class='glyphicon glyphicon-edit'></span>Create an Account</a></li>
			<?php } else { ?>
			<li><a href='/ui/account/logout.php'><span class='glyphicon glyphicon-log-out'></span>Log Out</a></li>
			<?php }?>
			<li class='divider' />
			<li><a href='/ui/about.php'><span class='glyphicon glyphicon-info-sign'></span>About This Site</a></li>
		</ul>
	</div>

	<div class='collapse navbar-collapse' id='navbar-collapsed'>
		<ul class='nav navbar-nav'>
			<li id='nliH'><a href='/ui/home.php'>Home</a></li>
			<?php if(hasPermission(['ACH'])){ ?>
			<li id='nliC' class='dropdown'>
				<a class='dropdown-toggle' data-toggle='dropdown'>Achievements <b class='caret'></b></a>
				<ul class='dropdown-menu'>
					<li><a href='/ui/services/ACH/home.php'>Main</a></li>
					<li><a href='/ui/services/ACH/faq.php'>FAQ</a></li>
					<?php if(hasPermission(['ACH'],2)){ ?>
					<li><a href='/ui/services/ACH/create.php'>Create...</a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			<li id='nliO' class='dropdown'>
				<a class='dropdown-toggle' data-toggle='dropdown'>Nius' Stuff <b class='caret'></b></a>
				<ul class='dropdown-menu'>
					<li><a href='/ui/nius/activity.php'>Blog</a></li>
					<li><a href='/ui/nius/art.php'>Artwork</a></li>
					<?php if(hasPermission(['ADM'],2)){ ?>
					<li><a href='/ui/nius/portfolio/main.php'>Portfolio</a></li>
					<?php } ?>
				</ul>
			</li>
			<?php if(hasPermission(['SFD','HMP','FPJ'],1,'OR')){ ?>
			<li id='nliS' class='dropdown'>
				<a class='dropdown-toggle' data-toggle='dropdown'>Services <b class='caret'></b></a>
				<ul class='dropdown-menu'>
					<?php if(hasPermission(['SFD'])){ ?>
					<li><a href='/ui/services/SFD/filedump.php'>File Dump</a></li>
					<?php } ?>
					<?php if(hasPermission(['HMP'])){ ?>
					<li><a href='/ui/services/HMP/home.php'>Homepage</a></li>
					<?php } ?>
					<?php if(hasPermission(['FPJ'])){ ?>
					<li><a href='/ui/services/FPJ/main.php'>Financial Projector</a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
<?php //IF ADMIN AUTHORIZED... ?>
<?php if(hasPermission('ADM')){ ?>
			<li id='nliA'><a href='/ui/admin/main.php'>Administrate</a></li>
<?php } /* end ADM conditional */ ?>
		<ul>
	</div>
</nav>
