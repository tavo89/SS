<?php
require_once('Conexxx.php');
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$n_r=0;
$hh=0;
if(isset($_SESSION['n_fac_ven']))
{

if(isset($_REQUEST['exi_ref']))$n_r=$_REQUEST['exi_ref'];

//echo $_REQUEST['boton']." NR:".$n_r;
if($_REQUEST['boton']='genera'&& $n_r>0)
{
	//echo "ENTRO!";
	$cliente=limpiarcampo($_REQUEST['cli']);
	$ced=limpiarcampo($_REQUEST['ced']);
	$cuidad=limpiarcampo($_REQUEST['city']);
	$dir=limpiarcampo($_REQUEST['dir']);
	$tel=limpiarcampo($_REQUEST['tel']);
	
	$form_p=$_REQUEST['form_pago'];
	$tipo_cli=$_REQUEST['tipo_cli'];
	$ven=$_REQUEST['vendedor'];
	$meca=$_REQUEST['meca'];
	$cajero=$_SESSION['cajero'];
	$fecha=$_REQUEST['fecha'];
	$vlr_let=$_REQUEST['vlr_let'];
	
	$sub=quitacom($_REQUEST['sub']);
	$iva=quitacom($_REQUEST['iva']);
	$dcto=quitacom($_REQUEST['dcto']);
	$tot=quitacom($_REQUEST['tot']);
	$entrega=quitacom($_REQUEST['entrega']);
	$cambio=quitacom($_REQUEST['cambio']);
	$nit=$_SESSION['Nit'];
	$num_fac=serial_fac_ven();
	
    $columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,ciudad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,descuento,tot,entrega,cambio,modificable,nit";
	
$sql="INSERT INTO fac_venta($columnas) VALUES($num_fac,'$ced','$cliente','$dir','$tel','$cuidad','$form_p','$tipo_cli','$ven','$meca','$cajero','$fecha','$vlr_let',$sub,$iva,$dcto,$tot,$entrega,$cambio,'si','$nit')";

//echo "<br>$sql<br>";

$_SESSION['vendedor']=$ven;
query($sql);

$rs=$linkPDO->query("SELECT id_usu FROM usuarios WHERE id_usu='$ced'");
//echo "SELECT id_usu FROM usuarios WHERE id_usu='$ced'";

if($row=$rs->fetch()){}
else {
	//echo"<br>INSERT INTO usuarios(id_usu,nombres,dir,tel,cuidad,tipo_usu) VALUES('$ced','$cliente','$dir','$tel','$cuidad','$tipo_cli')";
$linkPDO->exec("INSERT INTO usuarios(id_usu,nombre,dir,tel,cuidad,tipo_usu) VALUES('$ced','$cliente','$dir','$tel','$cuidad','$tipo_cli')");
}


$id_fac=0;
$num_art=$_REQUEST['num_ref'];
//echo "<br>No. Fac $num_fac<br>";

$rs=$linkPDO->query("SELECT id_fac_ven,num_fac_ven FROM fac_venta WHERE num_fac_ven=$num_fac");
//echo "SELECT id_fac_ven,num_fac_ven FROM fac_venta WHERE num_fac_ven=$num_fac";

if($row=$rs->fetch())
{//echo "entra if<br>";
	$sql="INSERT INTO art_fac_ven(num_fac_ven,ref,des,iva,cant,precio,dcto,sub_tot,nit) VALUES ";
	$update="";
	$II=0;
	for($i=0;$i<$num_art;$i++)
	{
		//echo "ref_$i".$_REQUEST['ref_'.$i];
		if(isset($_REQUEST['ref_'.$i]))
		{
		$ref=$_REQUEST['ref_'.$i];
		$det=$_REQUEST['det_'.$i];
		$cant=$_REQUEST['cant_'.$i];
		$iva=quitacom($_REQUEST['iva_'.$i]);
		$dcto=quitacom($_REQUEST['dcto_'.$i]);
		$precio=quitacom($_REQUEST['val_uni'.$i]);
		$sub_tot=quitacom($_REQUEST['val_tot'.$i]);
		
if($II==0){$sql=$sql."($num_fac,'$ref','$det',$iva,$cant,$precio,$dcto,$sub_tot,'$nit')";}
else{$sql=$sql.",($num_fac,'$ref','$det',$iva,$cant,$precio,$dcto,$sub_tot,'$nit')";}
$II++;

$update="UPDATE inv_inter set exist=exist-$cant WHERE id_pro='$ref' AND nit_scs=$codSuc;";
//echo $update."<br>";


query($update);



//echo "<BR>".$sql."<br>".$update;
		}
		

	}
	//echo "<BR>".$sql."<br>";
	query($sql);

$_SESSION['n_fac_ven']="";
}
	


?>
<script language="javascript1.5" type="text/javascript">

simplePopUp('Factura Cerrada');
window.close();
</script>
<?php
	
}//fin if guarda



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Factura</title>
<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
<link href="JS/lightBox.css" rel="stylesheet" type="text/css" />
<script src="JS/jquery-2.1.1.js"></script>
<script language="javascript" type="text/javascript" src="JS/fac_ven.js"></script>
<script language="javascript" type="text/javascript" src="JS/num_letras.js"></script>
<script language="javascript" type="text/javascript">
var HH=<?php echo $hh ?>;
var iva_serv=0.08;

$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


	$('#loader').hide();
	$('#val_frt').hide();
	$('#log_serv').hide();
	$('#revision').hide();
	$('#alas').hide();
	$('#hh').hide();
	$('#precio_servA').hide();
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

</head>

<body>

<?php
$num_fac=$_SESSION['n_fac_ven'];
 

$rs=$linkPDO->query("SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac'");

if($row=$rs->fetch()){
	
    //$columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,cuidad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,descuento,tot,entrega,cambio,modificable,nit";
	
	$nit = htmlentities($row["nit"], ENT_QUOTES,"$CHAR_SET");
	$form_pa = htmlentities($row["tipo_venta"], ENT_QUOTES,"$CHAR_SET");
	$tipo_cli = htmlentities($row["tipo_cli"], ENT_QUOTES,"$CHAR_SET");
	$vendedor = htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET");
	$mecanico = htmlentities($row["mecanico"], ENT_QUOTES,"$CHAR_SET");
	$cajero = htmlentities($row["cajero"], ENT_QUOTES,"$CHAR_SET");
	$fecha = htmlentities($row["fecha"], ENT_QUOTES,"$CHAR_SET");
	$ced = htmlentities($row["id_cli"], ENT_QUOTES,"$CHAR_SET");
	$cliente = htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET");
	$dir = htmlentities($row["dir"], ENT_QUOTES,"$CHAR_SET");
	$tel = htmlentities($row["tel_cli"], ENT_QUOTES,"$CHAR_SET");
	$ciudad = htmlentities($row["ciudad"], ENT_QUOTES,"$CHAR_SET");
	$val_let = htmlentities($row["val_letras"], ENT_QUOTES,"$CHAR_SET");
	$SUB = htmlentities($row["sub_tot"]);
	$IVA = htmlentities($row["iva"]);
	$DCTO = htmlentities($row["descuento"]);
	$TOT = htmlentities($row["tot"]);
	$entrega = htmlentities($row["entrega"]);
	$cambio = htmlentities($row["cambio"]);
?>
<form action="nueva_fac_ven.php" name="form_fac" method="post" id="form_fac" >
  <table border="1" align="center" cellspacing="0" style="background-color:#FFF">
    
    <tr>
      <td colspan="5">
      <table>
       <tr>
          <td colspan="2">Forma de Pago:<select name="form_pago">
          <option value="<?php echo $form_pa ?>" selected="selected"><?php echo $form_pa ?>*</option>
          <option value="Contado">Contado</option>
          <option value="Credito">Cr&eacute;dito</option>
          <option value="Cheque">Cheque</option>
          <option value="Tarjeta Credito">Tarjeta Credito</option>
          </select>
          </td>
          <td colspan="2">Tipo de Cliente:<select name="tipo_cli" id="tipo_cli">
          <option value="<?php echo $tipo_cli ?>" selected="selected"><?php echo $tipo_cli ?>*</option>
          <option value="Publico General">P&uacute;blico General</option>
          <option value="Taller Honda">Taller Honda</option>
          <option value="Otros Talleres">Otros Talleres</option>
          <option value="Garantia FANALCA">Garantia FANALCA</option>
          
          </select>
          </td>
          <td colspan="2">Vendedor:<select name="vendedor" id="vendedor">
          <option value="<?php echo $vendedor ?>" selected="selected"><?php echo $vendedor ?>*</option>    
          <?php
		  
		  $rs=$linkPDO->query("SELECT nombre FROM usuarios INNER JOIN tipo_usu ON tipo_usu.id_usu=usuarios.id_usu WHERE (des='Vendedor' OR des='Cajero') AND cod_su=$codSuc ORDER BY nombre");
		  while($row=$rs->fetch()){
		  ?>
          <option value="<?php echo $row['nombre']?>"><?php echo $row['nombre']?></option>
          
          <?php
		  }
		  ?>
          </select>
          </td>
          <td colspan="2">Mec&aacute;nico:<select name="meca" id="meca">
          <option value="<?php echo $mecanico ?>" selected="selected"><?php echo $mecanico ?>*</option>
<?php
		  
		  $rs=$linkPDO->query("SELECT nombre FROM usuarios INNER JOIN tipo_usu ON tipo_usu.id_usu=usuarios.id_usu WHERE des='Mecanico' AND cod_su=$codSuc ORDER BY nombre");
		  while($row=$rs->fetch()){
		  ?>
          <option value="<?php echo $row['nombre']?>"><?php echo $row['nombre']?></option>
          
          <?php
		  }
		  ?>          </select>
          </td>
          </tr>

      <tr>
                      

            <td width="61" colspan="1"  >Fecha:</td>
            <td width="144" colspan="2" ><input readonly="" id="fecha" type="text" value="<?PHP echo $fecha ?>" name="fecha" /></td>
            <td colspan="2" style="font-size:28px">FACTURA DE VENTA</td>
          
            <td><span style="color: #666"><strong>(Anterior)No.</strong><?PHP echo $num_fac ?><strong></strong></span></td>
            <td  align="center"><span style="color:#F00"><strong>No.</strong><?PHP echo serial_fac_ven() ?><strong></strong></span></td>
          </tr>
      <tr>
      <td colspan="7">Datos Cliente:</td>
      </tr>
          
          <tr>
            <td >Cliente:</td>
            <td ><input name="cli" type="text" id="cli" value="<?php echo $cliente ?>"/></td>
            <td>C.C./NIT:</td>
            <td><input name="ced" type="text" value="<?php echo $ced ?>" id="ced"  onchange="busq_cli(this)"/></td>
            <td>Ciudad:</td>
            <td><input name="city" type="text" id="city" value="<?php echo $ciudad ?>" onChange="javascript:valida_doc('cliente',this.value);"/></td>
          </tr>
          <tr>
            <td >Direcci&oacute;n:</td>
            <td ><input name="dir" type="text" value="<?php echo $dir ?>" id="dir" /></td>
            <td>Tel.:</td>
            <td><input name="tel"  type="text" value="<?php echo $tel ?>" id="tel" /></td>
           
          </tr>


                 </table>
        </td>
    </tr>
    <tr>
    <td colspan="5">
    <table id="articulos" width="100%">
    <tr style="background-color: #000; color:#FFF">
      
      <td><div align="center"><strong>Referencia</strong></div></td>
      <td><div align="center"><strong>Producto</strong></div></td>
      <td><div align="center"><strong>I.V.A</strong></div></td>
      <td height="28"><div align="center"><strong>Cant.</strong></div></td>
      <td><div align="center"><strong>Dcto</strong></div></td>
      <td><div align="center"><strong>Precio</strong></div></td>
      <td><div align="center"><strong>Subtotal</strong></div></td>
    </tr>
  <?php
 $rs=$linkPDO->query("SELECT * FROM art_fac_ven INNER JOIN (select id_pro,exist,nit_scs from inv_inter) AS inv ON inv.id_pro=art_fac_ven.ref WHERE num_fac_ven='$num_fac' and inv.nit_scs=$codSuc ");
 
 $cont=0;
 
  while($row=$rs->fetch()){
	  
	$ref = htmlentities($row["ref"], ENT_QUOTES,"$CHAR_SET");
	$des = htmlentities($row["des"], ENT_QUOTES,"$CHAR_SET");
	$iva = htmlentities($row["iva"]);
	$cant = htmlentities($row["cant"]);
	$cantL = htmlentities($row["exist"]);
	$pvp = htmlentities($row["precio"]);
	$sub_tot = htmlentities($row["sub_tot"]);
	$descuento = htmlentities($row["dcto"]);
	
	
	

	  
  ?> 

<tr id="tr_<?php echo $cont?>" class="art<?php echo $cont?>">
<td class="art<?php echo $cont?>" align="center">
<input class="art<?php echo $cont?>" readonly="" value="<?php echo $ref?>" type="text" id="<?php echo $cont?>" name="ref_<?php echo $cont?>">
</td>

<td class="art<?php echo $cont?>">
<textarea class="art<?php echo $cont?>" name="det_<?php echo $cont?>" id="det_<?php echo $cont?>" readonly=""><?php echo $des?></textarea>
</td>

<td class="art<?php echo $cont?>" align="center">
<input class="art<?php echo $cont?>" id="iva_<?php echo $cont?>" type="text" name="iva_<?php echo $cont?>" size="5" maxlength="5" value="<?php echo $iva?>" onKeyUp="javascript:valor_t(<?php echo $cont?>);" style=" width:50px" readonly="">
</td>

<td class="art<?php echo $cont?>" align="center">
<input class="art<?php echo $cont?>" id="<?php echo $cont?>cant_" type="text" name="cant_<?php echo $cont?>" size="5" maxlength="5" value="<?php echo $cant?>" onKeyUp="valor_t(this.id);" onBlur="valor_t(this.id);" style=" width:50px">
<input class="art<?php echo $cont?>" id="<?php echo $cont?>cant_L" type="hidden" name="cant_L<?php echo $cont?>" size="5" maxlength="5" value="<?php echo $cantL?>" style=" width:50px">
</td>

<td class="art<?php echo $cont?>" align="center">
<input class="art<?php echo $cont?>" id="dcto_<?php echo $cont?>" type="text" name="dcto_<?php echo $cont?>" size="5" maxlength="5" value="<?php echo $descuento?>" onKeyUp="javascript:valor_t(<?php echo $cont?>);" onBlur="valor_t(<?php echo $cont?>);" style=" width:50px">
</td>

<td class="art<?php echo $cont?>">
<input class="art<?php echo $cont?>" id="val_u<?php echo $cont?>" name="val_uni<?php echo $cont?>" type="text" onKeyUp="valor_t(<?php echo $cont?>);" value="<?php echo $pvp?>" readonly=""><input class="art<?php echo $cont?>" id="val_u<?php echo $cont?>HH" name="val_u<?php echo $cont?>" type="hidden" readonly="" value="<?php echo $pvp?>">
<input class="art<?php echo $cont?>" id="val_u<?php echo $cont?>H" name="val_u<?php echo $cont?>H" type="hidden" readonly="" value="0">
</td>

<td class="art<?php echo $cont?>">
<input class="art<?php echo $cont?>" id="val_t<?php echo $cont?>" name="val_tot<?php echo $cont?>" type="text" readonly="" value="<?php echo $sub_tot?>">
<input class="art<?php echo $cont?>" id="val_t<?php echo $cont?>HH" name="val_t<?php echo $cont?>" type="hidden" readonly="" value="<?php echo $sub_tot?>">
<input class="art<?php echo $cont?>" id="val_t<?php echo $cont?>H" name="val_t<?php echo $cont?>H" type="hidden" readonly="" value="0">
</td>

<td class="art<?php echo $cont?>"><img onMouseUp="eli($(this).prop('class'))" class="<?php echo $cont?>" src="Imagenes/delete.png" width="31px" heigth="31px"></td>

</tr>
<script language="javascript1.5" type="text/javascript">
cont++;
ref_exis++;
</script>  

<?php
$cont++;
  }//fin while

?>    
      
    </table></td></tr>
    <tr>
    
    <td colspan="5">
    <table align="center" frame="box">
  <tr valign="middle">
    <th >Cod. Art&iacute;culo:</th>
<td><input type="text" name="cod" value="" id="cod" onKeyPress="codx(this,'add');" />
<!--<img style="cursor:pointer" title="Buscar" onMouseUp="busq($('#cod'));" src="Imagenes/search128x128.png" width="34" height="31" /><br />--><div id="Qresp"></div></td><td><img id="loader" src="Imagenes/ajax-loader.gif" width="31" height="31" /></td>

    <td><input type="button" value="Cerrar Factura" name="boton" onMouseUp="gfv(this.value,'genera',document.forms['form_fac'])" /></td>
    <td>&nbsp;</td></tr>
  </table>

    </td>
    </tr>
    <tr>
    <td colspan="5">
    
    <table id="servicios" width="100%">
    <tr style="background-color: #000; color:#FFF"><td colspan="6" align="center"><b>Servicios Taller Honda</b></td></tr>
    <tr style="background-color: #000; color:#FFF"><td>Servicio</td><td colspan="3">Detalles</td><td>Precio</td><td>Subtotal</td></tr>
    </table>
    </td></tr>
    <tr>
    <td colspan="5">
    <table align="center" frame="box">
<tr>
<td>
<select name="tipo_man" onChange="servicios()" id="tipo_man">
<option value="" selected>SELECCIONE MANTENIMIENTO</option>
<optgroup label="Tipo A">
<option value="FRT">FRT</option>
</optgroup>
<optgroup label="Tipo B">
<option value="Garantia">Garant&iacute;a</option>
</optgroup>
<optgroup label="Tipo C">
<option value="Servicio adicional">Servicio adicional</option>
</optgroup>
<optgroup label="Tipo D">
<?php
$sql="SELECT * FROM TIPO_mantenimiento";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
	$t_man = $row["mantenimiento"];
	$des_man = htmlentities($row["des_man"], ENT_QUOTES,"$CHAR_SET");

?>
<option value="<?php echo $row['id_man']."|$t_man" ?>"><?php echo $t_man ?></option>
<?php
}//fin while

?>
</optgroup>
</select>

</td>
<td id="log_serv"><a href="#login-box" class="login-window">Acceder</a></td>
<td id="cilindraje">
<select name="cc" id="cc" onChange="servicios()">
<option value="" selected>Cilindraje</option>
<option value="100 CC" >100 CC</option>
<option value="125-150 CC" >125-150 CC</option>
<option value="200-250 CC" >200-250 CC</option>
</select>
</td>
<td id="revision">
<select name="revision" id="rev" onChange="servicios()">
<option value="" selected>Revision</option>
<option value="1000" >1000 KM</option>
<option value="3000" >3000 KM</option>
<option value="6000" >6000 KM</option>
<option value="12000" >12000 KM</option>
</select>
</td>
<td id="alas">
<input type="text" value="" name="" id="alas_txt" placeholder="Num. Alas" onKeyUp="servicios()" style="width:70px"/>
</td>
<td id="hh">HH:
<input type="text" value="<?PHP echo $hh ?>" name="hh" id="HH" readonly="readonly" />
</td>
<td >
<div id="val_frt">
<input type="text" value="" name="val_frt" id="valor_frt" onKeyUp="val_frtx()"  placeholder="Valor FRT"/>
</div>
</td>
<td id="precio_serv">
<input type="text" value="" name="precio_s" id="precio_s" placeholder="Precio Servicio" readonly="readonly"/>
</td>
<td id="precio_servA"><input type="text" value="" name="precio_s" id="precio_sA" placeholder="Precio Servicio" onKeyUp="puntoa($(this))" /></td>
<td>
<input type="button" value="Agregar Servicio" onClick="add_serv()" name="Add_serv" />
</td>
<td id="precio_serv">

</td>
</tr>

</table>

    
    </td>
    </tr>
   
    <tr id="desc">
      <td colspan="3" rowspan="6" align="center" width="500px" ><br />
        <br />
        <br />
        <br />
        <br />
        <div align="left">:
        
      
      <textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="40"><?php echo $val_let?></textarea></div></td>
      <th style="background-color: #000; color:#FFF" width="300px">Sub-Total:</th>
      <td align="right"><input  id="SUB" type="text" readonly="" value="<?php echo $SUB?>"   name="sub"/><input type="hidden" name="SUB" value="0" id="SUBH" /></td>
    </tr>
    <tr>
      <th style="background-color: #000; color:#FFF">Dcto:</th>
      <td align="right"><input name="dcto" id="DESCUENTO" type="text" value="<?php echo $DCTO?>" onKeyUp="javascript:puntoa(this);subtot();" /></td>
    </tr>
    <tr>
      <td  align="center" colspan="">I.V.A: </td>
      <td align="right"><input name="iva" readonly="readonly" id="IVA" type="text" value="<?php echo $IVA?>" /><input id="IVAH" type="hidden" name="IVA" value="0" /></td>
    </tr>
    <tr>
      <td  align="center" colspan="">TOTAL:</td>
      <td colspan="" align="right"><input id="TOTAL" type="text" readonly="" value="<?php echo $TOT?>" name="tot" /></td>
    </tr>
     <tr>
      <td  align="center" colspan="">Entrega:</td>
      <td colspan="" align="right"><input id="entrega" type="text"  value="<?php echo $entrega?>" name="entrega" onKeyUp="change($(this));" onBlur="change($(this));" /></td>
    </tr>
    <tr>
      <td  align="center" colspan="">Cambio:</td>
      <td colspan="" align="right"><input id="cambio" type="text"  value="<?php echo $cambio?>" name="cambio" readonly="readonly" /></td>
    </tr>
    

  </table>
  <span class="Estilo2"></span><br />
  <input type="hidden" name="boton" value="" id="genera" />
  <input type="hidden" name="num_ref" value="<?php echo $cont?>" id="num_ref" />
  <input type="hidden" name="exi_ref" value="<?php echo $cont?>" id="exi_ref" />
  <input type="hidden" name="num_facH" value="<?php echo $num_fac?>" id="n_facH" />
  <input type="hidden" value="" name="boton" id="genera"/>
  <?php //echo "<br>".quitacom("1,250,00.59") ?>
</form>

<?php
}

?>
</body>
</html>
<?php
}
else{

?>
<!DOCTYPE html>
<html>

<body>
<h1>No hay factura seleccionada</h1>
</body>
</html>
<?php
}

?>