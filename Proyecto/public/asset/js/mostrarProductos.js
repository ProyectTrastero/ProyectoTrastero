/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

window.addEventListener("load", iniciar);
var elementos;


function iniciar(){
    elementos = document.getElementsByClassName("productos");
  
    for(i=0;i<=elementos.length;i++){
        elementos[i].addEventListener("click", mostrarProductos);
    }
 
}

function mostrarProductos(e){
    var idSeleccionado = e.target.getAttribute("id");
//    alert(idSeleccionado);
}


