window.addEventListener("load", iniciar);
  var idEstanteria="";


function iniciar(){
  
  idEstanteria= document.getElementById('selectEstanterias').value;
  if(idEstanteria != ""){
    //enviamos el id de la estanteria seleccionada por default
    loadDoc('añadirProducto.php?idEstanteria=' + idEstanteria, setBaldas);
  }
  //cuando seleccionamos una estanteria enviamos el id que hemos seleccionado al server
  document.getElementById('selectEstanterias').addEventListener('change',function(){
    console.log(this.value);
    loadDoc('añadirProducto.php?idEstanteria=' + this.value , setBaldas);

    let baldaSelected = document.getElementById('selectBaldas')[ document.getElementById('selectBaldas').selectedIndex].value;
    loadDoc('añadirProducto.php?idBalda=' + baldaSelected, setCajas);
  })

  let idBalda = document.getElementById('selectBaldas').value;
  if (idBalda != "" ) {
    //enviamos el id de la balda seleccionada por default
    loadDoc('añadirProducto.php?idBalda=' + idBalda, setCajas);
  }

  document.getElementById('selectBaldas').addEventListener('change',function(){
    console.log(this.value);
    loadDoc('añadirProducto.php?idBalda=' + this.value, setCajas);
  })

}

function loadDoc(url,cFunction){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function(){cFunction(this);}
    xhttp.open("GET",url,false );
    xhttp.send();
}

function setBaldas(xhttp){
  const selectBaldas = document.getElementById('selectBaldas');
  //primero eliminamos los elementos
  Array.from(selectBaldas.childNodes).forEach(optionElement =>{
    optionElement.remove();
  })
  console.log(xhttp);
  //recibimos las baldas 
  
  let baldas = JSON.parse(xhttp.responseText);
  baldas.forEach(balda => {
  //creamos los elementos option 
  baldaElement = document.createElement("option");
  baldaElement.value = balda.id;
  baldaElement.innerText = balda.nombre;
  selectBaldas.appendChild(baldaElement);

  });
  
  
}

function setCajas (xhttp){
  const selectCajas = document.getElementById('selectCaja');
  //primero eliminamos los elementos
  Array.from(selectCajas.childNodes).forEach(optionElement => {
    optionElement.remove();
  })
  console.log(xhttp);
  //recibimos las cajas
  let cajas = JSON.parse(xhttp.responseText);
  cajas.forEach(caja=>{
    //creamos los elementos option
    let cajaElement = document.createElement('option');
    cajaElement.value = caja.id;
    cajaElement.innerText = caja.nombre;
    selectCajas.appendChild(cajaElement);
  })
}
