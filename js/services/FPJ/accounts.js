$(document).ready(function()
{
	$('#showCreateB').click(function()
	{
		$('#newAccountM').modal('show');
	});

	$('#accCreateB').click(function()
	{
		$('#ngForm').submit();
	});

	$('#ngForm').submit(function(e)
	{
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			type:		'post',
			url:		'/cgi/post/FPJ/newAccount.php',
			processData:	false,
			contentType:	false,
			data:		formData,
			success: function(response)
			{
				switch(response)
				{
					case '0':
						return mferror('#FF0000','Invalid input.');
					case '1':
						window.setTimeout(function(){location.reload();},750);
						return mferror('#00FF00','Success! Refreshing...');
					case '2':
						return mferror('#FF0000','Database error.');
				}
			},
			error: function(response)
			{
				mferror('#FF0000','An unknown error occurred.');
			}
		});
	});
});

function mferror(color, message)
{
	$('#mferr').css('color',color);
	$('#mferr').html(message);
	return true;
}
