window.addEventListener("load", iniciar);
  
var idTrastero;
var infoModal;

function iniciar(){

  ////get idTrastero
  loadDoc('añadirProducto.php?getIdTrastero',getIdTrastero);
  var modal=document.getElementById("mostrarModal");
  infoModal=modal.value;
  habilitarModal();

  ////estanterias
  let idEstanteria= document.getElementById('selectEstanterias').value;
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

  ///baldas
  let idBalda = document.getElementById('selectBaldas').value;
  if (idBalda != "" ) {
    //enviamos el id de la balda seleccionada por default
    loadDoc('añadirProducto.php?idBalda=' + idBalda, setCajas);
  }

  document.getElementById('selectBaldas').addEventListener('change',function(){
    console.log(this.value);
    loadDoc('añadirProducto.php?idBalda=' + this.value, setCajas);
  })

  ////cajas sin asignar
  loadDoc('añadirProducto.php?getCajasSinAsignar', setCajasSinAsignar);
  

  ////radio buttons 
  let radios = document.getElementsByName('ubicacion');
  for (let i = 0; i < radios.length; i++) {
    const radio = radios[i];
    radio.addEventListener('change',(e)=>{
      showHide(e);
    })
  }
  
}

function loadDoc(url,cFunction){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function(){cFunction(this);}
    xhttp.open("GET",url,false );
    xhttp.send();
}

function getIdTrastero (xhttp){
  idTrastero = xhttp.responseText;
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
  //agregamos la opcion por defecto
  let opcionDefault = document.createElement('option');
  opcionDefault.value = 0;
  opcionDefault.innerText='Seleccione una opción';
  opcionDefault.setAttribute('selected','true');
  selectCajas.appendChild(opcionDefault);
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

function setCajasSinAsignar(xhttp){
  const selectCajasSinAsignar = document.getElementById('selectCajasSinAsignar');
  //primero eliminamos los elementos
  Array.from(selectCajasSinAsignar.childNodes).forEach(optionElement => {
    optionElement.remove();
  })
  //recibimos las cajas sin asignar
  let cajasSinAsignar = JSON.parse(xhttp.responseText);
  cajasSinAsignar.forEach(caja => {
    let cajaElement = document.createElement('option');
    cajaElement.value = caja.id;
    cajaElement.innerText = caja.nombre;
    selectCajasSinAsignar.appendChild(cajaElement);
  });
}


function showHide(e){
  let target = e.target;
  console.log(target);
  if(target.id=='radioUbicacionEstanteria'){
    document.getElementById('idUbicacionEstanteria').classList.remove('hide');
    document.getElementById('idUbicacionCajasSinAsignar').classList.add('hide');
    //habilitamos los select 
    document.getElementById('selectEstanterias').disabled = false;
    document.getElementById('selectBaldas').disabled = false;
    document.getElementById('selectCaja').disabled = false;
    //tambien desabilitamos los select para que no envien informacion
    document.getElementById('selectCajasSinAsignar').disabled = true;
    
  }else if(target.id == 'radioCajasSinAsignar'){
    document.getElementById('idUbicacionCajasSinAsignar').classList.remove('hide');
    document.getElementById('idUbicacionEstanteria').classList.add('hide');
    //habilitamos los select
    document.getElementById('selectCajasSinAsignar').disabled = false;
    //tambien desabilitamos los select para que no envien informacion
    document.getElementById('selectEstanterias').disabled = true;
    document.getElementById('selectBaldas').disabled = true;
    document.getElementById('selectCaja').disabled = true;
    
  }else if(target.id == 'radioSinAsignar'){
    document.getElementById('idUbicacionCajasSinAsignar').classList.add('hide');
    document.getElementById('idUbicacionEstanteria').classList.add('hide');
    //desabilitamos todos los select 
    document.getElementById('selectEstanterias').disabled = true;
    document.getElementById('selectBaldas').disabled = true;
    document.getElementById('selectCaja').disabled = true;
    document.getElementById('selectCajasSinAsignar').disabled = true;
  }
  
}

function habilitarModal(){
    if(infoModal=="si"){
       $("#staticBackdrop").modal("show");
    }
}
