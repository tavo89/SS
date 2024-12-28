<table frame="box" rules="all" cellpadding="5" cellspacing="3" border="1">
<tr>
<th>NIT</th><th colspan="2">Proveedor</th><th>Abonado</th><th>Saldo</th>
</tr>
<?php
$MODO_FAC="";
$MODO_COMP="";

if($MODO_INFORME=="A")
{
$MODO_FAC=$A2;
$MODO_COMP=$A2C2;
		
}
else if($MODO_INFORME=="B")
{
	$MODO_FAC=$A;
	$MODO_COMP=$A2C;
}

$trabajoTerceros=0;
$id_clientes[]="";
$nom_clientes[]="";
$abonos[]=0;
$TOT_COMPRAS=0;
$totCompras[]=0;


$rs=$linkPDO->query("SELECT * from fac_com WHERE cod_su=$codSuc AND estado='CERRADA' AND tipo_fac='Compra'   $B GROUP BY nit_pro order by 3" );
//$A
$i=0;
while($row=$rs->fetch())
{
	$id_clientes[$i]=ucwords(strtolower($row["nit_pro"]));
	$totCompras[$row["nit_pro"]]=0;
	$nom_clientes[$i]=ucwords(strtolower($row["nom_pro"]));
	$abonos[$row['nit_pro']]=0;

	$i++;
	
}

$rs=$linkPDO->query("SELECT *,(tot-(r_fte+r_ica+r_iva+dcto2)) TOT FROM fac_com WHERE cod_su=$codSuc AND estado='CERRADA' AND tipo_fac='Compra' $filtroCortePagos  $A $B  order by 3" );
$i=0;
while($row=$rs->fetch())
{
	$totFac=$row["TOT"]*1;
	$nit=$row["nit_pro"];
	$TOT_COMPRAS+=$totFac;
	
	$sqlB="SELECT SUM(tot) as dev FROM fac_dev WHERE nit_pro='$nit' $A";
		$rsB=$linkPDO->query($sqlB);
		$rowB=$rsB->fetch();
		$dev=$rowB['dev'];
	$totCompras[$nit]+=($totFac-$dev);
	$i++;
	
}



$sql="SELECT id_beneficiario,SUM(valor) as tot,serial_fac_com FROM comp_egreso WHERE cod_su=$codSuc $MODO_COMP  AND anulado!='ANULADO'   GROUP BY id_beneficiario";
//AND serial_fac_com IN (SELECT serial_fac_com from fac_com WHERE cod_su=$codSuc AND estado='CERRADA' AND tipo_fac='Compra'  $A $B)
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$abonos[$row['id_beneficiario']]=$row['tot']*1;
	
	
}


$tot_saldo=0;
$tot_abono=0;
$TOT_ABONO=0;
$TOT_SALDO=0;

for($i=0;$i<count($id_clientes);$i++)
{
	$tot_saldo=0;
	$tot_abono=0;
	$sql="SELECT nit_pro,SUM((tot-(r_fte+r_ica+r_iva+dcto2))) as TOT FROM fac_com WHERE nit_pro='$id_clientes[$i]' AND cod_su=$codSuc AND estado='CERRADA' AND tipo_fac='Compra'  $MODO_FAC  $B GROUP BY nit_pro";
	$rs=$linkPDO->query($sql);
	//echo "<li>$sql</li>";
	while($row=$rs->fetch())
	{
	
	    	
	
	
		$totFac=$totCompras[$id_clientes[$i]];
		

		
			
		$saldo=$totFac-$abonos[$id_clientes[$i]] ;
		if($saldo>-10 && $saldo<10)$saldo=0;
		else if($saldo<-1000){$saldo=0;$abonos[$id_clientes[$i]]+=$saldo;}
		$abono=$abonos[$id_clientes[$i]];
		$tot_abono+=$abono;
		$tot_saldo+=$saldo;
		$TOT_ABONO+=$abono;
		$TOT_SALDO+=$saldo;
		
		
		
		
		?>
 <tr>
 <th><?php echo $id_clientes[$i] ?></th>
 <td colspan="2"><?php echo $nom_clientes[$i] ?></td><td><?php echo money3($tot_abono) ?></td><td><?php  echo money3($tot_saldo)?></td><td></td>
 
 
 </tr>
        <?php
		
	}


		
	
}


?>
<tr>
<tH colspan="3"></th><th>ABONOS</th><th>SALDOS</th><td></td>
</tr>
<tr>
<tH colspan="3" style="font-size:18px; font-weight:bold;"><b>TOTALES</b></th><td><?php echo money3($TOT_ABONO) ?></td><td><?php  echo money3($TOT_SALDO)?></td><td></td>
</tr>
<tr>
<tH colspan="3" style="font-size:18px; font-weight:bold;"><b>TOTAL CUENTAS POR PAGAR</b></th><td colspan="3"><?php echo money3($TOT_COMPRAS) ?></td>
</tr>
<tr>
<td colspan="6" align="center"><input type="button" value="MS EXCEL" name="boton" onClick="location.assign('<?php if($MODO_PAGINA!="B"){echo "$url?boton=MS EXCEL";}else {echo "$url&boton=MS EXCEL";} ?>')" /></td>
</tr>
</table>