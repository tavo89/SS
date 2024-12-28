<?php
require_once('Conexxx.php');
$n_r=0;
$null="";
$pre="";
$nit=$_SESSION['cod_su'];

// We'll be outputting a text file
//header('Content-type: text/plain');

// It will be called report.txt
header('Content-Disposition: attachment; filename="FACTURA.txt"');
if(isset($_SESSION['n_fac_ven'])&&isset($_SESSION['prefijo']))
{


if(isset($_REQUEST['exi_ref']))$n_r=$_REQUEST['exi_ref'];

if(isset($_SESSION['anulada']))$null="<div style=\"font-size:24px;\" align=\"center\" ><b>ANULADA</b></div>";





$num_fac=$_SESSION['n_fac_ven'];
$pre=$_SESSION['prefijo'];

$rs=$linkPDO->query("SELECT *, DATE(fecha) as fe FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$nit");

if($row=$rs->fetch()){
	
    	
	$nit = $row["nit"];
	$form_pa = $row["tipo_venta"];
	$tipo_cli = $row["tipo_cli"];
	$vendedor = $row["vendedor"];
	$mecanico = $row["mecanico"];
	$cajero = $row["cajero"];
	$fecha = $row["fe"];
	$ced = $row["id_cli"];
	$cliente = $row["nom_cli"];
	$cliente=$row["nom_cli"];
	$dir = $row["dir"];
	$tel = $row["tel_cli"];
	$ciudad = $row["ciudad"];
	$val_let = $row["val_letras"];
	$SUB = $row["sub_tot"];
	$IVA = $row["iva"];
	$DCTO = $row["descuento"];
	if($DCTO<0)$DCTO=0;
	$TOT = $row["tot"];
	$entrega = htmlentities($row["entrega"]);
	$cambio = htmlentities($row["cambio"]);
	$fe_naci=htmlentities($row["fe_naci"]);
	$placa=htmlentities($row["placa"]);
	$mail=htmlentities($row["mail"]);
	$estado=$row['anulado'];
	$fecha_anulado=$row['fecha_anula'];
	$null="<div style=\"font-size:24px;\" align=\"center\" ><b>ANULADA: $fecha_anulado</b></div>";
	$ESTADO=$row['estado'];
	$anticipo=$row['abono_anti'];
	

	
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
	
	}?>
<?php  echo $PUBLICIDAD_TXT  ?>

------------------------------------
FacturaDeVenta : <?php echo "$pre - $num_fac" ?>

Forma de Pago:  <?php echo "$form_pa" ?>


Fecha:<?php echo fecha("$fecha") ?>

Cliente : <?php echo "$cliente" ?>

NIT/C.C.: <?php echo "$ced" ?>
<?php
$rs=$linkPDO->query("SELECT *,fecha_vencimiento as feven FROM art_fac_ven  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND  nit=$codSuc" );
 

 $cont=0;
 $iva_show=0;
 $Iva=0;
 $excentas=0;
 $iva0=1000;
 $infoAdd="";
 $col_1=0;
 $col_2=0;
 $col_3=0;
 $col_4=0;
 $col_5=0;
  while($row=$rs->fetch()){
	  
	$feVence = $row["feven"];
	$ref = $row["cod_barras"];
	if(strlen($ref)>$col_1)$col_1=strlen($ref);
	$des = $row["des"] ;
	if(strlen($des)>$col_2)$col_2=strlen($des);
	if($usar_fecha_vencimiento==1)$des.="";
	$iva = $row["iva"];
	if(strlen($iva)>$col_5)$col_5=strlen($iva);
	if($iva>$iva_show)$iva_show=$iva;
	$cant = $row["cant"]*1;
	
	$uni = $row["unidades_fraccion"]*1;
	
	$pvp = money($row["precio"]*1);
	if(strlen($pvp)>$col_3)$col_3=strlen($pvp);
	$sub_tot = money(redondeo($row["sub_tot"]));
	if(strlen($sub_tot)>$col_4)$col_4=strlen($sub_tot);
	$descuento = $row["dcto"]*1;
	$IMEI=$row['talla'];
	$SN=$row['color'];
	$presentacion = $row["presentacion"];
	
  }

echo "
------------------------------------
Cant.	Producto			Tot. 
------------------------------------  ";
$rs=$linkPDO->query("SELECT *,fecha_vencimiento as feven FROM art_fac_ven  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND  nit=$codSuc" );  
 while($row=$rs->fetch()){
	  
	$feVence = $row["feven"];
	$ref = $row["cod_barras"];
	
	$des = $row["des"] ;

	if($usar_fecha_vencimiento==1)$des.="";
	$iva = $row["iva"];
	if($iva>$iva_show)$iva_show=$iva;
	$cant = $row["cant"]*1;
	
	$uni = $row["unidades_fraccion"]*1;
	
	$pvp = money($row["precio"]*1);

	$sub_tot =  money(redondeo($row["sub_tot"]));

	$descuento = $row["dcto"]*1;
	$IMEI=$row['talla'];
	$SN=$row['color'];
	$presentacion = $row["presentacion"];
	
if(!empty($SN))$infoAdd.="COLOR: $SN";
if(!empty($IMEI))$infoAdd.="TALLA: $IMEI";
$des.="$infoAdd";
	if($iva==0)
	{
		$excentas+=$row["precio"]*$cant;
		$iva0=0;
	}
?> 
<?php 
$des=mb_strimwidth($des, 0, 20, ".");

$des=ajusta_texto_txt($des);
echo "
$cant	$des	$sub_tot"; 
echo "";
$cont++;
  }//fin while
$excentas=redondeo($excentas);
?> 
------------------------------------
SUB:	 		<?php echo money($SUB*1) ?>

IVA:			<?php echo money($IVA*1) ?>

Total: 	 		<?php echo money($TOT*1) ?>

Pago Efectivo:	<?php echo money($entrega*1) ?>
			
Cange :			<?php echo money($cambio*1) ?>

<?php
if($form_pa=="Credito"){
if($ESTADO=="PENDIENTE")		echo "$ESTADO";
else if($ESTADO=="PAGADO")		echo "$ESTADO";
}

if($iva0==0){
}
else{
}
?>
------------------------------------

Conserve la factura para reclamaciones
	Gracias por su compra

<?php
}
}
else{
}

?>