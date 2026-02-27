// Obtener referencia al formulario 
const formulario = document.querySelector('#formFormulario');

document.querySelectorAll('.check-item').forEach(radio => {
    radio.addEventListener('change', function() {
        const bloque = this.closest('.sistema-bloque');
        const filas = bloque.querySelectorAll('tbody tr');
        
        let puntosObtenidos = 0;
        let itemsValidos = 0;

        filas.forEach(fila => {
            const seleccionado = fila.querySelector('input[type="radio"]:checked');
            if (seleccionado) {
                const peso = seleccionado.getAttribute('data-peso');
                if (peso !== "omitir") {
                    puntosObtenidos += parseFloat(peso);
                    itemsValidos++;
                }
            }
        });

        let porcentaje = 0;
        if (itemsValidos > 0) {
            porcentaje = (puntosObtenidos / itemsValidos) * 100;
        }

        // 1. Actualizamos el texto visual
        bloque.querySelector('.score-display').innerText = porcentaje.toFixed(2);
        
        // 2. Actualizamos el valor del input oculto para el registro
        bloque.querySelector('.input-score').value = porcentaje.toFixed(2);
    });
});

document.addEventListener('change', function(e) {
  if (e.target.classList.contains('check-item')) {
    const sistema = e.target.name.match(/check\[([^\]]+)\]/)[1];
    const index = e.target.name.match(/\[([^\]]+)\]$/)[1];
    const valor = e.target.value;
    
    // Actualizar el campo oculto
    const inputHidden = document.querySelector(`input[name="valores_check[${sistema}][${index}]"]`);
    if (inputHidden) {
      inputHidden.value = valor;
    }
  }
});

// Inicilizar el evento de envío del formulario
document.addEventListener('DOMContentLoaded', function() {

    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    document.getElementById('clear').addEventListener('click', () => {
        signaturePad.clear();
    });

    formulario.addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío tradicional del formulario
        // Aquí puedes agregar validaciones adicionales si es necesario

        if (!signaturePad.isEmpty()) {
            const firmaBase64 = signaturePad.toDataURL('image/png');
            document.getElementById('firma').value = firmaBase64;
        }
        const formData = new FormData(formulario);



        // Realizar peticion Fetch al servidor
        fetch( base_url + 'formulario/formulario_base', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(response => {
            
            if( response.status ){
                // console.log(response);
                // Mostrar alerta de éxito
                swal("Alerta!", response.msg, "success" );
                // Limpiar el formulario
                signaturePad.clear();
                formulario.reset();
                // Redirigir a la página de generación de PDF con el ID de inspección despues de 1 segundo
                setInterval(() => {
                   window.location.href = base_url + "inicio";
                }, 1500)
                window.location.href = base_url + "inicio";
            } else {
                // Mostrar alerta de error
                swal("Alerta!", response.msg, "error" );
                // Limpiar el formulario
                formulario.reset();
            }
        })

    });

    // Permitir obtener el valor del radio con el fin de habilitar la obtcion de fotografia 
    document.addEventListener("change", function(e){

        if(e.target.classList.contains("check-item")){

            const row = e.target.closest("tr");
            const btnFoto = row.querySelector(".btn-foto");
            const inputFoto = row.querySelector(".input-foto");

            

            if(e.target.value === "M"){

                btnFoto.classList.remove("d-none");
                inputFoto.required = true;

               
            } else {

                btnFoto.classList.add("d-none");
                inputFoto.required = false;
                inputFoto.value = "";

            }
        }

    });

    document.addEventListener("click", function(e){

        if(e.target.classList.contains("btn-foto")){

            const row = e.target.closest("tr");
            const inputFoto = row.querySelector(".input-foto");
            inputFoto.click();

        }

    });
});


