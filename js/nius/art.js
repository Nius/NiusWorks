$(document).ready(function()
{
	$('#nliO').addClass('active');

	$('.pic-card img').click(function(data)
	{
		$('#viewer_img').attr('src',$(this).attr('src'));
		$('#viewer').modal();
	});
});
