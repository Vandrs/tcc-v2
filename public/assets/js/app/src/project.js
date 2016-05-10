$(document).ready(function(){
	var fotoUploadUrl = $('#uploadImage').attr('data-route');
	var imageIndex = getImageIndex();
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
        	} else {	
        		console.log(data.result.msg);
        		addFeedBack('.photoFeedBack', data.result.msg, data.result.class_msg);
        	}
        },
        error:function(e){
        	addFeedBack('.photoFeedBack', GENERIC_ERROR_MSG, 'alert-danger');
        }
    });

    $("#uploadImage").click(function(evento){
    	evento.preventDefault();
    	$('[name="image"]').click();
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

});

function appendPhoto(file){
	var html = "<div class='col-xs-12 col-sm-6 col-md-4 margin-top-20'>";
		html += 	"<img src='"+file.url+"' class='img-responsive'/>";
		html += 	"<input type='text' maxlength='50' placeholder='Título da imagem' class='form-control photo-name margin-top-10' data-id='"+file.id+"'/>";
		html += 	"<div class='checkbox'>";
    	html +=			"<label>"
      	html +=				"<input type='radio' name='cover' class='photo-cover' data-id='"+file.id+"' /> Foto de capa";
    	html +=			"</label>"
  		html +=		"</div>";
		html += 	"<button class='remove-photo btn btn-danger full-size margin-top-10' data-id='"+file.id+"'>";
		html +=			"Excluir <span class='glyphicon glyphicon-trash'></span>";
		html += 	"</button>";
	    html += "</div>";
	    $('.photos-container').append(html);
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