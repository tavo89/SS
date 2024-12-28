<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"adm_serv")){header("location: centro.php");}

//$serial=1;
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("HEADER.php"); ?></head>
<body >

  
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>
<div class="uk-width-9-10 uk-container-center">
<div class="grid-100 posicion_form" align="center">

    <h1 align="center">CREAR SERVICIO</h1>
    <form action="agregar_producto.php" method="get" name="add" class="uk-form ms_panels" id="add">
      <div id="crea_inv">
        <table cellspacing="10" style="font-size:16px" align="center">
						  

						<tr>
							  <td><label>CODIGO:</label><br><!--</td>
						</tr>
						<tr>
							<td>-->
								<input id="cod_serv" name="cod_serv" type="text" placeholder="CODIGO SERVICIO" onChange="validar_c($(this),'servicios','cod_serv','Este CODIGO  Ya Existe!!!');"  value=""/>
								<div id="resp" style="visibility:hidden; color: #F00; width:180px;"><img alt="" src="Imagenes/delete.png" width="20" height="20" />Este SERVICIO ya existe</div>
							</td>
						<!--</tr>
								
						<tr>-->
							<td><label>Servicio:</label><br><!--</td>
						</tr>
						<tr>
							<td>-->
								<input id="serv" name="serv" type="text" placeholder="NOMBRE SERVICIO" onChange="validar_c($(this),'servicios','servicio','Este SERVICIO  Ya Existe!!!');"  value=""/>
								<div id="resp" style="visibility:hidden; color: #F00; width:180px;"><img alt="" src="Imagenes/delete.png" width="20" height="20" />Este SERVICIO ya existe</div>
							</td>
						</tr>
								
						<tr>
							<td><label>Descripci&oacute;n:</label><br><!--</td>
						</tr>
						<tr>
							<td>--><textarea name="des_serv" id="des_serv" rows="4"  placeholder="Descripci&oacute;n del producto" ></textarea></td>
					<!--	</tr>
								
						<tr>-->
							<td ><label>KM Revisi&oacute;n:</label><br><!--</td>
						</tr>
						<tr>
							<td colspan="">-->
								<input name="km_rev" id="km_rev" type="text" placeholder="Cada cuantos KM se debe aplicar el servicio" />
							</td>
						</tr>
						<tr>
							<td ><label>Precio de Venta al P&uacute;blico:</label><br><!--</td>
						</tr> 
						<tr>
							<td colspan="2">-->
							<input name="pvp" value="0" type="text" placeholder="Precio de Venta" id="pvp"  onBlur="nan($(this))" onKeyUp="puntoa($(this));"/>
							</td>
						<!--</tr>
						<tr>-->
							<td><label>I.V.A:</label><br><!--</td>
						</tr> 
						<tr>
							<td colspan="2">-->
								<select name="iva" id="iva" onChange="">
								<option selected value=""></option>
								<option value="0">0%</option>
								<option value="5">5%</option>
                                <option value="10">10%</option>
								<option value="19" selected>19%</option>
								</select>
							</td>
						</tr>
						
							  
						<tr>
							<td>
								<span  onClick="subir($('#boton'),'Guardar',document.forms['add']);" class="uk-button uk-button-large"><i class=" uk-icon-floppy-o"></i>Guardar</span>
							</td>

							<td>
								<span class="uk-button uk-button-large" onClick="location.assign('servicios.php');"><i class=" uk-icon-history"></i>Volver</span>
							</td>

						</tr>  
		</table>

      </div>
      <input type="hidden" name="boton" id="boton" value="">
      <input type="hidden" name="verify" id="verify" value="">
      <input type="hidden" name="verify2" id="verify2" value="">
      <input type="hidden" value="" name="htmlPag" id="HTML_Pag">
    </form>
     <?php require_once("js_global_vars.php"); ?>
    <?php require_once("FOOTER.php"); ?>	
   
 <script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
    <script language='javascript' src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script> 
    <script language="javascript1.5" type="text/javascript">
$('input').on("change",function(){$(this).prop('value',this.value);});
$('textarea').on("change",function(){$(this).html(this.value);});
$(document).ready(function() {
	


$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});



	
	});
function subir(btn,val,form)
{
	var $form=$('#add');
	if(esVacio(form.serv.value)){simplePopUp('Ingrese el SERVICIO');form.serv.focus();}
	else if(esVacio(form.pvp.value) && form.pvp.value!='0'){simplePopUp('Ingrese el Precio de Venta');form.pvp.focus();}
	else if(esVacio(form.iva.value)){simplePopUp('Escoga el IVA');form.iva.focus();}
	else{
	btn.prop('value',val);
	var Datos=$form.serialize();
	$.ajax({
     type: 'POST',
     url: 'ajax/insert_serv.php',
     data: Datos,
     success: function(resp){
		 var r=resp*1;
			if(r==2)open_pop('ERROR','SERVICIO REPETIDO','');
			else if(r==1){
			
			open_pop('Guardado','SERVICIO Registrado con &Eacute;xito','');
			$("#modal").on({
							'uk.modal.hide': function(){
						
							location.assign("add_serv.php");
								}
								});	
		}
		
    },
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
 });
	
	
	}
	//resetForm($form);

};



</script> 
</body>
</html>