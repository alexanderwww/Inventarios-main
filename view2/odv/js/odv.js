$(document).ready(function () { //Informacion al cargar la pagina

    $('#titlePage').text('Ordenes de Ventas');

    initModulo();

})

const resetTablas=()=>{
    tablaPrincipal();
    // "destroy": true,
  
}

const modulo = 8;
const initModulo=async()=>{
    tablaPrincipal();

    respuesta=await getSelectProductoPrimaryos();

    insertSelectInput('ProductoPrimario_example', respuesta['data']);
    insertSelectInput('Como_example', respuesta['data']);

    insertDataAllSelect(respuesta['data'],`.selectProductoEdit`,'Nombre','Id');
    insertDataAllSelect(respuesta['data'],`.selectVenderComoEdit`,'Nombre','Id');

    respuesta=await getSelectClientes();
    insertSelectInput('Cliente_alta', respuesta['data']);
    insertSelectInput('Cliente_edit', respuesta['data']);

    respuesta=await getSelectMoneda();
    insertSelectInput('Moneda_alta', respuesta['data']);
    insertSelectInput('Moneda_edit', respuesta['data']);


    $("#Cliente_alta").chosen({
        width: "100%",
        no_results_text: "No se a encontrado resultados",
        allow_single_deselect: true,
      });

    return;

}

const tablaPrincipal = () => {

    var accion = { "Accion": "odv", "Tabla": "odv" }

    var tablaSSP = $('#tablaOdv').DataTable({
        "order": [[1, "desc"]],

        'ajax': {
            'url': rutaApi,
            'type': 'GET',
            'data': accion,
            'dataSrc': 'data',
        },

        'columns': [
            { 'data': 'acciones' },
            
            { 'data': 'Id' },

            { 'data': 'Nombre' },

            { 'data': 'Moneda', className: 'text-center' },

            { 'data': 'Subtotal','render': $.fn.dataTable.render.number(',', '.', 2, '$')},

            
            { 'data': 'Iva','render': $.fn.dataTable.render.number(',', '.', 2, '$')},

            { 'data': 'Total', className: 'text-center','render': $.fn.dataTable.render.number(',', '.', 2, '$')},

            { 'data': 'Fecha',
              'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss',' DD/MM/YYYY')
            //   'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD hh:mm:ss')
            //   'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD hh:mm:ss ' , 'DD-MM-YYYY')
            },
            { 'data': 'Observaciones'},

            { 'data': 'Status'},


            

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

// ------------------------------------------------------------------------------------------------- Calculos
function total() {

    let subTotal = $('#SubTotal_alta').val();
    let porcentaje = $('#IvaPor_example').val();
    let ivaPorcetaje = 0;

    if (subTotal != '') {
        subTotal=subTotal.replace(/,/g, "");
        subTotal = parseFloat(subTotal);
    } else {
        subTotal = 0;
    }
    if (porcentaje != '') {
        porcentaje=porcentaje.replace(/,/g, "");
        porcentaje = parseFloat(porcentaje);
    } else {
        entrada = 0;
    }
    if (porcentaje == 1) {
        ivaPorcetaje = 0.08;
    } else {
        if (porcentaje == 2) {
            ivaPorcetaje = 0.16;
        } else {
            ivaPorcetaje = 0;
        }
    }

    let ivaTotal = subTotal * ivaPorcetaje;
    let total = subTotal + ivaTotal;

    $('#Total_alta').val(mascarMonedaInputs(total));

}



// ------------------------------------------------------------------------------------------------- KeyUp
$('.SubtotalEntrada').keyup(function () {

    total();
});

// -------------------------------------------------------------------------------------------------Funciones

const getDataItems = async (idForm, typeForm, separador) => {

    let arrayDataInputsRutas = document.querySelectorAll(`#${idForm} .boxItem`);

    let arrayDataInfoItems = [];

    arrayDataInputsRutas.forEach(boxItems => {

        // id del Producto a actualizar si es el caso
        idBox = typeForm + separarString(boxItems.id, separador, 1);

        arrayObj = {};

        producto = boxItems.querySelector('#ProductoPrimario' + idBox).value;
        Como = boxItems.querySelector('#Como' + idBox).value;
        Cantidad = boxItems.querySelector('#Cantidad' + idBox).value;
        Precio = boxItems.querySelector('#Precio' + idBox).value;
        IvaPorcentual = boxItems.querySelector('#Iva' + idBox).value;

        totalMaterial=boxItems.querySelector('#Material'+idBox).value;
        Total = boxItems.querySelector('#Total' + idBox).value;

        Iva=boxItems.querySelector('#IvaPorcentual'+idBox).value;
        Subtotal=boxItems.querySelector('#Subtotal'+idBox).value;

        IdItem='';

        if(boxItems.querySelector('#Total'+idBox).getAttribute('attr_Item')){

            IdItem=boxItems.querySelector('#Total'+idBox).getAttribute('attr_Item');

        }
        parseFloat(Cantidad.replace(/,/g, ""))
        arrayObj = {
            IdItem:IdItem,

            IdProducto: producto,
            IdProductoComo: Como,
            Cantidad: parseFloat(Cantidad.replace(/,/g, "")),
            Precio: parseFloat(Precio.replace(/,/g, "")),
            Subtotal: parseFloat(Subtotal.replace(/,/g, "")),
            IvaPorcentual: IvaPorcentual,
            Iva: parseFloat(Iva.replace(/,/g, "")),
            Total: parseFloat(Total.replace(/,/g, "")),

            totalMaterial:parseFloat(totalMaterial.replace(/,/g, ""))
        }

        arrayDataInfoItems.push(arrayObj);

    })


    return arrayDataInfoItems;
}


const getDataForms = async (claseInpustData, separador) => {

    let arrayInputsForm = document.querySelectorAll('.' + claseInpustData);

    let arrayData = [];

    arrayInputsForm.forEach(input => {

        nombreInput = separarString(input.id, separador, 0);

        if(nombreInput=='Iva'||nombreInput=='SubTotal'||nombreInput=='Total'){
           
            arrayData[nombreInput] =  parseFloat(input.value.replace(/,/g, ""));

        }else{
            arrayData[nombreInput] = input.value;

        }


    })

    return arrayData;

}

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

// Inserta 'option' a todos los selects que tenga la clase que le pasemos 
// Tiene la opcion de agregar atributos a los 'option' del select 
const insertDataAllSelect =async (data, classAllSelect, texto, identificador, arrayAttr = null) => {


    let arrayinputSelect = document.querySelectorAll(classAllSelect);

    let respArrayAttr = arrayAttr == null ? false : true;

    arrayinputSelect.forEach(inputSelect=>{

        inputSelect.innerHTML=`<option value="">Seleccione uno...</option>`;

        data.forEach(element => {

            var option = new Option(element[texto], element[identificador]);//name y id

            if (respArrayAttr) {


                arrayAttr.forEach(atributo => {

                    option.setAttribute(atributo['nameAttr'], element[atributo['valor']])

                })
            }

            inputSelect.appendChild(option);


        });

    })


    return;

}



const getIdBtn = (event) => {

    let idString = $(event).attr('id');

    return idString.substring(2);

}





// const getDataInputsForms = async (claseGetData) => {

//     let arrayInpust = document.querySelectorAll('#formEdit .' + claseGetData)

//     let arrayDataForm = [];

//     arrayInpust.forEach(input => {

//         arrayDataForm[separarString(input.id,'_edit',0)] = document.getElementById(input.id).value;

//     });

//     return arrayDataForm;

// }



// -------------------------------------------------------------------------------------------------Alta

$('.btnAceptarAlta').on('click', async () => {

    if (respValidar('validarDataAlta')) {

        if(!restaInventario()){
            showAlert("Alerta",'La cantidad en venta no puede ser mayor a la cantidad en el inventario', "info");

            return;
        }

        let data = await getDataForms('formAltaData', '_alta')

        let dataItem = await getDataItems('formAlta', '_alta', 'ItemAlta');



        insertOdv({ ...data }, { ...dataItem });

    }

})




const limpiarInputsAdvertencias = (getClass) => {

    let arrayInpustLimpiar = document.querySelectorAll('.' + getClass);

    arrayInpustLimpiar.forEach(input => {

        $("#" + input.id).css({ 'border-color': '#ced4da', "border-weight": "0" });

        $("#ul_" + input.id).css({ 'display': 'none' })

    })
}
// ------------------------------------------- Cancelar -------------------------------------------
$(document).on('click', '.btnCancelarTabla', function (e) {
    let id = e.target.id;
    id = id.substring(2);
    let btnModalCancelar=document.querySelector('.btnAceptarDeshabilitar');
    btnModalCancelar.removeAttribute('id');


    btnModalCancelar.setAttribute("id",id);

    $('#labelOdv').text('¿Desea cancelar la orden de venta #' + id + '?');
    // console.log(id);
    $('#modalCancelar').modal('show');
})
$(document).on('click', '.btnAceptarDeshabilitar', function (e) {
    let idModal = $(this).attr('id');

    // console.log(idModal);
    let status =0;
    cancelarOdv(idModal,status);

});
const cancelarOdv = async (id, status) => {

    let accion = { "Accion": "odv", "Tabla": "odv", 'Status': status, 'Id': id };

    return await fetch(rutaApi, {

        method: 'PUT',

        body: JSON.stringify(accion),

        headers: { 'Content-Type': 'application/json' }

    }).then(respuesta => respuesta.json())

        .then(respuesta => {

            if (respuesta['success']) {
                let comentario = "Cancelo la Orden de compra con el ID:"+id;
                setBitacora('6', comentario, modulo);
                showAlert("Correcto", respuesta['messenge'], "success")

                $('#modalCancelar').modal('hide');
                let tablaCargar = $('#tablaOdv').DataTable();
                tablaCargar.ajax.reload();

            } else {

                showAlert("Alerta", respuesta['messenge'], "info")

            }


        })

}

$('.btnModalAlta').on('click', async () => {

    $('#Cliente_alta').val('').trigger('chosen:updated');

    $('#modalAlta').modal('show');

    limpiarInputsAdvertencias('formAltaData');

    document.getElementById('formAlta').reset();

    $('#Entrada_alta').prop('disabled', false);
    $('#Salida_alta').prop('disabled', false);

    document.querySelector('#itemsSecundarios_alta').innerHTML = '';
    document.querySelector('#itemsPrincipal_alta').innerHTML = '';

    idNewBox = await crearBoxAlta();

    getNextId();
})










$('#formItems_alta').on('click', (event) => {

    let box = event.target;

    if (box.classList.contains('boxEliminarItem')) {

        let idContainerInput = separarString(box.id, 'Num_', 1);

        BoxRutasCopias = document.getElementById('boxItemAlta' + idContainerInput)
        BoxRutasCopias.remove();
        totalGeneral();
        // statusPorcentaje();
    }

    if (box.classList.contains('btnModalAgregar')) {

        crearBoxAlta();

    }


})










// -------------------------------------------------------------------------------------------------Edit

$('#tablaOdv tbody').on('click', '.btnView', function (e) {

    limpiarInputsAdvertencias('formDataEdit');

    document.querySelector('#itemsSecundarios_edit').innerHTML = '';

    document.querySelector('#itemsPrincipal_edit').innerHTML = '';

    document.getElementById("formEdit").reset();

    initModalEdit(this);

});


$('#formItems_edit').on('click', (event) => {

    let box = event.target;

    if (box.classList.contains('boxEliminarItem')) {

        let idContainerInput = separarString(box.id, 'Num_', 1);

        BoxRutasCopias = document.getElementById('boxItemEdit' + idContainerInput)
        BoxRutasCopias.remove();

    }

    if (box.classList.contains('btnModalAgregar')) {

        crearBoxEdit();

    }


})



$('.btnAceptarEdit').on('click', () => {


        if (respValidar('validarDataEdit')) {

                // Enviar los datos de los items
                getDataFormProductoEdit($('.btnAceptarEdit').attr('id'));

        };

})




const initModalEdit = async (element) => {

    let idElement = getIdBtn(element);

    $('.btnAceptarEdit').attr('id', idElement);

    $('#modalEditTitle').text($(element).attr('name'));

    $('#modalEdit').modal('show');


    // let arrayData = await getDataOdv(idElement);
    let arrayData=await getAccion(`?Accion=odv&Tabla=odv&Id=${idElement}`);
    
    insertValueFormEdit(arrayData['data']);


    statusItemsEdit(arrayData['items'])
};


const statusItemsEdit = async (arrayDataItems) => {

    document.querySelector('#itemsSecundarios_edit').innerHTML = '';

    document.querySelector('#itemsPrincipal_edit').innerHTML = '';


    if (!arrayDataItems.length) {

        await crearBoxEdit();

        return;
    }



    for (let itemData of arrayDataItems) {

        idContainerItem = await crearBoxEdit();

        await insertDataItem(idContainerItem, itemData);

    }

    return;

}

const insertDataInputsFormEdit = (data, claseGetData) => {

    let arrayInpust = document.querySelectorAll('.' + claseGetData)

    arrayInpust.forEach(input => {

        document.getElementById(input.id).value = data[input.id];

    });

    return true;

}


const getDataFormProductoEdit = async (id) => {

    let arrayDataInputs = getValuesFormEdit();

    let arrayDataItems = await getDataItems('formEdit', '_edit', 'ItemEdit');

    editOdv(id, arrayDataInputs, { ...arrayDataItems });

};



// -------------------------------------------------------------------------------------------------Fetch

const editOdv = async (id, dataForm, dataItems) => {

    let accion = { "Accion": "odv", "Tabla": "odv", 'Data': dataForm, 'Items': dataItems, 'Id': id };

    // return;
    return await fetch(rutaApi, {

        method: 'PUT',

        body: JSON.stringify(accion),

        headers: { 'Content-Type': 'application/json' }

    }).then(respuesta => respuesta.json())

        .then(respuesta => {

            if (respuesta['success']) {

                showAlert("Correcto", respuesta['messenge'], "success")

                $('#modalEdit').modal('hide');
                let tablaCargar = $('#tablaOdv').DataTable();
                tablaCargar.ajax.reload();

            } else {

                showAlert("Alerta", respuesta['messenge'], "info")

            }


        })

}


const getDataOdv = async (id) => {

    return (await fetch(rutaApi + '?Accion=productos&Tabla=productos&Id=' + id, {

        method: 'GET',

        headers: { 'Content-Type': 'application/json' }

    }).then(respuesta => respuesta.json())

        .then(respuesta => {

            return respuesta;

        })

    )

}

const updateStatusProducto = async (id, status) => {

    let accion = { "Accion": "productos", "Tabla": "productos", 'Id': id, 'Status': status };

    return await fetch(rutaApi, {

        method: 'PUT',

        body: JSON.stringify(accion),

        headers: { 'Content-Type': 'application/json' }

    }).then(respuesta => respuesta.json())

        .then(respuesta => {

            if (respuesta['success']) {

                showAlert("Correcto", respuesta['messenge'], "success")


            } else {

                showAlert("Alerta", respuesta['messenge'], "info")

            }

            return respuesta;
        })

}
const totalGeneral=()=> {

    let arrayTotal = document.querySelectorAll("#formAlta .Total");
    let arraySubTotal = document.querySelectorAll("#formAlta .SubTotal");
    let arrayIva = document.querySelectorAll("#formAlta .Iva");
    let totalGeneral = 0;
    let subTotalGeneral = 0;
    let ivaGeneral = 0;

    arrayTotal.forEach(element => {
    
        if (element.value != '') {
            totalGeneral = totalGeneral + parseFloat(element.value.replace(/,/g, ""))
        }
       
    });
    
    arraySubTotal.forEach(element => {
        if (element.value != '') {
            subTotalGeneral = subTotalGeneral + parseFloat(element.value.replace(/,/g, ""));
        }
       
    });
    
    arrayIva.forEach(element => {
        if (element.value != '') {
            ivaGeneral = ivaGeneral + parseFloat(element.value.replace(/,/g, ""));
        }
       
    });


    $('#SubTotal_alta').val(mascarMonedaInputs(subTotalGeneral));
    $('#Iva_alta').val(mascarMonedaInputs(ivaGeneral));
    $('#Total_alta').val(mascarMonedaInputs(totalGeneral));

}

const totalGeneralEdit=()=> {

    let arrayTotal = document.querySelectorAll("#formEdit .Total");
    let arraySubTotal = document.querySelectorAll("#formEdit .SubTotal");
    let arrayIva = document.querySelectorAll("#formEdit .Iva");
    let totalGeneral = 0;
    let subTotalGeneral = 0;
    let ivaGeneral = 0;

    arrayTotal.forEach(element => {
    
        if (element.value != '') {
            totalGeneral = totalGeneral + parseFloat(element.value)
        }
        // console.log(element.value);
    });
    
    arraySubTotal.forEach(element => {
        if (element.value != '') {
            subTotalGeneral = subTotalGeneral + parseFloat(element.value);
        }
        // console.log(element.value);
    });
    
    arrayIva.forEach(element => {
        if (element.value != '') {
            ivaGeneral = ivaGeneral + parseFloat(element.value);
        }
        // console.log(element.value);
    });


    $('#SubTotal_edit').val(subTotalGeneral);
    $('#Iva_edit').val(ivaGeneral);
    $('#Total_edit').val(totalGeneral);

}
function restaInventario(){

    let arrayDataItems=document.querySelectorAll('.containerItems_alta .boxItem');

    let statusInventario=true;

    arrayDataItems.forEach(item =>{

        materialExistente=item.querySelector('.cantidadClass').value;
        cantidadVenta=item.querySelector('.materialClass ').value;
        // parseFloat(cantidadVenta.replace(/,/g, ""))
        if(parseFloat(cantidadVenta.replace(/,/g, "")) < parseFloat(materialExistente.replace(/,/g, ""))){

            statusInventario=false;
        }

    })

    return statusInventario;
}

const calcularTotal=(event)=> {
    
    let idSelect = event.target.id;

    let idetificador,cantidad,precio,iva;

    if (idSelect.includes('alta')) {

        idetificador = separarString(idSelect, 'alta', 1);
        // console.log(idSelect);
        // Sacamos los valores de los inputs a calcular el Total
        cantidad = $('#Cantidad_alta' + idetificador).val();

        precio = $('#Precio_alta' + idetificador).val();

        iva = $('#Iva_alta' + idetificador + ' option:selected').val();

    } else {
        idetificador = separarString(idSelect, 'edit', 1);
        // Sacamos los valores de los inputs a calcular el Total
        cantidad = $('#Cantidad_edit' + idetificador).val();

        precio = $('#Precio_edit' + idetificador).val();

        iva = $('#Iva_edit' + idetificador + ' option:selected').val();
    }

    // -----------------------

    let totalesItems=statusTotales(cantidad,precio,iva);

    
    if (idSelect.includes('alta')) {

        $('#IvaPorcentual_alta' + idetificador).val(mascarMonedaInputs(totalesItems['ivaTotal']));
        $('#Subtotal_alta' + idetificador).val(mascarMonedaInputs(totalesItems['subtotal']));
        $('#Total_alta' + idetificador).val(mascarMonedaInputs(totalesItems['total']));
        // restaInventario();
        totalGeneral();

    }else{

        $('#IvaPorcentual_edit' + idetificador).val(mascarMonedaInputs(totalesItems['ivaTotal']));
        $('#Subtotal_edit' + idetificador).val(mascarMonedaInputs(totalesItems['subtotal']));
        $('#Total_edit' + idetificador).val(mascarMonedaInputs(totalesItems['total']));
        totalGeneralEdit();

    }

}


const statusTotales=(cantidad,precio,iva)=>{
    if (cantidad == '') {
        cantidad = 0;
    } else {
        cantidad=cantidad.replace(/,/g, "");
        cantidad = parseFloat(cantidad);
    }

    if (precio == '') {
        precio = 0;
    } else {
        precio=precio.replace(/,/g, "");
        precio = parseFloat(precio);
    }
    
    if (iva == '') {
        iva = 0;
    } else {
        iva=iva.replace(/,/g, "");
        iva = parseFloat(iva);
    }
    
    // Iva 
    let aplicarIva = 0;
    
    switch (iva) {
        case 0:
            aplicarIva = 0;
            break;
        case 1:
            aplicarIva = 0.08;
            break;
        case 2:
            aplicarIva = 0.16;
            break;
    }

    let subtotal= cantidad * precio;
    let ivaTotal= (subtotal) * (aplicarIva);
    let total= subtotal + ivaTotal;

    return {
        subtotal:subtotal,
        ivaTotal:ivaTotal, 
        total:total, 
    };
    
}


function getProductoSelect(params) {

    idSelect = this.id;

    IdProducto = $("#" + idSelect + " option:selected").val();

    let promesa = getProducto(IdProducto);

    promesa.then(datos => {

        if (this.id.includes("alta")) {

            idetificador = separarString(idSelect, 'alta', 1);

            $('#Material_alta' + idetificador).val(mascarMonedaInputs(datos.data['Total']));

            // $('#Precio_alta' + idetificador).val(datos.data['PrecioLitros']);

            $('#Cantidad_alta' + idetificador).val('');

        }else{

            idetificador = separarString(idSelect, 'edit', 1);

            $('#Material_edit' + idetificador).val(datos.data['Total']);
            // $('#Precio_edit' + idetificador).val(datos.data['PrecioLitros']);
            $('#Cantidad_edit' + idetificador).val('');

        }

    });

}

// Ver los datoas de pesos 


const getProducto = async (id) => {

    return await fetch(rutaApi + '?Accion=ajustes&Tabla=productos&Id=' + id, {

        method: 'GET',

        headers: { 'Content-Type': 'application/json' }

    }).then(respuesta => respuesta.json())

        .then(respuesta => {

            return respuesta;
        })

}

const getSelectClientes = async () => {
    return await fetch(rutaApi + '?Accion=odv&Tabla=clientes&Select=1', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    }).then(respuesta => respuesta.json())
        .then(respuesta => {
            return respuesta;
        })
}
const getNextId = async () => {
    return await fetch(rutaApi + '?Accion=odv&NextId=odv', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    }).then(respuesta => respuesta.json())
        .then(respuesta => {

            $('#Nombre_alta').val(respuesta.data['nextID']);

            return respuesta;
        })
}
const getSelectMoneda = async () => {
    return await fetch(rutaApi + '?Accion=odv&Tabla=moneda&Select=1', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    }).then(respuesta => respuesta.json())
        .then(respuesta => {
            return respuesta;
        })
}

const getSelectProductoPrimaryos = async () => {
    return await fetch(rutaApi + '?Accion=productos&Tabla=productos&Select=2', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    }).then(respuesta => respuesta.json())
        .then(respuesta => {

            return respuesta;
        })
}
function totalEntradaSalida() {
    // idSelect=this.val;
    let existente = $('#Existente_alta').val();
    let entrada = $('#Entrada_alta').val();
    let salida = $('#Salida_alta').val();
    if (existente != '') {
        existente=existente.replace(/,/g, "");
        existente = parseFloat(existente);
    } else {
        existente = 0;
    }
    if (entrada != '') {
        entrada=entrada.replace(/,/g, "");
        entrada = parseFloat(entrada);
    } else {
        entrada = 0;
    }
    if (salida != '') {
        salida=salida.replace(/,/g, "");
        salida = parseFloat(salida);
    } else {
        salida = 0;
    }

    // entrada = parseInt(entrada);
    // salida = parseInt(salida);
    let total = existente + entrada - salida;
    if (Math.sign(total) == -1) {
        $('#Despues_alta').val(mascarMonedaInputs(total));
        $('#Despues_alta').css('color', 'red');
    } else {
        if (Math.sign(total) >= 0) {
            $('#Despues_alta').val(mascarMonedaInputs(total));
            $('#Despues_alta').css('color', 'black');
        }
    }


}

$('.EntradaSalida').keyup(function () {
    if ($(this).attr('id') == 'Entrada_alta' && $(this).val() != '') {
        totalEntradaSalida();
        $('#Salida_alta').attr('disabled', true);
    } else {
        totalEntradaSalida();
        $('#Salida_alta').attr('disabled', false);
    }
    if ($(this).attr('id') == 'Salida_alta' && $(this).val() != '') {
        totalEntradaSalida();
        $('#Entrada_alta').attr('disabled', true);
    } else {
        totalEntradaSalida();
        $('#Entrada_alta').attr('disabled', false);
    }
    // totalEntradaSalida();


});


const insertOdv = async (dataForm, dataItems) => {
    let accion = { "Accion": "odv", "Tabla": "odv", 'Data': dataForm, 'Items': dataItems };


    // return;
    return await fetch(rutaApi, {

        method: 'POST',

        body: JSON.stringify(accion),

        headers: { 'Content-Type': 'application/json' }

    }).then(respuesta => respuesta.json())

        .then(respuesta => {

            if (respuesta['success']) {

                showAlert("Correcto", respuesta['messenge'], "success")
   
                let comentario = "Registro de la Orden de venta con el ID:"+respuesta['data'];
                setBitacora('6', comentario, modulo);
                $('#modalAlta').modal('hide');
                let tablaCargar = $('#tablaOdv').DataTable();

                tablaCargar.ajax.reload();

            } else {

                showAlert("Alerta", respuesta['messenge'], "info")

            }

            return respuesta['success'];
        })
}


// -------------------------------------------------------------------------------------------------Validaciones



const respValidar = (clase) => {



    let resultadoValidar = validar(clase);



    if (resultadoValidar) {



        return validarCaracteres(clase);



    } else {



        return false;



    }



}


// -------------------------------------------------------------------------------------------------Items

const modificarInfoItemAlta = (containerBox, number) => {

    containerBox = cambiarInfoInput(containerBox, '_alta' + number);

    containerBox = cambiarInfoItems(containerBox, 'Alta');

    return cambiarInfoSelect(containerBox, '_alta' + number);

}


const cambiarInfoItems = (container, key) => {

    container.querySelector('.containerBtnsDefault').classList.replace('containerBtnsDefault', 'containerBtns' + key)

    container.querySelector('.productoClass').classList.replace('formDataExample', `form${key}DataItems`);
    container.querySelector('.productoClass').classList.replace('validarDataExample', `validarData${key}`);

    container.querySelector('.comoClass').classList.replace('formDataExample', `form${key}DataItems`);
    container.querySelector('.comoClass').classList.replace('validarDataExample', `validarData${key}`);

    container.querySelector('.cantidadClass').classList.replace('formDataExample', `form${key}DataItems`);
    container.querySelector('.cantidadClass').classList.replace('validarDataExample', `validarData${key}`);

    container.querySelector('.precioClass').classList.replace('formDataExample', `form${key}DataItems`);
    container.querySelector('.precioClass').classList.replace('validarDataExample', `validarData${key}`);

    container.querySelector('.materialClass').classList.replace('formDataExample', `form${key}DataItems`);
    container.querySelector('.materialClass').classList.remove('validarDataExample');

    container.querySelector('.impuestoClass').classList.replace('formDataExample', `form${key}DataItems`);
    container.querySelector('.impuestoClass').classList.replace('validarDataExample', `validarData${key}`);

    container.querySelector('.totalClass').classList.replace('formDataExample', `form${key}DataItems`);
    container.querySelector('.totalClass').classList.remove('validarDataExample');

    return container;
}


const cambiarInfoInput = (container, key) => {

    // container.querySelector('#Porcentaje_example').id = 'Porcentaje' + key;
    container.querySelector('#Material_example').id = 'Material' + key;
    container.querySelector('#cantidad_example').id = 'Cantidad' + key;
    container.querySelector('#Precio_example').id = 'Precio' + key;
    container.querySelector('#Total_example').id = 'Total' + key;
    container.querySelector('#Subtotal_example').id = 'Subtotal' + key;
    container.querySelector('#IvaPorcentual_example').id = 'IvaPorcentual' + key;

    // container.querySelector('#ul_Porcentaje_example').id = 'ul_Porcentaje' + key;
    container.querySelector('#ul_Material_example').id = 'ul_Material' + key;
    container.querySelector('#ul_cantidad_example').id = 'ul_Cantidad' + key;
    container.querySelector('#ul_Precio_example').id = 'ul_Precio' + key;
    container.querySelector('#ul_Total_example').id = 'ul_Total' + key;

    return container;

}


const cambiarInfoSelect = (container, key) => {
    container.querySelector('#ProductoPrimario_example').classList.add('selectProductosAlta')

    container.querySelector('#ProductoPrimario_example').id = 'ProductoPrimario' + key;
    container.querySelector('#Como_example').id = 'Como' + key;
    container.querySelector('#Iva_example').id = 'Iva' + key;


    container.querySelector('#ul_ProductoPrimario_example').id = 'ul_ProductoPrimario' + key;
    container.querySelector('#ul_Como_example').id = 'ul_Como' + key;
    container.querySelector('#ul_Iva_example').id = 'ul_Iva' + key;


    return container;

}

var numberContadorAlta = 0;

const crearBoxAlta = async () => {

    // numberContadorAlta++;


    // Container de Rutas que se pueden Eliminiar
    let boxSecundarios = document.getElementById('itemsSecundarios_alta');

    // Item Principal
    let boxPrincipal = document.getElementById('itemsPrincipal_alta');
    let countContainerPrincipal = Number(boxPrincipal.childElementCount);


    let boxCloneNode = document.querySelector(".boxItemDefaul").cloneNode(true)
    boxCloneNode.classList.replace('boxItemDefaul', 'boxItem');
    boxCloneNode.id = 'boxItemAlta' + numberContadorAlta;


    if (countContainerPrincipal == 0) {

        boxCloneNode.querySelector('.containerBtnsDefault').innerHTML = `<div id='Num_${numberContadorAlta}' class="d-inline btn btn-success rounded-10 btn-sm btnModalAgregar bx bx-plus" style='font-size: 17px; color:#ffffff;' type="button" title="Agregar"></div>`

    } else {

        boxCloneNode.querySelector('.containerBtnsDefault').innerHTML = `<div id='Num_${numberContadorAlta}' class="d-inline btn btn-danger rounded-10 btn-sm boxEliminarItem bx bx-x" style='font-size: 17px; color:#ffffff;' type="button" title="Borrar"></div>`

    }
    // --------------------------------------


    container = await modificarInfoItemAlta(boxCloneNode, numberContadorAlta)


    if (countContainerPrincipal == 0) {

        boxPrincipal.appendChild(container);

    } else {

        boxSecundarios.appendChild(container);

    }

    $("#ProductoPrimario_alta"+numberContadorAlta).chosen({
        width: "100%",
        no_results_text: "No se a encontrado resultados",
        allow_single_deselect: true,
    });

    $("#Como_alta"+numberContadorAlta).chosen({
        width: "100%",
        no_results_text: "No se a encontrado resultados",
        allow_single_deselect: true,
    });

    return numberContadorAlta;
}








// ----------------------------------------------------------------EDIT 

var numberContadorEdit = 0;

const crearBoxEdit = async () => {

    numberContadorEdit++;

    // Container de Rutas que se pueden Eliminiar
    let boxSecundarios = document.getElementById('itemsSecundarios_edit');

    // Item Principal
    let boxPrincipal = document.getElementById('itemsPrincipal_edit');
    let countContainerPrincipal = Number(boxPrincipal.childElementCount);

    let boxCloneNode = document.querySelector(".boxItemDefaulView").cloneNode(true)
    boxCloneNode.classList.replace('boxItemDefaulView', 'boxItem');
    boxCloneNode.id = 'boxItemEdit' + numberContadorEdit;

    // --------------------------------------

    container = await modificarInfoItemEdit(boxCloneNode, numberContadorEdit)

    if (countContainerPrincipal == 0) {

        boxPrincipal.appendChild(container);

    } else {

        boxSecundarios.appendChild(container);

    }

    return numberContadorEdit;
}



const modificarInfoItemEdit = (containerBox, number) => {

    containerBox = cambiarInfoInput(containerBox, '_edit' + number);

    containerBox = cambiarInfoItems(containerBox, 'Edit');

    return cambiarInfoSelect(containerBox, '_edit' + number);

}

// -------------------------------------------------------------------------------------------------


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




const insertValueFormEdit=(data)=>{

    document.getElementById('TpCambio_edit').value=data['TipoCambio'];
    document.getElementById('Moneda_edit').value=data['Moneda'];
    document.getElementById('SubTotal_edit').value=data['Subtotal'];
    document.getElementById('Iva_edit').value=data['Iva'];
    document.getElementById('Total_edit').value=data['Total'];
    document.getElementById('Observaciones_edit').value=data['Observaciones'];
    document.getElementById('Cliente_edit').value=data['Id_cliente'];
    document.getElementById('Nombre_edit').value=data['Id'];

    return true;
};


const getValuesFormEdit=()=>{

    return { 
        TipoCambio:document.getElementById('TpCambio_edit').value,
        Moneda:document.getElementById('Moneda_edit').value,
        Subtotal:document.getElementById('SubTotal_edit').value,
        Iva:document.getElementById('Iva_edit').value,
        Total:document.getElementById('Total_edit').value,
        Observaciones:document.getElementById('Observaciones_edit').value,
        Id_cliente:document.getElementById('Cliente_edit').value
    };
    
};



const insertDataItem = async (key, itemData) => {

    document.getElementById('ProductoPrimario_edit' + key).value = itemData['Id_producto'];

    document.getElementById('Como_edit' + key).value = itemData['VendidoComo'];
    document.getElementById('Material_edit' + key).value = itemData['TotalMaterial'];
    
    document.getElementById('Cantidad_edit' + key).value = itemData['Cantidad'];
    document.getElementById('Precio_edit' + key).value = itemData['Precio_Litro'];
    
    // Es el id a selecionar en el select de iva
    document.getElementById('Iva_edit' + key).value = itemData['IvaPorcentual'];

    document.getElementById('Total_edit' + key).value = itemData['Total'];

    // Id del item 
    document.getElementById('Total_edit' + key).setAttribute('attr_Item',itemData['Id']);

    document.getElementById('Subtotal_edit'+key).value=itemData['Subtotal'];

    // Iva es  la cantidad del item en numero 
    document.getElementById('IvaPorcentual_edit'+key).value=itemData['Iva'];

    return true;
}



let dataExcel={
    idBtnExcel:'btnExcelTabla',
    nameFile:'Órdenes de venta',
    urlApi:rutaApi,
    accion:`?Accion=odv&getDataExcel=1&Tabla=odv`,
    columnasExcel:['D3:F3'],
    urlVendor:'../../requerimientos/vendors/spreadsheet/spreadsheetExcel.php'
}


let excelTabla = new exportarExcelTabla(dataExcel);