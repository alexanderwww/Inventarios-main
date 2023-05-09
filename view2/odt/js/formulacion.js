$(document).ready(function() { //Informacion al cargar la pagina
    
    $('#titlePage').text('Formulación');

    initSection();

    statusContainerTable(false);

})

const resetTablas=()=>{
    
    statusContainerTable(false);
    $('#Producto_alta').val('').trigger('chosen:updated');
    $('#UsuarioAsignado_alta').val('').trigger('chosen:updated');

}
const modulo = 6;
var arrayItemsSelectProductos;

const initSection=async()=>{

    let dataSelectProductos=await methodGetData('?Accion=odt&Tabla=productos&Select=1');

    arrayItemsSelectProductos=dataSelectProductos['items'];

    insertDataSelect(dataSelectProductos['items'],'Producto_alta','Nombre','Id',
    [{'nameAttr':'attr_InventarioActual','valor':'InventarioActual'}]
    )

    let dataSelectUsuarios=await methodGetData('?Accion=usuarios&Tabla=user_accounts&Select=1');

    insertDataSelect(dataSelectUsuarios['data'],'UsuarioAsignado_alta','Name','Id');
    
    // $('#Producto_alta').select2();
    // $('#UsuarioAsignado_alta').select2();
        $("#Producto_alta").chosen({
        no_results_text: "No se a encontrado resultados",
        allow_single_deselect: true,
      });
      $("#UsuarioAsignado_alta").chosen({
        no_results_text: "No se a encontrado resultados",
        allow_single_deselect: true,
      });
    
}





// ----------------------------------------- 
$('#CantidadFabricar_alta').on('keyup',async()=>{


    valueInput=document.getElementById('CantidadFabricar_alta').value;


    if(valueInput.includes(".")){

        valueInput=document.getElementById('CantidadFabricar_alta').value='';

        showAlert("Alerta",'Solo valores enteros',"info")
    }



    if(statusTableActive()){

        idTable=statusTableActive();

        await statusInventario(idTable);

        await litroPorProduccion(`#tablaTabs_${idTable}`);

       await getImportes(`#tablaTabs_${idTable}`);

        // await limpiarStatusImporte(`#tablaTabs_${idTable}`);

    }
    
})

const limpiarStatusImporte=async(tablaActive)=>{
    
    let arrayInputsLitros=document.querySelectorAll(`${tablaActive} tbody tr`);

    arrayInputsLitros.forEach(items=>{
        
        items.querySelector('.costoLitroItems').value='';

        items.querySelector('.importeItems').value='';

    });
    return;
}

// FUNCION CAMBIOS INPUTS 
const getImportes=async(tablaActive)=>{

    arrayInputsLitros=document.querySelectorAll(`${tablaActive} tbody tr`);
    var totalImportes=0;
    var totalLitros=0;
    arrayInputsLitros.forEach(items=>{

        importes=convertNumber(items.querySelector('.importeItems').value);
        totalImportes += importes;

        litros=items.querySelector('.litrosProducionItems').value;
        totalLitros += convertNumber(litros);
    
    });
    
    let precioBarril = ((totalImportes/totalLitros)*200).toFixed(2);
    totalImportes= totalImportes.toFixed(2);

    // document.getElementById('Costo_alta').value=totalImportes.toString()
    document.getElementById('Costo_alta').value=convertPrecio(totalImportes)
    
   if(!isNaN(precioBarril)){

    let numero = separarString(tablaActive,'_',1);
    // document.getElementById(`CostoBarrilItem_${numero}`).value=precioBarril.toString();
    document.getElementById(`CostoBarrilItem_${numero}`).value=convertPrecio(precioBarril);

   }
    return precioBarril;
};



// FUNCION CAMBIOS INPUTS INVENTARIO
const statusInventario=async(numContainerActive)=>{

    if($('#Producto_alta').val()){


        let cantidadAFabricar=document.getElementById('CantidadFabricar_alta').value;

        let cantidadInventario=document.querySelector(`#InventarioActualItem_${numContainerActive}`).value;
 
        cantidadFinal=convertNumber(cantidadInventario)+convertNumber(cantidadAFabricar);
    
        if($('#CantidadFabricar_alta').val()){


            if(cantidadFinal>=0){

                // VALORES A MOSTRAR 
                document.getElementById(`InventarioDespuesItem_${numContainerActive}`).value=convertPrecio(cantidadFinal.toString());

            }else{
                showAlert("Alerta",'Inventario actual insuficiente',"info")

                document.getElementById('CantidadFabricar_alta').value='';
                // document.getElementById('InventarioDespues_alta').value='';
                document.getElementById(`InventarioDespuesItem_${numContainerActive}`).value='';

            }


        }

    }

}



$('#Producto_alta').on('change',async function(){

    if($('#Producto_alta option:selected').attr('attr_InventarioActual')){

        // let inventarioActual=$('#Producto_alta option:selected').attr('attr_InventarioActual');

        // document.getElementById('InventarioActual_alta').value=inventarioActual;

        await statusContainerTable(true);

        await initVersionesProducto(this.value);
        
        statusInventario(statusTableActive());



    }else{

        // document.getElementById('InventarioActual_alta').value='';
        // document.getElementById('InventarioDespues_alta').value='';
        document.getElementById(`InventarioActualItem_${statusTableActive()}`).value='';
        document.getElementById(`InventarioDespuesItem_${statusTableActive()}`).value='';

        statusContainerTable(false);
        
        dataItemsFormulados=false;

    }
    
})



const statusContainerTable=async(status)=>{

    document.querySelector('.containerButtonTabs').innerHTML='';
    document.querySelector('#containerTablesTabs').innerHTML='';

    if(!status){

        document.querySelector('.containerPrincipalTabs').style.display='none';
        document.querySelector('.containerPrincipalTabs').setAttribute('attr_StatusTable','0');

    }else{

        document.querySelector('.containerPrincipalTabs').style.display='block';
        document.querySelector('.containerPrincipalTabs').setAttribute('attr_StatusTable','1');

    }
    return;

}

const insertDataSelect = async(data, idSelet, texto, identificador, arrayAttr = null) => {

    let inputSelect = document.getElementById(idSelet);

    let respArrayAttr = arrayAttr == null ? false : true;

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


const insertDataAllSelectValue =async (claseGetData) => {

    let arrayinputSelect = document.querySelectorAll(claseGetData);
    

    arrayinputSelect.forEach(select=>{

        select.value=select.getAttribute('attr_valuedefaul');


    })

    return;

}

const clearTable=(idTable)=>{

    var table = $('#'+idTable).DataTable(); 
    // table.destroy();

    table
    .clear()
    .draw();
}


const disabledSelect =async (claseGetData) => {

    let arrayinputSelect = document.querySelectorAll(claseGetData);
    

    arrayinputSelect.forEach(select=>{

        select.setAttribute('disabled',true);

    })

    return;

}


// -------------------------------------------------------------------------------------------------Funciones



const getDataForms=async(claseInpustData,separador)=>{

    let arrayInputsForm=document.querySelectorAll('.'+claseInpustData);
    // console.log(arrayInputsForm);
    let arrayData=[];

    arrayInputsForm.forEach(input=>{
            // console.log(input);
            nombreInput=separarString(input.id,separador,0);
            // console.log(nombreInput + input.value);
            arrayData[nombreInput]=input.value;

    })

    // console.log(arrayData);
    return arrayData;

}

const separarString=(text,separador,numberData)=>{

    var text=text.split(separador);

    return text[numberData];

}





const getIdBtn=(event)=>{

    let idString = $(event).attr('id');

    return idString.substring(2);

}





const getDataInputsForms=async (claseGetData)=>{

    let arrayInpust=document.querySelectorAll('.'+claseGetData)

    let arrayDataForm=[];

    arrayInpust.forEach(input =>{

        arrayDataForm[input.id]=document.getElementById(input.id).value;
 
    });

    return arrayDataForm;

}



// -------------------------------------------------------------------------------------------------Alta

$('.btnAceptarAlta').on('click',async()=>{

    
        if(respValidar('validarDataAlta')){
            
        
            if($('#CantidadFabricar_alta').val()==0){

                showAlert("Alerta",'La cantidad a fabricar debe de ser mayor a 0',"info");
                return;
            }

            if(!soloNumeros($('#CantidadFabricar_alta').val())){
                showAlert("Alerta",'La cantidad a fabricar debe ser solo números',"info");
                return;
            }

            if(!validarInputsTable(`#tablaTabs_${statusTableActive()} .validarDataAltaItems`)){
    
                // console.log('Entro validacion input')

                showAlert("Alerta",'Completar todos los campos de la tabla',"info");
                return;
            }


            if(!validacionSelectTableActive()){
                // console.log('Entro validacion selects')

                showAlert("Alerta",'Completar todos los campos de la tabla',"info");
                return;
            }

            if(!validacionPorcentajeNumberTablaActiva()){

                showAlert("Alerta",'Campo porcentaje de la formulación debe ser solo números',"info");
                return;

            }

            if(!validacionPorcentajeTablaActiva()){

                showAlert("Alerta",'El porcentaje de la formulación debe ser del 100%',"info");
                return;

            }

            if(!validarCerosPorcentaje()){

                showAlert("Alerta",'El porcentaje de las filas debe ser mayor del 0%',"info");
                return;
            }
            // Enviar los datos de los items 
            initAltaData();


    
        };

})




const getDataInputsTable=async(claseInpustData,separador)=>{

    let arrayInputsForm=document.querySelectorAll(`#tablaTabs_${statusTableActive()} tbody tr`);

    let arrayData=[];

    // Array donde almacena los datos de las filas a enviar
    let arrayDataItem=[];

    // Iteremos las "FILAS DE LA TABLA"
    arrayInputsForm.forEach(fileItem=>{        

        // Seleccionamos de la fila todos los items que tenga la clase
        arrayDataInputTable=fileItem.querySelectorAll('.'+claseInpustData);

        // Limpia el array 
        arrayDataItem=[];


        if(fileItem.querySelector('.formAltaDataItems')){

            // Almacenamos el Id del producto 
            arrayDataItem['IdProducto']=fileItem.querySelector('.formAltaDataItems').value;

        }
        
        // Iteramos todos los "ITEMS DENTRO DE LA FILA" que tenga la clase que le pasamos 
        arrayDataInputTable.forEach(input=>{

            // Example:
            // Asi indentificamos y si se agrego una nueva Fila 
            // idItemPORDEFAUTL = separarString('litrosBarril_7','_',1) = 1
            // idItemAGREGADOS = separarString('litrosBarril_1Nuevo','_',1) = 1Nuevo
            idItem=separarString(input.id,separador,1);


            // Example:
            // idItemPORDEFAUTL = separarString('litrosBarril_7','_',1) = litrosBarril
            // idItemAGREGADOS = separarString('litrosBarril_1Nuevo','_',1) = litrosBarril
            nombreInput=separarString(input.id,separador,0);

            // Obtenemos el nombre del input y lo almacenamos y le agregamos el valor
            arrayDataItem[nombreInput]=input.value;
            arrayDataItem['Id']=idItem;

        })



        arrayData.push(arrayDataItem);


    })


    // console.log(arrayData);
    // arrayData.splice(0, 1);

    return arrayData;

}




const sobreinputItems=(event)=>{
    
    $("#"+event.target.id).css({'border-color':'rgba(92, 106, 124, 0.3)'})

};

const validarInputsTable=(getClass)=>{

    let arrayValidar=document.querySelectorAll(getClass);

    let respuestaValidacion=true;

    arrayValidar.forEach(input=>{

        if(input.value){

            valueStrign=convertNumber(input.value);

            if(isNaN(valueStrign) || valueStrign==NaN){
                // if((valueStrign)){

                $("#" + input.id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });

                respuestaValidacion=false;
            }

        }else{

            $("#" + input.id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });

            respuestaValidacion=false;
        }

    })

    return respuestaValidacion;

}



// -------------------------------------------------------------Checks Formulacion 



const initAltaData=async()=>{

    // Array de los datos del formulario Principal 
    let arrayDataProducto=await getDataForms('formAltaData','_alta');
    // console.log(arrayDataProducto);

    // Array de los items de la tabla de producto
    let arrayDataItems=await getDataInputsTable('formAltaItemsData','_');  
     
    // let subName =arrayDataProducto.subNombre;
    // console.log(arrayDataProducto.subNombre);
    // console.log(subName);
    // console.log(arrayDataProducto);
    if(!statusVersionActive(arrayDataItems)){

        // Sin editar
        arrayDataProducto['status']=0;
    }else{
        // Editado 
        arrayDataProducto['status']=1;

    }

    arrayInfo=arrayItemsSelectProductos.filter(producto=>{

        // console.log(producto);
        // console.log(arrayItemsSelectProductos);
        return arrayDataProducto['Producto']==producto['Id'];

    })
    arrayDataProducto['info']=arrayInfo[0];
    // arrayDataProducto['info']['subNombre'].push(subName)
    // console.log(arrayDataProducto['info']);
    arrayDataProducto['Version']=statusTableActive();
    arrayDataProducto['InventarioActual']=document.getElementById(`InventarioActualItem_${statusTableActive()}`).value
    arrayDataProducto['Producto']=document.getElementById('tabsNumber_'+statusTableActive()).getAttribute('attr_VersionProducto');


    arrayDataItems = await clerMaskPrice(arrayDataItems);
    arrayDataProducto = await clearMaskPriceData(arrayDataProducto);
    // arrayDataProducto['info']['subNombre']=subName
    // console.log(arrayDataProducto);
    arrayDataProducto['NombreProducto']=document.getElementById(`NombreItem_${statusTableActive()}`).value
    // console.log(arrayDataProducto['NombreProducto']);
    await insertProducto({...arrayDataProducto},{...arrayDataItems})

}

const clerMaskPrice=async(arrayData)=>{

    arrayData=arrayData.map((value)=>{

        return{
            Id:convertNumber(value['Id']),
            IdProducto:convertNumber(value['IdProducto']),
            costoLitro:convertNumber(value['costoLitro']),
            importe:convertNumber(value['importe']),
            litrosBarril:convertNumber(value['litrosBarril']),
            litrosProducion:convertNumber(value['litrosProducion']),
            porcentaje:convertNumber(value['porcentaje']),
        }
    })

    return arrayData
}

const clearMaskPriceData=async(arrayData)=>{

    return{

        CantidadFabricar: convertNumber(arrayData['CantidadFabricar']),
        Costo: convertNumber(arrayData['Costo']),
        InventarioActual: convertNumber(arrayData['InventarioActual']),
        Producto: convertNumber(arrayData['Producto']),
        UsuarioAsignado: convertNumber(arrayData['UsuarioAsignado']),
        Version: convertNumber(arrayData['Version']),
        status:arrayData['status'],
        info:{

            Color:arrayData['info']['Color'],
            Concentracion:arrayData['info']['Concentracion'],
            Densidad:arrayData['info']['Densidad'],
            Formulacion:arrayData['info']['Formulacion'],
            Hazmat:arrayData['info']['Hazmat'],
            Id:arrayData['info']['Id'],
            InventarioActual:arrayData['info']['InventarioActual'],
            Marca:arrayData['info']['Marca'],
            Nombre:arrayData['info']['Nombre'],
            PrecioLitros:arrayData['info']['PrecioLitros'],
            Uso:arrayData['info']['Uso'],

        }
    }
}

const validacionSelectTableActive=()=>{

        let arrayValidar=document.querySelectorAll(`#tablaTabs_${statusTableActive()} tr select`);
    

        let respuestaValidacion=true;
    
        arrayValidar.forEach(input=>{
    
            if(!input.value){
               
                    $("#" + input.id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });
    
                    respuestaValidacion=false;
               
            }
    
        })
    
        return respuestaValidacion;
    
}


const validacionPorcentajeTablaActiva=()=>{

    let arrayValidar=document.querySelectorAll(`#tablaTabs_${statusTableActive()} tbody tr`);

    let totalPorcentajeFormulacion=0;

    arrayValidar.forEach(row=>{

        totalPorcentajeFormulacion=totalPorcentajeFormulacion + parseInt(row.querySelector('.porcentajeItems').value);

    })

    return totalPorcentajeFormulacion==100?true:false;

}


const validacionPorcentajeNumberTablaActiva=()=>{

    let arrayValidar=document.querySelectorAll(`#tablaTabs_${statusTableActive()} tbody tr`);

    let respuestaValidacion=true;

    arrayValidar.forEach(row=>{

        valuePorcentaje=row.querySelector('.porcentajeItems').value;

        if(!soloNumeros(valuePorcentaje)){
            respuestaValidacion=false;
        }

    })

    return respuestaValidacion;

}



const convertirAObtjeto=(arrayNormal)=>{


    let arrayData=[];

    arrayNormal.forEach(arrayItem=>{

        arrayData.push({...arrayItem});

    })

    return arrayData;
}

const validarCerosPorcentaje=()=>{

    let arrayValidar=document.querySelectorAll(`#tablaTabs_${statusTableActive()} tbody tr`);

    let statusResponse=true

    arrayValidar.forEach(row=>{

         if(row.querySelector('.porcentajeItems').value=='0' ||  (row.querySelector('.porcentajeItems').value).includes('-') ){
            statusResponse=false
         }

    })

    return statusResponse;

}

// Comparamos los datos de los valores que se va a enviar,
//  con los valores que trae la api por default ,
// Para verificar si debe de crear una nueva Version 
const statusVersionActive=(arrayDataItems)=>{

    // NOTA: la variable 'dataItemsFormulados' conteine todos las versiones del producto
    // Seleccionado en el formulario se inicializa en la funcion 'initVersionesProducto()'

    let idTable=statusTableActive();

    let statuEditVersion=false;

    // Verificamos que en el array todos sean nuevos item  
    let itemsNuevos=0;
    // console.log(arrayDataItems);
    arrayDataItems.forEach(producto=>{
        // console.log(producto);
        if(producto['Id'].includes("Nuevo")){
            itemsNuevos++
        }

    })

    if(arrayDataItems.length == itemsNuevos){
        // console.log('Todos los items son nuevos')
        return true;

    }
    // ----------------------- 


    // Nos devuelve los items de la tabla activa 
    // Contiene la formulacion del producto 
    let respuestaId=dataItemsFormulados.filter(item=>{

        return item['Version'] ==idTable ;

    })[0];

    // Comparamos la cantidad de items de los datos a enviar,
    // Con los que trae la api 
    if(arrayDataItems.length != respuestaId['contenido'].length){

        // console.log(respuestaId['contenido']);
        // console.log(arrayDataItems);
        // Si lo trae Ponemos el estatus en Editado, Que es crear nueva version
        statuEditVersion=true
    }
    
    // Contiene los items de la formulacion de la version del producto
    respuestaId['contenido'].forEach(itemDefault=>{
        

        // Buscar el itemDefault editado que coincida con el default(es el que viene en la api)
        // Nos devuelve el id del producto con sus datos por default 
         itemEdit=arrayDataItems.filter(itemsAlta=>{

            return itemDefault['Id'] == itemsAlta['Id']; ;
    
        })[0];

        // Comparamos que los datos, Si hubo un cambio creamos una una version  
        if(itemEdit['IdProducto'] != itemDefault['IdProducto'] || itemEdit['porcentaje'] != itemDefault['NoPorcentaje']){

            statuEditVersion=true
            
        }

    })


    if(statuEditVersion){
        // console.log('Nueva Version')
    }else{
        // console.log('Sin editar')

    }

    return statuEditVersion;

}

// Calcula los litros por barril, de la tabla activa,
// obtiene su porcentaje de la fila y hace una operacion para obtener 'los litros por barril'
// NOTA: ESTA FORMULA YA SE HACE EN LA FUNCION 'litroPorProduccion()'
// const insertLitroPorBarril=async(tablaActive)=>{

//     arrayInputsLitros=document.querySelectorAll(`${tablaActive} tbody tr`);

//     arrayInputsLitros.forEach(items=>{

//         textPorcentaje=items.querySelector('.porcentajeItems').value;

//         // Barriles para produccion 
//         items.querySelector('.litrosBarrilItems').value=formulaLitros(parseInt(textPorcentaje));
 
//     });

//     return;
// };



// const formulaLitros=(porcentaje)=>{

//     const litrosBarril=200;

//     return porcentaje*litrosBarril/100;

// }
// FUNCION CAMBIOS INPUTS EN LOS ITEMS DE FORMULACION 
const litroPorProduccion=async(tablaActive)=>{

    let cantidadAFabricar=convertNumber(document.getElementById('CantidadFabricar_alta').value);

    if(!isNaN(cantidadAFabricar)){


        arrayInputsLitros=document.querySelectorAll(`${tablaActive} tbody tr`);


        arrayInputsLitros.forEach(items=>{


            // Porcentaje entre 100% por la cantidadAFabricar = Listro para produccion 

            // Porcentaje entre 100% 
            porcentajeItem=convertNumber(items.querySelector('.porcentajeItems').value)/100

            
            // Litros Para Producccion
            porcentajeItem= porcentajeItem*cantidadAFabricar;
            // porcentajeItem= parseFloat(porcentajeItem.toFixed(2));
            porcentajeItem=convertNumber(porcentajeItem);

            importeTotal=porcentajeItem;

            // Litros para produccion 
            items.querySelector('.litrosProducionItems').value=convertPrecio(porcentajeItem);


            // Calcula los litros por barril, de la tabla activa,
            // obtiene su porcentaje de la fila y hace una operacion para obtener 'Barriles para produccion'

            // Barriles para produccion = Litros para produccio(barriles) entre un barril (tiene 200 lt)
            litrosPorBarril=(porcentajeItem/200).toFixed(2);
            items.querySelector('.litrosBarrilItems').value=convertPrecio(litrosPorBarril);

            // Costo por litro 
            valueCostoLitroItems=convertNumber(items.querySelector('.costoLitroItems').value);

            // Importe total: es igual Costro por litro X Litros Para Producccion
            importeItem=(valueCostoLitroItems*importeTotal).toFixed(2);
            items.querySelector('.importeItems').value=convertPrecio(importeItem);

        });

    }

    // ---Stauts 
    return true;

}

const statusImporte=(event)=>{

    let costoPorLitro=event.value;


    let numberId=separarString(event.id,'_',1);

    if(!isNaN(costoPorLitro)){

        inputsLitrosProducion=document.getElementById('litrosProducion_'+numberId).value;
    
        document.getElementById('importe_'+numberId).value=parseFloat(inputsLitrosProducion)*parseFloat(costoPorLitro);

    }


    $("#importe_"+numberId).css({'border-color':'rgba(92, 106, 124, 0.3)'});


    return;
}


// const combinarArray=async(arrayDataProducto,arrayDataItems)=>{

//     console.log(arrayDataItems)
//     console.log(arrayDataProducto)


//     let arrayData=[];

//     arrayDataProducto.forEach(producto=>{

//         arrayItem=[];

        
//         arrayItem['Id']=producto['Id'];
//         arrayItem['IdCompuesto']=producto['IdCompuesto'];
//         arrayItem['IdProducto']=producto['IdProducto'];
//         arrayItem['NombreVersion']=producto['NombreVersion'];
//         arrayItem['Porcentaje']=producto['Porcentaje'];
//         arrayItem['Version']=producto['Version'];


//         arrayDataItems.forEach(items=>{

//             if(items['Id']==producto['Id']){

//                 arrayItem['costoLitro']=items['costoLitro'];
//                 arrayItem['importe']=items['importe'];
//                 arrayItem['litrosBarril']=items['litrosBarril'];
//                 arrayItem['litrosProducion']=items['litrosProducion'];

//             }

//         })


//         arrayData.push({...arrayItem});

//     })


//     return arrayData

// }


// -------------------------------------------------------------------------------------------------Fetch





const methodGetData=async (accion)=>{

    return await fetch(rutaApi+accion,{

        method: 'GET',

        headers: {'Content-Type': 'application/json'}
    
    }).then(respuesta=>respuesta.json())
    
    .then(respuesta =>{
        
        return respuesta;
    })

}


const insertProducto=async (dataForm,dataItems)=>{
    // console.log(dataForm);
    let accion = {"Accion" : "odt","Tabla":"odt",'Data':dataForm,'Producto':dataItems};

    return await fetch(rutaApi,{

        method: 'POST',

        body: JSON.stringify(accion),

        headers: {'Content-Type': 'application/json'}
    
    }).then(respuesta=>respuesta.json())
    
    .then(respuesta =>{
        
        if(respuesta['success']){

            showAlert("Correcto",respuesta['messenge'],"success")     

            let comentario = "Agrego la ODT :"+respuesta['data'];

            setBitacora('1', comentario, modulo);

            document.getElementById('formAlta').reset();

            statusContainerTable(false);

            setTimeout( function() { window.location.href = "index.php"; }, 2000 );
        }else{

            showAlert("Alerta",respuesta['messenge'],"info")
        
        }

        return respuesta['success'];
    })

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






// Clona la tabla ejemplo y le cambia sus valores, 
// Su identificador es 'countTable' (que es el numero de la version del producto)
const initTableVersion=async(countTable)=>{


        let boxTemplateTable = document.querySelector(".boxTemplateTable").cloneNode(true)

        boxTemplateTable.classList.replace('boxTemplateTable','boxTableTabs');

        boxTemplateTable.querySelector("#tablaExample").id='tablaTabs_'+countTable;
    
        containerButton=document.querySelector('.containerButtonTabs');

        let countContainerPrincipal=Number(containerButton.childElementCount);


        boxTemplateTable.querySelector('.InventarioActualItem_Example').id='InventarioActualItem_'+countTable;
        boxTemplateTable.querySelector('.InventarioActualItem_Example').classList.remove('InventarioActualItem_Example')

        boxTemplateTable.querySelector('.InventarioDespuesItem_Example').id='InventarioDespuesItem_'+countTable;
        boxTemplateTable.querySelector('.InventarioDespuesItem_Example').classList.remove('InventarioDespuesItem_Example')

        boxTemplateTable.querySelector('.CostoBarrilItem_Example').id='CostoBarrilItem_'+countTable;
        boxTemplateTable.querySelector('.CostoBarrilItem_Example').classList.remove('CostoBarrilItem_Example')

        boxTemplateTable.querySelector('.NombreItem_Example').id='NombreItem_'+countTable;
        boxTemplateTable.querySelector('.NombreItem_Example').classList.remove('NombreItem_Example')

        // Validamos que el container donde van los botones para cambiar de tabla 
        // Este vacio, si es el caso insertamos el boton principal y lo dejamos activo 
        if(countContainerPrincipal==0){


            if(insertTabsPrincipal(countTable) & insertButtonPrincipal(countTable)){

                let boxPrincipal=document.querySelector('.containerTabs_'+countTable);
    
                boxPrincipal.appendChild(boxTemplateTable);
    
    
            }

        }else{

           
            if(insertTabs(countTable) & insertButtonTabs(countTable)){

                let boxPrincipal=document.querySelector('.containerTabs_'+countTable);
    
                boxPrincipal.appendChild(boxTemplateTable);
    
    
            }
            
        }

        return countTable;
        
}


const insertTabs=(id)=>{

    document.getElementById('containerTablesTabs').innerHTML+=`<div class="tab-pane fade containerTabs_${id}" attr_Number="${id}" id="tabsNumber_${id}" role="tabpanel"></div>`

    return true;
};


const insertButtonTabs=(id)=>{

    Version='Version'+id;


    document.querySelector('.containerButtonTabs').innerHTML+=`
    <li class="nav-item" role="presentation">
        <button type="button" class="nav-link buttonTabs"
        attr_numTab='${id}'
         role="tab" 
         data-bs-toggle="tab" 
         data-bs-target="#tabsNumber_${id}" 

         aria-selected="false" 
         tabindex="-1">
         ${Version}
         </button>
    </li>`;

    return true;
}





const insertButtonPrincipal=(id)=>{

    Version='Version'+id;

    document.querySelector('.containerButtonTabs').innerHTML=`
    <li class="nav-item buttonTabs" role="presentation">
        <button type="button" 
        class="nav-link active"
        attr_numTab='${id}' 
        role="tab" 
        data-bs-toggle="tab" 
        data-bs-target="#tabsNumber_${id}" 
        aria-selected="true">
        ${Version}
        </button>
    </li>
    `
    return true;

}


const insertTabsPrincipal=(id)=>{

    document.getElementById('containerTablesTabs').innerHTML=`    
    <div class="tab-pane fade active show containerTabs_${id}"  attr_Number="${id}" id="tabsNumber_${id}" role="tabpanel">
    </div>`;

    return true;
}



// --------------- 

// Se almacenan todos los datos (de las versiones del producto) de la consulta del producto
//  que selecciono en el formulario, 
// Para al subir la formulacion verificar si hubo un cambio en la formulación y crear una nueva version
var dataItemsFormulados
// Llama al id del producto para traer todas sus versiones 
// y inicializar una tabla por cada version
const initVersionesProducto=async(IdProducto)=>{


    // Almacena los id de las versiones disponibles 
    var arrayVersion=[];


    let accion=`?Accion=odt&Tabla=productos&Id=${IdProducto}`;

    data=await methodGetData(accion);

    dataItemsFormulados=data['items'];


    for(version of data['items']){

        await initTableVersion(version['Version']);

        await insertContenidoTabla(version['contenido'],version['Version']);

        // Agregamos el numero de la version del producto al container de la tabla
        document.getElementById('tabsNumber_'+version['Version']).setAttribute('attr_VersionProducto',version['IdProducto']);

        arrayTotalItem=[];
        // console.log(version);
        arrayTotalItem['Version']=version['Version'];
        arrayTotalItem['Total']=version['Total'];
        arrayTotalItem['Nombre']=version['Nombre'];
        arrayVersion.push(arrayTotalItem);

    }

    // Obtenemos los datos de los productos 
    let dataSelectProductos=await methodGetData('?Accion=odt&Tabla=productos&Select=1');

    for(version of arrayVersion){

            await initInfoTable(version['Version'],dataSelectProductos);
            // Ponemos en disable todos los selects por default
            // disabledSelect(`#tablaTabs_${version['Version']} .selectsProductoPrimario`);
            // Agregamos el total del inventario al input que esta arriba de la tabla 
            document.getElementById(`InventarioActualItem_${version['Version']}`).value=version['Total'];
            // console.log(version);
            document.getElementById(`NombreItem_${version['Version']}`).value=version['Nombre'];
    }



    $(`.containerPrincipalTabs select`).chosen({
        width: "100%",
        no_results_text: "...",
        allow_single_deselect: true,
    });
    // $('#Cliente_alta').val('').trigger('chosen:updated');
    $(`.containerPrincipalTabs select`).prop('disabled', true).trigger("chosen:updated");


    return;

};


// initVersionesProducto(42);





async function  insertContenidoTabla(dataSet,numContainer) {

    // Por cada item de 'dataSet' agregamos una fila 
    for(fila of dataSet){

        idSelect=await agregarFileTable(fila,numContainer);

        $(`#tablaTabs_${numContainer} .btnAgregarFila`).attr('disabled',true);

        $(`#tablaTabs_${numContainer} .btnElimarFila`).attr('disabled',true);


    };    

    

    // $('.selectsProductoPrimario').on('change',async function(){

    //     let key=this.getAttribute('attr_key');
    
    //     let inventarioProducto=$(`#${this.id} option:selected`).attr('attr_inventarioactual');
    //     let costoPorLitro=$(`#${this.id} option:selected`).attr('attr_costoporlitro');
        

    //     document.querySelector(`.totalProducto${key}`).textContent=inventarioProducto;
        
    //     document.getElementById(`costoLitro_${key}`).value=costoPorLitro;

    //     let keyTabla=statusTableActive();

    //     await litroPorProduccion(`#tablaTabs_${keyTabla}`);
    //     getImportes(`#tablaTabs_${keyTabla}`);
    //     // console.log('Entro Prueba Select')
    

    // })


    return;

}


// $(document).on('change', '.selectsProductoPrimarioNuevos', async function (event) {

// const asignarDatosProductoFila=(event)=>{
//     console.log(event);
//     let keyTabla = statusTableActive();

//     event=event.target;

//     let key = event.getAttribute('attr_key');
//     // console.log(event.id)
//     console.log(key);
    
//     let inventarioProducto = $(`#${event.id} option:selected`).attr('attr_inventarioactual');
//     let costoPorLitro = $(`#${event.id} option:selected`).attr('attr_costoporlitro');


//     document.querySelector(`#tablaTabs_${keyTabla} .totalProductoItems${key}`).textContent = inventarioProducto;

//     document.querySelector(`#tablaTabs_${keyTabla} #costoLitro_${key}`).value = costoPorLitro;


//     await litroPorProduccion(`#tablaTabs_${keyTabla}`);
//     getImportes(`#tablaTabs_${keyTabla}`);
//     // console.log('Entro Prueba Select')
// }
// })

// Hace el conteo de los datos de la tabla a actualizar, 
const initInfoTable=async(numContainer,dataSelectProductos)=>{

    // await insertLitroPorBarril(`#tablaTabs_${numContainer}`);
       
    await litroPorProduccion(`#tablaTabs_${numContainer}`);
    await getImportes(`#tablaTabs_${numContainer}`);
    // let dataSelectProductos=await methodGetData('?Accion=odt&Tabla=productos&Select=1');
    
    await insertDataAllSelect(dataSelectProductos['data'],`#tablaTabs_${numContainer} .selectsProductoPrimario`,'Nombre','Id',
        [
            {'nameAttr':'attr_InventarioActual','valor':'InventarioActual'},
            {'nameAttr':'attr_CostoPorLitro','valor':'PrecioLitros'}
        ]
    );
    
    await insertDataAllSelectValue(`#tablaTabs_${numContainer} .selectsProductoPrimario`);

    return;

}

const compararTable=(id)=>{

    let arrayInputs=document.querySelectorAll(`#tablaTabs_${id} tbody tr`);

    arrayInputs.forEach(items=>{

        textPorcentaje=items.querySelector('.porcentajeItems').textContent;


    });
}


const statusTableActive=()=>{

    let containerTables=document.querySelector('#containerTablesTabs');

    if(containerTables.querySelector('.active')){

        let itemTable=containerTables.querySelector('.active');

        return itemTable.getAttribute('attr_Number');
    
    }else{

        return false;

    }


}


const validacionTable=(id)=>{

    let inputsTable=document.querySelectorAll(`#tablaTabs_${id} .formAltaItemsData`)

    let arrayResultado=[];

    inputsTable.forEach(input=>{
        if(!validacionNumberTable(input.id)){
            arrayResultado.push(false);
        }
    })

    if(arrayResultado.length==0){
        return true
    }else{
        return false;
    }

};

const validacionNumberTable=(id)=>{
    
    if(!isNaN($("#" + id).val()) && $("#" + id).val()){

      $("#" + id).css({ 'border-color': '#ced4da',"border-weight": "0" });

      return true;

    }else{

      $("#" + id).css({ 'border-color': 'rgba(116, 0, 0, 0.6)', "border-weight": "3px" });

      return false;

    }

};


$('.containerPrincipalTabs').on('click', '.btnAgregarFila',async function (element) {

    numberContainer=statusTableActive();

    idSelect=await agregarFilaTablaNueva();

    let dataSelectProductos=await methodGetData('?Accion=odt&Tabla=productos&Select=1');
        //   console.log(dataSelectProductos);
    await insertDataSelect(dataSelectProductos['data'],`select${idSelect}`,'Nombre','Id',
        [
            {'nameAttr':'attr_InventarioActual','valor':'InventarioActual'},
            {'nameAttr':'attr_CostoPorLitro','valor':'PrecioLitros'},
            {'nameAttr':'attr_IdUni','valor':'tipoUnidad'},
            {'nameAttr':'attr_nameUni','valor':'nombreUnidad'},
        ]
    );

    
    $(`.containerPrincipalTabs select`).chosen({
        width: "100%",
        no_results_text: "...",
        allow_single_deselect: true,
    });
    // await initInfoTable(numberContainer,dataSelectProductos);
    // await insertDataAllSelect(dataSelectProductos['data'],`#tablaTabs_${numberContainer} .selectsProductoPrimario`,'Nombre','Id');
    // await insertLitroPorBarril(`#tablaTabs_${numberContainer}`);     
    // await litroPorProduccion(`#tablaTabs_${numberContainer}`);
    // await getImportes(`#tablaTabs_${numberContainer}`);

})

$('.containerPrincipalTabs').on('click', '.btnElimarFila',async function (element) {

    numberContainer=statusTableActive();

    let tableActive=document.querySelector(`#tablaTabs_${numberContainer} tbody`);

    if(tableActive.childElementCount!=1){

        this.parentNode.parentNode.remove()

        // await insertLitroPorBarril(`#tablaTabs_${numberContainer}`);     
        await litroPorProduccion(`#tablaTabs_${numberContainer}`);
        await getImportes(`#tablaTabs_${numberContainer}`);


    }

})


var statusEditVersion=false;

$('.containerPrincipalTabs').on('click', '.checkHabilitarEdit', function (element) {


    numberContainer=statusTableActive();
     getImportes(`#tablaTabs_${numberContainer}`);
    if(this.checked){

        statusEditVersion=true;

        // console.log('Activo');
        // $(`#tablaTabs_${numberContainer} .selectsProductoPrimario`).removeAttr('disabled')
        $(`#tablaTabs_${numberContainer} select`).prop('disabled', false).trigger("chosen:updated");

        $(`#tablaTabs_${numberContainer} .porcentajeItems`).removeAttr('readonly')

        $(`#tablaTabs_${numberContainer} .btnAgregarFila`).removeAttr('disabled')
        $(`#tablaTabs_${numberContainer} .btnElimarFila`).removeAttr('disabled')
        // $(`#tablaTabs_${numberContainer} .NombreItem_${numberContainer}`).removeAttr('disabled')
        $('#NombreItem_'+numberContainer).attr('disabled', false);
        // document.querySelector('.containerNombre').style.display='flex';

    }else{

        // console.log('Inanctivo');
        // document.querySelector('.containerNombre').style.display='none';

        // $(`#tablaTabs_${numberContainer} .selectsProductoPrimario`).attr('disabled',true)
        $(`#tablaTabs_${numberContainer} select`).prop('disabled', true).trigger("chosen:updated");

        $(`#tablaTabs_${numberContainer} .porcentajeItems`).attr('readonly',true)

        $(`#tablaTabs_${numberContainer} .btnAgregarFila`).attr('disabled',true)
        $(`#tablaTabs_${numberContainer} .btnElimarFila`).attr('disabled',true)
        $('#NombreItem_'+numberContainer).attr('disabled', true);
        // $(`#tablaTabs_${numberContainer} .NombreItem_${numberContainer}`).attr('disabled',true)

    }


})



const agregarFileTable=async(fila,numContainer)=>{
    newFila=`
        <td class='selectProductosFila'>
        ${fila['SelectContenido']}
        <td class='totalProducto${fila['Id']}' >${fila['Total']}</td>

        <td att-idUni = '${fila['IdUni']}'>
        <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
            ${fila['nombreUnidad']}
        </div>
        </td>
        <td>
            <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                ${fila['Porcentaje']}
                <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">%</span>
            </div>
        </td>
        <td>
            <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                ${fila['litrosBarril']}
                <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>
            </div>
        </td>
        <td>
            <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
            ${fila['litrosProducion']}
            <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>
            </div>
        </td>
        <td>

            <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
            ${fila['costoLitro']}
            </div>
        </td>
        <td>
            <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
                ${fila['importe']}
            </div>
        </td>
        <td>
        ${fila['agregar']}
        </td>
        <td>
        ${fila['eliminar']}
        </td>
    `

    let containerTabla=document.querySelector(`#tablaTabs_${numContainer} tbody`);
    let newElement = document.createElement('tr');
    newElement.id=`rowTable_${fila['Id']}`;
    containerTabla.appendChild(newElement);

    document.querySelector(`#tablaTabs_${numContainer} tbody #rowTable_${fila['Id']}`).innerHTML=newFila;

    // console.log(fila['Id']);


    return fila['Id'];
}



const statusPorcentaje=async(event)=>{

    let numContainer=statusTableActive()

    // await insertLitroPorBarril(`#tablaTabs_${numContainer}`);
    await litroPorProduccion(`#tablaTabs_${numContainer}`);
    await getImportes(`#tablaTabs_${numContainer}`);

}


var contadorFilaposition=0
const agregarFilaTablaNueva=async()=>{

    contadorFilaposition++

    let contadorFila=contadorFilaposition+'Nuevo';

// {/* <tr class="nuevoItem"> */}

    newFila=`
    <td>
        <select onchange="sobreSelectData(event);insertTotalProductosNuevoItems(this);" class="form-control formAltaDataItems validarAltaDataItems selectsProductoPrimario selectsProductoPrimarioNuevos form-select" attr_key='${contadorFilaposition}' attr_valuedefaul="" id="select${contadorFila}"></select></td>
    <td class='totalProductoItemsNuevo${contadorFilaposition}' >
        Sin definir
    </td>
    <td class='tipoUnidadNuevo_${contadorFilaposition}' >
        Sin definir
    </td>
    <td>
        <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                <input onkeyup="sobreinputItems(event); statusPorcentaje(this);" autocomplete="off" class="form-control borderRadiusInputPercentaje formAltaItemsData validarDataAltaItems stringClass trim porcentajeItems" type="text" id="porcentaje_${contadorFila}">
                <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">%</span>
        </div>
    </td>

    <td>
        <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
            <input onkeyup="sobreinputItems(event);" autocomplete="off" class="form-control borderRadiusInputLiters formAltaItemsData validarDataAltaItems stringClass trim litrosBarrilItems" type="text" id="litrosBarril_${contadorFila}" readonly>
            <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>
        </div>
    </td>
    <td>
        <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
            <input onkeyup="sobreinputItems(event);" autocomplete="off" class="form-control borderRadiusInputLiters formAltaItemsData validarDataAltaItems stringClass trim litrosProducionItems" type="text" id="litrosProducion_${contadorFila}" readonly>
            <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>
        </div>

    </td>
    <td>
        <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
            <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
            <input onkeyup="sobreinputItems(event);statusImporte(this);" autocomplete="off" class="form-control borderRadiusInputPrice formAltaItemsData validarDataAltaItems stringClass trim costoLitroItems" type="text" id="costoLitro_${contadorFila}" readonly>
        </div>
    </td>
    <td>
        <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
            <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
            <input onkeyup="sobreinputItems(event);" autocomplete="off" class="form-control borderRadiusInputPrice formAltaItemsData validarDataAltaItems stringClass trim importeItems" type="text" id="importe_${contadorFila}" readonly="">
        </div>
    </td>
    <td>
        <button class="btn btn-success btn-sm rounded-10 btnAgregarFila bx bx-plus" type="button" id="agregar_${contadorFila}" style="font-size: 18px;"></button>
    </td>
    <td>
        <button class="btn btn-danger btn-sm rounded-10 btnElimarFila bx bx-trash" type="button" id="eliminar_${contadorFila}" style="font-size: 18px;"></button>
    </td>
    `;

    let keyTabla=statusTableActive();

    let containerTabla=document.querySelector(`#tablaTabs_${keyTabla} tbody`);    
    let newElement = document.createElement('tr');
    newElement.id=`rowTable_${contadorFila}`;
    containerTabla.appendChild(newElement);

    document.querySelector(`#tablaTabs_${keyTabla} tbody #rowTable_${contadorFila}`).innerHTML=newFila;

    return contadorFila;
}



$('.containerButtonTabs').on('click', 'button', async function(){

    let key=this.getAttribute('attr_numTab');

    let dataSelectProductos=await methodGetData('?Accion=odt&Tabla=productos&Select=1');

    initInfoTable(key,dataSelectProductos);
    // console.log(key);
    statusInventario(key);

});

// Inseta los valores a la fila seleccionada:
// total de productos, costo por litro, y hace el calculo de nuevo
//  (los cambio se hacen solo para los selects creados con btn agregar)
const insertTotalProductosNuevoItems=async(event)=>{
    let key=event.getAttribute('attr_key');

    let totalProducto=$(`#${event.id} option:selected`).attr('attr_inventarioactual');
    document.querySelector(`.totalProductoItemsNuevo${key}`).textContent=convertPrecio(totalProducto);

    let costoPorLitro=$(`#${event.id} option:selected`).attr('attr_CostoPorLitro');
    document.querySelector(`#costoLitro_${key}Nuevo`).value=convertPrecio(costoPorLitro);

    let tipoUnidad=$(`#${event.id} option:selected`).attr('attr_nameuni');
    // console.log(tipoUnidad);
    document.querySelector(`.tipoUnidadNuevo_${key}`).textContent=tipoUnidad;

    let keyTabla = statusTableActive();
    await litroPorProduccion(`#tablaTabs_${keyTabla}`);
    await getImportes(`#tablaTabs_${keyTabla}`);

}

const insertTotalProductos=async(event)=>{

    let key=event.getAttribute('attr_key');

    let totalProducto=$(`#${event.id} option:selected`).attr('attr_inventarioactual');
    document.querySelector(`.totalProducto${key}`).textContent=convertPrecio(totalProducto);
    
    let costoPorLitro=$(`#${event.id} option:selected`).attr('attr_CostoPorLitro');
    document.querySelector(`#costoLitro_${key}`).value=convertPrecio(costoPorLitro);

    let keyTabla = statusTableActive();
    await litroPorProduccion(`#tablaTabs_${keyTabla}`);
    await getImportes(`#tablaTabs_${keyTabla}`);
}





// ------------ 




const convertPrecio=(price, currency = 'USD')=> {

    if(isNaN(price)){
        price=0;
        return price;
    }

    const formatter = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: currency
    });

    let precio = formatter.format(price);

    return precio.replace("$", "");
}

const convertNumber=(string2)=>{
    let string=string2;

    if(typeof string =='number'){//Si es un numero

        return string;
    }

    if(!isNaN(string)){//Si es un string con format number

        return parseFloat(string);
    }
    // Si incluye comas, lo limpiamos 
    if(string.includes(",")){

        let numberString=string.replace(/,/g, "");  // elimina las comas

        return parseFloat(numberString); 
    }

    return parseFloat(string);

}

function soloNumeros(cadena) {
    // Creamos una expresión regular que solo permita dígitos
    var expresionRegular = /^\d+$/;
  
    // Utilizamos el método search() para buscar una coincidencia en la cadena
    return expresionRegular.test(cadena);
  }