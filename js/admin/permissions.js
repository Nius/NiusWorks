$(document).ready(function()
{

	$('#recache').click(function()
	{
		$.post('/cgi/permissions/permissions.php',{fn:'recache'}).done(function(data)
		{
			switch(data){
			case '0':
				$('#recache').css('background-color','#FF0000');
				break;
			case '1':
				$('#recache').css('background-color','#00FF00');
				window.setTimeout(function(){location.reload();},500);
				break;
			}
			$('#recache').animate({'background-color':'#222222'},'slow');
		}).fail(function(data)
		{
			$('#recache').css('background-color','#FF0000');
			$('#recache').animate({'background-color':'#222222'},'slow');
		});
	});
	$('#resession').click(function()
	{
		$.post('/cgi/permissions/permissions.php',{fn:'resession'}).done(function(data)
		{
			switch(data){
			case '0':
				$('#resession').css('background-color','#FF0000');
				break;
			case '1':
				$('#resession').css('background-color','#00FF00');
				window.setTimeout(function(){location.reload();},500);
				break;
			}
			$('#resession').animate({'background-color':'#222222'},'slow');
		}).fail(function(data)
		{
			$('#resession').css('background-color','#FF0000');
			$('#resession').animate({'background-color':'#222222'},'slow');
		});
	});

	$('#nliA').addClass('active');
});
