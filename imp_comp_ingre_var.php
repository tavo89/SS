<?php
require_once('Conexxx.php');
valida_session();

$num_comp=0;
$num_fac=0;
$tot_cuotas=0;
$tot_fac=0;
$saldo=0;
$nom_cli="";
$id_cli="";
$vendedor="";
$concepto="";

$fecha_comp="";
$valor_comp=0;

$pre="";

if(isset($_SESSION['num_comp_ingre']))$num_comp=$_SESSION['num_comp_ingre'];
if(isset($_SESSION['num_fac_ven']))$num_fac=$_SESSION['num_fac_ven'];


$titulo="Comprobante de Ingreso No. $num_comp";

	

$sql="SELECT *,DATE(fecha) as fe FROM comprobante_ingreso WHERE num_fac=$num_fac AND num_com=$num_comp AND cod_su=$codSuc";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
	{
	   	$valor_comp=$row['valor'];
		$fecha_comp=$row['fe'];
		$concepto=$row['concepto'];
		$pre=$row['pre'];
		
	}
	


?>

<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php require_once("IMP_HEADER.php"); ?>
<script language="javascript" type="text/javascript" src="JS/num_letras.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(
	function(){$('#val_letras').html('('+covertirNumLetras(""+<?php echo $valor_comp ?>+"")+')')}
);
</script>
<title><?php echo $titulo ?></title>
</head>

<body>
<table align="center" cellspacing="1" frame="box" class="imp_pos">
    <tr valign="top">
     <td height="80" colspan="5" align="center">
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
      <tr>
      <td width="150px"> <div align="center">
<!--<img src="Imagenes/LOGO.png" width="110px" height="100px" />-->
</div></td>
      <td align="left">


<?php echo $PUBLICIDAD2 ?>
<br />


 </td>
 <td>
 <p align="center"><b><span style=" font-size:18px">Comprobante de Ingreso</span></b><br />
  No.
 <span style="color:#F00; font-size:18px"><strong></strong>&nbsp;<?php echo $num_comp?><strong></strong></span>
 </p>
 <br />
 
 
 </td></tr>
 </table>
 
 </td>
    </tr>
    
   <tr valign="top">
   <td>
   
   <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px; font-size:14px;">
   <tr>
   <td>Por concepto de:</td><td ><b><i><?php echo "$concepto"?></i></b></td>
   </tr>
   </table>
   
   </td>
   </tr>
   
   <tr valign="top">
   <td height="100%">
   <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
   <tr>
   <td width="20%">Efectivo: </td><td>$<?php echo money($valor_comp)?></td><td id="val_letras"></td>
   </tr>
   <tr>
   <td width="20%">Fecha: </td><td><?php echo fecha($fecha_comp)?></td><td ></td>
   </tr>
   <tr>
   <td align="center" colspan="3"><br />___________________________<BR /><?php echo "$vendedor"?><br />Cajero(a)</td>
   </tr>
   </table>
   </td>
   </tr>
   
    </table>
    <table align="center">
    <tr>
    <td>
    <div id="imp" align="center" style="width:15.5cm;border-style: groove; border-color:#F00">
    
    <input type="button" value="VOLVER" onClick="javascript:location.assign('creditos.php?pag=<?php if(isset($_SESSION['pag']))echo $_SESSION['pag'] ?>');" />
    <input  type="button" value="IMPRIMIR" name="boton"  onClick="imprimir()"/>
    
    <input name="hid" type="hidden" value="<%=dim4%>" id="Nart" />
  </div>
  </td></tr></table>
 </body>
</html>