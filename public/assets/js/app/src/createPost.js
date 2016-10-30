$(document).ready(function(){

	tinymce.init({ 
		selector:"textarea",
		language: "pt_BR",
  		plugins : "imagetools table link code textcolor VwNunesImageUpload",
    	toolbar: "undo redo | paragraph title bold italic forecolor backcolor | format | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link imagetools VwNunesImageUpload | table | removeformat | code",
    	extended_valid_elements : "article[class|name|id],figure[class|name|id],figcaption[class|name|id]",
    	relative_urls: false
	});

	$('body').on('change','#imageUpload',function(){
		var uploadButton = this;
		if(this.files.length){
			var validFiles = []; 
			var errors = [];
			var fileValidator = getFileValidator();
			for(var i = 0; i < this.files.length; i++){
				var currentFile = this.files[i];
				if(!fileValidator.extension(currentFile,['.png','.jpg','.jpeg'])){
					errors.push(currentFile.name+": extensão inválida. Permitido apenas (png, jpg, jpeg)");
				} else if(!fileValidator.size(this.files[i],10)){
					errors.push(currentFile+": tamanho máximo permitido 10MB");
				} else {
					validFiles.push(currentFile);
				}
			}
			if(errors.length){
				tinymce.activeEditor.windowManager.alert(errors.join("<br />"));			
			}
			$(validFiles).each(function(){
				var form = $('form')[0]; 
				var formData = new FormData(form);
				formData.append('image', this);
				formData.append('_token', TOKEN);
				simpleUpload(formData, $(uploadButton).attr('data-upload-route'));
			});
		}
	});

	
});

function simpleUpload(data, route){
		var editor = tinymce.editors[0];
		$.ajax({
			url: route,
			type:'POST',
			data: data,
			success:function(data){
				if(data.status){
					var html = "<img src='"+data.image+"' alt='"+data.name+"'/>";
					var htmlEditor = editor.getContent();
					editor.execCommand('mceInsertContent', false, html);
				} else {
					editor.windowManager.alert(data.msg);
				}
			},
			error:function(){
				editor.windowManager.alert(GENERIC_ERROR_MSG);
			},
			contentType: false,
    		processData: false,
    		dataType:'json'
		});
}

function getFileValidator(){
	return {
		"extension": function(file, allowedExtensions){
			var found = false;
			for(var i = 0; i < allowedExtensions.length; i++ ){
				var possibleExtension = file.name.slice(allowedExtensions[i].length * -1);
				if(allowedExtensions[i].toUpperCase() == possibleExtension.toUpperCase()){
					found = true;
					break;
				}
			}
			return found;
		},
		"size": function(file, maxSize){
			var size = file.size / 1048576;
			if(size > maxSize){
				return false
			}
			return true;
		}
	}
}

tinymce.PluginManager.add('VwNunesImageUpload', function(editor, url) {
    editor.addButton('VwNunesImageUpload', {
        text: 'Upload',
        icon: 'image',
        tooltip: "Upload de imagem",
        onclick: function() {
        	$('#imageUpload').click();
        }
    });
}); 