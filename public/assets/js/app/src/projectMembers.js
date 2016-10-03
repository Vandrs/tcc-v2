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

	$("#submitUserSearch").click(function(evento){
		evento.preventDefault();
		search();
	});

	$("body").on('click', ".pagination a", function(evento){
		evento.preventDefault();
		var url = BASE_URL+$(this).attr("href");
		sendSearch({},url);
	});
});


function search(){
	var form = $("#searchUsers");
	var data = extractFormData(form);
	if (validateSearch(data)) {
		sendSearch(data,$(form).attr("action"));
	} else {
		addFeedBack(".searchUsersBackArea", "Preencha pelo menos um dos campos para realizar a busca.", "alert-danger");
	}
}

function extractFormData(form){
	var data = {};
	data.name 		= $(form).find("[name='name']").val();
	data.work 		= $(form).find("[name='work']").val();
	data.graduation = $(form).find("[name='graduation']").val();
	data.skills 	= $(form).find("[name='skills']").val();
	return data;
}

function validateSearch(data){
	var keys = Object.keys(data);
	var found = false;
	for (var i in keys) {
		if (hasValue($.trim(data[keys[i]]))) {
			found = true;
			break
		}
	}
	return found;
}

function sendSearch(sendData, action){
	var feedBackArea = ".searchUsersBackArea";
	$.ajax({
		url: action,
		data: sendData,
		type: "GET",
		dataType: "json",
		success: function(data){
			if (data.status) {
				$(".users-search-result").html(data.html_users);
				$(".users-search-pagination").html(data.html_paginator);
				$("[data-toggle='tooltip']").tooltip();
				$('html, body').animate({ 
				   scrollTop: $(document).height()-$(window).height()}, 
				   700, 
				   "linear"
				);

			} else {
				addFeedBack(feedBackArea, data.msg, data.class_msg);	
			}
		},
		error: function(){
			addFeedBack(feedBackArea, GENERIC_ERROR_MSG, "alert-danger");
		},
		beforeSend: function(){	
			$(".users-search-result").html("");
			$(".users-search-pagination").html("");
			clearFeedBack(feedBackArea);
		}
	});
}