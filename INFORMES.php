<?php
require_once("Conexxx.php");
$boton="";
$fechaI="";
$fechaF="";
if(isset($_REQUEST['fechaI'])){$fechaI=$_REQUEST['fechaI'];$fechaF=$_REQUEST['fechaI'];}

if(isset($_REQUEST['boton'])){
//$num_fac= limpiarcampo($_REQUEST['num_fac']);
$boton=r('boton');
}
if($boton=='Inventario'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("informe_inventario_dias.php","","900px","700px");	
}
if($boton=='Lista de Clientes')
{
popup("informe_clientes.php","LISTADO DE CLIENTES","850px","500px");	
};

if($boton=='Lista de Productos')
{
popup("informe_productos.php","LISTADO DE CLIENTES","850px","500px");	
};
if($boton=='Lista de Proveedores')
{
popup("informe_proveedores.php","LISTADO DE CLIENTES","850px","500px");	
};


if($boton=='Lista Facturas ANULADAS')
{
popup("informe_anuladas.php","LISTADO DE CLIENTES","850px","500px");	
};
if($boton=='Costo Inventario')
{
popup("informe_costo_inv.php","LISTADO DE CLIENTES","850px","500px");	
};
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

<h1 align="center">INFORMES INVENTARIO</h1>

<form  method="post" name="anular" id="form_fac" class="uk-form">
<div id="query"></div>

<table  width="600px" align="center" style="color:#FFF">

<tr>
<td width="400px">

<table id="fecha" align="center" border="0"  style="color:#FFF">
<tr>
<td><input  type="submit" value="Costo Inventario" name="boton" class="uk-button uk-button-large" /></td>
<?php 
if($rolLv==$Adminlvl && $codSuc>0){
?>
<td><input  type="submit" value="Lista de Productos" name="boton" class="uk-button uk-button-large"  /></td>
<?php }?>
</tr>
</table>

</td>
</tr>


</table>

<h1 align="center">INVENTARIO POR D&Iacute;A</h1>
<table id="fecha" align="center" border="0"  style="color:#FFF">
<tr>
<td colspan="3" align="center">
D&Iacute;A :<BR>
<input size="10" name="fechaI" value="<?php echo $fechaI ?>" type="date" id="f_ini" onClick=""   placeholder="dd-mm-aaaa">
</td>
</tr>
<tr>
<td colspan="3" align="center"><input  type="submit" value="Inventario" name="boton" class="uk-button uk-button-large"  /></td>
</tr>
</table>

</form>
</div>
<?php require_once("FOOTER.php"); ?>	
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
</body>
</html>