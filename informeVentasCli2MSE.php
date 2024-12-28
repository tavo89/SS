<?php
require_once("Conexxx.php");


header("Content-Type:   application/vnd.ms-excel; charset=$CHAR_SET");
header("Content-Disposition: attachment; filename=Resumen Informe de Ventas por Cliente.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

$fechaI="";
$fechaF="";
$cli="";
$pro="";
$Az="";
$Za="";

$A="";
$B="";
$C="";
$D="";
$E="";
$ORDER_BY="";
$orden="";
$TOT_FACTURAS=0;
if(isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI']))$fechaI=$_SESSION['fechaI'];
if(isset($_SESSION['fechaF'])&&!empty($_SESSION['fechaF']))$fechaF=$_SESSION['fechaF'];
if(isset($_SESSION['fechaF'])&&isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI'])&&!empty($_SESSION['fechaF']))$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF')";
if(isset($_SESSION['cli'])&&!empty($_SESSION['cli'])){$cli=$_SESSION['cli'];$B=" AND nom_cli='$cli'";}




if(isset($_SESSION['ciudad'])&&!empty($_SESSION['ciudad']))
{
	$city=$_SESSION['ciudad'];
	$E="AND (";
	foreach($city as $key=> $resultado)
	{
		$E.="ciudad='$resultado' OR ";
	}
	$E.=" ciudad='$resultado') ";
	if(empty($resultado))$E="";
	
	}



if(isset($_SESSION['dcto'])&&!empty($_SESSION['dcto'])){$cli=$_SESSION['dcto'];$D=" AND ='$cli'";}
if(isset($_SESSION['pro'])&&!empty($_SESSION['pro'])){$pro=$_SESSION['pro'];$C=" AND fab='$pro'";}

if(isset($_SESSION['orden_ven'])&&!empty($_SESSION['orden_ven']))
{$orden=$_SESSION['orden_ven'];

if($orden=="mayor")$ORDER_BY=" ORDER BY tot DESC";
else $ORDER_BY=" ORDER BY tot ASC";
}


/////////////////////////////////////////////////// PRODUCTOS ///////////////////////////////////////////////////////////

$refPrecio[]=0;

$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE nit_scs=$codSuc");
while($row=$rs->fetch())
{
	
$refPrecio[$row['id_inter']]=$row['precio_v'];

}



/////////////////////////////////////////////////// CLIENTES ///////////////////////////////////////////////////////////////
$id_clientes[]="";
$nom_clientes[]="";
$abonos[][]=0;
$UTIL[]=0;
$TOT_UTIL=0;

	$tot_saldo=0;
	$tot_abono=0;
	$tot_util=0;
	$tot_venta_sin=0;
	
	$TOT_COSTO_SIN[]=array();
	$TOT_COSTO_IVA[]=array();
	$TOT_VENTAS_COSTO_IVA=0;
	$TOT_VENTAS_PVP=0;
$rs=$linkPDO->query("SELECT * from fac_venta WHERE nit=$codSuc AND ".VALIDACION_VENTA_VALIDA." $B $A $E GROUP BY id_cli order by 3");
$i=0;
while($row=$rs->fetch())
{
	$id_clientes[$i]=ucwords(strtolower(htmlentities($row["id_cli"], ENT_QUOTES,"$CHAR_SET")));
	//$nom_clientes[$i]=ucwords(strtolower(htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET")));
	$nom_clientes[$i]=ucwords(strtolower($row["nom_cli"]));
	$UTIL[$row["id_cli"]]=0;
	$TOT_COSTO_SIN[$row["id_cli"]]=0;
	$TOT_COSTO_IVA[$row["id_cli"]]=0;
	
	$abonos[$row['prefijo']][$row['num_fac_ven']]=0;
	
	$i++;
	
}

////////////////////////////////////////////////////////// UTILIDAD /////////////////////////////////////////////////////////////////////////////////

$sql="SELECT b.anulado,a.fab,b.ciudad,b.nit, b.id_cli,b.nom_cli,a.ref,a.des,a.cant,a.iva as iva_art,a.precio,a.sub_tot as  art_sub,a.dcto,a.costo,b.num_fac_ven,b.prefijo,b.sub_tot,b.iva,b.tot,DATE(b.fecha) as fecha,b.descuento,b.vendedor FROM (SELECT ref,cod_barras,prefijo,num_fac_ven,des,iva,precio,sub_tot,b.fab,cant,dcto,costo FROM art_fac_ven a INNER JOIN ".tabProductos." b ON a.ref=b.id_pro) a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven  WHERE a.prefijo=b.prefijo AND b.nit=$codSuc AND b.".VALIDACION_VENTA_VALIDA."  $A $C $E $B";

 
$resp=$sql;
$rs=$linkPDO->query($sql);

//echo "$sql";
	while($row=$rs->fetch())
	{
		
		$totFac=$row['tot']*1;
		$fecha=$row['fecha'];
		$saldo=$totFac;//-$abonos[$pre][$numFac];
		$abono=0;//$abonos[$pre][$numFac];
		$ref=$row['ref'];
		$idCli=$row['id_cli'];
		$nomCli= htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET");
	

	$cant = htmlentities($row["cant"]*1);
	$pvp = htmlentities($row["precio"]*1);
	$iva=$row['iva_art'];
	$costo=$row['costo']*1;
	$costoIVA=$costo*(1+($iva/100));
	$sub_tot = htmlentities($row["sub_tot"]*1);
	
	$descuento = $row["dcto"]*1;
	//$util=(( ($refPrecio[$ref]-$costo) /$refPrecio[$ref] )) - $descuento/100;
	$util=util($pvp*$cant,$costo*$cant,$iva,"per");
	//if($util<0)$util=$util*-1;
	
	$utilidad=util($pvp*$cant,$costo*$cant,$iva,"tot");
	
	
	
	$UTIL[$idCli]+=$utilidad;

	$TOT_UTIL+=$utilidad;
	$TOT_VENTAS_COSTO_IVA+=$costoIVA*$cant;
	$TOT_VENTAS_PVP+=$pvp*$cant;
	
	$TOT_COSTO_IVA[$idCli]+=$costoIVA*$cant;
	$TOT_COSTO_SIN[$idCli]+=$costo*$cant;
		
		
	
		
	}///////////////////////////////////// FIN WHILE UTILIDADES /////////////////////////////////////////////////////////////////////////////////////
?>
<?php
$titulo="RESUMEN DE VENTAS POR CLIENTE";
$url_header="C:/wamp/www/posgir/informeVentasCli2MSW_files/headerfooter.html";
$sitio="informeVentasCli2MSE.php";
$carpeta="informeVentasCli2MSW_files";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv=Content-Type content="text/html; charset=<?php echo $CHAR_SET ?>">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 11">
<title><?php echo $titulo ?></title>
<!--[if gte mso 9]>
<xml>
 <o:DocumentProperties>
...
</xml><![endif]-->
<!--[if gte mso 9]><xml>
 <x:ExcelWorkbook>
...
</xml><![endif]-->
<style>
@page
{
   {mso-header-data:"&CInventory";
	mso-footer-data:"Page &P of &N";
	margin:.31in 0in .31in 0in;
	mso-header-margin:0in;
	mso-footer-margin:0in;
	mso-page-orientation:landscape;}
tr
	{mso-height-source:auto;}
col
	{mso-width-source:auto;}
br
	{mso-data-placement:same-cell;}	
}
@page Section1 { }
div.Section1 { page:Section1; }
p.MsoHeader, p.MsoFooter { border: 1px solid black; }
td { page-break-inside:avoid; }
tr { page-break-after:avoid; }
 
    </style>
    
<!--[if gte mso 9]><xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Inventory</x:Name>
    <x:WorksheetOptions>
     <x:Print>
      <x:ValidPrinterInfo/>
      <x:PaperSizeIndex>9</x:PaperSizeIndex>
      <x:HorizontalResolution>600</x:HorizontalResolution>
      <x:VerticalResolution>600</x:VerticalResolution>
     </x:Print>
     <x:Selected/>
     <x:Panes>
      <x:Pane>
       <x:Number>1</x:Number>
       <x:ActiveRow>1</x:ActiveRow>
      </x:Pane>
     </x:Panes>
     <x:ProtectContents>False</x:ProtectContents>
     <x:ProtectObjects>False</x:ProtectObjects>
     <x:ProtectScenarios>False</x:ProtectScenarios>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
  <x:WindowHeight>8835</x:WindowHeight>
  <x:WindowWidth>15180</x:WindowWidth>
  <x:WindowTopX>120</x:WindowTopX>
  <x:WindowTopY>105</x:WindowTopY>
  <x:ProtectStructure>False</x:ProtectStructure>
  <x:ProtectWindows>False</x:ProtectWindows>
 </x:ExcelWorkbook>
</xml><![endif]-->


</head>

<body>
<div class="Section1"> <!--style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">-->
<table align="center" width="100%">
<tr>
<td colspan="4">
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top" colspan="4">
<p align="left" style="font-size:32px; font-weight:bold;">
<span align="center"><B>Informe de Ventas por Clientes</B></span> - RESUMEN
</p>
</td>

</tr>
</table>

<span style="font-size:18px; font-weight:bold">Fecha Informe: <?PHP echo $hoy ?></span>
<br>
<p align="left">
<b>B&Uacute;SQUEDA</b><br />
<?php 

if(isset($_SESSION['fechaF'])&&isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI'])&&!empty($_SESSION['fechaF']))echo "<b>Desde:</b> $fechaI <b>Hasta:</b> $fechaF<br>";
if(isset($_SESSION['cli'])&&!empty($_SESSION['cli'])){$cli=$_SESSION['cli'];echo "<b>Cliente:</b> $cli <br>";}
if(isset($_SESSION['pro'])&&!empty($_SESSION['pro'])){$pro=$_SESSION['pro'];echo "<b>Proveedor:</b> $pro <br>";}
if(isset($_SESSION['ciudad'])&&!empty($_SESSION['ciudad']))
{
	$city=$_SESSION['ciudad'];
	$GSX650="<b>Ciudad(es):</b> |";
	foreach($city as $key=>$resultado)
	{
			$GSX650.="$resultado |";
	}
	echo $GSX650."<br>";
	}
if(isset($_SESSION['fe_crea'])&&!empty($_SESSION['fe_crea'])&&$_SESSION['fe_crea']==1){echo "<b>CLIENTES NUEVOS</b>";}
 ?>
</p>
<table align="center" width="100%">
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">
</table>
</td>
</tr>
</table>
<?php
//echo $res
?>
<hr align="center" width="100%">
<table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%">
<tr style="font-size:14px;">
<th width="100">C.C/NIT</th>
<th width="150">Cliente</th>
<!--<th width="60"> </th>-->
<th width="100">Tot. Facturas</th>
<!--<th>Tot. Sdcto</th>-->
<th>TOTAL</th>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<th width="60"> Util. %</th>
<th> Utilidad</th>
<?php 
}
?>
</tr>
<?php
$tot_saldo=0;
$tot_abono=0;
$TOT_ABONO=0;
$TOT_SALDO=0;
$ORDER_BY="ORDER BY 6 ";
$ORDER_BY="ORDER BY 4 ";

	//$sql="SELECT b.iva,b.nit, b.id_cli,b.nom_cli,SUM(a.sub_tot) as tot,b.fecha,SUM(b.descuento) descuento,b.vendedor FROM art_fac_ven a INNER JOIN  fac_venta b  ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND a.nit=b.nit AND b.nit=$codSuc AND b.".VALIDACION_VENTA_VALIDA." $A $C $E GROUP BY id_cli $ORDER_BY";
	
	
$sql="SELECT b.iva,b.nit, b.id_cli,b.nom_cli,SUM(b.tot) as tot,b.fecha,SUM(b.descuento) descuento,b.vendedor,COUNT(*) as totF FROM  fac_venta b  WHERE b.nit=$codSuc AND b.".VALIDACION_VENTA_VALIDA." $A $C $E $B GROUP BY id_cli $ORDER_BY";

	 
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){
	$rs=$linkPDO->query($sql);
	?>
    
 
    
    <?php
	$TOT_FACTURAS=0;
	$TOT_VENTAS_SIN=0;
	$tot_saldo=0;
	$tot_abono=0;
	$tot_util=0;
	$tot_venta_sin=0;
	$nf=0;
	$pagBreak=0;
	$once=0;
	//eco_alert("once: $once");
	while($row=$rs->fetch())
	{
		$pagBreak++;
		$totFac=$row['tot']*1;
		$fecha=$row['fecha'];
		$saldo=$totFac;//-$abonos[$pre][$numFac];
		$abono=0;//$abonos[$pre][$numFac];
		$idCli=$row['id_cli'];
		$nomCli= htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET");	
		$iva = htmlentities($row["iva"]*1);
		$art_sub=$row["tot"]*1;
		$descuento = $row["descuento"]*1;
		$tot_abono+=$abono;
		$totF=$row['totF'];
		$TOT_FACTURAS+=$totF;
		
		//$cant = htmlentities($row["cant"]*1);
		//$ref = htmlentities($row["ref"], ENT_QUOTES,"$CHAR_SET");

		$tot_saldo=$art_sub;
		/*$util=($tot_util/($tot_saldo+$descuento));
		if($util<0)$util=$util*-1;
		$utilidad=$util*$art_sub;
		$tot_util+=$utilidad;*/
		
		$tot_venta_sin+=$descuento+$art_sub;

		$TOT_ABONO+=$abono;
		$TOT_SALDO+=$art_sub;
		$TOT_VENTAS_SIN=$tot_saldo+$descuento;
		
		
		?> 
<tr style="font-size:12px;" >
<td align="center" colspan=""><?php echo $idCli ?></td>
<td align="center" colspan=""><?php echo $nomCli ?></td>
<!--<td align="center"><?php //echo 0 ?></td>-->

<td align="center"><?php echo $totF ?></td>
<!--<td><?php echo money($descuento+$art_sub) //echo money2($cant*$refPrecio[$ref])?></td>-->
<td align="center"><?php  echo punto_a_coma($tot_saldo)?></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<td align="center"><?php echo util_tot($tot_saldo,$TOT_COSTO_IVA[$idCli],"per"); ?></td>
<td align="center"><?php  echo punto_a_coma(util_tot($tot_saldo,$TOT_COSTO_IVA[$idCli],"tot"))?></td>
<?php 
}
?>
</tr> 
    
    <?php
	
if($pagBreak==23&&$once==0){
	//eco_alert("once: $once");
	
	  //aecho "<tr  ><td colspan=7> <br clear=all style='mso-special-character:line-break;page-break-before:always'></td></tr>";
	  ?>
      </table>
      <br clear=all style="mso-special-character:line-break;page-break-before:always">
     <table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%">
      <?php
	  
	 $once=1;
	$pagBreak=0; 
	  }	
	
	
 if($pagBreak==30){
	  //aecho "<tr  ><td colspan=7> <br clear=all style='mso-special-character:line-break;page-break-before:always'></td></tr>";
	  ?>
      </table>
      <br clear=all style="mso-special-character:line-break;page-break-before:always">
     <table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%">
      <?php
	  
	  $pagBreak=0; 
	  }
		
	}///////////////////////////////////// FIN WHILE /////////////////////////////////////////////////////////////////////////////////////

	
	}// fin validacion if rs, FOR
	
//}


?>
</table>
<table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%" >
<thead>
<tr>
<th width="100"></th>
<th width="150"></th>
<!--<th width="60"> </th>-->
<th width="100">Tot. Facturas</th>
<!--<th>Tot. Sdcto</th>-->
<th>TOTAL</th>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<th width="60"> Util. %</th>
<th> Utilidad</th>
<?php 
}
?>
</tr>
</thead>
<tr style="font-size:16px; font-weight:bold;font-family: 'Arial Black', Gadget, sans-serif;">
<tH colspan="2"><b>TOTAL VENTAS</b></th>
<td align="center"><?php echo "$TOT_FACTURAS" ?></td>
<!--<td><?php  echo money($tot_venta_sin)?></td>-->
<td align="center"><?php  echo money($TOT_SALDO)?></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
$uti_per=0;
if($tot_venta_sin>0)$uti_per=($TOT_UTIL/$tot_venta_sin)*100;
?>
<td align="center"><?php echo util_tot($TOT_VENTAS_PVP,$TOT_VENTAS_COSTO_IVA,"per"); ?>%</td>
<td align="center"><?php  echo money(util_tot($TOT_VENTAS_PVP,$TOT_VENTAS_COSTO_IVA,"tot"))."." ?></td>
<?php 
}
?>

</tr> 
</table>

<hr align="center" width="100%" />

</div>
</body>
</html>