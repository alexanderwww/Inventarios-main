$(document).ready(function () { //Informacion al cargar la pagina


    // let data = [
    //     { 'acciones': 'Btn', 'id': 10, 'proveedor': 'nombreUSer', 'vencidoMX': 1000, 'vencidoUSD': 1000, 'creditoMX': 2000, 'creditoUSD': 2000 },
    //     { 'acciones': 'Btn', 'id': 12, 'proveedor': 'nombreUSer', 'vencidoMX': 2000, 'vencidoUSD': 2000, 'creditoMX': 1000, 'creditoUSD': 1000 },
    //     { 'acciones': 'Btn', 'id': 13, 'proveedor': 'nombreUSer', 'vencidoMX': 3000, 'vencidoUSD': 3000, 'creditoMX': 9000, 'creditoUSD': 9000 },
    //     { 'acciones': 'Btn', 'id': 14, 'proveedor': 'nombreUSer', 'vencidoMX': 4000, 'vencidoUSD': 4000, 'creditoMX': 3000, 'creditoUSD': 3000 },
    //     { 'acciones': 'Btn', 'id': 15, 'proveedor': 'nombreUSer', 'vencidoMX': 6000, 'vencidoUSD': 6000, 'creditoMX': 7000, 'creditoUSD': 7000 }
    // ]

    $('#titlePage').text('Cuentas por pagar');
    tablaPrincipal();


})

const maskMoney = (num) => {
    try {
        return num.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
        
    } catch (error) {
        console.log(error)
        console.log(num)
    }
}



const tablaPrincipal = () => {

    // return;
    var accion = { "Accion": "cxp", "Select": "getTabla" }
 
    $('#tablaPrincipal').DataTable({


        footerCallback: function (row, data, start, end, display) {
            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };


            formato = $.fn.dataTable.render.number(',', '.', 2, '$').display;



            vencidoMX = api
                .column(3, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            vencidoUSD = api
                .column(4, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);


            creditoMX = api
                .column(5, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            creditoUSD = api
                .column(6, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            $(api.column(3).footer()).html(`Totales: `+formato(vencidoMX) );
            $(api.column(4).footer()).html(`Totales: `+formato(vencidoUSD) );

            let totalMX=parseFloat(vencidoMX)+parseFloat(creditoMX);
            let totalUSD=parseFloat(vencidoUSD)+parseFloat(creditoUSD);

            $('#totalMX').text(maskMoney(totalMX))
            $('#totalUSD').text(maskMoney(totalUSD))


            $(api.column(5).footer()).html(`Totales: `+formato(creditoMX) );
            $(api.column(6).footer()).html(`Totales: `+formato(creditoUSD) );

            // $(api.column(4).footer()).html(`<div><div> Totales: ${formato(creditoMX)} MX</div><div>Totales: {formato(creditoUSD)} US</div></div>`);
            // $(api.column(4).footer()).html(creditoMX);

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



            { 'data': 'acciones' },

            { 'data': 'id' },

            { 'data': 'proveedor' },

            { 'data': 'vencidoMX','render': $.fn.dataTable.render.number(',', '.', 2, '$')},
            { 'data': 'vencidoUSD','render': $.fn.dataTable.render.number(',', '.', 2, '$')},

            { 'data': 'creditoMX','render': $.fn.dataTable.render.number(',', '.', 2, '$')},
            { 'data': 'creditoUSD','render': $.fn.dataTable.render.number(',', '.', 2, '$')},

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

            $(row).attr('name', data['proveedor']);
          
          }

    })


    

}


$('body').on('click', '.rowTable', function (event) {

    if (event.target.getAttribute('type') === 'button') {
        return;
    }

    let id = this.id;
    let name=$(this).attr('name');

    window.location.href='facturas.php?id='+id+'&name='+name;

})




// --------------------------------------------------------------------------PDF 
const getDataPDF = async (id) => {

    return (await fetch(rutaApi + '?Accion=cxp&Select=getFacturaPDF&Id=' + id, {
        // return (await fetch(rutaApi + '?Accion=odv&Tabla=odv&pdf=' + id+'&createPDF=orderOrder', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    }).then(respuesta => respuesta.json())
        .then(respuesta => {
            return respuesta;
        })
    )

}


const createPDF=async(id)=>{

    let dataPDF=await getDataPDF(id);
    console.log(dataPDF['data']);
    downloadPDF([])

}


const downloadPDF= (dataPDF) => {

    let urlPDF ="../pdf/accountStatus.php"; 

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


let statusPDF;

$(document).on('click', '.btnPDFDownload', function (e) {

    statusPDF=1;

    let id = e.target.id;

    id = id.substring(2);

    console.log(id);
    createPDF(id);


})

$(document).on('click', '.btnPDFView', function (e) {

    statusPDF=0;

    let id = e.target.id;

    id = id.substring(2);

    console.log(id);
    // createPDF(id);

    let urlPDF ="../pdf/accountStatus.php"; 

    window.open(urlPDF,'_blank')

})
