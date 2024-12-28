|<?php
require_once("Conexxx.php");
$url=thisURL();
$boton=r("boton");
if($boton=="MS EXCEL"){excel("Cartera Clientes");}

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
$F="";


//////////////////////////////////// NOMBRE /////////////////////////
if(isset($_SESSION['nom_cli_inf2']) && !empty($_SESSION['nom_cli_inf2']))
{
	$nom_cli=limpiarcampo($_SESSION['nom_cli_inf2']);
	$F=" AND (nom_cli='$nom_cli' OR id_cli='$nom_cli')";
	
}

$formaPago=s("forma_pago_informes");
$filtroFormaPago="";
if(!empty($formaPago)){$filtroFormaPago=" AND tipo_venta='$formaPago'";}

if(isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI']))$fechaI=$_SESSION['fechaI'];
if(isset($_SESSION['fechaF'])&&!empty($_SESSION['fechaF']))$fechaF=$_SESSION['fechaF'];
if(isset($_SESSION['fechaF'])&&isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI'])&&!empty($_SESSION['fechaF']))$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF')";
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



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>COMPROBANTE DE INFORME DIARIO DE VENTAS</title>
<?php
if($boton!="MS EXCEL"){

?>
<link rel="stylesheet" href="css/jq.css" type="text/css" media="print, projection, screen" />
<link rel="stylesheet" href="css/themes/blue/style.css" type="text/css" media="print, projection, screen" />

<script language="javascript1.5" type="text/javascript" src="JS/jquery-1.11.1.min.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery.tablesorter.min.js"></script>
<script language="javascript1.5" type="text/javascript">
$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


		//$("#TabClientes").tablesorter( {sortList: [[4,0]]} );
		$("#TabClientes").tablesorter();
	}
);
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
<?php

}

?>
</head>

<body style="font-size:12px">
<div style=" top:0cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>
<td colspan="2">
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top" colspan="4">
<p align="left" style="font-size:32px; font-weight:bold;">
<span align="center"><B>Ventas por Producto Cliente</B></span>
</p>
</td>

</tr>
</table>
<span style="font-size:18px; font-weight:bold">Fecha Informe: <?PHP echo $hoy ?></span>
<br>
<p align="left" style="font-size:16px;">
<b>B&Uacute;SQUEDA</b><br />
<?php 

if(isset($_SESSION['fechaF'])&&isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI'])&&!empty($_SESSION['fechaF']))echo "<b>Desde:</b> $fechaI <b>Hasta:</b> $fechaF<br>";
if(isset($_SESSION['nom_cli_inf2'])&&!empty($_SESSION['nom_cli_inf2'])){$cli=$_SESSION['nom_cli_inf2'];echo "<b>Cliente:</b> $nom_cli <br>";}
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
	echo $GSX650."<br>";;
	}


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

<hr align="center" width="100%">
<table frame="box" rules="all" cellpadding="5" cellspacing="3"  id="TabClientes" class="tablesorter">
<thead>
<tr>
<th>Ref.</th>
<th>Cod.</th>
<th>Producto</th><th width="40">Cant.</th><th>PvP</th>
<th>S. tot</th>
<th>IVA</th>
<th>TOTAL Vendido</th>

<th> IVA %</th>

</tr>
</thead>
<tbody>
<?php
$tot_saldo=0;
$tot_abono=0;
$TOT_ABONO=0;
$TOT_SALDO=0;

$TOT_VENTAS_SIN=0;
$TOT_VENTAS=0;
$TOT_COSTO_VENDIDOS_IVA=0;
$TOT_UTILIDAD=0;

$S_TOT=0;
$IVA=0;
$TOT=0;

$sql="SELECT a.fraccion,a.unidades_fraccion,a.fab,b.ciudad,b.nit, b.id_cli,b.nom_cli,a.ref,a.cod_barras,a.des,SUM(a.cant) AS cant,a.iva as IVAart,a.precio,SUM(a.sub_tot) as  art_sub,SUM(a.costo*a.cant) AS totCost,a.dcto,a.costo,b.num_fac_ven,b.prefijo,b.sub_tot,b.iva,b.tot,b.fecha,b.descuento,b.vendedor 
FROM 
(SELECT ref,cod_barras,prefijo,num_fac_ven,des,iva,precio,sub_tot,b.fab,cant,fraccion,unidades_fraccion,dcto,costo FROM art_fac_ven a INNER JOIN ".tabProductos." b ON a.ref=b.id_pro) a 
	
	INNER JOIN fac_venta b 
	
	ON a.num_fac_ven=b.num_fac_ven  WHERE a.prefijo=b.prefijo AND b.nit=$codSuc AND b.".VALIDACION_VENTA_VALIDA." $filtroFormaPago $A  $F GROUP BY a.ref";
	//echo "$sql";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){
	$rs=$linkPDO->query($sql);
	$tot_saldo=0;
	$tot_abono=0;
	$nf=0;
	$TOT_VENTAS_SIN=0;
	$TOT_VENTAS=0;
	$TOT_COSTO_VENDIDOS_IVA=0;
	$TOT_UTILIDAD=0;
	while($row=$rs->fetch())
	{
		$ref=$row['ref'];
		$codBar=$row['cod_barras'];
		$pre=$row['prefijo'];
		$numFac=$row['num_fac_ven'];
		$sede=$row['nit'];
		$totFac=$row['tot']*1;
		$fecha=$row['fecha'];
		$saldo=$totFac;//-$abonos[$pre][$numFac];
		$abono=0;//$abonos[$pre][$numFac];
		
		
		
	$des = htmlentities($row["des"], ENT_QUOTES,"$CHAR_SET");
	$iva = $row["IVAart"]*1;
	$iva_art=($iva/100);
	$art_IVA=0;

	//$totCosto=$row['totCost'] * (($iva/100) +1);
	$totCosto=$row['totCost'];
	$cant = $row["cant"]*1;
	$frac=$row['fraccion'];
	if($frac==0)$frac=1;
	$uni=$row['unidades_fraccion'];
	$factor=($uni+($cant*$frac))/$frac;
	
	
	$pvp=$row["precio"]*1;
	$pvp2=$pvp/(1+($iva/100));
	$art_IVA=($pvp2*$factor)*($iva/100);
	$s_tot=$pvp2*$factor;

	$costo=$row['costo'];
	
	$sub_tot = $row["sub_tot"]*1;
	$art_sub=redondeo($row["art_sub"]*1);
	
		
		$tot_abono+=$abono;
		
		
		
		$tot_saldo+=$art_sub;
		
		
		$S_TOT+=$s_tot;
		$IVA+=$art_IVA;
		$TOT+=$art_sub;
		
		$TOT_ABONO+=$abono;
		$TOT_SALDO+=$art_sub;
		$TOT_COSTO_VENDIDOS_IVA+=$totCosto*(($iva/100) +1);
		$TOT_VENTAS+=$art_sub;
		$TOT_UTILIDAD+=util($art_sub,$totCosto,$iva,"tot");
	
		?>
 <tr>
 <td>'<?php echo $ref ?>'</td>
 <td>'<?php echo $codBar ?>'</td>
 <td><?php echo $des ?></td>
 <td><?php echo "$cant;$uni" ?></td>
 <td><?php echo money2($pvp2) ?></td>
  <td><?php echo money2($s_tot)/*."---> $totCosto"*/ ?></td>
   <td><?php echo money2($art_IVA)/*."---> $totCosto"*/ ?></td>
 <td><?php echo money2($art_sub)/*."---> $totCosto"*/ ?></td>
  <td><?php echo $iva ?>%</td>


 
   
 
 </tr>
        <?php
		
	}///////////////////////////////////// FIN WHILE /////////////////////////////////////////////////////////////////////////////////////

		
	
}


?>
</tbody>
</table>
<table frame="box" rules="all" cellpadding="5" cellspacing="3"  id="TabClientes" class="tablesorter">
<thead>
<tr>
<th colspan="2" width="50px"></th>
<th colspan="2" width="50px">SUB TOT</th>
<!--<th>TOTAL sin Dcto</th>-->
<th colspan="2" width="50px"> IVA</th>
<th colspan="2" width="50px">TOT</th>
</tr>
</thead>

<tr style="font-size:14px; font-weight:bold;font-family: 'Arial Black', Gadget, sans-serif;">
<tH colspan="2"><b>TOTAL VENTAS</b></th>

<td colspan="2" align="center"><?php  echo money3($S_TOT)?></td>
 <!--<td><?php echo money3($TOT_VENTAS_SIN) ?></td>-->

<td colspan="2" align="center"><?php echo money3($IVA) ?></td>
<td colspan="2" align="center"><?php  echo money3($TOT)?></td>
</tr> 
</table>
<hr align="center" width="100%" />
<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
   <input type="button" value="MS EXCEL" name="boton" onclick="location.assign('<?php echo "$url?boton=MS EXCEL" ?>')" />
</div> 
</div>
</body>
</html>