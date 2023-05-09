$(document).ready(function () { //Informacion al cargar la pagina



    $('#titlePage').text('Ajustes');



    tablaPrincipal();



    getSelectProductoPrimaryos();







})



const resetTablas=()=>{

    // Sin Monedas a calcular 

    // tablaPrincipal();

    // "destroy": true,

  

}



const modulo =7;

const tablaPrincipal = () => {

    var accion = { "Accion": "ajustes", "Tabla": "ajustes" }



    var tablaSSP = $('#tablaAjustes').DataTable({

        "order": [[ 0, "desc" ]],



        'ajax': {

            'url': rutaApi,

            'type': 'GET',

            'data': accion,

            'dataSrc': 'data',

        },


        'columns': [



            { 'data': 'Id' },



            { 'data': 'Nombre' },



            { 'data': 'Existencia',className:'text-center', "render": function (data) {
                return addCommas(data); 
            } },


            { 'data': 'Entrada', "render": function (data) {
                return addCommas(data); 
            } },


            { 'data': 'Salida', "render": function (data) {
                return addCommas(data); 
            } },

            { 'data': 'ExistenciaDespues',className:'text-center', "render": function (data) {
                return addCommas(data); 
            } },



            { 'data': 'Fecha', 'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss',' DD/MM/YYYY')},

            

            { 'data': 'Observaciones' },



            



            // { 'data': 'Formulacion',className:'text-center'},

            // { 'data': 'acciones'}



        ],



        'language': {





            'url': '../../requerimientos/vendors/DataTables-1.10.24/language/Spanish_Mexico.json'





        },



        "scrollY": "500px",



        "sScrollX": "100%",



        "sScrollXInner": "100%",



        "scrollCollapse": true,



        "paging": false,



    })



}



// -------------------------------------------------------------------------------------------------Funciones



const getDataItems = async (idForm, typeForm, separador) => {



    let arrayDataInputsRutas = document.querySelectorAll(`#${idForm} .boxItem`);



    let arrayDataInfoItems = [];





    arrayDataInputsRutas.forEach(boxItems => {



        // id del Producto a actualizar si es el caso

        Id = null;



        idBox = typeForm + separarString(boxItems.id, separador, 1);



        arrayObj = {};



        valuePorcentaje = boxItems.querySelector('#Porcentaje' + idBox).value;





        if (boxItems.querySelector('#Porcentaje' + idBox).getAttribute('attrIdProducto')) {



            Id = boxItems.querySelector('#Porcentaje' + idBox).getAttribute('attrIdProducto');



        }



        valueProducto = boxItems.querySelector('#ProductoPrimario' + idBox).value;



        // AlmacenarData



        arrayObj = {

            Id: Id,

            Porcentaje: valuePorcentaje,

            Producto: valueProducto

        }



        arrayDataInfoItems.push(arrayObj);



    })





    return arrayDataInfoItems;

}





const getDataForms = async (claseInpustData, separador) => {



    let arrayInputsForm = document.querySelectorAll('.' + claseInpustData);

    // console.log(arrayInputsForm);

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





const insertSelectInput =async (id, data) => {



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





const getDataFormCheckes = async (claseGetData, arrayDataInfo) => {



    let arrayDataChecked = document.querySelectorAll('.' + claseGetData);



    arrayDataChecked.forEach(inputCheck => {



        if (inputCheck.checked) {



            arrayDataInfo[inputCheck.id] = 1;



        } else {



            arrayDataInfo[inputCheck.id] = 0;



        }

    })





    return arrayDataInfo;



}





const getDataInputsForms = async (claseGetData) => {



    let arrayInpust = document.querySelectorAll('.' + claseGetData)



    let arrayDataForm = [];



    arrayInpust.forEach(input => {



        arrayDataForm[input.id] = document.getElementById(input.id).value;



    });



    return arrayDataForm;



}









const insertDataChecBox = (dataCheck) => {



    document.getElementById('Hazmat').checked = dataCheck['Hazmat'] == 1 ? true : false;



    document.getElementById('Formulacion').checked = dataCheck['Formulacion'] == 1 ? true : false;



    return;

}











// -------------------------------------------------------------------------------------------------Alta



// $('.btnAceptarAlta').on('click', async () => {





//     if (respValidar('validarDataAlta')) {

//         let data = await getDataForms('formAltaData', '_alta')

//         // console.log(data);



//         if (data.Existente != data.Despues) {

//             IdProducto = $("#" + idSelect + " option:selected").val();

//             let Existente = data.Existente;

//             let Entrada = data.Entrada;

//             let Salida = data.Salida;

//             if (Existente != '') {

//                 Existente = parseInt(Existente);

//             } else {

//                 existente = 0;

//             }

//             if (Entrada != '') {

//                 Entrada = parseInt(Entrada);

//             } else {

//                 Entrada = 0;

//             }

//             if (Salida != '') {

//                 Salida = parseInt(Salida);

//             } else {

//                 Salida = 0;

//             }

            

//             let mRestante = Existente + Entrada - Salida;

//             // console.log(mRestante);

//             if (Math.sign(mRestante) >=0) {

//                 let promesa = getProducto(IdProducto);

//                 promesa.then(datos => {

//                     if (parseInt(data.Existente) == datos.data['Total']) {



//                         insertAjuste({ ...data });

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

//     // if( $('#Formulacion_alta').prop('checked') ) {



//     //     if(respValidar('validarDataAlta') & respValidar('validarAltaDataItems')){



//     //         if(validarPorcentaje('infoPorcentajeItemsAlta')){



//     //             // Enviar los datos de los items

//     //             initAltaProducto(true);



//     //         }



//     //     };

//     // }else{



//     //     if(respValidar('validarDataAlta')){





//     //         initAltaProducto(false);

//     //     };

//     // }









// })

$('.btnAceptarAlta').on('click', async () => {


    if (respValidar('validarDataAlta')) {
        let data = await getDataForms('formAltaData', '_alta')
        // console.log(data);

        if (data.Existente != data.Despues) {
            IdProducto = $("#" + idSelect + " option:selected").val();
            let Existente = data.Existente;
            let Entrada = data.Entrada;
            let Salida = data.Salida;
            if (Existente != '') {
                Existente=Existente.replace(/,/g, "");
                Existente = parseFloat(Existente);
            } else {
                existente = 0;
            }
            if (Entrada != '') {
                Entrada=Entrada.replace(/,/g, "");
                Entrada = parseFloat(Entrada);
            } else {
                Entrada = 0;
            }
            if (Salida != '') {
                Salida=Salida.replace(/,/g, "");
                Salida = parseFloat(Salida);
            } else {
                Salida = 0;
            }
            
            let mRestante = Existente + Entrada - Salida;
            // console.log(mRestante);
            if (Math.sign(mRestante) >=0) {
                let promesa = getProducto(IdProducto);
                promesa.then(datos => {
                    let total =datos.data['Total'].replace(/,/g, "");
                    total=parseFloat(total)
                    let dataExistente =data.Existente.replace(/,/g, "");
                    if (parseFloat(dataExistente) == total) {
                        let arrayObj ={};
                        console.log(data);
                        arrayObj = {
                            Despues: parseFloat(data.Despues.replace(/,/g, "")),
                            Entrada: Entrada,
                            Existente: parseFloat(data.Existente.replace(/,/g, "")),
                            Observaciones: data.Observaciones,
                            ProductoPrimario_example: data.ProductoPrimario_example,
                            Salida: Salida,
                            //AGREGADO ANGEL 21/04/23
                            Precio: parseFloat(data.Precio.replace(/,/g, "")), 
                            PrecioActual:parseFloat(data.PrecioActual.replace(/,/g, ""))

                        }
                       
                        insertAjuste({ ...arrayObj });
                        // insertAjuste({ ...data });
                        // showAlert("Datos correctos",'Se han aplicado ajuste',"success")
                    } else {
                        showAlert("Los datos cambiaron", 'Los datos de inventarios no son los correctos favor de veridicar', "error")

                    }
                });
            } else {
                showAlert("Material insuficiente", 'El material es insuficiente para ajuste', "error")

            }
        } else {
            //AGREGADO ANGEL 21/04/23
            if(!data.Precio){
                showAlert("Sin Ajustes", 'No se encontraron ajustes', "info")
            }else{
                // console.log(data);
                arrayObj = {
                    Despues: parseFloat(data.Despues.replace(/,/g, "")),
                    Entrada: 0,
                    Existente: parseFloat(data.Existente.replace(/,/g, "")),
                    Observaciones: data.Observaciones,
                    ProductoPrimario_example: data.ProductoPrimario_example,
                    Salida: 0,
                    Precio: parseFloat(data.Precio.replace(/,/g, "")), 
                    PrecioActual:parseFloat(data.PrecioActual.replace(/,/g, ""))
                }
                insertAjuste({ ...arrayObj });
            }


            // console.log(arrayObj);
            // showAlert("Sin Ajustes", 'No se encontraron ajustes', "info")
        }


    }
    // if( $('#Formulacion_alta').prop('checked') ) {

    //     if(respValidar('validarDataAlta') & respValidar('validarAltaDataItems')){

    //         if(validarPorcentaje('infoPorcentajeItemsAlta')){

    //             // Enviar los datos de los items
    //             initAltaProducto(true);

    //         }

    //     };
    // }else{

    //     if(respValidar('validarDataAlta')){


    //         initAltaProducto(false);
    //     };
    // }




})



const validarPorcentaje = (idPorcentaje) => {



    porcentaje = document.getElementById(idPorcentaje).textContent;



    if (porcentaje == '100%') {



        return true;



    } else {



        showAlert("Alerta", 'El porcentaje de la formulación debe de ser del 100%', "info")



        return false;



    }





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



    limpiarInputsAdvertencias('formAltaData');



    document.getElementById('formAlta').reset();



    $('#Entrada_alta').prop('disabled',false);

    $('#Salida_alta').prop('disabled',false);



    $('#ProductoPrimario_example').val('').trigger('chosen:updated');







})





$('#formItems_alta').on('click', (event) => {



    let box = event.target;





    let checkboxFormulacion = document.getElementById('Formulacion_alta');



    let checked = checkboxFormulacion.checked;





    if (box.classList.contains('boxEliminarItem') & checked) {



        let idContainerInput = separarString(box.id, 'Num_', 1);







        BoxRutasCopias = document.getElementById('boxItemAlta' + idContainerInput)

        BoxRutasCopias.remove();



        statusPorcentaje();



    }



    if (box.classList.contains('btnModalAgregar') & checked) {



        crearBoxAlta();



    }





})





const initAltaProducto = async (statusItems) => {



    let arrayDataProducto = await getDataForms('formAltaData', '_alta');



    arrayDataChecked = document.querySelectorAll('.formAltaDataChecked');



    arrayDataChecked.forEach(inputCheck => {



        nombreInput = separarString(inputCheck.id, '_alta', 0);



        if (inputCheck.checked) {



            arrayDataProducto[nombreInput] = 1;



        } else {



            arrayDataProducto[nombreInput] = 0;



        }





    })





    let arrayDataItems = [];



    if (statusItems) {



        arrayDataItems = await getDataItems('formAlta', '_alta', 'ItemAlta');



    }







    await insertProducto({ ...arrayDataProducto }, { ...arrayDataItems })



}







const deshabilidarInputs = async (idForm, typeForm, separador) => {



    let arrayDataInputsRutas = document.querySelectorAll(`#${idForm} .boxItem`);



    // console.log('Entro');



    arrayDataInputsRutas.forEach(boxItems => {



        idBox = typeForm + separarString(boxItems.id, separador, 1);



        boxItems.querySelector('#Porcentaje' + idBox).setAttribute('disabled', true);



        boxItems.querySelector('#ProductoPrimario' + idBox).setAttribute('disabled', true);



    })



    return true;



}







const habilidarInputs = async (idForm, typeForm, separador) => {



    let arrayDataInputsRutas = document.querySelectorAll(`#${idForm} .boxItem`);



    arrayDataInputsRutas.forEach(boxItems => {



        idBox = typeForm + separarString(boxItems.id, separador, 1);



        boxItems.querySelector('#Porcentaje' + idBox).removeAttribute('disabled');



        boxItems.querySelector('#ProductoPrimario' + idBox).removeAttribute('disabled');



    })



    return true;



}









// -------------------------------------------------------------------------------------------------Edit



$('#tablaAjustes tbody').on('click', '.btnEditarTabla', function (e) {



    let arrayInpustLimpiar = document.querySelectorAll('.formEditData')



    arrayInpustLimpiar.forEach(input => {



        $("#" + input.id).css({ 'border-color': '#ced4da', "border-weight": "0" });



        $("#ul_" + input.id).css({ 'display': 'none' })



    })



    document.querySelector('#itemsSecundarios_edit').innerHTML = '';



    document.querySelector('#itemsPrincipal_edit').innerHTML = '';



    document.getElementById("formEdit").reset();



    initModalEdit(this);



});





$('#formItems_edit').on('click', (event) => {



    let box = event.target;



    let checkboxFormulacion = document.getElementById('Formulacion');



    let checked = checkboxFormulacion.checked;







    if (box.classList.contains('boxEliminarItem') & checked) {



        let idContainerInput = separarString(box.id, 'Num_', 1);





        // deleteProducto()

        BoxRutasCopias = document.getElementById('boxItemEdit' + idContainerInput)

        BoxRutasCopias.remove();



    }



    if (box.classList.contains('btnModalAgregar') & checked) {



        crearBoxEdit();



    }





})







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



    let idElement = getIdBtn(element);



    $('.btnAceptarEdit').attr('id', idElement);



    $('#modalEditTitle').text($(element).attr('name'));



    $('#modalEdit').modal('show');





    let arrayData = await getDataProducto(idElement);



    let arrayDataItems = arrayData['items'];



    arrayData = arrayData['data'];



    insertDataInputsFormEdit(arrayData, 'formEditData');



    insertDataChecBox(arrayData);



    await statusItemsEdit(arrayDataItems);



    if (arrayData['Formulacion'] == 0) {



        deshabilidarInputs('formEdit', '_edit', 'ItemEdit');



    }



    document.getElementById('infoPorcentajeItemsEdit').innerHTML = '';





};





const statusItemsEdit = async (arrayDataItems) => {





    if (!arrayDataItems.length) {



        await crearBoxEdit();



        return;

    }



    document.querySelector('#itemsSecundarios_edit').innerHTML = '';



    document.querySelector('#itemsPrincipal_edit').innerHTML = '';





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





const getDataFormProductoEdit = async (id, statusItems) => {







    let arrayDataInputs = await getDataInputsForms('formEditData');





    arrayDataInputs = await getDataFormCheckes('formEditDataChecked', arrayDataInputs);





    let arrayDataItems = [];



    if (statusItems) {



        arrayDataItems = await getDataItems('formEdit', '_edit', 'ItemEdit');



    }





    editProducto(id, { ...arrayDataInputs }, { ...arrayDataItems });



};











// -------------------------------------------------------------------------------------------------Deshabilitar



$('#tablaAjustes tbody').on('click', '.btnDeshabilitarTabla', function (e) {



    initModalDeshabilidar(this);



})



$('#tablaAjustes tbody').on('click', '.btnHabilitarTabla', function (e) {



    initModalHabilidar(this);



})





const initModalDeshabilidar = (element) => {



    let idElement = getIdBtn(element);



    $('.btnAceptarDeshabilitar').attr('id', idElement);



    $('#modalDeshabilitarTitle').text($(element).attr('name'));



    $('#modalDeshabilitar').modal('show');



}



const initModalHabilidar = (element) => {



    let idElement = getIdBtn(element);



    $('.btnAceptarHabilitar').attr('id', idElement);



    $('#modalHabilitarTitle').text($(element).attr('name'));



    $('#modalHabilitar').modal('show');



}



$('.btnAceptarDeshabilitar').on('click', async function (e) {



    let respuestaUpdate = await updateStatusProducto(this.id, 0);



    if (respuestaUpdate['success']) {



        $('#modalDeshabilitar').modal('hide');



        let tablaCargar = $('#tablaAjustes').DataTable();

        tablaCargar.ajax.reload();



    }







})





$('.btnAceptarHabilitar').on('click', async function (e) {







    let respuestaUpdate = await updateStatusProducto(this.id, 1);



    if (respuestaUpdate['success']) {



        $('#modalHabilitar').modal('hide');



        let tablaCargar = $('#tablaAjustes').DataTable();

        tablaCargar.ajax.reload();



    }



})





// -------------------------------------------------------------------------------------------------Fetch



const editProducto = async (id, dataForm, dataItems) => {



    let accion = { "Accion": "productos", "Tabla": "productos", 'Data': dataForm, 'Items': dataItems, 'Id': id };



    return await fetch(rutaApi, {



        method: 'PUT',



        body: JSON.stringify(accion),



        headers: { 'Content-Type': 'application/json' }



    }).then(respuesta => respuesta.json())



        .then(respuesta => {



            if (respuesta['success']) {



                showAlert("Correcto", respuesta['messenge'], "success")



                $('#modalEdit').modal('hide');

                let tablaCargar = $('#tablaAjustes').DataTable();

                tablaCargar.ajax.reload();



            } else {



                showAlert("Alerta", respuesta['messenge'], "info")



            }





        })



}





const getDataProducto = async (id) => {



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



// $('.ProductoPrimario_example').change(function () {



//     idSelect = this.id;



//     IdProducto = $("#" + idSelect + " option:selected").val();



//     if(IdProducto){



//         let promesa = getProducto(IdProducto);

//         promesa.then(datos => {

//             let totalP = datos.data['Total'];

//             if(Math.sign(totalP) == -1){



//                 $('#Salida_alta').attr('disabled', true);

//                 $('#Entrada_alta').attr('disabled', false);

//                 $('#Salida_alta').val('');

//                 $('#Existente_alta').val(datos.data['Total']);

//                 $('#Despues_alta').val(datos.data['Total']);

//             }else{

//                 $('#Salida_alta').attr('disabled', false);

                

//                 $('#Existente_alta').val(datos.data['Total']);

//                 $('#Despues_alta').val(datos.data['Total']);

//             }

//             totalEntradaSalida();

//         });



//     }



// });

$('.ProductoPrimario_example').change(function () {

    idSelect = this.id;

    IdProducto = $("#" + idSelect + " option:selected").val();

    if(IdProducto){

        let promesa = getProducto(IdProducto);
        console.log(promesa);
        promesa.then(datos => {
            let totalP = datos.data['Total'];
            if(Math.sign(totalP) == -1){
                // console.log("Cambio de producto");
                $('#Salida_alta').attr('disabled', true);
                $('#Entrada_alta').attr('disabled', false);
                $('#Salida_alta').val('');
                $('#Existente_alta').val(mascarMonedaInputs(datos.data['Total']));
                $('#Despues_alta').val(mascarMonedaInputs(datos.data['Total']));

            }else{
                $('#Salida_alta').attr('disabled', false);
                
                $('#Existente_alta').val(mascarMonedaInputs(datos.data['Total']));
                $('#Despues_alta').val(mascarMonedaInputs(datos.data['Total']));
            }
            $('#PrecioActual_alta').val(mascarMonedaInputs(datos.data['PrecioLitros']));

            totalEntradaSalida();
        });

    }

});

const getProducto = async (id) => {



    return await fetch(rutaApi + '?Accion=ajustes&Tabla=productos&Id=' + id, {



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

        .then(async(respuesta) => {

            await insertSelectInput('ProductoPrimario_example', respuesta['data']);



            $("#ProductoPrimario_example").chosen({

                width: "100%",

                no_results_text: "No se a encontrado resultados",

                allow_single_deselect: true,

              });

            return respuesta;

        })



}

// function totalEntradaSalida() {

//     // idSelect=this.val;

//     let existente = $('#Existente_alta').val();

//     let entrada = $('#Entrada_alta').val();

//     let salida = $('#Salida_alta').val();

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

//     if (salida != '') {

//         salida = parseInt(salida);

//     } else {

//         salida = 0;

//     }



//     // entrada = parseInt(entrada);

//     // salida = parseInt(salida);

//     let total = existente + entrada - salida;

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





const insertAjuste = async (dataForm) => {

    let accion = { "Accion": "ajustes", "Tabla": "ajustes", 'Data': dataForm, };



    return await fetch(rutaApi, {



        method: 'POST',



        body: JSON.stringify(accion),



        headers: { 'Content-Type': 'application/json' }



    }).then(respuesta => respuesta.json())



        .then(respuesta => {



            if (respuesta['success']) {


                showAlert("Correcto", respuesta['messenge'], "success")

                $('#modalAlta').modal('hide');

                let tablaCargar = $('#tablaAjustes').DataTable();

                // console.log(dataForm);
                let invExistente=dataForm.Existente;
                let invDespues=dataForm.Despues;
                let precio=dataForm.Precio;
                let precioActual=dataForm.PrecioActual;
                let comentarioInit="Realizo el ajuste al Producto: "+respuesta['data']['Nombre'];
                if(invExistente===invDespues){
                    comentario = comentarioInit+", Con el precio actual: "+precioActual+" Al precio: "+precio+" "+dataForm.Observaciones;
                    
                }else{
                    if(precio>0){
                        comentario = comentarioInit+", Con la cantidad existente de:"+invExistente+" A la cantidad de: "+invDespues+" y del precio: "+precioActual+" Al precio: "+precio+" "+dataForm.Observaciones;

                    }else{
                        comentario = comentarioInit+", Con la cantidad existente de:"+invExistente+" A la cantidad de: "+invDespues+" "+dataForm.Observaciones;

                    }
                }

                // console.log(comentario);

                setBitacora('5', comentario, modulo);



                // dropZonaEvidenciasAlta.processQueue();



                tablaCargar.ajax.reload();



            } else {



                showAlert("Alerta", respuesta['messenge'], "info")



            }



            return respuesta['success'];

        })

}



const insertProducto = async (dataForm, dataItems) => {



    let accion = { "Accion": "productos", "Tabla": "productos", 'Data': dataForm, 'Items': dataItems };



    return await fetch(rutaApi, {



        method: 'POST',



        body: JSON.stringify(accion),



        headers: { 'Content-Type': 'application/json' }



    }).then(respuesta => respuesta.json())



        .then(respuesta => {



            if (respuesta['success']) {



                showAlert("Correcto", respuesta['messenge'], "success")



                $('#modalAlta').modal('hide');

                let tablaCargar = $('#tablaAjustes').DataTable();



                dropZonaEvidenciasAlta.processQueue();



                tablaCargar.ajax.reload();



            } else {



                showAlert("Alerta", respuesta['messenge'], "info")



            }



            return respuesta['success'];

        })



}





const deleteProducto = async (id) => {



    return (await fetch(rutaApi + '?Accion=productos&Tabla=productos&Id=' + id, {



        method: 'DELETE',



        headers: { 'Content-Type': 'application/json' }



    }).then(respuesta => respuesta.json())



        .then(respuesta => {



            if (respuesta['success']) {



                showAlert("Correcto", respuesta['messenge'], "success")



            } else {



                showAlert("Alerta", respuesta['messenge'], "info")



            }



            return respuesta['success'];



        })



    )



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



    container.querySelector('.Porcentaje_exampleClass').classList.replace('formDataExample', `form${key}DataItems`);

    container.querySelector('.Porcentaje_exampleClass').classList.replace('validarDataExample', `validar${key}DataItems`);





    container.querySelector('.ProductoPrimario_exampleClass').classList.replace('formDataExample', `form${key}DataItems`);

    container.querySelector('.ProductoPrimario_exampleClass').classList.replace('validarDataExample', `validar${key}DataItems`);





    return container;

}





const cambiarInfoInput = (container, key) => {





    container.querySelector('#Porcentaje_example').id = 'Porcentaje' + key;



    container.querySelector('#ul_Porcentaje_example').id = 'ul_Porcentaje' + key;



    return container;



}





const cambiarInfoSelect = (container, key) => {



    container.querySelector('#ProductoPrimario_example').id = 'ProductoPrimario' + key;



    container.querySelector('#ul_ProductoPrimario_example').id = 'ul_ProductoPrimario' + key;





    return container;



}



var numberContadorEdit = 0;



const crearBoxEdit = async () => {



    numberContadorEdit++;





    // Container de Rutas que se pueden Eliminiar

    let boxSecundarios = document.getElementById('itemsSecundarios_edit');



    // Item Principal

    let boxPrincipal = document.getElementById('itemsPrincipal_edit');

    let countContainerPrincipal = Number(boxPrincipal.childElementCount);





    let boxCloneNode = document.querySelector(".boxItemDefaul").cloneNode(true)

    boxCloneNode.classList.replace('boxItemDefaul', 'boxItem');

    boxCloneNode.id = 'boxItemEdit' + numberContadorEdit;





    if (countContainerPrincipal == 0) {



        boxCloneNode.querySelector('.containerBtnsDefault').innerHTML = `<div id='Num_${numberContadorEdit}' class="d-inline btn btn-success rounded-10 btn-sm btnModalAgregar bx bx-plus" style='font-size: 17px; color:#ffffff;' type="button" title="Agregar"></div>`



    } else {



        boxCloneNode.querySelector('.containerBtnsDefault').innerHTML = `<div id='Num_${numberContadorEdit}' class="d-inline btn btn-danger rounded-10 btn-sm boxEliminarItem bx bx-x" style='font-size: 17px; color:#ffffff;' type="button" title="Borrar"></div>`



    }

    // --------------------------------------





    container = await modificarInfoItemEdit(boxCloneNode, numberContadorEdit)



    if (countContainerPrincipal == 0) {



        boxPrincipal.appendChild(container);



    } else {



        boxSecundarios.appendChild(container);



    }





    // $('#ProductoPrimario_alta'+numberContadorEdit).select2();



    // $('#ProductoPrimario_alta'+numberContadorEdit).select2({

    //     dropdownParent: $('#modalAlta')

    // });





    return numberContadorEdit;

}





const modificarInfoItemEdit = (containerBox, number) => {



    containerBox = cambiarInfoInput(containerBox, '_edit' + number, 'formEditData ');



    containerBox = cambiarInfoItems(containerBox, 'Edit');



    return cambiarInfoSelect(containerBox, '_edit' + number);



}



const insertDataItem = async (key, itemData) => {

    document.getElementById('Porcentaje_edit' + key).value = itemData['Porcentaje'];

    document.getElementById('Porcentaje_edit' + key).setAttribute('attrIdProducto', itemData['Id']);

    document.getElementById('ProductoPrimario_edit' + key).value = itemData['IdProducto'];

    document.querySelector('#formItems_edit #Num_' + key).setAttribute('idProctoPrimario', itemData['Id']);



    return true;

}





// -------------------------------------------------------------------------------------------------





function statusPorcentaje(eve) {



    let totalPorcentajeInputs = 0;

    let textInfoPorcentaje;



    if (eve) {



        let input = document.getElementById(eve)



        if (input.classList.contains('formAltaDataItems')) {

            totalPorcentajeInputs = getStatusInputsPorcentaje('formAlta', '_alta', 'ItemAlta');



            textInfoPorcentaje = 'infoPorcentajeItemsAlta';

        } else {



            // console.log('Edit');

            totalPorcentajeInputs = getStatusInputsPorcentaje('formEdit', '_edit', 'ItemEdit');

            textInfoPorcentaje = 'infoPorcentajeItemsEdit';



        }

    }





    let total = totalPorcentajeInputs.toString();





    if (textInfoPorcentaje) {



        if (total == 100) {

            document.getElementById(textInfoPorcentaje).style.color = '#28a745'

        } else {



            document.getElementById(textInfoPorcentaje).style.color = '#f00'



        }



        document.getElementById(textInfoPorcentaje).innerHTML = total + '%'





    }



}





const getStatusInputsPorcentaje = (idForm, typeForm, separador) => {



    let arrayDataInputsRutas = document.querySelectorAll(`#${idForm} .boxItem`);



    let totalPorcentajeInputs = 0;





    arrayDataInputsRutas.forEach(boxItems => {



        idBox = typeForm + separarString(boxItems.id, separador, 1);



        valuePorcentaje = boxItems.querySelector('#Porcentaje' + idBox).value;



        if (!valuePorcentaje) {



            valuePorcentaje = 0;



        }



        totalPorcentajeInputs = parseInt(totalPorcentajeInputs) + parseInt(valuePorcentaje);



    })





    return totalPorcentajeInputs;

}







// ------------------------------------------------------ Alta Evidencias



let dataExcel={

    idBtnExcel:'btnExcelTabla',

    nameFile:'Ajustes',

    urlApi:rutaApi,

    accion:`?Accion=ajustes&getDataExcel=1&Tabla=ajustes`,

    urlVendor:'../../requerimientos/vendors/spreadsheet/spreadsheetExcel.php'

  }

  

  let excelTabla = new exportarExcelTabla(dataExcel);





