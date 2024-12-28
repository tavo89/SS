<?php
require_once('Conexxx.php');
valida_session();
$sql="";
$num_ref=0;
$num_fac="";
$nit_pro="";
$num_facH=0;
$nit_proH="";
$pag="";

if(isset($_SESSION['num_fac'])){$num_fac=$_SESSION['num_fac'];$num_facH=$_SESSION['num_fac'];}
if(isset($_SESSION['nit_pro'])){$nit_pro=$_SESSION['nit_pro'];$nit_proH=$_SESSION['nit_pro'];}
if(isset($_SESSION['serial_dev'])){$SERIAL_DEV=$_SESSION['serial_dev'];}
if(isset($_SESSION['pag'])){$pag=$_SESSION['pag'];}

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



$rsCount=$linkPDO->query("SELECT COUNT(*) AS nf FROM fac_dev WHERE num_fac_com='".$_SESSION['num_fac']."' AND cod_su=$codSuc AND nit_pro='".$_SESSION['nit_pro']."' AND serial_fac_dev='$SERIAL_DEV'" );

$rowCount=$rsCount->fetch();
$totArts=$rowCount["nf"];


if($Variable_size_imp_carta==1){$X_fac="11cm";}

if($totArts>=16)$X_fac="27.9cm";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<link href="JS/lightBox.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="JS/jquery-2.1.1.js"></script>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script language='javascript' src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script> 
<script language='javascript' src="JS/fac_com.js"></script> 
<script language="javascript" type="text/javascript" src="JS/num_letras.js"></script>
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};

</script>

<title>Fac. de Compra No. <?php echo $_SESSION['num_fac'] ?></title>
</head>

<body>
<form action="mod_fac_com.php" name="form_fac" method="get" id="form_fac">
<input type="text" style="visibility: hidden" name="boton" id="boton" value="" >
<?php

$sql="SELECT *,DATE(fecha) as fe, DATE(fecha_crea) as fe_crea  FROM fac_dev WHERE num_fac_com='".$_SESSION['num_fac']."' AND cod_su=$codSuc AND nit_pro='".$_SESSION['nit_pro']."' AND serial_fac_dev='$SERIAL_DEV'";
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
$num_fac_com=$row['serial_fac_dev'];
$NOTA= $row['nota_dev'];

	
}
?>
<table frame="box" align="center" cellspacing="0" cellpadding="1px" class=" round_table_white" width="900px" style="top:0px; width:21.5cm; height:<?php echo "$X_fac" ?>; font-family: Verdana, Geneva, sans-serif; background-color:#FFF;">
    <tr>
    
      <td height="80" colspan="8" align="center">
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
      <tr><td width="300px"> <div align="center">
<img src="<?php echo $url_LOGO_B ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>">
</div></td>
      <td align="left" colspan="5">
<?php echo $PUBLICIDAD ?>
 </td>
 <td align="center" style="font-size:26px; "><B>DEVOLUCION COMPRA</B><br><span style="color:#F00"># <?php echo $num_fac_com ?></span></td>

 </tr>
 </table>
 
 </td>
    </tr>

    <tr>
      <td colspan="5">
      <table frame="box" rules="rows" width="100%">
      <tr>
                      
<td  align="left" colspan="7">
            <table align="left" cellspacing="0"><tr>
            
            <td align="left" width="61" colspan="1"  >Fecha Factura:</td>
      <td width="144" colspan="2" ><?php echo fecha($fecha) ?></td>
      <td align="left" width="61" colspan="1"  >Fecha de Ingreso:</td>
      <td width="144" colspan="2" ><?php echo fecha($fechaIngreso) ?></td>
        <td colspan="3" align="right">
            <span style="color:#F00; font-size:18px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;FACTURA No.</strong></span></td>
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
      </td>
    </tr>
    <tr>
<td colspan="5" valign="top">
<table id="articulos" width="100%" cellspacing="0" cellpadding="0" frame="box" rules="cols" style="border-width:1px">
<tr>  
<td width="29"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Cant.</strong></div></td>
<td colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Referencia</strong></div></td>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Cod.</strong></div></td>
<td width="300" colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>&nbsp;Descripci&oacute;n&nbsp;</strong></div></td>

<?php if($usar_color==1){ ?>
<td>
<div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Color</strong></div>
</td>
<?php } ?>

<?php if($usar_talla==1){ ?>
<td>
<div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Talla</strong></div>
</td>
<?php } ?>


<?php
if($usar_fecha_vencimiento==1){
?>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Fecha Vencimiento</strong></div></td>

<?php
}
?>
<td width="96"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Costo</strong> sin IVA</div></td>
<td width="66" ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Dcto.(%)</strong></div></td>
<td width="65" ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>IVA (%)</strong></div></td>



<!--<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Utilidad</strong></div></td>-->
<td width="28"  colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong> Total</strong></div></td>
</tr>
<?php
$sql="SELECT * FROM art_fac_dev WHERE num_fac_com='$num_fac' AND cod_su=$codSuc AND nit_pro='$nit_pro' AND serial_dev='$SERIAL_DEV'";
$rs=$linkPDO->query($sql);
$i=0;
while($row=$rs->fetch())
{
        $cant=$row['cant']*1;
		$ref=$row['ref'];
		$des=$row['des'];
		$costo=money($row['costo']);
				//echo "<br><span style=\"color: #fff\"<b>$i costo SIN Dcto : $costo</b></span><br>";
		$dcto=$row['dcto'];
		$Iva=$row['iva'];
		$uti=$row['uti'];
		$pvp=money($row['pvp']);
		$s_tot=money($row['tot']);
		$fechaVen=$row['fecha_vencimiento'];
		$color=$row['color'];
		$talla=$row['talla'];
		$cod_bar=$row['cod_barras'];
?>
 <tr>     

<td class="art<?php echo $i ?>" align="center" valign="top" >
<?php echo $cant ?>
</td>
<td width="128" align="left" valign="top" class="art<?php echo $i ?>">
<?php echo $ref ?>
</td>
<td width="128" align="left"  class="art<?php echo $i ?>" style="font-size:10px;"><?php echo $cod_bar ?></td>
<td class="art<?php echo $i ?>" align="left" valign="top"  colspan="2" height="40px">
<?php echo $des ?>
</td>


<?php if($usar_color==1){ ?>
         <td class="art<?php echo $i ?>" align="center" ><?php echo $color ?></td>
         <?php } ?>
         <?php if($usar_talla==1){ ?>
         <td class="art<?php echo $i ?>" align="center" ><?php echo $talla ?></td>
         <?php } ?>


 <?php if($usar_fecha_vencimiento==1){ ?>
          <td class="art<?php echo $i ?>" align="center" ><?php echo $fechaVen ?></td>
          <?php }?>
<td class="art<?php echo $i ?>" align="right" valign="top">
<?php echo $costo ?>
</td>
<td class="art<?php echo $i ?>" align="center" valign="top">
<?php echo $dcto ?>
</td>
<td class="art<?php echo $i ?>" align="center" valign="top">
<?php echo $Iva ?>
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
   
<tr><td colspan="12" height="100%"></td></tr>
</table>

</td></tr>
    <?php


?>
<tr><td colspan="5" width="100%" height="100%"></td></tr>

 <tr id="desc" valign="bottom">
      <td colspan="3" rowspan="5" align="left" width="700px"  valign="bottom"> 
      NOTA:<BR>
            <textarea name="vlr_let2" id="vlr_let2" readonly="readonly" cols="60" rows="2" style="width:450px"><?php echo $NOTA ?></textarea>
            <BR>
      
      VALOR EN LETRAS<BR>
      <textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="60" rows="2" style="width:450px"><?php echo $val_letras ?></textarea>
     
      </td>
      <th width="100px" align="left">VALOR TOTAL:</th>
      <td align="right"><?php echo money($subtot) ?></td>
    </tr>
    <tr>
      <th  align="left"colspan="">DESCUENTOS: </th>
      <td align="right"><?php echo money($descuento) ?></td>
    </tr>
    <tr>
      <th  align="left" colspan="">VALOR FLETES(sin IVA): </th>
      <td align="right"><?php echo money($flete) ?></td>
    </tr>
    <tr>
      <th  align="left" colspan="">I.V.A TOTAL: </th>
      <td align="right"><?php echo money($iva) ?></td>
    </tr>
    <tr>
      <th  align="left" colspan="">VALOR A PAGAR:</th>
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
    <input type="button" value="Volver" onMouseDown="javascript:location.assign('devoluciones.php?pag=<?php echo $pag ?>');" class="addbtn" />
 
</div>
  
<div id="Qresp">

</div>
  <input type="hidden" name="num_ref" value="<?php echo $n_ref ?>" id="num_ref" />
    <input type="hidden" name="exi_ref" value="<?php echo $i ?>" id="exi_ref" />
  <input type="hidden" name="verify" id="verify" value="">
</form>
<div id="login-box" class="login-popup">
        <a href="#" class="close"><img src="Imagenes/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
          <form method="post" class="signin" action="#">
                <fieldset class="textbox">
            	<label class="username">
                <span>Usuario</span>
                <input id="username" name="username" value="" type="text" autocomplete="on" placeholder="Username">
                </label>
                
                <label class="password">
                <span>Contrase&ntilde;a</span>
                <input id="password" name="password" value="" type="password" placeholder="Password">
                </label>
                
                <button class="submit button" type="button" onClick="val_op($('#username').val(),$('#password').val(),'Administrador')">Aceptar</button>
                
                
                
                </fieldset>
          </form>
		</div>
</body>
</html>