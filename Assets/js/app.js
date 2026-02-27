var urlApp = window.location.href;

if ('serviceWorker' in navigator) {

    const isLocalhost = urlApp.includes('localhost');

    const swLocation = isLocalhost
        ? './service-worker.js'
        : '/proyect_preoperacional/service-worker.js';

    const swScope = isLocalhost
        ? './'
        : '/proyect_preoperacional/';

    console.log(
        isLocalhost
            ? 'Modo desarrollo, registrando ServiceWorker local'
            : 'Modo producción, registrando ServiceWorker producción'
    );

    navigator.serviceWorker.register(swLocation, { scope: swScope })
        .then(() => {
            console.log('ServiceWorker registrado con éxito');
            navigator.serviceWorker.ready
                .then( reg => {
                    return reg.sync.register('sync-data');
                })
                .catch( error => {
                    console.error('Error al registrar la sincronizacion: ', error );
                });
        })
        .catch(error => {
            console.error('Error al registrar el ServiceWorker:', error);
        });
}
