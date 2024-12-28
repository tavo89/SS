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
if(isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI']))$fechaI=$_SESSION['fechaI'];
if(isset($_SESSION['fechaF'])&&!empty($_SESSION['fechaF']))$fechaF=$_SESSION['fechaF'];



if(isset($_SESSION['fechaF'])&&isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI'])&&!empty($_SESSION['fechaF'])&&isset($_SESSION['fe_crea'])&&$_SESSION['fe_crea']==1)$A=" AND (DATE((SELECT fecha_crea FROM usuarios WHERE id_usu = id_cli))>='$fechaI' AND DATE((SELECT fecha_crea FROM usuarios WHERE id_usu = id_cli))<='$fechaF')";
else if(isset($_SESSION['fechaF'])&&isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI'])&&!empty($_SESSION['fechaF'])&&isset($_SESSION['fe_crea'])&&$_SESSION['fe_crea']==0)$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF')";

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

/////////////////////////////////////////////////// PRODUCTOS ///////////////////////////////////////////////////////////

$refPrecio[]=0;

$rs=$linkPDO->query("SELECT id_pro,precio_v  FROM inv_inter WHERE nit_scs=$codSuc");
while($row=$rs->fetch())
{
	
$refPrecio[$row['id_pro']]=$row['precio_v'];

}



/////////////////////////////////////////////////// CLIENTES ///////////////////////////////////////////////////////////////
$id_clientes[]="";
$nom_clientes[]="";
$abonos[][]=0;
$NUM_FACTURAS[]=0;
$sql="SELECT prefijo,num_fac_ven,id_cli,nom_cli from fac_venta WHERE nit=$codSuc AND ".VALIDACION_VENTA_VALIDA." $B $A $E GROUP BY id_cli order by 3";
$rs=$linkPDO->query($sql);
$i=0;
while($row=$rs->fetch())
{
	$id_clientes[$i]=ucwords(strtolower(htmlentities($row["id_cli"], ENT_QUOTES,"$CHAR_SET")));
	//$nom_clientes[$i]=ucwords(strtolower(htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET")));
	$nom_clientes[$i]=ucwords(strtolower($row["nom_cli"]));
	$abonos[$row['prefijo']][$row['num_fac_ven']]=0;
	$NUM_FACTURAS[$row["id_cli"]]=0;
	$i++;
	
}

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
<td>
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top">
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

//////////////////////////////////////////////////////////// FILTROS BUSQUEDA //////////////////////////////////////////////////////////////////////
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
	echo $GSX650;
	}

if(isset($_SESSION['fe_crea'])&&!empty($_SESSION['fe_crea'])&&$_SESSION['fe_crea']==1){echo "<br><b>CLIENTES NUEVOS</b>";}
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
<!--<th width="50">No.</th>-->
<th width="120" align="left">C.C/NIT</th>
<th width="170" align="left">Cliente</th>
<!--<th width="60"> </th>-->
<th width="120">Tot. Fac.</th>
<th>Tot. Sdcto</th>
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
//////////////////////////////////////////////////////////////// INICIO INFORME ///////////////////////////////////////////////////////////////////////
$tot_saldo=0;
$tot_abono=0;
$TOT_ABONO=0;
$TOT_SALDO=0;
$TOT_UTIL=0;
$TOT_VENTAS_SIN=0;
$pagBreak=0;
$once=0;
for($i=0;$i<count($id_clientes);$i++)
{
	
 
	
$sql="SELECT b.anulado,a.fab,b.ciudad,b.nit, b.id_cli,b.nom_cli,a.ref,a.des,a.cant,a.iva,a.precio,a.sub_tot as  art_sub,a.dcto,a.costo,b.num_fac_ven,b.prefijo,b.sub_tot,b.iva,b.tot,DATE(b.fecha) as fecha,b.descuento,b.vendedor FROM 
(SELECT ref,des,cant,iva,precio,sub_tot,dcto,costo,fab,id_pro,prefijo,num_fac_ven FROM art_fac_ven a INNER JOIN (select fab,id_pro from ".tabProductos.") b ON a.ref=b.id_pro) a INNER JOIN (SELECT id_cli,anulado,ciudad,nit,nom_cli,num_fac_ven,prefijo,sub_tot,iva,tot,fecha,descuento,vendedor FROM fac_venta WHERE id_cli='$id_clientes[$i]') b ON a.num_fac_ven=b.num_fac_ven  WHERE a.prefijo=b.prefijo AND b.nit=$codSuc AND b.".VALIDACION_VENTA_VALIDA."  $A $C $E ORDER BY fecha";
//echo $sql."<br><br><br>";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){
	$rs=$linkPDO->query($sql);



	$tot_saldo=0;
	$tot_abono=0;
	$tot_util=0;
	$tot_venta_sin=0;
	$nf=0;
	while($row=$rs->fetch())
	{
		$pre=$row['prefijo'];
		$numFac=$row['num_fac_ven'];
		$sede=$row['nit'];
		$totFac=$row['tot']*1;
		$fecha=$row['fecha'];
		$saldo=$totFac;//-$abonos[$pre][$numFac];
		$abono=0;//$abonos[$pre][$numFac];
		$NUM_FACTURAS[$row["id_cli"]]++;
		
	$ref = htmlentities($row["ref"], ENT_QUOTES,"$CHAR_SET");
	$des = htmlentities($row["des"], ENT_QUOTES,"$CHAR_SET");
	$iva = htmlentities($row["iva"]*1);
	$iva_art=($iva/100);
	$art_IVA=0;
	if($iva_art!=0)$art_IVA=$pvp/$iva_art;
	
	$cant = htmlentities($row["cant"]*1);
	$pvp = htmlentities($row["precio"]*1);
	$costo=$row['costo']*1;
	$sub_tot = htmlentities($row["sub_tot"]*1);
	$art_sub=redondeo($row["art_sub"]*1);
	$descuento = $row["dcto"]*1;
	$util=(( ($refPrecio[$ref]-$costo) /$refPrecio[$ref] )) - $descuento/100;
	//if($util<0)$util=$util*-1;
	$utilidad=$util*($cant*$refPrecio[$ref]);
	
	
		
		$tot_abono+=$abono;
		
		
		
		$tot_saldo+=$art_sub;
		$tot_util+=$utilidad;
		$tot_venta_sin+=$cant*$refPrecio[$ref];
		
		$TOT_ABONO+=$abono;
		$TOT_SALDO+=$art_sub;
		$TOT_UTIL+=$utilidad;
		$TOT_VENTAS_SIN+=$cant*$refPrecio[$ref];
 
  		
	}///////////////////////////////////// FIN WHILE /////////////////////////////////////////////////////////////////////////////////////

	?> 
<tr>
<td colspan="" align="left"><?php echo $id_clientes[$i] ?></td>
<td align="left"><?php echo $nom_clientes[$i] ?></td>
<td align="left"><?php echo $NUM_FACTURAS[$id_clientes[$i]] ?></td><td align="left"><?php echo punto_a_coma($tot_venta_sin) ?></td>
<td align="left"><?php  echo punto_a_coma($tot_saldo)?></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<td align="left"><?php echo redondeo(($tot_util/$tot_venta_sin)*100) ?>%</td>
<td align="left"><?php  echo punto_a_coma(redondeo($tot_util))?></td>
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
		

	
	}
}


?>
</tbody>
</table>

<table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%" >
<thead>
<tr>
<th width="100"></th>
<th width="150"></th>
<!--<th width="60"> </th>-->
<th width="100">Tot. Facturas</th>
<th>Tot. Sdcto</th>
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

<tr style="font-size:12px; font-weight:bold;font-family: 'Arial Black', Gadget, sans-serif;">
<tH colspan=""><b>TOTAL VENTAS</b></th>
<td align="left"><?php echo array_sum($NUM_FACTURAS) ?></td>

<td><?php  echo punto_a_coma($TOT_VENTAS_SIN)?></td>
<td><?php  echo punto_a_coma(redondeo($TOT_SALDO) )?></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
$util_per=0;
if($TOT_VENTAS_SIN>0)$util_per=($TOT_UTIL/$TOT_VENTAS_SIN)*100;
?>
<td><?php echo redondeo($util_per) ?>%</td>
<td><?php  echo punto_a_coma(redondeo($TOT_UTIL))?></td>
<?php 
}
?>


</tr> 
</tbody>
</table>
<hr align="center" width="100%" />

</div>
</body>
</html>