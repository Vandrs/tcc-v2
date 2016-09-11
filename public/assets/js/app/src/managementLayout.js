ManagementLayout = {};

ManagementLayout.buildList = function(list){
	var cardHtml = '';
	if(list.cards && list.cards.length > 0){
		$(list.cards).each(function(){
			cardHtml += ManagementLayout.buildCardInList(this);
		});
	}
	var listHtml = '<div class="management-list-wraper">'+
                        '<div class="management-list" data-list-id='+list.id+'>'+  
                            '<div class="management-list-header">'+
                                '<h4>'+list.name+'</h4>'+
                                '<div class="management-list-controls">'+
                                	'<a class="edit-list"><i class="material-icons">edit</i></a>'+
                                	'<a class="delete-list"><i class="material-icons">delete</i></a>'+
                                '</div>'+
                            '</div>'+
                            '<div class="management-list-cards">'+
                				cardHtml+            	
                            '</div>'+
                            '<div class="management-list-bottom text-right">'+
                            	'<button class="btn btn-success btn-raised addTask">'+
                            		'<i class="material-icons">add</i> Tarefa'+
                            	'</button>'+
                            '</div>'+
                        '</div>'+
                    '</div>';
    return listHtml;
};

ManagementLayout.buildCardInList = function(card){
	var html = '<div class="panel panel-info card" data-card-id="'+card.id+'">'+
			        '<div class="card-details">'+
			            '<div class="panel-heading alert alert-info card-title">'+card.name+'</div>'+
			            '<div class="panel-body text-right">'+
			            	'<button class="btn btn-default btn-fab btn-fab-mini editCard" data-toggle="tooltip" title="Editar">'+
								'<i class="material-icons">edit</i>'+
							'</button>&nbsp;'+
							'<button class="btn btn-default btn-fab btn-fab-mini deleteCard" data-toggle="tooltip" title="Excluir">'+
								'<i class="material-icons">delete</i>'+
							'</button>'+
			            '</div>'+
			        '</div>'+
    			'</div>';
    return html;
}