$(document).ready(function(){
	$('.btn-follow').click(function(evento){
		evento.preventDefault();
		if($(this).hasClass('following')){
			var route = $(this).attr('data-unfollow-route');
		} else {
			var route = $(this).attr('data-follow-route');
		}
		followProcess(route,this);
	});

	$("body").on("click", ".viewModalProfile", function(evento){
		evento.preventDefault();
		var id = $(this).attr('data-id');
		showModalProfile(id, ".searchUsersBackArea");
	});
});


function followProcess(route, btn){
	var feedBackArea = '.project-follow-feedBack';
	$.ajax({
		"url":route,
		"type":"POST",
		"data":{"_token":TOKEN},
		success:function(data){
			if(data.status){
				if($(btn).hasClass('following')){
					$(btn).removeClass('following');
				} else {
					$(btn).addClass('following');
				}
				$('.followers-area').html(data.html);
			} else {
				addFeedBack(feedBackArea, data.msg, data.class_msg);
			}
		},
		error:function(data){
			addFeedBack(feedBackArea, GENERIC_ERROR_MSG, 'alert-danger');
		},
		beforeSend:function(){
			clearFeedBack(feedBackArea);
		},
		"dataType":"json"
	});
}

