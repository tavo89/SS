<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"inventario_mod")){header("location: centro.php");}
$val= "";
$boton= "";
$pag="";
$boton= "";
$id_inter="";
$id_pro= "";
$pag=$_SESSION['pag'];
$backURL="inventario_inicial.php";

$nit= "";
$exis= "";
$max= "";
$min= "";
$cost= "";
$pvp= "";
$fra= "";
$check="";
$iva= "";
$gana= "";
$codBarras=$_REQUEST['REF'];

$id="";
if(isset($_REQUEST['REF']))$id=r('REF');
else{header("location: $backURL");}
if(isset($_REQUEST['boton']))

{
	$boton= r('boton');
	$cost= quitacom($_REQUEST['cost']);
	$gana= limpiarcampo($_REQUEST['ganancia']);
	
}


if($boton=='Volver'){header("location:inventario_inicial.php?pag=".$_SESSION['pag']);}

if($boton=='Guardar'){

$id_inter= r('id_inter');
//$id_pro= $_REQUEST['id_pro'];
 	
$exis= r('exis');
$max= 0;//$_REQUEST['max'];
$min= 0;//$_REQUEST['min'];
$cost= quitacom($_REQUEST['cost']);
$pvp= quitacom($_REQUEST['pvp']);
$fra= 0;//$_REQUEST['fra'];
//$check=$_REQUEST['ck'];
$iva= r('iva');
$gana= r('ganancia');
$color= rm('color');
$talla= rm('talla');
$gana2= "";//limpiarcampo($_REQUEST['descuento2']);
$tipoD="";// limpiarcampo($_REQUEST['tipo_dcto']);

	//echo "UPDATE  `motosem`.`inv_inter` SET  `id_inter` =  '$id_inter',`exist` =  '$exis',`max` =  '$max',`min` =  '$min',`costo` =  '$cost',`precio_v` =  '$pvp',`fraccion` =  '$fra',`gana` =  '$gana',`iva` =  '$iva' WHERE `inv_inter`.`id_pro` =  '$id'<br>";


	

$linkPDO->exec("UPDATE `inv_inter` SET  `id_inter` =  '$id_inter',`max` =  '$max',`min` =  '$min',`costo` =  '$cost',`precio_v` =  '$pvp',`fraccion` =  '$fra',`gana` =  '$gana',`iva` =  '$iva',dcto2='$gana2',`tipo_dcto`='$tipoD',`color` =  '$color',`talla` =  '$talla' WHERE `inv_inter`.`id_inter` =  '$id' AND nit_scs=$codSuc", $conex);

$check=1;
/*$sqlLog="<ul><li>UPDATE `inv_inter` SET  `id_inter` =  '$id_inter',`max` =  '$max',`min` =  '$min',`costo` =  '$cost',`precio_v` =  '$pvp',`fraccion` =  '$fra',`gana` =  '$gana',`iva` =  '$iva',dcto2='$gana2',`tipo_dcto`='$tipoD' WHERE `inv_inter`.`id_pro` =  '$id' AND nit_scs=$codSuc</li></ul>";

$HTML_antes="";
$HTML_despues="";
if(isset($_REQUEST['html_antes']))$HTML_antes=$_REQUEST['html_antes'];
if(isset($_REQUEST['html_despues']))$HTML_despues=$_REQUEST['html_despues'];
logDB($sqlLog,$SECCIONES[7],$OPERACIONES[2],$HTML_antes,$HTML_despues,$id_inter);
*/

//header("location:modificar_inv.php?ck=1");
}

?>
<!DOCTYPE html>
<html>
<head>
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
<h1 align="center">Modificar Art&iacute;culo</h1>
<form action="modificar_inv.php" method="post" name="form_fac" class="uk-form">
<?php  

if($check==1){
	
	?>
    <script language="javascript1.5">
	simplePopUp('Guardado Con Exito');
		location.assign('inventario_inicial.php?pag=<?php echo $pag ?>');

	</script>
    <?php
	
	}
?>

<?php  

if($check==2){
	
	?>
    <script language="javascript1.5">
	simplePopUp('Este producto YA ESTA REGISTRADO');
	</script>
    <?php
	
	}
	
	

	
	

?>

<table  align="center">

<tr>
<td colspan="2" width="100px">
<label>C&oacute;digo Interno<br>/ C&oacute;d. Barras:</label></td>
<td colspan="2"><input name="id_inter" type="text" value="<?php echo $row['cod_bar'] ?>" placeholder="C&oacute;digo" /></td>
</tr>
<tr><td colspan="2"><label>Costo:</label></td>
<td colspan="2"><input name="cost" value="<?php echo $row['costo']*1 ?>" type="text" placeholder="Costo del producto" id="costo" onKeyUp="puntoa($(this));tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');" /></td>
</tr>
<tr><td colspan="2"><label>Ganancia%:</label></td>

<td colspan=""><input name="ganancia" value="<?php echo $row['gana']*1 ?>" type="text" placeholder="Porcentaje de Descuento" id="ganancia" onKeyUp="tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');"  onBlur="nan($(this))"/></td>



<tr><td colspan="2"><label>P.V.P:</label></td>

<td colspan="2"><input name="pvp" value="<?php echo $row['precio_v']*1 ?>" type="text" placeholder="Precio de Venta"  id="pvp" onKeyUp="puntoa($(this));tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');"/></td>
</tr>
<tr><td colspan="2"><label>I.V.A:</label></td>

<td colspan="2">
<select name="iva" id="iva" onChange="tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>');">
<option selected value="<?php echo $row['iva'] ?>"><?php echo $row['iva'] ?>%</option>
<option value="0">0%</option>
<option value="5">5%</option>
<option value="16">16%</option>
</select>
</td>
</tr>



<tr>
<td colspan="4">
<table cellpadding="0" cellspacing="0" align="center">
<tr>
<td>

<span  onClick="save_inv(document.forms['form_fac']);" class="uk-button uk-button-large"><i class=" uk-icon-floppy-o"></i>Guardar</span>
</td>
<td>
<span class="uk-button uk-button-large" onClick="Goback();"><i class=" uk-icon-history"></i>Volver</span>
</td>
</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="boton" id="botonG" value="">
    <input type="hidden" value="" name="html_antes" id="HTML_antes">
    <input type="hidden" value="" name="html_despues" id="HTML_despues">
</form>
</div>
<?php require_once("js_global_vars.php"); ?>
<?php require_once("FOOTER.php"); ?>

<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 	
<script language='javascript' src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script> 
<script language="javascript1.5" type="text/javascript">
$('input').on("change",function(){$(this).prop('value',this.value);});
$('textarea').on("change",function(){$(this).html(this.value);});


function save_inv(frm)
{
	getPage($('#HTML_despues'),$('#mod_inv'));
	$('#botonG').prop('value','Guardar');
	frm.submit();
};
$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


getPage($('#HTML_antes'),$('#mod_inv'));																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																											

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
</script>

</body>
</html>