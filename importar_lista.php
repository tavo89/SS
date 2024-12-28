<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl ){header("location: centro.php");}
$boton=r("boton");
//$boton=$_REQUEST["boton"];
//echo "boton: $boton";
//$boton="Guardar";
$OPT_UPLOAD=r("opt_cols");
if($boton=="Guardar" ){

	//echo "GUARDAR<BR>";
	if(isset($_FILES["archivo"]))$csvFile=subir_csv("archivo", "Import/csvfile");
	else $csvFile="";
	
	/*
	 0         1        2       3       4      5       6        7  		   8          9    		10    	 	11			12			     13
    ref	Descripcion	Clase	Talla   Color   costo	pvp	     fracc		fab/marca	 CodBar    CodColor	   Vigencia	   grupoDest	 cantidades
	import_csv($csvFile,"A");
	
	
	
	import_csv($csvFile,"B"); //ROPA
	import_csv($csvFile,$OPT_UPLOAD,$codSuc);
	*/
	import_csv($csvFile,$OPT_UPLOAD);
	$_SESSION['num_fac']="1122334455-IMP";
	$_SESSION['nit_pro']="R-66Y";

	header("location: mod_fac_com.php");
	//echo "FILE: $csvFile";
}

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
<h1 align="center">IMPORTAR LISTADO PRODUCTOS  </h1>


<form action="importar_lista.php" id="frm" name="frm" class="uk-form" enctype="multipart/form-data" method="post">
<table align="center" class="uk-contrast dark_bg uk-text-large">
<thead>
<tr>
<th colspan="4" style="font-size:24px">FORMATO FILAS</th>
</tr>
<tr style="background-color:#FFF; color:#000; font-weight:bold;">

<td colspan="5">Referencia	| Descripcion | Clase |	Talla | Color | Costo |	PVP	| Fracci&oacute;n | Fab/Marca | Cod. barras | cod. COLOR | Vigencia Inicial | Grupo Destino</td>
</tr>

</thead>
<tbody>
<tr>
<td colspan="2"><label>Archivo CSV</label></td>
 
<td colspan="2">
<input type="file" name="archivo" id="archivo" size="40" value="">

</td>
<td>
<select name="opt_cols">
<option value="A" >A: Cant, REF, Cod. Barras</option>
<option value="B" selected>B: Cod. Barras,REF, Sin cantidades</option>
<option value="C" >C: Cod. Barras,REF, Sin cantidades, IVA</option>

</select>
</td>
</tr>
 
</tr>
<tr>
<td colspan="4" align="center">
<span  onClick="import_csv(document.forms['frm']);" class="uk-button uk-button-large"><i class=" uk-icon-floppy-o"></i>Guardar</span>

<span class="uk-button uk-button-large" onClick="Goback();"><i class=" uk-icon-history"></i>Volver</span>
</tr>
</tbody>
</table>

 
 
  <input type="hidden" name="boton" value="" id="boton" />
 
</form>
</div>
<?php require_once("FOOTER.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >
 function import_csv(form)
{
	$('#boton').prop("value","Guardar");
	if(esVacio(form.archivo.value)){alert('Seleccione un archivo CSV primero!');}
	else{
					form.submit();
						}
};


</script>

</body>
</html>