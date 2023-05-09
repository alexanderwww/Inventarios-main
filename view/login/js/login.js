
const URLLOGIN = '../inventario/index.php';
$('.btnLogin').click((event)=>{

    event.preventDefault();

    if(validarDatos('getDataForm')){

        procesarData();

    }

})

const validarDatos=(clase)=>{
    
    if(validar(clase)){

        if(validarCaracteres(clase)){
            return true
        }
        return false

    }
    return false

}

const procesarData=async()=>{

    data=await getDataForms('getDataForm')

    dataLogin(data);

}

const getDataForms=async (clases)=>{

    arrayDataForm=document.querySelectorAll('.'+clases);

    arrayData=[];

    arrayDataForm.forEach(element => {
        
        arrayData[element.id]=element.value;

    });



    return arrayData;

}


async function dataLogin(arrayData){

    let accion = {

        'Accion': 'dataLogin',
        'data':{'usuario':arrayData['usuario'],'password': arrayData['password']}
    };

    url = "controlador/loginApi.php";

    return await fetch(url,{

        method: 'POST',

        body: JSON.stringify(accion),

        headers: {'Content-Type': 'application/json'}
    
    }).then(res=>res.json())
    .then(resp=>{
        
        if(resp['success']){

            window.location.href = URLLOGIN;
        }else{

            mostrarMsAlert();

        }


    })

}


const mostrarMsAlert=()=>{

    document.querySelector('#ul_password').style.display='block';
    document.querySelector('#ul_usuario').style.display='block';

    document.querySelector('#ul_usuario').textContent='';
    document.querySelector('#ul_password').textContent='Contraseña o usuario incorrecto';

}

// ----------------------------------------------------------------- 

$('#btnRegistrar').click(()=>{

    procesarDataAcount('getDataAcount');

});

const procesarDataAcount=async clase=>{
 
    stringClass(clase)

    if(validarDatos(clase)){

        arrayDataInput=await getDataForms(clase);

    }

}

