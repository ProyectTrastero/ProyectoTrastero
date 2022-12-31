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
    var tipo = e.target.getAttribute("id");
    $(document).ready(function(){
            url="verTrastero.php";
            $.ajax({
                type: "POST",
                url: url, 
                dataType: "json", 
                data: {id: idSeleccionado, tipo: tipo},
                success: function(result){
                    var listadoProductos=eval(result);
                    var listado=eval(listadoProductos);
                    var tabla=document.getElementById("vtTable");
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
                        var fila=document.createElement("tr");
                        var columna1=document.createElement("td");
                        var columna2=document.createElement("td");
                        var columna3=document.createElement("td");
                        var columna4=document.createElement("td");
                        
                        columna1.setAttribute("class", "vtTdFecha");
                        columna2.setAttribute("class", "vtTdNombre");
                        columna3.setAttribute("class", "vtTdDescripcion");
                        columna4.setAttribute("class", "vtTdUbicacion");
                        
                        fila.setAttribute("class", "vtTr")    
                        
                        columna1.innerHTML = listadoProductos[i].fechaingreso;
                        columna2.innerHTML = listadoProductos[i].nombre;
                        columna3.innerHTML = listadoProductos[i].descripcion;
                        var numEstanteria=listadoProductos[i].estanteria;
                        var numBalda = listadoProductos[i].balda;
                        var numCaja = listadoProductos[i].caja;
                        
                        columna4.innerHTML = "Estanteria "+ numEstanteria+" - Balda "+numBalda+" - Caja "+numCaja;
//                        elemento.innerHTML=listadoBaldas[i];
                        fila.appendChild(columna1);
                        fila.appendChild(columna2);
                        fila.appendChild(columna3);
                        fila.appendChild(columna4);
                        body.appendChild(fila);
                    }
                   
            }}); 
    }); 
}


