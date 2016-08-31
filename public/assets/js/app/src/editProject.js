$(document).ready(function(){

    tinymce.init({ 
        selector:"textarea",
        language: "pt_BR",
        plugins : "table link code textcolor",
        toolbar: "undo redo | paragraph title bold italic forecolor backcolor | format | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | table | removeformat | code",
        extended_valid_elements : "article[class|name|id],figure[class|name|id],figcaption[class|name|id]",
        relative_urls: false
    });
    
    var urlsIdx = $('.project-url').length;
    var fotoUploadUrl = $('#uploadImage').attr('data-route');
    var fileUploadUrl = $('#uploadFile').attr('data-route');
    $('[name="image"]').fileupload({
        url: fotoUploadUrl,
        type:'POST',
        dataType: 'json',
        done: function (e, data) {
            if(data.result.status){
                appendPhoto(data.result.image);
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
                appendFile(data.result.file);
            } else {
                addFeedBack('.fileFeedBack', data.result.msg, data.result.class_msg);
            }
        },
        error:function(e){
            addFeedBack('.fileFeedBack', GENERIC_ERROR_MSG, 'alert-danger');
        }
    });
    $('#uploadImage').click(function(evento){
        evento.preventDefault();
        $('[name="image"]').click();
    });
    $("#uploadFile").click(function(evento){
        evento.preventDefault();
        $('[name="file"]').click();
    });
    $('body').on('change','.photo-name,.photo-cover',function(evento){
        evento.preventDefault();
        var photoContainer = $(this).parents('.photo-container:first');
        var image = {
            "id":$(this).attr('data-id'),
            "title":$(photoContainer).find(".photo-name").val(),
            "cover":$(photoContainer).find(".photo-cover").prop("checked")?1:0
        }
        updateImage(image);
    });
    $('body').on('click','.remove-photo',function(evento){
        evento.preventDefault();
        var photoContainer = $(this).parents('.photo-container:first');
        var id = $(this).attr('data-id');
        deleteImage(photoContainer, id);
    });
    $('body').on('click','.add-url',function(evento){
        evento.preventDefault();
        addUrl($(this),urlsIdx);
        urlsIdx++;
    });
    $('body').on('click','.remove-url',function(evento){
        evento.preventDefault();
        removeUrl($(this));
    });
    $('body').on('click','.remove-file',function(evento){
        evento.preventDefault();
        var container = $(this).parents(".file-container:first");
        var id = $(container).attr('data-id');
        deleteFile(container, id);
    })
});

function deleteImage(photoContainer, id){
    var deleteRoute = $('.photos-container').attr('data-exclude-route');
    $.ajax({
        url:deleteRoute,
        type:"POST",
        data:{"id":id,"_token":TOKEN},
        success:function(data){
            addFeedBack('.photoFeedBack',data.msg,data.class_msg);
            if(data.status){
                $(photoContainer).remove();
            }
        },
        error:function(){
            addFeedBack('.photoFeedBack',GENERIC_ERROR_MSG,"alert-danger");
        },
        dataType:"json"
    });
}

function updateImage(image){
    var updateRoute = $('.photos-container').attr('data-update-route');
    image._token = $("[name='_token']").val();
    $.ajax({
        url:updateRoute,
        type:"POST",
        data:image,
        success:function(data){
            addFeedBack('.photoFeedBack',data.msg,data.class_msg);
        },
        error:function(){
            addFeedBack('.photoFeedBack',GENERIC_ERROR_MSG,"alert-danger");
        },
        dataType:"json"
    });
}

function deleteFile(container,id){
    var deleteRoute = $(".files-container").attr('data-exclude-route');
    console.log(deleteRoute);
    $.ajax({
        url:deleteRoute,
        data:{"id":id,"_token":TOKEN},
        type:"POST",
        success:function(data){
            if(data.status){
                $(container).remove();
            }
            addFeedBack('.fileFeedBack',data.msg,data.class_msg);
        },
        error:function(){
            addFeedBack('.fileFeedBack',GENERIC_ERROR_MSG,"alert-danger");
        },
        dataType:"json"
    });
}

function appendPhoto(image){
    var html = "<div class='col-xs-12 col-sm-6 col-md-4 margin-top-20 photo-container'>"+
                "<img src='"+image.url+"' class='img-responsive'/>"+
                "<input type='text' maxlength='50' value='' placeholder='Título da imagem' class='form-control photo-name margin-top-10' data-id='"+image.id+"'/>"+
                "<div class='checkbox'>"+
                    "<label>"+
                        "<input type='radio' name='cover' class='photo-cover' data-id='"+image.id+"' /> Foto de capa"+
                    "</label>"+
                "</div>"+
                "<button class='remove-photo btn btn-danger full-size margin-top-10' data-id='"+image.id+"'>"+
                    "Excluir <span class='glyphicon glyphicon-trash'></span>"+
                "</button>"+
               "</div>";
    $(".photos-container").append(html);
}

function appendFile(file){
    var html = '<div class="file-container" data-id="'+file.id+'">'+
        '<div class="col-xs-10 col-md-11 margin-top-10">'+
        '<input type="text" value="'+file.title+'" class="form-control" readonly/>'+
        '</div>'+
        '<div class="col-xs-2 col-md-1 margin-top-10">'+
        '<button type="button" class="btn btn-danger remove-file">'+
        '<span class="glyphicon glyphicon-trash"></span>'+
        '</button>'+
        '</div>'+
        '</div>';
    $('.files-container').append(html);
}

function removeUrl(button){
    var parent = $(button).parents('.project-url:first');
    $(parent).remove();
}

function addUrl(button, inputIdx){
    var html = "<div class='project-url'>" +
        "<div class='col-xs-9 col-md-10 margin-top-10'>"+
        "<div class='input-group'>"+
        "<span class='input-group-addon'>http(s)://</span>"+
        "<input type='text' class='form-control' name='urls[]' id='url_'"+inputIdx+"'>"+
        "</div>"+
        "</div>"+
        "<div class='col-xs-3 col-md-2'>"+
        "<button type='button' 'Adicionar' class='add-url btn btn-success margin-right-5 margin-top-10'>"+
        "<span class='glyphicon glyphicon-plus'></span>"+
        "</button>"+
        "<button type='button' class='remove-url btn btn-danger margin-top-10'>"+
        "<span class='glyphicon glyphicon-trash'></span>"+
        "</button>"+
        "</div>"+
        "</div>";
    $(button).parents('.project-urls:first').append(html);
}

