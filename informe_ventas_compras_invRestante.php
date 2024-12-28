<?php

require_once("Conexxx.php");

require_once("arqueos_vars.php");

$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
excel("Consolidado Reportes $hoy");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
</head>

<body>
<h1>Ventas por Producto</h1>
Desde: <?PHP echo fecha($_SESSION['fechaI'])."&nbsp; $horaI" ?> 
Hasta: <?php echo fecha($_SESSION['fechaF'])."&nbsp; $horaF" ?>
<table align="center" width="100%" cellpadding="1" cellspacing="1" style="font-size:11px" rules="rows" id="TabClientes" class="tablesorter">
<thead>
<tr style="font-size:12px;  font-weight:bold; background-color:#CCC">
<th>#</th>
<th>Prefijo</th>
<th width="50">#Fac.</th>
<th width="60" align="center">Cliente</th>
<th width="100">Cod. Barras</th>
<th width="180">Descripci&oacute;n</th>
<th>Cant.</th>
<th align="center" width="50">Valor U.</th>
<th>IVA</th>
<th width="80" align="center">Sub Total</th>
<th width="80">Vendedor(a)</th>
<th width="50">Hora</th>
<th width="100">Fecha</th>
</tr>
</thead>
<tbody>
<?php
$iva_taller=0;
$tot_taller=0;
$iva_otros=0;
$tot_otros=0;

$trabajoTerceros=0;


$sql="SELECT cod_barras,nom_cli,art_fac_ven.num_fac_ven, precio,des,art_fac_ven.sub_tot,art_fac_ven.iva,cant,unidades_fraccion,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,fac_venta.prefijo,art_fac_ven.prefijo,cod_caja FROM fac_venta INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit AND art_fac_ven.nit=$codSuc $filtroCaja    AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql );

$total_taller=0;
$total_otros=0;
$total_mostrador=0;
$total_empleados=0;
$total_fanalca=0;

$total_contado=0;
$total_credito=0;
$total_cre_empleados=0;
$total_cre_fanalca=0;
$total_cre_otros=0;

$base16=0;
$iva16=0;
$excentas=0;
$total_repuestos=0;

$totFacAnticipo=0;



$i=0;
while($row=$rs->fetch())
{
	$i++;
	$num_fac=$row['num_fac_ven'];
	$subTot=money2($row['sub_tot']*1);
	$IVA_art=$row['iva']*1;
	$des=$row['des'];
	$cant=$row['cant']*1;
	$uni=$row['unidades_fraccion']*1;
	$valor=money2($row['precio']*1);
	$ref=$row['cod_barras'];
	$vendedor=ucwords(strtolower(htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET")));
	$HORA=$row['hora'];
	$fecha=$row['fe'];
	$tipo_venta=$row['tipo_venta'];
	$tipoCli=$row['tipo_cli'];
	$nomCli=$row['nom_cli'];
	
	
	$total_vendedores[$vendedor]+=$row['sub_tot']*1;
	
	if($IVA_art==0)$excentas+=$row['sub_tot']*1;
	
	if($IVA_art==16)
	{
		$base16+=($row['sub_tot']*1)/1.16;
		$iva16+=$row['sub_tot']*1-($row['sub_tot']*1)/1.16;
		
		}
	$total_repuestos+=$row['sub_tot']*1;
	
	
	///////////////////////// TIPO CLIENTE //////////////////////////
	if($tipoCli=="Taller Honda")
	{ 
	   $total_taller+=$row['sub_tot']*1;
		}
	if($tipoCli=="Mostrador")
	{
		$total_mostrador+=$row['sub_tot']*1;
		
		}
	if($tipoCli=="Otros Talleres")
	{
		$total_otros+=$row['sub_tot']*1;
		
		}
	if($tipoCli=="Empleados")
	{
		$total_empleados+=$row['sub_tot']*1;
		
		}
	if($tipoCli=="Garantia FANALCA")
	{
		$total_fanalca+=$row['sub_tot']*1;
		
		}
	//////////////////////////////////////////////////////////////
	
	/////// TIPO PAGO /////////////////////////////////////////////////////
	
	if($tipo_venta=="Contado"|| $tipo_venta=="Tarjeta Credito")
	{
		$total_contado+=$row['sub_tot']*1;
		
		}
	if($tipo_venta=="Credito")
	{
		$total_credito+=$row['sub_tot']*1;
		
		}
	if($tipo_venta=="Anticipo")
	{
		$totFacAnticipo+=$row['sub_tot']*1;
		
		}
	
	
	?>
    <tr>
    <td align="center"><?php echo $i ?></td>
    <td align="center"><?php if($row['tipo_venta']=='Credito'){echo $codCreditoSuc; }else {echo $codContadoSuc;} ?></td>
    <td align="center"><?php echo $num_fac ?></td>
    <td align="center"><?php echo $nomCli ?></td>
    <td align="center">[<?php echo $ref ?>]</td>
    <td ><?php echo $des ?></td>
    <td align="center"><?php echo "$cant;$uni" ?></td>
    <td align="center"><?php echo $valor ?></td>
    <td align="center"><?php echo $IVA_art ?></td>
    <td align="center"><?php echo $subTot ?></td>
    <td align="center"><?php echo $vendedor ?></td>
    <td align="center"><?php echo $HORA ?></td>
    <td align="center"><?php echo $fecha ?></td>
    </tr>
    
    <?php
	
	
}
$TOT_VENTAS0516=tot_ventas_0516($fechaI,$fechaF,$codSuc,$horaI,$horaF,$CodCajero);
?>
</tbody>
</table>
<BR>
<table align="" style="font-size:11px" cellspacing="0"   cellpadding="0" frame="box">
<tr style="font-size:16px; font-weight:bold; background-color:#000; color:#FFF">
<td colspan="5">
TOTAL VENTAS
</td>
</tr>
<tr>
<td colspan="4">VENTAS <?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP  echo money3(redondeo($TOT_VENTAS0516[0][0])) ?></span></td>
</tr>
<tr>
<td colspan="4">BASE IVA 5%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[5][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 5%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[5][2]) ?></span></td>
</tr>
<tr>
<td colspan="4">BASE IVA 16%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[16][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 16%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[16][2]) ?></span></td>
</tr>

<tr>
<td colspan="4">VENTAS TOTALES:</td><td> <span ><?PHP echo money3(redondeo($TOT_VENTAS0516[1][1])) ?></span></td>
</tr>
<tr style="font-size:16px; font-weight:bold; background-color:#000; color:#FFF">
<td colspan="5">
FORMAS DE PAGO
</td>
</tr>
<tr>
<td colspan="3">CONTADO:</td><td><span id="iva5"><?PHP echo money3(redondeo($total_contado )) ?></span></td>
<td align="center"><span><?PHP echo $tot_fac_contado ?></span></td>
</tr>
<tr>
<td colspan="3">TARJETAS CREDITO:</td><td><span id="iva5"><?PHP echo money3(redondeo($tot_tCredito/*+$anticipos_tCredito*/)) ?></span></td>
<td align="center"><span><?PHP echo $tot_fac_tarjeta_credito/*+$totFac_antiTcredito*/ ?></span></td>
</tr>
<tr>
<td colspan="3">TARJETAS DEBITO:</td><td><span id="iva5"><?PHP echo money3(redondeo($tot_tDebito/*+$anticipos_tDebito*/)) ?></span></td>
<td align="center"><span><?PHP echo $tot_fac_tarjeta_debito/*+$totFac_antiTdebito*/ ?></span></td>
</tr>
<tr>
<td colspan="3">CR&Eacute;DITO:</td><td><span id="iva5"><?PHP echo money3(redondeo($tot_Credito)) ?></span></td>
<td align="center"><span><?PHP echo $tot_fac_credito ?></span></td>
</tr>
<tr>
<td colspan="3">FACTURAS POR ANTICIPO:</td><td><?PHP echo money3(redondeo($tot_anticipos)) ?></td>
<td align="center"><span><?PHP echo $tot_fac_anticipo ?></span></td>
</tr>
<tr style="font-size:16px; font-weight:bold;">
<td colspan="4">
Total:
</td>
<td>
<?php echo money3($total_contado+$total_credito+$totFacAnticipo) ?>
</td>
</tr>
<tr style="font-size:16px; font-weight:bold; background-color: #CCC; color:#000">
<td colspan="4">
EFECTIVO CAJA:
</td>
<td></td>
</tr>


</table>



<br><br><br><br><br><br><br><br><br><br><br><br><br>

<h1>Compras</h1>
<table align="center" width="100%" cellpadding="1" cellspacing="1" style="font-size:11px" rules="rows" id="TabClientes" class="tablesorter">
<thead>
<tr style="font-size:12px;  font-weight:bold; background-color:#CCC">
<th>#</th>

<th width="50">#Fac.</th>
<th width="60" align="center">Proveedor</th>
<th width="100" colspan="2">Cod. Barras</th>
<th width="180">Descripci&oacute;n</th>
<th>Cant.</th>
<th align="center" width="50">Costo U.</th>
<th>IVA</th>
<th width="80" align="center">Sub Total</th>

<th width="50">Hora</th>
<th width="100">Fecha</th>
</tr>
</thead>
<tbody>
<?php
$TOT_COMPRAS=tot_compras($fechaI,$fechaF,$codSuc);


$sql="SELECT a.ref,a.cod_barras,a.des,a.costo,a.iva,TIME(fecha_crea) as hora,DATE(fecha_crea) as fechaFac,a.cant,a.unidades_fraccion,a.tot,a.nit_pro,a.num_fac_com,b.nom_pro,b.fecha,b.fecha_crea  FROM art_fac_com a INNER JOIN fac_com b ON a.num_fac_com=b.num_fac_com WHERE a.nit_pro=b.nit_pro AND a.cod_su=b.cod_su AND ( DATE(fecha_crea)>='$fechaI' AND DATE(fecha_crea)<='$fechaF' ) ORDER BY hora";

$rs=$linkPDO->query($sql);

$i=0;
while($row=$rs->fetch()){
	$i++;
	$cant=$row['cant']*1;
	$uni=$row['unidades_fraccion']*1;
	$nomPro=$row['nom_pro'];
	$numFacCom=$row['num_fac_com'];
	$codBar=$row['cod_barras'];
	$des=$row['des'];
	$costoU=$row['costo'];
	$ivaCosto=$row['iva'];
	$Stot=$row['tot'];
	$hora=$row['hora'];
	$fecha=$row['fechaFac'];
	
	
?>
<tr>
<td align="center"><?php echo $i ?></td>
<td align="center"><?php echo $numFacCom ?></td>
<td align="center"><?php echo $nomPro ?></td>
<td align="center" colspan="2">[<?php echo $codBar ?>]</td>
<td><?php echo $des ?></td>
<td align="center"><?php echo "$cant;$uni" ?></td>
<td align="center"><?php echo money3($costoU) ?></td>
<td align="center"><?php echo $ivaCosto ?></td>
<td align="center"><?php echo money3($Stot) ?></td>
<td align="center"><?php echo $hora ?></td>
<td align="center"><?php echo $fecha ?></td>
</tr>

<?php
}


?>
</tbody>
</table>
<BR>
<table align="" style="font-size:11px" cellspacing="0"   cellpadding="0" frame="box">
<tr style="font-size:16px; font-weight:bold; background-color:#000; color:#FFF">
<td colspan="4">
TOTAL COMPRAS
</td>
</tr>
<tr>
<td colspan="3">SUB TOTAL:</td><td><span id="iva5"><?PHP echo money3(redondeo($TOT_COMPRAS[0])) ?></span></td>

</tr>

<tr>
<td colspan="3">IVA:</td><td><span id="iva5"><?PHP echo money3(redondeo($TOT_COMPRAS[1])) ?></span></td>

</tr>
<tr style="font-size:16px; font-weight:bold;">
<td colspan="3">
Total:
</td>
<td>
<?php echo money3($TOT_COMPRAS[2]) ?>
</td>
</tr>

</table>
<br><br><br><br>
<?php
require_once("ReporteInv_vars.php");

?>
<br><br><br><br>
<h2><?php echo "Inventario $NOM_NEGOCIO ".$_SESSION["municipio"]." ".$hoy ?></h2>

<table cellpadding=0 cellspacing=0 border=1>
<tr style="font-size:14px;">
<tr bgcolor="#3366FF" style="color:#FFF" class="ui-btn-active">      
<th width="110" colspan="3">Ref</th>
<th width="110" colspan="2">Cod.</th>
<th width="210" colspan="2">Descripci&oacute;n</th>
<?php if($usar_talla==1){?><td>Talla</td><?php }?>
<?php if($usar_color==1){?><td>Color</td><?php }?>

<?php if($usar_fecha_vencimiento==1){?><th width="110" colspan="2">Fecha Vencimiento</th><?php }?>

<th width="30" colspan="2">Costo</th>

<th width="30" >Sistema</th>
<th width="60" >Ajuste</th>
</tr>
                      
<?php 
while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $id_inter = htmlentities($row["id_glo"]); 
            $des = htmlentities($row["detalle"], ENT_QUOTES,"$CHAR_SET"); 
			$clase = htmlentities($row["id_clase"], ENT_QUOTES,"$CHAR_SET");
			$id = htmlentities($row["id_sede"]);
			$frac = htmlentities($row["fraccion"]);
			$fab = htmlentities($row["fab"], ENT_QUOTES,"$CHAR_SET"); 
			$pvp=$row['precio_v'];
			$costo=$row['costo'];
			$unidades=$row['unidades_frac'];
			$feVenci=$row['fecha_vencimiento'];
			$talla=$row['talla'];
			$color=$row['color'];
			 
			
?>
 
<tr  bgcolor="#FFF" style="font-size:10px;">
<td width="80" align="left" height="20" colspan="3">'<?php echo $id_inter; ?>'</td>
<td width="80" align="left" height="20" colspan="2">'<?php echo $id; ?>'</td>
<td width="150" colspan="2"><?php echo $des; ?></td>

<?php if($usar_talla==1){?><td width=""><?php echo $talla; ?></td><?php }?>

<?php if($usar_color==1){?><td width=""><?php echo $color; ?></td><?php }?>

<?php if($usar_fecha_vencimiento==1){?><td width="70" colspan="2"><?php echo $feVenci; ?></td> <?php }?>

<td width="80" colspan="2"><?php echo money3($costo); ?></td>

<td width="80"  ><?php echo $row['exist'].";$unidades"; ?></td>
<!--<td width="80"><?php //echo money3($pvp); ?></td><td width="80"><?php //echo money3($costo*$row['exist']); ?></td>--> 
<td width="20"  ></td> 
</tr> 
         
<?php 
         } 
      ?>
 

 
<?php 
     	 
$sql="select sum(exist*costo) tot from inv_inter where nit_scs=$codSuc";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
$TOT=$row['tot'];	
}
      ?>
  <!--        
   <tr>
   <td width="100">Total Costo sin IVA:</td><td colspan=""><?php echo $TOT ?></td>
   </tr>
   -->
</table>
<?php

$TOT_INV=costo_now($codSuc,"con");
$costoC=$TOT_INV[0];
$costoS=$TOT_INV[1];
$inv_pvp=$TOT_INV[2];
$IVA=$costoC-$costoS;

?>
<BR>
<table>
<tr style="background-color:#999; color:#000">
<td colspan="5" >COSTO INVENTARIO</td>
</tr>
<tr>
<td colspan="4">Costo sin IVA:</td><td> <?php echo money3($costoS); ?></td>
</tr>
<tr>
<td colspan="4">IVA:</td><td><?php echo money3($IVA_costo); ?></td>
</tr>
<tr>
<td colspan="4">Costo con IVA:</td><td><?php echo money3($costoC); ?></td>

</tr>
</table>
</body>
</html>