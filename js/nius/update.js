var CLOCK_INTERVAL;

$(document).ready(function()
{
	// Limit short post length
	// Echo short post input as HTML
	$('#spi').bind('change paste keyup',function()
	{
		var text = $('#spi').val();
		if(text.length > 512)
		{
			$('#spi').val(text.substring(0,512));
			return;
		}
		$('#sp_echo').html(text);
		$('#sp_count').html(text.length + ' / 512');
	});

	// Echo long post input as HTML
	$('#lpi').bind('change paste keyup',function()
	{
		var text = $('#lpi').val();
		$('#lp_echo').html(text);
	});

	// Limit long post title length
	$('#lp_naM_title').bind('change paste keyup',function()
	{
		var text = $('#lp_naM_title').val();
		if(text.length > 512)
		{
			$('#lp_naM_title').val(text.substring(0,512));
			return;
		}
	});

	// Show modal for new article when click on 'new'
	$('#lp_articles tr:nth-of-type(1)').click(function()
	{
		$('#lp_naM').modal('show');
	});
	$('#lp_articles tr:not(.draft):not(tr:nth-of-type(1))').click(function()
	{
		window.location = '/ui/nius/viewPost.php?post='+$(this).attr('articleID');
	});

	// Switch to edit mode of draft when clicked
	$('.draft td:not(td:last-child)').click(function()
	{
		edit_lp($(this).parent().attr('articleID'));
	});

	// SHORT POST
	$('#sp_submitB').click(function()
	{
		$.post('/cgi/post/nius_update.php',{content:$('#spi').val(),mode:'short'},function(data)
		{
			switch(data){
			case '1':
				$('#sp_submitB').css('background-color','#00FF00');
				window.setTimeout(function(){
					window.location = '/ui/nius/activity.php';
				},500);
				break;
			default:
				$('#sp_submitB').css('background-color','#FF0000');
				break;
			}
			$('#sp_submitB').animate({'background-color':'#222222'},'slow');
		}).fail(function()
		{
			$('#sp_submitB').css('background-color','#FF0000');
			$('#sp_submitB').animate({'background-color':'#222222'},'slow');
		});
	});

	//
	// LONG POST
	//

	// New article (create title)
	$('#naM_executeB').click(function()
	{
		if($('#lp_naM_title').val().length < 1)
			return naM_feedback('#FF0000','Please enter a title.');

		$.post('/cgi/post/nius_update.php',{mode:'long_new',title:$('#lp_naM_title').val()},function(data)
		{
			switch(data){
			case 'title':
				return naM_feedback('#FF0000','This article title already exists.');
			case 'file':
				return naM_feedback('#FF0000','File write error.');
			default:
				naM_feedback('#00FF00','Article created.');
				window.setTimeout(function()
				{
					$('#lp_articles').hide();
					$('#lp_edit').show();
					$('#lp_title').html($('#lp_naM_title').val());
					$('#lp_edit').attr('aid',data);
					$('#lp_naM').modal('hide');
				},500);
			}
		}).fail(function()
		{
			return naM_feedback('#FF0000','A communication error occurred.');
		});
	});

	// Update draft
	$('#lp_draftB').click(function()
	{
		$.post('/cgi/post/nius_update.php',{mode:'long_update',aid:$('#lp_edit').attr('aid'),content:$('#lpi').val()},function(data)
		{
			switch(data){
			case '1':
				$('#lpi').css('background-color','#008800');
				break;
			default:
				$('#lpi').css('background-color','#880000');
				break;
			}
			$('#lpi').animate({'background-color':'#000000'},'slow');
		}).fail(function()
		{
			$('#lpi').css('background-color','#880000');
			$('#lpi').animate({'background-color':'#000000'},'slow');
		});
	});

	// Publish
	$('#lp_submitB').click(function()
	{
		$.post('/cgi/post/nius_update.php',{mode:'long_publish',aid:$('#lp_edit').attr('aid'),content:$('#lpi').val()},function(data)
		{
			switch(data){
			case '1':
				$('#lpi').css('background-color','#008800');
				window.setTimeout(function()
				{
					window.location = '/ui/nius/viewPost.php?post='+$('#lp_edit').attr('aid');
				},500);
				break;
			default:
				$('#lpi').css('background-color','#880000');
				break;
			}
			$('#lpi').animate({'background-color':'#000000'},'slow');
		}).fail(function()
		{
			$('#lpi').css('background-color','#880000');
			$('#lpi').animate({'background-color':'#000000'},'slow');
		});
	});

	updateDates();
	CLOCK_INTERVAL = window.setInterval(updateDates,1000);

	// Scaffold
	//$('tr[articleid="4"] > td:first').click();
});

function edit_lp(aid)
{
	$('#lp_articles').hide();
	$('#lp_edit').show();

	$.post('/cgi/post/nius_update.php',{mode:'long_query',aid:aid},function(data)
	{
		var pkg = $.parseJSON(data);
		$('#lp_title').html(pkg['title']);
		$('#lpi').val(pkg['content']);
		$('#lpi').change();
		$('#lp_edit').attr('aid',aid);
	}).fail(function()
	{
		var errp = document.createElement('p');
		$(errp).css({	'color'		: '#FF0000',
				'font-weight'	: 'bold'	});
		$(errp).html('Draft query failed.');
		$('#lp_edit').replaceWith(errp);
	});
}

function naM_feedback(color,message)
{
	$('#naM_errP').css('color',color);
	$('#naM_errP').html(message);
	return false;
}

function updateDates()
{
	var currentdate = new Date(); 
	var datetime =
                currentdate.getFullYear() + "-"
                + (currentdate.getMonth()+1)  + "-"
		+ currentdate.getDate() + " "
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();
	$('#spDateP').html(datetime);
	$('#lpDateP').html(datetime);
}

//
//	HTML ASSISTED TEXTBOXES
//

$(document).ready(function()
{
	$('.html-assist').keypress(function(e)
	{
		var text = $(this).val();
		if(e.which == 13 || e.which == 10)
		{
			if(e.shiftKey)
				return;
			else if(e.ctrlKey)
			{
				var lindex = text.lastIndexOf('<');
				var lindex2 = text.lastIndexOf('>');
				if(lindex < 0 || lindex2 < 0)
					return;
				var tag = text.substring(lindex + 1,lindex2);
				$(this).val(text + '</' + tag + '>\n<' + tag + '>');
			}
			else
				$(this).val(text + '<br>');
		}
	});
});
