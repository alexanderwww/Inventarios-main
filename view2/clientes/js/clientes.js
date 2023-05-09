$(document).ready(function() { //Informacion al cargar la pagina



    $('#titlePage').text('Clientes');



    msgAlert="";



    txtAlert="";



    tpAlert="";



    // ocultarNAV();



    tablaPrincipal();


    initSelects();



})

const initSelects=async()=>{

        // *Nombre Tabla , *Valores que requiero, *Id del select donde se insertar los valores 

        await getInputSelect('paises','Id_Pais,Pais','paisSelectAlta');   



        await getInputSelect('paises','Id_Pais,Pais','PaisCliente');   
    
    
    
        await getInputSelect('cuentasUSUARIOS','User,Id,Status,Rol','usuarioSelectAlta');   
    
    
    
    $(".selectChosenAlta").chosen({
        width: "100%",
        no_results_text: "No se a encontrado resultados",
        allow_single_deselect: true,
    });

    
    $(".selectChosenEdit").chosen({
        width: "100%",
        no_results_text: "No se a encontrado resultados",
        allow_single_deselect: true,
    });

    
}

const resetTablas=()=>{
        // Sin monedas a cambiar 
    // tablaPrincipal();
    // "destroy": true,

}
  

const modulo=3;

function tablaPrincipal(){



    var accion = {"Accion" : "getClientes"}

    var tablaSSP =$('#tablaCliente').DataTable({

  

    'ajax':{

  

      'url':'Controlador/clientesApi.php',

  

      'type': 'POST',

  

      'data':accion,

      

      'dataSrc': 'Data',

  

    },

  

    'columns': [

        { 'data': 'acciones'},


        { 'data': 'Id' },



        { 'data': 'Status' },



  

        { 'data': 'Nombre' },

  

        { 'data': 'RazonSocial' },

  

        { 'data': 'RFC' },



        { 'data': 'RegimenFiscal' },



        // { 'data': 'Ejecutiva' }, //Nombre Ejecutiva



        { 'data': 'Telefono'},



        { 'data': 'Ext'},



        { 'data': 'CalleCliente'},



        { 'data': 'CPCliente'},



        { 'data': 'dias_credito'},




  

    ],





  

    'language': {

  

    'url': '../../requerimientos/vendors/DataTables-1.10.24/language/Spanish_Mexico.json'

  

    },

  

    "scrollY": "500px",

  

    "sScrollX": "100%",

  

    "sScrollXInner": "100%",

  

    "scrollCollapse": true,

  

    "paging": false,

    "destroy": true,




  



  })



  

}



// -------------------------------------------- Toogle switch



var checkboxStatus = document.querySelector('.js-switch-status');

var initcheckboxStatus = new Switchery(checkboxStatus,{ color: '#0a0f47de', jackColor: '#ffffff' });



// -------------------------------------------- Excel



// let btnExcel=document.getElementById('btnExcelCliente');



// btnExcel.addEventListener('click',()=>{



//     $('#modalExcelInfo').modal('show');



//     btnExcel.setAttribute('disabled',true);



//     setTimeout(()=>{btnExcel.removeAttribute('disabled')}, 3000);



// });



// let btnExcelEntendido=document.getElementById('btnExcelEntendido');



// btnExcelEntendido.addEventListener('click',()=>{

    

//     let usuario=btnExcel.getAttribute('name');



//     getDataExcel(usuario);

// })





const downLoadExcel = (dataExcel,nameFile,usuario) => {



        let url= "../../vendors/spreadsheetVendor/spreadsheetExcel.php";

        

        var form = document.createElement("form");

        form.setAttribute("method", "post");

        form.setAttribute("action", url);

        

        var inputDataExcel = document.createElement("input");

        var inputNameFile = document.createElement("input");

        var inputCreatoFile = document.createElement("input");



        

        inputDataExcel.type = "hidden";

        inputDataExcel.name = "datosExcel";

        inputDataExcel.value = dataExcel;



        inputNameFile.type = "hidden";

        inputNameFile.name = "nameFile";

        inputNameFile.value = nameFile;



        inputCreatoFile.type = "hidden";

        inputCreatoFile.name = "nameCreator";

        inputCreatoFile.value = usuario;

        



        form.appendChild(inputDataExcel);

        form.appendChild(inputNameFile);

        form.appendChild(inputCreatoFile);





        document.body.appendChild(form);

        form.submit();

        

        document.body.removeChild(form);

};





async function getDataExcel(usuario){



    

    var accion = {'Accion': 'getDataExcel'};



    url = "Controlador/clientesApi.php";



    return await fetch(url, {



        method:'POST',



        body: JSON.stringify(accion),



        headers:{ 'Content-Type': 'application/json'}



    }).then(res => res.json())

    .then(res=>{



        if(res['success']){

            let excel=JSON.stringify(res['Data']);

            downLoadExcel(excel,'Clientes',usuario);

        }



    })



}





// -------------------------------------------------------------------------------------------------Llamadas Fetch



async function getInputSelect(nameTabla,datos,idInputSelect,idEstadoOCiudad=null,idUpdate=null){





    let accion = {'Accion': 'getSelect','data':{

        nameTabla:nameTabla,

        datos: datos

    }}



    let url = "Controlador/clientesApi.php";



    return await fetch(url,{

        method:'POST',



        body: JSON.stringify(accion),



        headers:{ 'Content-Type': 'application/json'}

    }).then(res => res.json())

    .then(data =>{

        insertarValuesSelect(idInputSelect,data['Data'],idEstadoOCiudad,idUpdate);

    })



}


async function insertarNuevoCliente(arrayInputs,arrayNames){

    

    let comentario = 'Creo al Cliente= '+arrayInputs['nombreComercialAlta'];


    let accion = {'Accion': 'altaCliente','data':{



        'Nombre': arrayInputs['nombreComercialAlta'],

        'ContactoPrincipal': arrayInputs['contactoPrincipalAlta'],

        'Telefono': arrayInputs['numeroTelefonoAlta'],

        'Ext': arrayInputs['extensionAlta'],

       'CorreoElectronico': arrayInputs['correoElectronicoAlta'],

        'RazonSocial': arrayInputs['razonSocialAlta'],

        'RFC': arrayInputs['rfcAlta'],



        'RegimenFiscal': arrayInputs['RegimenFiscalAlta'],



        // 'Ejecutiva': arrayInputs['usuarioSelectAlta'],







        'CalleCliente': arrayInputs['calleYNumeroAlta'],

        'ColoniaCliente': arrayInputs['coloniaAlta'],



        'CPCliente': arrayInputs['codigoPostalAlta'],



        'dias_credito': arrayInputs['diasCreditoAlta'],

        'Id_Pais_CltFk': arrayInputs['paisSelectAlta'],

        'Id_Estado_CltFk': arrayInputs['estadoSelectAlta'],

        'Id_Ciudad_CltFk': arrayInputs['ciudadSelectAlta'],



        'CiudadCliente': arrayNames['ciudadSelectAlta'],

        'EstadoCliente': arrayNames['estadoSelectAlta'],

        'PaisCliente': arrayNames['paisSelectAlta'],

    }}



    let url = "Controlador/clientesApi.php";



    return await fetch(url,{

        method:'POST',



        body: JSON.stringify(accion),



        headers:{ 'Content-Type': 'application/json'}

    }).then(res => res.json())

    .then(data=>{

        switch(data["success"]){



            case true:



             showAlert("Correcto",data["message"],"success")

             $('#modalAltaCliente').modal('hide');

             setBitacora('1', comentario, modulo);


            break;



            case false:



             showAlert("Alerta",data["message"],"info")



            break;



            default:



            break;

        }

    })

    .then(data =>{

        var tablaSSP = $('#tablaCliente').DataTable();

        tablaSSP.ajax.reload();



    }) 



}



async function updateCliente(arrayInputs,arrayNames,arrayInputCheckbox,idEditar){


    let comentario = 'Edito al Cliente con la Id= '+idEditar;



    let accion = {'Accion': 'updateCliente','Id':idEditar,'data':{



        'Nombre': arrayInputs['NombreCom'],

        'ContactoPrincipal': arrayInputs['ContactoPrincipal'],

        'Telefono': arrayInputs['Telefono'],

        'Ext': arrayInputs['Ext'],

       'CorreoElectronico': arrayInputs['CorreoElectronico'],

        'RazonSocial': arrayInputs['RazonSocial'],

        'RFC': arrayInputs['RFC'],

        'CalleCliente': arrayInputs['CalleCliente'],

        'ColoniaCliente': arrayInputs['ColoniaCliente'],

        

        'Status': arrayInputCheckbox['Status'],

        

        'RegimenFiscal': arrayInputs['RegimenFiscal'],

        // 'Ejecutiva': arrayInputs['Ejecutiva'],





        'CPCliente': arrayInputs['CPCliente'],



        'dias_credito': arrayInputs['dias_credito'],

        'Id_Pais_CltFk': arrayInputs['PaisCliente'],

        'Id_Estado_CltFk': arrayInputs['EstadoCliente'],

        'Id_Ciudad_CltFk': arrayInputs['CiudadCliente'],



        'CiudadCliente': arrayNames['CiudadCliente'],

        'EstadoCliente': arrayNames['EstadoCliente'],

        'PaisCliente': arrayNames['PaisCliente'],

    }}



    let url = "Controlador/clientesApi.php";



    return await fetch(url,{

        method:'POST',



        body: JSON.stringify(accion),



        headers:{ 'Content-Type': 'application/json'}

    }).then(res => res.json())

    .then(data=>{

        switch(data["success"]){



            case true:



             showAlert("Correcto",data["message"],"success")

             $('#modalEditar').modal('hide');
             setBitacora('4', comentario, modulo);




            break;



            case false:



             showAlert("Alerta",'Sin Cambios')

            //  $('#modalEditar').modal('hide');



            break;



            default:



            break;

        }

    })

    .then(data =>{

        var tablaSSP = $('#tablaCliente').DataTable();

        tablaSSP.ajax.reload();



    }) 



}



async function getDataCliente(idUserEdit){



    

    var accion = {'Accion': 'getDataCliente', 'Id': idUserEdit}



    url = "Controlador/clientesApi.php";



    return await fetch(url, {



        method:'POST',



        body: JSON.stringify(accion),



        headers:{ 'Content-Type': 'application/json'}



    }).then(res => res.json())

    .then(res=>{

        insertarDataInput(res);

    })



}



async function cambiarStatusCliente(id,status){


    let comentario = "Cambio el estatus del cliente con la ID: "+id;


    var accion = {'Accion': 'cambiarStatusCliente','Id':id,'data':{'Status':status}}



    url = "Controlador/clientesApi.php";



    return await fetch(url, {



        method:'POST',



        body: JSON.stringify(accion),



        headers:{ 'Content-Type': 'application/json'}



    }).then(res=> res.json())



    .then(data => {

        switch(data["success"]){



            case true:



            showAlert("Correcto",data["message"],"success");

            setBitacora('2', comentario, modulo);


            break;



            case false:



            this.showAlert("Sin cambios",data["message"],"info");



            break;



        }

    })

    .then(data =>{

        var tablaSSP = $('#tablaCliente').DataTable();



        tablaSSP.ajax.reload();







    })



    // }) 





}



// -------------------------------------------------------------------------------------------------Input Select





// Al darle click Obtiene el ID del pais y hace una consulta con los datos que le de

let idInputSelectPais=document.querySelector('#paisSelectAlta');

// idInputSelectPais.addEventListener("change", async function() {
$('#paisSelectAlta').on('change',async function(){

        var idPais=idInputSelectPais.value;

        await getInputSelect('estados','Id_Estado,Id_Pais_Fk,Estado','estadoSelectAlta',idPais)

        await getInputSelect('ciudades','Id_Estado_Fk,Ciudad,Ver,Id_Ciudad','ciudadSelectAlta','sinDatos')

        $(".selectChosenAlta").chosen("destroy");
        $(".selectChosenAlta").chosen({
            width: "100%",
            no_results_text: "No se a encontrado resultados",
            allow_single_deselect: true,
        });
});





let idInputSelectCiudad=document.querySelector('#estadoSelectAlta');

// idInputSelectCiudad.addEventListener("change", function() {
$('#estadoSelectAlta').on('change',async function(){


        var idCiudad=idInputSelectCiudad.value;

        await getInputSelect('ciudades','Id_Estado_Fk,Ciudad,Ver,Id_Ciudad','ciudadSelectAlta',idCiudad)

        $(".selectChosenAlta").chosen("destroy");
        $(".selectChosenAlta").chosen({
            width: "100%",
            no_results_text: "No se a encontrado resultados",
            allow_single_deselect: true,
        });
});



// ---Edit 



let idInputSelectPaisEdit=document.querySelector('#PaisCliente');

// idInputSelectPaisEdit.addEventListener("change", function() {
$('#PaisCliente').on('change',async function(){



        var idPais=idInputSelectPaisEdit.value;

        await getInputSelect('estados','Id_Estado,Id_Pais_Fk,Estado','EstadoCliente',idPais)

        await getInputSelect('ciudades','Id_Estado_Fk,Ciudad,Ver,Id_Ciudad','CiudadCliente','sinDatos');

        $(".selectChosenEdit").chosen("destroy");
        $(".selectChosenEdit").chosen({
            width: "100%",
            no_results_text: "No se a encontrado resultados",
            allow_single_deselect: true,
        });

});





let idInputSelectCiudadEdit=document.querySelector('#EstadoCliente');

// idInputSelectCiudadEdit.addEventListener("change", function() {
$('#EstadoCliente').on('change',async function(){


        var idCiudad=idInputSelectCiudadEdit.value;

        await getInputSelect('ciudades','Id_Estado_Fk,Ciudad,Ver,Id_Ciudad','CiudadCliente',idCiudad)

        $(".selectChosenEdit").chosen("destroy");
        $(".selectChosenEdit").chosen({
            width: "100%",
            no_results_text: "No se a encontrado resultados",
            allow_single_deselect: true,
        });

});





// -------------------------------------------------------------------------------------------------Btn





let btnNuevoCliente=document.querySelector('#btnAltaCliente');

btnNuevoCliente.addEventListener('click',()=>{

        $('.selectChosenAlta').val('').trigger('chosen:updated');

        resetearWizard('smartwizard')

        document.getElementById("formAltaCliente").reset();



        $("#formAltaCliente .form_text_adv").css({'display':'none'})

        $("#formAltaCliente .campoValidarAlta_Sectio1").css({ 'border-color': '#ced4da',"border-weight": "0" });



        $('#btnModalConfirmarAlta').attr('disabled',true);

        $("#btnModalConfirmarAlta").css("opacity", ".5");

    





})






$(document).on('click', '.btnTablaEditar', function (e) {
// $('#tablaCliente tbody').on('click','.btnTablaEditar',function (e){

    // $('.selectChosenEdit').val('').trigger('chosen:updated');

    let idString = $(this).attr('id');

    let id=idString.substring(2);

    let idUser=Number(id);





    resetearWizard('smartwizard2')



    $('#btnModalConfirmarEditar').attr('name',idUser);



    $("#modalEditar .form_text_adv").css({'display':'none'})

    $("#modalEditar .campoValidarEditar_Sectio1").css({ 'border-color': '#ced4da',"border-weight": "0" });



    let inputCheckboxStatus=document.getElementById('Status');

    if(inputCheckboxStatus.checked){

        inputCheckboxStatus.click();

    }





    getDataCliente(idUser)



});





// -------------------------------- Btn Modal Alta 



let btnModalConfirmarAlta=document.getElementById('btnModalConfirmarAlta');

let btnModalCancelarAlta=document.getElementById('btnModalCancelarAlta');



btnModalConfirmarAlta.addEventListener('click',()=>{

    stringClass('formAltaDataInput');



    validarAllSections('formAltaDataInput','campoValidarAlta','modalAltaCliente','campoValidarCaracteresAlta');

})



btnModalCancelarAlta.addEventListener('click',()=>{

    $('#modalAltaCliente').modal('hide')

})





// -------------------------------- Btn Modal Editar 





let btnModalConfirmarEditar=document.getElementById('btnModalConfirmarEditar');

let btnModalCancelarEditar=document.getElementById('btnModalCancelarEditar');



btnModalConfirmarEditar.addEventListener('click',()=>{

    stringClass('formEditarDataInput');



    // Formaulario a validar y clases de las secciones a validar 

    validarAllSections('formEditarDataInput','campoValidarEditar','modalEditar','campoValidarCaracteresEditar');

})



btnModalCancelarEditar.addEventListener('click',()=>{

    $('#modalEditar').modal('hide')

})



// -------------------------------------------------------------------------------------------------Funciones



function resetearWizard(idWizar){ $('#'+idWizar).smartWizard("reset"); }





function validarAllSections(Claseform,claseSection,idForm,classCaracteres){

    

    let respSection1=respValidar(claseSection+'_Sectio1');



    let respSection2=respValidar(claseSection+'_Sectio2');

    



    $('#'+idForm+' .'+classCaracteres).css({ 'border-color': '#ced4da',"border-weight": "0" });



    $('#'+idForm+' .formNoReq_text_adv').css({'display':'none'});



    let camposNoObligatorios=validarCaracteres(classCaracteres);



    let camposCorreosNoObligatorias=validarCorreosNoObligatorios(idForm);



    

    document.getElementById("ziseContentForm").style.height = "auto";

    document.getElementById("ziseContentForm2").style.height = "auto";





    if(respSection1 && respSection2 && camposNoObligatorios && camposCorreosNoObligatorias){

        // ('todas las section son correctas');



        // Clase que contiene la info de los input a enviar a la base de Datos 

        getDataForm(Claseform)

    }

}



function getDataForm(claseFormInput){

    let arrayAllInputs=document.querySelectorAll('.'+claseFormInput);



    // Inpus normales 

    let arrayInputsValues=[];

    // selects 

    let arrayInputsName=[];

    // checkboxs 

    let arrayInputCheckbox=[];





    // Obtiene los valores de los inputs 

    arrayAllInputs.forEach(input=>{

        arrayInputsValues[input.id]=input.value;

    })



    // Obtiene el nombre del Pais, Estado y Ciudad 

    arrayAllInputs.forEach(input=>{



        if($('#'+input.id+' option:selected').attr('name')!=undefined){

            var nameSelect = $('#'+input.id+' option:selected').attr('name');

            arrayInputsName[input.id]=nameSelect;

        }



    })



    // Obtiene el valor de los checkbox 

    let valueStatus=null;

    arrayAllInputs.forEach(checkbox=>{



        if(checkbox.checked){

            valueStatus='1';

        }else{

            valueStatus='0';

        }



        arrayInputCheckbox[checkbox.id]=valueStatus;

    })







    if(claseFormInput=='formEditarDataInput'){

        // console.log('clase Editar')



        

        let idEditar=$('#btnModalConfirmarEditar').attr('name');

        idEditar=Number(idEditar);



        updateCliente(arrayInputsValues,arrayInputsName,arrayInputCheckbox,idEditar)

    }

    if(claseFormInput=='formAltaDataInput'){

        // console.log('clase ALTA')

        insertarNuevoCliente(arrayInputsValues,arrayInputsName);

    }



}





// Inserta los valores a los Select y tambien seleciona la opcion del cliente del select en Update

function insertarValuesSelect(id,data,idEstadoOCiudad,idUpdate='Seleccione uno...'){



    switch (id){



        

        case 'paisSelectAlta':

            var idSelect = document.getElementById(id);



            data.forEach(element => {

                

                var option = new Option(element.Pais,element.Id_Pais);

                option.setAttribute('name', element.Pais)



                idSelect.appendChild(option);

        

            });



            break;

        

        case 'estadoSelectAlta':

            var idSelect = document.getElementById(id);

            idSelect.innerHTML='';



            data.forEach(element => {

                

                if(idEstadoOCiudad==element.Id_Pais_Fk || element.Id_Pais_Fk=='vacio'){

                    var option = new Option(element.Estado,element.Id_Estado);

                    option.setAttribute('name', element.Estado)



                    idSelect.appendChild(option);

                }

        

            });



            break;

        case 'ciudadSelectAlta':

            var idSelect = document.getElementById(id);

            idSelect.innerHTML='';



            data.forEach(element => {

                

                if(idEstadoOCiudad==element.Id_Estado_Fk || element.Id_Estado_Fk=='vacio'){

                    var option = new Option(element.Ciudad,element.Id_Ciudad);

                    option.setAttribute('name', element.Ciudad)

                    idSelect.appendChild(option);

                }

        

            });



            break;



        // case 'usuarioSelectAlta':

        //         var idSelect = document.getElementById(id);

    

        //         data.forEach(element => {

                    

        //             var option = new Option(element.Usuario,element.Id);

        //             option.setAttribute('name', element.Usuario)

    

        //             idSelect.appendChild(option);

            

        //         });

    

        //         break;



        // ------------------------Select Update

        

        case 'PaisCliente':

                var idSelect = document.getElementById(id);

    

                data.forEach(element => {

                    

                    var option = new Option(element.Pais,element.Id_Pais);

                    option.setAttribute('name', element.Pais)

    

                    idSelect.appendChild(option);

            

                });

    

                break;

            

        case 'EstadoCliente':

                var idSelect = document.getElementById(id);

                idSelect.innerHTML='';

    

                data.forEach(element => {

                    

                    if(idEstadoOCiudad==element.Id_Pais_Fk || element.Id_Pais_Fk=='vacio'){

                        var option = new Option(element.Estado,element.Id_Estado);

                        option.setAttribute('name', element.Estado)

    

                        idSelect.appendChild(option);

                    }

            

                });



                $("#"+id+" option[value="+idUpdate+"]").attr("selected", true);
                // $('.selectChosenAlta').val('').trigger('chosen:updated');

    

                break;

        case 'CiudadCliente':

                var idSelect = document.getElementById(id);

                idSelect.innerHTML='';

    

                data.forEach(element => {

                    

                    if(idEstadoOCiudad==element.Id_Estado_Fk || element.Id_Estado_Fk=='vacio'){

                        var option = new Option(element.Ciudad,element.Id_Ciudad);

                        option.setAttribute('name', element.Ciudad)

                        idSelect.appendChild(option);

                    }

            

                });

    

                $("#"+id+" option[value="+idUpdate+"]").attr("selected", true);



                break;



    // ------ 

        // case 'Ejecutiva':

        //     var idSelect = document.getElementById(id);

        //     idSelect.innerHTML='';



        //     data.forEach(element => {

                

        //             var option = new Option(element.Usuario,element.Id);

        //             option.setAttribute('name', element.Usuario)

        //             idSelect.appendChild(option);

        

        //     });



        //     $("#"+id+" option[value="+idUpdate+"]").attr("selected", true);





        //     break;





    }





}





async function insertarDataInput(data){

    let arrayData=data.message;



    // console.log(arrayData.Ejecutiva);



    let inpuSeletPais=document.querySelector('#PaisCliente');

    inpuSeletPais.value=arrayData.Id_Pais_CltFk;



    let nombreComercialUpdate=arrayData.NombreCom;

    $('#nameModalEditar').text(nombreComercialUpdate)



    // (*nombreDeLaTableBDD,*DatosQueQuiero,*Id del InputSelect donde se van insertar los valores,'Opcional': idDelPais, IdDelEstado)

    await getInputSelect('estados','Id_Estado,Id_Pais_Fk,Estado','EstadoCliente',arrayData.Id_Pais_CltFk,arrayData.Id_Estado_CltFk)

    await getInputSelect('ciudades','Id_Estado_Fk,Ciudad,Ver,Id_Ciudad','CiudadCliente',arrayData.Id_Estado_CltFk,arrayData.Id_Ciudad_CltFk)

    $(".selectChosenEdit").chosen("destroy");
    $(".selectChosenEdit").chosen({
        width: "100%",
        no_results_text: "No se a encontrado resultados",
        allow_single_deselect: true,
    });


    // getInputSelect('cuentasUSUARIOS','User,Id,Status,Rol','Ejecutiva',arrayData.Ejecutiva,arrayData.Ejecutiva); 





    let arrayInputFormUdpate=document.querySelectorAll('.dataInputEditar');

    let arrayCheckboxUpdate=document.querySelectorAll('.dataCheckboxEditar');



    arrayInputFormUdpate.forEach(input=>{

                let valorDataCliente=arrayData[input.id];

                // le agregamos al input el valor del objetcto 

                input.value=valorDataCliente;

    })





    arrayCheckboxUpdate.forEach(checkbox=>{



        let valorDataCliente=arrayData[checkbox.id];

        

        if(valorDataCliente=='1'){

            // por default todos los checkbox vienen desativados 

            let inputCheckbox=document.getElementById(checkbox.id);

            inputCheckbox.click();

        }



    })







}





// -------------------------------------------------------------------------------------------------Validaciones



function respValidar(clase){

    

    let resultadoValidar=validar(clase);



    if(resultadoValidar){

    

        return validarCaracteres(clase);

    

    }else{

    

        return false;

    

    }



}





// -------------------------------------------------------------------------------------------------Wizard



// Id del container del wizard donde se le agrega sus configuraciones 

// Numero de wizard 

function nuevoWizar(idModalWizar,idContador,formWizard,campoValidar){



    $(function() {

        $('#'+idModalWizar).smartWizard({

            selected: 0,

            theme: 'square',

            transition: {

              animation:'fade' 

            },

            toolbar: {

              showNextButton: false, 

              showPreviousButton: false, 

              position: 'both',

            },

            // keyboard: {

            //     keyNavigation: true}

        });

        

        $("#"+idModalWizar).on("showStep", function() {

            let stepInfo = $('#'+idModalWizar).smartWizard("getStepInfo");

            $("#sw-current-step"+idContador).text(stepInfo.currentStep + 1);

        })



    })



    $("#prev-btn-modal"+idContador).on("click", function() {

        $('#'+idModalWizar).smartWizard("prev");

        return true;

    });



        // Validaciones de secciones 

    $("#next-btn-modal"+idContador).on("click", function() {



        // return true;

            validacionesFormulario(idModalWizar,idContador,formWizard,campoValidar)

        });



    // ---------------------------------------------------- 



 







}



// Id del wizard

// Numero de wizard

// Id del form 

// clase de los campos a validar 

nuevoWizar('smartwizard',1,'formAltaCliente','campoValidarAlta');



nuevoWizar('smartwizard2',2,'formEditar','campoValidarEditar');

// ------------------------------------------------------------------------------------------------ 





function validacionesFormulario(idModalWizar,idContador,formWizard,campoValidar){



    let numContent_Section=document.getElementById('sw-current-step'+idContador);

    let valueNum=numContent_Section.textContent;



    if(valueNum=='' || valueNum==1){

        let respValidar_Section1=respValidar(campoValidar+'_Sectio1');

        document.getElementById("ziseContentForm").style.height = "auto";

        document.getElementById("ziseContentForm2").style.height = "auto";





        if(respValidar_Section1){

            $('#'+idModalWizar).smartWizard("next");



            $("#"+formWizard+" .form_text_adv").css({'display':'none'})

            $("#"+formWizard+" ."+campoValidar+"_Sectio2").css({ 'border-color': '#ced4da',"border-weight": "0" });

        

            return true;

        }

    }



    if(valueNum==2){

        let respValidar_Section2=respValidar(campoValidar+'_Sectio2');

        document.getElementById("ziseContentForm").style.height = "auto";

        document.getElementById("ziseContentForm2").style.height = "auto";





        if(respValidar_Section2){

            $('#'+idModalWizar).smartWizard("next");

            $("#"+formWizard+" .form_text_adv").css({'display':'none'})

            $("#"+formWizard+" ."+campoValidar+"_Sectio3").css({ 'border-color': '#ced4da',"border-weight": "0" });



            $('.btnWizarconfirmarDisponible'+idContador).attr('disabled',false);

            $(".btnWizarconfirmarDisponible"+idContador).css("opacity", "1");



            return true;

        }

    }

    



}





function validarCorreosNoObligatorios(idForm){



    let arrayGmail=document.querySelectorAll('#'+idForm+' .validarCorreosNoObligatorios');

    let emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;

    

    let arrayRespuestaCorreos=[];



    arrayGmail.forEach(email=>{

        

        if(email.value===''){

            $("#" + email.id).css({ 'border-color': '#ced4da',"border-weight": "0" });

                

            $("#ul_"+email.id).css({'display':'none'})



            arrayRespuestaCorreos.push(true);





        }else{



            if (!emailRegex.test(email.value)) {

                

                $("#ul_" + email.id).css({'display': 'block' });

    

                $("#ul_" + email.id).text('Se requiere correo Valido');

    

                $("#" + email.id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });

                

                arrayRespuestaCorreos.push(false);

            }  

            else {



                $("#" + email.id).css({ 'border-color': '#ced4da',"border-weight": "0" });

                

                $("#ul_"+email.id).css({'display':'none'})



                arrayRespuestaCorreos.push(true);

            }



        }







    })





    return arrayRespuestaCorreos.every(resp=> resp==true);



}



let dataExcel={
    idBtnExcel:'btnExcelTabla',
    nameFile:'Clientes',
    urlApi:rutaApi,
    accion:`?Accion=clientes&getDataExcel=1&Tabla=clientes`,
    urlVendor:'../../requerimientos/vendors/spreadsheet/spreadsheetExcel.php'
}
  
  let excelTabla = new exportarExcelTabla(dataExcel);
  