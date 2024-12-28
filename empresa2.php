<?php
$INFO_IMPRESORA_POS="<b>Facturas POS </b>IMPRESORA: BIXOLON MODELO: SRP-350 S/N: IMPCHKA10110096";
$NOM_NEGOCIO="MAYORISTAS TECNOLOGYS S.A.S";
$NIT_NEGOCIO="900688283-5";
$TITULO="Smart Selling SW 4.0";
$url_LOGO_A="Imagenes/LOGO.png";
$url_LOGO_B="Imagenes/LOGO.png";
$x="130px";
$y="50px";

$X="250px";
$Y="100px";

$PUBLICIDAD="
<p align=\"center\" style=\"font-size:12px;\">
<B>$NOM_NEGOCIO</B>
<BR />
NIT: $NIT_NEGOCIO
<BR />
$dirSuc
<br>
TEL&Eacute;FONO: $tel1Su
<br />
$munSuc- $depSuc
</p>
";

$PUBLICIDAD2="
<p align=\"left\" style=\"font-size:12px;\">

<span style=\" font-size:16px\"><B>$NOM_NEGOCIO</B></span>
<BR />

<br>
NIT: $NIT_NEGOCIO
<BR />
$dirSuc
<br>
TEL&Eacute;FONO: $tel1Su
<br />
$munSuc- $depSuc

</p>
";
$PUBLICIDAD_TXT="
				$NOM_NEGOCIO

				NIT: $NIT_NEGOCIO

			$dirSuc

				TELÉFONO: $tel1Su

				$depSuc - $munSuc
";
//$TIPO_CHUZO="STD";
//$TIPO_CHUZO="ROPA";
$TIPO_CHUZO="STD";
//$TIPO_CHUZO=" ";

/////////////// MODS//////////////////

$horaApertura="05:00:00 am";
$horaCierre="19:29:59 pm";

$PUNTUACION_MILES=".";
$PUNTUACION_DECIMALES=",";

///// SECCIONES /////////
$usar_anticipos_bonos=1;
$usar_gastos=1;
//////CAMPOS //////////
if($TIPO_CHUZO=="STD"){
$usar_color=0;
$usar_talla=0;
$usar_serial=0;
$usar_fecha_vencimiento=0;
$usar_fracciones_unidades=1;
}
else if($TIPO_CHUZO=="ROPA")
{
$usar_color=1;
$usar_talla=1;
$usar_serial=0;
$usar_fecha_vencimiento=0;
$usar_fracciones_unidades=1;	
}
else if($TIPO_CHUZO=="DRO")
{
$usar_color=0;
$usar_talla=0;
$usar_serial=0;
$usar_fecha_vencimiento=1;
$usar_fracciones_unidades=1;	
}
else 
{
$usar_color=0;
$usar_talla=0;
$usar_serial=0;
$usar_fecha_vencimiento=0;
$usar_fracciones_unidades=1;	
}

///////////////////////////////
$usar_iva=1;




$dias_anula_comps=500;






$imp_solo_pos=1;

$tipo_redondeo="decimal";
//$tipo_redondeo="centena";
$redondear_pvp_costo="s";
$redondear_util="s";

$global_text_fabricante="Fabricante";
$_descuentosFabricante="0";
$usar_costo_dcto=1;
$promediar_costos=1;




/*******************/
$vender_sin_inv=0;
if($vender_sin_inv==1)
{
//$_SESSION['FLUJO_INVENTARIO']=-1;	
}
$vende_sin_cant=0;
/*******************/



$fecha_lim_anulaCompra="";
//$fecha_lim_anulaVenta="AND ( MONTH(fecha)=MONTH(NOW()) AND YEAR(fecha)=YEAR(NOW()) )";
$fecha_lim_anulaVenta=" ";
$mod_costos=0;


//----------------comprobantes ingreso/egreso----------------------------------
$tipo_imp_comprobantes="COM";

$ImpURLcomprobantesPOS="imp_comp_ingre_pos.php";
$ImpURLcomprobantesCOM="imp_comp_ingre.php";
//----------------------------------------------------------------------------
//-----------------fac ven ------------------------------
//$ImpURLcontado="imp_fac_ven_papel.php";
//$ImpURLcontado="imp_fac_ven.php";
$ImpURLcontado="imp_fac_ven.php";
$ImpURLcredito="imp_fac_ven_cre.php";
$tipo_impresion="POS";
$precioBonoCasco=50000;
$ver_pvp_sin_iva=1;
$impFacVen_mini=1;
$usar_remision=0;
$cross_fac=0;
$usar_bsf=0;
$usar_posFac=0;
$tipo_fac_default="COM";


$vista_remi="A";
$usar_costo_remi=0;
//--------------------------------------------------------
$tipo_utilidad="A";

//------------------------------------
$caja=0;
$Adminlvl=10;
$CEOlvl=5;
$Multilvl=4;
$Midlvl=3;
$Cajalvl=2;
$Bottonlvl=1;

$NIT_FANALCA="890301886-1";

$SEDES=array(1,"ARAUCA","BOGOTA","VILLAVICENCIO","SARAVENA");
//////////////////////// COMPRAS /////////////////////////////
$impCompra="A";



////////////////////////////////TRASLADOS///////////////////////////////
$confirmar_tras="manual";// -manual







//////////////////////////////// MODULOS///////////////////////////////
$MODULES=array("CARTERA"=>1,"CARROS_RUTAS"=>0,"ANTICIPOS"=>1,"TRASLADOS"=>1,"REMISIONES"=>1,"CARGAR_CARROS"=>0,"GASTOS"=>1,"FLUJO_KARDEX"=>1,"SERVICIOS"=>1,"PVP_CREDITO"=>0,"COTIZACIONES"=>0,"MULTISEDES_UNIFICADAS"=>0,"PAGO_EFECTIVO_TARJETA"=>0,"AUTO_BAN_CLI"=>1,"UN_BAN_CLI2"=>1,"QUICK_FAC_INPUT"=>0);

/////////////////////////////////// OPCIONES //////////////////////////
$OPC=array("TIPO_FAC"=>"A");

$SelcOptTipoEgreso='
			        <optgroup label="Gastos Almac&eacute;n">
					<option value="Facturas Proveedores">Facturas Proveedores</option>
					<option value="MANTENIMIENTOS">MANTENIMIENTOS</option>
                    <option value="Envios, transportes y logistica"  >Envíos, transportes y logística</option>
                    <option value="Regalos y Obsequios">Regalos y Obsequios</option>
                    <option value="Otros gastos de almacen">Otros gastos de almac&eacute;n</option>
                    </optgroup>
                    <optgroup label="Gastos de Marketing">
                    <option value="Viajes">Viajes</option>
                    <option value="Comida y Bebida">Comida y Bebida</option>
                    <option value="Costes de publicidad">Costes de publicidad</option>
                    <option value="Costes de representación">Costes de representación</option>
                    </optgroup>
                    <optgroup label="Gastos de Oficina">
                    <option value="Suministros de oficina">Suministros de oficina</option>
                    <option value="Telefono y comunicaciones">Teléfono y comunicaciones</option>
                    <option value="Internet">Internet</option>
                    <option value="Software">Software</option>
                    <option value="Hardware">Hardware</option>
                    <option value="Otro material de oficina">Otro material de oficina</option>
                    </optgroup>
                    
                    <optgroup label="Seguro y cuotas">
                    <option value="Primas de seguro">Primas de seguro</option>
                    <option value="Cuotas de socio">Cuotas de socio</option>
                    <option value="Cuotas de Maquinaria">Cuotas de Maquinaria</option>
                    <option value="Cuotas de licencia">Cuotas de licencia</option>
                    </optgroup>
                    
                     <optgroup label="Instalaciones y edificios">
                    <option value="Alquiler">Alquiler</option>
                    <option value="Electricidad">Electricidad</option>
                    <option value="Agua, limpieza, basura">Agua, limpieza, basura</option>
                    <!--
                    <option value="Agua">Agua</option>
                    <option value="Limpieza">Limpieza</option>
                    <option value="Basura">Basura</option>
                    -->
                    </optgroup>

			<optgroup label="Obligaciones Financieras">
			<option value="DAVIVIENDA">DAVIVIENDA</option>
			<option value="BBVA">BBVA</option>
			<option value="BANCOLOMBIA">BANCOLOMBIA</option>
                    <option value="BANCO DE BOGOTA">BANCO DE BOGOTA</option>
                    <option value="FALABELLA">FALABELLA</option>
                    
                    </optgroup>
		<optgroup label="Camara de comercio">
			<option value="Renovacion de matricula">Renovacion de matricula</option>
			<option value="Certificaciones">Certificaciones</option>
		
                    
                    </optgroup>
                    
                     <optgroup label="Impuestos y Contabilidad">
			<option value="RETE FUENTE">RETE FUENTE</option>
			<option value="RETE ICA">RETE ICA</option>
			<option value="RETE IVA">RETE IVA</option>
			<option value="Gastos procesales">Gastos procesales</option>
                    <option value="Pago de IVA">Pago de IVA</option>
                    <option value="Devolución de IVA">Devolución de IVA</option>
                    <option value="Gastos legales">Gastos legales</option>
                     <option value="Gastos de contabilidad">Gastos de contabilidad</option>
                    </optgroup>
                    
                     <optgroup label="Gastos de N&oacute;mina">
                    <option value="Sueldo de los empleados">Sueldo de los empleados</option>
                    <option value="Seguridad social">Seguridad social</option>
		   <option value="Prima de servicios">Prima de servicios</option>
                    <option value="Gastos de transporte del / al trabajo">Gastos de transporte del / al trabajo</option>
                    <option value="Mantenimiento vehiculo">Mantenimiento vehiculo</option>
                    <option value="Repuestos vehiculo">Repuestos vehiculo</option>
                    </optgroup>
';
/////
?>