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
	C3Trello.getBoardLists(C3Trello.currentBoardId(),function(lists){
		console.log(lists);
		$(lists).each(function(){
			var listHtml = ManagementLayout.buildList(this);
			$(".management-container").append(listHtml);
		});
		$('[data-toggle="tooltip"]').tooltip();
	},managementConnectionFailed);
}

function managementConnectionFailed(){
	var msg = 'O gerenciamento de projetos ñão está disponível no momento.<br />Tente novamente mais tarde e se o erro persistir contate o administrador do sistema.';
	addFeedBack('.trelloBeedBackArea',msg,'alert-danger');
}