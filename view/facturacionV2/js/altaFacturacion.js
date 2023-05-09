$(document).ready(function () { //Informacion al cargar la pagina



    $('#titlePage').text('Alta Facturación');

    const initRow=async()=>{
        insertRowTable(contadorInsertRow_concepto);
        insertRowTable(contadorInsertRow_concepto);
        insertRowTable(contadorInsertRow_concepto);
        insertRowTable(contadorInsertRow_concepto);


        await initFetchs();

        mascarMoneda();


        $('#moneda').on('change',async()=>{
            document.getElementById('container_CFDI').innerHTML='';
            document.querySelector(`#tablaPrincipal tbody`).innerHTML='';

            insertRowTable(contadorInsertRow_concepto);
            insertRowTable(contadorInsertRow_concepto);
            insertRowTable(contadorInsertRow_concepto);
            insertRowTable(contadorInsertRow_concepto);
            calculoBalanceConceptos(await getDataItemsDinamicosTable());
        })
        
        $('#cliente').on('change',async()=>{
            document.getElementById('container_CFDI').innerHTML='';        
            document.querySelector(`#tablaPrincipal tbody`).innerHTML='';
            insertRowTable(contadorInsertRow_concepto);
            insertRowTable(contadorInsertRow_concepto);
            insertRowTable(contadorInsertRow_concepto);
            insertRowTable(contadorInsertRow_concepto);
            calculoBalanceConceptos(await getDataItemsDinamicosTable());
        
        })
        if($('#idPedido').val()){
            
            getPeditoFactura($('#idPedido').val());

        }
        if($('#idPedidoEdit').val()){

            getPeditoFacturaEdit($('#idPedidoEdit').val());
            $("#titleAltaFacturacion").text("Editar Factura");

        }
    }
    initRow();
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

        await getData(rutaApi + '?Accion=factura&Select=usoCfdi').then(async(response)=>{


            let data=response.data;

            data=data.map((item,index,array)=>{
                return {
                    Id:item.Id,
                    Nombre:`[${item.Codigo}] ${item.Nombre} `
                }
            })

            await insertDataSelect('usoCFDI', data, 'Nombre', 'Id');


            $("#usoCFDI").chosen({
    
                width: "100%",
    
                no_results_text: "No se a encontrado resultados",
    
                allow_single_deselect: true,
    
            });

        })



        await getData(rutaApi + '?Accion=factura&Select=metodoPago').then(async(response)=>{

            let data=response.data;

            await insertDataSelect('metodoDePago', data, 'Nombre', 'Id');

            $("#metodoDePago").chosen({
    
                width: "100%",
    
                no_results_text: "No se a encontrado resultados",
    
                allow_single_deselect: true,
    
            });
        })



        await getData(rutaApi + '?Accion=factura&Select=formaPago').then(async(response)=>{

            let data=response.data;

            await insertDataSelect('formaDePago', data, 'Nombre', 'Id');

            $("#formaDePago").chosen({
    
                width: "100%",
    
                no_results_text: "No se a encontrado resultados",
    
                allow_single_deselect: true,
    
            });

        })
        let datos = [
            {
               "Id":"1",
               "Nombre":"Ivan Palaez"
            },
            {
               "Id":"2",
               "Nombre":"Alejandra Ortiz"
            }
         ]
        await insertDataSelect('quienFactura', datos, 'Nombre', 'Id');

        $("#quienFactura").chosen({

            width: "100%",

            no_results_text: "No se a encontrado resultados",

            allow_single_deselect: true,

        });

        await getData(rutaApi + '?Accion=factura&Select=cfdi_relacionado').then(async(response)=>{

            let data=response.data;

            await insertDataSelect('CFDIrelacionado', data, 'Nombre', 'Id');

            $("#CFDIrelacionado").chosen({
    
                width: "100%",
    
                no_results_text: "No se a encontrado resultados",
    
                allow_single_deselect: true,
    
            });

        })





    // await getData(rutaApi + '?Accion=factura&Select=CFDIrelacionado').then(async (response) => {

    //     let data=response.data;
    //                 // data=data.map((item,index,array)=>{
    //         //     return {
    //         //         Id:item.Id,
    //         //         Nombre:`[${item.Codigo}] ${item.Nombre} `
    //         //     }
    //         // })

    //     await insertDataSelect('CFDIrelacionado', data, 'Nombre', 'Id');

    //     $("#CFDIrelacionado").chosen({

    //         width: "100%",

    //         no_results_text: "No se a encontrado resultados",

    //         allow_single_deselect: true,

    //     });
    // })


        


        await getData(rutaApi + '?Accion=factura&Select=regimenFiscal').then(async(response)=>{

            let data=response.data;

            // data=data.map((item,index,array)=>{
            //     return {
            //         Id:item.Id,
            //         Nombre:`[${item.Id}] ${item.Nombre} `
            //     }
            // })

            await insertDataSelect('regimenFiscal', data, 'Nombre', 'Id');

            $("#regimenFiscal").chosen({
    
                width: "100%",
    
                no_results_text: "No se a encontrado resultados",
    
                allow_single_deselect: true,
    
            });

        })
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

            // alerta(response['messenge']);

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
        <input  onkeyup="statusValidationInput(this);"  class="form-control validateItemsConceptos statusEditTable" type="text" id="itemOdv_${key}" key="${key}">
    </th>
    <th>
        <input  onkeyup="statusValidationInput(this);"  class="form-control validateItemsConceptos statusEditTable" type="text" id="itemCodigo_${key}" key="${key}">
    </th>
    <th>
        <input  onkeyup="statusValidationInput(this);"  class="form-control validateItemsConceptos statusEditTable" type="text" id="itemClaveProdServ_${key}" key="${key}">
    </th>
    <th>
        <input  onkeyup="statusValidationInput(this);"  class="form-control validateItemsConceptos statusEditTable" type="text" id="itemDescripcion_${key}" key="${key}">
    </th>
    <th>
        <input  onkeyup="statusValidationInput(this);" oninput="statusBalance(this);" class="form-control validateItemsConceptos statusEditTable mask-pesos" type="text" id="itemCantidad_${key}" key="${key}">
    </th>
    <th>
        <input  onkeyup="statusValidationInput(this);"  class="form-control statusEditTable validateItemsConceptos" type="text" id="itemUnidad_${key}" key="${key}">
    </th>
    <th>
        <div class="input-group">
            <span class="input-group-text">$</span>
            <input onkeyup="statusValidationInput(this);" oninput="statusBalance(this);" class="form-control validateItemsConceptos statusEditTable mask-pesos" type="text" id="itemPrecio_${key}" key="${key}">
        </div>
    </th>
    
    <th>
        <div class="input-group">
            <span class="input-group-text">$</span>
            <input class="form-control statusEditTable mask-pesos" type="text" id="itemTotal_${key}" key="${key}" disabled>
        </div>
    </th>

    <th>
        <select onChange='statusBalance(this)' class="form-select statusEditTable" autocomplete="off" style="width:100%;" id="itemImpuestos_${key}" key="${key}">
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

const insertRowTable = async(key) => {

    let newFila = cloneRowConceptos(key);

    let table = document.querySelector(`#tablaPrincipal tbody`);

    let newElement = document.createElement('tr');

    newElement.id = `rowTable_${key}`;
    newElement.classList.add( `rowTable`)
    newElement.setAttribute('key',key);
    // 21-04
    table.prepend(newElement);

    document.querySelector(`#tablaPrincipal tbody #rowTable_${key}`).innerHTML = newFila;

    initAutoComplete(key);
    
    // 21-04 
    oneIMask('itemCantidad_'+key);
    oneIMask('itemPrecio_'+key);

    contadorInsertRow_concepto++;

    return;
}

// Continuar

// ---------------------------------------------------------------------Autocomplete
const initAutoComplete=async(key)=>{
    $('#itemOdv_'+key).autocomplete({
        // serviceUrl: rutaApi+'?Accion=factura&Select=autoCompleteOdv',
        lookup: async function (query, done) {
            // Do Ajax call or lookup locally, when done,
            // call the callback and pass your results:
            let moneda=$('#moneda').val();
            let cliente=$('#cliente').val();

            let result=await getData(rutaApi+'?Accion=factura&Select=autoCompleteOdv&String='+query+'&moneda='+moneda+'&cliente='+cliente );
            
            if(result.length!=0){
                done(result.data);
            }

        },
        minChars: 1,
        onSelect: function (suggestion) {
            insertValuesAutoComplete(key,suggestion.value);
            clearInputsRow(key);
        }
    });
}


const initAutoCompleteCFDIS=async(key)=>{

    $('#folioFacturaCFDI_'+key).autocomplete({
        lookup: async function (query, done) {
            let moneda=$('#moneda').val();
            let cliente=$('#cliente').val();

            // let result=await getData(rutaApi+'?Accion=factura&Select=autoCompleteOdv&String='+query+'&moneda='+moneda+'&cliente='+cliente );
            let result=await getData(rutaApi+ '?Accion=factura&Select=getfacturaCFDI&moneda='+moneda+'&cliente='+cliente);

            if(result.length!=0){

                result=result.data.map(response=>{
                    return {
                        value:response.folioFactura,
                        data:response.id,
                        date:response.date
                    }
                })
                result={
                    suggestions:result
                }
                done(result);
            }

        },
        minChars: 1,
        onSelect: function (suggestion) {            
            $('#facturaFechaCFDI_'+key).val(suggestion.date);
            $('#folioFacturaCFDI_'+key).attr('folio',suggestion.data);
        }
    });
}


const clearInputsRow=(key)=>{
    clearValidacionInput('itemCodigo_'+key)
    clearValidacionInput('itemClaveProdServ_'+key);
    clearValidacionInput('itemDescripcion_'+key)
    clearValidacionInput('itemCantidad_'+key)
    clearValidacionInput('itemUnidad_'+key)
    clearValidacionInput('itemPrecio_'+key)

}

// MASCARA NOTA CRIS 21-04 
const insertValuesAutoComplete=async(key,id)=>{

    console.log(rutaApi + '?Accion=factura&Select=getFactura&Id=' + id);

    let result = await getData(rutaApi + '?Accion=factura&Select=getFactura&Id=' + id);


    $('#itemCodigo_'+key).val('');
    $('#itemClaveProdServ_'+key).val('');
    $('#itemDescripcion_'+key).val('');

    $('#itemCantidad_'+key).val('');
    $('#itemUnidad_'+key).val('');
    $('#itemPrecio_'+key).val('');
    $('#itemImpuestos_'+key).val('.00'); 

    $('#itemOdv_'+key).val(''); 

    

    for (let pedido of result.data) {
        
        pedido['pedido']=id;
        await setPedidoAutoComplete(pedido);
    }


    await calcularRowTotalesAutoComplete(await getDataItemsDinamicosTable(true));

    calculoBalanceConceptos( await getDataItemsDinamicosTable() );



    return;

}

const calcularRowTotalesAutoComplete=async (arrayData)=>{

    let total;

    arrayData.forEach(data=>{
        total=data.Cantidad * data.Precio

        document.getElementById('itemTotal_'+data.key).value=maskMoney(total); 
            
    })

}

// --------------------------------------------------------------------------------------------------- 

const statusBalance=(event)=>{
    let key=event.getAttribute('key');
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
    
    if(!cfdi){
        showAlert("Alerta", 'Seleccione un CFDI', "danger");
        return;
    }

    if(!$('#cliente').val()){
        showAlert("Alerta", 'Seleccione un cliente para agregar un CFDI relacionado', "danger");
        return;
    }

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
    // let moneda=$('#moneda').val();
    // let cliente=$('#cliente').val();

    // await getData(rutaApi + '?Accion=factura&Select=getfacturaCFDI&moneda='+moneda+'&cliente='+cliente).then(async(response)=>{
    //     let dataCFDI=response.data;
    //     await insertDataSelectCFDIS('folioFacturaCFDI_'+key, dataCFDI, 'folioFactura', 'id','date')
    //     $("#folioFacturaCFDI_"+key).chosen({
    //         width: "100%",
    //         no_results_text: "No se a encontrado resultados",
    //         allow_single_deselect: true,    
    //     });
    // })


    // let  DATAPRUEBA=[
    //     {'id':1,'folioFactura':'123 Factura Prueba', 'date':'25/09/22'},
    //     {'id':2,'folioFactura':'324 Factura Prueba', 'date':'12/03/22'},
    //     {'id':3,'folioFactura':'252 Factura Prueba', 'date':'05/03/22'},
    //     {'id':4,'folioFactura':'123 Factura Prueba', 'date':'09/01/22'},
    //     {'id':5,'folioFactura':'3532123 Factura Prueba', 'date':'15/12/22'},
    //     {'id':6,'folioFactura':'235 Factura Prueba', 'date':'21/04/22'}
    // ]

    // console.log(DATAPRUEBA);

    initAutoCompleteCFDIS(key)   


    contadorInsertRow_CFDI++;

    return;
}


// NOTA VALIDAR SI EL CAMPO FECHA VIENE VACIO ANTERIORMENTE SE VALIDABA SI SOLO UN CAMBPO VENIA VACIO PORQUE ERA UN SELECT, ESTE ES UN autocomplete
const cloneRowCFDI = (key,cfdi) => (
    `
        <div class="col">
            <div>

                <input class="form-control validarItemCFDI" type="text" id="folioFacturaCFDI_${key}" key="${key}">

            </div>
            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_folioFacturaCFDI_${key}" class="form_text_adv"></p>
            </div>
        <div class="col">
            <input class="form-control validarItemCFDI" id="facturaFechaCFDI_${key}" key='${key}' disabled>
            <input class="form-control" id="cfdiRelacionado_${key}" key='${key}' value='${cfdi}' type='hidden' disabled>

        </div>
        <div class="col">
            <input type="button" class="btn btn-danger text-white btnDeleteRowCFDI" id="btnDeleteRowCFDI_${key}" key='${key}' value="X">
        </div>
    `
    // <select onchange="statusCDFI(this);sobreSelectData(event)" class="form-control validarItemCFDI" id="folioFacturaCFDI_${key}"  key='${key}' autocomplete="off" style="width:100%;"></select>
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

            'folio':$('#folioFacturaCFDI_'+key).attr('folio'),
            'date':$('#facturaFechaCFDI_'+key).val(),
            'cfdi':$('#cfdiRelacionado_'+key).val(),
            'idRelacional':$('#cfdiRelacionado_'+key).attr('idRelacional'),
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
                'Impuestos':  clearImaks(block.querySelector('#itemImpuestos_'+key).value),
                // cambio Alexander 04-19
                'idFacturaPedido': block.querySelector('#itemOdv_'+key).getAttribute('factura_pedido')
            })
        
        }


    })

    return arrayData;
}


$('#insertFactura').on('click',async()=>{

    // CAMBIOS ALEXANDER 04-19
    if (!$("#regimenFiscal").val() || !$("#formaDePago").val()) {
        showAlert("Error", "Campos Incompletos en datos del cliento", "false");
        return
    }
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

        if(!validacionAutoCompleteDate()){
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

const validacionAutoCompleteDate=()=>{
    let status=true;

    document.querySelectorAll('.validarItemCFDI').forEach(input=>{

        if(!input.value){
            status=false
            return;
        }
    })
    
    return status;
}
const clearValidacionInput=(id)=>{
    $("#" + id).css({ 'border-color': '#ced4da',"border-weight": "0" });
    $("#ul_"+id).css({'display':'none'})

}

const clearDataConceptos=(arrayData)=>{

    arrayData.balanceImpuestoTrasladado=parseFloat( clearImaks(arrayData.balanceImpuestoTrasladado) );
    arrayData.balanceImpuestosRetenidos=parseFloat( clearImaks(arrayData.balanceImpuestosRetenidos) );
    arrayData.balanceSubtotal=parseFloat( clearImaks(arrayData.balanceSubtotal) );
    arrayData.balanceTotal=parseFloat( clearImaks(arrayData.balanceTotal) );

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


    // let accionFactura = { "Accion": "factura",'Select':'insertFactura','data':data};
    //CAMBIOS ALEXANDER 04-19

    let accionFactura;

    if($('#idPedidoEdit').val()){
        
        let idPedido=$('#idPedidoEdit').val();
        accionFactura = { "Accion": "factura",'Select':'insertFactura','data':data,id:idPedido};

    }else{
        accionFactura = { "Accion": "factura",'Select':'insertFactura','data':data,id:null};
    }

    await postData(rutaApi,accionFactura ).then(async (response) => {

        if(response['success']==true){
        showAlert("Correcto",response['messenge'],"success")   
        setTimeout( function() { window.location.href = "index.php"; }, 2000 );
        }else{
            showAlert("Error",response['messenge'],"false")   
        }
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

$(document).on('click', '.btnGenerarFactura', function (e) {

    let id = e.target.id;
    id = id.substring(2);

    window.location.href = "../factura/altaFactura.php?id="+id; 
    
})
//Funcion alexander
const getPeditoFactura=async(Id)=>{

    let rutaApi='../../api/api.php';
    let accion={Accion: 'factura', Tabla: 'factura', Id: Id,Select:'getDataPedido'};

    await postData(rutaApi,accion).then(async(arrayData)=>{

        console.log(arrayData);



        document.getElementById('container_CFDI').innerHTML='';        
        document.querySelector(`#tablaPrincipal tbody`).innerHTML='';

        // for(item of response['items']){
        //NOTA CAMBIOS ALEXANDER : Se le puso async a la funcion 'insertRowTable'
        // NOTA CAMBIOS ALEXANDER : SE COMENTA LA FUNCION alerta EN GETDATA 
            // console.log(arrayData);
            await setDataCliente(arrayData['data']);
            await setPedidoCliente(arrayData['data']['pedidos']);
            disabledDataFactura();
            // await setCFDICliente(arrayData['cfdis'])
            // await calculoBalanceConceptos( await getDataItemsDinamicosTable() );

    })
}
const setDataCliente = async (arrayData)=>{

    $('#cliente').val(arrayData['idCliente']).trigger('chosen:updated');
    $('#regimenFiscal').val(arrayData['RegimenFiscal']).trigger('chosen:updated');

    // $('#usoCFDI').val(arrayData['usoCFDI']).trigger('chosen:updated');
    // $('#metodoDePago').val(arrayData['metodoPago']).trigger('chosen:updated');
    // $('#formaDePago').val(arrayData['formaPago']).trigger('chosen:updated');

    $('#moneda').val(arrayData['Moneda']);
    $('#tipoDeCambio').val(arrayData['TipoCambio']);
}
const setPedidoCliente = async (arrayData) => {

    for (item of arrayData) {


        var keyRow = contadorInsertRow_concepto;

        await insertRowTable(contadorInsertRow_concepto);

        $('#itemOdv_' + keyRow).val(item['id']); //Ok
        $('#itemCodigo_'+keyRow).val(item['codigo']); // Ok

        $('#itemClaveProdServ_'+keyRow).val(item['claveProServ']); //Ok
        $('#itemDescripcion_'+keyRow).val(item['descripcion']); //Ok
        
        $('#itemCantidad_' + keyRow).val(item['Cantidad']); //Ok
        oneIMask('itemCantidad_'+keyRow);

        $('#itemUnidad_'+keyRow).val(item['unidad']);//Ok
        
        $('#itemPrecio_'+keyRow).val(item['precio']); // Ok
        oneIMask('itemPrecio_'+keyRow);

        $('#itemTotal_'+keyRow).val(item['Total']); // Ok
        await oneIMask('itemTotal_'+keyRow);
        
        $('#itemImpuestos_'+keyRow).val(item['IvaPorcentual']); // Ok

        calculoRowTable(keyRow);

        // NOTA:
        // Validar datos autocomplet al no selecionar clientes 

    }

    // NOTA AL LLAMAR A ESTA FUNCION SE HACEN LOS CALCULOS EN EL APARTADO DE TOTALES 
    calculoBalanceConceptos(await getDataItemsDinamicosTable());


}
const getPeditoFacturaEdit=async(Id)=>{

    let accion=`?Accion=factura&Tabla=factura&Id=${Id}&Select=getFacturaId`;

    await getData(rutaApi+accion).then(async(arrayData)=>{

            document.getElementById('container_CFDI').innerHTML='';        
            document.querySelector(`#tablaPrincipal tbody`).innerHTML='';

            arrayData=arrayData['data']

            await setDataClienteFactura(arrayData['cliente']);

            await setPedidoClienteFactura(arrayData['pedidos']);
            
            await setCFDIClienteFactura(arrayData['cfdis'])
            
            disabledDataFactura();

    })
}


const setDataClienteFactura = async (arrayData)=>{

    $('#cliente').val(arrayData['idCliente']).trigger('chosen:updated');
    $('#regimenFiscal').val(arrayData['regimenFiscal']).trigger('chosen:updated');

    $('#usoCFDI').val(arrayData['usoCFDI']).trigger('chosen:updated');
    $('#metodoDePago').val(arrayData['metodoPago']).trigger('chosen:updated');
    $('#formaDePago').val(arrayData['formaPago']).trigger('chosen:updated');

    $('#moneda').val(arrayData['moneda']);
    $('#tipoDeCambio').val(arrayData['tc']);
    $('#quienFactura').val(arrayData['razonfactura']).trigger('chosen:updated');
    $('#folio').val(arrayData['folio']);
}

const setPedidoClienteFactura = async (arrayData) => {

    for (item of arrayData) {


        var keyRow = contadorInsertRow_concepto;

        await insertRowTable(contadorInsertRow_concepto);

        $('#itemOdv_' + keyRow).val(item['pedido']); //Ok

        // CAMBIO ALEXANDER 04-19
        $('#itemOdv_' + keyRow).attr('factura_pedido',item['idFacturaPedido']); //Ok


        $('#itemCodigo_'+keyRow).val(item['codigo']); // Ok1

        $('#itemClaveProdServ_'+keyRow).val(item['claveProServ']); //Ok1
        $('#itemDescripcion_'+keyRow).val(item['descripcion']); //Ok1
        
        $('#itemCantidad_' + keyRow).val(item['cantidad']); //Ok1
        oneIMask('itemCantidad_'+keyRow);

        $('#itemUnidad_'+keyRow).val(item['unidad']); //ok1
        
        $('#itemPrecio_'+keyRow).val(item['precio']); // Ok 1
        oneIMask('itemPrecio_'+keyRow);

        $('#itemTotal_'+keyRow).val(item['total']); // Ok
        oneIMask('itemTotal_'+keyRow);
        
        $('#itemImpuestos_'+keyRow).val(item['impuesto']); // Ok

    }

    calculoBalanceConceptos(await getDataItemsDinamicosTable());
    // {
    //     "pedido": 10,
    //     "codigo": "SLTR - SERVICIO",
    //     "claveProdServ": "81141601",

    //     "descripcion": "SERVICIO",
    //     "cantidad": 13,
    //     "unidad": 3,
        
    //     "precio": 1000,
    //     "total": 4000,
    //     "impuesto": ".08"
    // },

}

const setCFDIClienteFactura=async(arrayData)=>{

for(CFDI of arrayData){
    var keyCFDI=contadorInsertRow_CFDI;

    await insertRowCFDIS(contadorInsertRow_CFDI,CFDI['cfdiRelacionado']);
    
    $('#cfdiRelacionado_'+keyCFDI).val(CFDI['cfdiRelacionado']);

    // CAMBIO ALEXANDER 04-19
    $('#cfdiRelacionado_'+keyCFDI).attr('idRelacional',CFDI['idRelacional']);

    

    $('#facturaFechaCFDI_'+keyCFDI).val(CFDI['date']);

    $('#folioFacturaCFDI_'+keyCFDI).val(CFDI['text']);
    $('#folioFacturaCFDI_'+keyCFDI).attr('folio',CFDI['folio']);

}

// itemCFDI={
//     folio: 8,
//     date: "2023-02-24",
//     cfdiRelacionado: 3,
//     text:'8 Utt $1102.08 MXN'
// };


}

const disabledDataFactura=()=>{

    // RegimenFiscal 
    // uso DE CFDI
    // METODO DE pago
    // FORMA DE pago
    // QUIEN FACTURA
    // CFDI RELACIONADOS
    // Observaciones

    $('#cliente').prop('disabled', true).trigger("chosen:updated");


    $("#moneda").attr("disabled",true);
    $("#tipoDeCambio").attr("disabled",true);
    $("#cliente").attr("disabled",true);
    $("#insertConcepto").attr("disabled",true);
    $(".statusEditTable").attr("disabled",true); 
}

//-------------------------------------------- ACCIONES EDIT FACTURA

$("#tablaPrincipal").on('click', '.btnEditarTabla', function (e) {

    let id = (e.target.id).substring(2);

    window.location.href = "./altaFacturacion.php?idPedido="+id; 

})
$("#regresarIndex").on("click",()=>{
    window.location.href = "index.php"
})







// 21-04
const setPedidoAutoComplete = async (item) => {

        var keyRow = contadorInsertRow_concepto;

        await insertRowTable(contadorInsertRow_concepto);


        $('#itemOdv_' + keyRow).val(item['pedido']); //01

        $('#itemCodigo_'+keyRow).val(item['Codigo']); // Ok1

        $('#itemClaveProdServ_'+keyRow).val(item['ClaveProdServ']); //Ok1
        $('#itemDescripcion_'+keyRow).val(item['Descripcion']); //Ok1
        
        $('#itemCantidad_' + keyRow).val(item['Cantidad']); //Ok1
        oneIMask('itemCantidad_'+keyRow);

        $('#itemUnidad_'+keyRow).val(item['Unidad']); //ok1
        
        $('#itemPrecio_'+keyRow).val(item['PrecioXLitro']); // Ok 1
        oneIMask('itemPrecio_'+keyRow);

        // $('#itemTotal_'+keyRow).val(item['total']); // Ok
        // oneIMask('itemTotal_'+keyRow);
        
        $('#itemImpuestos_'+keyRow).val(item['Impuestos']); // Ok

        calculoBalanceConceptos(await getDataItemsDinamicosTable());

}

