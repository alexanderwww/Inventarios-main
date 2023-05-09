$(document).ready(function () { //Informacion al cargar la pagina

  $('#titlePage').text('Proveedores');

  tablaPrincipal();
  iniFuntion();
})

const iniFuntion = async () => {

  let respuestaSelect = await getInputSelect('paises', 'Id_Pais,Pais');

  await insertDataSelect(respuestaSelect, 'paisSelectAlta', 'Pais', 'Id_Pais');

  await insertDataSelect(respuestaSelect, 'PaisProveedor', 'Pais', 'Id_Pais');

  // selectChosenEdit

  // $(".selectChosenAlta").chosen("destroy");
  $(".selectChosenAlta").chosen({
      width: "100%",
      no_results_text: "No se a encontrado resultados",
      allow_single_deselect: true,
  });
  // $(".selectChosenAlta").chosen("destroy");
  $(".selectChosenEdit").chosen({
    width: "100%",
    no_results_text: "No se a encontrado resultados",
    allow_single_deselect: true,
});


}

const resetTablas=()=>{
    // Sin monedas a cambiar 
  // tablaPrincipal();
}


const modulo = 2;

function tablaPrincipal() {

  var accion = { "Accion": "proveedores", 'Tabla': 'proveedores' }

  var tablaSSP = $('#tProveedores').DataTable({
    'ajax': {
      'url': rutaApi,
      'type': 'GET',
      'data': accion,
      'dataSrc': 'data',
    },
    'columns': [
      { 'data': 'acciones' },
      { 'data': 'Id' },
      { 'data': 'Status' },
      { 'data': 'Nombre' },
      { 'data': 'RazonSocial' },
      { 'data': 'RFC' },
      { 'data': 'ContactoPrincipal' },
      { 'data': 'Telefono' },
      { 'data': 'Ext' },
      { 'data': 'CalleProveedor' },
      { 'data': 'CPProveedor' },
      { 'data': 'DiasCredito' },
      { 'data': 'NoCuenta' },
      { 'data': 'MonedaBckup' },
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
  });
}

$('#tProveedores tbody').on('click', '.btnDeshabilitarTabla',function(e){

  initModalDeshabilidar(this);

})
$('#tProveedores tbody').on('click', '.btnHabilitarTabla',function(e){

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

  // console.log(name);

  $('.btnAceptarHabilitar').attr('id', idElement);

  $('.btnAceptarHabilitar').attr('name', name);


  $('#modalHabilitarTitle').text($(element).attr('name'));

  $('#modalHabilitar').modal('show');

}
const getNameBtn=(event)=>{

  let name = $(event).attr('name');

  return name;

}
$('.btnAceptarDeshabilitar').on('click', async function(e){
  let name = this.name;

  let respuestaUpdate=await updateStatusProveedor(this.id,0,name);

  if(respuestaUpdate['success']){

      reloadTable('tProveedores','modalDeshabilitar')
  
  }

})
$('.btnAceptarHabilitar').on('click',async function(e){

  let name = this.name;
// console.log(name);
  let respuestaUpdate=await updateStatusProveedor(this.id,1, name);

  if(respuestaUpdate['success']){

      reloadTable('tProveedores','modalHabilitar')
  
  }

})
const reloadTable=(idTable,idModal)=>{

  $('#'+idModal).modal('hide');

  let tablaCargar = $('#'+idTable).DataTable();
  tablaCargar.ajax.reload();

  return;
}
const updateStatusProveedor=async (id,status,name)=>{

  let accion = {"Accion" : "proveedores","Tabla":"proveedores",'Id':id,'Status':status};

  return await fetch(rutaApi, {

      method: 'PUT',

      body: JSON.stringify(accion),

      headers: {'Content-Type': 'application/json'}
  
  }).then(respuesta=>respuesta.json())
  
  .then(respuesta =>{
      
      if(respuesta['success']){

          showAlert("Correcto",respuesta['messenge'],"success")  

          if(status==0){
              var comentario = "Deshabilito el Proveedor: "+name;
              var codigo = 2;
          }else{
             var comentario = "Habilito el Proveedor: "+name;
             var codigo = 7;

          }   
          setBitacora(codigo, comentario, modulo);

      }else{

          showAlert("Alerta",respuesta['messenge'],"info")
      
      }

      return respuesta;
  })

}

// ----------------------------------------------Validacion 

function respValidar(clase) {
  let resultadoValidar = validar(clase);
  if (resultadoValidar) {
    return validarCaracteres(clase);
  } else {
    return false;
  }
}

function validarCorreosNoObligatorios(idForm) {
  let arrayGmail = document.querySelectorAll('#' + idForm + ' .validarCorreosNoObligatorios');
  let emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
  let arrayRespuestaCorreos = [];
  arrayGmail.forEach(email => {
    if (email.value === '') {
      $("#" + email.id).css({ 'border-color': '#ced4da', "border-weight": "0" });
      $("#ul_" + email.id).css({ 'display': 'none' })
      arrayRespuestaCorreos.push(true);
    } else {
      if (!emailRegex.test(email.value)) {
        $("#ul_" + email.id).css({ 'display': 'block' });
        $("#ul_" + email.id).text('Se requiere correo Valido');
        $("#" + email.id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });
        arrayRespuestaCorreos.push(false);
      }
      else {
        $("#" + email.id).css({ 'border-color': '#ced4da', "border-weight": "0" });
        $("#ul_" + email.id).css({ 'display': 'none' })
        arrayRespuestaCorreos.push(true);
      }
    }
  })
  return arrayRespuestaCorreos.every(resp => resp == true);
}

function validacionesFormularioProvedor(idModalWizar, idContador, formWizard, campoValidar, classCaractInpustNoObligat) {
  let numContent_Section = document.getElementById('sw-current-step' + idContador);
  let valueNum = numContent_Section.textContent;
  if (valueNum == '' || valueNum == 1) {
    document.getElementById("ziseContentForm").style.height = "auto";
    document.getElementById("ziseContentForm2").style.height = "auto";
    let respValidar_Section1 = respValidar(campoValidar + '_Sectio1');

    if (respValidar_Section1) {

      $('#' + idModalWizar).smartWizard("next");

      $("#" + formWizard + " .form_text_adv").css({ 'display': 'none' })

      $("#" + formWizard + " ." + campoValidar + "_Sectio2").css({ 'border-color': '#ced4da', "border-weight": "0" });

      return true;

    }







  }



  if (valueNum == 2) {

    let respValidar_Section2 = respValidar(campoValidar + '_Sectio2');

    document.getElementById("ziseContentForm").style.height = "auto";

    document.getElementById("ziseContentForm2").style.height = "auto";



    $('#' + idModalWizar + ' .' + classCaractInpustNoObligat).css({ 'border-color': '#ced4da', "border-weight": "0" });

    $('#' + idModalWizar + ' .formNoReq_text_adv').css({ 'display': 'none' });





    let respCaracteresSection2 = validarCaracteres(classCaractInpustNoObligat);





    if (respValidar_Section2 && respCaracteresSection2) {

      $('#' + idModalWizar).smartWizard("next");

      $("#" + formWizard + " .form_text_adv").css({ 'display': 'none' })

      $("#" + formWizard + " ." + campoValidar + "_Sectio3").css({ 'border-color': '#ced4da', "border-weight": "0" });



      $('.btnWizarconfirmarDisponible' + idContador).attr('disabled', false);

      $(".btnWizarconfirmarDisponible" + idContador).css("opacity", "1");



      return true;

    }

  }
  if (valueNum == 3) {

    let respValidar_Section3 = respValidar(campoValidar + '_Sectio3');

    document.getElementById("ziseContentForm").style.height = "auto";

    document.getElementById("ziseContentForm2").style.height = "auto";



    if (respValidar_Section3) {

      $('#' + idModalWizar).smartWizard("next");

      return true;

    }

  }

}

// -------------------------------------------- Toogle switch

// var checkboxStatus = document.querySelector('.js-switch-status');
// var initcheckboxStatus = new Switchery(checkboxStatus, { color: '#d7572b', jackColor: '#ffffff' });


// -------------------------------------------- Wizar

function nuevoWizar(idModalWizar, idContador, formWizard, campoValidar, classCaractInpustNoObligat) {
  $(function () {



    $('#' + idModalWizar).smartWizard({

      selected: 0,

      theme: 'square',

      transition: {

        animation: 'fade'

      },

      toolbar: {

        showNextButton: false,

        showPreviousButton: false,

        position: 'both',



      }



    });



    $("#" + idModalWizar).on("showStep", function (e, anchorObject, stepIndex, stepDirection, stepPosition) {



      let stepInfo = $('#' + idModalWizar).smartWizard("getStepInfo");



      $("#sw-current-step" + idContador).text(stepInfo.currentStep + 1);



    })



  })



  $("#prev-btn-modal" + idContador).on("click", function () {

    $('#' + idModalWizar).smartWizard("prev");

    return true;

  });



  // Validaciones de secciones 

  $("#next-btn-modal" + idContador).on("click", function () {

    // let numContent_Section = document.getElementById('sw-current-step' + idContador);

    // let valueNum = numContent_Section.textContent;

    validacionesFormularioProvedor(idModalWizar, idContador, formWizard, campoValidar, classCaractInpustNoObligat)

  });

  $("#next-btn-modalV" + idContador).on("click", function () {

    // let numContent_Section = document.getElementById('sw-current-step' + idContador);

    // let valueNum = numContent_Section.textContent;

    $('#' + idModalWizar).smartWizard("next");


  });



}

nuevoWizar('smartwizard', 1, 'formAltaProvedor', 'campoValidarAltaProvedor', 'campoValidarCaracteresAlta');



nuevoWizar('smartwizard2', 2, 'formEditarProvedor', 'campoValidarEditarProvedor', 'campoValidarCaracteresEditar');

nuevoWizar('smartwizard3', 3, 'formViewProvedor', 'campoValidarViewProvedor', 'campoValidarCaracteresView');
function resetearWizard(idWizar) { $('#' + idWizar).smartWizard("reset"); }



// async function getInputSelect(nameTabla, datos, idInputSelect, idEstadoOCiudad = null, idUpdate = null) {
async function getInputSelect(nameTabla, datos, idBuscar = '') {



  let accion = {
    'Accion': 'getSelect', 'data': {

      nameTabla: nameTabla,

      datos: datos,

      idBuscar: idBuscar
    }
  }



  let url = "Controlador/proveedorApi.php";



  return await fetch(url, {

    method: 'POST',



    body: JSON.stringify(accion),



    headers: { 'Content-Type': 'application/json' }



  }).then(respuesta => respuesta.json())



    .then(respuesta => {


      return respuesta['Data'];



    })



}




const insertDataSelect = async (data, idInputSelect, texto, identificador, arrayAttr = null) => {

  let inputSelect = document.getElementById(idInputSelect);

  inputSelect.innerHTML = `<option value="">Seleccione uno...</option>`

  let respArrayAttr = arrayAttr == null ? false : true;


  data.forEach(element => {

    var option = new Option(element[texto], element[identificador]);//name y id

    if (respArrayAttr) {

      arrayAttr.forEach(atributo => {

        option.setAttribute(atributo['nameAttr'], element[atributo['valor']])

      })
    }

    inputSelect.appendChild(option);

  })

}

async function insertarDataInputView(arrayData, formId) {

  let nombreComercialUpdateProvedor = arrayData.Nombre;
  $('#nameProvedorModalView').text(nombreComercialUpdateProvedor)




  let arrayInputFormUdpate = document.querySelectorAll('.dataInputViewProvedor');

  arrayInputFormUdpate.forEach(input => {
    let valorDataProvedor = arrayData[input.id];
    // le agregamos al input el valor del objetcto 
    input.value = valorDataProvedor;

  })
  document.getElementById('NombreComView').value = arrayData.Nombre;


  const checkbox = document.getElementById('StatusV');

  if (arrayData.Status == '1') {

    checkbox.checked = true;

  } else {

    checkbox.checked = false;

  }


}


async function insertarDataInput(arrayData, formId) {


  let inpuSeletPais = document.querySelector('#PaisProveedor');
  inpuSeletPais.value = arrayData.Id_Pais_Fk;


  let respuestaSelect = await getInputSelect('estados', 'Id_Estado,Id_Pais_Fk,Estado', arrayData.Id_Pais_Fk);

  await insertDataSelect(respuestaSelect, 'EstadoProveedor', 'Estado', 'Id_Estado',
    [{ 'nameAttr': 'attr_pais', 'valor': 'Id_Pais_Fk' }]
  );


  inputSelectEstado = document.getElementById('EstadoProveedor').value = arrayData.Id_Estado_Fk;




  respuestaSelect = await getInputSelect('ciudades', 'Id_Estado_Fk,Ciudad,Ver,Id_Ciudad', arrayData.Id_Estado_Fk)

  await insertDataSelect(respuestaSelect, 'CiudadProveedor', 'Ciudad', 'Id_Ciudad',
    [{ 'nameAttr': 'attr_estado', 'valor': 'Id_Estado_Fk' }]
  );


  document.getElementById('CiudadProveedor').value = arrayData.Id_Ciudad_Fk;




  let nombreComercialUpdateProvedor = arrayData.Nombre;
  $('#nameProvedorModalEditar').text(nombreComercialUpdateProvedor)




  let arrayInputFormUdpate = document.querySelectorAll('.dataInputEditarProvedor');

  arrayInputFormUdpate.forEach(input => {

    let valorDataProvedor = arrayData[input.id];
    // le agregamos al input el valor del objetcto 
    input.value = valorDataProvedor;

  })

  document.getElementById('NombreCom').value = arrayData.Nombre;


  const checkbox = document.getElementById('Status');

  if (arrayData.Status == '1') {

    checkbox.checked = true;

  } else {

    checkbox.checked = false;

  }


}


// Al darle click Obtiene el ID del pais y hace una consulta con los datos que le de

let idInputSelectPais = document.querySelector('#paisSelectAlta');

// idInputSelectPais.addEventListener("change", async function () {
$('#paisSelectAlta').on('change', async function () {


  var idPais = idInputSelectPais.value;

  let respuestaSelect = await getInputSelect('estados', 'Id_Estado,Id_Pais_Fk,Estado', idPais);

  await insertDataSelect(respuestaSelect, 'estadoSelectAlta', 'Estado', 'Id_Estado',
    [{ 'nameAttr': 'attr_pais', 'valor': 'Id_Pais_Fk' }]
  );


  // $("#paisSelectAlta option[value=" + idPais + "]").attr("selected", true);

  document.getElementById('ciudadSelectAlta').innerHTML = '<option value="Seleccione uno...">Seleccione uno...</option>';


  $(".selectChosenAlta").chosen("destroy");
  $(".selectChosenAlta").chosen({
    width: "100%",
    no_results_text: "No se a encontrado resultados",
    allow_single_deselect: true,
  });

});



let idInputSelectCiudad = document.querySelector('#estadoSelectAlta');

// idInputSelectCiudad.addEventListener("change", async function () {
$('#estadoSelectAlta').on('change', async function () {


  var idCiudad = idInputSelectCiudad.value;

  respuestaSelect = await getInputSelect('ciudades', 'Id_Estado_Fk,Ciudad,Ver,Id_Ciudad', idCiudad)


  await insertDataSelect(respuestaSelect, 'ciudadSelectAlta', 'Ciudad', 'Id_Ciudad',
    [{ 'nameAttr': 'attr_estado', 'valor': 'Id_Estado_Fk' }]
  );

  $(".selectChosenAlta").chosen("destroy");
  $(".selectChosenAlta").chosen({
    width: "100%",
    no_results_text: "No se a encontrado resultados",
    allow_single_deselect: true,
  });

});


// ---Edit 
let idInputSelectPaisEdit = document.querySelector('#PaisProveedor');

// idInputSelectPaisEdit.addEventListener("change", async function () {
$('#PaisProveedor').on('change', async function () {


  var idPais = idInputSelectPaisEdit.value;

  let respuestaSelect = await getInputSelect('estados', 'Id_Estado,Id_Pais_Fk,Estado', idPais);

  await insertDataSelect(respuestaSelect, 'EstadoProveedor', 'Estado', 'Id_Estado',
    [{ 'nameAttr': 'attr_pais', 'valor': 'Id_Pais_Fk' }]
  );

  document.getElementById('CiudadProveedor').innerHTML = '<option value="Seleccione uno...">Seleccione uno...</option>';


  $(".selectChosenEdit").chosen("destroy");
  $(".selectChosenEdit").chosen({
    width: "100%",
    no_results_text: "No se a encontrado resultados",
    allow_single_deselect: true,
  });

});





let idInputSelectCiudadEdit = document.querySelector('#EstadoProveedor');

// idInputSelectCiudadEdit.addEventListener("change", async function () {
$('#EstadoProveedor').on('change', async function () {


  var idCiudad = idInputSelectCiudadEdit.value;

  respuestaSelect = await getInputSelect('ciudades', 'Id_Estado_Fk,Ciudad,Ver,Id_Ciudad', idCiudad)

  await insertDataSelect(respuestaSelect, 'CiudadProveedor', 'Ciudad', 'Id_Ciudad',
    [{ 'nameAttr': 'attr_estado', 'valor': 'Id_Estado_Fk' }]
  );


  $(".selectChosenEdit").chosen("destroy");
  $(".selectChosenEdit").chosen({
    width: "100%",
    no_results_text: "No se a encontrado resultados",
    allow_single_deselect: true,
  });

});

// -------------------------------- Btn Modal Alta Provedor
let btnModalConfirmarAltaProvedor = document.getElementById('btnModalConfirmarAltaProvedor');
let btnModalCancelarAltaProvedor = document.getElementById('btnModalCancelarAltaProvedor');
btnModalConfirmarAltaProvedor.addEventListener('click', () => {
  stringClass('formAltaProvedorDataInput');
  validarAllSections('formAltaProvedorDataInput', 'campoValidarAltaProvedor', 'modalAltaProvedor', 'campoValidarCaracteresAlta');
})



btnModalCancelarAltaProvedor.addEventListener('click', () => {

  $('#modalAltaProvedor').modal('hide')

})

// -------------------------------- Btn Modal Editar Provedor
let btnModalConfirmarEditarProvedor = document.getElementById('btnModalConfirmarEditarProvedor');
let btnModalCancelarEditarProvedor = document.getElementById('btnModalCancelarEditarProvedor');
btnModalConfirmarEditarProvedor.addEventListener('click', () => {
  stringClass('formEditarProvedorDataInput');
  // Formaulario a validar y clases de las secciones a validar     
  validarAllSections('formEditarProvedorDataInput', 'campoValidarEditarProvedor', 'modalEditarProvedor', 'campoValidarCaracteresEditar');
})



btnModalCancelarEditarProvedor.addEventListener('click', () => {
  $('#modalEditarProvedor').modal('hide')
})
let btnModalCancelarViewProvedor = document.getElementById('btnModalCancelarViewProvedor');

btnModalCancelarViewProvedor.addEventListener('click', () => {
  $('#modalViewProvedor').modal('hide')
})


////////////////////////////
function validarAllSections(Claseform, claseSection, idForm, classCaracteres) {



  let respSection1 = respValidar(claseSection + '_Sectio1');



  let respSection2 = respValidar(claseSection + '_Sectio2');





  $('#' + idForm + ' .' + classCaracteres).css({ 'border-color': '#ced4da', "border-weight": "0" });
  $('#' + idForm + ' .formNoReq_text_adv').css({ 'display': 'none' });
  let camposNoObligatorios = validarCaracteres(classCaracteres);
  let camposCorreosNoObligatorias = validarCorreosNoObligatorios(idForm);
  document.getElementById("ziseContentForm").style.height = "auto";
  document.getElementById("ziseContentForm2").style.height = "auto";





  if (respSection1 && respSection2 && camposNoObligatorios & camposCorreosNoObligatorias) {

    // Clase que contiene la info de los input a enviar a la base de Datos 

    getDataForm(Claseform)

  }

}
function getDataForm(claseFormInput) {



  let arrayAllInputs = document.querySelectorAll('.' + claseFormInput);



  // Inpus normales 

  let arrayInputsValues = [];

  // Select 

  let arrayInputsName = [];

  // checkboxs 

  let arrayInputCheckbox = [];





  // Obtiene los valores de los inputs 

  arrayAllInputs.forEach(input => {



    arrayInputsValues[input.id] = input.value;



  })



  // Obtiene el nombre del Pais, Estado y Ciudad 

  arrayAllInputs.forEach(input => {



    if ($('#' + input.id + ' option:selected').attr('name') != undefined) {



      var nameSelect = $('#' + input.id + ' option:selected').attr('name');



      arrayInputsName[input.id] = nameSelect;



    }



  })



  // Obtiene el valor de los checkbox 

  let valueStatus = null;



  arrayAllInputs.forEach(checkbox => {



    if (checkbox.checked) {



      valueStatus = '1';



    } else {



      valueStatus = '0';



    }



    arrayInputCheckbox[checkbox.id] = valueStatus;

  })





  if (claseFormInput == 'formEditarProvedorDataInput') {



    let idProvedorEditar = $('#btnModalConfirmarEditarProvedor').attr('attr_id');



    idProvedorEditar = Number(idProvedorEditar);



    updateProvedor(arrayInputsValues, arrayInputsName, arrayInputCheckbox, idProvedorEditar);



  }

  if (claseFormInput == 'formAltaProvedorDataInput') {



    insertarNuevoProvedor(arrayInputsValues, arrayInputsName)



  }



}

async function insertarNuevoProvedor(arrayInputs, arrayNames) {


  let comentario = 'Creo al Proveedor con el RFC= ' + arrayInputs['rfcAlta'];

  let accion = {
    'Accion': 'altaProvedor', 'data': {



      'Nombre': arrayInputs['nombreComercialAlta'],

      'ContactoPrincipal': arrayInputs['contactoPrincipalAlta'],

      'Telefono': arrayInputs['numeroTelefonoAlta'],

      'Ext': arrayInputs['extensionProvedorAlta'],

      'CorreoElectronico': arrayInputs['correoElectronicoAlta'],

      'RazonSocial': arrayInputs['razonSocialAlta'],

      'RFC': arrayInputs['rfcAlta'],

      'CalleProveedor': arrayInputs['calleYNumeroAlta'],

      'ColoniaProveedor': arrayInputs['coloniaAlta'],



      'NoCuenta': arrayInputs['noCuentaProvedorAlta'],

      'MonedaBckup': arrayInputs['MonedaBckupAltaProvedor'],



      'CPProveedor': arrayInputs['codigoPostalAlta'],

      'DiasCredito': arrayInputs['diasCreditoAlta'],

      'Id_Pais_Fk': arrayInputs['paisSelectAlta'],

      'Id_Estado_Fk': arrayInputs['estadoSelectAlta'],

      'Id_Ciudad_Fk': arrayInputs['ciudadSelectAlta'],



      'CiudadProveedor': arrayNames['ciudadSelectAlta'],

      'EstadoProveedor': arrayNames['estadoSelectAlta'],

      'PaisProveedor': arrayNames['paisSelectAlta'],

    }
  }



  let url = "Controlador/proveedorApi.php";



  return await fetch(url, {

    method: 'POST',



    body: JSON.stringify(accion),



    headers: { 'Content-Type': 'application/json' }

  }).then(res => res.json())

    .then(data => {

      switch (data["success"]) {
        case true:
          showAlert("Correcto", data["message"], "success")
          $('#modalAltaProvedor').modal('hide');
          setBitacora('1', comentario, modulo);

          break;
        case false:
          showAlert("Alerta", data["message"], "info")
          break;
        default:
          break;

      }

    })

    .then(data => {

      var tablaSSP = $('#tProveedores').DataTable();

      tablaSSP.ajax.reload();



    })



}






async function getDataProvedor(idUserEdit) {

  accion = { 'Accion': 'proveedores', 'Id': idUserEdit }

  return await fetch(rutaApi + '?Accion=proveedores&Id=' + idUserEdit + '&Tabla=proveedores', {

    method: 'GET',
    // body: JSON.stringify(accion),
    headers: { 'Content-Type': 'application/json' }

  }).then(respuesta => respuesta.json())

    .then(respuesta => {

      return respuesta['data']

    })

}


async function getDataProvedorV(idUserEdit) {

  accion = { 'Accion': 'proveedores', 'Id': idUserEdit }

  return await fetch(rutaApi + '?Accion=proveedores&Id=' + idUserEdit + '&Tabla=proveedores&Status=1', {

    method: 'GET',
    // body: JSON.stringify(accion),
    headers: { 'Content-Type': 'application/json' }

  }).then(respuesta => respuesta.json())

    .then(respuesta => {

      return respuesta['data']

    })

}

async function updateProvedor(arrayInputs, arrayNames, arrayInputCheckbox, idProvedorEditar) {
  let comentario = 'Edito al Proveedor con la Id= ' + idProvedorEditar;

  let accion = {
    'Accion': 'UpdateProvedor', 'Id': idProvedorEditar, 'data': {
      'Nombre': arrayInputs['Nombre'],

      'ContactoPrincipal': arrayInputs['ContactoPrincipal'],

      'Telefono': arrayInputs['Telefono'],

      'Ext': arrayInputs['Ext'],

      'CorreoElectronico': arrayInputs['CorreoElectronico'],

      'RazonSocial': arrayInputs['RazonSocial'],

      'RFC': arrayInputs['RFC'],

      'CalleProveedor': arrayInputs['CalleProveedor'],

      'ColoniaProveedor': arrayInputs['ColoniaProveedor'],



      'NoCuenta': arrayInputs['NoCuenta'],

      'MonedaBckup': arrayInputs['MonedaBckup'],



      'Status': arrayInputCheckbox['Status'],

      'CPProveedor': arrayInputs['CPProveedor'],

      'DiasCredito': arrayInputs['DiasCredito'],

      'Id_Pais_Fk': arrayInputs['PaisProveedor'],

      'Id_Estado_Fk': arrayInputs['EstadoProveedor'],

      'Id_Ciudad_Fk': arrayInputs['CiudadProveedor'],



      'CiudadProveedor': arrayNames['CiudadProveedor'],

      'EstadoProveedor': arrayNames['EstadoProveedor'],

      'PaisProveedor': arrayNames['PaisProveedor'],

    }
  }



  let url = "Controlador/proveedorApi.php";



  return await fetch(url, {

    method: 'POST',



    body: JSON.stringify(accion),



    headers: { 'Content-Type': 'application/json' }

  }).then(res => res.json())

    .then(data => {

      switch (data["success"]) {



        case true:



          showAlert("Correcto", data["message"], "success")

          $('#modalEditarProvedor').modal('hide');
          setBitacora('4', comentario, modulo);



          break;



        case false:



          showAlert("Alerta", 'Sin Cambios')

          break;



        default:



          break;

      }

    })

    .then(data => {

      var tablaSSP = $('#tProveedores').DataTable();

      tablaSSP.ajax.reload();



    })



}


$('.btnShowModalAlta').on('click', () => {

  resetearWizard('smartwizard');
  document.getElementById('formAltaProvedor').reset();

  $('#modalAltaProvedor').modal('show');

   resetFormAlert();

  //  document.getElementById('paisSelectAlta').innerHTML=`<option value="">Seleccione uno...</option>`;
   document.getElementById('estadoSelectAlta').innerHTML=`<option value="">Seleccione uno...</option>`;
   document.getElementById('ciudadSelectAlta').innerHTML=`<option value="">Seleccione uno...</option>`;

   $('.selectChosenAlta').val('').trigger('chosen:updated');

   
   $(".selectChosenAlta").chosen("destroy");
  $(".selectChosenAlta").chosen({
    width: "100%",
    no_results_text: "No se a encontrado resultados",
    allow_single_deselect: true,
});

})

// -----------------------------------------------------Modal Actualizar


$('#tProveedores tbody').on('click', '.btnEditarTabla', function (e) {

  let id = getIdBtn(this)

  
  initModalEdit(id);

});
$('#tProveedores tbody').on('click', '.btnView', function (e) {

  let id = getIdBtn(this)

  initModalView(id);

});

const initModalEdit = async (id) => {

  initModal('modalEditarProvedor', 'formEditarProvedor', 'campoValidarEditarProvedor_Sectio1', 'btnModalConfirmarEditarProvedor', id)

  resetearWizard('smartwizard2');

  let respuestaForm = await getDataProvedor(id);

  await insertarDataInput(respuestaForm, 'formEditarProvedor');

  $(".selectChosenEdit").chosen("destroy");
  $(".selectChosenEdit").chosen({
    width: "100%",
    no_results_text: "No se a encontrado resultados",
    allow_single_deselect: true,
  });
  
}

const initModalView = async (id) => {

  initModalV('modalViewProvedor', 'formViewProvedor', 'campoValidarViewProvedor_Sectio1')

  resetearWizard('smartwizard3');

  let respuestaForm = await getDataProvedorV(id);
  // console.log(respuestaForm);
  insertarDataInputView(respuestaForm, 'formViewProvedor');

}


const getIdBtn = (event) => {

  let idString = $(event).attr('id');

  return idString.substring(2);

}


const initModal = async (idModal, idFormReset, claseValidar, idBtnAceptar, idUser) => {

  $(`#${idModal}`).modal(`show`);

  resetAdvertenciasInputs(idFormReset, claseValidar);

  document.querySelector(`#${idBtnAceptar}`).setAttribute('attr_id', idUser);

}
const initModalV = async (idModal, idFormReset, claseValidar) => {

  $(`#${idModal}`).modal(`show`);

  resetAdvertenciasInputs(idFormReset, claseValidar);

  // document.querySelector(`#${idBtnAceptar}`).setAttribute('attr_id', idUser);

}

const resetAdvertenciasInputs = (idFormReset, claseValidar) => {

  $(`#${idFormReset} .form_text_adv`).css({ 'display': 'none' })

  $(`#${idFormReset} .${claseValidar}`).css({ 'border-color': '#ced4da', 'border-weight': '0' });

}



let dataExcel={
  idBtnExcel:'btnExcelTabla',
  nameFile:'Proveedores',
  urlApi:rutaApi,
  accion:`?Accion=proveedores&getDataExcel=1&Tabla=proveedores`,
  // columnasExcel:['F3','I3'],
  urlVendor:'../../requerimientos/vendors/spreadsheet/spreadsheetExcel.php'
}

let excelTabla = new exportarExcelTabla(dataExcel);

const resetFormAlert=()=>{

  $(".form_text_adv").css({ 'display': 'none' })

  $(".formAltaProvedorDataInput").css({ 'border-color': '#ced4da', "border-weight": "0" });

}