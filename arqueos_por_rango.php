<?php
require_once("Conexxx.php");
$boton="";
$num_fac="";
$fechaI="";
$fechaF="";
$horaI=r("horaI");
$horaF=r("horaF");
$caja=r('caja');
$serial=r("serial");

if(isset($_REQUEST['fechaI']))$fechaI=$_REQUEST['fechaI'];
if(isset($_REQUEST['fechaF']))$fechaF=$_REQUEST['fechaF'];
if(isset($_REQUEST['boton'])){
//$num_fac= limpiarcampo($_REQUEST['num_fac']);
$boton=$_REQUEST['boton'];
$fechaI=$_REQUEST['fechaI'];
$fechaF=$_REQUEST['fechaF'];

}

if($boton=='EXOGENAS'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("INFORME_EXOGENA.php","Informe EXOGENAS","1100px","700px");
	
	
}

if($boton=='List. Fac ventas Productos'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['horaI']=$horaI;
	$_SESSION['horaF']=$horaF;
	$_SESSION['cod_caja_arq']=$caja;
	popup("arqueo_fac_repuestos.php?fe_i=$fechaI&fe_f=$fechaF","Abonos Cartera", "950px","650px");

	 
	
}

if($boton=='Lista Fac. CREDITOS'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['horaI']=$horaI;
	$_SESSION['horaF']=$horaF;
	$_SESSION['cod_caja_arq']=$caja;
	popup("arqueo_fac_repuestos-CREDITOS.php?fe_i=$fechaI&fe_f=$fechaF","Abonos Cartera", "950px","650px");

	 
	
}

if($boton=='List. Fac ventas Servicios'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['horaI']=$horaI;
	$_SESSION['horaF']=$horaF;
	$_SESSION['cod_caja_arq']=$caja;
	popup("arqueo_fac_servicios.php?fe_i=$fechaI&fe_f=$fechaF","Abonos Cartera", "950px","650px");

	 
	
}
if($boton=="Abonos Cartera")
{
	popup("informe_abonos_credito.php?fe_i=$fechaI&fe_f=$fechaF&idCli=arqueo_general","Abonos Cartera", "950px","650px");
};

if($boton=="Abonos Anticipos")
{
	popup("inf_abonos_anticipos.php?fe_i=$fechaI&fe_f=$fechaF&idCli=arqueo_general","Abonos Cartera", "950px","650px");
};

if($boton=='Fac. Anticipos'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['horaI']=$horaI;
	$_SESSION['horaF']=$horaF;
	$_SESSION['cod_caja_arq']=$caja;
	popup("inf_fac_anticipos.php?fe_i=$fechaI&fe_f=$fechaF","Fac. Anticipos", "950px","650px");

	 
	
}
 

if($boton=='Cajas sedes'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['horaI']=$horaI;
	$_SESSION['horaF']=$horaF;
	$_SESSION['numSerialArq']=$serial;
	if(!empty($caja)){
		$_SESSION['cod_caja_arq']=$caja;
		popup("arqueo_cajas_por_cajero.php","Informe Facturas Venta Anuladas","900px","600px");
	}
	else{
		unset($_SESSION['cod_caja_arq']);
	popup("arqueo_cajas_diarias.php?opc_multi=1","Informe Facturas Venta Anuladas","900px","600px");
	
	}
	
}
if($boton=='Total Cajas'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['horaI']=$horaI;
	$_SESSION['horaF']=$horaF;
	$_SESSION['numSerialArq']=$serial;
	
	if(!empty($caja)){
		$_SESSION['cod_caja_arq']=$caja;
		popup("arqueo_cajas_por_cajero.php","Informe Facturas Venta Anuladas","900px","600px");
	}
	else{
	popup("arqueo_cajas_diarias.php","Informe Facturas Venta Anuladas","900px","600px");
	
	}
	
}
if($boton=='Total Ventas'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("arqueo_ventas_totales.php","Informe Facturas Venta Anuladas","900px","600px");

	
}

if($boton=='Lista Fac. de Venta'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("ventas_informe_detallado.php","Informe Facturas Venta Anuladas","900px","600px");
	
	
}

if($boton=='Lista Remisiones'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("informe_remisiones.php","Informe ","900px","600px");
	
	
}


if($boton=='Venta por Producto'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['horaI']=$horaI;
	$_SESSION['horaF']=$horaF;
	popup("arqueo_ventas_art.php","Informe Facturas Venta Anuladas","900px","600px");

	
}



if($boton=='Fac. Venta Anuladas'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("informe_anuladas.php","Informe Facturas Venta Anuladas","900px","600px");
};
if($boton=='Resumen Ventas'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("arqueo_resumen_ventas.php","Informe Facturas Venta Anuladas","900px","600px");
};
if($boton=='Relacion Gastos'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("informe_egresos_resumen.php","Informe Facturas Venta Anuladas","900px","600px");
};

if($boton=='RESUMEN Gastos'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	//popup("informe_egresos_resumen.php?TIPO_INF=2","Informe Facturas Venta Anuladas","900px","600px");
	popup("RESUMEN_GASTOS.php?TIPO_INF=2","Informe Gastos","900px","600px");
};
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("HEADER.php"); ?>
</head>
<body >

<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>	


<div class="uk-width-9-10 uk-container-center">
<div class=" grid-100 posicion_form">


<form action="arqueos_por_rango.php" method="get" name="anular" id="form_fac" class="uk-form">
<div id="query"></div>
<h1 align="center">Arqueos de Caja </h1>

<br><br>

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

<!--              
<input size="10" name="fechaI" value="<?php echo $fechaI ?>" type="date" id="f_ini"  placeholder="Fecha" class="uk-form-large uk-animation-hover  uk-animation-reverse uk-animation-scale">
-->
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
<tr  style="font-size:24px;">
<td align="center" colspan="3">
<br>
<i class="uk-icon-big uk-icon-clock-o"></i>
HORA</td>
</tr>
<tr>
<td>Desde:<input type="time" name="horaI" value="<?php echo $horaI?>"></td><td>Hasta:<input type="time" name="horaF" value="<?php echo $horaF?>"></td>
</tr>

</table>

</td>
<td align="left" ><img id="loader" src="Imagenes/ajax-loader.gif" width="31" height="31"  style="position:relative; left:0px"/></td>
</tr>


</table>
<div align="center">

<div class="uk-margin">
                             
<div class="uk-button-group">
<button class="uk-button uk-button-large uk-animation-shake uk-button-success" onClick="submit();" name="boton" value="Total Cajas">Consolidado Cajas</button>
<?php
if($MODULES["MULTISEDES_UNIFICADAS"]==1){
?>
<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Cajas sedes">Cajas (Consolidado Sedes)</button>
<?php
}
?>
<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="EXOGENAS">EXOGENAS</button>

<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Lista Fac. de Venta">Lista Fac. de Venta</button>

<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="List. Fac ventas Productos">List. Fac ventas Productos</button>

<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="List. Fac ventas Servicios">List. Fac ventas Servicios</button>


<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Lista Remisiones">Lista Remisiones</button>
<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Fac. Anticipos">Fac. Anticipos</button>
<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Venta por Producto">Venta por Producto</button>
</div>

</div>

<div class="uk-margin">
<div class="uk-button-group">
<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Abonos Cartera">Abonos Cartera</button>
<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Abonos Anticipos">Abonos Anticipos</button>
<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Lista Fac. CREDITOS">Lista Fac. CREDITOS</button>
<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Relacion Gastos">Gastos (Detallado)</button>
<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="RESUMEN Gastos">Gastos (Resumen)</button>
<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Fac. Venta Anuladas">Fac. Venta Anuladas</button>
<!--
<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Consolidado Ventas, Compras, Inventario">Consolidado Ventas, Compras, Inventario</button>
-->
</div>
</div>

</div>

</form>
</div>
<?php require_once("FOOTER.php"); ?>	
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
</body>
</html>