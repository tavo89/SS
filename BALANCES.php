<?php
include_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"balances_contables")){header("location: centro.php");}
$boton="";
$num_fac="";
$fechaI=r("fechaI");
$fechaF=r("fechaF");
$fechaInv=r("fechaInv");
$horaI=r("horaI");
$horaF=r("horaF");
$caja=r('caja');
$serial=r("serial");

if(isset($_REQUEST['boton'])){
//$num_fac= limpiarcampo($_REQUEST['num_fac']);
$boton=$_REQUEST['boton'];


$_SESSION['fechaI']=$fechaI;
$_SESSION['fechaF']=$fechaF;

}

if(isset($_REQUEST['boton'])){
//$num_fac= limpiarcampo($_REQUEST['num_fac']);
$boton=$_REQUEST['boton'];
}

$validacionFechas = isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF);

if($boton=='EXOGENAS'&& $validacionFechas)
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("INFORME_EXOGENA.php","Informe EXOGENAS","1100px","800px");
	
}

if($boton=='EXOGENAS FE'&& $validacionFechas)
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("INFORME_EXOGENA_FE.php","Informe EXOGENAS - Facturas Electronicas","1100px","800px");
	
}

if($boton=='EXOGENAS 2'&&$validacionFechas)
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("INFORME_EXOGENA_PRODUCTOS.php","Informe EXOGENAS 2","1100px","800px");
	
	
}


if($boton=='Inventario'&&isset($fechaInv)&&!empty($fechaInv) )
{$_SESSION['fechaI']=$fechaInv;$_SESSION['fechaF']=$fechaInv;popup("informe_inventario_dias.php","","1100px","800px");}


if($boton=='Lista Fac. de Venta' &&$validacionFechas)
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("ventas_informe_detallado.php","Informe Facturas Venta Anuladas","1100px","800px");


}

if($boton=='Ventas' && $validacionFechas){popup("BALANCE_VENTAS.php","LISTADO DE CLIENTES","1100px","600px");	};

if($boton=='Compras' && $validacionFechas){popup("informe_resumen_compras.php","LISTADO DE CLIENTES","1100px","600px");	};

if($boton=='Compras detallado' && $validacionFechas){popup("compras_informe_detallado.php","LISTADO DE CLIENTES","1100px","600px");	};

if($boton=='Cuentas por Cobrar' && $validacionFechas){$_SESSION['fechaI_cre']=$fechaI;$_SESSION['fechaF_cre']=$fechaF;popup("estados_cuenta_publico.php","LISTADO DE CLIENTES","1100px","600px");	};

if($boton=='Cuentas por Pagar' && $validacionFechas){$_SESSION['fechaI_com']=$fechaI;$_SESSION['fechaF_com']=$fechaF;popup("cuentas_pagar2.php?mod=B","LISTADO DE CLIENTES","1100px","600px");	};

if($boton=='Cuentas por Pagar2' && $validacionFechas){$_SESSION['fechaI_com']=$fechaI;$_SESSION['fechaF_com']=$fechaF;popup("cuentas_pagar.php?mod=C","LISTADO DE CLIENTES","1100px","600px");	};

if($boton=='Ingresos-Egresos' && $validacionFechas){popup("informe_clientes.php","LISTADO DE CLIENTES","1100px","600px");	};

if($boton=='Relacion Gastos'&&$validacionFechas)
{popup("informe_egresos_resumen.php","Informe Facturas Venta Anuladas","1100px","800px");};



if($boton=='RESUMEN Gastos'&&$validacionFechas)
{popup("RESUMEN_GASTOS.php?TIPO_INF=2","Informe Facturas Venta Anuladas","1100px","800px");};

//SINMONEDA
if($boton=='RESUMEN Gastos SINMONEDA'&&$validacionFechas)
{popup("RESUMEN_GASTOS_SINMONEDA.php?TIPO_INF=2","Informe Facturas Venta Anuladas","1100px","800px");};

if($boton=='Retenciones a Proveedores'&&$validacionFechas)
{popup("informe_retenciones_compras.php","RETENCIONES COMPRAS","1100px","600px");}
//BALANCE INGRESOS
if($boton=='BALANCE INGRESOS'&&$validacionFechas)
{popup("BALANCE_INGRESOS_UTIL.php","BALANCE","1100px","600px");}


if($boton=='Relacion Gastos2'&&$validacionFechas)
{popup("informe_egresos_resumen.php?inversion=1","Informe Facturas Venta Anuladas","1100px","800px");};

if($boton=='RESUMEN Gastos2'&&$validacionFechas)
{popup("RESUMEN_GASTOS.php?TIPO_INF=2&inversion=1","Informe Facturas Venta Anuladas","1100px","800px");};
?>
<!DOCTYPE html>
<html>
<head>
<?php include_once("HEADER.php"); ?>
</head>
<body class="ui-content" >
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
			<?php include_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">
<?php include_once("sub_menu_informes.php"); ?>
<div class="grid-100 posicion_form">

<h1 align="center">INFORMES CONTABLES</h1>

<form  method="post" name="balances" id="balances" class="uk-form">
<div id="query"></div>

<table  width="600px" align="center">
<tr >
<td style="color:#FFF"><label></label></td>
</tr>
<tr>

<td width="600px">
<table id="fecha" align="center" border="0"  style="color:#FFF">

<tr>
<td colspan="3" align="center" style="font-size:26px;">
  <i class="uk-icon-big uk-icon-calendar"></i>
D&Iacute;AS</td>

</tr>
<tr>
<td align="center" colspan="">


 <div class="uk-form-icon">
    <i class="uk-icon-calendar"></i>
<input size="10" name="fechaI" value="<?php echo $fechaI ?>" type="date" id="f_ini"  placeholder="Fecha" class="uk-form-large">
</div>

</td>
<td align="center" colspan="">

  <div class="uk-form-icon">
    <i class="uk-icon-calendar"></i>
<!--
<input size="15" value="<?php echo $fechaF ?>" type="text" name="fechaF" id="f_fin"   readonly placeholder="Fecha Final" class="uk-form-large " data-uk-datepicker="{<?php echo $UKdatePickerFormat; ?>}">
-->
<input size="10" name="fechaF" value="<?php echo $fechaF ?>" type="date" id="f_fin"  placeholder="Fecha" class="uk-form-large">
</div>
</td>
</tr>


</table>

</td>
<td align="left" ><img id="loader" src="Imagenes/ajax-loader.gif" width="31" height="31"  style="position:relative; left:0px"/></td>
</tr>


</table>
<div align="center">

<div class="uk-margin">

<div class="uk-button-group">

<!-- VENTAS -->
<div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
<a class="uk-button uk-button-large " style="width:100px;">
<i class="uk-icon-dollar uk-icon-small"></i> VENTAS <i class="uk-icon-caret-down"></i>
</a>

    <!-- This is the dropdown -->
    <div class="uk-dropdown uk-dropdown-small">
        <ul class="uk-nav uk-nav-dropdown">
            <li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','Ventas')">Resumen</a></li>
            <li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','EXOGENAS FE')">EXOGENAS - FAC. ELECTRONICA</a></li>
            <li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','EXOGENAS')">EXOGENAS</a></li>
			<li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','EXOGENAS 2')">EXOGENAS 2</a></li>
        </ul>
    </div>

</div>

<!-- COMPRAS -->
<div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
<a class="uk-button uk-button-large " style="width:150px;">
<i class="uk-icon-folder-open-o uk-icon-small"></i> COMPRAS <i class="uk-icon-caret-down"></i>
</a>

    <!-- This is the dropdown -->
    <div class="uk-dropdown uk-dropdown-small">
        <ul class="uk-nav uk-nav-dropdown">
            <li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','Compras')">Resumen</a></li>
            <li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','Compras detallado')">Detallado</a></li>
			<li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','Cuentas por Pagar')">Cuentas por Pagar(Mes)</a></li>
			<li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','Cuentas por Pagar2')">Cuentas por Pagar(Total)</a></li>
			<li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','Retenciones a Proveedores')">Retenciones Compras</a></li>
        </ul>
    </div>

</div>


<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Cuentas por Cobrar"><i class="uk-icon-credit-card uk-icon-small"></i> Cuentas por Cobrar</button>

<!-- GASTOS -->
<div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
<a class="uk-button uk-button-large " style="width:190px;">
<i class="uk-icon-bank uk-icon-small"></i> GASTOS Y EGRESOS <i class="uk-icon-caret-down"></i>
</a>

    <!-- This is the dropdown -->
    <div class="uk-dropdown uk-dropdown-small">
        <ul class="uk-nav uk-nav-dropdown">
            <li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','Relacion Gastos')">Gastos Detallado</a></li>
            <li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','RESUMEN Gastos')">Gastos RESUMEN</a></li>
			<li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','RESUMEN Gastos SINMONEDA')">Gastos RESUMEN (Sin formato moneda)</a></li>
			<li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','Relacion Gastos2')">Relacion Egresos TODOS</a></li>
			<li><a href="#"  onClick="subirFormyBoton('BALANCES.php','balances','boton','RESUMEN Gastos2')">RESUMEN Egresos TODOS</a></li>

        </ul>
    </div>

</div>
</div>

</div>

<div class="uk-margin">
<div class="uk-button-group">


<button class="uk-button uk-button-large uk-button-success" onClick="submit();" name="boton" value="BALANCE INGRESOS"><i class="uk-icon uk-icon-balance-scale"></i> BALANCE INGRESOS</button>

<!--<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Consolidado Ventas, Compras, Inventario">Consolidado Ventas, Compras, Inventario</button>-->

</div>
</div>


</div>

<h1 align="center">INVENTARIO POR D&Iacute;A</h1>
<table id="fecha" align="center" border="0"  style="color:#FFF">
<tr>
<td colspan="3" align="center">
D&Iacute;A :<BR>
<input size="10" name="fechaInv" value="<?php echo $fechaInv ?>" type="date" id="fechaInv" onClick=""   placeholder="dd-mm-aaaa">
</td>
</tr>
<tr>
<td colspan="3" align="center"><input  type="submit" value="Inventario" name="boton" class="uk-button uk-button-large"  /></td>
</tr>
</table>

</form>
</div>
<?php include_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
</body>
</html>
