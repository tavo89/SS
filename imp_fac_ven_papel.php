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
$tabla_serv="serv_fac_remi";
$HEADER_FAC="<b>DEVOLUCION VENTA</b>";	
}
else{
$tabla_fac="fac_venta";
$tabla_art="art_fac_ven";
$tabla_serv="serv_fac_ven";
$HEADER_FAC="Factura de Venta";	
if($usar_remision==1){$HEADER_FAC="REMISION";}
}





$rsCount=$linkPDO->query("SELECT COUNT(*) AS nf FROM $tabla_art  WHERE num_fac_ven='$_SESSION[n_fac_ven]' AND prefijo='$_SESSION[prefijo]' AND  nit=$nit" );

$rowCount=$rsCount->fetch();
$totArts=$rowCount["nf"];


if($Variable_size_imp_carta==1){$X_fac="11cm";}

if($totArts>=16)$X_fac="27.9cm";

?>

<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>Factura <?php echo $_SESSION['n_fac_ven'] ?></title>
 <?php 
//require_once("HEADER_UK.php"); 

require_once("IMP_HEADER.php"); 

?>
<style type="text/css">
@media print
{
      .page-break  { display:block; page-break-before:always; }

    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }

    body 
    {
        background-color:#FFFFFF; 
       /** border: solid 1px black ;**/
        margin: 0px;  /* this affects the margin on the content before sending to printer */
   }
}

</style>
</head>

<body  >

<?php

$num_fac=$_SESSION['n_fac_ven'];
$pre=$_SESSION['prefijo'];
$hash=s('hashFacVen');

$filtroHash=" AND hash='$hash'";
if(empty($hash) || $hash==0)$filtroHash="";
 

$rs=$linkPDO->query("SELECT *, DATE(fecha) as fe FROM $tabla_fac WHERE num_fac_ven='$num_fac' AND prefijo='$pre' $filtroHash AND nit=$nit");

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
	$fecha = $row["fe"];
		$kkk=preg_split("[-]",$fecha);
	$dia=$kkk[2];
	$mes=$kkk[1];
	$ano=$kkk[0];
	
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
$TOT_PAGAR=$TOT+$IMP_BOLSAS-($R_FTE+$R_ICA+$R_IVA);
$estadoCre=$row['estado'];
if($estadoCre=="PAGADO"){$estadoCre='<span class="uk-badge uk-badge-success" style="font-size:18px;">PAGADO</span>';}
$pagare=$row['num_pagare'];
$sedeDest=$row['sede_destino'];
$sedeOrig=$row['nit'];


$placa=$row["placa"];
$vehi=vehi($placa);
$num_nota_entrega=$row["num_pagare"];
$formaPago=$row["tipo_venta"];


if($codigo_venta==$codPapelSuc){$HEADER_FAC="<b>ORDEN DE ENTREGA</b>";}


$url_img="Imagenes/FACTURA_ARAUCA.jpg";
if($codSuc!=1){$url_img="Imagenes/FORMATO_FACTURA_OTRAS.png";
header("location: imp_fac_ven_papel2.php");
}
?>


<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute; background-image: url(Imagenes/FACTURA_ARAUCA.jpg); font-weight:bold;">

<div style="position:absolute; left: 311px; top: 599px;" >
 
</div>





<div style="position: absolute; top:192px; left:65px; width: 0px;" ><b><?php echo "$dia" ?></b></div>
<div style="position: absolute; top:192px; left:131px" ><b><?php echo "$mes" ?></b></div>          
<div style="position: absolute; top:192px; left:196px" ><b><?php echo "$ano" ?></b></div>

<div style="position: absolute; top:225px; left: 200px;" ><?php echo "$cliente" ?></div>
<div style="position: absolute; top:225px; left: 629px;" ><?php echo "$ced" ?></div>
<div style="position: absolute; top:247px; left: 629px;" ><?php echo "$tel" ?></div>
<div style="position: absolute; top:247px; left: 200px;" ><?php echo "$dir" ?></div>
<!--<div style="position: absolute; top:336px; left: 220px;" ><?php echo "$ciudad" ?></div>-->
<div style="position: absolute; top:317px; left: 200px;" ><?php echo "$formaPago" ?></div>


  <?php
 $s="SELECT * FROM $tabla_art  WHERE num_fac_ven=$num_fac AND prefijo='$pre' $filtroHash AND nit=$nit ORDER BY des";

$rs=$linkPDO->query("$s" );
 
 $cont=0;
 $iva_show=0;
  $excentas=0;
 $iva0=1000;
 $infoAdd="";
 //$SUB=0;
 //$IVA=0;
 $SUB_SIMPLIFICADO=0;
 $limitPage=45;
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
	if($ver_pvp_sin_iva==0 || $MODULES["PVP_COTIZA"]==1)$pvp = $row["precio"];
	$sub_tot = $pvp*$factor;
	$descuento =$row["dcto"]*1;
	$IMEI=$row['talla'];
	$SN=$row['serial'];
	$presentacion = $row["presentacion"];
	if(empty($presentacion)){$presentacion="";}
	$color=$row['color'];
	$num_motor=$row["num_motor"];
	//$SUB+=$pvp*$factor;
	
	$Sql="SELECT * FROM inv_inter WHERE id_pro='$ref' AND id_inter='$codBarras' AND fecha_vencimiento='$feVence' AND nit_scs='$sedeOrig'";
	//echo "<li>$Sql</li>";
	$Rs=$linkPDO->query($Sql);
	$Row=$rs->fetch();
	
	$pvpTras=$Row["precio_v"];
	$cod_garantia = $row["cod_garantia"];
	if(!empty($cod_garantia))$infoAdd.="<br>T. Garantia: $cod_garantia";
if(!empty($SN))$infoAdd.="<br>S/N: $SN";
		$linea="";
		$fabricante="";
		$modelo="";
		$color="";
		$cilindraje="";
		$chasis="";
		$consecutivo_proveedor="";
	if(!empty($num_motor)){
		 
		$sql="SELECT * FROM art_fac_com WHERE num_motor='$num_motor'";
		$rs2=$linkPDO->query($sql);
		if($row2=$rs2->fetch()){
		$linea=$row2["linea"];
		$fabricante=$row2["fabricante"];
		$modelo=$row2["modelo"];
		$color=$row2["color"];
		$cilindraje=$row2["cilindraje"];
		$chasis=$row2["num_chasis"];
		$consecutivo_proveedor=$row2["consecutivo_proveedor"];
		
 
		}
		
		}

$des.="$infoAdd";
	if($iva==0)
	{
		$excentas+=$pvp*$factor;
		$iva0=0;
	}
	$SUB_SIMPLIFICADO+=$pvp*$factor;
	
	$dim=0;
 $top=230;
  ?> 
<div style="position:absolute; left:66px; top:381px;">1</div>
<div style="position:absolute; left:563px; top:381px;"><?php echo money($pvp) ?></div>
<div style="position:absolute; left:694px; top:381px;"><?php echo money($sub_tot) ?></div>  

<div style="position:absolute; left:124px; top:381px;">Tipo de Veh&iacute;culo:</div>
<div style="position:absolute; left:264px; top:381px;"><?php echo "MOTOCICLETA" ?></div>

<div style="position:absolute; left:124px; top:407px;">Marca:</div>
<div style="position:absolute; left:264px; top:407px;"><?php echo $fabricante ?></div>

<div style="position:absolute; left:124px; top:434px;">Linea:</div>
<div style="position:absolute; left:264px; top:434px;"> <?php echo "$linea" ?></div>

<div style="position:absolute; left:124px; top:459px;">Modelo:</div>
<div style="position:absolute; left:264px; top:459px;"><?php echo "$modelo" ?></div>

<div style="position:absolute; left:124px; top:487px;">Color:</div>
<div style="position:absolute; left:264px; top:487px;"><?php echo $color ?></div>


<div style="position:absolute; left:124px; top:513px;">Numero de Motor:</div>
<div style="position:absolute; left:264px; top:513px;"><?php echo $num_motor ?></div>

<div style="position:absolute; left:124px; top:539px;">Numero de Chasis:</div>
<div style="position:absolute; left:264px; top:539px;"><?php echo "$chasis" ?></div>

<div style="position:absolute; left:124px; top:564px;">VIN:</div>
<div style="position:absolute; left:264px; top:564px;"><?php echo "$chasis" ?></div>

<div style="position:absolute; left:124px; top:590px;">Cilindraje:</div>
<div style="position:absolute; left:264px; top:590px;"><?php echo "$cilindraje" ?></div>


<div style="position:absolute; left:124px; top:614px;">Consecutivo:</div>
<div style="position:absolute; left:264px; top:614px;"><?php echo "$consecutivo_proveedor" ?></div>

<div style="position:absolute; left:124px; top:643px;">Placa:</div>
<div style="position:absolute; left:264px; top:643px;"><?php echo "$placa" ?></div>
<?php
$cont++;
  }//fin while
$excentas=redondeo($excentas);
?> 



<div style="position: absolute; left:145px; top:958px; z-index:2; font-size:12px; width:400PX"><?php //echo $val_let ?></div>
<div style="position: absolute; left:657px; top:879px; z-index:2"><?php echo money($SUB*1) ?></div>
<div style="position:absolute; left:657px; top:912px"><?php echo money($IVA*1) ?></div>
<div style="position:absolute; left:657px; top:944px"><?php echo money($TOT*1) ?></div>

<div style="position:absolute; left:106px; top:773px; font-size:12px;">
<?php
if($form_pa=="Credito"){
	echo "<b>CON RESERVA DE DOMINIO A NOMBRE DE <br>COMERCIAL ELECTROMUEBLES LTDA NIT: 800 186 618-1</b>";
	
	}
?>

</div>
<div id="imp"  align="center"  >

<input  type="button" value="Imprimir" name="boton" onMouseDown="imprimir();" style="font-family:\22Arial Black\22,sans-serif;background-color:rgb(26, 240, 58);border-width:7px;border-color:rgb(98, 110, 222);" />
</div> 
</div>

<?php
}

?>

<?php 
 
require_once("FOOTER_UK.php"); 
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