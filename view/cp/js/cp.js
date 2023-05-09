$(document).ready(function () { //Informacion al cargar la pagina



    $('#titlePage').text('Complementos de pago');



    tablaPrincipal();

})


const modulo = 1000;



const resetTablas=()=>{

    tablaPrincipal();
  

}





const tablaPrincipal = () => {


    // return;

    var accion = {"Accion": "cp",'Select':'getTabla' }



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

            { 'data': 'folio' },

            { 'data': 'agente' },

            { 'data': 'cliente' },

            // { 'data': 'financiera' },

            { 'data': 'monto','render': $.fn.dataTable.render.number(',', '.', 2, '$')},

            { 'data': 'moneda' },

            { 'data': 'fecha','render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss',' DD/MM/YYYY HH:mm')},

            { 'data': 'fecha_pago','render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss',' DD/MM/YYYY HH:mm')},

            { 'data': 'status' },

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