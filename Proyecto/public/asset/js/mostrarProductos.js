/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

window.addEventListener("load", iniciar);
var elementos;
var ocultos;

//Función que se carga tras cargar la página.
function iniciar(){
    elementos = document.getElementsByClassName("productos");
    ocultos= document.getElementsByClassName("ocultos");
    for(i=0;i<ocultos.length;i++){
        //A cada elemento le añadimos los eventos correspondientes.
        ocultos[i].addEventListener("mouseover", añadirOjo);
    }  
    for(i=0;i<elementos.length;i++){
        elementos[i].addEventListener("click", mostrarProductos);
    }
 
}

//Crea la tabla donde se muestran los productos. 
//Obtenemos el id seleccionado y por ajax hacemos una peticíon a verTrastero.php
//que nos responde con el listado de productos de esa ubicación.
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
                    var anotacion = document.getElementById("anotacion");
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
                    if(listadoProductos.length==0){
                        anotacion.innerHTML="";
                    }else{
                        anotacion.innerHTML="*<sub>1</sub> Estanteria *<sub>2</sub> Balda *<sub>3</sub> Caja";
                    }
                    //Por cada elemento creamos una fila en la tabla
                    for(i=0;i<listadoProductos.length;i++){
                        var fila=document.createElement("tr");
                        var columna1=document.createElement("td");
                        var columna2=document.createElement("td");
                        var columna3=document.createElement("td");
                        var columna4=document.createElement("td");
                        //Le añadimos los atributos correspondientes.
                        columna1.setAttribute("class", "vtTdFecha");
                        columna2.setAttribute("class", "vtTdNombre");
                        columna3.setAttribute("class", "vtTdDescripcion");
                        columna4.setAttribute("class", "vtTdUbicacion");
                        
                        fila.setAttribute("class", "vtTr")    
                        //Escribimos cada celda con cada uno de los campos del producto recuperado.
                        columna1.innerHTML = listadoProductos[i].fechaingreso;
                        columna2.innerHTML = listadoProductos[i].nombre;
                        columna3.innerHTML = listadoProductos[i].descripcion;
                        var nomEstanteria=listadoProductos[i].estanteria;
                        var nomBalda = listadoProductos[i].balda;
                        var nomCaja = listadoProductos[i].caja;
                        
                        columna4.innerHTML = "<b>*E:</b> "+ nomEstanteria+"<b> *B:</b> "+nomBalda+"<b>  *C:</b> "+nomCaja;
                        //Añadimos a cada fila las celdas creadas
                        fila.appendChild(columna1);
                        fila.appendChild(columna2);
                        fila.appendChild(columna3);
                        fila.appendChild(columna4);
                        //Añadimos toda la tabla creada.
                        body.appendChild(fila);
                    }
                   
            }}); 
    }); 
}

//Función que añade color al elemento para ser visible y luego se lo quita con un retardo.
function añadirOjo(e){
        var oculto = e.target;
        var elemento= oculto.nextElementSibling;
        var color=elemento.getAttribute("style");
        if(color=="color: rgb(255,255,255,0)"){
        elemento.setAttribute("style","color: rgb(28, 87, 236, 0.8)");
        var retardo=setTimeout(function(){
            eliminado = true;
            elemento.setAttribute("style","color: rgb(255,255,255,0)"); 
        }, 2000); 
    }
       
}



