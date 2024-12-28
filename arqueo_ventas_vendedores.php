<?php
$sql="SELECT a.des,a.cant, a.sub_tot as stot,a.ref FROM `art_fac_ven` a 
      INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven 
	  WHERE a.prefijo=b.prefijo 
	  AND (DATE(b.fecha)>='$fechaI' AND DATE(b.fecha)<='$fechaF'  )  $filtroNOanuladas $filtroSEDE_Bnit ";
$rs=$linkPDO->query($sql);
$STOT=0;
while($row=$rs->fetch())
{
	$des=$row['des'];
	$cant=$row['cant']*1;
	$stot=round($row['stot']*1);
	$STOT+=$stot;
	
}


?>


<table align="left" width="100%" style="font-size:10px;" rules="all">
<TR>
<td>
<table cellpadding="1" cellspacing="1" rules="all" frame="box" align="left" width="100%">
<tr style="font-size:12px;  background-color:#999; font-weight:bold;">
<td colspan="9" align="left" >
VENTAS POR VENDEDOR
</td>
</tr>
<tr style="font-size:12px; font-weight:bold;">
<td>Vendedor</td>
<td align="center">Contado</td>
<td align="center">Cheques</td>
<td align="center">Tarjetas</td>
<td align="center">Transferencias</td>
<td align="center">Cr&eacute;dito</td>
<td align="center">Abonos Cr&eacute;ditos</td>
<td align="center">Gastos</td>

</tr>

<?php
$rs=$linkPDO->query("SELECT vendedor from fac_venta WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' ) $filtroNOanuladas $filtroSEDE_nit  GROUP BY vendedor" );
$totVendedores=0;
while($row=$rs->fetch())
{
$nomVende=nomTrim($row["vendedor"]);
$tcont=money3(round($total_vendedores[$nomVende][2]));
$tcontBsF=money3(round($total_vendedores[$nomVende][22]));
$tcre=money3(round($total_vendedores[$nomVende][3]));
$tventa=money3(round($total_vendedores[$nomVende][1]));
$tAbon=money3($total_vendedores[$nomVende][4]);
$tEfectivo=money3($total_vendedores[$nomVende][2]+$total_vendedores[$nomVende][4]-$total_vendedores[$nomVende][5]);
$tGastos=money3($total_vendedores[$nomVende][5]);

$totalTarjetas=money3(round($total_vendedores[$nomVende]['tarjetas']));
$totalCheques=money3(round($total_vendedores[$nomVende]['cheques']));
$totalTransferencias=money3(round($total_vendedores[$nomVende]['transferencias']));
if($total_vendedores[$nomVende]>0){
	echo "<tr>
	      <td>$nomVende</td>
		  <td><b>$tcont</b></td>
		  <td><b>$totalCheques</b></td>
		  <td><b>$totalTarjetas</b></td>
		  <td><b>$totalTransferencias</b></td>
		  <td>$tcre</td>
		  <td>$tAbon</td>
		  <td>$tGastos</td>";
}

	$totVendedores+=$total_vendedores[$nomVende][1];
	
}

?>
<!--
<tr style="font-size:20px; font-weight:bold;">
<th>Total Ventas</th><th colspan="6"><?php //echo money3($totVendedores) ?></th>
</tr>
-->
</table>
</td></TR></table>