$(document).ready(function () { //Informacion al cargar la pagina
    $('#titlePage').text('Cuentas por cobrar');
    tablaPrincipal();
})
const resetTablas=()=>{

    tablaPrincipal();
  

}

const tablaPrincipal = () => {

    var accion = {"Accion": "cxc",'Select':'getTabla' }
 
    $('#tablaPrincipal').DataTable({


        footerCallback: function (row, data, start, end, display) {
            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };


            formato = $.fn.dataTable.render.number(',', '.', 2, '$').display;

            credito = api
            .column(2, { page: 'current' })
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

            dias_15 = api
                .column(3, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);


            dias_16_30 = api
                .column(4, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            dias_30 = api
                .column(5, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);


            total = api
                .column(6, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            $(api.column(2).footer()).html('Totales: '+formato(credito));
            $(api.column(3).footer()).html('Totales: '+formato(dias_15));

            $(api.column(4).footer()).html('Totales: '+formato(dias_16_30));
            $(api.column(5).footer()).html('Totales: '+formato(dias_30));
            
            $(api.column(6).footer()).html('Totales: '+formato(total));

            let totalMX=parseFloat(total)
            let totalUSD=parseFloat(total)
            // let moneda = 1;
                console.log(totalMX);
                console.log(totalUSD);
                console.log(monedaGlobal);
            let monedaCheck= monedaGlobal.checked;
            if(monedaCheck){
                //Dolares
                 totalMX=totalMX*TCGlobal;
            }else{
                 //Pesos
                totalUSD=totalUSD/TCGlobal
            }
                // console.log(TCGlobal);
                // console.log(monedaGlobal);
                // console.log(moneda);
            $('#totalMX').text(maskMoney(totalMX))
            $('#totalUSD').text(maskMoney(totalUSD))
            
        },



        'ajax': {

            'url': rutaApi,

            'type': 'GET',

            'data': accion,

            'dataSrc': 'data',

        },

        // 'data': data,

        'order': [[1, 'desc']],

        'columns': [



            // { 'data': 'acciones' },
            { 'data': 'id' },

            { 'data': 'cliente' },

            { 'data': 'credito' ,'render': $.fn.dataTable.render.number(',', '.', 2, '$')},
            { 'data': 'dias_15' ,'render': $.fn.dataTable.render.number(',', '.', 2, '$')},
            { 'data': 'dias_16_30' ,'render': $.fn.dataTable.render.number(',', '.', 2, '$')},
            { 'data': 'dias_30' ,'render': $.fn.dataTable.render.number(',', '.', 2, '$')},
            { 'data': 'total' ,'render': $.fn.dataTable.render.number(',', '.', 2, '$')},

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

            $(row).addClass('rowTable');
      
            $(row).attr('id', data['id']);
            $(row).attr('name', data['cliente']);
          
          }

    })


    

}
const maskMoney = (num) => {
    try {
        return num.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
        
    } catch (error) {
        console.log(error)
        console.log(num)
    }
}


$('body').on('click', '.rowTable', function () {
    let id = this.id;
    let name=$(this).attr('name');

    window.location.href='facturas.php?id='+id+'&name='+name;
})
