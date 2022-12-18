window.addEventListener("load", iniciar);
var modal;
var elementoCorreo;
var ubicacionMensaje;
var botonVolver;
function iniciar(){
    modal = document.getElementById("comprobarContraseña");
    botonVolver=document.getElementById("volver");
    elementoCorreo = document.getElementById("correo");
    ubicacionMensaje = document.getElementById("mensaje");
    botonVolver.addEventListener("click", borrar);
    modal.addEventListener("click", recuperar);
}

function recuperar(){
    var correo = elementoCorreo.value;
  
$(document).ready(function(){
            url="recuperarContraseña.php";
           
            $.ajax({
                type: "POST",
                url: url, 
                dataType: "json", 
                data: {correo: correo},
                success: function(result){
                    var mensaje=result.mensaje;
                    elementoCorreo.value="";
                    if(mensaje=="El campo correo es obligatorio."){
                        var atributo = document.createAttribute("style");
                        atributo.value="color: red";
                        ubicacionMensaje.setAttributeNode(atributo);
                    }else{
                        var atributo = document.createAttribute("style");
                        atributo.value="color: black";
                        ubicacionMensaje.setAttributeNode(atributo);
                    }    
                    ubicacionMensaje.innerHTML = mensaje;  

            }}); 
    });  
}

function borrar(){
    ubicacionMensaje.innerHTML = "";
    elementoCorreo.value="";
}


