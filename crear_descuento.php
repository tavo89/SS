<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"clientes_mod")){header("location: centro.php");}
$id_cli="";
$nombre_cliente="";
if(isset($_SESSION['id_cli']))$id_cli=$_SESSION['id_cli'];
if(isset($_SESSION['nombre_cli']))$nombre_cliente=$_SESSION['nombre_cli'];

$ThisURL="crear_descuento.php";
$boton="";
if(isset($_REQUEST['boton']))$boton=$_REQUEST['boton'];

if($boton=="Guardar")
{
$fecha=$hoy;
$nom=limpiarcampo($nomUsu);
$cc=limpiarcampo($id_Usu);

$num_ajus=serial_ajustes($conex);

$i=0;
$num_art=$_REQUEST['num_ref'];

	for($i=0;$i<$num_art;$i++)
	{
		if(isset($_REQUEST['ref_'.$i]))
		{
		$ref=$_REQUEST['ref_'.$i];
		$det=$_REQUEST['det_'.$i];
		$dcto=$_REQUEST['cant_'.$i];
		
		$sql="select * from descuentos WHERE id_cli='$id_cli' AND id_pro='$ref'";
		$rs=$linkPDO->exec($sql);
		if($row=$rs->fetch())
		{
			$update="UPDATE descuentos SET dcto=$dcto WHERE id_cli='$id_cli' AND id_pro='$ref'";
			$linkPDO->exec($update);
		}
		else{
		$Insert1="INSERT INTO descuentos(id_cli,id_pro,usu,id_usu,dcto,cod_su) VALUES('$id_cli','$ref','$nomUsu','$id_Usu','$dcto','$codSuc')";
		$linkPDO->exec($Insert1);
		}

		
		}
	}

eco_alert("Operacion Exitosa");
}

?>
<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
<link href="JS/lightBox.css" rel="stylesheet" type="text/css" />
<script src="JS/jquery-2.1.1.js"></script>
<script language='javascript' src="JS/popcalendar.js"></script> 
<script language='javascript' src="JS/fac_ven.js"></script> 
<script language='javascript' >
var dcto_remi=0;
var HH=12000;
var iva_serv=0.16;
$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


	$('#loader').hide();
	$('#loader').ajaxStart(function(){$(this).show();})
	.ajaxStop(function(){$(this).hide();});
	
	$(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});

</script> 

<title>Descuentos</title>
</head>

<body>
<h1 align="center">Descuento por Cliente (<?php echo "$nombre_cliente" ?>)</h1>
<form name="frm" action="<?php echo $ThisURL ?>" method="post">
<div class="loader">

<img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" />
</div>
<table align="center" cellpadding="5" cellspacing="0" class="round_table_white">
<tr>
<thead style="font-size:24px;">
<td colspan="3"></td>
</tr>
</thead>
<tbody>
<tr>
<td colspan="3">Fecha: <?php echo $hoy ?></td>
</tr>
<tr>
<td colspan="5">

<table id="articulos" cellpadding="0" cellspacing="0" width="100%">
<tr style="background-color: #000; color:#FFF">
      
      <td><div align="center"><strong>Referencia</strong></div></td>
      <td><div align="center"><strong>Producto</strong></div></td>
      <td><div align="center"><strong>I.V.A</strong></div></td>
      <td height="28"><div align="center"><strong>Dcto (%).</strong></div></td>
      <td><div align="center"><strong>Util.</strong></div></td>
      <td><div align="center"><strong>Costo</strong></div></td>
      <td><div align="center"><strong>PvP</strong></div></td>
    </tr>

</table>
</td>
</tr>
<tr valign="middle">
    <th colspan="7" align="center">Cod. Art&iacute;culo:<input type="text" name="cod" value="" id="cod" onKeyPress="cod_ajus(this,'add');" />
<div id="Qresp"></div>
</th> 
</tr>

<tr>
<td colspan="7" align="center">
<input type="button" value="Guardar" id="btn" name="boton" onClick="save_dcto(this,'genera',document.forms['frm'])" class="addbtn" />
<input type="button" value="Volver" onClick="location.assign('dcto_cli.php')"  class="addbtn" />
</td>
</tr>
</tbody>
</table>
  <input type="hidden" name="boton" value="genera" id="genera" />
  <input type="hidden" name="num_ref" value="0" id="num_ref" />
  <input type="hidden" name="exi_ref" value="0" id="exi_ref" />

</form>
</body>
</html>