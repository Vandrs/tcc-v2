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

C3Trello.getBoardLists = function(boardId, successCallback, errorCallback){
	Trello.get('/boards/'+boardId+'/lists', {'cards':'open','filter':'open'} , successCallback, errorCallback);
}

