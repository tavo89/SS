<?php
include_once("Conexxx.php");

$url=thisURL();
$boton=r("boton");
if($boton=="MS EXCEL"){excel("Ventas");}

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
$orden="";
$TOT_FACTURAS=0;
$tipoInf=r("t");
$filtroNota="";


$formaPago=s("forma_pago_informes");
$filtroFormaPago="";
if(!empty($formaPago)){$filtroFormaPago=" AND tipo_venta='$formaPago'";}



if($tipoInf==1)$filtroNota=" AND nota!=''";
if(isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI']))$fechaI=$_SESSION['fechaI'];
if(isset($_SESSION['fechaF'])&&!empty($_SESSION['fechaF']))$fechaF=$_SESSION['fechaF'];
if(isset($_SESSION['fechaF'])&&isset($_SESSION['fechaI'])&&!empty($_SESSION['fechaI'])&&!empty($_SESSION['fechaF']))$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF')";
if(isset($_SESSION['cli'])&&!empty($_SESSION['cli'])){$cli=$_SESSION['cli'];$B=" AND (nom_cli='$cli' OR id_cli='$cli')";}




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

if(isset($_SESSION['orden_ven'])&&!empty($_SESSION['orden_ven']))
{$orden=$_SESSION['orden_ven'];

if($orden=="mayor")$ORDER_BY=" ORDER BY tot DESC";
else $ORDER_BY=" ORDER BY tot ASC";
}




/////////////////////////////////////////////////// CLIENTES ///////////////////////////////////////////////////////////////
$id_clientes[]="";
$nom_clientes[]="";
$abonos[][]=array();
$UTIL[][]=array();
$TOT_UTIL=0;

	$tot_saldo=0;
	$tot_abono=0;
	$tot_util=0;
	$tot_venta_sin=0;
	
	$TOT_COSTO_SIN[][]=array();
	$TOT_COSTO_IVA[][]=array();
	$TOT_VENTAS_COSTO_IVA=0;
	$TOT_VENTAS_PVP=0;
$rs=$linkPDO->query("SELECT num_fac_ven,prefijo,id_cli,nom_cli from fac_venta WHERE nit=$codSuc AND ".VALIDACION_VENTA_VALIDA." $filtroFormaPago $B $A $E order by 3");
$i=0;
while($row=$rs->fetch())
{
	$num_fac=$row['num_fac_ven'];
	$pre=$row['prefijo'];
	$id_clientes[$i]=ucwords(strtolower(htmlentities($row["id_cli"], ENT_QUOTES,"$CHAR_SET")));
	//$nom_clientes[$i]=ucwords(strtolower(htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET")));
	$nom_clientes[$i]=ucwords(strtolower($row["nom_cli"]));
	$UTIL[$num_fac][$pre]=0;
	$TOT_COSTO_SIN[$num_fac][$pre]=0;
	$TOT_COSTO_IVA[$num_fac][$pre]=0;
	
	$abonos[$row['prefijo']][$row['num_fac_ven']]=0;
	
	$i++;
	
}

if($tipoInf!=1){
////////////////////////////////////////////////////////// UTILIDAD /////////////////////////////////////////////////////////////////////////////////
 
$subQuery = "SELECT nit,ref, cod_barras, prefijo, num_fac_ven, des, iva, precio, sub_tot, b.fab, cant, fraccion, unidades_fraccion, dcto, costo,b.id_clase,id_sub_clase,nit_proveedor
FROM art_fac_ven a
INNER JOIN productos b ON a.ref = b.id_pro"; 


$sql="SELECT b.anulado,a.fab,b.ciudad,b.nit, b.id_cli,b.nom_cli,a.ref,a.des,a.cant,a.fraccion,a.unidades_fraccion,a.iva as iva_art,a.precio,a.sub_tot as  art_sub,a.dcto,a.costo,b.num_fac_ven,b.prefijo,b.sub_tot,b.iva,b.tot,DATE(b.fecha) as fecha,b.descuento,b.vendedor FROM ($subQuery) a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven  WHERE a.prefijo=b.prefijo AND b.nit=$codSuc AND b.".VALIDACION_VENTA_VALIDA." $filtroFormaPago  $A $C $E $B";
$resp=$sql;
$rs=$linkPDO->query($sql);

//echo "$sql";
	while($row=$rs->fetch())
	{
		$num_fac=$row['num_fac_ven'];
		$pre=$row['prefijo'];
		
		$totFac=$row['tot']*1;
		$fecha=$row['fecha'];
		$saldo=$totFac;//-$abonos[$pre][$numFac];
		$abono=0;//$abonos[$pre][$numFac];
		$ref=$row['ref'];
		$idCli=$row['id_cli'];
		$nomCli= $row["nom_cli"];
	

	$cant = $row["cant"]*1;
	$frac=$row['fraccion'];
	$uni=$row['unidades_fraccion'];
	$factor=($uni+($cant*$frac))/$frac;
	$pvp = $row["precio"]*1;
	$iva=$row['iva_art'];
	$costo=$row['costo']*1;
	$costoIVA=$costo*(1+($iva/100));
	$sub_tot = $row["sub_tot"]*1;
	
	$descuento = $row["dcto"]*1;
	//$util=(( ($refPrecio[$ref]-$costo) /$refPrecio[$ref] )) - $descuento/100;
	$util=util($pvp*$factor,$costo*$factor,$iva,"per");
	//if($util<0)$util=$util*-1;
	
	$utilidad=util($pvp*$factor,$costo*$factor,$iva,"tot");
	
	
	
	$UTIL[$num_fac][$pre]+=$utilidad;

	$TOT_UTIL+=$utilidad;
	$TOT_VENTAS_COSTO_IVA+=$costoIVA*$factor;
	$TOT_VENTAS_PVP+=$pvp*$factor;
	
	$TOT_COSTO_IVA[$num_fac][$pre]+=$costoIVA*$factor;
	$TOT_COSTO_SIN[$num_fac][$pre]+=$costo*$factor;
		
		
	
		
	}///////////////////////////////////// FIN WHILE UTILIDADES /////////////////////////////////////////////////////////////////////////////////////
	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html >
<head>
<?php include_once("HEADER_UK.php"); ?>

</head>

<body style="font-size:12px">
<div style=" top:0cm; width:21.5cm; height:27.9cm;  " class="uk-width-7-10 uk-container-center">
<table align="center" width="100%">
<tr>
<td colspan="3">
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top">
<p align="left" style="font-size:32px; font-weight:bold;">
<span align="center"><B>Informe de Ventas por Factura</B></span>
</p>
</td>

</tr>
</table>
<span style="font-size:18px; font-weight:bold">Fecha Informe: <?PHP echo $hoy ?></span>
<br>
<p align="left">
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
	echo $GSX650."<br>";
	}
if(isset($_SESSION['fe_crea'])&&!empty($_SESSION['fe_crea'])&&$_SESSION['fe_crea']==1){echo "<b>CLIENTES NUEVOS</b>";}
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
<?php
//echo $res
?>
<hr align="center" width="100%">
<table align="center"  frame="box" rules="cols" cellspacing="0" cellpadding="0"  class="uk-table uk-table-striped" style="font-size:10px;" width="70%">

<thead>
<?php
if($tipoInf!=1){

?>
<tr class="  uk-text-bold uk-text-center uk-block-secondary uk-contrast">
<th width="60" align="left">No. Factura</th>
<th width="60" align="left">C.C/NIT</th>
<th width="120" align="left">Cliente</th>
<!--<th width="60"> </th>-->

<th align="right">CostoIVA</th>
<th align="right">Costo sin IVA</th>
<th align="right">SUB T.</th>
<th>IVA</th>
<th>TOTAL</th>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<th width="60"> Util. %</th>
<th> Utilidad</th>
<?php 
}
?>
<th>FECHA</th>
</tr>


<?php
}
else{
?>


<tr class="  uk-text-bold uk-text-center uk-block-secondary uk-contrast">
<th width="60" >No. Factura</th>
<th width="60">C.C/NIT</th>
<th width="120">Cliente</th>
<th width="120">Nota</th>
<th width="60">Estado</th>
<th>TOTAL</th>

<th>FECHA</th>
</tr>

<?php
}// fin else

?>

</thead>
<tbody>

<?php
$tot_saldo=0;
$tot_abono=0;
$TOT_ABONO=0;
$TOT_SALDO=0;
$TOT_IVA=0;
$TOT_SUB=0;
//$ORDER_BY="ORDER BY 6 ";
//$ORDER_BY="ORDER BY 4 ";

	//$sql="SELECT b.iva,b.nit, b.id_cli,b.nom_cli,SUM(a.sub_tot) as tot,b.fecha,SUM(b.descuento) descuento,b.vendedor FROM art_fac_ven a INNER JOIN  fac_venta b  ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND a.nit=b.nit AND b.nit=$codSuc AND b.".VALIDACION_VENTA_VALIDA." $A $C $E GROUP BY id_cli $ORDER_BY";
	
$sql="SELECT num_fac_ven,prefijo,nota,anulado,tot,fecha,id_cli,nom_cli,iva,descuento,sub_tot  
FROM  fac_venta b  WHERE b.nit=$codSuc AND b.".VALIDACION_VENTA_VALIDA." $A $C $E $B $filtroFormaPago $filtroNota $ORDER_BY";

//echo $sql;
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){
	$rs=$linkPDO->query($sql);
	?>
    
 
    
    <?php
	$TOT_FACTURAS=0;
	$TOT_VENTAS_SIN=0;
	$tot_saldo=0;
	$tot_abono=0;
	$tot_util=0;
	$tot_venta_sin=0;
	$nf=0;
	while($row=$rs->fetch())
	{
		
		$num_fac=$row['num_fac_ven'];
		$pre=$row['prefijo'];
		
		$nota=$row["nota"];
		$estado=$row["anulado"];
		$totFac=$row['tot']*1;
		$fecha=$row['fecha'];
		$saldo=$totFac;//-$abonos[$pre][$numFac];
		$abono=0;//$abonos[$pre][$numFac];
		$idCli=$row['id_cli'];
		$nomCli= htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET");	
		$iva = htmlentities($row["iva"]*1);
		$art_sub=$row["tot"]*1;
		$descuento = $row["descuento"]*1;
		$tot_abono+=$abono;
		$subF=$row['sub_tot'];
		$ivaF=$row['iva'];
		$totF=$row['tot'];
		$TOT_FACTURAS+=$totF;
		$TOT_IVA+=$ivaF;
		$TOT_SUB+=$subF;
		
		//$factor = htmlentities($row["cant"]*1);
		//$ref = htmlentities($row["ref"], ENT_QUOTES,"$CHAR_SET");

		$tot_saldo=$art_sub;
		/*$util=($tot_util/($tot_saldo+$descuento));
		if($util<0)$util=$util*-1;
		$utilidad=$util*$art_sub;
		$tot_util+=$utilidad;*/
		
		$tot_venta_sin+=$descuento+$art_sub;

		$TOT_ABONO+=$abono;
		$TOT_SALDO+=$art_sub;
		$TOT_VENTAS_SIN=$tot_saldo+$descuento;

if($tipoInf!=1){		
		?> 
<tr>
<td colspan="" align="left"><?php echo "$num_fac $pre" ?></td>
<td colspan="" align="left" style="font-size: 9px;"><?php echo $idCli ?></td>
<td colspan="" align="left"><?php echo $nomCli ?></td>
<td align="right"><?php echo ($TOT_COSTO_IVA[$num_fac][$pre]) ?></td>
<td align="right"><?php echo round($TOT_COSTO_SIN[$num_fac][$pre]) ?></td>
<td align="right"><?php echo round($subF) ?></td>
<td align="right"><?php echo round($ivaF) ?></td>
<td align="right"><?php  echo round($totF)?></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<td align="right"><?php echo util_tot( $tot_saldo,$TOT_COSTO_IVA[$num_fac][$pre],"per" ); ?></td>
<td align="right"><?php  echo round(util_tot($tot_saldo,$TOT_COSTO_IVA[$num_fac][$pre],"tot"))?></td>
<?php 
}
?>
<td colspan=""><?php echo $fecha ?></td>
</tr> 
    
    <?php
}// fin if
else {
	
	
?>


<tr> 
<td colspan="" align="left"> <?php echo "$pre $num_fac" ?></td>
<td colspan="" align="left"><?php echo $idCli ?></td>
<td colspan="" align="left"><?php echo $nomCli ?></td>
<td align="left"><?php echo $nota ?></td>
<td align="left"><?php echo $estado ?></td>
<td align="right"><?php  echo money2($totF)?></td>
<td colspan=""><?php echo $fecha ?></td>
</tr> 


<?php
}// fin else
	}///////////////////////////////////// FIN WHILE /////////////////////////////////////////////////////////////////////////////////////

	
	}// fin validacion if rs, FOR
	
//}


?>
</tbody>
</table>

<table align="center"  frame="box" rules="cols" cellspacing="0" cellpadding="0"  class="uk-table uk-table-striped"  >
<thead>
<tr class="uk-text-large uk-text-bold uk-text-center uk-block-secondary uk-contrast">
<th width="100" colspan=""></th>

<!--<th width="60"> </th>-->
<th width="100">Costo IVA</th>
<th width="100">SUB T.</th>
<th width="100">IVA</th>
<!--<th>Tot. Sdcto</th>-->
<th>TOTAL</th>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<th width="60"> Util. %</th>
<th> Utilidad</th>
<?php 
}
?>
</tr>
</thead>
<tr style="font-size:12px; font-weight:bold;font-family: 'Arial Black', Gadget, sans-serif;">
<tH colspan=""><b>TOTAL VENTAS</b></th>
<td align="center"><?php echo money3($TOT_VENTAS_COSTO_IVA) ?></td>
<td align="center"><?php echo money3($TOT_SUB) ?></td>
<td align="center"><?php echo money3($TOT_IVA) ?></td>
<!--<td><?php  echo money3($tot_venta_sin)?></td>-->
<td align="center"><?php  echo money3($TOT_SALDO)?></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
$uti_per=0;
if($tot_venta_sin>0)$uti_per=($TOT_UTIL/$tot_venta_sin)*100;
?>
<td align="center"><?php echo util_tot($TOT_VENTAS_PVP,$TOT_VENTAS_COSTO_IVA,"per"); ?>%</td>
<td align="center"><?php  echo money3(util_tot($TOT_VENTAS_PVP,$TOT_VENTAS_COSTO_IVA,"tot")) ?></td>
<?php 
}
?>

</tr> 
</table>
<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <!--
    

 <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />   
    -->
<input  type="button" value="MS Word" name="boton" onMouseDown="location.assign('informeVentasClientes3MSW.php');" />
<input type="button" value="MS EXCEL" name="boton" onclick="location.assign('<?php if($tipoInf!=1 ){echo "$url?boton=MS EXCEL";}else echo "$url&boton=MS EXCEL"; ?>')" />
        
</div> 

</div>

<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER"; ?>"></script> 
<?php include_once("FOOTER_UK.php"); ?>
<script language="javascript1.5" type="text/javascript">
$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


		//$("#TabClientes").tablesorter( {sortList: [[4,0]]} );
	 
	}
);
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</body>
</html>