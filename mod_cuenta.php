<?php
include_once("Conexxx.php");

if($rolLv!=$Adminlvl && !val_secc($id_Usu,"clientes_mod")){header("location: centro.php");}


?>
<!DOCTYPE html>
<html>
<head>
<?php include_once("HEADER.php"); ?>
</head>
<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
			<?php include_once("boton_menu.php"); ?>	


<div class="uk-width-5-10 uk-container-center">
<div class=" grid-100 posicion_form">


<h1 align="center">Cuenta Usuario</h1>
<?php 
$ced=$id_Usu;
//$pag=limpiarcampo(limpianum($_REQUEST['pag']));
$sql="SELECT * FROM usuarios WHERE id_usu='$ced'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$nombre=$row['nombre'];
	$ced=$row['id_usu'];
	$usuLv=$row['cliente'];
	
	$cuidad=$row['cuidad'];
	$dir=$row['dir'];
	$tel=$row['tel'];
	
	$mail=$row['mail_cli'];
	$sim=$row['sim'];
 ?>
<form action="mod_cuenta.php" method="get" id="frm" class="uk-form uk-text-large">

<table align="center" class="uk-table" style="background-color:#FFF;" >
<thead>
<th colspan="4" style="font-size:24px">Datos personales</th>
</thead>
<tr>
<td>Nombre:</td>
<td><input name="cli" type="text" id="cli" value="<?php echo $nombre ?>" /></td>
<td>Ciudad:</td>
<td><input name="city" type="text" id="city" value="<?php echo $cuidad ?>" /></td>
</tr>
<tr>
<td>C.C/NIT:</td>
<td><input name="ced" type="hidden" value="<?php echo $ced ?>" id="ced" onChange="validar_c($(this),'usuarios','id_usu','Este Documento Ya esta registrado!!!');" />
<input name="NEWced" type="text" value="<?php echo $ced ?>" id="ced" onChange="validar_c($(this),'usuarios','id_usu','Este Documento Ya esta registrado!!!');" />
</td>
<td >Direcci&oacute;n:</td>
<td ><input name="dir" type="text" value="<?php echo $dir ?>" id="dir" /></td>
</tr>
<tr>
<td>Tel.:</td>
<td><input name="tel"  type="text" value="<?php echo $tel ?>" id="tel" /></td>
<td>E-mail.:</td>
<td><input name="mail"  type="text" value="<?php echo $mail ?>" id="mail" /></td>
</tr>



<?php
$USU="";
$CLA="";
if($usuLv==0){

$sql="SELECT * FROM usu_login WHERE id_usu='$ced'";
$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{
		$USU=$row['usu'];
		$CLA=$row['cla'];
			
	}
?>

<tr  >
<th colspan="4" style="font-size:24px">Login Usuario</th>
</tr>

<tr>

<td>Usuario:</td><td><input type="text" name="usu" id="usu" value="<?php echo $USU ?>" readonly></td>
</tr>
<tr>
<td>Contrase&ntilde;a:</td><td><input type="text" name="cla" id="cla" value="<?php echo $CLA ?>"></td>
</tr>





<?php
}////////////////////////////////////////////////////// fin IF cuenta usu ////////////////////////////////////////////////////////////////////////////
?>
<tr>
<td colspan="2" align="center">

<span  onClick="mod_cli($('#frm'));" class="uk-button uk-button-large"><i class=" uk-icon-floppy-o"></i>Guardar</span>
<span class="uk-button uk-button-large" onClick="Goback();"><i class=" uk-icon-history"></i>Volver</span>
</td>
</tr>

</table>
<div id="mensaje">
</div>
  <input type="hidden" name="check" value="0" id="check" />
    <input type="hidden" name="num_d" value="<?php echo $num_d ?>" id="num_d" />
</form>

<?php
}

?>

</div>
<?php include_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js?<?php echo "$LAST_VER" ?>"></script>
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


	});
function add_dcto()
{
	//var fab=fabricante('fab'+cd,'fab'+cd,'dcto_'+cd,'fab_dcto'+cd);
	var html='<tr class="dcto_'+cd+'" style="font-size:24px"><td class="dcto_'+cd+'"><input class="dcto_'+cd+'" type="text" name="dcto_'+cd+'" id="dcto_'+cd+'"   style="width:200px" placeholder="DCTO %"/></td><td class="dcto_'+cd+'" width="60"><select class="dcto_'+cd+'" name="tipo_dcto'+cd+'" id="tipo_dcto'+cd+'"><option value="NETO">NETO</option><option value="PRODUCTO">PRODUCTO</option></select></td><td class="dcto_'+cd+'">Operador:</td><td class="dcto_'+cd+'" id="fab_dcto'+cd+'"></td></tr>';
	
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
   //alert(c);
   $dcto=$('#dcto');
   $dcto.prop('value',d);
};

</script>
</body>
</html>