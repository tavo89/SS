<?php
require_once("Conexxx.php");
?>
<!DOCTYPE html PUBLIC>
<html>
<head>
<?php require_once("HEADER.php"); ?>
<link href="css/multi-select.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->

            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">
<h1 align="center"><i class="uk-icon-envelope-o"></i> &nbsp;Env&iacute;o Por Grupos</h1>
<div class=" posicion_form uk-grid uk-contrast">
<form class="uk-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form" id="form">
<fieldset>
        <legend>Tel&eacute;fonos (grupos)</legend>
        <div class="uk-form-row">
  		
				<div class="ms_panels uk-vertical-align-middle">	
				 
					<div id="filtroGruposDifusionBox" style="min-width:250px">
						<input type="button" name="botonFiltro" value="Ver Lista" onClick="addMultiSelc('filtroGruposDifusionBox','s','telefonos[]','telefonos','','sms_grupos_difusion','','id','nombre_grupo');document.getElementById('toggle_fab_ms').style = 'display:block;border-radius:5px;margin-top:10px;'" class="uk-button uk-button-success uk-button-large uk-width-1-2"> 
						<?php //echo ciudades($conex,"","ciudad[]","ciudades",""); ?>
					</div>
					
					<input id="toggle_fab_ms" type="button" value="Ocultar/Mostrar" class="uk-button" data-uk-toggle="{target:'#filtroGruposDifusionBox'}" style="display:none">
				</div>
        </div>
        
        
        <legend>Tel&eacute;fonos (Rutas)</legend>
        <div class="uk-form-row">
  		
				<div class="ms_panels uk-vertical-align-middle">	
				 
					<div id="filtroRutasBox" style="min-width:250px">
						<input type="button" name="botonFiltro2" value="Ver Rutas" onClick="addMultiSelc('filtroRutasBox','','telefonosRutas[]','telefonosRutas','','servicio_rutas','','id','nombre_ruta');document.getElementById('toggle_fab_ms2').style = 'display:block;border-radius:5px;margin-top:10px;'" class="uk-button uk-button-success uk-button-large uk-width-1-2"> 
						<?php //echo ciudades($conex,"","ciudad[]","ciudades",""); ?>
					</div>
					
					<input id="toggle_fab_ms2" type="button" value="Ocultar/Mostrar" class="uk-button" data-uk-toggle="{target:'#filtroRutasBox'}" style="display:none">
				</div>
        </div>
        <!--
              <textarea name="msisdns" id="numberField" rows="8" cols="13" onKeyUp="numCounter(this)" style="width:160px"></textarea>
<div class="uk-form-row">
<div class=" uk-width-1-1">      	
<div class="uk-panel uk-panel-box">
<div class="uk-panel-badge uk-badge">Tip</div>
<p class=""><i class="uk-icon uk-icon-lightbulb-o" ></i>&nbsp;<span  >3103895258<br>&nbsp;&nbsp;&nbsp;3203048997<br>&nbsp;&nbsp;&nbsp;3102895678<br>&nbsp;&nbsp;&nbsp;3197858367</span><br><span class="help">&nbsp;Un número por línea. No incluir el c&oacute;digo del pa&iacute;s, por defecto se enviar&aacute;n a COLOMBIA.</span></p>
</div>
</div>
</div>
-->

<div class="uk-form-row">
<legend>Mensaje</legend>
<textarea name="mensaje" id="messageField" rows="4" cols="40"></textarea>
</div>
<div class="uk-form-row">

<div class=" uk-width-1-1">      	
<div class="uk-panel uk-panel-box">
car&aacute;teres: <span id="characters" class="uk-text-primary uk-text-large" style="font-size:34px;"></span>
</div>
</div>

</div>
<div class="uk-form-row">
<button class="uk-button  uk-button-large uk-width-1-2" type="button" data-uk-button  id="botonEnviar"><i class="uk-icon uk-icon-envelope-o"></i> &nbsp;ENVIAR</button>
</div>
    </fieldset>

</form>

</div>
</div>
</div>


<?php require_once("FOOTER.php"); ?>
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery.multi-select.js"></script>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script language="javascript1.5" type="text/javascript">
//Send_SMS();
$('#botonEnviar').bind("click",Send_SMS);

$('#messageField').keyup(updateCount);
$('#messageField').keydown(updateCount);

 
	
	
function updateCount() {
    var cs = $(this).val().length;
    $('#characters').text(cs+" / 160");
};
function Send_SMS(){
	$('#botonEnviar').unbind("click",Send_SMS);
	var txt=$('#messageField').val();
	//alert("texto:"+txt);
	
	var Data=$('#form').serialize();
	var URL='ajax/SMS/RESP_API_mensajes.php';
	//alert("form: "+Data);
	ajax_x(URL,Data,function(resp){
		
		simplePopUp("Mensajes Enviados");
		$('#botonEnviar').bind("click",Send_SMS);
		//butt_gfv $('#butt_gfv').bind("click",gfv);
		});
		
};
</script>
</body>
</html>