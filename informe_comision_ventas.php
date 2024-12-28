<?php
require_once("Conexxx.php");

$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$clase=$_SESSION["clases"];
$fab=$_SESSION["fabs"];
$F="";
$F2="";
//////////////////////////////////// NOMBRE /////////////////////////
if(isset($_SESSION['nom_cli_inf2']) && !empty($_SESSION['nom_cli_inf2']))
{
	$nom_cli=limpiarcampo($_SESSION['nom_cli_inf2']);
	$F=" AND (nom_cli='$nom_cli' OR id_cli='$nom_cli')";
	$F2=" AND (nombre='$nom_cli' OR id_usu='$nom_cli')";
	
}

?>

<!DOCTYPE html  >
<html  >
<head>
<?php require_once("HEADER_UK.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
 <link href="JS/print.css" rel="stylesheet" type="text/css" media="print"> 
<title>COMPROBANTE DE INFORME DIARIO DE VENTAS</title>
 
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</head>

<body  >

<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>
<td>
<?php echo $PUBLICIDAD2 ?></td>
<td valign="top">
<p align="left" style="font-size:12px;">
<span align="center" style="font-size:24px"><B>Total Ventas por Comisi&oacute;n </B></span>
</p>
</td>

</tr>
</table>
Fecha: <?PHP echo $hoy ?>
<br>
<table align="center" width="100%">
<tr style="font-size:24px; font-weight:bold;">
<td>
Desde: <?PHP echo $_SESSION['fechaI'] ?>
</td>
<td> Hasta: <?PHP echo $_SESSION['fechaF'] ?>
</td>
</tr>
</table>
</td>
</tr>

</table>
<?php





?>
<BR /><BR /><BR />
<p align="left" style="font-size:16px;">


</p>

<table   cellpadding="5" cellspacing="3"  style="max-width:21.5cm;" class="uk-table">
<thead>
</thead>
<tbody>
<?php

$total_tec[]=0;
$codComiTec[]="";
$totVendedores=0;
$idTecnicos[]="";
$NomTecnicos[]="";
$CcTecnicos = array();
$i=0;
$rs=$linkPDO->query("SELECT * FROM usuarios   WHERE cod_comision!=''  AND cod_su=$codSuc $F2 AND cod_comision IN(SELECT cod_comision FROM fac_venta WHERE nit=$codSuc AND  DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND ".VALIDACION_VENTA_VALIDA.") ORDER BY nombre");
//echo "SELECT * FROM usuarios   WHERE cod_comision!=''  AND cod_su=$codSuc $F2 ORDER BY nombre";
while($row=$rs->fetch()){

$nomTec=ucwords(strtolower(htmlentities($row["nombre"], ENT_QUOTES,"$CHAR_SET")));
$idTec=$row["cod_comision"];
$idTecnicos[$i]=$idTec;
$NomTecnicos[$i]=$nomTec;
$CcTecnicos[$i]=$row["id_usu"];
$total_tec[$idTec]=0;
$codComiTec[$i]=$row["cod_comision"];
//echo "<li>cod: $codComiTec[$i] </li>";
$i++;

}
if(count($CcTecnicos)===0 || count($NomTecnicos)===0 || count($codComiTec)===0 ){

}
else {


for($i=0;$i<count($idTecnicos);$i++)
{
	
	
	 ?>
     
     <tr>
     
     <td colspan="6" style="font-size:14px; font-weight:bold;" class="uk-text-large uk-text-bold uk-text-center uk-panel-box-primary"><?php echo "$NomTecnicos[$i]"; ?></td>
     
     </tr>
     <tr style="font-size:14px; font-weight:bold;" class="uk-block-secondary uk-contrast">
     <td width="90px">Factura</td>
     <td width="190px">TOT. Factura</td><td>Comisi&oacute;n (%)</td><td width="100px">Comisi&oacute;n $</td><td width="150px">Pago Comisi&oacute;n</td><td width="100px">FECHA</td>
	</tr>
     <?php
	 
	$sql="SELECT * FROM fac_venta  WHERE nit=$codSuc AND  DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND ".VALIDACION_VENTA_VALIDA." AND (cod_comision='$codComiTec[$i]' AND cod_comision!='')";
	//echo "<li>$sql</li>";
	$rs=$linkPDO->query($sql);
	$tipoComi=0;
	$url_pago="idBene=$CcTecnicos[$i]&nomBene=$NomTecnicos[$i]&feI=$fechaI&feF=$fechaF&pay_comi=1&codComi=$codComiTec[$i]";
	while($row=$rs->fetch())
	{
		 if($row["tipo_comi"]=="Venta Directa"){$tipoComi=10;}
		 else {$tipoComi=5;}
		 
		 $valComi=redondeo($row["sub_tot"]*($tipoComi/100));
		 //$total_tec[$idTecnicos[$i]]+=$valComi;
		 $estadoPago=$row["estado_comi"];
		 if($estadoPago!="PAGADO"){$total_tec[$idTecnicos[$i]]+=$valComi;}
		 
		
			?>
            
            
            <tr class="uk-text-small">
            <td><?php echo "$row[prefijo] <b>$row[num_fac_ven]</b>"; ?></td>
            <td><?php echo money3(redondeo("$row[sub_tot]") ); ?></td>
            <td><?php echo "$tipoComi %"; ?></td>
            <td align="right"><?php echo money3("$valComi"); ?></td>
             <td><?php echo "$estadoPago"; ?></td>
             <td><?php echo "$row[fecha]"; ?></td>
            </tr>
            
            <?php 
	}
	$url_pago.="&valPago=".$total_tec[$idTecnicos[$i]];
	
	?>
    
    <tr>
     
     <td colspan="6" style="font-size:16px; font-weight:bold;">TOTAL COMISIONES <?php echo "$NomTecnicos[$i] : ".money3($total_tec[$idTecnicos[$i]]); ?> <input class="uk-button uk-button-success" type="button" value="Comprobante Pago" name="" id=""  onClick="print_pop('comp_egreso.php?<?php echo "$url_pago";?>','EGRESO',600,650)"></td>
     
     </tr>
    <?php
	
}
}
?>
</tbody>
<tfoot>
<tr style="font-size:20px; font-weight:bold;">
<th>Total</th><th><?php echo money3(array_sum($total_tec)) ?></th>
</tr>
</tfoot>
</table>

<BR /><BR /><BR />
<hr align="center" width="100%" />
<table width="100%" cellpadding="4" style="font-size:18px;">
<tr>
<td>C./Coordinador:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td>
Jefe venta POS:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td >
Contador:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
</tr>
</table>

<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 

 </div>
 <script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>
<?php require_once("FOOTER_UK.php"); ?>
</body>
</html>