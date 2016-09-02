
$(document).ready(function(){

	tinymce.init({ 
		selector:"textarea",
		language: "pt_BR",
  		plugins : "table link code textcolor",
    	toolbar: "undo redo | paragraph title bold italic forecolor backcolor | format | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | table | removeformat | code",
    	extended_valid_elements : "article[class|name|id],figure[class|name|id],figcaption[class|name|id]",
    	relative_urls: false
	});


	var fotoUploadUrl = $('#uploadImage').attr('data-route');
	var fileUploadUrl = $('#uploadFile').attr('data-route');
	var imageIndex = getImageIndex();
	var fileIndex = getFileIndex();
	var urlsIdx = $('.project-url').length;

	$('[name="image"]').fileupload({
        url: fotoUploadUrl,
        type:'POST',
        dataType: 'json',
        done: function (e, data) {
        	if(data.result.status){
        		var tempImageObjs = $.parseJSON($('[name="image_files"]').val());
        		data.result.file.id = imageIndex;
				tempImageObjs.push(data.result.file);
				imageIndex++;
				$('[name="image_files"]').val(JSON.stringify(tempImageObjs));
				appendPhoto(data.result.file);
				$.material.init();
        	} else {	
        		console.log(data.result.msg);
        		addFeedBack('.photoFeedBack', data.result.msg, data.result.class_msg);
        	}
        },
        error:function(e){
        	addFeedBack('.photoFeedBack', GENERIC_ERROR_MSG, 'alert-danger');
        }
    });

	$('[name="file"]').fileupload({
		url:fileUploadUrl,
		type:'POST',
		dataType:'json',
		done:function(e,data){
			if(data.result.status){
				var tempFileObjs = $.parseJSON($('[name="files"]').val());
				data.result.file.id = fileIndex;
				tempFileObjs.push(data.result.file);
				fileIndex++;
				$('[name="files"]').val(JSON.stringify(tempFileObjs));
				appendFile(data.result.file);
				$.magerial.init();
			} else {
				addFeedBack('.fileFeedBack', data.result.msg, data.result.class_msg);
			}
		},
		error:function(e){
			addFeedBack('.fileFeedBack', GENERIC_ERROR_MSG, 'alert-danger');
		}
	});

    $('body').on('keyup','.photo-name',function(){
    	var id = $(this).attr('data-id');
    	var field = 'name';
    	var value = $(this).val();
    	updateTempImage(id, field, value);
    });

    $('body').on('change','.photo-cover',function(){
    	var id = $(this).attr('data-id');
    	var field = 'cover';
    	var value = 1
    	updateTempImage(id, field, value);
    });

    $('body').on('click','.remove-photo',function(evento){
    	evento.preventDefault();
    	var id = $(this).attr('data-id');
    	var images = $.parseJSON($('[name="image_files"]').val());
    	var button = $(this);
		$(images).each(function(index){
			if(this.id == id){
				deleteImage(this,index,images,button);
				return false;
			}
		});
    });

	$('body').on('click','.remove-file',function(evento){
		evento.preventDefault();
		var id = $(this).parents('.file-container:first').attr('data-id');
		var files = $.parseJSON($('[name="files"]').val());
		var button = this;
		$(files).each(function(index){
			if(this.id == id){
				deleteFile(this,index,files,button);
				return false;
			}
		});
	});

	$('body').on('click','.add-url',function(evento){
		evento.preventDefault();
		addUrl($(this),urlsIdx);
		urlsIdx++;
		$.material.init();
	});

	$('body').on('click','.remove-url',function(evento){
		evento.preventDefault();
		removeUrl($(this));
	});


});

function appendPhoto(file){
	var html = "<div class='col-xs-12 col-sm-6 col-md-4 margin-top-20'>";
		html += 	"<img src='"+file.url+"' class='img-responsive'/>";
		html += 	"<input type='text' maxlength='50' placeholder='Título da imagem' class='form-control photo-name margin-top-10' data-id='"+file.id+"'/>";
		html += 	"<div class='radio radio-primary'>";
    	html +=			"<label>"
      	html +=				"<input type='radio' name='cover' class='photo-cover' data-id='"+file.id+"' /> Foto de capa";
    	html += 			"<span class='circle'></span>"
    	html += 			"<span class='check'></span>"
    	html +=			"</label>"
  		html +=		"</div>";
		html += 	"<button class='remove-photo btn btn-danger btn-raised full-size margin-top-10' data-id='"+file.id+"'>";
		html +=			"Excluir <span class='glyphicon glyphicon-trash'></span>";
		html += 	"</button>";
	    html += "</div>";
	    $('.photos-container').append(html);
}

function appendFile(file){
	var html = '<div class="col-xs-12 margin-top-10">'+
					'<div class="file-container form-group" data-id="'+file.id+'">'+
						'<div class="input-group">'+
							'<input type="text" value="'+file.title+'" class="form-control" readonly/>'+
							'<span class="input-group-btn">'+
								'<button type="button" class="btn btn-danger btn-fab btn-fab-mini remove-file">'+
									'<i class="material-icons">delete</i>'+
								'</button>'+
							'</span>'+
						'</div>'+
					'</div>'+
				'</div>';
	$('.files-container').append(html);
}

function getImageIndex(){
	var tempImageObjs = $.parseJSON($('[name="image_files"]').val());
	if(tempImageObjs.length > 0){
		var lasElement = tempImageObjs[tempImageObjs.length - 1];
		return (lasElement.id + 1);
	} else {
		return 1;
	}
}

function getFileIndex(){
	var tempFileObjs = $.parseJSON($('[name="files"]').val());
	if(tempFileObjs.length > 0){
		var lasElement = tempFileObjs[tempFileObjs.length - 1];
		return (lasElement.id + 1);
	} else {
		return 1;
	}
}

function updateTempImage(id, field, value){
	var images = $.parseJSON($('[name="image_files"]').val());
	$(images).each(function(){
		if(this.id == id){
			this[field] = value;
			return false;
		}
	});
	$('[name="image_files"]').val(JSON.stringify(images));
}

function deleteImage(file,index,arrayImages,button){
	var route = $('.photos-container').attr('data-exclude-route');
	$.ajax({
		url:route,
		type:"POST",
		data:{"file_name":file.file,"_token":TOKEN},
		success:function(data){
			if(data.status){
				button.parent().remove();
				arrayImages.splice(index,1);
				$('[name="image_files"]').val(JSON.stringify(arrayImages));
			} else {
				addFeedBack('.photoFeedBack', GENERIC_ERROR_MSG, 'alert-danger');
			}
		},
		error:function(){
			addFeedBack('.photoFeedBack', GENERIC_ERROR_MSG, 'alert-danger');
		},
		dataType:"json"
	});
}

function deleteFile(file,index,arrayFiles,button){
	var route = $('.photos-container').attr('data-exclude-route');
	$.ajax({
		url:route,
		type:"POST",
		data:{"file_name":file.file,"_token":TOKEN},
		success:function(data){
			if(data.status){
				arrayFiles.splice(index,1);
				$(button).parents('.file-container:first').remove();
				$('[name="files"]').val(JSON.stringify(arrayFiles));
			} else {
				addFeedBack('.fileFeedBack', GENERIC_ERROR_MSG, 'alert-danger');
			}
		},
		error:function(){
			addFeedBack('.fileFeedBack', GENERIC_ERROR_MSG, 'alert-danger');
		},
		dataType:"json"
	});
}

function removeUrl(button){
	var parent = $(button).parents('.project-url:first');
	$(parent).remove();
}

function addUrl(button, inputIdx){
	var html = "<div class='project-url'>" +
					"<div class='col-xs-12 margin-top-10'>"+
						"<div class='input-group'>"+
							"<span class='input-group-addon'>http(s)://</span>"+
							"<input type='text' class='form-control' name='urls[]' id='url_'"+inputIdx+"'>"+
							"<span class='input-group-btn'>"+
								"<button type='button' 'Adicionar' class='add-url btn btn-fab btn-fab-mini btn-success margin-top-10'>"+
									"<i class='material-icons'>add</i>"+
								"</button>"+
							"</span>"+
							"<span class='input-group-btn'>"+
								"<button type='button' class='remove-url btn btn-fab btn-fab-mini btn-danger margin-top-10'>"+
									"<i class='material-icons'>delete</i>"+
								"</button>"+
							"</span>"+
						"</div>"+
					"</div>"+
				"</div>";
	$(button).parents('.project-urls:first').append(html);
}