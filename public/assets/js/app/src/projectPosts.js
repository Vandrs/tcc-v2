$(document).ready(function(){
	var postsTable = $("#postsTable");
	var dataTableRoute = $(postsTable).attr('data-list-route');
	var dataTable = $(postsTable).dataTable({
		processing: true,
		serverSide: true,
		dom:dataTableScrollLayout,
		ajax: {
			url: dataTableRoute,
			data: function(d){
				
			},
		},
		order: [[ 1, "desc" ]],
		columns:[
			{data:'title', name:'posts.title'},
			{data:'created_at', name:'posts.created_at'},
			{data:'user_name', name:'users.name'},
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