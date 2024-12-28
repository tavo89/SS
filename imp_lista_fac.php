<?php
require_once('Conexxx.php');
$n_r=0;
$null="";
$pre="";
$fechaFacs="";
$nit=$_SESSION['cod_su'];
$FacSup="";
$FacInf="";
$A="";
if(isset($_SESSION['fecha_facs']))$fechaFacs=$_SESSION['fecha_facs'];
if(isset($_SESSION['facInf']))$FacInf=$_SESSION['facInf'];
if(isset($_SESSION['facSup']))$FacSup=$_SESSION['facSup'];

if(!empty($FacInf) && !empty($FacSup))$A=" AND (num_fac_ven>=$FacInf AND num_fac_ven<=$FacSup)";

$sql="SELECT * FROM fac_venta WHERE nit=$codSuc AND DATE(fecha)>='$fechaFacs' AND DATE(fecha)<='$fechaFacs' $A ORDER BY num_fac_ven";
$rs=$linkPDO->query($sql);
$PRE[]="";
$NUM_FAC[]=0;
$i=0;
while($row=$rs->fetch())
{
		$PRE[$i]=$row['prefijo'];
		$NUM_FAC[$i]=$row['num_fac_ven'];
		//echo "$NUM_FAC[$i]-$PRE[$i]<br>";
		$i++;
	
}
?>

<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Facturas </title>
<!--<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />-->
<script language="javascript" type="text/javascript" src="JS/jQuery1.8.2.min.js"></script>
<script language="javascript" type="text/javascript" src="JS/num_letras.js"></script>

<script language="javascript" type="text/javascript" src="JS/fac_ven.js"></script>

<script language="javascript" type="text/javascript">
$(document).keydown(function(e) { 
  c=e.keyCode;       
    if (c == 27) {
        window.close();
    }
	else if(c == 13)imprimir();
});
function imprimir(){
//$('#imp').css('visibility','hidden');
var $bt=$('.addbtn');
$('.addbtn').detach();
window.print();
//$('#imp').css('visibility','visible');
$bt.appendTo('#imp');
};
</script>

</head>

<body style="font-family:Arial, Helvetica, sans-serif">
<form action="mod_fac_ven.php" name="form_fac" method="post" id="form-fac" >
<?php

//echo "NUM:".count($NUM_FAC);

for($i=0;$i<count($NUM_FAC);$i++)
{
//echo "<br>i: $i";

$num_fac=$NUM_FAC[$i];
$pre=$PRE[$i];

$rs=$linkPDO->query("SELECT *, DATE(fecha) as fe FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$nit");

if($row=$rs->fetch()){
	
    //$columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,cuidad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,descuento,tot,entrega,cambio,modificable,nit";
	
	$nit = htmlentities($row["nit"], ENT_QUOTES,"$CHAR_SET");
	$form_pa = htmlentities($row["tipo_venta"], ENT_QUOTES,"$CHAR_SET");
	$tipo_cli = htmlentities($row["tipo_cli"], ENT_QUOTES,"$CHAR_SET");
	$vendedor = htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET");
	$mecanico = htmlentities($row["mecanico"], ENT_QUOTES,"$CHAR_SET");
	$cajero = htmlentities($row["cajero"], ENT_QUOTES,"$CHAR_SET");
	$fecha = htmlentities($row["fe"], ENT_QUOTES,"$CHAR_SET");
	$ced = htmlentities($row["id_cli"], ENT_QUOTES,"$CHAR_SET");
	$cliente = htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET");
	$cliente=$row["nom_cli"];
	$dir = htmlentities($row["dir"], ENT_QUOTES,"$CHAR_SET");
	$tel = htmlentities($row["tel_cli"], ENT_QUOTES,"$CHAR_SET");
	$ciudad = htmlentities($row["ciudad"], ENT_QUOTES,"$CHAR_SET");
	$val_let = htmlentities($row["val_letras"], ENT_QUOTES,"$CHAR_SET");
	$SUB = htmlentities($row["sub_tot"]);
	$IVA = htmlentities($row["iva"]);
	$DCTO = htmlentities($row["descuento"]);
	$TOT = htmlentities($row["tot"]);
	$entrega = htmlentities($row["entrega"]);
	$cambio = htmlentities($row["cambio"]);
	$fe_naci=htmlentities($row["fe_naci"]);
	$placa=htmlentities($row["placa"]);
	$mail=htmlentities($row["mail"]);
	$estado=$row['anulado'];
	$fecha_anulado=$row['fecha_anula'];
	$null="<div style=\"font-size:24px;\" align=\"center\" ><b>ANULADA: $fecha_anulado</b></div>";
	$ESTADO=$row['estado'];
	

	
	if($form_pa=="Contado")
	{
	$codigo_venta="$codContadoSuc";
	$Resol=$ResolContado;
	$FechaResol=$FechaResolContado;
	$Rango=$RangoContado;
	
	}
	else if($form_pa=="Credito")
	{
		$codigo_venta="$codCreditoSuc";
		
	$Resol=$ResolCredito;
	$FechaResol=$FechaResolCredito;
	$Rango=$RangoCredito;
		
	}
	else 
	{$codigo_venta="$codContadoSuc";
	$Resol=$ResolContado;
	$FechaResol=$FechaResolContado;
	$Rango=$RangoContado;
	
	}?>


<div style="top:0px; width:100%; position: relative; font-size:10px; font-family: Arial, Helvetica, sans-serif; font-weight:bold" >
<!--<div align="center">
<img src="<?php echo $url_LOGO_A ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>">
</div>
-->
<?php  echo $PUBLICIDAD  ?>

<h2 align="center">
<?php 
if($estado=="ANULADO") echo "$null";
?>
</h2>
<hr size="2%" style="height:0px"   />
<div align="center">
FacturaDeVenta : <?php echo "$pre - $num_fac" ?>
<br />
Forma de Pago:  <?php echo "$form_pa" ?>
</div>
<table align="center" cellpadding="0" cellspacing="0">
<tr>
<td>
Fecha:</td><td> <?php echo fecha("$fecha") ?></td>
</tr>
<tr>
<td>Cliente : </td><td><?php echo "$cliente" ?></td>
</tr>
<tr>
<td>
NIT/C.C.: </td><td><?php echo "$ced" ?></td>
</tr>
<tr>
<td>
Direcci&oacute;n: </td><td><?php echo "$dir" ?></td>
</tr>
</table>

<hr size="2%" style="height:0px"  />


<table id="articulos" width="100%" cellpadding="0" cellspacing="0">
    <tr ><td colspan="6" align="center"><b>Productos</b></td></tr>
    <tr>
      <td width="10%">Cant.</td>
      <td width="20%">Cod.</td>
      <td width="40%">Art&iacute;culos</td>
      <td width="10%">Val. U</td>
      <td width="10%">Iva.</td>
      <td width="20%">Valor</td>
    </tr>
  <?php
 $rs=$linkPDO->query("SELECT * FROM art_fac_ven INNER JOIN (select id_pro,exist,nit_scs from inv_inter) AS inv ON inv.id_pro=art_fac_ven.ref  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND inv.nit_scs=art_fac_ven.nit AND inv.nit_scs=$nit");
 
 $cont=0;
 $iva_show=0;
 $Iva=0;
 $excentas=0;
 $iva0=1000;
  while($row=$rs->fetch()){
	  
	$ref = htmlentities($row["ref"], ENT_QUOTES,"$CHAR_SET");
	$des = htmlentities($row["des"], ENT_QUOTES,"$CHAR_SET");
	$iva = htmlentities($row["iva"]);
	if($iva>$iva_show)$iva_show=$iva;
	$cant = htmlentities($row["cant"]*1);
	$cantL = htmlentities($row["exist"]);
	$pvp = htmlentities($row["precio"]*1);
	$sub_tot = htmlentities($row["sub_tot"]*1);
	$descuento = htmlentities($row["dcto"]*1);
	$IMEI=$row['imei'];
	$SN=$row['serial'];
	$infoAdd="";
if(!empty($SN))$infoAdd.="<br>S/N: $SN";
if(!empty($IMEI))$infoAdd.="<BR>IMEI: $IMEI";

	if($iva==0)
	{
		$excentas+=$pvp*$cant;
		$iva0=0;
	}
	
	
  ?> 
  <tr>
      <td  align=""><?php echo $cant ?></td>
      <td ><?php echo $ref ?></td>
      <td  ><?php echo $des."$infoAdd" ?></td>
       <td  align=""><?php echo $pvp ?></td>
      <td  align=""><?php echo $iva ?></td>
      <td><?php echo money($sub_tot) ?></td>
    </tr>
  
<?php
$cont++;
  }//fin while
$excentas=redondeo($excentas);
?> 

</table>  
<hr size="2%" style="height:0px; border-style: dotted;"  />
<table id="servicios" width="100%">
    
<?php
$sql="SELECT * FROM serv_fac_ven WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$nit";
$rs=$linkPDO->query($sql);
$control=0;
while($row=$rs->fetch())
{
	if($control==0)
	{
		
		
	?>
    <tr ><td colspan="6" align="center"><b>Servicios</b></td></tr>
    <tr ><td>Servicio</td><td colspan="3" align="center">Detalles</td><td>Precio</td><td>Subtotal</td></tr>
    
    
    <?php	
	}
	$control=1;
	$iva_show=12;
	$tipo_serv=$row['man'];
	$cc=$row['cc'];
	$hh=$row['hh'];
	$frt=$row['frt'];
	$p_serv=$row['precio'];
	$p_iva_serv=$row['precio_iva'];
	

	
 ?> 
  <tr>
      
      <td ><?php echo $tipo_serv ?></td>
      
      <?php 
	  $rev="";
	  /*if(isset($row['revision']))$rev="Revisi&oacute;n ".$row['revision']."Km";
	  if($tipo_serv=="FRT")echo "<td colspan=\"2\"  >$cc</td><td>FRT:$frt HH: $hh</td>";
	  */
	  echo "<td colspan=\"3\"  >".$row['nota']."</td>"; 
	  
	  ?>
      <td><?php echo money($p_serv) ?></td>
      <td><?php echo money($p_iva_serv) ?></td>
    </tr>
  
<?php
	
}

?>
</table>
<hr size="2%" style="height:0px; border-style: dotted;"  /> 
<table align="center" width="40%" cellpadding="0" cellspacing="0">
<tr>
<td>Valor</td><td><?php echo money($SUB*1) ?></td>
</tr>
<tr>
<td><?php echo $fac_ven_etiqueta_nogravados;?></td><td><?php echo money($excentas) ?></td>
</tr>
<tr>
<td>Dcto.</td><td><?php echo money($DCTO*1) ?></td>
</tr>
<tr>
<td>I.V.A</td><td><?php echo money($IVA*1) ?></td>
</tr>
<tr>
<td>Total</td><td><?php echo money($TOT*1) ?></td>
</tr>
<tr>
<td>Pago Efectivo</td><td><?php echo money($entrega*1) ?></td>
</tr>
<tr>
<td>Cange </td><td><?php echo money($cambio*1) ?></td>
</tr>
</table>
<br />
<table align="center" width="100%">
<tr>
<td>IVA</td><td>Base</td><td>Valor</td>
</tr>

<?php
if($form_pa=="Credito"){
if($ESTADO=="PENDIENTE")echo "<h1 align=\"center\">$ESTADO</h1>";
else if($ESTADO=="PAGADO")echo "<h2 align=\"center\">$ESTADO</h2>";
}
?>
<?php

if($iva0==0){
?>
<tr>
<td><?php echo $iva0 ?></td><td><?php echo money($excentas) ?></td><td><?php echo money("0") ?></td>
</tr>
<?php
}
else{
?>
<tr>
<td><?php echo $iva_show ?></td><td><?php echo money($SUB-$excentas) ?></td><td><?php echo money($IVA) ?></td>
</tr>

<?php

}
?>
</table>

<hr size="2%" style="height:0px"  />
<table align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td>Factur&oacute; :</td><td><?php echo $vendedor ?></td><td>Terminal :</td><td><?php echo "SERVIDOR" ?></td>
</tr>
</table>
<hr size="2%" style="height:0px; border-style: dotted;"  />
<P align="center">
ResolDian No. <?php echo $Resol ?> del <?php echo $FechaResol ?>
<br />
<?php echo $Rango ?>
<BR />
No somos autoretenedores
<br />
Gran Contribuyente
<br />
</P>
<hr size="2%" style="height:0px; border-style: dotted;"  />
<p align="center">
Conserve la factura para reclamaciones
<br />
Gracias por su compra
</p>
<hr size="2%" style="height:0px; border-style: dotted;"  />

 </div>
     


<?php
}




}

 ///////////////////////////////////////////// FIN FOR /////////////////////////////////////////////////////////////////

?>
<div id="imp"  align="center">
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 
</form>
</body>
</html>
