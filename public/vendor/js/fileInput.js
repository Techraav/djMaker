function fileInput(el)
{
	var form = $(el).parents('form')[0].getAttribute('id');
	var filename = $(el).val().replace(/C:\\fakepath\\/i, '');
	$('#file-name').text(filename);
	var fileExtension = filename.substr(filename.lastIndexOf('.')+1).toLowerCase();
	var allowedExtension = $(el).data("extension");
	allowedExtension = allowedExtension.replace(/'/g,"");
	allowedExtension = allowedExtension.replace(/ /g,"");
	allowedExtension = allowedExtension.substr(1,allowedExtension.length-2);
	allowedExtension = allowedExtension.split(',');

	var check = allowedExtension.indexOf(fileExtension) != -1;
	if(!check && fileExtension != ""){
		$('.file-control').addClass('file-error');
		$('#file-check').attr('checked', false);
	}
	else {
		$('.file-control').removeClass('file-error');
		$('#file-check').attr('checked', true);
	}

}

function clickFile()
{
	$('#file')[0].click();
}

$('.file-control #exit').click(function(){
	var form = $(this).parents('form')[0].getAttribute('id');
	$('.file-control').removeClass('file-error');
	$('#file-name').text('');
	$('#file').value = '';
	$('#file-check').attr('checked', false);
});	