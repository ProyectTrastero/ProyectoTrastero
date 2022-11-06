window.addEventListener("load", iniciar);
var ocultos;

function iniciar(){
    ocultos= document.getElementsByClassName("papeleraOculta");
    
    for(i=0;i<ocultos.length;i++){
        ocultos[i].addEventListener("mouseover", añadirPapelera);
        ocultos[i].addEventListener("mouseout", eliminarPapelera);
    }
}
    
    
function añadirPapelera(e){
        var elemento = e.target;
        var papelera= document.createElement("i");
        papelera.className="fa-sharp fa-solid fa-trash-can";
        var boton = document.createElement("input");
        var atributo=document.createAttribute("type");
        var atributo2 = document.createAttribute("name");
        var atributo3 = document.createAttribute("value");
       
        atributo.value = "submit";
        if(elemento.innerHTML.includes("Estanteria")){
            atributo2.value = "eliminarEstanteria";
        }
         if(elemento.innerHTML.includes("Balda")){
            atributo2.value = "eliminarBalda";
        }
         if(elemento.innerHTML.includes("Caja")){
            atributo2.value = "eliminarCaja";
        }
        atributo3.value = "Eliminar";
       
        boton.setAttributeNode(atributo);
        boton.setAttributeNode(atributo2);
        boton.setAttributeNode(atributo3);
        
        if((elemento.children.length==0)&&(e.target.tagName==="H6")){
        elemento.appendChild(papelera);
        elemento.appendChild(boton);
    }

}

function eliminarPapelera(e){
    var elemento = e.target;
    var papelera=elemento.children[0];
    var boton=elemento.children[1];
    var retardo=setTimeout(function(){
        elemento.removeChild(papelera);
        elemento.removeChild(boton);
    }, 1000);  

}
