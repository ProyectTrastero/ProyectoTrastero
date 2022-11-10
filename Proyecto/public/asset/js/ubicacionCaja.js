window.addEventListener("load", iniciar);
var estanteria;
var balda;
var seleccionada;
var sinAsignar;

function iniciar(){
    estanteria = document.getElementById("seleccionEstanteria");
    balda = document.getElementById("seleccionBalda");
    estanteria.addEventListener("click", crearBaldas);
    sinAsignar = document.getElementById("sinAsignar");
    sinAsignar = document.addEventListener("change", habilitarCombo);
    }

function obtenerEstanteria(){
    seleccionada= estanteria.selectedIndex;
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
                    if(balda.hasChildNodes()){
                        do{
                            var eliminado=balda.lastChild;
                           balda.removeChild(eliminado);  
                        }while(balda.hasChildNodes())
                    }
                    for(i=0;i<numero;i++){
                        var elemento=document.createElement("OPTION");
                        elemento.innerHTML=i+1;
                        balda.appendChild(elemento);
                    }
                   
            }}); 
    });  
}


