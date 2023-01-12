window.addEventListener('load', iniciar);

//borra esto xd era para hacer el commit

function iniciar() {
	checkSelectRadio();
	//creamos input en el que guardamos los id de las etiquetas que vamos a asignar al producto
	crearInputEtiquetasAñadidas();
	//add events a los elementos
	addEventToElements();
}

function addEventToElements() {
	//add event click a los radios
	const radios = document.getElementsByName('ubicacion');
	for (let i = 0; i < radios.length; i++) {
		const radio = radios[i];
		radio.addEventListener('click', (e) => {
			cambiarUbicacion(e.target.id);
		});
	}

	//añadimos event click a los span con la clase close-etiqueta
	let elementsCloseEtiquetas = Array.from(document.getElementsByClassName('close-etiqueta'));
	elementsCloseEtiquetas.forEach(elementClose => {
		elementClose.addEventListener('click', (e) => {
			//eliminamos el elemento padre del span close
			e.target.parentNode.remove();
			crearInputEtiquetasAñadidas();
		})
	})
	
	//event change select estanterias
	document.getElementById('selectEstanterias').addEventListener('change', getBaldasCajas);
	//event change select baldas
	document.getElementById('selectBaldas').addEventListener('change', getCajas);

	//event submit boton crear etiqueta del modal
	document.getElementById('formCrearEtiqueta').addEventListener('submit', (e)=>{
		e.preventDefault();
		crearEtiqueta();
	})
	//event click boton añadir etiqueta
	document.getElementById('añadirEtiqueta').addEventListener('click', añadirEtiqueta);
	//event click boton modificar producto
	document.getElementById('formProducto').addEventListener('submit', (e)=>{
		e.preventDefault();
		modificarProducto();
	});
	//añadimos event click a boton abrir modal eliminar etiqueta openEliminarEtiquetaModal
	document.getElementById('openEliminarEtiquetaModal').addEventListener('click',getEtiquetaOfSelect);
	//añadimos event submit al boton eliminar etiqueta 
	document.getElementById('formEliminarEtiqueta').addEventListener('submit',(e)=>{
		e.preventDefault();
		eliminarEtiqueta();
	  })

}


function checkSelectRadio() {
	let radios = document.getElementsByName('ubicacion');
	let idRadio = "";
	for (let i = 0; i < radios.length; i++) {
		const radio = radios[i];
		if (radio.checked == true) {
			idRadio = radio.id;
		}
	}

	//escondemos y mostramos partes del front
	cambiarUbicacion(idRadio);
}

function cambiarUbicacion(idRadio) {


	if (idRadio == 'radioUbicacionEstanteria') {
		document.getElementById('idUbicacionEstanteria').classList.remove('hide');
		document.getElementById('idUbicacionCajasSinUbicar').classList.add('hide');
		//habilitamos los select 
		document.getElementById('selectEstanterias').disabled = false;
		document.getElementById('selectBaldas').disabled = false;
		document.getElementById('selectCaja').disabled = false;
		//tambien desabilitamos los select para que no envien informacion
		document.getElementById('selectCajasSinUbicar').disabled = true;


	} else if (idRadio == 'radioCajasSinAsignar') {
		document.getElementById('idUbicacionCajasSinUbicar').classList.remove('hide');
		document.getElementById('idUbicacionEstanteria').classList.add('hide');
		//habilitamos los select
		document.getElementById('selectCajasSinUbicar').disabled = false;
		//tambien desabilitamos los select para que no envien informacion
		document.getElementById('selectEstanterias').disabled = true;
		document.getElementById('selectBaldas').disabled = true;
		document.getElementById('selectCaja').disabled = true;


	} else if (idRadio == 'radioSinAsignar') {
		document.getElementById('idUbicacionCajasSinUbicar').classList.add('hide');
		document.getElementById('idUbicacionEstanteria').classList.add('hide');
		//desabilitamos todos los select 
		document.getElementById('selectEstanterias').disabled = true;
		document.getElementById('selectBaldas').disabled = true;
		document.getElementById('selectCaja').disabled = true;
		document.getElementById('selectCajasSinUbicar').disabled = true;
	}

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
	if(!response.ok){
		throw new Error(`HTTP error status: ${response.status}`)
	}else{
		return response.json(); // datos que recibimos
	}
}

// GET method implementation:
async function getData(url = '') {
  
	const response = await fetch(url, {
	  method: 'GET',
	  headers: {
		'Content-Type': 'application/json'
	  }
	});
	if(!response.ok){
		throw new Error(`HTTP error status: ${response.status}`)
	}else{
		return response.json(); // datos que recibimos
	}
  }
  




function getBaldasCajas() {
	let selectBaldas = document.getElementById('selectBaldas');
	let selectCajas = document.getElementById('selectCaja');
	//recuperamos id de la estanteria seleccionada
	idEstanteriaSelected = selectEstanterias.value;
	//eliminamos los option element baldas y los option element cajas
	Array.from(selectBaldas).forEach(optionElement => {
		optionElement.remove();
	});
	Array.from(selectCajas).forEach(optionElement => {
		optionElement.remove();
	})
	//hacemos la peticion al servidor
	getData('modificarProducto.php?idEstanteria='+idEstanteriaSelected)
		//recibimos los datos
		.then(data => {
			//comprobamos cantidad de baldas
			if(data.baldas.length == 0){
				let baldaElement = document.createElement('option');
				baldaElement.value = 0;
				baldaElement.innerText = 'No hay baldas';
				selectBaldas.appendChild(baldaElement);
			}else{
				//si tenemos baldas
				data.baldas.forEach(balda => {
					let baldaElement = document.createElement('option');
					baldaElement.value = balda.id;
					baldaElement.innerText = balda.nombre;
					selectBaldas.appendChild(baldaElement);
				});
			}
			//comprobamos cantidad de cajas
			if(data.cajas.length==0){
				let cajaElement = document.createElement('option');
				cajaElement.value = 0;
				cajaElement.innerText = 'No hay cajas';
				selectCajas.appendChild(cajaElement);
			}else{
				//creamos option por default
				let cajaElement = document.createElement('option');
				cajaElement.value = 0;
				cajaElement.innerText = 'No ubicar en caja';
				selectCajas.appendChild(cajaElement);
				//creamos option element del select caja
				data.cajas.forEach(caja => {
					let cajaElement = document.createElement('option');
					cajaElement.value = caja.id;
					cajaElement.innerText = caja.nombre;
					selectCajas.appendChild(cajaElement);
				})
			}
			
		})
		.catch(error => console.error('Error:', error));

}

function getCajas() {
	let selectCajas = document.getElementById('selectCaja');
	//recuperamos id de la balda selecionada
	idBaldaSelected = selectBaldas.value;
	//eliminamos los option element cajas
	Array.from(selectCajas).forEach(optionElement => {
		optionElement.remove();
	})
	//hacemos la peticion al servidor
	getData('modificarProducto.php?idBalda='+idBaldaSelected)
		.then(data => {
			//comprobamos cantidad de cajas
			if(data.cajas.length==0){
				let cajaElement = document.createElement('option');
				cajaElement.value = 0;
				cajaElement.innerText = 'No hay cajas';
				selectCajas.appendChild(cajaElement);
			}else{
				//creamos option por default
				let cajaElement = document.createElement('option');
				cajaElement.value = 0;
				cajaElement.innerText = 'No ubicar en caja';
				selectCajas.appendChild(cajaElement);
				//creamos option element del select caja
				data.cajas.forEach(caja => {
					let cajaElement = document.createElement('option');
					cajaElement.value = caja.id;
					cajaElement.innerText = caja.nombre;
					selectCajas.appendChild(cajaElement);
				})
			}
		})
		.catch(error => console.error('Error:', error));
}

function crearEtiqueta() {
	//recuperamos el nombre de la etiqueta
	nombreEtiqueta = document.getElementById('nombreEtiqueta').value;
	//limpiamos el input
	document.getElementById('nombreEtiqueta').value = "";
	//hacemos peticion al servidor
	getData('modificarProducto.php?nombreEtiqueta='+nombreEtiqueta)
		.then(data => {
			createAlert(data);

			//si etiqueta creada correctamente, añadimos la etiqueta al select etiquetas
			if (data['msj-type'] == 'success') {
				insertEtiquetaSelect(nombreEtiqueta, data.idEtiqueta);
			}
		})
		.catch(error => console.error('Error:', error));
}

//insertamos etiqueta en el select etiquetas
function insertEtiquetaSelect(nombreEtiqueta,idEtiqueta) {
  let selectEtiquetas = document.getElementById('selectEtiquetas');
  //creamos elemento option
  let optionElement = document.createElement('option');
  optionElement.value = idEtiqueta;
  optionElement.innerText = nombreEtiqueta;
  selectEtiquetas.appendChild(optionElement);
}

function añadirEtiqueta() {
	//recuperamos el value del select 
	let idEtiquetaSelected = document.getElementById('selectEtiquetas').value;
	//si no recuperamos el id salimos
	if (idEtiquetaSelected == "" || idEtiquetaSelected === undefined || idEtiquetaSelected === null) {
		return;
	}
	//recuperamos el nombre de la etiqueta
	let nombreEtiquetaSelected = document.getElementById('selectEtiquetas').selectedOptions[0].innerText;

	//verificamos que la etiqueta no este añadida
	let etiquetasAñadidas = document.getElementById('etiquetasProducto').childNodes;
	for (let i = 0; i < etiquetasAñadidas.length; i++) {
		const etiqueta = etiquetasAñadidas[i];
		if (etiqueta.localName == 'span') {
			if (etiqueta.id == idEtiquetaSelected) {
				return;
			}
		}
	}

	//creamos element span que sera la etiqueta
	let spanElement = document.createElement('span');
	spanElement.innerText = nombreEtiquetaSelected;
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
	//añadimos el span x al span etiqueta
	document.getElementById(idEtiquetaSelected).appendChild(spanX);

	//añadimos event click al span x para remover la etiqueta
	spanX.addEventListener('click', (e) => {
		e.target.parentNode.remove();
		crearInputEtiquetasAñadidas();
	})
	crearInputEtiquetasAñadidas();
}

function crearInputEtiquetasAñadidas() {
	//recuperamos las etiquetas añadidas
	let etiquetasAñadidas = document.getElementById('etiquetasProducto').childNodes;
	//creamos un string con los id de las etiquetas añadidas
	let stringEtiquetasAñadidas = "";
	for (let i = 0; i < etiquetasAñadidas.length; i++) {
		let etiqueta = etiquetasAñadidas[i];
		if (etiqueta.localName == 'span') {
			stringEtiquetasAñadidas += etiqueta.id + " ";
		}
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


function modificarProducto() {
	const formProducto = document.getElementById('formProducto');
	//obtenemos los datos del formulario como un objeto formData
	const formData = new FormData(formProducto);
	//enviamos el id del producto que estamos modificando
	const data = { modificarProducto: document.getElementById('modificarProducto').value }
	//convertimos los datos del formulario en un objeto json
	formData.forEach((value, key) => {
		data[key] = value;
	});
	postData('modificarProducto.php', data)
		.then(data => {

			//eliminamos el mensaje de error si existe
			if (document.getElementById('nombreInvalido').firstChild != null)
				document.getElementById('nombreInvalido').firstChild.remove();

			//si nombre invalido
			if (data.error == 'nombreInvalido') {

				//creamos el alert
				createAlert(data);

				//div para mostrar error
				let divElement = document.createElement('div');
				divElement.classList.add('textError');
				divElement.classList.add('form-text');
				divElement.classList.add('p-1');
				divElement.classList.add('text-start');

				divElement.innerText = 'Ingresa un nombre al producto.';

				document.getElementById('nombreInvalido').appendChild(divElement);


			} else {

				createAlert(data);
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

			//creamos el alert
			createAlert(response);
			
			//si se ha eliminado la etiqueta
			if(response['msj-type']=='success'){
				//select etiquetas
				const selectEtiquetas = document.getElementById('selectEtiquetas');
				//eliminamos del select la etiqueta
				for (let i = 0; i < selectEtiquetas.childNodes.length; i++) {
					const etiqueta = selectEtiquetas.childNodes[i];
					if(etiqueta.value== idEtiqueta){
						etiqueta.remove();
					}
				}
			
				//recorremos los span etiquetas para remover el eliminado
				for (let i = 0; i < document.getElementById('etiquetasProducto').childNodes.length; i++) {
					const element = document.getElementById('etiquetasProducto').childNodes[i];
					if (element.id == idEtiqueta) {
					element.remove();
					break;
					}
				}

				crearInputEtiquetasAñadidas();
			
			}   
	  })
	  .catch(error => console.error('Error:', error));
  }
  
  function createAlert(mensaje){
	//select div alert
	const divAlerts = document.getElementById('alerts');

	//check si existe un alert
	if(divAlerts.childNodes.length > 0){
		//eliminamos el alert
		divAlerts.childNodes[0].remove();
	}

	//creamos un div que sera el alert
	let divElement = document.createElement('div');
  
	//añadimos clases al div
	divElement.classList.add('alert');
	divElement.classList.add('alert-dismissible');
	divElement.classList.add('alert-' + mensaje['msj-type']);
	divElement.innerHTML = mensaje['msj-content'];
  
	//añadimos el div element en el div alerts
	divAlerts.appendChild(divElement);
	
	//creamos element span para cerrar el alert
	let spanElement = document.createElement('span');
	//añadimos clases y atributos
	spanElement.classList.add('btn-close');
	spanElement.setAttribute('data-bs-dismiss','alert');
	spanElement.setAttribute('type','button');
	spanElement.setAttribute('aria-label','Close');
	//añadimos button element al alert
	divElement.appendChild(spanElement);
  }
  