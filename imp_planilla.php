<?php
require_once('Conexxx.php');
$n_r=0;
$null="";
$nit=$_SESSION['cod_su'];
if(isset($_SESSION['codOrigTras']) && $_SESSION['codOrigTras']!=0){$nit=$_SESSION['codOrigTras'];}
if(isset($_SESSION['n_fac_ven'])&&isset($_SESSION['prefijo']))
{

if(isset($_REQUEST['exi_ref']))$n_r=$_REQUEST['exi_ref'];


$tabla_fac="";
$tabla_art="";
$t=r("t");
$art=r("art");

$HEADER_FAC="";

if($t==2){
$tabla_fac="fac_remi";
$tabla_art="art_fac_remi";
$HEADER_FAC="<b>REMISION</b>";	
}
else{
$tabla_fac="fac_venta";
$tabla_art="art_fac_ven";
$HEADER_FAC="Factura de Venta";	
}

if($usar_remision==1)
{
	$HEADER_FAC="REMISION";
}
if($MODULES["CARROS_RUTAS"]==1 && $t==2)
{$HEADER_FAC="PLANILLA";}

?>

<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>Planilla <?php echo $_SESSION['n_fac_ven'] ?></title>
<?php require_once("IMP_HEADER.php"); ?>
</head>

<body>

<?php
$num_fac=$_SESSION['n_fac_ven'];
$pre=$_SESSION['prefijo'];
 

$rs=$linkPDO->query("SELECT * FROM $tabla_fac WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$nit");

if($row=$rs->fetch()){
	
    //$columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,cuidad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,descuento,tot,entrega,cambio,modificable,nit";
	
	//$nit = htmlentities($row["nit"], ENT_QUOTES,"$CHAR_SET");
	$form_pa =$row["tipo_venta"];
	$tipo_cli = $row["tipo_cli"];
	$vendedor = $row["vendedor"];
	$mecanico = $row["mecanico"];
	$cajero = $row["cajero"];
	$fecha = $row["fecha"];
	$ced = $row["id_cli"];
	$cliente = $row["nom_cli"];
	$dir = $row["dir"];
	$tel = $row["tel_cli"];
	$ciudad =$row["ciudad"];
	
	$val_let = $row["val_letras"];
	$SUB = $row["sub_tot"];
	$IVA = $row["iva"];
	$DCTO = $row["descuento"];
	if($DCTO<0)$DCTO=0;
	$TOT = $row["tot"];
	$estado=$row['anulado'];
	$fecha_anulado=$row['fecha_anula'];
	$null="<div style=\"font-size:24px;\" align=\"center\" ><b>ANULADA:  $fecha_anulado</b></div>";
	$INTERES = $TOT-($SUB+$IVA-$DCTO);
	$entrega = $row["entrega"];
	$cambio = $row["cambio"];
		$mail=$row["mail"];
	
	$Resol=$row['resolucion'];
	$codigo_venta=$row['prefijo'];
	$FechaResol=$row['fecha_resol'];
	$Rango=$row['rango_resol'];
$R_FTE=$row['r_fte'];
$R_IVA=$row['r_iva'];
$R_ICA=$row['r_ica'];
$TOT_CRE=$row["tot_cre"];
$TOT_PAGAR=$TOT-($R_FTE+$R_ICA+$R_IVA);
$estadoCre=$row['estado'];
$pagare=$row['num_pagare'];
$sedeDest=$row['sede_destino'];
$sedeOrig=$row['nit'];
	
?>
<form action="mod_fac_ven.php" name="form_fac" method="post" id="form-fac" >
<div class="">
<table align="center" style="top:0px; width:27.9cm; height:10.5cm; font-family: Verdana, Geneva, sans-serif;"  border="0" frame="box"  cellspacing="0px" cellpadding="0" class="imp_planilla">
<tr valign="top">
<td colspan="2" height="60px">
<table width="100%" height="70px"  frame="box" class="imp_planilla_inner" >
<tr>
<td  align="center" >
<img src="<?php echo $url_LOGO_A ?>" width="<?php echo $x ?>" height="<?php echo $y ?>">
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
<tr valign="top" class="imp_planilla_inner"> 
<td height="20px" colspan="" width="30%">

<table align="left" cellpadding="0" cellspacing="0" >
<tr>
<td>Se&ntilde;or(es) : </td>
</tr>
<tr>
<td><b><?php echo "$cliente" ?></b></td>
<td>C.C./NIT: </td><td> <?php echo "$ced" ?></td>

<td>
Tel.:  <?php echo "$tel" ?></td><td>E-mail: <?php echo "$mail" ?></td>

<td><?php echo mb_strtoupper("$dir","$CHAR_SET") ?></td>

<td><?php echo mb_strtoupper("$ciudad","$CHAR_SET") ?></td>
</tr>
</table>

</td>
<td>
<table align="center" >
<tr style="font-size:10px;">
<td colspan="2">
<?php echo "$HEADER_FAC: $pre - $num_fac" ?>

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
if($t!=2 && $usar_remision==0){
?>
<tr>
<td colspan="2">
<b>
ResolDian No. <?php echo $Resol ?> del <?php echo $FechaResol ?> &nbsp;
<?php echo $Rango ?>
</b>
</td>
</tr>
<?php
}
else{
	if($tipo_cli=="Traslado"){
		$ori=sede($sedeOrig);
		$dest=sede($sedeDest);
?>
<!--
<tr style="font-size:10px;">
<td colspan="3"><?php  if($MODULES["CARROS_RUTAS"]==0){echo "<b>Traslado</b> <br>Origen:$SEDES[$sedeOrig] <br>Destino: $SEDES[$sedeDest]";}else {echo "<b>Cargue Mercanc&iacute;a</b> <br>Origen:$ori <br>Destino: $dest";} ?></td>
</tr>
-->
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

<?php
$colSpan=8;

if($MODULES["CARROS_RUTAS"]==1)$colSpan=9;
if($MODULES["CARROS_RUTAS"]==1 && $usar_fracciones_unidades==1)$colSpan=10;
?>
<!--
<tr >
<td width="100%"  colspan="<?php echo $colSpan; ?>" align="center"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px; height:20px"><b>Productos y Servicios</b></div></td></tr>

-->
<tr>
<?php 
if($tipo_cli!="Traslado" || $vista_remi=="A"){
?>
<td width="40%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Descripci&oacute;n</div></td>
<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">REF.</div></td>
<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Envase</div></td>
<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Carga</div></td>
<!--
<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Fracci&oacute;n</div></td>
-->
<?php if($MODULES["CARROS_RUTAS"]==1 && $t==2){ ?>
<td width="8%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Devuelve</div></td>

<?php } ?>

<?php if($MODULES["CARROS_RUTAS"]==1 && $usar_fracciones_unidades==1 && $t==2){ ?>
<td width="13%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Frac. Dev.</div></td>
<?php } ?>


      <td width="8%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Factura</div></td>
      <td width="15%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Valor U.</div></td>
      
<?php if($usar_iva==1){?>    <td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">IVA</div></td><?php }?>

      
      <td width="25%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Total Fac.</div></td>
<?php 
}
else{
?>      
  
<td width="40%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Descripci&oacute;n</div></td>
<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">REF.</div></td>
<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Carga</div></td>
<!--
<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Fracci&oacute;n</div></td>
--->
<?php if($MODULES["CARROS_RUTAS"]==1 && $t==2){ ?>
<td width="8%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Devuelve</div></td>
<?php } ?>

<?php if($MODULES["CARROS_RUTAS"]==1 && $usar_fracciones_unidades==1 && $t==2){ ?>
<td width="10%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Frac. Dev.</div></td>
<?php } ?>

      

<td width="10%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Presentaci&oacute;n</div></td>
   
  
  
<?php
}
?>   
    </tr>
  <?php
//$rs=$linkPDO->query("SELECT *,fecha_vencimiento as feven FROM $tabla_art  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND  nit=$nit" );
$rs=$linkPDO->query("SELECT *,$tabla_art.fecha_vencimiento as feven FROM $tabla_art   INNER JOIN (select envase,fecha_vencimiento,id_pro,exist,nit_scs from inv_inter) AS inv ON inv.id_pro=$tabla_art.ref  WHERE num_fac_ven='$num_fac' AND inv.nit_scs=$tabla_art.nit AND inv.nit_scs=$nit AND $tabla_art.fecha_vencimiento=inv.fecha_vencimiento ORDER BY $tabla_art.orden_in" );
 //echo "SELECT * FROM $tabla_art INNER JOIN (select id_pro,exist,nit_scs from inv_inter) AS inv ON inv.id_pro=$tabla_art.ref  WHERE num_fac_ven='$num_fac' AND inv.nit_scs=$tabla_art.nit AND inv.nit_scs=$nit";
 $cont=0;
 $iva_show=0;
  $excentas=0;
 $iva0=1000;
 $infoAdd="";
 $SUB=0;
 $IVA=0;
 $TOT_FAC=0;
 $SUB_FAC=0;
 $VT_FAC=0;
 $TOT_ENVASE=0;
 $TOT_CANT=0;
 $TOT_DEV=0;
 //if($ver_pvp_sin_iva!=0)$SUB=0;
  while($row=$rs->fetch()){
	  
	$feVence = $row["feven"];
	$ref = $row["cod_barras"];
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
	$pvp = $row["precio"];
	$sub_tot = $pvp*$factor;
	$descuento =$row["dcto"]*1;
	$IMEI=$row['talla'];
	$SN=$row['serial'];
	$presentacion = $row["presentacion"];
	$color=$row['color'];
	$SUB+=$pvp*$factor;
	
	$envase=$row["envase"]*$cant;
	$TOT_ENVASE+=$envase;
	$TOT_CANT+=$cant;
	$TOT_DEV+=$cantDev;
	$cantFac=$cant-$cantDev;
	$VT_FAC=$cantFac*$pvp;
	$TOT_FAC+=$VT_FAC;
	
	
if(!empty($SN))$infoAdd.="<br>S/N: $SN";

$des.="$infoAdd";
	if($iva==0)
	{
		$excentas+=$pvp*$factor;
		$iva0=0;
	}
	
	
	
	

	  
  ?> 
  <tr>
<?php 
if($tipo_cli!="Traslado" || $vista_remi=="A"){
?>
 	   <td  ><?php echo $des ?></td>
       <td><?php echo $ref ?></td>
        <td align="center"><?php echo $envase ?></td>
      <td align="center"><?php echo $cant ?></td>
      <!--
      <td align="center"><?php echo $uni ?></td>
      -->
      <?php if($MODULES["CARROS_RUTAS"]==1 && $t==2){ ?>
      <td align="center"><?php echo $cantDev ?></td>
      <?php } ?>
      
      <?php if($MODULES["CARROS_RUTAS"]==1 && $usar_fracciones_unidades==1 && $t==2){ ?>
      
      <td align="center"><?php echo $uniDev ?></td>
      <?php } ?>
      
     <td  ><?php echo $cantFac ?></td>
      <td  ><?php echo money($pvp) ?></td>
      <?php
if($usar_iva==1){
?>

      <td  align="center"><?php echo $iva?></td>
      
      <?php
}
?>

      <td  align="center"><?php echo money($VT_FAC)?></td>
     
      
<?php 
}
else{
?>
<td  ><?php echo $des ?></td>
<td><?php echo $ref ?></td>
<td align="center"><?php echo $cant ?></td>
      <td align="center"><?php echo $uni ?></td>
      
      
      

      <td  align="center"><?php echo $presentacion?></td>

<?php
}
?>

</tr>
  
<?php
$cont++;
$infoAdd="";
  }//fin while
$sql="SELECT * FROM serv_fac_ven WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$nit";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
?> 


<!--<tr ><td width="100%"  colspan="6" align="center"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px; border-top-style:solid; border-top-width:1px; height:20px"><b>Servicio Taller</b></div></td></tr>
<tr>
      
      <td width="40%" colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Descripci&oacute;n</div></td>
      <td width="20%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Valor</div></td>
      <td width="20%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">IVA</div></td>
      <td width="20%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">%</div></td>
       <td width="30%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Total</div></td>
      
     
    </tr>-->
    
    
<?php
$sql="SELECT * FROM serv_fac_ven WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$nit ORDER BY revision";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$tipo_serv=$row['man'];
	$cc=$row['cc'];
	$hh=$row['hh'];
	$frt=$row['frt'];
	$p_serv=$row['precio'];
	$p_iva_serv=$row['precio_iva'];
	$nota=$row['nota'];
	

	
 ?> 
  <tr>
      <td align="center"><?php echo $row['cant'] ?></td>
      <td><?php echo $row['cant'] ?></td>
      <td colspan=""><?php echo "$tipo_serv : $nota" ?></td>
      <td><?php echo money($p_serv) ?></td>
       <?php
if($usar_iva==1){
?>

       <td align="center">16</td>
      <?php
}
?>

      <td colspan=""><?php echo money($p_iva_serv) ?></td>
      <td align="center">16</td>
      <td align="center">16</td>
    </tr>
  
<?php
	
}
}
?>
<tr valign="bottom">
<?php if($tipo_cli!="Traslado" || $vista_remi=="A"){?>

<td ></td><?php if($t==2 && $MODULES["CARROS_RUTAS"]==1)echo "<td></td><th ><b>$TOT_ENVASE</b></th>"; ?><th  height="100%"><?php echo "$TOT_CANT"; ?> </th><th  height="100%"><?php echo "$TOT_DEV"; ?> </th><td  height="100%">&nbsp; </td>
<?php if($usar_iva==1){?><td  height="100%">&nbsp; </td><?php }?>
<td  height="100%">&nbsp; </td><td  height="100%" colspan="">&nbsp; </td><td  height="100%" colspan="">&nbsp; </td>
<?php }
else{
?>
<td  ></td><?php if($t==2 && $MODULES["CARROS_RUTAS"]==1)echo "<td></td><td ><b>$TOT_ENVASE</b></td>"; ?><td  height="100%">&nbsp; </td><td  height="100%">&nbsp; </td><td  height="100%">&nbsp; </td>
<td  height="100%" colspan="">&nbsp; </td>

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

</td>
<td colspan="">

<table align="right"  cellpadding="6" cellspacing="0">
<tbody>


<!--
<tr>
<td>Valor</td><td><?php echo money($SUB) ?></td>
</tr>
<tr>
<td>Exentos</td><td><?php echo money($excentas) ?></td>
</tr>
<tr>
<td>Dcto.</td><td><?php echo money($DCTO) ?></td>
</tr>
-->
<?php
if($usar_iva==1){
?>
<tr>
<td>I.V.A</td><td><?php echo money($TOT-($SUB)) ?></td>
</tr>
<?php
}
?>
<tr>
<td>Total</td><td><?php echo money($TOT_FAC) ?></td>
</tr>


<tr>
<td>CREDITO:</td><td><?php echo money($TOT_CRE) ?></td>
</tr>

<?php
if($R_FTE!=0) echo "<tr><td><b>R-FTE:</b></td><td>-".money3($R_FTE)."</td></tr>";

if($R_FTE!=0) echo "<tr><td><b>R-IVA:</b></td><td>-".money3($R_IVA)."</td></tr>";

if($R_FTE!=0) echo "<tr><td><b>R-ICA:</b></td><td>-".money3($R_ICA)."</td></tr>";
?>
<tr>

<td colspan="" align="right"><b>TOT A PAGAR:</b></td><td><?php echo money3($TOT_FAC-$TOT_CRE) ?></td>
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

<table width="100%" frame="hsides">
<tr>
<?php
if($t!=2 && $usar_remision==0){
?>
<td>Esta factura se asemeja a una letra de cambio seg&uacute;n el art&iacute;culo 779 del c&oacute;digo de comercio. El incumplimiento en el pago causar&aacute; inter&eacute;s por mora a la m&aacute;xima  tasa establecida por la ley por mes o fracci&oacute;n</td>
<?php
}
?>
</tr>
</table>

</td>
</tr>

<tr valign="top">
<td colspan="2">
<table width="100%"  rules="cols" cellpadding="4">
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
<br />
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
else if(0){
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
<script language="javascript1.5" type="text/javascript">
$(document).ready(
	function(){$('#valor_letras').html(''+covertirNumLetras(""+<?php echo $TOT ?>+"")+'')}
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