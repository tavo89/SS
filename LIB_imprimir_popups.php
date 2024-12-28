<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function imp_fac($num_fac,$pre,$boton,$t=1,$sedeOrig="no",$ID=0,$tipoDoc='')
{
	global $codContadoSuc,$ImpURLcontado,$ImpURLcredito,$tipo_impresion,$codCreditoSuc,$codRemiPOS,$MODULES,$imp_fac_formatoCustom,$codSuc,$formatoFacturaDefecto;
	$formatoImpresion = r('formatoImp');
    if($MODULES["FACTURACION_ELECTRONICA"]==1){$ImpURLcredito = 'verFacturaElec.php';}
	if($MODULES["CARROS_RUTAS"]==1){$ImpURLcredito="imp_planilla.php";}
	if($imp_fac_formatoCustom==1 && $t!=2){$ImpURLcredito="imp_fac_ven_papel.php";$ImpURLcontado="imp_fac_ven_cre.php";}

	if($sedeOrig!="no")$_SESSION['codOrigTras']=$sedeOrig;
	else $_SESSION['codOrigTras']=0;
	//echo "<h1>$codContadoSuc $ImpURLcontado $ImpURLcredito | $num_fac - $pre , $boton</h1>";
	if($boton=="Imprimir")
	{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['prefijo']=$pre;
	$_SESSION['id_fac_ven']=$ID;
	$facVars = "codSu=$codSuc&prefijo=$pre&n_fac_ven=$num_fac";
	if($pre==$codContadoSuc || $pre=="VT" || $formatoImpresion=='POS'|| $formatoFacturaDefecto=='POS' ){
		popup("$ImpURLcontado?t=$t"," ", "900px","650px");
	}
	else {
		popup("$ImpURLcredito?t=$t&$facVars"," ", "900px","650px");
	}
	/*else if($pre==$codCreditoSuc || $tipoDoc==7) {popup("$ImpURLcredito?t=$t&$facVars"," ", "900px","650px");}
	else{popup("$ImpURLcontado?t=$t&$facVars","", "1200px","700px");}*/
	}

};
function imp_fac_pos($num_fac,$pre,$boton ,$t=1,$sedeOrig="no",$ID=0)
{
	global $codContadoSuc,$ImpURLcontado,$ImpURLcredito,$tipo_impresion,$codCreditoSuc,$codRemiPOS,$MODULES,$imp_fac_formatoCustom;

	if($sedeOrig!="no")$_SESSION['codOrigTras']=$sedeOrig;
	else $_SESSION['codOrigTras']=0;
 if($boton=="Imprimir")
	{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['prefijo']=$pre;
	$_SESSION['id_fac_ven']=$ID;
	 popup("$ImpURLcontado?t=$t","Factura No. $num_fac", "900px","650px");
	}
 

};
function imp_comp_ingre($numFac,$pre,$numComp)
{
global $tipo_imp_comprobantes,$ImpURLcomprobantesPOS,$ImpURLcomprobantesCOM;
$_SESSION['num_fac_ven']=$numFac;
$_SESSION['pre']=$pre;
$_SESSION['num_comp_ingre']=$numComp;
if($tipo_imp_comprobantes=="POS")popup("$ImpURLcomprobantesPOS","", "800px","650px");
else popup("$ImpURLcomprobantesCOM","", "900px","650px");
};

function imprimir_fac($num_fac,$pre,$hash){

global $vista_remi,$MODULES,$usar_fracciones_unidades,$usar_remision,$usar_firmas_factura,$Variable_size_imp_carta,$codPapelSuc,$SEDES,$usar_costo_remi,$usar_iva,$ver_pvp_sin_iva,$usar_fracciones_unidades,$X_fac,$url_LOGO_A,$X,$Y,$PUBLICIDAD,$CHAR_SET,$REGIMEN,$impuesto_consumo,$LABEL_REMISION,$codSuc,$descuento_despues_iva_ventas,$formasPagoFacturacion_facVenta;
global $linkPDO,$vencimiento_meses_resol_dian,$fac_ven_etiqueta_nogravados;


$n_r=0;
$null="";
$nit=$_SESSION['cod_su'];

$ID_FAC=s("id_fac_ven");
if(isset($_SESSION['codOrigTras']) && $_SESSION['codOrigTras']!=0){$nit=$_SESSION['codOrigTras'];}


if(isset($num_fac)&&isset($pre))
{

if(isset($_REQUEST['exi_ref']))$n_r=$_REQUEST['exi_ref'];


$tabla_fac="";
$tabla_art="";
$tabla_serv="";
$t=r("t");
$art=r("art");

$HEADER_FAC="";
if($usar_remision==1){$HEADER_FAC="REMISION";}
if($MODULES["CARROS_RUTAS"]==1 && $t==2)
{$HEADER_FAC="PLANILLA";}

if($t==2){
$tabla_fac="fac_remi";
$tabla_art="art_fac_remi";
$tabla_serv="serv_fac_remi";
$HEADER_FAC="<b>REMISION</b>";
}else if($t==3){
$tabla_fac="fac_dev_venta";
$tabla_art="art_devolucion_venta";
$tabla_serv="serv_fac_devolucion";
$HEADER_FAC="<b>DEVOLUCION VENTA</b>";
}
else{
$tabla_fac="fac_venta";
$tabla_art="art_fac_ven";
$tabla_serv="serv_fac_ven";
$HEADER_FAC="Factura de Venta";
if($MODULES['FACTURACION_ELECTRONICA']==1){$HEADER_FAC="Factura Electr&oacute;nica de Venta";}
if($usar_remision==1){$HEADER_FAC="REMISION";}
}



$firmaEMPRESA="";
if($usar_firmas_factura==1){
$rs=$linkPDO->query("SELECT firma_fac FROM sucursal WHERE cod_su='$codSuc'" );
//echo "SELECT firma_fac FROM sucursal WHERE cod_su='$codSuc'";
$row=$rs->fetch();
$firmaEMPRESA=$row["firma_fac"];

}


$rsCount=$linkPDO->query("SELECT COUNT(*) AS nf FROM $tabla_art  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND  nit=$nit" );

$rowCount=$rsCount->fetch();
$totArts=$rowCount["nf"];


if($Variable_size_imp_carta==1 && $MODULES['FACTURACION_ELECTRONICA']!=1){$X_fac="11cm";}

if($totArts>=16)$X_fac="27.9cm";


//echo "<li>SELECT COUNT(*) AS nf FROM $tabla_art  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND  nit=$nit</li><li>$totArts</li>";



$filtroHash=" AND hash='$hash'";
if(empty($hash) || $hash==0)$filtroHash="";


$rs=$linkPDO->query("SELECT * FROM $tabla_fac WHERE num_fac_ven='$num_fac' AND prefijo='$pre' $filtroHash AND nit=$nit");


if($row=$rs->fetch()){

	$TIPO_FAC=$row["tipo_fac"];
	if($TIPO_FAC=="cotizacion"){$HEADER_FAC="<b>COTIZACI&Oacute;N</b>";}
	if($TIPO_FAC=="remision"){$HEADER_FAC="<b>$LABEL_REMISION</b>";}
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
	$snombr=$row["snombr"];
	$apelli=$row["apelli"];
	$cliente="$cliente $snombr $apelli";
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
	$date = date_create($row['fecha_resol']);
	date_add($date, date_interval_create_from_date_string($vencimiento_meses_resol_dian.' MONTH'));
	$FechaResol=$row['fecha_resol']." a ".date_format($date, 'Y-m-d');
	$Rango=$row['rango_resol'];
	$R_FTE=$row['r_fte'];
	$R_IVA=$row['r_iva'];
	$R_ICA=$row['r_ica'];
	$IMP_CONSUMO=$row['imp_consumo'];
	$IMP_BOLSAS=$row['impuesto_bolsas'];
	$TOT_PAGAR=$TOT+$IMP_BOLSAS-($R_FTE+$R_ICA+$R_IVA);
	$estadoCre=$row['estado'];
	if($estadoCre=="PAGADO"){$estadoCre='<span class="uk-badge uk-badge-success" style="font-size:18px;">PAGADO</span>';}
	if($estadoCre=="PENDIENTE"){$estadoCre='<span class="uk-badge uk-badge-success" style=" font-weight:bold; color:orange;font-size:12px;">PENDIENTE DE PAGO</span>';}
	$pagare=$row['num_pagare'];
	$sedeDest=$row['sede_destino'];
	$sedeOrig=$row['nit'];

	$DCTO_IVA = $row["DESCUENTO_IVA"];
	$CUFE = isset($row["cufe"])?$row["cufe"]:'';


	$placa=$row["placa"];
	$vehi=vehi($placa);


//if($codigo_venta==$codPapelSuc){$HEADER_FAC="<b>ORDEN DE ENTREGA</b>";}

?>
<form action="mod_fac_ven.php" name="form_fac" method="post" id="form-fac" >
<div class="" id="imprimirFacturaPDF" >
<table align="center" style="top:0px; width:21.5cm; height:<?php echo "$X_fac" ?>;"  border="0" frame="box"  cellspacing="0px" cellpadding="0" class="imp_COM">
<tr valign="top">
<td colspan="2" height="60px">
<table width="100%" height=""  frame="box" class="imp_COM_Inner"  cellpadding="0" cellspacing="0">
<tr>
<?php if($url_LOGO_A!="Imagenes/logoApp.png"){

?>
<td  align="center"  class="uk-hiddens">
<img alt="LOGO" src="<?php echo ($url_LOGO_A) ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>">
</td>
<?php }?>
<?php


if($MODULES['FACTURACION_ELECTRONICA']==1){

	$urlVars="t=1&n_fac_ven=$num_fac&prefijo=$pre&hashFacVen=$hash&codSuc=$codSuc";
    $linkQR = "http://$_SERVER[HTTP_HOST]/verFacturaElec.php?$urlVars";
	$QR= getQRcode("$linkQR");
?>
<td  align="center"  class="uk-hiddens" width="100">
<img src="<?php echo ("$QR") ?>" width="100" height="100" alt="">
</td>
<?php
}
?>
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
<td colspan="2" style="font-size:17px;">
<?php if($TIPO_FAC!="cotizacion" ){echo "$HEADER_FAC:<br> $pre - $num_fac";}
else{echo "$HEADER_FAC: $num_fac";} ?>

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
if($t!=2 && $usar_remision==0 && $REGIMEN!="SIMPLIFICADO" ){
?>
<tr>
<td colspan="2" style="font-size:8px;">

Autorizaci&oacute;n numeraci&oacute;n de Facturaci&oacute;n Dian No. <?php echo $Resol ?> del <?php echo $FechaResol ?> &nbsp;
<?php echo $Rango ?>

</td>
</tr>
<?php
}
else{
	if($tipo_cli=="Traslado" && $TIPO_FAC!="cotizacion"){
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

<td width="5%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Frac</div></td>

<?php if($MODULES["CARROS_RUTAS"]==1 && $t==2){ ?>
<td width="13%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Cant. Dev.</div></td>
<?php } ?>

<?php if($MODULES["CARROS_RUTAS"]==1 && $usar_fracciones_unidades==1 && $t==2){ ?>
<td width="13%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Frac. Dev.</div></td>
<?php } ?>

<td width="25%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">REF.</div></td>
<td width="40%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Descripci&oacute;n</div></td>
<td width="15%"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px">Valor Unitario</div></td>

<?php if($usar_costo_remi==1 && $t==2 && $TIPO_FAC!='cotizacion'){?>
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

$s="SELECT a.*,b.presentacion as presentaPro FROM $tabla_art a LEFT JOIN productos b ON a.ref=b.id_pro  
    WHERE num_fac_ven=$num_fac AND prefijo='$pre' $filtroHash AND nit=$nit ORDER BY orden_in ASC ";

$rs=$linkPDO->query("$s" );

 $cont=0;
 $iva_show=0;
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

 $SUB_SIMPLIFICADO=0;
 $limitPage=28;
 $pageCont=0;
 //if($ver_pvp_sin_iva!=0)$SUB=0;
  while($row=$rs->fetch()){

	$pageCont++;
	$feVence = $row["fecha_vencimiento"];
	$codBarras = $row["cod_barras"];
	$ref = $row["ref"];
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
	$pvpE = ($row["precio"]/(1+$iva/100))*$factor;
	if(($ver_pvp_sin_iva==0 || $MODULES["PVP_COTIZA"]==1) && $MODULES['FACTURACION_ELECTRONICA']!=1)$pvp = $row["precio"];
	$sub_tot = $pvp*$factor;
	$descuento =$row["dcto"]*1;
	$IMEI=$row['talla'];
	$SN=$row['serial'];
	$presentacion = $row["presentaPro"];
	//if(empty($presentacion)){$presentacion="";}
	$color=$row['color'];
	$num_motor=$row["num_motor"];
	//$SUB+=$pvp*$factor;

	$Sql="SELECT * FROM inv_inter WHERE id_pro='$ref' AND id_inter='$codBarras' AND fecha_vencimiento='$feVence' AND nit_scs='$sedeOrig'";
	//echo "<li>$Sql</li>";
	$rsP=$linkPDO->query($Sql);
	$pvpTras=0;
	$cod_garantia ="";
	if($rowP=$rsP->fetch()){
	$pvpTras=$rowP["precio_v"];
	//$cod_garantia = $rowP["cod_garantia"];
	}

	if(!empty($cod_garantia))$infoAdd.="<br>T. Garantia: $cod_garantia";
	if(!empty($SN))$infoAdd.="<br>S/N: $SN";

	if(!empty($num_motor)){

		$sql="SELECT * FROM art_fac_com WHERE num_motor='$num_motor'";
		$rs2=$linkPDO->query($sql);
		if($row2=$rs2->fetch()){
		$linea=$row2["linea"];
		$modelo=$row2["modelo"];
		$color=$row2["color"];
		$cilindraje=$row2["cilindraje"];
		$chasis=$row2["num_chasis"];
		$consecutivo_proveedor=$row2["consecutivo_proveedor"];

		$infoAdd.="<br>Linea: $linea";
		$infoAdd.="<br>Modelo: $modelo";
		$infoAdd.="<br>Cilindrada: $cilindraje";
		$infoAdd.="<br>Color: $color";
		$infoAdd.="<br>#Chasis: $chasis";
		$infoAdd.="<br>#Motor: $num_motor";
		}

		}

$des.="$infoAdd";


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
	
	if($iva==0)
	{
		$excentas+=$pvp*$factor;
		$iva0=0;
	}
	$SUB_SIMPLIFICADO+=$pvp*$factor;


//if(!empty($Row['id_clase']) || !empty($Row['aplica_vehi']))$des.=" "."|$Row[id_clase]|$Row[aplica_vehi]";



  ?>
<tr style="<?php if($totArts>=16){echo "font-size:12px;";}else{echo "font-size:12px;";} ?>">
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
      <td > <?php echo $des ?> </td>
      <td   align="right"><?php echo money($pvp) ?></td>
      <?php if($usar_costo_remi==1 && $t==2 && $TIPO_FAC!='cotizacion'){?>
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

  if($pageCont>=$limitPage){
	$pageCont=0;

echo '
</table>
</td>
</tr>
</table>
';
//echo "[$pageCont]";
echo '
<div class="page-break"></div>

<table align="center" style=" width:21.5cm; height:'. $X_fac.'; "  border="0" frame="box"  cellspacing="0px" cellpadding="0" class="imp_COM">
<tr valign="top">
<td colspan="2" valign="top" height="100%">
<table id="articulos" width="100%" height="100%"  cellspacing="0" cellpadding="0" frame="box" rules="cols" style="border-width:1px" class="imp_COM_Inner">

';
}


  }//fin while


if(1){
$sql="SELECT * FROM $tabla_serv WHERE num_fac_ven=$num_fac AND prefijo='$pre' $filtroHash AND cod_su=$nit";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
?>


<tr ><td width="100%"  colspan="9" align="center"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px; border-top-style:solid; border-top-width:1px;  "><b>Servicios</b></div></td></tr>



<?php
$sql="SELECT * FROM $tabla_serv WHERE num_fac_ven=$num_fac AND prefijo='$pre' $filtroHash AND cod_su=$nit";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{

	$serv=$row["serv"];
	$nota=": ".$row["nota"];
	$idServ=$row["id_serv"];
	$codServ=$row["cod_serv"];
	$ivaServ=$row["iva"];
	$pvpE = ($row["pvp"]/(1+$ivaServ/100));

	$pvpServ = $row["pvp"]/(1+$ivaServ/100);
	if(($ver_pvp_sin_iva==0 || $MODULES["PVP_COTIZA"]==1) && $MODULES['FACTURACION_ELECTRONICA']!=1)$pvpServ = $row["pvp"];
	$nomTec=getTec($row["id_tec"]);

	
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

<td width="300px">
<b>SON:</b><span id="valor_letras"></span><?php echo $val_let; ?>
</td>
<td colspan="2" width="70%" align="right">

<table align="right"  cellpadding="6" cellspacing="0" class="imp_COM_Inner" width="100%">
<tbody>
<?php

if( ($REGIMEN!="SIMPLIFICADO" && $usar_iva==1) || ($R_FTE!=0 || $R_IVA!=0 ||$R_ICA!=0)){

if( ($REGIMEN!="SIMPLIFICADO" && $usar_iva==1)){
	
?>
<!--
<tr>
<td>Valor</td><td><?php echo money($SUB-$excentas) ?></td>

</tr>

<tr>
<td>Exento</td><td><?php echo money3($excentas) ?></td>

</tr>
-->

 <?php
}

else{
	
?>
<tr>
<td>Valor</td><td><?php echo money($SUB ) ?></td>

</tr>


<?php
}



 }
else{
?>
<!--
<tr>
<td>Valor </td><td><?php echo money3($SUB_SIMPLIFICADO) ?></td>
</tr>-->

<?php
}

if($DCTO!=0){/*

	$perDcto=redondeo(($DCTO/($DCTO+$SUB)) *100);
	?>
<tr class="uk-hidden">
<td>DCTO:</td><td><?php echo money($DCTO)." &nbsp; ($perDcto%)" ?></td>
</tr>
	<?php */}?>
<?php

if($usar_iva==1){

?>
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
<td>Base GRAVADA 19%</td><td><?php echo money($base19) ?></td>
</tr>
<tr>
<td>IVA 19% </td><td> <?php echo money($iva19) ?></td>
</tr>
<?php }?>
<?php
}
?>
<!--
<tr>
<td>Total</td><td><?php echo money($TOT) ?></td>
</tr>
-->
<?php
if($R_FTE!=0) echo "<tr><td><b>R-FTE:</b></td><td>-".money($R_FTE)."</td></tr>";

if($R_IVA!=0) echo "<tr><td><b>R-IVA:</b></td><td>-".money($R_IVA)."</td></tr>";

if($R_ICA!=0) echo "<tr><td><b>R-ICA:</b></td><td>-".money($R_ICA)."</td></tr>";

if($impuesto_consumo!=0) echo "<tr><td><b>Imp. Consumo:</b></td><td>".money($IMP_CONSUMO)."</td></tr>";
if($IMP_BOLSAS!=0) echo "<tr><td><b>Imp. Bolsas:</b></td><td>".money($IMP_BOLSAS)."</td></tr>";
?>


<?php if($DCTO_IVA!=0 && $descuento_despues_iva_ventas==1){


	?>
<tr>
<td>DCTO IVA:</td><td><?php echo money($DCTO_IVA)." &nbsp;  " ?></td>
</tr>
	<?php }?>

<tr style="font-size:18px;">
<td colspan="" align="left" ><b>TOTAL PAGAR:</b></td><td><?php echo money($TOT_PAGAR) ?></td>
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

<table width="100%" frame="hsides" class="imp_COM_Inner" style="height:90px;">
<tr style="font-size:12px;">

<td>
<?php

echo "<b ><span style=\" font-size:14px;\">$nota_fac</span></b> <br>";  
echo "<span style=\" font-size:14px;\">$formasPagoFacturacion_facVenta</span><br>";

if($t!=2 && $usar_remision==0&& $REGIMEN!="SIMPLIFICADO" ){
	echo "Esta factura se asemeja a una letra de cambio seg&uacute;n el art&iacute;culo 774 numeral 6 del c&oacute;digo de comercio. El incumplimiento en el pago causar&aacute; inter&eacute;s por mora a la m&aacute;xima  tasa establecida por la ley por mes o fracci&oacute;n";
}

?>
</td>
</tr>

</table>

</td>
</tr>

<tr valign="top">
<td colspan="2">
<table width="100%"  rules="cols" cellpadding="4" class="" style="font-size:12px;">
<?php
if($t!=2 || $tipo_cli!="Traslado"){
?>
<tr>
<td>Elabor&oacute;:



<p align="center">
<?php if(!empty($firmaEMPRESA)){?>
<div><img src="<?php echo "$firmaEMPRESA"; ?>"   style="max-width:60%; height:40px;"></div><?php } ?>
__________________________________</p>
<?php echo "$vendedor"?>
</td>

<td >
Aceptada:

<p align="center">
<?php if(!empty($firmaEMPRESA)){?>
<div><img src="Imagenes/EmptySign.png"   style="max-width:60%; height:40px;"></div><?php } ?>
__________________________________</p>
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
<tr>
<td width="100%" colspan="2">
<?php

if(!empty($CUFE) && isset($CUFE))
{
    echo '<div class="uk-grid  uk-grid-collapse uk-width-1-1"    align="center">
    <p class="pie_pagina_nanimo2">';	
    echo 'CUFE: '.$CUFE;
    echo '</p></div>';
}
?>

<div class="uk-grid  uk-grid-collapse uk-width-1-1"    align="center">
<p class="pie_pagina_nanimo"> 
&nbsp;NANIMO SOFTWARE NIT: 1.120.359.931-0
</p>
</div>
</td>
</tr>
 </table>

 </div>
 <div id="imp"  align="center">

    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" class="addbtn"/>
    <input  type="button" value="PDF" name="boton" onMouseDown="javascript: printPDF();" class="addbtn"/>


</div>

</form>

<?php
}
}




	}


function calc_fechas_suspension($fecha)
{
	
$firstBatch=explode("-",$fecha);

$mesActual=$firstBatch[1];
$yearActual=$firstBatch[0];

$mesActual=$mesActual*1;
if($mesActual<12){$mesActual++;}
else {$mesActual=1;$yearActual++;}

if($mesActual<10){$mesActual="0".$mesActual;}

$diaLIM="0".$_SESSION["dia_limite_pago_facturas"];
if($_SESSION["dia_limite_pago_facturas"]>=10){$diaLIM=$_SESSION["dia_limite_pago_facturas"];}

$diaSUS="0".$_SESSION["dia_suspension"];
if($_SESSION["dia_suspension"]>=10){$diaSUS=$_SESSION["dia_suspension"];}

 	

$resp["SUS"]="$yearActual-$mesActual-$diaSUS";
$resp["LIM"]="$yearActual-$mesActual-$diaLIM";

return $resp;
	
}

function imprimir_fac_custom($num_fac,$pre,$hash,$nit){

global $vista_remi,$MODULES,$usar_fracciones_unidades,$usar_remision,$usar_firmas_factura,$Variable_size_imp_carta,$codPapelSuc,$SEDES,$usar_costo_remi,$usar_iva,$ver_pvp_sin_iva,$usar_fracciones_unidades,$X_fac,$url_LOGO_A,$X,$Y,$PUBLICIDAD,$CHAR_SET,$REGIMEN,$impuesto_consumo,$dirSuc,$munSuc,$depSuc,$tel1Su,$limite_pago_facturas,$dia_suspension;
global $linkPDO,$email_sucursal,$vencimiento_meses_resol_dian;
$filtroHash=" AND hash='$hash'";
if(empty($hash) || $hash==0)$filtroHash="";

$tabla_fac="fac_venta";
$tabla_art="art_fac_ven";
$tabla_serv="serv_fac_ven";
$HEADER_FAC="Factura de Venta";

//echo "dia_limite_pago_facturas $limite_pago_facturas -- suspencion $dia_suspension<br>";

$sql="SELECT *,DATE(fecha) as fecha_n  FROM $tabla_fac WHERE num_fac_ven='$num_fac' AND prefijo='$pre' $filtroHash AND nit=$nit";
//echo "$sql";

$rs=$linkPDO->query($sql);

if($row=$rs->fetch()){


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

	$sqlAux="select * from usuarios where id_usu='$ced'";
	$rsAux= $linkPDO->query($sqlAux);
	$Ruta="";
	$OrdenRuta="";
	$listaRutas=$_SESSION["servicios_rutas_lista"];
	if($rowAux=$rsAux->fetch())
	{
		$Ruta=$listaRutas[$rowAux["id_ruta"]];
		$OrdenRuta=$rowAux["orden_ruta"];
	}
	if($OrdenRuta<10){$OrdenRuta="00$OrdenRuta";}
	else if($OrdenRuta>=10 && $OrdenRuta<100){$OrdenRuta="0$OrdenRuta";}
	else if($OrdenRuta>=100){$OrdenRuta="$OrdenRuta";}


	$cliente = $row["nom_cli"]." ".$row["snombr"]." ".$row["apelli"];
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
	$date = date_create($row['fecha_resol']);
	date_add($date, date_interval_create_from_date_string($vencimiento_meses_resol_dian.' MONTH'));
	$FechaResol=$row['fecha_resol']." a ".date_format($date, 'Y-m-d');
	$Rango=$row['rango_resol'];
	$barrio=$row['loccli'];
$R_FTE=$row['r_fte'];
$R_IVA=$row['r_iva'];
$R_ICA=$row['r_ica'];
$IMP_CONSUMO=$row['imp_consumo'];
$IMP_BOLSAS=$row['impuesto_bolsas'];
$TOT_PAGAR=$TOT+$IMP_BOLSAS-($R_FTE+$R_ICA+$R_IVA);
$estadoCre=$row['estado'];
if($estadoCre=="PAGADO"){$estadoCre='<span class="uk-badge uk-badge-success" style="font-size:18px;">PAGADO</span>';}
$pagare=$row['num_pagare'];
$sedeDest=$row['sede_destino'];
$sedeOrig=$row['nit'];


$placa=$row["placa"];
$vehi=vehi($placa);


$fechaNormal=$row["fecha_n"];

$mes=date("m",strtotime($fechaNormal));
$year=date("Y",strtotime($fechaNormal));
$lastDay=date("t",strtotime($fechaNormal));

$periodoIni="$year-$mes-01";
$periodoFin="$year-$mes-$lastDay";


$fechas_limite=calc_fechas_suspension($fechaNormal);

$fechaLIM=$fechas_limite["LIM"];
$fechaSUS=$fechas_limite["SUS"];


if($codigo_venta==$codPapelSuc){$HEADER_FAC="<b>ORDEN DE ENTREGA</b>";}


$sql="SELECT *, count(*) as TOT_ROWS FROM $tabla_serv WHERE num_fac_ven=$num_fac AND prefijo='$pre' $filtroHash AND cod_su=$nit ";
$rs=$linkPDO->query($sql);
$rowINI=$rs->fetch();
$TOTAL_REGISTROS=$rowINI["TOT_ROWS"];

$sql="SELECT *, count(*) as TOT_ROWS FROM $tabla_serv WHERE num_fac_ven=$num_fac AND prefijo='$pre' $filtroHash AND cod_su=$nit GROUP BY serial_art_fac";

$rs=$linkPDO->query($sql);
$excentas=0;

$anchobanda="";
$tipo_cliente="";
$estratoPlan="";
$valor_mora=0;
$INTERESES_MORA=0;
while($rowINI=$rs->fetch())
{

	$serv=$rowINI["serv"];
	$nota=": ".$rowINI["nota"];
	$idServ=$rowINI["id_serv"];
	$codServ=$rowINI["cod_serv"];
	$ivaServ=$rowINI["iva"];



	$pvpServ = $rowINI["pvp"]/(1+$ivaServ/100);
	if($ver_pvp_sin_iva==0 || $MODULES["PVP_COTIZA"]==1)$pvpServ = $rowINI["pvp"];
	$nomTec=getTec($rowINI["id_tec"]);

	if($ivaServ==0)	{$excentas+=$pvpServ;}

	if(!empty($rowINI["anchobanda"])){$anchobanda=$rowINI["anchobanda"];}
	if(!empty($rowINI["tipo_cliente"])){$tipo_cliente=$rowINI["tipo_cliente"];}
	if(!empty($rowINI["estrato"])){$estratoPlan=$rowINI["estrato"];}

	//echo " TOTAL_REGISTROS $TOTAL_REGISTROS<br>";

	$CONCEPTO_SERV="";
	$valor_afiliacion=0;
	  $CONCEPTO_SERV="";
	//$valorPlan=$rowINI["pvp"];



	$respBusq=strpos($serv,"AFILIACION");
	$valor_mora=0;
	if($respBusq===0){
		//echo "ENTRO, AFILIACION ";
	$CONCEPTO_SERV="AFILIACI&Oacute;N";
	$valorPlan=$rowINI["nota"];
	$valorPlanFacturado=0;
	$ivaPlanFacturado=$rowINI["iva"];
	$valor_afiliacion=$rowINI["pvp"]/(1+$ivaPlanFacturado/100);

	$PERIODO_FACTURADO="AFILIACI&Oacute;N";
	}
	//if($TOTAL_REGISTROS<=1)
	else if($TOTAL_REGISTROS<=1){
	//echo "ENTROOO else TOTAL_REGISTROS $TOTAL_REGISTROS";
	$CONCEPTO_SERV="";
	//$valorPlan=$rowINI["pvp"];
	$valorPlan=$rowINI["nota"];
	$valorPlanFacturado=$rowINI["pvp"]/(1+$ivaServ/100);
	$ivaPlanFacturado=$rowINI["iva"];
	$valor_afiliacion=0;
	$PERIODO_FACTURADO="$periodoIni a $periodoFin";
	}

	//echo "<li>serv: $serv </li>";
	$respBusq=strpos($serv,"PLAN INTERNET");
	if($respBusq===0){
	//echo "ENTRO, PLAN INTERNET ";
	$CONCEPTO_SERV=" ";
	$valorPlan=$rowINI["nota"];
	if($valorPlan==0)$valorPlan=$rowINI["pvp"]/(1+$ivaServ/100);
	$valorPlanFacturado=$rowINI["pvp"]/(1+$ivaServ/100);
	$ivaPlanFacturado=$rowINI["iva"];
	$valor_afiliacion=0;
	$PERIODO_FACTURADO="$periodoIni a $periodoFin";
	$anchobanda=$rowINI["anchobanda"];
	$tipo_cliente=$rowINI["tipo_cliente"];
	$estratoPlan=$rowINI["estrato"];


	}

	$respBusq=strpos($serv,"INTERES MORA");
	if($respBusq===0){
	//echo "ENTRO, PLAN INTERNET ";
	$INTERESES_MORA=round($rowINI["pvp"]/(1+$ivaServ/100),2);



	}
	//echo "<li>valorPlan $valorPlan,valorPlanFacturado $valorPlanFacturado,  </li>";
}

}

$fechaUltimoPago="";
$valorUltimoPago=0;
$saldoPeriodoAnt=0;

$sql="SELECT *,DATE(fecha) AS fecha2 FROM comprobante_ingreso WHERE id_cli='$ced' AND anulado!='ANULADO' AND fecha < '$fecha' ORDER BY fecha DESC LIMIT 1";
//echo "$sql<br>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){

$FechaC=$row["fecha2"];
$num_comp=$row["num_com"];
$STATS_PAGOS=tot_saldo2($ced, $fechaNormal,$num_comp);
 //echo "fecha $fechaNormal saldo: ".$STATS_PAGOS["saldo"]." abonos:".$STATS_PAGOS["abono"]." tot:".$STATS_PAGOS["tot"] ;
$fechaUltimoPago=$FechaC;
$valorUltimoPago=$row["valor"];
$ifRes=($STATS_PAGOS["saldo"]==0);
$saldo=$STATS_PAGOS["saldo"];
if($saldo===0){$STATS_PAGOS=tot_abon_cre($ced);
$saldoPeriodoAnt=$saldo ;
//echo "--if2 saldoCero:".$saldo."if: $ifRes<br>".gettype($saldo);
}else {$saldoPeriodoAnt=$STATS_PAGOS["saldo"] ;}


//echo "entra cartera: $STATS_PAGOS[saldo]";
}else{

$num_comp=0;
$STATS_PAGOS=tot_saldo2($ced, $fechaNormal,$num_comp);

$fechaUltimoPago="0000-00-00";
$valorUltimoPago="0";
$saldoPeriodoAnt=$STATS_PAGOS["saldo"];
//echo "entra cartera else: $STATS_PAGOS[saldo]";
}

$DCTO_IVA=$DCTO/(1+($ivaPlanFacturado/100));
if($saldoPeriodoAnt<0){$saldoPeriodoAnt=0;}
//echo "saldoPeriodoAnt $saldoPeriodoAnt";
if($valorPlanFacturado>0){$valorPlanFacturado=$valorPlanFacturado+$DCTO_IVA;}
if($valor_afiliacion>0){$valor_afiliacion= $valor_afiliacion + $DCTO_IVA;}
$TOTAL_PAGAR=$TOT+$saldoPeriodoAnt;
?>
<!-- inicio body -->

<div style="width:13.2cm; height:20cm; border: double; border-width:thick;border-width:9px 9px 9px 9px; border-color:#063A9B !important;" class=" uk-grid  uk-grid-collapse uk-width-1-2" data-uk-grid-margin>

<div class="uk-grid  uk-grid-collapse uk-width-1-1 label_titulo" style=" font-size:8px; font-weight:bold" data-uk-grid-margin>
 &nbsp;Vigilado por la superintendencia de industria y comercio tel. (1) 5920400 contactenos@sic.gov.co Cra 13 No 27-00 Bogot&aacute; D.C
</div>


<div class="uk-grid  uk-grid-small uk-width-1-1" data-uk-grid-margin style="height:140px;vertical-align:top;">
<div class="uk-width-1-2 " >
<img src="<?php echo $url_LOGO_A ?>" width="<?php echo $X ?>" height="<?php echo "80px" ?>">
</div>
<div class="uk-width-1-2 " >
<?php  echo $PUBLICIDAD ?>
<p style="font-size:9px;line-height:10px;" align="right" class="<?php if($usar_iva!=1){echo "uk-hidden";}?>">
Facturaci&oacute;n a computador
<br>

Autorizaci&oacute;n numeraci&oacute;n de Facturaci&oacute;n Dian No. <?php echo $Resol ?> <br>
Del <?php echo $FechaResol ?> &nbsp;
<?php echo "Numeraci&oacute;n $Rango" ?>


<h1 style="font-size:41px;font-weight:bold;color:rgb(204,0,0) !important; font-family:'Arial Black', Gadget, sans-serif; z-index:999;opacity:0.61;-webkit-transform:matrix(1.9, -0.9, 1.9, 2.1, -107, 273);-moz-transform:matrix(1.9, -0.9, 1.9, 2.1, -107, 273);
-o-transform:matrix(1.9, -0.9, 1.9, 2.1, -107, 273);-ms-transform:matrix(1.9, -0.9, 1.9, 2.1, -107, 273);transform:matrix(1.9, -0.9, 1.9, 2.1, -107, 273);"><?php if($estado=="ANULADO"){echo "$estado";}?></h1>
</p>
</div>
</div>

<div class="uk-grid  uk-grid-collapse uk-width-1-1 barra_negra" style="line-height:24px;" align="center" data-uk-grid-margin>
<?php echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $dirSuc $munSuc- $depSuc TELEFONO: $tel1Su &nbsp; &nbsp; &nbsp; ";?>
</div>

<div class="uk-grid  uk-grid-collapse uk-width-1-1" style="line-height:14px;" align="center" data-uk-grid-margin>
<?php echo "Ruta: $Ruta $OrdenRuta ";?>
</div>

<div class="uk-grid  uk-grid-collapse uk-width-1-1" style="height:30px;" data-uk-grid-margin>
<div class="uk-width-1-2 " style="" >
<br />
<table class="sub_table" width="100%">
<tr>
<td class="">FECHA EMISI&Oacute;N:</td>
<td><?php echo "$fecha";?> </td>
</tr>
</table>
</div>

<div class="uk-width-1-2 "  align="center">
<br />
<table class="sub_table" width="100%">
<tr>
<td class="label_titulo">FACTURA DE VENTA N&deg;</td>
<td> <b><?php echo "$pre $num_fac";?></b></td>
</tr>
</table>
</div>
</div>

<div class="uk-grid uk-grid-small  uk-width-1-1" style="" data-uk-grid-margin>
<div class="uk-width-1-2 " align="right" >
<br />
<table class="sub_table" width="210px;" align="right">
<thead>
<tr>
<td class="label_titulo" colspan="2">DATOS DEL CLIENTE</td>
</tr>
</thead>
<tbody>
<tr>
<td>NOMBRE: </td><td><?php echo "$cliente"; ?></td>
</tr>
<tr>
<td>CEDULA: </td><td><?php echo "$ced"; ?></td>
</tr>
<tr>
<td>TELEFONO: </td><td><?php echo "$tel"; ?></td>
</tr>
<tr>
<td>TIPO SERVICIO: </td><td><?php echo "$tipo_cliente"; ?></td>
</tr>
<tr>
<td>ESTRATO: </td><td><?php echo "$estratoPlan"; ?></td>
</tr>
<tr>
<td>DIRECCI&Oacute;N: </td><td><?php echo "$dir"; ?></td>
</tr>
<tr>
<td>BARRIO: </td><td><?php echo "$barrio"; ?></td>
</tr>
<tr>
<td>MUNICIPIO: </td><td><?php echo "$ciudad"; ?></td>
</tr>
</tbody>
</table>
<br>
</div>

<div class="uk-width-1-2 "  align="right"  >
<br />
<table class="sub_table" width="230">
<tr>
<td class="label_titulo" colspan="2" align="center">PERIODO FACTURADO</td>
</tr>
<tr>
<td class="" colspan="2" align="center"><?php echo "$PERIODO_FACTURADO";?></td>
</tr>
<tr>
<td class="label_titulo" colspan="2" align="center">DETALLE FACTURADO</td>
</tr>
<tr>
<td><b>PLAN</b></td><td><?php echo "$anchobanda";?></td>
</tr>
<tr>
<td><b>VALOR PLAN</b></td><td><?php echo money($valorPlan);?></td>
</tr>
</table>
</div>
</div>

<div class="uk-grid  uk-grid-small data-uk-grid-margin uk-width-1-1" style="" data-uk-grid-margin>
<div class="uk-width-1-2 "  align="">
<table class="sub_table" width="210" align="right">
<tr>
<td class="label_titulo" colspan="2" align="center">PAGOS REALIZADOS</td>
</tr>
<tr>
<td class="" colspan="" align=""><B>VALOR PAGADO</B></td><td><?php echo money3($valorUltimoPago);?></td>
</tr>
<tr>

</tr>
<tr>
<td><b>FECHA ULTIMO PAGO</b></td><td><?php echo "$fechaUltimoPago";?></td>
</tr>
</table>
<div class="uk-grid  uk-grid-collapse uk-width-1-2"    align="center">
<p class="vertical-text">- NANIMO SOFTWARE 1.120.359.931-0 -</p>
</div>
</div>
<div class="uk-width-1-2 "  align="center">
<table class="sub_table" width="230" height="">
<tr>
<td class="label_titulo" colspan="2" align="center">CARGOS FACTURADOS</td>
</tr>

<tr>
<td><b>PERIDODO ACTUAL</b></td><td></td>
</tr>
<tr>
<td>AFILIACION</td><td><?php echo money3($valor_afiliacion);?></td>
</tr>
<tr>
<td>SER. INTERNET</td><td><?php echo money3($valorPlanFacturado);?></td>
</tr>
<tr>
<td>INTERESES DE MORA</td><td><?php echo money3($INTERESES_MORA);?> </td>
</tr>
<tr>
<td>OTROS</td><td>$- </td>
</tr>
<tr>
<td>DESCUENTO</td><td><?php echo money3($DCTO);?> </td>
</tr>
<tr>
<td><b>SUBTOTAL</b></td><td><b><?php echo money3($SUB);?></b></td>
</tr>
<tr>
<td>IVA 19%</td><td><?php echo money3($IVA);?></td>
</tr>
<tr>
<td><b>TOTAL FACTURADO</b></td><td><?php echo money3($TOT_PAGAR);?></td>
</tr>
<tr>
<td>SALDO PERIODO ANTERIOR</td><td><?php echo money3($saldoPeriodoAnt);?></td>
</tr>



<tr>
<td><b>TOTAL A PAGAR</b></td><td><?php echo money3($TOT_PAGAR+$saldoPeriodoAnt);?></td>
</tr>

</table>
</div>
</div>

<div class="uk-grid  uk-grid-small data-uk-grid-margin uk-width-1-1" style="" data-uk-grid-margin>
<div class="uk-width-1-2 "  align="">

</div>

<div class="uk-width-1-2 "  align="">
<table class="sub_table" width="230">
<tr>
<td class="label_titulo_warning" colspan="2" align="center">FECHA LIMITE PAGO</td>
</tr>
<tr>
<td class="" colspan="2" align="center"><?php echo "$fechaLIM";?></td>
</tr>
<tr>
<td class="label_titulo_warning" colspan="2" align="center">FECHA SUSPENSI&Oacute;N</td>
</tr>
<tr>
<td class="" colspan="2" align="center"><?php echo "$fechaSUS";?></td>
</tr>
</table>
</div>
</div>

<!--
<div class="uk-grid  uk-grid-collapse uk-width-1-1" style="height:5%;" data-uk-grid-margin>
</div>
-->

<div class="uk-grid  uk-grid-collapse uk-width-1-1" style=" " data-uk-grid-margin>
<b> &nbsp;Esta factura de venta es un t&iacute;tulo valor conforme al Art. 1 de la ley  1231 del 17 de julio de 2008</b>
</div>

<div class="uk-grid  uk-grid-collapse uk-width-1-1 label_titulo" style=" font-size:20px; font-weight: bolder; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif" data-uk-grid-margin>
 &nbsp;L&iacute;nea gratuita, quejas y reclamos 018000944473
</div>

</div>
<?php

};


function imprimir_fac_custom2($num_fac,$pre,$hash,$nit){


global $vista_remi,$MODULES,$usar_fracciones_unidades,$usar_remision,$usar_firmas_factura,$Variable_size_imp_carta,$codPapelSuc,$SEDES,$usar_costo_remi,$usar_iva,$ver_pvp_sin_iva,$usar_fracciones_unidades,$X_fac,$url_LOGO_A,$X,$Y,$PUBLICIDAD,$CHAR_SET,$REGIMEN,$impuesto_consumo,$dirSuc,$munSuc,$depSuc,$tel1Su,$limite_pago_facturas,$dia_suspension;
global $linkPDO,$vencimiento_meses_resol_dian;
$filtroHash=" AND hash='$hash'";
if(empty($hash) || $hash==0)$filtroHash="";

$tabla_fac="fac_venta";
$tabla_art="art_fac_ven";
$tabla_serv="serv_fac_ven";
$HEADER_FAC="Factura de Venta";



$sql="SELECT *,DATE(fecha) as fecha_n  FROM $tabla_fac WHERE num_fac_ven='$num_fac' AND prefijo='$pre' $filtroHash AND nit=$nit";
//echo "$sql";


$rs=$linkPDO->query($sql);

if($row=$rs->fetch()){


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
	$cliente = $row["nom_cli"]." ".$row["snombr"]." ".$row["apelli"];
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
	$date = date_create($row['fecha_resol']);
	date_add($date, date_interval_create_from_date_string($vencimiento_meses_resol_dian.' MONTH'));
	$FechaResol=$row['fecha_resol']." a ".date_format($date, 'Y-m-d');
	$Rango=$row['rango_resol'];
	$barrio=$row['loccli'];
$R_FTE=$row['r_fte'];
$R_IVA=$row['r_iva'];
$R_ICA=$row['r_ica'];
$IMP_CONSUMO=$row['imp_consumo'];
$IMP_BOLSAS=$row['impuesto_bolsas'];
$TOT_PAGAR=$TOT+$IMP_BOLSAS-($R_FTE+$R_ICA+$R_IVA);
$estadoCre=$row['estado'];
if($estadoCre=="PAGADO"){$estadoCre='<span class="uk-badge uk-badge-success" style="font-size:18px;">PAGADO</span>';}
$pagare=$row['num_pagare'];
$sedeDest=$row['sede_destino'];
$sedeOrig=$row['nit'];


$placa=$row["placa"];
$vehi=vehi($placa);


$fechaNormal=$row["fecha_n"];

$mes=date("m",strtotime($fechaNormal));
$year=date("Y",strtotime($fechaNormal));
$lastDay=date("t",strtotime($fechaNormal));

$periodoIni="$year-$mes-01";
$periodoFin="$year-$mes-$lastDay";

$fechaLIM=$limite_pago_facturas;
$fechaSUS=$dia_suspension;


if($codigo_venta==$codPapelSuc){$HEADER_FAC="<b>ORDEN DE ENTREGA</b>";}

$sql="SELECT * FROM $tabla_serv WHERE num_fac_ven=$num_fac AND prefijo='$pre' $filtroHash AND cod_su=$nit";
$rs=$linkPDO->query($sql);
$excentas=0;

$anchobanda="";
$tipo_cliente="";
$estratoPlan="";
while($rowINI=$rs->fetch())
{

	$serv=$rowINI["serv"];
	$nota=": ".$rowINI["nota"];
	$idServ=$rowINI["id_serv"];
	$codServ=$rowINI["cod_serv"];
	$ivaServ=$rowINI["iva"];

	$pvpServ = $rowINI["pvp"]/(1+$ivaServ/100);
	if($ver_pvp_sin_iva==0 || $MODULES["PVP_COTIZA"]==1)$pvpServ = $rowINI["pvp"];
	$nomTec=getTec($rowINI["id_tec"]);

	if($ivaServ==0)
	{$excentas+=$pvpServ;}

	if(!empty($rowINI["anchobanda"])){$anchobanda=$rowINI["anchobanda"];}
	if(!empty($rowINI["tipo_cliente"])){$tipo_cliente=$rowINI["tipo_cliente"];}
	if(!empty($rowINI["estrato"])){$estratoPlan=$rowINI["estrato"];}

	$CONCEPTO_SERV="$serv";
	$valor_afiliacion=0;
	$respBusq=strpos($serv,"AFILIACION");
	$PERIODO_FACTURADO="$periodoIni a $periodoFin";
	if($respBusq===0){
	$CONCEPTO_SERV="AFILIACI&Oacute;N";
	$valorPlan=$rowINI["nota"];
	$valorPlanFacturado=0;
	$valor_afiliacion=$rowINI["pvp"];
	$PERIODO_FACTURADO="AFILIACI&Oacute;N";



	}
	else{
	$CONCEPTO_SERV="$serv";
	$valorPlan=$rowINI["pvp"];
	$valorPlanFacturado=$rowINI["pvp"];
	$valor_afiliacion=0;
	$PERIODO_FACTURADO="$periodoIni a $periodoFin";
	}
}

}

$fechaUltimoPago="";
$valorUltimoPago=0;
$saldoPeriodoAnt=0;

$sql="SELECT *,DATE(fecha) AS fecha2 FROM comprobante_ingreso WHERE id_cli='$ced' ORDER BY fecha DESC LIMIT 1";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$FechaC=$row["fecha2"];
$num_comp=$row["num_com"];
$STATS_PAGOS=tot_saldo($ced,$FechaC,$num_comp);

$fechaUltimoPago=$FechaC;
$valorUltimoPago=$row["valor"];
$saldoPeriodoAnt=$STATS_PAGOS["saldo"];
}else{


$STATS_PAGOS=tot_abon_cre($ced);

$fechaUltimoPago="0000-00-00";
$valorUltimoPago="0";
$saldoPeriodoAnt=$STATS_PAGOS["saldo"] -$TOT_PAGAR;
}

$TOTAL_PAGAR=$TOT+$saldoPeriodoAnt;
?>
<!-- inicio body -->

<div style="width:13.2cm; height:20cm; border: double; border-width:thick; border-width:6px; border-color:#063A9B !important;" class=" uk-grid  uk-grid-collapse uk-width-1-2" data-uk-grid-margin>

<div class="uk-grid  uk-grid-collapse uk-width-1-1 label_titulo" style=" font-size:8px; font-weight:bold" data-uk-grid-margin>
 &nbsp;
</div>


<div class="uk-grid  uk-grid-small uk-width-1-1" data-uk-grid-margin style="height:140px;vertical-align:top;">
<div class="uk-width-1-2 " >
<img src="<?php echo $url_LOGO_A ?>" width="<?php echo $X ?>" height="<?php echo "80px" ?>">
</div>
<div class="uk-width-1-2 " >
<?php  echo $PUBLICIDAD ?>
<p style="font-size:9px;line-height:10px;" align="right" class="<?php if($usar_iva!=1){echo "uk-hidden";}?>">
Facturaci&oacute;n a computador
<br>
Autorizaci&oacute;n numeraci&oacute;n de Facturaci&oacute;n Dian No. <?php echo $Resol ?> <br>
Del <?php echo $FechaResol ?> &nbsp;
<?php echo "Numeraci&oacute;n $Rango" ?>
</p>
</div>
</div>

<div class="uk-grid  uk-grid-collapse uk-width-1-1 barra_negra" style="line-height:24px;" align="center" data-uk-grid-margin>
<?php echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $dirSuc $munSuc- $depSuc TELEFONO: $tel1Su";?>
</div>

<div class="uk-grid  uk-grid-collapse uk-width-1-1" style="height:30px;" data-uk-grid-margin>
<div class="uk-width-1-2 " style="" >
<table class="sub_table" width="100%">
<tr>
<td class="">FECHA EMISI&Oacute;N;</td>
<td><?php echo "$fecha";?> </td>
</tr>
</table>
</div>

<div class="uk-width-1-2 "  align="center">
<table class="sub_table" width="100%">
<tr>
<td class="label_titulo">FACTURA DE VENTA N&deg;</td>
<td> <b><?php echo "$pre $num_fac";?></b></td>
</tr>
</table>
</div>
</div>

<div class="uk-grid uk-grid-small  uk-width-1-1" style="" data-uk-grid-margin>
<div class="uk-width-1-2 " >
<table class="sub_table" width="250px;">
<thead>
<tr>
<td class="label_titulo" colspan="2">DATOS DEL CLIENTE</td>
</tr>
</thead>
<tbody>
<tr>
<td>NOMBRE: </td><td><?php echo "$cliente"; ?></td>
</tr>
<tr>
<td>CEDULA: </td><td><?php echo "$ced"; ?></td>
</tr>
<tr>
<td>TELEFONO: </td><td><?php echo "$tel"; ?></td>
</tr>
<tr>
<td>TIPO SERVICIO: </td><td><?php echo "$tipo_cliente"; ?></td>
</tr>
<tr>
<td>ESTRATO: </td><td><?php echo "$estratoPlan"; ?></td>
</tr>
<tr>
<td>DIRECCI&Oacute;N: </td><td><?php echo "$dir"; ?></td>
</tr>
<tr>
<td>BARRIO: </td><td><?php echo "$barrio"; ?></td>
</tr>
<tr>
<td>MUNICIPIO: </td><td><?php echo "$ciudad"; ?></td>
</tr>
</tbody>
</table>
<br>
</div>

<div class="uk-width-1-2 "  align="center">
<table class="sub_table" width="220">
<tr>
<td class="label_titulo" colspan="2" align="center">PERIODO FACTURADO</td>
</tr>
<tr>
<td class="" colspan="2" align="center"><?php echo "$PERIODO_FACTURADO";?></td>
</tr>
<tr>
<td class="label_titulo" colspan="2" align="center">DETALLE FACTURADO</td>
</tr>
<tr>
<td width="70px"><b>PLAN</b></td><td><?php echo "$CONCEPTO_SERV";?></td>
</tr>
<tr>
<td><b>VALOR PLAN</b></td><td><?php echo money($valorPlan);?></td>
</tr>
</table>
</div>
</div>

<div class="uk-grid  uk-grid-small data-uk-grid-margin uk-width-1-1" style="" data-uk-grid-margin>
<div class="uk-width-1-2 "  align="">
<table class="sub_table" width="230">
<tr>
<td class="label_titulo" colspan="2" align="center">PAGOS REALIZADOS</td>
</tr>
<tr>
<td class="" colspan="" align=""><B>VALOR PAGADO</B></td><td><?php echo money3($valorUltimoPago);?></td>
</tr>
<tr>

</tr>
<tr>
<td><b>FECHA ULTIMO PAGO</b></td><td><?php echo "$fechaUltimoPago";?></td>
</tr>
</table>
</div>
<div class="uk-width-1-2 "  align="center">
<table class="sub_table" width="230" height="">
<tr>
<td class="label_titulo" colspan="2" align="center">CARGOS FACTURADOS</td>
</tr>

<tr>
<td><b>PERIDODO ACTUAL</b></td><td></td>
</tr>
<tr>
<td>AFILIACION</td><td><?php echo money3($valor_afiliacion);?></td>
</tr>
<tr>
<td>SERVICIO</td><td><?php echo money3($valorPlanFacturado);?></td>
</tr>
<tr>
<td><b>SUBTOTAL</b></td><td><b><?php echo money3($SUB);?></b></td>
</tr>
<tr>
<td>IVA 19%</td><td><?php echo money3($IVA);?></td>
</tr>
<tr>
<td><b>TOTAL FACTURADO</b></td><td><?php echo money3($TOT_PAGAR);?></td>
</tr>
<tr>
<td>SALDO PERIODO ANTERIOR</td><td><?php echo money3($saldoPeriodoAnt);?></td>
</tr>

<tr>
<td>OTROS</td><td>$- </td>
</tr>


</table>
</div>
</div>

<div class="uk-grid  uk-grid-small data-uk-grid-margin uk-width-1-1" style="" data-uk-grid-margin>
<div class="uk-width-1-2 "  align="">

</div>

<div class="uk-width-1-2 "  align="">
<table class="sub_table" width="230">
<tr>
<td class="label_titulo_warning" colspan="2" align="center">FECHA LIMITE PAGO</td>
</tr>
<tr>
<td class="" colspan="2" align="center"><?php echo "$fechaLIM";?></td>
</tr>
<tr>
<td class="label_titulo_warning" colspan="2" align="center">FECHA SUSPENSI&Oacute;N</td>
</tr>
<tr>
<td class="" colspan="2" align="center"><?php echo "$fechaSUS";?></td>
</tr>
</table>
</div>
</div>

<!--
<div class="uk-grid  uk-grid-collapse uk-width-1-1" style="height:5%;" data-uk-grid-margin>
</div>
-->
<div class="uk-grid  uk-grid-collapse uk-width-1-1" style=" " data-uk-grid-margin>
<b> &nbsp;Esta factura de venta es un t&iacute;tulo valor conforme al Art. 1 de la ley  1231 del 17 de julio de 2008</b>
</div>
<div class="uk-grid  uk-grid-collapse uk-width-1-1"    align="center">
<p class="pie_pagina_nanimo"> &nbsp;NANIMO SOFTWARE NIT: 1.120.359.931-0</p>
</div>
<div class="uk-grid  uk-grid-collapse uk-width-1-1 label_titulo" style=" font-size:20px; font-weight: bolder; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif" data-uk-grid-margin>
 &nbsp;<!-- PIE DE PAG -->
</div>
</div>
<?php


}
?>
