window.addEventListener("load", iniciar);
var estanterias;

function iniciar(){
    estanterias= document.getElementsByClassName("papeleraOculta");
    for(i=0;i<estanterias.length;i++){
        
        estanterias[i].addEventListener("mouseover", añadirPapelera);
        estanterias[i].addEventListener("mouseout", eliminarPapelera);
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
        atributo2.value = "eliminarEstanteria";
        atributo3.value = "Eliminar";
        boton.setAttributeNode(atributo);
        boton.setAttributeNode(atributo2);
        boton.setAttributeNode(atributo3);
        if((elemento.children.length==0)&&(e.target.tagName=="TH")){
        elemento.appendChild(papelera);
        elemento.appendChild(boton);
    }

}

function eliminarPapelera(e){
    var elemento = e.target;
    var papelera=elemento. firstElementChild;
    var boton=elemento.lastChild;
    var retardo=setTimeout(function(){
        elemento.removeChild(papelera);
        elemento.removeChild(boton);
    }, 1000);  

}
