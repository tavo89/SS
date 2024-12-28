<?php
include_once("Conexxx.php");
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

$formaPago="Contado";
$filtroFormaPago="";
$formaPago="";
if(!empty($formaPago)){$filtroFormaPago=" AND tipo_venta='$formaPago'";}

if(isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI']))$fechaI=$_SESSION['fechaI'];
if(isset($_SESSION['fechaF'])&&!empty($_SESSION['fechaF']))$fechaF=$_SESSION['fechaF'];
if(isset($_SESSION['fechaF'])&&isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI'])&&!empty($_SESSION['fechaF']))$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF')";



$TOT_UTILIDAD_VENTAS=tot_utilidad($A,"","","","",$filtroFormaPago,$codSuc);
$TOT_VENTAS_SERVICIOS = $TOT_UTILIDAD_VENTAS["TOT_VENTAS_SERVICIOS"];
$TOT_UTIL=$TOT_UTILIDAD_VENTAS["TOT_UTIL"]+$TOT_VENTAS_SERVICIOS;
$TOT_COSTO_VENTAS=$TOT_UTILIDAD_VENTAS["TOT_VENTAS_COSTO_IVA"];
$TOT_VENTAS2=$TOT_UTILIDAD_VENTAS["TOT_VENTAS_PVP"];








 

$filtroAnula="anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')";
$filtroAnula="anulado!='ANULADO' ";

 
$sql="SELECT tipo_gasto,
             tipo_pago,SUM(valor) as valor,
			 SUM(valor-(r_fte+r_ica)) as valor2,
			 SUM(r_fte) as r_fte, DATE(fecha) as fe2 
			 FROM comp_egreso WHERE DATE(fecha)>='$fechaI' 
			 AND DATE(fecha)<='$fechaF' AND cod_su='$codSuc' AND ($filtroAnula) $FILTRO_INVERSIONES   ";
//echo "$sql";
$rs=$linkPDO->query($sql);
 

$R_FTE=0;
$R_ICA=0;

$SUM_FTE=0;
$TOT_GASTOS=0;
$TOTAL=0;
while($row=$rs->fetch())
{
	 
	
	 

	$subTotArt=$row['valor2']*1;
	$cant=1;
	$val_uni=$row['valor']*1;
	$val_fac=$row['valor2']*1;
	$r_fte=$row['r_fte'];
	
	$SUM_FTE+=$r_fte;
	
		
	
	$val_tot=$val_uni*$cant;
	$tipo_venta=$row['tipo_pago'];	
	$TOTAL+=$row['valor2'];
	 
	$R_FTE+=$r_fte;

	
	$TOT_GASTOS+=$row['valor'];
	//$TOT_GASTOS+=$row['valor'];
	
	 
}
?>
<!DOCTYPE html  >
<html  >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<?php include_once("HEADER_UK.php"); ?>
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
<?php
 
if($multisede==1){$TOT_VENTAS0516=tot_ventas_0516($fechaI,$fechaF,"all",$horaI,$horaF,$CodCajero,1);}
else {$TOT_VENTAS0516=tot_ventas_0516($fechaI,$fechaF,$codSuc,$horaI,$horaF,$CodCajero,1);}

?>

<div class="uk-grid " >
<div class="uk-width-1-1  ">
<h2 align="left" class="uk-text-primary uk-text-bold"><b>UTILIDAD NETA PRODUCTOS</b></h2>
<table cellpadding="5" cellspacing="10">
<thead>
    <tr>
    <th>Ventas Productos</th>
    <th>Costo Ventas P.</th>
    <th>Ventas Servicios</th>
    <th>Util Bruta</th>
    <th>Egresos </th>
    <th>Utilidad</th>
    </tr>
</thead>
<tbody>
    <tr>
    <td><?php echo money3(redondeo($TOT_VENTAS2)) ?></td>
    <td><?php echo money3(redondeo($TOT_COSTO_VENTAS)) ?></td>
    <td><?php echo money3(redondeo($TOT_VENTAS_SERVICIOS)) ?></td>
    <td><?php echo money3(redondeo($TOT_UTIL)) ?></td>
    <td><?php echo money3(redondeo($TOT_GASTOS)) ?></td>
    <td><?php echo money3(redondeo($TOT_UTIL-$TOT_GASTOS)) ?></td>
    </tr>
</tbody>
</table>
<!-- TOTAL: <?PHP echo money3(redondeo($TOT_VENTAS0516[1][1])) ?>-->

</div>


<?php
 
if($multisede==1){$TOT_DEV_VENTAS=tot_dev_ventas($fechaI,$fechaF,"all",$horaI,$horaF,$CodCajero,1);}
else {$TOT_DEV_VENTAS=tot_dev_ventas($fechaI,$fechaF,$codSuc,$horaI,$horaF,$CodCajero,1);}

?>
<!--
<div class="uk-width-3-10  push-1">
<h2 align="left" class="uk-text-primary uk-text-bold"><b> </b></h2>

TOTAL: <?PHP echo money3(redondeo($TOT_DEV_VENTAS[1][1])) ?> 

</div>
-->

</div>







<div id="imp"  align="center" class="uk-width-1-1  ">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="imprimir();" />
</div> 
</div>








</div>
<?php include_once("FOOTER_UK.php"); ?>
</body>
</html>