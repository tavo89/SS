<?php
require_once("Conexxx.php");
$fechaI="";
$fechaF="";
if(isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI']))$fechaI=$_SESSION['fechaI'];
if(isset($_SESSION['fechaF'])&&!empty($_SESSION['fechaF']))$fechaF=$_SESSION['fechaF'];

$url=thisURL();
$boton=r("boton");
if($boton=="MS EXCEL"){excel("Costo inventario $fechaF");}

?>

<!DOCTYPE html >
<html>
<head>
<?php require_once("IMP_HEADER.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>INVENTARIO</title>

</head>

<body style="font-size:12px">
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>
<td>
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top">
<p align="left" style="font-size:32px; font-weight:bold;">
<span align="center"><B>COSTO INVENTARIO</B></span>
</p>
</td>

</tr>
</table>
<span style="font-size:18px; font-weight:bold">Fecha: <?PHP echo $fechaI ?></span>
<br>
<table align="center" width="100%">
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">
</table>
</td>
</tr>
</table>

<hr align="center" width="100%">
<table align="center" width="100%" cellpadding="5" cellspacing="5" style="font-size:11px" rules="rows" id="TabClientes" class="">
<tr style="font-size:18px;  font-weight:bold;">
<th width=""></th>
<th width="200" align="right">Costo Sin IVA</th>
<th width="200" align="right">IVA</th>
<th width="200" align="right">Tot. Costo</th>

</tr>
<?php
$costoC=0;
$costoS=0;

$RESP=inv_dias2($FechaHoy,$fechaF,$codSuc,"0");
$costoS=$RESP[0];
$costoC=$RESP[1];


$IVA=$costoC-$costoS;
?>
<!--
<tr style="font-size:18px;">
<th width="">EXENTO</th>
<td align="right"><?php echo money2(redondeo($costoS)) ?></td>
<td align="right"><?php echo money2(redondeo($IVA)) ?></td>
<td align="right"><?php echo money2(redondeo($costoC)) ?></td>
</tr>
-->

<?php
$costoC=0;
$costoS=0;

$RESP=inv_dias2($FechaHoy,$fechaF,$codSuc,"5");
$costoS=$RESP[0];
$costoC=$RESP[1];


$IVA=$costoC-$costoS;
?>
<!--
<tr style="font-size:18px;">
<th width="">IVA 5%</th>
<td align="right"><?php echo money2(redondeo($costoS)) ?></td>
<td align="right"><?php echo money2(redondeo($IVA)) ?></td>
<td align="right"><?php echo money2(redondeo($costoC)) ?></td>
</tr>
-->
<?php
$costoC=0;
$costoS=0;

$RESP=inv_dias2($FechaHoy,$fechaF,$codSuc,"19");
$costoS=$RESP[0];
$costoC=$RESP[1];


$IVA=$costoC-$costoS;
?>
<!--
<tr style="font-size:18px;">
<th width="">IVA 16%</th>
<td align="right"><?php echo money2(redondeo($costoS)) ?></td>
<td align="right"><?php echo money2(redondeo($IVA)) ?></td>
<td align="right"><?php echo money2(redondeo($costoC)) ?></td>
</tr>
-->
<?php
$costoC=0;
$costoS=0;

$RESP=inv_dias2($FechaHoy,$fechaF,$codSuc,"all");
$costoS=$RESP[0];
$costoC=$RESP[1];


$IVA=$costoC-$costoS;
?>
<tr style="font-size:18px;">
<th width="">TOTAL</th>
<td align="right"><?php echo money2(redondeo($costoS)) ?></td>
<td align="right"><?php echo money2(redondeo($IVA)) ?></td>
<td align="right"><?php echo money2(redondeo($costoC)) ?></td>
</tr>


</table>
<hr align="center" width="100%" />
<div id="imp"  align="center">
    
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 
</div>
</body>
</html>