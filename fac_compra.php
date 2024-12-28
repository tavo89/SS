<?php
require_once('Conexxx.php');
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"compras_add")){header("location: centro.php");}
valida_session();


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

$CurrentDate_form=gmdate("Y-m-d",hora_local(-5));
$AfterMonthDate_form=gmdate("Y-m-d",strtotime("+1 month"));
 
if(isset($_REQUEST['boton'])&&!empty($_REQUEST['boton']))$boton=$_REQUEST['boton'];

if($boton=="genera")
{
	
	

try { 

$linkPDO->beginTransaction();
$all_query_ok=true;
$echo_all=0;

$sql="";

$num_ref=limpiarcampo($_REQUEST['num_ref']);
$num_fac=limpiarcampo($_REQUEST['num_fac']);
$fecha=limpiarcampo($_REQUEST['fecha']);


$provedor=rm("provedor");
$nit_pro=rm("nit");
$ciudad=rm("ciudad");
$dir=rm("dir");
$tel=rm("tel");
$fax=limpiarcampo($_REQUEST['fax']);
$mail=limpiarcampo($_REQUEST['mail']);

$tipo_calc_op = limpiarcampo($_REQUEST['tipo_op']);

$subtot=quitacom($_REQUEST['SUBTOT']);
$descuento=quitacom($_REQUEST['DESCUENTO']);
$descuento2=quitacom($_REQUEST['DESCUENTO2']);
$flete=quitacom($_REQUEST['FLETE']);
$iva=quitacom($_REQUEST['IVA']);
$tot=quitacom($_REQUEST['TOTAL']);
$confirm=limpiarcampo($_REQUEST['confirma']);
$tipo_fac=limpiarcampo($_REQUEST['tipo_fac']);
$val_letras=limpiarcampo($_REQUEST['vlr_let']);

$feVen=r('fechaVen');

if($tipo_fac=="Traslado"){
	$numTras=serial_fac_tras($conex);
	$num_fac_com=-serial_fac_com($conex);
}
else{
	 $numTras=0;
	$num_fac_com=serial_fac_com($conex); 
}

 


$sql="INSERT IGNORE INTO provedores(nit,nom_pro,dir,tel,mail,ciudad,fax) VALUES('$nit_pro', '$provedor','$dir','$tel','$mail','$ciudad','$fax')";
$linkPDO->exec($sql);


$sql="UPDATE provedores SET  nom_pro='$provedor',dir='$dir',tel='$tel',mail='$mail',ciudad='$ciudad',fax='$fax' WHERE nit='$nit_pro'";
$linkPDO->exec($sql);


$sql="INSERT INTO fac_com(num_fac_com,fecha,cod_su,nom_pro,nit_pro,ciudad,dir,tel,fax,mail,subtot,descuento,flete,iva,tot,val_letras,fecha_crea,serial_fac_com,tipo_fac,dcto2,pago,fecha_pago,feVen,kardex,serial_tras, calc_pvp) VALUES('$num_fac','$fecha','$codSuc','$provedor','$nit_pro','$ciudad','$dir','$tel','$fax','$mail','$subtot','$descuento','$flete','$iva','$tot','$val_letras',now(),'$num_fac_com','$tipo_fac','$descuento2','PENDIENTE','0000-00-00','$feVen','$FLUJO_INV','$numTras','$tipo_calc_op')";

$linkPDO->exec($sql);


	$HTML_antes="";
	$HTML_despues="";
	if(isset($_REQUEST['htmlPag']))$HTML_despues=$_REQUEST['htmlPag'];
	logDB($sql,$SECCIONES[6],$OPERACIONES[1],$HTML_antes,$HTML_despues,$num_fac);
//echo "<span style=\"color: #fff\"<b>n_ref:$num_ref|SQL[$sql]</b></span>";


$i=0;
$color="";
$talla="";


     
 
$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;



	$_SESSION['num_fac']=$num_fac;
	$_SESSION['nit_pro']=$nit_pro;
		
js_location("mod_fac_com.php");	


}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}
 

}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once("HEADER.php"); ?>
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/style.css">
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/prism.css">
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/chosen.css">
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<script src="JS/jquery-2.1.1.js"></script>
<style>


@media (max-width: 576px) { 
body{
transform: scale(0.8);
transform-origin: top left;
-moz-transform: scale(0.8);
-moz-transform-origin: top left;
}
}

/* Medium devices (tablets, 768px and up)*/
@media (max-width: 768px) {

body{
transform: scale(0.8);
transform-origin: top left;
-moz-transform: scale(0.8);
-moz-transform-origin: top left;
}

 }
 
 @media (max-width: 992px) {

body{
transform: scale(0.9);
transform-origin: top left;
-moz-transform: scale(0.9);
-moz-transform-origin: top left;
}

 }

/*Extra large devices (large desktops, 1200px and up)*/
@media (min-width: 1200px) {

 


 }
body{
transform: scale(0.8);
transform-origin: top left;
-moz-transform: scale(0.8);
-moz-transform-origin: top left;
}
</style>
</head>
<body>
<div class="container ">
			<!-- Push Wrapper -->
			<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
            <?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">

<form action="fac_compra.php" name="form_fac" method="post" id="form_fac" class="uk-form" autocomplete="off">
<input type="text" style="visibility: hidden" name="boton" id="boton" value="" >
<?php
//echo "TECNICOS:".tecOpt($sel="none");
?>
<div class="loader"><img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" /></div>
<div id="fac_compra">
 
  <table align="center" cellspacing="0" cellpadding="0" class=" round_table_gray" id="tab_compra">
    <tr>
    
      <td colspan="15">
          <table frame="box" rules="rows" width="100%">
      <tr>
      <td width="450px"> <div align="center">
<img src="<?php echo $url_LOGO_B ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>">
</div></td>
      <td align="left" colspan="">
<?php  echo $PUBLICIDAD  ?>

 </td>
 <td align="center" style="font-size:18px; "><B>FACTURA DE COMPRA</B><br> 
 <span style="color:#F00" id="serialFac"># <?php echo serial_fac_com($conex) ?></span>
 
 <span style="color:#F00" id="serialTras"># <?php echo serial_fac_tras($conex) ?></span>
 
 </td>

 </tr>
 </table>
 
 </td>
    </tr>

    <tr>
    
      <td colspan="8">
      <table frame="box" rules="rows" width="100%">
      <tr>       
           <td colspan=""  class="uk-text-large">Fecha:</td>
           <td colspan="" >
           <input id="fecha2" type="date" value="<?php /*echo $CurrentDate_form;*/ ?>" name="fecha" onClick="//popUpCalendar(this,form_fac.fecha2,'yyyy-mm-dd')"  style=""/>
           </td>
           <td colspan="" align="left" class="uk-text-large uk-text-bold"  valign="bottom">
                      <h1 class="uk-text-danger uk-text-bold">FACTURA</h1></td>
           <td>
           <input name="num_fac" type="text" id="num_fac" value="" onChange="validar_2c($('#num_fac').val(),$('#nit').val(),'fac_com','num_fac_com','nit_pro',$('#resp'));" onBlur="//nan($(this))" class="  uk-form-danger uk-form-large" style="width:200px; font-size:28px;"/>
       
            </td>
            
            
             <td  style="font-size:18px">Tipo Facturaci&oacute;n</td>
            <td  align="left">
            <select name="tipo_fac"  id="tipo_fac" onChange="change_tc($(this),$('#num_fac'));cambioTFC($(this),$('#tab_compra'));" style="width:150px;">
            <option value="" ></option>
            <option value="Compra" selected >Compra</option>
            <?php
            if($MODULES["modulo_planes_internet"]==1){echo '<option value="Retorno Herramientas" >Retorno Herramientas</option>';}
			?>
            <option value="Remision" >Remision</option>
            <option value="Traslado" >TRASLADO</option>
            <option value="Inventario Inicial" >Inventario Inicial</option>
            <option value="Ajuste Seccion" >Ajuste Seccion</option>
            <?php
                    if($habilitaCorteInventario) {
                      echo '<option value="Corte Inventario" >Corte Inventario</option>';
                    }
                    ?>
            </select>
            </td>
             
            <td width="" valign="bottom">
            
         <div id="resp" style="visibility:hidden; color: #F00; width:180px;"><img alt="" src="Imagenes/delete.png" width="20" height="20" />Este n&uacute;mero ya existe</div>
            </td>       
            
            
            
            
           
      
          </tr>
     <tr>
                <td colspan="" class="uk-text-large">
                Fecha Vencimiento:
                </td>
                <td colspan="">
                <input  id="fechaVen" type="date" value="<?php /*echo $AfterMonthDate_form;*/ ?>" name="fechaVen"  class="save_fc" style="width:150px;" />
                </td>
                 <td>Costos y Pvp: </td>
             <td>
             <input type="hidden" value="" name="CNC" id="CNC" onChange="useDB($(this));">
             <!--<select name="CNC" id="CNC" onChange="useDB($(this));">
<option value="1" >Usar</option>
<option value="0" selected>NO usar</option>
</select>-->
<select name="tipo_op" id="tipo_op" onChange="">
<option value=""  >CALCULAR</option>
<option value="ganancia" selected >Ganancia</option>
<option value="costo" >Costo</option>
<option value="pvp" >PVP</option>
</select>
             </td>
             
             <td colspan="" style="font-size:18px; visibility:hidden">Promediar Costos:</td>
          
            <td  colspan="1" style="visibility:hidden">
            <select name="confirma" >
            <option value="" ></option>
            <option value="Si" >Si</option>
            <option value="No" selected>No</option>
            </select>
            </td>
                </tr>
          
          <tr>
            <td>Proveedor:</td>
        
          
            <td>
            <input name="provedor" type="text" id="provedor" value="" style="width:100px"/>
            </td>
            <td colspan="">
            <select data-placeholder="Escriba nombre proveedor" class="chosen-select " name="n_pro" id="n_pro" onChange="nom_pro(this);validar_2c($('#num_fac').val(),$('#nit').val(),'fac_com','num_fac_com','nit_pro',$('#resp'));">
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
           
         
            <td>NIT:</td>
            <td>
            <input style="width:100px;" name="nit" type="text" id="nit" value="" onChange="pro(this.value);" />
            </td>
            <td align="">Ciudad:</td>
            <td colspan=""><input style="width:100px" name="ciudad" type="text" id="ciudad" value="" /></td>
          
          </tr>
          <tr>
            <td >Direcci&oacute;n:</td>
            <td   colspan=""><input style="width:100px" name="dir" type="text" value="" id="dir" /></td>
            <td >Tel.:<input style="width:80px" name="tel"  type="text" value="" id="tel" /></td>
         
            <td colspan="">Fax:</td>
            <td align=""><input style="width:100px" name="fax" type="text" id="fax" value="" onChange="//javascript:valida_doc('cliente',this.value);"/></td>
               <td colspan="" align="left">&nbsp;&nbsp;&nbsp;Mail:</td>
            <td colspan=""><input style="width:100px" name="mail" type="text" id="mail" value=""> </td>

           
          </tr>


          
        </table>
      </td>
    </tr>
    
    
    
    
    
<tr>
<td colspan="8">
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
<td colspan="9" align="center">



<button id="addplus" class=" uk-button uk-button-primary uk-button-large uk-width-1-1 " onClick="subir($('#boton'),'genera',document.forms['form_fac'])" type="button"><i class="uk-icon-plus-circle uk-icon-large"></i>&nbsp;GUARDAR</button>
</td>
</tr>
<!--
<tr>
<td colspan="9" align="center"><?php if($codSuc==1){ ?><a href="#login-box" class="login-window">Registrar Producto Nuevo</a><?php } ?></td>
</tr>
-->
 <tr id="desc">
      <td colspan="3" rowspan="10" align="left" width="700px" > 
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
      <th  align="center" colspan="">Dcto: <input placeholder="%" id="R_FTE_PER" type="text"  value="" name="R_FTE_PER"  style="width:50px" onKeyUp="//calc_per($(this),$('#SUB'),$('#DESCUENTO2'));"/></th>
      <td align="right"><input name="DESCUENTO2" id="DESCUENTO2"   type="text" value="0" maxlength="2" onKeyUp="tot();"/></td>
    </tr>
    <tr>
      <th  align="center" colspan="">VALOR FLETES(sin IVA): </th>
      <td align="right"><input name="FLETE" id="FLETE"   type="text" value="0"  onKeyUp="puntoa($(this));tot();" onBlur="nan($(this))"></td>
    </tr>
    <tr>
      <th  align="center" colspan="">I.V.A TOTAL: </th>
      <td align="right"><input name="IVA" id="IVA"   type="text" value=""  readonly/></td>
    </tr>
    <tr>
      <th  align="center" colspan="">TOTAL:</th>
      <td colspan="" align="right"><input id="TOTAL" type="text"  name="TOTAL"  readonly/></td>
    </tr>
    <tr>
          <td  align="right" colspan="">R. FTE:<input placeholder="%" id="R_FTE_PER" type="text"  value="" name="R_FTE_PER"  style="width:50px" onKeyUp="//calc_per($(this),$('#SUB'),$('#R_FTE'));"/></td>
          <td colspan="" align="right"><input id="R_FTE" type="text"  value="0" name="R_FTE" />
          </td>
          </tr>
          
          <tr>
          <td  align="right" colspan="">R. IVA:<input placeholder="%" id="R_IVA_PER" type="text"  value="" name="R_IVA_PER"  style="width:50px" onKeyUp="//calc_per($(this),$('#IVA'),$('#R_IVA'));"/></td>
          <td colspan="" align="right"><input id="R_IVA" type="text"  value="0" name="R_IVA" />
          </td>
          </tr>
          
          <tr>
          <td  align="right" colspan="">R. ICA:<input placeholder="%" id="R_ICA_PER" type="text"  value="" name="R_ICA_PER"  style="width:50px" onKeyUp="//calc_per($(this),$('#SUB'),$('#R_ICA'));"/></td>
          <td colspan="" align="right"><input id="R_ICA" type="text"  value="0" name="R_ICA" />
          </td>
          </tr>
           <tr>
      <th  align="center" colspan="">VALOR A PAGAR:</th>
      <td colspan="" align="right"><input id="TOTAL_PAGAR" type="text"  name="TOTAL_PAGAR"  readonly/></td>
    </tr>
  <tr>
<td colspan="9">
<table align="center">
<tr>
<td colspan="" align="center"><input style="visibility:hidden" type="hidden" value="1" name="num_art" id="num_art"></td><td></td>
</tr>
  <tr valign="middle" id="btn">

  
<td>
<button type="button"  name="botonG" id="botonSave"   onClick="subir($('#boton'),'genera',document.forms['form_fac'])" class=" uk-button uk-button-large"><i class=" uk-icon-floppy-o"></i>Guardar</button>
</td>
<td>


<button type="button"  id="btn2" onMouseDown="javascript:location.assign('compras.php');" class="uk-button uk-button-large"><i class=" uk-icon-history"></i>Volver</button>
</td>
</tr>
  </table>

</td>
</tr>
<tr>
<td colspan="" align="center"></td>
</tr>    


  </table>
</div>  

<div id="Qresp">

</div>
  <input type="hidden" name="num_ref" value="" id="num_ref" />
  <input type="hidden" name="exi_ref" value="" id="exi_ref" />
    <!--<input type="hidden" name="tipo_op" value="pvp" id="tipo_op" />-->
  
  <input type="hidden" name="verify" id="verify" value="">
  <input type="hidden" value="" name="htmlPag" id="HTML_Pag">
</form>
     </div>
     </div>
<?php require_once("js_global_vars.php"); ?>     
<?php require_once("FOOTER2.php"); ?>

<?php require_once("lib_compras.php"); ?>
<?php require_once("keyFunc_fac_com.php"); ?>

<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>

<script language="javascript1.5" src="JS/fac_com.js?<?php echo "$LAST_VER" ?>"></script>
<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/chosen.jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/docsupport/prism.js"></script>


<script language="javascript1.5" >
$('#serialTras').hide();
$('#loader').hide();

</script>
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