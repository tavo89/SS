<?php
require_once("Conexxx.php");
$fechaI="";
$fechaF="";


?>
<head>
<?php require_once("HEADER.php"); ?>

<link href="css/multi-select.css" rel="stylesheet" type="text/css" />

</head>
<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>	


<div class="uk-width-9-10 uk-container-center">
<?php require_once("sub_menu_informes.php"); ?>

<div class=" grid-100 posicion_form">
<h1 align="center">INFORMES VENTAS </h1>
<?php //echo "boton:".$boton." ; dep: ".$dep."<br>INSERT INTO departamento (departamento) VALUES ('$dep')"; ?>
<form  method="post" name="anular" id="form_fac" class="uk-form">
<div id="query"></div>

<table  width="600px" align="center" style="color:#FFF">
<tr >
<td style="color:#FFF"><label></label></td>
</tr>

<tr>

<td width="400px">
<table id="fecha" align="center" border="0"  style="color:#FFF">
<tr>
<td>
Fecha:
</td>
<td style="color:#FFF" colspan="" width="350">
  <input size="10" name="fechaI" value="<?php echo $fechaI ?>" type="date" id="f_ini" onClick="//popUpCalendar(this, form_fac.f_ini, 'yyyy-mm-dd');">
</td>

<td>

<input size="15" value="<?php echo $fechaF ?>" type="date" name="fechaF" id="f_fin" onClick="//popUpCalendar(this, form_fac.f_fin, 'yyyy-mm-dd');" >
</td>
<td>

</td>
</tr>


<tr>
<td>GASTOS:</td>
<td colspan="2"> 
<select multiple="multiple" name="gastos[]" id="gastos">
<?php
echo tipoEgresoOpt();
?>
</select></td>
</tr>



<tr style="font-size: 36px;">
<td colspan="2" align="center"><input  type="submit" value="INFORME INGRESOS NETOS" name="boton" class="button" />
</td>
</tr>

</table>

</td>

</tr>


</table>
</form>
</div>

<?php require_once("FOOTER.php"); ?>	
<?php require_once("autoCompletePack.php"); ?>	
<script type="text/javascript" language="javascript1.5" src="JS/UNIVERSALES.js?<?php echo $LAST_VER ?>"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery.multi-select.js"></script>
<script type="text/javascript" language="javascript1.5">
$(document).ready( function() {

$('#gastos').multiSelect();
//call_autocomplete('NOM',$('#nom_cli'),'ajax/busq_cli.php');

	
});


</script>
</body>
</html>