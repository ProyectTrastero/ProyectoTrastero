window.addEventListener("load", iniciar);
var estanteria;
var balda;
var seleccionada;
function iniciar(){
    estanteria = document.getElementById("seleccionEstanteria");
//    balda = document.getElementById("seleccionBalda");
    estanteria.addEventListener("click", crearBaldas);
    }

function obtenerEstanteria(){
    seleccionada= estanteria.selectedIndex;
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
                    var balda = document.getElementById("seleccionBalda");
                    var numeroHijos=balda.children.length;
                    if(numeroHijos!=0){
                        do{
                           balda.removeChild(balda.lastChild);  
                        }while(numeroHijos.length!=0)
                    }
                    for(i=0;i<numero;i++){
                        var elemento=document.createElement("OPTION");
                        elemento.innerHTML=i+1;
                        document.getElementById("seleccionBalda").appendChild(elemento);
                    }
                   
            }}); 
    });  
}


