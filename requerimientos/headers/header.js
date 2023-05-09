// window.addEventListener('load', () => {

//     let checkTipoDeMoneda = document.getElementById('checkTipoDeMoneda');

//     checkTipoDeMoneda.addEventListener("change", statusCheckTipoMoneda, false);

//     function statusCheckTipoMoneda() {

//         document.querySelector('.toggle-switch').style.opacity = '0.5';
//         checkTipoDeMoneda.setAttribute('disabled', true);

//         setTimeout(() => {
//             checkTipoDeMoneda.removeAttribute('disabled')
//             document.querySelector('.toggle-switch').style.opacity = '1'
//         }, 4000)


//         let checked = checkTipoDeMoneda.checked;

//         if (checked) {

//             // Dolares
//             tipoDeMoneda(1)

//             // console.log('Activo')
//         } else {
//             // Pesos 
//             tipoDeMoneda(0)
//             // console.log('Inactivo')

//         }

//     }


//     const tipoDeMoneda = async (status) => {

//         let accion = { 'Accion': 'general', 'status': status }; //Pesos=0 y dolares=1
//         return (await fetch(rutaApi, {
//             method: 'POST',
//             body: JSON.stringify(accion),
//             headers: {
//                 'Content-Type': 'application/json'
//             }

//         }).then(respuesta => respuesta.json())

//             .then(respuesta => {

//                 return respuesta;

//             })

//         )

//     }

// })
