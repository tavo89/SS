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
 <link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/style.css">
  <link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/prism.css">
  <link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/chosen.css">
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
<form action="reg_usu.php" method="get" id="frm" class=" uk-form">
<h1 align="center">Datos Veh&iacute;culo</h1>
<table align="center">
<tr>
<td>
<table align="center">
<thead>
</thead>
<tr>
<td>Placa:</td>
<td><input name="placa" type="text" id="placa" value=""  onChange="validar_c($(this),'camion','placa','Esta PLACA ya esta Registrada!');"/></td>
<td>Modelo:</td>
<td><input name="modelo" type="text" id="modelo" value=""  onChange=""/></td>
</tr>
<tr>
<td>Marca:</td>
<td><input name="marca" type="text" value="" id="marca" onChange="" />
</td>
<td ></td>
<td ></td>
</tr>
<tr>
<td>Conductor</td>
<td>
<select name="id_usu" id="id_usu" onChange="" data-placeholder="Nombre del Conductor" class="chosen-select" style="width:180px;" tabindex="2">
<option value="" selected></option>
<?php

$sql="SELECT * FROM usuarios WHERE cliente=0 AND chofer=1 AND cod_su='$codSuc' ORDER BY nombre";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){

$nom=$row['nombre'];
$idCho=$row['id_usu'];
?>
<option value="<?php echo $idCho ?>"><?php echo "$nom" ?></option>
<?php
}

?>
</select>
</td>
<td>Ruta:</td>
<td>

<select name="ruta" id="ruta" onChange="" data-placeholder="Nombre del Conductor" class="chosen-select" style="width:180px;" tabindex="2">
<option value="" selected></option>
<?php

$sql="SELECT * FROM ruta WHERE cod_su='$codSuc'";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){

$des=$row['des_ruta'];
$idRuta=$row['id_ruta'];
?>
<option value="<?php echo $idRuta ?>"><?php echo "$des" ?></option>
<?php
}

?>
</select>
</td></tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<span  onClick="save_form($('#frm'),'reg_vehiculo.php','reg_vehiculo.php');" class="uk-button uk-button-large"><i class=" uk-icon-floppy-o"></i>Guardar</span>
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
<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/chosen.jquery.min.js?<?php echo "$LAST_VER" ?>"></script>
<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/docsupport/prism.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >
var cd=0;
var $nd=$('#num_d');


var config = {
      '.chosen-select'           : {no_results_text:'Oops, NO se encontro nada!'},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, NO se encontro nada!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

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