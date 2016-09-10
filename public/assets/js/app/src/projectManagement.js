$(document).ready(function(){

});

$(window).load(function(){
	if(C3Trello.isApiLoaded()){
		if(!C3Trello.isAuthorized()){ 
			C3Trello.authorize(initBoard,managementConnectionFailed);
		} else {
			initBoard();
		}
	} else {
		managementConnectionFailed();
	}	
});


function initBoard(){
	C3Trello.getBoardLists(
		C3Trello.currentBoardId(),
		getBoardLists,
		managementConnectionFailed
	);
}

function getBoardLists(lists){
	$(".management-container").html("");
	$(lists).each(function(){
		var listHtml = ManagementLayout.buildList(this);
		$(".management-container").append(listHtml);
	});
	$('[data-toggle="tooltip"]').tooltip();	
}

function managementConnectionFailed(){
	var msg = 'O gerenciamento de projetos ñão está disponível no momento.<br />Tente novamente mais tarde e se o erro persistir contate o administrador do sistema.';
	addFeedBack('.trelloBeedBackArea',msg,'alert-danger');
}

function openModalList(action, id, name){
	if(action == "create"){
		$("#modalList").find(".modal-title").text("Nova Lista de Tarefas");		
	} else if(action == "update"){
		$("#modalList").find(".modal-title").text("Editar Lista de Tarefas");		
		$("#modalList").find(".modal-title").val();
	}

	var modalList = $("#modalList").modal({
						"backdrop":"static"
					});
}