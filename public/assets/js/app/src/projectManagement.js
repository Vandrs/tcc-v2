$(document).ready(function(){

	$("#newList").click(function(evento){
		evento.preventDefault();
		ModalList.open(ModalList.CREATE_ACTION);
	});

	$("body").on("click", "#modalList .list-ok-action", function(evento){
		evento.preventDefault();
		ModalList.save(TRELLO_BOARD_ID);
	});

	$("body").on("click", ".edit-list", function(evento){
		evento.preventDefault();
		var listWraper = $(this).parents(".management-list:first");
		var listId = $(listWraper).attr("data-list-id");
		var listName = $(listWraper).find(".management-list-header h4").text();
		ModalList.open(ModalList.UPDATE_ACTION, listId, listName);
	});

	$("body").on("click", ".delete-list", function(evento){
		evento.preventDefault();
		var html = "Deseja mesmo excluir esta Lista de Tarefas?<br />Caso a Lista possua tarefas elas também serão excluídas";
		var listId = $(this).parents(".management-list:first").attr("data-list-id");
		showConfirmationModal(html,function(){
			ModalList.delete(listId);
		});
	});

	$("body").on("click", ".addTask", function(evento){
		evento.preventDefault();
		var listId = $(this).parents(".management-list:first").attr("data-list-id");
		ModalCard.open(ModalCard.CREATE_ACTION,listId);
	});

	$(".dueDatePicker").datepicker({
		"language":"pt-BR",
		"startDate":new Date(),
		"autoClose":true,
		"clearButton":true
	});

	$(".showDueDateCalendar").click(function(evento){
		evento.preventDefault();
		$(".dueDatePicker").datepicker().data('datepicker').show();
	});

	$(".card-ok-action").click(function(evento){
		evento.preventDefault();
		ModalCard.save();
	});

	$("body").on("click",".editCard",function(evento){
		evento.preventDefault();
		var idCard = $(this).parents(".card:first").attr('data-card-id');
		C3Trello.getCard(idCard,function(card){
			ModalCard.open(ModalCard.UPDATE_ACTION, card.idList, card.id, card.name, card.desc, "");
			if(hasValue(card.due)){
				$(".dueDatePicker").datepicker().data('datepicker').selectDate(new Date(card.due));
			}
		},function(){
			addFeedBack('.trelloBeedBackArea', GENERIC_ERROR_MSG, 'alert-danger');
		});
	});

	$("body").on("click",".deleteCard", function(evento){
		evento.preventDefault();
		var idCard = $(this).parents(".card:first").attr('data-card-id');
		var html = "Deseja mesmo excluir esta tarefa?";
		showConfirmationModal(html,function(){
			ModalCard.delete(idCard);
		});
	});

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

function initSortable(){
	 $( ".management-list-cards" ).sortable({
      	connectWith: ".management-list-cards",
      	placeholder: "ui-state-highlight",
      	zIndex: 999999,
      	containment: "document",
      	stop: function(event, ui){
      		var idCard = $(ui.item).attr("data-card-id");
      		var list   = $(ui.item).parents(".management-list:first");
      		var idList = $(list).attr("data-list-id");
      		C3Trello.updateCardList(idCard, idList);
      		var ids = [];
      		$(list).find(".card").each(function(){
      			ids.push($(this).attr("data-card-id"));

      		});
      		C3Trello.orderCards(ids);
      	}
    });

	$(".management-container").sortable({
	 	stop: function(){
	 		var ids = [];
	 		$(".management-list").each(function(){
	 			ids.push($(this).attr("data-list-id"));
	 		});
	 		C3Trello.orderLists(ids);
	 	}
	});
}


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
	initSortable();
}

function managementConnectionFailed(){
	var msg = 'O gerenciamento de projetos não está disponível no momento.<br />Tente novamente mais tarde e se o erro persistir contate o administrador do sistema.';
	addFeedBack('.trelloBeedBackArea', msg, 'alert-danger');
}

/* INICIO MODAL LIST */

var ModalList = {"id":"#modalList"}; 

ModalList.CREATE_ACTION = "create";
ModalList.UPDATE_ACTION = "update";

ModalList.open = function(action, listId, listName){
	this.clean();
	var modal = this.getModal();
	if (action == ModalList.CREATE_ACTION) {
		$(modal).find(".modal-title").text("Nova Lista de Tarefas");		
		this.setData("", "", action);
	} else if(action == ModalList.UPDATE_ACTION) {
		$(modal).find(".modal-title").text("Editar Lista de Tarefas");
		this.setData(listId, listName, action);			
	}
	$(modal).modal({"backdrop":"static"});
};

ModalList.save = function(boardId){
	var modal = this.getModal();
	var data = this.getData();
	if (data.action == ModalList.CREATE_ACTION) {
		if(this.validate()){
			var listData = {
				"idBoard": boardId,
				"name": data.name 
			};
			C3Trello.createList(listData,function(list){
				var listHtml = ManagementLayout.buildList(list);
				$(".management-container").prepend(listHtml);
				$(modal).modal("hide");
			},function(){
				addFeedBack(".modalLisFeedbackArea","Ocorreu um erro ao tentar criar a Lista de Tarefas","alert-danger");
			});
		}
	} else if (data.action == ModalList.UPDATE_ACTION) {
		if(this.validate()){
			var listData = {"value":data.name};
			C3Trello.updateList(data.id, listData, function(){
				var listWraper = $(".management-list[data-list-id='"+data.id+"']");
				$(listWraper).find("h4").text(data.name);
				$(modal).modal("hide");
			},function(){
				addFeedBack(".modalLisFeedbackArea","Ocorreu um erro ao tentar atualizar a Lista de Tarefas","alert-danger");
			});
		}	
	}
}

ModalList.validate = function(){
	var data = this.getData();
	if(!hasValue(data.name)){
		addFeedBack(".modalLisFeedbackArea","Informe o nome da Lista de Tarefas","alert-danger");
		return false;
	} else if (!hasValue(data.action)) {
		addFeedBack(".modalLisFeedbackArea", GENERIC_ERROR_MSG, "alert-danger");
		return false;
	} else if (data.action == this.UPDATE_ACTION && !hasValue(data.id)) {
		addFeedBack(".modalLisFeedbackArea", GENERIC_ERROR_MSG, "alert-danger");
		return false;
	}
	return true;
}

ModalList.getModal = function(){
	return $(this.id);
};

ModalList.clean = function(){
	var modal = this.getModal();
	clearFeedBack(".modalLisFeedbackArea");
	$(modal).find(".modal-title").text("");
	this.setData("","","");
};

ModalList.getData = function(){
	var data 	= {};
	var modal 	= this.getModal();
	data.id 	= $(modal).find(".modal-body").find("[name='listId']").val();
	data.name   = $(modal).find(".modal-body").find("[name='listName']").val();
	data.action = $(modal).find(".modal-body").find("[name='action']").val();
	return data;
};

ModalList.setData = function(listId, listName, action){
	var modal = this.getModal();
	$(modal).find(".modal-body").find("[name='listId']").val(listId);
	$(modal).find(".modal-body").find("[name='listName']").val(listName);
	$(modal).find(".modal-body").find("[name='action']").val(action);
};

ModalList.delete = function(listId){
	if(hasValue(listId)){
		C3Trello.closeList(listId, function(){
			var listHtml = $(".management-list[data-list-id='"+listId+"']");
			var listWraper = $(listHtml).parents(".management-list-wraper:first");
			$(listWraper).remove();
		},function(){
			addFeedBack(".trelloBeedBackArea", GENERIC_ERROR_MSG, "alert-danger");	
		});
	} else {
		addFeedBack(".trelloBeedBackArea", GENERIC_ERROR_MSG, "alert-danger");
	}
};

/* INICIO CARD */

ModalCard = {};

ModalCard.UPDATE_ACTION = "update";
ModalCard.CREATE_ACTION = "create";

ModalCard.getModal = function(){
	return $("#modalCard");
};

ModalCard.clean = function(){
	var modal = this.getModal();
	clearFeedBack(".modalCardFeedbackArea");
	$(modal).find(".modal-title").text("");
	this.setData("", "", "", "", "", "");
	$(".dueDatePicker").datepicker().data('datepicker').clear();
	
};

ModalCard.setData = function(action, cardIdList, cardId, cardName, cardDesc, cardDue){
	var modal = this.getModal();
	$(modal).find("[name='action']").val(action);
	$(modal).find("[name='cardId']").val(cardId);
    $(modal).find("[name='cardIdList']").val(cardIdList);
    $(modal).find("[name='cardName']").val(cardName);
    $(modal).find("[name='cardDesc']").val(cardDesc);
    $(modal).find("[name='cardDue']").val(cardDue);
};

ModalCard.getData = function(){
	var data 	= {};
	var modal 	= this.getModal();
	data.action = $(modal).find("[name='action']").val();
	data.id 	= $(modal).find("[name='cardId']").val();
    data.idList = $(modal).find("[name='cardIdList']").val();
    data.name 	= $(modal).find("[name='cardName']").val();
    data.desc 	= $(modal).find("[name='cardDesc']").val();
    data.due 	= $(modal).find("[name='cardDue']").val();
    return data;
};

ModalCard.open = function(action, cardIdList, cardId, cardName, cardDesc, cardDue){
	this.clean();
	var modal = this.getModal();
	if (action == this.CREATE_ACTION) {
		$(modal).find(".modal-title").text("Nova Tarefa");
		this.setData(action, cardIdList);
	} else if (action == this.UPDATE_ACTION) {
		$(modal).find(".modal-title").text("Editar Tarefa");
		this.setData(action, cardIdList, cardId, cardName, cardDesc);
	}
	$(modal).modal({"backdrop":"static"});
};

ModalCard.validate = function(){
	var data = this.getData();
	if (!hasValue(data.idList) || !hasValue(data.action)) {
		addFeedBack(".modalCardFeedbackArea","Informe o nome da Tarefa","alert-danger");
		return false;
	} else if (data.action == this.UPDATE_ACTION && !hasValue(data.id)) {
		addFeedBack(".modalCardFeedbackArea","Informe o nome da Tarefa","alert-danger");
		return false;
	} else if (!hasValue(data.name)) {
		addFeedBack(".modalCardFeedbackArea","Informe o nome da Tarefa","alert-danger");
		return false;
	}
	return true;
};

ModalCard.save = function(){
	if(this.validate()){
		var data = this.getData();
		var modal = this.getModal();
		if (data.action == this.CREATE_ACTION) {
			var cardData = {
				"name":data.name,
				"desc":data.desc,
				"due": hasValue(data.due) ? dateFromStrBR(data.due).toString() : null,
				"idList":data.idList,
				"pos":"bottom"
			};
			C3Trello.createCard(cardData, function(card){
				var htmlCard = ManagementLayout.buildCardInList(card);
				$(".management-list[data-list-id='"+card.idList+"']").find(".management-list-cards").append(htmlCard);
				$(modal).modal("hide");
			},function(){
				addFeedBack(".modalCardFeedbackArea","Ocorreu um erro ao tentar criar a Tarefa","alert-danger");
			});
		} else if (data.action == this.UPDATE_ACTION) {
			var cardData = {
				"name":data.name,
				"desc":data.desc,
				"due": hasValue(data.due) ? dateFromStrBR(data.due).toString() : null
			};
			C3Trello.updateCard(data.id, cardData, function(){
				$(".card[data-card-id='"+data.id+"']").find(".card-title").text(data.name);
				$(modal).modal("hide");
			},function(){
				addFeedBack(".modalCardFeedbackArea","Ocorreu um erro ao tentar atualizar a Tarefa","alert-danger");
			});
		}
	}
};

ModalCard.delete = function(cardId){
	if(hasValue(cardId)){
		C3Trello.closeCard(cardId,function(){
			$(".card[data-card-id='"+cardId+"']").remove();
		},function(){
			addFeedBack(".trelloBeedBackArea", GENERIC_ERROR_MSG, "alert-danger");
		});
	} else {
		addFeedBack(".trelloBeedBackArea", GENERIC_ERROR_MSG, "alert-danger");
	}
};
