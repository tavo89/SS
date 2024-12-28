<?php
require_once("Conexxx.php");
$url=thisURL();
$boton=r("boton");
if($boton=="MS EXCEL"){excel("Cartera Clientes");}
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
if(isset($_SESSION[$campo1]) && !empty($_SESSION[$campo1])){$nom_cli=$_SESSION[$campo1];$B=" AND nom_cli='$nom_cli'";}


/////////////////////////////////////////////////////////////////////////////////////////


$trabajoTerceros=0;
$id_clientes[]="";
$nom_clientes[]="";
$abonos[][]=0;

$rs=$linkPDO->query("SELECT * from fac_venta WHERE nit=$codSuc AND tipo_venta='Credito' AND ".VALIDACION_VENTA_VALIDA." AND estado!='PAGADO' AND tipo_cli='Mostrador' $A $B GROUP BY id_cli order by 3");
$i=0;
while($row=$rs->fetch())
{
	$id_clientes[$i]=ucwords(strtolower($row["id_cli"]));
	$nom_clientes[$i]=ucwords(strtolower($row["nom_cli"]));
	$abonos[$row['prefijo']][$row['num_fac_ven']]=0;
	$i++;
	
}

$sql="SELECT * from fac_venta WHERE nit=$codSuc AND tipo_venta='Credito' AND ".VALIDACION_VENTA_VALIDA."  AND tipo_cli='Mostrador' $A $B GROUP BY num_fac_ven,prefijo";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$abonos[$row['prefijo']][$row['num_fac_ven']]=0;
	
	
}


$sql="SELECT SUM(valor) as tot,pre,num_fac,cod_su FROM comprobante_ingreso WHERE cod_su=$codSuc AND anulado!='ANULADO' GROUP BY num_fac,pre";
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
<table align="center" width="100%">
<tr>
<td colspan="3">
<?php echo $PUBLICIDAD2 ?></td>
<td valign="top" colspan="3">
<p align="left" style="font-size:12px;">
<span align="center" style="font-size:24px"><B>CREDITOS POR COBRAR-P&Uacute;BLICO</B></span>
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

?>

<table frame="box" rules="all" cellpadding="5" cellspacing="3" border="1">
<tr>

<th>C.C</th><th colspan="2">Cliente</th><th>Abonado</th><th>Saldo</th>
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
	$sql="SELECT *,DATEDIFF(CURRENT_DATE(),DATE(fecha) ) AS mora,DATEDIFF(DATE(fecha_pago),DATE(fecha) ) AS mora2 FROM fac_venta WHERE id_cli='".$id_clientes[$i]."' AND ".VALIDACION_VENTA_VALIDA." AND tipo_venta='Credito' AND estado!='PAGADO' AND tipo_cli='Mostrador' $A $B";
	$rs=$linkPDO->query($sql);
	?>
<!--
<tr>
<td colspan="4"><b><?php //echo $id_clientes[$i]." - ".$nom_clientes[$i] ?></b></td>
</tr> 
 -->   
    <?php
	while($row=$rs->fetch())
	{
		$pre=$row['prefijo'];
		$numFac=$row['num_fac_ven'];
		$sede=$row['nit'];
		$totFac=$row['tot']*1;
		$fecha=$row['fecha'];
		$mora=$row['mora'];
			$mora2=$row['mora2'];
			$estado = $row["estado"];
			if($estado=="PAGADO"){$mora=$mora2;$abonos[$pre][$numFac]=$totFac;}
			$saldo=$totFac-$abonos[$pre][$numFac];
		$abono=$abonos[$pre][$numFac];
		$tot_abono+=$abono;
		$tot_saldo+=$saldo;
		$TOT_ABONO+=$abono;
		$TOT_SALDO+=$saldo;
		?>

<!-- <tr>
 <td><?php echo "$pre $numFac" ?></td><td><?php echo $fecha ?></td><td><?php echo money3($totFac) ?></td><td><?php echo money3($abonos[$pre][$numFac]) ?></td>
 <td><?php echo money3($totFac-$abonos[$pre][$numFac]) ?></td>
 <td><?php echo $mora ?></td>
 
 </tr>
 -->
        <?php
		
	}
	?>
<tr>
<th><?php echo $id_clientes[$i] ?></th><td colspan="2"><?php echo $nom_clientes[$i] ?></td><td><?php echo money3($tot_abono) ?></td><td><?php  echo money3($tot_saldo)?></td><td></td>
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
<td colspan="3" align="center"><input type="button" value="MS EXCEL" name="boton" onclick="location.assign('<?php echo "$url?boton=MS EXCEL" ?>')" /></td>
</tr>

</table>
</div>
</body>
</html>