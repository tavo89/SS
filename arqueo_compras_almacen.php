<?php
require_once("Conexxx.php");
$url=thisURL();
$boton=r("boton");
if($boton=="MS EXCEL"){excel("Cartera Clientes");}

$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$totFanalca=0;
$ivaFanalca=0;

$MainCondition="cod_su=$codSuc AND tipo_fac='Compra' AND estado='CERRADA'";

//////////////////// TOTALES X PROVEEDOR ///////////////

$SUM_TOT[]=0;
$SUM_TOTS[]=0;

$TOT_FANALCA=0;
$IVA_FANALCA=0;
$BRUTO_FANALCA=0;
$TOT_OTROS=0;
$TOT_OTROSS=0;
$IVA_OTROS=0;
$BRUTO_OTROS=0;

$sql="SELECT nom_pro,nit_pro,SUM(iva) IVA,SUM(tot) TOT,SUM(descuento) AS dcto  
	  FROM `fac_com` 
	  WHERE $MainCondition AND fecha>='$fechaI' AND fecha<='$fechaF' GROUP BY nom_pro ORDER BY 1 ASC";

$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
	
$proveedor=$row['nom_pro'];
$nit=$row['nit_pro'];
$bruto=$row['TOT']-$row['IVA'];
$iva=$row['IVA'];
$tot=$row['TOT'];

$SUM_TOT[$proveedor]=$tot;
$SUM_TOTS[$proveedor]=$row['dcto']+$tot;

if($nit==$NIT_FANALCA)
{
	$TOT_FANALCA=$tot;
	$IVA_FANALCA=$iva;
	$BRUTO_FANALCA=$bruto;
}
else
{
	$TOT_OTROS+=$tot;
	$TOT_OTROSS+=$tot+$row['dcto'];
	$IVA_OTROS+=$iva;
	$BRUTO_OTROS+=$bruto;
}

}///////////// FIN WHILE TOTALES/////////






$sql="SELECT nom_pro,descuento,nit_pro,serial_fac_com, num_fac_com,fecha,iva,tot  
FROM `fac_com` WHERE $MainCondition AND (fecha>='$fechaI' AND fecha<='$fechaF') order by nom_pro,fecha";
$rsFanalca=$linkPDO->query($sql );

?>
<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 
<title>COMPRAS ALMACEN</title>
<?php 
if($boton!="MS EXCEL"){
?>
<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="css/animate.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
 
 
<?php 
require_once("IMP_HEADER.php"); 
require_once("HEADER_UK.php"); 
}
?>
</head>

<body>
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>
<td>
<?php

echo $PUBLICIDAD2;

?>
</td>
<td valign="top">
<p align="left" style="font-size:12px;">
<span align="center" style=" font-size:24px"><B>INFORME DE COMPRAS DE ALMACEN</B></span>
</p>
</td>

</tr>
</table>
Fecha: <?PHP echo $hoy ?>
<br>
<table align="center" width="100%">
<tr>
<td>
Desde: <?PHP echo $_SESSION['fechaI'] ?>
</td>
<td> Hasta: <?PHP echo $_SESSION['fechaF'] ?>
</td>
</tr>
 
</table>

 

 
<table align="center"     class="uk-table uk-table-condensed">
<thead>
<tr>
<td>Proveedor</td>
<!--<td align="center">Cod. </td>-->
<td align="center"> Factura</td>
<td align="center">Fecha</td>
<td align="right">IVA</td>
  <!--<td align="center">Total Sdcto</td>-->
  <td align="right">Total</td>
</tr>
</thead>
<tbody>
 

 
 

<?php
$nomFlag="";
$nitFlag="";
$first=0;

$gotIN=0;
while($row=$rsFanalca->fetch()){
$gotIN=1;
$proveedor=$row['nom_pro'];
$nit=$row['nit_pro'];
$serial=$row['serial_fac_com'];
$num_fac=$row['num_fac_com'];
$fecha=$row['fecha'];
$iva=money2($row['iva']);
$tot=$row['tot'];
$totSdcto=$row['tot']+$row['descuento'];



if($proveedor!=$nomFlag && $first==1){

echo "<tr style='font-size:14px;' valign='top' class=\"uk-block-primary\">
      <td colspan='4' height='30px'><B>TOTAL COMPRAS [$nomFlag]</B></td>
	  <!--<td align='right'>".money2($SUM_TOTS[$nomFlag])."</td>-->
	  <td align='right' style='font-size:21px;'>".money2($SUM_TOT[$nomFlag])."</td>
	  </tr>";	

?>
</tbody>
</table>
<br><br>
<table align="center"     class="uk-table uk-table-condensed">
<thead>
<td>Proveedor</td>
<!--<td align="center">Cod. </td>-->
<td align="center"> Factura</td><td align="center">Fecha</td><td align="right">IVA</td>
  <!--<td align="center">Total Sdcto</td>-->
  <td align="right">Total</td>
</tr>
</thead>
<tbody>

<?php



}
?>
<tr>
<td><?php echo $proveedor  ?></td>
<!--<td align="center"><?php echo $serial  ?></td>-->
<td align="center"><?php echo $num_fac ?></td>
<td align="center"><?php echo $fecha ?></td>
<td align="right"><?php echo $iva ?></td>
<!--<td align="center"><?php echo money2($totSdcto)  ?></td>-->
<td align="right"><?php echo money2($tot)  ?></td>
</tr>

<?php
$nomFlag=$proveedor;
$nitFlag=$nit;
$first=1;
}
if($gotIN){
	echo "<tr style='font-size:14px;' valign='top' class=\"uk-block-primary\"><td colspan='4' height='30px'><B>TOTAL COMPRAS [$nomFlag]</B></td><!--<td align='right'>".money2($SUM_TOTS[$nomFlag])."</td>--><td align='right' style='font-size:21px;'>".money2($SUM_TOT[$nomFlag])."</td></tr>";	}


?>



</tbody>
</table>
<br><br>
<table class="uk-table">
<tr style="font-size:24px; ">
  <th colspan="7" align="center">TOTAL COMPRAS</th>
</tr>
<tr style="font-size:18px;">
<td colspan="3"></td><td align="right">Bruto</td><td align="right">IVA</td>
<!--<td align="center">Total Sdcto</td>-->
<td align="right">Total</td>
</tr>
<tr style="font-size:18px;">
<td colspan="3"></td>
<td align="right"><?php echo money2($BRUTO_OTROS) ?></td>
<td align="right"><?php echo money2($IVA_OTROS) ?></td>
<!--<td align="right"><?php //echo money2($TOT_OTROSS)  ?></td>-->
<td align="right"><?php echo money2($TOT_OTROS)  ?></td>

</tr>
 
</table>

<div id="imp" align="center">

<input type="button" value="IMPRIMIR" name="boton" onClick="imprimir()" />
<input type="button" value="MS EXCEL" name="boton" onClick="location.assign('<?php echo "$url?boton=MS EXCEL" ?>')" />


</div>
</div>
<?php 
if($boton!="MS EXCEL"){
require_once("FOOTER_UK.php"); 
}
?>
</body>
</html>