<?php
require_once("Conexxx.php");
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$cod_su=$_SESSION['cod_su'];

if($fechaI!=$fechaF){

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


///////////////////////////////////////////////// TOTAL RECUPERACION CREDITOS /////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc AND num_fac_ven NOT IN(SELECT num_fac FROM comprobante_ingreso) AND estado='PAGADO'  AND DATE(fecha_pago)>='$fechaI' AND DATE(fecha_pago)<='$fechaF' AND num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_comprobantes=$row['TOT'];
	
}

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

///////////////////////////////////////////////// TOTAL DEVOLUCIONES ////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc AND tipo_venta!='Anticipo'  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minus=$row['TOT'];
	$BASE_minus=$row['BASE'];
	$DESCUENTO_minus=$row['D'];
	$IVA_minus=$row['IVA'];
		
}
// ------------------------------------------------------- DEVOLUCIONES EXENTAS -----------------------------------------------------------------
$sql="SELECT fac_ven.fecha_anula,fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,fecha_anula FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su AND fac_ven.prefijo=art_fac_ven.prefijo AND anulado='ANULADO' and fac_ven.nit=$codSuc AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasB=$row['excentas'];
	
	if($excentasB==""){$excentasB=0;}
}

//////////////////////////////////////////////////////// TOTALES VENTAS //////////////////////////////////////////////////////////////////////////////
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
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$excentas=$row['excentas'];
	
	if($excentas==""){$excentas=0;}
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// Num. Facturas por cada tipo /////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli='Mostrador' AND num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{$tot_fac_mostrador=$row['tot_fac'];}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli='Empleados' AND num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_empleados=$row['tot_fac'];
	
}


$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Contado' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_contado=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_credito=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Cheque'  AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_cheque=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Tarjeta Credito' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_tarjeta_credito=$row['tot_fac'];
	
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

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
$ventas_tot=$iva16+$base_iva16+$base_iva5+$iva5+$excentas;
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------


$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Contado' AND num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_contado=$row['TOT'];
	
}
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Cheque' AND num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_cheque=$row['TOT'];
	
}

$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Tarjeta Credito' AND num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_tarjetaCre=$row['TOT'];
	
}

$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito' AND num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);

$tot_Credito=0;
if($row=$rs->fetch())
{
	$tot_Credito=$row['TOT'];
	
}

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
<style type="text/css">
.caja_cerrada{
	-webkit-transform: rotate(340deg);
	-moz-transform: rotate(340deg);
	-o-transform: rotate(340deg);
	writing-mode: lr-tb;
	position:relative;
	top:200px;
	left:300px;
	font-size:42px;
	}

</style>

</head>

<body>
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:relative; font-size:12px;">
<table align="center" width="100%">
<tr>
<td>
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top">
<p align="left" style="font-size:32px; font-weight:bold;">
<span align="center"><B>Cajas por Rangos</B></span>
</p>
</td>

</tr>
</table>
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
<tr style=" font-size:16px;">
<td><b>Cajero </b></td><td width="20px"></td><td colspan=""><b><?php echo $cajera ?></b></td>

<?php
$estado_caja="";
$sql="SELECT * FROM cajas WHERE fecha='$fechaF' AND cod_su=$codSuc";
	$rs=$linkPDO->query($sql);
    if($row=$rs->fetch())
	{
	 $cod_caja=$row['cod_caja']; 
	 $estado_caja=$row['estado_caja'];
	}

?>
<td style="font-size:18px;"><div class="caja_cerrada"><B><?php echo $estado_caja ?></B></div></td>
</tr>

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
<!--
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
-->
</table>
<?php
$base_tot=redondeo(redondeo((($TOTAL-$excentas)/1.16 + $excentas)));
$iva_tot=redondeo(($TOTAL-$excentas)-($TOTAL-$excentas)/1.16);

?>
<h2 align="left"><b>VENTAS TOTALES</b></h2>
<table style="font-size:12px" cellspacing="0">
<tr>
<td>BASE IVA 16%:</td><td><span ><?PHP echo money($base_tot) ?></span></td>
</tr>
<tr>
<?php

$IVA=redondeo(($BASE-$excentas)*0.16);
?>
<td>VALOR IVA 16%:</td><td><span ><?PHP echo money($iva_tot) ?></span></td>
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
<td>VENTAS <?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP  echo money(redondeo($excentas)) ?></span></td>
</tr>
<!--
<tr>
<td>INTERESES CR&Eacute;DITOS:</td><td> <span ><?PHP echo money(redondeo($InteresesCreditos)) ?></span></td>
</tr>
-->
<tr>
<td>VENTAS TOTALES:</td><th> <span ><?PHP echo money(redondeo($TOTAL)) ?></span></th>
</tr>

</table>
<BR>
<h2>RESUMEN DE VENTAS</h2>
<table align="left" cellpadding="4" cellspacing="0" frame="box" rules="all">
<thead>
<tr>
<th>PRODUCTO</th><th>CANT.</th><th>TOT. VENTA</th>
</tr>
</thead>
<tbody>
<?php 
$sql="SELECT a.des,SUM(a.cant) as cant, SUM(a.sub_tot) as stot FROM `art_fac_ven` a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND (DATE(b.fecha)>='$fechaI' AND DATE(b.fecha)<='$fechaF'  )  AND b.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') GROUP BY des";

$rs=$linkPDO->query($sql);
$STOT=0;
while($row=$rs->fetch())
{
	$des=$row['des'] ;
	$cant=$row['cant']*1;
	$stot=redondeo($row['stot']*1);
	$STOT+=$stot;
	?>

<tr>
<td><?php echo "$des" ; ?></td>
<td><?php echo "$cant" ; ?></td>
<td><?php echo money("$stot") ; ?></td>
</tr>
    <?php
	
}


 ?>
 </tbody>
 <tfoot>
 <th colspan="2">TOTAL VENTAS:</th><th><?php echo money(redondeo("$STOT")) ; ?></th>
 </tfoot>
</table>
<?php


$sql="SELECT art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT fecha_anula,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE anulado='ANULADO'  and fac_ven.nit=$cod_su AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasB=$row['excentas'];
	
	if($excentasB==""){$excentasB=0;}
}




$sql="SELECT COUNT(*) as tot_fac FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' ";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_fac=$row['tot_fac'];
	}

$ventas_totB=$iva16B+$base_iva16B+$base_iva5B+$iva5B+$excentasB;


?>
<?php
$iva_taller=0;
$tot_taller=0;
$iva_otros=0;
$tot_otros=0;

$trabajoTerceros=0;
$total_vendedores[]="";
////////////////////////////////////////////////////// LISTA VENDEDORES ////////////////////////////////////////////////////////////////
$rs=$linkPDO->query("SELECT vendedor from fac_venta WHERE nit=$codSuc GROUP BY vendedor");
while($row=$rs->fetch())
{
	$total_vendedores[ucwords(strtolower($row["vendedor"] ))]=0;
	
}

///////////////////////////////////////////////// PRODUCTOS //////////////////////////////////////////////////////////////////////
$sql="SELECT art_fac_ven.num_fac_ven, precio,des,art_fac_ven.sub_tot,art_fac_ven.iva,cant,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,fac_venta.prefijo,art_fac_ven.prefijo FROM fac_venta INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit AND art_fac_ven.nit=$codSuc    AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);

$total_taller=0;
$total_otros=0;
$total_mostrador=0;
$total_empleados=0;
$total_fanalca=0;

$total_contado=0;
$total_credito=0;
$total_cre_empleados=0;
$total_cre_fanalca=0;
$total_cre_otros=0;

$base16=0;
$iva16=0;
$excentas=0;
$total_repuestos=0;

$totFacAnticipo=0;



$i=0;
while($row=$rs->fetch())
{
	$i++;
	$num_fac=$row['num_fac_ven'];
	$subTot=money($row['sub_tot']*1);
	$IVA=money($row['iva']*1);
	$des= $row['des'] ;
	$cant=$row['cant'];
	$valor=money($row['precio']*1);
	$ref=$row['ref'];
	$vendedor=ucwords(strtolower( $row["vendedor"] ));
	$HORA=$row['hora'];
	$fecha=$row['fe'];
	$tipo_venta=$row['tipo_venta'];
	$tipoCli=$row['tipo_cli'];
	
	
	$total_vendedores[$vendedor]+=$row['sub_tot']*1;
	
	if($IVA==0)$excentas+=$row['sub_tot']*1;
	
	if($IVA==16)
	{
		$base16+=($row['sub_tot']*1)/1.16;
		$iva16+=$row['sub_tot']*1-($row['sub_tot']*1)/1.16;
		
		}
	$total_repuestos+=$row['sub_tot']*1;
	
	
	///////////////////////// TIPO CLIENTE //////////////////////////
	if($tipoCli=="Mostrador")
	{
		$total_mostrador+=$row['sub_tot']*1;
		
		}
	if($tipoCli=="Empleados")
	{
		$total_empleados+=$row['sub_tot']*1;
		
		}
	
	/////// TIPO PAGO /////////////////////////////////////////////////////
	
	if($tipo_venta=="Contado"|| $tipo_venta=="Tarjeta Credito")
	{
		$total_contado+=$row['sub_tot']*1;
		
		}
	if($tipo_venta=="Credito")
	{
		$total_credito+=$row['sub_tot']*1;
		
		}
	
	
}// FIN ARTICULOS

///////////////////////////////////// LISTA DEVOLUCIONES ///////////////////////////////////////////////
$sql="SELECT art_fac_ven.num_fac_ven, precio,des,art_fac_ven.sub_tot,art_fac_ven.iva,cant,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,fac_venta.prefijo,art_fac_ven.prefijo FROM fac_venta INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit AND art_fac_ven.nit=$codSuc AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula) AND MONTH(fecha)!=MONTH('$fechaI')";
$rs=$linkPDO->query($sql);

$i=0;
while($row=$rs->fetch())
{
	$i++;
	$num_fac=$row['num_fac_ven'];
	$subTot=money($row['sub_tot']*1);
	$IVA=money($row['iva']*1);
	$des=$row['des'];
	$cant=$row['cant'];
	$valor=money($row['precio']*1);
	$ref=$row['ref'];
	$vendedor=ucwords(strtolower($row["vendedor"]));
	$HORA=$row['hora'];
	$fecha=$row['fe'];
	$tipo_venta=$row['tipo_venta'];
	$tipoCli=$row['tipo_cli'];
	
	
	$total_vendedores[$vendedor]-=$row['sub_tot']*1;
	
	if($IVA==0)$excentas-=$row['sub_tot']*1;
	
	if($IVA==16)
	{
		$base16-=($row['sub_tot']*1)/1.16;
		$iva16-=$row['sub_tot']*1-($row['sub_tot']*1)/1.16;
		
		}
	$total_repuestos-=$row['sub_tot']*1;
	
	
	///////////////////////// TIPO CLIENTE //////////////////////////
	if($tipoCli=="Mostrador")
	{
		$total_mostrador-=$row['sub_tot']*1;
		
		}
	if($tipoCli=="Empleados")
	{
		$total_empleados-=$row['sub_tot']*1;
		
		}
	/////// TIPO PAGO /////////////////////////////////////////////////////
	
	if($tipo_venta=="Contado"|| $tipo_venta=="Tarjeta Credito")
	{
		$total_contado-=$row['sub_tot']*1;
		
		}
	if($tipo_venta=="Credito")
	{
		$total_credito-=$row['sub_tot']*1;
		
		}
	
}// FIN DEVOLUCIONES



?>
<?php //echo money($total_mostrador+$total_empleados+$total_taller+$total_otros+$total_fanalca) ?>
</td>
</tr>

</table>
<?php


?>
<br><br><br><br><br><br><br><br><br>
<!--
<table style="font-size:12px" cellspacing="0" frame="box"  cellpadding="0">
<tr>
<td colspan="2" align="left" style="font-size:14px;  background-color:#999">
<table cellspacing="0" cellpadding="0" frame="box"  width="100%">
<tr><td width="100%" colspan="2"><b>VENTAS DE PRODUCTOS</b></td></tr>
</table>
</td>
<td style="font-size:14px; background-color:#999">
<table cellspacing="0" cellpadding="0" frame="box"  width="100%">
<tr><td width="100%" colspan="2"><b>
# Facturas.</b></td></tr>
</table>

</td>
</tr>
<tr>
<td>MOSTRADOR:</td><td><span id="base_iva5"><?PHP echo money(redondeo($total_mostrador)) ?></span></td>
<td align="center"><span><?PHP echo $tot_fac_mostrador ?></span></td>
</tr>
<tr>
<td>EMPLEADOS:</td><td><?PHP echo money(redondeo($total_empleados))?></td>
<td align="center"><span><?PHP echo $tot_fac_empleados ?></span></td>
</tr>
<tr style="font-size:16px; font-weight:bold;">
<td colspan="">
Total:
</td>
<td>
<?PHP echo money(redondeo($total_repuestos)) ?>
</td></tr></table>

<h2 align="left">VENTAS TOTALES:<?php echo money(redondeo($total_mostrador+$total_empleados)) ?></h2>
<?php

$sql="SELECT SUM(tot) as TOT,COUNT(*) tf FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito' AND tipo_cli='Empleados' AND num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
$tot_Credito=0;
if($row=$rs->fetch())
{
	$tot_Credito_empleados=$row['TOT'];
	$totFacEmpleados=$row['tf'];
	
}


$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito' AND num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
$tot_Credito=0;
if($row=$rs->fetch())
{
	$tot_Credito=$row['TOT'];
	
}

?>
<table align="left" cellpadding="4" cellspacing="0" frame="box">
<tr>
<td colspan="3" align="left" style="font-size:14px; background-color:#999">
<table cellspacing="0" cellpadding="0" frame="box"  width="100%">
<tr><td  colspan=""><b>FORMAS DE PAGO</b></td><TD>#</TD></tr>
</table>
</td></tr>

<tr>
<td>CONTADO Y TARJETAS CREDITO:</td><td><span id="iva5"><?PHP echo money(redondeo($total_contado+$tot_fac_tarjeta_credito)) ?></span></td>
<td align="center"><span><?PHP echo $tot_fac_contado ?></span></td>
</tr>
<tr>
<td>CR&Eacute;DITO EMPLEADOS:</td><td><span id="iva5"><?PHP echo money(redondeo($tot_Credito_empleados)) ?></span></td>
<td align="center"><span><?PHP echo $totFacEmpleados ?></span></td>
</tr>

<tr>
<td>CR&Eacute;DITO P&Uacute;BLICO:</td><td><span id="iva5"><?PHP echo money(redondeo($tot_Credito-($tot_Credito_empleados))) ?></span></td>
<td align="center"><span><?PHP echo ($tot_fac_credito-($totFacEmpleados)) ?></span></td>
</tr>
<tr>
<td>TOTAL CR&Eacute;DITOS:</td><td><span id="iva5"><?PHP echo money(redondeo($tot_Credito)) ?></span></td>
<td align="center"><span><?PHP echo $tot_fac_credito ?></span></td>
</tr>

<tr style="font-size:16px;">
<td colspan="">
Total:
</td>
<td>
<?php echo money(redondeo($tot_Credito+$total_contado+$tot_fac_tarjeta_credito)) ?>
</td>
</tr>
</table>

-->
<br><br><br><br>
<H2 align="left">CUADRE DE CAJA</H2>
<table align="left" cellpadding="0" cellspacing="0">
<tr>
<td><B>CONTADO Y TARJETAS CREDITO:</B></td><td><span id="iva5"><?PHP echo money(redondeo($total_contado+$tot_fac_tarjeta_credito)) ?></span></td>
</tr>
<tr>
<td >
<b>RECUPERACI&Oacute;N CR&Eacute;DITOS:</b></td>

<td> <span id="total_excentas"><?PHP echo money(redondeo($tot_comprobantes+$tot_comprobantes_ingreso)) ?></span></td>
</tr>
<!--
<tr style="font-size:14px; ">
<td><b>DEVOLUCIONES PRODUCTOS:</b></td><td valign="bottom"> <span id="total2"><?PHP echo money(redondeo($TOTAL_minus)) ?></span></td>
</tr>-->
<tr style="font-size:24px;">
<td><b>TOTAL EFECTIVO:</b></td><td> <span id="total2"><?PHP echo money(redondeo($total_contado+$tot_fac_tarjeta_credito+$tot_comprobantes_ingreso+$tot_comprobantes)) ?></span></td>
</tr>
</table>

<br>
<table style="font-size:10px" cellspacing="0"  cellpadding="0" align="right">
<tr>
<td>TOTAL SISTEMA:</td><TD>&nbsp;&nbsp; <?php echo money(redondeo($total_contado+$tot_fac_tarjeta_credito+$tot_comprobantes_ingreso+$tot_comprobantes)) ?>
</TD>
</tr>
<tr>
<td>EFECTIVO:</td><TD>
<p align="center">________________________</p></TD>
</tr>
<tr>
<td>DIFERENCIA:</td><TD>
<p align="center">________________________</p></TD>
</tr>
<tr>
<td>NOVEDADES:</td>
<TD>
<p align="center">________________________</p>
<p align="center">________________________</p>
</TD>
</tr>

</table>
<br>
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
<br><br>
<h2 align="left"><b>ANULADAS</b></h2>
<table style="font-size:12px" cellspacing="0">
<tr>
<td>BASE IVA 16%:</td><td><span ><?PHP echo money(redondeo($BASEa-$DESCUENTOa)) ?></span></td>
</tr>
<tr>
<td>VALOR IVA 16%:</td><td><span ><?PHP echo money(redondeo($IVAa)) ?></span></td>
</tr>
<!--
<tr>
<td>BASE IVA 5%:</td><td><span><?PHP //echo money(redondeo($base_iva5B)) ?></span></td>
</tr>
<tr>
<td>VALOR IVA 5%:</td><td><span ><?PHP //echo money(redondeo($iva5B)) ?></span></td>
</tr>
-->
<tr>
<td>VENTAS <?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP echo money(redondeo($excentasB)) ?></span></td>
</tr>
<tr>
<td>VENTAS TOTALES:</td><td> <span ><?PHP echo money(redondeo($TOTALa)) ?></span></td>
</tr>
<tr >
<td>TOTAL ANULADAS:</td><td> <span ><?PHP echo $tot_fac ?></span></td>
</tr>
</table>

<table cellpadding="2" cellspacing="0" style="font-size:12px" align="left">
<tr style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold;">
<th>#</th><td>Num. Factura</td><td>Vendedor</td><td>Total</td>

<?php
$sql="SELECT * FROM fac_venta WHERE  nit=$cod_su AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF'";
$rs=$linkPDO->query($sql);
$con=0;
while($row=$rs->fetch()){
$con++;
$NFac=$row['num_fac_ven'];
$vendedor=$row['vendedor'];
$totalFac=money($row['tot']);	

?>
<tr>
<th><?php echo $con ?></th>
<td align="center"><?php echo $NFac ?></td>
<td align="center"><?php echo $vendedor?></td>
<td align="right"><?php echo $totalFac ?></td>
</tr>
<?php
	
}





?>
</tr>
</table>


<?PHP
}// FIN FECHA IGUAL
else{
echo "<h1>ESTE INFORME NO APLICA PARA UN SOLO DIA</h1>";	
	
}


?>

</body>
</html>