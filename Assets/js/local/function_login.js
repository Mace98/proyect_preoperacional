// Permite capturar la informacion del formulario de login
document.addEventListener('DOMContentLoaded', function(){

    let deferredPrompt;

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        // Mostrar tu botón instalar
        document.getElementById("btnInstalar").style.display = "block";
    });

    document.getElementById("btnInstalar").addEventListener("click", async () => {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            await deferredPrompt.userChoice;
            deferredPrompt = null;
        }
    });


    const formLogin = document.getElementById('form-login');
    formLogin.addEventListener('submit', async function(event){
        event.preventDefault();
        const userInfo = document.getElementById('user-info').value;
        const passInfo = document.getElementById('pass-info').value;
        console.log(userInfo, passInfo);

        // Validar que el usuario y contraseña no esten vacios
        if(userInfo.trim() === '' || passInfo.trim() === ''){
           // alert('Por favor, ingrese un usuario y contraseña');
           document.getElementById('mensaje').innerHTML = '<div class="alert alert-danger">Por favor, ingrese un usuario y contraseña</div>';
            return;
        }

        // Realizar la peticion fetch a la api de login
        try{
            const response = await fetch( base_url + 'home/session', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({'user': userInfo, 'pass': passInfo})
            });
            const data = await response.json();
            // Validar que la respuesta sea exitosa
            //console.log(data);
            if(data.status === 'success'){
                document.getElementById('mensaje').innerHTML = '<div class="alert alert-success">'+data.message+'</div>';
                // Redirigir a la pagina de dashboard
                window.location.href = base_url + 'Inicio';
            }else{
                document.getElementById('mensaje').innerHTML = '<div class="alert alert-danger">'+data.message+'</div>';
            }
        } catch(error){
            document.getElementById('mensaje').innerHTML = '<div class="alert alert-danger">Error al realizar la peticion</div>';
            console.log(error);
        }

    });

});

// Funcion que permite visualizar la informacion de la contraseña
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('pass-info');
    const eyeIcon = document.getElementById('eye-icon');
  
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text'; // Cambiar a texto
      eyeIcon.classList.remove('fa-eye'); // Cambiar el ícono
      eyeIcon.classList.add('fa-eye-slash');
    } else {
      passwordInput.type = 'password'; // Cambiar a contraseña
      eyeIcon.classList.remove('fa-eye-slash'); // Cambiar el ícono
      eyeIcon.classList.add('fa-eye');
    }
  }

