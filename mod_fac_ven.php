<?php
include_once('Conexxx.php');
/*
RELACIONADOS:
AJAX/
	save_fv.php
*/
$hide=!empty($tokenDianOperaciones)?'visibility:hidden':'';
$hide='';
 
$ukIconSize="uk-icon-large";
$ukFormSize="uk-form-large";
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"fac_mod"))
{header("location: centro.php");}
valida_session();
cajas($horaCierre,$horaApertura,$hora,$conex,$codSuc);

$boton=r('opc');
$num_fac=r("num_fac_venta");
$pre=r("pre");
$hash=r("hash");
$filtroHash=" AND hash='$hash'";
if(empty($hash)){$filtroHash="";}
$rs=$linkPDO->query("SELECT *,TIME(fecha) as hora, DATE(fecha) as fecha FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc' $filtroHash");

$n_ref=0;


$TC=tasa_cambio();
$MesaID="";
if($MODULES["mesas_pedidos"]==1){
$SubQ="SELECT * FROM mesas WHERE  num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su='$codSuc'";
$rsSubQ=$linkPDO->query($SubQ);	
$rowSubQ=$rsSubQ->fetch();
$MesaID = $rowSubQ['id_mesa'];

}




?>

<!DOCTYPE html>
<html>
<head>
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


<div class="uk-width-1-1 uk-container-center">
<nav class="uk-navbar">
<ul class="uk-navbar-nav">
<li><a href="<?php echo "ventas.php" ?>" ><i class="uk-icon-list <?php echo $uikitIconSize ?>"></i>&nbsp;Lista Facturas</a></li>
<li><a href="<?php echo thisURL(); ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>


<li class="<?php if($usar_bsf!=1) echo "uk-hidden" ; ?>"><a href="#tasa_cambio" data-uk-modal><i class="uk-icon-money <?php echo $uikitIconSize ?>"></i>&nbsp;TASA CAMBIO </a></li>

<?php if($MODULES["mesas_pedidos"]==1){ 
$S="SELECT * FROM mesas WHERE id_mesa='$MesaID'";
$RS_M=$linkPDO->query($S);
$ROW_M=$RS_M->fetch();
$numMesa=$ROW_M["num_mesa"];

?>

<li><a href="<?php echo thisURL()."&opc=Imprimir"; ?>" ><i class="uk-icon-print <?php echo $uikitIconSize ?>"></i>&nbsp;Imprimir Orden</a></li>

<li class="" >
  <a href="#" data-uk-modal style="font-size:14px;background-color:rgba(255, 183, 0, 0.52);border-style:solid;border-color:rgb(255, 13, 13);">
  <i class="uk-icon-cutlery <?php echo $uikitIconSize ?>"></i>&nbsp;MESA <span style="font-size:26px;">#<?php echo $numMesa ?></span>
</a>
</li>

<li><a href="MESAS.php" ><i class="uk-icon-sticky-note-o <?php echo $uikitIconSize ?>"></i>&nbsp;Ver Mesas</a></li>

<?php }?>

</ul>

</nav>


<form action="<?php echo "fac_venta.php?num_fac_venta=$num_fac&pre=$pre" ?>" name="form_fac" method="post" id="form-fac" autocomplete="off" class="uk-form uk-form-stacked">
<div class="loader uk-hidden"> <img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" class="uk-hidden" /> 
</div>

      
      
<?php      
if($row=$rs->fetch()){
	
$MesaID=$row['num_mesa'];	
$IMP_CONSUMO=$row["imp_consumo"];

$nomCli=$row['nom_cli'];
	$snombr=$row["snombr"];
	$apelli=$row["apelli"];
	
	$claper=$row["claper"];
	
	$coddoc=$row["coddoc"];
	$paicli=$row["paicli"];
	$depcli=$row["depcli"];
	$loccli=$row["loccli"];
	$nomcon=$row["nomcon"];
	$regtri=$row["regtri"];
	$razsoc=$row["razsoc"];
	
$idCli=$row['id_cli'];
$telCli=$row['tel_cli'];
$dirCli=$row['dir'];
$mailCli=$row['mail'];
$ciudadCli=$row['ciudad'];

$hash=$row["hash"];

$meca=$row["mecanico"];
$meca2=$row["tec2"];
$meca3=$row["tec3"];
$meca4=$row["tec4"];

$nota_fac=$row["nota"];
$nota_domicilio=$row["NORECE"];

$vendedor=nomTrim($row['vendedor']);	
$tipoPago=$row['tipo_venta'];
$tipoCli=$row['tipo_cli'];
$pagare=$row['num_pagare'];
$fecha_hora=$row['fecha']."T".$row['hora'];

$SUB=$row['sub_tot'];
$DESCUENTO=$row['descuento']*1;
$DESCUENTO_IVA=$row['DESCUENTO_IVA']*1;
$IVA=$row['iva'];
$TOT=$row['tot'];
$TOT_BSF=$row['tot_bsf'];

$val_letras=$row['val_letras'];
$entrega=$row['entrega'];
$entrega3=$row['tot_tarjeta'];
$cambio=$row['cambio'];
$entregaBSF=$row['entrega_bsf'];

$abon_anti=$row['abono_anti'];	
$num_exp=$row['num_exp'];
$confirm_anti=$row["anticipo_bono"];

$R_FTE=$row['r_fte'];
$R_IVA=$row['r_iva'];
$R_ICA=$row['r_ica'];

$marcaMoto=$row["marca_moto"];

$R_FTE_PER=$row['per_fte']*1;
$R_IVA_PER=$row['per_iva']*1;
$R_ICA_PER=$row['per_ica']*1;

$idCta=$row["id_cuenta"];
$TOT_PAGAR=$TOT-($R_FTE+$R_ICA+$R_IVA);


$km=$row["km"];
$placa=$row["placa"];

$NUM_BOLSAS=$row["num_bolsas"];

$codComision=$row["cod_comision"];
$tipoComision=$row["tipo_comi"];

//$tipoPago=$row['tipo_venta'];
 ?>
<div class="  uk-width-9-10 uk-container-center" id="factura_venta">  
<table id="fac_venta_table2" border="0" align="center" cellspacing="0" cellpadding="0" class="round_table_white uk-form-small uk-table uk-table-striped    " style="color:#000"  width="100%">
        <tr>
          <td colspan="5">
          <table width="100%">
              <tr>
                <td colspan="2" style="font-size:19px;font-weight:bold;">
                <i class="uk-icon-cc-mastercard <?php echo $ukIconSize ?>"></i>
                  <select style="width:150px;" id="form_pago" name="form_pago" onChange="creco(this.value,'credito','contado');call_tot();"  class="save_fc uk-text-primary  uk-text-bold uk-form-success <?php echo $ukFormSize ?>">
                    <option value=""></option>
                    <option value="Contado"  <?php if($tipoPago=="Contado")echo "selected"; ?>>Contado</option>
                    <option value="Cheque"  <?php if($tipoPago=="Cheque")echo "selected"; ?>>Cheque</option>
                    <?php if($rolLv>=$Adminlvl|| val_secc($id_Usu,"vende_credito")){?>
                    <option value="Credito" <?php if($tipoPago=="Credito")echo "selected"; ?>>Cr&eacute;dito</option>
                    <?php }?>
                    <option value="Tarjeta Credito" <?php if($tipoPago=="Tarjeta Credito")echo "selected"; ?>>Tarjeta Credito</option>
                    <option value="Tarjeta Debito" <?php if($tipoPago=="Tarjeta Debito")echo "selected"; ?>>Tarjeta Debito</option>
                     <option value="Transferencia Bancaria" <?php if($tipoPago=="Transferencia Bancaria")echo "selected"; ?>>Transferencia Bancaria</option>
                  </select></td>
                  <TD valign="bottom" class="<?php if($MODULES["PVP_CREDITO"]!=1){echo "uk-hidden";}?>"><input type="button" value="Aplicar PvP CREDITO" onClick="<?php if($MODULES["PVP_CREDITO"]==1) echo "cambiaFP();"; ?>" class="uk-button uk-button-primary">
                  
                  </TD>
                  <?php if($MODULES["CUENTAS_BANCOS"]==1){?>
                  <td style="font-size:16px;font-weight:bold;" colspan="">
                  <?php if($MODULES["CUENTAS_BANCOS"]==1)echo "<label class=\"uk-form-label\" for=\"id_cuenta\">Cuenta </label>".selcCta("","uk-text-primary  uk-text-bold uk-form-success save_fc",$idCta); ?>
                  </td>
                  <?php }?>
                  <?php if($MODULES["ANTICIPOS"]==1){ ?>
                   <td colspan="">Bono/Anticipo:<br>
                  <select id="
" name="confirm_bono" onChange="" class="save_fc">
                    
                    <option value="SI" <?php if($confirm_anti=="SI")echo "selected"; ?>>Si</option>
                    <option value="NO" <?php if($confirm_anti!="SI")echo "selected"; ?>>No</option>
                     
                  </select></td>
                  <?php }?>
                <td colspan="2">Tipo de Cliente:
                  <select name="tipo_cli" id="tipo_cli" class="save_fc" style="width:100px;">
                    <option value=""></option>
                    <option value="Mostrador" selected>Mostrador (P&uacute;blico)</option>
                   <option value="Empresas" <?php if($tipoCli=="Empresas")echo "selected"; ?>>Empresas (+16%)</option>
                   <option value="Taller" <?php if($tipoCli=="Taller")echo "selected"; ?>>Taller</option>
                    <!--<option value="Empleados">Empleados</option>-->
                  </select></td>
                <td colspan="2" align="left"><i class="uk-icon-user <?php echo $ukIconSize ?>"></i>
                  <select name="vendedor" id="vendedor"  style="width:100px" class="save_fc"   >
                   
                    <?php
		  
		  $rs=$linkPDO->query("SELECT nombre,a.id_usu FROM usuarios a INNER JOIN (SELECT a.estado,b.id_usu,b.des FROM usu_login a INNER JOIN tipo_usu b ON a.id_usu=b.id_usu WHERE (des='Vendedor' OR des='Caja' OR des='Inventarios' OR des='Administrador' OR des='Conductor') AND a.estado!='SUSPENDIDO') b ON b.id_usu=a.id_usu   ORDER BY nombre");
		  while($row=$rs->fetch()){
			  $idVendedor=$row["id_usu"];
			  $vende= nomTrim($row["nombre"]);
		  ?>
<option value="<?php echo "$vende-$idVendedor" ?>" <?php if($vende==$vendedor)echo "selected"; ?> ><?php echo $vende?></option>
                    <?php
		  }
		  ?>
                  </select></td>
              
              </tr>
              <!--
              <?php
			  
			  if($MODULES["SERVICIOS"]==1){
			  
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
                  
                  <td colspan="2" align="left">T&eacute;cnico 2:<br>
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
                  </select></td>
                  
                  
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
              -->
              <tr>
                <td width="61" colspan="1"  >Fecha:</td>
                <td width="144" colspan="2" >
              
                <input  id="fecha" type="datetime-local" value="<?php echo $fecha_hora; ?>" name="fecha" class="save_fc"  <?php if($rolLv!=$Adminlvl){echo "readonly";}?>/>
               
			
                </td>
                <td colspan="2"  > <h1   class="uk-text-primary uk-text-bold">VENTA</h1>
                 
                  </td>
                <TD colspan="2" style="color:#F00; font-size:42px;"  class="uk-text-bold uk-text-danger uk-text-large">
              
                <?php echo "$pre &nbsp; $num_fac" ?>
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
<td>
<select name="tipo_comi" id="tipo_comi" class="uk-button-success save_fc">
<option value="" selected>Seleccione Tipo Comisi&oacute;n</option>
<option value="<?php echo "$tipoComision";?>" selected><?php echo "$tipoComision";?></option>
<option value="Venta Directa">Venta Directa(Venta al Mec&aacute;nico)</option>
<option value="Venta Cliente">Venta Cliente</option>
</select>
</td>
</tr>
<?php }?>    

              
<tr style="" id="pagare" class="<?php if($MODULES["NUM_NOTA_ENTREGA"]!=1){echo "uk-hidden";}?>">
              
              
              <td>NOTA ENTREGA:</td>
              <td><input type="text" value="<?php echo "$pagare" ?>" name="num_pagare" id="num_pagare" class="save_fc"></td>
             <td colspan="2">PLACA: <input type="text" name="placa" value="<?php echo "$placa" ; ?>" id="placa" onChange="" class="save_fc"></td>
              </tr>
           
              <tr>
                <td >Nombre:</td>
                <?php if($MODULES["FACTURACION_ELECTRONICA"]==1){
					?>

<td ><input name="cli" type="text" id="cli" value="<?php echo "$nomCli" ?>" onKeyUp="//get_nom(this,'add');mover($(this),$('#cod'),$(this),0);" class="save_fc" onBlur="busq_cli(this);"/></td>
<td>Nombre 2:</td>
<td><input name="snombr" type="text" id="snombr" value="<?php echo "$snombr"; ?>" onChange="" class="save_fc"/>
</td>
<td>Apellidos:</td>
<td><input name="apelli" type="text" id="apelli" value="<?php echo "$apelli"; ?>" onChange="" class="save_fc"/>
</td>

</tr>
					
					
					<?php
					}
				
				else{?>
                <td ><input name="cli" type="text" id="cli" value="<?php echo "$nomCli" ?>" onKeyUp="//get_nom(this,'add');mover($(this),$('#cod'),$(this),0);" class="save_fc" onBlur="busq_cli(this);"/></td>
                <?php }?>
                <td>C.C./NIT:</td>
                <td><input name="ced" type="text" value="<?php echo "$idCli" ?>" id="ced"  onchange="busq_cli(this)" class="save_fc"/></td>
                <td>Ciudad:</td>
                <td><input name="city" type="text" id="city" value="<?php echo "$ciudadCli" ?>" onChange="valida_doc('cliente',this.value);" class="save_fc" /></td>
                <!--
                <td>Fecha nacimiento:</td>
                <td><input size="10" name="fe_naci" value="" type="date" id="fe_naci" onClick="//popUpCalendar(this, form_fac.fe_naci, 'yyyy-mm-dd');"  placeholder="Fecha Nacimiento"></td>
                -->
                <?php if($MODULES["VEHICULOS"]==1){?>
                <td>
                PLACA:
                <a href="#OT_VEHI" data-uk-modal>REGISTRAR</a>
                </td>
                <td>
                <input type="text" name="placa" value="<?php echo "$placa" ; ?>" id="placa" onChange="val_placa($(this));" class="save_fc">
                </td>
                <?php } ?>
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
                <td colspan=""><input name="km"  type="text" value="<?php echo "$km"; ?>" id="km" placeholder="Kil&oacute;metros del Veh&iacute;culo" class="save_fc"/></td>
                <?php }?>
              </tr>
               
             
            </table></td>
        </tr>
        <tr>
          <td colspan="5"><table id="articulos" width="100%" border="1px" cellpadding="0" cellspacing="0">
              <tr style="background-color: #000; color:#FFF">
                <td class="uk-hidden-touch"><div align="center"><strong>Referencia</strong></div></td>
                <td class="uk-hidden-touch"><div align="center"><strong>Cod. Barras</strong></div></td>
                 <?php if($usar_serial==1){ ?>
                <td><div align="center"><strong>Serial</strong></div></td>
                 <?php } ?>
                 
                  <?php if($MODULES["COD_GARANTIA"]==1){ ?>
                <td><div align="center"><strong>Garantia</strong></div></td>
                 <?php } ?>
                 
                <td><div align="center"><strong>Producto</strong></div></td>
                <td class="uk-hidden-touch"><div align="center"><strong>Presentaci&oacute;n</strong></div></td> 
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
                
                <td class="uk-hidden-touch"><div align="center"><strong>I.V.A</strong></div></td>
                <td height="28"><div align="center"><strong>Cant.</strong></div></td>
                <?php if($usar_fracciones_unidades==1){ ?>
                <td height="28"><div align="center"><strong>Fracci&oacute;n</strong></div></td>
                <?php } ?>
                <td><div align="center"><strong>Dcto</strong></div></td>
                <td><div align="center"><strong>Precio</strong></div></td>
                <td colspan="2"><div align="center"><strong>Subtotal</strong></div></td>
              </tr>
              
<?php
if($vender_sin_inv==0 && $FLUJO_INV==1)
{
$sql="SELECT a.costo,a.precio,a.sub_tot,a.dcto,a.des,a.cant,a.fraccion,a.unidades_fraccion,a.color,a.talla,a.ref,a.cod_barras,a.fecha_vencimiento as feven,a.iva as IVA,a.presentacion as presen,a.serial,i.pvp_credito,i.exist,i.unidades_frac,i.precio_v,cod_garantia,num_motor FROM art_fac_ven a LEFT JOIN inv_inter i ON i.id_inter=a.cod_barras WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc' $filtroHash AND nit_scs='$codSuc' AND a.fecha_vencimiento=i.fecha_vencimiento AND a.ref=i.id_pro  ORDER BY orden_in ASC";
}
else {
$sql="SELECT a.costo,a.precio,a.sub_tot,a.dcto,a.des,a.cant,a.fraccion,a.unidades_fraccion,a.color,a.talla,a.ref,a.cod_barras,a.fecha_vencimiento as feven,a.iva as IVA,a.presentacion as presen,a.serial,precio as pvp_credito,(cant+1000) as exist,fraccion as unidades_frac,precio as precio_v,cod_garantia,num_motor FROM art_fac_ven a  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc' $filtroHash ORDER BY orden_in ASC";

	}

  $sql="SELECT a.costo,
        a.precio,
        a.sub_tot,
        a.dcto,
        a.des,
        a.cant,
        a.fraccion,
        a.unidades_fraccion,
        a.color,
        a.talla,
        a.ref,
        a.cod_barras,
        a.fecha_vencimiento as feven,
        a.iva as IVA,
        a.presentacion as presen,
        a.serial,precio as pvp_credito,
        a.imprimir,
        (cant+1000) as exist,
        fraccion as unidades_frac,
        precio as precio_v,
        cod_garantia,num_motor 
        FROM art_fac_ven a  
        WHERE num_fac_ven='$num_fac' AND prefijo='$pre' 
        AND nit='$codSuc' $filtroHash ORDER BY orden_in ASC";

//echo "$sql";
$rs=$linkPDO->query($sql);
$i=0;
$No=0;
$rbg="background-color:rgba(200,200,200,1)";
$n_cant=0;
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
$pvpCredito=$pvp;
$sub_tot=$row['sub_tot'];
$imprimirComanda = $row['imprimir'] ==1 ? 'checked' : '';
 


$cant = $row["cant"]*1;
$n_cant+=$cant;
$fracc=$row['fraccion'];
if($fracc<=0)$fracc=1;
$uni = $row["unidades_fraccion"]*1;
$factor=($uni/$fracc)+$cant;
$cod_garantia=$row["cod_garantia"];
$num_motor=$row["num_motor"];

$UNI=$row['unidades_frac'];

$PVP=0;
$sqlAUX="SELECT exist,precio_v,pvp_credito FROM inv_inter a WHERE id_pro='$ref' AND id_inter='$cod_barras'";
$rsAUX=$linkPDO->query($sqlAUX);
if($rowAUX=$rsAUX->fetch())
{
$exist=$rowAUX['exist']+$cant;
$PVP=$rowAUX['precio_v']*1;
$pvpCredito=$rowAUX['pvp_credito']*1;
if($pvpCredito==0)$pvpCredito=$PVP;
	
}
else {$exist=100000;}

$exist=100000;
if($FLUJO_INV!=1)$exist=100000;

 
$valBase=$costo*1.03;
if($vende_sin_cant==1 || $dctos_ropa==1)$valBase=1;
?>
  
<tr id="tr_<?php echo $i ?>" class="art<?php echo $i ?>">

<!-- REF -->
<td class="art<?php echo $i ?> uk-hidden-touch" align="center">
<input class="art<?php echo $i ?>" readonly="" value="<?php echo "$ref" ?>" type="text" id="ref_<?php echo $i ?>" name="ref_<?php echo $i ?>" style=" width:80px">
<input class="art<?php echo $i ?>" readonly="" value="0" type="hidden" id="orden_in<?php echo $i ?>" name="orden_in<?php echo $i ?>" style=" width:80px">
</td>


<!-- cod. barras -->
<td class="art<?php echo $i ?> uk-hidden-touch" align="center">
<input class="art<?php echo $i ?>" value="<?php echo "$cod_barras" ?>" type="text" id="cod_bar<?php echo $i ?>" name="cod_bar<?php echo $i ?>" placeholder="" style=" width:130px" readonly="">
</td>

<?php if($usar_serial==1){ ?>
<!-- S/N -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" value="<?php echo "$SN" ?>" type="text" id="SN<?php echo $i ?>" name="SN_<?php echo $i ?>" placeholder="S/N" style=" width:100px" onBlur="save_fv(<?php echo $i; ?>);">
</td>
<?php } ?>


<?php if($MODULES["COD_GARANTIA"]==1){ ?>
<!-- GARANTIA -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" value="<?php echo "$cod_garantia" ?>" type="text" id="COD_GARANTIA<?php echo $i ?>" name="COD_GARANTIA<?php echo $i ?>" placeholder="Garantia" style=" width:100px" onBlur="save_fv(<?php echo $i; ?>);">
</td>
<?php } ?>

<!-- descripcion -->
<td class="art<?php echo $i ?>">
<textarea style=" width:150px" cols="10" rows="2" class="art<?php echo $i ?>" name="det_<?php echo $i ?>" id="det_<?php echo $i ?>" onBlur="save_fv(<?php echo $i; ?>);"><?php echo "$des" ?></textarea>
</td>

 
 
<td class="art<?php echo $i ?> uk-hidden-touch">
<input class="art<?php echo $i ?>" value="<?php echo "$presentacion" ?>" type="text" id="presentacion<?php echo $i ?>" name="presentacion<?php echo $i ?>" placeholder="" style=" width:100px" onBlur="save_fv(<?php echo $i; ?>);">
</td>
 
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
<input class="art<?php echo $i ?>" value="<?php echo "$talla" ?>" type="text" id="talla<?php echo $i ?>" name="talla<?php echo $i ?>" placeholder="" style=" width:30px" >
</td>
<?php } ?>

<!-- num_motor -->
<?php if($usar_datos_motos==1){ ?>
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" value="<?php echo "$num_motor" ?>" type="text" id="num_motor<?php echo $i ?>" name="num_motor<?php echo $i ?>" placeholder="" style=" width:100px; font-size:11px;" onblur="save_fv(<?php echo $i; ?>);">
</td>
<?php }?>

<!-- iva -->
<td class="art<?php echo $i ?> uk-hidden-touch" align="center">
<input class="art<?php echo $i ?>" id="iva_<?php echo $i ?>" type="text" name="iva_<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$iva" ?>" onkeyup="valor_t(<?php echo $i ?>);" style=" width:50px" onBlur="save_fv(<?php echo $i; ?>);">
</td>

<!-- cant -->
<td class="art<?php echo $i ?>" align="center">

 
<input class="art<?php echo $i ?> fc_cant" id="<?php echo $i ?>cant_" type="number" name="cant_<?php echo $i ?>" size="5" maxlength="20" value="<?php echo "$cant" ?>"  onkeyup="calc_uni($('#<?php echo $i ?>cant_'),$('#fracc<?php echo $i ?>'),$('#unidades<?php echo $i ?>'));valor_t(this.id);" onblur="cant_dcto(this.id);valor_t(this.id);save_fv(<?php echo $i; ?>);" style=" width:50px">

<input class="art<?php echo $i ?>" id="<?php echo $i ?>cant_L" type="hidden" name="cant_L<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$exist" ?>" style=" width:50px">
<div class="uk-button-group"><a class="uk-button" onClick="restaCant($('#<?php echo $i ?>cant_'));"><i class="uk-icon uk-icon-minus-square"  ></i> </a><a class="uk-button" onClick="sumaCant($('#<?php echo $i ?>cant_'));"> <i class="uk-icon uk-icon-plus-square"></i> </a></div>


</td>


<!-- fracc. -->
<?php if($usar_fracciones_unidades==1){ ?>
<td class="art<?php echo $i ?>">
<input class="art<?php echo $i ?>" value="<?php echo "$uni" ?>" type="text" id="unidades<?php echo $i ?>" name="unidades<?php echo $i ?>" placeholder="" style="width:80px" onkeyup="calc_cant($('#<?php echo $i ?>cant_'),$('#fracc<?php echo $i ?>'),$('#unidades<?php echo $i ?>'));valor_t(<?php echo $i ?>);" onBlur="save_fv(<?php echo $i; ?>);">

<input class="art<?php echo $i ?>" value="<?php echo "$fracc" ?>" type="hidden" id="fracc<?php echo $i ?>" name="fracc<?php echo $i ?>" placeholder="" style=" width:80px" readonly="">

<input class="art<?php echo $i ?>" value="<?php echo "$UNI" ?>" type="hidden" id="unidadesH<?php echo $i ?>" name="unidadesH<?php echo $i ?>" placeholder="" style=" width:80px" readonly="">
</td>
<?php } ?>


<!-- dcto -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" id="dcto_<?php echo $i ?>" type="text" name="dcto_<?php echo $i ?>" size="5" maxlength="5" value="<?php echo $dcto*1 ?>" onkeyup="valor_t(<?php echo $i ?>);" onblur="" onChange="dct(<?php echo $i ?>);valMin($('#val_u<?php echo $i ?>'),<?php echo "$costo" ?>,<?php echo "$PVP" ?>,<?php echo "$i" ?>);save_fv(<?php echo $i; ?>);" style=" width:50px" <?php if($rolLv!=$Adminlvl){echo "readonly";}?>>

<input class="art<?php echo $i ?>" id="dcto_cli<?php echo $i ?>" name="dcto_cli<?php echo $i ?>" type="hidden" value="<?php echo "$dcto" ?>">
<input class="art<?php echo $i ?>" id="tipo_dcto<?php echo $i ?>" name="tipo_dcto<?php echo $i ?>" type="hidden" value="<?php echo "$dcto" ?>">
</td>

<!-- val Uni -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>" name="val_uni<?php echo $i ?>" type="text" onkeyup="puntoa($(this));keepVal(<?php echo $i; ?>);valor_t(<?php echo "$i" ?>);" value="<?php echo money("$pvp") ?>" onblur="valMin($(this),<?php echo "$valBase" ?>,<?php echo "$pvp" ?>,<?php echo "$i" ?>);change16('<?php echo $i; ?>');save_fv(<?php echo $i; ?>);" style=" width:70px">

<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>HH" name="val_u<?php echo $i ?>" type="hidden" value="<?php echo "$pvp" ?>">
<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>H" name="val_u<?php echo $i ?>H" type="hidden" value="<?php echo "$pvp" ?>">
<input class="art0" id="valMin<?php echo $i ?>" type="hidden" name="valMin<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$valBase" ?>" style=" width:30px">
<input class="art<?php echo $i ?>" id="val_orig<?php echo $i ?>" name="val_orig<?php echo $i ?>" type="hidden" value="<?php if($tipoCli=="Empresas"&&$iva!=0){echo redondeoF($PVP*1.19,-2);}else{echo "$pvp";} ?>">
<input class="art<?php echo $i ?>" id="val_origb<?php echo $i ?>" name="val_origb<?php echo $i ?>" type="hidden" value="<?php echo "$pvp" ?>">
<input class="art<?php echo $i ?>" id="val_cre<?php echo $i ?>H" name="val_cre<?php echo $i ?>H" type="hidden" readonly="" value="<?php echo "$pvpCredito" ?>">
</td>

<!-- sub tot -->
<td class="art<?php echo $i ?>">
<input class="art<?php echo $i ?>" id="val_t<?php echo $i ?>" name="val_tot<?php echo $i ?>" type="text" readonly="" value="<?php echo money("$sub_tot") ?>" style=" width:70px">
<input class="art<?php echo $i ?>" id="val_t<?php echo $i ?>HH" name="val_t<?php echo $i ?>" type="hidden" readonly="" value="<?php echo "$sub_tot" ?>">
<input class="art<?php echo $i ?>" id="val_t<?php echo $i ?>H" name="val_t<?php echo $i ?>H" type="hidden" readonly="" value="<?php echo "$sub_tot" ?>">
</td>
<td class="art<?php echo $i ?>" bgcolor="white">
<img onmouseup="eli_fv_mod($(this).prop('class'))" class="<?php echo $i ?>" src="Imagenes/delete.png" width="31px" heigth="31px">
<br>
<div class="pretty p-switch p-fill"><input class="art<?php echo $i ?>" type="checkbox" name="imprimirComanda<?php echo $i ?>" id="imprimirComanda<?php echo $i ?>" value="1" onClick="save_fv(<?php echo $i ?>);" <?php echo $imprimirComanda; ?>/><div class="state p-success"><label></label></div></div>
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
        <?php if($MODULES["SERVICIOS"]==1){ ?>
<tr>
<td colspan="11">
<table id="servicios" width="100%">
              <tr style="background-color: #000; color:#FFF">
                <?php if($MODULES["modulo_planes_internet"]!=1){?>
              <th><i class="uk-icon uk-icon-medium  uk-icon-wrench"></i>&nbsp;ID</th>
              <th>Codigo</th>
              <th>Servicio</th>
              <th>Nota</th>
              <th>IVA</th>
              <th>Dcto</th>
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
              <th>Precio PLAN(informativo)</th>
              <th>IVA</th>
              <th>Dcto</th>
              <th>PVP Servicio</th>
              <th>Total</th>
        
              <?php }?>
              </tr>
<?php
$saveFunct="save_fv";
$eliFunct="eli_serv_mod_ven";
$sql="SELECT * FROM serv_fac_ven WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND cod_su=$codSuc";
$rs=$linkPDO->query($sql);
include("fac_ven_load_serv.php");
?>

 </table>
 </td>
 </tr>
<?php } ?>

        <tr>
          <td colspan="5">
          <table align="center" frame="box">
          <?php 
		if(($rolLv==$Adminlvl || val_secc($id_Usu,"adm_art")) && $codSuc>0){ 
			?>
              <tr valign="middle">
                <th >Cod. Art&iacute;culo:</th>
                <td><input type="text" name="cod" value="" id="cod" onKeyPress="codx($(this),'mod');" onKeyUp="mover($(this),$('#entrega'),$(this),0);codx($(this),'mod');" /><!-- data-uk-tooltip title="F9: Buscar, ENTER:Ingresar Cod. Barras" -->
                <input type="hidden" value="" id="feVen" name="feVen">
                <input type="hidden" value="" id="Ref" name="Ref">
                </td>
                <td>
<img style="cursor:pointer" data-uk-tooltip title="Ver todos los Productos" onMouseUp="busq($('#cod'));" src="Imagenes/search128x128.png" width="34" height="31" />
<br />
                  <div id="Qresp"></div>
                  </td>
                  <?php }?>
               <td>
      <input type="button" value="Guardar" name="botonG"  onMouseUp="banDcto();save_fv(-1);" class="uk-button uk-button-primary uk-button-large">
      </td>
       <td>
      <input type="button" value="CERRAR FACTURA" name="botonG"  onMouseUp="banDcto();close_fv(-1);" class="uk-button uk-button-success uk-button-large">
      </td>
                <td>&nbsp;</td>
              </tr>
              <?php 
			        $formsElements = new lib_CommonFormsElements();
              $botonesCategorias = $formsElements->botonesCategoriasMesas();
              echo $botonesCategorias;  
			        ?>
              
                <?php
		
			  if($MODULES["SERVICIOS"]==1){
			  
			  ?>
               <tr>
              <td>SERVICIOS:</td>
              <td><?php  echo selc_serv("serv($(this),'save_fv','eli_serv_mod_ven')"); ?></td>
              </tr>
                <?php
			  
			  }
			  
			  ?>
              <tr>
              
              <td>TOTAL PRODUCTOS:</td>
              <TD>
              <input type="text" name="exi_ref" value="<?php echo $n_ref ?>" id="exi_ref" style="font-size:24px; font-weight:bold; width:50px;"  readonly/>
              </TD>
              <td colspan="">TOTAL CANTIDADES:</td><td>
              <input type="text" name="TOT_CANT" value="<?php echo "$n_cant"; ?>" id="TOT_CANT" style="font-size:24px; font-weight:bold; width:50px;"  readonly/>
              </TD>
              </tr>
            </table></td>
        </tr>
        <tr id="desc">
        
        <td>
        <table align="right" width="100%">
        <tr >
        
          <td colspan="3" rowspan="<?php if($MODULES["ANTICIPOS"]==1){echo "15";}else {echo "14";} ?>" align="center" width="" class="" > 
 
            <div align="left">
              <textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="40" style="width:200px" class="save_fc uk-hidden">
			        <?php echo "$val_letras" ?>
              </textarea>
              
              <textarea name="nota_fac" id="nota_fac"   cols="40" rows="6" placeholder="NOTAS" style="width:200px;
-webkit-border-radius:19px;
-moz-border-radius:19px;
border-radius:19px;
border:6px solid rgb(201, 38, 38);" class="save_fc"><?php echo "$nota_fac" ?></textarea>
              
              
              
            </div>
            <div align="left">
<textarea class="save_fc" name="nota_domicilio" id="nota_domicilio"   cols="40" rows="4" placeholder="DATOS DOMICILIO" style="-webkit-border-radius:19px;-moz-border-radius:19px;border-radius:19px;border:6px solid rgb(201, 38, 38);"><?php echo "$nota_domicilio" ?></textarea>
</div>
          </td>

          <th style="background-color: #000; color:#FFF" width="300px">Base IVA:</th>
          <td align="right"><input  id="SUB" type="text" readonly="" value="<?php echo money("$SUB") ?>"   name="sub" class="save_fc"/>
            <input type="hidden" name="SUB" value="<?php echo "$SUB" ?>" id="SUBH" class="save_fc"/>
           <input  readonly name="dcto" id="DESCUENTO" type="hidden" value="<?php echo "$DESCUENTO" ?>" onKeyUp="" class="save_fc"/>
           <input id="EXCENTOS" type="hidden" readonly="" value="0" name="exc" class="save_fc"/>
            
            </td>
            
        </tr>
<tr>
<th style="background-color: #000; color:#FFF;<?php echo $hide;?>">Dcto:</th>
<td align="right" style="<?php echo $hide;?>"><input <?php if($DESCUENTO>0){echo " ";}?> placeholder="%" id="DCTO_PER" type="text"  value="" name="DCTO_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#DESCUENTO2'));" onChange="dctoB();"/><input name="dcto2" id="DESCUENTO2" type="text" value="<?php echo money("$DESCUENTO") ?>" onKeyUp="dctoB();" class="save_fc" />
           </td>
           
        </tr>
    
        
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
<tr id="anticipo_bono_tr" class="<?php if($rolLv!=$Adminlvl && !val_secc($id_Usu,"dcto_despues_iva")){echo "uk-hidden";}?>">



<td style=" background-color: #000; color:#FFF;font-size:24px; padding:1px;">Dcto Despu&eacute;s IVA: 
<input class="save_fc" placeholder="%" id="DCTO_IVA_PER" type="text"  value="" name="DCTO_IVA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#DESCUENTO_IVA'));" onBlur="tot();"/>
</td>
<td align="right" >

<input class="save_fc save_fc uk-form-small" name="DESCUENTO_IVA" id="DESCUENTO_IVA" type="text" value="<?php echo "$DESCUENTO_IVA" ?>" onKeyUp="dctoB();"  style="font-size:30px;width:200px;"  />
</td>
</tr>

        <tr  class="uk-hidden">
          <td  align="center" colspan="" class="<?php if($usar_bsf!=1) echo "uk-hidden" ; ?>">TOTAL (Pesos):
          <input class="uk-button uk-button-success <?php if($usar_bsf!=1)echo "uk-hidden"; ?> save_fc" data-uk-toggle="{target:'.bsf'}" value="BsF" type="button"   onMouseDown="//change($('#entrega'));" />
          </td>
          
          <td colspan="" align="right"><input id="TOTAL" type="tel" readonly="" value="<?php echo money("$TOT") ?>" name="tot" class="save_fc"/>
          </td>
          </tr>
          <tr id="bsf"  class="<?php if($usar_bsf==0) echo "uk-hidden" ; ?> bsf">
          
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
           <tr style="background-color:#000; color:#FFF">
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
        
        <tr class="<?php if($MODULES["RETENCIONES"]==1){}else{echo "uk-hidden";}?>">
          <td  align="right" colspan="">R. FTE:<input placeholder="%" id="R_FTE_PER" type="text"  value="<?php echo $R_FTE_PER ?>" name="R_FTE_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_FTE'));" class="save_fc"/></td>
          <td colspan="" align="right"><input id="R_FTE" type="text"  value="<?php echo money($R_FTE*1) ?>" name="R_FTE" class="save_fc" />
          </td>
          </tr>
          
          <tr class="<?php if($MODULES["RETENCIONES"]==1){}else{echo "uk-hidden";}?>">
          <td  align="right" colspan="">R. IVA:<input placeholder="%" id="R_IVA_PER" type="text"  value="<?php echo $R_IVA_PER ?>" name="R_IVA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#IVA'),$('#R_IVA'));" class="save_fc"/></td>
          <td colspan="" align="right"><input id="R_IVA" type="text"  value="<?php echo money($R_IVA*1) ?>" name="R_IVA"  class="save_fc"/>
          </td>
          </tr>
          
          <tr class="<?php if($MODULES["RETENCIONES"]==1){}else{echo "uk-hidden";}?>">
          <td  align="right" colspan="">R. ICA:<input placeholder="%" id="R_ICA_PER" type="text"  value="<?php echo $R_ICA_PER ?>" name="R_ICA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_ICA'));" class="save_fc"/></td>
          <td colspan="" align="right"><input id="R_ICA" type="text"  value="<?php echo money($R_ICA*1) ?>" name="R_ICA" class="save_fc"/>
          </td>
          </tr>
          <tr class="<?php if($impuesto_consumo==1){}else{echo "uk-hidden";}?>">
<td  align="right" colspan="">IMP. Consumo<input placeholder="%" id="CONSUMO_PER" type="hidden"  value="" name="CONSUMO_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#TOT'),$('#IMP_CONSUMO'));" class="save_fc uk-form-small"/></td>
<td colspan="" align="right"><input id="IMP_CONSUMO" type="text"  value="<?php echo "$IMP_CONSUMO"; ?>" name="IMP_CONSUMO" class="save_fc uk-form-small"/>
</td>
</tr>
<tr>
<td></td>
<td>
<div class="uk-width-5-10 <?php if($impuesto_bolsas!=1){echo "uk-hidden";}?>">

<i class="uk-icon-shopping-bag <?php echo $ukIconSize ?>"></i>
<input style="font-size:20px; width:80px; " id="bolsas" type="text"  value="<?php echo "$NUM_BOLSAS";?>" name="bolsas" onKeyUp="call_tot();"  class="save_fc uk-form-large uk-form-success  "  />
 
</div>
</td>
</tr>

           <tr>
       <td  align="right" colspan="" style="font-size:42px;"><b>TOTAL:</b></td>
      <td colspan="" align="right"><input style="font-size:30px;width:200px;" id="TOTAL_PAGAR" type="text" value="<?php echo money($TOT_PAGAR*1) ?>"  name="TOTAL_PAGAR"  readonly class="save_fc uk-form-large"/></td>
    </tr>
        <tr class="PAGO_PESOS">
          <td  align="right" colspan="" style="font-size:42px;" >  PAGO:</td>
          <td colspan="" align="right"><input style="font-size:30px; width:200px; "  id="entrega" type="text"  value="<?php echo money("$entrega") ?>" name="entrega" onKeyUp="mover($(this),$('#cod'),$(this),0);change($(this));" onBlur="change($(this));" class="save_fc uk-form-success uk-form-large"/></td>
        </tr>
          <tr class="PAGO_PESOS2<?php if($MODULES["PAGO_EFECTIVO_TARJETA"]!=1)echo "uk-hidden"; ?>">
          <td style="font-size:24px;"  align="right" colspan=""  >Pago Tarjetas:</td>
          <td colspan="" align="right" class="PAGO_PESOS2" style="font-size:24px;"><input id="entrega3" type="text"  value="<?php echo money("$entrega3") ?>" name="entrega3" onKeyUp="change($(this));mover($(this),$('#cod'),$(this),0);" onBlur="//change($(this));" data-uk-tooltip title="" class="save_fc uk-form-large" style="font-size:30px;width:200px;  "/></td>
        </tr>
        <tr class="<?php if($usar_bsf!=1)echo "uk-hidden"; ?> bsf">
          <td  align="center" colspan="" >Pago Efectivo(bsF):</td>
          <td colspan="" align="right"><input id="entrega2" type="text"  value="<?php echo money("$entregaBSF") ?>" name="entrega2" onKeyUp="mover($(this),$('#cod'),$(this),0);change($(this));" onBlur="change($(this));" class="save_fc"/></td>
        </tr>
      
        <tr>
          <td  align="right" colspan="" style="font-size:42px;"><b>CAMBIO:</b></td>
          <td colspan="" align="right"><input style="font-size:30px;"  id="cambio" type="text"  value="<?php echo money("$cambio") ?>" name="cambio" readonly="readonly" class="save_fc uk-form-large"/>
          
          <div id="cambio_pesos" style="font-weight: bold; font-size:24px; color:#F00"></div>
          </td>
        </tr>
        </td>
        </table>
      </table>
</div> 
      
      <?php
	  
	  
}// fin for fac_ven valida+

?>
      </div>
      <span class="Estilo2"></span><br />
      
      
      <input type="hidden" name="id_mesa" value="<?php echo $MesaID ?>" id="id_mesa" class="save_fc"/>
      
       <input type="hidden" name="boton" value="" id="genera" />
            <input type="hidden" name="num_ref" value="<?php echo $n_ref ?>" id="num_ref" />
            
            <input type="hidden" name="num_fac_venta" value="<?php echo $num_fac ?>" id="num_fac" class="save_fc"/>
                        <input type="hidden" name="hash" value="<?php echo $hash ?>" id="hash" class="save_fc"/>
            
            
            
            <input type="hidden" name="pre" value="<?php echo $pre ?>" id="pre" class="save_fc"/>
            
            <input type="hidden" name="marca_moto" value="<?php echo $marcaMoto ?>" id="marca_moto" class="save_fc" />
            
            <input type="hidden" name="tope_credito" value="0" id="tope_credito" class="save_fc"/>
<input type="hidden" name="total_credito" value="0" id="total_credito" class="save_fc"/>
            
            <input type="hidden" name="num_serv" value="0" id="num_serv" />
            <input type="hidden" name="modFV" value="1" id="modFV" />
            <input type="hidden" name="exi_serv" value="0" id="exi_serv" />
            <input type="hidden" name="remision" value="0" id="remision" />
            <input type="hidden" value="" name="html" id="pagHTML" class="save_fc">
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
		else{echo '$("#articulos").find("input,button,textarea,select").prop("readonly", "readonly");$("#DESCUENTO2").prop("readonly", "readonly");$("#DCTO_PER").prop("readonly", "readonly");';}
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
		$('input[type=button]').prop("disabled","disabled").css("color","red");
		})
	.ajaxSuccess(function(){
		$(this).hide();
		$('input[type=button]').removeAttr("disabled").css("color","black");
		});
	
	//$('#loader').ajaxError(function(){$('input[type=button]').prop("disabled","disabled").css("color","red");$(this).hide();});


	

});// FIN $document


</script> 
<?php 
imp_fac($num_fac,$pre,$boton,1,"no",0,$hash); 
?>
</body>
</html>