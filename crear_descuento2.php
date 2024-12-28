<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"clientes_mod")){header("location: centro.php");}
$id_cli="";
$nombre_cliente="";
if(isset($_SESSION['id_cli']))$id_cli=$_SESSION['id_cli'];
if(isset($_SESSION['nombre_cli']))$nombre_cliente=$_SESSION['nombre_cli'];

$ThisURL="crear_descuento2.php";
$boton="";
if(isset($_REQUEST['boton']))$boton=$_REQUEST['boton'];

if($boton=="Guardar")
{
$fecha=$hoy;
$nom=limpiarcampo($nomUsu);
$cc=limpiarcampo($id_Usu);
$fab=limpiarcampo($_REQUEST['fabricante']);
$dcto=limpiarcampo($_REQUEST['dcto']);

$rs=$linkPDO->query("SELECT * FROM dcto_fab WHERE id_cli='$id_cli' AND fabricante='$fab'");
if($row=$rs->fetch())
{
	$sql="UPDATE dcto_fab SET dcto=$dcto WHERE id_cli='$id_cli' AND fabricante='$fab'";
	$linkPDO->exec($sql);
}
else{
$sql="INSERT INTO dcto_fab(id_cli,fabricante,dcto,usu,id_usu,cod_su) VALUES('$id_cli','$fab',$dcto,'$nomUsu','$id_Usu',$codSuc)";
$linkPDO->exec($sql);
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

function d(cant)
{
   var c=100+cant*1;
   var entre=100000;
   var vOri=1000;
   var d=(1-(entre/(c*vOri)) )*100;
   //alert(c);
   $dcto=$('#dcto');
   $dcto.prop('value',d);
};
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
<h1 align="center">Crear Descuento por Operador (<?php echo "$nombre_cliente" ?>)</h1>
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
<tr style="font-size:24px">
<td colspan="5">Descuento %
<input type="text" name="dcto" id="dcto"   style="width:200px"/>
</td>
<td >
Operador:
<?php
	echo fab($conex);
?>
</td>
</tr>
<tr>
<td colspan="3">Calcular Dcto por Producto:<input type="text" name="per" id="per" value="" placeholder="Porcentaje deseado %" onKeyUp="d(this.value);" /></td>
</tr>
<tr>
<td colspan="7" align="center">
<input type="button" value="Guardar" id="btn" name="boton" onClick="save_dcto2(this,'genera',document.forms['frm'])" class="addbtn" />
<input type="button" value="Volver" onClick="location.assign('dcto_cli2.php')"  class="addbtn" />
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