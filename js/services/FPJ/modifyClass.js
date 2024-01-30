$(document).ready(function()
{

	if(typeof listOpts !== 'undefined')
		$('#addSubTxt').autocomplete(
		{
			source: listOpts,
			html: true,
			select: selectSub,
			focus: selectSub
		});

	$('#addSubB').click(addSub);

	colorCodeValues();

	$('#requery').click(reQuery);
	$('#commit').click(commit);
});

// Select a sub in the selection box, to be ready to click the add button
function selectSub(occasion, ui)
{
	$('#addSubTxt').val($('<span>'+ui.item.label+'</span>').text());
	$('#addSubTxt').attr('data-selected',ui.item.value);
	return false;
}

// Add a subclass from the selection box.
// The parameter is optional and is only used during original page loading.
function addSub(selected)
{
	// If the parameter was omitted then it will instead be the
	// event object, passed by default.
	if(typeof selected === 'object')
	{
		// Get the ID of the selected class
		selected = $('#addSubTxt').attr('data-selected');
		if(selected.length < 1)
			return;
		alert(selected);
	}

	// Verify that this class isn't already listed as a subclass
	if($('.subClass[subid='+selected+']').length > 0)
		return;

	// Get the class with the extracted index
	var opt = listOptsIndexed[selected];

	var nel = $('#subClassTemplate').clone();
	$(nel).removeAttr('id');
	$(nel).attr('subID',selected);
	$(nel).prop('hidden',false);
	$(nel).find('.subClassName').html(opt['NAME']);
	$(nel).insertAfter('#subClassTemplate');
	$(nel).find('button.close').click(function()
	{
		$(this).parents('.subClass').remove();
	});
}

// Mark positive and negative transactions with green and red
function colorCodeValues()
{
	var firstRow = true;
	$.each($('#matchesT tr'),function(index,tr)
	{
		if(firstRow)
		{
			firstRow = false;
			return;
		}
		var value = parseFloat($(tr).children('.amount-td').first().html());
		$(tr).addClass(value > 0 ? 'positive' : 'negative');
	});

}

// Re-query the database with the provided regex (and subcategories)
function reQuery()
{
	matchError('#FFFF00','Searching...');

	var pattern = JSON.stringify($('#patternT').val());

	var subKeys = Array();
	$('.subClass:not(:first-child)').each(function()
	{
		subKeys.push($(this).attr('subid'));
	});

	$.ajax({
		url: '/cgi/query/FPJ/matchTRX.php',
		type: 'GET',
		data: {pattern: pattern, subKeys: subKeys},
		success: function(result)
		{
			// In case reQuery is happening after a commit
			$('#commit').prop('disabled',false);

			if(result == '0')
				matchError('#FF0000','Invalid search pattern.');
			else if(result == '-1')
				matchError('#FF0000','No matches found.');
			else
				parseMatches(result);
		},
		error: function(result)
		{
			matchError('#FF0000','An unexpected error occurred. Please try again.');
		}
	});
}

// Commit the modified class to the database
function commit()
{
	matchError('#FFFF00','Committing...');
	$('#commit').prop('disabled',true);

	var pattern = JSON.stringify($('#patternT').val());
	var type = $('#modeSel').val();
	var name = $('#className').val();

	var subKeys = Array();
	$('.subClass:not(:first-child)').each(function()
	{
		subKeys.push($(this).attr('subid'));
	});

	var mod = $('#modifying');
	var action = mod.length === 1 ? $(mod).html() : 'new';

	$.ajax({
		url: '/cgi/post/FPJ/modifyClass.php',
		type: 'POST',
		data: {pattern: pattern, type: type, name: name, subs: subKeys, action: action},
		success: function(result)
		{
			if(result == '1')
			{
				if(action === 'new')
				{
					matchError('#00FF00','Success! Redirecting...');
					window.setTimeout(goToReview,750);
					return;
				}
				else
				{
					matchError('#00FF00','Success! Refreshing...');
					window.setTimeout(reQuery,750);
					return;
				}
			}
			else
				matchError('#FF0000','Invalid data.');
		},
		error: function(result)
		{
			matchError('#FF0000','Unknown error.');
		}
	});
}

function goToReview()
{
	window.location.href = '/ui/services/FPJ/review.php';
}

function matchError(color, message)
{
	$('#matchesT tbody').empty();
	$('#matchesT tbody').html('<tr><td id="matchErrText" style="color:'+color+'" colspan=5>'+message+'</td></tr>');
}

function parseMatches(matchesJSON)
{
	var matches = JSON.parse(matchesJSON);
	var table = $('#matchesT tbody');
	$(table).empty();

	$.each(matches,function(index,match)
	{
		$(table).append
		(
			'<tr>'+
			'<td class="date-td">'+match['TRXDATE']+'</td>'+
			'<td class="account-td">'+match['ACCOUNT']+'</td>'+
			'<td class="amount-td">'+match['VALUE']+'</td>'+
			'<td class="descr-td">'+match['DESCRIPTION']+'</td>'+
			'</tr>'
		);
	});

	colorCodeValues();
}
