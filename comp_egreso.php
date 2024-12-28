<?php
include("Conexxx.php");
$formSize="uk-form-small";
$feI=r("feI");
$feF=r("feF");
$payComi=r("pay_comi");
$codComi=r("codComi");
$idBene=r("idBene");
$nomBene=r("nomBene");
$valPago=r("valPago");

$codCompra=r("facCom");

$des="";
if($payComi==1){$des="Comision ventas periodo $feI a $feF";}
?>
<!DOCTYPE >
<html >
<head>
<?php require_once("HEADER.php"); ?>

<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/style.css">
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/prism.css">
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/chosen.css">
  
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>Comprobante EGRESO</title>
</head>

<body>
<div class="loader"><img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" /> </div>
<div id="Qresp"></div>

<form action="comp_egreso.php" method="post" name="frm" id="egresos" class="uk-form uk-contrast uk-form-stacked">

<div class=" uk-container-center round_table_white uk-width-large-9-10 uk-width-small-1-1">
<!--   uk-contrast comprobantes_ingreso uk-table-->
<div align="center"    class="uk-contrast dark_bg  uk-text-large uk-form-controls-condensed uk-border-rounded" >
 
<div class="uk-grid" data-uk-grid-margin>
<div style="font-size:20px;" class="comp_egreso_header uk-width-1-1">
COMPROBANTE DE EGRESO <span style="color:red;">No. <?php echo serial_comp_egreso(); ?></span>
</div>
</div>

<div class="uk-grid " data-uk-grid-margin id="">
<div class="uk-width-1-2">
<a class="uk-button uk-button-small uk-button-primary" data-uk-toggle="{target:'#box_FP', animation:'uk-animation-slide-left, uk-animation-slide-bottom'}">
Formas de Pago
</a>
</div>

<div class="uk-width-1-2">
<a id="r_fte_button" class="uk-button uk-button-small uk-button-primary" data-uk-toggle="{target:'#pago_RETE', animation:'uk-animation-slide-left, uk-animation-slide-bottom'}">
Cobro RETENCIONES
</a>
</div>
</div>

<div class="uk-grid" data-uk-grid-margin>
<div class="uk-width-1-2">
<label class="uk-form-label" for="fecha_pago">Fecha</label>
<input type="datetime-local" name="fecha_pago" id="fecha_pago" value="<?PHP echo $FechaHoy."T".$hora ?>" class="<?php echo $formSize; ?> uk-form-width-medium"> 
</div>
<div class="uk-width-1-2">
<label class="uk-form-label uk-text-warning" for="tipo_gasto">Tipo Egreso</label>
<?php if($payComi!=1){?>
<select style="width:250px;" data-placeholder="Escriba un concepto" id="tipo_gasto" name="tipo_gasto" onChange="" class="chosen-select uk-form-large<?php //echo $formSize; ?> uk-form-width-medium uk-form-success">
<option value="" selected></option>
<?php echo tipoEgresoOpt();?></select>
<?php }
else{
	?>
    <input value="Comision por Ventas" type="text" id="tipo_gasto" name="tipo_gasto" class=" uk-form-large<?php //echo $formSize; ?> uk-form-width-medium uk-form-success" readonly>
	<?php
	}
?>


</div>

</div>



<div id="box_FP" class="uk-hidden">
<div class="uk-grid " data-uk-grid-margin id="">

<div class="uk-width-1-2">
<label class="uk-form-label" for="form_pago">Forma de Pago</label>

<?php 
$selection="";
if(!empty($codCompra)){$selection="Contado-Caja General";}

echo selc_form_pa("form_pago","form_pago","",$FP_egresos,$selection);?>
</div>
<div class="uk-width-1-2">				 
<?php if($MODULES["CUENTAS_BANCOS"]==1){echo "<label class=\"uk-form-label\" for=\"id_cuenta\">Cuenta</label> ";
  echo selcCta("selc_trans($(this));","uk-text-primary  uk-text-bold uk-form-success  $formSize uk-form-width-medium");}
 
 ?>
</div>

<div class="uk-width-1-1">				 
<?php if($MODULES["CUENTAS_BANCOS"]==1){echo "<label class=\"uk-form-label\" for=\"id_cuenta_TRANS\">Cuenta para TRANSFERENCIA</label>";

echo selcCta("","uk-text-primary  uk-text-bold uk-form-success  $formSize uk-form-width-medium","","id_cuenta_TRANS",1);} ?>
</div>

</div>


<div class="uk-grid" data-uk-grid-margin>
<div class="uk-width-1-2">
<label class="uk-form-label" for="banco2">
Banco:
</label>
 
<input type="text" name="banco" id="banco2" value="" class="<?php echo $formSize; ?> uk-form-width-medium">

<span id="inCuenta"></span>
</div>
 
<div class="uk-width-1-2">
<label class="uk-form-label" for="num_cheque">
No. Cheque:
</label>
<input type="text" name="num_cheque" id="num_cheque" placeholder="" class="<?php echo $formSize; ?> uk-form-width-medium">
</div>

</div>

</div>

<div class="uk-grid" data-uk-grid-margin>

	<div class="uk-width-1-2">
		<label class="uk-form-label" for="beneficiario">Beneficiario</label>
		<input type="text" name="beneficiario" id="beneficiario" placeholder="Nombre" onBlur="busq_cli_x(this);" class="<?php echo $formSize; ?> uk-form-width-medium" value="<?php echo "$nomBene";?>">
	</div>

	<div class="uk-width-1-2">
		<label class="uk-form-label" for="nit">NIT/C.C.</label>
		<input type="text" name="nit" id="nit" placeholder="Doc. del Beneficiario" onBlur="busq_cli_x(this);" class="<?php echo $formSize; ?> uk-form-width-medium" value="<?php echo "$idBene";?>">
	</div>

</div>



<?php
if($MODULES["CUENTAS_PAGAR"]==1){
	if(($rolLv==$Adminlvl || val_secc($id_Usu,"compras")) ){
?>
<div class="uk-grid " data-uk-grid-margin id="pagoPro">
<div class="uk-width-1-1">
<label class="uk-form-label uk-text-warning " for="num_fac">
 PAGO COMPRAS
</label>
 
<select name="num_fac" id="num_fac" onChange="selc_prov($(this),$('#beneficiario'),$('#nit'),1);" data-placeholder="Escriba un No. de Factura" class="chosen-select  <?php echo $formSize; ?> uk-form-width-large" style="width:350px;" tabindex="2">
<option value="" selected>BORRAR</option>
<?php

 

$sql="SELECT * FROM fac_com WHERE pago='PENDIENTE' AND estado='CERRADA' AND  cod_su='$codSuc'  AND tipo_fac='Compra' ORDER BY nom_pro";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
$idFac=$row['serial_fac_com'];
$numFacCom=$row['num_fac_com'];
$nomPro=$row['nom_pro'];
$nitPro=$row['nit_pro'];
?>
<option value="<?php echo $idFac ?>" <?php if( $codCompra==$idFac){echo "selected";}?>><?php echo "$numFacCom-$nomPro" ?></option>
<?php
}
	}
?>
</select>
 </div>
 
</div>


<?php }?>

<div class="uk-grid" data-uk-grid-margin>
<div class="uk-width-1-1">
<label class="uk-form-label" for="valor">
 Valor Pago: 
</label>
<div class="uk-form-icon">
    <i class="uk-icon-usd uk-icon-large "></i>
<input type="text" name="valor" id="valor"  value="<?php echo money("$valPago");?>"  onKeyUp="puntoa($(this));calc_per_a($('#R_ICA_PER'),$('#valor'),$('#R_ICA'));calc_per_a($('#R_FTE_PER'),$('#valor'),$('#R_FTE'));" placeholder="Valor a Pagar" onChange="" class="uk-form-large<?php //echo $formSize; ?> uk-form-width-medium"/> 
</div>
<span id="saldoFac" class="uk-text-danger" align="center"></span>

</div>

</div>




<div class="uk-grid uk-hidden" data-uk-grid-margin id="pago_RETE">
<div class="uk-width-1-2">
<label class="uk-form-label" for="R_FTE_PER">
 R. FTE:
 </label> 
<input placeholder="%" id="R_FTE_PER" type="text"  value="<?php if($payComi==1)echo "10";?>" name="R_FTE_PER"  style="width:50px" onKeyUp="calc_per_a($(this),$('#valor'),$('#R_FTE'));" class=""/> 
<input id="R_FTE" type="text"  value="0" name="R_FTE" class=""/>
</div>
<div class="uk-width-1-2">
<label class="uk-form-label" for="R_ICA_PER">
 R. ICA: 
 </label>
<input placeholder="%" id="R_ICA_PER" type="text"  value="" name="R_ICA_PER"  style="width:50px" onKeyUp="calc_per_a($(this),$('#valor'),$('#R_ICA'));" class=""/> 
<input id="R_ICA" type="text"  value="0" name="R_ICA" class=""/>
 </div>
</div>

<div class="uk-grid" data-uk-grid-margin>
<div class="uk-width-1-1">
<label class="uk-form-label" for="concepto">
Por Concepto de:
</label>
<textarea name="concepto" id="concepto" cols="25" rows="2" style="width:300px;" class=""><?php echo "$des";?></textarea>
</div>

<div class="uk-width-1-1">
<span id="botonSubmit" class=" uk-button uk-button-primary uk-button-large uk-width-7-10"   onClick="save_egreso(this,'boton',document.forms['frm'])" style="margin-bottom:10px;">Guardar</span>
<br>
</div>
</div>



</div>
<?php require_once("FOOTER.php"); ?>
<?php require_once("autoCompletePack.php"); 

$rs=null;
$stmt=null;
$linkPDO= null;

?>


<input type="hidden" value="<?php echo "$feI";?>" name="feI" id="feI" />
<input type="hidden" value="<?php echo "$feF";?>" name="feF" id="feF" />
<input type="hidden" value="<?php echo "$payComi";?>" name="pay_comi" id="pay_comi" />
<input type="hidden" value="<?php echo "$codComi";?>" name="codComi" id="codComi" />


	
<input type="hidden" value="" name="verify" id="verify" />
<input type="hidden" value="" name="boton" id="boton" />
</form>

<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/chosen.jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/docsupport/prism.js"></script>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<!--
<script language='javascript' src="JS/imp_fac.js?<?php //echo "$LAST_VER" ?>"></script> 
-->
<script language='javascript' >


var usarCtas=<?php echo $MODULES["CUENTAS_BANCOS"]; ?>

function busq_cli_x(n)
{
	    
        $.ajax({
		url:'ajax/add_usu_ven.php',
		data:{ced:n.value},
	    type: 'POST',
		dataType:'JSON',
		success:function(response){
			var resp=response[0].respuesta*1;
			if(resp!=0)
			{		   
			  $('#beneficiario').prop('value',response[0].nombre);	
			  $('#nit').prop('value',response[0].cc);

			}
			
			
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
};
$(document).ready(function() {
call_autocomplete('ID',$('#beneficiario'),'ajax/busq_cli.php');	

selc_prov($('#num_fac'),$('#beneficiario'),$('#nit'),1);

<?php

if($payComi==1)echo "calc_per_a($('#R_FTE_PER'),$('#valor'),$('#R_FTE'));$('#r_fte_button').click();";

?>
	
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


	$('#loader').hide();
	$('#loader').ajaxStart(function(){
		$(this).show();
		$('input[type=button]').prop("disabled","disabled").css("color","red");
		})
	.ajaxSuccess(function(){
		$(this).hide();
		$('input[type=button]').removeAttr("disabled").css("color","black");
		});
	
	//$('#loader').ajaxError(function(){$('input[type=button]').prop("disabled","disabled").css("color","red");$(this).hide();});
});

var dobleCont=0;
function save_egreso(btn,id,frm)
{
	//$(btn).hide();
	 
	
	var val = btn.value;
	var fp=frm.form_pago.value;
	var ctaOUT=0;
	var ctaIN=0;
	
	if(usarCtas==1){
		
	 ctaOUT=frm.id_cuenta.value;
	 ctaIN=frm.id_cuenta_TRANS.value;
	}

    if(esVacio(frm.tipo_gasto.value)){simplePopUp('Indique el Tipo de Gasto');focusRed($(frm.tipo_gasto));}
	else if(esVacio(frm.fecha_pago.value) || frm.fecha_pago.value=='0000-00-00'){simplePopUp('Ingrese la fecha');focusRed($(frm.fecha_pago));}
	else if( (fp!='Contado'&&fp!='Contado-Caja General') && (esVacio(ctaOUT) ) &&  usarCtas==1){simplePopUp('Seleccione un CUENTA!!');focusRed($(frm.id_cuenta));}
	else if( (!esVacio(ctaIN) && (ctaOUT==ctaIN)) &&  usarCtas==1){simplePopUp('AMBAS CUENTAS NO PUEDEN SER IGUALES PARA UN TRANSFERENCIA!!');focusRed($(frm.id_cuenta_TRANS));}
	
	else if((fp=='Contado'||fp=='Contado-Caja General') && (!esVacio(ctaOUT) ) &&  usarCtas==1){simplePopUp('Los pagos de CONTADO solo pueden ir a la Cuenta de CAJA GENERAL');focusRed($(frm.form_pago));}
	
	else if(esVacio(frm.valor.value)){simplePopUp('Ingrese el Valor del Gasto');focusRed($(frm.valor));}
	else if(isNaN(quitap(frm.valor.value))){simplePopUp('Ingrese un NUMERO');focusRed($(frm.valor));}
	else if(esVacio(frm.concepto.value)){simplePopUp('Especifique concepto del Gasto');focusRed($(frm.concepto));}
	else if( $("#form_pago").val() == "Cheque" && ( $("#banco2").val() == "" || $("#num_cheque").val() == "" ) ){ if( $("#banco2").val() == "" ){ simplePopUp("Ingrese el nombre del banco");$("#banco2").focus(); }else if( $("#num_cheque").val() == "" ){ simplePopUp("Ingrese el número del cheque");$("#num_cheque").focus(); } }
	else if( $("#beneficiario").val() == "" || $("#nit").val() == "" ){ if($("#beneficiario").val() == ""){ simplePopUp("Ingrese el nombre del beneficiario");$("#beneficiario").focus(); }else if($("#nit").val() == ""){ simplePopUp("Ingrese la cédula o NIT del beneficiario");$("#nit").focus(); } }
	else if(dobleCont!=0){simplePopUp('Deje el puto AFAN!');}
	else {
	$('#'+id).prop("value",val);
    $(btn).hide();
	dobleCont=1;
	save_any($("#egresos"),'ajax/FORMS/egresos.php',function(r){
		
		if(r==2){
			simplePopUp('ESTE REGISTRO YA EXISTE');
			
			
			}
		else if(r==1){
			
			<?php if($tipo_impresion=="POS"){echo 'location.assign("imp_comp_egreso.php");';} 
					else {echo 'location.assign("imp_comp_egreso_com.php");';}
			?>
			
			}
		else if(r==0){simplePopUp('COMPLETE TODOS LOS CAMPOS');}
		else {simplePopUp(r);}
		});
	//print_pop('imp_comp_egreso.php','EGRESO',600,500);
	//$(btn).show();
	
	//frm.submit();
	
	}
	
	
	
};

function selc_trans($sel)
{
	var val=$sel.val();
	
	if(!esVacio(val)){$('#form_pago option[value="Tranferencia Bancaria"]').prop('selected', true);}
	else {$("#form_pago :selected").prop('selected', false);}
};

function selc_cuenta($banco,$box)
{
	var banco=$banco.val().split("|");
	if(!esVacio(banco[1])){
	var URL='ajax/busq_cuentas_banco.php',Data='id_banco='+banco[1]+'';
	$.ajax({
     type: 'POST',
     url: URL,
     data: Data,
     success: function(resp){
		 	  //$('#id_cuenta').remove();
			 $(resp).appendTo($box);
			
		 
		
    }
 });
 
	}//if !vacio
	else {/*$('#id_cuenta').remove();*/}
};
function selc_prov($nf,$nom,$nit,col)
{
	$nom.prop('value','');
	$nit.prop('value','');
	$('#saldoFac').html(' '); 
	var nf=$nf.val();
	if(!esVacio(nf)){
	var URL='ajax/busq_compra.php',Data='id_compra='+nf+'&col='+col;
	$.ajax({
     type: 'POST',
     url: URL,
     data: Data,
     success: function(resp){
			 if(resp!=-1){
				var r=resp.split("|");
				$nom.prop('value',r[0]);
				$nit.prop('value',r[1]);
				$('#valor').prop('value',puntob(r[2]));
				$('#concepto').prop('value',r[4]);
				$('#R_ICA').prop('value',puntob(r[5]));
				$('#R_FTE').prop('value',puntob(r[6]));
				
				if(r[5]>0 || r[6]>0){$('#r_fte_button').click();}
				
				$('#saldoFac').html('<B>SALDO: $'+puntob(r[2])+'</B>'); 
			 }
			
		 
		
    }
 });
 
	}//if !vacio
	else {/*$('#id_cuenta').remove();*/}
};


	$("#nit").on("change",function(){
		var nitStripTemp = $("#nit").val().replace(/\./g,'');
		$("#nit").val(nitStripTemp);
	});


 
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
  </script>
</body>
</html>