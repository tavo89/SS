<?php
require_once("Conexxx.php");
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];

$lastDay=date("t",strtotime($fechaI));

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














 
////////////////////////////////////////////// EGRESOS //////////////////////////////////////////////////
$filtroAnula="anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')";
$filtroAnula="anulado!='ANULADO' ";

 
$GROUP_BY="GROUP BY tipo_gasto";
if($TIPO_INF==2)$GROUP_BY="";
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
$OFRENDA_ZONAL=0;

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
	
	case 510572: $CAJA_COMPENSACION+=$val_tot ;break;
	case 510575: $CAJA_COMPENSACION+=$val_tot ;break;
	case 510578: $CAJA_COMPENSACION+=$val_tot ;break;
	
	
	case 510580: $SOLIDARIDAD_PENSIONAL+=$val_tot ;break;
	}
	
				
	
	

	
	
}//////////////////////////////////// FIN EGRESOS ////////////////////////




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

<title>RELACION INGRESOS <?php echo "$fechaI   a   $fechaF";?></title>
<meta http-equiv=Content-Type content="text/html; charset=<?php echo $CHAR_SET ?>">

<?php require_once("IMP_HEADER.php"); 
}

?>
</head>

<body style="font-size:12px">
<div style="width:21.5cm; height:27.9cm; padding-left:10px;">
<table align="center" >
<tr valign="top">

<td valign="top">
<p align="left" style="font-size:12px;">
<span align="center" style=" font-size:20px"><B><?php echo "$NOM_NEGOCIO";?></B></span>
<br>
Con personer&iacute;a no. 2.002 Diciembre 19 de 1996 NIT <?php echo "$NIT_NEGOCIO";?>
</p>
</td>
<td><img src="<?php echo "$url_LOGO_A";?>" width="150px" height="100px"></td>

</tr>
<tr valign="top">
<td valign="top" colspan="2" align="center"><h4>REPORTE MENSUAL</h4></td>
</tr>
</table>


<table cellpadding="5" cellspacing="5">
<tr>
<td>Lugar: &nbsp;</td><td><b><?php echo "$munSuc";?></b>&nbsp;</td><td>Mes y a&ntilde;o&nbsp;: &nbsp; </td><td><b><?php echo mes_year($fechaI);?></b></td><td>&nbsp;ZONA: &nbsp;</td><td>________________</td>
</tr>
<tr>
<td>PASTOR:&nbsp;</td><td>____________________</td><td>&nbsp;TEL:&nbsp;</td><td>_____________</td>
</tr>
</table>
<br>
<p>
Líderes ___  Amigos ___ Convertidos ___ Reconciliaciones ___ Bautizados en Agua ___
<br>
Miembros Activos ___  Inactivos  Retirados ___   Integrados___ Defunciones___ Total Miembros ___
<br>
Promedio Reuniones: Escuela D. Adultos:  ___  Niños ___ Domingo Noche: Adultos ___ Niños ___
<br>
No. Grupos Familiares ___  Asistencia Adultos  ___ Niños  ___  Semanal ___ Jóvenes ___ Damas____   
<br>
Caballeros_____       Tercera Edad_____
</p>
<!--
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
-->
<br>
<h4>INGRESOS</h4>
<table align="left" width="100%" cellpadding="0" cellspacing="0"    class=" table-striped table-bordered table-hover" rules="all" frame="box">
<thead  >
<tr style="font-size:13px;" class="uk-block-secondary uk-contrast ">
<?php
?>

<th align="">Dias/Semana</th>

<th align="">Valor Diezmo</th>
<th> Valor Ofrenda</th>

<th>TOTAL</th>

</tr>
</thead>
<tbody>
<?php


$mesAux=date("m",strtotime($fechaI));
$yearAux=date("Y",strtotime($fechaI));
$base_fecha="$yearAux-$mesAux";
$dia_i=1;
$dia_f=7;
$tot_ofrenda=0;
$tot_diezmo=0;
$SUB_TOT1=0;
$SUB=0;
$rangoFecha=" AND (DATE(fecha)>='$base_fecha-$dia_i' AND DATE(fecha)<='$base_fecha-$dia_f'  )";
for($i=0;$i<=3;$i++){


if($i==0){$dia_i="01";$dia_f="07";}
if($i==1){$dia_i="08";$dia_f=14;}
else if($i==2){$dia_i=15;$dia_f=21;}
else if($i==3){$dia_i=22;$dia_f=$lastDay;}

$rangoFecha=" AND (DATE(fecha)>='$base_fecha-$dia_i' AND DATE(fecha)<='$base_fecha-$dia_f'  )";

$sql="SELECT SUM(a.pvp) as pvp FROM serv_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven AND a.prefijo=b.prefijo WHERE cod_serv='02'  $rangoFecha AND b.anulado!='ANULADO' GROUP BY cod_serv";
//echo "<li>$sql</li><br>";
$rsAux=$linkPDO->query($sql);
if($rowAux=$rsAux->fetch()){
$tot_ofrenda=$rowAux["pvp"];
//echo "$tot_ofrenda - ".$rowAux["pvp"]."<br>";
}
$sql="SELECT SUM(a.pvp) as pvp FROM serv_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven AND a.prefijo=b.prefijo WHERE cod_serv='01'  $rangoFecha AND b.anulado!='ANULADO'";
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$tot_diezmo=$row["pvp"];

$SUB=$tot_diezmo+$tot_ofrenda;
$SUB_TOT1+=$SUB;
?>
<tr>
<td><?php echo "$dia_i al $dia_f"; ?></td>
<td><?php echo money3($tot_diezmo); ?></td>
<td><?php echo money3($tot_ofrenda); ?></td>
<td><?php echo money3($SUB); ?></td>
</tr>
<?php	
	
}
?>
 </tbody>
 <tfoot>
 <tr>
 <td></td><td></td><td>Sub TOTAL (1)</td><td><?php echo money3($SUB_TOT1);?></td>
 </tr>
 </tfoot>
</table>
<br><br><br><br><br>
<h5>OTROS INGRESOS</h5>
<table align="left" width="50%" cellpadding="0" cellspacing="0"   class=" table-striped table-bordered table-hover" rules="all" frame="box">
<thead  >
<tr style="font-size:13px;" class="uk-block-secondary uk-contrast ">
<th></th><th>Detalle</th><th>Valor</th>
</tr>
</thead>
<tbody>
<tr>
<td></td><td >GRUPOS FAMILIARES</td><td></td></tr>			
<tr>
<td></td><td >OFRENDA MISIONERA	</td><td></td></tr>		
<tr>
<td></td><td >INGRESOS PRO-TEMPLO	</td><td></td></tr>		
<tr>
<td></td><td >MINISTERIO INFANTIL	</td><td></td></tr>		
<tr>
<td></td><td >INGRESOS OBRAS ANEXAS	</td><td></td></tr>		
<tr>
<td></td><td >INGRESOS PRO-INSTRUMENTOS	</td><td></td></tr>		
<tr>
<td></td><tD>INGRESOS INSTITUTO BÍBLICO	</td><td></td></tr>		
<tr>
<td></td><td >PRESTAMOS A LA IGLESIA	</td><td></td></tr>		
<tr>
<td></td><td >OTROS CONCEPTOS DE INGRESOS</td><td></td></tr>

</tbody>
<?php 

?>
<tfoot>
<tr>
<td colspan="2">Sub TOTAL Otros Ingresos (2):</td><td align="right"><?php echo "";?></td>
</tr>
</tfoot>
</table>
<br><br><br><br><br><br><br><br><br><br><br>


<h5>REPORTE RETENCI&Oacute;N</h5>
<table align="left" width="100%" cellpadding="0" cellspacing="0"   class=" table-striped table-bordered table-hover" rules="all" frame="box">
<thead  >
<tr style="font-size:13px;" class="uk-block-secondary uk-contrast ">

<th>CODIGO</th>	<th>TARIFA</th>	         <th>C.C./NIT No.</th>	<th>	         NOMBRE O RAZON SOCIAL		</th><th>	V/ BASE RET.</th><th>	V/ RETENCION</th>

</tr>
</thead>
<tbody>
<?php
$GROUP_BY="GROUP BY id_beneficiario";

$sql="select *,tipo_gasto,tipo_pago,SUM(valor) as valor,SUM(valor-(r_fte+r_ica)) as valor2,SUM(r_fte) as r_fte, DATE(fecha) as fe2 FROM comp_egreso WHERE r_fte!=0 AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND cod_su='$codSuc' AND ($filtroAnula) $FILTRO_INVERSIONES $GROUP_BY ORDER BY beneficiario";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
$nomCli=$row['beneficiario'];
$idBene=$row["id_beneficiario"];
$r_fte=$row['r_fte'];
$val_uni=$row['valor']*1;
$val_fac=$row['valor2']*1;
$tarifa=$r_fte/$val_uni*100;
?>
<tr>
<td>&nbsp;</td>
<td><?php echo "$tarifa";?>%</td><td><?php echo "$idBene";?></td><td><?php echo "$nomCli";?></td><td><?php echo money3("$val_uni");?></td><td><?php echo money3("$r_fte");?></td>
</tr>
<?php 

}

?>
</tbody>
<?php 

?>
<tfoot>
<tr>
<td colspan="5">TOTAL RETENCIONES:</td><td align="right"><?php echo money("$SUM_FTE");?></td>
</tr>
</tfoot>
</table>
<br><br><br><br><br><br><br><br><br>


<table align="left" width="60%" cellpadding="0" cellspacing="0"   class=" table-striped table-bordered table-hover" rules="all" frame="box">
<thead  >
<tr style="font-size:13px;" class="uk-block-secondary uk-contrast ">

<th>CODIGO</th>	<th>CONCEPTOS</th>	         <th>&nbsp;</th>	<th>CODIGO</th>	<th>CONCEPTOS</th>

</tr>
</thead>
<tbody>
<tr>
<td>236505</td>	<td>SALARIOS</td> <td></td>		<td>236530</td><td>	ARRENDAMIENTOS</td></tr>
<tr>
	
<td>236515</td>	<td>HONORARIOS</td><td></td>		<td>236535</td><td>	RENDIMIENTOS FINANCIEROS</td></tr>
<tr>	
<td>236520</td><td>	COMISIONES</td><td></td>		<td>236540</td><td>	COMPRAS</td></tr>
<tr>
<td> 	
236525</td><td>	SERVICIOS</td><td></td>		<td>236570</td><td>	OTRAS RETENCIONES</td>	

</tr>
</tbody>
</table>

<br><br><br><br><br><br>
<h5>BALANCE FINANCIERO</h5>
<?php
$SALDO_ANT=saldo_mes_ant($fechaI);
$SALDO_MES=($SALDO_ANT+$SUB_TOT1)-$TOT;
//echo "$SALDO_MES=($SALDO_ANT+$SUB_TOT1)-$TOT;";
?>
<table align="left" width="90%" cellpadding="0" cellspacing="0"  class=" table-striped table-bordered table-hover" rules="all" frame="box">
<tr>
<td>SALDO FINAL MES ANTERIOR</td><td><?php echo money3($SALDO_ANT) ?></td><td>MAS (+) ENTRADAS DEL MES</td><td><?php echo money3($SUB_TOT1) ?></td>
</tr>

<tr>
<td>MENOS (-) GASTOS DEL MES</td><td><?php echo money3($TOT); ?></td><td>SALDO FINAL DEL MES</td><td><?php echo money3($SALDO_MES) ?></td>
</tr>

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