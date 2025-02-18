<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"inventario_add")){header("location: centro.php");}

// temporales
$claseTemp = s('claseTemp');

$clase = (!empty($claseTemp))?$claseTemp:'';

$serial=$hostName=="elarauco.nanimosoft.com"? serial_id_pro():'';
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
<div class="uk-width-9-10 uk-container-center" >
<div class=" posicion_form" align="center" >

    <h1 align="center">Crear Producto Nuevo</h1>
    <form action="agregar_producto.php"    class="uk-form ms_panels" name="addpro" id="addpro" enctype="multipart/form-data" method="post">
      <div id="crea_inv">
        <table cellspacing="10" style="font-size:20px" align="center" class="">
          <tr>
            <td valign="top">
            <table align="left" width="">
                <tr>
                  <td><label>Referencia:</label></td>
                </tr>
                <tr>
                  <td><input id="id_pro" name="id_producto" type="text" placeholder="Codigo del producto" onChange="validar_c($(this),'<?php echo tabProductos; ?>','id_pro','Esta Referencia Ya Existe!!!');load_ref($(this).val());copiaCodigo($(this).val());"  value="<?php echo $serial ?>"/>
				  
				  <input id="id_pro2" name="id_producto2" type="hidden" placeholder="Codigo del producto"  value="<?php echo $serial ?>"/>
				  <td>
<!--<a href="#" class="uk-icon-bars uk-icon-button uk-icon-hover uk-icon-small" onClick="serial_inv();">

</a>-->
</td>
				  <div id="resp" style="visibility:hidden; color: #F00; width:180px;"><img alt="" src="Imagenes/delete.png" width="20" height="20" />Este C&oacute;digo ya existe</div></td>
                </tr>
                <tr>
                  <td colspan="2"><label>C&oacute;d. Barras:</label></td>
                </tr>
                <tr>
                  <td colspan=""><input name="id_inter" id="id_int" type="text" placeholder="C&oacute;digo" onChange="valida_inv($(this),$('#cod_su'),'inv_inter','id_inter','nit_scs','Esta Referencia Ya Existe!!!');"/><div id="resp2" style="visibility:hidden; color: #F00; width:180px;"><img alt="" src="Imagenes/delete.png" width="20" height="20" />Este C&oacute;digo ya existe</div>
                  <input type="hidden" value="<?php echo "$codSuc" ?>" name="cod_su" id="cod_su">
                  </td>
                </tr>
                <tr>
                  <td><label>Descripci&oacute;n:</label></td>
                </tr>
                <tr>
                  <td><textarea name="des" id="des" rows="4"  placeholder="Descripci&oacute;n del producto" ></textarea></td>
                </tr>
<?php 
if($MODULES["APLICA_VEHI"]==1){
?>
                <tr>
                  <td><label>Aplicaci&oacute;n:</label></td>
                </tr>
                <tr>
<td colspan="4"><input style="width: 300px;" name="aplica_vehi" value="" type="text"  id="aplica_vehi" placeholder="Nombre del producto"  /></td>
</tr>

<?php 
}
?>
<tr>
 <td><label>Fabricante / Marca:</label></td>
 </tr>
<tr>
<td><input name="fab" id="fab" type="text" placeholder="Fabricante" value="" onBlur="$('#claseA').focus()" /></td>
 </tr>
   
<tr>
<td><label >Clase:</label><BR><input name="claseA" id="claseA" type="text" placeholder="Clase" value="<?php echo $clase;?>" onBlur="$('#costo').focus()" onfocus="$(this).select();"/></td>
</tr>
<!-- 
<tr>
<td><label >SUB Clase:</label><BR><input name="sub_claseA" id="sub_claseA" type="text" placeholder="SUB Clase" value="" /></td>
</tr>
 -->       

                      
 
              </table>
              
              </td>
            <td valign="top">
            
            
            
           
              
              <table align="left" >
                 <?php
			if($usar_color==1){  
			  
			  ?>            
                
               
                <tr>
                  <td><label >Color:</label>
                  </td>
                  </tr>
                  <tr>
                  <td>
                  <input name="colorA" id="colorA" type="text" placeholder="Color NUEVO" value="" /></td>
                </tr>
           
 
<?php }	 ?>
              
              
              <?php if($usar_talla==1){?>
                 <tr>
                  <td><label >Talla:</label>
                  </td>
                  </tr>
                  <tr>
                  <td>
                  <input name="tallaA" id="tallaA" type="text" placeholder="Talla NUEVA" value="" /></td>
                </tr>
                <tr>
                  <td>
               
                  </td>
                </tr>

<?php
	}		  
			  
			  ?>


<tr>
 <td><label>Presentaci&oacute;n:</label></td>
 </tr>
<tr>
<td><input name="presentacion"  id="presentacion" type="text" placeholder="presentacion" value="UNIDAD" /></td>
 </tr>
 <!--
<tr>
<td colspan=""><label>Foto</label></td>
</tr>

<tr>
<td colspan="">
<input type="file" name="img_pro"  size="40" value="">
</td>
</tr>

-->
<tr>
 <td><label>Fracci&oacute;n:</label></td>
 </tr>
<tr>
<td><input name="frac" id="frac" type="text" placeholder="UNIDAD:1, DOCENA:12,ETC" value="1" /></td>
 </tr>
 
 <tr>
 <td><label>Tipo Producto:</label></td>
 </tr>
<tr>
<td><select name="tipoProducto" id="tipoProducto">
<option value="Normal" selected>Normal</option>
<option value="Manufacturado" >Manufacturado</option>
<option value="Materia prima" >Materia prima</option>

</select>
</td>
 </tr>
 
<!-- 
 <tr>
 <td colspan="2"><label>Envase/Caja Retornable:</label></td>

</tr>

<tr>
<td>
<select name="envase">
<option value="1" selected>SI</option>
<option value="0" >NO</option>
</select>
</td>
</tr>
-->
<?php
if($usar_fecha_vencimiento==1){
?>
<tr>
 <td><label>Fecha Vencimiento:</label></td>
 </tr>
<tr>
<td><input name="fecha_ven" type="date" placeholder="" value="" /></td>
 </tr>




 <?php
}
 ?>
  <tr>
            <td colspan="2" align="center" valign="bottom">
            <table cellpadding="0" cellspacing="10">
                <tr>
                  <td>
                  <span  onClick="guardarProducto($('#boton'),'Guardar',document.forms['addpro']);" class="uk-button uk-button-large uk-button-success"><i class=" uk-icon-floppy-o"></i> &nbsp;Guardar</span>


                  </td>
<td><span class="uk-button uk-button-large uk-button-primary" onClick="location.assign('inventario_inicial.php');"><i class=" uk-icon-history"></i>&nbsp;Volver</span></td>
                </tr>
              </table>
              </td>
          </tr>
</table>

</td>



<td valign="top">

<table>
<tr><td colspan="2"><label>Costo:</label></td>
</tr>
<tr><td colspan="2"><input name="cost" value="0" type="text" placeholder="Costo del producto"  id="costo" onKeyUp="puntoa($(this));tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');" onBlur="nan($(this))" onclick="$(this).select();"/></td>
</tr>
<tr><td colspan="2"><label>Ganancia %:</label></td>
</tr> 
<tr>
<td colspan="2"><input name="gana" value="30" type="text" placeholder="Porcentaje de Ganancia" id="ganancia" onKeyUp="tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');"  onBlur="$('#pvp').focus();" onfocus="$(this).select();"/></td>
<td width="60">

<select name="tipo_op" id="tipo_op" onChange="tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');" class="uk-text-danger  uk-text-bold uk-form-danger uk-form-large">
<option value=""  >CALCULAR</option>
<option value="ganancia"  >Ganancia</option>
<option value="costo" selected>Costo</option>
<option value="pvp" >PVP</option>
</select>

</td>

</tr>

<tr>
<td colspan="2"><label>Precio de Venta al P&uacute;blico:</label></td>
</tr> 
<tr>
<td colspan="2"><input name="pvp" value="" type="text" placeholder="Precio de Venta" id="pvp"  onBlur="nan($(this))" onKeyUp="puntoa($(this));tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');saveFormEnterKey()"/></td>
</tr>
<?php
if($MODULES["PVP_CREDITO"]==1){
?>
<td colspan="2"><label>P.V.P Credito:</label></td>
</tr>
<tr>
<td colspan="2"><input name="pvpCre" value=" " type="text" placeholder="Precio de Venta"  id="pvpCre" onKeyUp="puntoa($(this));//tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');"/></td>
</tr>

<?php
}
if($MODULES["PVP_MAYORISTA"]==1){
?>
<tr>
<td colspan="2"><label>P.V.P al Mayor:</label></td>
</tr>
<tr>
<td colspan="2"><input name="pvpMay" value=" " type="text" placeholder="PvP Mayorista"  id="pvpMay" onKeyUp="puntoa($(this));//tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');"/></td>
</tr>


<?php

}
?>
<tr>
  <td colspan="2">
    <label>I.V.A:</label>
  </td>
</tr>
<tr>
<td colspan="2">
<select name="iva" id="iva" onChange="tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');">
<option  value=""></option>
<option value="0" <?php if($REGIMEN!="COMUN") echo "selected";?>>0%</option>
<option value="5">5%</option>
<option value="10">10%</option>
<option value="19" <?php if($REGIMEN=="COMUN") echo "selected";?>>19%</option>
</select>
</td>
</tr>

<tr>
<td colspan="2"><label>Impuesto Saludable</label></td>
</tr>
<tr>
<td colspan="2">
<?php 
$onChangeFunction="tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'$redondear_pvp_costo',0);";
$formsElements = new lib_CommonFormsElements();
$impuestoSaludableForm = $formsElements->impuestoSaludable($onChangeFunction,0,0);
echo $impuestoSaludableForm;
?>
</td>
</tr>

</table>
</td>        
          
</tr>
         
        </table>
      </div>
      <input type="hidden" name="boton" id="boton" value="">
      <input type="hidden" name="verify" id="verify" value="">
      <input type="hidden" name="id_serial" id="id_serial" value="">
      <input type="hidden" name="cur_serial" id="cur_serial" value="">
      <input type="hidden" name="verify2" id="verify2" value="">
      <input type="hidden" value="" name="htmlPag" id="HTML_Pag">
    </form>
    <?php require_once("js_global_vars.php"); ?>

    <?php require_once("FOOTER.php"); ?>	
        <?php require_once("autoCompletePack.php"); ?>	
    <script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
    <script language='javascript' src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script> 
    <script language="javascript1.5" type="text/javascript">
	$('#id_pro').focus();
function saveFormEnterKey(){
	if(!event.which && ((event.charCode || event.charCode === 0) ? event.charCode: event.keyCode))
	{
		event.which = event.charCode || event.keyCode;
	}
	key=event.which;
	
	if(key==13){
		guardarProducto($('#boton'),'Guardar',document.forms['addpro']);
	}
}

function copiaCodigo(valorRef){
	$('#id_int').prop('value',valorRef);
	$('#des').focus()
}
function load_ref(ref)
{
	if(!esVacio(ref)){
	var data='ref='+ref;
	var URL='ajax/add_art_inv.php';
	ajax_x(URL,data,function(resp){
		var r=resp.split("|");
		$('#des').prop('value',r[1]);
		$('#fab').prop('value',r[8]);
		$('#claseA').prop('value',r[14]);
		$('#presentacion').prop('value',r[11]);
		$('#frac').prop('value',r[9]);
		$('#costo').prop('value',r[4]);
		$('#ganancia').prop('value',r[5]);
		$('#pvp').prop('value',r[3]);
		$('#iva option[value='+r[2]+']').prop('selected', true);
		
		});
		
	}
};

function set_serial(id_serial,current)
{
	$('#id_pro').prop('value',current);
	$('#id_int').prop('value',current);
	
	$('#id_serial').prop('value',id_serial);
	$('#cur_serial').prop('value',current);
	
	hide_pop('#modal');	
	remove_pop($('#modal'));
	
};
	
function serial_inv()
{
	var data='id=';
	var URL='ajax/POP_UPS/inv_ids.php';
	ajax_x(URL,data,function(resp){open_pop3('Seriales Inventario ','',resp,1)});
	
};	
	
$('input').on("change",function(){$(this).prop('value',this.value);});
$('textarea').on("change",function(){$(this).html(this.value);});
$(document).ready(function() {
	

call_autocomplete('NOM',$('#claseA'),'ajax/busq_class.php');

//call_autocomplete('NOM',$('#sub_claseA'),'ajax/busq_sub_class.php');

call_autocomplete('NOM',$('#fab'),'ajax/busq_fab.php');

call_autocomplete('NOM',$('#presentacion'),'ajax/busq_presentacion.php');

$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});




	
	});


function selc(i,id)
{
	$('#id_pro').prop('value',id);
}


function br()
{
	var v=$("#sb").val();
	if($("#sb").length > 0)
	{

$.ajax({
	url:"ajax/br_ao.php",
	data:{ba:v},
	type:"POST",
	cache:"false",
	dataType:"text",
	success:function(text){
	    var $art=$(text);
		$('#busqArt').html($art)//.appendTo('#busqArt');
		},
	error:function(xhr,status){simplePopUp('Ups! Ha ocurrido un error..'); simplePopUp('xhr:' + xhr); simplePopUp('status:' + status);}
	});	
	
	}	

};

</script> 
</body>
</html>