$(document).ready(function () { //Informacion al cargar la pagina



    $('#titlePage').text('Edit Facturación');


    // insertRowTable(contadorInsertRow_concepto);
    // insertRowTable(contadorInsertRow_concepto);
    // insertRowTable(contadorInsertRow_concepto);
    // insertRowTable(contadorInsertRow_concepto);
    // Nota agregar datoos de la API 

    initFetchs();

    mascarMoneda();

})


let contadorInsertRow_concepto = 0;
let contadorInsertRow_CFDI = 0;

const initFetchs = async () => {

    let accionClientes = { "Accion": "getClientes" }
    let rutaClientes = '../clientes/Controlador/clientesApi.php';

    await postData(rutaClientes, accionClientes).then(async (response) => {

        await insertDataSelect('cliente', response.Data, 'RazonSocial', 'Id');

        $("#cliente").chosen({

            width: "100%",

            no_results_text: "No se a encontrado resultados",

            allow_single_deselect: true,

        });



    });


    // NOTA: CAMBIAR RUTAS y ACCIONES 

    let accionFolio = { "Accion": "getFolio" }
    // CAMBIAR 
    let rutaGeneral = '../clientes/Controlador/clientesApi.php';


    await getData(rutaApi + '?Accion=factura&Select=getFolio').then(async(response)=>{
        let folio=response.data;
        document.getElementById('folio').value=folio;

    })


    // await postData(rutaFolio, accionFolio).then(async (response) => {

        // let data=response.Data;

        let  DATAPRUEBA=[
            {'id':1,'nombre':'AFGD23 - Prueba'},
            {'id':2,'nombre':'SDG35 - Prueba'},
            {'id':3,'nombre':'DFSD4 - Prueba'},
            {'id':4,'nombre':'SDGDS4 - Prueba'},
            {'id':5,'nombre':'BDR4 - Prueba'},
            {'id':6,'nombre':'AFGD23 - Prueba'}
        ]

        await insertDataSelect('usoCFDI', DATAPRUEBA, 'nombre', 'id');

        $("#usoCFDI").chosen({

            width: "100%",

            no_results_text: "No se a encontrado resultados",

            allow_single_deselect: true,

        });

        await insertDataSelect('regimenFiscal', DATAPRUEBA, 'nombre', 'id');

        $("#regimenFiscal").chosen({

            width: "100%",

            no_results_text: "No se a encontrado resultados",

            allow_single_deselect: true,

        });

        await insertDataSelect('metodoDePago', DATAPRUEBA, 'nombre', 'id');

        $("#metodoDePago").chosen({

            width: "100%",

            no_results_text: "No se a encontrado resultados",

            allow_single_deselect: true,

        });

        await insertDataSelect('CFDIrelacionado', DATAPRUEBA, 'nombre', 'id');
        
        $("#CFDIrelacionado").chosen({

            width: "100%",

            no_results_text: "No se a encontrado resultados",

            allow_single_deselect: true,

        });

        await insertDataSelect('formaDePago', DATAPRUEBA, 'nombre', 'id');

        $("#formaDePago").chosen({

            width: "100%",

            no_results_text: "No se a encontrado resultados",

            allow_single_deselect: true,

        });
        
}




// ----------------------------------------------------------------------------------------------Funciones Generales 


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

const insertDataSelectCFDIS = async (id, data, text, key,attrName) => {

    let selectInput = document.getElementById(id);

    selectInput.innerHTML = `<option value="">Seleccione uno...</option>`;

    data.forEach(element => {

        var option = new Option(element[text], element[key]);

        option.setAttribute('date',element[attrName]);

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

$('#tablaPrincipal tbody').on('click', '.btnDeleteRowConceptos', async function () {

    let tableActive = document.querySelector(`#tablaPrincipal tbody`);

    if (tableActive.childElementCount != 1) {

        this.parentNode.parentNode.remove()

        calculoBalanceConceptos(await getDataItemsDinamicosTable());

    }

})

$('#container_CFDI').on('click', '.btnDeleteRowCFDI', function () {

    // let tableActive = document.querySelector(`#container_CFDI`);

    // if (tableActive.childElementCount != 1) {

        this.parentNode.parentNode.remove()

    // }

})

// Falta keys inputs 
const cloneRowConceptos = (key) => (
    `
    <tr>
    <th>
        <button class="btn btn-danger btnDeleteRowConceptos btnDeleteRowConceptos_${key}" key="${key}">X</button>
    </th>
    <th>
        <input  onkeyup="statusValidationInput(this);"  autocomplete="off" class="form-control validateItemsConceptos" type="text" id="itemOdv_${key}" key="${key}">
    </th>
    <th>
        <input  onkeyup="statusValidationInput(this);"  autocomplete="off" class="form-control validateItemsConceptos" type="text" id="itemCodigo_${key}" key="${key}">
    </th>
    <th>
        <input  onkeyup="statusValidationInput(this);"  autocomplete="off" class="form-control validateItemsConceptos" type="text" id="itemClaveProdServ_${key}" key="${key}">
    </th>
    <th>
        <input  onkeyup="statusValidationInput(this);"  autocomplete="off" class="form-control validateItemsConceptos" type="text" id="itemDescripcion_${key}" key="${key}">
    </th>
    <th>
        <input  onkeyup="statusValidationInput(this);" oninput="statusBalance(this);" autocomplete="off" class="form-control validateItemsConceptos mask-pesos" type="text" id="itemCantidad_${key}" key="${key}">
    </th>
    <th>
        <input  onkeyup="statusValidationInput(this);"  autocomplete="off" class="form-control validateItemsConceptos" type="text" id="itemUnidad_${key}" key="${key}">
    </th>
    <th>
        <div class="input-group">
            <span class="input-group-text">$</span>
            <input onkeyup="statusValidationInput(this);" oninput="statusBalance(this);" class="form-control validateItemsConceptos mask-pesos" type="text" id="itemPrecio_${key}" key="${key}">
        </div>
    </th>
    
    <th>
        <div class="input-group">
            <span class="input-group-text">$</span>
            <input class="form-control mask-pesos" type="text" id="itemTotal_${key}" key="${key}" disabled>
        </div>
    </th>

    <th>
        <select onChange='statusBalance(this)' class="form-select" autocomplete="off" style="width:100%;" id="itemImpuestos_${key}" key="${key}">
            <option value=".00">0.00%</option>
            <option value=".08">8.00%</option>
            <option value=".16">16.00%</option>
            <option value=".12">16.00% y Retención</option>
        </select>
    </th>

</tr>
    `
)

$('#insertConcepto').on('click', () => {

    insertRowTable(contadorInsertRow_concepto)

})

const insertRowTable = (key) => {

    let newFila = cloneRowConceptos(key);

    let table = document.querySelector(`#tablaPrincipal tbody`);

    let newElement = document.createElement('tr');

    newElement.id = `rowTable_${key}`;
    newElement.classList.add( `rowTable`)
    newElement.setAttribute('key',key);
    table.appendChild(newElement);

    document.querySelector(`#tablaPrincipal tbody #rowTable_${key}`).innerHTML = newFila;

    initAutoComplete(key);
    
    contadorInsertRow_concepto++;

    return;
}



// ---------------------------------------------------------------------Autocomplete
const initAutoComplete=async(key)=>{
    $('#itemOdv_'+key).autocomplete({
        // serviceUrl: rutaApi+'?Accion=factura&Select=autoCompleteOdv',
        lookup: async function (query, done) {
            // Do Ajax call or lookup locally, when done,
            // call the callback and pass your results:
            console.log(query);

            let result=await getData(rutaApi+'?Accion=factura&Select=autoCompleteOdv&String='+query);
            
            if(result.length!=0){
                done(result.data);
            }
        },
        minChars: 1,
        onSelect: function (suggestion) {
            console.log('Datos:'+suggestion+', Key:'+key);
            insertValuesAutoComplete(key);
        }
    });
}


const insertValuesAutoComplete=async(key)=>{

    let data={
        Codigo:'Codigo',
        ClaveProdServ:'ClaveProdServ',
        Descripcion:'Descripcion',
        Cantidad:2,
        Unidad:'Unidad',
        Precio:123,
        Impuestos:'.12'
    }
    
    $('#itemCodigo_'+key).val(data.Codigo);
    $('#itemClaveProdServ_'+key).val(data.ClaveProdServ);
    $('#itemDescripcion_'+key).val(data.Descripcion);

    $('#itemCantidad_'+key).val(data.Cantidad);
    $('#itemUnidad_'+key).val(data.Unidad);
    $('#itemPrecio_'+key).val(data.Precio);
    $('#itemImpuestos_'+key).val(data.Impuestos); 


    oneIMask('itemPrecio_'+key);

     await calcularRowTotalesAutoComplete(await getDataItemsDinamicosTable(true));

     calculoBalanceConceptos( await getDataItemsDinamicosTable() );

}


// --------------------------------------------------------------------------------------------------- 

const statusBalance=(event)=>{
    let key=event.getAttribute('key');
    // console.log(key);
    calculoRowTable(key)
}


const calculoRowTable=async(key)=>{

    let cantidad=document.getElementById('itemCantidad_'+key).value;
    let precio=document.getElementById('itemPrecio_'+key).value;
    // let impuestos=document.getElementById('itemImpuestos_'+key).value;
    
    let total=document.getElementById('itemTotal_'+key);


    if(cantidad && precio){

        total.value=maskMoney(clearImaks(cantidad)*clearImaks(precio));

        calculoBalanceConceptos(await getDataItemsDinamicosTable());

    }

}

const calcularRowTotalesAutoComplete=async (arrayData)=>{

    let total;

    arrayData.forEach(data=>{
        total=data.Cantidad * data.Precio

        document.getElementById('itemTotal_'+data.key).value=maskMoney(total); 
            
    })

}

const clearImaks=(stringNumber)=>{
    let numeroSinComas = stringNumber.replace(/,/g, '');
    return parseFloat(numeroSinComas);
}

const maskMoney = (num) => {
     let numberPrecio=num.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
     return numberPrecio.slice(1);
}

const calculoBalanceConceptos=(arrayData)=>{

    let total=0;
    let subTotal=0;
    let impuestoRetenido=0;
    let impuestos=0;

    let ivaTotal=0;

    arrayData.forEach(data=>{

        // Si total es un numero 
        if(!isNaN(data.Total)){

            subTotal+=data.Total

            // Si es diferente de cero se suma el impuesto al TOTAL GENERAL 
            if(!data.Impuestos==0){

                ivaTotal=data.Total * data.Impuestos

                impuestos+= ivaTotal 
        
                total+=data.Total + ivaTotal;
    
                // Si es impuesto retenido lo agregamos a la seccion impuesto retenido
                if(data.Impuestos==0.12){
        
                    impuestoRetenido+=ivaTotal;
                }

            }else{

                total+=data.Total

            }

        }

    })

    document.getElementById('balanceSubtotal').value=maskMoney(subTotal); 
    document.getElementById('balanceImpuestosRetenidos').value=maskMoney(impuestoRetenido); 
    document.getElementById('balanceImpuestoTrasladado').value=maskMoney(impuestos) 
    document.getElementById('balanceTotal').value=maskMoney(total) 

}

// ----------------------------------------------------------------------------------------------CFDIS 

$('#insertCFDI').on('click', () => {

    let cfdi=$('#CFDIrelacionado').val();
    
    console.log(cfdi);
    insertRowCFDIS(contadorInsertRow_CFDI,cfdi);

})

const insertRowCFDIS = async(key,cfdi) => {

    let newFila = cloneRowCFDI(key,cfdi);

    let containerCFDIS = document.querySelector(`#container_CFDI`);

    let newElement = document.createElement('div');

    newElement.id = `rowCFDIS_${key}`;
    newElement.classList.add('row','rowCFDI');
    newElement.setAttribute('key',key);

    containerCFDIS.appendChild(newElement);

    document.querySelector(`#rowCFDIS_${key}`).innerHTML = newFila;




    
    let  DATAPRUEBA=[
        {'id':1,'folioFactura':'123 Factura Prueba', 'date':'25/09/22'},
        {'id':2,'folioFactura':'324 Factura Prueba', 'date':'12/03/22'},
        {'id':3,'folioFactura':'252 Factura Prueba', 'date':'05/03/22'},
        {'id':4,'folioFactura':'123 Factura Prueba', 'date':'09/01/22'},
        {'id':5,'folioFactura':'3532123 Factura Prueba', 'date':'15/12/22'},
        {'id':6,'folioFactura':'235 Factura Prueba', 'date':'21/04/22'}
    ]

    await insertDataSelectCFDIS('folioFacturaCFDI_'+key, DATAPRUEBA, 'folioFactura', 'id','date')


    $("#folioFacturaCFDI_"+key).chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });


    contadorInsertRow_CFDI++;

    return;
}


const cloneRowCFDI = (key,cfdi) => (
    `
        <div class="col">
            <div>
                <select onchange="statusCDFI(this);sobreSelectData(event)" class="form-control validarItemCFDI" id="folioFacturaCFDI_${key}"  key='${key}' autocomplete="off" style="width:100%;"></select>
            </div>
            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_folioFacturaCFDI_${key}" class="form_text_adv"></p>
            </div>
        <div class="col">
            <input class="form-control validarItemDateCFDI" id="facturaFechaCFDI_${key}" key='${key}' disabled>
            <input class="form-control" id="cfdiRelacionado_${key}" key='${key}' value='${cfdi}' type='hidden' disabled>

        </div>
        <div class="col">
            <input type="button" class="btn btn-danger text-white btnDeleteRowCFDI" id="btnDeleteRowCFDI_${key}" key='${key}' value="X">
        </div>
    `
)


const statusCDFI=(event)=>{

    let key=event.getAttribute('key');

    let date=$('#'+event.id +' option:selected' ).attr('date');

    document.getElementById('facturaFechaCFDI_'+key).value=date;

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

const getDataItemsDinamicosCFDI=async()=>{

    
    let arrayItemsDinamicos=document.querySelectorAll('.rowCFDI');

    let arrayData=[];

    let key;

    arrayItemsDinamicos.forEach(block=>{

        key=block.getAttribute('key');
        
        arrayData.push({

            'folio':$('#folioFacturaCFDI_'+key).val(),
            'date':$('#facturaFechaCFDI_'+key).val(),
            'cfdi':$('#cfdiRelacionado_'+key).val(),

        })

    })

    return arrayData;
}


const getDataItemsDinamicosTable=async(allValue=false)=>{

    let arrayItemsDinamicos=document.querySelectorAll('#tablaPrincipal tbody .rowTable');

    let arrayData=[];

    let key;

    let cantidad;
    let precio;

    arrayItemsDinamicos.forEach(block=>{

        key=block.getAttribute('key');
        
        if(allValue){

            cantidad=block.querySelector('#itemCantidad_'+key).value;
            precio=block.querySelector('#itemPrecio_'+key).value

            if(cantidad &&  precio){

                arrayData.push({
                    'Cantidad':clearImaks( block.querySelector('#itemCantidad_'+key).value ),
                    'Precio':clearImaks( block.querySelector('#itemPrecio_'+key).value ),
                    'Impuestos':  clearImaks(block.querySelector('#itemImpuestos_'+key).value),
                    'key':key
                })
    
            }

        }else{

            arrayData.push({
    
                'odv':block.querySelector('#itemOdv_'+key).value,
                'Codigo':block.querySelector('#itemCodigo_'+key).value,
                'ClaveProdServ':block.querySelector('#itemClaveProdServ_'+key).value,
    
                'Descripcion':block.querySelector('#itemDescripcion_'+key).value,
    
                'Cantidad':clearImaks( block.querySelector('#itemCantidad_'+key).value ),
                
                'Unidad':block.querySelector('#itemUnidad_'+key).value,
                
                'Precio':clearImaks( block.querySelector('#itemPrecio_'+key).value ),
                'Total':clearImaks( block.querySelector('#itemTotal_'+key).value ),
                'Impuestos':  clearImaks(block.querySelector('#itemImpuestos_'+key).value) 
    
            })
        
        }


    })

    console.log(arrayData);
    return arrayData;
}


$('#insertFactura').on('click',async()=>{



    if (!respValidar('validarDataCliente')) {
        showAlert("Alerta", 'Campo de datos cliente incompleto', "danger");
        return;
    }


    if(!respValidar('validateItemsConceptos')){
        showAlert("Error", "Campos incompletos en sección de conceptos", "danger");
        return;
    }
    let tableActive = document.querySelector(`#container_CFDI`);

    if (tableActive.childElementCount !=0){

        if(!respValidar('validarItemCFDI')){
            showAlert("Alerta", 'Campo CFDI incompleto', "danger");
            return
        }
    }

    // let dataBalance = await getDataForms('getDataBalance');


    let dataConceptos=await getDataItemsDinamicosTable();
    let dataCliente = clearDataClientes(await getDataForms('getDataCliente'));


    let dataBalanceConceptos = clearDataConceptos(await getDataForms('getDataBalanceConceptos'));

    let dataCFDIS=await getDataItemsDinamicosCFDI();

    insertAltaFactura(dataCliente,dataConceptos,dataBalanceConceptos,dataCFDIS);

    
})

const clearDataConceptos=(arrayData)=>{

    arrayData.balanceImpuestoTrasladado=parseFloat(arrayData.balanceImpuestoTrasladado);
    arrayData.balanceImpuestosRetenidos=parseFloat(arrayData.balanceImpuestosRetenidos);

    arrayData.balanceSubtotal=parseFloat(arrayData.balanceSubtotal);
    arrayData.balanceTotal=parseFloat(arrayData.balanceTotal);

    return arrayData;

}

const clearDataClientes=(arrayData)=>{

    arrayData.cliente=parseInt(arrayData.cliente);
    arrayData.folio=parseInt(arrayData.folio);
    arrayData.tipoDeCambio=parseFloat(arrayData.tipoDeCambio);

    return arrayData;
}


let insertAltaFactura=async(cliente,conceptos,balanceConceptos,cfdis)=>{

    let data={
        infoCliente:{...cliente},

        infoConceptos:{...conceptos},
        infoBalanceConceptos:{...balanceConceptos},

        infoCFDIS:{...cfdis}
    }

    console.log(data);

    let accionFactura = { "Accion": "factura",'Select':'insertFactura','data':data};


    await postData(rutaApi,accionFactura ).then(async (response) => {

        console.log(response);
        alert(response);
    })


}


const statusValidationInput=(event)=>{event.style.border='1px solid #d9dee3'};


const getDataCliente=()=>{

    let arrayForm=document.querySelectorAll('.getDataCliente');

    let arrayData=[];
    

    arrayForm.forEach(input=>{

        arrayData
    })
}


