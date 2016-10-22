$(document).ready(function(){
	var table = $('#tableValidations');
	var listRoute = $(table).attr('data-list-route');
	var dataTable = $(table).DataTable({
		processing: true,
		serverSide: true,
		dom:dataTableScrollLayout,
		ajax: {
			url: listRoute,
			data: function(d){
				
			},
		},
		order: [[ 0, "asc" ]],
		columns:[
			{data:'title', name:'project_validations.title'},
			{data:'started_at', name:'project_validations.started_at', searchable:false},
			{data:'ended_at', name:'project_validations.ended_at', searchable:false},
			{data:'actions',name:'actions', orderable:false, searchable:false}
		],
		language:translateDataTables(),
		filter:false,
		drawCallback: function( settings ) {
    		$('[data-toggle="tooltip"]').tooltip();
    		$.material.init();        
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