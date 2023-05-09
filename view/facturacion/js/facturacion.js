$(document).ready(function () { //Informacion al cargar la pagina



    $('#titlePage').text('Facturación');

    tablaPrincipal(1);
    // console.log("prueba de implentar");
    // let accionFactura = { "Accion": "factura",'Select':'timbrarFactura4','data':5};
    // postData(rutaApi,accionFactura ).then(async (response) => {
    //     if(response['success']==true){
    //         dir = "../../Data/facturas/"+response['data']+'.zip';
    //         descargarArchivo(dir);
    //     showAlert("Correcto",response['messenge'],"success")  
    //     setTimeout( function() { window.location.href = "index.php"; }, 2000 );
    //     }else{
    //         showAlert("Error",response['messenge'],"false")   
    //     }
    // })
})


const modulo = 1000;



const resetTablas=()=>{
    var tabActivo = $('.tab-pane.active').attr('id');
    tablaPrincipal();
  

}


// function descargarArchivo(id) {
//     // fetch('../../Data/facturas/'+id)
//     //   .then(response => response.blob())
//     //   .then(blob => {
//         const link = document.createElement('a');
//         link.href = 'https://visionremota.com.mx/5inco/Data/facturas/'+id+'.zip';
//         link.download = id+'.zip';
//         document.body.appendChild(link);
//         link.click();
//         document.body.removeChild(link);
//     //   });
//   }
// Agrega un listener al evento 'shown.bs.tab' de Bootstrap

//// modificación Angel Mercado para mostrar facturas de quienFactura  26/abril/2023

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

    //SE OBTINE EL ATTRIBUTO QUE IDENTIFICA A CADA TAB
    var href = $(e.target).attr('href');
    let razonfactura= 1;

    // CON UN SWITCH SE LLAMA A LA TABLA DEPENDIENDO EL TAB
    switch (href) {
        case '#tab1':
            tablaPrincipal(razonfactura);
            break;
        case '#tab2':
            razonfactura= 2;
            tablaPrincipal(razonfactura);
        break;

    }

  });

const tablaPrincipal = (razonfactura) => {
//// modificación Angel Mercado para mostrar facturas de quienFactura  26/abril/2023
    var accion = { "Accion": "factura",'Select':'getTabla', 'razonfactura':razonfactura }

    var tablaSSP = $('#tablaPrincipal').DataTable({

        'ajax': {
            'url': rutaApi,
            'type': 'GET',
            'data': accion,
            'dataSrc': 'data',
        },

        'order': [[1, 'desc']],

        'columns': [

            { 'data': 'acciones' },
            { 'data': 'id' },
            { 'data': 'status' },
            { 'data': 'Usuario' },
            { 'data': 'moneda' },
            { 'data': 'cliente' },
            { 'data': 'total','render': $.fn.dataTable.render.number(',', '.', 2, '$')},
            { 'data': 'fecha','render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss',' DD/MM/YYYY HH:mm')},
            { 'data': 'fecha_vencimiento' },
            { 'data': 'statusPago' },

        ],

        'language': {

            'url': '../../requerimientos/vendors/DataTables-1.10.24/language/Spanish_Mexico.json'

        },

        "destroy": true,
        "scrollY": "500px",
        "sScrollX": "100%",
        "sScrollXInner": "100%",
        "scrollCollapse": false,
        "paging": false,
        createdRow: function (row, data) {
            if(data.estilo){
                $(row).css('background-color',data.estilo);
                // $(row).css('color','#fff');
            }
          },
    })
}

async function postData(rutaAccion, accion) {

    return await fetch(rutaAccion, {
        method: 'POST',
        body: JSON.stringify(accion),
        headers: { 'Content-Type': 'application/json' }

    }).then(response => response.json())

        .then(response => {

            if (response['success']) {

                return response;
            }

            alert(response['messenge']);

        }).catch(response=>{
            console.log(response);
        })

}


$(document).on('click', '.btnPDFDownloadZip', function (e) {

    let id = e.target.id;
    id = id.substring(2);
    initCFDIS(id);
    
})

const initCFDIS=(id)=>{
    
    let accionFactura = { "Accion": "factura",'Select':'timbrarFactura4','data':id};
    postData(rutaApi,accionFactura ).then(async (response) => {
        if(response['success']==true){
            dir = "../../Data/facturas/"+response['data']+'.zip';
            descargarArchivo(dir);
            // verArchivo(response['data']);
        showAlert("Correcto",response['messenge'],"success")  
        }else{
            showAlert("Error",response['messenge'],"false");
             
        }
    })

}
function descargarArchivo(direccion) {
    fetch(direccion)
      .then(response => response.blob())
      .then(blob => {
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = direccion.split('/').pop();
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        let tablaCargar = $('#tablaPrincipal').DataTable();
        tablaCargar.ajax.reload();
      });
  }
function verArchivo(direccion) {
        // Crea un blob con el contenido de la respuesta
        var blob = new Blob([direccion], { type: 'application/pdf' });
        // Crea una URL para el blob
        var url = URL.createObjectURL(blob);
        // Abre una nueva ventana con el PDF
        window.open(url);
  }

  //-------------------------------------------- Funciones 

function descargarDoc(direccion) {
    fetch(direccion)
      .then(response => response.blob())
      .then(blob => {
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = direccion.split('/').pop();
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      });
  }


function viewDoc(direccion) {
    fetch(direccion)
        .then(response => response.blob())
        .then(blob => {
            const url = URL.createObjectURL(blob);
            const nuevaVentana = window.open(url, '_blank');
            nuevaVentana.focus();
        });
}

//-------------------------------------------- ACCIONES FACTURA TIMBRADA 
// Factura Timbrada: Descagar XML/PDF
$("#tablaPrincipal").on('click', '.btnPDFDownloadZipTimbrada', function (e) {
    let id = (e.target.id).substring(2);

    let accionFactura = { "Accion": "factura",'Select':'getZipFacturaTimbrada','idFactura':id};

    postData(rutaApi,accionFactura ).then(async (response) => {
        if(response['success']==true){
            dir = "../../Data/facturas/"+response['data'];
            descargarDoc(dir);
        showAlert("Correcto",response['messenge'],"success")  
        }else{
            showAlert("Error",response['messenge'],"false")   
        }
    })

})


// Factura Timbrada: Descagar PDF
$("#tablaPrincipal").on('click', '.btnDownloadPDFTimbrada', function (e) {

    let id = (e.target.id).substring(2);

    let accionFactura = { "Accion": "factura",'Select':'getPDFfacturaTimbrada','idFactura':id};


    postData(rutaApi,accionFactura ).then(async (response) => {

        
        if(response['success']==true){

            dir = "../../Data/facturas/"+response['data'];
            descargarDoc(dir)
            showAlert("Correcto",response['messenge'],"success")  

        }else{

            showAlert("Error",response['messenge'],"false")   
        
        }


    })

})


// Factura Timbrada: Ver PDF
$("#tablaPrincipal").on('click', '.btnViewPDFTimbrada', function (e) {

    let id = (e.target.id).substring(2);

    let accionFactura = { "Accion": "factura",'Select':'getPDFfacturaTimbrada','idFactura':id};



    postData(rutaApi,accionFactura ).then(async (response) => {

        
        if(response['success']==true){

            dir = "../../Data/facturas/"+response['data'];
            viewDoc(dir)
            showAlert("Correcto",response['messenge'],"success")  

        }else{
            showAlert("Error",response['messenge'],"false")   
        }
        
    })

})

$("#tablaPrincipal").on("click", ".btnFacturaVerPDF", function (e) {
    let id = e.target.id.substring(2);
  
    let accionFactura = {
      Accion: "factura",
      Select: "pdfFactura",
      idFactura: id,
    };
  
  
          fetch(rutaApi,{
              method: 'POST',
              body: JSON.stringify(accionFactura),
              headers: { 'Content-Type': 'application/json' }
          })
          .then(response => response.blob())
          .then(blob => {
            //   const url = URL.createObjectURL(blob);
            //   const a = document.createElement('a');
            //   a.href = url;
            //   a.download = 'archivo.pdf';
            //   a.click();
            const pdfUrl = URL.createObjectURL(blob);
            const newWindow = window.open(pdfUrl);
            newWindow.addEventListener('unload', () => {
            URL.revokeObjectURL(pdfUrl);
            });
          });
  
          
  
  });

  $("#tablaPrincipal").on("click", ".btnFacturaGenerarPDF", function (e) {
    let id = e.target.id.substring(2);
  
    let accionFactura = {
      Accion: "factura",
      Select: "pdfFactura",
      idFactura: id,
    };
  
    fetch(rutaApi, {
      method: "POST",
      body: JSON.stringify(accionFactura),
      headers: { "Content-Type": "application/json" },
    })
      .then((response) => response.blob())
      .then((blob) => {
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = `factura_${id}.pdf`;
        a.click();
      });
  });

  $("#tablaPrincipal").on("click", ".btnFacturaCancelar", function (e) {
    
    let id = e.target.id.substring(2);

    let accionFactura = { "Accion": "factura",'Select':'cancelarFactura','idFactura':id};

    postData(rutaApi,accionFactura ).then(async (response) => {

        if(response['success']==true){
            let tablaCargar = $('#tablaPrincipal').DataTable();
            tablaCargar.ajax.reload();
            showAlert("Correcto",response['messenge'],"success")  
        }else{
            showAlert("Error",response['messenge'],"false")   
        }
        
    })
  
});

$("#tablaPrincipal").on("click", ".btnCancelarTimbrado", function (e) {

    let id = e.target.id.substring(2);

    let accionFactura = { "Accion": "factura",'Select':'cancelarFacturaTimbrada','idFactura':id};

    postData(rutaApi,accionFactura ).then(async (response) => {

        if(response['success']==true){
            showAlert("Correcto",response['messenge'],"success")  
        }else{
            showAlert("Error",response['messenge'],"false")   
        }
        
    })
  
});

$("#tablaPrincipal").on('click', '.btnEditarTabla', function (e) {

    let id = (e.target.id).substring(2);

    window.location.href = "./altaFacturacion.php?idPedido="+id; 

})