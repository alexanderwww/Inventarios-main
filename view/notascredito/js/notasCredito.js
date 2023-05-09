$(document).ready(function () { //Informacion al cargar la pagina



    $('#titlePage').text('Notas de Crédito');

    tablaPrincipal();

})


const modulo = 1000;



const resetTablas=()=>{

    tablaPrincipal();
  

}





const tablaPrincipal = () => {


    // return;

    var accion = { "Accion": "notaCredito",'Select':'getTabla' }


// return;
    var tablaSSP = $('#tablaPrincipal').DataTable({


        'ajax': {

            'url': rutaApi,

            'type': 'GET',

            'data': accion,

            'dataSrc': 'data',

        },
        // 'data': data,

        'order': [[1, 'desc']],

        'columns': [



            { 'data': 'acciones' },


            { 'data': 'id' },


            { 'data': 'factura_Relacionadas' },


            { 'data': 'status' },


            { 'data': 'nameUSer' },


            { 'data': 'moneda' },


            { 'data': 'cliente' },


            { 'data': 'importe' ,'render': $.fn.dataTable.render.number(',', '.', 2, '$')},


            { 'data': 'fechaFactura' },

            { 'data': 'fechaVencimiento' },


            { 'data': 'folioPago' },


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
                $(row).css('background-color', data.estilo);
                // $(row).css('color', '#fff');

            }


        },




    })



}


