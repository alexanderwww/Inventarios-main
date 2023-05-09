$(document).ready(function () { //Informacion al cargar la pagina


    $('#titlePage').text('Cuentas por pagar');
    initFetch();
    oneIMask('importeDePago');

})
const initFetch = async () => {
    let id = document.getElementById('facturaProveedor').value;
    let name=$('#facturaName').val();

    $('#nameProveedor').text(name);

    await getData(rutaApi + '?Accion=cxp&Select=getFacturas&Id=' + id).then(response => {


        tablaPrincipal(response['data']);
    })

}
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


const tablaPrincipal = (data) => {

    // let id = document.getElementById('facturaProveedor').value;


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



            { 'data': 'btnPago' },



            { 'data': 'id' },



            { 'data': 'folio' },

            { 'data': 'total','render': $.fn.dataTable.render.number(',', '.', 2, '$')},
            
            { 'data': 'saldo','render': $.fn.dataTable.render.number(',', '.', 2, '$')},

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

            // $(row).attr('attr_id', data['Id']);

        },


    })



}
$('#tablaPrincipal tbody').on('click', '.btnPago', function (e) {

    let btn ='btnAceptarAlta';
    let modal='modalAlta';

    initModal(this, btn,modal);



});
const initModal = async (element, btn,modal) => {

    let id= $(element).attr('id');
    $('.'+btn).attr('id', id);
    $('#'+modal+'Title').text(id);
    $('#'+modal).modal('show');
    let accionTipoPago = '?Accion=cxp&Select=formaPago';
    let accionMetodoPago = '?Accion=cxp&Select=metodoPago';
    await getSelect(accionMetodoPago,'metodoPago')
    await getSelect(accionTipoPago,'tipoDePago')

    $("#tipoDePago").chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });
    $("#metodoPago").chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });

    await getData(rutaApi + '?Accion=cxp&Select=getFacturaId&Id=' + id).then(response => {

        insertValuePago(response['data']);
    })


};


$('.btnAceptarAlta').on('click',async()=>{
    // getDataMetodoDePago
    // validarMetodoPago
    if (!respValidar('validarMetodoPago')) {
        showAlert("Alerta", 'Campos incompletos', "danger");
        return;
    }



    let arrayData=await getDataForms('getDataMetodoDePago');
    
    arrayData.importeDePago=clearImaks(arrayData.importeDePago)
    // Accion=cxp&Select=getFacturaId&Id
    let accion={'Accion':'cxp','Select':'aplicarPago','data': {...arrayData} };

    await postData(rutaApi,accion).then((response)=>{
                
        if (response.success) {
            showAlert("Correcto", response['messenge'], "success");
            $('#modalAlta').modal('hide')
        } else {
            showAlert("Error", response['messenge'], "false")
        }

    })

})


$(function () {
    $('#fechaDePago').daterangepicker({
        // timePicker: true,

        singleDatePicker: true,
        showDropdowns: true,
        minYear: 2000,

        "locale": {
            "format": "DD/MM/YYYY",
            "applyLabel": "Aceptar",
            "cancelLabel": "Cancelar",
            "customRangeLabel": "Custom",
            "weekLabel": "W",
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
            "firstDay": 1,

            // "cancelLabel": 'Clear'

        },

        //   maxYear: parseInt(moment().format('YYYY'),10)
    }, function (start, end, label) {

    });




});

const getDataForms = async (claseInpustData) => {

    let arrayInputsForm = document.querySelectorAll('.' + claseInpustData);

    let arrayData = [];

    arrayInputsForm.forEach(input => {

        nombreInput = input.id;

        arrayData[nombreInput] = input.value;

    })

    return arrayData;

}

const respValidar = (clase) => {

    let resultadoValidar = validar(clase);

    if (resultadoValidar) {
        return validarCaracteres(clase);

    } else {
        return false;
    }

}


const getSelect=async (accion,clase)=>{

    return await fetch(rutaApi+accion,{

        method: 'GET',

        headers: {'Content-Type': 'application/json'}

    }).then(respuesta=>respuesta.json())

    .then(respuesta =>{

        insertSelectInput(clase,respuesta['data']);
        return respuesta;
    })

}
const getAccion = async (accion) => {

    return (await fetch(rutaApi + accion, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    }).then(respuesta => respuesta.json())

    .then(respuesta=>{
        return respuesta
    })
    )

}
const insertValuePago=(data)=>{



    // document.getElementById('Folio_alta').value=data['id'];

    // document.getElementById('Moneda_alta').value=data['moneda'];

    // document.getElementById('Emisor_alta').value=data['emisor'];

    // document.getElementById('Fecha_alta').value=data['fecha'];

    // document.getElementById('Total_alta').value=mascarMonedaInputs(data['total']);

    // // document.getElementById('Cliente_edit').value=data['Id_cliente'];

    // document.getElementById('Nombre_edit').value=data['Id'];


    

    $('#emisor').val(data['emisor']);

    $('#total').val( maskMoney(data['total']).slice(1) );
    
    $('#folioFactura').val(data['id']);
    $('#moneda').val(data['moneda']);
    $('#fechaFactura').val(formatFecha(data['fecha']));

    return true;

};

const oneIMask=(id)=>{
    let miInput=document.getElementById(id);

    new IMask(miInput, {
        mask: Number,
        scale: 2,
        padFractionalZeros: true,
        thousandsSeparator: ',',
        radix: '.',
        mapToRadix: ['.']
    });
}

const insertSelectInput=(id,data)=>{

    let selectInput = document.getElementById(id);

    selectInput.innerHTML=`<option value="">Seleccione uno...</option>`;

    data.forEach(element => {

        var option = new Option(element['Nombre'],element['Id']);

        selectInput.appendChild(option);
    });
    return;

}

// Add event listener for opening and closing details
$('#tablaPrincipal tbody').on('click', '.rowTable', function () {
    let table = $('#tablaPrincipal').DataTable();

    var tr = $(this).closest('tr');
    var row = table.row(tr);

    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    } else {
        // Open this row
        row.child(format(row.data())).show();
        tr.addClass('shown');
    }
});




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
            return false;
            // aler (response['messenge']);

        })

}

const format = (data) => (
`
<div>

<div class="row titleItemFacturacion" style='background:#2a2e5f'>
    <div class="d-flex justify-content-between">
        <h5 style='color:#fff' class='sinMargin'>Folio de Factura: #<strong>${data.id}</strong> </h5>
        <div>
            <h6 style='color:#fff' class='sinMargin'><strong>Tipo:</strong> ${data.tipoFactura}</h6>

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
                <small class="card-title mb-2 fontSubtitle">${maskMoney(data.subTotal)}</small>
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
                <small class="card-title mb-2 fontSubtitle">${maskMoney(data.Impuesto_retenido)}</small>
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

                <span class="card-title fontSubtitle"><strong>Fecha de factura: </strong><small class="card-title mb-2 fontSubtitle">${formatFecha(data.fecha)}</small></span>

            </div>
        </div>

    </div>

    <div class="col-4 row gap">

        <div class="row">

            <div class="col-12 align-self-center">

                <span class="card-title fontSubtitle"><strong>Fecha de inicio de crédito: </strong><small class="card-title mb-2 fontSubtitle">${data.fechaInicio}</small></span>

            </div>
        </div>

        <div class="row">

            <div class="col-12 align-self-center">

                <span class="card-title fontSubtitle"><strong>Fecha de pago:</strong> <small class="card-title mb-2 fontSubtitle">${formatFecha(data.fecha_pago)}</small></span>

            </div>
        </div>


        <div class="row">

            <div class="col-12 align-self-center">

                <span class="card-title fontSubtitle"><strong>Días de crédito:</strong> <small class="card-title mb-2 fontSubtitle">${data.dias_credito}</small></span>

            </div>
        </div>

        <div class="row">

            <div class="col-12 align-self-center">

                <span class="card-title fontSubtitle"><strong>Fecha de vencimiento:</strong> <small class="card-title mb-2 fontSubtitle">${data.fecha_vencimiento}</small></span>

            </div>
        </div>

    </div>


</div>


</div>
`
)

const formatFecha=(string)=>{
    try {
        let fecha=string.split(' ');
        fecha=fecha[0]
        const fechaPartes = fecha.split("-");
        return `${fechaPartes[2]}/${fechaPartes[1]}/${fechaPartes[0]}`;
    
    } catch (error) {
        return 'No definida'
    }
}

const maskMoney = (num) => {
    try {
        num=parseFloat(num)

        return num.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
        
    } catch (error) {
    }
}


const clearImaks=(stringNumber)=>{
    try {

        let numeroSinComas = stringNumber.replace(/,/g, '');
        return parseFloat(numeroSinComas);
    
    } catch (error) {
        console.log(stringNumber)
        return;
    }
}



// Indicadores Poner su text y validando el estatus de dias 
$('#metodoPago').on('change', function () {

    let option = $(this).val();

    valiacionMetodoPagoOption(option);

})

const valiacionMetodoPagoOption=(option)=>{
    switch (option) {
        case '1': //Efectivo
            // statusContainer(false)
            // addClassValidacion();
            document.getElementById('containerMetodoDePago').innerHTML=''
            break;
        case '7': //Cheke
            // statusContainer(true)
            // $('#metodoDePagoText_1').text('Banco')
            // $('#metodoDePagoText_2').text('Número de Cheque');
            // addClassValidacion();
            document.getElementById('containerMetodoDePago').innerHTML=inputMetodoDePago('Banco','Número de Cheque');
    
            break;
            case '9': //Transferencia

            // statusContainer(true)
            // $('#metodoDePagoText_1').text('Banco')
            // $('#metodoDePagoText_2').text('Número de Referencia');
            // addClassValidacion();
            document.getElementById('containerMetodoDePago').innerHTML=inputMetodoDePago('Banco','Número de Referencia');

            break;    
            case '10': // Deposito Bancario  
                // statusContainer(true)
                // $('#metodoDePagoText_1').text('Banco')
                // $('#metodoDePagoText_2').text('Número de Referencia');
                // addClassValidacion();
                document.getElementById('containerMetodoDePago').innerHTML=inputMetodoDePago('Banco','Número de Referencia');
    
            break;

            case '11': // Nota de credito  
            // statusContainer(true)
            // $('#metodoDePagoText_1').text('Refencia Nota de Crédito')
            // $('#metodoDePagoText_2').text('No Enviar');
            // addClassValidacion();
            document.getElementById('containerMetodoDePago').innerHTML=inputMetodoDePago('Referencia de nota de crédito');

        break;
                 
        default:
            document.getElementById('containerMetodoDePago').innerHTML='';
            break;
    }
}

const inputMetodoDePago = (textInput_1,textInput_2=false) => (
    `
    <div class="col-12" style="padding-top: 0.4rem;">
        <label id="metodoDePagoText_1">${textInput_1}</label>
        <input onkeyup="sobreinput(event);" class="form-control getDataMetodoDePago validarMetodoPago" type="text" id="metodoDePagoInput_1">
        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_metodoDePagoInput_1" class="form_text_adv"></p>
    </div>
    ${
        !textInput_2?'':`
            <div class="col-12 " style="padding-top: 0.4rem;">
                <div id="containerMetodoPagoInput_2">
                    <label id="metodoDePagoText_2">${textInput_2}</label>
                    <input onkeyup="sobreinput(event);" class="form-control getDataMetodoDePago validarMetodoPago" type="number" id="metodoDePagoInput_2">
                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_metodoDePagoInput_2" class="form_text_adv"></p>
                </div>
            </div>
        `
    }
    `
)


$('#tipoDePago').on('change',function(){
    
    let tipoDePago=$(this).val();

    if(tipoDePago==1){
        $('#importeDePago').attr('disabled',true);
        $('#importeDePago').val( $('#total').val() );


    }else{
        $('#importeDePago').attr('disabled',false);
        $('#importeDePago').val('');

    }
})

