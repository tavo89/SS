<?php
require_once('Conexxx.php');
valida_session();


$sql="";
$n_ref=0;
$num_ref=0;
if(isset($_REQUEST['nt'])){
$num_tras=$_REQUEST['nt'];
$cod_su_tras=$_REQUEST['cod_su_tras'];
}
else header("recibir_traslados.php");
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


 
if(isset($_REQUEST['boton'])&&!empty($_REQUEST['boton']))$boton=$_REQUEST['boton'];

if($boton=="genera")
{
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;
 

$sql_promCosto="";

$sql="";

$num_ref=$_REQUEST['num_ref'];

$fecha=r('fecha');
$sede_dest=r('sede_dest');

$subtot=quitacom($_REQUEST['SUBTOT']);
$descuento=quitacom($_REQUEST['DESCUENTO']);
$flete=quitacom($_REQUEST['FLETE']);
$iva=quitacom($_REQUEST['IVA']);
$tot=quitacom($_REQUEST['TOTAL']);

$sql="SELECT * FROM traslados WHERE cod_tras=$num_tras AND estado='SIN CONFIRMAR' AND cod_su_dest=$codSuc and cod_su=$cod_su_tras FOR UPDATE";

$rs=$linkPDO->query($sql);
//echo "<span style=\"color: #fff\"<b>n_ref:$num_ref|SQL[$sql]</b></span>";
if($row=$rs->fetch()){
$i=0;
for($i=0;$i<$num_ref;$i++)
{
	if(isset($_REQUEST['ref'.$i])&&!empty($_REQUEST['ref'.$i]) )
	{
		$cant=r('cant'.$i);
		$ref=r('ref'.$i);
		$des=r('des'.$i);
		$costo=quitacom($_REQUEST['costo'.$i]);
				//echo "<br><span style=\"color: #fff\"<b>$i costo SIN Dcto : $costo</b></span><br>";
		$dcto=r('dcto'.$i);
		$iva=r('iva'.$i);
		$uti=r('uti'.$i);
		$pvp=quitacom($_REQUEST['pvp'.$i]);
		$s_tot=quitacom($_REQUEST['v_tot'.$i]);
		if(empty($dcto)){$dcto=0;}
		if(empty($iva)){$iva=0;}
		$costo=$costo - $costo*($dcto/100);

$sql="SELECT * FROM inv_inter WHERE id_pro='$ref' AND nit_scs=$codSuc FOR UPDATE";
$sqli="";
$rs=$linkPDO->query($sql);
//echo "<span style=\"color: #fff\"<b>n_ref:$num_ref|SQL[$sql]</b></span>";
if($row=$rs->fetch()){

}
else 
{
	
$sql="INSERT INTO inv_inter(id_pro,id_inter,max,min,fraccion,costo,precio_v,gana,iva,nit_scs) 
SELECT id_pro,id_inter,max,min,fraccion,costo,precio_v,gana,iva,(select cod_su from sucursal where cod_su=$codSuc) as cu FROM inv_inter WHERE nit_scs=1 AND id_pro='$ref'  ";
$linkPDO->exec($sql);



}
	

				$sql_promCosto=prom_cost_sede($costo,$uti,$iva,$cant,$ref,$conex,$codSuc);
				$linkPDO->exec($sql_promCosto);

 
	}
	
}
$linkPDO->exec("SAVEPOINT A");
$A="UPDATE `inv_inter` i INNER JOIN (select at.cod_su,sum(cant) cant,ref,cod_su_dest,estado from art_tras at inner join (select * from traslados where cod_tras=$num_tras) t ON at.cod_tras=t.cod_tras WHERE t.cod_su=at.cod_su AND t.cod_su=$cod_su_tras AND t.cod_su_dest=$codSuc AND t.estado='CONFIRMADO' group by at.ref) a ON a.ref=i.id_pro SET i.exist=(i.exist+a.cant) where i.nit_scs=a.cod_su_dest and i.nit_scs=$codSuc";


$B="UPDATE traslados SET estado='CONFIRMADO',fecha_recibo='$hoy' WHERE cod_tras=$num_tras and cod_su=$cod_su_tras";
$linkPDO->exec($B);

$linkPDO->exec($A);


$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;
if($all_query_ok){
eco_alert("Traslado Exitoso!"); 	
}
else{eco_alert("ERROR...Verifique los campos ingresados e intente nuevamente"); }



}
else {eco_alert("Este Traslado ya estaba Confirmado, no se realizaron cambios...");}



}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}


}// FIN GENERA


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<link href="JS/lightBox.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<script src="JS/jquery-2.1.1.js"></script>
<script language='javascript' src="JS/popcalendar.js"></script>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script>
<script language='javascript' src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script> 
<script language='javascript' src="JS/fac_com.js?<?php echo "$LAST_VER" ?>"></script> 
<script language="javascript" type="text/javascript" src="JS/num_letras.js"></script>
<script language="javascript1.5" type="text/javascript">
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
<form action="confirm_traslado.php" name="form_fac" method="post" id="form_fac">
<input type="text" style="visibility: hidden" name="boton" id="boton" value="" >
<input type="hidden" value="<?php echo $num_tras ?>" name="nt" id="nt">
<input type="hidden" value="<?php echo $cod_su_tras ?>" name="cod_su_tras" id="nt">
<?php

?>
<div class="loader">

<img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" />
</div>
  <table frame="box" align="center" cellspacing="0" style="background-color:#FFF">
    <tr>
    
      <td height="80" colspan="8" align="center">
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
      <tr><td width="450px"> <div align="center">
<img src="Imagenes/Honda3.jpg" width="100px" height="100px" />
</div></td>
      <td align="left" colspan="5">
<?php echo $PUBLICIDAD2 ?>
 </td>
 <td align="center" style="font-size:18px; "><B>RECIBIR TRASLADO</B></td>

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
 $sql="SELECT * FROM traslados WHERE cod_tras=$num_tras and cod_su=$cod_su_tras";
 //echo $sql;
 
 $rs=$linkPDO->query($sql);
 if($row=$rs->fetch())
 {
	 $fecha=$row['fecha_envio'];
	 $sede_ori=$row['cod_su'];
	 $subtot=$row['subtot'];
	 $descuento=$row['descuento'];
	 $flete=$row['flete'];
	 $iva=$row['iva'];
	 $tot=$row['tot'];
	 $estado=$row['estado'];
 }
 
 ?>
      <table frame="box" rules="rows" width="100%">
      <tr>
                      
<td  align="left" colspan="2">
            <table align="left" cellspacing="0"><tr>
            
            <td align="right" width="61" colspan="2"  >Fecha Envio:</td>
      <td width="144" colspan="2" ><input readonly="" id="fecha2" type="text" value="<?php echo $fecha ?>" name="fecha" onClick="//popUpCalendar(this,form_fac.fecha2,'yyyy-mm-dd')" /></td>
            <td colspan="1">
            <span style="color:#F00; font-size:18px"><strong>No.</strong>&nbsp;&nbsp;&nbsp;&nbsp;
             <?php echo $num_tras ?>
            <strong></strong></span>
            </td>
            <td>
         <div id="resp" style="visibility:hidden; color: #F00; width:180px;"><img alt="" src="Imagenes/delete.png" width="20" height="20" />Este n&uacute;mero ya existe</div>
            </td>
            <td>Sede Origen</td><td>
            <select name="sede_dest" id="sede_dest" onChange="">
           
            <?php
		    $rs=$linkPDO->query("SELECT * FROM sucursal WHERE cod_su=$sede_ori");
			while($row=$rs->fetch()){
				
			?>
            <option value="<?php echo $row['cod_su'] ?>" selected><?php echo $row['nombre_su'] ?></option>
            <?php
			}
			?>
            </select>
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

<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Costo</strong> sin IVA</div></td>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Dcto.(%)</strong></div></td>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>IVA (%)</strong></div></td>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Util.(%)</strong></div></td>

<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Precio de Venta</strong></div></td>
<!--<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Utilidad</strong></div></td>-->
<td width="90px"  colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong> Total</strong></div></td>
</tr>

<?php
$sql="SELECT * FROM art_tras WHERE cod_tras=$num_tras and cod_su=$cod_su_tras";
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
<input style="width:30px" class="art<?php echo $i ?>" name="cant<?php echo $i ?>" id="cant<?php echo $i ?>" value="<?php echo $cant ?>" onkeyup="tot();" type="text" readonly>
</td>
<td class="art<?php echo $i ?>" align="center" valign="top">
<input onkeyup="cod(this,'add',0,0)" class="art<?php echo $i ?>" name="ref<?php echo $i ?>" id="ref<?php echo $i ?>" value="<?php echo $ref ?>" style="width:60px;top:10px" type="text" readonly>
</td>
<td valign="top">
<td class="art<?php echo $i ?>" align="center" valign="top">
<textarea style="" class="art<?php echo $i ?>" name="des<?php echo $i ?>" id="des<?php echo $i ?>" value="" readonly><?php echo $des ?></textarea>
</td>
<td class="art<?php echo $i ?>" align="center" valign="top">
<input class="art<?php echo $i ?>" name="costo<?php echo $i ?>" id="costo<?php echo $i ?>" value="<?php echo $costo ?>" onchange="puntoa($(this));" onkeyup="puntoa($(this));CalculaPVP($('#costo0'),$('#uti0'),$('#iva0'),$('#pvp0'));tot()" type="text" readonly>
</td>
<td class="art<?php echo $i ?>" align="center" valign="top">
<input onkeyup="CalculaPVP($('#costo0'),$('#uti0'),$('#iva0'),$('#dct0'),$('#pvp0'));" class="art<?php echo $i ?>" name="dcto<?php echo $i ?>" id="dct<?php echo $i ?>" value="<?php echo $dcto ?>" style="width:30px;" type="text"  readonly>
</td>
<td class="art<?php echo $i ?>" align="center" valign="top">
<input onkeyup="CalculaPVP($('#costo0'),$('#uti0'),$('#iva0'),$('#dct0'),$('#pvp0'));tot()" class="art<?php echo $i ?>" name="iva<?php echo $i ?>" id="iva<?php echo $i ?>" value="<?php echo $Iva ?>" style="width:30px;" type="text" readonly>
</td>
<td class="art<?php echo $i ?>" align="center" valign="top">
<input style="width:30px" class="art<?php echo $i ?>" name="uti<?php echo $i ?>" id="uti<?php echo $i ?>" value="<?php echo $uti ?>" onkeyup="CalculaPVP($('#costo0'),$('#uti0'),$('#iva0'),$('#dct0'),$('#pvp0'));tot()" type="text" readonly>
</td>
<td class="art<?php echo $i ?>" align="center" valign="top">
<input class="art<?php echo $i ?>" name="pvp<?php echo $i ?>" id="pvp<?php echo $i ?>" value="<?php echo $pvp ?>" type="text" readonly>
</td>
<td class="art<?php echo $i ?>" align="center" valign="top">
<input class="art<?php echo $i ?>" name="v_tot<?php echo $i ?>" id="v_tot<?php echo $i ?>" value="<?php echo $s_tot ?>" type="text" readonly>
</td>
<td>

</td>

</tr>


<?php
$i++;
}
$n_ref=$i;
?>
</table>

</td></tr>
    <?php


?>
<!--<tr>
<td height="133" colspan="6" align="center"><input type="button" value="+" id="addplus"  onmouseUp="addinv();" class="addbtn"></td>
</tr>
-->
<tr>
<!--<td colspan="6" align="center"><a href="#login-box" class="login-window">Registrar Producto Nuevo</a></td>-->
</tr>

 <tr id="desc">
      <td colspan="3" rowspan="5" align="left" width="700px" > 
      <textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="40"></textarea>
        <br />
        <br />
        <br />
        <br />
        <br />
        <div align="left"></div></td>
      <th width="100px">VALOR TOTAL:</th>
      <td align="right"><input  id="SUB" type="text" name="SUBTOT" value="<?php echo money($subtot) ?>"  readonly  /></td>
    </tr>
    <tr>
      <th  align="center" colspan="">DESCUENTOS: </th>
      <td align="right"><input name="DESCUENTO" id="DESCUENTO"   type="text" value="<?php echo money($descuento) ?>"  readonly/></td>
    </tr>
    <tr>
      <th  align="center" colspan="">VALOR FLETES: </th>
      <td align="right"><input name="FLETE" id="FLETE"   type="text" value="<?php echo money($flete) ?>"  onKeyUp="puntoa($(this));tot();" readonly></td>
    </tr>
    <tr>
      <th  align="center" colspan="">I.V.A TOTAL: </th>
      <td align="right"><input name="IVA" id="IVA"   type="text" value="<?php echo money($iva) ?>"  readonly/></td>
    </tr>
    <tr>
      <th  align="center" colspan="">VALOR A PAGAR:</th>
      <td colspan="" align="right"><input id="TOTAL" type="text" value="<?php echo money($tot) ?>"  name="TOTAL"  readonly/></td>
    </tr>
  <tr>
<td colspan="12">
<table align="center">
<tr>
<td colspan="" align="center"><input style="visibility:hidden" type="hidden" value="1" name="num_art" id="num_art"></td><td></td>
</tr>
  <tr valign="middle">

    <td>
    <?php
	if($estado=="SIN CONFIRMAR"){
	?>
    <input type="button" value="Confirmar Recibido"  onMouseUp="subir_tras($('#boton'),'genera',document.forms['form_fac'])" class="button">
    <?php
	}
	?>
    </td>
    <td>&nbsp;</td></tr>
  </table>

</td>
</tr>
<tr>
<td colspan="12" align="center"></td>
</tr>    


  </table>
  
<div id="Qresp">

</div>
  <input type="hidden" name="num_ref" value="<?php echo $n_ref ?>" id="num_ref" />
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