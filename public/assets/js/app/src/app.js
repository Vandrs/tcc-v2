var dataTableScrollLayout = "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                            "<'row'<'col-sm-12'<'responsive-table-panel' tr>>>" +
                            "<'row'<'col-sm-5'i><'col-sm-7'p>>";

function isvalidBRDate(strData)
{
    var data = strData;
    var dia = data.substring(0,2);
    var mes = data.substring(3,5);
    var ano = data.substring(6,10);
 
    var novaData = new Date(ano,(mes-1),dia);
 
    var mesmoDia = parseInt(dia,10) == parseInt(novaData.getDate());
    var mesmoMes = parseInt(mes,10) == parseInt(novaData.getMonth())+1;
    var mesmoAno = parseInt(ano) == parseInt(novaData.getFullYear());
 
    if (!((mesmoDia) && (mesmoMes) && (mesmoAno)))
    {
        return false;
    }
    
    return true;
}

function hasValue(data,allowZero){
    if(allowZero && (data === '0' || data === 0)){
        return true;
    } 
    if(data !== null && data !== "" && data !== undefined && data !== 0 && data !== '0'){
        return true;
    }
    return false;
}

function ucwords(str){
    arrStr = str.toLowerCase().split(" ");
    arrAux = new Array();
    
    for(var i = 0; i < arrStr.length; i++){
        var nome = arrStr[i][0];
        if(nome != undefined){
            nome = nome.toUpperCase();
            nome += arrStr[i].substr(1,arrStr[i].length);
            arrAux.push(nome);
        }
    }
    return arrAux.join(" ");
}

function myTrim(x) {
    return x.replace(/^\s+|\s+$/gm,'');
}

function isObjectEmpty(obj){
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop)){
            return false;
        }
    }

    return true;
}

/**
  * @author Marcone Gledson de Almeida
  * @date 2008
  */  
function converteMoedaFloat(valor){

   if(valor === ""){
      valor =  0;
   }else{
      valor = valor.replace(/\./g,"");
      valor = valor.replace(",",".");
      valor = parseFloat(valor);
   }
   return valor;

}

/**
  * @author Marcone Gledson de Almeida
  * @date 2008
  */
function converteFloatMoeda(valor){
   
   var inteiro = null, decimal = null, c = null, j = null;
   var aux = new Array();
   
   valor = ""+valor;
   c = valor.indexOf(".",0);
   
   if(c > 0){
      inteiro = valor.substring(0,c);
      decimal = valor.substring(c+1,valor.length);
   }else{
      inteiro = valor;
   }

   for (j = inteiro.length, c = 0; j > 0; j-=3, c++){
      aux[c]=inteiro.substring(j-3,j);
   }

   inteiro = "";
   for(c = aux.length-1; c >= 0; c--){
      inteiro += aux[c]+'.';
   }

   inteiro = inteiro.substring(0,inteiro.length-1);

   decimal = parseInt(decimal);
   if(isNaN(decimal)){
      decimal = "00";
   }else{
      decimal = ""+decimal;
      if(decimal.length === 1){
         decimal = decimal+"0";
      }
   }

   valor = inteiro+","+decimal;
   return valor;
}

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

function unsetFromArray(arrayToSearch,key){
    for (var field in arrayToSearch) {
        if (myTrim(arrayToSearch[field].toString()) === myTrim(key.toString())) {
            arrayToSearch.splice(field, 1);
        }
    }
    return arrayToSearch;
}

function testEmail(email){
    var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
    return re.test(email);
}

function addFeedBack(area,msg,classMsg,timeOutDesapear){
    var html =  '<div class="alert '+classMsg+' alert-dismissible fade in" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                        '<span aria-hidden="true">×</span>'+
                    '</button>'+
                    msg+
                '</div>';
    $(area).html(html);
    if(hasValue(timeOutDesapear)){
      setTimeout(function(){
        clearFeedBack(area);
      },timeOutDesapear);
    }                
}

function clearFeedBack(area){
    $(area).html("");
}

function clearErrorsInArea(area){
    $(area).find(".control-group").each(function(){
        $(this).removeClass("has-error");
    });
}

function translateDataTables(){
  return  {
    "processing": "Carregando...",
        "lengthMenu": "Mostrando _MENU_ registros por página",
        "zeroRecords": "Nenhum registro encontrado",
        "info": "Página _PAGE_ de _PAGES_  (_TOTAL_ registros)",
        "infoEmpty": "Nenhum registro encontrado",
        "infoFiltered": " - Total de _MAX_",
        "search": "Buscar em todos os campos:",
        "paginate": {
          "next": "Próximo",
          "previous":"Anterior",
          "first":"Início",
          "last":"Fim"
        }
    };
}
function startsWith(str,param){
  if(str.match("^"+param)){
    return true;
  }
  return false;
}

$(document).ready(function(){
    $.material.init();
    $('[data-toggle="tooltip"]').tooltip();
});

function showConfirmationModal(html,acceptCallBack,denyCallBack){
    $("#appConfirmModal #confirmModalAction").unbind('click');
    $("#appConfirmModal #denyModalAction").unbind('click');
    $("#appConfirmModal").modal('show');
    $("#appConfirmModal").find(".modal-body").html(html);
    $("#appConfirmModal #denyModalAction").click(function(evento){
        evento.preventDefault();
        if(denyCallBack){
            denyCallBack();
        }
        $("#appConfirmModal").modal('hide');
    });
    $("#appConfirmModal #confirmModalAction").click(function(evento){
        evento.preventDefault();
        if(acceptCallBack){
            acceptCallBack();
        }
        $("#appConfirmModal").modal('hide');
    });
}