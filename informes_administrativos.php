<?php
require_once("Conexxx.php");
$boton="";
$num_fac="";
$fechaI="";
$fechaF="";
if(isset($_REQUEST['fechaI'])){$fechaI=$_REQUEST['fechaI'];$fechaF=$_REQUEST['fechaI'];}
//if(isset($_REQUEST['fechaF']))$fechaF=$_REQUEST['fechaF'];
if(isset($_REQUEST['boton'])){
//$num_fac= limpiarcampo($_REQUEST['num_fac']);
$boton=$_REQUEST['boton'];
$fechaI=$_REQUEST['fechaI'];
$fechaF=$_REQUEST['fechaI'];

}


if($boton=='Compras Almacen'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	
	?>
                <script language="javascript1.5" type="text/javascript">
				
window.open('arqueo_compras_almacen.php','Arqueos','width=850,height=600,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
				
				</script>
                
                <?php
	
}

if($boton=='Total ventas por vendedor'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	
	?>
                <script language="javascript1.5" type="text/javascript">
				
window.open('total_ventas_vendedores.php','Kardex Inventario','width=850,height=600,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
				
				</script>
                
                <?php
	
}

if($boton=='Clientes Registrados'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("informe_clientes_registrados.php","","900px","700px");	
}

if($boton=='Inventario'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("informe_inventario_dias.php","","900px","700px");	
}

?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("HEADER.php"); ?>	
<script type="text/javascript" language="javascript1.5" src="JS/popcalendar.js"></script>
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>

</head>
<body class="ui-content" >
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">
<div class="grid-100 posicion_form">

<h1  align="center">Informes Administrativos</h1>
<h1  align="center"> Por D&iacute;a</h1>
<?php //echo "boton:".$boton." ; dep: ".$dep."<br>INSERT INTO departamento (departamento) VALUES ('$dep')"; ?>
<form action="" method="get" name="anular" id="form_fac" class="uk-form">
<div id="query"></div>

<table  width="600px" align="center" style="color:#FFF">
<tr >
<td style="color:#FFF"><label></label></td>
</tr>
<tr>

<td width="400px">
<table id="fecha" align="center" border="0"  style="color:#FFF">
<tr>

<td colspan="3" align="center">
D&Iacute;A :
<input size="10" name="fechaI" value="<?php echo $fechaI ?>" type="date" id="f_ini" onClick="//popUpCalendar(this, form_fac.f_ini, 'yyyy-mm-dd');"   placeholder="dd-mm-aaaa">
</td>

<td>

</td>
</tr>
<tr>
<td><input  type="submit" value="Compras Almacen" name="boton" class="button"  /></td>

<td><input  type="submit" value="Total ventas por vendedor" name="boton" class="button"  /></td>
<td><input  type="submit" value="Inventario" name="boton" class="button"  /></td>
</tr>
</table>

</td>

</tr>


</table>
</form>
</div>
<?php require_once("FOOTER.php"); ?>	

</body>
</html>