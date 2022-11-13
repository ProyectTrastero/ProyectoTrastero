window.addEventListener("load", iniciar);
var estanteria;
var balda;
var seleccionada;
var sinAsignar;

function iniciar(){
    estanteria = document.getElementById("seleccionEstanteria");
    balda = document.getElementById("seleccionBalda");
    estanteria.addEventListener("click", crearBaldas);
    crearBaldas();
    sinAsignar = document.getElementById("sinAsignar");
    sinAsignar = document.addEventListener("change", habilitarCombo);
    }

function obtenerEstanteria(){
    seleccionada= estanteria.options[estanteria.selectedIndex].text;
}


function habilitarCombo(){
    if(document.getElementById("sinAsignar").checked){
        var deshabilitado = document.createAttribute("disabled");
        var deshabilitado1 = document.createAttribute("disabled");
        estanteria.setAttributeNode(deshabilitado);
        balda.setAttributeNode(deshabilitado1);
    }else{
        estanteria.removeAttribute("disabled");
        balda.removeAttribute("disabled");
    }
}

function crearBaldas(){

$(document).ready(function(){
            url="respuestaAjax.php";
            obtenerEstanteria();
            $.ajax({
                type: "POST",
                url: url, 
                dataType: "json", 
                data: {estanteriaSeleccionada: seleccionada},
                success: function(result){
                    var numero=result.numeroBaldas;
                    var listadoBaldas=Object.keys(numero);
                   
                    if(balda.hasChildNodes()){
                        do{
                            var eliminado=balda.lastChild;
                           balda.removeChild(eliminado);  
                        }while(balda.hasChildNodes())
                    }
                    for(i=0;i<listadoBaldas.length;i++){
                        var elemento=document.createElement("OPTION");
                        elemento.innerHTML=parseInt(listadoBaldas[i])+1;
                        balda.appendChild(elemento);
                    }
                   
            }}); 
    });  
}


