<?php
include_once("Conexxx.php");
$sql="";

$num_ref=0;
$num_fac="";
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
$sql="";
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;

$num_ref=$_REQUEST['num_ref'];
$num_fac=$_REQUEST['num_fac'];
$fecha=$_REQUEST['fecha'];
$num_fac_com=serial_fac_dev($conex);

$provedor=$_REQUEST['provedor'];
$nit_pro=$_REQUEST['nit'];
$ciudad=$_REQUEST['ciudad'];
$dir=$_REQUEST['dir'];
$tel=$_REQUEST['tel'];
$fax=$_REQUEST['fax'];
$mail=$_REQUEST['mail'];

$subtot=quitacom($_REQUEST['SUBTOT']);
$descuento=quitacom($_REQUEST['DESCUENTO']);
$flete=0;
$iva=quitacom($_REQUEST['IVA']);
$tot=quitacom($_REQUEST['TOTAL']);
//$confirm=$_REQUEST['confirma'];
$val_letras=$_REQUEST['vlr_let'];
$nota_dev=$_REQUEST['nota'];
	$ven=r('vendedor');
	$vendedorArr=explode("-",$ven);
	
	$ven=$vendedorArr[0];
	$idVen=$vendedorArr[1];

	$TIPDOC=r("TIPDOC");
	$FECVEN=r("FECVEN");
	$MONEDA=r("MONEDA");
	$TIPCRU=r("TIPCRU");
	$CODSUC=r("CODSUC");
	$NUMREF=r("num_fac");
	$FORIMP=r("FORIMP");
	$CLADET=r("CLADET");
	$ORDENC=r("ORDENC");
	$NUREMI=r("NUREMI");
	$NORECE=r("NORECE");
	$EANTIE=r("EANTIE");
	$COMODE=r("COMODE");
	$COMOHA=r("COMOHA");
	$FACCAL=r("FACCAL");
	$FETACA=r("FETACA");
	$FECREF=r("FECREF");
	$OBSREF=r("OBSREF");
	$TEXDOC=r("TEXDOC");
	$MODEDI=r("MODEDI");
	$NDIAN=r("NDIAN");
	$SOCIED=r("SOCIED");
	$MOTDEV=r("MOTDEV");
	$CODVEN=$idVen;
	
	$claper=r("claper");
	$coddoc=r("coddoc");
	$paicli=r("paicli");
	$depcli=r("depcli");
	$regFiscal=r("regFiscal");
	$nomcon=r("nomcon");
	$regtri=r("regtri");
	$razsoc=r("razsoc");
	$snombr=r("snombr");
	$apelli=r("apelli");
	

	


$rs=$linkPDO->query("SELECT * FROM provedores WHERE nit='$nit_pro' FOR UPDATE");

if($row=$rs->fetch()){}
else
{
$sql="INSERT INTO provedores VALUES('$nit_pro', '$provedor','$dir','$tel','$mail','$ciudad','$fax')";
$linkPDO->exec($sql);
}

$sql="INSERT INTO fac_dev(num_fac_com,fecha,cod_su,nom_pro,nit_pro,ciudad,dir,tel,fax,mail,subtot,descuento,flete,iva,tot,val_letras,fecha_crea,serial_fac_dev,nota_dev,kardex, TIPDOC, FECVEN, MONEDA, CODSUC, NUMREF, FORIMP, CLADET, ORDENC, NUREMI, NORECE, EANTIE, COMODE, COMOHA, FACCAL, FETACA, FECREF, OBSREF, TEXDOC, MODEDI, NDIAN, SOCIED, MOTDEV, claper, coddoc, paicli, depcli, regFiscal, nomcon, regtri, razsoc, snombr, apelli,id_vendedor,vendedor) VALUES('$num_fac','$fecha',$codSuc,'$provedor','$nit_pro','$ciudad','$dir','$tel','$fax','$mail',$subtot,$descuento,$flete,$iva,$tot,'$val_letras',CURRENT_TIMESTAMP(),'$num_fac_com','$nota_dev','$FLUJO_INV', '$TIPDOC', '$FECVEN', '$MONEDA', '$CODSUC', '$NUMREF', '$FORIMP', '$CLADET', '$ORDENC', '$NUREMI', '$NORECE', '$EANTIE', '$COMODE', '$COMOHA', '$FACCAL', '$FETACA', '$FECREF', '$OBSREF', '$TEXDOC', '$MODEDI', '$NDIAN', '$SOCIED', '$MOTDEV', '$claper', '$coddoc', '$paicli', '$depcli', '$regFiscal', '$nomcon', '$regtri', '$razsoc', '$snombr', '$apelli','$idVen','$ven')";

$linkPDO->exec($sql);
//echo "<span style=\"color: #fff\"<b>n_ref:$num_ref|SQL[$sql]</b></span>";
/////////////////////////////////////////////////////////// REPUESTOS /////////////////////////////////////////////////////////////////
$linkPDO->exec("SAVEPOINT A");
$i=0;
for($i=0;$i<$num_ref;$i++)
{
	$linkPDO->exec("SAVEPOINT loop".$i);
	if(isset($_REQUEST['ref'.$i])&&!empty($_REQUEST['ref'.$i]) )
	{
		$cant=limpianum($_REQUEST['cant'.$i]);
		$ref=rm('ref'.$i);
		
		$cod_bar=rm('cod_bar'.$i);
		if(empty($ref))$ref=$cod_bar;
		$des=rm('des'.$i);
		$color=rm('color'.$i);
		$talla=rm('talla'.$i);
		$costo=quitacom($_REQUEST['costo'.$i]);
		
		$presentacion=rm('presentacion'.$i);
		if(empty($presentacion))$presentacion="UNIDAD";
		
		$fracc=r('fracc'.$i);
		if(empty($fracc))$fracc=1;
		$unidades_fracc=r('unidades'.$i);
		if(empty($unidades_fracc)|| $unidades_fracc<0)$unidades_fracc=0;
		
				//echo "<br><span style=\"color: #fff\"<b>$i costo SIN Dcto : $costo</b></span><br>";
		$dcto=limpianum($_REQUEST['dcto'.$i]);
		$iva=limpianum($_REQUEST['iva'.$i]);
		$uti=limpianum($_REQUEST['util'.$i]);
   		$tipoD=rm('tipo_dcto'.$i);
		$pvp=quitacom($_REQUEST['pvp'.$i]);
		$fabricante=rm('fabricante'.$i);
		$clase=rm('clase'.$i);
		$fechaVenci=r('fecha_vencimiento'.$i);
		if(empty($fechaVenci)){$fechaVenci="0000-00-00";}
		$s_tot=quitacom($_REQUEST['v_tot'.$i]);
		if(empty($dcto))$dcto=0;
		if(empty($iva))$iva=0;
		$costoDesc=$costo - $costo*($dcto/100);
		if($usar_costo_dcto==1)$costoDesc=$costo - $costo*($dcto/100);
		else $costoDesc=$costo;
		$uti=util($pvp,$costoDesc,$iva,"per");
		//echo "<br><span style=\"color: #fff\"<b>$i costo con Dcto : $costo</b></span><br>";
		
		
		
		$sql="INSERT INTO art_fac_dev (`num_fac_com`, `cant`, `ref`, `des`, `costo`, `dcto`, `iva`, `uti`, `pvp`, `tot`, `cod_su`, `nit_pro`, `cod_barras`, `color`, `talla`, `presentacion`, `fecha_vencimiento`, `fraccion`, `unidades_fraccion`, `serial_dev`, `linea`, `modelo`, `num_motor`, `num_chasis`, `cilindraje`, `consecutivo_proveedor`) VALUES('$num_fac',$cant,'$ref','$des',$costo,$dcto,$iva,$uti,$pvp,$s_tot,$codSuc,'$nit_pro','$cod_bar','$color','$talla','$presentacion','$fechaVenci','$fracc','$unidades_fracc','$num_fac_com','','','','','','')";
		$linkPDO->exec($sql);
		
		
//// SUMA cantidades
if($FLUJO_INV==1  ){	

$sql="UPDATE `inv_inter`  SET exist=(exist-$cant), unidades_frac=(unidades_frac-$unidades_fracc) WHERE nit_scs='$codSuc' AND fecha_vencimiento='$fechaVenci' AND id_pro='$ref' AND id_inter='$cod_bar'";
//echo "<li>$sql</li>";
$linkPDO->exec($sql);

}

			}
	
}

/*$sql="UPDATE `inv_inter` i 
INNER JOIN 
(select ar.cod_su,ar.cant,ar.ref,ar.cod_barras,ar.fecha_vencimiento,ar.unidades_fraccion from art_fac_dev ar WHERE ar.cod_su='$codSuc' AND ar.num_fac_com='$num_fac' AND ar.nit_pro='$nit_pro' AND serial_dev='$num_fac_com' group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a 
ON i.id_inter=a.cod_barras 
SET i.exist=(i.exist-a.cant), i.unidades_frac=(i.unidades_frac-a.unidades_fraccion) WHERE i.nit_scs=a.cod_su and i.nit_scs=$codSuc AND i.fecha_vencimiento=a.fecha_vencimiento AND  i.id_pro=a.ref";
$linkPDO->exec($sql);
if(!$all_query_ok){printf("<br>Errormessage:[$sql] %s\n", $conex->error);}
*/


$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){
	
	//SendNotaDebDIAN($num_fac,$nit_pro,$codSuc);
	eco_alert("Operacion Exitosa");
	
	js_location("devoluciones.php");
}
else{eco_alert("ERROR! Intente nuevamente");} 



}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

}// FIN GENERA DOC

//echo "FLUJO_INV  $FLUJO_INV";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("HEADER.php"); ?>
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/style.css">
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/prism.css">
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/chosen.css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<script src="JS/jquery-2.1.1.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script>
<script language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script> 
<script language="javascript1.5" src="JS/fac_com.js?<?php echo "$LAST_VER" ?>"></script> 
<script language="javascript1.5" type="text/javascript" src="JS/num_letras.js"></script>
<script language="javascript1.5" type="text/javascript">
var traslado=0;


function subir_dev(btn,val,form)
{
	//alert('PROMEDIO  VAL:'+form.confirma.value);
	if(esVacio(form.num_fac.value)){alert('Ingrese el numero de Factura');form.num_fac.focus();}
	else if(form.verify.value!='ko'){alert('El numero de Factura  NO es  Valido!');form.num_fac.focus();}
	else if(esVacio(form.fecha.value)){alert('Ingrese la FECHA');form.fecha.focus();}
	else if(esVacio(form.nit.value)||esVacio(form.provedor.value)){alert('Ingrese el PROVEEDOR');form.n_pro.focus();}
	else if(esVacio(form.num_ref.value)){alert('Ingrese los ARTICULOS');form.addplus.focus();}
	else{
	btn.prop('value',val);
	form.submit();
	$('#btn').remove();
	}
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

<title>Fac. de Compra</title>
</head>

<body>
<div class="container ">
			<!-- Push Wrapper -->
			<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
            <?php include_once("boton_menu.php"); ?>

<?php

$nomCli="";
$idCli="";
$telCli="";
$dirCli="";
$mailCli="";
$PLACA="";
$KM="";
$cuidad="";

$nota_fac="";

	$snombr="";
	$apelli="";
	
	$claper="";
	
	$coddoc="31";
	$paicli="";
	$depcli="";
	$loccli="";
	$nomcon="";
	$regtri="2";
	$razsoc="";
	
	$TIPDOC="02";
	$FECVEN="";
	$MONEDA="";
	$TIPCRU="";
	$CODSUC="";
	$NUMREF="";
	$FORIMP="";
	$CLADET="";
	$ORDENC="";
	$NUREMI="";
	$NORECE="";
	$EANTIE="";
	$COMODE="";
	$COMOHA="";
	$FACCAL="";
	$FETACA="";
	$FECREF="";
	$OBSREF="";
	$TEXDOC="";
	$MODEDI="";
	$NDIAN="";
	$SOCIED="";
	$MOTDEV="";
?>

<div class="uk-width-9-10 uk-container-center">
<nav class="uk-navbar">

		<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-flip" >  
		
				<?php 
				if($MODULES["GASTOS"]==1){}
				?>

				<?php 
				if($MODULES["ANTICIPOS"]==1){}
				?>
				
					 
				
				<?php if(($rolLv==$Adminlvl || val_secc($id_Usu,"fac_lista")) && $codSuc>0){?>
				<li><a href="<?php echo "devoluciones.php" ?>" ><i class="uk-icon-list <?php echo $uikitIconSize ?>"></i>&nbsp;Lista DEVOLUCIONES</a></li>
				<?php } ?>
				<li><a href="#OPC_FAC" data-uk-modal ><i class="uk-icon-gear  uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;OPCIONES</a></li>
					<!--<li><a href="<?php echo thisURL(); ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>-->
			</ul>

		</nav>
<form action="devolucion.php" name="form_fac" method="get" id="form_fac" class="uk-form" autocomplete="off">


<input type="hidden" value="DEVOLUCION_COMPRAS" name="nombreModulo" id="nombreModulo" class=""/>

<div class="loader"><img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" /></div>
  <table align="center" cellspacing="0"  width="1000px" class="round_table_gray" >
    <tr>
    
      <td height="80" colspan="8" align="center" >
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
      <tr><td width="450px"> <div align="center">
<img src="<?php echo $url_LOGO_B ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>">
</div></td>
      <td align="left" colspan="5">

<?php // echo $PUBLICIDAD  ?>

 </td>
 <td align="center" style="font-size:18px; "><B>NOTA D&Eacute;BITO</B><br> <span style="color:#F00"># <?php echo serial_fac_dev($conex) ?></span></td>

 </tr>
 </table>
 
 </td>
    </tr>

    <tr>
      <td colspan="5">
      <table frame="box" rules="rows" width="100%">
      <tr>
                      
<td  align="left" colspan="2">
            <table align="left" cellspacing="0"><tr>
            
            <td align="right" width="61" colspan="1"  >Fecha:</td>
      <td width="144" colspan="2" ><input   id="fecha2" type="date" value="" name="fecha" onClick="//popUpCalendar(this,form_fac.fecha2,'yyyy-mm-dd')" /></td>
            <td colspan="1">
            <span style="color:#F00"><strong>No.</strong></span><td colspan="2">
<input name="num_fac" type="text" id="num_fac" value="" onChange="validar_2c($('#num_fac').val(),$('#nit').val(),'fac_com','num_fac_com','nit_pro',$('#resp'));" onBlur="//nan($(this))" placeholder="Num. Compra a Devolver"/>
          
            </td>
            <td>
         <div id="resp" style="visibility:hidden; color: #0C3; width:180px;"><img alt="" src="Imagenes/confirm.png" width="20" height="20" />Factura Condirmada</div>
            </td>
            </tr></table>
            </td>

            
            
            
            <td colspan="2" style="font-size:18px">Promediar Costos:</td>
          
            <td colspan="3">
            </td>
            
      
          </tr>
     
          
          <tr>
            <td width="">Proveedor:</td>
            <td >
            <table border="0">
            <tr>
            <td>
            <input name="provedor" type="text" id="provedor" value="" style="width:100px"/></td>
            <td>
            <select data-placeholder="Escriba un No. de Factura" class="chosen-select " name="n_pro" id="n_pro" onChange="nom_pro(this);validar_2c($('#num_fac').val(),$('#nit').val(),'fac_com','num_fac_com','nit_pro',$('#resp'));">
            <option value="" selected>Provedores Usuales</option>
            <?php
		    $rs=$linkPDO->query("SELECT * FROM provedores ORDER BY nom_pro");
			while($row=$rs->fetch()){
			?>
            <option value="<?php echo $row['nit'] ?>"><?php echo $row['nom_pro'] ?></option>
            <?php
			}
			?>
            </select>
            </td>
            <td><a href="#CREAR_CLI" data-uk-modal="" class="uk-button uk-button-success">Datos Proveedor</a></td>
            </tr>
            </table>
            </td>
            <td>NIT:</td>
            <td><input style="width:100px;" name="nit" type="text" id="nit" value="" onChange="pro(this.value);" /></td>
            <td align="">Ciudad:</td>
            <td colspan=""><input style="width:200px" name="ciudad" type="text" id="ciudad" value="" /></td>
            <td>
            <!--<table>
            <tr>
            <td>Estado:</td>
            <td>
            <select name="estado">
            <option value="PAGADO">PAGADO</option>
            <option value="PENDIENTE">PENDIENTE</option>
            </select></td>
            </tr>
            </table>-->
            </td>
          </tr>
          <tr>
            <td >Direcci&oacute;n:</td>
            <td ><input style="width:200px" name="dir" type="text" value="" id="dir" /></td>
            <td>Tel.:</td>
            <td colspan=""><input style="width:100px" name="tel"  type="text" value="" id="tel" /></td><td>Fax:</td>
            <td align=""><input style="width:100px" name="fax" type="text" id="fax" value="" onChange="javascript:valida_doc('cliente',this.value);"/></td>
            <td colspan="2">&nbsp;&nbsp;&nbsp;Mail:<input style="width:150px" name="mail" type="text" id="mail" value=""> </td>

           
          </tr>


          
        </table>
      </td>
    </tr>
    <tr>
<td colspan="5">
<table id="articulos" width="100%" cellspacing="0" cellpadding="0" frame="box" rules="cols" style="border-width:1px">
<tr>  
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>#</strong></div></td>
<td colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Referencia</strong></div></td>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Cod. Barras</strong></div></td>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Cant.</strong></div></td>

<?php if($usar_fracciones_unidades==1){ ?>
<td>
<div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Fracc.</strong></div>
</td>

<td>
<div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Uni.</strong></div>
</td>
<?php } ?>


<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>&nbsp;Descripci&oacute;n&nbsp;</strong></div></td>

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
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Presentaci&oacute;n</strong></div></td>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Fabricante</strong></div></td>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Clase</strong></div></td>

<?php
if($usar_fecha_vencimiento==1){
?>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Fecha Vencimiento</strong></div></td>

<?php
}
?>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Costo</strong> sin IVA</div></td>
<td  colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Dcto.(%)</strong></div></td>
<td  colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Util.(%)</strong></div></td>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>IVA (%)</strong></div></td>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>PVP</strong></div></td>

<!--<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Utilidad</strong></div></td>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Util.(%)</strong></div></td>

-->

<td width="90px"  colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong> Total</strong></div></td>
</tr>
      

</table>

</td></tr>
    <?php


?>
<tr>
<td colspan="6" align="center"><input type="button" name="addplus" value="Agregar Producto" id="addplus"  onmouseUp="addinv_dev();" class="addbtn"></td>
</tr>
<tr>
<td colspan="6" align="center"></td>
</tr>

 <tr id="desc">
      <td colspan="3" rowspan="5" align="left" width="700px" > 
      <textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="40" style="width:200px"></textarea>
        <br />
        <br />
        <br />
        <br />
        <br />
        <div align="left"></div></td>
      <th width="100px">VALOR TOTAL:</th>
      <td align="right"><input  id="SUB" type="text" name="SUBTOT" value=""  readonly  /></td>
    </tr>
    <tr>
      <th  align="center" colspan="">DESCUENTOS: </th>
      <td align="right"><input name="DESCUENTO" id="DESCUENTO"   type="text" value=""  readonly/></td>
    </tr>
    <tr>
      <th  align="center" colspan="">VALOR FLETES(sin IVA): </th>
      <td align="right"><input name="FLETE" id="FLETE"   type="text" value=""  onKeyUp="puntoa($(this));tot();" onBlur="nanC($(this))"></td>
    </tr>
    <tr>
      <th  align="center" colspan="">I.V.A TOTAL: </th>
      <td align="right"><input name="IVA" id="IVA"   type="text" value=""  readonly/></td>
    </tr>
    <tr>
      <th  align="center" colspan="">VALOR A PAGAR:</th>
      <td colspan="" align="right"><input id="TOTAL" type="text"  name="TOTAL"  readonly/></td>
    </tr>
  <tr>
<td colspan="12">
<table align="center">
<tr>
<td colspan="" align="center"><input style="visibility:hidden" type="hidden" value="1" name="num_art" id="num_art"></td><td></td>
</tr>
<tr>
<td colspan="2" align="center"><textarea placeholder="Notas de la Devoluci&oacute;n" name="nota" id="nota" cols="20" rows="3" style="width:300px"></textarea></td>
</tr>


  <tr valign="middle" id="btn">

    <td><input type="button" value="Generar" name="botonG"  onMouseUp="subir_dev($('#boton'),'genera',document.forms['form_fac'])" class="addbtn" ></td>
    <td><input type="button" value="Volver" onMouseDown="javascript:location.assign('devoluciones.php');" class="addbtn" /></td></tr>
  </table>

</td>
</tr>
<tr>
<td colspan="12" align="center"></td>
</tr>    


  </table>
  
<div id="Qresp">

</div>
  <input type="hidden" name="num_ref" value="" id="num_ref" />
  <input type="hidden" name="verify" id="verify" value="">
  <input type="text" style="visibility: hidden" name="boton" id="boton" value="" >
  
  <div id="CREAR_CLI" class="uk-modal">
<div class="uk-modal-dialog" style="width:900px;">

<a class="uk-modal-close uk-close"></a>
<h1 style="color:#000">REGISTRO CLIENTE</h1>
<table cellspacing="0" cellpadding="0">
<tr class="clientes">
<td>Nombre 1:</td>

<td >
<!--<input name="cli" type="text" id="cli" value="<?php echo "$nomCli"; ?>" onKeyUp="//get_nom(this,'add');mover($(this),$('#cod'),$(this),0);" onBlur="busq_cli(this);" class="uk-form-small datos_cli"/>-->
<input name="cli" type="text" id="cli" value="<?php echo $nomCli ?>" class="datos_cli" />
<input name="cliA" type="hidden" id="cliA" value="<?php echo $nomCli ?>" class="datos_cli" />
<?php if($MODULES["ALIAS_CLI"]){?>
<input name="aliasCli" type="text" id="aliasCli" value="" placeholder="ALIAS" style="width:60px;" class="datos_cli">
<?php }?>
</td>
<td>Nombre 2:</td>
<td><input name="snombr" type="text" id="snombr" value="<?php echo "$snombr"; ?>" onChange="" class="datos_cli"/>
<input name="snombrA" type="hidden" id="snombrA" value="<?php echo "$snombr"; ?>" onChange="" class="datos_cli"/></td>
<td>Apellidos:</td>
<td><input name="apelli" type="text" id="apelli" value="<?php echo "$apelli"; ?>" onChange="" class="datos_cli"/>
<input name="apelliA" type="hidden" id="apelliA" value="<?php echo "$apelli"; ?>" onChange="" class="datos_cli"/></td>


</tr>


<tr class="clientes">
<td>Tipo Documento:</td>
<td>
<select name="coddoc" id="coddoc" style="width:200px;" class="datos_cli">

<option value="13"  <?php if($coddoc=="13" || empty($coddoc)){echo "selected";} ?>>Cedula de ciudadania</option>
<option value="31" <?php if($coddoc=="31"){echo "selected";} ?>>NIT</option>
<option value="11" <?php if($coddoc=="11"){echo "selected";} ?>>Registro civil</option>
<option value="12" <?php if($coddoc=="12"){echo "selected";} ?>>Tarjeta de identidad</option>

<option value="21" <?php if($coddoc=="21"){echo "selected";} ?>>Tarjeta de extranjeria</option>
<option value="22" <?php if($coddoc=="22"){echo "selected";} ?>>Cedula de extranjeria</option>

<option value="41" <?php if($coddoc=="41"){echo "selected";} ?>>Pasaporte</option>
<option value="42" <?php if($coddoc=="42"){echo "selected";} ?>>Documento de identificacion extranjero</option>
</select>
</td>
 
<td>Clase Persona:</td>
<td>
<select name="claper" id="claper" class="datos_cli">
<option value="1" <?php if($claper=="1"){echo "selected";} ?>>Juridica</option>
<option value="2" <?php if($claper=="2" || empty($claper)){echo "selected";} ?>>Natural</option>
</select>
</td>
 
</tr>
<tr>
<td>Regimen Tributario:</td>
<td>
<select name="regtri" id="regtri" class="datos_cli">
<option value="0" <?php if($regtri=="0"){echo "selected";} ?>>Simplificado</option>
<option value="2" <?php if($regtri=="2"){echo "selected";} ?>>Comun</option>
</select>
</td>
<td>Razon Social :</td>
<td><input name="razsoc"  type="text" value="<?php echo "$razsoc"; ?>" id="razsoc" class="datos_cli"  /></td>
</tr>
<tr>
 <td>Departamento:</td>
<td><input name="depcli" type="text" id="depcli" value="<?php echo "$depcli"; ?>" class="datos_cli" /></td>
 
<td>Localidad :</td>
<td><input name="loccli"  type="text" value="<?php echo "$loccli"; ?>" id="loccli" class="datos_cli"  /></td>
 
</tr>
 

<tr>
<td>Cod. Pa&iacute;s:</td>
<td> 
<select name="paicli" id="paicli" class="datos_cli">
<option value="CO" <?php if($paicli=="CO"){echo "selected";} ?>>Colombia</option>
<option value="VE" <?php if($paicli=="VE"){echo "selected";} ?>>Venezuela</option>
</select>
</td>

<td>Nombre de Contacto:</td>
<td>
<input name="nomcon"  type="text" value="<?php echo "$nomcon"; ?>" id="nomcon"  class="datos_cli" />
</td>

</tr>


<tr>
<td colspan="4" align="center"><input type="button" value="Aceptar" name="filtro"  class="uk-button uk-button-success uk-modal-close datos_cli" onClick="//save_cli($('.datos_cli')) "></td>
</tr>
</table>
<input type="hidden" value="1" name="auth_cre" id="auth_cre" class="datos_cli"/>
<input type="hidden" value="Particular" name="tipo_usu" id="tipo_usu" class="datos_cli" />
    </div>
</div>




<div id="OPC_FAC" class="uk-modal" >
<div class="uk-modal-dialog" style="width:1000px;">

<a class="uk-modal-close uk-close"></a>
<h1 style="color:#000">Opciones Adicionales Factura</h1>
<table cellspacing="0" cellpadding="0" class="uk-table">
<tr  >


<td valign="bottom" colspan=" ">
APLICAR PRECIOS: </td>
<td>
<input type="button" value="CREDITO" onClick="<?php if($MODULES["PVP_CREDITO"]==1) echo "cambiaFP();"; ?>" class="uk-button uk-button-primary"></TD>
<td>
 <input type="button" value="MAYORISA" onClick="<?php if($MODULES["MAYORISTA_PER"]==1) echo "set_mayorista();"; ?>" class="uk-button uk-button-primary">
</td>
</tr>
<tr>

<?php if($MODULES["ANTICIPOS"]==1){ ?>
<td colspan="" class="">
USAR Bono/Anticipo </td>
<td>
<select id="confirm_bono" name="confirm_bono" onChange="" class="uk-form-small">
<option value="SI" >Si</option>
<option value="NO" selected>No</option>
</select>
</td>
<?php }?>
                 

</tr>
       
<tr class="<?php if($MODULES["RETENCIONES"]==1){}else{echo "uk-hidden";}?>">
<td  align="right" colspan="">R. FTE:<input placeholder="%" id="R_FTE_PER" type="text"  value="" name="R_FTE_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_FTE'));" class="save_fc uk-form-small"/>
</td>
<td colspan="" align="right"><input id="R_FTE" type="text"  value="" name="R_FTE" class="save_fc uk-form-small" />
</td>
</tr>
          

<tr class="<?php if($MODULES["RETENCIONES"]==1){}else{echo "uk-hidden";}?>">
<td  align="right" colspan="">R. IVA:<input placeholder="%" id="R_IVA_PER" type="text"  value="" name="R_IVA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#IVA'),$('#R_IVA'));"  class="uk-form-small"/></td>
<td colspan="" align="right"><input id="R_IVA" type="text"  value="" name="R_IVA"  class="save_fc uk-form-small"/>
</td>
</tr>
          

<tr class="<?php if($MODULES["RETENCIONES"]==1){}else{echo "uk-hidden";}?>">
<td  align="right" colspan="">R. ICA:<input placeholder="%" id="R_ICA_PER" type="text"  value="" name="R_ICA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_ICA'));" class="save_fc uk-form-small"/></td>
<td colspan="" align="right"><input id="R_ICA" type="text"  value="" name="R_ICA" class="save_fc uk-form-small"/>
</td>
</tr>

<tr class="<?php if($impuesto_consumo==1){}else{echo "uk-hidden";}?>">
<td  align="right" colspan="">IMP. Consumo<input placeholder="%" id="CONSUMO_PER" type="hidden"  value="" name="CONSUMO_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#TOT'),$('#IMP_CONSUMO'));" class="save_fc uk-form-small"/></td>
<td colspan="" align="right"><input id="IMP_CONSUMO" type="text"  value="" name="IMP_CONSUMO" class="save_fc uk-form-small"/>
</td>
</tr>



<tr>
<td>
<!-- DEAULTS -->
<input type="hidden" value="<?php echo $TIPDOC; ?>" name="TIPDOC" id="TIPDOC" class=""/>
<input type="hidden" value="L" name="TIPCRU" id="TIPCRU" class=""/>
<input type="hidden" value="<?php echo $codSuc; ?>" name="CODSUC" id="CODSUC" class=""/>
<input type="hidden" value="01" name="FORIMP" id="FORIMP" class=""/>
<input type="hidden" value="D" name="CLADET" id="CLADET" class=""/>
<input type="hidden" value="" name="ORDENC" id="ORDENC" class=""/>
<input type="hidden" value="" name="NUREMI" id="NUREMI" class=""/>
<input type="hidden" value="" name="NORECE" id="NORECE" class=""/>
<input type="hidden" value="" name="EANTIE" id="EANTIE" class=""/>
<input type="hidden" value="COP" name="COMODE" id="COMODE" class=""/>
<input type="hidden" value="COP" name="COMOHA" id="COMOHA" class=""/>
<input type="hidden" value="" name="FACCAL" id="FACCAL" class=""/>
<input type="hidden" value="" name="FETACA" id="FETACA" class=""/>
<input type="hidden" value="" name="FECREF" id="FECREF" class=""/>
<input type="hidden" value="" name="OBSREF" id="OBSREF" class=""/>
<input type="hidden" value="" name="FACCAL" id="FACCAL" class=""/>
<input type="hidden" value="" name="FETACA" id="FETACA" class=""/>

<input type="hidden" value="<?php echo "$ResolContado";?>" name="NDIAN" id="NDIAN" class=""/>

Fecha Vencimiento:
</td>
<td><input type="date" name="FECVEN" id="FECVEN" class="" /></td>
<td>Moneda:</td>
<td>
<select name="MONEDA" id="MONEDA" class="">
<option value="COP" selected="selected">COP</option>
<option value="USD">USD</option>
</select>
</td>
<td>Numero doc. referenciado:</td>
<td><input type="text" name="NUMREF" id="NUMREF" class="" /></td>

<td>Orden de compra:</td>
<td><input type="text" name="ORDENC" id="ORDENC" class="" /></td>

</tr>
<tr>
<td>Fecha Doc. Referenciado</td>
<td><input type="date" name="FECREF" id="FECREF" class="" /></td>

<td>Observaciones Fac. referenciada:</td>
<td><textarea name="OBSREF" id="OBSREF" class=""></textarea></td>
<td>Organización de ventas:</td>
<td><input type="text" name="SOCIED" id="SOCIED" class=""></td>

</tr>
<tr>
<td>Texto documento:</td>
<td><textarea name="TEXDOC" id="TEXDOC" class=""></textarea></td>
<td>Motivo devoluci&oacute;n DIAN:</td>
<td><textarea name="MODEDI" id="MODEDI" class=""></textarea></td>

<td>Motivo devoluci&oacute;n SISTEMA:</td>
<td><textarea name="MOTDEV" id="MOTDEV" class=""></textarea></td>
</tr>
</table>
</div>
</div>
<select name="vendedor" id="vendedor"  style="width:100px" class="<?php echo $ukFormSize ?>"  >

<option value="<?PHP echo "$nomUsu-$id_Usu" ?>" selected><?PHP echo $nomUsu ?></option>
</select>
</form>
<?php include_once("js_global_vars.php"); ?>
<?php include_once("FOOTER.php"); ?>

<?php include_once("lib_compras.php"); ?>
<?php include_once("keyFunc_fac_com.php"); ?>
<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/chosen.jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/docsupport/prism.js"></script>
<script type="text/javascript">
    var config = {
      '.chosen-select'           : {no_results_text:'Oops, NO se encontro nada!'},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, NO se encontro nada!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>
</body>
</html>