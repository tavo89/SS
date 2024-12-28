<?php
/*
ajax relacionados:
save_fac_remi.php [INSERT]
save_remi.php [UPDATE]
*/
include_once('Conexxx.php');
if($rolLv!=$Adminlvl && (!val_secc($id_Usu,"cotizacion") && !val_secc($id_Usu,"remi_crea")))
{
header("location: centro.php");
}

valida_session();
cajas($horaCierre,$horaApertura,$hora,$conex,$codSuc);
$TALLER=r("OT");
$COTIZACION=r("co");
$URL_LIST="remisiones.php";
$ENCABEZADO_FAC="REMISION";
$fuenteFac=r("fuente");//vals:  cotiza
$tipoFAC=r("tipoFAC");

if($tipoFAC=="OT"){$ENCABEZADO_FAC='<i class="uk-icon uk-icon-wrench"></i> &nbsp;'.$LABEL_REMISION;}

if(!empty($cod_origen)){$tipoFAC="Traslado";}
$cotiza_a_fac=r("co_remi");

$nf=r("nf");
$pre=r("prefijo");
$idCliente=r("idCliente");
$FechaI=r("FechaI");
$FechaF=r("FechaF");
$filtroA="";$filtroB="";$filtroFecha="";$filtroFacturaSeleccionada="";
$filtro_remi_coti=" AND tipo_fac='cotizacion' ";

if(!empty($nf) && !empty($pre))$filtroA=" AND (num_fac_ven='$nf' AND prefijo='$pre')";
if(!empty($nf) && !empty($pre))$filtroFacturaSeleccionada=" AND (a.num_fac_ven='$nf' AND a.prefijo='$pre')";
if($cotiza_a_fac==1)$filtro_remi_coti=" AND tipo_fac='cotizacion' $filtroFacturaSeleccionada";
if(!empty($idCliente)){$filtroB=" AND (id_cli='$idCliente' OR nom_cli='$idCliente' ) AND anulado!='ANULADO' AND anulado!='FACTURADA' ";/*$FLUJO_INV=-1;*/}
if(!empty($FechaI) && !empty($FechaF))$filtroFecha=" AND (date(fecha)>='$FechaI' AND date(fecha)<='$FechaF')";
if($COTIZACION==1){
	
	$ENCABEZADO_FAC="COTIZACI&Oacute;N";
	$URL_LIST="COTIZACIONES.php";
	$FLUJO_INV=-1;
	//$_SESSION['FLUJO_INVENTARIO']=1;
	}
$cod_origen=r("origen");

$n_r=0;
$n_s=0;
$hh=0;
$cod_su=0;
$TC=tasa_cambio();
if(isset($_SESSION['cod_su']))$cod_su=$_SESSION['cod_su'];
if(isset($_REQUEST['exi_ref']))$n_r=$_REQUEST['exi_ref'];
if(isset($_REQUEST['exi_serv']))$n_s=$_REQUEST['exi_serv'];
$num_fac=0;
$boton=r("boton");
 

$RESP=r("resp");
if($RESP=="imp"){
	$num_fac=$_SESSION['n_fac_ven'];
	$PRE=$_SESSION['prefijo'];
	//imp_fac($num_fac,$PRE,"Imprimir",2);
	imp_fac($num_fac,$PRE,"Imprimir",2,"no",0,''); 
	/*
	$t=2;
	 
	
	if($imprimir_remi_pos==0){imp_fac($num_fac,$pre,"Imprimir",2);}
	else{imp_fac_pos($num_fac,$pre,"Imprimir",$t,$val2);}
	*/
	js_location("remisiones.php");
}
 






?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />

<?php include_once("js_global_vars.php"); ?>	

<script src="JS/jquery-2.1.1.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>
<script language="javascript1.5" type="text/javascript" src="JS/fac_ven.js?<?php  echo "$LAST_VER"; ?>" ></script>
<?php include_once("HEADER.php"); ?>
<?php include_once("chosen_pack.php"); ?>	

<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<!--
<link href="css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/component.css" />
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
			<?php include_once("boton_menu.php");?>
			<div class="uk-width-1-1 uk-container-center">
            <?php include("menu_bar_remi.php");?>
            
            </div>

<div class="uk-width-9-10 uk-container-center">
	
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


$nomCli="";
$idCli="";
$telCli="";
$dirCli="";
$mailCli="";
$PLACA="";
$KM="";


include("load_remi_cli.php");

if($tipoFAC=="remi"){
$nomCli=$PUBLICO_GENERAL;
$idCli=$NIT_PUBLICO_GENERAL;	
}

?>
<form action="fac_remi.php" name="form_fac" method="post" id="form-fac" autocomplete="off" class="uk-form">
      <div class="loader"> <img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" /> </div>
      <div id="factura_venta">
       
      <table border="0" align="center" cellspacing="1" class="<?php  if($TALLER==1){echo "fac_taller";}else{echo "round_table_white";} ?>" style="color:#000" >
        <tr>
          <td colspan="5"><table>
              <tr>
                
                  
                  <?php if($MODULES["ANTICIPOS"]==1){ ?>
                   <td colspan="" class="uk-hidden">Bono/Anticipo:<br>
                  <select id="
" name="confirm_bono" onChange="">
                    
                    <option value="SI" >Si</option>
                    <option value="NO" selected>No</option>
                     
                  </select></td>
                  <?php }?>
                  <td colspan="2" style="font-size:19px;font-weight:bold;">Forma de Pago:
                  <select id="form_pago" name="form_pago" onChange="creco(this.value,'credito','contado');call_tot();" style="font-size:19px;font-weight:bold;color:rgb(0, 68, 255);">
                    <option value="Contado"  selected>Contado</option>
                  <?php if($rolLv>=$Adminlvl|| val_secc($id_Usu,"vende_credito")){?>
                  <option value="Credito">Cr&eacute;dito</option>
                  
                  <?php }?>
                   
                      <!--
                       <option value="Credito">Cr&eacute;dito</option>
                    <option value="Tarjeta Credito">Tarjeta Credito</option>
                    <option value="Tarjeta Debito">Tarjeta Debito</option>
                    -->
                  </select></td>
                  <TD valign="bottom"><input type="button" value="Aplicar PvP CREDITO" onClick="<?php if($MODULES["PVP_CREDITO"]==1) echo "cambiaFP();"; ?>" class="uk-button uk-button-primary">
                  
                  </TD>
                <td colspan="2">Tipo de Cliente:
                  <select name="tipo_cli" id="tipo_cli" style="font-size:16px; background-color: #9F9;">
                  <option value="Mostrador." selected>Mostrador (P&uacute;blico)</option>
                  <?php 
				  if($TALLER!=1){
				  if($MODULES["CARROS_RUTAS"]==0){ ?>
                    
                    <?php if($MODULES["SERVICIOS"]==1){?>
                    
                    <option value="Taller" >Taller</option>
                     <option value="Empresas" >Empresas (+16%)</option>
                     <?php }?>
                     <?php if($MODULES["TRASLADOS"]==1 && $tipoFAC!="OT" && $COTIZACION!=1 && $tipoFAC="Traslado"){?>
                      <option value="Traslado"  <?php if(!empty($cod_origen)){echo "selected";}?>>Traslado</option>
                      <?php }?>
                    <?php  }
					else{
					 ?>
                    <option value="Traslado" >Traslado Camiones</option>
                    <?php
					
					}
					
				  }//// FIN TALLER VAL();
				  
				  else
				  {
					 ?>
                      <option value="Taller" >Taller</option>
					  
					 <?php 
				  }
					?>
                    <!--<option value="Empleados">Empleados</option>-->
                  </select></td>
                <td colspan="2" align="left">Vendedor:<br>
                  <select name="vendedor" id="vendedor"  style="width:150px">
                    <option value="<?PHP echo $nomUsu ?>" selected><?PHP echo $nomUsu ?></option>
                    <?php
		  
		  $rs=$linkPDO->query("SELECT nombre FROM usuarios a INNER JOIN (SELECT a.estado,b.id_usu,b.des FROM usu_login a INNER JOIN tipo_usu b ON a.id_usu=b.id_usu WHERE (des='Vendedor' OR des='Caja' OR des='Inventarios' OR des='Administrador' OR des='Conductor') AND a.estado!='SUSPENDIDO') b ON b.id_usu=a.id_usu   ORDER BY nombre");
		  while($row=$rs->fetch()){
			  
			  $vendedor= $row["nombre"];
		  ?>
                    <option value="<?php echo $vendedor ?>" <?php if($vendedor==$nomUsu)echo "selected"; ?> ><?php echo $vendedor?></option>
                    <?php
		  }
		  ?>
                  </select></td>
</tr>
  <?php
			  
			  if(0){
			  
			  ?>
<tr>
<td colspan="2" align="left">T&eacute;cnico:<br>
<select name="mecanico" id="mecanico"  style="width:150px">  
<option value=""  selected></option>                    
<?php	  
$rs=$linkPDO->query("SELECT nombre FROM usuarios INNER JOIN tipo_usu ON tipo_usu.id_usu=usuarios.id_usu WHERE (des='Tecnico' OR des='Mecanico' ) AND cod_su=$codSuc ORDER BY nombre");
while($row=$rs->fetch()){
$vendedor= $row["nombre"];
?>
<option value="<?php echo $vendedor ?>" <?php if($vendedor==$nomUsu)echo "selected"; ?> ><?php echo $vendedor?></option>
<?php }?>
</select></td>

<td colspan="2" align="left">T&eacute;cnico 2:<br>
<select name="mecanico2" id="mecanico2"  style="width:150px"> 
<option value=""  selected></option>                     
<?php	  
$rs=$linkPDO->query("SELECT nombre FROM usuarios INNER JOIN tipo_usu ON tipo_usu.id_usu=usuarios.id_usu WHERE (des='Tecnico' OR des='Mecanico' ) AND cod_su=$codSuc ORDER BY nombre");
while($row=$rs->fetch()){
$vendedor= $row["nombre"];
?>
<option value="<?php echo $vendedor ?>" <?php if($vendedor==$nomUsu)echo "selected"; ?> ><?php echo $vendedor?></option>
<?php }?>
</select></td>

<td colspan="2" align="left">T&eacute;cnico 3:<br>
<select name="mecanico3" id="mecanico3"  style="width:150px">  
<option value=""  selected></option>                    
<?php	  
$rs=$linkPDO->query("SELECT nombre FROM usuarios INNER JOIN tipo_usu ON tipo_usu.id_usu=usuarios.id_usu WHERE (des='Tecnico' OR des='Mecanico' ) AND cod_su=$codSuc ORDER BY nombre");
while($row=$rs->fetch()){
$vendedor= $row["nombre"];
?>
<option value="<?php echo $vendedor ?>" <?php if($vendedor==$nomUsu)echo "selected"; ?> ><?php echo $vendedor?></option>
<?php }?>
</select></td>
       

<td colspan="2" align="left">T&eacute;cnico 4:<br>
<select name="mecanico4" id="mecanico4"  style="width:150px">
<option value=""  selected></option>                      
<?php	  
$rs=$linkPDO->query("SELECT nombre FROM usuarios INNER JOIN tipo_usu ON tipo_usu.id_usu=usuarios.id_usu WHERE (des='Tecnico' OR des='Mecanico' ) AND cod_su=$codSuc ORDER BY nombre");
while($row=$rs->fetch()){
$vendedor= $row["nombre"];
?>
<option value="<?php echo $vendedor ?>" <?php if($vendedor==$nomUsu)echo "selected"; ?> ><?php echo $vendedor?></option>
<?php }?>
</select></td>        
              </tr>
                <?php
			  
			  }
			  
			  ?>
              <tr>
                <td width="61" colspan="1"  >Fecha:</td>
                <td width="144" colspan="2" >
                <input  id="fecha" type="datetime-local" value="<?php echo $FECHA_HORA; ?>" name="fecha" />
                
                </td>
                
                <td colspan="3" style="font-size:28px; font-weight:bold;"><?php if($MODULES["CARROS_RUTAS"]==1){echo "PLANILLA CARGUE";}else {echo "$ENCABEZADO_FAC";} ?>
                 
                  </td>
                <TD>
                <select name="tipo_resol" id="tipo_resol" onChange="cambia_resol($(this),$('#pos'),$('#com'),$('#papel'),$('#com_ant'),$('#papel_ant'),$('#cre_ant'))">
                <?php
				if($FLUJO_INV==1 && $tipoFAC=="OT"){
				?>

                <option value="COM" selected>ORDEN SERV</option>
             
                <?php } 
				else if($FLUJO_INV==1 && $tipoFAC=="remi"){echo '<option value="COM2" selected>REMISION</option>';}
				else{
				?>
 
                <option value="CRE_ANT" selected>COTIZACION</option>
                
                <?php } ?>
                </select>
                </TD>
<td colspan="2"  align="center">
<span id="pos" style="color: #F00; font-size:24px;"><strong><?php echo $codRemiPOS ?>  </strong><?PHP //echo serial_fac("remision","REM_POS","fac_remi") ?><strong></strong></span>

<span id="com" style="color:#F00; font-size:24px;"><strong><?php echo $codRemiCOM ?> </strong><?PHP echo serial_fac("remision_com","REM_COM","fac_remi") ?><strong></strong></span>

<span id="com2" style="color:#F00; font-size:24px;"><strong><?php echo $codRemiCOM2 ?> </strong><?PHP echo serial_fac("remision_com2","REM_COM2","fac_remi") ?><strong></strong></span>

<span id="papel" style="color:#F00; font-size:24px;"><strong><?php echo $codPapelSuc ?> </strong><?PHP //echo serial_fac("resol_papel","PAPEL","fac_remi") ?><strong></strong></span>



<span id="com_ant" style="color:#F00; font-size:24px;"><strong><?php echo $codCreditoSuc ?> </strong><?PHP //echo serial_fac("credito_ant","COM","fac_remi") ?><strong></strong></span>

<span id="papel_ant" style="color:#F00; font-size:24px;"><strong><?php echo $codPapelSuc ?> </strong><?PHP //echo serial_fac("resol_papel_ant","PAPEL","fac_remi") ?><strong></strong></span>

<span id="cre_ant" style="color:#F00; font-size:24px;"><strong><?php echo $codCreditoANTSuc ?> </strong><?PHP echo serial_fac("cartera_ant","CRE","fac_remi")."" ?><strong></strong></span>

</td>
</tr>
              
<?php if($MODULES["COMI_VENTAS"]==1){?>
<tr>           
<td colspan="2" class="destacar_cont">Cod. Comisi&oacute;n</td>
<td>

<select name="cod_comision" id="cod_comision"  style="width:200px" data-placeholder="C&oacute;digo Otros Talleres" class="chosen-select">
<option value="" selected>- - - - - - - - - </option>
<?php include("load_cod_ven.php");?>
 </select>


</td>
</tr>
<?php }?>    

              
              <tr style="" id="pagare" class=" ">
              
              
              <td>PAGARE:</td>
              <td><input type="text" value="" name="num_pagare" id="num_pagare" ></td>
              <?php if($MODULES["TRASLADOS"]==1 && $tipoFAC!="OT" && $COTIZACION!=1){?>
              <th style="font-size:18px;">DESTINO:</th>
              <td style="">
              <select name="sucDestino" id="sucDestino"  style="width:150px;font-size:16px; background-color: #9F9;">
			  <option value="" selected></option>
<?php
$sql_top_panel="SELECT * FROM sucursal WHERE cod_su!='$codSuc'";
$rs_top_panel=$linkPDO->query($sql_top_panel );
while($row=$rs_top_panel->fetch())
{
	$NombreSuc=$row['nombre_su'];
	$codigoSu=$row['cod_su'];
	?>
    <option  value="<?php echo "$codigoSu" ?>" <?php if($codigoSu==1) echo "selected" ?>><?php echo "$NombreSuc" ?> </option>
    <?php
	
}
?>
</select>
                
                </td>
                <?php }?>
              </tr>
        
              <tr>
                <td >Nombre:</td>
                <td ><input name="cli" type="text"   id="cli" value="<?php echo "$nomCli"; ?>" onKeyUp="" onBlur="busq_cli(this);" autocomplete="false"/></td>
                <td>C.C./NIT:</td>
                <td><input name="ced" type="text" value="<?php echo "$idCli"; ?>" id="ced"  onchange="busq_cli(this)"/></td>
                <td>Ciudad:</td>
                <td><input name="city" type="text" id="city" value="<?php echo "$ciudadCli"; ?>" onChange="javascript:valida_doc('cliente',this.value);"/></td>
                <?php if($MODULES["VEHICULOS"]==1){?>
                <td>
                PLACA:
                <a href="#OT_VEHI" data-uk-modal>REGISTRAR</a>
                </td>
                <td>
                <input type="text" name="placa" value="<?php  echo "$PLACA"; ?>" id="placa" onChange="val_placa($(this));">
                </td>
                <?php } ?>
                <!--
                <td>Fecha nacimiento:</td>
                <td><input size="10" name="fe_naci" value="" type="date" id="fe_naci" onClick="//popUpCalendar(this, form_fac.fe_naci, 'yyyy-mm-dd');"  placeholder="Fecha Nacimiento"></td>
                -->
              </tr>
              <tr>
                <td >Direcci&oacute;n:</td>
                <td ><input name="dir" type="text" value="<?php echo "$dirCli"; ?>" id="dir" /></td>
                <td>Tel.:</td>
                <td><input name="tel"  type="text" value="<?php echo "$telCli"; ?>" id="tel" /></td>
                <td>E-mail.:</td>
                <td colspan=""><input name="mail"  type="text" value="<?php echo "$mailCli"; ?>" id="mail" /></td>
                <?php if($MODULES["VEHICULOS"]==1){?>
                 <td>Kms:</td>
                <td colspan=""><input name="km"  type="text" value="" id="km" placeholder="Kil&oacute;metros del Veh&iacute;culo" /></td>
                <?php }?>
              </tr>
              
            </table></td>
        </tr>
        <tr>
          <td colspan="5">
          <table id="articulos" width="100%" cellspacing="0" cellpadding="0" border="1px">
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
                
                <?php if($usar_talla==1){ ?>
                <td><div align="center"><strong>Talla</strong></div></td>
               <?php } ?>
               <?php if($usar_datos_motos==1){ ?>
               <td><div align="center"><strong># Motor</strong></div></td>
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
 
   $src="remi";
  include("load_remi_arts.php");?>                
              
            </table>
            </td>
        </tr>
        <?php if($MODULES["SERVICIOS"]==1){?>
        <tr>

 <td colspan="5">

<table id="servicios" width="100%">
              <tr style="background-color: #000; color:#FFF">
              <th>ID</th>
              <th>Codigo</th>
              <th>Servicio</th>
               <th>Nota</th>
              <th>IVA</th>
              <th>PVP</th>
              <th>Total</th>
              <th>T&eacute;cnico</th>
              
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
 <?php } ?>
        <tr>
          <td colspan="5">
          
          <table align="center" frame="box">
          <tr valign="middle" style=" font-size:24px; font-weight:bold;">
             
          <?php 
		if(($rolLv==$Adminlvl || val_secc($id_Usu,"adm_art")) && $codSuc>0){ 
			?>
                 <th >Cod. Art&iacute;culo:</th>
                <td>
                <?php 
				$opc="add";
				if($COTIZACION==1){$opc="cotiza";}
				?>
<input type="text" name="cod" value="" id="cod" onKeyPress="codx($(this),'<?php echo $opc;?>');" onKeyUp="mover($(this),$('#entrega'),$(this),0);codx($(this),'<?php echo $opc;?>');//mover($(this),$('#entrega'),$('#cli'),1);" />
                
                
                <input type="hidden" value="" id="feVen" name="feVen">
                <input type="hidden" value="" id="Ref" name="Ref">
                
                  <img style="cursor:pointer" data-uk-tooltip title="Ver todos los Productos" onMouseUp="busq_all($('#cod'));" src="Imagenes/search128x128.png" width="34" height="31" /><br />
                  <div id="Qresp"></div></td>
                  <?php
		}
				  ?>
                <td><input id="butt_gfv" type="button" value="Guardar" name="boton2" onClick="requireDataMHRemi($(this),'genera',document.forms['form_fac'])" class=" uk-button" /></td>
                <td>&nbsp;</td>
              </tr>
              <?php if($MODULES["SERVICIOS"]==1){?>
              <tr valign="middle" style=" font-size:16px; font-weight:bold;">
             <td>SERVICIOS:</td> <td><?php  echo selc_serv("serv($(this),'','eli')");  ?></td>
              </tr>
              <?php }?>
            </table>
            
            </td>
        </tr>
        
        <tr id="desc">
          <td colspan="3" rowspan="<?php if($MODULES["ANTICIPOS"]==1){echo "8";}else {echo "7";} ?>" align="center" width="500px" ><br />
            <br />
            <br />
            <br />
            <br />
            <div align="left">
              <textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="40" style="width:300px"></textarea>
              <br>
              <textarea name="nota_fac" id="nota_fac"   cols="40" placeholder="NOTAS" style="width:300px;
-webkit-border-radius:19px;
-moz-border-radius:19px;
border-radius:19px;
border:6px solid rgb(201, 38, 38);"></textarea>
            </div></td>
          <th style="background-color: #000; color:#FFF" width="300px">Base IVA:</th>
          <td align="right"><input  id="SUB" type="text" readonly="" value="0"   name="sub"/>
            <input type="hidden" name="SUB" value="0" id="SUBH" />
           <input readonly name="dcto" id="DESCUENTO" type="hidden" value="" onKeyUp="" />
           <input id="EXCENTOS" type="hidden" readonly="" value="0" name="exc" />
            
            </td>
        </tr>
        <tr>
          <th style="background-color: #000; color:#FFF">Dcto:</th>
          <td align="right">
          <input placeholder="%" id="DCTO_PER" type="text"  value="" name="DCTO_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#DESCUENTO2'));" onBlur="dctoB();" class="<?php if($COTIZACION!=1)echo "uk-hidden";?>"/>
          <input name="dcto2" id="DESCUENTO2" type="text" value="" onKeyUp="dctoB();" />
           </td>
        </tr>
    
        <!--
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
        <tr>
          <td  align="center" colspan="">I.V.A: </td>
          <td align="right"><input name="iva" readonly="readonly" id="IVA" type="text" value="" />
            <input id="IVAH" type="hidden" name="IVA" value="0" /></td>
        </tr>
        <?php
		}
		?>
        <tr >
          <td  align="center" colspan="" class="tot_fac">TOTAL:
          <input class="uk-button uk-button-success <?php if($usar_bsf!=1)echo "uk-hidden"; ?>" data-uk-toggle="{target:'#bsf'}" value="BsF" type="button"   onMouseDown="//change($('#entrega'));"/>
          </td>
          
          <td colspan="" align="right"><input id="TOTAL" type="text" readonly="" value="0" name="tot" />
          </td>
          </tr>
          <tr id="bsf"  class="uk-hidden">
          
          <td align="right" style="font-size:24px;
font-family:Georgia,serif;
color:rgb(255, 64, 0);
font-weight:bold;
font-style:italic;">
        
          TOTAL (BsF) </td>
          <td align="right"><input id="TOTAL_BSF" type="text" readonly="" value="0" name="totB" />
         
          </td>
          </tr>
          <?php if($MODULES["ANTICIPOS"]==1){ ?>
           <tr style="background-color:#000; color:#FFF" class="uk-hidden">
      <td  align="center" colspan="">Anticipo/Bono:</td>
      <td colspan="" align="right"><input id="anticipo" type="text"  value="0" name="anticipo" onKeyUp="change($(this));" onBlur="change($(this));"  readonly/>
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
          <td  align="center" colspan="" >Pago Efectivo:</td>
          <td colspan="" align="right"><input id="entrega" type="text"  value="" name="entrega" onKeyUp="change($(this));mover($(this),$('#cod'),$(this),0);//mover($(this),$('#cli'),$('#cod'),1);" onBlur="change($(this));" /></td>
        </tr>
        <tr class="uk-hidden">
          <td  align="center" colspan="">Cambio:</td>
          <td colspan="" align="right"><input  id="cambio" type="text"  value="0" name="cambio" readonly="readonly" />
          
          <div id="cambio_pesos" style="font-weight: bold; font-size:24px; color:#F00"></div></td>
        </tr>
        
      </table>
      </div>
      <span class="Estilo2"></span><br />
      <input type="hidden" name="boton" value="" id="genera" />
      <input type="hidden" name="num_ref" value="<?php echo $n_ref ?>" id="num_ref" />
      
      <input type="hidden" name="id_cuenta" value="0" id="id_cuenta" />
      <input type="hidden" name="num_serv" value="0" id="num_serv" />
      <input type="hidden" name="exi_serv" value="0" id="exi_serv" />
       <input type="hidden" name="exi_ref" value="<?php echo $n_ref ?>" id="exi_ref" />
      <input type="hidden" name="remision" value="1" id="remision" />
      <input type="hidden" name="bsF_flag" value="0" id="bsF_flag" />
      <input type="hidden" name="co" value="<?php echo "$COTIZACION"  ?>" id="co" />
      <input type="hidden" name="tipoFAC" value="<?php echo "$tipoFAC"  ?>" id="tipoFAC" />
      
      
<input type="hidden" name="tope_credito" value="0" id="tope_credito" />
<input type="hidden" name="total_credito" value="0" id="total_credito" />
      
      <input type="hidden" value="" name="html" id="pagHTML">
      <input id="entrega3" type="hidden"  value="" name="entrega3" onKeyUp="change($(this));mover($(this),$('#cod'),$(this),0);" onBlur="//change($(this));" data-uk-tooltip title=""/>
      <?php //echo "<br>".quitacom("1,250,00.59") ?>
    </form>

</div>

</div>
<div id="tasa_cambio" class="uk-modal">
<div class="uk-modal-dialog">

<a class="uk-modal-close uk-close"></a>
<h1 style="color:#000">TASA CAMBIO</h1>
<table>
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
<table>
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
<script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>
 
<script language="javascript1.5" type="text/javascript" src="JS/num_letras.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery.autocomplete.js"></script>
<script type="text/javascript" language="javascript1.5" src="JS/jQuery_throttle.js"></script>
<script language="javascript1.5" type="text/javascript">

var dcto_remi=0;
var HH=<?php echo $hh ?>;
var iva_serv=0.16;
var alas=<?php echo $alasSuc ?>;
var usaFechaVen=<?php echo $usar_fecha_vencimiento ?>;
var index = -1;

function cambia_resol($sel,$pos,$com,$papel,$com_ant,$papel_ant,$cre)
{
	//alert($sel.val());
	if($sel.val()=='POS')
	{
		$cre.hide();
		$pos.show();
		$com.hide();
		$papel.hide();
		$com_ant.hide();
		$papel_ant.hide();
	}
	else if($sel.val()=='COM')
	{
		$cre.hide();
		$pos.hide();
		$com_ant.hide();
		$papel_ant.hide();
		$com.show();
		$papel.hide();
	}
	else if($sel.val()=='PAPEL')
	{
		$cre.hide();
		$pos.hide();
		$com.hide();
		$com_ant.hide();
		$papel_ant.hide();
		
		$papel.show();
	}
	else if($sel.val()=='COM_ANT')
	{
		$pos.hide();
		$com.hide();
		$papel.hide();
		$papel_ant.hide();
		$cre.hide();
		$com_ant.show();
	}
	else if($sel.val()=='PAPEL_ANT')
	{
		$pos.hide();
		$com.hide();
		$papel.hide();
		$com_ant.hide();
		$cre.hide();
		
		$papel_ant.show();
	}
	else if($sel.val()=='CRE_ANT')
	{
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
move($('#cod'),cont+'cant_','entrega','','');


$('input').on("change",function(){$(this).prop('value',this.value);});
$('textarea').on("change",function(){$(this).html(this.value);});
$('#cod').focus();

<?php
if($MODULES["FACTURACION_ELECTRONICA"]==1){echo "call_autocomplete('ID',$('#cli'),'ajax/busq_cli.php');";}
else {echo "call_autocomplete('NOM',$('#cli'),'ajax/busq_cli.php');";}
?>


	$('#loader').hide();
	//$('.bsf').hide();
//alert('fac_kardex'+kardex);
	$('#papel').hide();
	$('#cre_ant').hide();
	$('#papel_ant').hide();
	$('#val_frt').hide();
	$('#log_serv').hide();
	$('#revision').hide();
	$('#alas').hide();
	$('#hh').hide();
	$('#com2').hide();
	$('#precio_servA').hide();
	
	if(kardex==1)
 	{
	$('#pos').hide();
	$('#com').show();
	$('#com_ant').hide();
	}
	else 
 	{
	$('#com').hide();
	$('#com_ant').hide();
	$('#pos').hide();
	$('#cre_ant').show();
	}
	
<?php 

if($tipoFAC=="remi"){echo "	$('#pos').hide();
	$('#com').hide();
	$('#com2').show();
	$('#com_ant').hide();";}


if($tipoFAC=="OT"){echo "	$('#pos').hide();
	$('#com').show();
	$('#com2').hide();
	$('#com_ant').hide();";}
	
if($tipoFAC=="Traslado"){echo "	$('#pos').hide();
	$('#com').hide();
	$('#com2').hide();
	$('#com_ant').hide();
	$('#cre_ant').show();";}
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
});

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