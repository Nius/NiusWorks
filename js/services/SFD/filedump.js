$(document).ready(function()
{
	$('#nliS').addClass('active');

	$('#file-selector').change(function()
	{
		$('#errP').html($('#file-selector').val().match(/[^\\\/]*$/)[0]);
		$('#errP').css('color','#BBBBBB');
	});

	$('#executeB').click(function()
	{
		$('#errP').html('Uploading...');
		$('#errP').css('color','#FFFF00');
		var files = document.getElementById('file-selector').files;
		var formData = new FormData();
		for(var i = 0; i < files.length; i ++)
		{
			var file = files[i];
			formData.append('files[]',file,file.name);
		}

		var xhr = new XMLHttpRequest();
		xhr.open('POST','/cgi/post/SFD/upload.php',true);
		xhr.onload = function()
		{
			if(xhr.status === 200)
			{
				$('#errP').html('Upload complete.');
				$('#errP').css('color','#00FF00');
				window.setTimeout(function(){location.reload();},500);
			}
			else
			{
				$('#errP').html('Error');
				$('#errP').css('color','#FF0000');
			}
		};

		xhr.send(formData);
	});
});
