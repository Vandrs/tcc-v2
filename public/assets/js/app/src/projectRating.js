$(document).ready(function(){
    initRatings();
    $("#rate-project").click(function(evento){
        evento.preventDefault();
        if(!$(this).hasClass('disabled')){
            getUserProjectNote($(this).attr('data-route'),$(this));
        }
    });
});

function initRatings(){
    $('.project-note-value').each(function(){
        var min  = parseFloat($(this).attr('min'));
        var max  = parseFloat($(this).attr('max'));
        var value = parseFloat($(this).val());
        var options = {
            'min':min,
            'max':max,
            'size':'sm',
            'step':0.5,
            'stars':5,
            'displayOnly':true,
            'language':'pt-bt'
        };
        $(this).rating(options);
    });
}

function getUserProjectNote(route,button){
    $.ajax({
        url:route,
        type:"GET",
        success:function(data){
            if(data.status){
                builEvaluationArea(data.note);
                $(button).addClass('disabled');
            } else {
                addFeedBack('.project-rate-feedBack','Avaliação indisponível no momento','alert-danger',5000);
            }
        },
        error:function(){
            addFeedBack('.project-rate-feedBack','Avaliação indisponível no momento','alert-danger',5000);
        },
        dataType:"json"
    });
}

function builEvaluationArea(data){
    $('.rate-project-area').html("<input type='number' value='"+data.actual+"' id='rateProject'/>");
    $('#rateProject').rating({
        'min':data.min,
        'max':data.max,
        'size':'sm',
        'step':0.5,
        'stars':5,
        'language':'pt-bt',
        'showCaption':false,
        'showClear':false
    });
    $('#rateProject').on('rating.change', rateEventCallback);
    $('.rate-project-area').removeClass('hidden');
}

function rateEventCallback(event,value,label){
    rateProject(value);
}

function rateProject(note){
    var route = $('#rate-project').attr('data-rate-route');
    var postData = {"note":note,"_token":TOKEN};
    $.ajax({
        url:route,
        type:'POST',
        data:postData,
        success:function(data){
            if(data.status){
                $('.project-note-value').rating('update', data.avg_note);
            }
            addFeedBack('.project-rate-feedBack', data.msg, data.class_msg, 5000);
        },
        error:function(){
            addFeedBack('.project-rate-feedBack','Avaliação indisponível no momento','alert-danger',5000);
        },
        dataType:'json'
    });
    console.log(route);
}