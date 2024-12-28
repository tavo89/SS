<?php
require_once('Conexxx.php');
$ID=r("ID");
$sql="SELECT * FROM orden_garantia WHERE id='$ID'";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();

$sql="SELECT * FROM usuarios WHERE id_usu='$row[id_cli]'";
$rs=$linkPDO->query($sql);
$row2=$rs->fetch();

$sql="SELECT * FROM vehiculo WHERE id_propietario='$row[id_cli]'";
$rs=$linkPDO->query($sql);
$row3=$rs->fetch();
?>
<!DOCTYPE html PUBLIC >
<html >
<head>
 
 
<?php require_once("HEADER_UK.php"); ?>


</head>

<body>
<table align="center" style="top:0px; width:27cm; height:<?php echo "13cm" ?>; font-family: Verdana, Geneva, sans-serif;"  border="0" frame="box"  cellspacing="10px" cellpadding="0" class="">
<thead>
<tr>
<td valign="top"><?php echo $row["fecha"]; ?></td><td></td><td colspan="2" class="uk-text-bold uk-text-danger uk-text-large" style="font-size:22px;">ORDEN DE GARANT&Iacute;A  #<?php echo $row["id"]; ?>
</td>
</tr>
<tr valign="top">
<td>Se&ntilde;or (a): </td><td><?php echo $row2["nombre"]; ?></td><td colspan="2">NIT/CC:  <?php echo $row2["id_usu"]; ?></td>
</tr>
<tr valign="top">
<td>Direcci&oacute;n: </td><td><?php echo $row2["dir"]; ?></td><td colspan="2">Tel.:  <?php echo $row2["tel"]; ?></td>
</tr>
<tr valign="top">
<td>Ciudad: </td><td><?php echo $row2["cuidad"]; ?></td><td colspan="2">Veh&iacute;culo:  <?php echo $row3["placa"]; ?></td>
</tr>
<tr valign="top">
<td>&nbsp;</td><td>&nbsp;</td><td colspan="2">&nbsp;  </td>
</tr>

<tr valign="top">
<td>Tarjeta de Garant&iacute;a </td><td><?php echo $row["cod_garantia"]; ?></td><td >Referencia:  <?php echo $row["id_pro"]; ?></td>
<td align="left" style=" ">Fecha Venta: <?php echo $row["fecha_venta"]; ?></td>
</tr>

<tr valign="top">
<td>Bater&iacute;a de Pr&eacute;stamo:</td><td><?php echo $row["id_pro_prestamo"]; ?></td><td >    </td>
<td align="left" style="font-size:12px;"> </td>
</tr>
</thead>
<tbody>

<tr valign="top">
<td colspan="4"><p align="justify"><br>La bater&iacute;a ser&aacute; enviada al laboratorio del distribuidor Mayorista (COEXITO) para el an&aacute;lisis y diagn&oacute;stico, siendo ellos quien otorguen o no la garant&iacute;a. Se le informar&aacute; el resultado en los diez d&iacute;as h&aacute;biles siguientes a la solicitud.<br><br>
Despu&eacute;s de 30 d&iacute;as no se responde por bater&iacute;as dejadas para garant&iacute;a.<br>
No. de gu&iacute;a <b><?php echo $row["num_gui"];?></b> Transportadora <b><?php echo $row["transportadora"];?></b> Fecha Env&iacute;o <b><?php echo $row["fecha_envio"];?></b>
.</p>
</td>
</tr>
<tr valign="top">
<td valign="top" colspan="4" align="center"> <input id="imp"  type="button" value="IMPRIMIR" name="boton"  onClick="imprimir()"/></td>
</tr>
</tbody>
</table>
<?php require_once("FOOTER_UK.php"); ?>
<script language="javascript" type="text/javascript">
var key;
$(document).keydown(function(e) { 
  c=e.keyCode;       
    if (c == 27) {
        window.close();
    }
	else if(c == 13)imprimir();
});
function imprimir(){
$('#imp').hide();
window.print();
$('#imp').show();
};
</script>
</body>
</html>