|<?php
require_once("Conexxx.php");

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

$formaPago=s("forma_pago_informes");
$filtroFormaPago=""; 

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
if(isset($_SESSION['pro'])&&!empty($_SESSION['pro'])){$pro=$_SESSION['pro'];$C=" AND fab='$pro'";}

/////////////////////////////////////////////////// PRODUCTOS ///////////////////////////////////////////////////////////

$refPrecio[]=0;
$UTIL[]=0;
$TOT_UTIL[]=0;
$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE nit_scs=$codSuc");
while($row=$rs->fetch())
{
	
$refPrecio[$row['id_inter']]=$row['precio_v'];
$UTIL[$row['id_inter']]=0;
$TOT_UTIL[$row['id_inter']]=0;

}
///////////////////////////// FIN WHILE UTILIDADES /////////////////////////////////////////////////////////////////////////////////////
?>

<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>COMPROBANTE DE INFORME DIARIO DE VENTAS</title>
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
<span align="center"><B>Informe de Compras por Producto</B></span>
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
<table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%" id="TabClientes" class="tablesorter">
<thead>
<tr>
<th>Ref.</th>
<th>Cod.</th>
<th>Producto</th><th width="40">Cant.</th><th><p>Costo sin IVA</p></th>
<th>IVA. %</th>
<th>TOTAL Comprado</th>
<!--



<th>Valor Utilidad</th>
-->
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

	$sql="SELECT a.fraccion,a.unidades_fraccion,a.fab,b.ciudad, b.nit_pro,b.nom_pro,a.ref,a.cod_barras,a.des,SUM(a.cant) AS cant,a.iva as IVAart,a.pvp,SUM(a.tot) as  art_sub,SUM(a.costo*a.cant) AS totCost,a.dcto,a.costo,b.num_fac_com,b.nit_pro,b.subtot,b.iva,b.tot,b.fecha,b.descuento FROM (SELECT ref,cod_barras,nit_pro,num_fac_com,des,iva,pvp,tot,b.fab,cant,fraccion,unidades_fraccion,dcto,costo FROM art_fac_com a INNER JOIN ".tabProductos." b ON a.ref=b.id_pro) a INNER JOIN fac_com b ON a.num_fac_com=b.num_fac_com  WHERE a.nit_pro=b.nit_pro AND b.cod_su=$codSuc AND b.estado='CERRADA' $filtroFormaPago  $A $C $E GROUP BY a.ref";
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
		$pre=$row['nit_pro'];
		$numFac=$row['num_fac_com'];
	
		$totFac=$row['tot']*1;
		$fecha=$row['fecha'];
		$saldo=$totFac;//-$abonos[$pre][$numFac];
		$abono=0;//$abonos[$pre][$numFac];
		
	$pvp = $row["costo"]*1;	
	$des = $row["des"];
	$iva = $row["IVAart"]*1;
	$iva_art=($iva/100);
	$art_IVA=0;
	if($iva_art!=0)$art_IVA=$pvp/$iva_art;
	//$totCosto=$row['totCost'] * (($iva/100) +1);
	$totCosto=$row['totCost'];
	$cant = $row["cant"]*1;
	$frac=$row['fraccion'];
	$uni=$row['unidades_fraccion'];
	$factor=($uni+($cant*$frac))/$frac;
	
	$costo=$row['costo'];
	
	$sub_tot = $row["tot"]*1;
	$art_sub=redondeoF(($row["costo"]*$factor*($iva_art+1)),1);
	
		
		$tot_abono+=$abono;
		
		
		
		$tot_saldo+=$art_sub;
		
		$TOT_ABONO+=$abono;
		$TOT_SALDO+=$art_sub;
		$TOT_COSTO_VENDIDOS_IVA+=$totCosto*(($iva/100) +1);
		$TOT_VENTAS+=$art_sub;
		$TOT_UTILIDAD+= util($art_sub,$totCosto,$iva,"tot") ||0;
		
		?>
 <tr>
 <td><?php echo $ref ?></td>
 <td><?php echo $codBar ?></td>
 <td><?php echo $des ?></td>
 <td><?php echo "$cant;$uni" ?></td>
 <td><?php echo money2($pvp) ?></td>
  <td><?php echo $iva ?>%</td>
 <td><?php echo money2($art_sub)/*."---> $totCosto"*/ ?></td>
 <!--
  <td><?php //echo money2(util($art_sub,$totCosto,$iva,"tot")) ?></td>

 -->
   
 
 </tr>
        <?php
		
	}///////////////////////////////////// FIN WHILE /////////////////////////////////////////////////////////////////////////////////////

		
	
}


?>
</tbody>
</table>
<table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%" id="TabClientes" class="tablesorter">
<thead>
<tr>
<th colspan="3"></th>
<th>TOTAL COMPRAS</th>
<!--<th>TOTAL sin Dcto</th>
<th>Valor Utilidad</th>
<th> Util. %</th>
-->


</tr>
</thead>

<tr style="font-size:16px; font-weight:bold;font-family: 'Arial Black', Gadget, sans-serif;">
<tH colspan="3"><b>TOTAL VENTAS</b></th>

<td><?php  echo money($TOT_SALDO)?></td>

<!--
<td><?php //echo redondeo2(util_tot($TOT_VENTAS,$TOT_COSTO_VENDIDOS_IVA,"per")) ?>%</td>
<td><?php  //echo money(redondeo($TOT_UTILIDAD))?></td>
-->
</tr> 
</table>
<hr align="center" width="100%" />
<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 
</div>
</body>
</html>