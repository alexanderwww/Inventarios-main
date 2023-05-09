$(document).ready(function () { //Informacion al cargar la pagina

    $('#titlePage').text('Formulación');

    initFetchs();

})

var arrayDataProductos;

const initFetchs = async () => {
     arrayDataProductos = await methodGetData('?Accion=productos&Tabla=productos&Select=2');
     await newRowTable();
     await newRowTable();

     oneIMask('cantidadFabricar')

}






let countTable=0;
const table = document.getElementById("containerTable");

const newRowTable=async() => {
    let key=countTable;

    let row = document.createElement("tr");
    row.classList.add("rowsTable", 'itemsTable_'+key);
    row.setAttribute("key", key);
    table.appendChild(row);

    let newItemRow=await createRowTable(key);
    document.querySelector('.itemsTable_'+key).innerHTML=newItemRow;

    await initMaskRow(key);

    countTable++;

    return key;

};

const createRowTable = async(key) => {

    return `
    <td>
        <div style="width: 100%;">
            <select onchange="statusItemProducto(this);calculateRow(${key})" class="form-control form-select" id='producto_${key}' key="${key}">
            <option>Seleccione</option>
            <option id="22">2fafasf</option>

            </select>
        </div>
    </td>


    <td>
        <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
            <input onkeyup="calculateRow(${key})" onchange="calculateRow(${key})" class="form-control borderRadiusInputPercentaje" type="text" id="porcentaje_${key}" key="${key}">
            <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">%</span>
        </div>
    </td>


    <td>
        <span>$</span>
        <span id="costoPorLitro_${key}" key="${key}">0.00</span>
    </td>

    <td>
        <span>$</span>
        <span id="importeTotal_${key}" key="${key}">0.00</span>
    </td>

    <td>
        <span id="inventario_${key}" key="${key}">0</span>
    </td>


    <td>
        <span id="barrilesParaProduccion_${key}" key="${key}">0</span>
        <span></span>
    </td>

    <td>
        <span id="litrosPorProduccion_${key}" key="${key}">0</span>
        <span>LTS</span>
    </td>


    <td>
        <button class="btn btn-success btn-sm rounded-10 btnAgregarFila bx bx-plus" type="button" id="btnAgregar_${key}" key="${key}" style="font-size: 18px;"></button>
    </td>
    <td>
        <button class="btn btn-danger btn-sm rounded-10 btnElimarFila bx bx-trash" type="button" id="btnDelete_${key}" key="${key}" style="font-size: 18px;"></button>
    </td>



    `;

}




const maskMoney = (num) => {
        num=parseFloat(num);

        let number= num.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
        
        return number.slice(1);

}



const insertDataProducto=(key,data)=>{

    // $("#producto_"+key).val(data['idProducto']) // ?


    // $("#porcentaje_"+key).val(data['porcentaje'])
    
    $("#costoPorLitro_"+key).text(maskMoney(data['PrecioLitros']))
    $("#inventario_"+key).text(maskMoney( data['Total']) )
    
    // $("#importeTotal_"+key).text( maskMoney( data['importeTotal']) )
    // $("#barrilesParaProduccion_"+key).text( maskMoney(data['barrilesProduccion']) );
    // $("#litrosPorProduccion_"+key).text( maskMoney(data['litrosProduccion']) );
    

    return key;

}



$('#tableMain').on('click','.btnAgregarFila',async()=>{

    let newKey=await newRowTable();
    calculateBalanceGeneral();

})


$('#tableMain').on('click','.btnElimarFila ',async function(){

    if(table.childElementCount!=1){
        this.parentNode.parentNode.remove()
        calculateBalanceGeneral();
    }

})




const methodGetData=async (accion)=>{

    return await fetch(rutaApi+accion,{
        method: 'GET',
        headers: {'Content-Type': 'application/json'}
    }).then(respuesta=>respuesta.json())
    .then(respuesta =>{
        return respuesta;
    })

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


const oneIMask=(id)=>{
    let miInput=document.getElementById(id);

    new IMask(miInput, {
        mask: Number,
        scale: 2,
        padFractionalZeros: true,
        thousandsSeparator: ',',
        radix: '.',
        mapToRadix: ['.']
    });
}



const initMaskRow= async(key)=>{

    await insertDataSelect(arrayDataProductos['data'], 'producto_'+key, 'Nombre', 'Id')

    $("#producto_"+key).chosen({

        no_results_text: "No se a encontrado resultados",

        allow_single_deselect: true,

    });


};


const clearNumber=(string)=>{
    // Quita todas las letras exceptuando el "." y los numeros 
    let  number = string.replace(/[^\d.]/g, "");
    if(!number){
        number=0
    }
    // console.log(cleanedStr); // "100"
    return parseFloat(number);
}




const getTotalesTable=async()=>{

    
    let arrayDataProductos=document.querySelectorAll(`#containerTable tr`);

    var total_porcentaje=0;

    var total_costoPorLitro=0;

    var total_importeTotal=0;

    var total_inventario=0;

    var total_barrilesParaProduccion=0;

    var total_litrosProduccion=0;
    
    arrayDataProductos.forEach(row=>{

        let key = row.getAttribute('key');
    
        var item_Porcentaje=clearNumber($(`#porcentaje_${key}`).val());
        var item_CostoPorLitro=clearNumber($(`#costoPorLitro_${key}`).text());

        var item_Total=clearNumber($(`#importeTotal_${key}`).text());
        var item_Inventario=clearNumber($(`#inventario_${key}`).text());

        var item_BarrilesProduccion=clearNumber($(`#barrilesParaProduccion_${key}`).text());
        var item_LitrosProduccion=clearNumber($(`#litrosPorProduccion_${key}`).text());


        total_porcentaje+=item_Porcentaje;
        total_costoPorLitro+=item_CostoPorLitro;
        total_importeTotal+=item_Total;
        total_inventario+=item_Inventario;
        total_barrilesParaProduccion+=item_BarrilesProduccion;
        total_litrosProduccion+=item_LitrosProduccion;

    });


    return{
        porcentaje:total_porcentaje,
        costoPorLitro:total_costoPorLitro,

        importeTotal:total_importeTotal,
        inventario:total_inventario,
        
        barrilesParaProduccion:total_barrilesParaProduccion,
        litrosProduccion:total_litrosProduccion,
    };

}


const setTotalesTable = (arrayData) => {

    $("#balance_porcentaje").text( maskMoney(arrayData["porcentaje"]));

    $("#balance_costoPorLitro").text( maskMoney(arrayData["costoPorLitro"]) );
    $("#balance_importeTotal").text(maskMoney(arrayData["importeTotal"]) );


    $("#balance_barrilesParaProduccion").text( maskMoney(arrayData["barrilesParaProduccion"]) );
    $("#balance_litrosPorProduccion").text( maskMoney(arrayData["litrosProduccion"]) );


    let precioBarril = ((arrayData.importeTotal/arrayData.litrosProduccion)*200).toFixed(2);

    $("#balance_costoPorBarril").text(maskMoney(isNaN(precioBarril)?0:precioBarril));

    return;
}


const statusItemProducto=(event)=>{

    let productoSelect=event.value;

    let key=$(event).attr('key');


    let dataProducto=arrayDataProductos['data'].filter((value,index,arrray)=>{

        if(value.Id == productoSelect){
            return value;
        }

    });

    insertDataProducto(key,dataProducto[0]);

}


// ------------------------------------------------------------------------- 


// sacar margen:
// Precio de venta por litros 
// Precop de vemta por barril 

// El costo por litros * la suma del marge mas 1 entre 100  = Precio de venta por Litros
// El precio de venta por litros  * 200 = precio de venta por barril

// --------------------------------------------

// sacar margen:
// Precio de venta por litros 
// y por barril 


// costos por litros * la suma de 1 mas el "margen" entre 100 
// Precio de venta 

// precio por litro * 200 
// Precio de venta por barril 


const calculateMargen=(event)=>{

    let margen=$(event).val();

    let costoPorLitro = maskMoney($('#balance_costoPorLitro').text());
    let margenNeto=(1 + margen)/100;



    let precioVentaPorlitros=costoPorLitro * margenNeto;
    $('#margen_precioVentaPorLitros').text(maskMoney(precioVentaPorlitros));

    let precioVentaPorBarril=precioVentaPorlitros * 200;
    $('#margen_precioVentaPorBarril').text(maskMoney(precioVentaPorBarril));




};

const calculateRow=async(key)=>{

    await statusItemPorcentaje(key);
    await statusImporteTotal(key);
    await statusItemsBarrilesProduccion(key);

    calculateBalanceGeneral();
    return;
}

const calculateBalanceGeneral=async()=>{
    let arrayTotales=await getTotalesTable();

    setTotalesTable(arrayTotales);
    return;
};



const setRowsTable=async()=>{

    let arrayDataProductos=document.querySelectorAll(`#containerTable tr`);

    for (const row of arrayDataProductos) {

        let key = row.getAttribute('key');
        await calculateRow(key);

    }

    return;
}


const statusItemPorcentaje=async(key)=>{
    // Porcentaje entre 100% por la cantidadAFabricar = Listro para produccion 

    let porcentaje=(clearNumber($('#porcentaje_'+key).val()))/100;
    let cantidadFabricar=clearNumber($('#cantidadFabricar').val());

    let litrosProduccion=porcentaje*cantidadFabricar;

    $("#litrosPorProduccion_"+key).text(maskMoney(litrosProduccion));


    return;
}

const statusImporteTotal=async(key)=>{
    // Importe total: es igual Costro por litro X Litros Para Producccion
    let costoPorLitro=clearNumber( $("#costoPorLitro_"+key).text() );
    let litrosPorProduccion=clearNumber( $("#litrosPorProduccion_"+key).text() );

    let importeTotal=costoPorLitro * litrosPorProduccion;

    $('#importeTotal_'+key).text( maskMoney(importeTotal) );
    return;

}

const statusItemsBarrilesProduccion=async(key)=>{
    // Barriles para produccion = Litros para produccio(barriles) entre un barril (tiene 200 lt)

    let litrosProduccion=clearNumber( $("#litrosPorProduccion_"+key).text() );
    let barril=200;

    $('#barrilesParaProduccion_'+key).text(litrosProduccion/barril);

    return;
};

// ------------------------------------------------------------------------- 





// Trae todo los TOTALES de la table y los agrega a balance 
// Agregar cada vez que el usuario haga un cambio se calcule su final de los datos afectados