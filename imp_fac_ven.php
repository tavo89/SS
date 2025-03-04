<?php
include_once('Conexxx.php');
$n_r=0;
$null="";
$pre="";
$nit=$_SESSION['cod_su'];

if(isset($_SESSION['codOrigTras']) && $_SESSION['codOrigTras']!=0){$nit=$_SESSION['codOrigTras'];}  
if(isset($_SESSION['n_fac_ven'])&&isset($_SESSION['prefijo']))
{

if(isset($_REQUEST['exi_ref']))$n_r=$_REQUEST['exi_ref'];

if(isset($_SESSION['anulada']))$null="<div style=\"font-size:24px;\" align=\"center\" ><b>ANULADA</b></div>";

$tabla_fac="";
$tabla_art="";
$t=r("t");
$art=r("art");


$HEADER_FAC="";

if($t==2){
$tabla_fac="fac_remi";
$tabla_art="art_fac_remi";
$HEADER_FAC="REMISION";
}
else if($t==3){
$tabla_fac="fac_remi";
$tabla_art="art_fac_remi";
$HEADER_FAC="TRASLADO";
}
else if($t==4){
$tabla_fac="fac_remi";
$tabla_art="art_fac_remi";
$HEADER_FAC="COTIZACION";
}
else{
$tabla_fac="fac_venta";
$tabla_art="art_fac_ven";
$HEADER_FAC="Factura de Venta";
if($MODULES['FACTURACION_ELECTRONICA']==1){$HEADER_FAC="Factura Electr&oacute;nica de Venta";}
}

if($usar_remision==1)
{
	$HEADER_FAC="REMISION";
}


 if(isset($_SESSION['codOrigTras']) && $_SESSION['codOrigTras']!=0){$HEADER_FAC="TRASLADO";} 


?>

<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>No. <?php echo $_SESSION['n_fac_ven'] ?></title>
<!--<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
<link href="JS/fac_ven.css?<?php echo "$LAST_VER";?>" rel="stylesheet" type="text/css" />
-->

<?php include_once("IMP_HEADER.php"); ?>
<style type="text/css" media="print">
    @page
    {
        size: auto;   /* auto is the initial value */
        margin: 5mm;  /* this affects the margin in the printer settings */
    }

    body
    {
        background-color:#FFFFFF;
       /** border: solid 1px black ;**/
        margin: 5px;  /* this affects the margin on the content before sending to printer */
   }


</style>
<style type="text/css">
.imp_pos{

	top:0px;
	width:100%;
	position: relative;

	 font-size:<?php echo $fac_ven_font_size; ?>px;
	/*

	font-family:  "Bell Gothic";
	font-family: "Bell MT";
	text-transform:lowercase;
	*/
	font-family: "Arial";
	font-weight:bold;

	margin:0px;
}
</style>
</head>

<body>

<?php
$num_fac=$_SESSION['n_fac_ven'];
$pre=$_SESSION['prefijo'];
$hash=s('hashFacVen');

$filtroHash=" AND hash='$hash'";
if(empty($hash) || $hash==0)$filtroHash="";


$showNote="";

//echo "SELECT *, DATE(fecha) as fe FROM $tabla_fac WHERE num_fac_ven='$num_fac' AND prefijo='$pre' $filtroHash AND nit=$nit";
$rs=$linkPDO->query("SELECT *, DATE(fecha) as fe FROM $tabla_fac WHERE num_fac_ven='$num_fac' AND prefijo='$pre' $filtroHash AND nit=$nit");

if($row=$rs->fetch()){


	$tipoFactura = $row["TIPDOC"];
	$nit = $row["nit"];
	$form_pa = $row["tipo_venta"];
	$tipo_cli =$row["tipo_cli"];
	$vendedor = $row["vendedor"];
	$mecanico =$row["mecanico"];
	$cajero = $row["cajero"];
	$fecha = $row["fecha"];
	$ced = $row["id_cli"];
	$cliente = $row["nom_cli"];
	$cliente=$row["nom_cli"];
	$snombr=$row["snombr"];
	$apelli=$row["apelli"];
	$cliente="$cliente $snombr $apelli";
	$dir =$row["dir"];
	$tel = $row["tel_cli"];
	$ciudad = $row["ciudad"];
	$val_let = $row["val_letras"];
	$SUB = $row["sub_tot"];
	$IVA = $row["iva"];
	$DCTO = $row["descuento"];
	$DCTO_IVA = $row["DESCUENTO_IVA"];
	if($DCTO<0)$DCTO=0;
	$TOT = $row["tot"];
	$TOT2 = $row["tot_bsf"];
	$entrega = $row["entrega"];
	if($entrega<0)$entrega=0;
	$entrega2 = $row["entrega_bsf"];
	$cambio = $row["cambio"];
	if($cambio<0)$cambio=0;
	$fe_naci=$row["fe_naci"];
	$placa=$row["placa"];
	$mail=$row["mail"];
	$estado=$row['anulado'];
	$fecha_anulado=$row['fecha_anula'];
	$null="<div style=\"font-size:24px;\" align=\"center\" ><b>ANULADA: $fecha_anulado</b></div>";
	$ESTADO=$row['estado'];
	$anticipo=$row['abono_anti'];

	$R_FTE=$row['r_fte'];
$R_IVA=$row['r_iva'];
$R_ICA=$row['r_ica'];
$IMP_CONSUMO=$row['imp_consumo'];
$IMP_BOLSAS=$row['impuesto_bolsas'];
$TOT_PAGAR=$TOT+$IMP_BOLSAS-($R_FTE+$R_ICA+$R_IVA);

	$NOTA_FAC=$row["nota"];
	$NOTA_DOMICILIO=$row["NORECE"];
	

	if($form_pa=="Contado")
	{
	$codigo_venta=$row['prefijo'];
	$Resol=$row['resolucion'];
	$FechaResol=$row['fecha_resol'];
	$Rango=$row['rango_resol'];

	}
	else if($form_pa=="Credito")
	{
	$codigo_venta=$row['prefijo'];
	$Resol=$row['resolucion'];
	$FechaResol=$row['fecha_resol'];
	$Rango=$row['rango_resol'];

	}
	else
	{
	$codigo_venta=$row['prefijo'];
	$Resol=$row['resolucion'];
	$FechaResol=$row['fecha_resol'];
	$Rango=$row['rango_resol'];

	}




$validacionMesas = ($MODULES["mesas_pedidos"]==1 && $estado!="CERRADA" && $t!=3&& $t!=2&& $t!=4);

$fontSizeDescripcion = $validacionMesas?'18px':'12px';

$vehi=vehi($placa);
$KM=$row["km"];
$MesaID=$row["num_mesa"];

$sedeDest=$row['sede_destino'];
$CUFE = isset($row["cufe"])?$row["cufe"]:'';
	?>
<form action="mod_fac_ven.php" name="form_fac" method="post" id="form-fac" >

<div class="imp_pos" >
<?php 
if(!$validacionMesas){

	if($url_LOGO_A!="Imagenes/logoApp.png" && ($imp_logo==1 )) 
	{?>

		<div align="center" style="padding:10px;">
		<img src="<?php echo $url_LOGO_A ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>">
		</div>

<?php }
}
?>

<table align="center" cellpadding="0" cellspacing="0" class="imp_pos" >
<tr>
<td>
<?php

if($validacionMesas){
$S="SELECT * FROM mesas WHERE id_mesa='$MesaID'";
$RS_M=$linkPDO->query($S);
$numMesa='';
if($ROW_M=$RS_M->fetch()){
	$numMesa=$ROW_M["num_mesa"];
}


echo "<p style=\" font-size:25px;\">Mesa # $numMesa <br> $fecha</p>";
}
else{
echo $PUBLICIDAD;
}

?>
</td></tr></table>
<h2 align="center">
<?php



if($estado=="ANULADO") echo "$null";
?>
</h2>
<?php 
if(!$validacionMesas){
?>
<hr size="2%" style="height:0px"   />
<?php }?>
<table align="center" cellpadding="0" cellspacing="0" class="imp_pos" >
<?php 
if(!$validacionMesas){
?>
<tr>
<td>
<?php 
echo "$HEADER_FAC: " 
?>
</td>
<td>
<?php 

echo "$pre - $num_fac";

?>
</td>

</tr>

<?php

if($t==3){
?>	
<tr>
<td>
<?php echo "Destino Traslado:" ?>
</td>
<td>
<?php echo "$SEDES[$sedeDest]" ?>
</td>

</tr>
<?php
}

?>

<tr>
<td>
Forma de Pago:
</td>
<td><?php echo "$form_pa" ?></td>
</tr>

<tr>
<td>
Fecha:</td><td> <?php echo "$fecha" ?></td>
</tr>
<tr>
<td>Cliente : </td><td><?php echo "$cliente" ?></td>
</tr>
<tr>
<td>
NIT/C.C.: </td><td><?php echo "$ced" ?></td>
</tr>
<?php
if(!empty($dir)){
?>
<tr>
<td>
Direcci&oacute;n: </td><td><?php echo "$dir" ?></td>
</tr>
<?php
}
if(!empty($tel)){
?>
<tr>
<td>
Tel&eacute;fono: </td><td><?php echo "$tel" ?></td>
</tr>
<?php
}
if(!empty($mail)){
?>
<tr>
<td>
E-mail: </td><td><?php echo "$mail" ?></td>
</tr>
<?php
}
?>

<?php
if(!empty($vehi["modelo"])){
?>
<tr>
<td>VEH&Iacute;CULO:  <?php echo $vehi["modelo"] ?></td>

<td colspan="">COLOR:  <?php echo $vehi["color"] ?></td>
<td>PLACA:  <?php echo $vehi["placa"] ?></td>
<td>KM:  <?php echo money($KM) ?></td>
</tr>
<?php
}

}
?>
</table>

<hr size="2%" style="height:0px"  />


<table id="articulos" width="" cellpadding="2" cellspacing="1" class="imp_pos" >

<?php

if($validacionMesas){
	$impFacVen_mini=1;
}

$rs=$linkPDO->query("SELECT *,fecha_vencimiento as feven FROM $tabla_art  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' $filtroHash AND  nit=$codSuc ORDER BY orden_in ASC" );
 if($row=$rs->fetch()){
?>

    <tr ><td colspan="8" align="center"><b></b></td></tr>

    <tr>
    <?php
    if($impFacVen_mini==0){

		?>
      <td  align="center">Cant.</td>

     <?php
    if($mostrar_cod_bar_facVenta==1){
         echo '<td  >Ref</td>';
	}
		?>
      <td  >Producto</td>

      <?php if($usar_iva==1){?><td  >IVA</td><?php } ?>
	  <td width="10%">Val. U</td>
      <td  >Valor</td>

      <!--

      <td width="5%">Cant.</td>
      <td width="5%">Unidades</td>
      <td width="20%">Cod.</td>
      <td width="40%">Art&iacute;culos</td>
       <td width="10%">Presentaci&oacute;n</td>
      <td width="10%">Val. U</td>
      <td width="5%">Iva.</td>
      <td width="20%">Valor</td>

      -->

 <?php
	}
else{

 ?>
 <td  >Cant.</td>
<td  >Producto</td>
<?php
if($mostrar_cod_bar_facVenta==1){
    echo '<td  >Ref</td>';
}
?>
<!--
<td  align=""><?php if($usar_iva==1)echo "IVA" ?></td>-->
<td  >Valor</td>







 <?php
}
?>

   </tr>
  <?php
 }

$rs=$linkPDO->query("SELECT *,fecha_vencimiento as feven FROM $tabla_art  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' $filtroHash AND  nit=$codSuc ORDER BY orden_in" );


 $cont=0;
 $iva_show=0;
 $Iva=0;
 $excentas=0;
 $iva0=1000;
 $infoAdd="";

 $base05=0;
 $iva05=0;

 $base10=0;
 $iva10=0;

 $base16=0;
 $iva16=0;

 $base19=0;
 $iva19=0;

 if($ver_pvp_sin_iva!=0)$SUB=0;
  while($row=$rs->fetch()){

    if($validacionMesas && $row["imprimir"]==0) {
        continue;
	}
	$cont++;
	$feVence = $row["feven"];
	$ref =$row["cod_barras"];
	$id_pro=$row["ref"];
	$des = $row["des"];



	$iva = $row["iva"];
	if($iva>$iva_show)$iva_show=$iva;
	$cant = $row["cant"]*1;
	$fracc=$row['fraccion'];
	if($fracc<=0)$fracc=1;
	$uni = $row["unidades_fraccion"]*1;
	$factor=($uni/$fracc)+$cant;
 
	$Sql=
	"SELECT productos.id_pro, inv_inter.id_inter id_inter, detalle, id_clase, fraccion, fab, max, 
	        min, costo, precio_v, exist, iva, gana, nit_scs, productos.presentacion, nit_proveedor, 
			id_sub_clase, inv_inter.color, inv_inter.talla, inv_inter.ubicacion,inv_inter.aplica_vehi,
			inv_inter.pvp_may,inv_inter.pvp_credito
	FROM productos
	INNER JOIN inv_inter ON productos.id_pro = inv_inter.id_pro
	WHERE productos.id_pro='$id_pro' AND id_inter='$ref'";
	//echo "$Sql";
	$Rs=$linkPDO->query($Sql);
	$Row=$Rs->fetch();
	//if(!empty($Row['id_clase']) || !empty($Row['aplica_vehi']))$des.=" "."|$Row[id_clase]|$Row[aplica_vehi]";

	$pvpE = ($row["precio"]/(1+$iva/100))*$factor;

	if($iva==5){
		$base05+=$pvpE;
		$iva05+=$pvpE*($iva/100);
	}


	if($iva==19){
		$base19+=$pvpE;
		$iva19+=$pvpE*($iva/100);
		}


		if($iva==10){
		$base10+=$pvpE;
		$iva10+=$pvpE*($iva/100);
		}

		if($iva==16){
		$base16+=$pvpE;
		$iva16+=$pvpE*($iva/100);
		}

	if($ver_pvp_sin_iva!=0)
	{
	$pvp = $row["precio"]/(1+$iva/100);

	$SUB+=$pvp*$factor;
	}
	else
	{
	$pvp=$row['precio']*1;
	}
	//$sub_tot = $pvp*$factor;
	$sub_tot = $row['sub_tot'];
	$descuento = $row["dcto"]*1;
	$IMEI=$row['talla'];
	$SN=$row['serial'];
	$presentacion = $row["presentacion"];
	$color=$row['color'];
	
/*

*/

$mostrarCant=($cant*$fracc) + $uni;
if($uni==0){$mostrarCant=$cant;}
if($mostrarCant==0){$mostrarCant=1;}
$PvpUnitario=redondeo($sub_tot/$mostrarCant);

if($TIPO_CHUZO!='DRO'){
	
	if($uni!=0)$uni="-$uni";
	else $uni="";
	$mostrarCant="$cant $uni";
	}



$cod_garantia = $row["cod_garantia"];
if(!empty($SN))$infoAdd.="<br>S/N: $SN";
if(!empty($cod_garantia))$infoAdd.="<br>T. Garantia: $cod_garantia";
if(!empty($IMEI))$infoAdd.="<BR>Talla: $IMEI<BR>Color:$color";


//$des=ajusta_texto_html($des);
$des.="$infoAdd";
	if($iva==0)
	{
		$excentas+=$row['sub_tot'];
		$iva0=0;
	}

if($impFacVen_mini==0){
  ?>
<tr>
<td  align="center"><?php echo $mostrarCant ?></td>
<?php
if($mostrar_cod_bar_facVenta==1){
    echo '<td style="font-size:6px;">'.$ref.'</td>';
}
?>
<td  style="font-size:<?php echo $fontSizeDescripcion; ?>;"><?php echo "$des" ?></td>


<?php if($usar_iva==1)echo "<td  style=\"font-size:6px;\">$iva</td>"; ?>
<td  align="" style="font-size:12px;"><?php echo money($PvpUnitario) ?></td>
<td style="font-size:12px;"><?php echo money($sub_tot) ?></td>
</tr>

<?php
	}


else{
	$PRINT_IVA="";
if($usar_iva==1)$PRINT_IVA="IVA:$iva";
?>
  <tr>


<td  align="left"><?php echo $mostrarCant ?></td>
<td  style="font-size: <?php echo $fontSizeDescripcion; ?>;"><?php echo "  $des" ?></td>
<!--
<td><?php echo "$ref" ?></td>
<td  align=""><?php if($usar_iva==1)echo $iva ?></td>
-->

<td  align="left"><?php echo money($sub_tot) ?></td>
</tr>



<?php
}
$infoAdd="";

  }//fin while

?>
<!--
<tr style="font-size:16px;">
<td>TOTAL PRODUCTOS:</td><TD><?php //echo "$cont" ?></TD>
</tr>
-->

<?php
if($MODULES["SERVICIOS"]==1){

	$sql="SELECT * FROM serv_fac_ven WHERE num_fac_ven='$num_fac' AND prefijo='$pre' $filtroHash AND  cod_su=$codSuc";

	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){
?>

    <tr >
    <td colspan="8" align="center"><b>SERVICIOS</b></td>
    </tr>
    <tr>
    <td>SERVICIO</td><td></td>
    </tr>



    <?php
	}
	$sql="SELECT * FROM serv_fac_ven WHERE num_fac_ven='$num_fac' AND prefijo='$pre' $filtroHash AND  cod_su=$codSuc";

	$rs=$linkPDO->query($sql);
	while($row=$rs->fetch()){

		$serv=$row["serv"];
		$nota=" :".$row["nota"];
		$pvpServ=$row["pvp"];
		$ivaServ=$row["iva"];
		$pvpE = ($row["pvp"]/(1+$ivaServ/100));


		if($ver_pvp_sin_iva!=0)
	    {
		$pvpServ = $row["pvp"]/(1+$ivaServ/100);

		$SUB+=$pvpServ;
		}
		else
		{
		$pvpServ=$row['pvp']*1;
		}


		if($ivaServ==19){
		$base19+=$pvpE;
		$iva19+=$pvpE*($ivaServ/100);
		}

		if($ivaServ==10){
		$base10+=$pvpE;
		$iva10+=$pvpE*($ivaServ/100);
		}

		if($ivaServ==16){
		$base16+=$pvpE;
		$iva16+=$pvpE*($ivaServ/100);
		}
		if($ivaServ==0)
	   {
		$excentas+=$row['pvp'];
		$iva0=0;
	    }



		?>
        <tr>
        <td><?php  echo "$serv $nota IVA: $ivaServ" ?></td>
        <td><?php  echo money("$pvpServ"); ?></td>
        </tr>
        <?php
	}


}
$excentas=redondeo($excentas);
$base05=redondeo($base05);
$iva05=redondeo($iva05);

$base10=redondeo($base10);
$iva10=redondeo($iva10);

$base16=redondeo($base16);
$iva16=redondeo($iva16);

$base19=redondeo($base19);
$iva19=redondeo($iva19);
	?>


</table>
<hr size="2%" style="height:0px; border-style: dotted;"  />
<?php


if(!$validacionMesas){
?>


<table align="center" width="60%" cellpadding="0" cellspacing="0" class="imp_pos" >
<?php if($usar_iva!=0 ){?>
<?php if($excentas!=0){?>
<tr>
<td><?php echo $fac_ven_etiqueta_nogravados;?></td><td><?php echo money($excentas)?></td>
</tr>
<?php }?>
<?php if($iva05!=0){?>
<tr>
<td>Base GRAVADA 5%</td><td><?php echo money($base05) ?></td>
</tr>
<tr>
<td>IVA 5%</td><td><?php echo money($iva05) ?></td>
</tr>
<?php }?>

<?php if($iva10!=0){?>
<tr>
<td>Base GRAVADA 10%</td><td><?php echo money($base10) ?></td>
</tr>
<tr>
<td>IVA 10% </td><td> <?php echo money($iva10) ?></td>
</tr>
<?php }?>

<?php if($iva16!=0){?>
<tr>
<td>Base GRAVADA 16%</td><td><?php echo money($base16) ?></td>
</tr>
<tr>
<td>IVA 16% </td><td> <?php echo money($iva16) ?></td>
</tr>
<?php }?>



<?php if($iva19!=0){?>
<tr>
<td>Base GRAVADA: </td><td align="left"><?php echo money($base19) ?></td>
</tr>
<tr>
<td>IVA 19% </td><td align="left"> <?php echo money($iva19) ?></td>
</tr>
<?php }?>

<?php
}
?>
<?php if($DCTO!=0 && $mostrar_dcto_fac==1){

	$perDcto=redondeo(($DCTO/($DCTO+$SUB)) *100);
	?>
<tr>
<td>DCTO:</td><td align="left"><?php echo money($DCTO)." &nbsp; ($perDcto%)" ?></td>
</tr>
	<?php }?>

<?php if($DCTO_IVA>0 ){


	?>
<tr>
<td>DCTO IVA:</td><td align="left"><?php echo money($DCTO_IVA)." &nbsp;  " ?></td>
</tr>
	<?php }?>
<!--
<tr>
<td>Total</td><td><?php echo money($TOT*1) ?></td>
</tr>-->
<?php
if($R_FTE!=0) echo "<tr><td><b>R-FTE:</b></td><td>-".money3($R_FTE)."</td></tr>";

if($R_IVA!=0) echo "<tr><td><b>R-IVA:</b></td><td>-".money3($R_IVA)."</td></tr>";

if($R_ICA!=0) echo "<tr><td><b>R-ICA:</b></td><td>-".money3($R_ICA)."</td></tr>";
if($impuesto_consumo!=0) echo "<tr><td><b>Imp. Consumo:</b></td><td>".money3($IMP_CONSUMO)."</td></tr>";
if($IMP_BOLSAS!=0) echo "<tr><td><b>Imp. Bolsas:</b></td><td>".money3($IMP_BOLSAS)."</td></tr>";
?>
 <tr style="font-size:14px;">

<td colspan="" align="left" ><b>TOTAL A PAGAR:</b></td><td align="left"><?php echo money3($TOT_PAGAR) ?></td>
</tr>
<?php if($MODULES["ANTICIPOS"]==1 && $anticipo>0 ){?>
<tr>
<td align="left">Pago Anticipos/Bonos:</td><td align="left"><?php echo money($anticipo) ?></td>
</tr>
<?php  } ?>


<tr>
<td align="left">Pago Efectivo: </td><td align="left"> <?php echo money($entrega*1) ?></td>

<?php if($TOT2>0){?>
<td align="left">Pago Efectivo(BsF): </td><td align="left"><?php echo money($entrega2*1) ?></td>
<?php }?>
</tr>
<tr>

<td align="left">Cange: </td><td align="left"><?php echo money($cambio) ?></td>
</tr>
<tr>
<?php


if($MODULES['FACTURACION_ELECTRONICA']==1 && $tipoFactura==7){

	$urlVars="t=1&n_fac_ven=$num_fac&prefijo=$pre&hashFacVen=$hash&codSuc=$codSuc";
    $linkQR = "http://$_SERVER[HTTP_HOST]/verFacturaElec.php?$urlVars";
	$QR= getQRcode("$linkQR");
?>
<td  align="center"  class="uk-hiddens" width="50" colspan="2">
<img src="<?php echo ("$QR") ?>" width="50" height="50" alt="" style="left: 0%;">
</td>
</tr>
<tr>
<td  align="center"  class="uk-hiddens" width="50" colspan="2">
<?php
if(!empty($CUFE) && isset($CUFE))
{
    echo '<div class="uk-grid  uk-grid-collapse uk-width-1-1"    align="center">
    <p class="pie_pagina_nanimo2">';	
    echo 'CUFE: '.$CUFE;
    echo '</p></div>';
}
}
?>
</td>
</tr>
</table>
<?php
if($form_pa=="Credito"){
if($ESTADO=="PENDIENTE")echo "<h1 align=\"center\">$ESTADO</h1>";
else if($ESTADO=="PAGADO")echo "<h2 align=\"center\">$ESTADO</h2>";
}
?>
<!--
<hr size="2%" style="height:0px"  />
<table align="center" width="100%" cellpadding="0" cellspacing="0" class="imp_pos" >
<tr>
<td colspan="2" align="right">Despach&oacute; :</td><td colspan="2"><?php echo $vendedor ?></td>
 
</tr>
</table>
-->
<?php
echo "<span style=\" font-size:12px; font-weight:normal\">$formasPagoFacturacion_facVenta</span><br>";
if($t!=2 && $usar_remision==0 && $usar_iva==1){
?>
<hr size="2%" style="height:0px; border-style: dotted; "  />
<P align="center">
Autorizaci&oacute;n numeraci&oacute;n de Facturaci&oacute;n Dian No. <?php echo $Resol ?> del <?php echo $FechaResol ?>
<br />
<?php echo $Rango ?>

<?php

}
else echo "<P align=\"center\">";
if($form_pa=="Credito"){
  echo "<BR /><BR /><BR /><BR />__________________________________________<br>Firma<br>$cliente<br>C.C:$ced";
}
?>
</P>
<?php

if($t!=2){
	
	if(!empty($NOTA_FAC))$showNote="Nota: $NOTA_FAC<br>";
	if(!empty($NOTA_DOMICILIO))$showNote.="Domicilio: <i>$NOTA_DOMICILIO</i><br>";
	
	
?>
<hr size="2%" style="height:0px; border-style: dotted;"  />
<table align="center" cellpadding="0" cellspacing="0" class="imp_pos" >
<tr>
<td>
<p align="center" >
<span style="font-size:12px"><?php echo "<b>$showNote</b>"; ?></span>
<?php
if($MODULES["mesas_pedidos"]==1 && $estado=="CERRADA"){
echo "Propina sugeria: por disposicion de la superintendencia de industria y comercio se informa que en este establecimiento la propina es sugerida al consumidor y corresonde al 10% sobre el valor total de la cuenta, el cual podra ser aceptado, rechazado o modificado por usted de aceurdo a la valorizacion del servicio prestado. Si no desea cancelar dicho valor haga caso omiso del mismo, si desea cancelar un valor diferente indiquelo asi para hacer el ajuste correspondiente: $ __________<br>En este establecimiento publico el dinero recogido por concepto de propinas se reparte en un 100% entre los trabajadores de las diferentes areas de trabajo. Cualquier inconveiente cobn el cobro de la propina comuniquese a la linea 3143678727";
}
else{
    echo "Conserve este recibo para reclamos<br />Gracias por su compra";

}
?>
</p>
</td></tr></table>
<hr size="2%" style="height:0px; border-style: dotted;"  />
<?php
}

}
else {
	if(!empty($NOTA_FAC))$showNote='<p style="font-size:16px;">Nota: '.$NOTA_FAC.'<br></p>';
	echo $showNote;
}
?>

<div id="imp"  align="center">

    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
    <input  type="button" value="Formato TXT" name="boton" onMouseDown="location.assign('imp_fac_txt.php');" />
</div>
 </div>

</form>
<script>
$(document).ready(function(){

	//imprimir();

	});
</script>
<?php
}

?>
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
