$(document).ready(function() { //Informacion al cargar la pagina

    $('#titlePage').text('Bitacora');

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

const tablaPrincipal=()=>{

    var accion = {"Accion" : "bitacora"};

    var tablaSSP = $('#tablaBitacora').DataTable({

    'ajax':{
      'url':rutaApi,
      'type': 'GET',
      'data':accion,
      'dataSrc': 'data',
    },
    "order": [[0, "desc"]],
    'columns': [
        { 'data': 'Id' },

        { 'data': "TimeStamp",'render': $.fn.dataTable.render.moment( 'YYYY-MM-DD HH:mm:ss',' DD/MM/YYYY')},

        { 'data': 'User' },

        { 'data': 'Operacion' },

        { 'data': 'Modulo' },

        { 'data': 'Comentarios' }

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
   
    createdRow: function (row, data) {

      if(data['Modulo']=='Ordenes de Venta'){

          $(row).css('background-color','rgb(0, 220, 0, 0.2)')

      }
      if(data['Modulo']=='Ajustes'){

        $(row).css('background-color','rgb(237, 28, 36, 0.2)')

    }
  }
  });

}



let dataExcel={
  idBtnExcel:'btnExcelTabla',
  nameFile:'Bitacora',
  urlApi:rutaApi,
  accion:`?Accion=bitacora&getDataExcel=1&Tabla=bitacora`,
  urlVendor:'../../requerimientos/vendors/spreadsheet/spreadsheetExcel.php'
}

let excelTabla = new exportarExcelTabla(dataExcel);