<?php

// los datos asociados te los convierte en array listo para Spreadsheet
function convertDataForm($data)
{
    $newData = array();
    $firstLine = true;

    foreach ($data as $dataRow)
    {
        if ($firstLine)
        {
            $newData[] = array_keys($dataRow);
            $firstLine = false;
        }

        $newData[] = array_values($dataRow);
    }

    return $newData;
}

if($_POST){

    if(empty($_POST['datosExcel'])==false){

        $arrayPost=json_decode($_POST['datosExcel']);

        $arrayExcel=[];
        foreach ($arrayPost as $key => $value) {

            $arraySub=[];
            foreach($value as $key => $column){
                $arraySub[$key]=$column;
            }
            $arrayExcel[]=$arraySub;

        }


        $dataArray=convertDataForm($arrayExcel);
        $columnas=count($dataArray[0]);
        $row=count($dataArray)+1;
        $nameFile=$_POST['nameFile'];
        $nameTitle=$_POST['nameTitle'];

        $nameCreator=$_POST['nameCreator'];

        if(empty($_POST['columnasExcel'])==false){

            $ajustes=json_decode($_POST['columnasExcel'],true);

            // print_r($ajustes);

            // return;
            // ['C3:D3','F3']

        }else{
            $ajustes=null;
        }


    }else{

        echo 'Variable POST No definida';

    }

}else{
    echo 'pagina No disponible';
}

// Comineza de la posición 1
$arrayAbeceadario=[null, 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];


require "../../vendorComposer/vendor/autoload.php";

// -------------------------- Liks

use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


// Border
use PhpOffice\PhpSpreadsheet\Style\Border;
// Color
use PhpOffice\PhpSpreadsheet\Style\Fill;
// Alignment
use PhpOffice\PhpSpreadsheet\Style\Alignment;
// Format Number
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// -------------------------- Style

$colorExcel='8DB4E2';


$styleBordersArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => Border::BORDER_MEDIUM,
            'color' => ['argb' => '000000'],
        ],
    ],
];

$styleBorderDefaultArray=[
    'allBorders' => [
        'borderStyle' => Border::BORDER_THIN,
        'color' => [
            'rgb' => '000000'
        ]
    ]
];

$styleFontArray=[
    'name' => 'Century Gothic',
    'bold' => TRUE,
    'size'=>18,
    'color' => [
        'rgb' => 'FFFFFF'
    ]
];

// -----------------------------
$styleFontDefaultArray = [
    'font' => [
        'name' => 'Century Gothic',
        'size'=>11,
    ],

];

$styleFontSubTitleArray = [
    'font' => [
        'bold' => true,
        'name' => 'Century Gothic',
    ],

];

$styleAlingArray=[
    'horizontal'   => Alignment::HORIZONTAL_CENTER,
    'vertical'     => Alignment::VERTICAL_CENTER,
    'textRotation' => 0,
    'wrapText'     => TRUE
];

// ----------------------- Init and Font Default

$spreadsheet = new Spreadsheet();
$hojaExcel = $spreadsheet->getActiveSheet();

$spreadsheet->getProperties()
    ->setCreator($nameCreator)
    ->setLastModifiedBy($nameCreator)
    ->setTitle($nameFile);


$hojaExcel->getStyle('A2:'.$arrayAbeceadario[$columnas].$row)->getBorders()->applyFromArray($styleBorderDefaultArray);

$hojaExcel->getStyle('A3:'.$arrayAbeceadario[$columnas].$row)->applyFromArray($styleFontDefaultArray);

// ----------------------- Title

$hojaExcel->mergeCells('A1:'.$arrayAbeceadario[$columnas].'1');
$hojaExcel->getRowDimension('1')->setRowHeight(40);
$hojaExcel->getStyle("A1")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($colorExcel);
$hojaExcel->setCellValue('A1',$nameTitle);


$hojaExcel->getStyle('A1')->getFont()->applyFromArray($styleFontArray);

$hojaExcel->getStyle('A1')->getAlignment()->applyFromArray($styleAlingArray);



// ------------------------------------------------- Sub Titulos

$rowArray = $dataArray[0];

// Ejemplo:
// [
//     ['Año', 'edad', 'id'],
//     ['2004',  10,      400],
//     ['2005',  70,      460],
//     ['2006',  60,       1120],
//     ['2007',  30,      540]
// ]
$hojaExcel->fromArray(
    $rowArray,   // El valor
    NULL,        // Los valores null con este valor no se establecerán
    'A2'         // En que parte comenzara
);


$hojaExcel->getStyle('A2:'.$arrayAbeceadario[$columnas].'2')->applyFromArray($styleFontSubTitleArray);


// ------------------------------------------------- Tamaño de column

foreach ($hojaExcel->getColumnIterator() as $column) {
    // $hojaExcel->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    $hojaExcel->getColumnDimension($column->getColumnIndex())->setWidth(30);

}

//columna id
$hojaExcel->getColumnDimension('A')->setWidth(15);



// Titulos de las columnas
unset($dataArray[0]);


$hojaExcel->fromArray(
        $dataArray,   // El Valor
        NULL,        // Los valores null con este valor no se establecerán
        'A3'         // En que parte comenzara
);


// ------------------------------------------------- Moneda columnas


if($ajustes!=null){

    foreach ($ajustes as $dataColumn) {

        $separar=strrpos($dataColumn, ':');

        if($separar!=false){

            $columnas=substr($dataColumn, 0, -1);

            $hojaExcel->getStyle($columnas.$row)
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

        }else{

            $arrayNum = array("1", "2", "3", "4", "5", "6", "7", "8", "9","0");
            $filaColumna = str_replace($arrayNum, "", $dataColumn); //F3 = F
            // $filaColumna=substr($dataColumn, 0, -1);

            // F3:F10
            $hojaExcel->getStyle($dataColumn.':'.$filaColumna.$row)
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

        }

    }
}


// -------------------------------------------- Seguridad Hoja

// $spreadsheet->getActiveSheet()->getProtection()->setPassword('pass');
//    $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
//    $spreadsheet->getActiveSheet()->getProtection()->setSort(true);
//    $spreadsheet->getActiveSheet()->getProtection()->setInsertRows(true);
//    $spreadsheet->getActiveSheet()->getProtection()->setFormatCells(true);

// ------------------------------------------------- Descarga
date_default_timezone_set('America/Los_Angeles');
$nowObj = new DateTime('now');
$now = $nowObj -> format('d-m-Y H:i');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$nameFile.$now.'.xlsx"');
header('Cache-Control: max-age=0');
$writer =IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');

?>
