$(document).ready(function () { //Informacion al cargar la pagina



    $('#titlePage').text('Compras');



    tablaPrincipal();



    initModulo()



})





const resetTablas=()=>{

    tablaPrincipal();

    // "destroy": true,



}



const initModulo = async () => {



    respuesta = await getAccion('?Accion=productos&Tabla=productos&Select=2');

    insertDataSelect(respuesta['data'], 'Producto_alta', 'Nombre', 'Id')





    respuesta = await getAccion('?Accion=compras&Tabla=moneda&Select=2');

    insertDataSelect(respuesta['data'], 'Moneda_alta', 'Nombre', 'Id')





    respuesta = await getAccion('?Accion=compras&Tabla=proveedores&Select=2');

    insertDataSelect(respuesta['data'], 'Proveedor_alta', 'Nombre', 'Id')





    $(`#Proveedor_alta`).chosen({

        width: "100%",

        no_results_text: "...",

        allow_single_deselect: true,

    });

    $(`#Producto_alta`).chosen({

        width: "100%",

        no_results_text: "...",

        allow_single_deselect: true,

    });

}





const modulo = 10;

const tablaPrincipal = () => {



    var accion = { "Accion": "compras", "Tabla": "compras" }



    var tablaSSP = $('#tablaCompras').DataTable({

        "order": [[0, "desc"]],



        'ajax': {

            'url': rutaApi,

            'type': 'GET',

            'data': accion,

            'dataSrc': 'data',

        },

        'order': [[2, 'desc']],

        'columns': [

            { 'data': 'acciones' },



            { 'data': 'Id' },



            { 'data': 'Fecha','render': $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', ' DD/MM/YYYY')},



            { 'data': 'NombreProducto' },



            { 'data': 'Entrada', "render": function (data) {
                return addCommas(data); 
            } },


            { 'data': 'NombreProveedor' },



            { 'data': 'PrecioLitro', 'render': $.fn.dataTable.render.number(',', '.', 2, '$') },



            { 'data': 'NombreMoneda' },



            { 'data': 'TipoCambio', 'render': $.fn.dataTable.render.number(',', '.', 2, '$') },



            { 'data': 'NoFactura' },



            { 'data': 'Observaciones' },



            { 'data': 'Status' },









        ],



        'language': {





            'url': '../../requerimientos/vendors/DataTables-1.10.24/language/Spanish_Mexico.json'





        },



        "scrollY": "300px",



        "sScrollX": "100%",



        "sScrollXInner": "100%",



        "scrollCollapse": false,



        "paging": false,



        "destroy": true,



    })



}





// -------------------------------------------------------------------------------------------------Funciones





const getDataForms = async (claseInpustData, separador) => {

// console.log(claseInpustData);

    let arrayInputsForm = document.querySelectorAll('.' + claseInpustData);



    let arrayData = [];



    arrayInputsForm.forEach(input => {



        nombreInput = separarString(input.id, separador, 0);



        arrayData[nombreInput] = input.value;



    })



    return arrayData;



}



const separarString = (text, separador, numberData) => {



    var text = text.split(separador);



    return text[numberData];



}



const insertDataSelect = (data, SelectId, texto, identificador, arrayAttr = null) => {



    let inputSelect = document.getElementById(SelectId);



    inputSelect.innerHTML = `<option value="">Seleccione uno...</option>`;



    let respArrayAttr = arrayAttr == null ? false : true;



    data.forEach(element => {



        var option = new Option(element[texto], element[identificador]);//name y id



        if (respArrayAttr) {



            arrayAttr.forEach(atributo => {



                option.setAttribute(atributo['nameAttr'], element[atributo['valor']])



            })

        }



        inputSelect.appendChild(option);





    });



}



const getIdBtn = (event) => {



    let idString = $(event).attr('id');



    return idString.substring(2);



}



const getDataInputsForms = async (claseGetData) => {



    let arrayInpust = document.querySelectorAll('.' + claseGetData)



    let arrayDataForm = [];



    arrayInpust.forEach(input => {



        arrayDataForm[input.id] = document.getElementById(input.id).value;



    });



    return arrayDataForm;



}



const getAccion = async (accion) => {



    return await fetch(rutaApi + accion, {

        method: 'GET',

        headers: {

            'Content-Type': 'application/json'

        }

    }).then(respuesta => respuesta.json())



        .then(respuesta => {



            return respuesta;



        })

}



// -------------------------------------------------------------------------------------------------Alta



// $('.btnAceptarAlta').on('click', async () => {

// // debugger

//     if (respValidar('validarDataAlta')) {



//         let data = await getDataForms('formDataAlta', '_alta')

//         // console.log(data);



//         if (data.Existente != data.Despues) {

//             IdProducto = $("#" + idSelect + " option:selected").val();

//             let mRestante = data.Existente + data.Entrada;

//             if (Math.sign(mRestante) >= 0) {

//                 let promesa = getProducto(IdProducto);

//                 promesa.then(datos => {

//                     if (parseInt(data.Existente) == datos.data['Total']) {

//                         console.log(data);

//                         insertCompras({ ...data });

//                         // showAlert("Datos correctos",'Se han aplicado ajuste',"success")

//                     } else {

//                         showAlert("Los datos cambiaron", 'Los datos de inventarios no son los correctos favor de veridicar', "error")



//                     }

//                 });

//             } else {

//                 showAlert("Material insuficiente", 'El material es insuficiente para ajuste', "error")



//             }

//         } else {

//             showAlert("Sin Ajustes", 'No se encontraron ajustes', "info")

//         }

//     }


// })
$('.btnAceptarAlta').on('click', async () => {
    // debugger
        if (respValidar('validarDataAlta')) {
    
            let data = await getDataForms('formDataAlta', '_alta')
            // console.log(data);
    
            if (data.Existente != data.Despues) {
                IdProducto = $("#" + idSelect + " option:selected").val();
                let mRestante = parseFloat(data.Existente.replace(/,/g, "")) + parseFloat(data.Entrada.replace(/,/g, ""));
    
                if (Math.sign(mRestante) >= 0) {
                    let promesa = getProducto(IdProducto);
                    promesa.then(datos => {
    
                        if (parseFloat(data.Existente.replace(/,/g, "")) == datos.data['Total']) {
                            // console.log(data);
                            let arrayObj ={};
                            arrayObj = {
                                Despues: parseFloat(data.Despues.replace(/,/g, "")),
                                Entrada: parseFloat(data.Entrada.replace(/,/g, "")),
                                Existente: parseFloat(data.Existente.replace(/,/g, "")),
                                Moneda: data.Moneda,
                                NoFactura: data.NoFactura,
                                Nombre: data.Nombre,
                                Observaciones: data.Observaciones,
                                Precio: parseFloat(data.Precio.replace(/,/g, "")),
                                Producto: data.Producto,
                                Proveedor: data.Proveedor,
                                TpCambio: parseFloat(data.TpCambio.replace(/,/g, "")),
                            }
                            insertCompras({ ...arrayObj });
                            // insertCompras({ ...data });
                            // showAlert("Datos correctos",'Se han aplicado ajuste',"success")
                        } else {
                            showAlert("Los datos cambiaron", 'Los datos de inventarios no son los correctos favor de veridicar', "error")
    
                        }
                    });
                } else {
                    showAlert("Material insuficiente", 'El material es insuficiente para ajuste', "error")
    
                }
            } else {
                showAlert("Sin Ajustes", 'No se encontraron ajustes', "info")
            }
    
    
        }
    
    
    
    
    
    })
    


const getProducto = async (id) => {



    return await fetch(rutaApi + '?Accion=ajustes&Tabla=productos&Id=' + id, {



        method: 'GET',



        headers: { 'Content-Type': 'application/json' }



    }).then(respuesta => respuesta.json())



        .then(respuesta => {



            // console.log(respuesta);

            // $('#Existente_alta').val(respuesta.data['Total']);

            // $('#Despues_alta').val(respuesta.data['Total']);

            // // // insertSelectInput('ProductoPrimario_example',respuesta['data']);

            // // console.log();

            // totalEntradaSalida();

            return respuesta;

        })



}





const limpiarInputsAdvertencias = (getClass) => {



    let arrayInpustLimpiar = document.querySelectorAll('.' + getClass);



    arrayInpustLimpiar.forEach(input => {



        $("#" + input.id).css({ 'border-color': '#ced4da', "border-weight": "0" });



        $("#ul_" + input.id).css({ 'display': 'none' })



    })

}



$('.btnModalAlta').on('click', async () => {





    $('#modalAlta').modal('show');



    limpiarInputsAdvertencias('formDataAlta');



    document.getElementById('formAlta').reset();



    $('#Entrada_alta').prop('disabled', false);





    $('#Proveedor_alta').val('').trigger('chosen:updated');

    $('#Producto_alta').val('').trigger('chosen:updated');



})











// --------------------------------------VIEW--------------------------------------------------------------------

$('#tablaCompras tbody').on('click', '.btnView', function (e) {



    limpiarInputsAdvertencias('formDataView');



    document.getElementById("formView").reset();



    initModalEdit(this);



});









// -------------------------------------------------------------------------------------------------Deshabilitar





$(document).on('click', '.btnCancelarTabla', function (e) {



    let idUser = $(this).attr('id');



    let btnModalCancelar = document.querySelector('.btnModalCancelar');

    btnModalCancelar.removeAttribute('id');





    btnModalCancelar.setAttribute("id", idUser);

    $('#btnCancelar').modal('show');



});
$(document).on('click', '.btnModalCancelar', function (e) {

    let idModal = $(this).attr('id');

    let idString = idModal.substring(2);

    let idUser = Number(idString);

    let status = 0;

    statusCompra(idUser, status);



});
$(document).on('click', '.btnAprobarTabla', function (e) {



    let idUser = $(this).attr('id');



    let btnModalAprobar = document.querySelector('.btnModalAprobar');

    btnModalAprobar.removeAttribute('id');





    btnModalAprobar.setAttribute("id", idUser);

    $('#btnAprobar').modal('show');



});
$(document).on('click', '.btnModalAprobar', function (e) {

    let idModal = $(this).attr('id');

    let idString = idModal.substring(2);

    let idUser = Number(idString);

    let status = 1;

    statusCompra(idUser, status);



});



const statusCompra = async (id, status) => {



    let accion = { "Accion": "compras", "Tabla": "compras", 'Status': status,'Select': 'status', 'Id': id };



    return await fetch(rutaApi, {



        method: 'PUT',



        body: JSON.stringify(accion),



        headers: { 'Content-Type': 'application/json' }



    }).then(respuesta => respuesta.json())



        .then(respuesta => {



            if (respuesta['success']) {

                let comentario = "Cancelo la compra con el ID:" + id;

                setBitacora('6', comentario, modulo);

                showAlert("Correcto", respuesta['messenge'], "success")



                $('#btnCancelar').modal('hide');

                let tablaCargar = $('#tablaCompras').DataTable();

                tablaCargar.ajax.reload();



            } else {



                showAlert("Alerta", respuesta['messenge'], "info")



            }





        })



}



// -------------------------------------------------------------------------------------------------Edit



$('#tablaCompras tbody').on('click', '.btnEditarTabla', function (e) {



    // let arrayInpustLimpiar = document.querySelectorAll('.formEditData')



    document.getElementById("formEdit").reset();



    initModalEdit(this);



});









$('.btnAceptarEdit').on('click', () => {



    if ($('#Formulacion').prop('checked')) {





        if (respValidar('validarDataEdit') & respValidar('validarEditDataItems')) {





            if (validarPorcentaje('infoPorcentajeItemsEdit')) {

                // Enviar los datos de los items

                getDataFormProductoEdit($('.btnAceptarEdit').attr('id'), true);



            }





        };



    } else {



        if (respValidar('validarDataEdit')) {



            getDataFormProductoEdit($('.btnAceptarEdit').attr('id'), false);



        };



    }





})









const initModalEdit = async (element) => {



    // console.log('Nuevo')



    let idElement = getIdBtn(element);



    $('#modal-titleView').text($(element).attr('name'));



    $('#modalView').modal('show');





    let arrayData = await getDataCompras(idElement);



    // let arrayDataItems = arrayData['items'];



    arrayData = arrayData['data'];



    insertDataInputsFormView(arrayData);



    // insertDataChecBox(arrayData);



    // await statusItemsEdit(arrayDataItems);



    // if (arrayData['Formulacion'] == 0) {



    //     deshabilidarInputs('formView', '_View', 'ItemView');



    // }



    // document.getElementById('infoPorcentajeItemsView').innerHTML = '';





};



const insertDataInputsFormView=(data)=>{

    // console.log(data);

    document.getElementById('Nombre_View').value=data['NombreUsuario'];

    document.getElementById('Producto_View').value=data['NombreProducto'];

    document.getElementById('Entrada_View').value=mascarMonedaInputs(data['Entrada']);

    document.getElementById('Proveedor_View').value=data['NombreProveedor'];

    document.getElementById('Precio_View').value=mascarMonedaInputs(data['totales']);

    document.getElementById('Moneda_View').value=data['NombreMoneda'];

    document.getElementById('TpCambio_View').value=data['TipoCambio'];

    document.getElementById('NoFactura_View').value=data['NoFactura'];

    document.getElementById('Observaciones_View').value=data['Observaciones'];



    return true;

};















// -------------------------------------------------------------------------------------------------Fetch









const getDataCompras = async (id) => {



    return (await fetch(rutaApi + '?Accion=compras&Tabla=compras&Id=' + id, {



        method: 'GET',



        headers: { 'Content-Type': 'application/json' }



    }).then(respuesta => respuesta.json())



        .then(respuesta => {



            return respuesta;



        })



    )



}





$('#Producto_alta').change(function () {



    idSelect = this.id;



    IdProducto = $("#" + idSelect + " option:selected").val();



    if(IdProducto){



        let promesa = getAccion('?Accion=ajustes&Tabla=productos&Id=' + IdProducto);



        promesa.then(datos => {



            $('#Existente_alta').val(mascarMonedaInputs(datos.data['Total']));

            $('#Despues_alta').val(mascarMonedaInputs(datos.data['Total']));


            // $('#Precio_alta').val(datos.data['PrecioLitros']);



            totalEntradaSalida();



        });

    }





});





const getProveedor = async (id) => {



    return await fetch(rutaApi + '?Accion=compras&Tabla=proveedor&Id=' + id, {



        method: 'GET',



        headers: { 'Content-Type': 'application/json' }



    }).then(respuesta => respuesta.json())



        .then(respuesta => {



            return respuesta;

        })



}


// const totalEntradaSalida = () => {



//     let existente = $('#Existente_alta').val();



//     let entrada = $('#Entrada_alta').val();



//     if (existente != '') {

//         existente = parseInt(existente);

//     } else {

//         existente = 0;

//     }

//     if (entrada != '') {

//         entrada = parseInt(entrada);

//     } else {

//         entrada = 0;

//     }



//     let total = existente + entrada;



//     if (Math.sign(total) == -1) {

//         $('#Despues_alta').val(total);

//         $('#Despues_alta').css('color', 'red');

//     } else {

//         if (Math.sign(total) >= 0) {

//             $('#Despues_alta').val(total);

//             $('#Despues_alta').css('color', 'black');

//         }

//     }

// }


const totalEntradaSalida = () => {

    let existente = $('#Existente_alta').val();

    let entrada = $('#Entrada_alta').val();

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

    let total = existente + entrada;

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
$('#Entrada_alta').keyup(function () {



    totalEntradaSalida();



});





const insertCompras = async (dataForm) => {

    let accion = { "Accion": "compras", "Tabla": "compras", 'Data': dataForm, };



    return await fetch(rutaApi, {



        method: 'POST',



        body: JSON.stringify(accion),



        headers: { 'Content-Type': 'application/json' }



    }).then(respuesta => respuesta.json())



        .then(respuesta => {



            if (respuesta['success']) {



                showAlert("Correcto", respuesta['messenge'], "success")



                $('#modalAlta').modal('hide');

                let tablaCargar = $('#tablaCompras').DataTable();

                let comentario = "Registro la compra con el ID:" + respuesta['data'];

                setBitacora('1', comentario, modulo);

                // dropZonaEvidenciasAlta.processQueue();



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







let dataExcel={

    idBtnExcel:'btnExcelTabla',

    nameFile:'Compras',

    urlApi:rutaApi,

    columnasExcel:['E3','G3'],

    accion:`?Accion=compras&getDataExcel=1&Tabla=compras`,

    urlVendor:'../../requerimientos/vendors/spreadsheet/spreadsheetExcel.php'

};



let excelTabla = new exportarExcelTabla(dataExcel);









// $('#Producto_alta').select2();



// $('#Producto_alta').select2({

//     dropdownParent: $('#modalAlta')

// });





// $('#Proveedor_alta').select2();



// $('#Proveedor_alta').select2({

//     dropdownParent: $('#modalAlta')

// });




// --------------------------------------------------------------------------PDF 
const getDataPDF = async (id) => {

    return (await fetch(rutaApi, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    }).then(respuesta => respuesta.json())
        .then(respuesta => {
            return respuesta;
        })
    )

}


const createPDF=async(id)=>{
    
    if(statusPDF==0){

        window.open('viewPDF.php?id='+id+'&statusPDF='+statusPDF);
    
        return;
    }

    dataPDF = await getAccion('?Accion=compras&createPDF=purchaseOrder&IdPDF='+id);

    downloadPDF(dataPDF['data']);
    
}




const downloadPDF= (dataPDF) => {

    let urlPDF ="../pdf/purchaseOrder.php"; 

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

    createPDF(id);

})
