$(document).ready(function(){

	$("#aceptTrello").click(function(){
		var errorCallback = function(){
			var msg = 'Não foi possível vincular-se a uma conta do Trello.<br/>Tente novamente em alguns segundos e se o erro persister contate o administrados do sistema.';
			addFeedBack('.trelloBeedBackArea',msg,'alert-danger');
		};
		C3Trello.authorize(showModalBoardsCallBack,errorCallback);
	});

	$("#btnConfirmBoardSelection").click(function(evento){
		evento.preventDefault();
		$(this).addClass('disabled').text('Processando...');
		var boardId = $("#availableBoards").find("option:selected").val();
		var route = $(this).attr('data-route');
		var button = this;
		if(hasValue(boardId)){
			setBoardId(boardId, route, button);	
		} else {
			var errorCallback = function(){
				var msg = 'Não foi possível criar Quadro.<br/>Tente novamente em alguns segundos e se o erro persister contate o administrados do sistema.';
				addFeedBack('.trelloBeedBackArea',msg,'alert-danger');
			};
			var data = { "name": PROJECT_NAME };
			var successCallBack = function(board){
				setBoardId(board.id, route, button);	
			};
			C3Trello.createBoard(data, successCallBack, errorCallback);
		}

	});

});

$(window).load(function(){
	if(C3Trello.isApiLoaded()){
		if(!C3Trello.isAuthorized()){ 
			C3Trello.authorize(showModalBoardsCallBack,null);
		} 
	} else {
		var msg = 'O gerenciamento de projetos não está disponível no momento.<br />Tente novamente mais tarde e se o erro persistir contate o administrador do sistema.';
		addFeedBack('.trelloBeedBackArea',msg,'alert-danger');
	}
});

function showModalBoards(boards){
	var html = makeBoardsOptions(boards);
	$("#availableBoards").append(html);
	$("#selectBoardModal").modal({
		"show":true,
		"backdrop":"static"
	});
}

function makeBoardsOptions(boards){
	var html = "<option value=''>Criar novo Quadro</option>";
	$(boards).each(function(){
		if(!this.closed){
			html +=	"<option value='"+this.id+"'>"+this.name+"</option>";
		}
	});
	return html;
}

function showModalBoardsCallBack(){
	if (NEED_TO_GET_ID) {
		addTrelloUserId();
	}
	C3Trello.getUserBoards(showModalBoards);	
}

function setBoardId(boardId, route, button){
	var postData = { "board_id": boardId, "_token": TOKEN };
	$.ajax({
		url: route,
		data: postData,
		type: 'POST',
		success:function(data){
			$(button).removeClass('disabled');
			$(button).text('OK');
			if(data.status){
				window.location = data.redirect_to;
			} else {
				addFeedBack(".boardSelectionFeedBackArea", data.msg, data.class_msg);
			}
		},
		error:function(){
			addFeedBack(".boardSelectionFeedBackArea",GENERIC_ERROR_MSG,"alert-danger");
			$(button).removeClass('disabled');
			$(button).text('OK');
		},
		beforeSend:function(){
			clearFeedBack(".boardSelectionFeedBackArea");
		},
		dataType:"json"
	});
}


function addTrelloUserId(){
	C3Trello.getUserData(function(data){
		var postData = {
			'idMember': data.id,
			'_token': TOKEN
		}
		$.post(SET_TRELLO_ID_ROUTE, postData);
	});
}