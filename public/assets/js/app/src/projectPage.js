$(document).ready(function(){
    $("#gallery").lightGallery({
        thumbnail:true
    });

    $(".showGallery").click(function(evento){
    	evento.preventDefault();
    	$("#gallery a").click();
    });

});
