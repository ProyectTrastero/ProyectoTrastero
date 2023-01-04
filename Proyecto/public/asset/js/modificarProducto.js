window.addEventListener('load', iniciar);

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
	//event click boton crear etiqueta del modal
	document.getElementById('crearEtiqueta').addEventListener('click', crearEtiqueta);
	//event click boton añadir etiqueta
	document.getElementById('añadirEtiqueta').addEventListener('click', añadirEtiqueta);
	//event click boton modificar producto
	document.getElementById('modificarProducto').addEventListener('click', modificarProducto);


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

//Example POST method implementation:
async function postData(url = '', data = {}) {
	// Default options are marked with *
	const response = await fetch(url, {
		method: 'POST',
		body: JSON.stringify(data), // datos que vamos a enviar
		headers: {
			'Content-Type': 'application/json'
		}
	});
	return response.json(); // datos que recibimos
}

function getCajasSinUbicacion() {
	let idCajaSelected = selectCajasSinUbicar.value;
	//borramos los elementos del select cajasSinUbicar
	Array.from(selectCajasSinUbicar.childNodes).forEach(optionElement => {
		optionElement.remove();
	})
	//hacemos la pericion al servidor
	postData('modificarProducto.php', {
		getCajasSinUbicar: '',

	})
		//recibimos los datos
		.then(data => {
			if (data.length == 0) {
				let cajaElement = document.createElement('option');
				cajaElement.value = 0;
				cajaElement.innerText = 'No hay cajas';
				selectCajasSinUbicar.appendChild(cajaElement);
			} else {
				data.forEach(caja => {
					let cajaElement = document.createElement('option');
					cajaElement.value = caja.id;
					cajaElement.innerText = caja.nombre;
					if (idCajaSelected == caja.id) cajaElement.selected = true;
					selectCajasSinUbicar.appendChild(cajaElement);

				})
			}
		})
		//controlamos los errores
		.catch(error => console.error('Error:', error));

}

function getEstanteriasBaldasCajas() {
	idEstanteriaSelected = document.getElementById('selectEstanterias').value;
	idBaldaSelected = document.getElementById('selectBaldas').value;
	idCajaSelected = document.getElementById('selectCaja').value;
	//borramos los elementos del select estanterias, baldas y cajas
	Array.from(selectEstanterias.childNodes).forEach(optionElement => {
		optionElement.remove();
	})
	Array.from(selectBaldas.childNodes).forEach(optionElement => {
		optionElement.remove();
	})
	Array.from(selectCaja.childNodes).forEach(optionElement => {
		optionElement.remove();
	})
	//hacemos la peticion al servidor y enviamos los datos
	postData('modificarProducto.php', {
		getEstanteriasBaldasCajas: '',
		idEstanteriaSelected: idEstanteriaSelected,
		idBaldaSelected: idBaldaSelected,
		idCajaSelected: idCajaSelected,
	})
		//recibimos los datos del servidor
		.then(data => {
			//si la ubicacion es invalida
			if (data['msj-content'] == 'Ubicacion invalida') {
				//creamos un div que sera el alert
				let divElement = document.createElement('div');

				//añadimos clases al div
				divElement.classList.add('alert');
				divElement.classList.add('alert-dismissible');
				divElement.classList.add('fade');
				divElement.classList.add('show');
				divElement.classList.add('alert-' + data['msj-type']);

				divElement.role = 'alert';
				divElement.innerHTML = data['msj-content'];

				//eliminamos el alert despues de 5 seg
				setTimeout(() => {
					divElement.remove();
				}, 5000);

				//añadimos el div alert
				document.getElementById('alerts').appendChild(divElement);

			} else {
				//si no tenemos estanterias
				if (data.estanterias.length == 0) {
					let estanteriaElement = document.createElement('option');
					estanteriaElement.value = 0;
					estanteriaElement.innerText = 'No hay estanterias';
					selectEstanterias.appendChild(estanteriaElement);
				} else {
					//creamos los option elemetos del select estanterias
					data.estanterias.forEach(estanteria => {
						let estanteriaElement = document.createElement('option');
						estanteriaElement.value = estanteria.id;
						estanteriaElement.innerText = estanteria.nombre;
						if (estanteria.id == data.productoSelected.idEstanteriaSelected) estanteriaElement.selected = true;
						selectEstanterias.appendChild(estanteriaElement);
					});
				}
				//si no tenemos baldas
				if (data.baldas.length == 0) {
					let baldaElement = document.createElement('option');
					baldaElement.value = 0;
					baldaElement.innerText = 'No hay baldas';
					selectBaldas.appendChild(baldaElement);
				} else {
					//creamos option element del select baldas
					data.baldas.forEach(balda => {
						let baldaElement = document.createElement('option');
						baldaElement.value = balda.id;
						baldaElement.innerText = balda.nombre;
						if (balda.id == data.productoSelected.idBaldaSelected) baldaElement.selected = true;
						selectBaldas.appendChild(baldaElement);
					});
				}
				if (data.cajas.length == 0) {
					let cajaElement = document.createElement('option');
					cajaElement.value = 0;
					cajaElement.innerText = 'No hay cajas';
					selectCaja.appendChild(cajaElement);
				} else {
					//creamos option por default
					let cajaElement = document.createElement('option');
					cajaElement.value = 0;
					cajaElement.innerText = 'No ubicar en caja';
					selectCaja.appendChild(cajaElement);
					//creamos los option element del select cajas
					data.cajas.forEach(caja => {
						let cajaElement = document.createElement('option');
						cajaElement.value = caja.id;
						cajaElement.innerText = caja.nombre;
						if (caja.id == data.productoSelected.idCajaSelected) cajaElement.selected = true;
						selectCaja.appendChild(cajaElement);
					})
				}

			}
		})
		//controlamos los errores
		.catch(error => console.error('Error:', error));
}

function getBaldasCajas() {
	//recuperamos id de la estanteria seleccionada
	idEstanteriaSelected = selectEstanterias.value;
	//eliminamos los option element baldas y los option element cajas
	Array.from(selectBaldas).forEach(optionElement => {
		optionElement.remove();
	});
	Array.from(selectCaja).forEach(optionElement => {
		optionElement.remove();
	})
	//hacemos la peticion al servidor
	postData('modificarProducto.php', {
		getBaldasCajas: '',
		idEstanteriaSelected: idEstanteriaSelected
	})
		//recibimos los datos
		.then(data => {
			data.baldas.forEach(balda => {
				let baldaElement = document.createElement('option');
				baldaElement.value = balda.id;
				baldaElement.innerText = balda.nombre;
				selectBaldas.appendChild(baldaElement);
			})
			//creamos option por default
			let cajaElement = document.createElement('option');
			cajaElement.value = 0;
			cajaElement.innerText = 'No ubicar en caja';
			selectCaja.appendChild(cajaElement);
			//creamos option element del select caja
			data.cajas.forEach(caja => {
				let cajaElement = document.createElement('option');
				cajaElement.value = caja.id;
				cajaElement.innerText = caja.nombre;
				selectCaja.appendChild(cajaElement);
			})
		})
		.catch(error => console.error('Error:', error));

}

function getCajas() {
	//recuperamos id de la balda selecionada
	idBaldaSelected = selectBaldas.value;
	//eliminamos los option element cajas
	Array.from(selectCaja).forEach(optionElement => {
		optionElement.remove();
	})
	//hacemos la pericion al servidor
	postData('modificarProducto.php', {
		getCajas: '',
		idBaldaSelected: idBaldaSelected
	})
		.then(data => {
			//creamos option por default
			let cajaElement = document.createElement('option');
			cajaElement.value = 0;
			cajaElement.innerText = 'No ubicar en caja';
			selectCaja.appendChild(cajaElement);
			//creamos option element del select caja
			data.forEach(caja => {
				let cajaElement = document.createElement('option');
				cajaElement.value = caja.id;
				cajaElement.innerHTML = caja.nombre;
				selectCaja.appendChild(cajaElement);
			})
		})
		.catch(error => console.error('Error:', error));
}

function crearEtiqueta() {
	//recuperamos el nombre de la etiqueta
	nombreEtiqueta = document.getElementById('nombreEtiqueta').value;
	//limpiamos el input
	document.getElementById('nombreEtiqueta').value = "";
	//hacemos peticion al servidor
	postData('modificarProducto.php', {
		crearEtiqueta: '',
		nombreEtiqueta: nombreEtiqueta,
	})
		.then(data => {
			//creamos element div que sera el alert
			divElement = document.createElement('div');
			//añadimos clases
			divElement.classList.add('alert');
			divElement.classList.add('alert-dismissible');
			divElement.classList.add('fade');
			divElement.classList.add('show');
			divElement.classList.add('alert-' + data['msj-type']);
			divElement.role = 'alert';
			divElement.innerHTML = data['msj-content'];

			//eliminamos el alert despues de un tiempo establecido
			//pendiente de mejorar
			setTimeout(() => {
				divElement.classList.remove('show');
			}, 5000);
			divElement.addEventListener('transitionend', () => {
				divElement.remove();
			})

			//añadimos el div alert
			document.getElementById('alerts').appendChild(divElement);
			document.getElementById('alerts').style.overflowX = 'hidden';

			//si etiqueta creada correctamente, recargamos select etiquetas
			if (data['msj-type'] == 'success') {
				getEtiquetas();
			}
		})
		.catch(error => console.error('Error:', error));
}

//recibimos las etiquetas del usuario
function getEtiquetas() {
	//eliminamos los option elment del select etiquetas
	Array.from(selectEtiquetas.childNodes).forEach(etiqueta => {
		//eliminamos las etiquetas
		etiqueta.remove();
	})
	//hacemos peticion al servidor para obtener las etiquetas
	postData('modificarProducto.php', { getEtiquetas: '' })
		.then(data => {
			data.forEach(etiqueta => {
				//creamos element option
				let optionElement = document.createElement('option');
				optionElement.value = etiqueta.id;
				optionElement.innerText = etiqueta.nombre;
				//añadimos la etiqueta al select
				selectEtiquetas.appendChild(optionElement);

			})
		})
		.catch(error => console.error('Error:', error));
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

	//añadimos event click al span x para eliminar la etiqueta
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
	//para eliminar el espacio del final
	stringEtiquetasAñadidas = stringEtiquetasAñadidas.trim();
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
	inputAñadirEtiquetas.value = stringEtiquetasAñadidas;
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
				//div para mostrar error
				let divElement = document.createElement('div');
				divElement.classList.add('textError');
				divElement.classList.add('form-text');
				divElement.classList.add('p-1');
				divElement.classList.add('text-start');

				divElement.innerText = 'Ingresa un nombre al producto.';

				document.getElementById('nombreInvalido').appendChild(divElement);


			} else {

				//creamos un div que sera el alert
				let divElement = document.createElement('div');

				//añadimos clases al div
				divElement.classList.add('alert');
				divElement.classList.add('alert-dismissible');
				divElement.classList.add('fade');
				divElement.classList.add('show');
				divElement.classList.add('alert-' + data['msj-type']);

				divElement.role = 'alert';
				divElement.innerHTML = data['msj-content'];

				//añadimos el div alert
				document.getElementById('alerts').appendChild(divElement);

				//añadimos una transicion a el alert despues de un tiempo establecido
				setTimeout(() => {
					divElement.classList.add('deleteAlert');

				}, 3000);

				//eliminamos el alert despues de acabada la transicion
				divElement.addEventListener('transitionend', () => {
					divElement.remove();
				})
			}
		})
		.catch(error => console.error('Error:', error));

}