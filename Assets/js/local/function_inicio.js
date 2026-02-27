const form_operacional = document.getElementById('formpreo');

// Permite ejecutar las funciones cuando se carga el DOM
$( document ).ready(function(){
    fntGetVehiculos();
	fntGetConductores();
}, false);

// Funcion que permita cargar la informacion de los vehiculos 
function fntGetVehiculos()
{
    var ajaxUrl = base_url+'inicio/obtener_placas';
	var request = (window.XMLHttpRequest) ? new XMLHttpRequest(): new ActiveXObject('Microsoft.XMLHTTP');
	request.open("GET", ajaxUrl, true);
	request.send();

	
	request.onreadystatechange = function(){
		if(request.readyState == 4 && request.status == 200){
			// console.log("Respuesta....", request.responseText);
			document.querySelector('#placainicio').innerHTML = request.responseText;
			document.querySelector('#placainicio').value = "0";
			$('#placa').selectpicker('render');
		}
	}
}

// Funcion que permita cargar la informacion de los conductores
function fntGetConductores()
{
	var ajaxUrl = base_url+'inicio/obtener_conductores';
	var request = (window.XMLHttpRequest) ? new XMLHttpRequest(): new ActiveXObject('Microsoft.XMLHTTP');
	request.open("GET", ajaxUrl, true);
	request.send();

	
	request.onreadystatechange = function(){
		if(request.readyState == 4 && request.status == 200){
			// console.log("Respuesta....", request.responseText);
			document.querySelector('#conductor').innerHTML = request.responseText;
			document.querySelector('#conductor').value = "0";
			$('#conductor').selectpicker('render');
		}
	}
}

// Permite realizar la consulta del formulario de acuerdo a las opciones seleccionadas 
document.addEventListener('DOMContentLoaded', function(){

	
	
	const tipov = document.getElementById('tipovehiculo');
	const conductor = document.getElementById('conductor');
	const movil = document.getElementById('placainicio');
	

	// Validar el tipo de vehículo
	tipov.addEventListener('change', function () {
		// console.log("TIPO:", this.value);
		//tipov.value = this.options[this.selectedIndex].text;
	});

	// Obtener información del conductor
	conductor.addEventListener('change', function () {
		// console.log("CONDUCTOR:", this.value);
		const textoConductor = this.options[this.selectedIndex].text;
		document.getElementById('conductorText').value = textoConductor;
	});

	// Validar la informacion el vehiculo
	movil.addEventListener('change', function(){
		// console.log("VEHÍCULO: ", this.value);
		const textoPlaca = this.options[this.selectedIndex].text;
		document.getElementById('placaText').value = textoPlaca;
	})

	// Envío del formulario
	form_operacional.addEventListener('submit', function (e) {
		// e.preventDefault();
		const tipo      = document.getElementById('tipovehiculo').value;
		const conductor = document.getElementById('conductor').value;
		const movil     = document.getElementById('placainicio').value;
		const km        = document.getElementById('kmactual').value;

		// Validaciones
		if (movil === '0' || movil === '') {
			e.preventDefault();
			alert('Seleccione un vehículo');
			return;
		}

		if (!km || isNaN(km)) {
			e.preventDefault();
			alert('Ingrese un kilometraje válido');
			return;
		}

		if (conductor === '0') {
			e.preventDefault();
			alert('Seleccione un conductor');
			return;
		}

		if (tipo === '0') {
			e.preventDefault();
			alert('Seleccione tipo de vehículo');
			return;
		}
		
		return true; // deja que el form haga POST
	});
	

})