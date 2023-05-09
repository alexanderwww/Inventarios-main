  const rutaApi2 = '../../api/';
  const rutaApi = '../../api/api.php';
  const TCGlobal=document.getElementById('inputValueTc').value;
  const monedaGlobal=document.getElementById('checkTipoDeMoneda');


  $(document).ready(function () { //Informacion al cargar la pagina
    $('#TC_input').blur(function(){
      let TC = $(this).val();
      updateTC(TC);
    });
    
    mascarMoneda()
      new statusMoneda()
})
function updateTC(TC){
  let checkTipoDeMoneda = document.getElementById('checkTipoDeMoneda');
  let checked = checkTipoDeMoneda.checked;
  if (checked) {
    // Dolares
    cambiarTC(TC,1)
    // console.log('Activo')
  } else {
    // Pesos 
    cambiarTC(TC,0)
    // console.log('Inactivo')
  }
}

async function cambiarTC(TC,status){


  let accion = { 'Accion': 'general', 'status': status,  'TC':TC}; //Pesos=0 y dolares=1

  return (await fetch(rutaApi, {
    method: 'POST',
    body: JSON.stringify(accion),
    headers: {
      'Content-Type': 'application/json'
    }

  }).then(respuesta => respuesta.json())

    .then(respuesta => {
      reloadTableTipoDeMoneda()
      return respuesta;

    })

  )

}
async function resetTC(){


  // let accion = { 'Accion': 'general', 'status': status,  'TC':TC}; //Pesos=0 y dolares=1

  return (await fetch(rutaApi+'?Accion=general&Status=1', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    }

  }).then(respuesta => respuesta.json())

    .then(respuesta => {
      reloadTableTipoDeMoneda()
      return respuesta;

    })

  )

}

  function setAlertNotify(msg,texto,alerta){



    objetoAlerta={



      title:msg,



      text:texto,



      type:alerta,



      styling:'fontawesome',



      delay: 1300







    }



  }



  //uso de alertPNotify  Funcion principal de mostrar alerta



  function showAlert(msg,texto,alerta){



    setAlertNotify(msg,texto,alerta);



    llamarConstructorNNotify();



  }



  //uso de alertPNotify Llama el constructor de alertPNotify



 function llamarConstructorNNotify(){



    new PNotify(objetoAlerta);



  }



  //uso de alertPNotify Se crea el objeto alerta



 function setObjalerta(alert){



    alerta=alert;



  }



  function sobreinput(eve){



    if (document.querySelector(`#ul_${eve.target.id}`)) {



        var ul=document.querySelector(`#ul_${eve.target.id}`);


        ul.style.display="none";



        $("#"+eve.target.id).css({'border-color':'rgba(92, 106, 124, 0.3)'})



    }


  }



  function stringClass(clases){



        let toProperCase=(str=>{

            return str.replace(/\w\S*/g, function (txt) {

                    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();

            })

        })



        let toNumber=(str=>{

            return str.replace (/[^a-zA-Z0-9]+/g, '');

        })



        let codCaracteresEspeciales=(data=>{

            return data.replaceAll("'", "\\'");

        })



        // -------------------------------



        let arrayClases=document.querySelectorAll('.'+clases)



        arrayClases.forEach(input=>{



            let arrayClasesInput=input.classList;



            arrayClasesInput.forEach(claseInput=>{



                switch (claseInput) {



                    case "properTrim":



                        document.getElementById(input.id).value=toProperCase(input.value.trim());

                        break;



                    case "upperTrim":



                        document.getElementById(input.id).value=input.value.toUpperCase().trim();





                        break;



                    case "properSinTrim":



                        document.getElementById(input.id).value=toProperCase(input.value);



                        break;



                    case "trim":



                        document.getElementById(input.id).value=input.value.trim();



                        break;

                    case "lowerTrim":



                        document.getElementById(input.id).value=input.value.toLowerCase().trim();



                        break;

                    case "toNumber":



                        document.getElementById(input.id).value=toNumber(input.value);



                        break;



                    case "urlEncoded":



                        document.getElementById(input.id).value=encodeURIComponent(input.value);



                        break;



                    case "caracterEspecial":



                        document.getElementById(input.id).value=codCaracteresEspeciales(input.value);



                        break;



                }





            })



        })





  }



  function  validar(clase) {



    let inputs = document.querySelectorAll('.' + clase);



    let arrayResult = [];



    let listaValidar = [];



    var resultado = "";



    for (let input of inputs) {



      listaValidar.push(input.id);



    }



    for (let id of listaValidar) {



      if (($("#" + id).val()) == "" && $("#" + id).attr("type")!=='file') {



        $("#" + id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });



        $("#ul_" + id).css({ 'display': 'block' });



        $("#ul_" + id).text('Se requiere campo.');



        arrayResult.push(false);



      } else {



        switch ($("#" + id).attr("type")) {



            case "text":



            if ($("#" + id).val() == "") {



              $("#" + id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });



              $("#ul_" + id).css({'display': 'block' });



              $("#ul_" + id).text('Se requiere Campo');



              arrayResult.push(false);



            } else {



                $("#" + id).css({ 'border-color': '#ced4da',"border-weight": "0" });



                $("#ul_"+id).css({'display':'none'})



                arrayResult.push(true);



            }



            break;



            case "hidden":



            if ($("#" + id).val() == "") {



              $("#" + id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });



              $("#ul_" + id).css({'display': 'block' });



              $("#ul_" + id).text('Se requiere Campo');



              arrayResult.push(false);



            } else {



                $("#" + id).css({ 'border-color': '#ced4da',"border-weight": "0" });



                $("#ul_"+id).css({'display':'none'})



                arrayResult.push(true);



            }



            break;



            case "checkbox":



            if($("#" + id).prop('checked')){



              arrayResult.push(true);



            }else{



              $("#" + id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });



              $("#ul_" + id).css({'display': 'block' });





              $("#ul_" + id).text('No se a seleccionado');



              arrayResult.push(false);



            }



            break;



            case "number":

              if(!isNaN($("#" + id).val())){



                $("#" + id).css({ 'border-color': '#ced4da',"border-weight": "0" });



                $("#ul_"+id).css({'display':'none'})



                arrayResult.push(true);



              }else{



                $("#" + id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });



                $("#ul_" + id).css({'display': 'block' });



                $("#ul_" + id).text('Se requiere Campo');



                arrayResult.push(false);



              }



              break;



            case 'select':



                if ($("#" + id).val() == "Seleccione uno...") {



                    $("#" + id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });



                    $("#ul_" + id).css({'display': 'block' });



                    $("#ul_" + id).text('Se requiere Campo');



                    arrayResult.push(false);



                    } else {



                        $("#" + id).css({ 'border-color': '#ced4da',"border-weight": "0" });



                        $("#ul_"+id).css({'display':'none'})



                        arrayResult.push(true);



                    }

            break;



            case 'email':



                emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;



                if (!emailRegex.test($("#" + id).val())) {



                    $("#" + id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });



                    $("#ul_" + id).css({'display': 'block' });



                    $("#ul_" + id).text('Se requiere correo valido');



                    arrayResult.push(false);



                }

                else {



                    $("#" + id).css({ 'border-color': '#ced4da',"border-weight": "0" });



                    $("#ul_"+id).css({'display':'none'})



                    arrayResult.push(true);



                }



            break;



            case 'file':



                if($('#'+id).val()!==''){



                    var filePath = $('#'+id).val();

                    var allowedExtensions = /(.jpg|.jpeg|.png)$/i;



                    if(!allowedExtensions.exec(filePath)){



                        // 'Extensión no permitida. Utiliza: .jpeg/.jpg/.png'

                        $("#" + id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });



                        $("#ul_" + id).css({'display': 'block' });





                        $("#ul_" + id).text('Formato del archivo no es aceptado (acepta png, jpg, jpeg)');



                        $('#'+id).val('');



                        arrayResult.push(false);



                    }else{

                        $("#ul_"+id).css({'display':'none'})



                        arrayResult.push(true);

                    }



                }else{



                    $("#ul_"+id).css({'display':'none'})



                    arrayResult.push(true);



                }



                break;



            case 'password':



                if( $("#" + id).val().length<3){



                    //    'pass incorrec

                    $("#" + id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });



                    $("#ul_"+id).css({'display':'block'})



                    $("#ul_" + id).text('Se requiere Campo');





                    arrayResult.push(false);



                }else{

                    // pass correct

                    $("#" + id).css({ 'border-color': '#ced4da',"border-weight": "0" });



                    $("#ul_"+id).css({'display':'none'})



                    arrayResult.push(true);

                }



            break;



        }



        arrayResult.push(true);



      }



    }



    for (var resultados of arrayResult) {



      if (resultados == false) {



        resultado = false;



        break;



      } else {



        resultado = true;



      }



    }



    return resultado;



  }



  function validarCaracteres(clase){
    


    let Listainputs = document.querySelectorAll('.' + clase);


    Listainputs.forEach(input=>{


      $("#" + input.id).css({ 'border-color': '#ced4da',"border-weight": "0" });



      $("#ul_"+input.id).css({'display':'none'})



    })

    let listaId = [];



    let arrayRespuestaCaratr=[];





    for (let input of Listainputs){listaId.push(input.id)}



    for (let id of listaId) {



        let text=$("#" + id).val();



        // -------------------------------------------------

        const listaCaracteres=['"',"'",'/'];



        let arrayText=text.split('');



        let listaSimbolos=[];



        arrayText.map(letra=>{



            let respuestaVal=listaCaracteres.every(caracter=>caracter!=letra)





            if(respuestaVal==false){



                listaSimbolos.push(false)



            }



        })



        // -------------------------------------------------



        if(listaSimbolos.length==0){

            // ('todo Limpio')

            arrayRespuestaCaratr.push(true);



        }else{

            $("#" + id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });



            $("#ul_" + id).css({ 'display': 'block' });



            $("#ul_" + id).text(`Caracteres no permitidos: [ ${listaCaracteres} ] `);





            arrayRespuestaCaratr.push(false);





        }





    }



    // Si todos los element cumplen la condicion nos devuelve true

    let respCaractr=arrayRespuestaCaratr.every(resp=> resp==true);





    return respCaractr;



  }






const mascaraPesos=(clase)=>{
    arrayData=document.querySelectorAll('tbody .'+clase);

    arrayData.forEach(data=>{

        num=formatterDolar.format(data.textContent);

        data.innerText=num;

    })

}


const formatterDolar = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
})


// Formato a enviar: 2022-12-16 14:32:37
const mascaraFechaVistaTabla=(clase)=>{

    arrayDataFechas=document.querySelectorAll('tbody .'+clase);
    arrayDataFechas.forEach(data=>{
            dataFecha=separarStringFecha(data.textContent,' ',0);

            fechaVista=formatoFechaApi(dataFecha);

            data.innerText=fechaVista;


    })


}


// Formato a enviar: '25/12/2022'
const formatoFechaBDD=(dataFecha,separador='/')=>{


    fecha=dataFecha.split(separador);

    y=fecha[2]

    m=fecha[1];

    d=fecha[0]

    fecha=y+'-'+m+'-'+d;

    return fecha;

    // repuesta: '2022-12-25'
}

// Templeta Xml campo Fecha
// Formato a enviar: '2022-06-24 13:55:00'
const formatoFechaApi=(dataFecha,separador='-',separadorVista='/')=>{

    fecha=dataFecha.split(separador);

    d=fecha[2]

    m=fecha[1];

    y=fecha[0]

    fecha=d+separadorVista+m+separadorVista+y;

    return fecha;

    // repuesta: '24/06/2022'
}


function separarStringFecha(text,separador='_',numberData=1){

    var text=text.split(separador);

    return text[numberData];

}
async function setBitacora(codigo, comentario, modulo){

  var accion = {'Accion': 'bitacora', 'codigo': codigo, 'comentario': comentario, 'modulo': modulo}

  return await fetch(rutaApi, {

      method:'POST',

      body: JSON.stringify(accion),

      headers:{ 'Content-Type': 'application/json'}

  }).then(res => res.json())

  .then(res=>{

      // console.log(res);

  })



}
const sobreSelectData=(event)=>{
    
  id=event.target.id;
  
  $("#" + id).css({ 'border-color': '#ced4da',"border-weight": "0" });

  $("#ul_"+id).css({'display':'none'})


}


const addCommas=(n)=>{
    
  var parts = n.toString().split(".");
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  result= parts.join(".");
  return result;

}

// function mascarMoneda(number) {   
//   return number.toFixed(3).replace(/\D/,'').replace(/\B(?=(\d{3})+(?!\d))/g,',') 
// }
function mascarMoneda() {
  var mskPesos = document.getElementsByClassName("mask-pesos");
  // console.log(mskPesos);
    for (var imskpe = 0; imskpe < mskPesos.length; imskpe++) {
      new IMask(mskPesos[imskpe], {
        mask: Number,
				scale: 2,
				padFractionalZeros: true,
        thousandsSeparator: ',',
				radix: '.',
				mapToRadix: ['.']
      });}
}
function mascarMonedaInputs(number) {
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(number);
}
class exportarExcelTabla {

  constructor(objExcel) {

      this.btnExcel = document.getElementById(objExcel.idBtnExcel);
      this.nameFile = objExcel.nameFile;
      this.urlApi = objExcel.urlApi;
      this.columnasExcel=objExcel.columnasExcel;
      this.accion=objExcel.accion;;
      this.urlVendor=objExcel.urlVendor;

      this.nameTitle;
      this.usuario;
      this.dataExcel;

      this.statusExportarExcel();
  }

  statusExportarExcel() {

      this.btnExcel.addEventListener('click', () => {

          this.btnExcel.setAttribute('disabled', true);

          this.usuario = this.btnExcel.getAttribute('name');
          this.nameTitle = this.btnExcel.getAttribute('nameTable');

          this.getDataExcel();

          setTimeout(() => { this.btnExcel.removeAttribute('disabled') }, 3000);

      })
  }

  async getDataExcel() {

      await fetch(this.urlApi + this.accion, {

          method: 'GET',

          headers: { 'Content-Type': 'application/json' }

      })
          .then(response => response.json())
          .then(response => {

              if (response['success']) {

                  // console.log(response)

                  this.dataExcel = response['data'];

                  this.descargarExcel()

              }

          })

  }

  createInputForm = (name, value) => {

      let inputCreateForm = document.createElement("input");

      inputCreateForm.type = "hidden";

      inputCreateForm.name = name;

      inputCreateForm.value = value;

      return inputCreateForm;
  }

  descargarExcel = () => {

      let urlExportarExcel = this.urlVendor;

      let form = document.createElement("form");

      form.setAttribute("method", "post");

      form.setAttribute("action", urlExportarExcel);

      form.appendChild(this.createInputForm('datosExcel',JSON.stringify(this.dataExcel)));
      form.appendChild(this.createInputForm('nameFile',this.nameFile));
      form.appendChild(this.createInputForm('nameTitle',this.nameTitle));
      form.appendChild(this.createInputForm('nameCreator',this.usuario));

      
      if(this.columnasExcel){
          form.appendChild(this.createInputForm('columnasExcel',JSON.stringify(this.columnasExcel)));
      }

      document.body.appendChild(form);

      form.submit();

      document.body.removeChild(form);

  };

}

// function updateTC(TC){
//   console.log(TC);
// }

class statusMoneda {

  constructor() {
    this.checkTipoDeMoneda = document.getElementById('checkTipoDeMoneda');
    this.initStatusCheck()
    this.cambiarMoneda;
    
    this.btnResetTipoDeCambio=document.getElementById('btnResetTipoDeCambio');
    this.initStatusButtonReset();
  }

  initStatusButtonReset(){
    this.btnResetTipoDeCambio.addEventListener('click',()=>{
      
      let TipoDeCambioInit=document.getElementById('inputValueTc').value;
      
      $('#TC_input').val(TipoDeCambioInit).trigger('blur');

    })
  }
  
  
  initStatusCheck() {
    this.checkTipoDeMoneda.addEventListener("change", this.statusCheckTipoMoneda, false);
  }

  statusCheckTipoMoneda() {
    let checkTipoDeMoneda = document.getElementById('checkTipoDeMoneda');

    document.querySelector('.toggle-switch').style.opacity = '0.5';
    checkTipoDeMoneda.setAttribute('disabled', true);

    setTimeout(() => {
      checkTipoDeMoneda.removeAttribute('disabled')
      document.querySelector('.toggle-switch').style.opacity = '1'
    }, 4000)


    let checked = checkTipoDeMoneda.checked;
    if (checked) {
      // Dolares
      cambiarMoneda(1)
      // console.log('Activo')
    } else {
      // Pesos 
      cambiarMoneda(0)
      // console.log('Inactivo')
    }

  }


}


async function cambiarMoneda(status){

  let accion = { 'Accion': 'general', 'status': status }; //Pesos=0 y dolares=1

  return (await fetch(rutaApi, {
    method: 'POST',
    body: JSON.stringify(accion),
    headers: {
      'Content-Type': 'application/json'
    }

  }).then(respuesta => respuesta.json())

    .then(respuesta => {
      reloadTableTipoDeMoneda()
      return respuesta;

    })

  )

}


const reloadTableTipoDeMoneda=()=>{

  resetTablas();

}