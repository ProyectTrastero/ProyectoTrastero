window.addEventListener("load", iniciar);
var ocultos;
var antiguoNombre;
var idElemento;
var primerElemento;
function iniciar(){
    ocultos= document.getElementsByClassName("papeleraOculta");
    
    for(i=0;i<ocultos.length;i++){
        ocultos[i].addEventListener("mouseover", añadirPapelera);
        ocultos[i].addEventListener("mouseout", eliminarPapelera);
        ocultos[i].addEventListener("click", habilitarEdicion);
        ocultos[i].addEventListener("blur", deshabilitarEdicion);
    }
}

function deshabilitarEdicion(e){
    var elemento = e.target;
    elemento.setAttribute("contenteditable", "false");
    
    var nombre = elemento.innerText;
    $(document).ready(function(){
            url="modificarNombreAjax.php";
            
            $.ajax({
                type: "POST",
                url: url, 
                dataType: "json", 
                data: {nuevoNombre: nombre, nombre: antiguoNombre, id: idElemento},
                success: function(result){
                    var respuesta = result.cambiado;
                    var antiguoNombre = result.nombre;
                    if(!respuesta){
                        alert ("Ya existe un elemento con ese nombre.");
                        elemento.innerText = antiguoNombre;
                    }
                     
            }}); 
    });  
    
    
}

function habilitarEdicion(e){
    var elemento = e.target;
    primerElemento = elemento.previousElementSibling;
    idElemento = primerElemento.getAttribute("value");
    antiguoNombre = elemento.innerText;
    elemento.setAttribute("contenteditable", "true");
    
    
}
    
    
function añadirPapelera(e){
        var elemento = e.target;
        var papelera= document.createElement("button");
        papelera.className="fa-sharp fa-solid fa-trash-can";
        var atributo=document.createAttribute("type");
        var atributo2 = document.createAttribute("name");
        var atributo3 = document.createAttribute("style");
        atributo3.value = "color: red; border: white";
       
        var elementoAnterior=elemento.previousElementSibling;
        var nombre = elementoAnterior.getAttribute("name");
        
        atributo.value = "submit";
        if(nombre.includes("Estanteria")){
            atributo2.value = "eliminarEstanteria";
        }
         if(nombre.includes("Balda")){
            atributo2.value = "eliminarBalda";
        }
         if(nombre.includes("Caja")){
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
