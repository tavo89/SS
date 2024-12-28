<?php
require_once("Conexxx.php");
$url=thisURL();
$boton=r("boton");
if($boton=="MS EXCEL"){excel("Ventas Clientes");}
if($boton=="MS WORD"){word("Ventas Clientes");}
$fechaI="";
$fechaF="";
$cli="";
$pro="";
$Az="";
$Za="";

$A="";
$B="";
$C="";
$D="";
$E="";
$ORDER_BY="";

$formaPago=s("forma_pago_informes");
$filtroFormaPago="";
if(!empty($formaPago)){$filtroFormaPago=" AND tipo_venta='$formaPago'";}

if(isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI']))$fechaI=$_SESSION['fechaI'];
if(isset($_SESSION['fechaF'])&&!empty($_SESSION['fechaF']))$fechaF=$_SESSION['fechaF'];

if(isset($_SESSION['fechaF'])&&isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI'])&&!empty($_SESSION['fechaF'])&&isset($_SESSION['fe_crea'])&&$_SESSION['fe_crea']==1)$A=" AND (DATE((SELECT fecha_crea FROM usuarios WHERE id_usu = id_cli))>='$fechaI' AND DATE((SELECT fecha_crea FROM usuarios WHERE id_usu = id_cli))<='$fechaF')";
else if(isset($_SESSION['fechaF'])&&isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI'])&&!empty($_SESSION['fechaF'])&&isset($_SESSION['fe_crea'])&&$_SESSION['fe_crea']==0)$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF')";


if(isset($_SESSION['cli'])&&!empty($_SESSION['cli'])){$cli=$_SESSION['cli'];$B=" AND nom_cli='$cli'";}
if(isset($_SESSION['ciudad'])&&!empty($_SESSION['ciudad']))
{
	$city=$_SESSION['ciudad'];
	$E="AND (";
	foreach($city as $key=> $resultado)
	{
		$E.="ciudad='$resultado' OR ";
	}
	$E.=" ciudad='$resultado') ";
	
	if(empty($resultado))$E="";
	
	
	}
	if(isset($_SESSION['dcto'])&&!empty($_SESSION['dcto'])){$cli=$_SESSION['dcto'];$D=" AND ='$cli'";}
if(isset($_SESSION['pro'])&&!empty($_SESSION['pro'])){$pro=$_SESSION['pro'];$C=" AND fab='$pro'";}

/////////////////////////////////////////////////// PRODUCTOS ///////////////////////////////////////////////////////////

$refPrecio[]=0;

$rs=$linkPDO->query("SELECT id_pro,precio_v,id_inter  FROM inv_inter WHERE nit_scs=$codSuc");
while($row=$rs->fetch())
{
	
$refPrecio[$row['id_inter']]=$row['precio_v'];

}

$rs=$linkPDO->query("SELECT ref,cod_barras,precio  FROM art_fac_ven WHERE nit=$codSuc AND cod_barras NOT IN(SELECT id_inter  FROM inv_inter WHERE nit_scs=$codSuc) ");
while($row=$rs->fetch())
{
	
$refPrecio[$row['cod_barras']]=$row['precio'];

}



/////////////////////////////////////////////////// CLIENTES ///////////////////////////////////////////////////////////////
$id_clientes[]="";
$nom_clientes[]="";
$abonos[][]=0;
$sql="SELECT prefijo,num_fac_ven,id_cli,nom_cli from fac_venta WHERE nit=$codSuc AND ".VALIDACION_VENTA_VALIDA." $filtroFormaPago $B $A $E GROUP BY id_cli order by 4";
$rs=$linkPDO->query($sql);
$i=0;
while($row=$rs->fetch())
{
	$id_clientes[$i]=ucwords(strtolower($row["id_cli"]));
	//$nom_clientes[$i]=ucwords(strtolower(htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET")));
	$nom_clientes[$i]=ucwords(strtolower($row["nom_cli"]));
	$abonos[$row['prefijo']][$row['num_fac_ven']]=0;
	$i++;
	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>COMPROBANTE DE INFORME DIARIO DE VENTAS</title>
<link rel="stylesheet" href="css/jq.css" type="text/css" media="print, projection, screen" />
<link rel="stylesheet" href="css/themes/blue/style.css" type="text/css" media="print, projection, screen" />
<script language="javascript1.5" type="text/javascript" src="JS/jquery-1.11.1.min.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery.tablesorter.min.js"></script>
<script language="javascript1.5" type="text/javascript">
$(document).ready(function()
	{
		//$("#TabClientes").tablesorter( {sortList: [[4,0]]} );
		//$("#TabClientes").tablesorter();
	}
);
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</head>

<body style="font-size:12px">
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>
<td>
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top">
<p align="left" style="font-size:32px; font-weight:bold;">
<span align="center"><B>Informe de Ventas por Clientes</B></span>
</p>
</td>

</tr>
</table>
<span style="font-size:18px; font-weight:bold">Fecha Informe: <?PHP echo $hoy ?></span>
<br>
<p align="left">
<b>B&Uacute;SQUEDA</b><br />
<?php 
//////////////////////////////////////////////////////////// FILTROS BUSQUEDA //////////////////////////////////////////////////////////////////////
if(isset($_SESSION['fechaF'])&&isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI'])&&!empty($_SESSION['fechaF']))echo "<b>Desde:</b> $fechaI <b>Hasta:</b> $fechaF<br>";
if(isset($_SESSION['cli'])&&!empty($_SESSION['cli'])){$cli=$_SESSION['cli'];echo "<b>Cliente:</b> $cli <br>";}
if(isset($_SESSION['pro'])&&!empty($_SESSION['pro'])){$pro=$_SESSION['pro'];echo "<b>Proveedor:</b> $pro <br>";}
if(!empty($formaPago))echo "<b>Forma de Pago:</b> $formaPago<br>";
if(isset($_SESSION['ciudad'])&&!empty($_SESSION['ciudad']))
{
	$city=$_SESSION['ciudad'];
	$GSX650="<b>Ciudad(es):</b> |";
	foreach($city as $key=>$resultado)
	{
			$GSX650.="$resultado |";
	}
	echo $GSX650;
	}
if(isset($_SESSION['fe_crea'])&&!empty($_SESSION['fe_crea'])&&$_SESSION['fe_crea']==1){echo "<br><b>CLIENTES NUEVOS</b>";}

 ?>
</p>
<table align="center" width="100%">
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">
</table>
</td>
</tr>
</table>


<table frame="box" rules="all" cellpadding="5" cellspacing="3" border="1">
<tr>
<th>Factura</th><th width="70">Fecha</th>
<th>Producto</th>
<th>Cant.</th>
<th>Costo Unitario</th>
<!--

<th>PvP</th>
<th>PvP Cdcto.</th>.
<th>% Dcto.</th>
-->

<!--
<th> IVA %</th>
<th>Valor IVA</th>
<th>Tot. Sdcto</th>
-->

<th>TOTAL</th>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<th> Util. %</th>
<th> Utilidad</th>
<?php 
}
?>


</tr>
<?php
//////////////////////////////////////////////////////////////// INICIO INFORME ///////////////////////////////////////////////////////////////////////
$tot_saldo=0;
$tot_abono=0;
$TOT_ABONO=0;
$TOT_SALDO=0;
$TOT_UTIL=0;
$TOT_VENTAS_SIN=0;
$TOT_VENTAS=0;
$TOT_COSTO_VENDIDOS_IVA=0;
$TOT_COSTO_VENDIDOS_IVA_CLI=0;
for($i=0;$i<count($id_clientes);$i++)
{
	
	
	
$sql="SELECT b.tipo_venta,b.anulado,a.fab,b.ciudad,b.nit, b.id_cli,b.nom_cli,a.ref,a.cod_barras,a.des,a.cant,a.fraccion,a.unidades_fraccion,a.iva as iva_art,a.precio,a.sub_tot as  art_sub,a.dcto,a.costo,b.num_fac_ven,b.prefijo,b.sub_tot,b.iva,b.tot,DATE(b.fecha) as fecha,b.descuento,b.vendedor 

FROM
 
(SELECT ref,des,cant,fraccion,unidades_fraccion,iva,precio,sub_tot,dcto,costo,fab,id_pro,prefijo,num_fac_ven,cod_barras FROM art_fac_ven a INNER JOIN (select fab,id_pro from ".tabProductos.") b ON a.ref=b.id_pro) a

INNER JOIN 
(SELECT tipo_venta,id_cli,anulado,ciudad,nit,nom_cli,num_fac_ven,prefijo,sub_tot,iva,tot,fecha,descuento,vendedor FROM fac_venta WHERE id_cli='$id_clientes[$i]') b

ON a.num_fac_ven=b.num_fac_ven  

WHERE a.prefijo=b.prefijo AND b.nit=$codSuc AND b.".VALIDACION_VENTA_VALIDA." $filtroFormaPago  $A $C $E   ORDER BY fecha";


                 // echo $sql."<br><br><br>";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){
	$rs=$linkPDO->query($sql);
	?>
    
<tr>
<td colspan="3"><b><?php echo $id_clientes[$i]." - ".$nom_clientes[$i] ?></b></td>
<!--
<th>PvP</th>
<th>PvP Cdcto.</th>
-->
<th>Cant.</th>
<th>Costo Unitario</th>
<!--
<th>% Dcto.</th>
<th> IVA %</th>
<th>Valor IVA</th>
<th>Tot. Sdcto</th>
-->

<th>TOTAL</th>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<th> Util. %</th>
<th> Utilidad</th>
<?php 
}
?>
</tr> 
    
    <?php
	$tot_saldo=0;
	$tot_abono=0;
	$tot_util=0;
	$tot_venta_sin=0;
	$nf=0;
	$tot_ventas=0;
	$tot_costo_ventas_iva=0;
	$TOT_COSTO_VENDIDOS_IVA_CLI=0;
	while($row=$rs->fetch())
	{
		$pre=$row['prefijo'];
		$numFac=$row['num_fac_ven'];
		$sede=$row['nit'];
		$totFac=$row['tot']*1;
		$fecha=$row['fecha'];
		$saldo=$totFac;//-$abonos[$pre][$numFac];
		$abono=0;//$abonos[$pre][$numFac];
		
	$ref = $row["ref"];
	$codBarras = $row["cod_barras"];
	$des = $row["des"];
	$iva =$row["iva_art"]*1;
	$iva_art=($iva/100);
	$art_IVA=0;
	if($iva_art!=0)$art_IVA=$refPrecio[$codBarras]/$iva_art;
	
	$cant = $row["cant"]*1;
	$frac=$row['fraccion'];
	$uni=$row['unidades_fraccion'];
	$factor=($uni+($cant*$frac))/$frac;
	$pvp = $row["precio"]*1;
	$costo=$row['costo']*1;
	$costoIVA=$costo + $costo*$iva_art;
	$sub_tot = $row["sub_tot"]*1;
	$art_sub=redondeo($row["art_sub"]*1);
	$descuento = $row["dcto"]*1;
	//$util=(( ($refPrecio[$codBarras]-$costo) /$refPrecio[$codBarras] )) - $descuento/100;
	$util=util(($pvp * $factor),($costo*$factor),$iva,"per");
	
	//if($util<0)$util=$util*-1;
	//$utilidad=$util*($factor*$refPrecio[$codBarras]);
	$utilidad=util(($pvp * $factor),($costo*$factor),$iva,"tot");
	
	
	
	
		
		$tot_abono+=$abono;
		
		
		
		$tot_saldo+=$art_sub;
		$tot_util+=$utilidad;
		$tot_venta_sin+=$factor*$refPrecio[$codBarras];
		
		
		$tot_costo_ventas_iva+=($costo*$factor)*(1+($iva/100));
	    $tot_ventas+=($pvp * $factor);
	
		$TOT_COSTO_VENDIDOS_IVA+=($costo*(1+($iva/100)))*$factor;
		$TOT_COSTO_VENDIDOS_IVA_CLI+=($costoIVA*(1+($iva/100)))*$factor;
		$TOT_VENTAS+=$art_sub;
		$TOT_ABONO+=$abono;
		$TOT_SALDO+=$art_sub;
		$TOT_UTIL+=$utilidad;
		$TOT_VENTAS_SIN+=$factor*$refPrecio[$codBarras];
		
		?>
 <tr>
 <td><?php echo "$pre $numFac" ?></td>
 <td><?php echo $fecha ?></td>
 <td><?php echo $des ?></td>
 <td><?php echo "$cant;$uni" ?></td>
  <td><?php echo money3(redondeo($costoIVA)) ?></td>
 <!--
 <td><?php //echo money3(redondeo($costo)) ?></td>
 <td><?php //echo money3($refPrecio[$codBarras]*1) ?></td>
 <td><?php //echo money3(redondeo($pvp)) ?></td>
 <td><?php echo redondeo2($descuento) ?>%</td>
 -->
 
 <!--
 <td><?php echo $iva ?></td>
 <td><?php echo money3($iva_art) ?></td>
 <td><?php echo money3($factor*$refPrecio[$codBarras]) ?></td>
 -->
 
 <td><?php echo money3($art_sub)/*."------> $tot_costo_ventas_iva"*/ ?></td>
 <?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
 <td><?php echo redondeo2($util) ?>%</td>
  <td><?php echo money3(redondeo($utilidad)) ?></td>
  <?php 
}
?>
 
 
   
 
 </tr>
        <?php
		
	}///////////////////////////////////// FIN WHILE /////////////////////////////////////////////////////////////////////////////////////

	?> 
<tr>
<tH colspan="2"><b>TOTAL CLIENTE <?php echo $nom_clientes[$i] ?></b></th>
<td><?php  ?></td>
<td><?php //echo money3($tot_venta_sin) ?></td>
<td><?php echo money3(redondeo($TOT_COSTO_VENDIDOS_IVA_CLI)) ?></td>
<td><?php  echo money3($tot_saldo)?></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<td><?php echo util_tot($tot_ventas,$tot_costo_ventas_iva,"per") ?>%</td>
<td><?php  echo money3(redondeo($tot_util))?></td>
<?php 
}
?>


</tr> 
    
    <?php
	}// fin validacion if rs, FOR
	
}


?>

<tr style="font-size:12px; font-weight:bold;font-family: 'Arial Black', Gadget, sans-serif;">
<tH colspan="2"><b>TOTAL VENTAS</b></th>
<td><?php //echo money3($TOT_ABONO) ?></td>
<td><?php //echo money3($TOT_ABONO) ?></td>
<td><?php echo money3(redondeo($TOT_COSTO_VENDIDOS_IVA)) ?></td>

<td><?php  echo money3(redondeo($TOT_SALDO) )?></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
$util_per=0;
if($TOT_VENTAS_SIN>0)$util_per=($TOT_UTIL/$TOT_VENTAS_SIN)*100;
?>
<td><?php echo util_tot($TOT_SALDO,$TOT_COSTO_VENDIDOS_IVA,"per") ?>%</td>
<td><?php  echo money3(redondeo($TOT_UTIL))?></td>
<?php 
}
?>


</tr> 
</table>

<div id="imp"  align="center">

<input  type="button" value="MS Word" name="boton" onclick="location.assign('<?php echo "$url?boton=MS WORD" ?>')" onMouseDown="//location.assign('informeVentasClientesMSW.php');" />
<input  type="button" value="MS Excel" name="boton" onclick="location.assign('<?php echo "$url?boton=MS EXCEL" ?>')" onMouseDown="//location.assign('informeVentasCliMSE.php');" />

<!--<input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />-->
</div> 
</div>
</body>
</html>