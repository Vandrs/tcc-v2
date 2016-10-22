$(document).ready(function(){
	$(".date").datepicker({
		"language":"pt-BR",
		"startDate":new Date(),
		"autoClose":true,
		"clearButton":true
	});

	$("#addQuestion").click(function(evento){
		evento.preventDefault();
		addQuestion(getIdx());
	});

	$('body').on('click','.deleteQuestion',function(evento){
		evento.preventDefault();
		var question = $(this).parents('.question:first');
		var idx = $(question).attr('data-idx');
		var questionId = $(question).find("[name='question["+idx+"][id]']").val();
		if (hasValue(questionId)) {
			deleteQuestion(questionId, question)
		} else {
			removeQuestion(question);
		}
	});
	$("body").on('click', '.deleteValidation', function(evento){
		evento.preventDefault();
		var url = $(this).attr('href');
		var html = "Deseja mesmo excluir este Questionário?<br />Esta ação não poderá ser desfeita.";
		function deleteProject(){
            window.location = url;
        }
        showConfirmationModal(html,deleteProject);
	});
});


function addQuestion(idx){
	var html ='<div class="col-xs-12 question" data-idx="'+idx+'">'+
			 	'<div class="form-group">'+
			 		'<div class="input-group">'+
			 			'<input type="text" name="question['+idx+'][title]" class="form-control" placeholder="Título da Questão"/>'+
			 			'<span class="input-group-btn">'+
			 				'<button class="btn btn-fab btn-fab-mini deleteQuestion">'+
			 					'<i class="material-icons">delete</i>'+
			 				'</button>'+
			 			'</span>'+
			 		'</div>'+
			 	'</div>'+
			 '</div>';
	$(".questions-container").append(html);
}

function removeQuestion(question){
	$(question).remove();
}

function getIdx(){
	var arrIdx = [];
	$('.question').each(function(){
		var idx = $(this).attr('data-idx');
		arrIdx.push(parseInt(idx));
	});
	if (arrIdx.length == 0) {
		return 0;
	} else {
		var maxVal = Math.max.apply(Math, arrIdx);	
		return (maxVal+1);
	}
}

function deleteQuestion(id, question){
	var route = $('#addQuestion').attr('data-delete-route');
	var area = $('.questionFeedbackArea');
	var postData = {
		'question_id':id,
		'_token':TOKEN
	};
	$.ajax({
		url: route,
		type: 'POST',
		data: postData,
		success: function(data){
			if (data.status) {
				removeQuestion(question);
			} else {
				addFeedBack(area, data.msg, data.class_msg);		
			}
		},
		error: function(){
			addFeedBack(area, GENERIC_ERROR_MSG, 'alert-danger');
		},
		beforeSend: function(){
			clearFeedBack(area);
		},
		dataType: 'json'
	});
}