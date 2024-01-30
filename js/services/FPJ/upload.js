$(document).ready(function()
{
	$('#nliS').addClass('active');

	// Clear input file on click. This allows the user to
	// re-upload the same file without refreshing the page.
	$('#inputFile').on('click',function(e)
	{
		$(this).val('');
	});
	$('#inputFile').change(function(e)
	{
		$('#uploadForm').submit();
	});
	$('#uploadForm').submit(function(e)
	{
		error('#FFFF00','Uploading...');
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			type:		'post',
			url:		'/cgi/post/FPJ/uploadTrxs.php',
			processData:	false,
			contentType:	false,
			data:		formData,
			success: function(response)
			{
				switch(response)
				{
					case '-1':
						error('#00FF00','Success! Redirecting...');
						window.setTimeout(goToReview,750);
						return;
					case '-2':
						error('#00FF00','No new transactions found.');
						break;
					case '0':
						error('#FF0000','Transmission error: no file was attached.');
						break;
					default:
						error('#FF0000','Invalid data on line ' + response + '.');
						break;
				}
			},
			error: function(response)
			{
				error('#FF0000','An unknown error occurred. Please try again.');
			}
		});
	});
});

function error(color, message)
{
	$('#errP').css('color',color);
	$('#errP').html(message);
}

function goToReview()
{
	window.location.href = '/ui/services/FPJ/review.php';
}
