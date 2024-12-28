<?php
require_once("Conexxx.php");
word("Informe Ventas por Factura $FechaHoy");
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



if(isset($_SESSION['dcto'])&&!empty($_SESSION['dcto'])){$cli=$_SESSION['dcto'];$D=" AND ='$cli'";}
if(isset($_SESSION['pro'])&&!empty($_SESSION['pro'])){$pro=$_SESSION['pro'];$C=" AND fab='$pro'";}

if(isset($_SESSION['orden_ven'])&&!empty($_SESSION['orden_ven']))
{$orden=$_SESSION['orden_ven'];

if($orden=="mayor")$ORDER_BY=" ORDER BY tot DESC";
else $ORDER_BY=" ORDER BY tot ASC";
}


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
$rs=$linkPDO->query("SELECT * from fac_venta WHERE nit=$codSuc AND ".VALIDACION_VENTA_VALIDA." $B $A $E order by 3");
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

////////////////////////////////////////////////////////// UTILIDAD /////////////////////////////////////////////////////////////////////////////////

$sql="SELECT b.anulado,a.fab,b.ciudad,b.nit, b.id_cli,b.nom_cli,a.ref,a.des,a.cant,a.iva as iva_art,a.precio,a.sub_tot as  art_sub,a.dcto,a.costo,b.num_fac_ven,b.prefijo,b.sub_tot,b.iva,b.tot,DATE(b.fecha) as fecha,b.descuento,b.vendedor FROM (SELECT ref,cod_barras,prefijo,num_fac_ven,des,iva,precio,sub_tot,b.fab,cant,dcto,costo FROM art_fac_ven a INNER JOIN ".tabProductos." b ON a.ref=b.id_pro) a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven  WHERE a.prefijo=b.prefijo AND b.nit=$codSuc AND b.".VALIDACION_VENTA_VALIDA."  $A $C $E $B";

 
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
		$nomCli= htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET");
	

	$cant = htmlentities($row["cant"]*1);
	$pvp = htmlentities($row["precio"]*1);
	$iva=$row['iva_art'];
	$costo=$row['costo']*1;
	$costoIVA=$costo*(1+($iva/100));
	$sub_tot = htmlentities($row["sub_tot"]*1);
	
	$descuento = $row["dcto"]*1;
	//$util=(( ($refPrecio[$ref]-$costo) /$refPrecio[$ref] )) - $descuento/100;
	$util=util($pvp*$cant,$costo*$cant,$iva,"per");
	//if($util<0)$util=$util*-1;
	
	$utilidad=util($pvp*$cant,$costo*$cant,$iva,"tot");
	
	
	
	$UTIL[$num_fac][$pre]+=$utilidad;

	$TOT_UTIL+=$utilidad;
	$TOT_VENTAS_COSTO_IVA+=$costoIVA*$cant;
	$TOT_VENTAS_PVP+=$pvp*$cant;
	
	$TOT_COSTO_IVA[$num_fac][$pre]+=$costoIVA*$cant;
	$TOT_COSTO_SIN[$num_fac][$pre]+=$costo*$cant;
		
		
	
		
	}///////////////////////////////////// FIN WHILE UTILIDADES /////////////////////////////////////////////////////////////////////////////////////
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html
    xmlns:o='urn:schemas-microsoft-com:office:office'
    xmlns:w='urn:schemas-microsoft-com:office:word'
    xmlns='http://www.w3.org/TR/REC-html40'>
<head>
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
	
}
@page Section1 { }
div.Section1 { page:Section1; }
p.MsoHeader, p.MsoFooter { border: 1px solid black; }
td { page-break-inside:avoid; }
tr { page-break-after:avoid; }
 
    </style>

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
<table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%" style="font-size:12px;">
<thead>
<tr>
<th >No. Factura</th>
<th >C.C/NIT</th>
<th >Cliente</th>
<!--<th width="60"> </th>-->

<!--<th>Tot. Sdcto</th>-->
<th>SUB TOTAL</th>
<th>IVA</th>
<th>TOTAL</th>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<th > Util. %</th>
<th> Utilidad</th>
<?php 
}
?>
<th>FECHA</th>
</tr>
</thead>
<tbody>
<?php
$tot_saldo=0;
$tot_abono=0;
$TOT_ABONO=0;
$TOT_SALDO=0;
$TOT_IVA=0;
$TOT_SUB=0;

$sql="SELECT * FROM  fac_venta b  WHERE b.nit=$codSuc AND b.".VALIDACION_VENTA_VALIDA." $A $C $E $B  $ORDER_BY";

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
	$pagBreak=0;
	$once=0;
	while($row=$rs->fetch())
	{
		$pagBreak++;
		$num_fac=$row['num_fac_ven'];
		$pre=$row['prefijo'];
		
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
		$subF=$row['sub_tot']*1;
		$ivaF=$row['iva']*1;
		$totF=$row['tot']*1;
		$TOT_FACTURAS+=$totF;
		$TOT_IVA+=$ivaF;
		$TOT_SUB+=$subF;
		
		//$cant = htmlentities($row["cant"]*1);
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
		
		?> 
<tr>
<td colspan=""><?php echo "$pre $num_fac" ?></td>
<td colspan=""><?php echo $idCli ?></td>
<td colspan=""><?php echo $nomCli ?></td>
<td align="center"><?php echo $subF ?></td>
<td align="center"><?php echo $ivaF ?></td>
<td><?php  echo money($totF)?></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<td><?php echo util_tot($tot_saldo,$TOT_COSTO_IVA[$num_fac][$pre],"per"); ?></td>
<td><?php  echo money(util_tot($tot_saldo,$TOT_COSTO_IVA[$num_fac][$pre],"tot"))?></td>
<?php 
}
?>
<td colspan=""><?php echo $fecha ?></td>
</tr> 
    
    <?php
	
	if($pagBreak==20&&$once==0){
	//eco_alert("once: $once");
	
	  //aecho "<tr  ><td colspan=7> <br clear=all style='mso-special-character:line-break;page-break-before:always'></td></tr>";
	  ?>
      </table>
     <br clear=all style="mso-special-character:line-break;page-break-before:always">
    <table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%">
      <?php
	  
	 $once=1;
	$pagBreak=0; 
	  }	
	
	
 if($pagBreak==26){
	  //aecho "<tr  ><td colspan=7> <br clear=all style='mso-special-character:line-break;page-break-before:always'></td></tr>";
	  ?>
      </table>
   <br clear=all style="mso-special-character:line-break;page-break-before:always">
     <table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%">
      <?php
	  
	  $pagBreak=0; 
	  }
		
	}///////////////////////////////////// FIN WHILE /////////////////////////////////////////////////////////////////////////////////////

	
	}// fin validacion if rs, FOR
	
//}


?>
</tbody>
</table>

<table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%" >
<thead>
<tr>
<th width="100"></th>
<th width="150"></th>
<!--<th width="60"> </th>-->
<th width="100">SUB TOTAL</th>
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
<tr style="font-size:16px; font-weight:bold;font-family: 'Arial Black', Gadget, sans-serif;">
<tH colspan="2"><b>TOTAL VENTAS</b></th>
<td align="center"><?php echo money($TOT_SUB) ?></td>
<td align="center"><?php echo money($TOT_IVA) ?></td>
<!--<td><?php  echo money($tot_venta_sin)?></td>-->
<td><?php  echo money($TOT_SALDO)?></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
$uti_per=0;
if($tot_venta_sin>0)$uti_per=($TOT_UTIL/$tot_venta_sin)*100;
?>
<td><?php echo util_tot($TOT_VENTAS_PVP,$TOT_VENTAS_COSTO_IVA,"per"); ?>%</td>
<td><?php  echo money(util_tot($TOT_VENTAS_PVP,$TOT_VENTAS_COSTO_IVA,"tot")) ?></td>
<?php 
}
?>

</tr> 
</table>
<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <!--
    

<input  type="button" value="MS Word" name="boton" onMouseDown="location.assign('informeVentasCli2MSW.php');" />
<input  type="button" value="MS Excel" name="boton" onMouseDown="location.assign('informeVentasCli2MSE.php');" />
    
    -->
        <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 

</div>
<hr align="center" width="100%" />

</body>
</html>