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

	/*
	$('#tree').on('loaded.jstree',function(e, data)
	{
		$('#tree').jstree('open_all');
	});
	*/

});
