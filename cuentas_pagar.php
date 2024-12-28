<?php
require_once("Conexxx.php");
$url=thisURL();
$boton=r("boton");
if($boton=="MS EXCEL"){excel("Cartera Clientes");}
$resumen=r("resumen");
$MOD=r("mod");

$filtroCortePagos="";
if(!empty($fecha_corte_cuentas_pagar)){$filtroCortePagos=" AND DATE(fecha)>'$fecha_corte_cuentas_pagar'";}

//////////////////////////////////////////////////////////// FILTRO FECHA ///////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_com";
$PAG_fechaF="fechaF_com";
$A="";
$B="";

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=limpiarcampo($_SESSION[$PAG_fechaI]);}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=limpiarcampo($_SESSION[$PAG_fechaF]);}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if($MOD=="C"){$A="";$resumen=1;}
/////////////////////////////////// FILTRO NOMBRE //////////////////////////////////////
$campo1="nombre_proveedor";
$nom="";
$nom_col="nom_pro";
if(isset($_SESSION[$campo1]) && !empty($_SESSION[$campo1])){$nom=$_SESSION[$campo1];$B=" AND ($nom_col='$nom' OR nit_pro='$nom')";}


/////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////// FILTRO ESTADO FAC. ///////////////////////////////////////////////////////////
$filtroB="";
$filtroPago="";


if(isset($_SESSION['filtroB_compras']))
{
	$filtroB=$_SESSION['filtroB_compras'];
	if($filtroB=="Pendientes")$filtroPago="AND pago='PENDIENTE'";
	else if($filtroB=="Pagados")$filtroPago="AND pago='PAGADO'";
	else if($filtroB=="Morosos"){$filtroPago=" AND DATEDIFF(CURRENT_DATE(),DATE(fecha) )>$DIAS_BAN_CLI AND fecha_pago='0000-00-00 00:00:00' ";}
	else $filtroPago="";
	
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



$trabajoTerceros=0;
$id_clientes[]="";
$nom_clientes[]="";
$abonos[]=0;

$rs=$linkPDO->query("SELECT * from fac_com WHERE cod_su=$codSuc AND estado='CERRADA' AND tipo_fac='Compra' $filtroCortePagos $filtroPago  $A $B  order by 3" );
$i=0;
while($row=$rs->fetch())
{

	$abonos[$row['serial_fac_com']]=0;
	$i++;
	
}

$rs=$linkPDO->query("SELECT * from fac_com WHERE cod_su=$codSuc AND estado='CERRADA' AND tipo_fac='Compra' $filtroCortePagos $filtroPago  $A $B GROUP BY nit_pro order by 3");
$i=0;
while($row=$rs->fetch())
{
	$id_clientes[$i]=ucwords(strtolower($row["nit_pro"]));
	$nom_clientes[$i]=ucwords(strtolower(htmlentities($row["nom_pro"], ENT_QUOTES,"$CHAR_SET")));

	$i++;
	
}

$sql="SELECT SUM(valor-(r_fte+r_iva+r_ica)) as tot,serial_fac_com FROM comp_egreso WHERE cod_su=$codSuc $A AND anulado!='ANULADO' AND serial_fac_com IN (SELECT serial_fac_com from fac_com WHERE cod_su=$codSuc AND estado='CERRADA' AND tipo_fac='Compra' $filtroCortePagos $filtroPago  $A $B) GROUP BY serial_fac_com";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$abonos[$row['serial_fac_com']]=$row['tot']*1;
	
	
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
<td colspan="3">
<?php echo $PUBLICIDAD2 ?></td>
<td valign="top" colspan="3">
<p align="left" style="font-size:12px;">
<span align="center" style="font-size:24px"><B>ESTADOS DE CUENTA-PROVEEDORES</B></span>
</p>
</td>

</tr>
</table>
Fecha Informe: <?PHP echo $hoy ?>
<br>
<b>FILTROS</b>
<?php
if(!empty($A))echo "<li>FECHA:  Desde $fechaI Hasta $fechaF </li>";
if(!empty($B))echo "<li>PROVEEDOR:  $nom </li>";

?>

<table frame="box" rules="all" cellpadding="5" cellspacing="3" border="1">
<?php
if($resumen!=1){
?>
<tr>
<th>No. Factura</th><th>Fecha</th><th>Fecha PAGO</th><th>Valor Fac.</th><th>Abonado</th><th>Saldo</th>
<th>D&iacute;as Restantes</th>
</tr>
<?php
}
else {
?>

<tr>
<th>NIT</th><th colspan="2">Proveedor</th><th>Abonado</th><th>Saldo</th>
</tr>

<?php
}
$tot_saldo=0;
$tot_abono=0;
$TOT_ABONO=0;
$TOT_SALDO=0;
for($i=0;$i<count($id_clientes);$i++)
{
	$tot_saldo=0;
	$tot_abono=0;
	$sql="SELECT *,DATEDIFF(DATE(feVen),DATE(NOW()) ) AS mora,DATEDIFF(DATE(fecha_pago),DATE(fecha) ) AS mora2,(tot-(r_fte+r_ica+r_iva+dcto2)) as TOT FROM fac_com WHERE nit_pro='$id_clientes[$i]' AND cod_su=$codSuc AND estado='CERRADA' AND tipo_fac='Compra' $filtroCortePagos $A $B $filtroPago";
	$rs=$linkPDO->query($sql);
	if($resumen!=1){
	?>
<tr>
<td colspan="7"><b><?php echo $id_clientes[$i]." - ".$nom_clientes[$i] ?></b></td>
</tr> 
    
    <?php
	}
	
	
	while($row=$rs->fetch())
	{
		$serialFacCom=$row['serial_fac_com'];
		$numFac=$row['num_fac_com'];
		$FECHA_PAGO=$row["fecha_pago"];
		$totFac=$row['TOT']*1;
		$fecha=$row['fecha'];
		$mora=$row['mora'];
		$estado=$row["pago"];
		 
		if($FECHA_PAGO!="0000-00-00")$mora=$row['mora2'];
			$mora2=$row['mora2'];
			$estado = $row["pago"];
			//if($estado=="PAGADO"){$mora=$mora2;$abonos[$serialFacCom]=$totFac;}
			
		$sqlB="SELECT SUM(tot) as dev FROM fac_dev WHERE nit_pro='$id_clientes[$i]' AND num_fac_com='$numFac' AND cod_su='$codSuc' $filtroCortePagos  $A AND cod_su='$codSuc'";
		$rsB=$linkPDO->query($sqlB);
		$rowB=$rsB->fetch();
		$dev=$rowB['dev'];
			
		$saldo=$totFac-$abonos[$serialFacCom]-$dev;
		
		
		
		fix_cuentas_pagar($serialFacCom,$codSuc,$saldo,$estado,$FECHA_PAGO);
		
		if($saldo>-10 && $saldo<10)$saldo=0;
		else if($saldo<-1000){$saldo=0;$abonos[$serialFacCom]+=$saldo;}
		$abono=$abonos[$serialFacCom];
		$tot_abono+=$abono;
		$tot_saldo+=$saldo;
		$TOT_ABONO+=$abono;
		$TOT_SALDO+=$saldo;
		if($resumen!=1){
		?>
 <tr>
 <td><?php echo "(#$serialFacCom) $numFac" ?></td><td><?php echo $fecha ?></td>
 
 <td><?php if($FECHA_PAGO!="0000-00-00")echo "<b>$FECHA_PAGO</b>" ?></td>
 <td><?php echo money3($totFac-$dev) ?></td><td><?php echo money3($abonos[$serialFacCom]) ?></td>
 <td><?php echo money3($saldo) ?></td>
 <td><?php echo $mora ?></td>
 
 </tr>
        <?php
		if($abonos[$serialFacCom]>0 && !empty($B)){echo "<tr><td colspan=\"6\"><b>Lista Abonos Factura $numFac:</b></td></tr>";

$sqlAbonos="SELECT num_com,cod_su,fecha,valor FROM comp_egreso WHERE serial_fac_com='$serialFacCom' AND cod_su='$codSuc' AND anulado!='ANULADO'";
$rsAbonos=$linkPDO->query($sqlAbonos);
?>

<tr>
<td colspan="6">
<table cellspacing="5">


<?php
while($rowAbonos=$rsAbonos->fetch())
{
	
$numCompIngreso=$rowAbonos['num_com'];
$fechaComp=$rowAbonos['fecha'];
$valorComp=$rowAbonos['valor'];
 
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
		
		
		
		
		
		}
		
	}///////////// FIN WHILE
	
	if($resumen!=1){
	?>
<tr>
<tH colspan="4"><b>TOTALES</b></th><td><?php echo money3($tot_abono) ?></td><td><?php  echo money3($tot_saldo)?></td><td></td>
</tr> 
    
    <?php
	}
else {
	
?>
 <tr>
 <th><?php echo $id_clientes[$i] ?></th>
 <td colspan="2"><?php echo $nom_clientes[$i] ?></td><td><?php echo money3($tot_abono) ?></td><td><?php  echo money3($tot_saldo)?></td>
 
 
 </tr>
<?php	
	
	
}
		
	
}///////////// FIN FOR


?>
<tr>
<tH colspan="<?php if($resumen!=1){ echo "4";}else {echo "3";}?>"></th><th>ABONOS</th><th>SALDOS</th><td></td>
</tr>
<tr>
<tH colspan="<?php if($resumen!=1){ echo "4";}else {echo "3";}?>" style="font-size:18px; font-weight:bold;"><b>TOTALES</b></th><td><?php echo money3($TOT_ABONO) ?></td><td><?php  echo money3($TOT_SALDO)?></td><td></td>
</tr>
<tr>
<tH colspan="<?php if($resumen!=1){ echo "4";}else {echo "3";}?>" style="font-size:18px; font-weight:bold;"><b>TOTAL CUENTAS POR PAGAR</b></th><td colspan="3"><?php echo money3($TOT_ABONO+$TOT_SALDO) ?></td>
</tr>
<tr>
<td colspan="7" align="center"><input type="button" value="MS EXCEL" name="boton" onClick="location.assign('<?php if($resumen!=1 && empty($MOD)){echo "$url?boton=MS EXCEL";}else {echo "$url&boton=MS EXCEL";} ?>')" /></td>
</tr>
</table>
</div>
</body>
</html>