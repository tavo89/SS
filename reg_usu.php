<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"clientes_add")){header("location: centro.php");}

if(isset($_REQUEST['boton'])){$boton=$_REQUEST['boton'];}

if($boton="Guardar" && isset($_REQUEST['cli']) &&!empty($_REQUEST['ced'])){
	
	$nombre=limpiarcampo(strtoupper($_REQUEST['cli']));
	$ced=limpiarcampo($_REQUEST['ced']);
	$usu=limpiarcampo($_REQUEST['usu']);
	$cla=limpiarcampo($_REQUEST['cla']);
	$tipoUsu=limpiarcampo($_REQUEST['tipo_usu']);
	$mail=limpiarcampo($_REQUEST['mail']);
	$lv=0;
	if($tipoUsu=="adm")$lv=10;
	else $lv=1;

try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


$sql="INSERT INTO usuarios(id_usu,nombre,cod_su,cliente,mail_cli) VALUES('$ced','$cliente',$codSuc,0,'$mail')";
$linkPDO->exec($sql);



$sql="INSERT INTO usu_login(id_usu,usu,cla,rol_lv) VALUES('$ced','$usu','$cla',$lv)";
$linkPDO->exec($sql);

$linkPDO->commit();

if($all_query_ok){
eco_alert("Guardado con Exito!");
}
else{eco_alert("ERROR! Intente nuevamente");}
}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

}

?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("HEADER.php"); ?>
<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
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
<form action="reg_usu.php" method="get" id="frm" class=" uk-form" autocomplete="off">
<h1 align="center">Datos Usuario</h1>
<table align="center" class="uk-contrast dark_bg uk-text-large">
<tr>
<td>
<table align="center">
<thead>
</thead>
<tr>
<td>Nombre:</td>
<td><input name="cli" type="text" id="cli" value="" /></td>
<td>Usuario:</td>
<td><input name="usu" type="text" id="usu" value=""  onChange="validar_c($(this),'usu_login','usu','Este USUARIO Ya esta registrado!!!');" autocomplete="off"/></td>
</tr>
<tr>
<td>C.C/NIT:</td>
<td><input name="ced" type="text" value="" id="ced" onChange="validar_c($(this),'usuarios','id_usu','Este Documento Ya esta registrado!!!');" />
</td>
<td >Clave:</td>
<td ><input name="cla" type="password" value="" id="cla"  autocomplete = "new-password"/></td>
</tr>
<tr>
<td>Tipo Usuario</td>
<td><select name="tipo_usu" >
<!--
<option value="Conductor">Conductor</option>
-->
<option value="Tecnico">Tecnico</option>
<option value="std">Estandar</option>

<option value="ven" selected>Vendedor</option>
<option value="adm">Administrador</option>
</select></td>
<td>E-mail.:</td>
<td><input name="mail"  type="text" value="" id="mail" /></td></tr>


<tr class="uk-block-primary">
<td colspan="4">OPCIONES N&Oacute;MINA</td> 
</tr>

<tr>
<td colspan="2">CURSO MEJORAMIENTO</td>
<td>
<select name="curso" id="curso">
<option value="1">Si</option>
<option value="no" selected>NO</option>
</select>
</td>
</tr>
</table>
</td>
</tr>




<tr>
<td colspan="4" align="center">
<span  onClick="save_form($('#frm'),'save_usu.php','reg_usu.php');" class="uk-button uk-button-large"><i class=" uk-icon-floppy-o"></i>Guardar</span>
<span class="uk-button uk-button-large" onClick="Goback();"><i class=" uk-icon-history"></i>Volver</span>
</td>
</tr>

</table>
<div id="mensaje">
</div>
  <input type="hidden" name="check" value="0" id="check" />
    <input type="hidden" name="num_d" value="0" id="num_d" />
</form>
</div>
<?php require_once("FOOTER.php"); 
$rs=null;
$stmt=null;
$linkPDO= null;
?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 	
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >
var cd=0;
var $nd=$('#num_d');
$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


$('body').ajaxStart(function(){
	$.mobile.loading( 'show', {
	text: 'Procesando datos...',
	textVisible: true,
	theme: 'a',
	html: ""});
		$('input[type=button]').prop("disabled","disabled").css("color","red");
		})
		
.ajaxSuccess(function(){
		$.mobile.loading( 'hide', {
	text: 'Enviando datos...',
	textVisible: false,
	theme: 'a',
	html: ""});
		$('input[type=button]').removeAttr("disabled").css("color","black");
		})
	
.ajaxError(function(){$('input[type=button]').prop("disabled","disabled").css("color","red");$(this).hide();});	

	
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

</div>
</div>

</body>
</html>