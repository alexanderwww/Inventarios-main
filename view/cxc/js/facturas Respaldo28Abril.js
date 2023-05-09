$(document).ready(function () { //Informacion al cargar la pagina


    $('#titlePage').text('Cuentas por cobrar');

    initFetch();

})


const initFetch = async () => {
    let id = document.getElementById('facturaCliente').value;
    
    let name = document.getElementById('facturaName').value;
    $('#nameFactura').text(name);
    await getData(rutaApi + '?Accion=cxc&Select=getPedidos&Id=' + id).then(response => {

        if(!response.success){
            return;
        }
        response.data.forEach((element, position) => {

            response.data[position].fechaInicio = `<input class="form-control styleInputDate" id="fechaCredito${element.id}" value='${element.fechaInicio ? element.fechaInicio : ''}' type="text" >`
            response.data[position].fechaPromesa = `<input class="form-control styleInputDate" id="fechaPago${element.id}" value='${element.fechaPromesa ? element.fechaPromesa : ''}' type="text" >`

        });


        tablaPrincipal(response['data']);
    })

}

const tablaPrincipal = (data) => {


    var accion = { "Accion": "odt", "Tabla": "odt" }



    var tablaSSP = $('#tablaPrincipal').DataTable({

        // 'ajax': {

        //     'url': rutaApi,

        //     'type': 'GET',

        //     'data': accion,

        //     'dataSrc': 'data',

        // },


        'data': data,

        'order': [[1, 'desc']],

        'columns': [



            // { 'data': 'acciones' },



            { 'data': 'status',render:function(data,display,array){

                    return `<span style='background: ${array.estilo?array.estilo:'none'};padding: 0.5rem;border-radius: 6px;color: #fff;'>${data}</span>`;
            } },


            { 'data': 'id' },

            {
                'data': 'fechaPromesa', render: function (data, display, array) {
                    
                    initDatePicker('fechaPago' + array.id, 0,{id:array.id});

                    return data;

                }
            },

            {
                'data': 'fechaInicio', render: function (data, display, array) {

                    $('#fechaCredito'+array.id).attr('fechavencimiento',array.fecha_vencimiento);
                    $('#fechaCredito'+array.id).attr('diasCredito',array.dias_credito);

                    initDatePicker('fechaCredito' + array.id, 1,{id:array.id});

                    return data;

                }
            },


            { 'data': 'saldo', 'render': $.fn.dataTable.render.number(',', '.', 2, '$') },

            { 'data': 'total', 'render': $.fn.dataTable.render.number(',', '.', 2, '$') },

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

                // NOTA CAMBIO 999 COMENTADO
                // if(data.estilo){
                    // $(row).css('background-color',data.estilo);
                    // $(row).css('color','#fff');
                // }

        },


    })



}


const initDatePicker = (id, typeDate,obj) => {


    $('#' + id).daterangepicker({
        "singleDatePicker": true,
        "autoUpdateInput": false,
        "locale": {
            "format": "DD/MM/YYYY",
            "applyLabel": "Aceptar",
            "cancelLabel": "Cancelar",

            "daysOfWeek": [
                "Lu",
                "Ma",
                "Mi",
                "Jue",
                "Vie",
                "Sab",
                "Do"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
        }
    },
        async function (start, end, label) {

            $('#' + id).val(start.format('DD/MM/YYYY'))


            let accionDate;

            let dateInput=start.format('DD/MM/YYYY');

            if (typeDate === 0) {
                accionDate = { "Accion": "cxc", 'Select': 'fechaPromesaPago', 'data':{
                    'date':dateInput,
                    'id':obj.id
                }}
            } else {

                let dateVencimiento=sumarDayOnDate( start.format('YYYY-MM-DD') , $('#'+id).attr('diasCredito') );

                console.log(dateVencimiento);

                accionDate = { "Accion": "cxc", 'Select': 'fechaInicioCredito','data':{
                    'date':dateInput,
                    'id':obj.id,
                    'fechaVencimiento': dateVencimiento

                    // 'fechaVencimiento':$('#'+id).attr('fechavencimiento'),
                    // 'diasCredito':$('#'+id).attr('diasCredito')
                }}
            }


                
                await postData(rutaApi, accionDate).then(async (response) => {
                    if(!response){
                        return;
                    }
                    if(response.success){
                        showAlert("Correcto", response['messenge'], "success");
                        initFetch();
                        return;
                    }

                    showAlert("Alerta", response['messenge'], "danger")
                    return;
    
                })


        }


    )

}

const sumarDayOnDate = (fecha,dias) => {



        let fechaOriginal = new Date(fecha);
        let nuevaFecha = new Date(fechaOriginal);
        console.log(nuevaFecha);
        nuevaFecha.setDate(nuevaFecha.getDate() + parseInt(dias));
        return convertirFecha(nuevaFecha.toLocaleDateString());
}

function convertirFecha(fecha) {
    if(!fecha){
        return 'No Definido'
    }
    return fecha.replace(/(\d{4})-(\d{2})-(\d{2})/, "$3/$2/$1");

    // const fechaOriginal = new Date(fecha);
    // const dia = fechaOriginal.getDate().toString().padStart(2, '0');
    // const mes = (fechaOriginal.getMonth() + 1).toString().padStart(2, '0');
    // const anio = fechaOriginal.getFullYear().toString();
    // return `${dia}/${mes}/${anio}`;
  }


// Add event listener for opening and closing details
$('#tablaPrincipal tbody').on('click', '.rowTable', function (event) {
    let table = $('#tablaPrincipal').DataTable();

    if (event.target.getAttribute('type') === 'text') {
        return;

    }

    var tr = $(this).closest('tr');
    var row = table.row(tr);

    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    } else {
        // Open this row
        console.log(row.data);

        row.child(format(row.data())).show();
        tr.addClass('shown');
    }
});




const format = (data) => (
    `
    <div>

    <div class="row titleItemFacturacion" style='background:#2a2e5f'>
        <div class="d-flex justify-content-between">
            <h5 style='color:#fff' class='sinMargin'>Folio de Factura: #<strong>${data.id}</strong> </h5>
            <div>
                <h6 style='color:#fff' class='sinMargin'>Tipo: ${data.tipoFactura}</h6>

            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-4 row gap">

            <div class="row">

                <div class="col-6 align-self-center">
                    <span class="card-title fontSubtitle"><strong>Subtotal:</strong></span>
                </div>
                <div class="col-6">
                    <small class="card-title mb-2 fontSubtitle">${maskMoney(data.subtotal)}</small>
                </div>

            </div>

            <div class="row">

                <div class="col-6 align-self-center">
                    <span class="card-title fontSubtitle"><strong>Impuesto:</strong></span>
                </div>
                <div class="col-6">
                    <small class="card-title mb-2 fontSubtitle">${maskMoney(data.impuesto)}</small>
                </div>

            </div>

            <div class="row">

                <div class="col-6 align-self-center">
                    <span class="card-title fontSubtitle"><strong>Impuesto retenido:</strong></span>
                </div>
                <div class="col-6">
                    <small class="card-title mb-2 fontSubtitle">${maskMoney(data.impuestoR)}</small>
                </div>

            </div>

            <div class="row lineaTotal"></div>

            <div class="row">

                <div class="col-6 align-self-center">
                    <span class="card-title fontSubtitle"><strong>Total:</strong></span>
                </div>
                <div class="col-6">
                    <small class="card-title mb-2 fontSubtitle">${maskMoney(data.total)}</small>
                </div>

            </div>

        </div>

        <div class="col-4 row gap">

            <div class="row">

                <div class="col-12 align-self-center">

                    <span class="card-title fontSubtitle"><strong>Moneda:</strong> <small class="card-title mb-2 fontSubtitle">${data.moneda}</small></span>

                </div>
            </div>

            <div class="row">

                <div class="col-12 align-self-center">

                    <span class="card-title fontSubtitle"> <strong>Importe pagado/Aplicado:</strong> <small class="card-title mb-2 fontSubtitle">${maskMoney(data.importe_pagado)}</small></span>

                </div>
            </div>

            <div class="row">

                <div class="col-12 align-self-center">

                    <span class="card-title fontSubtitle"><strong>Saldo:</strong> <small class="card-title mb-2 fontSubtitle">${maskMoney(data.saldo)}</small></span>

                </div>
            </div>

            <div class="row">

                <div class="col-12 align-self-center">

                    <span class="card-title fontSubtitle"><strong>Fecha de factura:</strong> <small class="card-title mb-2 fontSubtitle">${data.fecha}</small></span>

                </div>
            </div>

        </div>

        <div class="col-4 row gap">

            <div class="row">

                <div class="col-12 align-self-center">

                <!--   <span class="card-title fontSubtitle">Fecha de promesa de pago: <small class="card-title mb-2 fontSubtitle">${data.fechaPromesaDePago}</small></span> -->

                </div>
            </div>

            <div class="row">

                <div class="col-12 align-self-center">

                  <!--  <span class="card-title fontSubtitle">Fecha de inicio de crédito: <small class="card-title mb-2 fontSubtitle">${data.fechaInicioCredito}</small></span> -->

                </div>
            </div>


            <div class="row">

                <div class="col-12 align-self-center">

                    <span class="card-title fontSubtitle"><strong>Días de crédito:</strong> <small class="card-title mb-2 fontSubtitle">${data.dias_credito}</small></span>

                </div>
            </div>

            <div class="row">

                <div class="col-12 align-self-center">

                    <span class="card-title fontSubtitle"><strong>Fecha de vencimiento:</strong> <small class="card-title mb-2 fontSubtitle">${ convertirFecha(data.fechaVencimiento,0) }</small></span>

                </div>
            </div>

        </div>


    </div>


</div>
`
)


async function getData(accion) {

    return await fetch(accion, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }

    }).then(response => response.json())

        .then(response => {

            if (response['success']) {

                return response;
            }

            alerta(response['messenge']);

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

            // alert(response['messenge']);

        })

}


const maskMoney = (num) => {
    try {
        num=parseFloat(num)

        return num.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
        
    } catch (error) {
        console.log(error)
        console.log(num)
    }
}

