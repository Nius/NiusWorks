$(document).ready(function()
{
	$('#nliC').addClass('active');

	$('#tree').on('select_node.jstree',function(e, data)
	{
		var id = (data['selected'] + "").substring(4);
		var name = data['node']['text'];
		$('#parentInfo').html("(" + id + ") " + name);
		$('#parentInfo').attr('selectedID',id);
	});

	$('#tree').on('check_node.jstree',function(e, data)
	{
		var id = (data['node']['id'] + "").substring(4);
		var name = data['node']['text'];
		var ico = "<span class='" + data['node']['icon'] + "'></span>";

		var el = document.createElement('p');
		$(el).attr('id','SP' + id);
		$(el).addClass('spitem');
		$(el).html(ico + '&nbsp;&nbsp;(' + id + ') ' + name);

		$('#splist').append(el);
	});

	$('#tree').on('uncheck_node.jstree',function(e, data)
	{
		var id = (data['node']['id'] + "").substring(4);
		$('#SP' + id).remove();
	});

	$('#tree').on('loaded.jstree',function(e, data)
	{
		$('#tree').jstree('open_all');
	});

	$('#submitB').click(function()
	{
		var sparents = [];
		$('.spitem').each(function(i,el)
		{
			sparents.push($(el).attr('id').substring(2));
		});

		var newAch =
		{
			'type':		($('#rbCAT').is(':checked') ? 0 :
					($('#rbUNS').is(':checked') ? 1 :
					($('#rbSIG').is(':checked') ? 2 : 3 ))),
			'name':		$('#constructName').val(),
			'description': 	$('#description').val(),
			'accrueTF': 	$('#accrueTF').is(':checked'),
			'accrueQT': 	$('#accrueQTY').is(':checked'),
			'points':	$('#points').val(),
			'repeat':	$('#repeatTF').is(':checked'),
			'parent':	$('#parentInfo').attr('selectedID'),
			'sparents':	sparents
		}

		$.post('/cgi/achievements/achievements.php',{fn:'create',newAch:newAch}).done(function(data)
		{
			switch(data)
			{
				case('0'):
					$('#submitErrP').html('Invalid construct type.');
					break;
				case('1'):
				case('2'):
					$('#submitErrP').html('Invalid accrue or points value.');
					break;
				case('3'):
					$('#submitErrP').html('Invalid parent.');
					break;
				case('5'):
					$('#submitErrP').html('Invalid stepparent.');
					break;
				case('6'):
					$('#submitErrP').html('Success! Reloading...');
					window.setTimeout(function(){
						window.location = '/ui/services/ACH/create.php';
					},500);
					break;
				default:
					$('#submitErrP').html('Unknown error: ' + data);
					break;
			}
			switch(data)
			{
				case('6'):
					$('#submitErrP').css('color','#00FF00');
					break;
				default:
					$('#submitErrP').css('color','#FF0000');
					break;
			}
		});
	});

	$('#reloadB').click(function()
	{
		$.post('/cgi/achievements/achievements.php',{fn:'reload'}).done(function(data)
		{
			switch(data)
			{
				case '6':
					$('#reloadStatusP').html('Reloaded.');
					window.setTimeout(function(){
						window.location = '/ui/services/ACH/create.php';
					},500);
					break;
				default:
					$('#reloadStatusP').html('Error.');
			}
		});
	});

	$('#rbCAT,#rbACH').change(function()
	{
		if($('#rbCAT').is(':checked'))
		{
			$('#accrueTF').prop('checked',false);
			$('#accrueTF').change();
			$('#accrueTF').attr('disabled','');
			$('#points').attr('disabled','');
			$('#points').css('color','#555555');
			$('#repeatTF').attr('disabled','');

			$('#rbUNS').attr('disabled','');
			$('#rbSIG').attr('disabled','');
			$('#rbSPC').attr('disabled','');
		}
		else
		{
			$('#accrueTF').removeAttr('disabled');
			$('#points').removeAttr('disabled');
			$('#points').css('color','#BBCCFF');
			$('#repeatTF').removeAttr('disabled');

			$('#rbUNS').removeAttr('disabled');
			$('#rbSIG').removeAttr('disabled');
			$('#rbSPC').removeAttr('disabled');
		}
	});

	$('#accrueTF').change(function()
	{
		var box = $('#accrueQTY');
		if($(this).is(':checked'))
		{
			box.removeAttr('disabled');
			box.css('color','#BBCCFF');
		}
		else
		{
			box.attr('disabled','');
			box.css('color','#555555');
		}
	});
});
