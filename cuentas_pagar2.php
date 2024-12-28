<?php
require_once("Conexxx.php");
$url=thisURL();
$boton=r("boton");
if($boton=="MS EXCEL"){excel("Cartera Clientes");}
$MODO_PAGINA=r("mod");

$filtroCortePagos="";
if(!empty($fecha_corte_cuentas_pagar)){$filtroCortePagos=" AND DATE(fecha)>'$fecha_corte_cuentas_pagar'";}

//////////////////////////////////////////////////////////// FILTRO FECHA ///////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_com";
$PAG_fechaF="fechaF_com";
$A="";
$A2="";
$A2C="";
$A2C2="";
$B="";

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=limpiarcampo($_SESSION[$PAG_fechaI]);}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=limpiarcampo($_SESSION[$PAG_fechaF]);}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
	$A2=" AND ( (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') OR (serial_fac_com IN (SELECT serial_fac_com FROM comp_egreso WHERE cod_su=$codSuc AND anulado!='ANULADO' $A $B))) ";
	
	$A2C=" AND ( (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') AND (serial_fac_com IN (SELECT serial_fac_com FROM fac_com WHERE cod_su=$codSuc AND estado='CERRADA' $A))) ";
	
	$A2C2=" AND ( (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') AND (serial_fac_com NOT IN (SELECT serial_fac_com FROM fac_com WHERE cod_su=$codSuc AND estado='CERRADA' $A))) ";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////// FILTRO NOMBRE //////////////////////////////////////
$campo1="nombre_proveedor";
$nom="";
$nom_col="nom_pro";
if(isset($_SESSION[$campo1]) && !empty($_SESSION[$campo1])){$nom=$_SESSION[$campo1];$B=" AND ($nom_col='$nom' OR nit_pro='$nom')";}


/////////////////////////////////////////////////////////////////////////////////////////








?>
<!DOCTYPE html  >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>Estados de Cuenta</title>
<script language="javascript1.5" type="text/javascript" src="JS/jQuery1.8.2.min.js">
</script>
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</head>

<body>
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>
<td colspan="3">
<?php echo $PUBLICIDAD2 ?></td>
<td valign="top" colspan="3">
<p align="left" style="font-size:12px;">
<span align="center" style="font-size:24px"><B>ESTADOS DE CUENTA-PROVEEDORES</B></span>
</p>
</td>

</tr>
</table>
Fecha Informe: <?PHP echo $hoy ?>
<br>
<b>FILTROS</b>
<?php
if(!empty($A))echo "<li>FECHA:  Desde $fechaI Hasta $fechaF </li>";
if(!empty($B))echo "<li>PROVEEDOR:  $nom </li>";

$MODO_INFORME="A";

if($MODO_PAGINA!="B"){include("cuentas_pagar_vars.php");}
else{
	
echo "<h2>Pagos a Facturas de la Fecha SELECCIONADA</h2>";	
$MODO_INFORME="B";
include("cuentas_pagar_vars.php");	

echo "<h2>Pagos a Facturas ANTARIORES a la fecha</h2>";	
$MODO_INFORME="A";
include("cuentas_pagar_vars.php");	
}
?>


</div>
</body>
</html>