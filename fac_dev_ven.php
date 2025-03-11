<?php
include_once('Conexxx.php');
$_opt_facVen_dev="FAC_DEV";
$tipo_fac_default="COM";
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"fac_crea")){header("location: centro.php");}
$fuenteFac=r("fuente");//vals:  cotiza
cajas($horaCierre,$horaApertura,$hora,$conex,$codSuc);

$cotiza_a_fac=r("co");
$nf=r("nf");
$pre=r("prefijo");
$idCliente=r("idCliente");
$FechaI=r("FechaI");

$FechaF=r("FechaF");
$filtroA="";$filtroB="";$filtroFecha="";$filtroFacturaSeleccionada="";

$filtro_remi_coti=" AND tipo_fac='remision' ";



if(!empty($nf) && !empty($pre))$filtroA=" AND (num_fac_ven='$nf' AND prefijo='$pre')";

if(!empty($nf) && !empty($pre))$filtroFacturaSeleccionada=" AND (a.num_fac_ven='$nf' AND a.prefijo='$pre')";

if($cotiza_a_fac==1)$filtro_remi_coti=" AND tipo_fac='cotizacion' $filtroFacturaSeleccionada";

if(!empty($idCliente)){$filtroB=" AND (id_cli='$idCliente' OR nom_cli='$idCliente' ) AND anulado!='ANULADO' AND anulado!='FACTURADA' ";/*$FLUJO_INV=-1;*/}
//if($cotiza_a_fac==1){$FLUJO_INV=1;}

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



/* CAMPOS FACUTRACION ELEC.*/
include("CommonVarsInclude/FeDatosClienteDefault.php");

?>

<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />

<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<?php include_once("HEADER.php"); ?>
<?php include_once("js_global_vars.php"); ?>	
 
<script src="JS/jquery-2.1.1.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>
<script language="javascript1.5" type="text/javascript" src="JS/fac_ven.js?<?php  echo "$LAST_VER"; ?>" ></script>
<?php include_once("chosen_pack.php"); ?>
<!--<link rel="stylesheet" type="text/css" href="css/component.css" />
<link href="JS/lightBox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript1.5" src="JS/popcalendar.js"></script>
-->

</head>

<body>
<div class="container ">
			<!-- Push Wrapper -->
			<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
			<?php include_once("boton_menu.php"); ?>


<div class="uk-width-9-10 uk-container-center">
			<!-- Lado izquierdo del Nav -->
		<nav class="uk-navbar">

		<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/favSmart.png" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-flip" >  
		
				<?php 
				if($MODULES["GASTOS"]==1){}
				?>

				<?php 
				if($MODULES["ANTICIPOS"]==1){}
				?>
				
					 
				
				<?php if(($rolLv==$Adminlvl || val_secc($id_Usu,"fac_lista")) && $codSuc>0){?>
				<li><a href="<?php echo "dev_ventas.php" ?>" ><i class="uk-icon-list <?php echo $uikitIconSize ?>"></i>&nbsp;Lista DEVOLUCIONES</a></li>
				<?php } ?>
				<li><a href="#OPC_FAC" data-uk-modal ><i class="uk-icon-gear  uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;OPCIONES</a></li>
					<!--<li><a href="<?php echo thisURL(); ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>-->
			</ul>

		</nav>
<?php //echo "codPOS: $codContadoSuc, resolPOS: $ResolContado, fePOS: $FechaResolContado, rangoPOS: $RangoContado, COD_SU: $codSuc<br> $codCreditoSuc-$ResolCredito-$FechaResolCredito-$RangoCredito";  
//if($FLUJO_INV==1 && (($rolLv!=$Adminlvl && !val_secc($id_Usu,"fac_mod")) ))echo "PERMITE CERRAR";
//else echo"NO PERMITE CERRAR $ventas_rapidas ".val_secc($id_Usu,"fac_mod") ;

?>
 


<?php
$nomCli="";
$idCli="";
$telCli="";
$dirCli="";
$mailCli="";
$ciudadCli="$munSuc";

$vendedor="";	
$tipoPago="";
$tipoCli="";
$pagare="";
$fecha_hora="";

$SUB="";
$DESCUENTO="";
$IVA="";
$TOT="";
$TOT_BSF="";

$val_letras="";
$entrega="";
$cambio="";
$entregaBSF="";

$abon_anti="";	
$num_exp="";

$R_FTE="";
$R_IVA="";
$R_ICA="";

$R_FTE_PER="";
$R_IVA_PER="";
$R_ICA_PER="";
$TOT_PAGAR="";


$nomCli="$PUBLICO_GENERAL";
$idCli=$NIT_PUBLICO_GENERAL;
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
	
	$coddoc="";
	$paicli="";
	$depcli="";
	$loccli="";
	$nomcon="";
	$regtri="";
	$razsoc="";
	
	$TIPDOC="03";
	$FECVEN="";
	$MONEDA="";
	$TIPCRU="R";
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



include("load_remi_cli.php");
//$tipoPago=$row['tipo_venta'];
 ?>

 <form action="fac_venta.php" name="form_fac"  method="post" id="form-fac" autocomplete="off" class="uk-form uk-form-stacked">
      <div class="loader"> <img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" /> </div>
     <div class="uk-overflow-container uk-width-1-1">
     
      <table border="0" align="center" cellspacing="0" cellpadding="0" class="round_table_white  " style="color:#000" >
        <tr>
          <td colspan="8">
          <table cellspacing="0" cellpadding="0">
              <tr class="">
                <td colspan="2" style="font-size:16px;font-weight:bold;" id="fp_container">
                <label class="uk-form-label" for="form_pago">Forma de Pago</label>
                  <select id="form_pago" name="form_pago" onChange="creco(this.value,'credito','contado');<?php if($MODULES["PVP_CREDITO"]==1) echo "cambiaFP();"; ?>call_tot();"  class="uk-text-primary  uk-text-bold uk-form-success">
                    <option value=""></option>
                    <option value="Contado"  selected>Contado</option>
                    <?php if($rolLv>=$Adminlvl|| val_secc($id_Usu,"vende_credito")){?>
                    <option value="Credito" >Cr&eacute;dito</option>
                    <?php }?>
                   <!-- <option value="Tarjeta Credito" >Tarjeta Credito</option>
                    <option value="Tarjeta Debito" >Tarjeta Debito</option>
                    <option value="Transferencia Bancaria" >Transferencia Bancaria</option>-->
                  </select>
                  </td>
                  <td>Numero doc. referenciado:</td>
<td><input type="text" name="NUMREF" id="NUMREF" class="" placeholder="PREFIJO+NUM FACTURA" /></td>
<td>Motivo devoluci&oacute;n DIAN:</td>
<td><textarea name="MODEDI" id="MODEDI" class=""></textarea></td>
                  
                   
          </tr>
  
              <tr>
                
              </tr>
 
    
    
               
               
               
               
</table>
</td>
</tr>

<tr>
<td colspan="8">
<?php include("fac_venta_cliente.php");?>
</td>
</tr>

<tr>
<td colspan="5">
<table id="articulos" width="100%" cellspacing="0" cellpadding="0">
              <tr style="background-color: #000; color:#FFF">
                <td><div align="center"><strong>Referencia</strong></div></td>
                <td><div align="center"><strong>Cod. Barras</strong></div></td>
                 <?php if($usar_serial==1){ ?>
                <td><div align="center"><strong>Serial</strong></div></td>
                 <?php } ?>
                <td><div align="center"><strong>Producto</strong></div></td>
                 
                <!--<td><div align="center"><strong>Presentaci.</strong></div></td>
                <td><div align="center"><strong>Ubicaci&oacute;n</strong></div></td>-->
                <?php if($usar_fecha_vencimiento==1){ ?>
                <td><div align="center"><strong>Fecha Vencimiento</strong></div></td>
                <?php } ?>
                
               <?php if($usar_color==1){ ?>
                <td><div align="center"><strong>Color</strong></div></td>
                <?php } ?>
                
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
                <td><div align="center"><strong>Subtotal</strong></div></td>
              </tr>
			  
			  
			  
<?php 
$src="fac";
include("load_remi_arts.php");?>            
</table>
            
            
            </td>
        </tr>

<?php if($MODULES["SERVICIOS"]==1){ ?>
<tr class="">
<td colspan="11">
<table id="servicios" width="100%" cellspacing="0" cellpadding="0" >
              <tr style="background-color: #000; color:#FFF">
              <th>ID</th>
              <th>Codigo</th>
              <th>Servicio</th>
              <th>Nota</th>
              <th>IVA</th>
              <th>PVP</th>
              <th>Total</th>
              <th colspan="3">T&eacute;cnico</th>
              </tr>      
<?php
if(!empty($filtroB)){
$saveFunct="";
$eliFunct="eli";
$sql="SELECT a.id_tec,a.serv,a.nota,a.iva,a.cod_serv,SUM(a.pvp) as pvp,a.id_serv FROM serv_fac_remi a INNER JOIN fac_remi b ON a.num_fac_ven=b.num_fac_ven WHERE cod_su=$codSuc $filtroFacturaSeleccionada $filtroB $filtroFecha GROUP BY id_serv";
//echo "$sql";
$rs=$linkPDO->query($sql);
include("fac_ven_load_serv.php");
}
$n_ref+=$i;
?>
 </table>
 </td>
 </tr>
 <?php }/////// FIN SERVICIOS ?>
 
        <tr>
          <td colspan="5">
          
          	  <table align="center" frame="box" style="border-width:thick;" cellspacing="0" cellpadding="0">
              <tr valign="middle" style=" font-size:24px; font-weight:bold;">
                
              <td colspan="" align="right">
              <?php if($MODULES["SERVICIOS"]==1){ ?>
              SERVICIOS <?php  echo selc_serv("serv($(this),'','eli')"); ?>
              <?php } ?>
              </td>
                <th width="200px" colspan="2">PRODUCTO:</th>
                <td>
                <div class="uk-form-icon">
                <i class="uk-icon-database uk-icon-small uk-icon-justify"></i>
                <input type="text" name="cod" value="" id="cod" onKeyPress="codx($(this),'add');" onKeyUp="mover($(this),$('#entrega'),$(this),0);codx($(this),'dev_ven');//mover($(this),$('#entrega'),$('#cli'),1);"  class="uk-form-large"/>
                </div>
                <input type="hidden" value="" id="feVen" name="feVen">
                <input type="hidden" value="" id="Ref" name="Ref">
                </td>
                <td>
                  <img style="cursor:pointer" data-uk-tooltip title="Ver todos los Productos" onMouseUp="busq($('#cod'));" src="Imagenes/search128x128.png" width="34" height="31" /><br />
                  <div id="Qresp"></div>
                  </td>
                <td><input id="butt_gfv" type="button" value="Guardar" name="boton" onClick="gfd(document.forms['form_fac']);" class=" uk-button uk-button-success uk-button-large" /></td>
                <td>&nbsp;</td>
              </tr>
              <?php
			  
			  if($MODULES["SERVICIOS"]==1){}
			  
			  
			  ?>
              <tr class="uk-hidden">
              
              <td colspan="">TOTAL REF:</td><td>
              <input type="text" name="exi_ref" value="<?php echo "$n_ref"; ?>" id="exi_ref" style="font-size:24px; font-weight:bold; width:50px;"  readonly/>
              </TD>
              
            
              
              <td colspan="">TOTAL CANTIDADES:</td><td>
              <input type="text" name="TOT_CANT" value="<?php echo "$n_cant"; ?>" id="TOT_CANT" style="font-size:24px; font-weight:bold; width:50px;"  readonly/>
              </TD>
              
              </tr>
            </table>
            
            </td>
        </tr>
        <tr id="desc" >
          <td colspan="" rowspan="<?php if($MODULES["ANTICIPOS"]==1){echo "14";}else {echo "13";} ?>" align="center" width="100px" ><br />
           
         
            <div align="left">
              <textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="40" style="width:300px"></textarea>
              <br>
<textarea name="nota_fac" id="nota_fac"   cols="40" placeholder="NOTAS" style="width:300px;
-webkit-border-radius:19px;
-moz-border-radius:19px;
border-radius:19px;
border:6px solid rgb(201, 38, 38);"></textarea>
            </div></td>
          <th class="uk-hidden" style="background-color: #000; color:#FFF" width=""colspan="">Base IVA:</th>
          <td align="right" colspan="" class="uk-hidden"><input  id="SUB" type="text" readonly="" value="<?php echo money("$SUB") ?>"   name="sub"/>
            <input type="hidden" name="SUB" value="0" id="SUBH" />
          <input id="EXCENTOS" type="hidden" readonly="" value="0" name="exc" />
            <input readonly name="dcto" id="DESCUENTO" type="hidden" value="" onKeyUp="" />
            </td>
        </tr>
    
    
   			<tr class="uk-hidden">
          <th style="background-color: #000; color:#FFF">Dcto:</th>
          <td align="right"><input name="dcto2" id="DESCUENTO2" type="text" value="<?php echo "$DESCUENTO" ?>" onKeyUp="dctoB();" />
           </td>
        </tr>
    
        <!--
         <input readonly name="dcto" id="DESCUENTO" type="hidden" value="" onKeyUp="" />
           
           
           
            <tr>
          <th style="background-color: #000; color:#FFF">Dcto:</th>
          <td align="right"><input readonly name="dcto" id="DESCUENTO" type="hidden" value="" onKeyUp="" /></td>
        </tr>
          <tr>
          <th style="background-color: #06F; color:#FFF">Dcto :</th>
          <td align="right"><input name="dcto2" id="DESCUENTO2" type="text" value="" onKeyUp="javascript:puntoa($(this));call_tot();" /></td>
        </tr>
          <tr>
          <td  align="center" colspan="">EXCENTOS:</td>
          <td colspan="" align="right"><input id="EXCENTOS" type="text" readonly="" value="0" name="exc" /></td>
        </tr>
        -->
      
        <?php
		if($usar_iva==1){
		?>
        <tr class="uk-hidden">
          <td  align="center" colspan="">I.V.A: </td>
          <td align="right"><input name="iva" readonly="readonly" id="IVA" type="text" value="<?php echo money("$IVA") ?>" />
            <input id="IVAH" type="hidden" name="IVA" value="0" /></td>
        </tr>
        <?php
		}
		?>
        <tr  class="<?php if($usar_bsf!=1) echo "uk-hidden" ; ?>">
          <td  align="center" colspan="" class="tot_fac">TOTAL (Pesos):
          <input class="uk-button uk-button-success <?php if($usar_bsf!=1)echo "uk-hidden"; ?>" data-uk-toggle="{target:'.bsf'}" value="BsF" type="button"   onMouseDown="//change($('#entrega'));"/>
          </td>
          
          <td colspan="" align="right">
          <input id="TOTAL" type="text" readonly="0" value="<?php echo "" ?>" name="tot" />
          </td>
          </tr>
          <tr id="bsf"  class="<?php if($usar_bsf!=1) echo "uk-hidden" ; ?> bsf">
          
          <td align="right" style="font-size:24px;
font-family:Georgia,serif;
color:rgb(255, 64, 0);
font-weight:bold;
font-style:italic;">
        
          TOTAL (BsF) </td>
          <td align="right"><input id="TOTAL_BSF" type="text" readonly="" value="<?php echo money("") ?>" name="totB" />
         
          </td>
          </tr>
          <?php if($MODULES["ANTICIPOS"]==1){ ?>
           <tr style="background-color:#000; color:#FFF" class="uk-hidden">
      <td  align="center" colspan="">Anticipo/Bono:</td>
      <td colspan="" align="right"><input id="anticipo" type="text"  value="0" name="anticipo" onKeyUp="change($(this));" onBlur="change($(this));" class="uk-form-large"  readonly/>
        <input type="hidden" name="num_exp" id="num_exp" value="0"  /></td>
    </tr>
          
          
          <?php } 
		  else{
		  ?>
          <input id="anticipo" type="hidden"  value="0" name="anticipo" onKeyUp="change($(this));" onBlur="change($(this));"  readonly/>
        <input type="hidden" name="num_exp" id="num_exp" value="0"  />
          
          <?php
		  }
          if($usar_iva==0){
		?>
        <input name="iva" readonly="readonly" id="IVA" type="hidden" value="" />
        <input id="IVAH" type="hidden" name="IVA" value="0" />
        <?php
		}
		?>
          </td>
        </tr>
        
        <tr class="uk-hidden">
          <td  align="right" colspan="">R. FTE:<input placeholder="%" id="R_FTE_PER" type="text"  value="" name="R_FTE_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_FTE'));" class="save_fc"/></td>
          <td colspan="" align="right"><input id="R_FTE" type="text"  value="" name="R_FTE" class="save_fc" />
          </td>
          </tr>
          
          <tr class="uk-hidden">
          <td  align="right" colspan="">R. IVA:<input placeholder="%" id="R_IVA_PER" type="text"  value="" name="R_IVA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#IVA'),$('#R_IVA'));" /></td>
          <td colspan="" align="right"><input id="R_IVA" type="text"  value="" name="R_IVA"  class="save_fc"/>
          </td>
          </tr>
          
          <tr class="uk-hidden">
          <td  align="right" colspan="">R. ICA:<input placeholder="%" id="R_ICA_PER" type="text"  value="" name="R_ICA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_ICA'));" class="save_fc"/></td>
          <td colspan="" align="right"><input id="R_ICA" type="text"  value="" name="R_ICA" class="save_fc"/>
          </td>
          </tr>
           <tr >
      <th  align="right" colspan=""></th>
      <td colspan="" align="right"><input style="font-size:40px;" id="TOTAL_PAGAR" type="text" value=""  name="TOTAL_PAGAR"  readonly class="save_fc uk-form-large uk-form-success uk-form-width-large "/></td>
    </tr>
        
        
        <tr class="PAGO_PESOS uk-hidden">
          <td  align="right" colspan="" style="font-size:42px;" >PAGO:</td>
          <td colspan="" align="right"><input id="entrega" type="text"  value="" name="entrega" onKeyUp="mover($(this),$('#cod'),$(this),0);change($(this));" onBlur="change($(this));" class="uk-form-success" /></td>
        </tr>
         <tr class="uk-hidden PAGO_PESOS2<?php if($MODULES["PAGO_EFECTIVO_TARJETA"]!=1)echo "uk-hidden"; ?>">
          <td  align="right" colspan="" class="PAGO_PESOS2">Pago Tarjetas:</td>
          <td colspan="" align="right" ><input id="entrega3" type="text"  value="" name="entrega3" onKeyUp="change($(this));mover($(this),$('#cod'),$(this),0);" onBlur="//change($(this));" data-uk-tooltip title=""/></td>
        </tr>
        <tr class="<?php if($usar_bsf!=1)echo "uk-hidden"; ?> bsf">
          <td  align="center" colspan="" >Pago Efectivo(bsF):</td>
          <td colspan="" align="right"><input id="entrega2" type="text"  value="" name="entrega2" onKeyUp="mover($(this),$('#cod'),$(this),0);change($(this));" onBlur="change($(this));" /></td>
        </tr>
       
        <tr class="uk-hidden">
          <td  align="center" colspan="">Cambio:</td>
          <td colspan="" align="right"><input  id="cambio" type="text"  value="0" name="cambio" readonly="readonly" class="uk-form-large uk-text-primary uk-text-bold" />
          
          <div id="cambio_pesos" style="font-weight: bold; font-size:24px; color:#F00"></div></td>
        </tr>
        
      </table>
      </div>
      <span class="Estilo2"></span><br />
      <input type="hidden" name="boton" value="" id="genera" />
      <input type="hidden" name="num_ref" value="<?php echo $n_ref ?>" id="num_ref" />
      
      
       <input type="hidden" name="idCliente" value="<?php echo $idCliente ?>" id="idCliente" />
        <input type="hidden" name="co" value="<?php echo $cotiza_a_fac ?>" id="co" />
       <input type="hidden" name="FechaI" value="<?php echo $FechaI ?>" id="FechaI" />
       <input type="hidden" name="FechaF" value="<?php echo $FechaF ?>" id="FechaF" />
        <input type="hidden" name="nf" value="<?php echo $nf ?>" id="nf" />
         <input type="hidden" name="prefijo" value="<?php echo $pre ?>" id="prefijo" />
	  <!--<input type="hidden" name="exi_ref" value="<?php echo $n_ref ?>" id="exi_ref" />-->
      
      <input type="hidden" name="num_serv" value="0" id="num_serv" />
      <input type="hidden" name="exi_serv" value="0" id="exi_serv" />
      <input type="hidden" name="remision" value="3" id="remision" />
      <input type="hidden" name="bsF_flag" value="0" id="bsF_flag" />
      <input type="hidden" value="" name="html" id="pagHTML">
      <?php //echo "<br>".quitacom("1,250,00.59") 
	  
	  
	  ?>
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

<?php include_once("autoCompletePack.php"); 
$rs=null;
$stmt=null;
$linkPDO= null;



?>	
<script language="javascript1.5" type="text/javascript" src="JS/num_letras.js"></script>
<script type="text/javascript" language="javascript1.5" src="JS/jQuery_throttle.js"></script>
<script language="javascript1.5" type="text/javascript">

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

//window.onbeforeunload = confirmExit;
    function confirmExit() {
		
        if(cont!=0 &&flag_gfv==0)return "AUN NO HA CERRADO ESTA FACTURA, DESEA SALIR SIN GUARDAR??";
    }

function cambia_resol($sel,$pos,$com,$papel,$com_ant,$papel_ant,$cre)
{
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
	
	else if($sel.val()=='DEV_VEN')
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
//38 up, 40down
$(document).ready(function() {
	tot();
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
move($('#cod'),cont+'cant_','entrega','','');


$('input').on("change",function(){$(this).prop('value',this.value);});
$('textarea').on("change",function(){$(this).html(this.value);});
$('#cod').focus();
call_autocomplete('ID',$('#cli_lookup'),'ajax/busq_cli.php');
	
	
	$('#loader').hide();
	//$('.bsf').hide();
//alert('fac_kardex'+kardex)
	

	$('#papel').hide();
	$('#cre_ant').hide();
	$('#papel_ant').hide();
	$('#val_frt').hide();
	$('#log_serv').hide();
	$('#revision').hide();
	$('#alas').hide();
	$('#hh').hide();
	$('#precio_servA').hide();
	
	
	
	
	
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
});


</script> 

</body>
</html>