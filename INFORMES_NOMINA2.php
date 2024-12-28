<?php
require_once("Conexxx.php");
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




if($boton=='nomina asesores'){popup("informe_nomina_motos.php?opt=A","LISTADO DE CLIENTES","1200px","600px");	};


if($boton=='Ventas Motos'){popup("informe_ventas_resumen_pro.php","LISTADO DE CLIENTES","850px","500px");	};

if($boton=='Ventas Motos por Nota Entrega'){popup("informe_ventas_motos.php","LISTADO DE CLIENTES","1200px","600px");	};



?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("HEADER.php"); ?>
</head>
<body class="ui-content" >
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">
<?php require_once("sub_menu_informes.php"); ?>
<div class="grid-100 posicion_form">

<h1 align="center">INFORMES N&Oacute;MINA</h1>

<form  method="post" name="anular" id="form_fac" class="uk-form">
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
<button class="uk-button uk-button-large    " onClick="submit();" name="boton" value="nomina asesores">N&oacute;mina Asesores</button>

<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Ventas Motos">Ventas Motos</button>


<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Ventas Motos por Nota Entrega">Ventas Motos por Nota Entrega</button>
</div>

</div>

<div class="uk-margin">
<div class="uk-button-group">
 
<!--<button class="uk-button uk-button-large" onClick="submit();" name="boton" value="Consolidado Ventas, Compras, Inventario">Consolidado Ventas, Compras, Inventario</button>-->
 
</div>
</div>


</div>

</form>
</div>
<?php require_once("FOOTER.php"); ?>	
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
</body>
</html>