var C3Trello = {};

C3Trello.isApiLoaded = function(){
	return Trello ? true : false;
};

/*Authorizarion*/
C3Trello.isAuthorized = function(){
	return Trello.authorized();
};

C3Trello.authorize = function(successCallback, errorCallback){
	Trello.authorize({
		type: 'popup',
		name: TRELLO_APP_NAME,
		scope: {
		    read: 'true',
		    write: 'true'
		},
		expiration: 'never',
		success: function(){
			if(successCallback){
				successCallback();
			}
		},
		error: function(){
			if(errorCallback){
				errorCallback();
			}
		}
	});
};

/*Boards*/
C3Trello.hasBoard = function(){
	return TRELLO_BOARD_ID ? TRELLO_BOARD_ID : null;
};

C3Trello.currentBoardId = function(){
	return TRELLO_BOARD_ID;
}

C3Trello.getUserBoards = function(callback){
	Trello.members.get('me/boards').promise().then(function(data){
		callback(data);
	});
};

C3Trello.createBoard = function(data, successCallback, errorCallback){
	Trello.post('/boards/', data, successCallback, errorCallback);
};

/* LIST */

C3Trello.getBoardLists = function(boardId, successCallback, errorCallback){
	Trello.get('/boards/'+boardId+'/lists', {'cards':'open','filter':'open'} , successCallback, errorCallback);
};

C3Trello.createList = function(data, successCallback, errorCallback){
	Trello.post('/lists', data, successCallback, errorCallback);
};

C3Trello.updateList = function(id, data, successCallback, errorCallback){
	Trello.put('/lists/'+id+'/name', data, successCallback, errorCallback);	
};

C3Trello.closeList = function(id, successCallback, errorCallback){
	var data = {"value":true};
	Trello.put('/lists/'+id+'/closed', data, successCallback, errorCallback);	
};

C3Trello.orderLists = function(ids){
	for(var i = 0; i < ids.length; i++){
		var data = {"value":(i+1)};
		var url = "/lists/"+ids[i]+"/pos";
		Trello.put(url,data);
	}
};

/* CARD */

C3Trello.createCard = function(data, successCallback, errorCallback){
	Trello.post('/cards', data, successCallback, errorCallback);	
};

C3Trello.getCard = function(id, successCallback, errorCallback){
	Trello.get('/cards/'+id, {"fields":"closed,idList,idBoard,name,desc,due"}, successCallback, errorCallback);	
};

C3Trello.updateCard = function(id, data, successCallback, errorCallback){
	Trello.put('/cards/'+id, data, successCallback, errorCallback);
};

C3Trello.updateCardList = function(id, idList, successCallback, errorCallback){
	var data = {"value":idList};
	Trello.put('/cards/'+id+'/idList', data, successCallback, errorCallback);
};

C3Trello.orderCards = function(ids){
	for(var i = 0; i < ids.length; i++){
		var data = {"value":(i+1)};
		var url = "/cards/"+ids[i]+"/pos";
		Trello.put(url,data);
	}
};

C3Trello.closeCard = function(id, successCallback, errorCallback){
	var data = {"value":true};
	Trello.put('/cards/'+id+'/closed', data, successCallback, errorCallback);
};
