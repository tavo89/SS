<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"informes_kardex")){header("location: centro.php");}
$boton="";
$num_fac="";

$ref=r("Ref");
$feVen=r("feVen");
if(isset($_REQUEST['boton'])){
//$num_fac= limpiarcampo($_REQUEST['num_fac']);
$boton=$_REQUEST['boton'];
$busq=$_REQUEST['busq'];
$col=$_REQUEST['opc'];

}
//echo "$busq, $col";
if($boton=='Ver Kardex'&&isset($busq)&&!empty($busq)&&isset($col)&&!empty($col))
{
	/*
	$_SESSION['codBar']=$busq;
	$_SESSION['Ref']=$ref;
	$_SESSION['feVen']=$feVen;
	$_SESSION['col']=$col;*/
	popup("kardex.php?codBar=$busq&Ref=$ref&feVen=$feVen&col=$col","Informe Facturas Venta Anuladas","900px","600px");

	
}

 

?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("HEADER.php"); ?>

<script type="text/javascript" language="javascript1.5" >
var dcto_remi=0;
</script>

</head>
<body class="ui-content" >

<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>	


<div class="uk-width-9-10 uk-container-center">
<?php require_once("sub_menu_informes.php"); ?>
<div class=" grid-100 posicion_form">
<h1 align="center">INFORME K&Aacute;RDEX</h1>
<form action="panel_kardex.php" method="get" name="anular" id="form_fac">
<div id="query"></div>

<table  width="600px" align="center" style="color:#FFF">
<tr >
<td style="color:#FFF"><label></label></td>
</tr>
<tr>

<td width="400px">
<table id="fecha" align="center" border="0"  style="color:#FFF">

<tr>
<td><input type="text" name="busq" id="cod" value="" placeholder="Referencia" onKeyPress="cod_fs($(this));">

<input type="hidden" value="" id="feVen" name="feVen">
                <input type="hidden" value="" id="Ref" name="Ref">
</td>
<td>
<img style="cursor:pointer" title="Buscar" onMouseUp="busq_fs($('#cod'));" src="Imagenes/search128x128.png" width="34" height="31" />
<div id="Qresp"></div></td>
<td>
<select name="opc">
<option value="" selected></option>
<option value="1"  selected>Referencia</option>
<!--<option value="2" >Descripcion</option>-->
</select>
</td>
<td><input  type="submit" value="Ver Kardex" name="boton" class="button"  /></td>

<td>&nbsp;</td>
</tr>
</table>

</td>
<!--<td align="left" ><img id="loader" src="Imagenes/ajax-loader.gif" width="31" height="31"  style="position:relative; left:0px"/></td>
-->
</tr>


</table>
</form>
</div>
<?php require_once("js_global_vars.php"); ?>
<?php require_once("FOOTER.php"); ?>

<script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>
<script language="javascript" type="text/javascript" src="JS/fac_ven.js?<?php  echo "$LAST_VER"; ?>"></script>
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js?<?php  echo "$LAST_VER"; ?>"></script>

<script language="javascript1.5">
function busq_fs(n)
{//alert(n.val());
	var $val=$('#Qtab');
	if($val.length!=0){$val.remove();}
	
	if(!esVacio(n.val())){
		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_art_fs.php',
		data:{busq:n.val(),fs:1},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			//$('#Qresp').html(text);
			open_pop('Resultado Busqueda','',text);
			remove_pop($('#modal'));
			
			if($('#tab_art').lenght!=0){
				n.blur();
				$('#tab_art').focus();
			
				}
			/*$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);*/
			}
			else {alert('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
	
	}
};
function cod_fs($n){
	 remove_pop($('#modal'));
	//alert('cod '+n.value);
if(!esVacio($n.val()))
{
// $n.bind('keyup',function(e){
if (!event.which && ((event.charCode || event.charCode === 0) ? event.charCode: event.keyCode))
{
    event.which = event.charCode || event.keyCode;
}
	key=event.which;
	if(contTO==0)
	{
	timeOutCod=setTimeout(function()
	{
		if(op=='add')add_art_ven();
		else if(op=='mod')add_art_ven_mod();
		
		},800000);
		contTO=1;
	}
	
	//alert('key:'+key);
	if(key==13 || key==120){busq_fs($('#cod'));}

	//});
	
	}
	///////////////////
	
	};
	
function selc_fs(i,ref,id,feVen)
{	//alert('selc');
	hide_pop('#modal');
	$('#cod').prop('value',id);
	$('#feVen').prop('value',feVen);
	$('#Ref').prop('value',ref);

};
</script>

</body>
</html>