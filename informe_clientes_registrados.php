<?php
require_once("Conexxx.php");

$fechaI="";
$fechaF="";
if(isset($_SESSION['fechaI']))$fechaI=$_SESSION['fechaI'];
if(isset($_SESSION['fechaF']))$fechaF=$_SESSION['fechaF'];

$DCTO[]="";
$tipoD[]="";
$fabricante[]="";
$D="";
if(isset($_SESSION['dcto'])&&!empty($_SESSION['dcto'])){$dcto=$_SESSION['dcto'];$D="WHERE dcto>=$dcto AND dcto<($dcto+1)";}
$rs=$linkPDO->query("SELECT * FROM dcto_fab $D");
$i=0;
$id=0;

while($row=$rs->fetch())
{
	//$tecnico=ucwords(strtolower(htmlentities($row["mecanico"], ENT_QUOTES,"$CHAR_SET")));
	if($id!=$row['id_cli'])$i=0;
	$DCTO[$row['id_cli']]["$i"]=$row['dcto']*1;
	$tipoD[$row['id_cli']]["$i"]=$row['tipo_dcto'];
	$fabricante[$row['id_cli']]["$i"]=$row['fabricante'];
	$id=$row['id_cli'];
	$i++;
	
}
?>

<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>COMPROBANTE DE INFORME DIARIO DE VENTAS</title>
<link rel="stylesheet" href="css/jq.css" type="text/css" media="print, projection, screen" />
<link rel="stylesheet" href="css/themes/blue/style.css" type="text/css" media="print, projection, screen" />
<script language="javascript1.5" type="text/javascript" src="JS/jquery-1.11.1.min.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery.tablesorter.min.js"></script>
<script language="javascript1.5" type="text/javascript">
$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});

		//$("#TabClientes").tablesorter( {sortList: [[4,0]]} );
		$("#TabClientes").tablesorter();
	}
);
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</head>

<body style="font-size:12px">
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>
<td>
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top">
<p align="left" style="font-size:32px; font-weight:bold;">
<span align="center"><B>Base de Datos de Clientes</B></span>
</p>
</td>

</tr>
</table>
<span style="font-size:18px; font-weight:bold">Fecha: <?PHP echo $hoy ?></span>
<br>
<table align="center" width="100%">
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">
</table>
</td>
</tr>
</table>

<hr align="center" width="100%">
<table align="center" width="100%" cellpadding="2" cellspacing="1" style="font-size:11px" rules="rows" id="TabClientes" class="tablesorter">
<thead>
<tr style="font-size:12px;  font-weight:bold;">
<th>#</th>
<th width="300">Nombre</th>
<th width="150">C.C./NIT</th>
<th width="200" align="center">Direcci&oacute;n</th>
<th width="150">Tel&eacute;fono</th>
<th width="280">Fecha Registro</th>
</tr>
</thead>
<tbody>
<?php
$ii=0;
$sql="SELECT * FROM usuarios WHERE DATE(fecha_crea)>='$fechaI' AND DATE(fecha_crea)<='$fechaF' ORDER BY fecha_crea";
//if(!empty($D))$sql="SELECT *,(SELECT id_cli FROM dcto_fab $D)as D FROM usuarios WHERE id_usu=D";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{

	$nombre=$row['nombre'];
	$cc=$row['id_usu'];
	$dir=$row['dir'];
	$tel=$row['tel'];	
	$fechaCrea=$row['fecha_crea'];
	if(array_key_exists($cc,$DCTO)){
			$ii++;
	?>
    <tr>
    <td width="center"><?php echo $ii."" ?></td>
    <td><?php echo "$nombre" ?></td>
    <td><?php echo "$cc" ?></td>
    <td><?php echo "$dir" ?></td>
    <td><?php echo "$tel" ?></td>
    <td><?php echo "$fechaCrea" ?></td>
    </tr>
    
    <?php
	}// fin if exist key upper
}
?>
</tbody>
</table>
<hr align="center" width="100%" />
<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 
</div>
</body>
</html>