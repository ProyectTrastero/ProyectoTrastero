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
        var papelera= document.createElement("button");
        papelera.className="fa-sharp fa-solid fa-trash-can";
        //var boton = document.createElement("input");
        var atributo=document.createAttribute("type");
        var atributo2 = document.createAttribute("name");
        var atributo3 = document.createAttribute("style");
        atributo3.value = "color: red";
       
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
      
       
        papelera.setAttributeNode(atributo);
        papelera.setAttributeNode(atributo2);
        papelera.setAttributeNode(atributo3);
        
        if((elemento.children.length==0)&&(e.target.tagName==="SPAN")){
        elemento.appendChild(papelera);
    }

}

function eliminarPapelera(e){
    var elemento = e.target;
    var papelera=elemento.children[0];
    var retardo=setTimeout(function(){
        elemento.removeChild(papelera);
    }, 1000);  

}
