<?php
require_once("Conexxx.php");
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$url=thisURL();
$boton=r("boton");
if($boton=="MS EXCEL"){excel("RELACION GASTOS");}
$CONT=0;
$T_DEB=0;
$T_CRE=0;
$CRED=0;  

$TOTAL=0;

$TIPO_INF=1;

if(!empty($_REQUEST["TIPO_INF"]))$TIPO_INF=r("TIPO_INF");

//$FILTRO_INVERSIONES=" AND tipo_gasto!='Consignacion Ventas' AND tipo_gasto!='Transferencia Entre Cuentas' AND tipo_gasto!='Inversion Negocio' AND tipo_gasto!='Inversion Personal'";

/*

SELECT
 WEEKOFYEAR(`fecha`) AS period,
 DATE(subdate(fecha, INTERVAL weekday(fecha) DAY)) prim_dia,
 DATE(adddate(`fecha`, INTERVAL 6-weekday(`fecha`) DAY)) ult_dia,
 SUM(valor) AS valor
FROM `comp_egreso`
WHERE DATE(fecha)>='2016-08-01' AND DATE(fecha)<='2016-08-31'
GROUP BY period

*/
?>

<!DOCTYPE html  >
<html  >
<head>
<?php //require("HEADER_UK.php"); 
if($boton!="MS EXCEL"){
?>
<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="css/animate.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">

<title>RELACION GASTOS <?php echo "$fechaI   a   $fechaF";?></title>
<meta http-equiv=Content-Type content="text/html; charset=<?php echo $CHAR_SET ?>">

<?php require_once("IMP_HEADER.php"); 
}

?>
</head>

<body style="font-size:12px">
<div style="width:21.5cm; height:27.9cm; padding-left:10px;">
<table align="center" width="100%">
<tr>

<td valign="top">
<p align="left" style="font-size:12px;">
<span align="center" style=" font-size:20px"><B>RELACI&Oacute;N DE GASTOS</B></span></p>
</td>

</tr>
</table>

<table align="center" width="100%">
<tr style="font-size:16px; font-weight:bold;">
<td >
Desde: <?PHP echo $_SESSION['fechaI'] ?>
</td>
<td> Hasta: <?PHP echo $_SESSION['fechaF'] ?>
</td>
</tr>
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">

</table>
</td>
</tr>
</table>


 
<table align="center" width="100%" cellpadding="0" cellspacing="0"  id="TabClientes" class="display  table-striped table-bordered table-hover" rules="all" frame="box">
<thead  >
<tr style="font-size:13px;" class="uk-block-secondary uk-contrast ">
<?php if($TIPO_INF==1)echo "<th>#</th><th>Fecha</th>";
$FORMAS_PAGO[]=0;
$FORMAS_PAGO_TEMP[][]=0;
$limit=count($FP_egresos);
	for($i=0;$i<$limit;$i++){
		
	$FORMAS_PAGO[$FP_egresos[$i]]=0;
	$FORMAS_PAGO_TEMP[$FP_egresos[$i]] =0;	
		
	}

?>

<th align="center">Tipo</th>

<th align="">Total</th>
<th> FTE</th>
<!--
<th> ICA</th>
-->
</tr>
</thead>
<tbody>
<?php
 
////////////////////////////////////////////// EGRESOS //////////////////////////////////////////////////
$filtroAnula="anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')";
$filtroAnula="anulado!='ANULADO' ";

 
$GROUP_BY="GROUP BY num_com";
if($TIPO_INF==2)$GROUP_BY="GROUP BY tipo_gasto";
$sql="select tipo_gasto,tipo_pago,SUM(valor) as valor,SUM(valor-(r_fte+r_ica)) as valor2,SUM(r_fte) as r_fte, DATE(fecha) as fe2 FROM comp_egreso WHERE DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND cod_su='$codSuc' AND ($filtroAnula) $FILTRO_INVERSIONES $GROUP_BY ORDER BY valor DESC";
//echo "$sql";
$rs=$linkPDO->query($sql);
$TOT=0;
$TOT_CONT_CAJA=0;
$TOT_TDEB=0;
$TOT_TCRE=0;
$TOT_CRE=0;
$TOT_CHE=0;

$R_FTE=0;
$R_ICA=0;

$SUM_FTE=0;

$DISTRITO=0;
$SALIM=0;
$ZONA=0;
$SUM_10=0;

$OFRENDA_MISIONERA=0;
$OFRENDA_ZONAL=300000;

$SEGURIDAD_SOCIAL=0;
$RESERVA_PRESTACIONES=0;
$CAJA_COMPENSACION=0;
$SOLIDARIDAD_PENSIONAL=0;
while($row=$rs->fetch())
{
	$total_contado_fac=0;
	$total_cheque_fac=0;
	$total_contado_caja_fac=0;
	$total_Tdeb_fac=0;
	$total_Tcre_fac=0;
	$total_credito_fac=0;
	
	$fechaComp=$row["fe2"];

	$subTotArt=$row['valor2']*1;
	$cant=1;
	$val_uni=$row['valor']*1;
	$val_fac=$row['valor2']*1;
	$r_fte=$row['r_fte'];
	
	$SUM_FTE+=$r_fte;
	
		
	
	$val_tot=$val_uni*$cant;
	$tipo_venta=$row['tipo_pago'];	
	$TOTAL+=$row['valor2'];
	$FORMAS_PAGO_TEMP[$tipo_venta]=0;
	$R_FTE+=$r_fte;

	
	$TOT+=$row['valor'];
	
	$tipo=$row['tipo_gasto'];
	
	$tipo2=limpianum($tipo);
	
	switch($tipo2){
	case 281505: $DISTRITO+=$val_tot;break;
	case 281510: $SALIM+=$val_tot ;break;
	case 281515: $ZONA+=$val_tot ;break;
	
	case 510558: $SEGURIDAD_SOCIAL+=$val_tot ;break;
	case 510569: $SEGURIDAD_SOCIAL+=$val_tot ;break;
	case 510570: $SEGURIDAD_SOCIAL+=$val_tot ;break;
	
	case 510530: $RESERVA_PRESTACIONES+=$val_tot ;break;
	case 510533: $RESERVA_PRESTACIONES+=$val_tot ;break;
	case 510536: $RESERVA_PRESTACIONES+=$val_tot ;break;
	case 510539: $RESERVA_PRESTACIONES+=$val_tot ;break;
	
	case 510572: $CAJA_COMPENSACION+=$val_tot ;break;
	case 510575: $CAJA_COMPENSACION+=$val_tot ;break;
	case 510578: $CAJA_COMPENSACION+=$val_tot ;break;
	
	
	case 510580: $SOLIDARIDAD_PENSIONAL+=$val_tot ;break;
	}
	
				
	
	
	?>
    <tr>

     <td><?php echo "$tipo"  ?></td>
    
    <td align="right"><?php echo money2($val_tot) ?></td>
    <td align="right"><?php echo money2($r_fte) ?></td>
    
   
  
    </tr>
    
    <?php
	
	
}//////////////////////////////////// FIN EGRESOS ////////////////////////

?>
 <tfoot>
<tr style="font-size:16px;">
<?php 
$SUM_FTE=money($SUM_FTE);
?>
<td>TOTAL PAGOS</td>
<td align="right" ><?php echo money3($TOT); ?></td>
<td ><?php //echo $SUM_FTE; ?></td>

</tr>

 </tfoot>
</tbody>
</table>
<h5>RESUMEN DINEROS A ENTREGAR</h5>
<table align="center" width="100%" cellpadding="5" cellspacing="1">
<tr valign="top">
<td>
<table align="center" width="50%" cellpadding="0" cellspacing="0"    class="table table-striped table-bordered table-hover" rules="all" frame="box">
<thead  >
<tr style="font-size:13px;" class="uk-block-secondary uk-contrast ">
<th>Detalle</th><th>Cantidad</th>
</tr>
</thead>
<tbody>
<tr>
<td>7% DISTRITO 281505</td><td align="right"><?php echo money3($DISTRITO);?></td>
</tr>
<tr>
<td>1% SALIM    281510</td><td align="right"><?php echo money3($SALIM);?></td>
</tr>
<tr>
<td>2% ZONA     281515</td><td align="right"><?php echo money3($ZONA);?></td>
</tr>

</tbody>
<?php $TOT_10=$ZONA+$SALIM+$DISTRITO;
$OFRENDA_MISIONERA=400000;
//$OFRENDA_ZONAL=0;

$GRAND_TOTAL=$TOT_10+$OFRENDA_MISIONERA+$SEGURIDAD_SOCIAL+$RESERVA_PRESTACIONES+$R_FTE+$OFRENDA_ZONAL+$CAJA_COMPENSACION+$SOLIDARIDAD_PENSIONAL;
?>
<tfoot>
<tr>
<td>TOTAL:</td><td align="right"><?php echo money3($TOT_10);?></td>
</tr>
</tfoot>
</table>
<table>
<tr>
<td>FECHA ENTREGA</td><td colspan="2">&nbsp;______________</td>
</tr>
<tr>
<td colspan="3">&nbsp;</td>
</tr>
<tr>
<td>RECIBIDO</td><td colspan="2">&nbsp;_____________________</td>
</tr>

</table>
</td>


<td>
<table align="center" width="50%" cellpadding="0" cellspacing="0"  id="" class="table table-striped table-bordered table-hover" rules="all" frame="box">
<thead  >
<tr style="font-size:13px;" class="uk-block-secondary uk-contrast ">
<th>Detalle</th><th>Cantidad</th>
</tr>
</thead>
<tbody>
<tr>
<td>Total del 10 %</td><td align="right"><?php echo money3($TOT_10);?></td>
</tr>
<tr>
<td>Ofrenda Misionera Distrital</td><td align="right"><?php echo money3($OFRENDA_MISIONERA);?></td>
</tr>
<tr>
<td>Seguridad Social</td><td align="right"><?php echo money3($SEGURIDAD_SOCIAL);?></td>
</tr>
<tr>
<td>Reserva Prestaciones</td><td align="right"><?php echo money3($RESERVA_PRESTACIONES);?></td>
</tr>
<tr>
<td>Retención en la Fuente</td><td align="right"><?php echo money3($R_FTE);?></td>
</tr>
<tr>
<td>Ofrenda zonal</td><td align="right"><?php echo money3($OFRENDA_ZONAL);?></td>
</tr>
<tr>
<td>CAJA COMPENSACION</td><td align="right"><?php echo money3($CAJA_COMPENSACION);?></td>
</tr>
<tr>
<td>SOLIDARIDAD PENSIONAL</td><td align="right"><?php echo money3($SOLIDARIDAD_PENSIONAL);?></td>
</tr>

</tbody>
<tfoot>
<tr>
<td>TOTAL:</td><td align="right"><?php echo money3($GRAND_TOTAL)?></td>
</tr>
</tfoot>

</table>

</td>
</tr>
</table>

<table align="center" width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover" rules="all" frame="box">
<thead  >
<tr style="font-size:13px;" class="uk-block-secondary uk-contrast ">
<th>Nombre</th><th>Reservas</th><th>Caja</th><th>Sena</th><th>ICBF</th><th>Sueldo</th><th>Seguridad Social</th>
</tr>
</thead>
<tbody>
<tr>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
</tr>

</tbody>
</table>


<div id="imp" align="center">

<input type="button" value="IMPRIMIR" name="boton" onClick="imprimir()" />
<!--<input type="button" value="MS EXCEL" name="boton" onClick="location.assign('<?phP echo "$url&boton=MS EXCEL"; ?>')" />-->


</div>
</div>
<?php 
if($boton=="MS EXCEL"){
require_once("FOOTER_UK.php"); ?>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script src="JS/bootstrap.min.js"></script>
<script src="JS/plugins/dataTables/datatables.min.js"></script>
<script language="javascript1.5" type="text/javascript">
$(document).ready(function()
	{}
);
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
<?php }?>
</body>
</html>