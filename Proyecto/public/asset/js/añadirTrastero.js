window.addEventListener("load", iniciar);
var ocultos;
var antiguoNombre;
var idElemento;
var primerElemento;
var guardado;
var infoModal;
var infoModal2;
var infoModal3;
function iniciar(){
    var tipo = document.getElementById("guardadoModificado");
    var modal=document.getElementById("mostrarModal");
    infoModal=modal.value;
    var modal2=document.getElementById("mostrarModal2");
    infoModal2=modal2.value;
    var modal3=document.getElementById("mostrarModal3");
    infoModal3=modal3.value;
    guardado=tipo.getAttribute("value");
    ocultos= document.getElementsByClassName("papeleraOculta");
    habilitarmodal();
    habilitarmodal();
    deshabilitarBotones();
    if(guardado=="false"){
        for(i=0;i<ocultos.length;i++){
        ocultos[i].addEventListener("mouseover", añadirPapelera);
        ocultos[i].addEventListener("mouseout", eliminarPapelera);
        ocultos[i].addEventListener("click", habilitarEdicion);
        ocultos[i].addEventListener("blur", deshabilitarEdicion);
        }
    }  
}

function habilitarmodal(){
    if(infoModal=="si"){
       $("#staticBackdrop1").modal("show");
    }
    
    if(infoModal2=="si"){
       $("#staticBackdrop").modal("show");
    }
    if(infoModal3=="si"){
       $("#staticBackdrop2").modal("show");
    }
    
   
}

function deshabilitarBotones(){
    if(guardado=="true"){
        var botones = document.getElementsByTagName("button");
        var botones = document.getElementsByTagName("button");
        for(i=0;i<botones.length;i++){
            var nombreValue=botones[i].innerHTML;
            if(nombreValue!="Volver"){
            var nombreValue=botones[i].innerHTML;
            if(nombreValue!="Volver"){
                 botones[i].setAttribute("disabled", "true");
            }
           
           
        }
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
