<?php
/*
relacionados:
reg_cli.php
mod_cli.php

*/
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"clientes_add")){header("location: centro.php");}

	$nombre="";
	$ced="";
	
	$snombr="";
	$apelli="";
	
	$claper="";
	
	$coddoc="";
	$paicli="";
	$depcli="";
	$loccli="";
	$nomcon="";
	$regtri="";
	$razsoc="";
	
	
	


	
	$dir="";
	$tel="";
	$mail="";
	$sim="";
	$cuidad="";
	
	$topCre="";
	$authCre="";
	
	$tipoUsu="";
	
	$fechaBan="";
	$montoBan="";
	
	$fechaBanRemi="";
	$montoBanRemi="";
	
	$codComision="";
	$aliasCli="";
	
	$cod_caja="";
	$usuLv="";
	
	$cursoMejoramiento="";
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
<div class="grid-100 posicion_form">
<form action="registro_cli.php" method="get" id="frm" class="uk-form">
<table align="center" class="uk-contrast dark_bg uk-text-large">
<tr>
<td>
<table align="center">
<thead>
<th colspan="6" style="font-size:24px"><i class="uk-icon-user">&nbsp;</i>Datos Cliente</th>
</thead>
<tr>
<td>Nombre 1:</td>
<td><input name="cli" type="text" id="cli" value="<?php echo "$nombre"; ?>" onChange="//$(this).prop('value',this.value)"/></td>
<td>Nombre 2:</td>
<td><input name="snombr" type="text" id="snombr" value="<?php echo "$snombr"; ?>" onChange=""/></td>
<td>Apellidos:</td>
<td><input name="apelli" type="text" id="apelli" value="<?php echo "$apelli"; ?>" onChange=""/></td>

</tr>

<tr>
<td>Tipo Documento:</td>
<td>
<select name="coddoc" id="coddoc" style="width:200px;">

<option value="13"  <?php if($coddoc=="13" || empty($coddoc)){echo "selected";} ?>>Cedula de ciudadania</option>
<option value="31" <?php if($coddoc=="31"){echo "selected";} ?>>NIT</option>
<option value="11" <?php if($coddoc=="11"){echo "selected";} ?>>Registro civil</option>
<option value="12" <?php if($coddoc=="12"){echo "selected";} ?>>Tarjeta de identidad</option>

<option value="21" <?php if($coddoc=="21"){echo "selected";} ?>>Tarjeta de extranjeria</option>
<option value="22" <?php if($coddoc=="22"){echo "selected";} ?>>Cedula de extranjeria</option>

<option value="41" <?php if($coddoc=="41"){echo "selected";} ?>>Pasaporte</option>
<option value="42" <?php if($coddoc=="42"){echo "selected";} ?>>Documento de identificacion extranjero</option>
</select>
</td>
<td>C.C/NIT:</td>
<td><input name="ced" type="text" value="<?php echo "$ced"; ?>" id="ced" onChange="validar_c($(this),'usuarios','id_usu','Este Documento Ya esta registrado!!!');" />
</td>
<td>Clase Persona:</td>
<td>
<select name="claper" id="claper">
<option value="1" <?php if($claper=="1"){echo "selected";} ?>>Juridica</option>
<option value="2" <?php if($claper=="2" || empty($claper)){echo "selected";} ?>>Natural</option>
</select>
</td>

</tr>

<tr>
<td>Regimen Tributario:</td>
<td>
<select name="regtri" id="regtri">
<option value="0" <?php if($regtri=="0"){echo "selected";} ?>>Simplificado</option>
<option value="2" <?php if($regtri=="2"){echo "selected";} ?>>Comun</option>
</select>
</td>
<td>Razon Social :</td>
<td><input name="razsoc"  type="text" value="<?php echo "$razsoc"; ?>" id="razsoc"  /></td>
</tr>

<tr>
<th colspan="6" align="center" style="font-size:24px">
<i class="uk-icon-home">&nbsp;</i>
Datos de Contacto
</th>
</tr>
<tr>

<td >Direcci&oacute;n:</td>
<td ><input name="dir" type="text" value="<?php echo "$dir"; ?>" id="dir"  /></td>

<td>Localidad :</td>
<td><input name="loccli"  type="text" value="<?php echo "$loccli"; ?>" id="loccli"  /></td>
<td>Tel.:</td>
<td><input name="tel"  type="text" value="<?php echo "$tel"; ?>" id="tel"  /></td>
</tr>
<tr>
<td>Departamento:</td>
<td><input name="depcli" type="text" id="depcli" value="<?php echo "$depcli"; ?>" /></td>
<td>Ciudad:</td>
<td><input name="city" type="text" id="city" value="<?php echo "$cuidad"; ?>" /></td>
<td>E-mail.:</td>
<td><input name="mail"  type="text" value="<?php echo "$mail"; ?>" id="mail"  /></td>
</tr>

<tr>
<td>Cod. Pa&iacute;s:</td>
<td> 
<select name="paicli" id="paicli">
<option value="CO" <?php if($paicli=="CO"){echo "selected";} ?>>Colombia</option>
<option value="VE" <?php if($paicli=="VE"){echo "selected";} ?>>Venezuela</option>
</select>
</td>

<td>Nombre de Contacto:</td>
<td>
<input name="nomcon"  type="text" value="<?php echo "$nomcon"; ?>" id="nomcon"  />
</td>

</tr>

<tr class="uk-text-large uk-hidden">

<td>Tipo Usuario:</td>
<td><select name="tipo_usu" id="tipo_usu" >
<option value="Empleado">Empleado</option>
<option value="Particular" selected>Particular</option>
<option value="Otros Talleres">Otros Talleres</option>
</select>
</td>
<td>Cod. Comisi&oacute;n</td>
<td><input type="text" value="" name="cod_comision" id="cod_comision" onChange="validar_c($(this),'usuarios','cod_comision','Este CODIGO Ya esta registrado!');"></td>
</tr>

<tr>
<th colspan="6" align="center" style="font-size:24px">
OPCIONES DE CARTERA
</th>
</tr>

<tr>
<td>Tope Cr&eacute;dito:</td>
<td><input type="text" name="top_cre" id="tope_cre" value=" " onKeyUp="puntoa($(this))"></td>
<td>Autorizar Cr&eacute;dito:</td>
<td>
<select name="auth_cre" id="auth_cre">
<option value="1"   selected>SI</option>
<option value="0" >NO</option>
</select>
</td>
</tr>

<tr class="uk-text-large <?php if($MODULES["modulo_planes_internet"]==1 || $fac_servicios_mensuales==1){}else{echo "uk-hidden";}?>">
<th colspan="6" align="center" style="font-size:24px">
PLANES INTERNET / SERVICIOS MENSUALES
</th>
</tr>

<tr>
<td>Afiliacion:</td>
<td>
<input type="date" name="fecha_afiliacion" id="fecha_afiliacion" value="">
</td>
<td>Suspensi&oacute;n:</td>
<td>
<input type="date" name="fecha_suspension" id="fecha_suspension" value="">
</td>
<td>Terminaci&oacute;n:</td>
<td>
<input type="date" name="fecha_terminacion" id="fecha_terminacion" value="">
</td>
</tr>

</table>
</td>
</tr>

<tr>
<td colspan="2" align="center">
<span  onClick="save_cli($('#frm'));" class="uk-button uk-button-large"><i class=" uk-icon-floppy-o"></i>Guardar</span>

<span class="uk-button uk-button-large" onClick="Goback();"><i class=" uk-icon-history"></i>Volver</span>
</tr>

</table>
<div id="mensaje">
</div>
  <input type="hidden" name="check" value="0" id="check" />
    <input type="hidden" name="num_d" value="0" id="num_d" />
    <input type="hidden" value="" name="html" id="pagHTML">
</form>
</div>
<?php require_once("FOOTER.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >
var cd=0;
var $nd=$('#num_d');
function save_cli($form)
{
	
	getPage($('#pagHTML'),$('#registro_cliente'));
	var externals=$form;
	var ExtString=externals.serialize();
	var Datos=ExtString;
	//alert(Datos);
	//$('.loader');
	//alert('ref H:'+$('#ref'+c).offset()+', ref H:'+$('#ref'+c).offset());
	
	$.ajax({
		url:'ajax/reg_cli.php',
		data:Datos,
		type:'POST',
		dataType:'text',
		success:function(text){
			var c=text*1;
			//alert(text);
			//$('<p>'+text+'</p>').appendTo('#salida');
			if(c==1)
			{
			open_pop('Guardado','Ha sido Registrado con &Eacute;xito','');
			
		$("#modal").on({

    'show.uk.modal': function(){
       
    },

    'hide.uk.modal': function(){
        location.assign('mod_clientes.php');
		//alert('del pop');
    }
});
			
			
			}
			else if(c==0)
			{
				open_pop('ERROR','Ha ingresado datos no v&aacute;lidos','Por Favor Intentelo de nuevo');
				
				
			}
			else if(c==2)
			{
				open_pop('ERROR','Faltan Datos','Por Favor Llene todos los campos');
				
			};
			//else alert('Actualizado');
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});

};

$('input').on("change",function(){
	
	$(this).prop('value',this.value);
});

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
	var html='<tr class="dcto_'+cd+'" style="font-size:24px"><td class="dcto_'+cd+'"><input class="dcto_'+cd+'" type="text" name="dcto_'+cd+'" id="dcto_'+cd+'"   style="width:200px" placeholder="DCTO %"/></td><td class="dcto_'+cd+'" width="60"><select class="dcto_'+cd+'" name="tipo_dcto'+cd+'" id="tipo_dcto'+cd+'"><option value="NETO">NETO</option><option value="PRODUCTO">PRODUCTO</option></select></td><td class="dcto_'+cd+'">Fabricante:</td><td class="dcto_'+cd+'" id="fab_dcto'+cd+'"></td></tr>';
	
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