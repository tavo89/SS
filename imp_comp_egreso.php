<?php
include('Conexxx.php');

$num_comp=0;
$vendedor="";
$concepto="";
$fecha_comp="";
$valor_comp=0;

$titulo="COMPROBANTE DE EGRESO";
$ID=s("id_comp_egreso");

$sql="SELECT *,DATE(fecha) as fe FROM comp_egreso WHERE id='$ID'  AND cod_su=$codSuc";
$rs=$linkPDO->query($sql);
$vendedor="";
$TIPO="";
if($row=$rs->fetch())
	{
		
		$num_comp=$row["num_com"];
	   	$valor_comp=$row['valor'];
		
		$fecha_comp=$row['fe'];
		$concepto=$row['concepto'];
		$vendedor=$row['cajero'];
		$beneficiario=$row['beneficiario'];
		$id_bene=$row['id_beneficiario'];
		$banco=$row['banco'];
		$num_cheque=$row['num_cheque'];
		$idCuenta=$row['id_cuenta'];
		
		$R_FTE=$row['r_fte']*1;
		$R_ICA=$row['r_ica']*1;
		
		$PAGA=$valor_comp-($R_FTE+$R_ICA);
		
		$CUENTA=get_cuenta($idCuenta);
		
	}
	

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<?php include_once("IMP_HEADER.php"); ?>
<link href="JS/fac_ven.css?<?php echo $LAST_VER ?>" rel="stylesheet" type="text/css" />
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 3mm;  /* this affects the margin in the printer settings */
    }

    body 
    {
        background-color:#FFFFFF; 
       /** border: solid 1px black ;**/
        margin: 3px;  /* this affects the margin on the content before sending to printer */
   }
</style>
<script language="javascript" type="text/javascript">
$(document).ready(
	function(){$('#val_letras').html('('+covertirNumLetras(""+<?php echo $PAGA ?>+"")+')')}
);
/*
var key;
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
*/
</script>

</head>

<body>
<table class="imp_pos" align="center" cellspacing="1" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;top:0px; width:100%; height:6cm;">
    <tr valign="top">
     <td height="80" colspan="5" align="lfet">
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;" class="imp_pos">
      <tr>
      <td colspan="3" align="center"> 
     
<img src="<?php echo $url_LOGO_B;  ?>" class="logo_comps"  />

</td>
</tr>
<tr>
<td align="center" colspan="3" >
<?php echo $PUBLICIDAD2 ?>
</td>
</tr>
<tr>
<td  colspan="3"  align="center">
<b><span style=" font-size:14px"><?php echo $titulo ?></span></b>
<br />
No.<span style="color:#F00; font-size:14px">&nbsp;<?php echo $num_comp?><strong></strong></span>
 
 </td></tr>
 </table>
 
 </td>
    </tr>

 
   <tr valign="top">
   <td>
   
   <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;" class="imp_pos">
   <tr>
   <td width="20%">Cheque No.:</td><td style="color:red"><?php echo "$num_cheque"?></td>
   </tr>
   <tr>
   <td width="20%">Banco:</td><td ><?php echo "$banco"?></td>
   </tr>
   <!--
   <tr>
   <td>CUENTA: <?php echo "$CUENTA[0] - $CUENTA[1]"?> </td>
   </tr>
   -->
   <tr>
   <td width="20%">Beneficiario:</td><td><?php echo "$beneficiario"?></td>
   </tr>
   <tr>
   <td>NIT/C.C:</td><td><?php echo "$id_bene"?></td>
   </tr>
   <tr>
   <td>Por concepto de:</td><td ><b><i><?php echo "$concepto"?></i></b></td>
   </tr>
   </table>
   
   </td>
   </tr>
   
   <tr valign="top">
   <td height="100%">
   <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;" class="imp_pos">
   <tr>
   <td width="20%">DEBE: </td><td>$<?php echo money($valor_comp)?></td><td ></td>
   </tr>
   <tr><td width="20%">R. FTE: </td><td>$<?php echo money($R_FTE)?></td><td ></td></tr>

<tr><td width="20%">R. ICA: </td><td>$<?php echo money($R_ICA)?></td><td ></td></tr>

<tr>
   <td width="20%">PAGA: </td><td>$<?php echo money($PAGA)?></td>
   </tr>
   <tr>
      <td id="val_letras" colspan="3"></td>
   </tr>
   <tr>
   <td width="20%">Fecha: </td><td ><?php echo fecha($fecha_comp)?></td><td ></td>
   </tr>
   <tr>
   <td align="left" colspan="2"><br />___________________________<BR /><?php echo "$vendedor"?><br />Cajero(a)</td>
   <td align="left" colspan="2"><br />___________________________<BR /><?php echo "$beneficiario"?><br />Beneficiario</td>
   </tr>
   </table>
   </td>
   </tr>
   
    </table>
    <table align="center" class="imp_pos">
    <tr>
    <td>
    <div id="imp" align="center" style="width:100%;border-style: groove; border-color:#F00">
    
    <input type="button" value="Usar Formato Carta" onClick="location.assign('imp_comp_egreso_com.php');" />
    <input  type="button" value="Imprimir" name="boton"  onClick="imprimir();"/>
    
    <input name="hid" type="hidden" value="<%=dim4%>" id="Nart" />
  </div>
  </td></tr></table>
</body>
</html>