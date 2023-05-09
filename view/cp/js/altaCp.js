$(document).ready(function () { //Informacion al cargar la pagina



    $('#titlePage').text('Alta Complemento de pago');


    insertRowTable(contadorInsertRow_Factoraje);
    insertRowTable(contadorInsertRow_Factoraje);
    insertRowTable(contadorInsertRow_Factoraje);
    insertRowTable(contadorInsertRow_Factoraje);


    initFetchs();

    mascarMoneda();

    $('#moneda').on('change',()=>{
        document.querySelector(`#tablaPrincipal tbody`).innerHTML='';

        insertRowTable(contadorInsertRow_Factoraje);
        insertRowTable(contadorInsertRow_Factoraje);
        insertRowTable(contadorInsertRow_Factoraje);
        insertRowTable(contadorInsertRow_Factoraje);
        $('#totalBalance').val('');
    })

})

// "timePicker": true,

let contadorInsertRow_Factoraje = 0;

const initFetchs = async () => {

    // let DATAPRUEBA = [
    //     { 'id': 1, 'nombre': 'AFGD23 - Prueba' },
    //     { 'id': 2, 'nombre': 'SDG35 - Prueba' },
    //     { 'id': 3, 'nombre': 'DFSD4 - Prueba' },
    //     { 'id': 4, 'nombre': 'SDGDS4 - Prueba' },
    //     { 'id': 5, 'nombre': 'BDR4 - Prueba' },
    //     { 'id': 6, 'nombre': 'AFGD23 - Prueba' }
    // ]


    await getData(rutaApi + '?Accion=cp&Select=formaPago').then(async(response)=>{

        console.log(response)
        let data=response.data;

        await insertDataSelect('formaDePago', data, 'Nombre', 'Id');

        $("#formaDePago").chosen({

            width: "100%",
    
            no_results_text: "No se a encontrado resultados",
    
            allow_single_deselect: true,
    
        });
    
    })




    // await insertDataSelect('financiera', DATAPRUEBA, 'nombre', 'id');

    // $("#financiera").chosen({

    //     width: "100%",

    //     no_results_text: "No se a encontrado resultados",

    //     allow_single_deselect: true,

    // });

}


// ----------------------------------------------------------------------------------------------Funciones Generales 


const initDatePicker = (id) => {

    $('#' + id).daterangepicker({
        "singleDatePicker": true,
        "autoUpdateInput": false,
        // "showDropdowns": true,

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
    },
        function (start, end, label) {
            $('#' + id).val(start.format('DD/MM/YYYY'))

            //   console.log('New date range selected: ' + start.format('YYYY-MM-DD HH:mm') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });

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

            alerta(response['messenge']);

        })

}

const insertDataSelect = async (id, data, text, key) => {

    let selectInput = document.getElementById(id);

    selectInput.innerHTML = `<option value="">Seleccione uno...</option>`;

    data.forEach(element => {

        var option = new Option(element[text], element[key]);

        selectInput.appendChild(option);

    });

    return;
}



const respValidar = (clase) => {

    let resultadoValidar = validar(clase);

    if (resultadoValidar) {
        return validarCaracteres(clase);

    } else {
        return false;
    }

}

const getDataForms = async (claseInpustData) => {

    let arrayInputsForm = document.querySelectorAll('.' + claseInpustData);

    let arrayData = [];

    arrayInputsForm.forEach(input => {

        nombreInput = input.id;

        arrayData[nombreInput] = input.value;

    })

    return arrayData;

}

// ----------------------------------------------------------------------------------------------Tabla Conceptos 

$('#tablaPrincipal tbody').on('click', '.btnDeleteRowTable', async function () {

    let tableActive = document.querySelector(`#tablaPrincipal tbody`);

    if (tableActive.childElementCount != 1) {

        this.parentNode.parentNode.remove()

        document.getElementById('totalBalance').value = maskMoney(await getTotalItemsDinamicosTable());

    }

})



// Falta keys inputs 
const cloneRowTable = (key) => (
    `
<tr>
<th>
    <button class="btn btn-danger btnDeleteRowTable btnDeleteRow_${key}" key="${key}">X</button>
</th>
<th>
    <input onkeyup="statusValidationInput(this);" class="form-control validateItemsTable" type="text" id="itemFolio_${key}" key="${key}">
</th>
<th>
    <input class="form-control validateItemsTableDate" type="text" id="itemFecha_${key}" key="${key}" >
</th>
<th>
    <div class="input-group">
        <span class="input-group-text">$</span>
        <input class="form-control mask-pesos" type="text" id="itemTotal_${key}" key="${key}" disabled>
    </div>
</th>
<th>
    <div class="input-group">
        <span class="input-group-text">$</span>
        <input class="form-control mask-pesos" type="text" id="itemSaldo_${key}" key="${key}" disabled>
    </div>
</th>
<th>
    <input class="form-control" type="text" id="itemMoneda_${key}" key="${key}" disabled>
</th>
<th>
    <input class="form-control" type="text" id="itemNoParcialidad_${key}" key="${key}" disabled>
</th>
<th>
    <div class="input-group">
        <span class="input-group-text">$</span>
        <input onkeyup="statusValidationInput(this);statusTotalBalance();" class="form-control validateItemsTable mask-pesos" type="text" id="itemMontoPago_${key}" key="${key}">
    </div>
</th>

</tr>
`
)

const statusTotalBalance=async()=>{
    document.getElementById('totalBalance').value = maskMoney(await getTotalItemsDinamicosTable());
}

$('#insertNewRow').on('click', () => {

    insertRowTable(contadorInsertRow_Factoraje)

})

const insertRowTable = (key) => {

    let newFila = cloneRowTable(key);

    let table = document.querySelector(`#tablaPrincipal tbody`);

    let newElement = document.createElement('tr');

    newElement.id = `rowTable_${key}`;
    newElement.classList.add(`rowTable`)
    newElement.setAttribute('key', key);
    table.appendChild(newElement);

    document.querySelector(`#tablaPrincipal tbody #rowTable_${key}`).innerHTML = newFila;

    initAutoComplete(key);

    initDatePicker('itemFecha_'+key);

    contadorInsertRow_Factoraje++;

    return;
}




const clearImaks = (stringNumber) => {
    let numeroSinComas = stringNumber.replace(/,/g, '');
    return parseFloat(numeroSinComas);
}

const maskMoney = (num) => {
    let numberPrecio = num.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
    return numberPrecio.slice(1);
}

// ----------------------------------------------------------------------------------------------Cliente 
// NOTA CONTINUAR CON ENVIO DE INFORMACION DE LOS DOS PRIMEROS CAMPOS

// Y PASAR APIS 


function mascarMoneda() {
    var mskPesos = document.getElementsByClassName("mask-pesos");
    // console.log(mskPesos);
    for (var imskpe = 0; imskpe < mskPesos.length; imskpe++) {
        new IMask(mskPesos[imskpe], {
            mask: Number,
            scale: 2,
            padFractionalZeros: true,
            thousandsSeparator: ',',
            radix: '.',
            mapToRadix: ['.']
        });
    }
}



const getDataItemsDinamicosTable = async () => {

    let arrayItemsDinamicos = document.querySelectorAll('#tablaPrincipal tbody .rowTable');

    let arrayData = [];

    let key;

    arrayItemsDinamicos.forEach(block => {

        key = block.getAttribute('key');

        arrayData.push({

            'Folio': block.querySelector('#itemFolio_' + key).value,
            'Fecha': block.querySelector('#itemFecha_' + key).value,
            'Total': block.querySelector('#itemTotal_' + key).value,
            'Saldo': block.querySelector('#itemSaldo_' + key).value,
            'Moneda': block.querySelector('#itemMoneda_' + key).value,
            'NoParcialida': block.querySelector('#itemNoParcialidad_' + key).value,
            'MontoPag': block.querySelector('#itemMontoPago_' + key).value

            // 'Impuestos':  clearImaks(block.querySelector('#itemImpuestos_'+key).value) 

        })

    })

    console.log(arrayData);
    return arrayData;
}



const getTotalItemsDinamicosTable = async () => {

    let arrayItemsDinamicos = document.querySelectorAll('#tablaPrincipal tbody .rowTable');

    let totales = 0;

    let key;

    let item = 0;

    arrayItemsDinamicos.forEach(block => {

        key = block.getAttribute('key');

        item = block.querySelector('#itemMontoPago_' + key).value

        if (item) {

            totales += clearImaks(item);
        }

    })

    console.log(totales);

    return totales;
}


$('#insertFactura').on('click', async () => {

    if (!respValidar('validarDataCliente')) {
        showAlert("Alerta", 'Campo de datos cliente incompleto', "danger");
        return;
    }


    if (!respValidar('validateItemsTable')) {
        showAlert("Error", "Campos incompletos en sección de complemento de pago", "danger");
        return;
    }


    if(!validarDate('validateItemsTableDate')){
        showAlert("Error", "Campos incompletos en apartado de Fecha en la Tabla", "danger");
        return;

    }


    let dataTable = await getDataItemsDinamicosTable();

    let dataPago = await getDataForms('getDataCliente');

    dataPago.totalBalance=clearImaks(dataPago.totalBalance);
    dataPago.tipoDeCambio=clearImaks(dataPago.tipoDeCambio);

    insertAltaFactura({ ...dataTable }, { ...dataPago });


})

const clearValidacionInput=(id)=>{
    $("#" + id).css({ 'border-color': '#ced4da',"border-weight": "0" });
    $("#ul_"+id).css({'display':'none'})

}



const validarDate=(claseInpustData)=>{

        let arrayInputsForm = document.querySelectorAll('.' + claseInpustData);
    
        let statusValidacion=true;

        arrayInputsForm.forEach(input => {

            if(!input.value){

                statusValidacion=false;
            }
    
        })
    
        return statusValidacion;
    
}


let insertAltaFactura = async (dataTable, dataPago) => {

    let data = {
        info: dataPago,
        complementosPago: dataTable
    }

    console.log(data);

    let accionFactura = { "Accion": "cp", 'Select': 'insertCp', 'data': data };


    await postData(rutaApi, accionFactura).then(async (response) => {

        if (response['success'] == true) {
            showAlert("Correcto", response['messenge'], "success")
            setTimeout(function () { window.location.href = "index.php"; }, 2000);
        } else {
            showAlert("Error", response['messenge'], "false")
        }

    })


}


const statusValidationInput = (event) => { event.style.border = '1px solid #d9dee3' };

$(function () {
    $('#fechaPago').daterangepicker({
        timePicker: true,

        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,

        "locale": {
            "format": "DD/MM/YYYY HH:mm",
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
        console.log(start);

        // console.log(end);
        // start.format('YYYY-MM-DD HH:mm') 
        console.log('New date range selected: ' + start.format('YYYY-MM-DD HH:mm') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');

    });

    // $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
    //     $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    // });


});


const oneIMask = (id) => {
    let miInput = document.getElementById(id);

    new IMask(miInput, {
        mask: Number,
        scale: 2,
        padFractionalZeros: true,
        thousandsSeparator: ',',
        radix: '.',
        mapToRadix: ['.']
    });
}







// ---------------------------------------------------------------------Autocomplete
const initAutoComplete = async (key) => {
    $('#itemFolio_' + key).autocomplete({
        // serviceUrl: rutaApi+'?Accion=factura&Select=autoCompleteOdv',
        lookup: async function (query, done) {
            // Do Ajax call or lookup locally, when done,
            // call the callback and pass your results:
            console.log(query);
            let moneda=$('#moneda').val();
            //mandar la moneda
            let result = await getData(rutaApi + '?Accion=cp&Select=autoCompleteOdv&String=' + query+'&moneda='+moneda);

            if (result.length != 0) {
                done(result.data);
            }
        },
        minChars: 1,
        onSelect: async function (suggestion) {
            console.log('Datos:' + suggestion + ', Key:' + key);
            await insertValuesAutoComplete(key, suggestion.value);
            // $('#totalBalance').val(await getTotalItemsDinamicosTable())
        }
    });
}


const insertValuesAutoComplete = async (key, id) => {

    let result = await getData(rutaApi + '?Accion=cp&Select=getFactura&Id=' + id);

    result = result.data[0];

    // $('#itemFolio_'+key).val(data.Folio)
    $('#itemFecha_' + key).val(result.Fecha)

    $('#itemTotal_' + key).val(result.Total)
    $('#itemSaldo_' + key).val(result.Saldo)
    $('#itemMoneda_' + key).val(result.Moneda)

    $('#itemNoParcialidad_' + key).val(result.NoParcialidad)

    $('#itemMontoPago_' + key).val(result.MontoPago)

    document.getElementById('totalBalance').value = maskMoney(await getTotalItemsDinamicosTable());

    oneIMask('itemTotal_' + key);
    oneIMask('itemSaldo_' + key);
    oneIMask('itemMontoPago_' + key);

    oneIMask('totalBalance');

    clearValidacionInput('itemMontoPago_'+key);
}

const calcularRowTotalesAutoComplete = async (arrayData) => {

    let total;

    arrayData.forEach(data => {
        total = data.Cantidad * data.Precio

        document.getElementById('itemTotal_' + data.key).value = maskMoney(total);

    })

}


// const formateDatePicker = (string) => {
//     const fecha = new Date(string);

//     // Paso 2: Obtener el día, el mes y el año de ese objeto Date
//     const dia = fecha.getDate();
//     const mes = fecha.getMonth() + 1; // Los meses empiezan desde 0, por lo que sumamos 1
//     const anio = fecha.getFullYear();

//     // Paso 3: Formatear la cadena de fecha en el formato DD/MM/YYYY
//     const fechaFormateada = `${dia < 10 ? '0' + dia : dia}/${mes < 10 ? '0' + mes : mes}/${anio}`;

//     console.log(fechaFormateada); // Salida: "22/02/2023"
//     return fechaFormateada
// }

// --------------------------------------------------------------------------------------------------- 

// CAMBIAR TITULO A EDIT EN ALTA 
// ASIGNAR VALORES EDIT 







// const setHeader=(arrayData)=>{

//     $("#formaDePago").val();
//     $("#fechaPago").val();
//     $("#moneda").val();
//     $("#tipoDeCambio").val();
//     $("#referencia").val();


// }
