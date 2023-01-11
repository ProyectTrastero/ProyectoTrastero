window.addEventListener("load", iniciar);
var ocultos;
var antiguoNombre;
var idElemento;
var primerElemento;
var guardado;
var infoModal;
var infoModal2;
var infoModal3;
var editable;
var visible;
//Inicializa las variables tras haber cargado la página.
function iniciar(){
    visible=false;
    var tipo = document.getElementById("guardadoModificado");
    var modal=document.getElementById("mostrarModal");
    infoModal=modal.value;
    var modal2=document.getElementById("mostrarModal2");
    infoModal2=modal2.value;
    guardado=tipo.getAttribute("value");
    ocultos= document.getElementsByClassName("papeleraOculta");
    editable= document.getElementsByClassName("editable")
    habilitarmodal();
    habilitarmodal();
    deshabilitarBotones();
    deshabilitarPrimeraBalda();
    if(guardado=="false"){
        for(i=0;i<ocultos.length;i++){
            var clase = ocultos[i].getAttribute("class");
            if(clase!="papeleraOculta primerabalda fa-sharp fa-solid fa-trash-can"){
                editable[i].addEventListener("mouseover", añadirPapelera);
            }
        editable[i].addEventListener("click", habilitarEdicion);
        editable[i].addEventListener("blur", deshabilitarEdicion);
        }
    }  
}
//Si el valor de la variable recogida tiene como valor "si" visualia el modal que le corresponde. 
function habilitarmodal(){
    if(infoModal=="si"){
       $("#staticBackdrop1").modal("show");
    }
    
    if(infoModal2=="si"){
       $("#staticBackdrop").modal("show");
    }
    
   
}

function deshabilitarPrimeraBalda(){
    var primeros = document.getElementsByClassName("primerabalda");
    for(i=0;i<primeros.length;i++){
        primeros[i].setAttribute("disabled", "true");
    }
}

//Tras presionar botón guardar desabilita todos los botones exceptuando el de volver.
function deshabilitarBotones(){
    if(guardado=="true"){
        var botones = document.getElementsByTagName("button");
        for(i=0;i<botones.length;i++){
            var nombreValue=botones[i].innerHTML;
            if(nombreValue!="Volver"){
                 botones[i].setAttribute("disabled", "true");
            } 
        }
    }
}

//Desabilita la edición hasta nuevo click y hace una petición ajax con el nombre actual del elemento.
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
                    nombre = nombre.trim();
                    //Si la respuesta a la peticíón es false se recupera el antiguo nombre y se avisa con un alert.
                    if(!respuesta){
                        elemento.innerText = antiguoNombre;
                        alert ("Ya existe un elemento con ese nombre.");
                    }
                    //Si el nuevo nombre está vacío mantenemos el antiguo.
                    if(nombre==""){
                        elemento.innerText = antiguoNombre;   
                    }              
            }}); 
    });  
    editando = false;
    
}

//Habilita la edición del nombre de cada elemento.
function habilitarEdicion(e){
    var elemento = e.target;
    primerElemento = elemento.previousElementSibling;
    idElemento = primerElemento.getAttribute("value");
    antiguoNombre = elemento.innerText;
    elemento.setAttribute("contenteditable", "true");   
}
    
//Añade la papelera para poder eliminar el elemento seleccionado.   
function añadirPapelera(e){
        var elemento = e.target;
        var papelera = elemento.nextElementSibling;
//        var clase = papelera.get
        papelera.setAttribute("style","color: rgb(236, 28, 36, 0.8)"); 
        var retardo=setTimeout(function(){
            papelera.setAttribute("style","color: rgb(255,255,255,0)");
        }, 2000); 
        
}

