<?php
require_once('Conexxx.php');
valida_session();


$sql="";
$n_ref=0;
$num_ref=0;
$num_tras=$_SESSION['n_tras'];
$codSuc=$_SESSION['suc_orig'];
$fecha="";
$provedor="";
$nit_pro="";
$ciudad="";
$dir="";
$tel="";
$fax="";
$mail="";

$subtot=0;
$iva=0;
$tot=0;

$boton="";

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<script language="javascript" type="text/javascript" src="JS/jQuery1.8.2.min.js"></script>
<script language='javascript' src="JS/popcalendar.js"></script>
<script language='javascript' src="JS/utiles.js"></script> 
<script language='javascript' src="JS/fac_com.js"></script> 
<script language="javascript" type="text/javascript" src="JS/num_letras.js"></script>
<script language="javascript1.5" type="text/javascript">
var traslado=1;
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
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

	$('a.login-window').click(function() {
		
		// Getting the variable's value from a link 
		var loginBox = $(this).prop('href');

		//Fade in the Popup and add close button
		$(loginBox).fadeIn(300);
		
		//Set the center alignment padding + border
		var popMargTop = ($(loginBox).height() + 24) / 2; 
		var popMargLeft = ($(loginBox).width() + 24) / 2; 
		
		$(loginBox).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// Add the mask to body
		$('body').append('<div id="mask"></div>');
		$('#mask').fadeIn(300);
		
		return false;
	});
	
	// When clicking on the button close or the mask layer the popup closed
	$('a.close, #mask').on('click', function() { 
	  $('#mask , .login-popup').fadeOut(300 , function() {
		$('#mask').remove();  
	}); 
	return false;
	});
});

</script>

<title>Documento sin título</title>
</head>

<body>
<form action="mod_traslado.php" name="form_fac" method="post" id="form_fac">
<input type="text" style="visibility: hidden" name="boton" id="boton" value="" >
<?php

?>
<div class="loader">

<img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" />
</div>
  <table frame="box" align="center" cellspacing="0" style="background-color:#FFF" class="round_table_white">
    <tr>
    
      <td height="80" colspan="8" align="center">
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
      <tr><td width="450px"> <div align="center">
<img src="Imagenes/Honda3.jpg" width="100px" height="100px" />
</div></td>
      <td align="left" colspan="5">
<?php echo $PUBLICIDAD2 ?>

 </td>
 <td align="center" style="font-size:18px; "><B> TRASLADO</B>
 <p align="center">
 <span style="color:#F00; font-size:18px"><strong>No.</strong>&nbsp;&nbsp;&nbsp;&nbsp;
             <?php echo $num_tras ?>
             <input name="num_fac" type="hidden" id="num_fac" value="<?php echo $num_tras ?>" />
            <strong></strong></span>
            </p>
 </td>

 </tr>
 </table>
 
 </td>
    </tr>

    <tr>
      <td colspan="5">
      
      
 <?php
 $fecha="";
 $sede_ori="";
$subtot="";
$descuento="";
$flete="";
 $iva="";
 $tot="";
 $sql="SELECT * FROM traslados WHERE cod_tras=$num_tras AND COD_SU=$codSuc";
 
 $rs=$linkPDO->query($sql);
 if($row=$rs->fetch())
 {
	 $fecha=$row['fecha_envio'];
	 $sede_ori=$row['cod_su'];
	 $sede_dest=$row['cod_su_dest'];
	 $subtot=$row['subtot'];
	 $descuento=$row['descuento'];
	 $flete=$row['flete'];
	 $iva=$row['iva'];
	 $tot=$row['tot'];
 }
 
 ?>
      <table frame="box" rules="rows" width="100%">
      <tr>
                      
<td  align="left" colspan="2">
            <table align="left" cellspacing="0"><tr>
            
            <td align="right"  colspan="2"  >Fecha Envio:</td>
      <td width="144" colspan="2" ><?php echo $fecha ?></td>
            <td colspan="1">
            
            </td>
            <td>
         <div id="resp" style="visibility:hidden; color: #F00; width:180px;"><img alt="" src="Imagenes/delete.png" width="20" height="20" />Este n&uacute;mero ya existe</div>
            </td>
            <td>Sede Destino: </td><td>
           
          
            <?php
		    $rs=$linkPDO->query("SELECT * FROM sucursal WHERE cod_su!=$codSuc");
			while($row=$rs->fetch()){
				if($sede_dest==$row['cod_su']){
					
					?>
           <?php echo $row['nombre_su'] ?>
            <?php
				}
				else {
			?>
            
            <?php
			}
			
			}
			?>
          
            </td>
            </tr></table>
            </td>

            
            
            
           

          
        </table>
      </td>
    </tr>
    <tr>
<td colspan="5">
<table id="articulos" width="100%" cellspacing="0" cellpadding="0" frame="box" rules="cols" style="border-width:1px">
<tr>  
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Cant.</strong></div></td>
<td colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Referencia</strong></div></td>
<!--<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Cod.</strong></div></td>-->
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>&nbsp;Descripci&oacute;n&nbsp;</strong></div></td>

<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Costo</strong></div></td>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Dcto.</strong></div></td>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>IVA</strong></div></td>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Util.</strong></div></td>

<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>PVP</strong></div></td>
<!--<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Utilidad</strong></div></td>-->
<td width="90px"  colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong> Total</strong></div></td>
</tr>

<?php
$sql="SELECT * FROM art_tras WHERE cod_tras=$num_tras and cod_su=$codSuc";
//echo $sql;
$rs=$linkPDO->query($sql);
$i=0;
while($row=$rs->fetch())
{
        $cant=$row['cant'];
		$ref=$row['ref'];
		$des=$row['des'];
		$costo=money($row['costo']);
	
				//echo "<br><span style=\"color: #fff\"<b>$i costo SIN Dcto : $costo</b></span><br>";
		$dcto=$row['dcto'];
		$Iva=$row['iva'];
		$uti=$row['uti'];
		$pvp=money($row['pvp']);
		$s_tot=money($row['tot']);
?>
 <tr>     

<td class="art0" align="center" valign="top">
<?php echo $cant ?>
</td>
<td class="art<?php echo $i ?>" align="left" valign="top" colspan="2" style="font-size:12px;">
<?php echo $ref ?>
</td>
<td class="art<?php echo $i ?>" align="left" valign="top" style="font-size:12px;">
<?php echo $des ?>
</td>
<td class="art<?php echo $i ?>" align="right" valign="top">
<?php echo $costo ?>
</td>
<td class="art<?php echo $i ?>" align="center" valign="top">
<?php echo $dcto ?>
</td>
<td class="art<?php echo $i ?>" align="center" valign="top">
<?php echo $Iva ?>
</td>
<td class="art<?php echo $i ?>" align="center" valign="top">
<?php echo $uti ?>
</td>
<td class="art<?php echo $i ?>" align="right" valign="top">
<?php echo $pvp ?>
</td>
<td class="art<?php echo $i ?>" align="right" valign="top">
<?php echo $s_tot ?>
</td>
</tr>
<script language="javascript1.5" type="text/javascript">
cont++;
ref_exis++;
$('#num_ref').prop('value',cont);
$('#exi_ref').prop('value',ref_exis);
</script>


<?php
$i++;
}
$n_ref=$i;
?>
</table>

</td></tr>
    <?php


?>
<tr>
<td height="" colspan="6" align="center"></td>
</tr>


<tr>
<!--<td colspan="6" align="center"><a href="#login-box" class="login-window">Registrar Producto Nuevo</a></td>-->
</tr>

 <tr id="desc">
      <td colspan="3" rowspan="5" align="left" width="700px" ><br />
        <br />
        <br />
        <br />
        <br />
      <div align="left"></div></td>
      <th width="100px">SUB TOTAL:</th>
      <td align="right"><?php echo money($subtot) ?></td>
    </tr>
         <tr>
      <th  align="center" colspan="">I.V.A : </th>
      <td align="right"><?php echo money($iva) ?></td>
    </tr>
    <tr>
      <th  align="center" colspan="">TOTAL:</th>
      <td colspan="" align="right"><?php echo money($tot) ?></td>
    </tr>
  <tr>
<td colspan="12">
<table align="center">
<tr>
<td colspan="" align="center">
<input style="visibility:hidden" type="hidden" value="1" name="num_art" id="num_art">
</td><td></td>
</tr>
  <tr valign="middle">

    <td colspan="2">  <div id="imp"  align="center">
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" class="addbtn"/>
    </div>
    </td></tr>
  </table>

</td>
</tr>
<tr>
<td colspan="12" align="center"></td>
</tr>    


  </table>

<table class="round_table_white">
<tr>

</tr>
</table>
<table class="round_table_white" cellpadding="4" align="center">
<tr>
<td>Descarga/Entrega:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td>
Trasportador:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td >
Recibe/Carga:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
</tr>
</table>

<div id="Qresp">

</div>
  <input type="hidden" name="num_ref" value="<?php echo $n_ref ?>" id="num_ref" />
  <input type="hidden" name="verify" id="verify" value="">
</form>
</body>
</html>