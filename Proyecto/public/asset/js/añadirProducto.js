window.addEventListener("load", iniciar);
  
var idTrastero;
var infoModal;

function iniciar(){

  ////get idTrastero
  loadDoc('añadirProducto.php?getIdTrastero',getIdTrastero);

  ////estanterias
  let idEstanteria= document.getElementById('selectEstanterias').value;
  if(idEstanteria != ""){
    //enviamos el id de la estanteria seleccionada por default
    loadDoc('añadirProducto.php?idEstanteria=' + idEstanteria, setBaldas);
  }
  

  ///baldas
  let idBalda = document.getElementById('selectBaldas').value;
  if (idBalda != "" ) {
    //enviamos el id de la balda seleccionada por default
    loadDoc('añadirProducto.php?idBalda=' + idBalda, setCajas);
  }

  ////cajas sin asignar
  loadDoc('añadirProducto.php?getCajasSinAsignar', setCajasSinAsignar);
  
  //add events a los elementos
  addEventToElements();
  
  
}


function addEventToElements (){
  //añadimos event change a selectEstanterias
  //cuando seleccionamos una estanteria enviamos el id que hemos seleccionado al server
  document.getElementById('selectEstanterias').addEventListener('change',function(){
    loadDoc('añadirProducto.php?idEstanteria=' + this.value , setBaldas);

    //enviamos el id de la balda
    let baldaSelected = document.getElementById('selectBaldas').value;
    loadDoc('añadirProducto.php?idBalda=' + baldaSelected, setCajas);
  })

  //añadimos event change a selectBaldas
  document.getElementById('selectBaldas').addEventListener('change',function(){
    console.log(this.value);
    loadDoc('añadirProducto.php?idBalda=' + this.value, setCajas);
  })

  ////añadimos event click a los radio buttons 
  let radios = document.getElementsByName('ubicacion');
  for (let i = 0; i < radios.length; i++) {
    const radio = radios[i];
    radio.addEventListener('click',(e)=>{
      showHide(e);
    })
  }

  ////añadimos event click a btn añadir etiqueta
  document.getElementById('añadirEtiqueta').addEventListener('mousedown',function(){
    
    let idEtiquetaSelected = document.getElementById('selectEtiquetas').value;
    loadDoc('añadirProducto.php?añadirEtiqueta=' + idEtiquetaSelected, añadirEtiqueta);
  })

 

  //añadimos event click a el boton añadir del modal para añadir etiquetas
  document.getElementById('crearEtiqueta').addEventListener('click',()=>{
    //recuperamos el nombre de la etiqueta
    let nombreEtiqueta = document.getElementById('nombreEtiqueta').value;
    loadDoc('añadirProducto.php?crearEtiqueta=' + nombreEtiqueta, crearEtiqueta )
  })

  
  
}

function loadDoc(url,cFunction){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function(){cFunction(this);}
    xhttp.open("GET",url,false );
    xhttp.send();
}

function getIdTrastero (xhttp){
  //recibimos el id del trastero
  idTrastero = xhttp.responseText;
}

function setBaldas(xhttp){
  //select en donde iran las baldas
  const selectBaldas = document.getElementById('selectBaldas');
  //primero eliminamos los elementos
  Array.from(selectBaldas.childNodes).forEach(optionElement =>{
    optionElement.remove();
  })

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
  //select en donde iran las cajas que tienen ubicacion
  const selectCajas = document.getElementById('selectCaja');
  //primero eliminamos los elementos
  Array.from(selectCajas.childNodes).forEach(optionElement => {
    optionElement.remove();
  })
  //agregamos la opcion por defecto
  let opcionDefault = document.createElement('option');
  opcionDefault.value = 0;
  opcionDefault.innerText='No ubicar en caja';
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
  if(target.id=='radioUbicacionEstanteria' && target.checked == true){
    document.getElementById('idUbicacionEstanteria').classList.remove('hide');
    document.getElementById('idUbicacionCajasSinAsignar').classList.add('hide');
    //habilitamos los select 
    document.getElementById('selectEstanterias').disabled = false;
    document.getElementById('selectBaldas').disabled = false;
    document.getElementById('selectCaja').disabled = false;
    //tambien desabilitamos los select para que no envien informacion
    document.getElementById('selectCajasSinAsignar').disabled = true;
    
  }else if(target.id == 'radioCajasSinAsignar' && target.checked == true){
    document.getElementById('idUbicacionCajasSinAsignar').classList.remove('hide');
    document.getElementById('idUbicacionEstanteria').classList.add('hide');
    //habilitamos los select
    document.getElementById('selectCajasSinAsignar').disabled = false;
    //tambien desabilitamos los select para que no envien informacion
    document.getElementById('selectEstanterias').disabled = true;
    document.getElementById('selectBaldas').disabled = true;
    document.getElementById('selectCaja').disabled = true;
    
  }else if(target.id == 'radioSinAsignar' && target.checked == true){
    document.getElementById('idUbicacionCajasSinAsignar').classList.add('hide');
    document.getElementById('idUbicacionEstanteria').classList.add('hide');
    //desabilitamos todos los select 
    document.getElementById('selectEstanterias').disabled = true;
    document.getElementById('selectBaldas').disabled = true;
    document.getElementById('selectCaja').disabled = true;
    document.getElementById('selectCajasSinAsignar').disabled = true;
  }
  
}

function añadirEtiqueta(xhttp){
  //value del select
  let idEtiquetaSelected = document.getElementById('selectEtiquetas').value;
  //si no recuperamos el id salimos
  if(idEtiquetaSelected == "" || idEtiquetaSelected === undefined || idEtiquetaSelected === null){
    return;
  }
  //texto del select 
  let textEtiquetaSelected = document.getElementById('selectEtiquetas').selectedOptions[0].innerText;
  
  //verificamos que la etiqueta no este añadida
  let etiquetasAñadidas = document.getElementById('etiquetasProducto').childNodes;
  for (let i = 0; i < etiquetasAñadidas.length; i++) {
    let etiqueta = etiquetasAñadidas[i];
    if (etiqueta.id == idEtiquetaSelected) {
      return;
    }
  }
  //creasmos elemento span
  let spanElement = document.createElement('span');
  spanElement.innerText = textEtiquetaSelected;
  spanElement.id=idEtiquetaSelected;
  spanElement.classList.add('etiqueta');
  spanElement.classList.add('d-inline-flex');
  spanElement.classList.add('align-items-center');
  spanElement.classList.add('mb-1')
  //añadimos el span etiqueta a el div etiquetas producto
  document.getElementById('etiquetasProducto').appendChild(spanElement);
  //creamos elemento span que sera la x
  let spanX = document.createElement('span');
  spanX.classList.add('btn-close');
  spanX.classList.add('close-etiqueta');
  spanX.nodeType='button';
  //spanX.style='font-size: 0.9em ; margin-left: 0.4em';
  //añadimos el span x al span etiqueta
  document.getElementById(idEtiquetaSelected).appendChild(spanX);

  //añadimos event click a los span con la clase close-etiqueta
  let elementCloseEtiqueta = document.getElementsByClassName('close-etiqueta');
  for (let i = 0; i < elementCloseEtiqueta.length; i++) {
    const element = elementCloseEtiqueta[i];
    element.addEventListener('click',(e)=>{
      //eliminamos elemento padre del span x 
      e.target.parentNode.remove();
    })
  }
  
  
  //creamos un string con los id de las etiquetas añadidas
  let stringEtiquetasAñadidas="";
  for (let i = 0; i < etiquetasAñadidas.length; i++) {
    let etiqueta = etiquetasAñadidas[i];
    stringEtiquetasAñadidas += etiqueta.id + " ";
  }
  //guardamos la informacion en un input
  //eliminamos el input si esta creado
  if(document.getElementById('inputAñadirEtiquetas')){
    document.getElementById('inputAñadirEtiquetas').remove();
  }
  //creamos un input con los id de las etiquetas añadidas
  let inputAñadirEtiquetas = document.createElement('input');
  inputAñadirEtiquetas.id='inputAñadirEtiquetas';
  inputAñadirEtiquetas.nodeType='text';
  inputAñadirEtiquetas.setAttribute('hidden','true');
  inputAñadirEtiquetas.name='inputAñadirEtiquetas';
  inputAñadirEtiquetas.value=stringEtiquetasAñadidas;
  //element en donde ubicaremos el input
  let inputEtiquetas= document.getElementById('inputEtiquetas');
  document.getElementById('inputEtiquetas').appendChild(inputAñadirEtiquetas);


}

function crearEtiqueta(xhttp){
  if(xhttp.statusText=='OK'){
    //limpiamos el input
    document.getElementById('nombreEtiqueta').value="";
    //recuperamos el mensaje
    let mensaje = JSON.parse(xhttp.responseText);
    //creamos un div que sera el alert
    let divElement = document.createElement('div');
    
    //añadimos clases alert
    divElement.classList.add('alert');
    divElement.classList.add('alert-dismissible');
    divElement.classList.add('fade');
    divElement.classList.add('show');
    divElement.classList.add('alert-' + mensaje['msj-type']);

    divElement.role='alert';
    divElement.innerHTML=mensaje['msj-content'];

    //eliminamos el span despues de un tiempo establecido
    //pendiente de mejorar
    setTimeout(() => {
      divElement.classList.remove('show');
    }, 5000);
    divElement.addEventListener('transitionend',()=>{
      divElement.remove();
    })
    //añadimos tiempo para que desaparesca el alert
    
    //añadimos el div alert
    document.getElementById('alerts').appendChild(divElement);
    document.getElementById('alerts').style.overflowX='hidden';
    
    
    //creamos span x
    let spanX = document.createElement('span');
    spanX.classList.add('btn-close');
    spanX.setAttribute('data-bs-dismiss','alert');
    spanX.nodeType='button';

    //añadimos el span x al div
    divElement.appendChild(spanX);

    //si etiqueta creada correctamente, recargamos select etiquetas
    if (mensaje['msj-type']=='success') {
      loadDoc('añadirProducto.php?getEtiquetas', getEtiquetas);
    }
    
  }
  
  
}

//recibimos las etiquetas del usuario
function getEtiquetas(xhttp){
  //parse json la response
  let etiquetasToUpdate = JSON.parse(xhttp.responseText);
  //seleccionamos el select etiquetas
  let selectEtiquetas = document.getElementById('selectEtiquetas');
  //eliminamos las etiquetas
  Array.from(selectEtiquetas.childNodes).forEach(etiqueta=>{
    //eliminamos las etiquetas
    etiqueta.remove();
  })
  
  //recorremos las etiquetas actualizadas
  etiquetasToUpdate.forEach(etiqueta=>{
    //creamos element option
    let optionElement = document.createElement('option');
    optionElement.value = etiqueta.id;
    optionElement.innerText = etiqueta.nombre;
    //añadimos la etiqueta al select
    selectEtiquetas.appendChild(optionElement);
  })


  
}



