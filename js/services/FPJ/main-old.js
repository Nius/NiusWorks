$(document).ready(function()
{
	$('#nliS').addClass('active');
	$('#startBal').change(updateMoney);
	$('#startBal').change(updateDefault);

	$('#refreshB').click(function()
	{
		$('#errP').css('color','#FFFF00');
		$('#errP').html('Refreshing...');

		$.post('/cgi/query/finsheet.php',{fn:'refresh'}).done(function(data)
		{
			switch(data)
			{
				case '0':
					$('#errP').css('color','#FF0000');
					$('#errP').html('An error occurred.');
					break;
				case '1':
					$('#errP').css('color','#00FF00');
					$('#errP').html('Success! Refreshing...');
					window.setTimeout(function(){location.reload();},500);
					break;
				default:
					$('#errP').css('color','#FF0000');
					$('#errP').html('An unknown error occurred.');
					break;
			}
		}).fail(function(data)
		{
			$('#errP').css('color','#FF0000');
			$('#errP').html('Script unavailable.');
		});
	});

	$('.charge,.income').click(function()
	{
		ignore(this);
	});

	updateMoney();
});

function ignore(el)
{
	$(el).toggleClass('ignored');
	updateMoney();
}

function updateDefault()
{
	var def = $('#startBal').val();
	$.post('/cgi/query/finsheet.php',{fn:'updef',val:def});
}

function updateMoney()
{
	var prevTotal = parseFloat($('#startBal').val());
	if(prevTotal == null || isNaN(prevTotal))
		prevTotal = 0;
	var pmin = prevTotal;
	var pminday = $('#startBal').siblings('.mday').html();

	var fneg = null;
	var fnegday = pminday;

	var high = 0;
	var highday = pminday;

	var startday = pminday;
	var startbal = prevTotal;

	$.each($('#calendar td'),function(index,tdparent)
	{
//	$.each($('.total'),function(index,el)
//	{
		var total = 0;
		var el = $(tdparent).children('.total').first();

		var charges = $(el).siblings('.charge');
		$.each(charges,function(index,chg)
		{
			if($(chg).hasClass('ignored'))
				return;
			var amt = $(chg).find('.right').html();
			total -= parseFloat(amt);
		});

		var income = $(el).siblings('.income');
		$.each(income,function(index,chg)
		{
			if($(chg).hasClass('ignored'))
				return;
			var amt = $(chg).find('.right').html();
			total += parseFloat(amt);
		});

		total += prevTotal;
		prevTotal = total;
		$(el).html('New Balance:<br>' + total.toFixed(2));

		if(total < pmin)
		{
			pmin = total;
			pminday = $(el).siblings('.mday').html();
		}

		if(fneg == null && total < 0)
		{
			fneg = total;
			fnegday = $(el).siblings('.mday').html();
		}

		if(total > high)
		{
			high = total;
			highday = $(el).siblings('.mday').html();
		}

		if(prevTotal < 0)
			$(tdparent).addClass('neg');
	});

	$('#pmin').html('Projected Minimum:<br>'+pmin.toFixed(2)+' on '+pminday);
	if(pmin > 500)
		$('#pmin').css('color','#00FF00');
	else if(pmin > 0)
		$('#pmin').css('color','#FFFF00');
	else
		$('#pmin').css('color','#FF0000');

	if(fneg != null)
	{
		$('#fneg').html('First projected negative:<br>'+fneg.toFixed(2)+' on '+fnegday);
		$('#fneg').css('color','#FF0000');
	}
	else
	{
		$('#fneg').html('No projected negatives.');
		$('#fneg').css('color','#00FF00');
	}

	var daydiff = (new Date(highday) - new Date(startday)) / 86400000;
	if(daydiff > 150)
	{
		$('#high').css('color','#00FF00');
		$('#high').html('Positive trend.');
	}
	else
	{
		$('#high').html('Projected high:<br>'+high.toFixed(2)+' on '+highday+',<br>'+daydiff.toFixed(0)+' days from now.');
		$('#high').css('color','#FF0000');
	}
}
