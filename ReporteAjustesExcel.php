<?php 
error_reporting(E_ALL);
include("Conexxx.php");

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$opc=r("opc");
///////////////////////////////////////////////////////////////FILTRO TIPO ///////////////////////////////////////////////////////////////////////////
$filtro_tipo="TODOS";
if(isset($_SESSION['tipo_ajus']))$filtro_tipo=$_SESSION['tipo_ajus'];
if(isset($_REQUEST['tipo_ajus'])){$filtro_tipo=$_REQUEST['tipo_ajus'];$_SESSION['tipo_ajus']=$filtro_tipo;};

$E="";
if($filtro_tipo=="TODOS")$E="";
else if($filtro_tipo=="fys")$E=" AND cant!=0";
else if($filtro_tipo=="f")$E=" AND cant<0";
else if($filtro_tipo=="s")$E=" AND cant>0";
else if($filtro_tipo=="cero")$E=" AND cant=0";


//------------------------------------------------------------------------------------------------------------------------------------------------------


/////////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_ajus";
$PAG_fechaF="fechaF_ajus";
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" data-inline=\"true\" data-mini=\"true\" >";
$A="";
if(isset($_REQUEST['fechaI'])){$fechaI=$_REQUEST['fechaI']; $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=$_REQUEST['fechaF'];$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" data-inline=\"true\" data-mini=\"true\" data-icon=\"delete\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}





if($opc=="QUITAR")
{
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" data-inline=\"true\" data-mini=\"true\" >";
	$fechaI="";
	$fechaF="";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	$A="";
}
//---------------

//------------------------------------------------- filtro ajus ----------------------------------------------

$NumAjus="";
$F="";
if(!empty($_REQUEST['num_ajus']) && isset($_REQUEST['num_ajus'])){$NumAjus=$_REQUEST['num_ajus'];$_SESSION['num_ajus']=$NumAjus;$F=" AND a.num_ajuste='$NumAjus' ";}

if(isset($_SESSION['num_ajus']) && !empty($_SESSION['num_ajus'])){$NumAjus=$_SESSION['num_ajus'];$F=" AND a.num_ajuste='$NumAjus' ";}

if($NumAjus=="TODOS")$F="";


//--------------------------------------------------------------------------------------------------------
//---------------------------VAR PAG---------------------------------------------------
$busq="";
$val="";
$boton="";
$idx="";
$url_dialog="#";
$url_mod="#";
$url_new="crear_ajuste.php";

if(isset($_REQUEST['valor']))$val= $_REQUEST['valor'];
$num_fac=$val;
//------------------------------SQL-----------------------------------------------------
$tabla="ajustes";
$col_id="num_ajuste";
$columns="a.num_ajuste,id_usu,nom_usu,ref,cant,fecha,anulado,estado,des,a.cod_su,ar.cod_su,precio,costo,iva,fraccion,unidades_fraccion";
$url="ajustes.php";
//----------------------------ORDEN CONSULTA-------------------------------------------
$order="num_ajuste";
$sort="";
$colArray=array(0=>'num_ajuste','ref','des','nom_usu','id_usu','fecha');
$classActive=array(0=>'','','','','','');

if(isset($_REQUEST['sortAjus']))
{
	$sort=$_REQUEST['sortAjus'];
	$order= $colArray[$sort];
	$_SESSION['sortAjus']=$sort;
	$classActive[$sort]="ui-btn-active ui-state-persist";
}

if(isset($_SESSION['sortAjus']))
{        
        $sort=$_SESSION['sortAjus'];
		$order= $colArray[$sort];
		$classActive[$_SESSION['sortAjus']]="ui-btn-active ui-state-persist";
}

if(isset($_SESSION['id']))$idx=$_SESSION['id'];
if(isset($_REQUEST['opc'])){
$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val= $_REQUEST['valor'];
$boton= $_REQUEST['opc'];
}
//--------------------------PAGINACION--------------------------------------------
$offset=0;
$pag="";
$limit = 20; 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
//--------------------------------------------------------------------------------


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

$spreadsheet->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArrayHeader);

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(32);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$spreadsheet->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setWrapText(true);




///////////////////////////7 OCULTAR CELDAS INCATIVAS POR TIPO NEGOCIO ////////////////////////////

/*
if($usar_color!=1){$spreadsheet->getActiveSheet()->getColumnDimension('G')->setVisible(false);};
if($usar_talla!=1){$spreadsheet->getActiveSheet()->getColumnDimension('F')->setVisible(false);};
if($usar_fecha_vencimiento!=1){$spreadsheet->getActiveSheet()->getColumnDimension('H')->setVisible(false);};
if($MODULES["APLICA_VEHI"]!=1){$spreadsheet->getActiveSheet()->getColumnDimension('O')->setVisible(false);};
if($MODULES["PVP_MAYORISTA"]!=1){$spreadsheet->getActiveSheet()->getColumnDimension('L')->setVisible(false);};
*/

	
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', '#');
$sheet->setCellValue('B1', '#Ajuste');
$sheet->setCellValue('C1', 'Ref.');
$sheet->setCellValue('D1', 'Descripción');
$sheet->setCellValue('E1', 'Cant.');
$sheet->setCellValue('F1', 'Costo');
$sheet->setCellValue('G1', 'IVA');
$sheet->setCellValue('H1', 'PVP');
$sheet->setCellValue('I1', 'ToT. Costo');
$sheet->setCellValue('J1', 'ToT. Pvp');
$sheet->setCellValue('K1', 'Fecha');


//-------------------------------------CONSULTA CON PAGINACION---------------------------------------

$sql = "SELECT  $columns FROM ajustes a INNER JOIN art_ajuste ar ON a.num_ajuste=ar.num_ajuste 
        WHERE a.cod_su=ar.cod_su AND a.cod_su=$codSuc AND ar.estado2!='ANULADO' $F $A $E ORDER BY $order DESC"; 

//-------------------------------------CONSULTA CON PAGINACION---------------------------------------

//$sql = "SELECT  $columns FROM ajustes a INNER JOIN art_ajuste ar ON a.num_ajuste=ar.num_ajuste WHERE a.cod_su=ar.cod_su AND a.cod_su=$codSuc   $F $A $E ORDER BY $order DESC  LIMIT $offset, $limit"; 

$sqlTotal = "SELECT COUNT(*) as total FROM ajustes a INNER JOIN art_ajuste ar ON a.num_ajuste=ar.num_ajuste WHERE a.cod_su=ar.cod_su AND a.cod_su=$codSuc  $A $F $E  "; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 
 

//---------------------------------------BUSQUEDAS---------------------------------------------------------------------------------------------
if($boton=='Buscar' && isset($busq) && !empty($busq)){
$busq=limpiarcampo($busq);
$sql_busq="SELECT $columns FROM ajustes a INNER JOIN art_ajuste ar ON a.num_ajuste=ar.num_ajuste WHERE  a.cod_su=ar.cod_su AND a.cod_su=$codSuc $F AND (a.num_ajuste LIKE '$busq%' OR ref LIKE '$busq%' OR des LIKE '$busq%' OR nom_usu LIKE '$busq%')" ;
 



$rs=$linkPDO->query($sql_busq);

	
	}
	


$bgColor="";
$totCOSTONeg=0;
$totCOSTOPos=0;
$totPVPNeg=0;
$totPVPPos=0;

$MAX_ROWS_PAG=58;
$pagBreak=0;
$once=0;
$pag=1;
$TOT_PAG=round($total/$MAX_ROWS_PAG);
$ii=1;

if($TOT_PAG<($total/$MAX_ROWS_PAG))$TOT_PAG++;
//$columns="num_fac,fecha,nom_cli,id_cli,tel_cli,nom_ase,tipo_factura,subtotal,iva,total";
while ($row = $rs->fetch()) 
{ 
$ii++;
$pagBreak++;	    
            $num_ajus = $row["num_ajuste"];
            $nom_usu = $row["nom_usu"]; 
			$des = $row["des"]; 
			$idUsu = $row["id_usu"];
			$fecha = $row["fecha"];
			$ref =  $row["ref"];
			$cant = $row["cant"]*1;
			$uni = $row["unidades_fraccion"]*1;
			$frac=$row['fraccion'];
			$factor=($uni+($cant*$frac))/$frac;
			
			$iva=1+($row['iva']/100);
			$pvp=$row['precio'];
			$costo=$row['costo']*$iva;
			$totCosto = $costo*($factor);
			$totPvp = $pvp*($factor);
			if($idx==$num_ajus)$bgColor="#999999";
			else $bgColor="#FFFFFF";
			
			if($cant<0)
			{
				$totCOSTONeg+=$costo*(-$factor);
				$totPVPNeg+=$pvp*(-$factor);
			}
			if($cant>0)
			{
				$totCOSTOPos+=$costo*($factor);
				$totPVPPos+=$pvp*($factor);
			}
			
			$sheet->setCellValue('A'.$ii, $ii);
			$sheet->setCellValue('B'.$ii, $num_ajus);
			$sheet->setCellValue('C'.$ii, $ref);
			$sheet->setCellValue('D'.$ii, $des);
			$sheet->setCellValue('E'.$ii, "$cant ; $uni");
			$sheet->setCellValue('F'.$ii, $costo);
			$sheet->setCellValue('G'.$ii, $row['iva']);
			$sheet->setCellValue('H'.$ii, $pvp);
			$sheet->setCellValue('I'.$ii, $totCosto);
			$sheet->setCellValue('J'.$ii, $totPvp);
			$sheet->setCellValue('K'.$ii, $fecha);
		
			
			
			
			$spreadsheet->getActiveSheet()->getStyle('F'.$ii)->getNumberFormat()->setFormatCode('#,##0.00');
			$spreadsheet->getActiveSheet()->getStyle('H'.$ii)->getNumberFormat()->setFormatCode('#,##0.00');
			$spreadsheet->getActiveSheet()->getStyle('I'.$ii)->getNumberFormat()->setFormatCode('#,##0.00');
			$spreadsheet->getActiveSheet()->getStyle('J'.$ii)->getNumberFormat()->setFormatCode('#,##0.00');

			$spreadsheet->getActiveSheet()->getStyle("A$ii:K$ii")->applyFromArray($styleArray);
						 
			


         } ///////// fin while
$ii++;
$spreadsheet->getActiveSheet()->getStyle('A'.$ii)->applyFromArray($styleArrayHeader);
$sheet->setCellValue('A'.$ii, 'Tot. COSTO Mercancía Faltante:');
$sheet->setCellValue('B'.$ii, money($totCOSTONeg));

$ii++;
$spreadsheet->getActiveSheet()->getStyle('A'.$ii)->applyFromArray($styleArrayHeader);
$sheet->setCellValue('A'.$ii, 'Tot. PVP Mercancía Faltante:');
$sheet->setCellValue('B'.$ii, money($totPVPNeg));

$ii++;
$spreadsheet->getActiveSheet()->getStyle('A'.$ii)->applyFromArray($styleArrayHeader);
$sheet->setCellValue('A'.$ii, 'Tot. COSTO Mercancía Sobrante:');
$sheet->setCellValue('B'.$ii, money($totCOSTOPos));

$ii++;
$spreadsheet->getActiveSheet()->getStyle('A'.$ii)->applyFromArray($styleArrayHeader);
$sheet->setCellValue('A'.$ii, 'Tot. PVP Mercancía Sobrante:');
$sheet->setCellValue('B'.$ii, money($totPVPPos));

$ii++;
$spreadsheet->getActiveSheet()->getStyle('A'.$ii)->applyFromArray($styleArrayHeader);
$sheet->setCellValue('A'.$ii, 'Excedente(Falta-Sobra) Costo:');
$sheet->setCellValue('B'.$ii, money($totCOSTONeg-$totCOSTOPos));

$ii++;
$spreadsheet->getActiveSheet()->getStyle('A'.$ii)->applyFromArray($styleArrayHeader);
$sheet->setCellValue('A'.$ii, 'Excedente(Falta-Sobra) PVP:');
$sheet->setCellValue('B'.$ii, money($totPVPNeg-$totPVPPos));

$writer = new Xlsx($spreadsheet);
$writer->save("AJUSTE_INVENTARIO_".$NOM_NEGOCIO.".xlsx");


    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="AJUSTE_INVENTARIO_'.$NOM_NEGOCIO.'_'.$FechaHoy.'.xlsx"');
    $writer->save("php://output");
	
	unlink("AJUSTE_INVENTARIO_".$NOM_NEGOCIO.".xlsx");
