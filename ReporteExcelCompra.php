<?php
require_once("Conexxx.php");
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));


$ORDER_BY=" id ";
/////////////////////////////////////// FILTRO REP ////////////////////////////////////
$filtro_rep="";
if(isset($_SESSION['filtro_rep']))$filtro_rep=$_SESSION['filtro_rep'];
if(isset($_REQUEST['filtro_rep'])){$filtro_rep=$_REQUEST['filtro_rep'];$_SESSION['filtro_rep']=$filtro_rep;};

$filtroWhereSQL= $filtro_rep=='cero' ? ' AND cant=0 AND unidades_fraccion=0':'';

$E="";
if($filtro_rep=="rep")
{


$E=" INNER JOIN (SELECT  ref,cod_barras,fecha_vencimiento,COUNT(`ref`) c FROM art_fac_com WHERE num_fac_com='$_SESSION[num_fac]' AND cod_su=$codSuc AND nit_pro='$_SESSION[nit_pro]' GROUP BY ref,cod_barras HAVING c>1 ) b ON a.ref=b.ref ";

$ORDER_BY=" a.ref,a.des ";
}

else {$E="";$ORDER_BY=" id ";}
///////////////////////////////////////////////////////////////////////////////////////


excel("Productos Compra $_SESSION[num_fac] $hoy");
 
 

$sql="SELECT  * FROM art_fac_com a $E  WHERE a.num_fac_com='$_SESSION[num_fac]'  AND a.cod_su=$codSuc AND a.nit_pro='$_SESSION[nit_pro]' $filtroWhereSQL ORDER BY $ORDER_BY ASC";

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
 
 $spreadsheet->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styleArrayHeader);
 
 $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
 $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
 $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
 $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
 $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
 $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
 $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
 $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
 $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
 $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
 $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
 $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);
 $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);
 $spreadsheet->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setWrapText(true);


 ///////////////////////////7 OCULTAR CELDAS INCATIVAS POR TIPO NEGOCIO ////////////////////////////


if($usar_color!=1){$spreadsheet->getActiveSheet()->getColumnDimension('D')->setVisible(false);};
if($usar_talla!=1){$spreadsheet->getActiveSheet()->getColumnDimension('E')->setVisible(false);};
if($usar_fecha_vencimiento!=1){$spreadsheet->getActiveSheet()->getColumnDimension('F')->setVisible(false);};



$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Ref');
$sheet->setCellValue('B1', 'Cod.');
$sheet->setCellValue('C1', 'Descripción');
$sheet->setCellValue('D1', 'Talla');
$sheet->setCellValue('E1', 'Color');
$sheet->setCellValue('F1', 'Fecha Vencimiento');
$sheet->setCellValue('G1', 'Ubica');
$sheet->setCellValue('H1', 'Fabricante');
$sheet->setCellValue('I1', 'Costo+IVA');
$sheet->setCellValue('J1', 'IVA');
$sheet->setCellValue('K1', 'PvP');
$sheet->setCellValue('L1', 'Cantidad/Fraccion');
$sheet->setCellValue('M1', 'ToT. Costo');
$sheet->setCellValue('N1', 'ToT. Pvp');

$ii=1;
while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $id_inter = $row["cod_barras"]; 
            $des = $row["des"]; 
			$clase = $row["clase"];
			$id =$row["ref"];
			$frac = $row["fraccion"];
			if($frac==0)$frac=1;
			$fab = $row["fabricante"]; 
			$pvp=$row['pvp'];
			
			$unidades=$row['unidades_fraccion'];
			$feVenci=$row['fecha_vencimiento'];
			$talla=$row['talla'];
			$color=$row['color'];
			$iva=1+($row['iva']/100);

			$cant=$row["cant"]*1;
			$factor=($unidades/$frac)+$cant;
			$costo=$row['costo']*$iva;
			$totCosto = $costo*($factor);
			$totPvp = $pvp*($factor);
			
			
			$ubica=$row["ubicacion"];


			$sheet->setCellValue('A'.$ii, $id_inter);
			$sheet->setCellValue('B'.$ii, $id);
			$sheet->setCellValue('C'.$ii, $des);
			$sheet->setCellValue('D'.$ii, $talla);
			$sheet->setCellValue('E'.$ii, $color);
			$sheet->setCellValue('F'.$ii, $feVenci);
			$sheet->setCellValue('G'.$ii, $ubica);
			$sheet->setCellValue('H'.$ii, $fab);
			$sheet->setCellValue('I'.$ii, $costo);
			$sheet->setCellValue('J'.$ii, $row['iva']);
			$sheet->setCellValue('K'.$ii, $pvp);
			$sheet->setCellValue('L'.$ii, "$cant ; $unidades");
			$sheet->setCellValue('M'.$ii, $totCosto);
			$sheet->setCellValue('N'.$ii, $totPvp);
			
			
			$spreadsheet->getActiveSheet()->getStyle('I'.$ii)->getNumberFormat()->setFormatCode('#,##0.00');
			$spreadsheet->getActiveSheet()->getStyle('K'.$ii)->getNumberFormat()->setFormatCode('#,##0.00');
			$spreadsheet->getActiveSheet()->getStyle('M'.$ii)->getNumberFormat()->setFormatCode('#,##0.00');
			$spreadsheet->getActiveSheet()->getStyle('N'.$ii)->getNumberFormat()->setFormatCode('#,##0.00');

			$spreadsheet->getActiveSheet()->getStyle("A$ii:N$ii")->applyFromArray($styleArray);
			 
			

         }

   $writer = new Xlsx($spreadsheet);
   $nombreDoc ="Referencias Compra_Inventario ".$_SESSION["num_fac"].".xlsx";
   $writer->save($nombreDoc);


    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'.$nombreDoc.'"');
    $writer->save("php://output");
	
	unlink($nombreDoc);