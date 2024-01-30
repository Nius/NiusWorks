$(document).ready(function()
{
	var un = [
		'Nius',		'CommanderSpork',	'QueenElizabeth',
		'B_Obama',	'ZaftigDobok',		'Dobok',
		'PriscillaDeMimsyPorpington',		'Genevieve',
		'HerpDerp',	'McDerpalds',		'BillyMays',
		'Sasquatch',	'Garlic',		'Willy_Wonka',
		'Every_Single_Smurf',			'Canada',
		'CaptainBenjaminSocko',			'Herobrine',
		'JimmerMcJimmerton',			'JoeJoeThePropPlane',
		'Skevin',	'Clyde',		'T_Hanks',
		'D_Morgan',	'DreadPirateRibbons'
		];

	var pw = [
		'CorrectHorseBatteryStaple',		'password123',
		'VeritableEquineElectricalBindings',	'FortyTwo',
		'IFoundNarnia',	'ILoveSpinach',		'Ten-Four',
		'BroDoYouEvenPassword',			'WinSauce',
		'ILikeTeddyBears',			'Horseradish',
		'LolImNotGivingYouMyPassword',		'112358'
		];

	//$('#username').attr('placeholder',un[Math.floor(Math.random()*un.length)]);
	//$('#password').attr('placeholder',pw[Math.floor(Math.random()*un.length)]);
	type($('#username'),un[Math.floor(Math.random()*un.length)]);
	type($('#password'),pw[Math.floor(Math.random()*pw.length)]);

	$(document).keyup(function(event)
	{
		if(event.keyCode == 13) // Enter Key
			$('#loginB').click();
	});

	$('#loginB').click(function()
	{
		var un = $('#username').val();
		var pw = $('#password').val();

		if(un.length < 4 || un.length > 15 || !/^\w{0,}$/.test(un))
			return error('The username you provided is invalid.','red');
		if(pw.length < 1)
			return error('Please enter your password.','red');

		error('Logging in...','yellow');

		$.post('/cgi/session/login.php',{un:un,pw:pw}).done(function(data)
		{
			switch(data)
			{
			case '0':
				error('Login failed. Please check your information and try again.','red');
				break;
			case '1':
				error('Success! Redirecting...','lime');
				window.setTimeout(function(){
					window.location = '/ui/home.php';
				},500);
				break;
			default:
				error('Something has gone drastically wrong. Please contact a site administrator.<br>(Response code: ' + data + ')','red');
			}
		}).fail(function(data)
		{
			error('A communication error occurred. Please try again.','red');
		});
	});

});

function error(message,color)
{
	$('#errP').html(message);
	$('#errP').css('color',color);
	return false;
}

function type(el,text)
{
	if(typeof $(el).attr('placeholder') != 'undefined')
		var len = $(el).attr('placeholder').length;
	else
		var len = 0;
	if(len == text.length)
		return;
	$(el).attr('placeholder',text.substring(0,len + 1));
	var rand = Math.floor(Math.random() * 200) + 50;
	window.setTimeout(function(){type(el,text);},rand);
}
