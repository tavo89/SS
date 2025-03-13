<script language="javascript1.5" type="text/javascript">
var per_mayo='<?php echo $per_mayo; ?>';
var lim_dcto=<?php if($rolLv!=$Adminlvl){echo $lim_dcto;}else {echo 100;}?>;
var tecOpt='<?php echo tecOpt(); ?>';
var clasesOpt='<?php //echo clasesOpt(); ?>';
var fabOpt='<?php //echo fabOpt(); ?>';
var preOpt='<?php //echo presentacionOpt(); ?>';
var inv_ini='<?php //echo serial_inv_ini(); ?>';


var mesas_pedidos='<?php echo $MODULES["mesas_pedidos"]; ?>';
var usar_datos_motos=<?php echo $usar_datos_motos; ?>;
var usa_ubica=<?php echo $usar_ubica; ?>;


var PVP_CRE='<?php echo $MODULES["PVP_CREDITO"]; ?>';
var PVP_MAY='<?php echo $MODULES["PVP_MAYORISTA"]; ?>';
var usar_lim_fac='<?php echo $MODULES["LIM_FAC_REMI"]; ?>';
var redon_pvp_costo='<?php echo $redondear_pvp_costo; ?>';
var redon_pvp_costo='<?php echo $redondear_util; ?>';
var usarFechaVenci='<?php echo $usar_fecha_vencimiento; ?>';
var usarTalla='<?php echo $usar_talla; ?>';
var usarColor='<?php echo $usar_color; ?>';
var usarFracc='<?php echo $usar_fracciones_unidades; ?>';
var tipoUtil='<?php echo $tipo_utilidad; ?>';
var vendeSin='<?php echo $vender_sin_inv; ?>';
var vendeSinCant='<?php echo $vende_sin_cant; ?>';
var usarSerial='<?php echo $usar_serial; ?>';
var usarCertImport='<?php echo $cert_import; ?>';
var tasaCambio='<?php echo tasa_cambio(); ?>';
var kardex='<?php echo $FLUJO_INV; ?>';
var modFac='<?php echo $valModFac; ?>';
var carros_rutas='<?php echo $MODULES["CARROS_RUTAS"]; ?>';
var tipoFAC='<?php echo $OPC["TIPO_FAC"]; ?>';
var QFI='<?php echo $MODULES["QUICK_FAC_INPUT"]; ?>';
var BAN_DCTO_CRE='<?php echo $MODULES["BAN_DCTO_CRE"]; ?>';
var tipoRedondeo='<?php echo $tipo_redondeo; ?>';

var cta_bancos='<?php echo $MODULES["CUENTAS_BANCOS"]; ?>';
var usar_aplica_vehi='<?php echo $MODULES["APLICA_VEHI"]; ?>';
var usar_des_full='<?php echo $MODULES["DES_FULL"]; ?>';
var usar_cod_garantia='<?php echo $MODULES["COD_GARANTIA"]; ?>';
var pos_fac='<?php echo $usar_posFac; ?>';

var usar_campos_01_02='<?php echo $usar_campos_01_02; ?>';
var label_campo_add_01='<?php echo $label_campo_add_01; ?>';
var label_campo_add_02='<?php echo $label_campo_add_02; ?>';

var CLIENTE_MOSTRADOR='<?php echo $PUBLICO_GENERAL; ?>';
var ID_CLIENTE_MOSTRADOR='<?php echo $NIT_PUBLICO_GENERAL; ?>';



var fix_lector_barras='<?php echo $fix_lector_barras; ?>';
var ropa_campos_extra= '<?php echo $ropa_campos_extra; ?>';


var fac_ven_verSubtotales='<?php echo $fac_ven_verSubtotales; ?>';
var ganancia_ventas_mayorista='<?php echo $ganancia_ventas_mayorista; ?>';
var ganancia_ventas_mayorista2='<?php echo $ganancia_ventas_mayorista2; ?>';
var descuento_despues_iva_ventas= '<?php echo $descuento_despues_iva_ventas; ?>';

var modulo_planes_internet='<?php echo $MODULES["modulo_planes_internet"]; ?>';
var fac_servicios_mensuales= '<?php echo $fac_servicios_mensuales; ?>';

var usar_decimales_exactos= '<?php echo $usar_decimales_exactos; ?>';
var mod_ivas_facs= '<?php echo $mod_ivas_facs; ?>';

// variables Dian
var autoSendFE= '<?php echo $autoSendFE; ?>';
var impuesto_consumo='<?php echo $impuesto_consumo; ?>'*1;
var impuestos_consumo= '<?php echo $impuestos_consumo; ?>';
var impuesto_bolsas=<?php echo $impuesto_bolsas; ?>;
var valor_impuesto_bolsas=<?php echo $valor_impuesto_bolsas; ?>;

</script>
