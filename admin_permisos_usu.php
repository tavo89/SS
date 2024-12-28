<?php
include_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"usuarios_add")){header("location: centro.php");}

$accion = r('accion');


if($accion=='Aplica Permisos Ventas'){
	creaPermisosUsuarioVentas($_SESSION['id_cli']);
}

?>
<!DOCTYPE html>
<html>
<head>
<?php include_once("HEADER.php"); ?>
<script src="JS/jquery-2.1.1.js"></script>
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
</head>
<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
			<?php include_once("boton_menu.php"); ?>	


<div class="uk-width-6-10 uk-container-center">
<h1 align="center">PERMISOS USUARIO</h1>
<form action="">
<input class="uk-button uk-button-large uk-button-success"  type="submit" value="Aplica Permisos Ventas" name="accion">
<span class="uk-button uk-button-large" onClick="location.assign('usuarios.php');">Volver</span>
</form>
<?php 
$ced=$_SESSION['id_cli'];

$sql="SELECT * FROM  `secciones` ORDER BY  `secciones`.`id_secc` ASC ";
$rs=$linkPDO->query($sql);
$ID_SECC[]="";
$PERMITE[]="";
while($row=$rs->fetch())
{
	$PERMITE[$row['id_secc']]="No";
}

$sql="SELECT * FROM permisos WHERE id_usu='$ced'";
$rs=$linkPDO->query($sql);

while($row=$rs->fetch())
{
	
	$PERMITE[$row['id_secc']]=$row['permite'];
}
//$pag=limpiarcampo(limpianum($_REQUEST['pag']));
$sql="SELECT * FROM usuarios WHERE id_usu='$ced'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$nombre=$row['nombre'];
	$ced=$row['id_usu'];
	
	$cuidad=$row['cuidad'];
	$dir=$row['dir'];
	$tel=$row['tel'];
	
	$mail=$row['mail_cli'];
	$sim=$row['sim'];
 ?>
<form class="uk-form">
<table align="center" class="uk-table-striped uk-table uk-table-hover uk-text-large" style="background-color:#FFF;" >
<tr>
<td colspan="2"><h1 class="uk-text-primary" align="center">Facturacion</h1></td>
</tr>
<?php
$sql="SELECT * FROM  `secciones` WHERE habilita=1 AND modulo='Ventas' ORDER BY  `secciones`.`des_secc` ASC ";
$rs=$linkPDO->query($sql);
$ii=0;
while($row=$rs->fetch())
{
	$secc=$row['des_secc'];
	$id_secc=$row['id_secc'];
	
	?>
    <tr>
    <td colspan="" align="center"><?php echo $secc ?></td>
    <td colspan="" align="left">
<select class="uk-form-large" name="permite<?php echo $ii ?>" id="permite<?php echo $ii ?>"  onChange="save_permiso('<?php echo $ced ?>','<?php echo $id_secc ?>',$(this).val());pos_neg($(this));" <?php if($PERMITE[$id_secc]=="Si"){echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(89, 232, 60);\"";}else {echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(166, 166, 166);\"";} ?>>
	<option value="No" <?php if($PERMITE[$id_secc]=="No")echo "selected" ?>>No</option>
	<option value="Si" <?php if($PERMITE[$id_secc]=="Si")echo "selected" ?>>Si</option>
</select></td>
</tr>
    <?php	
	$ii++;
	
}
?>



<tr>
<td colspan="2"><h1 class="uk-text-primary" align="center">Cartera Clientes</h1></td>
</tr>

<?php
$sql="SELECT * FROM  `secciones` WHERE habilita=1 AND modulo='Cartera Clientes' ORDER BY  `secciones`.`des_secc` ASC ";
$rs=$linkPDO->query($sql);
$ii=0;
while($row=$rs->fetch())
{
	$secc=$row['des_secc'];
	$id_secc=$row['id_secc'];
	
	?>
    <tr>
    <td colspan="" align="center"><?php echo $secc ?></td>
    <td colspan="" align="left">
<select class="uk-form-large" name="permite<?php echo $ii ?>" id="permite<?php echo $ii ?>"  onChange="save_permiso('<?php echo $ced ?>','<?php echo $id_secc ?>',$(this).val());pos_neg($(this));" <?php if($PERMITE[$id_secc]=="Si"){echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(89, 232, 60);\"";}else {echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(166, 166, 166);\"";} ?>>
	<option value="No" <?php if($PERMITE[$id_secc]=="No")echo "selected" ?>>No</option>
	<option value="Si" <?php if($PERMITE[$id_secc]=="Si")echo "selected" ?>>Si</option>
</select></td>
</tr>
    <?php	
	$ii++;
	
}
?>





<tr>
<td colspan="2"><h1 class="uk-text-primary" align="center">Compras</h1></td>
</tr>

<?php
$sql="SELECT * FROM  `secciones` WHERE habilita=1 AND modulo='Compras' ORDER BY  `secciones`.`des_secc` ASC ";
$rs=$linkPDO->query($sql);
$ii=0;
while($row=$rs->fetch())
{
	$secc=$row['des_secc'];
	$id_secc=$row['id_secc'];
	
	?>
    <tr>
    <td colspan="" align="center"><?php echo $secc ?></td>
    <td colspan="" align="left">
<select class="uk-form-large" name="permite<?php echo $ii ?>" id="permite<?php echo $ii ?>"  onChange="save_permiso('<?php echo $ced ?>','<?php echo $id_secc ?>',$(this).val());pos_neg($(this));" <?php if($PERMITE[$id_secc]=="Si"){echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(89, 232, 60);\"";}else {echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(166, 166, 166);\"";} ?>>
	<option value="No" <?php if($PERMITE[$id_secc]=="No")echo "selected" ?>>No</option>
	<option value="Si" <?php if($PERMITE[$id_secc]=="Si")echo "selected" ?>>Si</option>
</select></td>
</tr>
    <?php	
	$ii++;
	
}
?>


<tr>
<td colspan="2"><h1 class="uk-text-primary" align="center">Traslados</h1></td>
</tr>

<?php
$sql="SELECT * FROM  `secciones` WHERE habilita=1 AND modulo='Traslados' ORDER BY  `secciones`.`des_secc` ASC ";
$rs=$linkPDO->query($sql);
$ii=0;
while($row=$rs->fetch())
{
	$secc=$row['des_secc'];
	$id_secc=$row['id_secc'];
	
	?>
    <tr>
    <td colspan="" align="center"><?php echo $secc ?></td>
    <td colspan="" align="left">
<select class="uk-form-large" name="permite<?php echo $ii ?>" id="permite<?php echo $ii ?>"  onChange="save_permiso('<?php echo $ced ?>','<?php echo $id_secc ?>',$(this).val());pos_neg($(this));" <?php if($PERMITE[$id_secc]=="Si"){echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(89, 232, 60);\"";}else {echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(166, 166, 166);\"";} ?>>
	<option value="No" <?php if($PERMITE[$id_secc]=="No")echo "selected" ?>>No</option>
	<option value="Si" <?php if($PERMITE[$id_secc]=="Si")echo "selected" ?>>Si</option>
</select></td>
</tr>
    <?php	
	$ii++;
	
}
?>


<tr>
<td colspan="2"><h1 class="uk-text-primary" align="center">Egresos</h1></td>
</tr>

<?php
$sql="SELECT * FROM  `secciones` WHERE habilita=1 AND modulo='Egresos' ORDER BY  `secciones`.`des_secc` ASC ";
$rs=$linkPDO->query($sql);
$ii=0;
while($row=$rs->fetch())
{
	$secc=$row['des_secc'];
	$id_secc=$row['id_secc'];
	
	?>
    <tr>
    <td colspan="" align="center"><?php echo $secc ?></td>
    <td colspan="" align="left">
<select class="uk-form-large" name="permite<?php echo $ii ?>" id="permite<?php echo $ii ?>"  onChange="save_permiso('<?php echo $ced ?>','<?php echo $id_secc ?>',$(this).val());pos_neg($(this));" <?php if($PERMITE[$id_secc]=="Si"){echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(89, 232, 60);\"";}else {echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(166, 166, 166);\"";} ?>>
	<option value="No" <?php if($PERMITE[$id_secc]=="No")echo "selected" ?>>No</option>
	<option value="Si" <?php if($PERMITE[$id_secc]=="Si")echo "selected" ?>>Si</option>
</select></td>
</tr>
    <?php	
	$ii++;
	
}
?>


<tr>
<td colspan="2"><h1 class="uk-text-primary" align="center">Inventario</h1></td>
</tr>

<?php
$sql="SELECT * FROM  `secciones` WHERE habilita=1 AND modulo='manejo de Inventario' ORDER BY  `secciones`.`des_secc` ASC ";
$rs=$linkPDO->query($sql);
$ii=0;
while($row=$rs->fetch())
{
	$secc=$row['des_secc'];
	$id_secc=$row['id_secc'];
	
	?>
    <tr>
    <td colspan="" align="center"><?php echo $secc ?></td>
    <td colspan="" align="left">
<select class="uk-form-large" name="permite<?php echo $ii ?>" id="permite<?php echo $ii ?>"  onChange="save_permiso('<?php echo $ced ?>','<?php echo $id_secc ?>',$(this).val());pos_neg($(this));" <?php if($PERMITE[$id_secc]=="Si"){echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(89, 232, 60);\"";}else {echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(166, 166, 166);\"";} ?>>
	<option value="No" <?php if($PERMITE[$id_secc]=="No")echo "selected" ?>>No</option>
	<option value="Si" <?php if($PERMITE[$id_secc]=="Si")echo "selected" ?>>Si</option>
</select></td>
</tr>
    <?php	
	$ii++;
	
}
?>

<tr>
<td colspan="2"><h1 class="uk-text-primary" align="center">Informes</h1></td>
</tr>

<?php
$sql="SELECT * FROM  `secciones` WHERE habilita=1 AND modulo='Informes' ORDER BY  `secciones`.`des_secc` ASC ";
$rs=$linkPDO->query($sql);
$ii=0;
while($row=$rs->fetch())
{
	$secc=$row['des_secc'];
	$id_secc=$row['id_secc'];
	
	?>
    <tr>
    <td colspan="" align="center"><?php echo $secc ?></td>
    <td colspan="" align="left">
<select class="uk-form-large" name="permite<?php echo $ii ?>" id="permite<?php echo $ii ?>"  onChange="save_permiso('<?php echo $ced ?>','<?php echo $id_secc ?>',$(this).val());pos_neg($(this));" <?php if($PERMITE[$id_secc]=="Si"){echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(89, 232, 60);\"";}else {echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(166, 166, 166);\"";} ?>>
	<option value="No" <?php if($PERMITE[$id_secc]=="No")echo "selected" ?>>No</option>
	<option value="Si" <?php if($PERMITE[$id_secc]=="Si")echo "selected" ?>>Si</option>
</select></td>
</tr>
    <?php	
	$ii++;
	
}
?>




<tr>
<td colspan="2"><h1 class="uk-text-primary" align="center">Clientes y Usuarios</h1></td>
</tr>

<?php
$sql="SELECT * FROM  `secciones` WHERE habilita=1 AND modulo='Clientes y Usuarios' ORDER BY  `secciones`.`des_secc` ASC ";
$rs=$linkPDO->query($sql);
$ii=0;
while($row=$rs->fetch())
{
	$secc=$row['des_secc'];
	$id_secc=$row['id_secc'];
	
	?>
    <tr>
    <td colspan="" align="center"><?php echo $secc ?></td>
    <td colspan="" align="left">
<select class="uk-form-large" name="permite<?php echo $ii ?>" id="permite<?php echo $ii ?>"  onChange="save_permiso('<?php echo $ced ?>','<?php echo $id_secc ?>',$(this).val());pos_neg($(this));" <?php if($PERMITE[$id_secc]=="Si"){echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(89, 232, 60);\"";}else {echo "style=\"font-family:'Arial Black',sans-serif;font-size:14px;background-color:rgb(166, 166, 166);\"";} ?>>
	<option value="No" <?php if($PERMITE[$id_secc]=="No")echo "selected" ?>>No</option>
	<option value="Si" <?php if($PERMITE[$id_secc]=="Si")echo "selected" ?>>Si</option>
</select></td>
</tr>
    <?php	
	$ii++;
	
}
?>

</table>
</form>
<span class="uk-button uk-button-large" onClick="location.assign('usuarios.php');">Volver</span>

<div id="mensaje">
</div>
  <input type="hidden" name="check" value="0" id="check" />
    <input type="hidden" name="num_d" value="" id="num_d" />

</div>
<?php
}

?>
<?php include_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >
var cd=$('#num_d').val();
var $nd=$('#num_d');

$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


$	});
function pos_neg($this)
{
	var state=$this.val();
if(state=='Si')$this.css({"font-family" : "Arial Black,sans-serif","font-size":"14px","background-color":"rgb(89, 232, 60)"});
else $this.css({"font-family" : "Arial Black,sans-serif","font-size":"14px","background-color":"rgb(166, 166, 166)"});
}
function add_dcto()
{
	//var fab=fabricante('fab'+cd,'fab'+cd,'dcto_'+cd,'fab_dcto'+cd);
	var html='<tr class="dcto_'+cd+'" style="font-size:24px"><td class="dcto_'+cd+'"><input class="dcto_'+cd+'" type="text" name="dcto_'+cd+'" id="dcto_'+cd+'"   style="width:200px" placeholder="DCTO %"/></td><td class="dcto_'+cd+'" width="60"><select class="dcto_'+cd+'" name="tipo_dcto'+cd+'" id="tipo_dcto'+cd+'"><option value="NETO">NETO</option><option value="PRODUCTO">PRODUCTO</option></select></td><td class="dcto_'+cd+'">Marca:</td><td class="dcto_'+cd+'" id="fab_dcto'+cd+'"></td></tr>';
	
	var $d=$(html);
	$d.appendTo('#descuentos');
	fabricante('fab'+cd,'fab'+cd,'dcto_'+cd,'fab_dcto'+cd);
	cd++;
	
	$nd.prop('value',cd);
	
	
};
function d(cant)
{
   var c=100+cant*1;
   var entre=100000;
   var vOri=1000;
   var d=(1-(entre/(c*vOri)) )*100;
   $dcto=$('#dcto');
   $dcto.prop('value',d);
};

</script>

</div>
</div>

</body>
</html>