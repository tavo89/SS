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
//$pre=$_SESSION['pre'];
$titulo="Comprobante de Ingreso No. $num_comp";

$sql="SELECT *,DATE(fecha) as fe,(select CONCAT(nombre,' ',snombr,' ',apelli) from usuarios WHERE usuarios.id_usu=comprobante_ingreso.id_cli LIMIT 1) as nom_cli FROM comprobante_ingreso WHERE num_com=$num_comp AND cod_su=$codSuc";
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
	{
	   	$valor_comp=$row['valor'];
		$fecha_comp=$row['fe'];
		$concepto=$row['concepto'];
		$pre=$row['pre'];
		$nom_cli=$row['nom_cli'];
		$id_cli=$row['id_cli'];
		$FechaC=$row["fecha"];
		$tipo_op=$row["tipo_operacion"];
		$feI=$row["fe_ini"];
		$feF=$row["fe_fin"];
		$CAJERO=$row["cajero"];
		$r_fte=$row["r_fte"];
		$r_ica=$row["r_ica"];
		
		
	}

if($MODULES["CARTERA_MASS"]==1){$STATS_PAGOS=tot_saldo_cre_rango($FechaC,$feI,$feF,$num_comp);}
else {$STATS_PAGOS=tot_saldo($id_cli,$FechaC,$num_comp);}
$tot_fac=$STATS_PAGOS["tot"];
$tot_cuotas=$STATS_PAGOS["abono"];

 
$saldo=$STATS_PAGOS["saldo"];


$PUBLICIDAD2="
<p align=\"left\" style=\"font-size:12px\" class=\"imp_pos\">
<span style=\"  \"><B>$NOM_NEGOCIO</B></span>
<BR />
$showNIT
 
</p>
";
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
<style type="text/css">
body{ font-size:11px;	
}
table{ font-size:13px;}
</style>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 4mm;  /* this affects the margin in the printer settings */
    }

    body 
    {
        background-color:#FFFFFF; 
       /** border: solid 1px black ;**/
        margin: 4px;  /* this affects the margin on the content before sending to printer */
   }
   

</style>
<style type="text/css">
.imp_pos{
	
	top:0px;
	width:100%; 
	position: relative; 
	 
	 font-size:<?php echo $fac_ven_font_size; ?>px;
	/*
	
	font-family:  "Bell Gothic";
	font-family: "Bell MT";
	text-transform:lowercase;
	*/
	font-family: "Arial";
	font-weight:bold;
	
	margin:0px;
}
</style>
<title><?php echo $titulo ?></title>
</head>

<body>
<table align="center" cellspacing="1" frame="box" class="imp_pos" style="">
<tr valign="top">
<td height="" colspan="5" align="">
<table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
<tr>
<td colspan="2"><img src="<?php echo $url_LOGO_A ?>" width="<?php echo $X ?>" height="<?php echo "80px" ?>"></td>
</tr>
<tr>
<td align="left">


<?php echo $PUBLICIDAD2 ?>


 </td>
 <td><p align="center"><b><span style=" font-size:12px">Comprobante de Ingreso</span></b><br />
  No.
 <span style="color:#F00; font-size:12px"><strong></strong>&nbsp;<?php echo $num_comp?><strong></strong></span>
 </p>
 <br />
 
 
 </td></tr>
 </table>
 
 </td>
    </tr>
    
   <tr valign="top">
   <td>
   
   <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
   <tr>
   <td width="20%">Cliente:</td><td><?php echo "$nom_cli"?></td>
   </tr>
   <tr>
   <td>NIT/C.C:</td><td><?php echo "$id_cli"?></td>
   </tr>
   <tr>
   <td>Concepto:</td><td ><b><i><?php echo "$concepto"?></i></b></td>
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
   <td width="20%">Fecha: </td><td colspan="2"><?php echo fecha($fecha_comp)?></td> 
   </tr>
   <tr>
   <td width="20%">Saldo: </td><td colspan="2">$<?php echo money($saldo)?></td>
   </tr>
   <tr>
   <td align="center" colspan="3"><br /><br /><br />___________________________<BR /><?php echo "$vendedor"?><br />Cajero(a)</td>
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