<?php
require_once("Conexxx.php");
$boton="";
$num_fac="";
$fechaI="";
$fechaF="";
$cli="";
$pro="";
$dcto="";
if(isset($_REQUEST['fechaI']))$fechaI=$_REQUEST['fechaI'];
if(isset($_REQUEST['fechaF']))$fechaF=$_REQUEST['fechaF'];
if(isset($_REQUEST['cli']))$cli=$_REQUEST['cli'];
if(isset($_REQUEST['dcto']))$dcto=$_REQUEST['dcto'];
if(isset($_REQUEST['fab']))$pro=$_REQUEST['fab'];
if(isset($_REQUEST['boton'])){$boton=$_REQUEST['boton'];}

if($boton=='Generar Informe')
{

	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	
	$_SESSION['cli']=$cli;
	$_SESSION['dcto']=$dcto;
	$_SESSION['pro']=$pro;
	
	popup("informe_ventas_clientes.php","LISTADO DE CLIENTES","850px","500px");

};

if($boton=='Lista de Clientes')
{
	$_SESSION['dcto']=$dcto;
	popup("informe_clientes.php","LISTADO DE CLIENTES","850px","500px");
};

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>
</title>
<link rel="stylesheet" href="JS/fac_ven.css?<?php echo $LAST_VER;?>"/>
<link rel="stylesheet" href="JS/lightBox.css"/>
<link href="css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript1.5" src="JS/popcalendar.js"></script>
<script src="JS/jquery-2.1.1.js"></script>
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery.autocomplete.js"></script>
</head>
<body class="ui-content" >

<div data-role="page" data-theme="b" >

<div data-role="content">
<h1 style="color: #FFF">Informe Balance Ventas</h1>
<?php //echo "boton:".$boton." ; dep: ".$dep."<br>INSERT INTO departamento (departamento) VALUES ('$dep')"; ?>
<form  method="get" name="anular" id="form_fac">
<div id="query"></div>

<table  width="600px" align="center" style="color:#FFF">
<tr >
<td style="color:#FFF"><label></label></td>
</tr>

<tr>

<td width="400px">
<table id="fecha" align="center" border="0"  style="color:#FFF">
<tr>
<td>
Fecha:
</td>
<td style="color:#FFF" colspan="">Desde:
<input size="10" name="fechaI" value="<?php echo $fechaI ?>" type="text" id="f_ini" onClick="popUpCalendar(this, form_fac.f_ini, 'yyyy-mm-dd');" readonly placeholder="Fecha Inicial">
</td>

<td>
Hasta:
<input size="15" value="<?php echo $fechaF ?>" type="text" name="fechaF" id="f_fin" onClick="popUpCalendar(this, form_fac.f_fin, 'yyyy-mm-dd');" readonly placeholder="Fecha Final">
</td>
<td>

</td>
</tr>
<tr>
<td>Proveedor:</td><td>
<?php echo fab($conex,"","fab","fab","dcto");
?>

</td>
</tr>
<tr>
<td colspan="3" align="center"><input  type="submit" value="Generar Informe" name="boton" class="button" /></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td colspan="3" align="center"><input  type="submit" value="Lista de Clientes" name="boton" class="button" /></td>
</tr>
<!--
<tr>
<td><input  type="submit" value="Compras Almacen" name="boton" class="button"  /></td>

<td><input  type="submit" value="Totales ventas por vendedor" name="boton" class="button"  /></td>
<td><input  type="submit" value="Total Ventas" name="boton" class="button" /></td>
</tr>
-->
</table>

</td>
<td align="left" ><img id="loader" src="Imagenes/ajax-loader.gif" width="31" height="31"  style="position:relative; left:0px"/></td>
</tr>


</table>
</form>
<script type="text/javascript" language="javascript1.5" >
var busqueda='#cli';


  function findValue(li) {
  	if( li == null ) return alert("No match!");

  	// if coming from an AJAX call, let's use the CityId as the value
  	if( !!li.extra ) var sValue = li.extra[0];

  	// otherwise, let's just display the value in the text box
  	else var sValue = li.selectValue;

  	//alert("The value you selected was: " + sValue);
  };

  function selectItem(li) {
    	findValue(li);
  };

  function formatItem(row) {
    	return row[0] + " (id: " + row[1] + ")";
  };

  function lookupAjax(){
  	var oSuggest = $(busqueda)[0].autocompleter;
    oSuggest.findValue();
  	return false;
  };

  function lookupLocal(){
    	var oSuggest = $("#CityLocal")[0].autocompleter;

    	oSuggest.findValue();

    	return false;
  };

$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


	
	//$("#cli");
	$("#cli").autocomplete(
	"ajax/busq_cli.php",
      {
		  	
  			delay:10,
  			minChars:2,
  			matchSubset:1,
  			matchContains:1,
  			cacheLength:10,
  			onItemSelect:selectItem,
  			onFindValue:findValue,
  			formatItem:formatItem,
  			autoFill:true
  		}
    );
	
	});  
</script>
</div>
</div>
</body>
</html>