$(document).ready(function(){
	$(".date").datepicker({
		"language":"pt-BR",
		"startDate":new Date(),
		"autoClose":true,
		"clearButton":true
	});

	$("#addQuestion").click(function(){
		addQuestion(getIdx());
	});

	$('body').on('click','.deleteQuestion',function(){
		var question = $(this).parents('.question:first');
		removeQuestion(question);
	});
});


function addQuestion(idx){
	var html ='<div class="col-xs-12 question" data-idx="'+idx+'">'+
			 	'<div class="form-group">'+
			 		'<div class="input-group">'+
			 			'<input type="text" name="question['+idx+']" class="form-control" placeholder="Título da Questão"/>'+
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