<?php
require_once('Conexxx.php');
$n_r=0;
$null="";
$nit=$_SESSION['cod_su'];
$ID_FAC=s("id_fac_ven");
if(isset($_SESSION['codOrigTras']) && $_SESSION['codOrigTras']!=0){$nit=$_SESSION['codOrigTras'];}
if(isset($_SESSION['n_fac_ven'])&&isset($_SESSION['prefijo']))
{

if(isset($_REQUEST['exi_ref']))$n_r=$_REQUEST['exi_ref'];


$tabla_fac="";
$tabla_art="";
$tabla_serv="";
$t=r("t");
$art=r("art");

$HEADER_FAC="";

if($t==2){
$tabla_fac="fac_remi";
$tabla_art="art_fac_remi";
$tabla_serv="serv_fac_remi";
$HEADER_FAC="<b>REMISION</b>";	
}else if($t==3){
$tabla_fac="fac_dev_venta";
$tabla_art="art_devolucion_venta";
$tabla_serv="serv_fac_remi";
$HEADER_FAC="<b>DEVOLUCION VENTA</b>";	
}
else{
$tabla_fac="fac_venta";
$tabla_art="art_fac_ven";
$tabla_serv="serv_fac_ven";
$HEADER_FAC="Factura de Venta";	
}

if($usar_remision==1)
{
	$HEADER_FAC="REMISION";
}
if($MODULES["CARROS_RUTAS"]==1 && $t==2)
{$HEADER_FAC="PLANILLA";}



$rsCount=$linkPDO->query("SELECT COUNT(*) AS nf FROM $tabla_art  WHERE num_fac_ven='$_SESSION[n_fac_ven]' AND prefijo='$_SESSION[prefijo]' AND  nit=$nit" );

$rowCount=$rsCount->fetch();
$totArts=$rowCount["nf"];


if($Variable_size_imp_carta==1){$X_fac="11cm";}

if($totArts>=16)$X_fac="27.9cm";


//echo "<li>SELECT COUNT(*) AS nf FROM $tabla_art  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND  nit=$nit</li><li>$totArts</li>";

?>

<!DOCTYPE html PUBLIC >
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>  <?php echo $_SESSION['n_fac_ven'] ?></title>
<?php 
//require_once("HEADER_UK.php"); 
require_once("IMP_HEADER.php"); 

?>
<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
$num_fac=$_SESSION['n_fac_ven'];
$pre=$_SESSION['prefijo'];
 

$rs=$linkPDO->query("SELECT * FROM $tabla_fac WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$nit");

if($row=$rs->fetch()){
	
    //$columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,cuidad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,descuento,tot,entrega,cambio,modificable,nit";
	
	//$nit = htmlentities($row["nit"], ENT_QUOTES,"$CHAR_SET");
	$TIPO_FAC=$row["tipo_fac"];
	if($TIPO_FAC=="cotizacion"){$HEADER_FAC="<b>COTIZACI&Oacute;N</b>";}
	if($TIPO_FAC=="remision"){$HEADER_FAC="<b>ORDEN DE COMPRA</b>";}
	$form_pa =$row["tipo_venta"];
	$tipo_cli = $row["tipo_cli"];
	$vendedor = $row["vendedor"];
	$mecanico = $row["mecanico"];
	$mecanico2 = $row["tec2"];
	$mecanico3 = $row["tec3"];
	$mecanico4 = $row["tec4"];
	$cajero = $row["cajero"];
	$fecha = $row["fecha"];
	$ced = $row["id_cli"];
	$cliente = $row["nom_cli"];
	$dir = $row["dir"];
	$tel = $row["tel_cli"];
	$ciudad =$row["ciudad"];
	
	$nota_fac=$row["nota"];
	
	$val_let = $row["val_letras"];
	$SUB = $row["sub_tot"];
	$IVA = $row["iva"];
	$DCTO = $row["descuento"];
	if($DCTO<0)$DCTO=0;
	$TOT = $row["tot"];
	$estado=$row['anulado'];
	$fecha_anulado=$row['fecha_anula'];
	$null="<div style=\"font-size:18px;\" align=\"center\" ><b>ANULADA:  $fecha_anulado</b></div>";
	$INTERES = $TOT-($SUB+$IVA-$DCTO);
	$entrega = $row["entrega"];
	$cambio = $row["cambio"];
	$mail=$row["mail"];
	$KM=$row["km"];
	$Resol=$row['resolucion'];
	$codigo_venta=$row['prefijo'];
	$FechaResol=$row['fecha_resol'];
	$Rango=$row['rango_resol'];
$R_FTE=$row['r_fte'];
$R_IVA=$row['r_iva'];
$R_ICA=$row['r_ica'];
$IMP_CONSUMO=$row['imp_consumo'];
$IMP_BOLSAS=$row['impuesto_bolsas'];
$TOT_PAGAR=$TOT+$IMP_CONSUMO+$IMP_BOLSAS-($R_FTE+$R_ICA+$R_IVA);
$estadoCre=$row['estado'];
if($estadoCre=="PAGADO"){$estadoCre='<span class="uk-badge uk-badge-success" style="font-size:18px;">PAGADO</span>';}
$pagare=$row['num_pagare'];
$sedeDest=$row['sede_destino'];
$sedeOrig=$row['nit'];


$placa=$row["placa"];
$vehi=vehi($placa);


if($codigo_venta==$codPapelSuc){$HEADER_FAC="<b>ORDEN DE ENTREGA</b>";}
	
?>
<form action="mod_fac_ven.php" name="form_fac" method="post" id="form-fac" >
<div class="">
<table align="center" style="top:0px; width:21.5cm; height:<?php echo "$X_fac" ?>; font-family: Verdana, Geneva, sans-serif;"  border="0" frame="box"  cellspacing="0px" cellpadding="0" class="imp_COM">
<tr valign="top">
<td colspan="2" height="60px">
<table width="100%" height=""  frame="box" class="imp_COM_Inner"  cellpadding="0" cellspacing="0">
<tr>
<td  align="center"  class="uk-hidden">
<img src="<?php echo $url_LOGO_A ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>">
</td>

<td >
<?php  echo $PUBLICIDAD ?></td>
</tr>
</table>
</td>
</tr>
<?php   
//echo "anulada".$_SESSION['anulada'];

/*if($_SESSION['anulada']=="ANULADA")

{
	echo $null;
}
*/
?>
<tr valign="top" class="imp_COM_Inner"> 
<td height="" colspan="" width="60%" cellpadding="0" cellspacing="0">

<table align="left" cellpadding="0" cellspacing="0" width="100%" class="imp_COM_Inner">
<tr>
<td colspan="3">Se&ntilde;or(es) :  <b><?php echo "$cliente" ?></b></td>
<td colspan="2">
C.C./NIT:  <?php echo "$ced" ?></td>
</tr>
 
 
<tr>
<td><?php echo mb_strtoupper("$dir","$CHAR_SET")." ".mb_strtoupper("$ciudad","$CHAR_SET") ?></td>
<td colspan="2">
Tel.:  <?php echo "$tel" ?></td><td colspan="2"> &nbsp;E-mail: <?php echo "$mail" ?></td>
</tr>
<?php

if(!empty($vehi["modelo"])){
?>
<tr>
<td>VEH&Iacute;CULO:  <?php echo $vehi["modelo"] ?></td>

<td colspan="2">COLOR:  <?php echo $vehi["color"] ?></td>
<td>PLACA:  <?php echo $vehi["placa"] ?></td>
<td>KM:  <?php echo money($KM) ?></td>
</tr>
<?php
}

?>
<!--<tr>
<?php
if(!empty($mecanico)){echo "<td>T&eacute;cnico: $mecanico</td>";}
if(!empty($mecanico2)){echo "<td>T&eacute;cnico: $mecanico2</td>";}
if(!empty($mecanico3)){echo "<td>T&eacute;cnico: $mecanico3</td>";}
if(!empty($mecanico3)){echo "<td>T&eacute;cnico: $mecanico4</td>";}
?>

</tr>-->
</table>

</td>
<td>
<table align="center" class="imp_COM_Inner" cellpadding="0" cellspacing="0">
<tr style="font-size:10px;">
<td colspan="2">
<?php if($TIPO_FAC!="cotizacion"){echo "$HEADER_FAC: $pre - $num_fac";}else{echo "$HEADER_FAC: $num_fac";} ?>

</td>
</tr>
<?php
if($t!=2){
?>
<tr style="font-size:10px;">
<td>Forma Pago: <?php echo $form_pa ?></td>

<td> <?php if($form_pa=="Credito")echo "Estado: $estadoCre" ?></td>
</tr>
<?php
}
?>
<?php
if(!empty($pagare)){
?>
<tr>
<td>PAGARE:</td>
<td><?php echo $pagare ?></td>
</tr>
<?php
}
?>
<tr>
<td>
Fecha:</td><td> <?php echo "$fecha" ?></td>
</tr>
<?php
if($t!=2 && $usar_remision==0 && $REGIMEN!="SIMPLIFICADO" && $codigo_venta!=$codPapelSuc){
?>
<tr>
<td colspan="2">
 
ResolDian No. <?php echo $Resol ?> del <?php echo $FechaResol ?> &nbsp;
<?php echo $Rango ?>
 
</td>
</tr>
<?php
}
else{
	if($tipo_cli=="Traslado"){
?>

<tr style="font-size:10px;">
<td colspan="3"><?php  if($MODULES["CARROS_RUTAS"]==0){echo "<b>Traslado</b> <br>Origen:$SEDES[$sedeOrig] <br>Destino: $SEDES[$sedeDest]";}else {echo "<b>Cargue Mercanc&iacute;a</b> <br>Origen:$SEDES[$sedeOrig] <br>Destino: $SEDES[$sedeDest]";} ?></td>
</tr>

<?php
	}

}
?>
</table>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<?php 
if($estado=="ANULADO") echo "$null";
?>
</td>
</tr>
<tr valign="top">
<td colspan="2" valign="top" height="100%">

<table id="articulos" width="100%" height="100%"  cellspacing="0" cellpadding="0" frame="box" rules="cols" style="border-width:1px" class="imp_COM_Inner">
<tr >
<?php
$colSpan=9;

if($MODULES["CARROS_RUTAS"]==1)$colSpan=9;
if($MODULES["CARROS_RUTAS"]==1 && $usar_fracciones_unidades==1)$colSpan=10;
?>
<td width="100%"  colspan="<?php echo $colSpan; ?>" align="center"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px; "><b>Productos</b></div></td></tr>


<tr>
<?php 
if($tipo_cli!="Traslado" || $vista_remi=="A"){
?>
<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Cant.</div></td>

<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Fracci&oacute;n</div></td>

<?php if($MODULES["CARROS_RUTAS"]==1 && $t==2){ ?>
<td width="13%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Cant. Dev.</div></td>
<?php } ?>

<?php if($MODULES["CARROS_RUTAS"]==1 && $usar_fracciones_unidades==1 && $t==2){ ?>
<td width="13%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Frac. Dev.</div></td>
<?php } ?>

<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">REF.</div></td>
<td width="40%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Descripci&oacute;n</div></td>
<td width="15%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Valor</div></td>

<?php if($usar_costo_remi==1){?>
<td width="15%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">PvP</div></td>
<?php }?>

<?php
if($usar_iva==1){
?>    
<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">IVA</div></td>
<?php
}
?>

      <td width="10%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Presentaci&oacute;n</div></td>
      <td width="25%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Total</div></td>
<?php 
}
else{
?>      
  

<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Cant.</div></td>

<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Fracci&oacute;n</div></td>
<?php if($MODULES["CARROS_RUTAS"]==1 && $t==2){ ?>
<td width="10%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Cant. Dev.</div></td>
<?php } ?>

<?php if($MODULES["CARROS_RUTAS"]==1 && $usar_fracciones_unidades==1 && $t==2){ ?>
<td width="10%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Frac. Dev.</div></td>
<?php } ?>
<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">REF.</div></td>
<td width="40%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Descripci&oacute;n</div></td>
<td width="10%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Presentaci&oacute;n</div></td>
   
<?php
}
?>   
    </tr>
  <?php
$s="SELECT *,fecha_vencimiento as feven FROM $tabla_art INNER JOIN (select id_pro,exist,nit_scs,precio_v,id_inter FROM inv_inter) AS inv ON inv.id_pro=$tabla_art.ref AND inv.id_inter=$tabla_art.cod_barras AND  inv.nit_scs=$tabla_art.nit AND inv.nit_scs=$nit  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND  nit=$nit ORDER BY des";
$rs=$linkPDO->query("$s" );
//echo "$s";


 //echo "SELECT * FROM $tabla_art INNER JOIN (select id_pro,exist,nit_scs from inv_inter) AS inv ON inv.id_pro=$tabla_art.ref AND  inv.nit_scs=$tabla_art.nit AND inv.nit_scs=$nit WHERE num_fac_ven='$num_fac'  ";
 $cont=0;
 $iva_show=0;
  $excentas=0;
 $iva0=1000;
 $infoAdd="";
 //$SUB=0;
 //$IVA=0;
 $SUB_SIMPLIFICADO=0;
 
 //if($ver_pvp_sin_iva!=0)$SUB=0;
  while($row=$rs->fetch()){
	  
	$feVence = $row["feven"];
	$ref = $row["cod_barras"];
	$id_pro=$row["ref"];
	$des = $row["des"];
	//if($usar_fecha_vencimiento==1)$des.=" <br> VENCE: $feVence";
	$iva = $row["iva"];
	if($iva>$iva_show)$iva_show=$iva;
	$cant = $row["cant"]*1;
	$cantDev = $row["cant_dev"]*1;
	$fracc=$row['fraccion'];
	if($fracc<=0)$fracc=1;
	$uni = $row["unidades_fraccion"]*1;
	$uniDev = $row["uni_dev"]*1;
	$factor=($uni/$fracc)+$cant;
	
	$pvp = $row["precio"]/(1+$iva/100);
	if($ver_pvp_sin_iva==0 || $MODULES["PVP_COTIZA"]==1)$pvp = $row["precio"];
	$sub_tot = $pvp*$factor;
	$descuento =$row["dcto"]*1;
	$IMEI=$row['talla'];
	$SN=$row['serial'];
	$presentacion = $row["presentacion"];
	$color=$row['color'];
	//$SUB+=$pvp*$factor;
	
	$Sql="SELECT * FROM vista_inventario WHERE id_glo='$id_pro' AND id_sede='$ref'";
	//echo "$Sql";
	//$Rs=$linkPDO->query($Sql);
	//$Row=$rs->fetch();
	
	$pvpTras=$row["precio_v"];
	$cod_garantia = $row["cod_garantia"];
	if(!empty($cod_garantia))$infoAdd.="<br>T. Garantia: $cod_garantia";
if(!empty($SN))$infoAdd.="<br>S/N: $SN";


$des.="$infoAdd";
	if($iva==0)
	{
		$excentas+=$pvp*$factor;
		$iva0=0;
	}
	$SUB_SIMPLIFICADO+=$pvp*$factor;
	
	
//if(!empty($Row['id_clase']) || !empty($Row['aplica_vehi']))$des.=" "."|$Row[id_clase]|$Row[aplica_vehi]";	
	

	  
  ?> 
  <tr style="<?php if($totArts>=16){echo "font-size:9px;";} ?>">
<?php 
if($tipo_cli!="Traslado" || $vista_remi=="A"){
?>
 	  
      <td align="center"><?php echo $cant ?></td>
      <td align="center"><?php echo $uni ?></td>
      <?php if($MODULES["CARROS_RUTAS"]==1 && $t==2){ ?>
      <td align="center"><?php echo $cantDev ?></td>
      <?php } ?>
      
      <?php if($MODULES["CARROS_RUTAS"]==1 && $usar_fracciones_unidades==1 && $t==2){ ?>
      
      <td align="center"><?php echo $uniDev ?></td>
      <?php } ?>
      <td><?php echo $ref ?></td>
      <td  > <?php echo $des ?> </td>
      <td   align="right"><?php echo money($pvp) ?></td>
      <?php if($usar_costo_remi==1){?>
	  <td align="right"><?php echo money($pvpTras) ?></td>
	  <?php }?>
      <?php
if($usar_iva==1){
?>

      <td  align="right"><?php echo $iva?></td>
      
      <?php
}
?>

      <td  align="left"><?php echo $presentacion?></td>
      <td align="right"><?php echo money($sub_tot) ?></td>
      
<?php 
}
else{
?>
<td align="center"><?php echo $cant ?></td>
      <td align="center"><?php echo $uni ?></td>
      <td><?php echo $ref ?></td>
      <td  ><pre><?php echo $des ?></pre></td>
      

      <td  align="center"><?php echo $presentacion?></td>

<?php
}
?>

</tr>
  
<?php
$cont++;
$infoAdd="";
  }//fin while
  
  
if($t!=3){
$sql="SELECT * FROM $tabla_serv WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND cod_su=$nit";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
?> 


<tr ><td width="100%"  colspan="9" align="center"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px; border-top-style:solid; border-top-width:1px;  "><b>Servicios</b></div></td></tr>

    
    
<?php
$sql="SELECT * FROM $tabla_serv WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND cod_su=$nit";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	
	$serv=$row["serv"];
	$nota=": ".$row["nota"];
	$idServ=$row["id_serv"];
	$codServ=$row["cod_serv"];
	$ivaServ=$row["iva"];
	 
	$pvpServ = $row["pvp"]/(1+$ivaServ/100);
	if($ver_pvp_sin_iva==0 || $MODULES["PVP_COTIZA"]==1)$pvpServ = $row["pvp"];
	$nomTec=getTec($row["id_tec"]);
	
	if($ivaServ==0)
	{
		$excentas+=$pvpServ;
	
	}
	
	
 ?> 
  <tr>
  <td colspan=""><?php echo "1" ?></td>
  <td colspan=""><?php echo "" ?></td>
  <td colspan=""><?php echo "$codServ" ?></td>
  <td colspan=""><?php echo "$serv $nota" ?></td>
  
  <td align="right"><?php echo money($pvpServ) ?></td>
   <?php if($usar_iva==1){?><td align="center"><?php echo "$ivaServ"; ?></td><?php }?>  
     <!-- <td align="center"><?php echo " "; ?></td>-->
     <td align="center" style="font-size:9px;"><?php echo "$nomTec"; ?></td>
      <td align="right"><?php echo money($pvpServ) ?></td>
      
     
      
    </tr>
  
<?php
	
}


}///////////// FIN t==3
}
?>
<tr>
<?php if($tipo_cli!="Traslado" || $vista_remi=="A"){?>

<td ></td><?php if($t==2 && $MODULES["CARROS_RUTAS"]==1)echo "<td></td><td ></td>"; ?><td  height="100%">&nbsp; </td><td  height="100%">&nbsp; </td><td  height="100%">&nbsp; </td>
<?php if($usar_iva==1){?><td  height="100%">&nbsp; </td><?php }?>
<td  height="100%">&nbsp; </td><td  height="100%" colspan="">&nbsp; </td><td  height="100%" colspan="">&nbsp; </td>
<?php }
else{
?>
<td  ></td><?php if($t==2 && $MODULES["CARROS_RUTAS"]==1)echo "<td></td><td ></td>"; ?><td  height="100%">&nbsp; </td><td  height="100%">&nbsp; </td><td  height="100%">&nbsp; </td>
<td  height="100%" colspan="">&nbsp;&nbsp;&nbsp;&nbsp; </td>

<?php }?>
</tr>


</table> 


 </td>
 </tr>
 
<?php 
if($tipo_cli!="Traslado" || $vista_remi=="A"){
?> 
 
<tr valign="top">

<td>
<b>SON:</b><span id="valor_letras"></span><?php echo $val_let; ?>
</td>
<td colspan="">

<table align="right"  cellpadding="6" cellspacing="0" class="imp_COM_Inner">
<tbody>
<?php
if($REGIMEN!="SIMPLIFICADO" && $usar_iva==1){
?>
<tr>
<td>Valor</td><td><?php echo money($SUB-$excentas) ?></td>

<td>Exentos</td><td><?php echo money3($excentas) ?></td>
</tr>
 <?php }
else{ 
?>
<!--
<tr>
<td>Valor </td><td><?php echo money3($SUB_SIMPLIFICADO) ?></td>
</tr>-->
 
<?php 
}
if($DCTO!=0){
	
	$perDcto=redondeo(($DCTO/($DCTO+$SUB)) *100);
	?>
<tr>
<td>DCTO:</td><td><?php echo money($DCTO)." &nbsp; ($perDcto%)" ?></td>
</tr>
	<?php }?>
<?php
if($usar_iva==1){
?>
<tr>
<td>I.V.A</td><td><?php echo money($IVA) ?></td>
</tr>
<?php
}
?>
<!--
<tr>
<td>Total</td><td><?php echo money($TOT) ?></td>
</tr>
-->
<?php
if($R_FTE!=0) echo "<tr><td><b>R-FTE:</b></td><td>-".money3($R_FTE)."</td></tr>";

if($R_IVA!=0) echo "<tr><td><b>R-IVA:</b></td><td>-".money3($R_IVA)."</td></tr>";

if($R_ICA!=0) echo "<tr><td><b>R-ICA:</b></td><td>-".money3($R_ICA)."</td></tr>";

if($impuesto_consumo!=0) echo "<tr><td><b>Imp. Consumo:</b></td><td>".money3($IMP_CONSUMO)."</td></tr>";
if($IMP_BOLSAS!=0) echo "<tr><td><b>Imp. Bolsas:</b></td><td>".money3($IMP_BOLSAS)."</td></tr>";
?>
<tr style="font-size:18px;">

<td colspan="" align="right" ><b>TOT A PAGAR:</b></td><td><?php echo money3($TOT_PAGAR) ?></td>
</tr>

</tbody>
</table>
</td>
</tr>
<?php 
}
?>


<tr valign="top">
<td colspan="2">

<table width="100%" frame="hsides" class="imp_COM_Inner">
<tr>
<?php
if($t!=2 && $usar_remision==0&& $REGIMEN!="SIMPLIFICADO" && $codigo_venta!=$codPapelSuc){
?>
<td>
<?php  echo "$nota_fac <br>";  ?>


Esta factura se asemeja a una letra de cambio seg&uacute;n el art&iacute;culo 779 del c&oacute;digo de comercio. El incumplimiento en el pago causar&aacute; inter&eacute;s por mora a la m&aacute;xima  tasa establecida por la ley por mes o fracci&oacute;n</td>
<?php
}
else{ echo "<td>$nota_fac</td>";}
?>
</tr>
</table>

</td>
</tr>

<tr valign="top">
<td colspan="2">
<table width="100%"  rules="cols" cellpadding="4" class="imp_COM_Inner">
<?php
if($t!=2 || $tipo_cli!="Traslado"){
?>
<tr>
<td>Elabor&oacute;:
<br />
<p align="center">________________________</p>
<?php echo "$vendedor"?>
</td>
<td>
Firma autorizada:
<br /><br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td >
Aceptada:
<br />
<p align="center">________________________</p>
<?php echo "$cliente (Cliente)"?>
</td>
</tr>
<?php
}
else{
?>
<tr>
<td>TRANSPORTA

<br />
Firma Transportista:
<p align="center">___________________</p>
<?php echo "C.C:  &nbsp;___________"?>
</td>
<td>ENVIA
<br>
Firma almacen:
<br />
<p align="center">___________________</p>
<?php echo "&nbsp;"?>
</td>
<td>ELABORA
<br>
Firma:
<br />
<p align="center">___________________</p>
<?php echo "$vendedor"?>
</td>
<td >RECIBE
<br />
Firma Cliente:
<p align="center">______________________</p>
<?php echo "$cliente"?>
</td>
</tr>
<?php
}
?>
</table>
</td>
</tr>
 </table>
 
 </div>
 <div id="imp"  align="center">
  
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" class="addbtn"/>
    
 
</div>
     
</form>

<?php
}

?>
<?php 
 
require_once("FOOTER_UK.php"); 
?>
<script language="javascript1.5" type="text/javascript">
$(document).ready(
	function(){
		$('#valor_letras').html(''+covertirNumLetras(""+<?php echo $TOT ?>+"")+'');
	imprimir();
	
	}
	
);
</script>
</body>
</html>
<?php
}
else{

?>
<!DOCTYPE html>
<html>

<body>
<h1>No hay factura seleccionada</h1>
</body>
</html>
<?php
}

?>