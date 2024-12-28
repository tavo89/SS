<?php
require_once("Conexxx.php");
$url=thisURL();
$boton=r("boton");
$resumen=r("resumen");
$filtro=r("filtro");
$fechaMora=r("fecha_mora");
if($boton=="MS EXCEL"){excel("Cartera Clientes");}

$FILTRO_SEDES_FAC="AND nit=$codSuc";

if($MODULES["MULTISEDES_UNIFICADAS"]==1){$FILTRO_SEDES_FAC="";}


//////////////////////////////////////////////////////////// FILTROS VARIOS //////////////////////////////////////////////////

$FILTRO_A="";
if($filtro==1){$FILTRO_A=" AND (DATEDIFF(CURRENT_DATE(),DATE(fecha) )>$DIAS_BAN_CLI AND fecha_pago='0000-00-00 00:00:00')";}

$FILTRO_MOROSOS_FECHA_X="";

$FILTRO_MORA_COMPROBANTES_WHERE=" ";
$FILTRO_MORA_COMPROBANTES=" ";
if(!empty($fechaMora)){$FILTRO_MOROSOS_FECHA_X=" AND (DATEDIFF('$fechaMora',DATE(fecha) )>=$DIAS_BAN_CLI AND (fecha_pago='0000-00-00 00:00:00' OR DATE(fecha_pago)>='$fechaMora' ))";

$FILTRO_MORA_COMPROBANTES_WHERE=" WHERE DATE(fecha)<='$fechaMora' ";
$FILTRO_MORA_COMPROBANTES=" AND  DATE(fecha)<='$fechaMora' ";
}



//////////////////////////////////////////////////////////// FILTRO FECHA ///////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_cre";
$PAG_fechaF="fechaF_cre";
$A="";
$B="";

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=limpiarcampo($_SESSION[$PAG_fechaI]);}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=limpiarcampo($_SESSION[$PAG_fechaF]);}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////// FILTRO NOMBRE //////////////////////////////////////
$campo1="nom_cli";
$nom_cli="";
if(isset($_SESSION[$campo1]) && !empty($_SESSION[$campo1])){$nom_cli=$_SESSION[$campo1];$B=" AND (nom_cli='$nom_cli' OR id_cli='$nom_cli')";}


/////////////////////////////////////////////////////////////////////////////////////////


$trabajoTerceros=0;
$id_clientes[]="";
$nom_clientes[]="";
$abonos[][]=0;

$rs=$linkPDO->query("SELECT id_cli,nom_cli,prefijo,num_fac_ven from fac_venta WHERE tipo_venta='Credito' $FILTRO_SEDES_FAC  AND ".VALIDACION_VENTA_VALIDA."  $A $B $FILTRO_A $FILTRO_MOROSOS_FECHA_X GROUP BY id_cli order by 3" );
$i=0;
while($row=$rs->fetch())
{
	$id_clientes[$i]=ucwords(strtolower($row["id_cli"]));
	$nom_clientes[$i]=ucwords(strtolower($row["nom_cli"]));
	$abonos[$row['prefijo']][$row['num_fac_ven']]=0;
	$i++;
	
}

$sql="SELECT prefijo,num_fac_ven from fac_venta WHERE  tipo_venta='Credito' $FILTRO_SEDES_FAC  AND ".VALIDACION_VENTA_VALIDA."  $A $B GROUP BY num_fac_ven,prefijo";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$abonos[$row['prefijo']][$row['num_fac_ven']]=0;
	
	
}
$sql="SELECT SUM(a.abono) as tot,a.pre,a.num_fac FROM cartera_mult_pago a LEFT JOIN (SELECT cod_su,fecha,num_com,cajero,valor,num_fac,pre,anulado FROM comprobante_ingreso WHERE cod_su='$codSuc' $FILTRO_MORA_COMPROBANTES ) b ON a.num_comp=b.num_com WHERE  estado!='ANULADO' AND a.cod_su=b.cod_su AND a.cod_su='$codSuc' $FILTRO_MORA_COMPROBANTES GROUP BY num_fac,pre";
$rs=$linkPDO->query($sql);

while($row=$rs->fetch())
{
	$abonos[$row['pre']][$row['num_fac']]=$row['tot']*1;
	
	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>Estados de Cuenta</title>
<?php require_once("IMP_HEADER.php"); ?>
</head>

<body>
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<?php //echo "<p style=\"z-index:999999\"><br>$sql</p>"; ?>
<table align="center" width="100%">
<tr>
<td colspan="3">
<?php echo $PUBLICIDAD2 ?></td>
<td valign="top" colspan="3">
<p align="left" style="font-size:12px;">
<span align="center" style="font-size:24px"><B>ESTADOS DE CUENTA-P&Uacute;BLICO</B></span>
</p>
</td>

</tr>
</table>
Fecha Informe: <?PHP echo $hoy ?>
<br>
<b>FILTROS</b>
<?php
if(!empty($A))echo "<li>FECHA:  Desde $fechaI Hasta $fechaF </li>";
if(!empty($B))echo "<li>CLIENTE:  $nom_cli </li>";
if(!empty($fechaMora))echo "<li>MOROSOS A <b>$fechaMora</b></li>";

?>

<table frame="box" rules="all" cellpadding="5" cellspacing="3" border="1" style="max-width:21.5cm;">
<tr>
<th width="80px">No. Factura</th><th width="80px">Fecha</th><th>Valor Fac.</th><th>Abonado</th><th>Saldo</th><th width="80px">D&iacute;as Mora</th>
</tr>
<?php
$tot_saldo=0;
$tot_abono=0;
$TOT_ABONO=0;
$TOT_SALDO=0;
for($i=0;$i<count($id_clientes);$i++)
{
	$tot_saldo=0;
	$tot_abono=0;
	$sql_mora="DATEDIFF(CURRENT_DATE(),DATE(fecha) )";
	$sql_mora2="DATEDIFF(DATE(fecha_pago),DATE(fecha) )";
	if(!empty($fechaMora)){
	$sql_mora="DATEDIFF('$fechaMora',DATE(fecha) )";
	$sql_mora2=$sql_mora;
	}
	$sql="SELECT *,$sql_mora AS mora,$sql_mora2 AS mora2,fecha_pago FROM fac_venta WHERE id_cli='$id_clientes[$i]' AND ".VALIDACION_VENTA_VALIDA." AND tipo_venta='Credito'  $A $B $FILTRO_A $FILTRO_SEDES_FAC $FILTRO_MOROSOS_FECHA_X";
	//echo "$sql";
	$rs=$linkPDO->query($sql);
	if($resumen!=1){
	?>
  
<tr>
<td colspan="6"><b><?php echo $id_clientes[$i]." - ".$nom_clientes[$i] ?></b></td>
</tr> 
 
    <?php
	}
	while($row=$rs->fetch())
	{
		$fechaPago=$row["fecha_pago"];
		$pre=$row['prefijo'];
		$numFac=$row['num_fac_ven'];
		$sede=$row['nit'];
		$totFac=$row['tot']*1;
		$fecha=$row['fecha'];
		$mora=$row['mora'];
			$mora2=$row['mora2'];
			$estado = $row["estado"];
			if($estado=="PAGADO" && empty($fechaMora)){$mora=$mora2;$abonos[$pre][$numFac]=$totFac;}
			$saldo=$totFac-$abonos[$pre][$numFac];
		$abono=$abonos[$pre][$numFac];
		$tot_abono+=$abono;
		$tot_saldo+=$saldo;
		$TOT_ABONO+=$abono;
		$TOT_SALDO+=$saldo;
		fix_cartera($id_clientes[$i],$numFac,$pre,$codSuc,$abono,$totFac,$estado,$fechaPago);
		if($abonos[$pre][$numFac]>0 && !empty($B)){fix_cartera($id_clientes[$i],$numFac,$pre,$codSuc,$abono,$totFac,$estado,$fechaPago);}
		if($resumen!=1){
		?>
 <tr>
 <td align="left"><?php echo "$pre $numFac" ?></td><td align="left"><?php echo $fecha ?></td><td align="right"><?php echo money3($totFac) ?></td>
 <td align="right"><?php echo money3($abonos[$pre][$numFac]) ?></td>
 <td align="right"><?php echo money3($totFac-$abonos[$pre][$numFac]) ?></td>
 <td align="right" width="60px"><?php echo $mora ?></td>
 
 </tr>
<?php
		}
if($abonos[$pre][$numFac]>0 && !empty($B)){echo "<tr><td colspan=\"6\"><b>Lista Abonos Factura $pre $numFac:</b></td></tr>";

$sqlAbonos="SELECT b.cajero, b.num_com,b.valor,a.abono,DATE(b.fecha) as fecha2 FROM cartera_mult_pago a LEFT JOIN (SELECT fecha,cod_su,num_com,cajero,valor,num_fac,pre,anulado FROM comprobante_ingreso WHERE cod_su='$codSuc' $FILTRO_MORA_COMPROBANTES ) b ON a.num_comp=b.num_com WHERE ((a.num_fac='$numFac' AND a.pre='$pre') OR (b.num_fac='$numFac' AND b.pre='$pre')) AND b.anulado!='ANULADO' AND a.cod_su=b.cod_su AND a.cod_su='$codSuc' ORDER BY b.fecha";
$rsAbonos=$linkPDO->query($sqlAbonos);
?>

<tr>
<td colspan="6">
<table cellspacing="5">


<?php
while($rowAbonos=$rsAbonos->fetch())
{
	
$numCompIngreso=$rowAbonos['num_com'];
$fechaComp=$rowAbonos['fecha2'];
$valorComp=$rowAbonos['abono'];
$cajeroComp=$rowAbonos['cajero'];
	?>
<tr>
<td><li><?php echo "#$numCompIngreso"; ?></li></td>
<td><?php echo "$fechaComp"; ?></td>
<td><?php echo money3("$valorComp"); ?></td>
</tr>
    
    
    <?php
	
}
?>
</table>
<?php
	}// FIN ABONOS DISPONIBLES
	}//FIN WHILE MAIN
	?>
<tr style="<?php if($resumen!=1){echo "background-color: #CFF";} ?>">
<td colspan="3" align="left"><b>TOTAL <?php echo strtoupper($nom_clientes[$i]) ?></b></td><td><?php echo money3($tot_abono) ?></td><td><?php  echo money3($tot_saldo)?></td><td></td>
</tr> 
    
    <?php

		
	
}


?>
<tr>
<tH colspan="3"></th><th>ABONOS</th><th>SALDOS</th><td></td>
</tr>
<tr>
<tH colspan="3" style="font-size:18px; font-weight:bold;"><b>TOTALES</b></th><td><?php echo money3($TOT_ABONO) ?></td><td><?php  echo money3($TOT_SALDO)?></td><td></td>
</tr>
<tr>
<tH colspan="3" style="font-size:18px; font-weight:bold;"><b>TOTAL VENTAS CREDITO</b></th><td colspan="3"><?php echo money3($TOT_ABONO+$TOT_SALDO) ?></td>
</tr>
<tr id="imp">

<td colspan="3" align="center"><input type="button" value="IMPRIMIR" name="boton" onclick="imprimir()" /></td>
<td colspan="3" align="center"><input type="button" value="MS EXCEL" name="boton" onclick="location.assign('<?php if($resumen!=1 && $filtro!=1 && empty($fechaMora)){echo "$url?boton=MS EXCEL";}else {echo "$url&boton=MS EXCEL";} ?>')" /></td>


</tr>
</table>
</div>
</body>
</html>