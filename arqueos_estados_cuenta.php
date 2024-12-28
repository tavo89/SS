<?php
require_once("Conexxx.php");

$trabajoTerceros=0;
$id_clientes[]="";
$nom_clientes[]="";
$abonos[][]=0;

$rs=$linkPDO->query("SELECT * from fac_venta WHERE nit=$codSuc AND tipo_venta='Credito' AND ".VALIDACION_VENTA_VALIDA." AND id_cli!='$NIT_FANALCA' GROUP BY id_cli order by 3");
$i=0;
while($row=$rs->fetch())
{
	$id_clientes[$i]=ucwords(strtolower( $row["id_cli"] ));
	$nom_clientes[$i]=ucwords(strtolower( $row["nom_cli"] ));
	$abonos[$row['prefijo']][$row['num_fac_ven']]=0;
	$i++;
	
}

$sql="SELECT * from fac_venta WHERE nit=$codSuc AND tipo_venta='Credito' AND ".VALIDACION_VENTA_VALIDA." AND id_cli!='$NIT_FANALCA' GROUP BY num_fac_ven,prefijo";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$abonos[$row['prefijo']][$row['num_fac_ven']]=0;
	
	
}


?>
<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>Estados de Cuenta</title>
<script language="javascript1.5" type="text/javascript" src="JS/jQuery1.8.2.min.js">
</script>
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</head>

<body>
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>
<td>
<?php echo $PUBLICIDAD2 ?></td>
<td valign="top">
<p align="left" style="font-size:12px;">
<span align="center" style="font-size:24px"><B>ESTADOS DE CUENTA</B></span>
</p>
</td>

</tr>
</table>
Fecha Informe: <?PHP echo $hoy ?>
<br>
<table frame="box" rules="all" cellpadding="5" cellspacing="3">
<tr>
<th>No. Factura</th><th>Fecha</th><th>Valor Fac.</th><th>Abonado</th><th>Saldo</th><th>D&iacute;as Mora</th>
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
	$sql="SELECT *,DATEDIFF(CURRENT_DATE(),DATE(fecha) ) AS mora,DATEDIFF(DATE(fecha_pago),DATE(fecha) ) AS mora2 FROM fac_venta WHERE id_cli='".$id_clientes[$i]."' AND ".VALIDACION_VENTA_VALIDA." AND tipo_venta='Credito' AND id_cli!='$NIT_FANALCA'";
	$rs=$linkPDO->query($sql);
	?>
<tr>
<td colspan="6"><b><?php echo $id_clientes[$i]." - ".$nom_clientes[$i] ?></b></td>
</tr> 
    
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
			$estado =  $row["estado"] ;
			if($estado=="PAGADO"){$mora=$mora2;$abonos[$pre][$numFac]=$totFac;}
			$saldo=$totFac-$abonos[$pre][$numFac];
		$abono=$abonos[$pre][$numFac];
		$tot_abono+=$abono;
		$tot_saldo+=$saldo;
		$TOT_ABONO+=$abono;
		$TOT_SALDO+=$saldo;
		?>
 <tr>
 <td><?php echo "$pre $numFac" ?></td><td><?php echo $fecha ?></td><td><?php echo money($totFac) ?></td><td><?php echo money($abonos[$pre][$numFac]) ?></td>
 <td><?php echo money($totFac-$abonos[$pre][$numFac]) ?></td>
 <td><?php echo $mora ?></td>
 
 </tr>
        <?php
		
	}
	?>
<tr>
<tH colspan="3"><b>TOTALES</b></th><td><?php echo money($tot_abono) ?></td><td><?php  echo money($tot_saldo)?></td><td></td>
</tr> 
    
    <?php

		
	
}


?>
<tr>
<tH colspan="3" style="font-size:18px; font-weight:bold;"><b>TOTALES</b></th><td><?php echo money($TOT_ABONO) ?></td><td><?php  echo money($TOT_SALDO)?></td><td></td>
</tr>
</table>
</div>
</body>
</html>