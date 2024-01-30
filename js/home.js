$(document).ready(function()
{
	$('.user-card .header').css('background-image','url(/resources/images/GenericBanner.png)');

	$('.cn_cell').click(function()
	{
		$('#ec_cell').html($('#ec_'+$(this).attr('id')).html());
		$('#ec_cell, #ec_cell td').css('background-color','#111111');
		$('#ec_cell, #ec_cell td').animate({'background-color':'#000000'},'slow');
	});

	$('.cn_cell').hover(
		function()
		{
			$(this).animate({'color':'#BBFFBB'},'fast');
		},function()
		{
			$(this).animate({'color':'#BBBBBB'},'fast');
		});

	$('#gmail').click();

	$('#nliH').addClass('active');
});
