$(document).ready(function(){
	var membersTable = $("#membersTable");
	var listRoute = $(membersTable).attr('data-list-route');
	var dataTable = $(membersTable).dataTable({
		processing: true,
		serverSide: true,
		dom:dataTableScrollLayout,
		ajax: {
			url: listRoute,
			data: function(d){
				
			},
		},
		order: [[ 0, "ASC" ]],
		columns:[
			{data:'name', name:'users.name'},
			{data:'role', name:'user_projects.role'},
			{data:'actions',name:'actions', orderable:false, searchable:false}
		],
		language:translateDataTables(),
		filter:false,
		drawCallback: function( settings ) {
    		$('[data-toggle="tooltip"]').tooltip();
    		$.material.init();        
  		}
	});
});