$(document).ready(function(){
	var dataTableRoute = $("#invitationsTable").attr('data-list-route');
	var invitationsTable = $("#invitationsTable").DataTable({
		processing: true,
		serverSide: true,
		dom:dataTableScrollLayout,
		ajax: {
			url: dataTableRoute,
			data: function(d){		
			},
		},
		order: [[ 0, "desc" ]],
		columns:[
			{data:'invitation_date', name:'user_projects.created_at'},
			{data:'message', name:'projects.title', orderable:false, searchable:false},
			{data:'actions',name:'actions', orderable:false, searchable:false}
		],
		language:translateDataTables(),
		filter:false,
		drawCallback: function( settings ) {
    		$('[data-toggle="tooltip"]').tooltip();
    		$.material.init();        
  		}
	});

	$("body").on("click",".accept, .deny",function(evento){
		evento.preventDefault();
		var route = $(this).attr('href');
		doProcess(route, invitationsTable);
	});
});

function doProcess(route, table){
	var postData = {"_token":TOKEN};
	var area = $(".invitationFeedbackArea");
	$.ajax({
		url: route,
		type: 'POST',
		data: postData,
		success: function(data){
			addFeedBack(area, data.msg, data.class_msg);	
			table.draw();
		},
		error: function(){
			addFeedBack(area, GENERIC_ERROR_MSG, "alert-danger");
		},
		beforeSend: function(){
			clearFeedBack(area);
		},
		dataType: 'json'
	});
}