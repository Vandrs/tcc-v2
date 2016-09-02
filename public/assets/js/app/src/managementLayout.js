ManagementLayout = {};

ManagementLayout.buildList = function(list){
	var cardHtml = '';
	console.log(list.cards);
	if(list.cards && list.cards.length > 0){
		$(list.cards).each(function(){
			cardHtml += ManagementLayout.buildCardInList(this);
		});
	}
	var listHtml = '<div class="management-list-wraper" data-list-id='+list.id+'>'+
                        '<div class="management-list">'+  
                            '<div class="management-list-header">'+
                                '<h4>'+list.name+'</h4>'+
                            '</div>'+
                            '<div class="management-list-cards">'+
                				cardHtml            	
                            '</div>'+
                        '</div>'+
                    '</div>';
    return listHtml;
};

ManagementLayout.buildCardInList = function(card){
	var html = '<div class="panel panel-info card" data-card-id="'+card.id+'">'+
			        '<div class="card-details">'+
			            '<div class="panel-heading alert alert-info card-title">'+card.name+'</div>'+
			            '<div class="panel-body card-title text-right">'+
			            	'<button class="btn btn-default btn-fab btn-fab-mini" data-toggle="tooltip" title="Editar">'+
								'<i class="material-icons">edit</i>'+
							'</button>&nbsp;'+
							'<button class="btn btn-default btn-fab btn-fab-mini" data-toggle="tooltip" title="Excluir">'+
								'<i class="material-icons">delete</i>'+
							'</button>'+
			            '</div>'+
			        '</div>'+
    			'</div>';
    return html;
}