window.addEventListener('load', iniciar);

function iniciar() {
	checkSelectRadio();

	//add events a los elementos
	addEventToElements();
}

function addEventToElements() {
	//add event click a los radios
	const radios = document.getElementsByName('ubicacion');
	for (let i = 0; i < radios.length; i++) {
		const radio = radios[i];
		radio.addEventListener('click', (e) => {
			cambiarUbicacion(e);
		});
	}

	document.getElementById('selectEstanterias').addEventListener('change',getBaldasCajas);

	document.getElementById('selectBaldas').addEventListener('change', getCajas);

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

function cambiarUbicacion(e) {
	let target = e.target;

	if (target.id == 'radioUbicacionEstanteria') {
		document.getElementById('idUbicacionEstanteria').classList.remove('hide');
		document.getElementById('idUbicacionCajasSinUbicar').classList.add('hide');
		//habilitamos los select 
		document.getElementById('selectEstanterias').disabled = false;
		document.getElementById('selectBaldas').disabled = false;
		document.getElementById('selectCaja').disabled = false;
		//tambien desabilitamos los select para que no envien informacion
		document.getElementById('selectCajasSinUbicar').disabled = true;
		//hacemos peticion al servidor para rellenar los select
		getEstanteriasBaldasCajas();

	} else if (target.id == 'radioCajasSinAsignar') {
		document.getElementById('idUbicacionCajasSinUbicar').classList.remove('hide');
		document.getElementById('idUbicacionEstanteria').classList.add('hide');
		//habilitamos los select
		document.getElementById('selectCajasSinUbicar').disabled = false;
		//tambien desabilitamos los select para que no envien informacion
		document.getElementById('selectEstanterias').disabled = true;
		document.getElementById('selectBaldas').disabled = true;
		document.getElementById('selectCaja').disabled = true;
		//hacemos peticion al servidor para rellenar los select
		getCajasSinUbicacion();

	} else if (target.id == 'radioSinAsignar') {
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
		body: JSON.stringify(data) // datos que vamos a enviar
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
	postData('modificarProducto.php', { getCajasSinUbicar: '',
										
									})
		//recibimos los datos
		.then(data => {
			data.forEach(caja=>{
				let cajaElement = document.createElement('option');
				cajaElement.value = caja.id;
				cajaElement.innerText = caja.nombre;
				if (idCajaSelected == caja.id) cajaElement.selected = true;
				selectCajasSinUbicar.appendChild(cajaElement);

			})
		})
		//controlamos los errores
		.catch(error => console.error('Error:', error));

}

function getEstanteriasBaldasCajas(){
	idEstanteriaSelected = document.getElementById('selectEstanterias').value;
	idBaldaSelected = document.getElementById('selectBaldas').value;
	idCajaSelected = document.getElementById('selectCaja').value;
	//borramos los elementos del select estanterias, baldas y cajas
	Array.from(selectEstanterias.childNodes).forEach(optionElement =>{
		optionElement.remove();
	})
	Array.from(selectBaldas.childNodes).forEach(optionElement =>{
		optionElement.remove();
	})
	Array.from(selectCaja.childNodes).forEach(optionElement =>{
		optionElement.remove();
	})
	//hacemos la peticion al servidor y enviamos los datos
	postData('modificarProducto.php',{	getEstanteriasBaldasCajas: '',
										idEstanteriaSelected : idEstanteriaSelected,
										idBaldaSelected : idBaldaSelected,
										idCajaSelected : idCajaSelected,
									})
		//recibimos los datos del servidor
		.then(data=>{
			//creamos los option elemetos del select estanterias
			data.estanterias.forEach(estanteria=>{
				let estanteriaElement = document.createElement('option');
				estanteriaElement.value = estanteria.id;
				estanteriaElement.innerText = estanteria.nombre;
				if (estanteria.id == data.productoSelected.idEstanteriaSelected)  estanteriaElement.selected = true;
				selectEstanterias.appendChild(estanteriaElement);
			});
			//creamos option element del select baldas
			data.baldas.forEach(balda =>{
				let baldaElement =document.createElement('option');
				baldaElement.value = balda.id;
				baldaElement.innerText = balda.nombre;
				if (balda.id == data.productoSelected.idBaldaSelected) baldaElement.selected = true;
				selectBaldas.appendChild(baldaElement);
			});
			//creamos los option element del select cajas
			data.cajas.forEach(caja => {
				let cajaElement = document.createElement('option');
				cajaElement.value = caja.id;
				cajaElement.innerText = caja.nombre;
				if (caja.id == data.productoSelected.idCajaSelected) cajaElement.selected = true;
				selectCaja.appendChild(cajaElement);
			})
		})
		//controlamos los errores
		.catch(error => console.error('Error:', error));
}

function getBaldasCajas(){
	//recuperamos id de la estanteria seleccionada
	idEstanteriaSelected = selectEstanterias.value;
	//eliminamos los option element baldas y los option element cajas
	Array.from(selectBaldas).forEach(optionElement=>{
		optionElement.remove();
	});
	Array.from(selectCaja).forEach(optionElement=>{
		optionElement.remove();
	})
	//hacemos la peticion al servidor
	postData('modificarProducto.php',{ 	getBaldasCajas : '',
										idEstanteriaSelected  : idEstanteriaSelected
									})
	//recibimos los datos
	.then(data=>{
		data.baldas.forEach(balda=>{
			let baldaElement = document.createElement('option');
			baldaElement.value = balda.id;
			baldaElement.innerText = balda.nombre;
			selectBaldas.appendChild(baldaElement);
		})
		data.cajas.forEach(caja=>{
			let cajaElement = document.createElement('option');
			cajaElement.value = caja.id;
			cajaElement.innerText = caja.nombre;
			selectCaja.appendChild(cajaElement);
		})
	})
	.catch(error => console.error('Error:',error));

}

function getCajas(){
	//recuperamos id de la balda selecionada
	idBaldaSelected = selectBaldas.value;
	//eliminamos los option element cajas
	Array.from(selectCaja).forEach(optionElement=>{
		optionElement.remove();
	})
	//hacemos la pericion al servidor
	postData('modificarProducto.php',	{getCajas : '',
										idBaldaSelected : idBaldaSelected
									})
	.then(data=>{
		data.forEach(caja=>{
			let cajaElement = document.createElement('option');
			cajaElement.value = caja.id;
			cajaElement.innerHTML = caja.nombre;
			selectCaja.appendChild(cajaElement);
		})
	})
	.catch(error=> console.error('Error:',error));
}
