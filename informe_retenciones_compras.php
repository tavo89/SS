<?php
require_once("Conexxx.php");
$url=thisURL();
$boton=r("boton");
if($boton=="MS EXCEL"){excel("Cartera Clientes");}

$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$totFanalca=0;
$ivaFanalca=0;

$MainCondition="cod_su=$codSuc AND tipo_fac='Compra' AND (r_fte!=0 OR r_iva!=0 OR r_ica!=0)";

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

$TOT_R_FTE[]=0;
$TOT_R_IVA[]=0;
$TOT_R_ICA[]=0;

$TOT_R_FTES=0;
$TOT_R_IVAS=0;
$TOT_R_ICAS=0;

$sql="SELECT SUM(r_fte) r_fte,SUM(r_iva) r_iva,SUM(r_ica) r_ica,nom_pro,nit_pro,SUM(iva) IVA,SUM(tot) TOT,SUM(descuento) AS dcto,SUM(subtot) as sub  FROM `fac_com` WHERE $MainCondition AND fecha>='$fechaI' AND fecha<='$fechaF' GROUP BY nom_pro ORDER BY 1 ASC";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
	
$proveedor=$row['nom_pro'];
$nit=$row['nit_pro'];
$bruto=$row['TOT']-$row['IVA'];
$iva=$row['IVA'];
$tot=$row['TOT'];

$TOT_R_FTE[$proveedor]=$row["r_fte"];
$TOT_R_IVA[$proveedor]=$row["r_iva"];
$TOT_R_ICA[$proveedor]=$row["r_ica"];


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






$sql="SELECT r_fte,r_iva,r_ica,nom_pro,descuento,nit_pro,serial_fac_com, num_fac_com,fecha,iva,tot,subtot as sub  FROM `fac_com` WHERE $MainCondition AND (fecha>='$fechaI' AND fecha<='$fechaF') order by nom_pro,fecha";
$rsFanalca=$linkPDO->query($sql );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<span align="center" style=" font-size:24px"><B>RETENCIONES COMPRAS</B></span>
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

<?php



?>
 
<table align="center"     class="uk-table uk-table-condensed">
<thead>
<tr>
<td align="center">Fecha</td>
<td>Proveedor</td>
<td align="center">Factura</td>
<td align="right">S. Total</td>

<td align="right">R. FTE</td>
 <td align="right">R. IVA</td>
 <td align="right">R. ICA</td>
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
$iva=money3($row['iva']);
$sub=$row["sub"];
$tot=$row['tot'];
$totSdcto=$row['tot']+$row['descuento'];

$R_FTE=$row["r_fte"]*1;
$R_IVA=$row["r_iva"]*1;
$R_ICA=$row["r_ica"]*1;

$TOT_R_FTES+=$R_FTE;
$TOT_R_IVAS+=$R_IVA;
$TOT_R_ICAS+=$R_ICA;



if($proveedor!=$nomFlag && $first==1){

echo "<tr style='font-size:14px;' valign='top' class=\"uk-block-primary\"><td colspan='4' height='30px'><B>[$nomFlag]</B></td><td align='right'>".money3($TOT_R_FTE[$nomFlag])."</td><td align='right'>".money3($TOT_R_IVA[$nomFlag])."</td><td align='right'>".money3($TOT_R_ICA[$nomFlag])."</td></tr>";

?>
</tbody>
</table>
<br /><br />
<table align="center"     class="uk-table uk-table-condensed">
<thead>
<tr>
<td align="center">Fecha</td>
<td>Proveedor</td>
<td align="center">Factura</td>
<td align="right">S. Total</td>

<td align="right">R. FTE</td>
 <td align="right">R. IVA</td>
 <td align="right">R. ICA</td>
</tr>
</thead>
<tbody>
<?php	
}
?>
<tr style="font-size:12px;">
<td align="center"><?php echo $fecha ?></td>
<td><?php echo $proveedor  ?></td><td align="center"><?php echo $num_fac ?></td>
<td align="right"><?php echo money3($sub) ?></td>


<td align="right"><?php echo money3($R_FTE) ?></td>
<td align="right"><?php echo money3($R_IVA)  ?></td>
<td align="right"><?php echo money3($R_ICA)  ?></td>
</tr>

<?php
$nomFlag=$proveedor;
$nitFlag=$nit;
$first=1;
}
if($gotIN)echo "<tr style='font-size:14px;' valign='top' class=\"uk-block-primary\"><td colspan='4' height='30px'><B>[$nomFlag]</B></td><td align='right' style='font-size:15px;'>".money3($TOT_R_FTE[$nomFlag])."</td><td align='right' style='font-size:17px;'>".money3($TOT_R_IVA[$nomFlag])."</td><td align='right' style='font-size:17px;'>".money3($TOT_R_ICA[$nomFlag])."</td></tr>";	


?>


<tr style="font-size:24px; ">
  <th colspan="7">TOTAL RETENCIONES</th>
</tr>

<tr style="font-size:18px;">
<td colspan="3"></td><td align="right">R. FTE</td>
  <td align="right">R. IVA</td><td align="right">R. ICA</td>
</tr>
<tr style="font-size:18px;">

<td align="right" colspan="3"></td>
<td align="right"><?php echo money3($TOT_R_FTES) ?></td>
<td align="right"><?php echo money3($TOT_R_IVAS)  ?></td>
<td align="right"><?php echo money3($TOT_R_ICAS)  ?></td>

</tr>
 </tbody>
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