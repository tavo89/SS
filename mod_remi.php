<?php
/*
ajax relacionados:
save_fac_remi.php [INSERT]
save_remi.php [UPDATE]
*/
include_once('Conexxx.php');
if($rolLv!=$Adminlvl && (!val_secc($id_Usu,"cotizacion") && !val_secc($id_Usu,"remi_crea")) )
{
	header("location: centro.php");
	}

$TALLER=r("OT");
$COTIZACION=r("co");
$ENCABEZADO_FAC="REMISION";

if($COTIZACION==1){
	
	$ENCABEZADO_FAC="COTIZACI&Oacute;N";
	
	$FLUJO_INV=-1;
	}

$tipoFAC=r("tipoFAC");
if($tipoFAC=="OT"){$ENCABEZADO_FAC='<i class="uk-icon uk-icon-wrench"></i> &nbsp;'.$LABEL_REMISION;}
valida_session();
cajas($horaCierre,$horaApertura,$hora,$conex,$codSuc);

$TABLA="fac_remi";
$tabla2="art_fac_remi";

$num_fac=r("num_fac_venta");
$pre=r("pre");
$cod_origen=r("origen");

$codSuc=$cod_origen;

$rs=$linkPDO->query("SELECT *,TIME(fecha) as hora, DATE(fecha) as fecha FROM $TABLA WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc'");

//echo "SELECT *,TIME(fecha) as hora, DATE(fecha) as fecha FROM $TABLA WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc'";
$n_ref=0;


$TC=tasa_cambio();
$codOrigen=1;
$codDest=1;
?>
<?php      
if($row=$rs->fetch()){
$nomCli=$row['nom_cli'];
$idCli=$row['id_cli'];
$telCli=$row['tel_cli'];
$dirCli=$row['dir'];
$mailCli=$row['mail'];
$ciudadCli=$row['ciudad'];

$meca=$row["mecanico"];
$meca2=$row["tec2"];
$meca3=$row["tec3"];
$meca4=$row["tec4"];

$nota_fac=$row["nota"];

$vendedor=nomTrim($row['vendedor']);	
$tipoPago=$row['tipo_venta'];
$tipoCli=$row['tipo_cli'];
$pagare=$row['num_pagare'];
$fecha_hora=$row['fecha']."T".$row['hora'];

$SUB=$row['sub_tot'];
$DESCUENTO=$row['descuento']*1;
$IVA=$row['iva'];
$TOT=$row['tot'];
$TOT_BSF=$row['tot_bsf'];

$val_letras=$row['val_letras'];
$entrega=$row['entrega'];
$cambio=$row['cambio'];
$entregaBSF=$row['entrega_bsf'];

$abon_anti=$row['abono_anti'];	
$num_exp=$row['num_exp'];

$R_FTE=$row['r_fte'];
$R_IVA=$row['r_iva'];
$R_ICA=$row['r_ica'];

$R_FTE_PER=0;
$R_IVA_PER=0;
$R_ICA_PER=0;

$TOT_CRE=$row["tot_cre"];

$TOT_PAGAR=$TOT-($R_FTE+$R_ICA+$R_IVA);

$codOrigen=$row["nit"];
$codDest=$row["sede_destino"];

$km=$row["km"];
$placa=$row["placa"];



$codComision=$row["cod_comision"];
//$tipoPago=$row['tipo_venta'];
 ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<link href="JS/fac_ven.css?<?php  echo "$LAST_VER"; ?>" rel="stylesheet" type="text/css" />
<?php include_once("HEADER.php"); ?>	
<script src="JS/jquery-2.1.1.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>
<script language="javascript1.5" type="text/javascript" src="JS/fac_ven.js?<?php  echo "$LAST_VER"; ?>" ></script>
<?php include_once("chosen_pack.php"); ?>
<?php include_once("autoCompletePack.php"); ?>
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

<style type="text/css">

</style>
<div class="uk-width-9-10 uk-container-center">
 
<?php
include("menu_bar_remi.php");

 //echo "codPOS: $codContadoSuc, resolPOS: $ResolContado, fePOS: $FechaResolContado, rangoPOS: $RangoContado, COD_SU: $codSuc<br> $codCreditoSuc-$ResolCredito-$FechaResolCredito-$RangoCredito";  ?>
    <form action="<?php echo "mod_remi.php?num_fac_venta=$num_fac&pre=$pre" ?>" name="form_fac" method="post" id="form_fac" autocomplete="off" class="uk-form">
      <div class="loader"> <img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" /> </div>
      <div id="factura_venta">
       
      

      
      <table border="0" align="center" cellspacing="1" class="<?php  if($TALLER==1){echo "fac_taller";}else{echo "round_table_white";} ?>" style="color:#000" >
        <tr>
          <td colspan="5"><table>
              <tr>
                <td colspan="2" style="font-size:19px;font-weight:bold;" >Forma de Pago:
<select id="form_pago" name="form_pago" onChange="creco(this.value,'credito','contado');call_tot();" style="font-size:19px;
font-weight:bold;
color:rgb(0, 68, 255);" class="save_fc">
                   
                    <option value="Contado"  <?php if($tipoPago=="Contado")echo "selected"; ?>>Contado</option>
<?php if($rolLv>=$Adminlvl|| val_secc($id_Usu,"vende_credito")){?>
<option value="Credito" <?php if($tipoPago=="Credito")echo "selected"; ?>>Cr&eacute;dito</option>
<?php  }?>
                    
                     <!--
                     
                    <option value="Tarjeta Credito" <?php if($tipoPago=="Tarjeta Credito")echo "selected"; ?>>Tarjeta Credito</option>
                    <option value="Tarjeta Debito" <?php if($tipoPago=="Tarjeta Debito")echo "selected"; ?>>Tarjeta Debito</option>
                    -->
                  </select></td>
                  <?php if($MODULES["ANTICIPOS"]==1){ ?>
                   <td colspan="" class=" uk-hidden">Bono/Anticipo:<br>
                  <select id="
" name="confirm_bono" onChange="" class="save_fc">
                    
                    <option value="SI" >Si</option>
                    <option value="NO" selected>No</option>
                     
                  </select></td>
                  <?php }?>
                  <TD valign="bottom"><input type="button" value="Aplicar PvP CREDITO" onClick="<?php if($MODULES["PVP_CREDITO"]==1) echo "cambiaFP();"; ?>" class="uk-button uk-button-primary">
                  
                  </TD>
                <td colspan="2">Tipo de Cliente:
                  <select name="tipo_cli" id="tipo_cli" class="save_fc">
                  <?php if($MODULES["CARROS_RUTAS"]==0){ ?>
                 
                   <option value="Mostrador." <?php if($tipoCli=="Mostrador.")echo "selected"; ?>>Mostrador (P&uacute;blico)</option>
                   <option value="Empresas" <?php if($tipoCli=="Empresas")echo "selected"; ?>>Empresas (+16%)</option>
                   <option value="Taller" <?php if($tipoCli=="Taller")echo "selected"; ?>>Taller</option>
                   <option value="Traslado" <?php if($tipoCli=="Traslado")echo "selected"; ?>>Traslado</option><option value="Empleados">Empleados</option>
                   <?php }?>
                   
                    <!-- -->
                  </select></td>
                <td colspan="3" align="left">Vendedor:<br>
                  <select name="vendedor" id="vendedor"  style="width:200px" class="save_fc">
                   
                    <?php
		  
		 $rs=$linkPDO->query("SELECT nombre FROM usuarios a INNER JOIN (SELECT a.estado,b.id_usu,b.des FROM usu_login a INNER JOIN tipo_usu b ON a.id_usu=b.id_usu WHERE (des='Vendedor' OR des='Caja' OR des='Inventarios' OR des='Administrador' OR des='Conductor') AND a.estado!='SUSPENDIDO') b ON b.id_usu=a.id_usu   ORDER BY nombre");
		  while($row=$rs->fetch()){
			  
			  $vende= nomTrim($row["nombre"]);
		  ?>
<option value="<?php echo $vende ?>" <?php if($vende==$vendedor)echo "selected"; ?> ><?php echo $vende?></option>
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
                  <select name="mecanico" id="mecanico"  style="width:100px" class="save_fc">
                   <option value=""></option>
                    <?php
		  
		  $rs=$linkPDO->query("SELECT nombre FROM usuarios INNER JOIN tipo_usu ON tipo_usu.id_usu=usuarios.id_usu WHERE (des='Tecnico' OR des='Mecanico' ) AND cod_su=$codSuc ORDER BY nombre");
		  while($row=$rs->fetch()){
			  
			  $vendedor= $row["nombre"];
		  ?>
                    <option value="<?php echo $vendedor ?>" <?php if($vendedor==$meca)echo "selected"; ?> ><?php echo $vendedor?></option>
                    <?php
		  }
		  ?>
                  </select></td>
                  
                  <td colspan="2" align="left">T&eacute;cnico 2:
                    <select name="mecanico2" id="mecanico2"  style="width:100px" class="save_fc">
                      <option value=""></option>
                      <?php
		  
		  $rs=$linkPDO->query("SELECT nombre FROM usuarios INNER JOIN tipo_usu ON tipo_usu.id_usu=usuarios.id_usu WHERE (des='Tecnico' OR des='Mecanico' ) AND cod_su=$codSuc ORDER BY nombre");
		  while($row=$rs->fetch()){
			  
			  $vendedor= $row["nombre"];
		  ?>
                      <option value="<?php echo $vendedor ?>" <?php if($vendedor==$meca2)echo "selected"; ?> ><?php echo $vendedor?></option>
                      <?php
		  }
		  ?>
                    </select>                    <br></td>
                  
                  
                  <td colspan="2" align="left">T&eacute;cnico 3:<br>
                  <select name="mecanico3" id="mecanico3"  style="width:100px" class="save_fc">
                   <option value=""></option>
                    <?php
		  
		  $rs=$linkPDO->query("SELECT nombre FROM usuarios INNER JOIN tipo_usu ON tipo_usu.id_usu=usuarios.id_usu WHERE (des='Tecnico' OR des='Mecanico' ) AND cod_su=$codSuc ORDER BY nombre");
		  while($row=$rs->fetch()){
			  
			  $vendedor= $row["nombre"];
		  ?>
                    <option value="<?php echo $vendedor ?>" <?php if($vendedor==$meca3)echo "selected"; ?> ><?php echo $vendedor?></option>
                    <?php
		  }
		  ?>
                  </select></td>
                  
                  
                  
                  
                  <td colspan="2" align="left">T&eacute;cnico 4:<br>
                  <select name="mecanico4" id="mecanico4"  style="width:100px" class="save_fc">
                   <option value=""></option>
                    <?php
		  
		  $rs=$linkPDO->query("SELECT nombre FROM usuarios INNER JOIN tipo_usu ON tipo_usu.id_usu=usuarios.id_usu WHERE (des='Tecnico' OR des='Mecanico' ) AND cod_su=$codSuc ORDER BY nombre");
		  while($row=$rs->fetch()){
			  
			  $vendedor= $row["nombre"];
		  ?>
                    <option value="<?php echo $vendedor ?>" <?php if($vendedor==$meca4)echo "selected"; ?> ><?php echo $vendedor?></option>
                    <?php
		  }
		  ?>
                  </select></td>
              </tr>
                <?php
			  
			  }
			  
			  ?>
              <tr>
                <td width="61" colspan="1"  >Fecha:</td>
                <td width="144" colspan="2" >
              
                <input  id="fecha" type="datetime-local" value="<?php echo $fecha_hora; ?>" name="fecha" class="save_fc"/>
               
			
                </td>
                <td colspan="3" style="font-size:28px"><?php if($MODULES["CARROS_RUTAS"]==1){echo "PLANILLA CARGUE";}else {echo "$ENCABEZADO_FAC";} ?>
                 
                  </td>
                <TD colspan="" style="color:#F00; font-size:28px;" valign="bottom">
                <?php echo "$pre &nbsp;" ?>
                </TD>
                 <TD colspan="" style="color:#F00; font-size:28px;">
                <?php echo "$num_fac" ?>
                </TD>
                
              </tr>
              
              <?php if($MODULES["COMI_VENTAS"]==1){?>
<tr>           
<td colspan="2" class="destacar_cont">Cod. Comisi&oacute;n</td>
<td>

<select name="cod_comision" id="cod_comision"  style="width:200px" data-placeholder="C&oacute;digo Otros Talleres" class="chosen-select save_fc">
<option value="" >- - - -</option>
<option value="<?php echo "$codComision";?>" selected><?php echo "$codComision";?></option>

<?php include("load_cod_ven.php");?>
 </select>


</td>
</tr>
<?php }?>    

              
              
              <tr style="" id="pagare">
              
              
              <td>PAGARE:</td>
              <td><input type="text" value="<?php echo "$pagare" ?>" name="num_pagare" id="num_pagare" class="save_fc"></td>
              <?php if($MODULES["TRASLADOS"]==1 && $tipoFAC!="OT" && $COTIZACION!=1){?>
              <th style="font-size:18px;">DESTINO:</th>
              <td>
              <select name="sucDestino" id="sucDestino"  style="width:150px;font-size:16px; background-color: #9F9;" class="save_fc">
<option value=""></option>
<?php
$sql_top_panel="SELECT * FROM sucursal WHERE cod_su!='$codSuc'";
$rs_top_panel=$linkPDO->query($sql_top_panel);
while($row=$rs_top_panel->fetch())
{
	$NombreSuc=$row['nombre_su'];
	$codigoSu=$row['cod_su'];
	?>
    <option  value="<?php echo "$codigoSu" ?>" <?php if($codigoSu==$codDest) echo "selected" ?>><?php echo "$NombreSuc" ?> </option>
    <?php
	
}
?>
</select>
              </td>
              <?php }?>
              </tr>
              <tr>
                <td colspan="8">Datos Cliente:</td>
              </tr>
              <tr>
                <td >Cliente:</td>
                <td ><input name="cli" type="text" id="cli" value="<?php echo "$nomCli" ?>" onKeyUp="get_nom(this,'add');mover($(this),$('#cod'),$(this),0);" class="save_fc" onBlur="busq_cli(this);"/></td>
                <td>C.C./NIT:</td>
                <td><input name="ced" type="text" value="<?php echo "$idCli" ?>" id="ced"  onchange="busq_cli(this)" class="save_fc"/></td>
                <td>Ciudad:</td>
                <td><input name="city" type="text" id="city" value="<?php echo "$ciudadCli" ?>" onChange="valida_doc('cliente',this.value);" class="save_fc" /></td>
                  <?php if($MODULES["VEHICULOS"]==1){?>
                <td>
                PRODUCTO:
                <a href="#OT_VEHI" data-uk-modal>REGISTRAR</a>
                </td>
                <td>
                <input type="text" name="placa" value="<?php echo "$placa" ; ?>" id="placa" onChange="val_placa($(this));" class="save_fc">
                </td>
                <?php } ?>
                <!--
                <td>Fecha nacimiento:</td>
                <td><input size="10" name="fe_naci" value="" type="date" id="fe_naci" onClick="//popUpCalendar(this, form_fac.fe_naci, 'yyyy-mm-dd');"  placeholder="Fecha Nacimiento"></td>
                -->
              </tr>
              <tr>
                <td >Direcci&oacute;n:</td>
                <td ><input name="dir" type="text" value="<?php echo "$dirCli" ?>" id="dir" class="save_fc"/></td>
                <td>Tel.:</td>
                <td><input name="tel"  type="text" value="<?php echo "$telCli" ?>" id="tel" class="save_fc"/></td>
                <td>E-mail.:</td>
                <td colspan=""><input name="mail"  type="text" value="<?php echo "$mailCli" ?>" id="mail" class="save_fc"/></td>
                
              <?php if($MODULES["VEHICULOS"]==1){?>
                 <td>Kms:</td>
                <td colspan=""><input name="km"  type="text" value="<?php echo "$km" ?>" id="km" placeholder="Kil&oacute;metros del Veh&iacute;culo" class="save_fc"/></td>
                <?php }?>
              
              </tr>
            </table></td>
        </tr>
        <tr>
          <td colspan="5"><table id="articulos" width="100%">
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
               <!-- <td><div align="center"><strong>Presentaci&oacute;n</strong></div></td>-->
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
                <td><div align="center" ><strong># Motor</strong></div></td>
                <?php }?>
                <td><div align="center"><strong>I.V.A</strong></div></td>
                
                <td height="28"><div align="center"><strong>Cant.</strong></div></td>
                <?php if($usar_fracciones_unidades==1){ ?>
                <td height="28"><div align="center"><strong>Fracci&oacute;n</strong></div></td>
                <?php } ?>
                
                 <?php if($MODULES["CARROS_RUTAS"]==1){ ?>
<td height="28" style="background-color:#F90; color:#000"><div align="center" ><strong>Devuelve</strong></div></td>
                <?php } ?>
                
                 <?php if($MODULES["CARROS_RUTAS"]==1 && $usar_fracciones_unidades==1){ ?>
<td height="28" style="background-color:#F90; color:#000"><div align="center" ><strong>Devuelve<br>(Fracci&oacute;n)</strong></div></td>
                <?php } ?>
                
                <td><div align="center"><strong>Dcto</strong></div></td>
                <td><div align="center"><strong>Precio</strong></div></td>
                <td><div align="center"><strong>Subtotal</strong></div></td>
              </tr>
              
<?php
if($vender_sin_inv==0 && $FLUJO_INV==1)
{
$sql="SELECT a.cant_dev,a.uni_dev,a.costo,a.precio,a.sub_tot,a.dcto,a.des,a.cant,a.fraccion,a.unidades_fraccion,a.color,a.talla,a.ref,a.cod_barras,a.fecha_vencimiento as feven,a.iva as IVA,a.presentacion as presen,a.serial,i.pvp_credito,i.exist,i.unidades_frac,i.precio_v,cod_garantia,num_motor FROM $tabla2 a LEFT JOIN inv_inter i ON i.id_inter=a.cod_barras WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc' AND nit_scs='$codSuc' AND a.fecha_vencimiento=i.fecha_vencimiento AND a.ref=i.id_pro  ORDER BY serial_art_fac ASC";
}
else {
$sql="SELECT a.cant_dev,a.uni_dev,a.costo,a.precio,a.sub_tot,a.dcto,a.des,a.cant,a.fraccion,a.unidades_fraccion,a.color,a.talla,a.ref,a.cod_barras,a.fecha_vencimiento as feven,a.iva as IVA,a.presentacion as presen,a.serial,precio as pvp_credito,(cant+1000) as exist,fraccion as unidades_frac,precio as precio_v,cod_garantia,num_motor FROM $tabla2 a  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc'  ORDER BY serial_art_fac ASC";
	}

//echo "$sql";
$rs=$linkPDO->query($sql);
$i=0;
$No=0;
$rbg="background-color:rgba(200,200,200,1)";
while($row=$rs->fetch())
{
$feVence = $row["feven"];
$ref =$row["ref"];
$cod_barras =$row["cod_barras"];
$des = $row["des"];
$SN=$row['serial'];
$presentacion = $row["presen"];
$color=$row['color'];
$talla=$row['talla'];
$iva = $row["IVA"];
$dcto=$row['dcto'];



$costo=$row['costo'];
$pvp=$row['precio'];
$sub_tot=$row['sub_tot'];

$cod_garantia=$row["cod_garantia"];
$num_motor=$row["num_motor"];

$PVP=0;
$sqlAUX="SELECT exist,precio_v,pvp_credito FROM inv_inter a WHERE id_pro='$ref' AND id_inter='$cod_barras'";
$rsAUX=$linkPDO->query($sqlAUX);
if($rowAUX=$rsAUX->fetch())
{
 
$PVP=$rowAUX['precio_v']*1;
 
 
	
}


$pvpCredito=$row['pvp_credito']*1;
if($pvpCredito==0)$pvpCredito=$PVP;

$cant = $row["cant"]*1;
$cantDev = $row["cant_dev"]*1;
$fracc=$row['fraccion'];
if($fracc<=0)$fracc=1;
$uni = $row["unidades_fraccion"]*1;
$uniDev = $row["uni_dev"]*1;
$factor=($uni/$fracc)+$cant;

$exist=$row['exist']+$cant;
if($FLUJO_INV!=1 || $vende_sin_cant==1)$exist=100000;
$UNI=$row['unidades_frac'];
?>
  
<tr id="tr_<?php echo $i ?>" class="art<?php echo $i ?>">

<!-- REF -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" readonly="" value="<?php echo "$ref" ?>" type="text" id="ref_<?php echo $i ?>" name="ref_<?php echo $i ?>" style=" width:80px">
<input class="art<?php echo $i ?>" readonly="" value="0" type="hidden" id="orden_in<?php echo $i ?>" name="orden_in<?php echo $i ?>" style=" width:80px">
</td>


<!-- cod. barras -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" value="<?php echo "$cod_barras" ?>" type="text" id="cod_bar<?php echo $i ?>" name="cod_bar<?php echo $i ?>" placeholder="" style=" width:130px" readonly="">
</td>

<?php if($usar_serial==1){ ?>
<!-- S/N -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" value="<?php echo "$SN" ?>" type="text" id="SN<?php echo $i ?>" name="SN_<?php echo $i ?>" placeholder="S/N" style=" width:100px" onBlur="//save_remi(<?php echo $i; ?>);">
</td>
<?php } ?>


<?php if($MODULES["COD_GARANTIA"]==1){ ?>
<!-- GARANTIA -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" value="<?php echo "$cod_garantia" ?>" type="text" id="COD_GARANTIA<?php echo $i ?>" name="COD_GARANTIA<?php echo $i ?>" placeholder="Garantia" style=" width:100px" onBlur="//save_remi(<?php echo $i; ?>);">
</td>
<?php } ?>


<!-- descripcion -->
<td class="art<?php echo $i ?>">
<textarea style=" width:150px" cols="10" rows="2" class="art<?php echo $i ?>" name="det_<?php echo $i ?>" id="det_<?php echo $i ?>" onBlur="//save_remi(<?php echo $i; ?>);"><?php echo "$des" ?></textarea>
</td>


<!-- presentacion 
<td class="art<?php echo $i ?>">
<input class="art<?php echo $i ?>" value="<?php echo "$presentacion" ?>" type="text" id="presentacion<?php echo $i ?>" name="presentacion<?php echo $i ?>" placeholder="" style=" width:100px" onBlur="////save_remi(<?php echo $i; ?>);">
</td>
-->

<?php if($usar_fecha_vencimiento==1){ ?>
<!-- fecha_venci -->
<td class="art<?php echo $i ?>">
<input class="art<?php echo $i ?>" value="<?php echo "$feVence" ?>" type="text" id="feVen<?php echo $i ?>" name="feVen<?php echo $i ?>" placeholder="" style=" width:100px" readonly="">
</td>
<?php } ?>

<?php if($usar_color==1){ ?>
<!-- color -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" value="<?php echo "$color" ?>" type="text" id="color<?php echo $i ?>" name="color<?php echo $i ?>" placeholder="" style=" width:50px" >
</td>
<?php } ?>


<?php if($usar_talla==1){ ?>
<!-- talla -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" value="<?php echo "$talla" ?>" type="text" id="talla<?php echo $i ?>" name="talla<?php echo $i ?>" placeholder="" style=" width:40px" >
</td>
<?php } ?>

<!-- num_motor -->
<?php if($usar_datos_motos==1){ ?>
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" value="<?php echo "$num_motor" ?>" type="text" id="num_motor<?php echo $i ?>" name="num_motor<?php echo $i ?>" placeholder="" style=" width:100px; font-size:11px;" onblur="//save_remi(<?php echo $i; ?>);">
</td>
<?php }?>

<!-- iva -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" id="iva_<?php echo $i ?>" type="text" name="iva_<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$iva" ?>" onkeyup="valor_t(<?php echo $i ?>);" style=" width:50px" onBlur="//save_remi(<?php echo $i; ?>);" readonly="readonly">
</td>

<!-- cant -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?> fc_cant" id="<?php echo $i ?>cant_" type="text" name="cant_<?php echo $i ?>" size="5" maxlength="20" value="<?php echo "$cant" ?>"  onkeyup="calc_uni($('#<?php echo $i ?>cant_'),$('#fracc<?php echo $i ?>'),$('#unidades<?php echo $i ?>'));valor_t(this.id);" onblur="cant_dcto(this.id);valor_t(this.id);//save_remi(<?php echo $i; ?>);" style=" width:50px">

<input class="art<?php echo $i ?>" id="<?php echo $i ?>cant_L" type="hidden" name="cant_L<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$exist" ?>" style=" width:50px">

</td>


<!-- fracc. -->
<?php if($usar_fracciones_unidades==1){ ?>
<td class="art<?php echo $i ?>">
<input class="art<?php echo $i ?>" value="<?php echo "$uni" ?>" type="text" id="unidades<?php echo $i ?>" name="unidades<?php echo $i ?>" placeholder="" style="width:80px" onkeyup="calc_cant($('#<?php echo $i ?>cant_'),$('#fracc<?php echo $i ?>'),$('#unidades<?php echo $i ?>'));valor_t(<?php echo $i ?>);" onBlur="//save_remi(<?php echo $i; ?>);">

<input class="art<?php echo $i ?>" value="<?php echo "$fracc" ?>" type="hidden" id="fracc<?php echo $i ?>" name="fracc<?php echo $i ?>" placeholder="" style=" width:80px" readonly="">

<input class="art<?php echo $i ?>" value="<?php echo "$UNI" ?>" type="hidden" id="unidadesH<?php echo $i ?>" name="unidadesH<?php echo $i ?>" placeholder="" style=" width:80px" readonly="">
</td>
<?php } ?>


<?php if($MODULES["CARROS_RUTAS"]==1){ ?>
<!--  DEVUELVE CANT   -->

<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" id="<?php echo $i ?>cant_dev" type="text" name="cant_dev<?php echo $i ?>" size="5" maxlength="20" value="<?php echo "$cantDev" ?>"  onkeyup="" onblur="//save_remi(<?php echo $i; ?>);" style=" width:50px">
</td>
<?php } ?>

<?php if($MODULES["CARROS_RUTAS"]==1 &&$usar_fracciones_unidades==1){ ?>
<!--  DEVUELVE FRACC   -->

<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" id="<?php echo $i ?>unidades_dev" type="text" name="unidades_dev<?php echo $i ?>" size="5" maxlength="20" value="<?php echo "$uniDev" ?>"  onkeyup="" onblur="//save_remi(<?php echo $i; ?>);" style=" width:50px">
</td>
<?php } ?>

<!-- dcto -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" id="dcto_<?php echo $i ?>" type="text" name="dcto_<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$dcto" ?>" onkeyup="valor_t(<?php echo $i ?>);" onblur="dct(<?php echo $i ?>);valMin($('#val_u<?php echo $i ?>'));//save_remi(<?php echo $i; ?>);" style=" width:50px">
<input class="art<?php echo $i ?>" id="dcto_cli<?php echo $i ?>" name="dcto_cli<?php echo $i ?>" type="hidden" value="<?php echo "$dcto" ?>">
<input class="art<?php echo $i ?>" id="tipo_dcto<?php echo $i ?>" name="tipo_dcto<?php echo $i ?>" type="hidden" value="<?php echo "$dcto" ?>">
</td>

<!-- val Uni -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>" name="val_uni<?php echo $i ?>" type="text" onkeyup="puntoa($(this));keepVal('<?php echo "$i" ?>');valor_t(<?php echo "$i" ?>);" value="<?php echo money("$pvp") ?>" onblur="valMin($(this),<?php echo ("$costo") ?>,<?php echo "$PVP" ?>,<?php echo "$i" ?>);change16('<?php echo $i; ?>');//save_remi(<?php echo $i; ?>);" style=" width:70px">

<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>HH" name="val_u<?php echo $i ?>" type="hidden" value="<?php echo "$pvp" ?>">
<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>H" name="val_u<?php echo $i ?>H" type="hidden" value="<?php echo "$pvp" ?>">
<input class="art0" id="valMin<?php echo $i ?>" type="hidden" name="valMin<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$pvp" ?>" style=" width:30px">
<input class="art<?php echo $i ?>" id="val_orig<?php echo $i ?>" name="val_orig<?php echo $i ?>" type="hidden" value="<?php echo "$PVP" ?>">
<input class="art<?php echo $i ?>" id="val_orig2<?php echo $i ?>" name="val_orig2<?php echo $i ?>" type="hidden" value="<?php echo "$PVP" ?>">
<input class="art<?php echo $i ?>" id="val_cre<?php echo $i ?>H" name="val_cre<?php echo $i ?>H" type="hidden" readonly="" value="<?php echo "$pvpCredito" ?>">
</td>

<!-- sub tot -->
<td class="art<?php echo $i ?>">
<input class="art<?php echo $i ?>" id="val_t<?php echo $i ?>" name="val_tot<?php echo $i ?>" type="text" readonly="" value="<?php echo money("$sub_tot") ?>" style=" width:70px">
<input class="art<?php echo $i ?>" id="val_t<?php echo $i ?>HH" name="val_t<?php echo $i ?>" type="hidden" readonly="" value="<?php echo "$sub_tot" ?>">
<input class="art<?php echo $i ?>" id="val_t<?php echo $i ?>H" name="val_t<?php echo $i ?>H" type="hidden" readonly="" value="<?php echo "$sub_tot" ?>">
</td>
<td class="art<?php echo $i ?>">
<img onmouseup="eli_remi_mod($(this).prop('class'))" class="<?php echo $i ?>" src="Imagenes/delete.png" width="31px" heigth="31px">
</td>
</tr>  
<script language="javascript1.5" type="text/javascript">
				call_autocomplete('NOM',$('#num_motor'+cont),'ajax/busq_num_motor.php'); 
			    move($('#'+cont+'cant_'),(cont-1)+'cant_',(cont+1)+'cant_',cont+'cant_','unidades'+cont);
	
				move($('#unidades'+cont),'unidades'+(cont-1),'unidades'+(cont+1),cont+'cant_','dcto_'+cont);
	
				move($('#dcto_'+cont),'dcto_'+(cont-1),'dcto_'+(cont+1),'unidades'+cont,'val_u'+cont);
	
				move($('#val_u'+cont),'val_u'+(cont-1),'val_u'+(cont+1),'dcto_'+cont,'val_u'+cont);
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
            </td>
        </tr>
        
        <tr>
        <td colspan="8">
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
$sql="SELECT * FROM serv_fac_remi WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND cod_su=$codSuc";
//echo "$sql";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	
	$serv=$row["serv"];
	$notaServ=$row["nota"];
	$idServ=$row["id_serv"];
	$codServ=$row["cod_serv"];
	$ivaServ=$row["iva"];
	$pvpServ=$row["pvp"];
	$idTec=$row["id_tec"];
	




?>
<tr>

<!-- ID SERV -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="id_serv<?php echo $i ?>" name="id_serv<?php echo $i ?>" type="text"  value="<?php echo "$idServ" ?>" style=" width:70px" readonly>

<input class="art<?php echo $i ?>" id="<?php echo $i ?>cant_" type="hidden" name="cant_<?php echo $i ?>" size="5" maxlength="20" value="<?php echo "1" ?>"   style=" width:50px">
</td>

<!-- COD SERV -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="cod_serv<?php echo $i ?>" name="cod_serv<?php echo $i ?>" type="text"  value="<?php echo "$codServ" ?>" style=" width:70px" readonly></td>

<!-- SERV -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="serv<?php echo $i ?>" name="serv<?php echo $i ?>" type="text"  value="<?php echo "$serv" ?>" style=" width:200px" readonly></td>


<!-- NOTA -->
<td class="art<?php echo $i ?>">
<textarea style=" width:150px" cols="10" rows="2" class="art<?php echo $i ?>" name="nota<?php echo $i ?>" id="nota<?php echo $i ?>" onBlur="//save_remi(<?php echo $i; ?>);"><?php echo "$notaServ" ?></textarea>
</td>

<!-- IVA SERV -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="iva_<?php echo $i ?>" name="iva_serv<?php echo $i ?>" type="text"  value="<?php echo "$ivaServ" ?>" style=" width:70px" readonly></td>


<!-- val Uni -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>" name="val_serv<?php echo $i ?>" type="text" onkeyup="puntoa($(this));keepVal('<?php echo "$i" ?>');valor_t(<?php echo "$i" ?>);" value="<?php echo money("$pvpServ") ?>" onblur="//save_remi(<?php echo $i; ?>);" style=" width:70px">

<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>HH" name="val_u<?php echo $i ?>" type="hidden" value="<?php echo "$pvpServ" ?>">
<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>H" name="val_u<?php echo $i ?>H" type="hidden" value="<?php echo "$pvpServ" ?>">
<input class="art0" id="valMin<?php echo $i ?>" type="hidden" name="valMin<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$pvpServ" ?>" style=" width:30px">
<input class="art<?php echo $i ?>" id="val_orig<?php echo $i ?>" name="val_orig<?php echo $i ?>" type="hidden" value="<?php echo "$pvpServ" ?>">
<input class="art<?php echo $i ?>" id="val_cre<?php echo $i ?>H" name="val_cre<?php echo $i ?>H" type="hidden" readonly="" value="<?php echo "$pvpServ" ?>">

</td>

<!-- sub tot -->
<td class="art<?php echo $i ?>">
<input class="art<?php echo $i ?>" id="val_t<?php echo $i ?>" name="val_totServ<?php echo $i ?>" type="text" readonly="" value="<?php echo money("$pvpServ") ?>" style=" width:70px" >
</td>


<!-- TECNICO -->
<td class="art<?php echo $i ?>">
<select class="art<?php echo $i ?>" name="tec_serv<?php echo $i ?>" id="tec_serv<?php echo $i ?>" onChange="//save_remi(<?php echo $i; ?>);">
<?php  echo tecOpt($idTec); ?>
</select>
</td>



<td class="art<?php echo $i ?>">
<img onmouseup="eli_serv_mod_remi($(this).prop('class'))" class="<?php echo $i ?>" src="Imagenes/delete.png" width="31px" heigth="31px">
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



?>

</table>
</td>

              </tr>
        
        <tr>
          <td colspan="5">
          <table align="center" frame="box">
          <tr valign="middle" style=" font-size:24px; font-weight:bold;">
             
          <?php 
		if(($rolLv==$Adminlvl || val_secc($id_Usu,"adm_art")) && $codSuc>0){ 
				//$opc="mod_remi";
				$opc="add";
				if($COTIZACION==1){$opc="cotiza_mod";}
			?>
                 <th >Cod. Art&iacute;culo:</th>
                <td><input type="text" name="cod" value="" id="cod" onKeyPress="codx($(this),'<?php echo $opc;?>');" onKeyUp="mover($(this),$('#entrega'),$(this),0);codx($(this),'<?php echo $opc;?>');" /><!-- data-uk-tooltip title="F9: Buscar, ENTER:Ingresar Cod. Barras" -->
                <input type="hidden" value="" id="feVen" name="feVen">
                <input type="hidden" value="" id="Ref" name="Ref">
                </td>
                <td>
                  <img style="cursor:pointer" data-uk-tooltip title="Ver todos los Productos" onMouseUp="busq_all($('#cod'));" src="Imagenes/search128x128.png" width="34" height="31" /><br />
                  <div id="Qresp"></div></td>
                  <?php }?>
               <td>
      <input type="button" value="Guardar" name="botonG"  onMouseUp="save_remi(-1);"/ class="addbtn">
      </td>
      <?php if($MODULES["CARROS_RUTAS"]==1){?>
       <td>
      <input type="button" value="CREAR FACTURA" name="botonG"  onMouseUp="remi_fv();"/ class="addbtn">
      </td>
      <?php  ?>
                <td>&nbsp;</td>
              </tr>
              <?php }if($MODULES["SERVICIOS"]==1){?>
              <tr valign="middle" style=" font-size:16px; font-weight:bold;">
             <td>SERVICIOS:</td> <td><?php  echo selc_serv("serv($(this),'save_remiNULL','eli_serv_mod_remi')");  ?></td>
              </tr>
              <?php }?>
              <tr>
              
              <td>TOTAL PRODUCTOS:</td>
              <TD>
              <input type="text" name="exi_ref" value="<?php echo $n_ref ?>" id="exi_ref" style="font-size:24px; font-weight:bold; width:50px;"  readonly/>
              </TD>
              </tr>
            </table></td>
        </tr>
        <tr id="desc">
          <td colspan="3" rowspan="<?php if($MODULES["ANTICIPOS"]==1){echo "13";}else {echo "12";} ?>" align="center" width="500px" ><br />
            <br />
            <br />
            <br />
            <br />
            <div align="left">
              <textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="40" style="width:300px" class="save_fc">
			  <?php echo "$val_letras" ?></textarea>
              <br>
              <textarea name="nota_fac" id="nota_fac"   cols="40" placeholder="NOTAS" style="width:300px;
-webkit-border-radius:19px;
-moz-border-radius:19px;
border-radius:19px;
border:6px solid rgb(201, 38, 38);" class="save_fc"><?php echo "$nota_fac" ?></textarea>
            </div></td>
          <th style="background-color: #000; color:#FFF" width="300px">Base IVA:</th>
          <td align="right"><input  id="SUB" type="text" readonly="" value="<?php echo money("$SUB") ?>"   name="sub" class="save_fc"/>
            <input type="hidden" name="SUB" value="<?php echo "$SUB" ?>" id="SUBH" class="save_fc"/>
            
            </td>
            <td>
            
           <input  readonly name="dcto" id="DESCUENTO" type="hidden" value="<?php echo "$DESCUENTO" ?>" onKeyUp="" class="save_fc"/>
           
           
           <input id="EXCENTOS" type="hidden" readonly="" value="0" name="exc" class="save_fc"/>
            
            </td>
        </tr>
        	<tr>
          <th style="background-color: #000; color:#FFF">Dcto:</th>
          <td align="right">
          
          <input placeholder="%" id="DCTO_PER" type="text"  value="" name="DCTO_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#DESCUENTO2'));" onBlur="dctoB();" class="<?php if($COTIZACION!=1)echo "uk-hidden";?>"/>
          <input name="dcto2" id="DESCUENTO2" type="text" value="<?php echo money("$DESCUENTO") ?>" onKeyUp="dctoB();" class="save_fc"/>
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
          <td align="right"><input name="iva" readonly="readonly" id="IVA" type="text" value="<?php echo money("$IVA") ?>" class="save_fc"/>
            <input id="IVAH" type="hidden" name="IVA" value="<?php echo "$IVA" ?>" class="save_fc"/></td>
        </tr>
        <?php
		}
		?>
        
        
        
        <tr class="uk-hidden">
          <td  align="center" colspan="" class="tot_fac">TOTAL Cr&eacute;dito:
          
          </td>
          
          <td colspan="" align="right"><input id="TOT_CRE" type="text" value="<?php echo money("$TOT_CRE") ?>" name="TOT_CRE" class="save_fc" onKeyUp="puntoa($(this));" onBlur="save_remi(-1);"/>
          </td>
          </tr>
        
        
        <tr >
          <td  align="center" colspan="" class="tot_fac">TOTAL (Pesos):
          <input class="uk-button uk-button-success uk-hidden save_fc" data-uk-toggle="{target:'.bsf'}" value="BsF" type="button"   onMouseDown="//change($('#entrega'));" />
          </td>
          
          <td colspan="" align="right"><input id="TOTAL" type="text" readonly="" value="<?php echo money("$TOT") ?>" name="tot" class="save_fc"/>
          </td>
          </tr>
          <tr id="bsf"  class="uk-hidden bsf">
          
          <td align="right" style="font-size:24px;
font-family:Georgia,serif;
color:rgb(255, 64, 0);
font-weight:bold;
font-style:italic;">
        
          TOTAL (BsF) </td>
          <td align="right"><input id="TOTAL_BSF" type="text" readonly="" value="<?php echo money("$TOT_BSF") ?>" name="totB" class="save_fc"/>
         
          </td>
          </tr>
          <?php if($MODULES["ANTICIPOS"]==1){ ?>
           <tr style="background-color:#000; color:#FFF" class="uk-hidden">
      <td  align="center" colspan="">Anticipo/Bono:</td>
      <td colspan="" align="right"><input id="anticipo" type="text"  value="<?php echo money("$abon_anti") ?>" name="anticipo" onKeyUp="change($(this));" onBlur="change($(this));"  readonly class="save_fc"/>
        <input type="hidden" name="num_exp" id="num_exp" value="<?php echo "$num_exp" ?>"  class="save_fc"/></td>
    </tr>
          
          
          <?php } 
		  else{
		  ?>
          <input id="anticipo" type="hidden"  value="<?php echo money("$abon_anti") ?>" name="anticipo" onKeyUp="change($(this));" onBlur="change($(this));"  readonly class="save_fc"/>
        <input type="hidden" name="num_exp" id="num_exp" value="<?php echo "$num_exp" ?>"  class="save_fc"/>
          
          <?php
		  }
          if($usar_iva==0){
		?>
        <input name="iva" readonly="readonly" id="IVA" type="hidden" value="<?php echo money("$IVA") ?>" class="save_fc"/>
        <input id="IVAH" type="hidden" name="IVA" value="<?php echo "$IVA" ?>" class="save_fc"/>
        <?php
		}
		?>
          </td>
        </tr>
        
        <tr class="uk-hidden">
          <td  align="right" colspan="">R. FTE:<input placeholder="%" id="R_FTE_PER" type="text"  value="<?php echo $R_FTE_PER ?>" name="R_FTE_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_FTE'));" class="save_fc"/></td>
          <td colspan="" align="right"><input id="R_FTE" type="text"  value="<?php echo money($R_FTE*1) ?>" name="R_FTE" class="save_fc" />
          </td>
          </tr>
          
          <tr class="uk-hidden">
          <td  align="right" colspan="">R. IVA:<input placeholder="%" id="R_IVA_PER" type="text"  value="<?php echo $R_IVA_PER ?>" name="R_IVA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#IVA'),$('#R_IVA'));" class="save_fc"/></td>
          <td colspan="" align="right"><input id="R_IVA" type="text"  value="<?php echo money($R_IVA*1) ?>" name="R_IVA"  class="save_fc"/>
          </td>
          </tr>
          
          <tr class="uk-hidden">
          <td  align="right" colspan="">R. ICA:<input placeholder="%" id="R_ICA_PER" type="text"  value="<?php echo $R_ICA_PER ?>" name="R_ICA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_ICA'));" class="save_fc"/></td>
          <td colspan="" align="right"><input id="R_ICA" type="text"  value="<?php echo money($R_ICA*1) ?>" name="R_ICA" class="save_fc"/>
          </td>
          </tr>
           <tr class="uk-hidden">
      <th  align="center" colspan="">VALOR A PAGAR:</th>
      <td colspan="" align="right"><input id="TOTAL_PAGAR" type="text" value="<?php echo money($TOT_PAGAR*1) ?>"  name="TOTAL_PAGAR"  readonly class="save_fc"/></td>
    </tr>
        <tr class="uk-hidden">
          <td  align="center" colspan="">Pago Efectivo(Pesos):</td>
          <td colspan="" align="right"><input id="entrega" type="text"  value="<?php echo money("$entrega") ?>" name="entrega" onKeyUp="mover($(this),$('#cod'),$(this),0);change($(this));" onBlur="change($(this));" class="save_fc"/></td>
        </tr>
        <tr class="uk-hidden bsf">
          <td  align="center" colspan="" >Pago Efectivo(bsF):</td>
          <td colspan="" align="right"><input id="entrega2" type="text"  value="<?php echo money("$entregaBSF") ?>" name="entrega2" onKeyUp="mover($(this),$('#cod'),$(this),0);change($(this));" onBlur="change($(this));" class="save_fc"/></td>
        </tr>
        <tr class="uk-hidden">
          <td  align="center" colspan="">Cambio:</td>
          <td colspan="" align="right"><input  id="cambio" type="text"  value="<?php echo money("$cambio") ?>" name="cambio" readonly="readonly" class="save_fc"/>
          
          <div id="cambio_pesos" style="font-weight: bold; font-size:24px; color:#F00"></div></td>
        </tr>
        
      </table>
      
      
      <?php
	  
	  
}// fin for fac_ven valida+

?>
      </div>
      <span class="Estilo2"></span><br />
       <input type="hidden" name="boton" value="" id="genera" />
            <input type="hidden" name="num_ref" value="<?php echo $n_ref ?>" id="num_ref" />
            
            <input type="hidden" name="num_fac_venta" value="<?php echo $num_fac ?>" id="num_fac" class="save_fc"/>
            <input type="hidden" name="pre" value="<?php echo $pre ?>" id="pre" class="save_fc"/>
            
            <input type="hidden" name="cod_orig" value="<?php echo $codOrigen ?>" id="cod_orig" class="save_fc"/>
            <input type="hidden" name="cod_dest" value="<?php echo $codDest ?>" id="cod_dest" class="save_fc"/>
             <input type="hidden" name="co" value="<?php echo "$COTIZACION"  ?>" id="co" />
             
             
<input type="hidden" name="tope_credito" value="0" id="tope_credito" class="save_fc"/>
<input type="hidden" name="total_credito" value="0" id="total_credito" class="save_fc"/>
            
            <input type="hidden" name="num_serv" value="0" id="num_serv" />
            <input type="hidden" name="modREMI" value="1" id="modREMI" />
            <input type="hidden" name="exi_serv" value="0" id="exi_serv" />
            <input type="hidden" name="remision" value="1" id="remision" />
            <input type="hidden" value="" name="html" id="pagHTML">
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
<h1 style="color:#000">REGISTRA ELECTRODOMESTICO</h1>
<table>
<tr>

<td>
<input type="text" value="" name="placa_ve" id="placa_ve" placeholder="NUMERO DE SERIE" class="VEHI" onChange="">
</td>

<td>

<select name="modelo_ve" id="modelo_ve" class="VEHI">
<option value="AIRE">AIRE</option>
<option value="AIRE">NEVERA</option>
<option value="AIRE">LAVADORA</option>
<option value="COCINA">COCINA</option>
</select>
</td>
<td>
<input type="text" value="" name="color_ve" id="color_ve" placeholder="COLOR" class="VEHI">
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
<?php include_once("js_global_vars.php"); ?>
<?php include_once("FOOTER2.php"); ?>
<?php include_once("keyFunc_fac_ven.php"); ?>


<script language="javascript1.5" type="text/javascript" src="JS/num_letras.js"></script>
<script type="text/javascript" language="javascript1.5" src="JS/jQuery_throttle.js"></script>
<script language="javascript1.5" type="text/javascript">

var dcto_remi=0;
var HH=<?php echo 0 ?>;
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
    };

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
	
	<?php 
		if(($rolLv==$Adminlvl || val_secc($id_Usu,"adm_art")) && $codSuc>0){ }
		else{echo '$("#articulos").find("input,button,textarea,select").prop("readonly", "readonly");$("#DESCUENTO2").prop("readonly", "readonly");';}
			?>
	
$('[data-uk-switcher]').on('show.uk.switcher', function(event, area){
    console.log("Switcher switched to ", area);
});

$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});
//envia($('#entrega'));
move($('#cod'),cont+'cant_','entrega','','');
call_autocomplete('NOM',$('#cli'),'ajax/busq_cli.php');

$('input').on("change",function(){$(this).prop('value',this.value);});
$('textarea').on("change",function(){$(this).html(this.value);});
$('#cod').focus();
$('#loader').hide();
	//$('.bsf').hide();
//alert('fac_kardex'+kardex);


	$('#val_frt').hide();
	$('#log_serv').hide();
	$('#revision').hide();
	$('#alas').hide();
	$('#hh').hide();
	$('#precio_servA').hide();
	
	
	
	
	
	$('#loader').ajaxStart(function(){
		$(this).show();
		$('input[type=button]').prop("disabled","disabled").css("color","red");alert("ajax!");
		})
	.ajaxSuccess(function(){
		$(this).hide();
		$('input[type=button]').removeAttr("disabled").css("color","black");
		});
	
	//$('#loader').ajaxError(function(){$('input[type=button]').prop("disabled","disabled").css("color","red");$(this).hide();});


	

});


</script> 

</body>
</html>