window.addEventListener("load", iniciar);
var modal;
var elementoCorreo;
var ubicacionMensaje;
var botonVolver;
//Inicializamos las variables tras cargar la página y añadimos eventos correspondientes.
function iniciar(){
    modal = document.getElementById("comprobarContraseña");
    botonVolver=document.getElementById("volver");
    elementoCorreo = document.getElementById("correo");
    ubicacionMensaje = document.getElementById("mensaje");
    botonVolver.addEventListener("click", borrar);
    modal.addEventListener("click", recuperar);
}
//Recupera el correo para enviarlo con una petición AJAX
function recuperar(){
    var correo = elementoCorreo.value;
  
$(document).ready(function(){
            url="recuperarContraseña.php";
           
            $.ajax({
                type: "POST",
                url: url, 
                dataType: "json", 
                data: {correo: correo},
                //La petición Ajax nos devuelve el resultado de si el envío se ha realizado correctamente.
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
//Borra el correo actual del input.
function borrar(){
    ubicacionMensaje.innerHTML = "";
    elementoCorreo.value="";
}


