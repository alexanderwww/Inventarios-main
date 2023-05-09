$(document).ready(function () { //Informacion al cargar la pagina



    $('#titlePage').text('Facturación');



    tablaPrincipal();

})


const modulo = 1000;



const resetTablas=()=>{

    tablaPrincipal();
  

}





const tablaPrincipal = () => {


    // return;

    var accion = { "Accion": "factura",'Select':'getTabla' }



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



        "scrollCollapse": true,



        "paging": false,

        createdRow: function (row, data) {

            if(data.estilo){
                $(row).css('background-color',data.estilo);
                // $(row).css('color','#fff');
            }

          },
      



    })



}






function descargarArchivo(id) {
    fetch('../')
      .then(response => response.blob())
      .then(blob => {
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = id+'.zip';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      });
  }


// --------------------------------------------------------------------------PDF 
// var accion = { "Accion": "factura",'Select':'getTabla' }

const getDataPDF = async (id) => {

    
    return (await fetch(rutaApi + '?Accion=factura&Select=createPDF&id='+id, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    }).then(respuesta => respuesta.json())
        .then(respuesta => {
            return respuesta;
        })
    )

}


const createPDF=async(id)=>{



    if(statusPDF==0){

        window.open('viewPDF.php?id='+id+'&statusPDF='+statusPDF);
    
        return;
    }

    let dataPDF=await getDataPDF(id);

    downloadPDF(dataPDF['data'])
}


const downloadPDF= (dataPDF) => {

    let urlPDF ="../pdf/factura.php"; 

    let form = document.createElement("form");

    form.setAttribute("method", "post");

    form.setAttribute("action", urlPDF);

    form.appendChild(createInputForm('dataPDF',JSON.stringify(dataPDF)));
    form.appendChild(createInputForm('statusPDF',JSON.stringify(statusPDF)));

    document.body.appendChild(form);

    form.submit();

    document.body.removeChild(form);

};


const createInputForm = (name, value) => {

    let inputCreateForm = document.createElement("input");

    inputCreateForm.type = "hidden";

    inputCreateForm.name = name;

    inputCreateForm.value = value;

    return inputCreateForm;
}




let keyFacturacion=null;

$(document).on('click', '.btnPDFDownloadZip', function (e) {

    let id = e.target.id;
    keyFacturacion = id.substring(2);    
    $("#modalStatus").modal('show')
    $('.numFactura').text("#"+keyFacturacion);
})

$('.btnAcepta_modalStatus').on('click',()=>{

    initCFDIS(keyFacturacion);
    keyFacturacion=null;
})


const initCFDIS=(id)=>{
    
    let accionFactura = { "Accion": "factura",'Select':'timbrarFactura4','data':id};
    postData(rutaApi,accionFactura ).then(async (response) => {
        if(response['success']==true){
            dir = "../../Data/facturas/"+response['data']+'.zip';
            descargarArchivo(dir);
        showAlert("Correcto",response['messenge'],"success")  
        }else{
            showAlert("Error",response['messenge'],"false")   
        }
    })

}





const postData = async (rutaAccion, accion) => {

    return await fetch(rutaAccion, {
        method: 'POST',
        body: JSON.stringify(accion),
        headers: { 'Content-Type': 'application/json' }
    }).then(response => response.json())
        .then(response => {
            return response;
        }).catch(response => {
            console.log('ERROR EN POST:' + response);
        })

}

let response={
    success:true,
    data:'12_9_evidencia.pdf',
    messenge:'messenge'
};


// CAMBIO ALEXANDER 04/19

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



//-------------------------------------------- ACCIONES EDIT FACTURA

$("#tablaPrincipal").on('click', '.btnEditarTabla', function (e) {

    let id = (e.target.id).substring(2);

    window.location.href = "./altaFacturacion.php?idPedido="+id; 

})

//-------------------------------------------- ACCIONES FACTURA NO TIMBRADA

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
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `factura_${id}.pdf`;
            a.click();
        });

});




$("#tablaPrincipal").on("click", ".btnFacturaGenerarPDF", function (e) {
  let id = e.target.id.substring(2);

  let accionFactura = {
    Accion: "factura",
    Select: "pdfFactura",
    idFactura: id,
  };

//   fetch(rutaApi, {
//     method: "POST",
//     body: JSON.stringify(accionFactura),
//     headers: { "Content-Type": "application/json" },
//   })
//     .then((response) => response.blob())
//     .then((blob) => {
//       const url = URL.createObjectURL(blob);
//       const a = document.createElement("a");
//       a.href = url;
//       a.download = "archivo.pdf";
//       a.click();
//     });

});


$("#tablaPrincipal").on("click", ".btnFacturaCancelar", function (e) {

    let id = e.target.id.substring(2);

    let accionFactura = { "Accion": "factura",'Select':'cancelarFactura','idFactura':id};

    postData(rutaApi,accionFactura ).then(async (response) => {

        if(response['success']==true){
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

