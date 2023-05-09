$(document).ready(function () { //Informacion al cargar la pagina




    $('#titlePage').text('Recepción de gastos');
    tablaPrincipal();

})
let data = [
    {
        'id': 0,

        'acciones': `<i class='btnView btn btn-primary bx bx-plus'></i>`,

        'folio': 10,
        'uuid': 2000,

        'emisorRfc': 'RFC',
        'emisorNombre': 'Nombre',

        'concepto': 'concepto',

        'subtotal': 2000,
        'iva': 2000,

        'total': 2000,
        'moneda': 'Pesos',

        'notaDeCredito': 2000,

    },

    {
        'id': 1,

        'acciones': `<i class='btnView btn btn-primary bx bx-plus'></i>`,

        'folio': 10,
        'uuid': 2000,

        'emisorRfc': 'RFC',
        'emisorNombre': 'Nombre',

        'concepto': 'concepto',

        'subtotal': 2000,
        'iva': 2000,

        'total': 2000,
        'moneda': 'Pesos',

        'notaDeCredito': 2000,

    },
    {
        'id': 2,

        'acciones': `<i class='btnView btn btn-primary bx bx-plus'></i>`,

        'folio': 10,
        'uuid': 2000,

        'emisorRfc': 'RFC',
        'emisorNombre': 'Nombre',

        'concepto': 'concepto',


        'subtotal': 2000,
        'iva': 2000,

        'total': 2000,
        'moneda': 'Pesos',

        'notaDeCredito': 2000,

    },

]

const tablaPrincipal = async (data) => {


    var accion = {"Accion": "gastos",'Select':'getTabla' }



    var tablaSSP = $('#tablaPrincipal').DataTable({

        'ajax': {

            'url': rutaApi,

            'type': 'GET',

            'data': accion,

            // 'dataSrc': 'data',

        },

        'data': data,

        'order': [[1, 'desc']],

        'columns': [



            { 'data': 'acciones' },

            { 'data': 'folio' },
            { 'data': 'uuid' },

            { 'data': 'emisorRfc' },
            { 'data': 'emisorNombre' },

            // { 'data': 'concepto' },

            { 'data': 'subtotal' ,'render': $.fn.dataTable.render.number(',', '.', 2, '$')},
            { 'data': 'iva' ,'render': $.fn.dataTable.render.number(',', '.', 2, '$')},
            { 'data': 'total' ,'render': $.fn.dataTable.render.number(',', '.', 2, '$')},

            { 'data': 'moneda' },

            { 'data': 'notaDeCredito' },

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


    return true;

}


console.log('Entro')
// Add event listener for opening and closing details
$('#tablaPrincipal tbody').on('click', '.btnView', function () {
    let table = $('#tablaPrincipal').DataTable();

    var tr = $(this).closest('tr');
    var row = table.row(tr);

    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    } else {
        // Open this row
        let data=row.data();

        // CREARMOS LAS TABLA DE LA FILA 
        row.child( format(data.id) ).show();
        tr.addClass('shown');

        console.log(row.data())
        rowTable(data.id);
        // initChildrenRow();
    }
});





const format = (key) => (
    `
<div>

<div class="row titleItemFacturacion">
    <div class="d-flex justify-content-between">
        <h5 class='sinMargin'>Conceptos<strong></strong> </h5>
        <div>
            <!-- <h6 class='sinMargin'>Tipo: </h6> -->

        </div>
    </div>
</div>

<div class="row">


    <div class="table-responsive text-nowrap m-3 inline" style="min-height: 250px;">



        <table class="table" id="itemTableConceptos_${key}">

            <thead>

                <tr>
                    <th>Aceptar</th>
                    <th>Evidencia</th>
                    <th>Tipo de gasto</th>
                    <th>Referencia</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                </tr>

            </thead>

            <tbody>
            </tbody>

        </table>

    </div>

</div>


</div>
`
)


const rowTable = async(key) => {

    let newFila = await cloneRowTable(key);

    let table = document.querySelector(`#itemTableConceptos_${key} tbody`);

    let newElement = document.createElement('tr');

    newElement.id = `rowTable_${key}`;
    newElement.classList.add(`rowTable`)
    newElement.setAttribute('key', key);
    table.appendChild(newElement);

    document.querySelector(`#itemTableConceptos_${key} tbody #rowTable_${key}`).innerHTML = newFila;

    initSelectSearch(key);

    return;


}

const cloneRowTable = async (key) => (
        `
        <th><input type="checkbox" class="itemCheck" key='${key}' id="itemStatus${key}"></th>

        <th>
            <div>
                <select class="form-control" key='${key}' id="itemEvidencia${key}" disabled style="width:100%;"></select>
            </div>
        </th>

        <th>
            <div>
                <select class="form-control" key='${key}' id="itemTipoGasto${key}" disabled style="width:100%;"></select>
            </div>

        </th>

        <th>Referencia Prueba</th>

        <th>Descripción</th>

        <th>Camión</th>

        <th>1000000</th>

        <th>100.00</th>
        `
)


$('body').on('click','.itemCheck',function(){
    console.log(this);
    let key=this.getAttribute('key');
    let id=this.id;

    if ($('#'+id).prop('checked')) {
        console.log('Esta seleccionado');

        $("#itemEvidencia"+key).prop('disabled', false);
        $("#itemTipoGasto"+key).prop('disabled', false);

        // hacer algo si está seleccionado
      } else {
        console.log('NO Esta seleccionado')

        $("#itemEvidencia"+key).prop('disabled', true);
        $("#itemTipoGasto"+key).prop('disabled', true);

        // hacer algo si no está seleccionado
      }


      $("#itemEvidencia"+key).trigger("chosen:updated");
        $("#itemTipoGasto"+key).trigger("chosen:updated");



})



const insertDataSelect = async (id, data, text, key) => {

    let selectInput = document.getElementById(id);

    selectInput.innerHTML = `<option value="">Seleccione uno...</option>`;

    data.forEach(element => {

        var option = new Option(element[text], element[key]);

        selectInput.appendChild(option);

    });

    return;
}



const initSelectSearch = async (key) => {

    let DATAPRUEBA = [
        { 'id': 1, 'nombre': 'AFGD23 - Prueba' },
        { 'id': 2, 'nombre': 'SDG35 - Prueba' },
        { 'id': 3, 'nombre': 'DFSD4 - Prueba' },
        { 'id': 4, 'nombre': 'SDGDS4 - Prueba' },
        { 'id': 5, 'nombre': 'BDR4 - Prueba' },
        { 'id': 6, 'nombre': 'AFGD23 - Prueba' }
    ]

    await insertDataSelect('itemEvidencia' + key, DATAPRUEBA, 'nombre', 'id');

    $("#itemEvidencia" + key).chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });


    await insertDataSelect('itemTipoGasto' + key, DATAPRUEBA, 'nombre', 'id');

    $("#itemTipoGasto" + key).chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });
}