<?php
require_once("Conexxx.php");

if(isset($_REQUEST['id_producto']))$id_inter= $_REQUEST['id_producto'];
if(isset($_REQUEST['clase']))$clase= $_REQUEST['clase'];
if(isset($_REQUEST['class']))$class= $_REQUEST['class'];
if(isset($_REQUEST['boton']))$boton=$_REQUEST['boton'];




if($boton="Borrar Clase" && isset($class) &&!empty($class)){
$linkPDO->exec("DELETE FROM clases WHERE id_clas=$class");
}

if($boton="Guardar" && isset($clase) &&!empty($clase)){
$linkPDO->exec("INSERT INTO clases (des_clas) VALUES ('$clase')");
}

?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("HEADER.php"); ?>

</head>
<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>	


<div class="uk-width-9-10 uk-container-center">
<div class=" grid-100 posicion_form">
<?php //echo "boton:".$boton." ;class: ".$class; ?>
<form action="clases.php" method="post" name="addcla" id="form_class" class="uk-form">
<h1 align="center">Clasificaci&oacute;n inventario</h1>
<table  width="600px" align="center">
<tr>
<td><label>Clase:</label></td>
</tr>
<tr>

<td width="400px" colspan="2"><input name="clase" type="text" placeholder="Clase de Producto"/></td>
</tr>

<tr>
<td width="200px">
Clases Actuales:
</td>
</tr>
<tr>
<td>
<select name="class" data-inline="false" data-theme="a">
<option value="" selected ></option>
<?php

$rs=$linkPDO->query("SELECT * FROM clases ORDER BY des_clas");
while($row = $rs->fetch()) 
         { 
		 echo "<option value=\" ".$row["id_clas"]."\">".$row["des_clas"]."</option>  ";
		 }
?>

</select>
</td>
<td>
<input type="submit" value="Borrar Clase" name="boton" class=" uk-button">
</td>
</tr>
<tr>
<td colspan="2" align="center">
<div class="ui-grid-a" data-inline="true" data-mini="true" align="center">
	<div class="ui-block-a"><input type="submit" value="Guardar" name="boton" class=" uk-button"></div>
	<div class="ui-block-b"><a href="inventario_total.php" data-role="button" data-icon="back" id="volver">Volver</a></div>	   
</div>


</td>
</td>
</table>
</form>
</div>
</div>


<?php require_once("FOOTER.php"); ?>
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
<script type="text/javascript" language="javascript1.5" >

function class_in(){
location.assign('inventario_total.php');
};
</script>

</body>
</html>