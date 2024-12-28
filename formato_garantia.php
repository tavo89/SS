<?php
include("Conexxx.php");
$formSize="uk-form-small";
?>
<!DOCTYPE html PUBLIC >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Orden de Garant&iacute;a</title>
<?php include("HEADER_UK.php"); ?>
</head>

<body>
<form action="" method="post" id="frm_any" name="frm_any" class="uk-form  ">

<!--<div align="center" style="top:0px; width:21.5cm; height:27cm; font-family: Verdana, Geneva, sans-serif; border-width:2px; border:  outset; padding-left:10px; padding-top:10px; padding-right:15px;"   class="imp_COM">-->
<div align="center" style=" font-family: Verdana, Geneva, sans-serif; border-width:2px; border:  outset; padding-left:20px; padding-top:20px; padding-right:20px;" >
<div class="uk-grid" data-uk-grid-margin>
<div class="uk-width-1-2">
<label class="uk-form-label" for="fecha_pago">Fecha</label>
<input type="datetime-local" name="c1" id="fecha_pago" value="<?PHP echo $FechaHoy."T".$hora ?>" class="<?php echo $formSize; ?> uk-form-width-medium"> 
</div>

 
<div style="font-size:20px;" class="comp_egreso_header uk-width-1-2">
ORDEN GARANT&Iacute;A <span style="color:red;"></span>
</div>
</div>

<div class="uk-grid" data-uk-grid-margin>

<div class="uk-width-1-2">
<label class="uk-form-label" for="nom_cli">Se&ntilde;or</label>
<input type="text" name="nom_cli" id="nom_cli" value="" class="<?php echo $formSize; ?> uk-form-width-medium" onBlur="busq_cli_x(this);"> 
</div>
<div class="uk-width-1-2">
<label class="uk-form-label" for="id_cli">NIT/CC</label>
<input type="text" name="c2" id="id_cli" value="" class="<?php echo $formSize; ?> uk-form-width-medium"> 
</div>

</div>
<div class="uk-grid" data-uk-grid-margin>

<div class="uk-width-1-2">
<label class="uk-form-label" for="dir_cli">Direcci&oacute;n</label>
<input type="text" name="dir_cli" id="dir_cli" value="" class="<?php echo $formSize; ?> uk-form-width-medium"> 
</div>
<div class="uk-width-1-2">
<label class="uk-form-label" for="tel_cli">Tel.</label>
<input type="text" name="tel_cli" id="tel_cli" value="" class="<?php echo $formSize; ?> uk-form-width-medium"> 
</div>

</div>


<div class="uk-grid" data-uk-grid-margin>

<div class="uk-width-1-2">
<label class="uk-form-label" for="vehi_cli">Veh&iacute;culo</label>
<input type="text" name="c3" id="vehi_cli" value="" class="<?php echo $formSize; ?> uk-form-width-medium"> 
</div>

</div>

<hr>
<div class="uk-grid" data-uk-grid-margin>



<div class="uk-width-1-4">
<label class="uk-form-label" for="ref">Buscar REF</label>
<div class="uk-form-icon">
    <i class="uk-icon-search"></i> 
<!--                
<input size="15" value="<?php echo $fechaF ?>" type="text" name="fechaF" id="f_fin"   readonly placeholder="Fecha Final" class="uk-form-large " data-uk-datepicker="{<?php echo $UKdatePickerFormat; ?>}">
-->
<input   name="busq01" value=" " type="text" id="busq01"  placeholder="Buscar REF" class="<?php echo $formSize; ?> uk-form-width-medium" onChange="busq_any_inv($(this),'addRefGaran',0)">
</div>
 
</div>


<div class="uk-width-1-4">
<label class="uk-form-label" for="ref">Referencia</label>
<input type="text" name="c5" id="ref" value="" class="<?php echo $formSize; ?> uk-form-width-medium" readonly> 
</div>

<div class="uk-width-1-4">
<label class="uk-form-label" for="cod_bar">Cod. Interno</label>
<input type="text" name="c11" id="cod_bar" value="" class="<?php echo $formSize; ?> uk-form-width-medium" readonly> 
</div>




<div class="uk-width-1-4">
<label class="uk-form-label" for="tar_garantia">Tarjeta de Garant&iacute;a</label>
<input type="text" name="c4" id="tar_garantia" value="" class="<?php echo $formSize; ?> uk-form-width-medium"> 
</div>

</div>


<div class="uk-grid" data-uk-grid-margin>

<div class="uk-width-1-4">
<label class="uk-form-label" for="ref">Buscar REF</label>
<div class="uk-form-icon">
    <i class="uk-icon-search"></i> 
<!--                
<input size="15" value="<?php echo $fechaF ?>" type="text" name="fechaF" id="f_fin"   readonly placeholder="Fecha Final" class="uk-form-large " data-uk-datepicker="{<?php echo $UKdatePickerFormat; ?>}">
-->
<input   name="busq01" value=" " type="text" id="busq01"  placeholder="Buscar REF" class="<?php echo $formSize; ?> uk-form-width-medium" onChange="busq_any_inv($(this),'addRefPres',1)">
</div>
 
</div>


<div class="uk-width-1-4">
<label class="uk-form-label" for="ref2">Referencia PRESTAMO</label>
<input type="text" name="c10" id="ref2" value="" class="<?php echo $formSize; ?> uk-form-width-medium" readonly> 
</div>

<div class="uk-width-1-4">
<label class="uk-form-label" for="cod_bar2">Cod. Interno PRETAMO</label>
<input type="text" name="c12" id="cod_bar2" value="" class="<?php echo $formSize; ?> uk-form-width-medium" readonly> 
</div>


<div class="uk-width-1-4">
<label class="uk-form-label" for="fe_venta">Fecha de Venta</label>
<input type="date" name="c6" id="fe_venta" value="" class="<?php echo $formSize; ?> uk-form-width-medium"> 
</div>


</div>


<div class="uk-grid" data-uk-grid-margin>

<div class="uk-width-1-3">
<label class="uk-form-label" for="num_guia">No. Gu&iacute;a</label>
<input type="text" name="c7" id="num_guia" value="" class="<?php echo $formSize; ?> uk-form-width-medium"> 
</div>

<div class="uk-width-1-3">
<label class="uk-form-label" for="transportadora">Transportadora</label>
<input type="text" name="c8" id="transportadora" value="" class="<?php echo $formSize; ?> uk-form-width-medium"> 
</div>

<div class="uk-width-1-3">
<label class="uk-form-label" for="fe_envio">Fecha Env&iacute;o</label>
<input type="date" name="c9" id="fe_envio" value="" class="<?php echo $formSize; ?> uk-form-width-medium"> 
</div>


</div>
<br>
<div class="uk-width-1-1">
<span id="botonSubmit" class=" uk-button uk-button-primary uk-button-large uk-width-7-10"   onClick="save_any2(document.forms['frm_any'],val_orden,ajuste);" style="margin-bottom:10px;">Guardar</span>
<br>
</div>

<input type="hidden" name="numF" id="numF" value="12">
        <input type="hidden" name="Colset" id="Colset" value="fecha,id_cli,placa_vehi,cod_garantia,id_pro,fecha_venta,num_gui,transportadora,fecha_envio,id_pro_prestamo,id_inter,id_inter2">
        <input type="hidden" name="tab" id="tab" value="orden_garantia">

</div>
</form>
<?php include("FOOTER_UK.php"); ?>
<?php require_once("autoCompletePack.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript">


call_autocomplete('ID',$('#nom_cli'),'ajax/busq_cli.php');


function ajuste(){
	
var ref=$('#ref2').val();
var cod=$('#cod_bar2').val();
	   
	   if(!esVacio(ref) && !esVacio(cod)){ 
        $.ajax({
		url:'ajax/ajuste_inv.php',
		data:{ref:ref,cod_barras:cod,cant:-1},
	    type: 'POST',
		dataType:'text',
		success:function(text){
			//alert('Encontrado!:'+text);
			var resp=parseInt(text);
			//resp=resp.replace(/\+/g," ");
				if(resp==1){simplePopUp('Se AJUSTO Ref:'+ref+' en -1 cantidad.');}
				else {simplePopUp(text);}
				successAny(resp);
			
			
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
	
	   }
};


function addRefGaran(i,ref,cod,fe)
{
	$('#ref').prop('value',ref);
	$('#cod_bar').prop('value',cod);
	hide_pop('#modal');
	
};

function addRefPres(i,ref,cod,fe)
{
	$('#ref2').prop('value',ref);
	$('#cod_bar2').prop('value',cod);
	hide_pop('#modal');
	
};



var flag_gfv=0;
function val_orden(form){
	
	if(flag_gfv==0){
	
	if(esVacio(form.id_cli.value)){warrn_pop('Ingrese Datos del CLIENTE');form.id_cli.focus();return 0;}
	else if(esVacio(form.tar_garantia.value)){warrn_pop('Ingreses el numero de la Tarjeta  de Garantia');form.tar_garantia.focus();return 0;}
	else if(esVacio(form.vehi_cli.value)){warrn_pop('Ingrese Placa del Vehiculo');form.vehi_cli.focus();return 0;}
	else if(esVacio(form.ref.value)){warrn_pop('Ingrese Referencia de la Bateria');form.ref.focus();return 0;}
	else if(esVacio(form.cod_bar.value)){warrn_pop('Ingrese Cod. de la Bateria');form.cod_bar.focus();return 0;}
	
	else if(esVacio(form.ref2.value)){warrn_pop('Ingrese Referencia de la Bateria');form.ref2.focus();return 0;}
	else if(esVacio(form.cod_bar2.value)){warrn_pop('Ingrese Cod. de la Bateria');form.cod_bar2.focus();return 0;}
	
	else if(esVacio(form.fe_venta.value)){warrn_pop('Seleccione la FECHA de venta de la Bateria');form.fe_venta.focus();return 0;}
	else if(esVacio(form.num_guia.value)){warrn_pop('Ingrese No. de Guia');form.num_guia.focus();return 0;}
	else if(esVacio(form.transportadora.value)){warrn_pop('Ingrese Nombre Transportadora');form.transportadora.focus();return 0;}
	else if(esVacio(form.fe_envio.value)){warrn_pop('Ingrese FECHA envio');form.fe_envio.focus();return 0;}
	else {flag_gfv=1;}
	$('#botonSubmit').off( 'click' );
	return 1;
	
	
	}
	
	
};

function busq_cli_x(n)
{
	    
        $.ajax({
		url:'ajax/add_usu_ven.php',
		data:{ced:n.value},
	    type: 'POST',
		dataType:'text',
		success:function(text){
			//alert('Encontrado!:'+text);
			var resp=parseInt(text);
			//resp=resp.replace(/\+/g," ");
			if(resp!=0)
			{
			  var campos=text;
			  //campos=unescape(campos);
			  //ampos=campos.replace(/\+/g," ");
			 
			  //alert(campos+' camión');
			  campos=campos.split('|');
			 $('#nom_cli').prop('value',campos[0]);	
			 $('#dir_cli').prop('value',campos[1]);
			 $('#tel_cli').prop('value',campos[2]);
			 /*$('#dir_cli').prop('value',campos[1]);
			 $('#tel_cli').prop('value',campos[2]);
			 $('#city').prop('value',campos[3]);
			 $('#mail').prop('value',campos[4]);
			 $('#fe_naci').prop('value',campos[5]);*/
			 $('#id_cli').prop('value',campos[6]);
			 
	
	
				
		
			}
			
			
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
};

</script>
</body>
</html>