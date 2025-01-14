<?php
/*
-- RELATED
save_fac_ven.php // INSERT
*/
include_once('Conexxx.php');
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"fac_crea")){header("location: centro.php");}

cajas($horaCierre,$horaApertura,$hora,$conex,$codSuc);

$hide=!empty($tokenDianOperaciones)?'visibility:hidden':'';
$hide='';
$MesaID="";
if($MODULES["mesas_pedidos"]==1){
$MesaID=r("id_mesa");
}
$cotiza_a_fac=r("co");
$nf=r("nf");
$pre=r("prefijo");
$idCliente=r("idCliente");
$FechaI=r("FechaI");
$FechaF=r("FechaF");

$reFacturarPOS = r('reFacturar');
$readOnlyResol = '';
if($reFacturarPOS){
	$tipo_fac_default="PAPEL";
	$readOnlyResol = 'readonly';
}

$filtroA="";$filtroB="";$filtroFecha="";$filtroFacturaSeleccionada="";

$fuenteFac=r("fuente");//vals:  cotiza
if($fuenteFac=="cotiza"){$cotiza_a_fac=1;}

$filtro_remi_coti=" AND tipo_fac='remision'  ";
if(!empty($nf) && !empty($pre))$filtroA=" AND (num_fac_ven='$nf' AND prefijo='$pre')";
if(!empty($nf) && !empty($pre))$filtroFacturaSeleccionada=" AND (a.num_fac_ven='$nf' AND a.prefijo='$pre')";
if($cotiza_a_fac==1 || $fuenteFac=="cotiza")$filtro_remi_coti=" AND tipo_fac='cotizacion' $filtroFacturaSeleccionada";
if(!empty($idCliente)){$filtroB=" AND (id_cli='$idCliente' OR nom_cli='$idCliente' ) AND anulado!='ANULADO' AND anulado!='FACTURADA' ";/*$FLUJO_INV=-1;*/}
if(!empty($FechaI) && !empty($FechaF))$filtroFecha=" AND (date(fecha)>='$FechaI' AND date(fecha)<='$FechaF')";



$n_r=0;
$n_s=0;
$hh=0;
$cod_su=0;
$TC=tasa_cambio();
if(isset($_SESSION['cod_su']))$cod_su=$_SESSION['cod_su'];

if(isset($_REQUEST['exi_ref']))$n_r=$_REQUEST['exi_ref'];

if(isset($_REQUEST['exi_serv']))$n_s=$_REQUEST['exi_serv'];
$num_fac="";

$closeREMIS="UPDATE fac_remi SET anulado='FACTURADA' WHERE (id_cli='$idCliente' OR nom_cli='$idCliente' ) AND anulado!='ANULADO' $filtroFecha $filtroA";

$RESP=r("resp");
if(isset($_SESSION['n_fac_ven'])){
if($RESP=="imp"){
	$num_fac=s('n_fac_ven');
	$PRE=s('prefijo');
	$tipoDoc=s('TIPDOC');
	//imp_fac($num_fac,$PRE,"Imprimir");
	imp_fac($num_fac,$PRE,"Imprimir",1,"no",0,$tipoDoc);
}else if($RESP=="mod"){
	$num_fac=s('n_fac_ven');
	$PRE=s('prefijo');
	js_location("mod_fac_ven.php?num_fac_venta=$num_fac&pre=$PRE");
	}
}
?>

<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />

<?php include_once("HEADER.php"); ?>

<link href="JS/fac_ven.css?<?php  echo "$LAST_VER"; ?>" rel="stylesheet" type="text/css" />
<?php include_once("js_global_vars.php"); ?>	
 
<script src="JS/jquery-2.1.1.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>

<script language="javascript1.5" type="text/javascript" src="JS/fac_ven.js?<?php  echo "$LAST_VER"; ?>" ></script>
<?php include_once("chosen_pack.php"); ?>

<style>
table td, table th {
    padding: 2px 2px;
    border-bottom: 0px  ;
}
/* The sticky class is added to the header with JS when it reaches its scroll position */
.sticky {
  position: fixed;
  top: 0;
  width: 100%
}

/* Add some top padding to the page content to prevent sudden quick movement (as the header gets a new position at the top of the page (position:fixed and top:0) */
.sticky + .container {
  padding-top: 102px;
}

</style> 

</head>
<body>
<div class="container ">
			<!-- Push Wrapper -->
			<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
			<?php include_once("boton_menu.php"); ?>


<div class="uk-width-1-1 uk-container-center">
<?php
//echo "usar_posFac $usar_posFac";
?>
			<!-- Lado izquierdo del Nav -->
		<nav class="uk-navbar uk-width-9-10 uk-container-center">

		<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-flip" >  
		
        		<?php 
				 
				if(($rolLv==$Adminlvl || val_secc($id_Usu,"dev_ventas")) ){
				?>
				<li class="ss-navbar-center"><a href="#"  onClick="print_pop('fac_dev_ven.php','EGRESO',950,850)"><i class="uk-icon-minus-circle <?php echo $uikitIconSize ?>"></i>&nbsp;DEVOLUCI&Oacute;N</a></li>
				<?php
				}
 
				?>
                
         
                <?php 
				if($MODULES["CARTERA"]==1){
				if(($rolLv==$Adminlvl || val_secc($id_Usu,"crea_recibo_caja")) ){
				?>
				<li class="ss-navbar-center"><a href="#"  onClick="print_pop('comp_ingreso_vario.php','EGRESO',600,650)"><i class="uk-icon-credit-card <?php echo $uikitIconSize ?>"></i>&nbsp;ABONO</a></li>
				<?php
				}
				}
				?>
                
        
				<?php 
				if($MODULES["GASTOS"]==1){
				if(($rolLv==$Adminlvl || val_secc($id_Usu,"crear_comp_egreso")) ){
				?>
				<li class="ss-navbar-center"><a href="#"  onClick="print_pop('comp_egreso.php','EGRESO',600,650)"><i class="uk-icon-bank <?php echo $uikitIconSize ?>"></i>&nbsp;GASTOS</a></li>
				<?php
				}
				}
				?>

				<?php 
				if($MODULES["ANTICIPOS"]==1){
				if(($rolLv==$Adminlvl || val_secc($id_Usu,"crear_anticipo")) ){
				?>
				<li class="ss-navbar-center"><a href="#"  onClick="print_pop('agregar_exp.php','EGRESO',800,600)"><i class="uk-icon-dollar <?php echo $uikitIconSize ?>"></i>&nbsp;ANTICIPO</a></li>
				<?php
				}
				}
				?>
				
					<li class="<?php if($usar_bsf!=1) echo "uk-hidden" ; ?>"><a href="#tasa_cambio" data-uk-modal><i class="uk-icon-money <?php echo $uikitIconSize ?>"></i>&nbsp;TASA CAMBIO </a></li>
				
				 
				
					<li><a href="#OPC_FAC" data-uk-modal ><i class="uk-icon-gear  uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;OPCIONES</a></li>
			</ul>

		</nav>
<?php //echo "codPOS: $codContadoSuc, resolPOS: $ResolContado, fePOS: $FechaResolContado, rangoPOS: $RangoContado, COD_SU: $codSuc<br> $codCreditoSuc-$ResolCredito-$FechaResolCredito-$RangoCredito";  
//if($FLUJO_INV==1 && (($rolLv!=$Adminlvl && !val_secc($id_Usu,"fac_mod")) ))echo "PERMITE CERRAR";
//else echo"NO PERMITE CERRAR $ventas_rapidas ".val_secc($id_Usu,"fac_mod") ;

?>
 


<?php
include("CommonVarsInclude/FeDatosClienteDefault.php");
	
 



include("load_remi_cli.php");
//$tipoPago=$row['tipo_venta'];
 ?>

<form action="fac_venta.php" name="form_fac" method="post" id="form-fac" autocomplete="off" class="uk-form uk-form-stacked">
<?php //echo"ip: $IP_CLIENTE ";
$val=($usar_posFac==0 || val_secc($id_Usu,"caja_centro"));
//echo "posFac: $usar_posFac IF: $val".(val_secc($id_Usu,"caja_centro"));
?>
<input type="hidden" value="<?php echo"$hoy / $IP_CLIENTE";?>" name="hashFac" id="hashFac">
<div class="  uk-width-1-1" id="factura_venta">
<table id="fac_venta_table2" border="0" align="center" cellspacing="0" cellpadding="0" class="round_table_white uk-form-small uk-table uk-table-striped" style="color:#000" >

<tr>
<td colspan="">
<?php include("fac_venta_cliente.php");?>
</td>
</tr>


<tr class="">
<td colspan=""><!--max-height: 200px;height:150px; max-height: 320px;-->
<div class="uk-overflow-container" style=" <?php if($MODULES["SERVICIOS"]==1){echo "" ;}else {echo "";}  ?> border-style: double">
<table id="articulos" width="100%" cellspacing="0" cellpadding="0" border="0px" style="font-size:12px;">
              <tr style="background-color: #000; color:#FFF">
                <td><div align="center"><strong>Referencia</strong></div></td>
                <td><div align="center"><strong>Cod. Barras</strong></div></td>
                 <?php if($usar_serial==1){ ?>
                <td><div align="center"><strong>Serial</strong></div></td>
                 <?php } ?>
                 
                  <?php if($MODULES["COD_GARANTIA"]==1){ ?>
                <td><div align="center"><strong>Garantia</strong></div></td>
                 <?php } ?>
                 
                <td><div align="center"><strong>Producto</strong></div></td>
                
                 <?php if($usar_ubica==1){ ?>
                <td><div align="center"><strong>Ubicaci&oacute;n</strong></div></td>
                 <?php } ?>
                <?php if($usar_fecha_vencimiento==1){ ?>
                <td><div align="center"><strong>Fecha Vencimiento</strong></div></td>
                <?php } ?>
                
               <?php if($usar_color==1){ ?>
                <td><div align="center"><strong>Color</strong></div></td>
                <?php } ?>
                
                  <?php if($usar_datos_motos==1){ ?>
                <td><div align="center" ><strong># Motor</strong></div></td>
                
                <?php }?>
                
                <?php if($usar_talla==1){ ?>
                <td><div align="center"><strong>Talla</strong></div></td>
               <?php } ?>
               
                <td><div align="center"><strong>I.V.A</strong></div></td>
                <td height="28"><div align="center"><strong>Cant.</strong></div></td>
                <?php if($usar_fracciones_unidades==1){ ?>
                <td height="28"><div align="center"><strong>Fracci&oacute;n</strong></div></td>
                <?php } ?>
                <td><div align="center"><strong>Dcto</strong></div></td>
                <td><div align="center"><strong>Precio</strong></div></td>
                <td colspan="2"><div align="center"><strong>Subtotal</strong></div></td>
              </tr>
 			  
<?php $src="fac";
      include("load_remi_arts.php");?>  
          
</table>
</div>
</td>
</tr>

<?php if($MODULES["SERVICIOS"]==1){ ?>
<tr>
<td colspan=""><!-- max-height: 200px;height:150px; border-style: double-->
                  <div class="uk-overflow-container" >
<table id="servicios" width="100%" cellspacing="0" cellpadding="0" style="font-size:12px;">
              <tr style="background-color: #000; color:#FFF">
              <?php if($MODULES["modulo_planes_internet"]!=1){?>
              <th><i class="uk-icon uk-icon-medium  uk-icon-wrench"></i>&nbsp;ID</th>
              <th>Codigo</th>
              <th>Servicio</th>
              <th>Nota</th>
              <th>IVA</th>
              <th>PVP</th>
              <th>Total</th>
              <th colspan="3">T&eacute;cnico</th>
              <?php }
			  else {
			  ?>
              
              
              <?php ?>
              <th><i class="uk-icon uk-icon-medium  uk-icon-wrench"></i>&nbsp;ID</th>
              <th>Codigo</th>
              <th>Servicio</th>
              <th>Ancho Banda</th>
              <th>Tipo Cliente</th>
              <th>Estrato</th>
              <th>Precio PLAN</th>
              <th>IVA</th>
              <th>Dcto</th>
              <th>PVP Afiliaci&oacute;n/Plan</th>
              <th>Total</th>
        
              <?php }?>
              </tr>      
<?php
if(!empty($filtroB)){
$saveFunct="";
$eliFunct="eli";
$sql="SELECT a.id_tec,a.serv,a.nota,a.iva,a.cod_serv,SUM(a.pvp) as pvp,a.id_serv,a.anchobanda,a.estrato,a.tipo_cliente 
FROM serv_fac_remi a INNER JOIN fac_remi b ON a.num_fac_ven=b.num_fac_ven 
WHERE cod_su=$codSuc $filtroFacturaSeleccionada $filtroB $filtroFecha GROUP BY id_serv";
//echo "$sql";
$rs=$linkPDO->query($sql);
include("fac_ven_load_serv.php");
}
$n_ref+=$i;
?>
 </table>
 </div>
 </td>
 </tr>
 <?php }/////// FIN SERVICIOS ?>
 


        
        
        
<tr>
<td colspan="">      
<?php include("fac_venta_totales.php");?>
</td>
</tr>


</table>
</td>
</tr>

         
</table>



<div align="center" id="FacFooter" class="uk-block-secondary">
<table align=" " frame="box" style="border-width:thick; color:#FFF; width:700px;" cellspacing="0" cellpadding="0" width="100%"   class="uk-block-secondary">
<tr valign="middle" style=" font-size:16px; font-weight:bold;">
<td colspan="">
REF: 
<input type="text" name="exi_ref" value="<?php echo "$n_ref"; ?>" id="exi_ref" style="font-size:16px; font-weight:bold; width:50px;"  readonly/>
</TD>
 <td colspan="">CANT: 
<input type="text" name="TOT_CANT" value="<?php echo "$n_cant"; ?>" id="TOT_CANT" style="font-size:16px; font-weight:bold; width:50px;"  readonly/>
</TD>
<td style="width:100%;"></td>
<td colspan="" align="right">
<?php if($MODULES["SERVICIOS"]==1){ ?>
SERVICIOS <?php  echo selc_serv("serv($(this),'','eli')"); ?>
<?php } ?>
</td>


<?php if(($rolLv==$Adminlvl || val_secc($id_Usu,"adm_art")) && $codSuc>0){ ?>
<td>
<div class="uk-form-icon">
<i class="uk-icon-database uk-icon-small uk-icon-justify"></i>
<input type="text" name="cod" value="" id="cod" onKeyPress="codx($(this),'add');" onKeyUp="mover($(this),$('#entrega'),$(this),0);codx($(this),'add');//mover($(this),$('#entrega'),$('#cli'),1);"  class="uk-form-large  " placeholder="BUSCAR PRODUCTO" style="border-width:4px;border-color:rgb(201, 0, 0);"/>
</div>
<input type="hidden" value="" id="feVen" name="feVen">
<input type="hidden" value="" id="Ref" name="Ref">
</td>
<td>
 <img style="cursor:pointer" data-uk-tooltip title="Ver todos los Productos" onMouseUp="busq($('#cod'));" src="Imagenes/search128x128.png" width="34" height="31" /><div id="Qresp"></div>
 </td>
<?php }?>

<td>
 <input id="butt_gfv" type="button" value="Cerrar Factura" name="boton" onClick="gfv($(this),'genera',document.forms['form_fac']);" class=" uk-button uk-button-success uk-button-large  " />
</td>
<td>
 <input id="butt_gfv" type="button" value="PAGO" name="boton" onClick="$('#entrega').focus();" class=" uk-button uk-button-primary uk-button-large  " />
</td>
</tr>
<?php 
    $formsElements = new lib_CommonFormsElements();
    $botonesCategorias = $formsElements->botonesCategoriasMesas();
    echo $botonesCategorias;
?>
</table>
            
</div>

</div>

<div class="loader"> <img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" /> </div>
        <input type="hidden" name="id_mesa" value="<?php echo $MesaID ?>" id="id_mesa" />
      <input type="hidden" name="boton" value="" id="genera" />
      
      <input type="hidden" name="num_ref" value="<?php echo $n_ref ?>" id="num_ref" />
       <input type="hidden" name="idCliente" value="<?php echo $idCliente ?>" id="idCliente" />
	   <input type="hidden" name="reFacturarPOS" value="<?php echo $reFacturarPOS ?>" id="reFacturarPOS" />
        <input type="hidden" name="co" value="<?php echo $cotiza_a_fac ?>" id="co" />
       <input type="hidden" name="FechaI" value="<?php echo $FechaI ?>" id="FechaI" />
       <input type="hidden" name="FechaF" value="<?php echo $FechaF ?>" id="FechaF" />
        <input type="hidden" name="nf" value="<?php echo $nf ?>" id="nf" />
         <input type="hidden" name="prefijo" value="<?php echo $pre ?>" id="prefijo" />
      <input type="hidden" name="num_serv" value="0" id="num_serv" />
      <input type="hidden" name="exi_serv" value="0" id="exi_serv" />
      <input type="hidden" name="remision" value="0" id="remision" />
      <input type="hidden" name="bsF_flag" value="0" id="bsF_flag" />
      <input type="hidden" value="" name="html" id="pagHTML">
      
      
<input type="hidden" name="tope_credito" value="0" id="tope_credito" />
<input type="hidden" name="total_credito" value="0" id="total_credito" />
      
      
      <input type="hidden" name="marca_moto" value="" id="marca_moto" />
    </form>

</div>
</div>
<div id="tasa_cambio" class="uk-modal">
<div class="uk-modal-dialog">
<a class="uk-modal-close uk-close"></a>
<h1 style="color:#000">TASA CAMBIO</h1>
<table cellspacing="0" cellpadding="0">
<tr>
<td>
<input type="text" value="<?php echo $TC ?>" name="tasaCam" id="tasaCam">
</td>
</tr>
<tr>
<td colspan="2" align="center"><input type="button" value="Guardar" name="filtro"  class="uk-button" onClick="save_tc()"></td>
</tr>
</table>
</div>
</div>
<!----------------------------FORM CARRO ----------------------------------------->
<div id="OT_VEHI" class="uk-modal">
<div class="uk-modal-dialog">
<a class="uk-modal-close uk-close"></a>
<h1 style="color:#000">REGISTRO VEH&Iacute;CULO</h1>
<table cellspacing="0" cellpadding="0">
<tr>
<td>
<input type="text" value="" name="placa_ve" id="placa_ve" placeholder="PLACA VEH&Iacute;CULO" class="VEHI" onChange="">
</td>

<td>
<input type="text" value="" name="modelo_ve" id="modelo_ve" placeholder="MODELO VEH&Iacute;CULO" class="VEHI">
</td>
<td>
<input type="text" value="" name="color_ve" id="color_ve" placeholder="COLOR VEH&Iacute;CULO" class="VEHI">
</td>
</tr>
<tr>
<td>
<input type="text" value="" name="id_prop" id="id_prop" placeholder="C.C. PROPIETARIO" class="VEHI">
</td>
<td>
<input type="text" value="" name="nom_prop" id="nom_prop" placeholder="NOMBRE PROPIETARIO" class="VEHI">
</td>
<td>
<input type="text" value="" name="tel_prop" id="tel_prop" placeholder="TEL&Eacute;FONO" class="VEHI">
</td>
</tr>
<tr>
<td colspan="3" align="center"><input type="button" value="Guardar" name="filtro"  class="uk-button uk-button-success" onClick="save_vehi()"></td>
</tr>
</table>
</div>
</div>
<?php include_once("FOOTER2.php"); ?>
<?php include_once("keyFunc_fac_ven.php"); ?>
<?php include_once("autoCompletePack.php"); ?>	
<script language="javascript1.5" type="text/javascript" src="JS/num_letras.js"></script>
<script type="text/javascript" language="javascript1.5" src="JS/jQuery_throttle.js"></script>
<script src="node_modules/dom-to-image-more/dist/dom-to-image-more.min.js"></script>
<script language="javascript1.5" type="text/javascript">
$('#loader').hide();

$(":input").focus(function() { this.select(); });
	

$('#papel').hide();
$('#cre_ant').hide();
$('#papel_ant').hide();
$('#val_frt').hide();
$('#log_serv').hide();
$('#revision').hide();
$('#alas').hide();
$('#hh').hide();
$('#precio_servA').hide();
	
var dcto_remi=0;
var HH=<?php echo $hh ?>;
var iva_serv=0.16;
var alas=<?php echo $alasSuc ?>;
var usaFechaVen=<?php echo $usar_fecha_vencimiento ?>;
var index = -1;
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




window.onbeforeunload = confirmExit;
    function confirmExit() {
		
        if(cont!=0 &&flag_gfv==0)return "AUN NO HA CERRADO ESTA FACTURA, DESEA SALIR SIN GUARDAR??";
    }

function cambia_resol($sel,$pos,$com,$papel,$com_ant,$papel_ant,$cre)
{
	console.log('selec: '+$sel.val());
	//alert($sel.val());
	if($sel.val()=='POS')
	{
		//alert($pos.prop('id'));
		loadResolFac($pos.prop('id'),$sel.val());
		
		$cre.hide();
		$pos.show();
		$com.hide();
		$papel.hide();
		$com_ant.hide();
		$papel_ant.hide();
	}
	else if($sel.val()=='COM')
	{
		loadResolFac($com.prop('id'),$sel.val());
		$cre.hide();
		$pos.hide();
		$com_ant.hide();
		$papel_ant.hide();
		$com.show();
		$papel.hide();
	}
	else if($sel.val()=='PAPEL')
	{
		console.log('papel.prop id '+$papel.prop('id')+'');
		loadResolFac($papel.prop('id'),$sel.val());
		$cre.hide();
		$pos.hide();
		$com.hide();
		$com_ant.hide();
		$papel_ant.hide();
		
		$papel.show();
	}
	else if($sel.val()=='COM_ANT')
	{
		loadResolFac($com_ant.prop('id'),$sel.val());
		$pos.hide();
		$com.hide();
		$papel.hide();
		$papel_ant.hide();
		$cre.hide();
		$com_ant.show();
	}
	else if($sel.val()=='PAPEL_ANT')
	{
		loadResolFac($papel_ant.prop('id'),$sel.val());
		$pos.hide();
		$com.hide();
		$papel.hide();
		$com_ant.hide();
		$cre.hide();
		
		$papel_ant.show();
	}
	else if($sel.val()=='CRE_ANT')
	{
		loadResolFac($cre.prop('id'),$sel.val());
		$pos.hide();
		$com.hide();
		$papel.hide();
		$com_ant.hide();		
		$papel_ant.hide();
		
		$cre.show();
		
		
	}
};


function resol_warn()
{
	var data='';
	ajax_b('ajax/WARNINGS/resol_out_date.php',data,function(resp){
		
		var msg1='<div class="uk-alert-close">MIRAR DE INMEDIATO!!!</div>',msg2='Resoluci&oacute;n de Facturas a punto de Caducar',msg3=resp;
		
		if(resp!=0)open_pop(msg1,msg2,msg3)
		
		});
	
};
function disable_resol_warn()
{
	var data='';
	ajax_b('ajax/WARNINGS/disable_resol_warn.php',data,function(resp){
		hide_pop($('#modal'));
	});
};

function moveFooter() {
   // Get the header
var header = document.getElementById("FacFooter");
var elementPosition = $('#FacFooter').offset();

$(window).scroll(function(){
        if($(window).scrollTop() < elementPosition.top){
            $('#FacFooter').css({'position':'fixed','bottom':'0'});
            //$('.bg1 h2').css({'margin-top':'232px'});
        } else {
            $('#FacFooter').css({'position':'static'});
            //$('.bg1 h2').css({'margin-top':'100px'});
        }    
});

};

//38 up, 40down
//<source src="notify.ogg" type="audio/ogg">
$(document).ready(function() {

	

// When the user scrolls the page, execute moveFooter 
window.onscroll = function() {moveFooter()};



// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position

	tot();
	resol_warn();
$('[data-uk-switcher]').on('show.uk.switcher', function(event, area){
    console.log("Switcher switched to ", area);
});

$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});
envia($('#entrega'));
envia($('#entrega2'));
envia($('#entrega3'));
move($('#cod'),'entrega','entrega','','');



$('input').on("change",function(){$(this).prop('value',this.value);});
$('textarea').on("change",function(){$(this).html(this.value);});
$('#cod').focus();
$('#tipo_resol').change();


call_autocomplete('ID',$('#cli_lookup'),'ajax/busq_cli.php');


	
	

	cambia_resol($('#tipo_resol'),$('#pos'),$('#com'),$('#papel'),$('#com_ant'),$('#papel_ant'),$('#cre_ant'));
	<?php 
	if($FLUJO_INV==1 || !empty($filtroB)){
	
	if($tipo_fac_default=="COM"){echo "loadResolFac($('#com').prop('id'),'COM');$('#com').show();$('#com_ant').hide();$('#pos').hide();";}
	 else if($tipo_fac_default=="POS"){echo "loadResolFac($('#pos').prop('id'),'POS');$('#com').hide();$('#com_ant').hide();$('#pos').show();";} 
	 else if($tipo_fac_default=="PAPEL"){echo "loadResolFac('papel','PAPEL');$('#com').hide();$('#com_ant').hide();$('#pos').hide();$('#papel').show();";} 
	 	else {echo "loadResolFac($('#com').prop('id'),'COM');$('#com').show();$('#com_ant').hide();$('#pos').hide();";}
		
	}
	else
	{
		echo "$('#com').hide();$('#com_ant').show();$('#pos').hide();";
		
	}
 	?>
	
	
	
	$('#loader').ajaxStart(function(){
		$(this).show();
		$('input[type=button]').prop("disabled","disabled").css("color","red");
		})
	.ajaxSuccess(function(){
		$(this).hide();
		$('input[type=button]').removeAttr("disabled").css("color","black");
		});
	
	//$('#loader').ajaxError(function(){$('input[type=button]').prop("disabled","disabled").css("color","red");$(this).hide();});

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
	
	
	
<?php 
if( !empty($MesaID))
{
	echo "$('#butt_gfv').click();";
	
	}
	
?>
	
});

$("#ced").on("keyup",function(){
	var nitStripTemp = $("#ced").val().replace(/\./g,'');
	nitStripTemp = nitStripTemp.replace(/([^0-9\-])+/g,'');
	$("#ced").val(nitStripTemp);
});


$('#articulos').on("keydown","input,textarea,select", function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		var yMovement = $("#articulos > tbody > tr:eq(1) input:visible, #articulos > tbody > tr:eq(1) select:visible, #articulos > tbody > tr:eq(1) textarea:visible").toArray().length;

        if(key == 39) {
            e.preventDefault();
            var inputs = $(this).parents('#articulos').find(':input:enabled:visible:not("disabled"),textarea,select');

            inputs.eq( inputs.index(this)+ 1 ).focus();
            inputs.eq( inputs.index(this)+ 1 ).select();
        }else if(key == 37) {
            e.preventDefault();
            var inputs = $(this).parents('#articulos').find(':input:enabled:visible:not("disabled"),textarea,select');

            if(inputs.eq( inputs.index(this)- 1 )[0].className.includes("fc_stot")){
                inputs.eq( inputs.index(this)- 2 ).focus();
                inputs.eq( inputs.index(this)- 2 ).select();
            }
            else{
                inputs.eq( inputs.index(this)- 1 ).focus();
                inputs.eq( inputs.index(this)- 1 ).select();
            }
        }else if(key == 38) {
            e.preventDefault();
            var inputs = $(this).parents('#articulos').find(':input:enabled:visible:not("disabled"),textarea,select');

            inputs.eq( inputs.index(this)- yMovement ).focus();
            inputs.eq( inputs.index(this)- yMovement ).select();
        }else if(key == 40) {
            e.preventDefault();
            var inputs = $(this).parents('#articulos').find(':input:enabled:visible:not("disabled"),textarea,select');

            inputs.eq( inputs.index(this)+ yMovement ).focus();
            inputs.eq( inputs.index(this)+ yMovement ).select();
        }
    });

</script> 

</body>
</html>