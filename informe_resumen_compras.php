<?php
require_once("Conexxx.php");
$url=thisURL();
$boton=r("boton");
if($boton=="MS EXCEL"){excel("Cartera Clientes");}

$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<script language="javascript" type="text/javascript" src="JS/jQuery1.8.2.min.js"></script>
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
<title>RESUMEN COMPRAS</title>
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
<span align="center" style=" font-size:24px"><B>INFORME RESUMEN DE COMPRAS</B></span>
</p>
</td>

</tr>
</table>
Fecha: <?PHP echo $hoy ?>
<br>
<table align="center" width="100%">
<tr style="font-size:24px;">
<td>
Desde: <?PHP echo $_SESSION['fechaI'] ?>
</td>
<td> Hasta: <?PHP echo $_SESSION['fechaF'] ?>
</td>
</tr>
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">
<tr>
<td><b>Cajero </b></td><td width="50px"></td><td colspan=""><b><?php echo $_SESSION['nom_usu'] ?></b></td>
</tr>
</table>
</td>
</tr>
</table>

<hr align="center" width="100%">
<?php

if($fechaI<"2018-01-01" && $fechaF<"2018-01-01"){
$totComp_0=tot_compras_0516a($fechaI,$fechaF,$codSuc,0);

$totComp_5=tot_compras_0516a($fechaI,$fechaF,$codSuc,5);

$totComp_10=tot_compras_0516a($fechaI,$fechaF,$codSuc,10);

$totComp_16=tot_compras_0516a($fechaI,$fechaF,$codSuc,16);

$totComp_19=tot_compras_0516a($fechaI,$fechaF,$codSuc,19);
	
	
	
	
}
else{
$totComp_0=tot_compras_0516($fechaI,$fechaF,$codSuc,0);

$totComp_5=tot_compras_0516($fechaI,$fechaF,$codSuc,5);

$totComp_10=tot_compras_0516($fechaI,$fechaF,$codSuc,10);

$totComp_16=tot_compras_0516($fechaI,$fechaF,$codSuc,16);

$totComp_19=tot_compras_0516($fechaI,$fechaF,$codSuc,19);

$totComp_CONSUMO=$totComp_0["CONSUMO"]+$totComp_5["CONSUMO"]+$totComp_10["CONSUMO"]+ $totComp_16["CONSUMO"]+ $totComp_19["CONSUMO"];

}

?>

<table style="font-size:18px" cellspacing="0">
<tr>
<td colspan="4">COMPRAS <?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP  echo money3(redondeo($totComp_0["TOT"])) ?></span></td>
</tr>
<tr>
<td colspan="4">BASE IVA 5%:</td><td><span ><?PHP echo money3($totComp_5["BASE"]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 5%:</td><td><span ><?PHP echo money3($totComp_5["IVA"]) ?></span></td>
</tr>
<tr>
<td colspan="4">BASE IVA 16%:</td><td><span ><?PHP echo money3($totComp_16["BASE"]) ?></span></td>
</tr>
<tr>
<td colspan="4">VALOR IVA 16%:</td><td><span ><?PHP echo money3($totComp_16["IVA"]) ?></span></td>
</tr>

<tr>
<td colspan="4">BASE IVA 19%:</td><td><span ><?PHP echo money3($totComp_19["BASE"]) ?></span></td>
</tr>
<tr>
<td colspan="4">VALOR IVA 19%:</td><td><span ><?PHP echo money3($totComp_19["IVA"]) ?></span></td>
</tr>

<tr>
<td colspan="4">Impuesto Consumo:</td><td><span ><?PHP echo money3($totComp_CONSUMO) ?></span></td>
</tr>

<tr>
<td colspan="4">COMPRAS TOTALES:</td><td> <span ><?PHP echo money3(redondeo($totComp_CONSUMO+$totComp_19["TOT"]+$totComp_16["TOT"]+$totComp_10["TOT"]+$totComp_5["TOT"]+$totComp_0["TOT"])) ?></span></td>
</tr>
<tr id="imp">
<td colspan="5" align="center">
<input type="button" value="IMPRIMIR" name="boton" onclick="imprimir();" />
<input type="button" value="MS EXCEL" name="boton" onclick="location.assign('<?php echo "$url?boton=MS EXCEL" ?>')" /></td>
</tr>
</table>

</div>
</body>
</html>