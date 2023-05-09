$(document).ready(function () { //Informacion al cargar la pagina

    $('#titlePage').text('Orden de trabajo');

    tablaPrincipal();



})
const modulo = 6;

const resetTablas=()=>{
    
    tablaPrincipal();
    // "destroy": true,
  
}


const tablaPrincipal = () => {

    var accion = { "Accion": "odt", "Tabla": "odt" }

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

            { 'data': 'Id' },

            { 'data': 'Usuario' },

            { 'data': 'TipoCambio','className': 'text-center','render': $.fn.dataTable.render.number(',', '.', 2, '$')},

            { data: 'CantidadFabricar', 'className': 'text-center', "render": function (data) {
                return addCommas(data); 
            }},

            { 'data': 'nameUser', 'className': 'text-center'},

            { 'data': 'Costo','render': $.fn.dataTable.render.number(',', '.', 2, '$')},

            { 'data': 'nameProducto' , 'className': 'text-center'},
            
            { 'data': 'SubNombre' , 'className': 'text-center'},

            { data: 'InventarioActual' , 'className': 'text-center', "render": function (data) {
                return addCommas(data); 
            }},

            { data: 'InventarioDespues' , 'className': 'text-center', "render": function (data) {
                return addCommas(data); 
            }},

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

    })

}
// -------------------------------------------------------------------------------------------------Funciones

$(document).on('click', '.btnCancelarTabla', function (e) {
    let id = e.target.id;
    id = id.substring(2);
    let btnModalCancelar=document.querySelector('.btnAceptarCancelar');
    // btnModalCancelar.removeAttribute('id');
    btnModalCancelar.setAttribute('id',id);

    $('#labelOdv').text('¿Desea cancelar la orden de venta #' + id + '?');
    // console.log(id);
    $('#modalCancelar').modal('show');
})
$(document).on('click', '.btnAceptarCancelar', function (e) {
    let idModal = $(this).attr('id');

    // console.log(idModal);
    let status =0;
    // console.log(idModal);
    // console.log(status);
    cancelarOdt(idModal,status);

});
const cancelarOdt = async (id, status) => {

    let accion = { "Accion": "odt", "Tabla": "odt", 'Status': status, 'Id': id };

    return await fetch(rutaApi, {

        method: 'PUT',

        body: JSON.stringify(accion),

        headers: { 'Content-Type': 'application/json' }

    }).then(respuesta => respuesta.json())

        .then(respuesta => {

            if (respuesta['success']) {
                let comentario = "Cancelo la Orden de trabajo con el ID:"+id;
                setBitacora('6', comentario, modulo);
                showAlert("Correcto", respuesta['messenge'], "success")

                $('#modalCancelar').modal('hide');
                let tablaCargar = $('#tablaPrincipal').DataTable();
                tablaCargar.ajax.reload();

            } else {

                showAlert("Alerta", respuesta['messenge'], "info")

            }


        })

}
// -------------------------------------------------------------------------------------------------Funciones

const separarString = (text, separador, numberData) => {

    var text = text.split(separador);

    return text[numberData];

}

const insertSelectInput = (id, data) => {

    let selectInput = document.getElementById(id);

    selectInput.innerHTML = `<option value="">Seleccione uno...</option>`;

    data.forEach(element => {

        var option = new Option(element['Nombre'], element['Id']);

        selectInput.appendChild(option);

    });

    return;
}


const getIdBtn = (event) => {

    let idString = $(event).attr('id');

    return idString.substring(2);

}

const insertDataChecBox = (dataCheck) => {

    document.getElementById('Hazmat').checked = dataCheck['Hazmat'] == 1 ? true : false;

    document.getElementById('Formulacion').checked = dataCheck['Formulacion'] == 1 ? true : false;

    return;
}

const reloadTable = (idTable, idModal) => {

    $('#' + idModal).modal('hide');

    let tablaCargar = $('#' + idTable).DataTable();
    tablaCargar.ajax.reload();

    return;
}


// -------------------------------------------------------------------------------------------------Alta


const limpiarInputsAdvertencias = (getClass) => {

    let arrayInpustLimpiar = document.querySelectorAll('.' + getClass);

    arrayInpustLimpiar.forEach(input => {

        $("#" + input.id).css({ 'border-color': '#ced4da', "border-weight": "0" });

        $("#ul_" + input.id).css({ 'display': 'none' })

    })
}



$('#tablaPrincipal tbody').on('click', '.btnViewTabla', function (e) {

    document.getElementById("formView").reset();
    $('#modalView').modal('show');

    let idOdt=getIdBtn(this);


    // console.log(this);

    initFormView(idOdt)

});

const initFormView=async(idOdt)=>{

    let arrayDataFormulacion=await getData('?Accion=odt&Tabla=odt&subTabla=odtproducto&Id='+idOdt);

    insertValueFormView(arrayDataFormulacion['data']);


    for(item of arrayDataFormulacion['items']){


        key=await crearBoxView();

        setValueInputs(key,item);
    }
}


var numBoxView = 0;

const crearBoxView = async () => {

    numBoxView++;

    // Container de Rutas que se pueden Eliminiar
    let boxSecundarios = document.getElementById('itemsSecundariosView');

    // Item Principal
    let boxPrincipal = document.getElementById('itemsPrincipalView');
    let countContainerPrincipal = Number(boxPrincipal.childElementCount);


    let boxCloneNode = document.querySelector(".boxItemDefaul").cloneNode(true)
    boxCloneNode.classList.replace('boxItemDefaul', 'boxItem');
    boxCloneNode.id = 'boxItemView_' + numBoxView;

    // --------------------------------------

    container = await modificarInfoItemView(boxCloneNode, 'View_'+numBoxView)

    // container=boxCloneNode;

    if (countContainerPrincipal == 0) {

        boxPrincipal.appendChild(container);

    } else {

        boxSecundarios.appendChild(container);

    }

    return 'View_'+numBoxView;
}

const modificarInfoItemView = (containerBox, key) => {

    containerBox = setKeysInputs(containerBox,key);

    return containerBox;
}

const setKeysInputs=(container,key)=>{

    container.querySelector('#producto_example').id=`producto${key}`;
    container.querySelector('#precioPorLitro_example').id=`precioPorLitro${key}`;
    container.querySelector('#litrosPorProduccion_example').id=`litrosPorProduccion${key}`;

    container.querySelector('#litrosPorBarriol_example').id=`litrosPorBarriol${key}`;
    container.querySelector('#importe_example').id=`importe${key}`;


    container.querySelector('#estatus_example').id=`estatus${key}`;
    
    return container;

}

// Inseta los valores a los items de la Formulacion 
const setValueInputs=(key,arrayData)=>{

    document.getElementById(`producto${key}`).value=arrayData['nombreProducto'];
    document.getElementById(`precioPorLitro${key}`).value=arrayData['PrecioLitros'];
    document.getElementById(`litrosPorProduccion${key}`).value=arrayData['LitroProduccion'];

    document.getElementById(`litrosPorBarriol${key}`).value=arrayData['LitroBarril'];
    document.getElementById(`importe${key}`).value=arrayData['Importe'];

    document.getElementById(`estatus${key}`).value=arrayData['Status']=='1'?'Activo':'Cancelado';;

    return true;

}

const insertValueFormView=(arrayData)=>{

    document.getElementById('odt_view').value=arrayData["Id"];
    document.getElementById('usuario_view').value=arrayData["Usuario"];
    
    document.getElementById('costo_view').value=arrayData["costoFabricacion"];
    document.getElementById('cantidadFabricar_view').value=arrayData["CantidadFabricar"];
    document.getElementById('usuarioAsignado_view').value=arrayData["nameUsuarioAsignado"];

    document.getElementById('producto_view').value=arrayData["nameProducto"];
    document.getElementById('inventarioActual_view').value=arrayData["InventarioActual"];
    document.getElementById('inventarioDespues_view').value=arrayData["InventarioDespues"];

    return;
}

// -------------------------------------------------------------------------------------------- Excel
let dataExcel={
    idBtnExcel:'btnExcelOdt',
    nameFile:'Odt',
    urlApi:rutaApi,
    accion:`?Accion=odt&getDataExcel=1&Tabla=odt`,
    columnasExcel:['F3','I3'],
    urlVendor:'../../requerimientos/vendors/spreadsheet/spreadsheetExcel.php'
}

let excelTabla1 = new exportarExcelTabla(dataExcel);




// --------------------------------------------------------------------------------------------- 
const initGraficaTabla = (arrayValues,arrayTitle) => {

    var options = {
        series: arrayValues,
        chart: {
            width: 380,
            type: 'donut',
        },
        dataLabels: {
            enabled: false
        },
          labels: arrayTitle,
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    show: true
                }
            }
        }],
        legend: {
            position: 'right',
            offsetY: 0,
            height: 230,
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

}

// funcion que seleccione los 4 primeros datos con el numero mas alto y si se sepite poner el total de otros


const getData = async (accion) => {

    return (await fetch(rutaApi + accion, {

        method: 'GET',

        headers: { 'Content-Type': 'application/json' }

    }).then(response => response.json())

        .then(response => {

            return response;

        })

    )

}

const initGrafica=async()=>{

    let arrayData=await getData('?Accion=odt&Tabla=odt')

    let arrayDataAndKey=await countProductos(arrayData['data'])

    const arrayTitles = arrayDataAndKey[0].map(item => item[0]);
    const arrayValues = arrayDataAndKey[0].map(item => item[1]);

    let contador=0;

    arrayDataAndKey[0].map(item => {
        contador+=item[1]
    });


    let totalOtros=arrayDataAndKey[1]-contador;
    // arrayDataAndKey[1]-totalTop[0];

    // console.log(totalOtros)

    arrayValues.push(totalOtros);
    arrayTitles.push('Otros');

    initGraficaTabla(arrayValues,arrayTitles);

}

// tengo un objeto, 'data' tiene items, quiero saber las veces que se repite en el array, los valores de 'Productos'
let arrayItems = [];

const countProductos = async(arrayData) => {

    let totalProductos=0
    arrayData.forEach(items => {

        arrayKeys = Object.keys(arrayItems);

        if (arrayKeys.includes(items['nameProducto'])) {

            arrayItems[items['nameProducto']]++;

        } else {
            arrayItems[items['nameProducto']] = 1;

        }

        totalProductos++;

    })

    // console.log(arrayItems);

    arrayItems = { ...arrayItems };
    
    const data = Object.entries(arrayItems);
    
    // Ordenamos el array en orden descendente por el valor
    data.sort((a, b) => b[1] - a[1]);

    // Obtenemos los primeros 4 elementos del array
    const top4 = data.slice(0, 4);

    // Mostramos los resultados
    // console.log(top4);
    //     console.log(totalProductos)


    return [top4,totalProductos];
}

// initGrafica()
