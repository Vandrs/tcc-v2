$(document).ready(function(){

	$(".date").datepicker({
		"language":"pt-BR",
		"autoClose":true
	});

	$('[data-toggle="tooltip"]').tooltip();

	$('body').on('click', '.remove-graduation', function(evento){
		evento.preventDefault();
		$(this).parents('.row.graduation:first').remove();
	});

	$('body').on('click', '.remove-work', function(evento){
		evento.preventDefault();
		$(this).parents('.row.work:first').remove();
	});

	$('body').on('click', '.addWork', function(evento){
		evento.preventDefault();
		var idx = getWorkTplNextIdx();
		var html = workTpl(idx);
		$(".works-container").append(html);
		$(".date").datepicker({
			"language":"pt-BR",
			"autoClose":true
		});
		$('[data-toggle="tooltip"]').tooltip();
		initSortables();
	});

	$('body').on('click', '.addGraduation', function(evento){
		evento.preventDefault();
		var idx = getGraduationTplNextIdx();
		var html = graduationTpl(idx);
		$(".graduations-container").append(html);
		$(".date").datepicker({
			"language":"pt-BR",
			"autoClose":true
		});
		$('[data-toggle="tooltip"]').tooltip();
		initSortables();
	});

	$("body").on('click', '.showDatePicker', function(evento){
		evento.preventDefault();
		$(this).parents(".input-group:first").find('.date').datepicker().data('datepicker').show();
	});

});

function getWorkTplNextIdx(){
	var arrIdx = [];
	$('.row.work').each(function(){
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

function getGraduationTplNextIdx(){
	var arrIdx = [];
	$('.row.graduation').each(function(){
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


function workTpl(idx){
	var html = '<div class="row work box" data-idx="'+idx+'">'+
                        '<div class="col-xs-12 text-right margin-top-10">'+
                            '<button data-toggle="tooltip" type="button" class="btn btn-fab btn-fab-mini btn-raised btn-danger remove-work margin-right-5" title="Remover"><i class="material-icons">delete</i></button>'+
                        '</div>'+
                        '<div class="col-xs-12 col-md-6">'+
                            '<div class="form-group">'+
                                '<label for="work_title_'+idx+'" class="control-label">Cargo*</label>'+
                                '<input class="form-control" type="text" name="User[works]['+idx+'][title]" id="work_title_'+idx+'" value=""/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-xs-12 col-md-6">'+
                            '<div class="form-group">'+
                                '<label for="work_company_'+idx+'" class="control-label">Empresa*</label>'+
                                '<input class="form-control" type="text" name="User[works]['+idx+'][company]" id="work_company_'+idx+'" value=""/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-xs-12">'+
                            '<div class="form-group">'+
                                '<label for="work_description_'+idx+'" class="control-label">Principais atividades*</label>'+
                                '<textarea id="work_description_'+idx+'" class="form-control" name="User[works]['+idx+'][description]"></textarea>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-xs-12 col-md-6">'+
                            '<div class="form-group">'+
                                '<label for="work_started_at_'+idx+'" class="control-label">Inicio*</label>'+
                                '<input class="form-control date" type="text" name="User[works]['+idx+'][started_at]" id="work_started_at_'+idx+'" value=""/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-xs-12 col-md-6">'+
                            '<div class="form-group">'+
                                '<label for="work_ended_at_'+idx+'" class="control-label">Fim* (Não preencher se for o emprego atual)</label>'+
                                '<input class="form-control date" type="text" name="User[works]['+idx+'][ended_at]" id="work_ended_at_'+idx+'" value=""/>'+
                            '</div>'+
                        '</div>'+
                    '</div>';
    return html;
}

function graduationTpl(idx){
	var html = '<div class="row graduation box" data-idx="'+idx+'">'+
			        '<div class="col-xs-12 text-right margin-top-10">'+
			            '<button data-toggle="tooltip" type="button" class="btn btn-fab btn-fab-mini btn-raised btn-danger remove-graduation btn-danger" title="Remover"><i class="material-icons">delete</i></button>'+
			        '</div>'+
			        '<div class="col-md-4">'+
			            '<div class="form-group">'+
			                '<label class="control-label" for="grad_course_'+idx+'">Curso*</label>'+
			                '<input class="form-control" type="text" name="User[graduations]['+idx+'][course]" id="grad_course_'+idx+'" value=""/>'+
			            '</div>'+
			        '</div>'+
			        '<div class="col-md-4">'+
			            '<div class="form-group">'+
			                '<label class="control-label" for="grad_institution_'+idx+'">Instituição de Ensino*</label>'+
			                '<input class="form-control" type="text" name="User[graduations]['+idx+'][institution]" id="grad_institution_'+idx+'" value=""/>'+
			            '</div>'+
			        '</div>'+
			        '<div class="col-md-4">'+
			            '<div class="form-group">'+
			                '<label class="control-label" for="grad_conclusion_at_'+idx+'">Data de Conclusão*</label>'+
			                '<input class="form-control date" type="text" name="User[graduations]['+idx+'][conclusion_at]" id="grad_conclusion_at_'+idx+'" value=""/>'+
			            '</div>'+
			        '</div>'+
			    '</div>';
	return html;
}