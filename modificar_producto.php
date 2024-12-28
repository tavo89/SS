<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"inventario_mod")){header("location: centro.php");}
$backURL="inventario_total.php";
$REF="";

if(isset($_REQUEST['REF'])){
$rs=$linkPDO->query("SELECT * FROM ".tabProductos." WHERE id_pro='".$_REQUEST['REF']."'");
$REF=$_REQUEST['REF'];
}
else header("location: $backURL?pag=".$_SESSION['pag']);
//echo "ID:".$_SESSION['id'];
$result=$rs->fetch();
$boton="";


if(isset($_REQUEST['boton'])){
	
$boton= $_REQUEST['boton'];
$clas= $_REQUEST['clas'];
$des= $_REQUEST['des'];
$fra= $_REQUEST['fra'];
$fab= $_REQUEST['fab'];

$id_inter= $_REQUEST['id_producto'];

}

if($boton=='Volver')header("location:inventario_total.php?pag=".$_SESSION['pag']);


if($boton=='Guardar'&& isset($des) &&!empty($des) && isset($clas) &&!empty($clas)){
	

$id_inter= $_REQUEST['id_producto'];
 	
$clas= $_REQUEST['clas'];
$des= $_REQUEST['des'];
$fra= $_REQUEST['fra'];
$fab= $_REQUEST['fab'];

$linkPDO->exec("UPDATE ".tabProductos." SET id_pro='$id_inter',detalle='$des',id_clase='$clas',frac=$fra,fab='$fab' WHERE id_pro='".$_REQUEST['REF']."'");

header("location:modificar_producto.php");


}
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("HEADER.php"); ?></head>
<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">
<div class="grid-100 posicion_form">
<h1  align="center">MODIFICAR PRODUCTO</h1>
<form action="modificar_producto.php" method="get" name="modpro" class="uk-form">
<input type="hidden" value="<?php echo $REF  ?>" name="REF" id="REF">
<table align="center" width="600px" style="color: #FFF">
<tr>
<td>
<label>Referencia producto:</label>
</td>
</tr>
<tr>
<td>
<input value="<?php echo htmlentities($result['id_pro'], ENT_QUOTES,"$CHAR_SET") ?>" name="id_producto" type="text" placeholder="Codigo del producto" />
</td>
</tr>
<tr><td><label>Descripci&oacute;n:</label></td>
</tr>
<tr><td>
<textarea cols="10" rows="5" name="des" type="text" placeholder="Descripci&oacute;n del producto"><?php echo htmlentities($result['detalle'], ENT_QUOTES,"$CHAR_SET") ?></textarea></td>
</tr>
<tr>
<td>
<label>Fracciones:</label></td>
</tr>
<tr><td>
<input value="<?php echo $result['frac'] ?>" name="fra" type="text" placeholder="Fracciones del producto" />
</td>
</tr>
<tr><td>
<label>Fabricante:</label></td>
</tr>
<tr><td>
<input value="<?php echo htmlentities($result['fab'], ENT_QUOTES,"$CHAR_SET") ?>" name="fab" type="text" placeholder="Fabricante" />
</td>
</tr>


<tr>
<td>

<label >Clase:</label>
</td>
</tr>
<tr>
<td>
<select name="clas">
<option value="<?php echo $result['id_clase'] ?>" selected><?php echo htmlentities($result['id_clase'], ENT_QUOTES,"$CHAR_SET") ?></option>
<?php

$rs=$linkPDO->query("SELECT * FROM clases ORDER BY des_clas");
while($row = $rs->fetch()) 
         { 
		 echo "<option value=\"$row[des_clas]\">$row[des_clas]</option>  ";
		 }?>

</select>
</td>
</tr>
<tr>
<td>
<button name="boton" type="submit"  class="uk-button uk-button-large" value="Guardar"><i class=" uk-icon-floppy-o"></i>Guardar</button>
<button name="boton2" type="button"  class="uk-button uk-button-large" onClick="Goback();"><i class=" uk-icon-history"></i>Volver</button>

</td>
</tr>
</table>


</form>
</div>
<?php require_once("FOOTER.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
</body>
</html>