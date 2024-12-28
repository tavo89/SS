<?php
require_once("Conexxx.php");
$url=thisURL();
$boton=r("boton");
$resumen=r("resumen");
$filtro=r("filtro");
if($boton=="MS EXCEL"){excel("Cartera Clientes");}

$FILTRO_SEDES_FAC="AND nit=$codSuc";
if($MODULES["MULTISEDES_UNIFICADAS"]==1){$FILTRO_SEDES_FAC="";}


//////////////////////////////////////////////////////////// FILTRO FECHA ///////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_informes";
$PAG_fechaF="fechaF_informes";
$A="";
$B="";

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=limpiarcampo($_SESSION[$PAG_fechaI]);}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=limpiarcampo($_SESSION[$PAG_fechaF]);}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////// FILTRO MULTI SELECT ////////////////////////////////////////////////////////////////////////
$varName="";
$multiSelcVar=s("$varName");
$sql_col="";
$FILTRO_MULTI_A=multiSelcSql($array,$sql_col);



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>INFORME</title>
<?php require_once("../IMP_HEADER.php"); ?>

</head>

<body>
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>
<td colspan="3">
<?php echo $PUBLICIDAD2 ?></td>
<td valign="top" colspan="3">
<p align="left" style="font-size:12px;">
<span align="center" style="font-size:24px"><B>ESTADOS DE CUENTA-P&Uacute;BLICO</B></span>
</p>
</td>

</tr>
</table>
Fecha Informe: <?PHP echo $hoy ?>
<br>
<b>FILTROS</b>
<?php
if(!empty($A))echo "<li>FECHA:  Desde $fechaI Hasta $fechaF </li>";
if(!empty($B))echo "<li>CLIENTE:  $nom_cli </li>";

?>

<table frame="box" rules="all" cellpadding="5" cellspacing="3" border="1">
<thead>
<tr>
<th>No. Factura</th><th>Fecha</th><th>Valor Fac.</th><th>Abonado</th><th>Saldo</th><th>D&iacute;as Mora</th>
</tr>
</thead>
<tbody>
<?php

	?>

    
<?php

?>


</tbody>
<tfoot>
<tr id="imp">

<td colspan="3" align="center"><input type="button" value="IMPRIMIR" name="boton" onclick="imprimir()" /></td>
<td colspan="3" align="center"><input type="button" value="MS EXCEL" name="boton" onclick="location.assign('<?php echo "$url?boton=MS EXCEL" ?>')" /></td>


</tr>
</tfoot>
</table>
</div>
</body>
</html>