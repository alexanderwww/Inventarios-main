$(document).ready(function() { //Informacion al cargar la pagina

    $('#titlePage').text('Usuarios');

    msgAlert="";

    txtAlert="";

    tpAlert="";

    // ocultarNAV();

    initModulo();

})

const resetTablas=()=>{
    
        // Sin monedas a cambiar 
    // tablaPrincipal();
    // "destroy": true,

}
  
const initModulo = async () => {

    tablaPrincipal();
    getInputSelect('roles', 'id,nombre', 'SelectRolAltaUser');
    getInputSelect('roles', 'id,nombre', 'SelectRolUserEdit');


    let responseRoles = await getDataRoles('roles', 'id,nombre');

    await insertSelectInput('SelectRolUserView', responseRoles['Data'], 'nombre', 'id');


    $("#SelectRolAltaUser").chosen({
        width: "100%",
        no_results_text: "No se a encontrado resultados",
        allow_single_deselect: true,
    });


    $("#SelectRolUserEdit").chosen({
        width: "100%",
        no_results_text: "No se a encontrado resultados",
        allow_single_deselect: true,
    });

}
// -------------------------------------------- Toogle switch
const modulo = 1;
var checkboxStatus = document.querySelector('.js-switch-status');

var initcheckboxStatus = new Switchery(checkboxStatus,{ color: '#0a0f47de', jackColor: '#ffffff' });

// var checkboxStatusView = document.querySelector('.js-switch-status-view');
// var initcheckboxStatusView = new Switchery(checkboxStatusView,{ color: '#d7572b', jackColor: '#ffffff' });



// -----------------------------------------------------Btn Nuevo Usuario

const btnNuevoUsuario=document.querySelector('#btnNuevoUsuario');

const btnFormAltaUser=document.querySelector('.btn_FormAltaUser');

btnNuevoUsuario.addEventListener('click',()=>{

    document.getElementById("formAltaUsurio").reset();

    // Libreria icheck
    $('#formAltaUsurio .icheckbox_flat-green').removeClass('checked');

    // Css
    $("#formAltaUsurio .form_text_adv").css({'display':'none'})

    $("#formAltaUsurio .campoValidarAltaUsuario").css({ 'border-color': '#ced4da',"border-weight": "0" });

})

btnFormAltaUser.addEventListener('click',()=>{

    stringClass('formDataAlta');

    let res=respValidar('campoValidarAltaUsuario')

    // Si todos lo datos son corretos
    if(res){
    
        let inputs = document.querySelectorAll('.campoValidarAltaUsuario');
    
        let checks = document.querySelectorAll('.input_checkAltaUsuario');

        let arrayData=[];

        let arrayCheck=[];
        
        inputs.forEach(e=>{
        
            arrayData[e.id]=e.value;
        
        })

        checks.forEach(e=>{
        
            arrayCheck[e.id]=e.checked;
        
        })


        if(arrayData['Foto']==''){
        
            altaUsuario(arrayData,arrayCheck);
            // altaUsuarioV2(arrayData,arrayCheck);
        
        }else{
        
            subirFoto(arrayData,arrayCheck,'altaUsuario')
        
        }


    }


})

// --------------------------Init modal view
$('#tablaUsuarios').on('click','.btnView', async function(){
    document.getElementById('formView').reset();

    $('.modalViewTitle').text(this.getAttribute('name'));
    
    $('#modalView').modal('show');


    let idString = $(this).attr('id');
    id=idString.substring(2);
    id=Number(id);

    let responseData=await getDataUsuarioView(id);


    insertDataModalView(responseData['message']);

})

const insertDataModalView=(arrayData)=>{
    console.log(arrayData);

    document.getElementById('UsuarioView').value=arrayData['User']
    document.getElementById('NombreView').value=arrayData['Name']
    document.getElementById('EmailView').value=arrayData['Email']
    document.getElementById('SelectRolUserView').value=arrayData['Rol']
    document.getElementById('estatusView').value=arrayData['Status']=='1'?'Activo':'Desactivado';
}


// --------------------------Btn Eliminar Usuario

// $('#tablaUsuarios tbody').on('click','.btnElimarTabla',function (e){
//     let idUser = $(this).attr('id');

//     let btnEliminarModal=document.querySelector('.btnModalEliminarUser');
//     btnEliminarModal.removeAttribute('id');

//     // console.log($(this).attr('name'));

//     let nameUserModalEliminar=$(this).attr('name');
//     let agregarName=$('#nameUserModalEliminar').text(nameUserModalEliminar);

//     btnEliminarModal.setAttribute("id",idUser);
// });

// $('body').on('click','.btnModalEliminarUser',function (e){
//     let idModal = $(this).attr('id');
//     // let idString=idModal.substring(2);
//     // let idUser=Number(idString);

//     eliminarUserStatus(idModal);


// });


// --------------------------Btn Restablecimiento Sesion

$('#tablaUsuarios tbody').on('click','.btnResetTabla',function (e){

    let idUser = $(this).attr('id');

    let btnEliminarModal=document.querySelector('.btnModalResetUser');
    
    btnEliminarModal.removeAttribute('id');

    let nameUserModalEliminar=$(this).attr('name');
    
    let agregarName=$('#nameUserModalDosFactores').text(nameUserModalEliminar);

    btnEliminarModal.setAttribute("id",idUser);

});


$('body').on('click','.btnModalResetUser',function (e){

    let idModal = $(this).attr('id');
    
    let id=idModal.substring(2);
    
    let idUser=Number(id);
    
    restablecimiento(idUser);

});


// --------------------------Btn Actualizar Usuario

$(document).on('click', '.btnEditarTabla', function (e) {
    $('#SelectRolUserEdit').val('').trigger('chosen:updated');

// $('#tablaUsuarios tbody').on('click','.btnEditarTabla',function (e){
    let idString = $(this).attr('id');
    let id=idString.substring(2);
    let idUser=Number(id);
    getDataUsuario(idUser,'formEditUsuario');
    // Por default todos los inputs van a estar desativados,
    //si el usuario esta activo lo marcar 
    let inputCheckboxStatus=document.getElementById('Status');
    if(inputCheckboxStatus.checked){
        inputCheckboxStatus.click();
    }
    $("#formEditUsuario .form_text_adv").css({'display':'none'})
    $("#formEditUsuario .campoValidar").css({ 'border-color': '#ced4da',"border-weight": "0" });
    let btnUpdateModal=document.querySelector('.btn_FormEditUser');
    btnUpdateModal.removeAttribute('id');
    btnUpdateModal.setAttribute("id",idString);
});

$('body').on('click','.btn_FormEditUser',function (e){
    
    let idModal = $(this).attr('id');

    let valuePass=$('#formEditUsuario .form_password').val();
    
    let name=$('#formEditUsuario .form_password').attr('name');

    // Si el campos es vacio quita la clase para que no se valide
    if(valuePass==''){
    
        $('#formEditUsuario .form_password').removeClass('campoValidar')
    
    }

    stringClass('formDataEdit');

    let res=respValidar('campoValidar')

    let passId = document.querySelector('#PasswordEdit').id;

    // Si todos lo datos son corretos
    if(res){
        
        let inputs = document.querySelectorAll('.campoValidar');
        
        let checks = document.querySelectorAll('.input_check');
        
        let checkStatus=document.getElementById('Status');

        let arrayData=[];

        let arrayCheck=[];
        
        arrayData[checkStatus.id]=Number(checkStatus.checked);

        inputs.forEach(e=>{
        
            arrayData[e.id]=e.value;
        
        })

        // Si no contiene la class validar es decir que nose modifico
        if($("#"+passId).hasClass("campoValidar")==false){
        
            arrayData[passId]=['sinCambio',name];
        
        }

        checks.forEach(e=>{
        
            arrayCheck[e.id]=e.checked;
        
        })


        idModal=idModal.substr(2);
        
        if(arrayData['FotoEdit']==''){


            updateUsuario(arrayData,arrayCheck,idModal);
        
        }else{
        
            subirFoto(arrayData,arrayCheck,'updateUsuario',idModal)
        
        }
    
    }

    // Lo dejo como estaba anteriormente
    if($("#"+passId).hasClass("campoValidar")==false){
    
        $('#formEditUsuario .form_password').addClass('campoValidar')
    
    }

});


// --------------------------Btn Asignar Unidades

$('#tablaUsuarios tbody').on('click','.btnAsignarUnidadeTabla',function (e){

    let idString = $(this).attr('id');
    
    let id=idString.substring(2);
    
    let idUser=Number(id);

    let btnAsignarUnidades=document.querySelector('.btnModalAsignarUnidadesUser');
    
    btnAsignarUnidades.removeAttribute('id');
    
    btnAsignarUnidades.setAttribute("id",idString);

    let nameUserModalEliminar=$(this).attr('name');
    
    let agregarName=$('#nameUserModalAsignarUnidades').text(nameUserModalEliminar);

    getUnidades(idUser);

});


$('body').on('click','.btnModalAsignarUnidadesUser',function (e){
    
    let idModal = $(this).attr('id');

    getCheckUnidades(idModal);

});


async function updateUsuario(arrayData,arrayCheck,id,nameFoto=''){
    let comentario = 'Edito al Usuario= '+arrayData['UsuarioEdit'];
    
    if(nameFoto==''){
        var accion = {'Accion': 'updateUsuario', 'Id':id,
        'data':{
            'User':arrayData['UsuarioEdit'],
            'Name': arrayData['NombreEdit'],
            'Email':arrayData['EmailEdit'],
            'Password':arrayData['PasswordEdit'],
            'Rol':arrayData['SelectRolUserEdit'],
            'Status':arrayData['Status'],
        }};
    }else{
        var accion = {'Accion': 'updateUsuario', 'Id':id,
        'data':{
            'User':arrayData['UsuarioEdit'],
            'Name': arrayData['NombreEdit'],
            'Email':arrayData['EmailEdit'],
            'Password':arrayData['PasswordEdit'],
            'Rol':arrayData['SelectRolUserEdit'],
            'ImgUser': nameFoto,
            'Status':arrayData['Status'],

        }};
    }

    url = "Controlador/usuariosApi.php";

    return await fetch(url, {

        method:'POST',

        body: JSON.stringify(accion),

        headers:{ 'Content-Type': 'application/json'}

    }).then(res=> res.json())

    .then(data => {
        switch(data["success"]){

            case true:

            showAlert("Correcto",data["message"],"success");

            $('#editUsuario').modal('hide');
           setBitacora('4',comentario,modulo);


            break;

            case false:

            showAlert("Sin cambios",data["message"],"info");

            break;

        }
    })
    .then(data =>{
        
        var tablaSSP = $('#tablaUsuarios').DataTable();
        
        tablaSSP.ajax.reload();

    })


}


async function altaUsuario(arrayData,arrayCheck,nameFoto='foto.png'){
    let comentario = 'Creo al Usuario= '+arrayData['Usuario'];

    let accion = {'Accion': 'altaUsuario',
        'data':{
            'User':arrayData['Usuario'],
            'Name': arrayData['Nombre'],
            'Email':arrayData['Email'],
            'Password':arrayData['Password'],
            'Rol':arrayData['SelectRolAltaUser'],
            'ImgUser': nameFoto,

    }};


    url = "Controlador/usuariosApi.php";

    return await fetch(url, {

        method:'POST',
        
        body: JSON.stringify(accion),
        
        headers:{ 'Content-Type': 'application/json'}

    }).then(data=>data.json())
    .then(data=>{

        switch(data["success"]){

            case true:

             showAlert("Correcto",data["message"],"success")
             
             $('#altaUsuario').modal('hide');
             setBitacora('1',comentario, modulo);


            break;

            case false:

             showAlert("Alerta",data["message"],"info")

            break;

            default:

            break;

        }
    })
    .then(data =>{

        var tablaSSP = $('#tablaUsuarios').DataTable();
        
        tablaSSP.ajax.reload();

    })
}
async function altaUsuarioV2(arrayData,arrayCheck,nameFoto='foto.png'){
    let comentario = 'Creo al Usuario= '+arrayData['Usuario'];
    let accion = {
            'User':arrayData['Usuario'],
            'Name': arrayData['Nombre'],
            'Email':arrayData['Email'],
            'Password':arrayData['Password'],
            'Rol':arrayData['SelectRolAltaUser'],
            'ImgUser': nameFoto,
            };


    // url = "Controlador/usuariosApi.php";

    return await fetch(ruta, {

        method:'POST',
        
        body: JSON.stringify(accion),
        
        headers:{ 'Content-Type': 'application/json'}

    }).then(data=>data.json())
    .then(data=>{

        switch(data["success"]){

            case true:

             showAlert("Correcto",data["message"],"success")
             
             $('#altaUsuario').modal('hide');
             setBitacora('9',comentario);



            break;

            case false:

             showAlert("Alerta",data["message"],"info")

            break;

            default:

            break;

        }
    })
    .then(data =>{

        var tablaSSP = $('#tablaUsuarios').DataTable();
        
        tablaSSP.ajax.reload();

    })
}


async function getDataUsuario(idUserEdit,idForm){


    var accion = {'Accion': 'getDataUsuario', 'Id': idUserEdit}

    url = "Controlador/usuariosApi.php";

    return await fetch(url, {

        method:'POST',

        body: JSON.stringify(accion),

        headers:{ 'Content-Type': 'application/json'}

    }).then(res => res.json())
    .then(res=>{
        
        insertarDataInput(res,idForm);
    
    })


}


async function getDataUsuarioView(idUser){


    var accion = {'Accion': 'getDataUsuario', 'Id': idUser}

    url = "Controlador/usuariosApi.php";

    return await fetch(url, {

        method:'POST',

        body: JSON.stringify(accion),

        headers:{ 'Content-Type': 'application/json'}

    }).then(res => res.json())
    .then(res=>{
        
        return res;
    
    })


}

async function getUnidades(idUser){

    let accion={'Accion': 'getUnidades'};
    
    url='Controlador/usuariosApi.php';

    return await fetch(url,{

        method:'POST',

        body: JSON.stringify(accion),

        headers:{'Content-Type': 'application/json'}

    }).then(res=>res.json())
    .then(res=>{
        
        filtrarDataUnidades(res,idUser);
    
    })

}

async function updateUnidades(arrayCheckSelect,id){

    let accion = {'Accion': 'updateUnidades', 'Id':id,'Equipos':arrayCheckSelect}

    url = "Controlador/usuariosApi.php";

    return await fetch(url, {

        method:'POST',

        body: JSON.stringify(accion),

        headers:{ 'Content-Type': 'application/json'}

    }).then(res=> res.json())
    .then(data=>{
        switch(data["success"]){

            case true:

             showAlert("Correcto",'Unidades Modificadas',"success")
             
             $('#altaUsuario').modal('hide');

            break;

            case false:

             showAlert("Sin cambios",'Unidades sin modificar',"info")

            break;

            default:

            break;

        }
    })



}

async function getUnidadesUser(id){

    let accion={'Accion': 'getUnidadesUser','Id':id};

    url='Controlador/usuariosApi.php';

    return await fetch(url,{

        method:'POST',

        body: JSON.stringify(accion),

        headers:{'Content-Type': 'application/json'}

    }).then(res=>res.json())
    .then(res=>{

        mostrarCheckSelect(res)
    
    })

}

async function restablecimiento(id){

    let accion = {'Accion': 'restablecimiento','Id':id};

    url = "Controlador/usuariosApi.php";

    return await fetch(url, {

        method:'POST',

        body: JSON.stringify(accion),

        headers:{ 'Content-Type': 'application/json'}

    }).then(data => data.json())
    .then(data=>{
        switch(data["success"]){

            case true:

                showAlert("Correcto",data["message"],"success");

                break;

            case false:

            this.showAlert("Falla",data["message"],"warning");

            break;

        }
    })


}

// Funcion para subir Fotos , En alta usuarios y actualizar usuario
async function subirFoto(arrayData,arrayCheck,dato,idModal=null){

        // declaro la variable formData e instancio el objeto nativo de javascript new FormData
        if(dato=='altaUsuario'){

            var formData = new FormData(document.getElementById("formAltaUsurio"));

        }
        
        if(dato=='updateUsuario'){
        
            var formData = new FormData(document.getElementById("formEditUsuario"));
        
        }

        var url = '../../requerimientos/vendors/Upload_img/procesar-subida.php';

        return await fetch(url, {

            method:'POST',

            body: formData,

        }).then(data => data.json())
        .then(data=>{

            if(data['success'] && dato=='altaUsuario'){
            
                altaUsuario(arrayData,arrayCheck,data['nameFoto']);
            
            }
            
            if(data['success'] && dato=='updateUsuario'){
            
                updateUsuario(arrayData,arrayCheck,idModal,data['nameFoto']);

                let nameFotoUdateUser=$('#ul_FotoEdit').attr('name')
            
                eliminarFoto(nameFotoUdateUser);

            }

        })

};


async function eliminarFoto(nameFoto){

    var accion = {'Accion': 'eliminarFoto','nameFoto':nameFoto}
    
    url = "Controlador/usuariosApi.php";

    return await fetch(url, {

        method:'POST',

        body: JSON.stringify(accion),

        headers:{ 'Content-Type': 'application/json'}

    }).then(data => data.text())

}


// -----------------------------------------------Funciones

function tablaPrincipal(){

    var accion = {"Accion" : "usuarios", "Tabla":"user_accounts"};

    var tablaSSP = $('#tablaUsuarios').DataTable({

    'ajax':{

      'url':rutaApi,

      'type': 'GET',

      'data':accion,

      'dataSrc': 'data',

    },

    'columns': [
        
        { 'data': 'acciones'},
        
        { 'data': 'Id' },

        { 'data': 'Status'},

        { 'data': 'User' },

        { 'data': 'Name' },

        { 'data': 'Email' },

        { 'data': 'nameRol' },

        // { 'data': 'AsignarUnidades'},

        // { 'data': 'reset'},


        // { 'data': 'eliminar'} 

    ],

    'language': {

    'url': '../../requerimientos/vendors/DataTables-1.10.24/language/Spanish_Mexico.json'

    },

    "scrollY": "400",

    "sScrollX": "100%",

    "sScrollXInner": "100%",

    "scrollCollapse": false,

    "paging": false,

    "destroy": true,


  });

}


function insertarDataInput(data,formId){

    let arrayData=data['message'];

    $('#'+formId+' .form_usuario').val(arrayData['User']);
    
    $('#'+formId+' .form_nombre').val(arrayData['Name']);
    
    $('#'+formId+' .form_email').val(arrayData['Email']);

    $('#'+formId+' .form_password').val('');

    // Le agrego el password actual
    $('#'+formId+' .form_password').attr('name',arrayData['Password']);

    // $('#'+formId+' .form_rol').val(arrayData['Rol']);

    $('#SelectRolUserEdit').val(arrayData['Rol']).trigger('chosen:updated');


    $('#'+formId+' .form_foto').val('');


    // Agrego el name de la foto actual ,para cuando cambio de foto eliminar la foto anterior
    let nameFotoUdateUser=document.getElementById('ul_FotoEdit');

    nameFotoUdateUser.setAttribute('name',arrayData['ImgUser']);

    $('#'+formId+' .EditDeleteGPS').prop('checked', Number(arrayData['EditDeleteGPS']));
    $('#'+formId+' .DeleteUnidades').prop('checked', Number(arrayData['DeleteUnidades']));
    $('#'+formId+' .DeleteODV').prop('checked', Number(arrayData['DeleteODV']));
    $('#'+formId+' .editProveedor').prop('checked', Number(arrayData['editProveedor']));
    $('#'+formId+' .EditDeleteTms').prop('checked', Number(arrayData['EditDeleteTms']));
    $('#'+formId+' .VerPrecios').prop('checked', Number(arrayData['VerPrecios']));
    $('#'+formId+' .UsuarioExt').prop('checked', Number(arrayData['UsuarioExt']));

    if(arrayData['Status']=='1'){

        let status=document.getElementById('Status');
        
        status.click();
    
    }

    // Libreria
    let objectCheck=[
            {
                name:'EditDeleteGPS',
                estado:arrayData['EditDeleteGPS']
            },
            {
                name:'DeleteUnidades',
                estado:arrayData['DeleteUnidades']
            },
            {
                name:'DeleteODV',
                estado:arrayData['DeleteODV']
            },
            {
                name:'editProveedor',
                estado:arrayData['editProveedor']
            },
            {
                name:'EditDeleteTms',
                estado:arrayData['EditDeleteTms']
            },
            {
                name:'VerPrecios',
                estado:arrayData['VerPrecios']
            },
            {
                name:'UsuarioExt',
                estado:arrayData['UsuarioExt']
            }

        ]


    objectCheck.forEach(e=>{

        if(e.estado=='1'){
        
            $('#'+formId+' .'+e.name+'').parent().addClass('checked');
        
        }
        else{
        
            $('#'+formId+' .'+e.name+'').parent().removeClass('checked');
        
        }

    })

}


function filtrarDataUnidades(resp,idUser){

    let data=resp.Data

    let array=[];

    data.forEach(e=>{

            array.push( [e.proveedor,e.no_economico,e.GPS_Id] )
    
    })

    let arrayPrincipal=[];

    // Itera todo los elementos
    array.forEach(item=>{

        // Guardo todo los elementos que tenga el mismo provedor en un array
        let ArrayProvedor=array.filter(element=> element[0]==item[0]);

        // Buscamos si el Nombre del provedor ya se encuenta en el Array Principal
        // En caso de no estar nos devuelve -1
        let nameRepeat=arrayPrincipal.findIndex(element2=>element2[0]==ArrayProvedor[0]);

        // Si no se repite el provedor se guarda en Array Principal
        if(nameRepeat==-1){

            arrayPrincipal.push(ArrayProvedor);
        
        }

    })


    printUnidades(arrayPrincipal,idUser);


}


function printUnidades(array,idUser){

        let mitad = Math.floor(array.length / 3);

        let inicio = array.slice(0, mitad);
        
        mitadMedia=mitad+mitad
        
        let medio = array.slice(mitad,mitadMedia);
        
        let final = array.slice(mitadMedia);

        let contador=0;

        let arrayPartes=[]
        arrayPartes.push(inicio);
        arrayPartes.push(medio);
        arrayPartes.push(final);

        // Form
        let container=document.querySelector('#containerListaUnidades');
        container.innerHTML='';


        arrayPartes.forEach(itemArray=>{

            // Creamos 2 columnas
            let containerColumna=document.createElement('div');
            containerColumna.classList.add('col');

            if(contador==0){
                containerColumna.innerHTML=`
                <div id='selectAllChecked'>
                        <div>
                            <input type="checkbox" value='sin value' id='allCheckbox'>
                            <label for="allCheckbox"><b>SELECIONAR TODOS</b></label>
                        </div>
                </div>`
                contador++;
            }

            itemArray.forEach(element=>{

                // Creamos un containder donde van ir todos los noEco de los provedores
                let item=document.createElement('div');


                item.innerHTML =`<b>${element[0][0]}</b>`;


                element.forEach(no_economico=>{
                    let idCheck=no_economico[1].split(" ").join("")

                    item.innerHTML+=`
                    <div>
                            <div>
                                <input type="checkbox" id='${idCheck}' class="asignarUniChecks" value='"${no_economico[2]}"'>
                                <label for="${idCheck}" >${no_economico[1]}</label>
                            </div>
                    </div>`
                })

                containerColumna.appendChild(item);

            })

            containerListaUnidades.appendChild(containerColumna)

        })



        getUnidadesUser(idUser);
        
        clickAllCheckbox();

}


function getCheckUnidades(id){

    let checks=document.querySelectorAll('.asignarUniChecks');

    let arrayCheckSelect=[];

    let arrayCompleto=[];

    checks.forEach(check=>{

        if(check.checked){
        
            arrayCheckSelect.push(check.value);
        
            arrayCompleto.push(true);
        
        }

    })


    if(arrayCompleto.length==checks.length){

        arrayCheckSelect=['"*"']
    
    }

    updateUnidades(arrayCheckSelect,id);

}


function mostrarCheckSelect(arrayUser){

    let Arraycheckboxs=document.querySelectorAll('.asignarUniChecks');

    // let array=['T-33','T-55','T-3q33','T-5345','T-343','T-545','T-33r','T-535','T-rs33','T-sf55','T-rs33','T-sf55'];

    // SI el usuario contiene un ['*'] selecionar todos
    if(arrayUser.message.Equipos=='["*"]'){

        $('#allCheckbox').attr('checked',true);

        Arraycheckboxs.forEach(element=>{

            element.setAttribute('checked',true)
        
        })

    }else{

        let arrayString=arrayUser.message.Equipos

        arrayString=arrayString.replace('[','');
        
        arrayString=arrayString.replace(']','');

        let array = arrayString.split(',');

        Arraycheckboxs.forEach(element => {

                array.filter(no_eco=>{

                    if(no_eco==element.value){
                    
                        element.setAttribute('checked',true);
                    
                    }

                })
        });

    }

}

function clickAllCheckbox(){

    $("#allCheckbox").on("click", function() {

        $(".asignarUniChecks").prop("checked", this.checked);
        
    });

        // Si le doy click a algun checkbox, ya no estarian todos selecionados,
        // O si le doy click a uno y se acompletan todos muestre el btn cheked
        $(".asignarUniChecks").on("click", function() {
        
            if ($(".asignarUniChecks").length == $(".asignarUniChecks:checked").length) {
        
                $("#allCheckbox").prop("checked", true);
        
            } else {
        
                $("#allCheckbox").prop("checked", false);
        
            }
        
        });

}

function respValidar(clase){
    
    let resultadoValidar=validar(clase);

    if(resultadoValidar){
    
        return validarCaracteres(clase);
    
    }else{
    
        return false;
    
    }

}
async function getInputSelect(nameTabla, datos, idInputSelect, idEstadoOCiudad = null, idUpdate = null) {



    let accion = {
      'Accion': 'getSelect', 'data': {
        nameTabla: nameTabla,
        datos: datos
      }
    }
  
  
  
    let url = "Controlador/usuariosApi.php";
  
  
  
    return await fetch(url, {
  
      method: 'POST',
  
  
  
      body: JSON.stringify(accion),
  
  
  
      headers: { 'Content-Type': 'application/json' }
  
  
  
    }).then(res => res.json())
  
  
  
      .then(data => {
  
  
  
        insertarValuesSelect(idInputSelect, data['Data'], idEstadoOCiudad, idUpdate);
  
  
  
      })
  
  
  
  }

  function insertarValuesSelect(id, data, idEstadoOCiudad, idUpdate = 'Seleccione uno...') {

    switch (id) {
      case 'SelectRolAltaUser':
  
        var idSelect = document.getElementById(id);
  
  
  
        data.forEach(element => {
  
  
  
          var option = new Option(element.nombre, element.id);
  
          option.setAttribute('name', element.nombre)
  
  
  
          idSelect.appendChild(option);
  
  
  
        });
  
  
  
        break;
  
  
  
      case 'SelectRolUserEdit':
  
        var idSelect = document.getElementById(id);
  
  
  
        data.forEach(element => {
  
  
  
          var option = new Option(element.nombre, element.id);
  
          option.setAttribute('name', element.nombre)
  
  
  
          idSelect.appendChild(option);
  
  
  
        });
        break;
  
        var idSelect = document.getElementById(id);
  
        idSelect.innerHTML = '';
  
  
  
        data.forEach(element => {
  
  
  
          if (idEstadoOCiudad == element.Id_Estado_Fk || element.Id_Estado_Fk == 'vacio') {
  
            var option = new Option(element.Ciudad, element.Id_Ciudad);
  
            option.setAttribute('name', element.Ciudad)
  
            idSelect.appendChild(option);
  
          }
  
  
  
        });
  
  
  
        $("#" + id + " option[value=" + idUpdate + "]").attr("selected", true);
  
  
  
        break;
    }
}

const insertSelectInput = async(id, data,text,key) => {

    let selectInput = document.getElementById(id);

    selectInput.innerHTML = `<option value="">Seleccione uno...</option>`;

    data.forEach(element => {

        var option = new Option(element[text], element[key]);

        selectInput.appendChild(option);

    });

    return;
}



async function getDataRoles(nameTabla, datos) {

    let accion = {
        'Accion': 'getSelect', 'data': {
            nameTabla: nameTabla,
            datos: datos
        }
    }

    let url = "Controlador/usuariosApi.php";

    return await fetch(url, {

        method: 'POST',
        body: JSON.stringify(accion),
        headers: { 'Content-Type': 'application/json' }

    }).then(res => res.json())
        .then(data => {
            console.log(data);
            return data;
        })
}


let dataExcel={
    idBtnExcel:'btnExcelTabla',
    nameFile:'Usarios',
    urlApi:rutaApi,
    accion:`?Accion=usuarios&getDataExcel=1&Tabla=user_accounts`,
    urlVendor:'../../requerimientos/vendors/spreadsheet/spreadsheetExcel.php'
}

let excelTabla = new exportarExcelTabla(dataExcel);
