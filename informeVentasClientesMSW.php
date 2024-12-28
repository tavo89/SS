<?php
require_once("Conexxx.php");

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=Informe Resumen Ventas por Cliente $FechaHoy.doc");

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

$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE nit_scs=$codSuc");
while($row=$rs->fetch())
{
	
$refPrecio[$row['id_inter']]=$row['precio_v'];

}



/////////////////////////////////////////////////// CLIENTES ///////////////////////////////////////////////////////////////
$id_clientes[]="";
$nom_clientes[]="";
$abonos[][]=0;

$rs=$linkPDO->query("SELECT * from fac_venta WHERE nit=$codSuc AND ".VALIDACION_VENTA_VALIDA." $B $A $E GROUP BY id_cli order by 3");
$i=0;
while($row=$rs->fetch())
{
	$id_clientes[$i]=ucwords(strtolower(htmlentities($row["id_cli"], ENT_QUOTES,"$CHAR_SET")));
	//$nom_clientes[$i]=ucwords(strtolower(htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET")));
	$nom_clientes[$i]=ucwords(strtolower($row["nom_cli"]));
	$abonos[$row['prefijo']][$row['num_fac_ven']]=0;
	$i++;
	
}
?>

<?php
$titulo="Informe Detallado Ventas Por Cliente";
$url_header="C:/wamp/www/posgir/informeVentasCliMSW_files/headerfooter.html";
$sitio="informeVentasCliMSW.php";
$carpeta="informeVentasCliMSW_files";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html
    xmlns:o='urn:schemas-microsoft-com:office:office'
    xmlns:w='urn:schemas-microsoft-com:office:word'
    xmlns='http://www.w3.org/TR/REC-html40'>
    <head>
<title><?php echo $titulo ?></title>
<link rel=File-List href="<?php echo $carpeta ?>/filelist.xml">
<!--[if gte mso 9]-->
    <xml>
        <w:WordDocument>
            <w:View>Print</w:View>
            <w:Zoom>90</w:Zoom>
            <w:DoNotOptimizeForBrowser/>
        </w:WordDocument>
    </xml>
    <!-- [endif]-->
<style>
@page
{
    size:21cm 27.9cmt;  /* letter */
    margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
    mso-page-orientation: portrait;  
    mso-header: url("<?php echo $url_header ?>") h1;
   	mso-footer: url("<?php echo $url_header ?>") f1;	
}
@page Section1 { }
div.Section1 { page:Section1; }
p.MsoHeader, p.MsoFooter { border: 1px solid black; }
td { page-break-inside:avoid; }
tr { page-break-after:avoid; }
 
    </style>

</head>

<body style="font-size:12px">
<div class="Section1">
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

<hr align="center" width="100%">
<table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%">
<tr style="font-size:14px;">
<th>Factura</th><th width="70">Fecha</th>
<th>Producto</th>
<th>Cant.</th>
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
$pagBreak=0;
	$once=0;
for($i=0;$i<count($id_clientes);$i++)
{
$sql="SELECT b.anulado,a.fab,b.ciudad,b.nit, b.id_cli,b.nom_cli,a.ref,a.cod_barras,a.des,a.cant,a.iva as iva_art,a.precio,a.sub_tot as  art_sub,a.dcto,a.costo,b.num_fac_ven,b.prefijo,b.sub_tot,b.iva,b.tot,DATE(b.fecha) as fecha,b.descuento,b.vendedor 

FROM
 
(SELECT ref,des,cant,iva,precio,sub_tot,dcto,costo,fab,id_pro,prefijo,num_fac_ven,cod_barras FROM art_fac_ven a INNER JOIN (select fab,id_pro from ".tabProductos.") b ON a.ref=b.id_pro) a

INNER JOIN 
(SELECT id_cli,anulado,ciudad,nit,nom_cli,num_fac_ven,prefijo,sub_tot,iva,tot,fecha,descuento,vendedor FROM fac_venta WHERE id_cli='$id_clientes[$i]') b

ON a.num_fac_ven=b.num_fac_ven  

WHERE a.prefijo=b.prefijo AND b.nit=$codSuc AND b.".VALIDACION_VENTA_VALIDA."  $A $C $E ORDER BY fecha";

	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){
	$rs=$linkPDO->query($sql);
	?>
    
<tr style="font-size:12px;">
<td colspan="3"><b><?php echo $id_clientes[$i]." - ".$nom_clientes[$i] ?></b></td>

<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>

<?php 
}
?>
</tr> 
    
    <?php
	$tot_saldo=0;
	$tot_abono=0;
	$tot_util=0;
	$tot_venta_sin=0;
	
	$tot_costo_ventas_iva=0;
	$tot_ventas=0;
	$nf=0;
	
	while($row=$rs->fetch())
	{
		$pagBreak++;
		$pre=$row['prefijo'];
		$numFac=$row['num_fac_ven'];
		$sede=$row['nit'];
		$totFac=$row['tot']*1;
		$fecha=$row['fecha'];
		$saldo=$totFac;//-$abonos[$pre][$numFac];
		$abono=0;//$abonos[$pre][$numFac];
		
	$ref = $row["ref"];
	$codBarras = $row["cod_barras"];
	$des = htmlentities($row["des"], ENT_QUOTES,"$CHAR_SET");
	$iva = $row["iva_art"]*1;
	$iva_art=($iva/100);
	$art_IVA=0;
	
	
	$cant = htmlentities($row["cant"]*1);
	$pvp = htmlentities($row["precio"]*1);
	$costo=$row['costo']*1;
	$sub_tot = htmlentities($row["sub_tot"]*1);
	$art_sub=$row["art_sub"]*1;
	$descuento = $row["dcto"]*1;
	if($iva_art!=0)$art_IVA=$pvp/$iva_art;
	//$util=(( ($refPrecio[$codBarras]-$costo) /$refPrecio[$codBarras] )) - $descuento/100;
	$util=util(($pvp * $cant),($costo*$cant),$iva,"per");
	//if($util<0)$util=$util*-1;
	//$utilidad=$util*($cant*$refPrecio[$codBarras]);
	$utilidad=util(($pvp * $cant),($costo*$cant),$iva,"tot");
	
	
		
		$tot_abono+=$abono;
		
		
		
		$tot_saldo+=$art_sub;
		$tot_util+=$utilidad;
		$tot_venta_sin+=$cant*$refPrecio[$codBarras];
		
		$tot_costo_ventas_iva+=($costo*$cant)*(1+($iva/100));
		$tot_ventas+=($pvp * $cant);
		
		$TOT_COSTO_VENDIDOS_IVA+=($costo*(1+($iva/100)))*$cant;
		$TOT_VENTAS+=$art_sub;
		$TOT_ABONO+=$abono;
		$TOT_SALDO+=$art_sub;
		$TOT_UTIL+=$utilidad;
		$TOT_VENTAS_SIN+=$cant*$refPrecio[$codBarras];
		
		?>
 <tr style="font-size:10px;">
 <td><?php echo "$pre $numFac" ?></td>
 <td><?php echo $fecha ?></td>
 <td><?php echo $des ?></td>
 <td><?php echo $cant ?></td>
 <!--
 <td><?php //echo money(redondeo($costo)) ?></td>
 <td><?php //echo money($refPrecio[$codBarras]*1) ?></td>
 <td><?php //echo money(redondeo($pvp)) ?></td>
  <td><?php echo redondeo2($descuento) ?>%</td>
 -->

 <!--
 <td><?php echo $iva ?></td>
 <td><?php echo money($iva_art) ?></td>
  <td><?php echo money($cant*$refPrecio[$codBarras]) ?></td>
 -->

 <td><?php echo money($art_sub) ?></td>
 <?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
 <td><?php echo redondeo2($util) ?>%</td>
  <td><?php echo money(redondeo($utilidad)) ?></td>
  <?php 
}
?>
 
 
   
 
 </tr>
        <?php
/*
if($pagBreak==20&&$once==0){
	//eco_alert("once: $once");
	
	  //aecho "<tr  ><td colspan=7> <br clear=all style='mso-special-character:line-break;page-break-before:always'></td></tr>";
	  ?>
      </table>
     <!-- <br clear=all style="mso-special-character:line-break;page-break-before:always">-->
     <table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%">
      <?php
	  
	 $once=1;
	$pagBreak=0; 
	  }	
	
	
 if($pagBreak==26){
	  //aecho "<tr  ><td colspan=7> <br clear=all style='mso-special-character:line-break;page-break-before:always'></td></tr>";
	  ?>
      </table>
       <!-- <br clear=all style="mso-special-character:line-break;page-break-before:always">-->
     <table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%">
      <?php
	  
	  $pagBreak=0; 
	  }
*/
		
	}///////////////////////////////////// FIN WHILE /////////////////////////////////////////////////////////////////////////////////////

	?> 
<tr style="font-size:10px;">
<tH colspan="4"><b>TOTAL CLIENTE <?php echo $nom_clientes[$i] ?></b></th>
<!--<td><?php  ?></td>

<td><?php echo money($tot_venta_sin) ?></td>
-->

<td><?php  echo money($tot_saldo)?></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
$util_per=0;
if($tot_venta_sin>0)$util_per=($tot_util/$tot_venta_sin)*100;
?>
<td><?php echo util_tot($tot_ventas,$tot_costo_ventas_iva,"per") ?>%</td>
<td><?php  echo money(redondeo($tot_util))?></td>
<?php 
}
?>


</tr> 
    
    <?php
	}// fin validacion if rs, FOR
	
}


?>

<tr style="font-size:12px; font-weight:bold;font-family: 'Arial Black', Gadget, sans-serif;">
<tH colspan="4"><b>TOTAL VENTAS</b></th>
<!--
<td><?php echo money($TOT_ABONO) ?></td>
<td><?php  echo money($TOT_VENTAS_SIN)?></td>
-->

<td><?php  echo money(redondeo($TOT_SALDO) )?></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
$util_per=0;
if($TOT_VENTAS_SIN>0)$util_per=($TOT_UTIL/$TOT_VENTAS_SIN)*100;
?>
<td><?php echo util_tot($TOT_SALDO,$TOT_COSTO_VENDIDOS_IVA,"per") ?>%</td>
<td><?php  echo money(redondeo($TOT_UTIL))?></td>
<?php 
}
?>


</tr> 
</table>
<hr align="center" width="100%" />
</div>
</body>
</html>