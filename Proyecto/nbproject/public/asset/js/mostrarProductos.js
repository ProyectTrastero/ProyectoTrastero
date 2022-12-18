/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

window.addEventListener("load", iniciar);
var elementos;


function iniciar(){
    elementos = document.getElementsByClassName("productos");
  
    for(i=0;i<elementos.length;i++){
        elementos[i].addEventListener("click", mostrarProductos);
    }
 
}

function mostrarProductos(e){
    var idSeleccionado = e.target.getAttribute("name");
    $(document).ready(function(){
            url="verTrastero.php";
            $.ajax({
                type: "POST",
                url: url, 
                dataType: "json", 
                data: {id: idSeleccionado},
                success: function(result){
                    var listadoProductos=eval(result);
                    var listado=eval(listadoProductos);
                    var tabla=document.getElementById("cabecera");
                    var body=tabla.lastChild;
                    var filas= body.children;
                    if(filas.length>1){
                       
                        do{
                            var indice=filas.length;
                            var eliminado=filas[indice-1];
                            body.removeChild(eliminado);
                           
                        }while(filas.length>1)
                    }
                    for(i=0;i<listadoProductos.length;i++){
                        var columna=document.createElement("TR");
                        var fila1=document.createElement("TD");
                        var fila2=document.createElement("TD");
                        var fila3=document.createElement("TD");
                        
                        fila1.innerHTML = "22/01/2022";
                        fila2.innerHTML = listadoProductos[i].nombre;
                        fila3.innerHTML = listadoProductos[i].descripcion;
                    
//                        elemento.innerHTML=listadoBaldas[i];
                        columna.appendChild(fila1);
                        columna.appendChild(fila2);
                        columna.appendChild(fila3);
                        body.appendChild(columna);
                    }
                   
            }}); 
    }); 
}


