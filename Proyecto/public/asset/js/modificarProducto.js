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
		document.getElementById('idUbicacionCajasSinAsignar').classList.add('hide');
		//habilitamos los select 
		document.getElementById('selectEstanterias').disabled = false;
		document.getElementById('selectBaldas').disabled = false;
		document.getElementById('selectCaja').disabled = false;
		//tambien desabilitamos los select para que no envien informacion
		document.getElementById('selectCajasSinAsignar').disabled = true;

	} else if (idRadio == 'radioCajasSinAsignar') {
		document.getElementById('idUbicacionCajasSinAsignar').classList.remove('hide');
		document.getElementById('idUbicacionEstanteria').classList.add('hide');
		//habilitamos los select
		document.getElementById('selectCajasSinAsignar').disabled = false;
		//tambien desabilitamos los select para que no envien informacion
		document.getElementById('selectEstanterias').disabled = true;
		document.getElementById('selectBaldas').disabled = true;
		document.getElementById('selectCaja').disabled = true;

	} else if (idRadio == 'radioSinAsignar') {
		document.getElementById('idUbicacionCajasSinAsignar').classList.add('hide');
		document.getElementById('idUbicacionEstanteria').classList.add('hide');
		//desabilitamos todos los select 
		document.getElementById('selectEstanterias').disabled = true;
		document.getElementById('selectBaldas').disabled = true;
		document.getElementById('selectCaja').disabled = true;
		document.getElementById('selectCajasSinAsignar').disabled = true;
	}
}

function cambiarUbicacion(e) {
	let target = e.target;

	if (target.id == 'radioUbicacionEstanteria') {
		document.getElementById('idUbicacionEstanteria').classList.remove('hide');
		document.getElementById('idUbicacionCajasSinAsignar').classList.add('hide');
		//habilitamos los select 
		document.getElementById('selectEstanterias').disabled = false;
		document.getElementById('selectBaldas').disabled = false;
		document.getElementById('selectCaja').disabled = false;
		//tambien desabilitamos los select para que no envien informacion
		document.getElementById('selectCajasSinAsignar').disabled = true;

	} else if (target.id == 'radioCajasSinAsignar') {
		document.getElementById('idUbicacionCajasSinAsignar').classList.remove('hide');
		document.getElementById('idUbicacionEstanteria').classList.add('hide');
		//habilitamos los select
		document.getElementById('selectCajasSinAsignar').disabled = false;
		//tambien desabilitamos los select para que no envien informacion
		document.getElementById('selectEstanterias').disabled = true;
		document.getElementById('selectBaldas').disabled = true;
		document.getElementById('selectCaja').disabled = true;
		getCajasSinUbicacion();

	} else if (target.id == 'radioSinAsignar') {
		document.getElementById('idUbicacionCajasSinAsignar').classList.add('hide');
		document.getElementById('idUbicacionEstanteria').classList.add('hide');
		//desabilitamos todos los select 
		document.getElementById('selectEstanterias').disabled = true;
		document.getElementById('selectBaldas').disabled = true;
		document.getElementById('selectCaja').disabled = true;
		document.getElementById('selectCajasSinAsignar').disabled = true;
	}

}

// Example POST method implementation:
async function postData(url = '', data = {}) {
	// Default options are marked with *
	const response = await fetch(url, {
		method: 'POST',
		body: JSON.stringify(data) // body data type must match "Content-Type" header
	});
	return response.json(); // parses JSON response into native JavaScript objects
}

function getCajasSinUbicacion(){
	postData('modificarProducto.php', { getCajasSinAsignar: 'daniel' })
		.then((data) => {
			console.log(data); // JSON data parsed by `data.json()` call
		});

}