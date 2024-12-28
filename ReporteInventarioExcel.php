<?php
error_reporting(E_ERROR | E_PARSE);
include("Conexxx.php");
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}

$tipoReport=r("tipoReport");
//////////////////////////////////////////////////////// FILTROS TABLA ////////////////////////////////////////

$FILTRO_CLASE=multiSelcFilter("clases","filtro_clase","id_clase",$opc);
$FILTRO_SUB_CLASE=multiSelcFilter("sub_clases","filtro_sub_clase","id_sub_clase",$opc);
//$FILTRO_LAB="";
$FILTRO_LAB=multiSelcFilter("fabricantes","filtro_lab","fab",$opc);

//filtroExist
$FILTRO_PROVEDORES=multiSelcFilter("provedores","filtro_provedores","nit_proveedor",$opc);
$FILTRO_EXIST=existFilter("filtroExist","filtro_existencias","",$opc);

$FILTRO_DES=desFilter("filtroDes","filtro_descripcion","",$opc);

$FILTRO_VENCIDOS=venciFilter("filtroVencidos","filtroVencidos",$opc);

$FILTROS_TABLA=" $FILTRO_SUB_CLASE $FILTRO_EXIST $FILTRO_CLASE $FILTRO_LAB $FILTRO_DES $FILTRO_VENCIDOS $FILTRO_PROVEDORES";
//echo "$FILTROS_TABLA";
//-----------------------------------------------------------------------------------------------------------//


$columns="fab,ubicacion,".tabProductos.".id_pro id_glo,inv_inter.id_inter  id_sede,detalle,id_clase,fraccion,fab,max,min,costo,precio_v,exist,iva,gana,fecha_vencimiento,unidades_frac,talla,color,".tabProductos.".presentacion,aplica_vehi,pvp_may";

$sql = "SELECT  $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE nit_scs=$codSuc $FILTROS_TABLA   ORDER BY detalle "; 
$rs=$linkPDO->query($sql);




$spreadsheet = new Spreadsheet();
$spreadsheet->getActiveSheet()->getPageMargins()->setTop(1);
$spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.75);
$spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.75);
$spreadsheet->getActiveSheet()->getPageMargins()->setBottom(1);
$spreadsheet->getActiveSheet()->getPageSetup()
    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

$styleArrayHeader = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
    'borders' => [
        'top' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startColor' => [
            'argb' => 'FFA0A0A0',
        ],
        'endColor' => [
            'argb' => 'FFFFFFFF',
        ],
    ],
];


$styleArray = [
    'font' => [
        'bold' => false,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
    
];

$spreadsheet->getActiveSheet()->getStyle('A1:P1')->applyFromArray($styleArrayHeader);

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(32);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);
$spreadsheet->getActiveSheet()->getStyle('A1:P1')
    ->getAlignment()->setWrapText(true);




///////////////////////////7 OCULTAR CELDAS INCATIVAS POR TIPO NEGOCIO ////////////////////////////


if($usar_color!=1){$spreadsheet->getActiveSheet()->getColumnDimension('G')->setVisible(false);};
if($usar_talla!=1){$spreadsheet->getActiveSheet()->getColumnDimension('F')->setVisible(false);};
if($usar_fecha_vencimiento!=1){$spreadsheet->getActiveSheet()->getColumnDimension('H')->setVisible(false);};
if($MODULES["APLICA_VEHI"]!=1){$spreadsheet->getActiveSheet()->getColumnDimension('O')->setVisible(false);};
if($MODULES["PVP_MAYORISTA"]!=1){$spreadsheet->getActiveSheet()->getColumnDimension('L')->setVisible(false);};


	
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'REF');
$sheet->setCellValue('B1', 'Cod.');

$sheet->setCellValue('C1', 'Descripcion');
$sheet->setCellValue('D1', 'Presenta');
$sheet->setCellValue('E1', 'Clase');
$sheet->setCellValue('F1', 'Talla');
$sheet->setCellValue('G1', 'Color');
$sheet->setCellValue('H1', 'Fecha Venci');
$sheet->setCellValue('I1', 'Costo+IVA');
$sheet->setCellValue('J1', 'IVA');
$sheet->setCellValue('K1', 'PvP');
$sheet->setCellValue('L1', 'PvpMayo');
$sheet->setCellValue('M1', 'Cant');
$sheet->setCellValue('N1', 'Marca');
$sheet->setCellValue('O1', 'Aplicacion');
$sheet->setCellValue('P1', 'CostoSinIVA');





$ii=1;
while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $ref = $row["id_glo"]; 
            $des = $row["detalle"]; 
			$aplicaVehi=$row["aplica_vehi"];
			$clase = $row["id_clase"];
			$marca = $row["fab"];
			$codBar =$row["id_sede"];
			$frac = $row["fraccion"];
			if($frac==0)$frac=1;
			$fab = $row["fab"]; 
			$pvp=$row['precio_v'];
			$pvpMayorista=$row["pvp_may"];
			
			$unidades=$row['unidades_frac'];
			$feVenci=$row['fecha_vencimiento'];
			$talla=$row['talla'];
			$color=$row['color'];
			$iva= is_numeric($row['iva'])?$row['iva']:0 ;
			$costoi= is_numeric($row['costo'])?$row['costo']:0 ;
			$costo=$costoi*(1+($iva/100));
			$cant=$row["exist"]*1;
			
			$factor=($unidades/$frac)+$cant;
			$ubica=$row["ubicacion"];
				$fab=$row["fab"];
				$presentacion=$row["presentacion"];
			$showUni=";$unidades";
if($unidades==0)$showUni="";

		
$sheet->setCellValue('A'.$ii, $ref);
$sheet->setCellValue('B'.$ii, $codBar);
$sheet->setCellValue('C'.$ii, $des);
$sheet->setCellValue('D'.$ii, $presentacion);
$sheet->setCellValue('E'.$ii, $clase);
$sheet->setCellValue('F'.$ii, $talla);
$sheet->setCellValue('G'.$ii, $color);
$sheet->setCellValue('H'.$ii, $feVenci);
$sheet->setCellValue('I'.$ii, $costo);
$sheet->setCellValue('J'.$ii, $iva);
$sheet->setCellValue('K'.$ii, $pvp);
$sheet->setCellValue('L'.$ii, $pvpMayorista);
$sheet->setCellValue('M'.$ii, "$row[exist] $showUni");
$sheet->setCellValue('N'.$ii, $marca);
$sheet->setCellValue('O'.$ii, $aplicaVehi);
$sheet->setCellValue('P'.$ii, $costoi);


$spreadsheet->getActiveSheet()->getStyle("A$ii:P$ii")->applyFromArray($styleArray);

$spreadsheet->getActiveSheet()->getStyle('I'.$ii)->getNumberFormat()
    ->setFormatCode('#,##0.00');

$spreadsheet->getActiveSheet()->getStyle('J'.$ii)->getNumberFormat()
    ->setFormatCode('#,##0.00');

$spreadsheet->getActiveSheet()->getStyle('P'.$ii)->getNumberFormat()
    ->setFormatCode('#,##0.00');

				
}


$writer = new Xlsx($spreadsheet);
$writer->save("INVENTARIO_".$USU.".xlsx");


    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="INVENTARIO_'.$NOM_NEGOCIO.'_'.$FechaHoy.'.xlsx"');
    $writer->save("php://output");
	
	unlink("INVENTARIO_".$USU.".xlsx");


?>