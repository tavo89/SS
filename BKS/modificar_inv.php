<?php
require_once("Conexxx.php");
$URL=thisURL();
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("HEADER.php"); ?>
<?php require_once("modificar_inv_bloque.php"); ?>
</head>
<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>	


<div class="uk-width-8-10 uk-container-center">
<div class="">
<h1 align="center">Modificar Art&iacute;culo</h1>
<form action="<?php echo $URL; ?>"   name="form_fac" class="uk-form ms_panels" enctype="multipart/form-data" method="post">
 

<?php  

if($check==2){
	
	?>
    <script language="javascript1.5">
	simplePopUp('Este producto YA ESTA REGISTRADO');
	</script>
    <?php
	
	}
	//echo "SELECT * FROM inv_inter WHERE id_inter='$id'<br>";
	
	
if(isset($rs)){
	
	
while($row=$rs->fetch()){
	$tipoD=$row['tipo_dcto'];
	$ref=$row['ref'];
	$cert=$row['certificado_importacion'];
	$pvpCre=$row['pvp_credito'];
	$pvpMay=$row['pvp_may'];
	$envase=$row["envase"];
	$ubi=$row["ubicacion"];
	$IMG_PRO=$row["url_img"];
?>
<h2 align="center"><?php echo $row['detalle'] ?> </h2>
<input type="hidden" value="<?php echo $feVen ?>" name="fe_ven">
<table  align="center">

<tr>
<td>
<table>
<td colspan="2" width="100px"><label>C&oacute;d. Barras:</label></td>
<td colspan="2"><input name="id_inter" type="text" value="<?php echo $row['cod_bar'] ?>" placeholder="C&oacute;digo" /></td>
</tr>
<tr><td colspan="2"><label>Costo:</label></td>
<td colspan="2"><input name="cost" value="<?php echo $row['costo']*1 ?>" type="text" placeholder="Costo del producto" id="costo" onKeyUp="puntoa($(this));tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');" /></td>
</tr>
<tr><td colspan="2"><label>Ganancia%:</label></td>

<td colspan=""><input name="ganancia" value="<?php echo $row['gana']*1 ?>" type="text" placeholder="Porcentaje de Descuento" id="ganancia" onKeyUp="tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');"  onBlur="nan($(this))"/></td>
<td width="60">

<select name="tipo_op" id="tipo_op" onChange="tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>'  );">
<option value=""  selected>CALCULAR</option>
<option value="ganancia" >Ganancia</option>
<option value="costo" >Costo</option>
<option value="pvp" >PVP</option>
</select>

</td>
</tr>
<!--
<tr>
<td colspan="2"><label>Dcto Pronto Pago %:</label></td>

<td colspan=""><input name="descuento2" value="<?php echo $row['dcto2']*1 ?>" type="text" placeholder="Porcentaje de Descuento" id="descuento2" onKeyUp="cto($('#tipo_dcto'),$('#descuento'),$('#costo'),$('#pvp'));"  onBlur="nan($(this))"/></td>
<td> </td>
</tr>
-->

<tr>

<td colspan="2"><label>P.V.P:</label></td>
<td colspan="2"><input name="pvp" value="<?php echo money($row['precio_v']*1) ?>" type="text" placeholder="Precio de Venta"  id="pvp" onKeyUp="puntoa($(this));tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');"/></td>
</tr>

<tr class="<?php if($MODULES["PVP_CREDITO"]!=1){echo "uk-hidden";}?>">

<td colspan="2"><label>P.V.P Credito:</label></td>
<td colspan="2"><input name="pvpCre" value="<?php echo money($pvpCre*1) ?>" type="text" placeholder="Precio de Venta"  id="pvpCre" onKeyUp="puntoa($(this));//tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');"/></td>
</tr>
<tr class="<?php if($MODULES["PVP_MAYORISTA"]!=1){echo "uk-hidden";}?>">

<td colspan="2"><label><?php echo "$label_pvp_mayo";?></label></td>
<td colspan="2"><input name="pvpMay" value="<?php echo money($pvpMay*1) ?>" type="text" placeholder="<?php echo "$label_pvp_mayo";?>"  id="pvpMay" onKeyUp="puntoa($(this));//tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');"/></td>
</tr>
<tr><td colspan="2"><label>I.V.A:</label></td>

<td colspan="2">
<select name="iva" id="iva" onChange="tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');">
<option selected value="<?php echo $row['iva'] ?>"><?php echo $row['iva'] ?>%</option>
<option value="0">0%</option>
<option value="5">5%</option>
<option value="10">10%</option>
<option value="19">19%</option>
</select>
</td>
</tr>


<?php 
if($usar_color==1){
?>
<tr><td colspan="2"><label>Color:</label></td>

<td colspan="2"><input name="color" value="<?php echo $row['color'] ?>" type="text" placeholder="Color"  id="pvp" /></td>
</tr>
<?php
}
?>

<?php 
if($usar_talla==1){
?>
<tr><td colspan="2"><label>Talla:</label></td>

<td colspan="2"><input name="talla" value="<?php echo $row['talla'] ?>" type="text" placeholder="talla"  id="pvp" /></td>
</tr>
<?php 
}
?>

<tr>
<td colspan="2"><label>Descripci&oacute;n:</label></td>
 
 <td colspan="2">
<textarea style="width:200px;" cols="10" rows="2" name="des" type="text" placeholder="Descripci&oacute;n del producto"><?php echo $row['detalle'] ?></textarea></td>
</tr>

<tr><td colspan="2"><label>Ubicacion:</label></td>

<td colspan="2"><input name="ubi" value="<?php echo $ubi ?>" type="text" placeholder="Ubicacion" /></td>
</tr>


<tr><td colspan="2"><label>Existencias:</label></td>

<td colspan="2"><input readonly name="exis" value="<?php echo $row['exist']*1 ?>" type="text" placeholder="Existencias" /></td>
</tr>
</table>
</td>
<td valign="top">
<table>
<tr>
<td colspan="2"><label>Referencia:</label></td>
<td colspan="2"><input name="refe" value="<?php echo $idPro ?>" type="text" placeholder="Presentacion"  id="refe" />
</td>
</tr>

<tr>
<td colspan="2"><label>Presentaci&oacute;n:</label></td>
<td colspan="2"><input name="pres" value="<?php echo $row['presentacion'] ?>" type="text" placeholder="Presentacion"  id="pres" />
</td>
</tr>
<tr>
<td colspan="2"><label>Fracciones:</label></td>
<td colspan="2">
<input value="<?php echo $row['fraccion'] ?>" name="fra" type="text" placeholder="Fracciones del producto" />
</td>
</tr>

<tr><td colspan="2"><label>Clase:</label></td>

<td colspan="2"><input name="clas" value="<?php echo $row['id_clase'] ?>" type="text" placeholder="Clase"  id="clas" /></td>
</tr>
<!--
<tr><td colspan="2"><label>SUB-Clase:</label></td>

<td colspan="2"><input name="sub_clas" value="<?php echo $row['id_sub_clase'] ?>" type="text" placeholder="SUB-Clase"  id="sub_clas" /></td>
</tr>
-->
<!--

<tr><td colspan="2"><label>Envase:</label></td>

<td colspan="2">
<select name="envase">
<option value="1" <?php if($envase==1)echo "selected" ?>>SI</option>
<option value="0" <?php if($envase==0)echo "selected" ?>>NO</option>
</select>
</td>
</tr>
-->
<tr>
<td colspan="2"><label>Fabricante:</label></td>
<td colspan="2"><input name="fab" value="<?php echo $row['fab'] ?>" type="text" placeholder="Fabricante"  id="fab" /></td>
</tr>

<tr>
<td colspan="2"><label>Tipo Producto:</label></td>
<td colspan="2"><select name="tipoProducto"    placeholder="tipoProducto"  id="fab">
<option value="<?php echo $row['tipo_producto'] ?>" selected><?php echo $row['tipo_producto'] ?></option>
<option value="Normal">Normal</option>
<option value="Manufacturado">Manufacturado</option>
<option value="Materia prima">Materia prima</option>
</select>
</td>
</tr>
<?php 
if($usar_fecha_vencimiento==1){
?>
<td>Fecha Vencimiento: </td>
<td colspan="2"><input name="fechaVenci" value="<?php echo $row['fecha_vencimiento'] ?>" type="date"  id="feven" /></td>

<?php 
}
?>
</tr>

<?php 
if($MODULES["APLICA_VEHI"]==1){
?>
<tr>
<td>Aplicaci&oacute;n Veh&iacute;culos: </td>
<td colspan="2"><input name="aplica_vehi" value="<?php echo $row['aplica_vehi'] ?>" type="text"  id="aplica_vehi" /></td>
</tr>
<?php 
}
?>

<?php 
if($MODULES["DES_FULL"]==1){
?>
<tr>
<td>Aplicaci&oacute;n: </td>
<td colspan="2"><input name="des_full" value="<?php echo $row['des_full'] ?>" type="text"  id="des_full" /></td>
</tr>
<?php 
}
?>

<?php 
if($usar_campos_01_02==1){
?>
<tr style="background-color:rgb(64, 64, 107);">
<td><?php echo $label_campo_add_01;?>: </td>
<td colspan="2"><input name="campo_add_01" value="<?php echo $row['campo_add_01'] ?>" type="text"  id="campo_add_01" /></td>
</tr>
<tr style="background-color:rgb(64, 64, 107);">
<td><?php echo $label_campo_add_02;?>: </td>
<td colspan="2"><input name="campo_add_02" value="<?php echo $row['campo_add_02'] ?>" type="text"  id="campo_add_02" /></td>
</tr>
<?php 
}
?>
<!--

<tr>
<td colspan="2"><label>Marca/Modelo</label></td>
 
 <td colspan="2">
<textarea  cols="10" rows="2" name="cdi"  style="width:200px;"><?php echo $cert ?></textarea>
</td>
</tr>
-->
<tr>
<td colspan="2"><label>Foto</label></td>
 
<td colspan="2">
<input type="file" name="img_pro" size="40" value="<?php echo $IMG_PRO?>">
<?php echo print_pro($IMG_PRO) ?> 
</td>
</tr>

</table>
</td>
</tr>
<tr>
<td colspan="4">
<table cellpadding="0" cellspacing="0" align="center">
<tr>
<td>

<span  onClick="mod_inv(document.forms['form_fac']);" class="uk-button uk-button-large uk-button-success"><i class=" uk-icon-floppy-o"></i>Guardar</span>
</td>
<td>
<span class="uk-button uk-button-large" onClick="location.assign('inventario_inicial.php');"><i class=" uk-icon-history"></i>Volver</span>
</td>
</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" value="<?php echo $codBarras ?>" name="REF" id="REF">
<input type="hidden" value="<?php echo $idPro ?>" name="idPro" id="idPro">

<input type="hidden" value="<?php echo $serialInv ?>" name="serial_inv" id="serial_inv">
<input type="hidden" value="<?php echo $serialPro ?>" name="serial_pro" id="serial_pro">

<input type="hidden" name="boton" id="botonG" value="">
    <input type="hidden" value="" name="html_antes" id="HTML_antes">
    <input type="hidden" value="" name="html_despues" id="HTML_despues">
</form>
</div>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 	
<script language='javascript' src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script> 
<?php require_once("js_global_vars.php"); ?>
<?php require_once("FOOTER.php"); ?>

 <?php 
 
 require_once("autoCompletePack.php"); 
 
 if($check==1){
	
	?>
    <script language="javascript1.5">
	//warrn_pop('Guardado Con Exito');
	var modal = UIkit.modal.blockUI("<h1 class='uk-text-primary uk-text-center uk-text-bold'>Guardado Con Exito</h1>");
	setTimeout(function()
					{
						modal.hide();
					},2500);

	</script>
    <?php
	
	}

 ?>

 

<script language="javascript1.5" type="text/javascript">
$('input').on("change",function(){$(this).prop('value',this.value);});
$('textarea').on("change",function(){$(this).html(this.value);});


function mod_inv(frm)
{
	var modal = UIkit.modal.blockUI("<h1 class='uk-text-primary uk-text-center uk-text-bold'>Enviando Solicitud.......</h1>");
	$('#botonG').prop('value','Guardar');
	frm.submit();
};
$(document).ready(function() {
	call_autocomplete('NOM',$('#clas'),'ajax/busq_class.php');

//call_autocomplete('NOM',$('#sub_clas'),'ajax/busq_sub_class.php');

call_autocomplete('NOM',$('#fab'),'ajax/busq_fab.php');

call_autocomplete('NOM',$('#pres'),'ajax/busq_presentacion.php');
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


getPage($('#HTML_antes'),$('#mod_inv'));																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																											

$('body').ajaxStart(function(){
	
	$('input[type=button]').prop("disabled","disabled").css("color","red");

		
		})
		
.ajaxSuccess(function(){
	$('input[type=button]').removeAttr("disabled").css("color","black");
		
		
		})
	
.ajaxError(function(){$('input[type=button]').prop("disabled","disabled").css("color","red");$(this).hide();});	

	
	});
</script>
<?php
}//cieere while
}//cierre if val rs

?>
</body>
</html>