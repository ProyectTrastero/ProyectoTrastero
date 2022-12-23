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
		//hacemos peticion al servidor
		getEstanteriasBaldas();

	} else if (target.id == 'radioCajasSinAsignar') {
		document.getElementById('idUbicacionCajasSinUbicar').classList.remove('hide');
		document.getElementById('idUbicacionEstanteria').classList.add('hide');
		//habilitamos los select
		document.getElementById('selectCajasSinUbicar').disabled = false;
		//tambien desabilitamos los select para que no envien informacion
		document.getElementById('selectEstanterias').disabled = true;
		document.getElementById('selectBaldas').disabled = true;
		document.getElementById('selectCaja').disabled = true;
		//hacemos peticion al servidor
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
	//borramos los elementos del select cajasSinUbicar
	Array.from(selectCajasSinUbicar.childNodes).forEach(optionElement => {
		optionElement.remove();
	})
	postData('modificarProducto.php', { getCajasSinUbicar: 'daniel' })
		//recibimos los datos
		.then(data => {
			data.forEach(caja=>{
				let cajaElement = document.createElement('option');
				cajaElement.value = caja.id;
				cajaElement.innerText = caja.nombre;
				selectCajasSinUbicar.appendChild(cajaElement);

			})
		})
		//controlamos los errores
		.catch(error => console.error('Error:', error));

}
// function getCajasSinUbicacion(){
// 	fetch('modificarProducto.php', {
// 	method: 'POST',
// 	body: JSON.stringify({
// 		título: 'Mi nuevo post',
// 		contenido: 'Este es el contenido de mi nuevo post'
// 	}),
// 	headers: {
// 		'Content-Type': 'application/json'
// 	}
// 	}).then(res => res.json())
// 	.then(response => console.log('Success:', JSON.stringify(response)))
// 	.catch(error => console.error('Error:', error));
// }