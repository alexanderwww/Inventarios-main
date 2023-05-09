$(document).ready(function() { //Informacion al cargar la pagina

    

    $('#titlePage').text('Productos');



    tablaProductos();



    // getSelectProductoPrimaryos();



    // Dropzone.autoDiscover = false; //Evita buscar classes dropzone en el documento y se auto asigne una configuracion establecida





    // subirArchivosAlta();



})



const resetTablas=()=>{

        // Sin monedas a cambiar 

    // tablaPrincipal();

    // "destroy": true,



}



const modulo = 5;



const tablaProductos=()=>{



    var accion = {"Accion" : "productos","Tabla":"productos"}



    var tablaSSP =$('#tablaProductos').DataTable({



        "order": [[ 1, "desc" ]],

    'ajax':{

      'url':rutaApi,

      'type': 'GET',

      'data':accion,      

      'dataSrc': 'data',

    },



    'columns': [

        { 'data': 'acciones'},



        { 'data': 'Id' },



        { 'data': 'Nombre' },



        { 'data': 'Densidad',"render": function (data) {
            return addCommas(data); 
        } },



        {'data': 'Color'},



        { 'data': 'Hazmat',className:'text-center'},



        { 'data': 'Marca' },



        { 'data': 'Concentracion', "render": function (data) {
            return addCommas(data);
        } },



        { 'data': 'Uso'},



        { 'data': 'Formulacion',className:'text-center'},

        

        { 'data': 'CAS'},



        { 'data': 'UN'},



        { 'data': 'Unidad'},



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



const getDataItems=async(idForm,typeForm,separador)=>{



    let arrayDataInputsRutas=document.querySelectorAll(`#${idForm} .boxItem`);



    let arrayDataInfoItems=[];



    

    arrayDataInputsRutas.forEach(boxItems=>{



        // id del Producto a actualizar si es el caso 

        Id=null;



        idBox=typeForm+separarString(boxItems.id,separador,1);

        

        arrayObj={};



        valuePorcentaje=boxItems.querySelector('#Porcentaje'+idBox).value;





        if(boxItems.querySelector('#Porcentaje'+idBox).getAttribute('attrIdProducto')){



            Id=boxItems.querySelector('#Porcentaje'+idBox).getAttribute('attrIdProducto');



        }

        

        valueProducto=boxItems.querySelector('#ProductoPrimario'+idBox).value;



        // AlmacenarData



        arrayObj={

            Id: Id,

            Porcentaje: valuePorcentaje,

            Producto:valueProducto

        }



        arrayDataInfoItems.push(arrayObj);



    })





    return arrayDataInfoItems;

}





const getDataForms=async(claseInpustData,separador)=>{



    let arrayInputsForm=document.querySelectorAll('.'+claseInpustData);



    let arrayData=[];



    arrayInputsForm.forEach(input=>{

         

            nombreInput=separarString(input.id,separador,0);



            arrayData[nombreInput]=input.value;



    })



    return arrayData;



}



const separarString=(text,separador,numberData)=>{



    var text=text.split(separador);



    return text[numberData];



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





const getIdBtn=(event)=>{



    let idString = $(event).attr('id');



    return idString.substring(2);



}

const getNameBtn=(event)=>{



    let name = $(event).attr('name');



    return name;



}



const getDataFormCheckes=async(claseGetData,arrayDataInfo)=>{



    let arrayDataChecked=document.querySelectorAll('.'+claseGetData);



    arrayDataChecked.forEach(inputCheck=>{



        if(inputCheck.checked){



            arrayDataInfo[inputCheck.id]=1;

        

        }else{



            arrayDataInfo[inputCheck.id]=0;



        }

    })





    return arrayDataInfo;



}





const getDataInputsForms=async (claseGetData)=>{



    let arrayInpust=document.querySelectorAll('.'+claseGetData)



    let arrayDataForm=[];



    arrayInpust.forEach(input =>{



        arrayDataForm[input.id]=document.getElementById(input.id).value;

 

    });



    return arrayDataForm;



}









const insertDataChecBox=(dataCheck)=>{



    document.getElementById('Hazmat').checked=dataCheck['Hazmat']==1?true:false;



    document.getElementById('Formulacion').checked=dataCheck['Formulacion']==1?true:false;



    return;

}









const reloadTable=(idTable,idModal)=>{



    $('#'+idModal).modal('hide');



    let tablaCargar = $('#'+idTable).DataTable();

    tablaCargar.ajax.reload();



    return;

}







// -------------------------------------------------------------------------------------------------Alta



$('.btnAceptarAlta').on('click',async()=>{





    if( $('#Formulacion_alta').prop('checked') ) {



        if(respValidar('validarDataAlta') & respValidar('validarAltaDataItems')){

        

            if(!validarCaracteres('validarCaracteresAlta')){



                return;

            }



            if(validarPorcentaje('infoPorcentajeItemsAlta')){

    

                // Enviar los datos de los items 

                initAltaProducto(true);



            }

    

        };

    }else{



        if(respValidar('validarDataAlta')){

        

            if(!validarCaracteres('validarCaracteresAlta')){



                return;

            }



            initAltaProducto(false);

    

        };

    }









})





const validarPorcentaje=(idPorcentaje)=>{



    porcentaje=document.getElementById(idPorcentaje).textContent;



    if(porcentaje=='100%'){



        return true;



    }else{



        showAlert("Alerta",'El porcentaje de la formulación debe de ser del 100%',"info")



        return false;



    }





}



const limpiarInputsAdvertencias=(getClass)=>{

    

    let arrayInpustLimpiar=document.querySelectorAll('.'+getClass);



    arrayInpustLimpiar.forEach(input=>{

            

    $("#" + input.id).css({ 'border-color': '#ced4da',"border-weight": "0" });



    $("#ul_"+input.id).css({'display':'none'})

    

    })

}



$('.btnModalAlta').on('click',async()=>{

  

    await getSelectProductoPrimaryos();

    document.getElementById('TotalPorcentaje').innerHTML= '';


    let accion ='?Accion=productos&Tabla=tipounidad&Select=3';

    await getSelect(accion,'tipoUnidad_alta')
    document.querySelector('.containerItems_alta').style.display='none';

    $('#modalAlta').modal('show');



    limpiarInputsAdvertencias('formAltaData');



    document.getElementById('formAlta').reset();

    $("#tipoUnidad_alta").chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });

    $("#Flameabilidad_alta").chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });

    $("#Reactividad_alta").chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });

    $("#Toxicidad_alta").chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });

    $("#Corrosividad_alta").chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });
    $('#tipoUnidad_alta').val('').trigger('chosen:updated');
    $('#Flameabilidad_alta').val('').trigger('chosen:updated');
    $('#Reactividad_alta').val('').trigger('chosen:updated');
    $('#Toxicidad_alta').val('').trigger('chosen:updated');
    $('#Corrosividad_alta').val('').trigger('chosen:updated');
    document.querySelector('#itemsSecundarios_alta').innerHTML='';



    document.querySelector('#itemsPrincipal_alta').innerHTML='';



    idNewBox=await crearBoxAlta();

    $('#ProductoPrimario_alta'+idNewBox).prop('disabled', true).trigger("chosen:updated");





    document.querySelector('#Porcentaje_alta'+idNewBox).setAttribute('disabled',true);

    document.querySelector('#ProductoPrimario_alta'+idNewBox).setAttribute('disabled',true);



    document.getElementById('infoPorcentajeItemsAlta').innerHTML='';



    dropZonaEvidenciasAlta.removeAllFiles(true);

})





// -------------------------------------------------------------Checks Formulacion 





let checkboxFormulacionAlta = document.getElementById('Formulacion_alta');



checkboxFormulacionAlta.addEventListener("change", validaCheckboxAlta, false);



function validaCheckboxAlta(){



  let checked = checkboxFormulacionAlta.checked;

  

  if(checked){

    

    statusPorcentaje();

    habilidarInputs('formAlta','_alta','ItemAlta');



  }else{



    limpiarInputsAdvertencias('formAltaDataItems');



    deshabilidarInputs('formAlta','_alta','ItemAlta');



    document.getElementById('infoPorcentajeItemsAlta').innerHTML='';



  }



}





let checkboxFormulacionEdit = document.getElementById('Formulacion');



checkboxFormulacionEdit.addEventListener("change", validaCheckboxEdit, false);



function validaCheckboxEdit(){



  let checked = checkboxFormulacionEdit.checked;

  

  

  if(checked){

    

    // habilidarInputs('formEdit','_edit','ItemEdit');

    statusPorcentajeEdit()





  }else{

  

    document.getElementById('infoPorcentajeItemsEdit').innerHTML='';



    limpiarInputsAdvertencias('formEditDataItems');



    // deshabilidarInputs('formEdit','_edit','ItemEdit');





  }



}











$('#formItems_alta').on('click',async(event)=>{

// console.log("le di click a formulacipon");

    let box=event.target;





    let checkboxFormulacion = document.getElementById('Formulacion_alta');



    let checked = checkboxFormulacion.checked;

    



    if(box.classList.contains('boxEliminarItem') & checked){



        let idContainerInput=separarString(box.id,'Num_',1);







        BoxRutasCopias=document.getElementById('boxItemAlta'+idContainerInput)

        BoxRutasCopias.remove();



        statusPorcentaje();



    }



    if(box.classList.contains('btnModalAgregar') & checked){



        let numberContadorAlta=await crearBoxAlta();



        // $('#ProductoPrimario_alta'+numberContadorAlta).prop('disabled', false).trigger("chosen:updated");



    }





})





const initAltaProducto=async(statusItems)=>{



    let arrayDataProducto=await getDataForms('formAltaData','_alta');



    arrayDataChecked=document.querySelectorAll('.formAltaDataChecked');



    arrayDataChecked.forEach(inputCheck=>{



        nombreInput=separarString(inputCheck.id,'_alta',0);



        if(inputCheck.checked){



            arrayDataProducto[nombreInput]=1;

        

        }else{



            arrayDataProducto[nombreInput]=0;



        }





    })



    

    let arrayDataItems=[];



    if(statusItems){



        arrayDataItems=await getDataItems('formAlta','_alta','ItemAlta');



    }







    await insertProducto({...arrayDataProducto},{...arrayDataItems})



}







const deshabilidarInputs=async(idForm,typeForm,separador)=>{


    let arrayDataInputsRutas=document.querySelectorAll(`#${idForm} .boxItem`);


    document.querySelector('.containerItems_alta').style.display='none';



    arrayDataInputsRutas.forEach(boxItems=>{



        idBox=typeForm+separarString(boxItems.id,separador,1);

        

        boxItems.querySelector('#Porcentaje'+idBox).setAttribute('disabled',true);



        // boxItems.querySelector('#ProductoPrimario'+idBox).setAttribute('disabled',true);

        $('#ProductoPrimario'+idBox).prop('disabled', true).trigger("chosen:updated");



    })



    return true;



}







const habilidarInputs=async(idForm,typeForm,separador)=>{



    let arrayDataInputsRutas=document.querySelectorAll(`#${idForm} .boxItem`);

    
    document.querySelector('.containerItems_alta').style.display='block';

    arrayDataInputsRutas.forEach(boxItems=>{



        idBox=typeForm+separarString(boxItems.id,separador,1);

        

        boxItems.querySelector('#Porcentaje'+idBox).removeAttribute('disabled');



        // boxItems.querySelector('#ProductoPrimario'+idBox).removeAttribute('disabled');



        $('#ProductoPrimario'+idBox).prop('disabled', false).trigger("chosen:updated");



    })



    return true;



}









// -------------------------------------------------------------------------------------------------Edit



$('#tablaProductos tbody').on('click', '.btnView',function (e) {



    initModalTemplate(this);

    $('#modalEditOption').text('');



    document.querySelector('.btnAceptarEdit').style.display='none';

    

    statusViewForm(false);



})



const statusViewForm=(status)=>{



    if(status){



        document.getElementById('Nombre');

        document.getElementById('Densidad').removeAttribute('disabled');

        document.getElementById('Color').removeAttribute('disabled');

        document.getElementById('Hazmat').removeAttribute('disabled');

        document.getElementById('Marca').removeAttribute('disabled');

        document.getElementById('Concentracion').removeAttribute('disabled');

        document.getElementById('Uso').removeAttribute('disabled');

        document.getElementById('Nombre').removeAttribute('disabled');

        document.getElementById('CAS').removeAttribute('disabled');

        document.getElementById('UN').removeAttribute('disabled');

        document.getElementById('tipoUnidad').removeAttribute('disabled');



        document.getElementById('Flameabilidad').removeAttribute('disabled');

        document.getElementById('Reactividad').removeAttribute('disabled');

        document.getElementById('Toxicidad').removeAttribute('disabled');

        document.getElementById('Corrosividad').removeAttribute('disabled');



    }else{



        document.getElementById('Densidad').setAttribute('disabled',true);

        document.getElementById('Color').setAttribute('disabled',true);

        document.getElementById('Hazmat').setAttribute('disabled',true);

        document.getElementById('Marca').setAttribute('disabled',true);

        document.getElementById('Concentracion').setAttribute('disabled',true);

        document.getElementById('Uso').setAttribute('disabled',true);

        document.getElementById('Nombre').setAttribute('disabled',true);

        document.getElementById('CAS').setAttribute('disabled',true);

        document.getElementById('UN').setAttribute('disabled',true);

        document.getElementById('tipoUnidad').setAttribute('disabled',true);



        document.getElementById('Flameabilidad').setAttribute('disabled',true);

        document.getElementById('Reactividad').setAttribute('disabled',true);

        document.getElementById('Toxicidad').setAttribute('disabled',true);

        document.getElementById('Corrosividad').setAttribute('disabled',true);

    }

}



$('#tablaProductos tbody').on('click', '.btnEditarTabla',function (e) {



    initModalTemplate(this);



    $('#modalEditOption').text('Actualizar');



    document.querySelector('.btnAceptarEdit').style.display='inline-block';

    

    statusViewForm(true);



});





const initModalTemplate=(event)=>{



    $('#tipoUnidad').val('').trigger('chosen:updated');



    $('#Flameabilidad').val('').trigger('chosen:updated');

    $('#Reactividad').val('').trigger('chosen:updated');

    $('#Toxicidad').val('').trigger('chosen:updated');

    $('#Corrosividad').val('').trigger('chosen:updated');



    let arrayInpustLimpiar=document.querySelectorAll('.formEditData')



    arrayInpustLimpiar.forEach(input=>{

            

    $("#" + input.id).css({ 'border-color': '#ced4da',"border-weight": "0" });



    $("#ul_"+input.id).css({'display':'none'})

    

    })



    document.querySelector('#itemsSecundarios_edit').innerHTML='';



    document.querySelector('#itemsPrincipal_edit').innerHTML='';



    document.getElementById("formEdit").reset();



    initModalEdit(event);



}



$('#formItems_edit').on('click',(event)=>{



    let box=event.target;



    let checkboxFormulacion = document.getElementById('Formulacion');



    let checked = checkboxFormulacion.checked;

    





    if(box.classList.contains('boxEliminarItem') & checked){



        let idContainerInput=separarString(box.id,'Num_',1);





        // deleteProducto(Id )

        BoxRutasCopias=document.getElementById('boxItemEdit'+idContainerInput)

        BoxRutasCopias.remove();



    }



    if(box.classList.contains('btnModalAgregar') & checked){



        crearBoxEdit();



    }





})







$('.btnAceptarEdit').on('click',()=>{


    if(respValidar('validarDataEdit')){

        

        if(!validarCaracteres('validarCaracteresEdit')){



            return;

        }

        getDataFormProductoEdit($('.btnAceptarEdit').attr('id'),false);        



    };



})









const initModalEdit=async(element)=>{

    await getSelectProductoPrimaryos();

    let idElement=getIdBtn(element);



    $('.btnAceptarEdit').attr('id', idElement);



    $('#modalEditTitle').text($(element).attr('name'));



    $('#modalEdit').modal('show');



    



    let arrayData=await getDataProducto(idElement);



    let accion ='?Accion=productos&Tabla=tipounidad&Select=3';

    

    await getSelect(accion,'tipoUnidad')

    $("#tipoUnidad").chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });



    $("#Flameabilidad").chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });

    $("#Reactividad").chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });

    $("#Toxicidad").chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });

    $("#Corrosividad").chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });



    let arrayDataItems=arrayData['items'];



    arrayData=arrayData['data'];



    insertDataInputsFormEdit(arrayData,'formEditData');



    $('#tipoUnidad').val(arrayData['tipoUnidad']).trigger('chosen:updated');

   

    $('#Flameabilidad').val(arrayData['Flameabilidad']).trigger('chosen:updated');

    $('#Reactividad').val(arrayData['Reactividad']).trigger('chosen:updated');

    $('#Toxicidad').val(arrayData['Toxicidad']).trigger('chosen:updated');

    $('#Corrosividad').val(arrayData['Corrosividad']).trigger('chosen:updated');



    insertDataChecBox(arrayData);

    

    await statusItemsEdit(arrayDataItems);



    // if(arrayData['Formulacion']==0){



    //     deshabilidarInputs('formEdit','_edit','ItemEdit');



    // }



    document.getElementById('infoPorcentajeItemsEdit').innerHTML='';



    // statusPorcentajeEdit()



    deshabilidarInputs('formEdit','_edit','ItemEdit');





};





const statusPorcentajeEdit=()=>{



    totalPorcentajeInputs=getStatusInputsPorcentaje('formEdit','_edit','ItemEdit');



    textInfoPorcentaje='infoPorcentajeItemsEdit';



    let total=totalPorcentajeInputs.toString();





    if(total==100){

        document.getElementById(textInfoPorcentaje).style.color='#28a745'

    }else{



        document.getElementById(textInfoPorcentaje).style.color='#f00'



    }



    document.getElementById('TotalPorcentaje').innerHTML= 'Total: ';

    document.getElementById(textInfoPorcentaje).innerHTML=total+'%'



}



const statusItemsEdit=async(arrayDataItems)=>{



    

    if(!arrayDataItems.length){

        

        await crearBoxEdit();



        return;

    }



    document.querySelector('#itemsSecundarios_edit').innerHTML='';



    document.querySelector('#itemsPrincipal_edit').innerHTML='';





    for (let itemData of arrayDataItems) {



        idContainerItem=await crearBoxEdit();



        await insertDataItem(idContainerItem,itemData);



    }



    return;



}



const insertDataInputsFormEdit=(data,claseGetData)=>{



    let arrayInpust=document.querySelectorAll('.'+claseGetData)



    arrayInpust.forEach(input =>{



        document.getElementById(input.id).value=data[input.id];

 

    });



    return true;



}





const getDataFormProductoEdit=async(id,statusItems)=>{







    let arrayDataInputs=await getDataInputsForms('formEditData');





    arrayDataInputs=await getDataFormCheckes('formEditDataChecked',arrayDataInputs);





    let arrayDataItems=[];



    if(statusItems){



        arrayDataItems=await getDataItems('formEdit','_edit','ItemEdit');



    }





    editProducto(id,{...arrayDataInputs},{...arrayDataItems});



};











// -------------------------------------------------------------------------------------------------Deshabilitar



$('#tablaProductos tbody').on('click', '.btnDeshabilitarTabla',function(e){



    initModalDeshabilidar(this);



})



$('#tablaProductos tbody').on('click', '.btnHabilitarTabla',function(e){



    initModalHabilidar(this);



})





const initModalDeshabilidar=(element)=>{

    

    let idElement=getIdBtn(element);

    let name =getNameBtn(element);

    $('.btnAceptarDeshabilitar').attr('id', idElement);



    $('.btnAceptarDeshabilitar').attr('name', name);



    $('#modalDeshabilitarTitle').text($(element).attr('name'));



    $('#modalDeshabilitar').modal('show');



}



const initModalHabilidar=(element)=>{

    

    let idElement=getIdBtn(element);

    let name =getNameBtn(element);



    $('.btnAceptarHabilitar').attr('id', idElement);



    $('.btnAceptarHabilitar').attr('name', name);





    $('#modalHabilitarTitle').text($(element).attr('name'));



    $('#modalHabilitar').modal('show');



}



$('.btnAceptarDeshabilitar').on('click', async function(e){

    let name = this.name;



    let respuestaUpdate=await updateStatusProducto(this.id,0,name);



    if(respuestaUpdate['success']){



        reloadTable('tablaProductos','modalDeshabilitar')

    

    }







})





$('.btnAceptarHabilitar').on('click',async function(e){





    let name = this.name;



    let respuestaUpdate=await updateStatusProducto(this.id,1, name);



    if(respuestaUpdate['success']){



        reloadTable('tablaProductos','modalHabilitar')

    

    }



})





// -------------------------------------------------------------------------------------------------Fetch



const editProducto=async (id,dataForm,dataItems)=>{



    let accion = {"Accion" : "productos","Tabla":"productos",'Data':dataForm,'Items':dataItems,'Id':id};



    return await fetch(rutaApi, {



        method: 'PUT',



        body: JSON.stringify(accion),



        headers: {'Content-Type': 'application/json'}

    

    }).then(respuesta=>respuesta.json())

    

    .then(respuesta =>{

        

        if(respuesta['success']){



            showAlert("Correcto",respuesta['messenge'],"success")   

            var comentario = "Edito el Producto: "+dataForm.Nombre;



         

         setBitacora('4', comentario, modulo);  



            reloadTable('tablaProductos','modalEdit')



        }else{



            showAlert("Alerta",respuesta['messenge'],"info")

        

        }





    })



}





const getDataProducto=async (id)=>{



    return( await fetch(rutaApi+'?Accion=productos&Tabla=productos&Id='+id, {



        method: 'GET',



        headers: {'Content-Type': 'application/json'}

    

    }).then(respuesta=>respuesta.json())

    

    .then(respuesta =>{

        

        return respuesta;



    })



    )



}



const updateStatusProducto=async (id,status,name)=>{



    let accion = {"Accion" : "productos","Tabla":"productos",'Id':id,'Status':status};



    return await fetch(rutaApi, {



        method: 'PUT',



        body: JSON.stringify(accion),



        headers: {'Content-Type': 'application/json'}

    

    }).then(respuesta=>respuesta.json())

    

    .then(respuesta =>{

        

        if(respuesta['success']){



            showAlert("Correcto",respuesta['messenge'],"success")  



            if(status==0){

                var comentario = "Deshabilito el Producto: "+name;

                var codigo = 2;

            }else{

               var comentario = "Habilito el Producto: "+name;

               var codigo = 7;



            }   

            setBitacora(codigo, comentario, modulo);



        }else{



            showAlert("Alerta",respuesta['messenge'],"info")

        

        }



        return respuesta;

    })



}





const getSelectProductoPrimaryos=async ()=>{



    return await fetch(rutaApi+'?Accion=productos&Tabla=productos&Select=2',{



        method: 'GET',



        headers: {'Content-Type': 'application/json'}

    

    }).then(respuesta=>respuesta.json())

    

    .then(respuesta =>{

        

        insertSelectInput('ProductoPrimario_example',respuesta['data']);



        return respuesta;

    })



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





const insertProducto=async (dataForm,dataItems)=>{



    let accion = {"Accion" : "productos","Tabla":"productos",'Data':dataForm,'Items':dataItems};



    return await fetch(rutaApi,{



        method: 'POST',



        body: JSON.stringify(accion),



        headers: {'Content-Type': 'application/json'}

    

    }).then(respuesta=>respuesta.json())

    

    .then(respuesta =>{

        

        if(respuesta['success']){



            document.getElementById('ProductosEvidenciaIdAlta').value=respuesta['data']['Id'];



            showAlert("Correcto",respuesta['messenge'],"success")     

        let comentario = "Agrego el Prdocuto: "+respuesta['data']['Nombre'];

        setBitacora('1', comentario, modulo);



            dropZonaEvidenciasAlta.processQueue();





            reloadTable('tablaProductos','modalAlta')



        }else{



            showAlert("Alerta",respuesta['messenge'],"info")

        

        }



        return respuesta['success'];

    })



}







const deleteProducto=async (id)=>{



    return( await fetch(rutaApi+'?Accion=productos&Tabla=productos&Id='+id, {



        method: 'DELETE',



        headers: {'Content-Type': 'application/json'}

    

    }).then(respuesta=>respuesta.json())

    

    .then(respuesta =>{

        

        if(respuesta['success']){



            showAlert("Correcto",respuesta['messenge'],"success")     

  

        }else{



            showAlert("Alerta",respuesta['messenge'],"info")

        

        }



        return respuesta['success'];



    })



    )



}



// -------------------------------------------------------------------------------------------------Validaciones







const respValidar=(clase)=>{



    



    let resultadoValidar=validar(clase);







    if(resultadoValidar){



    



        return validarCaracteres(clase);



    



    }else{



    



        return false;



    



    }







}





// -------------------------------------------------------------------------------------------------Items



const modificarInfoItemAlta=(containerBox,number)=>{



    containerBox=cambiarInfoInput(containerBox,'_alta'+number);



    containerBox=cambiarInfoItems(containerBox,'Alta');

    

    return cambiarInfoSelect(containerBox,'_alta'+number);



}





const cambiarInfoItems=(container,key)=>{



    // container.querySelector('.containerBtnsDefault').classList.replace('containerBtnsDefault','containerBtns'+key)



    container.querySelector('.Porcentaje_exampleClass').classList.replace('formDataExample',`form${key}DataItems`);

    container.querySelector('.Porcentaje_exampleClass').classList.replace('validarDataExample',`validar${key}DataItems`);





    container.querySelector('.ProductoPrimario_exampleClass').classList.replace('formDataExample',`form${key}DataItems`);

    container.querySelector('.ProductoPrimario_exampleClass').classList.replace('validarDataExample',`validar${key}DataItems`);



    return container;

}





const cambiarInfoInput=(container,key)=>{





    container.querySelector('#Porcentaje_example').id='Porcentaje'+key;

    

    container.querySelector('#ul_Porcentaje_example').id='ul_Porcentaje'+key;

    container.querySelector('#ProductoPrimario_example2').id='ProductoPrimario2'+key;

    


    return container;



}





const cambiarInfoSelect=(container,key)=>{



    container.querySelector('#ProductoPrimario_example').id='ProductoPrimario'+key;

    

    container.querySelector('#ul_ProductoPrimario_example').id='ul_ProductoPrimario'+key;

    



    return container;



}





var numberContadorAlta=0;



const crearBoxAlta=async()=>{



    numberContadorAlta++;





    // Container de Rutas que se pueden Eliminiar 

    let boxSecundarios = document.getElementById('itemsSecundarios_alta');



    // Item Principal 

    let boxPrincipal = document.getElementById('itemsPrincipal_alta');

    let countContainerPrincipal=Number(boxPrincipal.childElementCount);



    

    let boxCloneNode = document.querySelector(".boxItemDefaul").cloneNode(true)

    boxCloneNode.classList.replace('boxItemDefaul','boxItem');

    boxCloneNode.id='boxItemAlta'+numberContadorAlta;





    if(countContainerPrincipal==0){



        boxCloneNode.querySelector('.containerBtnsDefault').innerHTML=`<div id='Num_${numberContadorAlta}' class="d-inline btn btn-success rounded-10 btn-sm btnModalAgregar bx bx-plus" style='font-size: 17px; color:#ffffff;' type="button" title="Agregar"></div>`

        

    }else{



        boxCloneNode.querySelector('.containerBtnsDefault').innerHTML=`<div id='Num_${numberContadorAlta}' class="d-inline btn btn-danger rounded-10 btn-sm boxEliminarItem bx bx-x" style='font-size: 17px; color:#ffffff;' type="button" title="Borrar"></div>`



    }

    // -------------------------------------- 





    container=await modificarInfoItemAlta(boxCloneNode,numberContadorAlta)

    

    if(countContainerPrincipal==0){



        boxPrincipal.appendChild(container);



    }else{



        boxSecundarios.appendChild(container);



    }







    $("#ProductoPrimario_alta"+numberContadorAlta).chosen({

        width: "100%",

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });

    







    return numberContadorAlta;

}





var numberContadorEdit=0;



const crearBoxEdit=async()=>{



    numberContadorEdit++;





    // Container de Rutas que se pueden Eliminiar 

    let boxSecundarios = document.getElementById('itemsSecundarios_edit');



    // Item Principal 

    let boxPrincipal = document.getElementById('itemsPrincipal_edit');

    let countContainerPrincipal=Number(boxPrincipal.childElementCount);



    

    let boxCloneNode = document.querySelector(".boxItemDefaul").cloneNode(true)

    boxCloneNode.classList.replace('boxItemDefaul','boxItem');

    boxCloneNode.id='boxItemEdit'+numberContadorEdit;





    // if(countContainerPrincipal==0){



        // boxCloneNode.querySelector('.containerBtnsDefault').innerHTML=`<div id='Num_${numberContadorEdit}' class="d-inline btn btn-success rounded-10 btn-sm btnModalAgregar bx bx-plus" style='font-size: 17px; color:#ffffff;' type="button" title="Agregar"></div>`

        

    // }else{



        // boxCloneNode.querySelector('.containerBtnsDefault').innerHTML=`<div id='Num_${numberContadorEdit}' class="d-inline btn btn-danger rounded-10 btn-sm boxEliminarItem bx bx-x" style='font-size: 17px; color:#ffffff;' type="button" title="Borrar"></div>`



    // }

    // -------------------------------------- 





    container=await modificarInfoItemEdit(boxCloneNode,numberContadorEdit)

    

    if(countContainerPrincipal==0){



        boxPrincipal.appendChild(container);



    }else{



        boxSecundarios.appendChild(container);



    }





    // $('#ProductoPrimario_alta'+numberContadorEdit).select2();



    // $('#ProductoPrimario_alta'+numberContadorEdit).select2({

    //     dropdownParent: $('#modalAlta')

    // });





    return numberContadorEdit;

}





const modificarInfoItemEdit=(containerBox,number)=>{



    containerBox=cambiarInfoInput(containerBox,'_edit'+number,'formEditData ');



    containerBox=cambiarInfoItems(containerBox,'Edit');

    

    return cambiarInfoSelect(containerBox,'_edit'+number);



}



const insertDataItem=async(key,itemData)=>{



    document.getElementById('Porcentaje_edit'+key).value=itemData['Porcentaje'];



    document.getElementById('Porcentaje_edit'+key).setAttribute('attrIdProducto',itemData['Id']);

        document.querySelector('#ProductoPrimario_edit'+key).style.display='none';
        document.querySelector('#ProductoPrimario2_edit'+key).style.display='flex';
    

    document.getElementById('ProductoPrimario2_edit'+key).value=itemData['Nombre'];

    



    // document.querySelector('#formItems_edit #Num_'+key).setAttribute('idProctoPrimario',itemData['Id']);

    

    return true;

}





// -------------------------------------------------------------------------------------------------





function statusPorcentaje(eve){



    let totalPorcentajeInputs=0;

    let textInfoPorcentaje;



    if(eve){



        let input=document.getElementById(eve)

        

        if(input.classList.contains('formAltaDataItems')){

            totalPorcentajeInputs=getStatusInputsPorcentaje('formAlta','_alta','ItemAlta');



            textInfoPorcentaje='infoPorcentajeItemsAlta';

        }else{



            totalPorcentajeInputs=getStatusInputsPorcentaje('formEdit','_edit','ItemEdit');

            textInfoPorcentaje='infoPorcentajeItemsEdit';



        }

    }





    let total=totalPorcentajeInputs.toString();





    if(textInfoPorcentaje){



        if(total==100){

            document.getElementById(textInfoPorcentaje).style.color='#28a745'

        }else{



            document.getElementById(textInfoPorcentaje).style.color='#f00'

    

        }

        document.getElementById('TotalPorcentaje').innerHTML= 'Total: ';

        document.getElementById(textInfoPorcentaje).innerHTML=total+'%'

    

    

    }



}





const getStatusInputsPorcentaje=(idForm,typeForm,separador)=>{



    let arrayDataInputsRutas=document.querySelectorAll(`#${idForm} .boxItem`);



    let totalPorcentajeInputs=0;





    arrayDataInputsRutas.forEach(boxItems=>{



        idBox=typeForm+separarString(boxItems.id,separador,1);

        

        valuePorcentaje=boxItems.querySelector('#Porcentaje'+idBox).value;



        if(!valuePorcentaje){

            

            valuePorcentaje=0;



        }



        totalPorcentajeInputs= parseInt(totalPorcentajeInputs)+parseInt(valuePorcentaje);



    })





    return totalPorcentajeInputs;

}







// ------------------------------------------------------ Edit Evidencias 









const getStatusEvidencias=async(id,countFile)=>{



    let accion=`?Accion=productos&Tabla=archivosproductos&Id=${id}&Cantidad=${countFile}`;



    await fetch(rutaApi+accion, {



        method:'GET',



        headers:{ 'Content-Type': 'application/json'}



    }).then(res => res.json())



    .then(async(respuesta)=>{





        if(!respuesta["success"]){



            showAlert("Alerta",respuesta["messenge"],"info")

    

       }else{



            dropZonaEvidencias.processQueue();



       }

       

        return respuesta['success'];



    })



}











const initModalEvidencias=async(element)=>{

    

    let id=getIdBtn(element);



    $('#modalEvidencias').modal('show');

    

    let btnEvidenciaModal=document.querySelector('.btnFormEvidencias');            

    btnEvidenciaModal.setAttribute("id",id);



    document.getElementById('inputIdEvidenciasEdit').value=id;



    dropZonaEvidencias.removeAllFiles(true);



    getEvidenciasProductos(id);

}







const getEvidenciasProductos=async(id)=>{



    let accion = '?Accion=productos&Tabla=archivosproductos&Id='+id;



    return await fetch(rutaApi+accion, {



        method:'GET',

    

        headers:{ 'Content-Type': 'application/json'}





    }).then(respuesta=>respuesta.json())



    .then(async(respuesta)=>{



        if(typeof respuesta['data']!='string'){



            document.getElementById('containerEvidenciasUserEdit').innerHTML='';



            for(evidencia of respuesta['data']){



                await insertDataEvindencias(id,evidencia);



            }



        }else{

            

            document.getElementById('containerEvidenciasUserEdit').innerHTML=`<p class="text-center" style="width:100%;">No hay evidencias.</p>`;



        }

    })





}


const insertDataEvindencias = async (id, evidencia) => {

    let nameFile = evidencia['NombreArchivo'];

    let viewFile = evidencia['NombreArchivo'];

    let rutaArchivo = "../../Data/EvidenciasProductos/";

    let rutaVista = "../../Data/EvidenciasProductos/";

    if (evidencia['Extension'] == 'pdf') {
        nameFile = 'imgPdf.png';
        rutaVista = "../../Data/ImgGenerales/";
    }
    if (evidencia['Extension'] == 'xlsx') {

        nameFile = 'imgExcel.png';
        rutaVista = "../../Data/ImgGenerales/";
    }

    console.log(evidencia);

    let urlCopy = evidencia['url'] + `Data/EvidenciasProductos/${evidencia['NombreArchivo']}`;

    document.getElementById('containerEvidenciasUserEdit').innerHTML += `
<div class="col-md-55 containerItemsEvidencias" style="position:relative" >
<div class="thumbnail">

    <div class="image view view-first containerInfoItemsEvidencias" style="border-radius: 30px;">
    
            <div class="containerImgEvidencias" style='position: relative;'>

            <div class='file_download' 
                att_link="${urlCopy}"
                att_name="${evidencia['NombreArchivo']}"
            >Descargar</div>

            <img src="${rutaVista+nameFile}" alt="image" style="width: 100%;height: 100%;display:block;
            position: absolute;">

            </div>
            <div class="mask mousePointer" attr='campoEliminar'>
            </div>
    
    </div>
    
    <div class="caption containerTextEvidencias">
        <a href="${rutaArchivo+viewFile}" target="_blank" class="text-center" style='color: rgb(81, 81, 81);'>${evidencia['NombreArchivo']}</a> 
        <a class='btnCopyArchivo' type='button' value='${urlCopy}'>Copiar link<span class='bx bxs-copy' ></span></a>
        
    </div>

</div>
</div>`;

    // <p style='cursor:pointer;' attr_id_BillTo='${id}' class='eliminarEvidenciasEdit' id='${evidencia['Id']}'>Eliminar</p>


}




// const insertDataEvindencias=async(id,evidencia)=>{



//     let nameFile=evidencia['NombreArchivo'];



//     let viewFile=evidencia['NombreArchivo'];

    

//     let rutaArchivo="../../Data/EvidenciasProductos/";



//     let rutaVista="../../Data/EvidenciasProductos/";



//     if(evidencia['Extension']=='pdf'){

//         nameFile='imgPdf.png';

//         rutaVista="../../Data/ImgGenerales/";

//     }

//     if(evidencia['Extension']=='xlsx'){

    

//         nameFile='imgExcel.png';

//         rutaVista="../../Data/ImgGenerales/";

//     }



//     let urlCopy=evidencia['url']+`Data/EvidenciasProductos/${evidencia['NombreArchivo']}`;



//     document.getElementById('containerEvidenciasUserEdit').innerHTML+= `

//     <div class="col-md-55 containerItemsEvidencias" style="position:relative" >

//         <div class="thumbnail">



//             <div class="image view view-first containerInfoItemsEvidencias" style="border-radius: 30px;">

            

//                     <div class="containerImgEvidencias">



//                     <img src="${rutaVista+nameFile}" alt="image" style="width: 100%;height: 100%; " display: "block;">



//                     </div>

//                     <div class="mask mousePointer" attr='campoEliminar'>

//                     </div>

            

//             </div>

            

//             <div class="caption containerTextEvidencias">

//                 <a href="${rutaArchivo+viewFile}" target="_blank" class="text-center" style='color: rgb(81, 81, 81);'>${evidencia['NombreArchivo']}</a> 

//                 <a class='btnCopyArchivo' type='button' value='${urlCopy}'>Copiar link<span class='bx bxs-copy' ></span></a>

                

//             </div>



//         </div>

//     </div>`;



//     // <p style='cursor:pointer;' attr_id_BillTo='${id}' class='eliminarEvidenciasEdit' id='${evidencia['Id']}'>Eliminar</p>



    

// }



// -----------------------------------------------------Modal Evidencias



$('#tablaProductos tbody').on('click','.btnEvidenciasTabla',function (e){



    initModalEvidencias($(this));



});





var dropZonaEvidencias;



function subirArchivosEdit() {



    $("#dropzone-archivos").dropzone({

    paramName: "file",

    autoProcessQueue: false,

    maxFiles: 10,

    uploadMultiple: true,

    acceptedFiles: "image/*,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", //pdf, jpeg,jpg,png,

    addRemoveLinks: true,

    maxFilesize: 10,

    parallelUploads: 20,

    url: rutaApi,



    removedfile: function(file) {

        var fileName = file.name; 

    

        var _ref;



        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;

    

    },



    accept: function (file, done) {



        done();



    },



    init: function () {



        let myDropzone = this;

        dropZonaEvidencias=this;

        // Cuando le den click envie la data 

        document.querySelector(".btnFormEvidencias").addEventListener("click", async(e) => {



            e.preventDefault();



            // myDropzone.processQueue();

            let arrayItemFiles=document.querySelectorAll('#dropzone-archivos .dz-preview')

        

            countItemsFile=arrayItemFiles.length;

    

            let id=document.getElementById('inputIdEvidenciasEdit').value;





            await getStatusEvidencias(id,countItemsFile)



            e.stopPropagation();



        });



        // -------------------------- 



        // Si se completa el envio se ve su status 

        this.on("successmultiple", function (file, response) {



        // let rsp = JSON.parse(response);

        let rsp = response;



        if (rsp["success"]) {



            Dropzone.forElement('#dropzone-archivos').removeAllFiles(true)



            showAlert("Correcto", rsp["messenge"], "success");

            

            $('#modalEvidencias').modal('hide');





        } else {



            showAlert("Error", rsp["messenge"], "danger");



        }



        });







    },



    });



}



var dropZonaEvidenciasAlta;



function subirArchivosAlta() {



    Dropzone.autoDiscover = false;

    

    $("#dropzone-archivos-alta").dropzone({

        paramName: "file",

        autoProcessQueue: false,

        maxFiles: 10,

        uploadMultiple: true,

        acceptedFiles: "image/*,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", //pdf, jpeg,jpg,png,

        addRemoveLinks: true,

        maxFilesize: 10,

        parallelUploads: 20,

        //   params: {'data1':'id','data2':'namePrueba'},



        url: rutaApi,



        removedfile: function(file) {

            var fileName = file.name;



            var _ref;



            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;



        },



        accept: function(file, done) {



            done();



        },



        init: function() {



            let myDropzone = this;

            dropZonaEvidenciasAlta = this;



            // Si se completa el envio se ve su status 

            this.on("successmultiple", function(file, response) {



                let rsp = response;



                if (rsp["success"]) {



                    Dropzone.forElement('#dropzone-archivos-alta').removeAllFiles(true)



                    showAlert("Correcto", rsp["messenge"], "success");



                } else {



                    showAlert("Error", rsp["messenge"], "danger");



                }



            });







        },



    });





    // ----------------------- 



}





$('#containerEvidenciasUserEdit').on('click','.btnCopyArchivo',function(){    



        let text=this.getAttribute('value')

     

        navigator.clipboard.writeText(text)

        .then(() => {



            showAlert("Correcto",'Link copiado en portapapeles',"success")   



        })

        .catch(err => {

            showAlert("Error",'Texto no copiado',"info")



        });



})







let dataExcel={

    idBtnExcel:'btnExcelProducto',

    nameFile:'Productos',

    urlApi:rutaApi,

    accion:`?Accion=productos&getDataExcel=1&Tabla=productos`,

    urlVendor:'../../requerimientos/vendors/spreadsheet/spreadsheetExcel.php'

}



let excelTabla1 = new exportarExcelTabla(dataExcel);

const downLoadFileEvidencias = (uri, name) => {

    const link = document.createElement("a");
    link.download = name;

    link.href = uri;

    document.body.appendChild(link);

    link.click();

    document.body.removeChild(link);
}

$('#containerEvidenciasUserEdit').on('click', '.file_download', function() {

    let nameFile = $(this).attr('att_name');
    // let linkSitio=window.location.href;
    // linkSitio=linkSitio.split('view');
    // linkSitio=linkSitio[0]+"Data/EvidenciasProductos/"+nameFile;

    // console.log(linkSitio);
    linkSitio = "../../Data/EvidenciasProductos/" + nameFile;

    downLoadFileEvidencias(linkSitio, nameFile);

})