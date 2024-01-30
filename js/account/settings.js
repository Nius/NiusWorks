$(document).ready(function()
{
	$('#cpB').click(function(){$('#changePasswordM').modal('show');});

	// Post

	$('#CPMexecuteB').click(function()
	{
		if($('#password').val().length < 5)
			return CPMerr('Please enter a password of at least 5 characters.','red');
		if($('#password').val() != $('#xpassword').val())
			return CPMerr('Your new passwords do not match.','red');
		CPMerr('Submitting...','yellow');
		$.post('/cgi/post/changePassword.php',{obsolete:$('#CPM_old').val(),requested:$('#password').val()}).done(function(data)
		{
			switch(data){
			case '1':
//				window.setTimeout(function(){$('#CPMcancelB').click();},500);
				window.setTimeout(function(){location.reload();},500);
				return CPMerr('Success!','green');
			case '2':
				return CPMerr('Your current password was not correctly entered. Please correct it and try again.','red');
			case '3':
				return CPMerr('The new password is invalid. Please change it and try again.','red');
			default:
				return CPMerr('Something went very wrong. Please let us know that there is a problem.','red');
			}
		}).fail(function()
		{
			CPMerr('A communication error occurred. Please try again.','red');
		});
	});

	// CPM Popovers

	$('#password').popover(
        {
                'title':        'Create a password.',
                'container':    '#changePasswordM .modal-body',
                'html':         true,
                'placement':    'bottom',
                'trigger':      'focus',
                'content':      $('#pwpc').html()
        });
        $('#xpassword').popover(
        {
                'title':        'Confirm your password.',
                'container':    '#changePasswordM .modal-body',
                'html':         true,
                'placement':    'bottom',
                'trigger':      'focus',
                'content':      $('#xppc').html()
        });

	// CPM Popover Feedback

	$('#password').bind('change paste keyup',function()
        {
                if($('#password').val().length >= 5)
                {
                        $('[id=pwpc_char]').css('color','#00FF00');
                        $('[id=pwpc_char] .glyphicon').removeClass('glyphicon-remove');
                        $('[id=pwpc_char] .glyphicon').addClass('glyphicon-ok');
                }
                else
                {
                        $('[id=pwpc_char]').css('color','#FF0000');
                        $('[id=pwpc_char] .glyphicon').addClass('glyphicon-remove');
                        $('[id=pwpc_char] .glyphicon').removeClass('glyphicon-ok');
                }
                $('#password').data('bs.popover').options.content = $('#pwpc').html();
                $('#password').data('bs.popover').tip().find('.popover-content').html($('#pwpc').html());
                $('#xpassword').change();
        });
        $('#xpassword').bind('change paste keyup',function()
        {
                if($('#xpassword').val() == $('#password').val())
                {
                        $('[id=xppc_match]').css('color','#00FF00');
                        $('[id=xppc_match] .glyphicon').removeClass('glyphicon-remove');
                        $('[id=xppc_match] .glyphicon').addClass('glyphicon-ok');
                }
                else
                {
                        $('[id=xppc_match]').css('color','#FF0000');
                        $('[id=xppc_match] .glyphicon').addClass('glyphicon-remove');
                        $('[id=xppc_match] .glyphicon').removeClass('glyphicon-ok');
                }
                $('#xpassword').data('bs.popover').options.content = $('#xppc').html();
		$('#xpassword').data('bs.popover').tip().find('.popover-content').html($('#xppc').html());
	});

});

function CPMerr(text, color)
{
	$('#CPMerrP').html(text);
	$('#CPMerrP').css('color',color);
	return false;
}
