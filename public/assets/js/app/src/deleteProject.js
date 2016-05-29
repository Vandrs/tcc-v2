$(document).ready(function(){
    $('body').on('click','a.delete-project',function(evento){
        evento.preventDefault();
        var html = "Deseja mesmo excluir este projeto?<br />Esta ação não poderá ser desfeita.";
        var url = $(this).attr('href');
        function deleteProject(){
            window.location = url;
        }
        showConfirmationModal(html,deleteProject);
    });
});


