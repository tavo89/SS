<?php
require_once('Conexxx.php');
valida_session();



$sql="";

$num_ref=0;
$num_fac="";
$nit_pro="";
$num_facH=0;
$nit_proH="";
if(isset($_SESSION['num_fac'])){$num_fac=$_SESSION['num_fac'];$num_facH=$_SESSION['num_fac'];}
if(isset($_SESSION['nit_pro'])){$nit_pro=$_SESSION['nit_pro'];$nit_proH=$_SESSION['nit_pro'];}

$fecha="";
$provedor="";
$ciudad="";
$dir="";
$tel="";
$fax="";
$mail="";

$subtot=0;
$iva=0;
$tot=0;

$boton="";
 
if(isset($_REQUEST['boton'])&&!empty($_REQUEST['boton']))$boton=$_REQUEST['boton'];

if($boton=="genera")
{}//fin 

//--------------------fin articulos----------------------			

	

	



?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script language="javascript" type="text/javascript" src="JS/jQuery1.8.2.min.js"></script>
<script language='javascript' src="JS/popcalendar.js"></script>
<script language='javascript' src="JS/utiles.js"></script> 
<script language='javascript' src="JS/fac_com.js"></script> 
<script language="javascript" type="text/javascript" src="JS/num_letras.js"></script>
<script language="javascript1.5" type="text/javascript">
$(document).keydown(function(e) { 
  c=e.keyCode;       
    if (c == 27) {
        window.close();
    }
	else if(c == 13)imprimir();
});

function imprimir(){
//$('#imp').css('visibility','hidden');
var $bt=$('.addbtn');
$('.addbtn').detach();
window.print();
//$('#imp').css('visibility','visible');
$bt.appendTo('#imp');
};
</script>

<title>Fac. de Compra No. <?php echo $_SESSION['num_fac'] ?></title>
</head>

<body>
<form action="mod_fac_com.php" name="form_fac" method="get" id="form_fac">
<input type="text" style="visibility: hidden" name="boton" id="boton" value="" >
<?php

$sql="SELECT *,DATE(fecha) as fe, DATE(fecha_crea) as fe_crea  FROM fac_com WHERE num_fac_com='".$_SESSION['num_fac']."' AND cod_su=$codSuc AND nit_pro='".$_SESSION['nit_pro']."'";
$rs=$linkPDO->query($sql);

if($row=$rs->fetch())
{
$fecha=$row['fe'];
$fechaIngreso=$row['fe_crea'];
$provedor=$row['nom_pro'];
$ciudad=$row['ciudad'];
$dir=$row['dir'];
$tel=$row['tel'];
$fax=$row['fax'];
$mail=$row['mail'];

$subtot=$row['subtot'];
$descuento=$row['descuento'];
$iva=$row['iva'];
$flete=$row['flete'];
$tot=$row['tot'];
$val_letras=$row['val_letras'];
$num_fac_com=$row['serial_fac_com'];
$estado=$row['estado'];

	
}
?>
<table frame="box" align="center" cellspacing="0" cellpadding="1px" class=" round_table_white"  style="width:21.5cm; height:27cm;">
    <tr>
    
      <td height="80" colspan="8" align="center" valign="top">
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
      <tr><td width="300px"> <div align="center"><img src="<?php echo $url_LOGO_B ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>"></div></td>
      <td align="left" colspan="5">

<?php
echo $PUBLICIDAD;
?>
 </td>
 <td align="center" style="font-size:18px; "><B>FACTURA DE COMPRA</B><br><span style="color:#F00"># <?php echo $num_fac_com ?></span>
 <br>
 <span><b><?php  echo $estado ?></b></span>
 </td>

 </tr>
 </table>
 
 </td>
    </tr>

    <tr>
      <td colspan="5" valign="top">
      <table frame="box" rules="rows" width="100%">
      <tr>
                      
<td  align="left" colspan="7">
            <table align="left" cellspacing="0"><tr>
            
            <td align="left" width="61" colspan="1"  >Fecha Factura:</td>
      <td width="144" colspan="2" ><?php echo fecha($fecha) ?></td>
      <td align="left" width="61" colspan="1"  >Fecha de Ingreso:</td>
      <td width="144" colspan="2" ><?php echo fecha($fechaIngreso) ?></td>
        <td colspan="3" align="right">
            <span style="color:#F00; font-size:18px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;No.</strong></span></td>
            <td><span style="color:#F00; font-size:18px"><strong>
              <?php echo $num_fac ?>
           </strong></span>
            </td>
            <td>
         <div id="resp" style="visibility:hidden; color: #F00; width:180px;"><img alt="" src="Imagenes/delete.png" width="20" height="20" />Este n&uacute;mero ya existe</div>
            </td>
            </tr></table>
            </td>

            
            
            
           
            
      
          </tr>
     
          
          <tr>
            <td width="">Proveedor:</td>
            <td >
            <table border="0">
            <tr>
            <td>
            <?php echo $provedor ?></td>
            
            </tr>
            </table>
            </td>
            <td>NIT:</td>
            <td><?php echo $nit_pro ?></td>
            <td align="">Ciudad:</td>
            <td colspan=""><?php echo $ciudad ?></td><td></td>
            
          </tr>
          <tr>
            <td >Direcci&oacute;n:</td>
            <td ><?php echo $dir ?></td>
            <td>Tel.:</td>
            <td colspan=""><?php echo $tel ?></td><td>Fax:</td>
            <td align=""><?php echo $fax ?></td>
            <td colspan="2">&nbsp;&nbsp;&nbsp;Mail:<?php echo $mail ?> </td>

           
          </tr>


   </table>
      <table width="100%" cellspacing="0" cellpadding="3" frame="box" rules="cols" style="border-width:1px">
        <tr>
          <td width="29"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>#</strong></div></td>
          <td width="29"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Cant.</strong></div></td>
          <td colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Referencia</strong></div></td>
         <td colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Cod. Bar.</strong></div></td>
          <td width="174"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>&nbsp;Descripci&oacute;n&nbsp;</strong></div></td>
         <td colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Color</strong></div></td>
        <td colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Talla</strong></div></td>
<td colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Fabricante</strong></div></td>
 <td colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Clase</strong></div></td>
          <td width="96"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Costo</strong></div></td>
          <td width="66" ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Dcto</strong></div></td>
          <td width="65" ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>IVA</strong></div></td>
          <td width="60" ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Util.</strong></div></td>
          <td width="161" ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>PVP</strong></div></td>
          <!--<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Utilidad</strong></div></td>-->
          <td width="28"  colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong> Total</strong></div></td>
        </tr>
        <?php
$sql="SELECT * FROM art_fac_com WHERE num_fac_com='$num_fac' AND cod_su=$codSuc AND nit_pro='$nit_pro' ORDER BY ref";
$rs=$linkPDO->query($sql);
$i=0;
$No=0;
$limH=0;
$once=0;
while($row=$rs->fetch())
{
	$No++;
	$limH++;
        $cant=$row['cant'];
		$ref=$row['ref'];
		$cod_bar=$row['cod_barras'];
		$des=$row['des'];
		$color=$row['color'];
		$talla=$row['talla'];
		$clase=$row['clase'];
		$costo=money($row['costo']);
				//echo "<br><span style=\"color: #fff\"<b>$i costo SIN Dcto : $costo</b></span><br>";
		$dcto=$row['dcto']*1;
		$Iva=$row['iva'];
		$fabricante=$row['fabricante'];
		$uti=$row['uti']*1;
		$pvp=money($row['pvp']);
		$s_tot=money($row['tot']);
?>
        <tr>
          <td class="art<?php echo $i ?>" align="center"  height="15px"><?php echo "$No" ?></td>
          <td class="art<?php echo $i ?>" align="center" ><?php echo $cant*1 ?></td>
          <td width="128" align="left"  class="art<?php echo $i ?>" style="font-size:10px;"><?php echo $ref ?></td>
          <td class="art<?php echo $i ?>" align="right" ><?php echo $cod_bar ?></td>
          <td class="art<?php echo $i ?>" align="left"  style="font-size:10px;"><?php echo $des ?></td>
          <td class="art<?php echo $i ?>" align="right" ><?php echo $color ?></td>
          <td class="art<?php echo $i ?>" align="center" ><?php echo $talla ?></td>
         <td class="art<?php echo $i ?>" align="center" ><?php echo $fabricante ?></td>
         <td class="art<?php echo $i ?>" align="center" ><?php echo $clase ?></td>
          <td class="art<?php echo $i ?>" align="right" ><?php echo $costo ?></td>
          <td class="art<?php echo $i ?>" align="right" ><?php echo $dcto ?></td>
          <td class="art<?php echo $i ?>" align="right" ><?php echo $Iva ?></td>
          <td class="art<?php echo $i ?>" align="right" ><?php echo $uti ?></td>
          <td class="art<?php echo $i ?>" align="right" ><?php echo $pvp ?></td>
          <td class="art<?php echo $i ?>" align="right" ><?php echo $s_tot ?></td>
        </tr>
        <?php

if(($i+1)==20&& $once==0)
{
$limH=0;
$once=1;
?>
        <!--<tr><td colspan="5" height="100%"></td></tr>-->
      </table>
      </td>
    </tr>
    <tr>
<td colspan="14" valign="top">&nbsp;</td></tr></table>
   <br>
<table frame="box" align="center" cellspacing="0" cellpadding="1px" class="round_table_white" width="900px" style="width:21.5cm; height:27.9cm;">
        <tr>
<td colspan="5" valign="top">
    <table frame="box" rules="cols" class="tabla_inv" align="center" cellpadding="0px" cellspacing="0" width="100%"  style="font-size:11px; border-width:1px">
    <?php
}

if($limH==50){
	$limH=0;
	
	?>
    <!--<tr><td colspan="5" height="100%"></td></tr>
      <tr><td colspan="7" height="50">&nbsp;</td></tr>
      
      -->
    </table></td></tr></table>
   <br>
    <table frame="box" align="center" cellspacing="0" cellpadding="1px" class=" round_table_white" width="900px" style="width:21.5cm; height:27.9cm;">
        <tr>
<td colspan="5" valign="top">

    
    <table frame="box" rules="cols" class="" align="center" cellpadding="3px" cellspacing="0" width="100%"  style="font-size:11px; border-width:1px">
    
  
    <?php
}


$i++;
}
$n_ref=$i;
?>
   
<tr>
<td colspan="14" height="100%"></td>
</tr>
</table>

</td></tr>
    <?php


?>
<tr><td colspan="5" width="100%" height="100%"></td></tr>

 <tr id="desc" valign="bottom">
      <td colspan="3" rowspan="5" align="left" width="700px"  valign="bottom"> 
      VALOR EN LETRAS<BR>
      <textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="60" rows="2" style="width:450px"><?php echo $val_letras ?></textarea>
     
      </td>
      <th width="100px" align="left">SUB TOTAL:</th>
      <td align="right"><?php echo money($subtot) ?></td>
    </tr>
    <tr>
      <th  align="left"colspan="">DESCUENTOS: </th>
      <td align="right"><?php echo money($descuento) ?></td>
    </tr>
    <tr>
      <th  align="left" colspan=""> FLETE: </th>
      <td align="right"><?php echo money($flete) ?></td>
    </tr>
    <tr>
      <th  align="left" colspan="">I.V.A : </th>
      <td align="right"><?php echo money($iva) ?></td>
    </tr>
    <tr>
      <th  align="left" colspan="">TOTAL:</th>
      <td colspan="" align="right"><?php echo money($tot) ?></td>
    </tr>
  <tr>
<td colspan="12">
<table align="center">
<tr>
<td colspan="" align="center"><input style="visibility:hidden" type="hidden" value="1" name="num_art" id="num_art"></td><td></td>
</tr>
  <tr valign="middle">

    <td> </td>
    </tr>
  </table>

</td>
</tr>
<tr>
<td colspan="12" align="center"></td>
</tr>    


  </table>
  <div id="imp"  align="center">
  
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" class="addbtn"/>
    <input type="button" value="Volver" onMouseDown="javascript:location.assign('compras.php');" class="addbtn" />
 
</div>
  
  <input type="hidden" name="num_ref" value="<?php echo $n_ref ?>" id="num_ref" />
    <input type="hidden" name="exi_ref" value="<?php echo $i ?>" id="exi_ref" />
  <input type="hidden" name="verify" id="verify" value="">
</form>
</body>
</html>