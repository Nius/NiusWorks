$(document).ready(function()
{
	// Mark positive and negative transactions with green and red
	var firstRow = true;
	$.each($('#trxs-tbl tr'),function(index,tr)
	{
		if(firstRow)
		{
			firstRow = false;
			return;
		}
		var value = parseFloat($(tr).children('.amount-td').first().html());
		$(tr).addClass(value > 0 ? 'positive' : 'negative');
	});

	
});
