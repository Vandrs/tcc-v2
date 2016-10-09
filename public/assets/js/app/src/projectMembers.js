$(document).ready(function(){
	var membersTable = $("#membersTable");
	var listRoute = $(membersTable).attr('data-list-route');
	var dataTableMembers = $(membersTable).DataTable({
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

	$("body").on("click", ".view-modal-profile", function(evento){
		evento.preventDefault();
		var id = $(this).attr('data-user-id');
		showModalProfile(id, ".searchUsersBackArea");
	});

	$("body").on("click", ".show-invite-modal", function(evento){
		evento.preventDefault();
		clearFeedBack('.projectUsersBackArea');
		var userId = $(this).attr('data-user-id');
		var userName = $(this).attr('data-user-name');
		$("#formInvite").find("[name='user_id']").val(userId);
		$("#formInvite").find("[name='temp_role']").val("");
		$("#formInvite").find(".user-name").text(userName);
		$("#modalInvite").modal({
			backdrop: "static"
		});
	});

	$("body").on('click','.inviteUser',function(evento){
		evento.preventDefault();
		inviteProcess(dataTableMembers);
	})

	$("body").on("change", ".change-role", function(evento){
		evento.preventDefault();
		var userId = $(this).attr('data-user-id');
		var roleId = $(this).find("option:selected").val();
		changeRole(userId, roleId);
	});

	$("body").on("click", ".removeMember", function(evento){
		evento.preventDefault();
		var userId = $(this).attr('data-user-id');
		var html = "Deseja mesmo realizar esta ação?";
		function goRemove(){
			removeMember(userId, dataTableMembers);	
		}
		showConfirmationModal(html,goRemove);
		
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

function inviteProcess(dataTableMembers){
	var form  = $("#formInvite");
	var route = $("#formInvite").attr("action");
	var formData  = $("#formInvite").serialize();
	var area = ".inviteFeedbackArea";
	$.ajax({
		url: route,
		type: "POST",
		data: formData,
		success: function(data){
			if(data.status){
				addFeedBack(".projectUsersBackArea", data.msg, data.class_msg);
				$("#modalInvite").modal("hide");
				$('html, body').animate({scrollTop:0}, 700, "linear");
				dataTableMembers.draw();
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
		dataType: "json"
	});
}

function changeRole(userId, roleId){
	var area = ".projectUsersBackArea";
	var route = $("#membersTable").attr('data-role-route');
	var postData = {
		"user_id": userId,
		"role_id": roleId,
		"_token" : TOKEN
	};
	$.ajax({
		url: route,
		type: 'POST',
		data: postData,
		success: function(data){
			addFeedBack(area, data.msg, data.class_msg);
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

function removeMember(userId, table){
	var area = ".projectUsersBackArea";
	var route = $("#membersTable").attr('data-remove-route');
	var postData = {
		"user_id": userId,
		"_token" : TOKEN
	};
	$.ajax({
		url: route,
		type: 'POST',
		data: postData,
		success: function(data){
			addFeedBack(area, data.msg, data.class_msg);
			if (data.status) {
				table.draw();
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