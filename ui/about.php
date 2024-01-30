<?php
$PAGE_TITLE = 'About Niusworks';
?>

<?php

require_once('/web/serve/niusworks.com/resources/modules/header.php');
//requirePermission('ADM',2,403);

?>
<link type='text/css' rel='stylesheet' href='/css/about.css'></link>
<script type='text/javascript' src='/js/about.js'></script>
</head>

<body>
<?php require_once('/web/serve/niusworks.com/resources/modules/navbar.php'); ?>

<div class='container-fluid'>

<?php /* Row 0 */ ?>
<div class='row'>
<div class='col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3'>

	<div id='introD'>
Construction of NiusWorks.com has continued sporatically since its advent in autumn of 2012.
Since then the site has undergone several overhauls, each implementing new homemade and third-party libraries and using new backend schemas while offering differing features as necessary at the time.
<br>
The entire site is hand-written from scratch, except for the third-party libraries listed below and except for the software actually running the site (such as Apache and MySQL Server). The site's accounts and permissions system, blog management system, and stylesheets (where not provided by a library such as Bootstrap) are all custom-built from zero.
<br>
The site is currently on its 4th iteration.<?php /*  An expanded history of the site is available below. */ ?>
	</div>

	<div id='libD'>
		<h4>Libraries Currently In Use</h4>
		<ul>
			<li>A SQL-PHP library written by Cody Creager (<a href='https://github.com/rigel314'>Rigel314</a>) and Ethan Reesor (<a href='https://github.com/firelizzard18'>FireLizzard18</a>) for automating and reducing numerous SQL-related tasks.</li>
			<li><a href='http://jstree.com/'>JSTree</a> by Ivan Bozhanov, a library for implementing interactive hierarchal tree structures using JavaScript.</li>
			<li><a href='https://quilljs.com/'>Quill Editor</a> for implementing rich text editors using JavaScript.</li>
			<li><a href='https://jquery.org/'>JQuery</a> version 1.10.2, by the JQuery Foundation (now <a href='https://js.foundation/'>the JS Foundation</a>).</li>
			<li><a href='https://getbootstrap.com/docs/3.3/'>Bootstrap</a> version 3.0.3 by <a href='https://github.com/orgs/twbs/people'>the Bootstrap team</a>.</li>
			<li><a href='http://jeffreysambells.com/2012/10/25/human-readable-filesize-php'>A php function</a> by Jeffrey Sambells for formatting file sizes as human-readable, which is somewhat cumbersome with Linux and PHP.</li>
		</ul>
	</div>

<?php /*
	<div id='histD'>
		<h4>Iteration 1</h4>
NiusWorks first arose as a vehicle for Minecraft-related activities. An old computer in Steakumz's basment, lovingly named GlaDOS, hosted a simple vanilla Minecraft service for our small community of friends. During this time Nius was in school studying the basics of web development and used GlaDOS as a platform for experimenting with web design.
<br>
As time went on Nius experimented with rudimentary web integration with the Minecraft service. A crude web interface for the game chat was made available, and players could click a button on the site to reset the Minecraft service if necessary.
<br>
This iteration was farily basic, using no third-party libraries and having a somewhat elementary appearance.
		<h4>Iteration 2</h4>
Eventually GlaDOS was moved to Nius' basement (while remaining the property of Steakumz) and at about this time the software aboard was overhauled. Most notably this iteration added the use of JQuery and featured a user accounts system.
<br>
GlaDOS was called upon to host two different Minecraft serivces, one of them being the heavily-modded Feed the Beast variant, and also a Starbound service. The website offered basic services for users to upload screenshots and publicly log the coordinates of interesting locations in the game. The site was also able to retrieve Minecraft user skins from the Mojang servers using a third-party library and manipulate them such that the player's face could be presented on their profile.
		<h4>Iteration 3</h4>
At some point Nius became aware of Bootstrap, which is the primary framework for the site's page design today. Though many parts of the Bootstrap styling have been modified to fit the site's theme, much of the site's CSS regarding the navigation bar's styling and the basic layout of each page is owed to Bootstrap.
<br>
Nius restyled the entire site using Bootstrap and in so doing redesigned much of the site's basic structure. At this time GlaDOS' primary function was to host FTB Minecraft and, aside from the web interface for Dynmap, did not see much web use.
		<h4>Iteration 4</h4>
Nius and GlaDOS eventually relocated again and with the end of College came the end of the nearly constant use of the game services being hosted on the machine. Since game-related services were Nius' primary motivation for building a site and due to Nius becoming a full-time member of the workforce the site fell by the wayside and became essentially vacant.
<br>
Gradually Nius felt the need to build a site for himself, with the primary goal of hosting his own musical compositions and blog entries in a place where he would not be subject to somebody else's terms of use or copyright policy.
<br>
Nius scrapped the whole site and started from scratch. First he built the permissions system, which is a homemade PHP libarary for manipulating a user's privileges as stored in the database and comparing them to a permissions hierarchy stored in server memory using APCu. After this came a new user accounts system and some basic services just for Nius, such as a system for uploading files to the server without having to use SCP.
<br>
Eventually the site reached the state it embodies today, serving primarily as a practice field for improving in web design and as a dumping ground for Nius' thoughts and creations.
	</div>
*/ ?>

</div>
</div> <?php /* End Row 0 */ ?>

</div> <?php /* End container */ ?>

</body>
</html>
