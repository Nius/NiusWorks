$(document).ready(function()
{
	$('#submit').click(function()
	{
		var em = $('#email').val();
		var un = $('#username').val();
		var pw = $('#password').val();
		var nm = $('#name').val();

		// Validate
		
		if(un.length < 4 || un.length > 15)
			return error('Please enter a username at between 4 and 15 characters long.','red');
		if(!/^\w{0,}$/.test(un))
			return error('Please enter a username without any special characters.','red');
		if(!isValidEmail(em))
			return error('Please enter a valid email address.','red');
		if(em.length > 30)
			return error('Please enter an email address no longer than 30 characters.','red');
		if(pw.length < 5)
			return error('Please enter a password of at least 5 characters.','red');
		var res = testPassword(pw,un,em);
		if(res != null)
			return error(res,'red');
		if($('#xpassword').val() != pw)
			return error('Your passwords do not match.','red');
		if(!/^[a-zA-Z]+ [a-zA-Z]+$/.test(nm))
			return error('Please enter your first and last name, separated by a space.','red');
		if(!/^[a-zA-Z]{2,} [a-zA-Z]{2,}$/.test(nm))
			return error('Please ensure that both names are at least two characters long.','red');
		if(nm.length > 25)
			return error('Please enter a name no longer than 25 characters long.','red');

		// Submit

		error('Submitting...','yellow');

		$.post('/cgi/session/createAccount.php',{email:em,username:un,password:pw,name:nm}).done(function(data)
		{
			switch(data)
			{
			case '0':
				error('The server rejected the data recieved from this form. Please try again.','red');
				break;
			case '1':
				error('Success! Redirecting...','lime');
				window.setTimeout(function(){
					window.location = '/ui/account/login.php';
				},500);
				break;
			case '2':
				error('This username is already in use.','red');
				break;
			case '3':
				error('Something went wrong. Please try again.','red');
				break;
			case '4':
				error('This email is already registered with us.','red');
				break;
			case '5':
				error('NiusWorks is not currently accepting new accounts. Please contact our administration if you need access.','red');
				break;
			default:
				error('Something has gone drastically wrong. Please contact a site administrator.<br>(Response code: ' + data + ')','red');
			}
		}).fail(function(data)
		{
			error('A communication error occurred. Please try again.','red');
		});
		
	});

	$('#clearB').click(function()
	{
		$('input').val('');
	});

	//
	//	POPOVERS
	//

	$('#username').popover(
	{
		'title':	'Create a username.',
		'container':	'body',
		'html':		true,
		'placement':	'bottom',
		'trigger':	'focus',
		'content':	$('#unpc').html()
	});
	$('#email').popover(
	{
		'title':	'Tell us your email address.',
		'container':	'body',
		'html':		true,
		'placement':	'bottom',
		'trigger':	'focus',
		'content':	$('#empc').html()
	});
	$('#password').popover(
	{
		'title':	'Create a password.',
		'container':	'body',
		'html':		true,
		'placement':	'bottom',
		'trigger':	'focus',
		'content':	$('#pwpc').html()
	});
	$('#xpassword').popover(
	{
		'title':	'Confirm your password.',
		'container':	'body',
		'html':		true,
		'placement':	'bottom',
		'trigger':	'focus',
		'content':	$('#xppc').html()
	});
	$('#name').popover(
	{
		'title':	'Tell us your name.',
		'container':	'body',
		'html':		true,
		'placement':	'top',
		'trigger':	'focus',
		'content':	$('#pnpc').html()
	});

	//
	//	POPOVER FEEDBACK
	//

	$('#username').bind('change paste keyup',function()
	{
		if($('#username').val().length >= 4)
		{
			$('#unpc_char').css('color','#00FF00');
			$('#unpc_char .glyphicon').removeClass('glyphicon-remove');
			$('#unpc_char .glyphicon').addClass('glyphicon-ok');
		}
		else
		{
			$('#unpc_char').css('color','#FF0000');
			$('#unpc_char .glyphicon').addClass('glyphicon-remove');
			$('#unpc_char .glyphicon').removeClass('glyphicon-ok');
		}
		$('#username').data('bs.popover').options.content = $('#unpc').html();
		$('#username').data('bs.popover').tip().find('.popover-content').html($('#unpc').html());
	});
	$('#username').bind('change paste keyup',function()
	{
		if(/^\w{0,}$/.test($('#username').val()))
		{
			$('#unpc_reg').css('color','#00FF00');
			$('#unpc_reg .glyphicon').removeClass('glyphicon-remove');
			$('#unpc_reg .glyphicon').addClass('glyphicon-ok');
		}
		else
		{
			$('#unpc_reg').css('color','#FF0000');
			$('#unpc_reg .glyphicon').addClass('glyphicon-remove');
			$('#unpc_reg .glyphicon').removeClass('glyphicon-ok');
		}
		$('#username').data('bs.popover').options.content = $('#unpc').html();
		$('#username').data('bs.popover').tip().find('.popover-content').html($('#unpc').html());
	});
	$('#username').bind('change paste keyup',function()
	{
		if($('#username').val().length <= 15)
		{
			$('#unpc_max').css('color','#00FF00');
			$('#unpc_max .glyphicon').removeClass('glyphicon-remove');
			$('#unpc_max .glyphicon').addClass('glyphicon-ok');
		}
		else
		{
			$('#unpc_max').css('color','#FF0000');
			$('#unpc_max .glyphicon').addClass('glyphicon-remove');
			$('#unpc_max .glyphicon').removeClass('glyphicon-ok');
		}
		$('#username').data('bs.popover').options.content = $('#unpc').html();
		$('#username').data('bs.popover').tip().find('.popover-content').html($('#unpc').html());
	});
	$('#email').bind('change paste keyup',function()
	{
		if(isValidEmail($('#email').val()))
		{
			$('#empc_vem').css('color','#00FF00');
			$('#empc_vem .glyphicon').removeClass('glyphicon-remove');
			$('#empc_vem .glyphicon').addClass('glyphicon-ok');
		}
		else
		{
			$('#empc_vem').css('color','#FF0000');
			$('#empc_vem .glyphicon').addClass('glyphicon-remove');
			$('#empc_vem .glyphicon').removeClass('glyphicon-ok');
		}
		$('#email').data('bs.popover').options.content = $('#empc').html();
		$('#email').data('bs.popover').tip().find('.popover-content').html($('#empc').html());
	});
	$('#email').bind('change paste keyup',function()
	{
		if($('#email').val().length <= 30)
		{
			$('#empc_max').css('color','#00FF00');
			$('#empc_max .glyphicon').removeClass('glyphicon-remove');
			$('#empc_max .glyphicon').addClass('glyphicon-ok');
		}
		else
		{
			$('#empc_max').css('color','#FF0000');
			$('#empc_max .glyphicon').addClass('glyphicon-remove');
			$('#empc_max .glyphicon').removeClass('glyphicon-ok');
		}
		$('#email').data('bs.popover').options.content = $('#empc').html();
		$('#email').data('bs.popover').tip().find('.popover-content').html($('#empc').html());
	});
	$('#password').bind('change paste keyup',function()
	{
		if($('#password').val().length >= 5)
		{
			$('#pwpc_char').css('color','#00FF00');
			$('#pwpc_char .glyphicon').removeClass('glyphicon-remove');
			$('#pwpc_char .glyphicon').addClass('glyphicon-ok');
		}
		else
		{
			$('#pwpc_char').css('color','#FF0000');
			$('#pwpc_char .glyphicon').addClass('glyphicon-remove');
			$('#pwpc_char .glyphicon').removeClass('glyphicon-ok');
		}
		$('#password').data('bs.popover').options.content = $('#pwpc').html();
		$('#password').data('bs.popover').tip().find('.popover-content').html($('#pwpc').html());
		$('#xpassword').change();
	});
	$('#xpassword').bind('change paste keyup',function()
	{
		if($('#xpassword').val() == $('#password').val())
		{
			$('#xppc_match').css('color','#00FF00');
			$('#xppc_match .glyphicon').removeClass('glyphicon-remove');
			$('#xppc_match .glyphicon').addClass('glyphicon-ok');
		}
		else
		{
			$('#xppc_match').css('color','#FF0000');
			$('#xppc_match .glyphicon').addClass('glyphicon-remove');
			$('#xppc_match .glyphicon').removeClass('glyphicon-ok');
		}
		$('#xpassword').data('bs.popover').options.content = $('#xppc').html();
		$('#xpassword').data('bs.popover').tip().find('.popover-content').html($('#xppc').html());
	});
	$('#name').bind('change paste keyup',function()
	{
		if(/^[a-zA-Z]+ [a-zA-Z]+$/.test($('#name').val()))
		{
			$('#pnpc_token').css('color','#00FF00');
			$('#pnpc_token .glyphicon').removeClass('glyphicon-remove');
			$('#pnpc_token .glyphicon').addClass('glyphicon-ok');
		}
		else
		{
			$('#pnpc_token').css('color','#FF0000');
			$('#pnpc_token .glyphicon').addClass('glyphicon-remove');
			$('#pnpc_token .glyphicon').removeClass('glyphicon-ok');
		}
		$('#name').data('bs.popover').options.content = $('#pnpc').html();
		$('#name').data('bs.popover').tip().find('.popover-content').html($('#pnpc').html());
	});
	$('#name').bind('change paste keyup',function()
	{
		if(/^[a-zA-Z]{2,} [a-zA-Z]{2,}$/.test($('#name').val()))
		{
			$('#pnpc_char').css('color','#00FF00');
			$('#pnpc_char .glyphicon').removeClass('glyphicon-remove');
			$('#pnpc_char .glyphicon').addClass('glyphicon-ok');
		}
		else
		{
			$('#pnpc_char').css('color','#FF0000');
			$('#pnpc_char .glyphicon').addClass('glyphicon-remove');
			$('#pnpc_char .glyphicon').removeClass('glyphicon-ok');
		}
		$('#name').data('bs.popover').options.content = $('#pnpc').html();
		$('#name').data('bs.popover').tip().find('.popover-content').html($('#pnpc').html());
	});
	$('#name').bind('change paste keyup',function()
	{
		if($('#name').val().length <= 25)
		{
			$('#pnpc_max').css('color','#00FF00');
			$('#pnpc_max .glyphicon').removeClass('glyphicon-remove');
			$('#pnpc_max .glyphicon').addClass('glyphicon-ok');
		}
		else
		{
			$('#pnpc_max').css('color','#FF0000');
			$('#pnpc_max .glyphicon').addClass('glyphicon-remove');
			$('#pnpc_max .glyphicon').removeClass('glyphicon-ok');
		}
		$('#name').data('bs.popover').options.content = $('#pnpc').html();
		$('#name').data('bs.popover').tip().find('.popover-content').html($('#pnpc').html());
	});

	$('#tosbutton').click(function(){window.location = '/ui/account/tos.php';});
});

function testPassword(pw,un,em)
{
	if(pw.toUpperCase() == un.toUpperCase())
		return 'Your password should never be the same as your username.<br/>Ever.';

	if(pw.toUpperCase() == em.toUpperCase())
		return 'Your password should never be the same as your email address.<br/>Ever.';

	switch(pw.toUpperCase())
	{
		case '12345':
		case 'PASSWORD':
		case 'PASS123':
			return 'That is probably the first password anyone would guess. Please come up with something a little more creative.';
		case 'CORRECTHORSEBATTERYSTAPLE':
		case 'CORRECTHORSEBATTERYSTAPLER':
			return 'It looks like you missed the point of that comic... Please choose a different password.';
		case 'NIUS':
		case 'NIUSATREIDES':
		case 'NIUS_ATREIDES':
			return 'I\'m flattered, but that\'s not a good password. Please choose something different.';
		case 'SPICEBOYZ10196':
			return 'You\'re too creative to have to resort to using the example password. Please come up with something different.';
	}

	return null;
}

function error(message,color)
{
	$('#errP').html(message);
	$('#errP').css('color',color);
	return false;
}

function isValidEmail(addr)
{
	//Checks whether a string is a valid email address.
	//Called by validate().
	
	//Only one at-sign
	if(addr.split("@").length != 2)
		return false;

	var domain = addr.split("@")[1];

	//Only one period
	if(domain.split("\.").length < 2)
		return false;
		
	var suffix = domain.split("\.")[domain.split("\.").length - 1];
	
	//Valid suffix
	switch(suffix)
	{
		case "com":
		case "net":
		case "org":
		case "gov":
		case "co":
			break;
		default:
			return false;
	}
	
	//Valid domain name
	if(/[^\-\d\w\.]/.test(domain))
		return false;
		
	//Valid username
	if(/[^\-\d\w\.]/.test(addr.split("@")[0]))
		return false;
	
	//First character is a letter
	if(/[^A-Za-z]/.test(addr.split("@")[0].split("")[0]))
		return false;
		
	return true;
}
