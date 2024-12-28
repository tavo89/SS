<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl ){header("location: centro.php");}
$boton="";
$num_fac="";
$fechaI="";
$fechaF="";
if(isset($_REQUEST['fechaI']))$fechaI=$_REQUEST['fechaI'];
if(isset($_REQUEST['fechaF']))$fechaF=$_REQUEST['fechaF'];
if(isset($_REQUEST['boton'])){
//$num_fac= limpiarcampo($_REQUEST['num_fac']);
$boton=$_REQUEST['boton'];
$fechaI=$_REQUEST['fechaI'];
$fechaF=$_REQUEST['fechaF'];

}



if($boton=='Compras Almacen'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("arqueo_compras_almacen.php","COMPRAS","850px","500px");	

	
}

if($boton=='RESUMEN COMPRAS'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("informe_resumen_compras.php","RESUMEN COMPRAS","850px","500px");	

	
}

if($boton=='COMPRAS X PRODUCTO'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("informe_compras_producto.php","RESUMEN COMPRAS","850px","500px");	

	
}

if($boton=='Retenciones a Proveedores'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("informe_retenciones_compras.php","RETENCIONES COMPRAS","850px","500px");	

	
}

if($boton=='Lista de Proveedores')
{
popup("informe_proveedores.php","LISTADO DE CLIENTES","850px","500px");	
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
<h1  align="center">INFORMES</h1>
<h1  align="center">COMPRAS Y PROVEEDORES</h1>
<?php //echo "boton:".$boton." ; dep: ".$dep."<br>INSERT INTO departamento (departamento) VALUES ('$dep')"; ?>
<form action="" method="get" name="anular" id="form_fac">
<div id="query"></div>

<table  width="600px" align="center" style="color:#FFF">
<tr >
<td style="color:#FFF"><label></label></td>
</tr>
<tr>

<td width="400px">
<table id="fecha" align="center" border="0"  style="color:#FFF">
<tr style="font-size:18px;">
<td style="color:#FFF;" colspan="2" align="right">Desde:

<input size="10" name="fechaI" value="<?php echo $fechaI ?>" type="date" id="f_ini"  placeholder="Fecha Inicial">
</td>

<td colspan="2">
Hasta:
<input size="15" value="<?php echo $fechaF ?>" type="date" name="fechaF" id="f_fin"   placeholder="Fecha Final">
</td>
<td>

</td>
</tr>
<tr>
<td><input  type="submit" value="Compras Almacen" name="boton" class="uk-button uk-button-large"  /></td>

<td><input  type="submit" value="COMPRAS X PRODUCTO" name="boton" class="uk-button uk-button-large uk-button-success"  /></td>

<td><input  type="submit" value="RESUMEN COMPRAS" name="boton" class="uk-button uk-button-large uk-button-success"  /></td>

<td><input  type="submit" value="Lista de Proveedores" name="boton" class="uk-button uk-button-large" /></td>
<td>&nbsp;</td>

</tr>
<!--
<tr>
<td colspan="2"><input  type="submit" value="Retenciones a Proveedores" name="boton" class="uk-button uk-button-large" /></td>
</tr>
-->
</table>

</td>
</tr>


</table>
</form>
</div>
<?php require_once("FOOTER.php"); ?>	
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
</body>
</html>