window.addEventListener("load", iniciar);

var infoModal;

function iniciar() {
  // comprobamos si tenemos un alert
  if (document.getElementById('alertsPhp')) {
    // eliminamos los alerts en un tiempo establecido
    setTimeout(() => {
      document.getElementById('alertsPhp').remove();
    }, 5000);
  }

  //add events a los elementos
  addEventToElements();


}


function addEventToElements() {
  //añadimos event change a selectEstanterias
  //cuando seleccionamos una estanteria enviamos el id que hemos seleccionado al server
  document.getElementById('selectEstanterias').addEventListener('change', function () {
    loadDoc('añadirProducto.php?idEstanteria=' + this.value, setBaldasCajas);
  })

  //añadimos event change a selectBaldas
  document.getElementById('selectBaldas').addEventListener('change', function () {
    loadDoc('añadirProducto.php?idBalda=' + this.value, setCajas);
  })

  ////añadimos event change a los radio buttons 
  let radios = document.getElementsByName('ubicacion');
  for (let i = 0; i < radios.length; i++) {
    const radio = radios[i];
    radio.addEventListener('change', (e) => {
      showHide(e);
    })
  }

  ////añadimos event click a btn añadir etiqueta
  document.getElementById('añadirEtiqueta').addEventListener('mousedown', function () {
  let idEtiquetaSelected = document.getElementById('selectEtiquetas').value;
    loadDoc('añadirProducto.php?añadirEtiqueta=' + idEtiquetaSelected, añadirEtiqueta);
  })

  //añadimos event click a boton abrir modal eliminar etiqueta openEliminarEtiquetaModal
  document.getElementById('openEliminarEtiquetaModal').addEventListener('click',getEtiquetaOfSelect);

  //añadimos event click al boton eliminar etiqueta 
  document.getElementById('formEliminarEtiqueta').addEventListener('submit',(e)=>{
    e.preventDefault();
    eliminarEtiqueta();
  })

  //prevent event submit del form crearEtiqueta
  document.getElementById('formCrearEtiqueta').addEventListener('submit',(e)=>{
    e.preventDefault();
     //recuperamos el nombre de la etiqueta
     let nombreEtiqueta = document.getElementById('nombreEtiqueta').value;
     loadDoc('añadirProducto.php?crearEtiqueta=' + nombreEtiqueta, crearEtiqueta);
  })

  //prevent event submit del form crearEtiqueta
  document.getElementById('formAñadirProducto').addEventListener('submit',(e)=>{
    e.preventDefault();
    añadirProducto();
  })
  
}

function loadDoc(url, cFunction) {
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () { cFunction(this); }
  xhttp.open("GET", url, false);
  xhttp.send();
}

// POST method implementation:
async function postData(url = '', data = {}) {
  
  const response = await fetch(url, {
    method: 'POST',
    body: JSON.stringify(data), // datos que vamos a enviar
    headers: {
      'Content-Type': 'application/json'
    }
  });
  return response.json(); // datos que recibimos
}

// GET method implementation:
async function getData(url = '') {
  
  const response = await fetch(url, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    }
  });
  return response.json(); // datos que recibimos
}




function setBaldasCajas(xhttp) {
  //select en donde iran las baldas
  const selectBaldas = document.getElementById('selectBaldas');
  //select en donde iran las cajas que tienen ubicacion
  const selectCajas = document.getElementById('selectCaja');

  //eliminamos los elementos option del select baldas
  Array.from(selectBaldas.childNodes).forEach(optionElement => {
    optionElement.remove();
  })

  //eliminamos los elementos option del select cajas
  Array.from(selectCajas.childNodes).forEach(optionElement => {
    optionElement.remove();
  })

  //recibimos la respuesta
  let response = JSON.parse(xhttp.responseText);
  let baldas = response.baldas;
  let cajas = response.cajas;
  //creamos los elementos option en el select baldas
  baldas.forEach(balda => {
    baldaElement = document.createElement("option");
    baldaElement.value = balda.id;
    baldaElement.innerText = balda.nombre;
    selectBaldas.appendChild(baldaElement);
  });

  //si no tenemos cajas
  if (cajas.length == 0) {
    //agregamos la opcion si no hay cajas
    let opcionDefault = document.createElement('option');
    opcionDefault.value = 0;
    opcionDefault.innerText = 'No hay cajas';
    opcionDefault.setAttribute('selected', 'true');
    selectCajas.appendChild(opcionDefault);
  } else {
    //agregamos la opcion por defecto para el select cajas
    let opcionDefault = document.createElement('option');
    opcionDefault.value = 0;
    opcionDefault.innerText = 'No ubicar en caja';
    opcionDefault.setAttribute('selected', 'true');
    selectCajas.appendChild(opcionDefault);

    //creamos los elementos option en el select cajas
    cajas.forEach(caja => {
      let cajaElement = document.createElement('option');
      cajaElement.value = caja.id;
      cajaElement.innerText = caja.nombre;
      selectCajas.appendChild(cajaElement);
    })
  }


}

function setCajas(xhttp) {
  //select en donde iran las cajas que tienen ubicacion
  const selectCajas = document.getElementById('selectCaja');
  //eliminamos los elementos option del select cajas
  Array.from(selectCajas.childNodes).forEach(optionElement => {
    optionElement.remove();
  })
  //agregamos la opcion por defecto
  let opcionDefault = document.createElement('option');
  opcionDefault.value = 0;
  opcionDefault.innerText = 'No ubicar en caja';
  opcionDefault.setAttribute('selected', 'true');
  selectCajas.appendChild(opcionDefault);
  //recibimos las cajas
  let cajas = JSON.parse(xhttp.responseText);
  cajas.forEach(caja => {
    //creamos los elementos option
    let cajaElement = document.createElement('option');
    cajaElement.value = caja.id;
    cajaElement.innerText = caja.nombre;
    selectCajas.appendChild(cajaElement);
  })
}


function showHide(e) {
  let target = e.target;
  if (target.id == 'radioUbicacionEstanteria' && target.checked == true) {
    document.getElementById('idUbicacionEstanteria').classList.remove('hide');
    document.getElementById('idUbicacionCajasSinUbicar').classList.add('hide');
    //habilitamos los select 
    document.getElementById('selectEstanterias').disabled = false;
    document.getElementById('selectBaldas').disabled = false;
    document.getElementById('selectCaja').disabled = false;
    //tambien desabilitamos los select para que no envien informacion
    document.getElementById('selectCajasSinUbicar').disabled = true;

  } else if (target.id == 'radioCajasSinAsignar' && target.checked == true) {
    document.getElementById('idUbicacionCajasSinUbicar').classList.remove('hide');
    document.getElementById('idUbicacionEstanteria').classList.add('hide');
    //habilitamos los select
    document.getElementById('selectCajasSinUbicar').disabled = false;
    //tambien desabilitamos los select para que no envien informacion
    document.getElementById('selectEstanterias').disabled = true;
    document.getElementById('selectBaldas').disabled = true;
    document.getElementById('selectCaja').disabled = true;

  } else if (target.id == 'radioSinAsignar' && target.checked == true) {
    document.getElementById('idUbicacionCajasSinUbicar').classList.add('hide');
    document.getElementById('idUbicacionEstanteria').classList.add('hide');
    //desabilitamos todos los select 
    document.getElementById('selectEstanterias').disabled = true;
    document.getElementById('selectBaldas').disabled = true;
    document.getElementById('selectCaja').disabled = true;
    document.getElementById('selectCajasSinUbicar').disabled = true;
  }

}

function añadirEtiqueta(xhttp) {
  //value del select
  let idEtiquetaSelected = document.getElementById('selectEtiquetas').value;
  //si no recuperamos el id salimos
  if (idEtiquetaSelected == "" || idEtiquetaSelected === undefined || idEtiquetaSelected === null) {
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
  spanElement.id = idEtiquetaSelected;
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
  spanX.nodeType = 'button';
  //spanX.style='font-size: 0.9em ; margin-left: 0.4em';
  //añadimos el span x al span etiqueta
  document.getElementById(idEtiquetaSelected).appendChild(spanX);

  //añadimos event click a los span con la clase close-etiqueta
  let elementCloseEtiqueta = document.getElementsByClassName('close-etiqueta');
  for (let i = 0; i < elementCloseEtiqueta.length; i++) {
    const element = elementCloseEtiqueta[i];
    element.addEventListener('click', (e) => {
      //eliminamos elemento padre del span x 
      e.target.parentNode.remove();
    })
  }


  //creamos un string con los id de las etiquetas añadidas
  let stringEtiquetasAñadidas = "";
  for (let i = 0; i < etiquetasAñadidas.length; i++) {
    let etiqueta = etiquetasAñadidas[i];
    stringEtiquetasAñadidas += etiqueta.id + " ";
  }
  //guardamos la informacion en un input
  //eliminamos el input si esta creado
  if (document.getElementById('inputAñadirEtiquetas')) {
    document.getElementById('inputAñadirEtiquetas').remove();
  }
  //creamos un input con los id de las etiquetas añadidas
  let inputAñadirEtiquetas = document.createElement('input');
  inputAñadirEtiquetas.id = 'inputAñadirEtiquetas';
  inputAñadirEtiquetas.nodeType = 'text';
  inputAñadirEtiquetas.setAttribute('hidden', 'true');
  inputAñadirEtiquetas.name = 'inputAñadirEtiquetas';
  inputAñadirEtiquetas.value = stringEtiquetasAñadidas.trim();
  //element en donde ubicaremos el input
  let inputEtiquetas = document.getElementById('inputEtiquetas');
  document.getElementById('inputEtiquetas').appendChild(inputAñadirEtiquetas);


}

function crearEtiqueta(xhttp) {
  if (xhttp.statusText == 'OK') {
    //limpiamos el input
    document.getElementById('nombreEtiqueta').value = "";
    //recuperamos el mensaje
    let mensaje = JSON.parse(xhttp.responseText);
    
    createAlert(mensaje);

    //si etiqueta creada correctamente, recargamos select etiquetas
    if (mensaje['msj-type'] == 'success') {
      loadDoc('añadirProducto.php?getEtiquetas', getEtiquetas);
    }

  }


}

//recibimos las etiquetas del usuario
function getEtiquetas(xhttp) {
  //parse json la response
  let etiquetasToUpdate = JSON.parse(xhttp.responseText);
  //seleccionamos el select etiquetas
  let selectEtiquetas = document.getElementById('selectEtiquetas');
  //eliminamos las etiquetas
  Array.from(selectEtiquetas.childNodes).forEach(etiqueta => {
    //eliminamos las etiquetas
    etiqueta.remove();
  })

  //recorremos las etiquetas actualizadas
  etiquetasToUpdate.forEach(etiqueta => {
    //creamos element option
    let optionElement = document.createElement('option');
    optionElement.value = etiqueta.id;
    optionElement.innerText = etiqueta.nombre;
    //añadimos la etiqueta al select
    selectEtiquetas.appendChild(optionElement);
  })
}

function añadirProducto() {

  const formAñadirProducto = document.getElementById('formAñadirProducto');
  //obtenemos los datos del formulario como un objeto formData
  const formData = new FormData(formAñadirProducto);
  const data = { añadirProducto: '' };
  //convertimos los datos del formulario en un objeto json
  formData.forEach((value, key) => {
    data[key] = value;
  });
  postData('añadirProducto.php', data)
    .then(response => {
      //eliminamos el mensaje de error nombre invalido si existe
      if (document.getElementById('nombreInvalido').firstChild != null)
        document.getElementById('nombreInvalido').firstChild.remove();

        //si no se ha especificado un nombre al producto
      if (response.error == 'nombreInvalido') {
        //div para mostrar error
        let divElement = document.createElement('div');
        divElement.classList.add('textError');
        divElement.classList.add('form-text');
        divElement.classList.add('p-1');
        divElement.classList.add('text-start');

        divElement.innerText = 'Ingresa un nombre al producto.';

        document.getElementById('nombreInvalido').appendChild(divElement);
        return;
      }

      //creamos un div que sera el alert
      createAlert(response);

      //si producto añadido correctamente 
      if (response['msj-type'] == 'success') {
        //limpiamos el form
        formAñadirProducto.reset();
        //borramos las etiquetas
        const etiquetasProducto = document.getElementById('etiquetasProducto');
        Array.from(etiquetasProducto.childNodes).forEach(element => {
          element.remove();
        })

        //ubicacion por defecto
        document.getElementById('idUbicacionCajasSinUbicar').classList.add('hide');
        document.getElementById('idUbicacionEstanteria').classList.add('hide');
        //desabilitamos todos los select 
        document.getElementById('selectEstanterias').disabled = true;
        document.getElementById('selectBaldas').disabled = true;
        document.getElementById('selectCaja').disabled = true;
        document.getElementById('selectCajasSinUbicar').disabled = true;
      }
    })
    .catch(error => console.error('Error:', error));
}

//obtenemos el nombre de la etiqueta que se desea eliminar
function getEtiquetaOfSelect(){
  
  document.getElementById('nombreEtiquetaSelect').innerText = document.getElementById('selectEtiquetas').selectedOptions[0].innerText;               
  
}

//enviamos id de la etiqueta que vamos a eliminar
function eliminarEtiqueta(){
  const idEtiqueta = document.getElementById('selectEtiquetas').selectedOptions[0].value;
  getData('añadirProducto.php?idEliminarEtiqueta=' + idEtiqueta)
    .then(response=>{
      console.log(response);
      //creamos el alert
      createAlert(response);
      
      //si se ha eliminado la etiqueta
      if(response['msj-type']=='success'){
        //copia de los options
        const selectEtiquetas = Array.from(document.getElementById('selectEtiquetas'));
        //eliminamos los element option etiquetas
          Array.from(document.getElementById('selectEtiquetas').childNodes).forEach(etiqueta => {
            //eliminamos las etiquetas
            etiqueta.remove();
          })
        //recorremos elements options del select etiquetas para eliminar del array la etiqueta eliminada
        for (let i = 0; i < selectEtiquetas.length; i++) {
          const element = selectEtiquetas[i];
          if(element.value == idEtiqueta){
            //eliminamos element option
            selectEtiquetas.splice(i,1);
            break;
          }
        }
        //actualizamos el select etiquetas
        //recorremos las etiquetas actualizadas
        selectEtiquetas.forEach(etiqueta => {
          //creamos element option
          let optionElement = document.createElement('option');
          optionElement.value = etiqueta.value;
          optionElement.innerText = etiqueta.innerText;
          //añadimos la etiqueta al select
          document.getElementById('selectEtiquetas').appendChild(optionElement);
        })

        //recorremos las etiquetas que vamos a añadir
        for (let i = 0; i < document.getElementById('etiquetasProducto').childNodes.length; i++) {
          const element = document.getElementById('etiquetasProducto').childNodes[i];
          if(element.id == idEtiqueta){
            element.remove();
            break;
          }
          
        }

        let etiquetasAñadidas = document.getElementById('etiquetasProducto').childNodes;
        
        //actualizamos input con id de las etiquetas
        //creamos un string con los id de las etiquetas añadidas
        let stringEtiquetasAñadidas = "";
        for (let i = 0; i < etiquetasAñadidas.length; i++) {
          let etiqueta = etiquetasAñadidas[i];
          stringEtiquetasAñadidas += etiqueta.id + " ";
        }
        //eliminamos el input si esta creado
        if (document.getElementById('inputAñadirEtiquetas')) {
          document.getElementById('inputAñadirEtiquetas').remove();
        }
        //creamos un input con los id de las etiquetas añadidas
        let inputAñadirEtiquetas = document.createElement('input');
        inputAñadirEtiquetas.id = 'inputAñadirEtiquetas';
        inputAñadirEtiquetas.nodeType = 'text';
        inputAñadirEtiquetas.setAttribute('hidden', 'true');
        inputAñadirEtiquetas.name = 'inputAñadirEtiquetas';
        inputAñadirEtiquetas.value = stringEtiquetasAñadidas.trim();
        //element en donde ubicaremos el input
        let inputEtiquetas = document.getElementById('inputEtiquetas');
        document.getElementById('inputEtiquetas').appendChild(inputAñadirEtiquetas);
      }   
    })
    .catch(error => console.error('Error:', error));
}

function createAlert(mensaje){
  //creamos un div que sera el alert
  let divElement = document.createElement('div');

  //añadimos clases al div
  divElement.classList.add('alert');
  
  
  divElement.classList.add('alert-' + mensaje['msj-type']);
  divElement.innerHTML = mensaje['msj-content'];

  //añadimos el div alert
  document.getElementById('alerts').appendChild(divElement);
  
  //añadimos una transicion a el alert despues de un tiempo establecido
  setTimeout(() => {
    divElement.classList.add('deleteAlert');
    
  }, 3000);

  //eliminamos el alert despues de acabada la transicion
  divElement.addEventListener('transitionend',()=>{
    divElement.remove();
  })
}



