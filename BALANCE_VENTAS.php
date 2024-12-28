<?php
require_once("Conexxx.php");
$CodCajero=s("cod_caja_arq");
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
//$cod_su=$_SESSION['cod_su'];
$horaI=s("horaI");
$horaF=s("horaF");
$num_serial_arq=s("numSerialArq");
$filtroHora="";
$filtroHoraAnula="";
if(!empty($horaI) && !empty($horaF))$filtroHora=" AND (fecha>='$fechaI $horaI' AND fecha<='$fechaF $horaF')";
if(!empty($horaI) && !empty($horaF))$filtroHoraAnula=" AND (fecha_anula>='$fechaI $horaI' AND fecha_anula<='$fechaF $horaF')";

$multisede=r("opc_multi");
?>
<!DOCTYPE html  >
<html  >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<?php require_once("HEADER_UK.php"); ?>
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};</script>
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
<?php
 
if($multisede==1){$TOT_VENTAS0516=tot_ventas_0516($fechaI,$fechaF,"all",$horaI,$horaF,$CodCajero,1);}
else {$TOT_VENTAS0516=tot_ventas_0516($fechaI,$fechaF,$codSuc,$horaI,$horaF,$CodCajero,1);}

?>

<div class=" " style="padding:20px;">
<table align="center" width="100%">
<tr>
<td align="left" colspan="2">
<?php echo $PUBLICIDAD2 ?>
</td>
 

</tr>
</table>
Fecha INFORME: <?PHP echo fecha($FechaHoy)."     Hora: ".$hora ?>
<br>

<table align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="font-size:24px; font-weight:bold;">
Desde: <?PHP echo fecha($_SESSION['fechaI'])."&nbsp; $horaI" ?>
</td>
<td style="font-size:24px; font-weight:bold;"> Hasta: <?php echo fecha($_SESSION['fechaF'])."&nbsp; $horaF" ?>
</td>
</tr>
 
 
</table>
<br /><br /><br />


<div class="uk-grid " >
<div class="uk-width-3-10  push-1">
<h2 align="left" class="uk-text-primary uk-text-bold"><b>VENTAS TOTALES</b></h2>
<table style="font-size:12px; width:200px;" cellspacing="0" align="left">
<?php if($TOT_VENTAS0516[0][0]!=0){ ?>
<tr>
<td colspan="4"><?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP  echo money3(redondeo($TOT_VENTAS0516[0][0])) ?></span></td>
</tr>
<?php } ?>
<?php if($TOT_VENTAS0516[5][1]!=0){ ?>
<tr>
<td colspan="4">BASE IVA 5%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[5][1]) ?></span></td>
</tr>
<tr>
<td colspan="4">VALOR IVA 5%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[5][2]) ?></span></td>
</tr>
<?php } ?>

<?php if($TOT_VENTAS0516[10][1]!=0){ ?>
<tr>
<td colspan="4">BASE IVA 10%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[10][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 10%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[10][2]) ?></span></td>
</tr>
<?php } ?>


<?php if($TOT_VENTAS0516[16][1]!=0){ ?>
<tr>
<td colspan="4">BASE IVA 16%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[16][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 16%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[16][2]) ?></span></td>
</tr>
<?php } ?>

<?php if($TOT_VENTAS0516[19][1]!=0){ ?>
<tr>
<td colspan="4">BASE IVA 19%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[19][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 19%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[19][2]) ?></span></td>
</tr>
<?php } ?>

<?php if($TOT_VENTAS0516[8][1]!=0){ ?>
<tr>
<td colspan="4">Imp. Consumo 8%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[8][1]) ?></span></td>
</tr>
<?php } ?>

<?php if($TOT_VENTAS0516[20][1]!=0){ ?>
<tr>
<td colspan="4">Imp. Bolsas :</td><td><span ><?PHP echo money3($TOT_VENTAS0516[20][1]) ?></span></td>
</tr>
<?php } ?>


<tr>
<td colspan="4">TOTAL:</td><td> <span ><?PHP echo money3(redondeo($TOT_VENTAS0516[1][1])) ?></span></td>
</tr>
<tr>
<td colspan="5">
<div id="imp"  align="center" class="uk-width-1-1  ">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="imprimir();" />
</div> 
</td>
</tr>
</table>

</div>


<?php
 
if($multisede==1){$TOT_VENTAS0516=tot_dev_ventas($fechaI,$fechaF,"all",$horaI,$horaF,$CodCajero,1);}
else {$TOT_VENTAS0516=tot_dev_ventas($fechaI,$fechaF,$codSuc,$horaI,$horaF,$CodCajero,1);}

?>

<div class="uk-width-3-10  push-1">
<h2 align="left" class="uk-text-primary uk-text-bold"><b>DEVOLUCIONES VENTAS</b></h2>
<table style="font-size:12px; width:200px;" cellspacing="0" align="left">
<?php if($TOT_VENTAS0516[0][0]!=0){ ?>
<tr>
<td colspan="4"><?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP  echo money3(redondeo($TOT_VENTAS0516[0][0])) ?></span></td>
</tr>
<?php } ?>
<?php if($TOT_VENTAS0516[5][1]!=0){ ?>
<tr>
<td colspan="4">BASE IVA 5%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[5][1]) ?></span></td>
</tr>
<tr>
<td colspan="4">VALOR IVA 5%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[5][2]) ?></span></td>
</tr>
<?php } ?>

<?php if($TOT_VENTAS0516[10][1]!=0){ ?>
<tr>
<td colspan="4">BASE IVA 10%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[10][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 10%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[10][2]) ?></span></td>
</tr>
<?php } ?>


<?php if($TOT_VENTAS0516[16][1]!=0){ ?>
<tr>
<td colspan="4">BASE IVA 16%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[16][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 16%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[16][2]) ?></span></td>
</tr>
<?php } ?>

<?php if($TOT_VENTAS0516[19][1]!=0){ ?>
<tr>
<td colspan="4">BASE IVA 19%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[19][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 19%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[19][2]) ?></span></td>
</tr>
<?php } ?>


<tr>
<td colspan="4">TOTAL:</td><td> <span ><?PHP echo money3(redondeo($TOT_VENTAS0516[1][1])) ?></span></td>
</tr>
<tr>
<td colspan="5">
 
</td>
</tr>
</table>

</div>


</div>








</div>








</div>
<?php require_once("FOOTER_UK.php"); ?>
</body>
</html>