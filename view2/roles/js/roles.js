$(document).ready(function() { //Informacion al cargar la pagina

    $('#titlePage').text('Roles');

    msgAlert="";

    txtAlert="";

    tpAlert="";

    tablaPrincipal();


})

const resetTablas=()=>{
    
        // Sin monedas a cambiar 
    // tablaPrincipal();
    // "destroy": true,

}
const modulo = 4;
// -------------------------------------------- Carga de Checks
$(document).on('click', '.btnAltaRol', function (e) {
        checkPermitidos('contCheck','checkAlta'); 
    });
$(document).on('click', '.btnGuardarRol', function (e) {
    let name = document.getElementById('rol').value;    
    let checks=document.querySelectorAll('.checkAlta ');
    let arrayCheckSelect=[];
    let arrayCompleto=[];
    checks.forEach(check=>{
        if(check.checked){
            arrayCheckSelect.push(check.value);
        
            arrayCompleto.push(true);
        
        }

    })
    altaRol(arrayCheckSelect,name);
    });

    function altaRol(CheckSelect,nombre){
    let comentario = 'Creo el Rol= '+nombre;
    console.log(CheckSelect);
        let accion = {'Accion': 'altaRoles','Roles':CheckSelect,'nombre':nombre}
        url = "Controlador/rolApi.php";

        fetch(url, {
            method:'POST',
            body: JSON.stringify(accion),
            headers:{ 'Content-Type': 'application/json'}
        }).then(res=> res.json())
        .then(data=>{
            switch(data["success"]){
                case true:
                showAlert("Alta Rol",'Datos correctos',"success")
                $('#altaRol').modal('hide');
                let tabla = $('#tablaRoles').DataTable();
                tabla.ajax.reload();
                setBitacora('1', comentario, modulo);

                break;
                case false:
                showAlert("Error",'Datos incorrectos',"info")
                break;
                default:
                break;
            }
        })

    }

// --------------------------Btn Eliminar Rol

$(document).on('click', '.btnElimarTabla', function (e) {
// $('#tablaRoles tbody').on('click','.btnElimarTabla',function (e){
    let idUser = $(this).attr('id');

    let btnEliminarModal=document.querySelector('.btnModalEliminarRol');
    btnEliminarModal.removeAttribute('id');
    // let nameUserModalEliminar=$(this).attr('name');
    // let agregarName=$('#nameUserModalEliminar').text(nameUserModalEliminar);

    btnEliminarModal.setAttribute("id",idUser);
});
$(document).on('click', '.btnModalEliminarRol', function (e) {
// $('body').on('click','.btnModalEliminarRol',function (e){
    let idModal = $(this).attr('id');
    let idString=idModal.substring(2);
    let idUser=Number(idString);
    deleteRol(idUser);


});
function deleteRol(id){
        let comentario = "Elimino el Rol con el Id:"+id;
        let accion = {'Accion': 'dRol','data':id}
        url = "Controlador/rolApi.php";
        fetch(url, {
            method:'POST',
            body: JSON.stringify(accion),
            headers:{ 'Content-Type': 'application/json'}
        }).then(resp =>resp.json())
        .then(data =>{
            if(data['success']){
                showAlert("Cambios Realizados",data['message'],"success")
                $('#editRol').modal('hide');
                let tabla = $('#tablaRoles').DataTable();
                tabla.ajax.reload();

                setBitacora('3', comentario, modulo);

            }else{
                showAlert("Sin cambios",data['message'],"info")
            }
        })
}

// --------------------------Btn Restablecimiento Sesion

$('#tablaRoles tbody').on('click','.btnResetTabla',function (e){

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
$(document).on('click', '.btnActualizarEdit', function (e) {
// $('#tablaRoles tbody').on('click','.btnEditarTabla',function (e){
    let idString = $('#idEdit').val();

    let id=idString.substring(2);
    
    let idRol=Number(id);

    // let valuePass=$('#formEditUsuario .form_password').val();
    
    let name=$('#rolEditName').val();


    let checks = document.querySelectorAll('.checkEdit');
        
        let checkStatus=document.getElementById('Status');

        let arrayData=[];
        arrayData['nombre'] = name;
        // let arrayCheck=[];
        // arrayData[checkStatus.id]=Number(checkStatus.checked);
        checks.forEach(e=>{
            let estado = false;
            let id = e.value;
            if(e.checked){
                estado = true;
            }
            if(id != ''){
                arrayData[e.value] = estado; 
            }
        })
        setRol(arrayData,idRol);
});
function setRol(CheckSelect,id){
    // console.log(CheckSelect);
    const obj = {... CheckSelect};
    // CheckSelect = JSON.stringify(CheckSelect);
    // console.log(obj);
    let accion = {'Accion': 'setRoles','data': obj ,'id':id}
    // console.log(JSON.stringify(accion));
    url = "Controlador/rolApi.php";
    fetch(url, {
        method:'POST',
        body: JSON.stringify(accion),
        headers:{ 'Content-Type': 'application/json'}
    }).then(resp =>resp.json())
    .then(data =>{
        if(data['success']){
            showAlert("Cambios Realizados",data['message'],"success")
            $('#editRol').modal('hide');
        }else{
            showAlert("Sin cambios",data['message'],"info")
        }
    })
}
$(document).on('click', '.btnEditarTabla', function (e) {
// $('body').on('click','.btn_FormEditUser',function (e){
    let idString = $(this).attr('id');
    let id=idString.substring(2);
    let idUser=Number(id);
    new Promise((resolve, reject) => {
        let complet = checkPermitidos('contCheckEdit','checkEdit');
        if(complet){
            resolve(complet);
        }
    }).then(data =>{
        let resp = getRolesEdit(idUser);
        
    });
});


// --------------------------Btn Asignar Unidades

$('#tablaRoles tbody').on('click','.btnAsignarUnidadeTabla',function (e){
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
// -----------------------------------------------Llamadas
async function checkPermitidos(div,clase){
    var accion = {'Accion': 'getChecks'}
    url = "Controlador/rolApi.php";
    return await fetch(url, {
        method:'POST',
        body: JSON.stringify(accion),
        headers:{ 'Content-Type': 'application/json'}
    }).then(res => res.json())
    .then(data =>{
        $('#'+div).empty();
        let divContenedor = '';
        let divName = '';
        let classDiv = 'col-4 mt-3';
        Object.entries(data['data']).forEach(function callback(value, index) {
            let opcion = '';
            let nombre = ""; 
            let itemTitulo = '';
            if(parseInt(value[1]) == 1){
                let containerColumna=document.createElement('div');
                containerColumna.classList.add('col');
                let container=document.querySelector('#'+div);
                opcion = value[0].substring(0,3);
                let accion = value[0].charAt(value[0].length -1);
                switch(accion){
                    case 'C':
                        accion = 'Alta';
                    break;
                    case 'R':
                        accion = 'Ver';
                    break;
                    case 'U':
                        accion = 'Editar';
                    break;
                    case 'D':
                        accion = 'Eliminar';
                    break;
                    case 'B':
                        accion = 'Cerrar';
                    break;
                    case 'S':
                        accion = 'Cambiar Costo de';
                    break;
                }
                switch(opcion){
                    case 'pro':
                         nombre = "Proveedor";
                          divContenedor= document.getElementById('divProveedores'+div);
                        //  console.log(divProveedores.length);
                         if(divContenedor == null){
                            let itemProveedores=document.createElement('div');
                            itemProveedores.classList = classDiv;
                            itemTitulo=document.createElement('span');
                            itemTitulo.innerHTML = nombre;
                            itemProveedores.id = 'divProveedores'+div;
                            container.appendChild(itemProveedores);
                            itemProveedores.appendChild(itemTitulo);
                            divName = 'divProveedores'+div;
                         }
                    break;
                    case 'cli':
                         nombre = "Cliente";
                          divContenedor=document.getElementById('divCliente'+div);
                         if(divContenedor == null){
                            let itemCliente=document.createElement('div');
                            itemCliente.classList = classDiv;
                            itemTitulo=document.createElement('span');
                            itemTitulo.innerHTML = nombre;
                            itemCliente.id = 'divCliente'+div;
                            container.appendChild(itemCliente);
                            itemCliente.appendChild(itemTitulo);
                            divName = 'divCliente'+div;
                         }
                    break;
                    case 'usu':
                         nombre = "Usuario";
                          divContenedor= document.getElementById('divUsuario'+div);
                         if(divContenedor == null){
                            let itemUsuario=document.createElement('div');
                            itemUsuario.classList = classDiv;
                            itemTitulo=document.createElement('span');
                            itemTitulo.innerHTML = nombre;
                            itemUsuario.id = 'divUsuario'+div;
                            container.appendChild(itemUsuario);
                            itemUsuario.appendChild(itemTitulo);
                            divName = 'divUsuario'+div;
                         }

                    break;
                    case 'rol':
                         nombre = "Roles";
                          divContenedor= document.getElementById('divRoles'+div);
                         if(divContenedor == null){
                            let itemRoles=document.createElement('div');
                            itemRoles.classList = classDiv;
                            itemTitulo=document.createElement('span');
                            itemTitulo.innerHTML = nombre;
                            itemRoles.id = 'divRoles'+div;
                            container.appendChild(itemRoles);
                            itemRoles.appendChild(itemTitulo);
                            divName = 'divRoles'+div;
                         }
                    break;
                    case 'prd':
                        nombre = "Producto";
                         divContenedor= document.getElementById('divProducto'+div);
                        if(divContenedor == null){
                           let itemRoles=document.createElement('div');
                           itemRoles.classList = classDiv;
                           itemTitulo=document.createElement('span');
                           itemTitulo.innerHTML = nombre;
                           itemRoles.id = 'divProducto'+div;
                           container.appendChild(itemRoles);
                           itemRoles.appendChild(itemTitulo);
                           divName = 'divProducto'+div;
                        }
                   break;
                   case 'for':
                    nombre = "Formulas";
                     divContenedor= document.getElementById('divFormulas'+div);
                    if(divContenedor == null){
                       let itemRoles=document.createElement('div');
                       itemRoles.classList = classDiv;
                       itemTitulo=document.createElement('span');
                       itemTitulo.innerHTML = nombre;
                       itemRoles.id = 'divFormulas'+div;
                       container.appendChild(itemRoles);
                       itemRoles.appendChild(itemTitulo);
                       divName = 'divFormulas'+div;
                    }
               break;
                   case 'odt':
                    nombre = "Ordenes de Trabajo";
                     divContenedor= document.getElementById('divOdt'+div);
                    if(divContenedor == null){
                       let itemRoles=document.createElement('div');
                       itemRoles.classList = classDiv;
                       itemTitulo=document.createElement('span');
                       itemTitulo.innerHTML = nombre;
                       itemRoles.id = 'divOdt'+div;
                       container.appendChild(itemRoles);
                       itemRoles.appendChild(itemTitulo);
                       divName = 'divOdt'+div;
                    }
               break;
                   case 'not':
                    nombre = "Notas de Venta";
                     divContenedor= document.getElementById('divNtsV'+div);
                    if(divContenedor == null){
                       let itemRoles=document.createElement('div');
                       itemRoles.classList = classDiv;
                       itemTitulo=document.createElement('span');
                       itemTitulo.innerHTML = nombre;
                       itemRoles.id = 'divNtsV'+div;
                       container.appendChild(itemRoles);
                       itemRoles.appendChild(itemTitulo);
                       divName = 'divNtsV'+div;
                    }
               break;
                   case 'com':
                    nombre = "Compras";
                     divContenedor= document.getElementById('divCompras'+div);
                    if(divContenedor == null){
                       let itemRoles=document.createElement('div');
                       itemRoles.classList = classDiv;
                       itemTitulo=document.createElement('span');
                       itemTitulo.innerHTML = nombre;
                       itemRoles.id = 'divCompras'+div;
                       container.appendChild(itemRoles);
                       itemRoles.appendChild(itemTitulo);
                       divName = 'divCompras'+div;
                    }
               break;
                   case 'aju':
                    nombre = "Ajustes";
                     divContenedor= document.getElementById('divAjustes'+div);
                    if(divContenedor == null){
                       let itemRoles=document.createElement('div');
                       itemRoles.classList = classDiv;
                       itemTitulo=document.createElement('span');
                       itemTitulo.innerHTML = nombre;
                       itemRoles.id = 'divAjustes'+div;
                       container.appendChild(itemRoles);
                       itemRoles.appendChild(itemTitulo);
                       divName = 'divAjustes'+div;
                    }
               break;
               case 'bit':
                nombre = "Bitacora";
                 divContenedor= document.getElementById('divBitacora'+div);
                if(divContenedor == null){
                   let itemRoles=document.createElement('div');
                   itemRoles.classList = classDiv;
                   itemTitulo=document.createElement('span');
                   itemTitulo.innerHTML = nombre;
                   itemRoles.id = 'divBitacora'+div;
                   container.appendChild(itemRoles);
                   itemRoles.appendChild(itemTitulo);
                   divName = 'divBitacora'+div;
                }
           break;
                }
                if(divContenedor == null){
                    divContenedor= document.getElementById(divName);
                }
                // Creamos un containder donde van ir todos los noEco de los provedores
                let item=document.createElement('div');
                // let container=document.querySelector('#contCheck');
                item.innerHTML+=`
                <div>
                        <div>
                            <input type="checkbox" id='${opcion}' class="RolChecks ${clase} form-check-input ${value[0]}" value='${value[0]}'>
                            <label clase="form-check-label " for="${opcion}" >${accion} ${nombre} </label>
                        </div>
                </div>`;
                divContenedor.appendChild(item);
            }
            return true;
          });
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
// -----------------------------------------------Funciones

function tablaPrincipal(){

    var accion = {"Accion" : "getRoles"};

    var tablaSSP = $('#tablaRoles').DataTable({

    'ajax':{

      'url':'Controlador/rolApi.php',

      'type': 'POST',

      'data':accion,

      'dataSrc': 'Data',

    },

    'columns': [

        { 'data': 'acciones'},

        { 'data': 'id' },

        { 'data': 'nombre'},

        // { 'data': 'User' },

        // { 'data': 'Name' },

        // { 'data': 'Email' },

        // { 'data': 'Rol' },

        // { 'data': 'AsignarUnidades'},

        // { 'data': 'reset'},


        // { 'data': 'eliminar'} 

    ],

    'language': {

    'url': '../../requerimientos/vendors/DataTables-1.10.24/language/Spanish_Mexico.json'

    },
    "destroy": true,

    "scrollY": "500px",

    "sScrollX": "100%",

    "sScrollXInner": "100%",

    "scrollCollapse": true,

    "paging": false

  });

}
function insertarDataInput(data,formId){

    let arrayData=data['message'];

    const usuario=$('#'+formId+' .form_usuario').val(arrayData['Usuario']);
    
    const nombre=$('#'+formId+' .form_nombre').val(arrayData['Nombre']);
    
    const email=$('#'+formId+' .form_email').val(arrayData['Email']);

    $('#'+formId+' .form_password').val('');

    // Le agrego el password actual
    const password=$('#'+formId+' .form_password').attr('name',arrayData['Password']);

    const rol=$('#'+formId+' .form_rol').val(arrayData['Rol']);

    const foto=$('#'+formId+' .form_foto').val('');


    // Agrego el name de la foto actual ,para cuando cambio de foto eliminar la foto anterior
    let nameFotoUdateUser=document.getElementById('ul_FotoEdit');

    nameFotoUdateUser.setAttribute('name',arrayData['Foto']);

    const editDeleteGps=$('#'+formId+' .EditDeleteGPS').prop('checked', Number(arrayData['EditDeleteGPS']));
    const DeleteUnidades=$('#'+formId+' .DeleteUnidades').prop('checked', Number(arrayData['DeleteUnidades']));
    const DeleteODV=$('#'+formId+' .DeleteODV').prop('checked', Number(arrayData['DeleteODV']));
    const editProveedor=$('#'+formId+' .editProveedor').prop('checked', Number(arrayData['editProveedor']));
    const EditDeleteTms=$('#'+formId+' .EditDeleteTms').prop('checked', Number(arrayData['EditDeleteTms']));
    const verPrecios=$('#'+formId+' .VerPrecios').prop('checked', Number(arrayData['VerPrecios']));
    const usuarioExterno=$('#'+formId+' .UsuarioExt').prop('checked', Number(arrayData['UsuarioExt']));

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
function getCheckAlta(){

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
async function getRolesEdit(id) {
    let accion = {'Accion': 'getRolEdit', 'data': id}
  
    let url = "Controlador/rolApi.php";
  
  
  
    return await fetch(url, {
  
      method: 'POST',
  
  
  
      body: JSON.stringify(accion),
  
  
  
      headers: { 'Content-Type': 'application/json' }
  
  
  
    }).then(res => res.json())
  
  
  
      .then(data => {
        let array = data['data'];
        arreglo = [];
        array.forEach(element => {
            arreglo = element;
        });
        // console.log(arreglo);
        let comentario = "Actualizo el Rol :"+arreglo.nombre;
        setBitacora('4', comentario, modulo);
        Object.entries(arreglo).forEach(element => {
            // console.log(element);
            if(element[0] == 'id'){
                $('#idEdit').val('ed'+element[1]);
            }else{
                if(element[0] == 'nombre'){
                    $('#rolEditName').val(element[1]);
                }else{
                    if(element[1] == "1")
                    $( "."+element[0]).prop( "checked", true );
                }
            } 
        });
      })
}
