<?php
require_once("Conexxx.php");
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$cod_su=$_SESSION['cod_su'];
$IVA=0;
$DESCUENTO=0;
$BASE=0;
$base_iva16=0;
$iva16=0;
$base_iva5=0;
$iva5=0;
$excentas=0;
$base_iva16B=0;
$iva16B=0;
$base_iva5B=0;
$iva5B=0;
$excentasB=0;
$ult_fac=0;
$pri_fac=0;
$DCTO=0;
$tot_comprobantes=0;

$tot_fac_taller=0;
$tot_fac_inspecciones=0;
$tot_fac_otros_talleres=0;
$tot_fac_mostrador=0;
$tot_trabajos3ros=0;
$tot_fac_contado=0;
$tot_fac_credito=0;
$tot_fac_tarjeta_credito=0;
$tot_fac_cheque=0;
$InteresesCreditos=0;

$TOTAL=0;



$sql="SELECT SUM(valor) as TOT FROM comprobante_ingreso  WHERE  cod_su=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_comprobantes_ingreso=$row['TOT'];
	
}

/////////////////////////////////////////////////////////////////// TOTALES FAC ANULADAS //////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTALa=$row['TOT'];
	$BASEa=$row['BASE'];
	$DESCUENTOa=$row['D'];
	$IVAa=$row['IVA'];
	
	
}

/////////////////////////////////////////////////////////// DEVOLUCIONES /////////////////////////////////////////////////////////////

$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula) AND MONTH(fecha)!=MONTH('$fechaI')";
$rs=$linkPDO->query($sql);

if($row=$rs->fetch())
{
	$TOTAL_minus=$row['TOT'];
	$BASE_minus=$row['BASE'];
	$DESCUENTO_minus=$row['D'];
	$IVA_minus=$row['IVA'];
	
	
}
////////////////////////////// DEVOLUCIONES EXENTAS //////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT fac_ven.fecha_anula,fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,fecha_anula FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su AND fac_ven.prefijo=art_fac_ven.prefijo AND anulado='ANULADO'  and fac_ven.nit=$codSuc AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND iva=0 AND DATE(fecha)!=DATE(fecha_anula) AND MONTH(fecha)!=MONTH('$fechaI')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasB=$row['excentas'];
	
	if($excentasB==""){$excentasB=0;}
}
// ---------------------------------------------------- TOT DEVOLUCIONES POS ----------------------------------------------------------------------------
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc AND prefijo='$codContadoSuc'  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula) AND MONTH(fecha)!=MONTH('$fechaI')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minusPOS=$row['TOT'];
	$BASE_minusPOS=$row['BASE'];
	$DESCUENTO_minusPOS=$row['D'];
	$IVA_minusPOS=$row['IVA'];
		
}
////////////////////////////// DEVOLUCIONES EXENTAS POS//////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT fac_ven.fecha_anula,fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,fecha_anula FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su AND fac_ven.prefijo=art_fac_ven.prefijo AND anulado='ANULADO' AND fac_ven.prefijo='$codContadoSuc'  and fac_ven.nit=$codSuc AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND iva=0 AND DATE(fecha)!=DATE(fecha_anula) AND MONTH(fecha)!=MONTH('$fechaI')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasMinusPOS=$row['excentas'];
	
	if($excentasMinusPOS==""){$excentasMinusPOS=0;}
}

// ---------------------------------------------------- TOT DEVOLUCIONES COMPUTADOR ----------------------------------------------------------------------------
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc AND prefijo='$codCreditoSuc'  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula) AND MONTH(fecha)!=MONTH('$fechaI')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minusCOM=$row['TOT'];
	$BASE_minusCOM=$row['BASE'];
	$DESCUENTO_minusCOM=$row['D'];
	$IVA_minusCOM=$row['IVA'];
		
}
////////////////////////////// DEVOLUCIONES EXENTAS COMPUTADOR //////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT fac_ven.fecha_anula,fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,fecha_anula FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su AND fac_ven.prefijo=art_fac_ven.prefijo AND anulado='ANULADO' AND fac_ven.prefijo='$codCreditoSuc'  and fac_ven.nit=$codSuc AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND iva=0 AND DATE(fecha)!=DATE(fecha_anula) AND MONTH(fecha)!=MONTH('$fechaI')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasMinusCOM=$row['excentas'];
	
	if($excentasMinusCOM==""){$excentasMinusCOM=0;}
}
// --------------------------- DEVOLUCIONES FAC. ANTICIPOS--------------------------------------------------------
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc AND tipo_venta='Anticipo'  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula) AND MONTH(fecha)!=MONTH('$fechaI')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minusAnti=$row['TOT'];
	$BASE_minusAnti=$row['BASE'];
	$DESCUENTO_minusAnti=$row['D'];
	$IVA_minusAnti=$row['IVA'];
		
}




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////// TOTALES VENTAS //////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'  AND num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL=$row['TOT'];
	$BASE=$row['BASE'];
	$DESCUENTO=$row['D'];
	$IVA=$row['IVA'];	
}

//////////////////////////////////////////////////// VENTAS EXENTAS ///////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su AND fac_ven.prefijo=art_fac_ven.prefijo AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql) ;
if($row=$rs->fetch())
{
	$excentas=$row['excentas'];
	
	if($excentas==""){$excentas=0;}
}

// ----------------------------------------------------- TOT POS --------------------------------------------------------------------------------------------
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc and prefijo='$codContadoSuc'  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'  AND num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_POS=$row['TOT'];
	$BASE_POS=$row['BASE'];
	$DESCUENTO_POS=$row['D'];
	$IVA_POS=$row['IVA'];	
}

//////////////////////////////////////////////////// VENTAS EXENTAS POS///////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.prefijo='$codContadoSuc' AND fac_ven.nit=$cod_su AND fac_ven.prefijo=art_fac_ven.prefijo AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$excentasPOS=$row['excentas'];
	
	if($excentasPOS==""){$excentasPOS=0;}
}

// ----------------------------------------------------- TOT COMPUTADOR ----------------------------------------------------------------------------------------
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc and prefijo='$codCreditoSuc'  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'  AND num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_COM=$row['TOT'];
	$BASE_COM=$row['BASE'];
	$DESCUENTO_COM=$row['D'];
	$IVA_COM=$row['IVA'];	
}

//////////////////////////////////////////////////// VENTAS EXENTAS COMPUTADOR////////////////////////////////////////////////////////////////////////////////
$sql="SELECT fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.prefijo='$codCreditoSuc' AND fac_ven.nit=$cod_su AND fac_ven.prefijo=art_fac_ven.prefijo AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$excentasCOM=$row['excentas'];
	
	if($excentasCOM==""){$excentasCOM=0;}
}
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta!='Credito' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac=$row['tot_fac'];
	$ult_fac=$row['ultima'];
	$pri_fac=$row['primera'];
}
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_facCre=$row['tot_fac'];
	$ult_facCre=$row['ultima'];
	$pri_facCre=$row['primera'];
}


//////////////////////////////// ajustes ventas tot ///////////////////////////////////////////////////
$base_tot=redondeo(redondeo((($TOTAL-$excentas)/1.16 + $excentas)));
$iva_tot=redondeo(($TOTAL-$excentas)-($TOTAL-$excentas)/1.16);

$IVA=redondeo(($BASE-$excentas)*0.16);

//////////////////////////////// ajustes ventas tot POS ///////////////////////////////////////////////////
$base_totPOS=redondeo(redondeo((($TOTAL_POS-$excentasPOS)/1.16 + $excentasPOS)));
$iva_totPOS=redondeo(($TOTAL_POS-$excentasPOS)-($TOTAL_POS-$excentasPOS)/1.16);

$IVA_POS=redondeo(($BASE_POS-$excentasPOS)*0.16);

//////////////////////////////// ajustes ventas tot COMPUTADOR ///////////////////////////////////////////////////
$base_totCOM=redondeo(redondeo((($TOTAL_COM-$excentasCOM)/1.16 + $excentasCOM)));
$iva_totCOM=redondeo(($TOTAL_COM-$excentasCOM)-($TOTAL_COM-$excentasCOM)/1.16);

$IVA_COM=redondeo(($BASE_COM-$excentasCOM)*0.16);

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>INFORME DIARIO DE VENTAS</title>
<script src="JS/jquery-2.1.1.js"></script>
<script language="javascript1.5" type="text/javascript">

$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


	
	
	});
function redondeo(numero)
{
var original=parseFloat(numero);
var result=Math.round(original*10)/10 ;
return result;
};


function quitap(T) {
	
   var n = T.split(","), 
   i = 0, h = ""; 
   for(i = 0; i < n.length; i++) {
      h = h + n[i]; 
      }
   return h; 
   }; 


function puntoa(ve) {
   var T = ve.val().toString(); 
   T = quitap(T);
   var i = T.length - 1, ii = T.length; 
   T = T.split(""); 
   var x, a, b, c, C = 0, h = '',ff=0; 
   while(i >= 0) {
	   	 
      if(C == 3 && ii != 3 && T[i] != '-') {
         h = T[i] + ',' + h; 
         C = 0; 
         }
      else {
         h = T[i] + h;
         }
	  
	  
	   if(T[i]=='.'){C=-1;h=quitap(h);}
	   C++; 
	   i--;
       
      }
   $(ve).prop('value', h); 
   }; 
function puntob(ve) {
   var T = ve.toString(); 
   T = quitap(T);
   var i = T.length - 1, ii = T.length; 
   T = T.split(""); 
   var x, a, b, c, C = 0, h = '',ff=0; 
   while(i >= 0) {
	 
      if(C == 3 && ii != 3 && T[i] != '-') {
         h = T[i] + ',' + h; 
         C = 0; 
         }
      else {
         h = T[i] + h;
         }
	  
	  
	   if(T[i]=='.'){C=-1;h=quitap(h);}
	   C++; 
	   i--;
       
      }
   return h; 
   }; 

function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</head>

<body>
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:relative; font-size:12px;">

<?php
echo $PUBLICIDAD2
?>
Fecha Arqueo: <?PHP echo fecha($FechaHoy)."     Hora: ".$hora ?>
<br>
<table align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="font-size:24px; font-weight:bold;">
Desde: <?PHP echo fecha($_SESSION['fechaI']) ?>
</td>
<td style="font-size:24px; font-weight:bold;"> Hasta: <?php echo fecha($_SESSION['fechaF']) ?>
</td>
</tr>
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">
<?php
$cajera="";
$sql="SELECT * FROM cajasb WHERE DATE(inicio)>='$fechaI' AND DATE(inicio)<='$fechaF' AND cod_su=$codSuc";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$cajera= $row['usu'] ;
}

?>
<!--
<tr>
<td><b>Cajero </b></td><td width="50px"></td><td colspan=""><b><?php echo $cajera ?></b></td>
</tr>
-->
</table>
</td>
</tr>
<tr>
<td colspan="4"><b>Facturas POS </b>IMPRESORA: <?PHP ECHO  "BIXOLON MODELO: SRP-350 S/N: IMPCHKA10110096" ?><br>
ResolDian No. <?php echo $ResolContado ?> del <?php echo $FechaResolContado ?> <?php echo $RangoContado ?>


</td>
</tr>
<tr>
<td>
<b>Desde Fac No. <?php echo $pri_fac ?></b>
</td>
<td>
<b>Hasta Fac No. <?php echo $ult_fac ?></b>
</td><td><b>Total Fac. <?php echo $tot_fac ?></b></td>
</tr>
<tr>
<td colspan="4">
<hr align="center" width="100%">
<b>Facturas COMPUTADOR </b><br>
ResolDian No. <?php echo $ResolCredito ?> del <?php echo $FechaResolCredito ?> <?php echo $RangoCredito ?>


</td>
</tr>

<tr>
<td>
<b>Desde Fac No. <?php echo $pri_facCre ?></b>
</td>
<td>
<b>Hasta Fac No. <?php echo $ult_facCre ?></b>
</td><td><b>Total Fac. <?php echo $tot_facCre ?></b></td>
</tr>

</table>
<?php

?>
<h3 align="left"><b>Ventas Totales (SIN DEVOCLUCIONES)</b></h3>
<table style="font-size:12px" cellspacing="0">
<tr>
<td>BASE IVA 16%:</td><td><span ><?PHP echo money($base_tot) ?></span></td>
</tr>
<tr>
<td>VALOR IVA 16%:</td><td><span ><?PHP echo money($iva_tot) ?></span></td>
</tr>
<tr>
<td>VENTAS <?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP  echo money(redondeo($excentas)) ?></span></td>
</tr>
<tr>
<td>VENTAS TOTALES:</td><td> <span ><?PHP echo money(redondeo($TOTAL)) ?></span></td>
</tr>

</table>

<h3 align="left"><b>TOTAL Ventas POS</b></h3>
<table style="font-size:12px" cellspacing="0">
<tr>
<td>BASE IVA 16%:</td><td><span ><?PHP echo money($base_totPOS-$BASE_minusPOS) ?></span></td>
</tr>
<tr>
<td>VALOR IVA 16%:</td><td><span ><?PHP echo money($iva_totPOS- $IVA_minusPOS) ?></span></td>
</tr>
<tr>
<td>VENTAS <?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP  echo money(redondeo($excentasPOS-$excentasMinusPOS)) ?></span></td>
</tr>
<tr>
<td>VENTAS TOTALES:</td><td> <span ><?PHP echo money(redondeo($TOTAL_POS-$TOTAL_minusPOS)) ?></span></td>
</tr>

</table>

<h3 align="left"><b>TOTAL Ventas COMPUTADOR</b></h3>
<table style="font-size:12px" cellspacing="0">
<tr>
<td>BASE IVA 16%:</td><td><span ><?PHP echo money($base_totCOM-$BASE_minusCOM) ?></span></td>
</tr>
<tr>
<td>VALOR IVA 16%:</td><td><span ><?PHP echo money($iva_totCOM-$IVA_minusCOM) ?></span></td>
</tr>
<tr>
<td>VENTAS <?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP  echo money(redondeo($excentasCOM-$excentasMinusCOM)) ?></span></td>
</tr>
<tr>
<td>VENTAS TOTALES:</td><td> <span ><?PHP echo money(redondeo($TOTAL_COM-$TOTAL_minusCOM)) ?></span></td>
</tr>

</table>


<?php


?>
<h3 align="left"><b>Ventas Totales</b></h3>
<table style="font-size:12px" cellspacing="0">
<tr>
<td>BASE IVA 16%:</td><td><span ><?PHP echo money($base_tot-$BASE_minus) ?></span></td>
</tr>
<tr>
<?php

?>
<td>VALOR IVA 16%:</td><td><span ><?PHP echo money($iva_tot-$IVA_minus) ?></span></td>
</tr>
<!--
<tr>
<td>BASE IVA 5%:</td><td><span><?PHP //echo money(redondeo($base_iva5)) ?></span></td>
</tr>
<tr>
<td>VALOR IVA 5%:</td><td><span ><?PHP //echo money(redondeo($iva5)) ?></span></td>
</tr>
-->
<tr>
<td>VENTAS <?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP  echo money(redondeo($excentas-$excentasB)) ?></span></td>
</tr>
<!--
<tr>
<td>INTERESES CR&Eacute;DITOS:</td><td> <span ><?PHP echo money(redondeo($InteresesCreditos)) ?></span></td>
</tr>
-->
<tr>
<td>VENTAS TOTALES:</td><td> <span ><?PHP echo money(redondeo($TOTAL-$TOTAL_minus)) ?></span></td>
</tr>

</table>
<br><br><br><br><br><br><br><br><br><br><br><br>
<table width="100%" cellpadding="4">
<tr>
<td>C./Coordinador:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td>
Jefe venta POS:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td >
Contador:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
</tr>
</table>
<?php //echo $strServ."<br><br>".$strIns ?>
<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
    <input type="button" value="Volver" onClick="javascript:location.assign('factura_venta.jsp');" />
</div> 
<?php //echo $sql3; ?>
</div>


<?php
$sql="SELECT * FROM fac_venta WHERE  nit=$cod_su AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF'";
$rs=$linkPDO->query($sql);
$con=0;
while($row=$rs->fetch()){
$con++;
$NFac=$row['num_fac_ven'];
$vendedor= $row['vendedor'] ;
$totalFac=money($row['tot']);	

?>
<?php
	
}

?>


</body>
</html>