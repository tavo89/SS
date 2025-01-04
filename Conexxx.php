<?php
include_once("DB.php");
///////////////////////// GENERALIDADES PAG///////////////////////////////////////////////////////////
date_default_timezone_set('America/Bogota');

mysqli_query($conex, "SET time_zone = '-05:00';");
mysqli_query($conex, "SET sql_mode = '';");
//error_reporting(E_ERROR | E_PARSE);



//echo "SET GLOBAL general_log_file = '".__DIR__."\all.log';";


$uikitIconSize="uk-icon-large";
$uikitIconSize_menu="uk-icon-small";
$UKdatePickerFormat="format:'YYYY-MM-DD',i18n:{months:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'], weekdays:['Dom','Lun','Mar','Mier','Jue','Vier','Sab']}";


$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$FechaHoy = gmdate("Y-m-d",hora_local(-5));
$hora = gmdate("H:i:s",hora_local(-5));

$mesActual=gmdate("m",hora_local(-5));
$yearActual=gmdate("Y",hora_local(-5));

$LAST_VER=$FechaHoy;
$LAST_VER='13.221227-3'; // version del sistema, renueva cache de cliente si se cambia.
//$LAST_VER=$hoy;

$fechaVenciInterval="days";
$fechaVenciMINval="180";
$fechaVenciALERTval="150";
$fechaVenciOPTval="170";
$VER_PROGRAMA="4.9.530102018";
$FECHA_ACTUALIZAR_SW="2018-10-30";

$CHAR_SET="UTF-8";

ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);
ini_set('max_execution_time', 1600);
define('CSV_PATH','Imagenes/Import/csvfile/');
session_start();

//echo "<br>INIT CLASS<span style='color:black'>idusu:".$_SESSION['id_usu'].", tipoUsu:".$_SESSION['tipo_usu']."</span>";die();
$_opt_facVen_dev="FAC_VEN";


if(isset($_SESSION["see_warn_resol"]))$see_warn_resol=s("see_warn_resol");
else $see_warn_resol=1;

//header("Content-Type: text/html; charset=$CHAR_SET");
//include("$_SERVER[DOCUMENT_ROOT]/../DB.php");

include_once('vendor/phpqrcode-master/phpqrcode.php');
$SEDES=$_SESSION["sucursales"];
$SEDES2=array(1,"A","T","AQ","S");
valida_session();

$FP_ingresos=array("Contado","Contado-Caja General","Tarjeta Debito","Tarjeta Credito","Cheque","Tranferencia Bancaria");


$FILTRO_INVERSIONES=" AND tipo_gasto!='Consignacion Ventas' AND tipo_gasto!='Transferencia Entre Cuentas' AND tipo_gasto!='Inversion Negocio' AND tipo_gasto!='Inversion Personal' AND tipo_gasto!='Facturas Proveedores' AND tipo_gasto!='Anticipos de Compras' AND tipo_gasto!='Proveedores' AND tipo_gasto!='2205 Nacionales' AND tipo_gasto!='22 Proveedores'  AND tipo_gasto!='Compras'";

$FILTRO_INVERSIONES2=" AND (  tipo_gasto='Inversion Negocio' OR tipo_gasto='Inversion Personal' OR tipo_gasto='Facturas Proveedores' OR tipo_gasto='Anticipos de Compras' OR tipo_gasto='Proveedores' OR tipo_gasto='2205 Nacionales' OR tipo_gasto='22 Proveedores')";

$FILTRO_INVERSIONES2=" AND (  tipo_gasto!='')";



$GLOBAL_TIPOS_CLI='<option value="Mostrador" selected>Mostrador (P&uacute;blico)</option><option value="Mayoristas">Mayoristas</option>';

//$GLOBAL_TIPOS_CLI='<option value="Diesmador" >Diesmador</option><option value="Ofrendas" selected>Ofrendas</option>';
//$ID_CLI_DCTO_ESPECIAL=array("");

$see_warn_ban_list=s("see_warn_ban_list");
$see_warn_inv=s("see_warn_inv");
//$see_warn_inv=1;

/*****************************************************/

$eyy="
─────────────████████──███████
──────────████▓▓▓▓▓▓████░░░░░██
────────██▓▓▓▓▓▓▓▓▓▓▓▓██░░░░░░██
──────██▓▓▓▓▓▓████████████░░░░██
────██▓▓▓▓▓▓████████████████░██
────██▓▓████░░░░░░░░░░░░██████
──  ████████░░░░██░░██░░██▓▓▓▓██
──██░░████░░░░░░██░░██░░██▓▓▓▓██
██░░░░██████░░░░░░░░░░░░░░██▓▓██
██░░░░░░██░░░░██░░░░░░░░░░██▓▓██
──██░░░░░░░░░███████░░░░██████
────████░░░░░░░███████████▓▓██
──────██████░░░░░░░░░░██▓▓▓▓██
────██▓▓▓▓██████████████▓▓██
──██▓▓▓▓▓▓▓▓████░░░░░░████
████▓▓▓▓▓▓▓▓██░░░░░░░░░░██
████▓▓▓▓▓▓▓▓██░░░░░░░░░░██
██████▓▓▓▓▓▓▓▓██░░░░░░████████
──██████▓▓▓▓▓▓████████████████
────██████████████████████▓▓▓▓██
──██▓▓▓▓████████████████▓▓▓▓▓▓██
████▓▓██████████████████▓▓▓▓▓▓██
██▓▓▓▓██████████████████▓▓▓▓▓▓██
██▓▓▓▓██████████──────██▓▓▓▓████
██▓▓▓▓████──────────────██████
──████";




/******************************************************/



///////////////// FECHAS VENCIMIENTOS //////////////////

$fechaVenciMIN=date("Y-m-d",strtotime($FechaHoy. "+ $fechaVenciMINval $fechaVenciInterval"));
$fechaVenciALERT=date("Y-m-d",strtotime($FechaHoy. "+ $fechaVenciALERTval $fechaVenciInterval"));
//$fechaVenciMIN=date('Y-m-d', strtotime($FechaHoy. ' + 10 days'));

///////////////////////////////////////////////////////


$horaB = gmdate("H:i A",hora_local(-5));
$FECHA_HORA=$FechaHoy."T".$hora;
$FECHA_HORA2="00-00-0000"."T".$hora;
$codSuc=s('cod_su');
$hostName=$_SERVER['HTTP_HOST'];


$MESES=array(1,"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$SECCIONES=array(1,"Clientes","Factura Venta","Pago Factura Credito","Comprobante Ingreso","Anulacion Facturas Venta","Facturas de Compra","Inventario","Ajustes Inventario");
$OPERACIONES=array(1,"Crear Registro","Modificar Registro","Eliminar Registro");
$alasSuc=s('alas');
$RESOLUCIONES=resol();



$FX_MENU_TOP=r('ACTIVE_FLUX');
if(!empty($FX_MENU_TOP)){
	$_SESSION['FLUJO_INVENTARIO']=$FX_MENU_TOP;
	//eco_alert("fx: $FX_MENU_TOP");
	}
	$FLUJO_INV=$_SESSION['FLUJO_INVENTARIO'];

$codContadoSuc=$RESOLUCIONES['POS'][0];
$ResolContado=$RESOLUCIONES['POS'][1];
$FechaResolContado=$RESOLUCIONES['POS'][2];
$RangoContado=$RESOLUCIONES['POS'][3];

$codCreditoSuc=$RESOLUCIONES['COM'][0];
$ResolCredito=$RESOLUCIONES['COM'][1];
$FechaResolCredito=$RESOLUCIONES['COM'][2];
$RangoCredito=$RESOLUCIONES['COM'][3];

$codPapelSuc=$RESOLUCIONES['PAPEL'][0];
$ResolPapel=$RESOLUCIONES['PAPEL'][1];
$FechaResolPapel=$RESOLUCIONES['PAPEL'][2];
$RangoPapel=$RESOLUCIONES['PAPEL'][3];

$codCreditoANTSuc=$RESOLUCIONES['CRE'][0];
$ResolCreditoANT=$RESOLUCIONES['CRE'][1];
$FechaResolCreditoANT=$RESOLUCIONES['CRE'][2];
$RangoCreditoANT=$RESOLUCIONES['CRE'][3];

$codRemiPOS=$RESOLUCIONES['REM_POS'][0];
$ResolRemiPOS=$RESOLUCIONES['REM_POS'][1];
$FechaRemiPOS=$RESOLUCIONES['REM_POS'][2];
$RangoRemiPOS=$RESOLUCIONES['REM_POS'][3];

$codRemiCOM=$RESOLUCIONES['REM_COM'][0];
$ResolRemiCOM=$RESOLUCIONES['REM_COM'][1];
$FechaRemiCOM=$RESOLUCIONES['REM_COM'][2];
$RangoRemiCOM=$RESOLUCIONES['REM_COM'][3];

$codRemiCOM2=$RESOLUCIONES['REM_COM2'][0];
$ResolRemiCOM2=$RESOLUCIONES['REM_COM2'][1];
$FechaRemiCOM2=$RESOLUCIONES['REM_COM2'][2];
$RangoRemiCOM2=$RESOLUCIONES['REM_COM2'][3];


$nomSuc=strtoupper($_SESSION['nom_su']);
$dirSuc=strtoupper($_SESSION['dir_su']);
$tel1Su=$_SESSION['tel1_su'];
$tel2Su=$_SESSION['tel2_su'];

//$nomUsu=htmlentities($_SESSION['nom_usu'],ENT_QUOTES,"$CHAR_SET");
$nomUsu=$_SESSION['nom_usu'];
$id_Usu=$_SESSION['id_usu'];
$rolUsu=$_SESSION['tipo_usu'];
$rolLv=$_SESSION['rol_lv'];
$munSuc=$_SESSION['municipio'];
$depSuc=$_SESSION['departamento'];
$email_sucursal=strtoupper($_SESSION["mail_su"]);
//echo "$email_sucursal mail";

$codCaja=$_SESSION['cod_caja'];

$IP_CLIENTE=$_SESSION['ipClient'];
$fechaCreaUsu=s("fecha_crea_usu");
//echo "fecha crea: $fechaCreaUsu<br>";
/************************************************************************************************************************************************/
/************************************************************************************************************************************************/
/************************************************************************************************************************************************/
/************************************************************************************************************************************************/
/************************************************************************************************************************************************/
													include_once("empresa.php");
/************************************************************************************************************************************************/
/************************************************************************************************************************************************/
/************************************************************************************************************************************************/
/************************************************************************************************************************************************/


bloquearSistema();

//echo "host: $hostName |codsu: $codSuc|usar p2: $usar_productos2";

if($usar_productos2==1 && $codSuc==2 && ($hostName=="saman.nanimosoft.com"|| $hostName=="127.0.0.12"))
{define("tabProductos","productos2"); }
else{define("tabProductos","productos");}



//echo "<br>Tabla Productos:".tabProductos;

$DIAS_BAN_CLI=$DIAS_MORA_CREDITO;
//echo "$DIAS_BAN_CLI";
if($MODULES["LIM_FAC_REMI"]==1){$GLOBAL_TIPOS_CLI='<option value="Mostrador" selected>Mostrador (P&uacute;blico)</option><option value="Empresas" >Empresas (+16%)</option> <option value="Otros Talleres">Otros Talleres</option><option value="Mayoristas">Mayoristas</option>';}
//$FP_egresos=array("Contado","Contado-Caja General","Cheque","Tranferencia Bancaria");
if($MODULES["EGRESOS_2"]==1 ){$FP_egresos=array("Contado","Contado-Caja General","Cheque","Tranferencia Bancaria"





);
}else {$FP_egresos=array("Contado");}

//echo "<h1>S. $codSuc</h1>";
$valModFac=0;
if($FLUJO_INV==1 && (($rolLv!=$Adminlvl && !val_secc($id_Usu,"fac_mod")) ) )$valModFac=0;
else $valModFac=1;

function hora_local($zona_horaria = 0) {
    if ($zona_horaria > -12.1 and $zona_horaria < 12.1) {
        $hora_local = time() + ($zona_horaria * 3600);
        return $hora_local;
    }
    return 'error';
};

function licenceKey()
{
	global $codSuc,$linkPDO;
	$rs=$linkPDO->query("SELECT licencia_key FROM sucursal WHERE cod_su='$codSuc'");
	if($row=$rs->fetch())
	{
		return $row['licencia_key'];
	}
	else return "";
};


////////////////////////////////////////
$cols_busq="tipo_producto,
            inv_inter.id_pro ref,
			inv_inter.id_inter cod,
			exist,precio_v,pvp_may,pvp_credito,
			iva,detalle,
			fab,
			id_clase,
			color,
			talla,
			inv_inter.presentacion,
			inv_inter.fecha_vencimiento,
			inv_inter.fraccion,
			inv_inter.unidades_frac,
			".tabProductos.".presentacion as prese,
			certificado_importacion,
			aplica_vehi,
			".tabProductos.".des_full,
			".tabProductos.".url_img,
			inv_inter.tipo_producto,
			inv_inter.impuesto_saludable";



if($codSuc==1)$fechaKardex="2000-05-07";
else if($codSuc==2)$fechaKardex="2010-05-07";
else if($codSuc==3)$fechaKardex="2010-05-07";
else if($codSuc==4)$fechaKardex="2010-05-07";
else $fechaKardex="2010-05-07";




// ---------------------------------------------------------------------------- LIBRERIAS -----------------------------------------------------------------------------------------
include_once("LIB_compras.php");
include_once("lib_clientes.php");
include_once("lib_CommonFormsElements.php");
include("LIB_imprimir_popups.php");
include('Class/Clientes.php');
include('CommonVarsInclude/reglasInformesVentas.php');
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function valida_session()
{
global $BaseDatos,$HOST,$USU,$CLA,$linkPDO;

//echo "<br><span style='color:black'>idusu:".$_SESSION['id_usu'].", tipoUsu:".$_SESSION['tipo_usu']."</span>";die();
	if(isset($_SESSION['id_usu'])&&isset($_SESSION['tipo_usu']))
	{
		$id=$_SESSION['id_usu'];
		$tipoUsu=$_SESSION['tipo_usu'];
		$qry="SELECT usu_login.usu,usu_login.cla,usu_login.id_usu 
			FROM usu_login,tipo_usu,usuarios 
			WHERE usuarios.id_usu='$id' AND usu_login.id_usu=tipo_usu.id_usu AND tipo_usu.des='$tipoUsu'";
//echo "$qry";die();
		$rs=$linkPDO->query($qry);
		if($row=$rs->fetch())
		{

		}
		else {
			header("location: sesioncerrada.php");
			}

	}
	else {
		header("location: sesioncerrada.php");
		}

};

function cajas($Hc,$Ha,$HA,$conex,$codSuc)
{
	global $caja,$linkPDO;
	$rx=$caja;
if($rx==1){
$estado_caja="";
$sql="select estado_caja from cajas where fecha=CURRENT_DATE() AND cod_su=$codSuc";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$estado_caja=$row['estado_caja'];
}
else $estado_caja;

if($estado_caja=="CERRADA"||empty($estado_caja)){eco_alert("Caja CERRADA, no es  posible realizar Ventas!");js_location("centro.php");}
else if($HA>$Hc){eco_alert("Recuerde cerrar la Caja! Hora $HA");}
else if ($HA<$Ha){eco_alert("Aun no inicia horario laboral, no es posible realizar Ventas! Hora $HA");js_location("centro.php"); }
}
};

function limpianum($num)
{
	global $PUNTUACION_DECIMALES;
	$T=(string)$num;
	$resp="";
   if(!empty($num)){
	$i = strlen($T) - 1;
	$ii = strlen($T);
   	$T=preg_split('//',$T,-1,PREG_SPLIT_NO_EMPTY);
	$long=count($T);

	for($j=0;$j<$long;$j++)
	{
		if($T[$j]=="0"||$T[$j]=="1"||$T[$j]=="2"||$T[$j]=="3"||$T[$j]=="4"||$T[$j]=="5"||$T[$j]=="6"||$T[$j]=="7"||$T[$j]=="8"||$T[$j]=="9"||$T[$j]=="."||$T[$j]=="-")
		{
			$resp.=$T[$j];
		}
	}
   }
   else $resp=0;
	return $resp*1;
};
function limpiafloat($num)
{
	$T=(string)$num;

	$i = strlen($T) - 1;
	$ii = strlen($T);
   	$T=preg_split('//',$T,-1,PREG_SPLIT_NO_EMPTY);
	$long=count($T);
	$resp="";
	for($j=0;$j<$long;$j++)
	{
		if($T[$j]=="0"||$T[$j]=="1"||$T[$j]=="2"||$T[$j]=="3"||$T[$j]=="4"||$T[$j]=="5"||$T[$j]=="6"||$T[$j]=="7"||$T[$j]=="8"||$T[$j]=="9"||$T[$j]==".")
		{
			$resp.=$T[$j];
		}
	}
	return $resp;
};
///////////////////////////////////////////////////////////////// HTML <select > //////////////////////////////////////////////////////////////////
function fab($conex,$sel,$name,$id,$clase)
{
	global $linkPDO;

	$sql="SELECT * FROM fabricantes";
	$rs=$linkPDO->query($sql);
	$imp="";
	$color="";
	$selec="<select name=\"$name\" id=\"$id\" class=\"$clase\"><option value=\"\"  selected></option>";
	while($row=$rs->fetch())
	{
		$fab=$row['fabricante'];
		if($sel==$fab){$imp="selected";}
		else $imp="";
		$selec.="<option value=\"$fab\" $imp ><span $color >$fab</span></option>";
	}
	$selec.="</select>";

	return minify_html($selec);
};
function usuarios($sel,$name,$id,$clase)
{
	global $codSuc,$linkPDO;

	$sql="SELECT id_usu,nombre FROM usuarios WHERE cod_su=$codSuc AND cliente=0";
	$rs=$linkPDO->query($sql);
	$imp="";
	$color="";
	$selec="";
	//$selec="<select name=\"$name\" id=\"$id\" class=\"$clase\"><option value=\"all\"  selected>TODOS</option>";
	while($row=$rs->fetch())
	{
		$idUsu=$row['id_usu'];
		$nom=$row['nombre'];
		if($sel==$idUsu){$imp="selected";}
		else $imp="";
		$selec.="<option value=\"$idUsu\" $imp ><span $color >$nom</span></option>";
	}
	//$selec.="</select>";

	return $selec;
};
function ciudades($conex,$sel,$name,$id,$clase)
{
	global $linkPDO;

	$sql="SELECT * FROM usuarios where cuidad!='ARAUCA' group by cuidad";
	$rs=$linkPDO->query($sql);
	$imp="";
	$color="";
	$selec="<select multiple=\"multiple\" name=\"$name\" id=\"$id\" class=\"$clase\"><option value=\"\"  selected>TODOS</option><optgroup label=\"Principal\"><option value=\"ARAUCA\">ARAUCA</option></optgroup><optgroup label=\"Dem&aacute;s Ciudades\">";
	while($row=$rs->fetch())
	{
		$fab=$row['cuidad'];
		if($sel==$fab){$imp="selected";}
		else $imp="";
		$selec.="<option value=\"$fab\" $imp ><span $color >$fab</span></option>";
	}
	$selec.="</optgroup></select>";

	return minify_html($selec);
};
function modulos($sel)
{
	global $linkPDO,$codSuc,$SECCIONES;

	$LIM=count($SECCIONES);
	$select="";
	$imp="";
	$color="";
	for($i=1;$i<$LIM;$i++)
	{

		if($sel==$SECCIONES[$i]){$imp="selected";}
		else $imp="";
		$select.="<option value=\"$SECCIONES[$i]\" $imp ><span $color >$SECCIONES[$i]</span></option>";
	}
	return minify_html($select);
};
function operaciones($sel)
{
	global $OPERACIONES;

	$LIM=count($OPERACIONES);
	$select="";
	$imp="";
	$color="";
	for($i=1;$i<$LIM;$i++)
	{

		if($sel==$OPERACIONES[$i]){$imp="selected";}
		else $imp="";
		$select.="<option value=\"$OPERACIONES[$i]\" $imp ><span $color >$OPERACIONES[$i]</span></option>";
	}
	return minify_html($select);
};
function tecOpt($sel="none")
{
global $linkPDO;

	$rs=$linkPDO->query("SELECT a.id_usu,a.nombre FROM usuarios a INNER JOIN tipo_usu b ON a.id_usu=b.id_usu WHERE des='Tecnico' OR des='Mecanico'");
	$resp="<option value=></option>";
	$s="";
	while($row = $rs->fetch())
         {
		 if($sel==$row["id_usu"])$s="selected";
		 else $s="";
		 $resp.= "<option value=$row[id_usu] $s>$row[nombre]</option>  ";
		 }
		 return minify_html($resp);
};

function egresoOpt($opc)
{
	global $codSuc;
	$selc="";
	$limit=count($opc);
	for($i=0;$i<$limit;$i++){
		$VALS=$opc[$i];
		$selc.="<option value=\"$VALS\"  >$VALS</option>";
	}

	return minify_html($selc);
};

function tipoEgresoOpt()
{
global $ListEgreA,$ListEgreB,$ListEgreC,$ListEgreD,$MODULES;
$SelcOptTipoEgreso='';
if($ListEgreA){
$SelcOptTipoEgreso='';
$SelcOptTipoEgreso='
<option value="281505  DISTRITO"  >281505  DISTRITO</option>
<option value="281510 FINCA SALIM"  >281510 FINCA SALIM</option>
<option value="281515 FONDO ZONAL"  >281515 FONDO ZONAL</option>
<option value="510506 SUELDOS"  >510506 SUELDOS</option>
<option value="510530 CESANTIAS"  >510530 CESANTIAS</option>
<option value="510533 INTERESES SOBRE CESANTÍAS"  >510533 INTERESES SOBRE CESANTÍAS</option>
<option value="510536 PRIMA DE SERVICIOS"  >510536 PRIMA DE SERVICIOS</option>
<option value="510558 RIESGOS PROFESIONALES - ARP"  >510558 RIESGOS PROFESIONALES - ARP</option>
<option value="510569 SEGURIDAD SOCIAL - SALUD"  >510569 SEGURIDAD SOCIAL - SALUD</option>
<option value="510570 FONDO DE PENSIONES"  >510570 FONDO DE PENSIONES</option>
<option value="510580 FONDOS SOLIDARIDA PENSIONAL"  >510580 FONDOS SOLIDARIDA PENSIONAL</option>
<option value="510572 APORTES CAJA COMPENSACION"  >510572 APORTES CAJA COMPENSACION</option>
<option value="510575 APORTE ICBF"  >510575 APORTE ICBF</option>
<option value="510578 APORTE SENA"  >510578 APORTE SENA</option>
<option value="513505 SERVICIOS PUBLICOS"  >513505 SERVICIOS PUBLICOS</option>
<option value="513535 COMBUSTIBLE Y LUBRICANTES"  >513535 COMBUSTIBLE Y LUBRICANTES</option>
<option value="514505 MANT. Y REPARACIONES IGLESIA"  >514505 MANT. Y REPARACIONES IGLESIA</option>
<option value="514540 MANTENIMIENTO DE VEHICULOS"  >514540 MANTENIMIENTO DE VEHICULOS </option>
<option value="519525 PORTERIA ASEO CAFETERIA"  >519525 PORTERIA ASEO CAFETERIA</option>
<option value="519530 UTILES PAPELERIA Y FOTOCOPIAS"  >519530 UTILES PAPELERIA Y FOTOCOPIAS</option>
<option value="519545 TRANSPORTES  (Taxis, Buses, Aereo)"  >519545 TRANSPORTES  (Taxis, Buses, Aereo)</option>
<option value="519595 OTROS GASTOS"  >519595 OTROS GASTOS</option>
<option value="539530 EMOLUMENTO MINISTERIAL"  >539530 EMOLUMENTO MINISTERIAL</option>
<option value="539540 OFRENDAS AUXILIOS OBRA SOCIAL"  >539540 OFRENDAS AUXILIOS OBRA SOCIAL</option>';
}


if($ListEgreB){
	$SelcOptTipoEgreso='';
$SelcOptTipoEgreso='
			        <optgroup label="Gastos Personales">
					<option value="Cuotas ICETEX"  selected>Cuotas ICETEX</option>
					<option value="Consignacion Ventas">Consignacion Ventas</option>
					<option value="Inversion Personal">Inversion Personal</option>
					<option value="Inversion Negocio">Inversion Negocio</option>
					<option value="Manejo Tarjeta Banco">Manejo Tarjeta Banco</option>
					<option value="Transferencia Entre Cuentas"   >Transferencia Entre Cuentas</option>
					<option value="Prestamos"   >Prestamos</option>
					<option value="Viajes Dpto." selected>Viajes Dpto.</option>
					<option value="Gastos de transporte del / al trabajo" selected>Gastos de transporte del / al trabajo</option>
					<option value="Mantenimiento Moto"  selected>Mantenimiento Moto</option>
			        <option value="Manutencion" selected>Manutencion</option>
					<option value="Alquiler" selected>Alquiler</option>
					<option value="Agua" selected>Agua</option>
					<option value="Electricidad" selected>Electricidad</option>

					<option value="Cuotas de socio" >Cuotas de socio</option>
					<option value="Electrodomesticos">Electrodomesticos</option>
			        <option value="Ropa">Ropa</option>



					<option value="Accesorios Moto"  >Accesorios Moto</option>


                    <option value="Viajes Ocio">Viajes Ocio</option>
                    <option value="Salud">Salud</option>
					<option value="Telefono y comunicaciones">Teléfono y comunicaciones</option>
                    <option value="Internet">Internet</option>

                    <option value="Limpieza">Limpieza</option>
					<option value="Aseo Personal">Aseo Personal</option>
                    <option value="Basura">Basura</option>
                    </optgroup>


                    <optgroup label="Gastos de Marketing">
                    <option value="Viajes">Viajes</option>
                    <option value="Comida y Bebida">Comida y Bebida</option>
                    </optgroup>

                    <optgroup label="Gastos de Oficina">
                    <option value="Suministros de oficina">Suministros de oficina</option>
                    <option value="Software">Software</option>
                    <option value="Hardware">Hardware</option>
                    <option value="Otro material de oficina">Otro material de oficina</option>
                    </optgroup>


                	<optgroup label="Impuestos y Contabilidad">
					 <option value="Predial">Predial</option>
					 <option value="Pago de IVA">Pago de IVA</option>
					 <option value="ICA">ICA</option>
					 <option value="Industria y Comercio">Industria y Comercio</option>
					 <option value="Retefuente">Retefuente</option>
					 <option value="Renta">Renta</option>
					 <option value="Camara de Comercio">Camara de Comercio</option>


                    <option value="Devolución de IVA">Devolución de IVA</option>
                    <option value="Otros Impuestos">Otros Impuestos</option>

                    </optgroup>





                    <optgroup label="Gastos de N&oacute;mina">
                    <option value="Sueldo de los empleados">Sueldo de los empleados</option>
                    <option value="Seguridad social">Seguridad social</option>
                    <option value="Mantenimiento vehiculo">Mantenimiento vehiculo</option>
                    <option value="Repuestos vehiculo">Repuestos vehiculo</option>

                    </optgroup>
';
/////
}

if($ListEgreC){
$SelcOptTipoEgreso='';
$SelcOptTipoEgreso.=pucSelc();



}

// GENERICO
if($ListEgreD){


$SelcOptTipoEgreso='<optgroup label="Gastos Almac&eacute;n">';


					$SelcOptTipoEgreso=egreGeneralSelc();

					}




	$selc=$SelcOptTipoEgreso;
					$selc=minify_html($selc);
					return $selc;
};
function clasesOpt()
{
global $linkPDO;

	$rs=$linkPDO->query("SELECT des_clas FROM clases GROUP BY des_clas");
	$resp="";
	while($row = $rs->fetch())
         {
		 $resp.= "<option value=$row[des_clas]>$row[des_clas]</option>  ";
		 }
		 return  minify_html($resp);
};
function fabOpt()
{
	global $linkPDO;
	$sql="SELECT * FROM fabricantes";
	$rs=$linkPDO->query($sql);
	$resp="";
	while($row = $rs->fetch())
         {
		 $resp.= "<option value=$row[fabricante]>$row[fabricante]</option>  ";
		 }
		 return  minify_html($resp);
};
function presentacionOpt()
{
	global $linkPDO;
	$sql="SELECT * FROM presentacion";
	$rs=$linkPDO->query($sql);
	$resp="";
	while($row = $rs->fetch())
         {
		 $resp.= "<option value=$row[presentacion]>$row[presentacion]</option>  ";
		 }
		 return  minify_html($resp);
};

/////////////////////////////////////////////////// FIN HTML <select > /////////////////////////////////////////////////////////////////////////////
function imp_a($session,$valSession,$url,$nomPag,$x,$y)
{
	$_SESSION[$session]=$valSession;
	popup($url,$nomPag,$x,$y);

}


function popup($url,$nom_pag,$ancho,$alto)
{
echo "<script type='text/javascript'>
      window.open('$url','$nom_pag','width=$ancho,height=$alto,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
	  </script>";
};


function rol($rol)
{
	global $linkPDO;

	if(isset($_SESSION['id_usu'])&&isset($_SESSION['tipo_usu']))
	{
		$id=$_SESSION['id_usu'];
		$tipoUsu=$_SESSION['tipo_usu'];
		$qry="SELECT usu_login.usu,usu_login.cla,usu_login.id_usu FROM usu_login,tipo_usu,usuarios WHERE usuarios.id_usu='$id' AND usu_login.id_usu=tipo_usu.id_usu AND tipo_usu.des='$tipoUsu'";

		$rs=$linkPDO->query($qry);
		if($row=$rs->fetch())
		{

		}
		else {header("location: sesioncerrada.php");}

	}
	else {header("location: sesioncerrada.php");}
};

function val_re2x($tabla,$col1,$col2,$val1,$val2)
{
global $linkPDO;

$rs=$linkPDO->query("SELECT * FROM $tabla WHERE $col1='$val1' AND $col2='$val2'");
if($row=$rs->fetch()){ return 1;}

else{ return 0;}

}


function val_re($tabla,$col,$val)
{
global $linkPDO;

$rs=$linkPDO->query("SELECT * FROM $tabla WHERE $col='$val'");
if($row=$rs->fetch()){ return 1;}

else{ return 0;}

}



function money($num)
{
	global $PUNTUACION_MILES,$PUNTUACION_DECIMALES;
	$num = !empty($num)?$num:0;
	$num=round($num,2);
	$num=$num*1;
	$T=(string)$num;
   $T=quitacom($T);

	$i = strlen($T) - 1;
	$ii = strlen($T);
   	$T=preg_split('//',$T,-1,PREG_SPLIT_NO_EMPTY);

   $C = 0;
   $h = '';
   $ff=0;
   while($i >= 0) {

      if($C == 3 && $ii != 3 && $T[$i] != '-') {
         $h = $T[$i] . ',' . $h;
         $C = 0;
         }
      else {
         $h = $T[$i] . $h;
         }
	   if($T[$i]=='.'){$C=-1;
	   //$h=quitacom($h);
	   }
	   $C++;
	   $i--;

      }
   return "".$h;
};
function money2($num)
{
	global $PUNTUACION_MILES,$PUNTUACION_DECIMALES;
	if(!empty($num)){
		$num=round($num,2);
		$T=(string)$num;
		$T=quitacom($T);
		$t=$T;
		$a="";
		$b="00";
		if (substr_count($t, '.') > 0)
		{
		list($a, $b) = explode('.', $t);
		}
		else $a=$T;
		$i = strlen($a) - 1;
		$ii = strlen($a);

		$T=preg_split('//',$a,-1,PREG_SPLIT_NO_EMPTY);
		$x=0;
		$a;
		$b;
		$c=0;
		$C = 0;
		$h = '';
		$ff=0;
		while($i >= 0) {
			if($C == 3 && $ii != 3 && $T[$i] != '-') {
				$h = $T[$i] . "$PUNTUACION_MILES" . $h;
				$C = 0;
				}
			else {
				$h = $T[$i] . $h;
				}
			if($T[$i]==','){$C=-1;
			//$h=quitacom($h);
			}
			$C++;
			$i--;

			}
		return "$ ".$h;
	}
};
function money3($num)
{
	global $PUNTUACION_MILES,$PUNTUACION_DECIMALES;
	$num = !empty($num)?$num:0;
	$num=round($num,2);
	$T=(string)$num;
    $T=quitacom($T);
	$t=$T;
	$a="";
	$b="00";
	if (substr_count($t, '.') > 0)
	{
    list($a, $b) = explode('.', $t);
	}
	else $a=$T;
	$i = strlen($a) - 1;
	$ii = strlen($a);

   	$T=preg_split('//',$a,-1,PREG_SPLIT_NO_EMPTY);
   $x=0;
   $a;
   $b;
   $c=0;
   $C = 0;
   $h = '';
   $ff=0;
   while($i >= 0) {
      if($C == 3 && $ii != 3 && $T[$i] != '-') {
         $h = $T[$i] . "$PUNTUACION_MILES" . $h;
         $C = 0;
         }
      else {
         $h = $T[$i] . $h;
         }
      //if(T[i] != '-'&&T[i]=='.')


	   if($T[$i]==','){$C=-1;
	   //$h=quitacom($h);
	   }
	   $C++;
	   $i--;

      }
  if($b!=0) {return "$ ".$h."$PUNTUACION_DECIMALES".$b; }
  else {return money2($num);}

};
function money_dt($num)
{
	$PUNTUACION_MILES=",";
	$PUNTUACION_DECIMALES=".";
	$num = !empty($num)?$num:0;
	//$num=round($num,2);
	$T=(string)$num;
    $T=quitacom($T);
	$t=$T;
	$a="";
	$b="00";
	if (substr_count($t, '.') > 0)
	{
    list($a, $b) = explode('.', $t);
	}
	else $a=$T;
	$i = strlen($a) - 1;
	$ii = strlen($a);

   	$T=preg_split('//',$a,-1,PREG_SPLIT_NO_EMPTY);
   $x=0;
   $a;
   $b;
   $c=0;
   $C = 0;
   $h = '';
   $ff=0;
   while($i >= 0) {
      if($C == 3 && $ii != 3 && $T[$i] != '-') {
         $h = $T[$i] . "$PUNTUACION_MILES" . $h;
         $C = 0;
         }
      else {
         $h = $T[$i] . $h;
         }
	   if($T[$i]=="$PUNTUACION_MILES"){$C=-1;
	   //$h=quitacom($h);
	   }
	   $C++;
	   $i--;

      }
  if($b!=0) {return "$ ".$h."$PUNTUACION_DECIMALES".$b; }
  else {return "$ ".$h;}

};
function fecha($fechaENG, $separador='-')
{
	$fecha=$fechaENG;
	$kkk=preg_split("[-]",$fecha);
	$fechaESP="$kkk[2]".$separador."$kkk[1]".$separador."$kkk[0]";
	return $fechaESP;
};
function limp2($str)
{
	$n = preg_split("[|]",$str);
   $i = 0;
    $h = "";
   for($i = 0; $i < count($n); $i++) {
      $h = $h.$n[$i];
      }
   return $h;
};
function quitacom($val){
	
try{
	if(!empty($val)){
		$n = preg_split("[,]",$val);
		$i = 0;
		$h = "";
		for($i = 0; $i < count($n); $i++) {
			$h = $h.$n[$i];
			}
		if(empty($h)|| !is_numeric($h)){
			$h = 0;	
		}
		
		return (float)$h;
	}
	else return 0;
} catch (Exception $ex){
	
	return 0;
}
};
function quitaamp($val){

   $n = preg_split("[amp;]",$val);
   $i = 0;
    $h = "";
   for($i = 0; $i < count($n); $i++) {
      $h = $h.$n[$i];
      }

	  $Result=$h;
	  $htmlCods=array("&Aacute;","&aacute;","&Eacute;","&eacute;","&Iacute;","&iacute;","&Oacute;","&oacute;","&Uacute;","&uacute;","&Ntilde;","&ntilde;","&Uuml;","&uuml;");
	  //$jsCods=array("\u00C1","\u00E1","\u00B4","\u00C0","\u00E0","\u00C9","\u00E9","\u00C8","\u00E8","\u00CD","\u00ED","\u00CC","\u00EC","\u00O3","\u00F3","\u00D2","\u00F2","\u00DA","\u00FA","\u00D9","\u00F9","\u00D1","\u00F1","\u00DC","\u00FC");

	  $jsCods=array("\u00C1","\u00E1","\u00C9","\u00E9","\u00CD","\u00ED","\u00D3","\u00F3","\u00DA","\u00FA","\u00D1","\u00F1","\u00DC","\u00FC");
	  //$Result=str_replace($htmlCods,$jsCods,$h);


   return $Result;

};
function serial_ajustes($conex)
{
	global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="ajustes";
$tabla="ajustes";
$serial_col="num_ajuste";
$codSU_col="cod_su";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit'");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE $codSU_col=$nit order by $serial_col desc" );

	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};

function serial_exp($conex)
{
	global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="expedientes";
$tabla="exp_anticipo";
$serial_col="num_exp";
$codSU_col="cod_su";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit'");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();;
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE $codSU_col=$nit order by $serial_col desc");

	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};
function serial_comp_exp($conex)
{
	global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="comp exp";
$tabla="comp_anti";
$serial_col="num_comp";
$codSU_col="cod_su";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit'");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE $codSU_col=$nit order by $serial_col desc");

	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};
function serial_fac_ven($conex)
{
global $codContadoSuc,$ResolContado,$FechaResolContado,$cross_fac;
global $linkPDO;

$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="factura venta";
$tabla="fac_venta";
$serial_col="num_fac_ven";

$codSU_col="nit='$nit'";
$codSuSerial=" nit_sede='$nit'";

if(1){
$codSU_col="nit='1'";
$codSuSerial=" nit_sede='1'";

}
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND $codSuSerial");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;

	$rs2=$linkPDO->query("SELECT MAX($serial_col) AS us FROM $tabla WHERE $codSU_col AND resolucion='$ResolContado' AND fecha_resol='$FechaResolContado' AND prefijo='$codContadoSuc' ");

	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};
function serial_fac_gas($conex)
{
	global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="factura gasto";
$tabla="fac_gasto";
$serial_col="num_fac_gas";
$codSU_col="nit";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit'");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE $codSU_col=$nit AND tipo_venta!='Credito' order by $serial_col desc");

	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};
function serial_credito($conex)
{
global $codCreditoSuc,$ResolCredito,$FechaResolCredito;
global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="credito";
$tabla="fac_venta";
$serial_col="num_fac_ven";
$codSU_col="nit";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit'");

$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE $codSU_col=$nit AND fecha_resol='$FechaResolCredito' AND resolucion='$ResolCredito' AND prefijo='$codCreditoSuc' order by $serial_col desc");

	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};
function serial_fac($seccion,$tipoFac,$tabla="fac_venta")
{
global $codPapelSuc,$ResolPapel,$FechaResolPapel,$conex,$codCreditoSuc,$ResolCredito,$FechaResolCredito,$codContadoSuc,$ResolContado,$FechaResolContado,$codCreditoANTSuc,$ResolCreditoANT,$FechaResolCreditoANT,$codRemiPOS,$ResolRemiPOS,$FechaRemiPOS,$RangoRemiPOS,$codRemiCOM,$ResolRemiCOM,$FechaRemiCOM,$RangoRemiCOM,$codRemiCOM2,$ResolRemiCOM2,$FechaRemiCOM2,$RangoRemiCOM2,$cross_fac,$linkPDO,$ResolCompartidaFE,$ResolCompartida,$hostName;


$nit=$_SESSION['cod_su'];
//echo "nit: $nit";
$inf=0;
$sup=0;
$serial=0;
$secc="$seccion";
//$tabla="fac_venta";

$serial_col="num_fac_ven";
$codSU_col=" AND nit='$nit'";
$codSucSeriales="nit_sede='$nit'";

$RESOLUCION="";
$PREFIJO="";
$FECHA_RESOLUCION="";
if($tipoFac=="POS")
{
	$RESOLUCION=$ResolContado;
	$FECHA_RESOLUCION=$FechaResolContado;
	$PREFIJO=$codContadoSuc;
	
	if(!empty($ResolCompartida) && in_array($nit, $ResolCompartida)){

		$codSU_col = " AND (nit='".$ResolCompartida[0]."' ";
		foreach ($ResolCompartida as $key => $value) {
			$codSU_col.= " OR nit='".$value."'";
		}
		$codSU_col.=")";

	}
}
else if($tipoFac=="COM")
{
	$RESOLUCION=$ResolCredito;
	$FECHA_RESOLUCION=$FechaResolCredito;
	$PREFIJO=$codCreditoSuc;
	if(!empty($ResolCompartida) && in_array($nit, $ResolCompartida)){

		$codSU_col = " AND (nit='".$ResolCompartida[0]."' ";
		foreach ($ResolCompartida as $key => $value) {
			$codSU_col.= " OR nit='".$value."'";
		}
		$codSU_col.=")";

	}
}
else if($tipoFac=="PAPEL")
{
	$RESOLUCION=$ResolPapel;
	$FECHA_RESOLUCION=$FechaResolPapel;
	$PREFIJO=$codPapelSuc;
	if(!empty($ResolCompartidaFE) && in_array($nit, $ResolCompartidaFE)){

		$codSU_col = " AND (nit='".$ResolCompartidaFE[0]."' ";
		foreach ($ResolCompartidaFE as $key => $value) {
			$codSU_col.= " OR nit='".$value."'";
		}
		$codSU_col.=")";

	}
}
else if($tipoFac=="CRE")
{
	$RESOLUCION=$ResolCreditoANT;
	$FECHA_RESOLUCION=$FechaResolCreditoANT;
	$PREFIJO=$codCreditoANTSuc;
}

else if($tipoFac=="REM_POS")
{
	$RESOLUCION=$ResolRemiPOS;
	$FECHA_RESOLUCION=$FechaRemiPOS;
	$PREFIJO=$codRemiPOS;
}
else if($tipoFac=="REM_COM")
{
	$RESOLUCION=$ResolRemiCOM;
	$FECHA_RESOLUCION=$FechaRemiCOM;
	$PREFIJO=$codRemiCOM;
}
else if($tipoFac=="REM_COM2")
{
	$RESOLUCION=$ResolRemiCOM2;
	$FECHA_RESOLUCION=$FechaRemiCOM2;
	$PREFIJO=$codRemiCOM2;
}


if($cross_fac==1){
$codSU_col=" ";
$codSucSeriales="nit_sede='$nit'";
}
//is_array($ResolCompartidaFE)

//echo "codSU_col: $codSU_col";
$linkPDO->exec("SAVEPOINT consultaSerial");
$sql="SELECT serial_inf,serial_sup FROM seriales WHERE seccion='$secc' AND $codSucSeriales FOR UPDATE";
// echo "$sql <br>";
$rs=$linkPDO->query($sql);


$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$linkPDO->exec("SAVEPOINT consultaNumeroFac");
	$sql = "SELECT MAX($serial_col) AS us 
	        FROM $tabla 
			WHERE  ( $serial_col>=$inf AND $serial_col<=$sup)   
			AND fecha_resol='$FECHA_RESOLUCION' 
			AND resolucion='$RESOLUCION' 
			AND prefijo='$PREFIJO'  
			$codSU_col 
			FOR UPDATE";
			//echo "$sql <br>";

			 
	$rs2=$linkPDO->query($sql);

	if($row=$rs2->fetch()){

	     //echo "inferior: $inf _>".$row['us'];
		 $current=$row['us']*1;
		  if($current<$inf){return $inf;}
		      else if($current<$sup && $current >=$inf){return ($current+1);}

		      else if($current=$sup){return "LIMITE DE FACTURAS ALCANZADO";}

		}
	else {
		$sql = "SELECT MAX($serial_col) AS us 
	        FROM $tabla 
			WHERE  ( $serial_col=$inf )   
			AND fecha_resol='$FECHA_RESOLUCION' 
			AND resolucion='$RESOLUCION' 
			AND prefijo='$PREFIJO'  
			$codSU_col 
			FOR UPDATE";

		//echo "$sql <br>";

		$rsElse=$linkPDO->query($sql);
		
		if($row=$rsElse->fetch()){
			die( "Numero inferior {$inf} repetido, ERROR de sistema.");
		} else {
			return $inf;
		}
		
	}

	}
else return "NO HAY RANGO ESTABLECIDO";



};
function serial_papel()
{
global $codPapelSuc,$ResolPapel,$FechaResolPapel,$conex;
global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="resol_papel";
$tabla="fac_venta";
$serial_col="num_fac_ven";
$codSU_col="nit";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit'");

$nf_seri=$rs->rowCount();

if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE $codSU_col=$nit AND fecha_resol='$FechaResolPapel' AND resolucion='$ResolPapel' AND prefijo='$codPapelSuc' order by $serial_col desc");

	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};

function serial_fac_tras($conex)
{
	global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="factura compra";
$tabla="fac_com";
$serial_col="serial_tras";
$codSU_col="cod_su";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit' FOR UPDATE");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT MAX($serial_col) AS us FROM $tabla WHERE $codSU_col=$nit FOR UPDATE");

	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};
function serial_fac_dev($conex)
{
	global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="factura dev";
$tabla="fac_dev";
$serial_col="serial_fac_dev";
$codSU_col="cod_su";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit' FOR UPDATE");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE $codSU_col=$nit order by $serial_col desc FOR UPDATE");

	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};
function serial_traslado($conex)
{
	global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="traslado";
$tabla="fac_tras";
$serial_col="cod_tras";
$codSU_col="cod_su";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit' FOR UPDATE");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE $codSU_col=$nit order by $serial_col desc FOR UPDATE");

	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};
function serial_caja()
{
global $linkPDO;
global $conex;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='caja' AND nit_sede='$nit' FOR UPDATE");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT cod_caja AS us FROM caja where cod_su=$nit order by cod_caja desc FOR UPDATE");

	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};

function serial_fac_taller($conex)
{
	global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="factura taller";
$tabla="fac_taller";
$serial_col="num_fac_orden";
$codSU_col="nit";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit' FOR UPDATE");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE $codSU_col=$nit order by $serial_col desc FOR UPDATE");

	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};

function serial_fac_remi($conex)
{
	global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="remision";
$tabla="fac_taller";
$serial_col="num_fac_orden";
$codSU_col="nit";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit' FOR UPDATE");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE $codSU_col=$nit AND remision=1 order by $serial_col desc FOR UPDATE");
	//echo mysqli_num_rows($rs2);
	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};


function serial_comp_ingreso($conex)
{
	global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="comprobante ingreso";
$tabla="comprobante_ingreso";
$serial_col="num_com";
$codSU_col="nit";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit' FOR UPDATE");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT MAX($serial_col) AS us FROM $tabla WHERE cod_su='$nit' order by $serial_col desc FOR UPDATE" );
	//echo mysqli_num_rows($rs2);
	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};
function serial_comp_anti()
{
	global $codSuc;
	global $linkPDO;
	$tipo="Bono";
	$inf=0;
	$sup=0;
	$serial=0;
	$secc="comprobante anticipo";
	$tabla="comp_anti";
	$serial_col="num_com";
	$codSU_col="cod_su";
	$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$codSuc' FOR UPDATE");
	//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
	$nf_seri=$rs->rowCount();
	if($row=$rs->fetch())
	{
		$inf=$row['serial_inf'];
		$sup=$row['serial_sup'];
		//echo $inf;
		$rs2=$linkPDO->query("SELECT MAX($serial_col) AS us FROM $tabla  WHERE tipo_comprobante='$tipo' AND cod_su='$codSuc' FOR UPDATE" );
		//echo mysqli_num_rows($rs2);
		if($row=$rs2->fetch()){
			//echo "entra query us:";
			if($row['us']<$sup)return ($row['us']+1);
			else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
			}
		else return $inf;

		}
	else return "NO HAY RANGO ESTABLECIDO";

};
function serial_comp_egreso()
{
global $conex;
global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="comp_egreso";
$tabla="comp_egreso";
$serial_col="num_com";
$codSU_col="cod_su";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit' FOR UPDATE");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE cod_su='$nit' order by $serial_col desc FOR UPDATE" );
	//echo mysqli_num_rows($rs2);
	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};

function serial_inv_ini()
{

global $conex;
global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="Inventario Inicial";
$tabla="fac_com";
$serial_col="num_fac_com";
$codSU_col="cod_su";
$WHERE=" WHERE nit_pro='00000000-0' ";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit' FOR UPDATE");
//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla $WHERE order by $serial_col desc FOR UPDATE");
	//echo mysqli_num_rows($rs2);
	if($row=$rs2->fetch()){
	     //echo "entra query us:";
		 $us= $row['us']>=0?$row['us']:0;
		if($us<$sup)return ($us+1);
		else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
		}
	else return $inf;

	}
else return "NO HAY RANGO ESTABLECIDO";

};
function serial_id_pro()
{

global $conex;
global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="ref";
$tabla=tabProductos;
$serial_col="cast(id_pro as unsigned)";
$codSU_col="cod_su";
$WHERE=" WHERE $serial_col REGEXP '^[[:digit:]]+$' ";
//$WHERE="WHERE STRCMP(CAST($serial_col AS DECIMAL(10,0)), $serial_col) = 0";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit' FOR UPDATE");
//echo "SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE  $serial_col>=$inf AND $serial_col<=$sup order by us desc FOR UPDATE");
	//echo "SELECT $serial_col AS us FROM $tabla WHERE  $serial_col>=$inf AND $serial_col<=$sup order by $serial_col desc  ";
	//echo mysqli_num_rows($rs2);
	if($row=$rs2->fetch()){
	     //echo "entra query us:";

		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE ALCANZADO";
		}
	else return ($inf+1);

	}
else return "NO HAY RANGO ESTABLECIDO";

};
function serial_id_pro2()
{

global $conex;
global $linkPDO;
$nit=$_SESSION['cod_su'];
$inf=0;
$sup=0;
$serial=0;
$secc="ref";
$tabla=tabProductos;
$serial_col="cast(id_pro as unsigned)";
$codSU_col="cod_su";
$WHERE=" WHERE $serial_col REGEXP '^[[:digit:]]+$' ";
//$WHERE="WHERE STRCMP(CAST($serial_col AS DECIMAL(10,0)), $serial_col) = 0";
$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit' FOR UPDATE");
//echo "SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit'";
$nf_seri=$rs->rowCount();
if($row=$rs->fetch())
{
	$inf=$row['serial_inf'];
	$sup=$row['serial_sup'];
	//echo $inf;
	$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE  $serial_col>=$inf AND $serial_col<=$sup order by us desc FOR UPDATE");
	//echo "SELECT $serial_col AS us FROM $tabla WHERE  $serial_col>=$inf AND $serial_col<=$sup order by $serial_col desc  ";
	//echo mysqli_num_rows($rs2);
	if($row=$rs2->fetch()){
	     //echo "entra query us:";

		if($row['us']<$sup)return ($row['us']+1);
		else if($row['us']=$sup)return "LIMITE ALCANZADO";
		}
	else return ($inf+1);

	}
else return "NO HAY RANGO ESTABLECIDO";

};

function limpiarcampo($string){

$liberate =$string;


$liberate = str_replace("'","-", $liberate);
$liberate = str_replace("<","", $liberate);
$liberate = str_replace(">","", $liberate);
$liberate = str_replace("+","", $liberate);
$liberate = str_replace("$","", $liberate);




/*
$liberate = str_replace("#","", $liberate);
$liberate = str_replace(";","", $liberate);
$liberate = str_replace("&lt;","", $liberate);
$liberate = str_replace("&gt;","", $liberate);
$liberate = str_replace('&quot;',"", $liberate);*/

$liberate = str_replace('"',"", $liberate);
$liberate = str_replace("\\","", $liberate);

$liberate = str_replace("°","<br>", $liberate);
return trim(limp2($liberate));
};
function limpiarJS($dirty){
 $liberate=$dirty;
//$liberate = str_replace("'","\'", $liberate);

$liberate = str_replace('"','\"', $liberate);

/*

UPDATE your_table
SET your_field = REPLACE(your_field, '"', '&quot;')

*/

return trim($liberate);
};
function limpWhere($n)
{
	//$n=strtoupper($n);
	$m=$n;
	$m=str_replace("INSERT","",$m);
	$m=str_replace("UPDATE","",$m);
	$m=str_replace("DELETE","",$m);
	return $m;
};

function eco_alert($msj) {
    echo "<script type='text/javascript'>alert('$msj');</script>";
};
function js_location($url) {
    echo "<script type='text/javascript'>location.assign('$url');</script>";
};
function js_reload() {
    echo "<script type='text/javascript'>location.reload();</script>";
};
function js_close()
{
	 echo "<script type='text/javascript'>window.close();</script>";
};
function callback($thisURL)
{
	$_SESSION['url_back']=$thisURL;
	js_location("callback.php");

};


function prom_cost($cf,$util,$iva,$cant,$codBar,$pvp)
{

	global $tipo_utilidad,$promediar_costos,$codSuc;
	global $linkPDO;
	$sql_promCosto="";
	$Ecost=0;
	$Eexist=0;
	$IVA=($iva*1)/100 +1;
	$EcostFac=$cf*$cant;
//  echo "cf: $cf, cant: $cant------------>";
	$CostProm=0;
	if($tipo_utilidad=="A")$gan=($util/100)+1;
	else $gan=((100-$util)*1)/100;






	$sql="SELECT *, SUM(((unidades_frac+(exist*fraccion))/fraccion)*costo) as Ecost,SUM((unidades_frac+(exist*fraccion))/fraccion) as Eexist FROM inv_inter WHERE id_inter='$codBar' AND nit_scs='$codSuc'";

	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{
		$Ecost=$row['Ecost'];
		$Eexist=$row['Eexist'];
		if(($Eexist+$cant)>0)$CostProm=($Ecost+$EcostFac)/($Eexist+$cant);
		else $CostProm=0;

		if($row['Eexist']==0 || $Ecost<0)
		{
		  //echo "<div style=\"color:white;\" >EXIST=0<BR></div>";
		  $sql_promCosto="UPDATE inv_inter SET costo=$cf,precio_v=$pvp,iva=$iva,gana=$util WHERE id_inter='$codBar' AND nit_scs='$codSuc'";

		}
		else {
		$Ecost=$row['Ecost'];
		$Eexist=$row['Eexist'];
		$b1 = ($Eexist+$cant)>0?($Eexist+$cant):1;
		$CostProm=($Ecost+$EcostFac) / $b1;


		if($row['costo']==$cf)
		{
			$CostProm=$cf;

		}
		else{
			//echo "<div style=\"color:white;\" >costo distinto? BD COST:".$row['costo']."==$cf, RS:$CostProm<BR></div>";
			}

		$sql_promCosto="UPDATE inv_inter SET costo=$CostProm,precio_v=$pvp,gana=$util,iva=$iva WHERE id_inter='$codBar' AND nit_scs='$codSuc'";


		}

	}
	else {

		}

return $sql_promCosto;
};
function prom_cost_sede($cf,$util,$iva,$cant,$ref,$con,$codSuc)
{
	global $linkPDO;
	//echo "<div style=\"color:white;\" >MOD sede, COSTO FAC $cf, Cant: $cant<BR></div>";
	$sql_promCosto="";
	$Ecost=0;
	$Eexist=0;
	$EcostFac=$cf*$cant;
	$CostProm=0;
	$gan=((100-$util)*1)/100;
	$IVA=($iva*1)/100 +1;
	$pvp=($cf/$gan)*$IVA;
	$pvp=redondeo2($pvp);

	$sql="SELECT *, SUM(exist*costo) as Ecost,SUM(exist) as Eexist FROM inv_inter WHERE id_pro='$ref' AND nit_scs=$codSuc";
	//echo "<div style=\"color:white;\" >$sql;<BR>EcostF: $EcostFac, GAN: $gan, IVA: $IVA, pvp : $pvp</div>";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{
		if($row['exist']==0)
		{
		  //echo "<div style=\"color:white;\" >EXIST=0<BR></div>";
		  $sql_promCosto="UPDATE inv_inter SET costo=$cf,precio_v=$pvp,gana=$util,iva=$iva WHERE id_pro='$ref' AND nit_scs=$codSuc";
		}
		else {


		$Ecost=$row['Ecost'];
		$Eexist=$row['Eexist'];
		$CostProm=($Ecost+$EcostFac)/($Eexist+$cant);
		$pvp=($CostProm/$gan)*$IVA;
		$pvp=redondeo2($pvp);
		//echo "<div style=\"color:white;\" ><br>BD COST:".$row['costo'].", RS:$CostProm,Ecost: $Ecost, Eexist: $Eexist,Ecost: $EcostFac<BR></div>";
		if($row['costo']==$cf){$CostProm=$cf;

			}
		else{



			}

			$sql_promCosto="UPDATE inv_inter SET costo=$CostProm,precio_v=$pvp,gana=$util,iva=$iva WHERE id_pro='$ref' AND nit_scs=$codSuc";


		}

	}
	else {
		//echo "<div style=\"color:white;\" >rs VACIO<BR></div>";
		$sql_promCosto="UPDATE inv_inter SET costo=$cf,precio_v=$pvp,gana=$util,iva=$iva WHERE id_pro='$ref' AND nit_scs=$codSuc";
		}

return $sql_promCosto;

};
function MODprom_cost($cf,$util,$iva,$cant,$cantG,$ref,$con)
{};
function MODprom_cost_sede($cf,$util,$iva,$cant,$cantG,$ref,$con,$codSuc)
{};
function redondeo($num)
{
	if(!empty($num)){
		$NUM=quitacom($num);
		$result=round($NUM,2);
		return $result;
	}
	else {
		return 0;
	}

};
function redondeo2($num)
{
	$num = !empty($num)?$num:0;
	$NUM=quitacom($num);
	$result=round($NUM,4);
	return $result;
};
function redondeoF($num,$dec)
{
	global $tipo_redondeo;
	$result=0;
	$num = !empty($num)?$num:0;
	$NUM=quitacom($num);
	if($tipo_redondeo=="decimal")$result=round($NUM,$dec,PHP_ROUND_HALF_UP);

	//$result=round($NUM,$dec,PHP_ROUND_HALF_UP);
	else if($tipo_redondeo=="centena"){$result=ceil($NUM/100)*100;}
	return $result;
};

function query($sql)
{
/*
	global $conex;
	mysqli_query($conex, $sql);
	if (!mysqli_query($conex, $sql)) {
    printf("Errormessage: %s\n [$sql]", mysqli_error($conex));
	}

*/
};
function corta_txt($string,$len=NULL)
{
	if($len==NULL)$len=40;

	$strTrunk=substr(strip_tags($string),0,$len);
	if(strlen(strip_tags($string))>$len)
	{
		$strTrunk.="...";
	}
	return $strTrunk;
};

function resto_inv($codSuc,$fecha)
{
$conex = mysqli_connect("127.0.0.1", "root", "Cronos9173", "motosem");
/* comprobar conexión */
if (mysqli_connect_errno()) {
    printf("Conexión fallida: %s\n", mysqli_connect_error());
    exit();
}
$codSu=$codSuc;
$fechaI=$fecha;
$fechaF="";
$entrasSalidasInv="SELECT (select SUM(cant) from art_fac_com a inner join fac_com f on a.num_fac_com=f.num_fac_com  where a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSu) INcom,

(select sum(cant) cant from art_tras at inner join (select * from traslados) t ON at.cod_tras=t.cod_tras WHERE t.cod_su=at.cod_su and t.cod_su_dest=$codSu AND t.estado='CONFIRMADO') INtras,

(select sum(cant) cant from art_fac_ven av inner join (select * from fac_venta) f ON av.num_fac_ven=f.num_fac_ven WHERE f.nit=av.nit AND f.nit=$codSu AND f.anulado='ANULADO') INnull,

(select sum(cant) cant from art_tras at inner join traslados t ON at.cod_tras=t.cod_tras WHERE t.cod_su=at.cod_su and t.cod_su=$codSu AND t.fecha_envio>='$fechaI') OUTtras,

(select sum(cant) cant from art_fac_ven ar inner join (select * from fac_venta f ) fv on fv.num_fac_ven=ar.num_fac_ven where ar.nit=fv.nit and ar.nit=$codSu)  OUTven,

(select SUM(cant) from art_fac_taller a inner join (select * from fac_taller) f on a.num_fac_orden=f.num_fac_orden where a.nit=f.nit and a.nit=$codSu AND f.estado!='CERRADA') OUTtaller,

(select SUM(exist) from inv_inter i where i.nit_scs=$codSu) Totinv,
(select SUM(IFNULL(INcom,0)+IFNULL(INtras,0)+IFNULL(INnull,0))) TotIN,
(select SUM(IFNULL(OUTtras,0)+IFNULL(OUTven,0)+IFNULL(OUTtaller,0))) TotOUT,
(select SUM(IFNULL(TotIN,0)-IFNULL(TotOUT,0))) InvIDEAL,
(select SUM(IFNULL(InvIDEAL,0)-IFNULL(Totinv,0))) Differencia
";

$RestoreInv="
UPDATE inv_inter set exist=0 where nit_scs=$codSu;

RESTAURAR CANT DE COMPRAS

UPDATE `inv_inter` i INNER JOIN (select sum(cant) cant,ref,cod_su from art_fac_com ar WHERE ar.cod_su=$codSu group by ar.ref) a ON i.id_pro=a.ref SET i.exist=(a.cant) WHERE i.nit_scs=a.cod_su AND i.nit_scs=$codSu;

UPDATE `inv_inter` i INNER JOIN (select at.cod_su,sum(cant) cant,ref,cod_su_dest,estado from art_tras at inner join traslados t ON at.cod_tras=t.cod_tras WHERE t.cod_su=at.cod_su AND t.cod_su_dest=$codSu AND t.estado='CONFIRMADO' group by at.ref) a ON a.ref=i.id_pro SET i.exist=(i.exist+a.cant) where i.nit_scs=a.cod_su_dest and i.nit_scs=$codSu;

UPDATE `inv_inter` i
INNER JOIN
(select ar.nit nitAr,sum(cant) cant,ref from art_fac_ven ar inner join (select * from fac_venta f  ) fv ON fv.num_fac_ven=ar.num_fac_ven WHERE fv.nit=ar.nit and fv.nit=$codSu group by ar.ref) a
ON i.id_pro=a.ref
SET i.exist=(i.exist-a.cant) WHERE i.nit_scs=a.nitAr and i.nit_scs=$codSu;

UPDATE `inv_inter` i
INNER JOIN
(select at.nit nitAt,sum(cant) cant,ref from art_fac_taller at inner join (select * from fac_taller f) ft ON ft.num_fac_orden=at.num_fac_orden WHERE ft.nit=at.nit and  ft.nit=$codSu AND ft.estado!='CERRADA' group by at.ref) a
ON i.id_pro=a.ref
SET i.exist=(i.exist-a.cant) WHERE i.nit_scs=a.nitAt and i.nit_scs=$codSu;

UPDATE `inv_inter` i INNER JOIN (select av.nit nitAv,sum(cant) cant,ref,av.nit,anulado,av.num_fac_ven from art_fac_ven av inner join fac_venta f ON av.num_fac_ven=f.num_fac_ven WHERE f.nit=av.nit and f.nit=av.nit AND f.nit=$codSu AND f.anulado='ANULADO' group by av.ref) a ON i.id_pro=a.ref SET i.exist=(i.exist+a.cant) where i.nit_scs=a.nitAv and i.nit_scs=$codSu;

UPDATE `inv_inter` i INNER JOIN (select sum(cant) cant,ref,cod_su_dest,estado,t.cod_su from art_tras at inner join traslados t ON at.cod_tras=t.cod_tras WHERE t.cod_su=at.cod_su AND t.cod_su=$codSu AND t.fecha_envio>='$fechaI' group by at.ref) a ON i.id_pro=a.ref SET i.exist=(i.exist-a.cant) WHERE i.nit_scs=a.cod_su AND i.nit_scs=$codSu;
";
	mysqli_multi_query($conex, $RestoreInv);
	
};
function lock_tables($tables)
{
	global $linkPDO;
	//$linkPDO->exec("SET autocommit=0;");
	$linkPDO->exec("LOCK TABLES $tables;");



};





function t1($SQL)
{

	global $linkPDO;
	try {
	$linkPDO->beginTransaction();
	$all_query_ok=true;


	$linkPDO->exec($SQL);


	$linkPDO->commit();
	if($all_query_ok){return true;}
	else {return false;}
	}catch (Exception $e) {
	$linkPDO->rollBack();
	echo "Failed: " . $e->getMessage();
	}

};
function t2($q1,$q2)
{
	global $linkPDO;
	try {
	$linkPDO->beginTransaction();

	$linkPDO->exec($q1);
	$linkPDO->exec("SAVEPOINT t2");
	$linkPDO->exec($q2);

	$linkPDO->commit();

	}catch (Exception $e) {$linkPDO->rollBack();echo "Failed: [$q1]- -[$q2]" . $e->getMessage();}

};

function t3($q1,$q2,$q3)
{
	global $linkPDO;
	try {
	$linkPDO->beginTransaction();
	$all_query_ok=true;


	$linkPDO->exec($q1);
	$linkPDO->exec("SAVEPOINT t3");
	$linkPDO->exec($q2);
	$linkPDO->exec("SAVEPOINT t3b");
	$linkPDO->exec($q3);

	$linkPDO->commit();

	if($all_query_ok){return true;}
	else {return false;}
	}catch (Exception $e) {
	$linkPDO->rollBack();
	echo "Failed: " . $e->getMessage();
	}
};
function th($str,$sort,$width,$url,$pag,$classActive)
{
$th="<th width=\"$width\">
<div style=\"display:inline-block;table-layout:fixed;width:80%;\">$str</div>
<div style=\"display:inline-block;table-layout:fixed;width:20%; \">
<a data-role=\"button\" data-iconpos=\"notext\" href=\"$url?sort=$sort&pag=$pag\" data-icon=\"arrow-d\" class=\"$classActive[$sort]\" data-inline=\"true\"> </a>
</div>
</th>";
return $th;
	}
function munSuc($codsuc)
{
	global $linkPDO;
	$qry="SELECT sucursal.*,departamento.departamento,municipio.municipio,usuarios.nombre,usuarios.tel FROM usuarios,sucursal,departamento,municipio WHERE sucursal.cod_su=$codsuc AND sucursal.id_dep=departamento.id_dep AND sucursal.id_mun=municipio.id_mun";

	$rs=$linkPDO->query($qry);
	if($row=$rs->fetch())
	{
		return $row['municipio'];
        //$row['departamento'];

	}
	else return "Sede no Encontrada";
};

function shutDownPC($_PCShutdown = 30, $_force = FALSE)
{
	exec('shutdown -s -t ' . intval($_PCShutdown) . ($_force ? ' -f' : ''));
}
if (isset($_POST['powersav_update']) && ($_POST['powersav_update'] == 'Update'))
{
$time = $_POST['PCshutdown'];
shutDownPC($time*60);
}

function randomColor() {
$str = '#';
for ($i = 0; $i < 6; $i++) {
    $randNum = rand(0, 15);
    switch ($randNum) {
        case 10: $randNum = 'A';
            break;
        case 11: $randNum = 'B';
            break;
        case 12: $randNum = 'C';
            break;
        case 13: $randNum = 'D';
            break;
        case 14: $randNum = 'E';
            break;
        case 15: $randNum = 'F';
            break;
    }
    $str .= $randNum;
}
return $str;}




/////////////////////////////////////////////////////// ADMIN PERMISOS /////////////////////////////////////////////////////////////////////////////

function val_secc($id_usu,$id_secc,$conex = null)
{
	global $linkPDO;
	$sql="SELECT * FROM permisos WHERE id_usu='$id_usu' AND id_secc='$id_secc'";
	$rs=$linkPDO->query($sql);
	$permiso="No";
	if($row=$rs->fetch())
	{
		$permiso=$row['permite'];
		//eco_alert("Permiso : $permiso");
	}
	$sql="SELECT * FROM secciones WHERE id_secc='$id_secc'";
	$rs=$linkPDO->query($sql);
	$row=$rs->fetch();
	$habil=$row['habilita'];
	if($permiso=="Si" && $habil==1) return 1;
	else return 0;
};
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function exist_fecha($codSuc,$fechaI,$reference)
{
	global $fechaKardex;
	global $linkPDO;

	$RESP="0";

	$saldo[]=0;
	$refColor[]="";
	$refCosto[]=0;
	$SUMsaldos[]=0;
	$SUMcostos[]=0;
	$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE nit_scs=$codSuc");
	while($row=$rs->fetch())
	{

	$saldo[$row['id_inter']]=0;
	$refColor[$row['id_inter']]=randomColor();
	$refCosto[$row['id_inter']]=$row['costo'];
	$SUMsaldos[$row['id_inter']]=0;
	$SUMcostos[$row['id_inter']]=0;
	}


	$Az="";
	$Za="";


	$fechaF=$fechaI;
	$busq=$reference;
	$col=1;
	$ORDEN=array(1=>"cod_barras","des");

	$ORDER_BY="";
	if($col==1)$ORDER_BY="fe";
	else $ORDER_BY="fe";


	if($col==1){
		$sql="select 1 as src,a.num_fac_com as num,cod_barras,des,cant,a.iva,costo as precio,a.tot,DATE(f.fecha_mod) fecha,f.fecha as fe from art_fac_com a inner join fac_com f on a.num_fac_com=f.num_fac_com  where f.estado='CERRADA' AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc  and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col]='$busq' )
		UNION

		select 2 as src,a.num_fac_ven as num,cod_barras,des,cant,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe from art_fac_ven a inner join fac_venta f on a.num_fac_ven=f.num_fac_ven  where a.prefijo=f.prefijo and a.nit=f.nit and f.".VALIDACION_VENTA_VALIDA." and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col]='$busq' )
		UNION

		select 4 as src,a.num_fac_ven as num,cod_barras,des,cant,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe from art_fac_ven a inner join fac_venta f on a.num_fac_ven=f.num_fac_ven  where a.prefijo=f.prefijo AND a.nit=f.nit and f.anulado='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col]='$busq' )

		UNION


		select 7 as src,a.num_fac_com as num,cod_barras,des,cant,a.iva,costo as precio,a.tot,DATE(f.fecha) fecha,f.fecha as fe from art_fac_dev a inner join fac_dev f on a.num_fac_com=f.num_fac_com  where a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col]='$busq' )
		UNION

		select 8 as src,a.num_ajuste as num,cod_barras,des,cant,a.iva,costo as precio,a.precio,DATE(f.fecha) fecha,f.fecha as fe from art_ajuste a inner join ajustes f on a.num_ajuste=f.num_ajuste  where a.cod_su=f.cod_su  and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col]='$busq' )

		ORDER BY $ORDER_BY
		";
	}

	else if($col==2){
		$sql="select 1 as src,a.num_fac_com as num,ref,des,cant,a.iva,costo as precio,a.tot,DATE(f.fecha_mod) fecha,f.fecha as fe from art_fac_com a inner join fac_com f on a.num_fac_com=f.num_fac_com  where f.estado='CERRADA' AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc  and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%' )
		UNION

		select 2 as src,a.num_fac_ven as num,ref,des,cant,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe from art_fac_ven a inner join fac_venta f on a.num_fac_ven=f.num_fac_ven  where a.prefijo=f.prefijo and a.nit=f.nit and f.".VALIDACION_VENTA_VALIDA." and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%' )
		UNION


		select 4 as src,a.num_fac_ven as num,ref,des,cant,a.iva,costo as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe from art_fac_ven a inner join fac_venta f on a.num_fac_ven=f.num_fac_ven  where a.prefijo=f.prefijo AND a.nit=f.nit and f.anulado='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%' )

		UNION

		select 5 as src,a.cod_tras as num,ref,des,cant,a.iva,costo,a.tot,DATE(f.fecha_envio) fecha,f.fecha_envio as fe from art_tras a inner join traslados f on a.cod_tras=f.cod_tras  where a.cod_su=f.cod_su and a.cod_su=$codSuc and DATE(f.fecha_envio)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%' )
		UNION

		select 6 as src,a.cod_tras as num,ref,des,cant,a.iva,costo,a.tot,DATE(f.fecha_envio) fecha,f.fecha_envio as fe from art_tras a inner join (select * from traslados) f on a.cod_tras=f.cod_tras  where a.cod_su=f.cod_su and f.cod_su_dest=$codSuc AND f.estado='CONFIRMADO' and DATE(f.fecha_envio)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%' )
		UNION

		select 7 as src,a.num_fac_com as num,ref,des,cant,a.iva,costo as precio,a.tot,DATE(f.fecha) fecha,f.fecha as fe from art_fac_dev a inner join fac_dev f on a.num_fac_com=f.num_fac_com  where a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%')
		UNION

		select 8 as src,a.num_ajuste as num,ref,des,cant,a.iva,costo as precio,a.precio,DATE(f.fecha) fecha,f.fecha as fe from art_ajuste a inner join ajustes f on a.num_ajuste=f.num_ajuste  where a.cod_su=f.cod_su  and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%')

		ORDER BY $ORDER_BY
		";
	}

	//echo "ORDEN:  $ORDEN[$col], col: $col";
	$rs=$linkPDO->query($sql);

	$fuente=array(1=>"Compra","Venta","Orden Taller","ANULADO","Envio Traslado","Recibo Traslado","Devolucion","Ajuste Inventario");
	$signo=array(1=>"+","-","-","~","-","+","-","+");

	$refQuery[]=0;

	////////////////////////////////////////////////// FIN PRE ANALISIS ///////////////////////////////////////////////////////////////////////////
	$fuente=array(1=>"Compra","Venta","Orden Taller","ANULADO","Envio Traslado","Recibo Traslado","Devolucion","Ajuste Inventario");
	////////////////////////////////////////////////////////// CONSULTA //////////////////////////////////////////////////////////////
	$rs=$linkPDO->query($sql);
	$contMES=0;
	$flagMES=0;
	$dateArray="";
	while($row=$rs->fetch())
	{
		$num_fac=$row['num'];
		$subTot=money($row['tot']);
		$IVA=money($row['iva']);
		//$des=htmlentities($row['des'], ENT_QUOTES,"$CHAR_SET");
		$des=$row['des'];
		$cant=$row['cant']*1;
		$valor=money($row['precio']);
		$ref=$row['cod_barras'];
		//$vendedor=htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET");
		//$HORA=$row['hora'];
		$fecha=$row['fecha'];
		$src=$row['src'];
		if($signo[$src]=="+")$saldo[$ref]+=$cant;
		else if($signo[$src]=="-")$saldo[$ref]-=$cant;

		if($fecha<=$fechaF){
		$RESP=$saldo[$ref];
		$dateArray=explode("-",$fecha);
		$MES=1*$dateArray[1];
		if($MES!=$flagMES)$contMES=0;
		//if($fuente[$src]=="Venta"||$fuente[$src]=="Envio Traslado"||$fuente[$src]=="Orden Taller"||$fuente[$src]=="Devolucion")$saldo[$ref]+=$cant;
		$SUMsaldos[$ref]=$saldo[$ref];
		$SUMcostos[$ref]=$saldo[$ref]*$refCosto[$ref];
		$RESP=$saldo[$ref];


		$SUM_CANT=array_sum($SUMsaldos);
		$SUM_COSTOS=array_sum($SUMcostos);



		if($contMES==0){
			$contMES=1;
						}



		$flagMES=$MES;


		}// FIN RANGO FECHA

	}

	return $RESP;
	};
function utf8($string)
{

	$resp=htmlentities($string,ENT_QUOTES,'UTF-8');
	return $resp;
};


///////////////////////////////////////////////////////// SQLite FUNCIONES //////////////////////////////////////////////////////////////////////////
$TABLE="CREATE TABLE auditoria (
	audit_id	INTEGER,
	sql	TEXT,
	usu	TEXT,
	id_usu	TEXT,
	ip_host TEXT,
	tipo_operacion	TEXT,
	seccion_afectada	TEXT,
	fecha_op	TEXT,
	hora_op	TEXT
);";

function get_ip()
{
$ipaddress = '';
      if (getenv('HTTP_CLIENT_IP'))
          $ipaddress = getenv('HTTP_CLIENT_IP');
      else if(getenv('HTTP_X_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if(getenv('HTTP_X_FORWARDED'))
          $ipaddress = getenv('HTTP_X_FORWARDED');
      else if(getenv('HTTP_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if(getenv('HTTP_FORWARDED'))
          $ipaddress = getenv('HTTP_FORWARDED');
      else if(getenv('REMOTE_ADDR'))
          $ipaddress = getenv('REMOTE_ADDR');
      else
          $ipaddress = 'UNKNOWN';

      return $ipaddress;
};
function liteConex()
{


};
function lite_exe($stm)
{

};

function logDB($sql,$secc,$tipoOP,$RegAnt,$RegNew,$RegKey)
{
	global $id_Usu,$nomUsu,$FechaHoy,$hora,$codSuc,$linkPDO;
	/*
	$sql=sqlite_escape_string($sql);
	$id_Usu=sqlite_escape_string($id_Usu);
	$nomUsu=sqlite_escape_string($nomUsu);
	$RegNew=sqlite_escape_string($RegNew);
	$RegAnt=sqlite_escape_string($RegAnt);
	*/
	$sql="";
	$id_Usu=limpiarcampo($id_Usu);
	$nomUsu=limpiarcampo($nomUsu);
	$RegNew=minify_html($RegNew);
	$RegAnt=minify_html($RegAnt);
	$RegKey=limpiarcampo($RegKey);
	$ip=get_ip();

	$stm="INSERT INTO `auditoria`(`sql`, 
	                              `usu`, 
								  `id_usu`, 
								  `ip_host`, 
								  `tipo_operacion`, 
								  `seccion_afectada`, 
								  `reg_ant`, 
	                              `reg_new`, 
								  `reg_key`, 
								  `fecha_op`, 
								  `hora_op`, 
								  `id_sede`)  
		  VALUES('$sql',
		         '$nomUsu',
				 '$id_Usu',
				 '$ip',
				 '$tipoOP',
				 '$secc',
				 '$RegAnt',
				 '$RegNew',
				 '$RegKey',
		         '$FechaHoy',
				 '$hora',
				 '$codSuc')";
	//echo "$stm<br>";
	$linkPDO->exec($stm);
};
function fal($rs)
{
	global $conex;
	$row=mysqli_fetch_array($rs, MYSQLI_ASSOC);
	return $row;
};
function rsl($sql)
{

};
function minify_html($d) {
    $d = str_replace(array(chr(9), chr(10), chr(11), chr(13)), ' ', $d);
    $d = preg_replace('`<\!\-\-.*\-\->`U', ' ', $d);
	$d = preg_replace('/[ ]+/', ' ', $d);
    $d = str_replace('> <', '><', $d);
	$d = str_replace("'","\'", $d);
    return $d;
}
function minify_css1($d) {
    $d = str_replace(array(chr(9), chr(10), chr(11), chr(13)), ' ', $d);
    $d = preg_replace('`/\*.*\*/`U', ' ', $d);
    $d = preg_replace('/[ ]+/', ' ', $d);
    $d = str_replace('; ', ';', $d);
    $d = str_replace('} ', '}', $d);
    $d = str_replace('{ ', '{', $d);
    $d = str_replace(': ', ':', $d);
    $d = str_replace(' {', '{', $d);
    return htmlspecialchars($d);
}
////////////////////////////////////////////////////////////////////// FIN SQLite /////////////////////////////////////////////////////////////////
function punto_a_coma($str)
{
	$n = str_replace('.', ',', $str);

   return $n;
};



//////////////////////////////////////////////////////////////////////////// INFORMES ////////////////////////////////////////////////////////////////////////////////
function costo_now($codSuc,$con_sin)
{
	global $linkPDO;
$TOT_COSTO_SIN_IVA=0;
$TOT_COSTO=0;
$TOT_PVP=0;
$resp[]=0;

$sql="SELECT IFNULL( ROUND(SUM((costo*(1+(iva/100)))*((unidades_frac+(exist*fraccion))/fraccion ) )) , 0 ) AS TOTcostoIVA,
IFNULL( ROUND(SUM(costo*((unidades_frac+(exist*fraccion))/fraccion ) )) , 0 ) AS TOTcosto,
IFNULL( ROUND(SUM(precio_v*((unidades_frac+(exist*fraccion))/fraccion ) )) , 0 ) AS TOTpvp
FROM inv_inter WHERE nit_scs='$codSuc'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOT_COSTO=$row['TOTcostoIVA'];
	$TOT_COSTO_SIN_IVA=$row['TOTcosto'];
	$TOT_PVP=$row["TOTpvp"];
	/*
	if($con_sin=="con")$resp=$TOT_COSTO;
	else $resp=$TOT_COSTO_SIN_IVA;*/

	$resp[0]=$TOT_COSTO;
	$resp[1]=$TOT_COSTO_SIN_IVA;
	$resp[2]=$TOT_PVP;
}

return $resp;
};
function redondeo3($num)
{
	$num = !empty($num)?$num:0;
	$NUM=quitacom($num);
	$result=round($NUM,2);
	return $result;
};
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function r($REQUEST_VARIABLE)
{
	$resp="";
	if(isset($_REQUEST[$REQUEST_VARIABLE]) )$resp=limpiarcampo(trim($_REQUEST[$REQUEST_VARIABLE]));
	$resp = str_replace("|","", $resp);
	return $resp;
};
function r_array($REQUEST_VARIABLE)
{
	$resp="";

	if(isset($_REQUEST[$REQUEST_VARIABLE]) ){

		$resp=array_map('trim',$_REQUEST[$REQUEST_VARIABLE]);

	}
	return $resp;
};
function s_array($SESSION_VARIABLE)
{
	$resp="";
	if(isset($_SESSION[$SESSION_VARIABLE])&& !empty($_SESSION[$SESSION_VARIABLE])){
		//$resp=limpiarcampo($_SESSION[$SESSION_VARIABLE]);
		$resp=array_map('trim',$_SESSION[$SESSION_VARIABLE]);
	}
	return $resp;
};
function rm($REQUEST_VARIABLE)
{
	global $CHAR_SET;
	$resp="";
	if(isset($_REQUEST[$REQUEST_VARIABLE]))$resp=limpiarcampo(trim(mb_strtoupper($_REQUEST[$REQUEST_VARIABLE],"$CHAR_SET")));
	$resp = str_replace("|","", $resp);
	return $resp;
};

function s($SESSION_VARIABLE)
{
	$resp="";
	if(isset($_SESSION[$SESSION_VARIABLE]))$resp=limpiarcampo($_SESSION[$SESSION_VARIABLE]);
	$resp = str_replace("|","", $resp);
	return $resp;
};
function varSesionSistema($nombreSesion)
{
	$session=(isset($_SESSION[$nombreSesion])) ? $_SESSION[$nombreSesion]:'';
	return trim($session);
};
function calc_gana($cost,$pvp,$iva)
{
	//echo "C: $cost, P: $pvp, $iva;  ";
	$resp=0;
	$C=quitacom($cost);
	$P=quitacom($pvp);

	$IVA= ($iva/100) +1;
	$gan=0;
	//echo "c: $C, p: $P, Iva: $IVA, gcia: $gan<<<<<<<<";
	if($P!=0)
	{

		$gan=($C/$P)*$IVA;
		//echo "($C/$P)*$IVA >>> gan: $gan ;<br> ";
		$resp=redondeoF((1-$gan)*100,2);
		//echo "RTA: $resp";
	}

	return $resp;
};
function util($pvp,$costoSIN,$iva,$modo_tot_per, $impuesto_saludable = 0)
{
	global $tipo_utilidad,$usar_iva;
	$resp=0;
	$pvp=quitacom($pvp);
	if($pvp==0)$pvp=1;
	//if($usar_iva==0){$iva=0;}
	$costoSIN=quitacom($costoSIN);
	//echo "$pvp,$costoSIN,$iva <br>";
	$impSaludable = $impuesto_saludable/100;
	$ivaD=$iva/100 + $impSaludable;
	$costoIVA=$costoSIN*($ivaD+1);
	//echo "costoIVA= $costoSIN * ($ivaD+1)";
	$utilBRUTA=$pvp-$costoIVA;
	$impuestos=$ivaD*$utilBRUTA;
	//$utilNETA=$utilBRUTA - $impuestos;
	$utilNETA=$utilBRUTA;
	//echo "$utilNETA,$impuestos,$utilBRUTA <br>";

	if($modo_tot_per=="per")
	{
		if($costoIVA!=0){
		if($tipo_utilidad=="A"){
			if($costoIVA==0){$costoIVA=1;}
			// gan=((valorArticulo-costoProducto)/costoProducto)*100 ||0;
			$resp= (($pvp-$costoIVA)/$costoIVA)*100;
			//echo "100*($utilNETA/$costoIVA) ---> $resp";

		}
		//else $resp=redondeo(100*(($pvp/$costoIVA)-1));
		else {
			if($pvp==0){$pvp=1;}
		    //$resp=redondeo(100*(1-($costoIVA/$pvp)));
		    $resp= 100*(1-($costoIVA/$pvp));
			//echo "  100*(1-($costoIVA/$pvp)) ";
		}
		//echo "100*($utilNETA/$pvp) $utilBRUTA - $impuestos $iva";
		//echo $resp;
		}
		else $resp=0;
	}
	else
	{
		$resp=$utilNETA;
	}
	
	return redondeo($resp);
};

function util_tot($pvp,$costoIVA,$modo_tot_per)
{
	global $tipo_utilidad,$usar_iva;
	$resp="";
	$pvp=quitacom($pvp);
	$costoIVA=quitacom($costoIVA);

	$utilBRUTA=$pvp-$costoIVA;

	$utilNETA=$utilBRUTA;
	if($costoIVA<=0)$costoIVA=$pvp;

	//echo "$pvp,$costoIVA  <br>";
	//echo "$utilNETA, ,$utilBRUTA <br>";

	if($modo_tot_per=="per")
	{
		if($tipo_utilidad=="A")
		{
			if($pvp!=0)$resp=redondeo(100*($utilNETA/$pvp));
			else $resp=0;
		}
		else $resp=redondeo(100*($utilNETA/$costoIVA));

		//echo "100*($utilNETA/$pvp) $utilBRUTA, C:$costoIVA<br>";
	}
	else
	{
		$resp=$utilNETA;
	}

	return $resp;
};
function calc_pvp($costoSIN_IVA,$util,$iva)
{
	global $tipo_utilidad,$redondear_pvp_costo;
	$resp=0;
	$IVA=($iva*1)/100 +1;
	$costoIVA=$costoSIN_IVA*$IVA;
	//echo "costIVA:$costoIVA";
	if($tipo_utilidad=="A")
	{
		$gan=($util/100) +1;
		$pvp=$costoIVA*$gan;
		$resp=$pvp;
		//echo "A: $resp";
		}
	else{
	$gan=((100-$util)*1)/100;

	$pvp=($costoSIN_IVA/$gan)*$IVA;

	$pvp=redondeoF($pvp,0);

	$resp=$pvp;
	//echo "B: $resp";
	}

	if($redondear_pvp_costo=="s")$resp=redondeoF($pvp,0);
	else $resp=redondeoF($pvp,0);
	return $resp;
};
function excel($nomInforme)
{

global $CHAR_SET;
//header("Content-Type:   application/vnd.ms-excel; charset=$CHAR_SET");
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=$nomInforme.xls");  //File name extension was wrong
//header("Cache-Control: private",false);

};

function word($nomInforme)
{
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$nomInforme.doc");

};

function thisURL() {
 $pageURL = 'http';
 if (isset($_SERVER["HTTPS"])&&$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
};
function num_rows($rs)
{
	return $rs->rowCount();
};
function tot_comp_egreso($fechaI,$fechaF,$codSuc,$tipo_almacen_personales_otros,$tipoPago_contado_otros)
{
	global $linkPDO;
	$tipo=$tipo_almacen_personales_otros;
	$tipo_pago=$tipoPago_contado_otros;
	$resp=0;
	$B="";
	$C="";
	if($tipo=="almacen")$B=" AND tipo_gasto='Almacen'";
	else if($tipo=="personales") $B=" AND tipo_gasto='Personales'";
	else if($tipo=="otros") $B=" AND tipo_gasto='Otros'";

	if($tipo_pago=="contado")$C=" AND tipo_pago='Contado'";
	else if($tipo_pago=="otros") $C=" AND tipo_pago!='Contado'";


	$sql="SELECT SUM(valor) as t FROM comp_egreso WHERE DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND cod_su='$codSuc' AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) $B $C";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{

		$resp=$row['t'];
	}

	return $resp;

};
function tot_anticipos($filtroCaja,$fechaI,$fechaF,$codSuc,$tipoPago_contado_tDebito_tCredito,$tipo_bono_anticipo,$cobrado = "p")
{
	global $linkPDO;
	$F="";
	//echo "<li>$filtroCaja</li>";
	if($filtroCaja!=0){$F=$filtroCaja;}
	$filtroSEDE_cod_su="AND cod_su=$codSuc";
	if($codSuc=="all")
	{$filtroSEDE_cod_su=" ";}
	$tipo=$tipo_bono_anticipo;
	$tipo_pago=$tipoPago_contado_tDebito_tCredito;
	$resp[]=0;
	$A="";
	$B="";
	$C="";
	//echo "<li>tipo: $tipo , Tipo pago: $tipo_pago</li>";
	if($cobrado=="si") $A=" AND estado='COBRADO'";

	if($tipo=="anticipo")$B=" AND tipo_comprobante='Anticipo'";
	else if($tipo=="bono") $B=" AND tipo_comprobante='Bono'";

	if($tipo_pago=="contado")$C=" AND tipo_pago='Contado'";
	else if($tipo_pago=="tDebito") $C=" AND tipo_pago='Tarjeta Debito'";
	else if($tipo_pago=="tCredito") $C=" AND tipo_pago='Tarjeta Credito'";

	//OR (DATE(fecha_anula)!=DATE(fecha) AND  estado='ANULADO')
	if($fechaI==$fechaF)
	{
$sql="SELECT SUM(valor) as t, COUNT(*) AS c FROM comp_anti WHERE DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroSEDE_cod_su AND (estado!='ANULADO' ) $A $B $C $filtroCaja";
	}
	else
	{
//$sql="SELECT SUM(valor) as t, COUNT(*) AS c FROM comp_anti WHERE DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND cod_su='$codSuc' AND num_com NOT IN(SELECT num_com FROM comp_egreso  WHERE  cod_su=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') $B $C";
$sql="SELECT SUM(valor) as t, COUNT(*) AS c FROM comp_anti WHERE DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroSEDE_cod_su AND (estado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  estado='ANULADO')) $A $B $C $filtroCaja";
	}

	//echo "<li>$sql</li>";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{

		$resp[0]=$row['t'];
		$resp[1]=$row['c'];
	}

	//if(empty($resp[0]) || $resp[0]==0)$resp[0]=$sql;
	return $resp;

};
function tot_credito($fechaI,$fechaF,$codSuc,$cod_caja=-1)
{
	global $linkPDO;
	$filtroCerradas=" AND anulado='CERRADA'";
	$filtroNOanuladas="AND ( (".VALIDACION_VENTA_VALIDA." $filtroCerradas) OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) ";


	$filtroSEDE_nit="AND nit=$codSuc";
	if($codSuc=="all")
	{$filtroSEDE_nit=" ";}

	if($cod_caja!=-1)
	{
	$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  cod_caja='$cod_caja' $filtroSEDE_nit  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito' $filtroNOanuladas";
	}
	else
	{
		$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE   DATE(fecha)>='$fechaI' $filtroSEDE_nit AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito' $filtroNOanuladas";
	}

$rs=$linkPDO->query($sql);

$tot_Credito=0;
if($row=$rs->fetch())
{
	$tot_Credito=$row['TOT'];

}

return $tot_Credito;
};
function trunca_num($num)
{
	return (float)$num;
};
function trunc_text($text)
{
	$resp="";

	$len=strlen($text);
	$mid=$len/2;
	$mark=$mid;
	if($len>8){
	$B=mb_substr($text,$mark,$len);
	if(!empty($B) )$resp=mb_substr($text,0,$mark)."<br>".mb_substr($text,$mark,$len);
	else $resp=$text;
	return $resp;
	}
	else return $text;

};

function ajusta_texto_txt($text)
{
	$resp="";

	$len=strlen($text);
	//echo "<br>len $len, $text";
	if($len<20){


for($i=$len;$i<20;$i++){ $text.=" "; }

	return $text;
	}
	else return $text;

};
function ajusta_texto_html($text)
{
	$resp="";
	$text=mb_strimwidth($text, 0, 20, ".");
	$len=strlen($text);
	//echo "<br>len $len, $text";
	if($len<20){


for($i=$len;$i<20;$i++){ $text.="&nbsp;"; }

	return $text;
	}
	else return $text;

};
function tot_com($nit_pro,$num_fac,$SAVEPOINT,$calc_dcto)
{
global $linkPDO;

try {
$linkPDO->beginTransaction();
$all_query_ok=true;

if($calc_dcto=="valor"){$descuento="dcto";}
else {$descuento="costo*(dcto/100)";}

$descuento="costo*(dcto/100)";

$unidades="(unidades_fraccion+(cant*fraccion))/fraccion";

$SUB="ROUND(SUM(costo*($unidades) )) as SUB";
$DCTO="ROUND(SUM( ( $unidades  )*($descuento))) as DCTO";
$IVA="ROUND(SUM( ($unidades  )*(costo - ($descuento))*(iva/100))) as IVA";

$stot="(costo*( $unidades ))";
$dcto="(( $unidades  )*($descuento))";
$Ivaflete="flete*0.19";
$iva="( ($unidades  )*(costo - ($descuento))*(iva/100) )";


$TOT="ROUND(SUM(  $stot + $iva - $dcto) ) as TOT";


$sql="SELECT $SUB,$DCTO,$IVA,$TOT FROM `art_fac_com` WHERE nit_pro='$nit_pro' AND num_fac_com='$num_fac'";

$rsFacCom=$linkPDO->query($sql);
if($row=$rsFacCom->fetch())
{
$subCom=$row['SUB'];
$dctoCom=$row['DCTO'];
$ivaCom=$row['IVA'];
$totCom=$row['TOT'];
}
$sql="UPDATE fac_com SET subtot='$subCom',descuento='$dctoCom',iva=('$ivaCom' + flete*0.19),tot=('$totCom' + flete*1.19) WHERE nit_pro='$nit_pro' AND num_fac_com='$num_fac'";
$linkPDO->exec($sql);
//echo "UPDATE fac_com SET subtot='$subCom',descuento='$dctoCom',iva=('$ivaCom' + flete*0.16),tot=('$totCom' + flete*1.16) WHERE nit_pro='$nit_pro' AND num_fac_com='$num_fac'";


$linkPDO->commit();
}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

};
function cajeros_list()
{
	global $linkPDO;
	$sql="SELECT * FROM usuarios WHERE cliente=0 AND cod_caja!=0";
	$rs=$linkPDO->query($sql);
	$cajeros[]="";
	while($row=$rs->fetch())
	{
		$id=$row['cod_caja'];
		$cajeros[$id]=$row['nombre'];

	}
	return $cajeros;
};
function multiSelc($selected,$name,$id,$clase,$table,$where,$sel_id_col,$sel_des_col)
{
	global $linkPDO;
	$sql="SELECT *,($sel_des_col) a FROM $table $where";
	//echo "<br>$sql";
	$rs=$linkPDO->query($sql);
	$imp="";
	$color="";
	//<option value=\"-1\"  >TODOS</option>
	$selec="<select multiple=\"multiple\" name=\"$name\" id=\"$id\" class=\"$clase\">";
	while($row=$rs->fetch())
	{

		$selVal=$row[$sel_id_col];
		$selDes=$row["a"];
		if($selected==$selVal){$imp="selected";}
		else $imp="";
		$selec.="<option value=\"$selVal\" $imp ><span $color >$selDes</span></option>";
	}
	$selec.="</select>";

	return $selec;
};
function multiSelcFilter($requestName,$sessionName,$tableColName,$opc)
{
$var=array();
$E="";

//print_r( $_REQUEST[$requestName]."<br>");

//if(!isset($sessionVar))$_SESSION[$sessionName]=$var;
if(isset($_REQUEST[$requestName]) && !empty($_REQUEST[$requestName]))
{$var=array_filter($_REQUEST[$requestName]);$_SESSION[$sessionName]=array_filter($var);}

if(isset($_SESSION[$sessionName]) && !empty($_SESSION[$sessionName]))
{$var=array_filter($_SESSION[$sessionName]);}
//echo "s[$sessionName]:".$_SESSION[$sessionName];
//$_SESSION[$sessionName]=$var;

//print_r( $var);
$c=count($var);
$cc=0;
//$var2=array_filter($var);
if($var)
{
	//echo "<br> entra";
	$city=$_SESSION[$sessionName];
	//$_SESSION[$sessionName]=$var;
	$E=" AND (";
	foreach($city as $key=> $resultado)
	{
		//echo "$resultado<br>";
		$resultado=limpiarcampo($resultado);
		if($resultado==-1){$E="";break;}
		if(empty($resultado))$cc++;

		//if(!empty($resultado) && !($cc=($c-1)))
		$E.="$tableColName='$resultado' OR ";
		//else $E.=" ";


	}
	if($resultado!=-1)$E.=" $tableColName='$resultado') ";
	if($c==$cc)$E="";

	}

if($opc=="quitarFiltros")
{
$E="";
$_SESSION[$sessionName]="";
}
	//echo "e: $E";
	return $E;
};
function existFilter($requestName,$sessionName,$tableColName,$opc)
{
$var="";
$E="";
//if(!isset($sessionVar))$_SESSION[$sessionName]=$var;
if(isset($_REQUEST[$requestName]) && !empty($_REQUEST[$requestName]))
{$var=$_REQUEST[$requestName];$_SESSION[$sessionName]=$var;}

if(isset($_SESSION[$sessionName]) && !empty($_SESSION[$sessionName]))
{$var=$_SESSION[$sessionName];}

if(!empty($var))
{
if($var==1)$E="AND ((exist>0 OR unidades_frac>0) OR (exist<0 OR unidades_frac<0)) ";
else if($var==2)$E="AND (exist=0 AND unidades_frac=0)";
else if($var==3)$E="AND id_inter IN (SELECT cod_barras FROM `art_fac_ven` a INNER JOIN inv_inter b ON a.cod_barras=b.id_inter AND a.ref=b.id_pro WHERE a.fraccion!=b.fraccion GROUP BY a.cod_barras)";
else if($var==-1)$E="AND (exist<0 OR unidades_frac<0)";
}


if($opc=="quitarFiltros")
{
$E="";
$_SESSION[$sessionName]="";;
}

return $E;
};
function venciFilter($requestName,$sessionName,$opc)
{
global $fechaVenciMIN,$fechaVenciALERT;

$var="";
$E="";
//if(!isset($sessionVar))$_SESSION[$sessionName]=$var;
if(isset($_REQUEST[$requestName]) && !empty($_REQUEST[$requestName]))
{$var=$_REQUEST[$requestName];$_SESSION[$sessionName]=$var;}

if(isset($_SESSION[$sessionName]) && !empty($_SESSION[$sessionName]))
{$var=$_SESSION[$sessionName];}

if(!empty($var))
{
$E=" AND fecha_vencimiento<='$fechaVenciMIN' AND fecha_vencimiento!='0000-00-00' AND (exist>0 OR unidades_frac>0)";
}


if($opc=="quitarFiltros")
{
$E="";
$_SESSION[$sessionName]="";
}


return $E;
};
function val_vencidos()
{
global $fechaVenciMIN,$fechaVenciALERT;
global $linkPDO;
$r=0;
$rs=$linkPDO->query("select * from inv_inter WHERE fecha_vencimiento<='$fechaVenciMIN' AND fecha_vencimiento!='0000-00-00' AND (exist>0 OR unidades_frac>0)");
if($row=$rs->fetch())
{
	$r=1;
}
else $r=0;

return $r;
};
function desFilter($requestName,$sessionName,$tableColName,$opc)
{
$var="";
$E="";
//if(!isset($sessionVar))$_SESSION[$sessionName]=$var;
if(isset($_REQUEST[$requestName]) && !empty($_REQUEST[$requestName]))
{$var=$_REQUEST[$requestName];$_SESSION[$sessionName]=$var;}

if(isset($_SESSION[$sessionName]) && !empty($_SESSION[$sessionName]))
{$var=$_SESSION[$sessionName];}

if(!empty($var))
{
$E=" AND detalle LIKE '$var%'";
}


if($opc=="quitarFiltros")
{
$E="";
$_SESSION[$sessionName]="";;
}

return $E;
};
function utilFilter($requestName,$sessionName,$tableColName,$opc)
{
$var="";
$E="";
//if(!isset($sessionVar))$_SESSION[$sessionName]=$var;
if(isset($_REQUEST[$requestName]) && !empty($_REQUEST[$requestName]))
{$var=$_REQUEST[$requestName];$_SESSION[$sessionName]=$var;}

if(isset($_SESSION[$sessionName]) && !empty($_SESSION[$sessionName]))
{$var=$_SESSION[$sessionName];}

if(!empty($var))
{
if($var==1)$E=" ORDER BY gana DESC";
else if($var==2)$E=" ORDER BY gana ASC";

}


if($opc=="quitarFiltros")
{
$E="";
$_SESSION[$sessionName]="";;
}

return $E;
};


function comVenciFilter($requestName,$sessionName,$tableColName,$opc)
{
$var="";
$E="";
//if(!isset($sessionVar))$_SESSION[$sessionName]=$var;
if(isset($_REQUEST[$requestName]) && !empty($_REQUEST[$requestName]))
{$var=$_REQUEST[$requestName];$_SESSION[$sessionName]=$var;}

if(isset($_SESSION[$sessionName]) && !empty($_SESSION[$sessionName]))
{$var=$_SESSION[$sessionName];}

if(!empty($var))
{
$E=" AND DATEDIFF(DATE(feVen),CURRENT_DATE() )<$var";

}


if($opc=="quitarFiltros")
{
$E="";
$_SESSION[$sessionName]="";;
}

return $E;
};



function empty_array($array)
{

foreach( $array as $key => $value )
{
    if( empty( $value ) )
    {
       unset( $array[$key] );
    }
}
if( empty( $array ) )
{
   return true;
}
else return false;

};
function val_new_inv($id_pro,$cod_bar,$fechaVenci,$des,$codSuc)
{


	$sql="select * from ";

};
function refill_spc($str,$numSpc)
{
	$size=strlen($str);
	$resp=$str;
	while($size<$numSpc)
	{
		$resp=$resp." ";
		$size=strlen($resp);
	}
	return $resp."  ";
};
function tot_ventas_0516($fechaI,$fechaF,$codSuc,$horaI,$horaF,$CodCajero,$ventas_brutas=0,$RESOL=0,$PREFIJO="",$RANGO="")
{
global $MODULES;
global $linkPDO;

$filtroResol="";
$filtroCerradas=" AND anulado='CERRADA'";
$filtroNOanuladas="AND ( (".VALIDACION_VENTA_VALIDA." $filtroCerradas) OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) ";
if($ventas_brutas==1)$filtroNOanuladas=" AND ".VALIDACION_VENTA_VALIDA." ";
$filtroSEDE_art="AND art_fac_ven.nit=$codSuc";
$filtroSEDE_serv="AND b.cod_su=$codSuc";
$filtroSEDE_Fac="AND nit=$codSuc";

if($RESOL!=0)$filtroResol=" AND (fac_venta.resolucion='$RESOL' AND fac_venta.prefijo='$PREFIJO' AND fac_venta.rango_resol='$RANGO') ";

if($codSuc=="all")
{
$filtroSEDE_art="";
$filtroSEDE_serv="";
$filtroSEDE_Fac="";
}
$filtroHora="";
$filtroHoraAnula="";
if(!empty($horaI) && !empty($horaF))$filtroHora=" AND (fecha>='$fechaI $horaI' AND fecha<='$fechaF $horaF')";
if(!empty($horaI) && !empty($horaF))$filtroHoraAnula=" AND (fecha_anula>='$fechaI $horaI' AND fecha_anula<='$fechaF $horaF')";
$filtroCaja="";
if(!empty($CodCajero))$filtroCaja=" AND cod_caja=$CodCajero ";
$resp[][]=0;

//echo "filtro Resol $filtroResol AND (resolucion='$RESOL' AND prefijo='$PREFIJO' AND rango_resol='$RANGO')";

$sql="SELECT tipo_venta,
             cod_barras,
			 nom_cli,
			 art_fac_ven.num_fac_ven, 
			 precio,
			 des,
			 art_fac_ven.sub_tot,
			 art_fac_ven.iva,
			 cant,
			 unidades_fraccion,ref, 
			 TIME(fecha) as hora, 
			 DATE(fecha) as fe, 
			 tipo_venta,
			 tipo_cli,
			 vendedor,
			 fac_venta.prefijo,
			 art_fac_ven.prefijo,
			 cod_caja,
			 resolucion,
			 rango_resol 
	FROM fac_venta 
	INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven 
	WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit $filtroSEDE_art $filtroCaja    
	AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas $filtroResol";
//echo "$sql";
$rs=$linkPDO->query($sql );
$base5=0;
$iva5=0;
$tot5=0;

$base10=0;
$iva10=0;
$tot10=0;

$base16=0;
$iva16=0;
$tot16=0;

$base19=0;
$iva19=0;
$tot19=0;
$excentas=0;
$TOT=0;
$i=0;
$tot_tarjetaDeb=0;
$tot_contado=0;
$tot_Credito=0;
$tot_tarjetaCre=0;
$tot_cheque=0;
while($row=$rs->fetch())
{
$i++;
	$num_fac=$row['num_fac_ven'];
	$subTot=$row['sub_tot']*1;
	$IVA_art=$row['iva']*1;
	$des=$row['des'];
	$cant=$row['cant']*1;
	$uni=$row['unidades_fraccion']*1;
	$valor=$row['precio']*1;
	$ref=$row['cod_barras'];
	$vendedor=ucwords(strtolower($row["vendedor"]));
	$HORA=$row['hora'];
	$fecha=$row['fe'];
	$tipo_venta=$row['tipo_venta'];
	$tipoCli=$row['tipo_cli'];
	$nomCli=$row['nom_cli'];
	$formaPago=$row['tipo_venta'];
	if($formaPago=="Contado")$tot_contado+=$row['sub_tot'];
	else if($formaPago=="Credito")$tot_Credito+=$row['sub_tot'];
	else if($formaPago=="Tarjeta Debito")$tot_tarjetaDeb+=$row['sub_tot'];
	else if($formaPago=="Tarjeta Credito")$tot_tarjetaCre+=$row['sub_tot'];
	else if($formaPago=="Cheque")$tot_cheque+=$row['sub_tot'];
	if($IVA_art==0)$excentas+=$row['sub_tot']*1;

	if($IVA_art==19)
	{
		$base19+=round(($row['sub_tot']*1)/1.19);
		$iva19+=$row['sub_tot']*1-round(($row['sub_tot']*1)/1.19);
		$tot19=$row['sub_tot']*1;
		}

		if($IVA_art==16)
	{
		$base16+=round(($row['sub_tot']*1)/1.16);
		$iva16+=$row['sub_tot']*1-round(($row['sub_tot']*1)/1.16);
		$tot16=$row['sub_tot']*1;
		}

		if($IVA_art==10)
	{
		$base10+=round(($row['sub_tot']*1)/1.1);
		$iva10+=$row['sub_tot']*1-round(($row['sub_tot']*1)/1.1);
		$tot10=$row['sub_tot']*1;
		}


	if($IVA_art==5)
	{
		$base5+=round(($row['sub_tot']*1)/1.05);
		$iva5+=$row['sub_tot']*1-round(($row['sub_tot']*1)/1.05);
		$tot5=$row['sub_tot']*1;
		}
	$TOT+=$row['sub_tot']*1;
}
if($MODULES["SERVICIOS"]==1){
$sql="SELECT * FROM fac_venta  INNER JOIN serv_fac_ven b ON fac_venta.num_fac_ven=b.num_fac_ven WHERE fac_venta.prefijo=b.prefijo AND fac_venta.nit=b.cod_su $filtroSEDE_serv $filtroCaja    AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas $filtroResol";
$rs=$linkPDO->query($sql );
while($row=$rs->fetch()){
$num_fac=$row['num_fac_ven'];
	$subTot=$row['pvp']*1;
	$IVA_art=$row['iva']*1;
	$des=$row['serv'];
	$cant=1;
	$uni=0;
	$valor=$row['pvp']*1;
	$ref=$row['cod_serv'];
	$vendedor=ucwords(strtolower($row["vendedor"]));
	$tipo_venta=$row['tipo_venta'];
	$tipoCli=$row['tipo_cli'];
	$nomCli=$row['nom_cli'];
	$formaPago=$row['tipo_venta'];
	if($formaPago=="Contado")$tot_contado+=$row['sub_tot'];
	else if($formaPago=="Credito")$tot_Credito+=$row['sub_tot'];
	else if($formaPago=="Tarjeta Debito")$tot_tarjetaDeb+=$row['sub_tot'];
	else if($formaPago=="Tarjeta Credito")$tot_tarjetaCre+=$row['sub_tot'];
	else if($formaPago=="Cheque")$tot_cheque+=$row['sub_tot'];
	if($IVA_art==0)$excentas+=$row['pvp']*1;


	if($IVA_art==19)
	{
		$base19+=round(($row['pvp']*1)/1.19);
		$iva19+=$row['pvp']*1-round(($row['pvp']*1)/1.19);
		$tot19=$row['pvp']*1;
		}


		if($IVA_art==16)
	{
		$base16+=round(($row['pvp']*1)/1.16);
		$iva16+=$row['pvp']*1-round(($row['pvp']*1)/1.16);
		$tot16=$row['pvp']*1;
		}

	if($IVA_art==10)
	{
		$base10+=round(($row['pvp']*1)/1.1);
		$iva10+=$row['pvp']*1-round(($row['pvp']*1)/1.1);
		$tot10=$row['pvp']*1;
		}
	if($IVA_art==5)
	{
		$base5+=round(($row['pvp']*1)/1.05);
		$iva5+=$row['pvp']*1-round(($row['pvp']*1)/1.05);
		$tot5=$row['pvp']*1;
		}
	$TOT+=$row['pvp']*1;
}
}// FIN IF SERVICIOS





$TOT_DEV_VENTAS=tot_dev_ventas($fechaI,$fechaF,$codSuc,$horaI,$horaF,$CodCajero,1);

$sql="SELECT SUM(impuesto_bolsas) imp_bolsas, SUM(imp_consumo) imp_consumo FROM fac_venta WHERE     (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroSEDE_Fac $filtroCaja $filtroNOanuladas $filtroResol";
$rsz=$linkPDO->query($sql);
$rowz=$rsz->fetch();

$TOT_IMP_COMSUMO=$rowz["imp_consumo"];
$TOT_IMP_BOLSAS=$rowz["imp_bolsas"];


$sql="SELECT SUM(impuesto_bolsas) imp_bolsas, SUM(imp_consumo) imp_consumo FROM fac_venta WHERE  tipo_venta='Contado' $filtroSEDE_Fac $filtroCaja    AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas $filtroResol";
$rsz=$linkPDO->query($sql);
$rowz=$rsz->fetch();

$TOT_IMP_COMSUMOcont=$rowz["imp_consumo"];
$TOT_IMP_BOLSAScont=$rowz["imp_bolsas"];

if($fechaF<"2017-06-28" || $ventas_brutas==0){

$TOT_DEV_VENTAS[0][0]=0;
$TOT_DEV_VENTAS[5][1]=0;
$TOT_DEV_VENTAS[5][2]=0;
$TOT_DEV_VENTAS[5][3]=0;

$TOT_DEV_VENTAS[10][1]=0;
$TOT_DEV_VENTAS[10][2]=0;
$TOT_DEV_VENTAS[10][3]=0;

$TOT_DEV_VENTAS[16][1]=0;
$TOT_DEV_VENTAS[16][2]=0;
$TOT_DEV_VENTAS[16][3]=0;

$TOT_DEV_VENTAS[19][1]=0;
$TOT_DEV_VENTAS[19][2]=0;
$TOT_DEV_VENTAS[19][3]=0;

$TOT_DEV_VENTAS[1][1]=0;
}


$resp[0][0]=$excentas-$TOT_DEV_VENTAS[0][0];

$resp[5][1]=$base5-$TOT_DEV_VENTAS[5][1];
$resp[5][2]=$base5*0.05 -$TOT_DEV_VENTAS[5][2];
$resp[5][3]=$tot5 -$TOT_DEV_VENTAS[5][3];

$resp[10][1]=$base10 -$TOT_DEV_VENTAS[10][1];
$resp[10][2]=$base10*0.10 -$TOT_DEV_VENTAS[10][2];
$resp[10][3]=$tot10 -$TOT_DEV_VENTAS[10][3];

$resp[16][1]=$base16 -$TOT_DEV_VENTAS[16][1];
$resp[16][2]=$base16*0.16 -$TOT_DEV_VENTAS[16][2];
$resp[16][3]=$tot16 -$TOT_DEV_VENTAS[16][3];

$resp[19][1]=$base19 -$TOT_DEV_VENTAS[19][1];
$resp[19][2]=$base19*0.19 -$TOT_DEV_VENTAS[19][2];
$resp[19][3]=$tot19 -$TOT_DEV_VENTAS[19][3];


$resp[8][1]=$TOT_IMP_COMSUMO;
$resp[20][1]=$TOT_IMP_BOLSAS;
$resp[8][2]=$TOT_IMP_COMSUMOcont;
$resp[20][2]=$TOT_IMP_BOLSAScont;


$resp[1][1]=$TOT+$TOT_IMP_COMSUMO+$TOT_IMP_BOLSAS -$TOT_DEV_VENTAS[1][1];



$sql="SELECT SUM(impuesto_bolsas) imp_bolsas, SUM(imp_consumo) imp_consumo FROM fac_venta WHERE  tipo_venta='Contado' $filtroSEDE_Fac $filtroCaja    AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas $filtroResol";
$rsx=$linkPDO->query($sql);
$rowx=$rsx->fetch();

$TOT_IMP_COMSUMOcont=$rowx["imp_consumo"];
$TOT_IMP_BOLSAScont=$rowx["imp_bolsas"];

$resp["fp"][1]=$tot_contado+$TOT_IMP_COMSUMOcont;
$resp["fp"][2]=$tot_Credito;
$resp["fp"][11]=$tot_tarjetaCre;
$resp["fp"][12]=$tot_tarjetaDeb;
$resp["fp"][13]=$tot_cheque;
return $resp;
};

function resol()
{
	global $codSuc;
	global $linkPDO;
	$resp[][]="";
	$rs=$linkPDO->query("SELECT * FROM sucursal WHERE cod_su='$codSuc'");
	$row=$rs->fetch();
	$resp['POS'][0]=$row['cod_contado'];
	$resp['POS'][1]=$row['resol_contado'];
	$resp['POS'][2]=$row['fecha_resol_contado'];
	$resp['POS'][3]=$row['rango_contado'];

	$resp['COM'][0]=$row['cod_credito'];
	$resp['COM'][1]=$row['resol_credito'];
	$resp['COM'][2]=$row['fecha_resol_credito'];
	$resp['COM'][3]=$row['rango_credito'];

	$resp['PAPEL'][0]=$row['cod_papel'];
	$resp['PAPEL'][1]=$row['resol_papel'];
	$resp['PAPEL'][2]=$row['fecha_resol_papel'];
	$resp['PAPEL'][3]=$row['rango_papel'];

	$resp['CRE'][0]=$row['cod_credito_ant'];
	$resp['CRE'][1]=$row['resol_credito_ant'];
	$resp['CRE'][2]=$row['fecha_resol_credito_ant'];
	$resp['CRE'][3]=$row['rango_credito_ant'];

	$resp['REM_POS'][0]=$row['cod_remi_pos'];
	$resp['REM_POS'][1]=$row['resol_remi_pos'];
	$resp['REM_POS'][2]=$row['fecha_remi_pos'];
	$resp['REM_POS'][3]=$row['rango_remi_pos'];

	$resp['REM_COM'][0]=$row['cod_remi_com'];
	$resp['REM_COM'][1]=$row['resol_remi_com'];
	$resp['REM_COM'][2]=$row['fecha_remi_com'];
	$resp['REM_COM'][3]=$row['rango_remi_com'];

	$resp['REM_COM2'][0]=$row['cod_remi_com2'];
	$resp['REM_COM2'][1]=$row['resol_remi_com2'];
	$resp['REM_COM2'][2]=$row['fecha_remi_com2'];
	$resp['REM_COM2'][3]=$row['rango_remi_com2'];

	$resp[0][10]="SELECT * FROM sucursal WHERE cod_su='$codSuc'";

	return $resp;
};
function removeAccents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
    );

    $string = strtr($string, $chars);

    return $string;
}
function nomTrim($nom)
{
	global $CHAR_SET;
	//$nom=utf8_decode($nom);
	$nom=removeAccents($nom);
	$rta=ucwords(mb_strtolower(trim($nom),"$CHAR_SET"));
	return $rta;
};
function tasa_cambio()
{

};
function get_cuenta($idCuenta)
{
	global $linkPDO;
$resp[]="";
$sql_cuenta="SELECT * FROM bancos_cuentas WHERE id_cuenta=$idCuenta";
$rs_cuenta=$linkPDO->query($sql_cuenta);
if($row_cuenta=$rs_cuenta->fetch())
{
	$tipoCuenta=$row_cuenta['tipo_cuenta'];
	$numCuenta=$row_cuenta['num_cuenta'];
	$resp[0]=$tipoCuenta;
	$resp[1]=$numCuenta;
	return $resp;
}
else
{
return "INFO. NO DISPONIBLE";
}
};
function saldo_banco($idBanco,$idCuenta,$OP,$VALOR_TRANSACCION)
{
	global $conex;
	t1("UPDATE bancos_cuentas SET saldo_cuenta=(saldo_cuenta $OP $VALOR_TRANSACCION WHERE id_cuenta=$idCuenta) ");
};
function serial_arqueos($fechaI,$fechaF,$num=0)
{
	global $conex;
	global $linkPDO;
	$resp=0;
	if($num==0 || empty($num)){
	$sql="SELECT * FROM seriales_arqueos WHERE fechaI='$fechaI' AND fechaF='$fechaF'";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{
		$resp=$row['id'];
	}
	else{
		$linkPDO->exec("INSERT INTO seriales_arqueos(fechaI,fechaF) VALUES('$fechaI','$fechaF')");
		$sql="SELECT * FROM seriales_arqueos WHERE fechaI='$fechaI' AND fechaF='$fechaF'";
	    $rs=$linkPDO->query($sql);
		$row=$rs->fetch();
		$resp=$row['id'];
	}
	}// end IF num==0
	else{
	$s1="DELETE FROM seriales_arqueos WHERE id='$num' OR (fechaI='$fechaI' AND fechaF='$fechaF')";
	$s2="INSERT INTO seriales_arqueos(id,fechaI,fechaF) VALUES('$num','$fechaI','$fechaF')";
	t2($s1,$s2);
		$sql="SELECT * FROM seriales_arqueos WHERE fechaI='$fechaI' AND fechaF='$fechaF'";
	    $rs=$linkPDO->query($sql);
		$row=$rs->fetch();
		$resp=$row['id'];
	}
	return $resp;

};
function totFacVen2($num_fac,$pre,$codSuc)
{

	global $MODULES,$linkPDO;
	$factor="((unidades_fraccion/fraccion)+cant)";
	$pvpE="( precio/(1+(iva/100)) )";
	$sub=0;
	$ivaTOT=0;
	$tot=0;

	$serv_sub_tot="pvp/(1+(iva/100))";

	$sql="SELECT *  FROM `art_fac_ven` a  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc'";
	$stmt = $linkPDO->query($sql);
	//echo "----------FAC $pre $num_fac --------------<br>";
	while($row = $stmt->fetch()) {

	 $ref=$row["ref"];
	 $codBar=$row["cod_barras"];
	 $feVen=$row["fecha_vencimiento"];
	 

	 $pvp=$row["precio"];

	 $iva=$row["iva"];
	 $cant=$row["cant"];
	 $frac=$row["fraccion"];
	 $uni=$row["unidades_fraccion"];
	 $factor=$uni/$frac + $cant;

	 $pvpSin=$pvp/(1+($iva/100));

	 $SUB_tot_art=$pvpSin*$factor;
	 $IVA_art=$SUB_tot_art*($iva/100);

	 $tot2=$factor*$pvp;

	 $sub+=$SUB_tot_art;
	 $ivaTOT+=$IVA_art;
	 $tot+=$factor*$pvp;

	 $sqla="UPDATE art_fac_ven SET sub_tot='$tot2' WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc' AND (ref='$ref' AND cod_barras='$codBar' AND fecha_vencimiento='$feVen')";
	// echo "<li>$sqla</li>";
	 $linkPDO->exec($sqla);
	}
if($MODULES["SERVICIOS"]==1){
	$sql2="SELECT SUM($serv_sub_tot) AS sub, SUM(($serv_sub_tot)*(iva/100) ) AS iva  FROM `serv_fac_ven` a  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su='$codSuc'";
	$stmt = $linkPDO->query($sql2);
	if ($row2 = $stmt->fetch()) {
	 $sub+=$row2['sub'];
	 $ivaTOT+=$row2['iva'];
	 if($row2['sub']!=0)$tot+=$row2['sub']+$row2['iva'];
	}
}

	$sql="UPDATE fac_venta SET sub_tot='$sub', iva='$ivaTOT', tot='$tot'+imp_consumo+impuesto_bolsas-DESCUENTO_IVA WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc'";
	$linkPDO->exec($sql);

};
function totFacVen($num_fac,$pre,$codSuc)
{

	global $conex,$MODULES,$linkPDO,$usar_decimales_exactos;
	$factor="((unidades_fraccion/fraccion)+cant)";
	$pvpE="( precio/(1+(iva/100)) )";
	$sub=0;
	$ivaTOT=0;
	$tot=0;

	$serv_sub_tot="pvp/(1+(iva/100))";

	$sql="SELECT *  FROM `art_fac_ven` a  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc' FOR UPDATE";
	$stmt = $linkPDO->query($sql);
	while($row = $stmt->fetch()) {

	 $ref=$row["ref"];
	 $codBar=$row["cod_barras"];
	 $feVen=$row["fecha_vencimiento"];

	 $pvp=$row["precio"];

	 $iva=$row["iva"];
	 $cant=$row["cant"];
	 $frac=$row["fraccion"];
	 $uni=$row["unidades_fraccion"];
	 $factor=$uni/$frac + $cant;

	 $pvpSin=$pvp/(1+($iva/100));

	 $SUB_tot_art=$pvpSin*$factor;
	 $IVA_art=$SUB_tot_art*($iva/100);

	 $sub+=$SUB_tot_art;
	 $ivaTOT+=$IVA_art;
	 $tot+=$factor*$pvp;
	 $tot2=round($factor*$pvp);

	 $sqla="UPDATE art_fac_ven SET sub_tot='$tot2' WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc' AND (ref='$ref' AND cod_barras='$codBar' AND fecha_vencimiento='$feVen')";
	 $linkPDO->exec($sqla);
	}
if($MODULES["SERVICIOS"]==1){
	$sql2="SELECT SUM($serv_sub_tot) AS sub, SUM(($serv_sub_tot)*(iva/100) ) AS iva  FROM `serv_fac_ven` a  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su='$codSuc' FOR UPDATE";
	$stmt = $linkPDO->query($sql2);
	if ($row2 = $stmt->fetch()) {
	 $sub+=$row2['sub'];
	 $ivaTOT+=$row2['iva'];
	 if($row2['sub']!=0)$tot+=$row2['sub']+$row2['iva'];
	}
}

	 $sub=round($sub,2);
	 $ivaTOT=round($ivaTOT,2);
	 $tot=round($tot,2);

	$sql="UPDATE fac_venta SET sub_tot='$sub', iva='$ivaTOT', tot='$tot'+imp_consumo+impuesto_bolsas-DESCUENTO_IVA WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc'";
	$linkPDO->exec($sql);

};

function totFacRemi($num_fac,$pre,$codSuc)
{
	global $conex,$MODULES;
	global $linkPDO;
	$factor="((unidades_fraccion/fraccion)+cant)";
	$pvpE="( precio/(1+(iva/100)) )";
	$sub=0;
	$iva=0;
	$tot=0;
	//$sub_tot="$pvpE*$factor";
	$sub_tot="sub_tot/(1+(iva/100))";
	$serv_sub_tot="pvp/(1+(iva/100))";
	$sql="SELECT SUM($sub_tot) AS sub, SUM(($sub_tot)*(iva/100) ) AS iva  FROM `art_fac_remi` a  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc'";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{
	 $sub+=$row['sub'];
	 $iva+=$row['iva'];
	 $tot+=$sub+$iva;
	}
	if($MODULES["SERVICIOS"]==1){
	$sql2="SELECT SUM($serv_sub_tot) AS sub, SUM(($serv_sub_tot)*(iva/100) ) AS iva  FROM `serv_fac_remi` a  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su='$codSuc'";
	$rs2=$linkPDO->query($sql2);
	if($row2=$rs2->fetch())
	{
	 $sub+=$row2['sub'];
	 $iva+=$row2['iva'];
	 if($row2['sub']!=0)$tot+=$sub+$iva;
	}
	}
	t1("UPDATE fac_remi SET sub_tot='$sub', iva='$iva', tot='$tot' WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc'");
};
function sede($cod)
{
	global $linkPDO;
	$sql="select * from sucursal WHERE cod_su=$cod";
	$rs=$linkPDO->query($sql);
	$resp="";
	if($row=$rs->fetch())
	{
		$resp=$row['nombre_su'];
	}
	return $resp;
};
function chofer_sede($codSuc)
{
	global $linkPDO;
	$resp[]="";
	$sql="SELECT id_responsable,placa_vehiculo FROM sucursal WHERE cod_su='$codSuc'";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{
		$resp[0]=$row['id_responsable'];
		$resp[1]=$row['placa_vehiculo'];
	}
	return $resp;
};
function confirm_tras($sedeOrig,$sedeDest,$pre,$num_remi)
{

global $codSuc,$hoy,$conex,$igualar_precios_traslados,$linkPDO;

try {

ini_set('memory_limit', '1024M');
$linkPDO->beginTransaction();
$all_query_ok=true;


$sql="select cant,unidades_fraccion,ref,cod_barras,fecha_vencimiento FROM art_fac_remi a INNER JOIN fac_remi b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND a.num_fac_ven='$num_remi' AND a.prefijo='$pre' AND a.nit='$sedeOrig' AND b.sede_destino='$sedeDest' AND estado!='Recibido' AND anulado!='ANULADO' FOR UPDATE";

$rs=$linkPDO->query($sql);

$resp=0;
$i=0;
while($row=$rs->fetch())
{
	$i++;
	$linkPDO->exec("SAVEPOINT A".$i);
$resp=1;
$ref=$row['ref'];
$cod_bar=$row['cod_barras'];
$fechaVenci=$row['fecha_vencimiento'];
if(empty($fechaVenci)){$fechaVenci="0000-00-00";}
$cant=$row["cant"];
$uni=$row["unidades_fraccion"];

	$Sql="SELECT * FROM inv_inter WHERE id_pro='$ref' AND id_inter='$cod_bar' AND fecha_vencimiento='$fechaVenci' AND nit_scs='$sedeOrig' FOR UPDATE";
	//echo "$Sql";
	$Rs=$linkPDO->query($Sql);
	$Row=$Rs->fetch();


$pvp=$Row["precio_v"];
$costo=$Row["costo"];

$sql="INSERT IGNORE INTO inv_inter(id_pro,id_inter,max,min,fraccion,costo,precio_v,gana,tipo_dcto,iva,nit_scs,dcto2,color,talla,presentacion,fecha_vencimiento)
SELECT id_pro,id_inter,max,min,fraccion,costo,precio_v,gana,tipo_dcto,iva,(select cod_su from sucursal where cod_su=$sedeDest) as cu,dcto2,color,talla,presentacion,fecha_vencimiento FROM inv_inter WHERE nit_scs=$sedeOrig AND id_inter='$cod_bar' AND fecha_vencimiento='$fechaVenci' AND id_pro='$ref'";
$linkPDO->exec($sql);


$sql="UPDATE `inv_inter`  SET exist=(exist+$cant), unidades_frac=(unidades_frac+$uni) WHERE nit_scs='$codSuc' AND fecha_vencimiento='$fechaVenci' AND id_pro='$ref' AND id_inter='$cod_bar'";
$linkPDO->exec($sql);









if($pvp!=0 && $igualar_precios_traslados==1){
	$sql="UPDATE inv_inter SET precio_v='$pvp', costo='$costo' WHERE nit_scs=$sedeDest AND  (id_inter='$cod_bar' OR id_pro='$ref')";
	$linkPDO->exec($sql);	
	}

}

$linkPDO->exec("SAVEPOINT B");





$sql="UPDATE fac_remi SET estado='Recibido', fecha_recibe='$hoy' WHERE num_fac_ven='$num_remi' AND prefijo='$pre' AND nit='$sedeOrig' AND sede_destino='$sedeDest'";
$linkPDO->exec($sql);



if($all_query_ok){
$linkPDO->commit();
$rs=null;
$stmt=null;
$linkPDO= null;


}
else{echo "ERROR! Intente nuevamente";}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}
};




function calc_cols_coma($calc_dcto)
{
if($calc_dcto=="valor"){$descuento="a.dcto";}
else {$descuento="a.costo*(a.dcto/100)";}
$unidades="(a.unidades_fraccion+(a.cant*a.fraccion))/a.fraccion";
$SUB="ROUND(SUM(a.costo*($unidades) )) as SUB";
$DCTO="ROUND(SUM( ( $unidades  )*($descuento))) as DCTO";
$IVA="ROUND(SUM( ($unidades  )*(a.costo - ($descuento))*(a.iva/100))) as IVA";
$stot="(a.costo*( $unidades ))";
$dcto="(( $unidades  )*($descuento))";
$Ivaflete="b.flete*0.19";
$iva="( ($unidades  )*(a.costo - ($descuento))*(a.iva/100) )";
$TOT="ROUND(SUM(  $stot + $iva - $dcto) ) as TOT";
$TOT_FLETE="SUM(b.flete) as FLETE";
$cols="$SUB,$DCTO,$TOT_FLETE,$IVA,$TOT";
return $cols;
};









function limit_cre($idUsu)
{
	$resp=0;

	$RESP=tot_abon_cre($idUsu);
	$resp=$RESP["saldo"]*1;
	return $resp;
};
function saldo_tot_cre($idUsu,$fecha)
{
	global $linkPDO;
	$resp[]=0;
	$sql="select sum(tot) as tot,abon FROM (select tot,id_cli,num_fac_ven,fecha FROM fac_venta WHERE tipo_venta='Credito' AND id_cli='$idUsu' AND DATE(fecha)<='$fecha') a INNER JOIN (select sum(valor) as abon,num_fac FROM comprobante_ingreso WHERE DATE(fecha)<='$fecha' AND  num_fac IN(SELECT num_fac_ven FROM fac_venta WHERE id_cli='$idUsu' AND DATE(fecha)<='$fecha') ) b ";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){
		$resp[0]=$row["tot"];
		$resp[1]=$row["abon"];
		$resp[2]=$row["tot"]-$row["abon"];
	}
	return $resp;
	};
function new_sede($codSede)
{
	global $linkPDO;
try {
$linkPDO->beginTransaction();
$all_query_ok=true;


$sql="INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'ajustes',  '1',  '10000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'cartera_ant',  '1',  '10000',  '$codSede'
);
INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'comprobante anticipo',  '1',  '100000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'comprobante ingreso',  '1',  '10000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'comp_egreso',  '1',  '10000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'credito',  '754',  '1500',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'expedientes',  '1',  '100000',  '$codSede'
);
INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'factura compra',  '1',  '40000',  '$codSede'
);
INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'factura dev',  '1',  '10000000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'factura venta',  '1',  '50000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'Inventario Inicial',  '1',  '10000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'ref',  '1',  '10000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'remision',  '1',  '100000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'remision_com',  '1',  '100000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'resol_papel',  '1',  '1000',  '$codSede'
);
INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'traslado',  '1',  '100000',  '$codSede'
);
";
$linkPDO->exec($sql);
$linkPDO->commit();

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

};
function anula_fac_ven($num_fac,$pre,$codSuc)
{
global $nomUsu,$id_Usu,$FLUJO_INV,$conex,$SECCIONES,$OPERACIONES,$fecha_lim_anulaVenta,$conex,$linkPDO;
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));

try {

$linkPDO->beginTransaction();
$all_query_ok=true;

$qry="";
$sql="SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc' FOR UPDATE";
//echo $sql."<br>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$sql="SELECT *, (tot-r_fte-r_ica-r_iva) as total_fac FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND ".VALIDACION_VENTA_VALIDA." AND anulado='CERRADA' AND nit=$codSuc FOR UPDATE";
            //echo $sql."<br>";
            $rs=$linkPDO->query($sql);
			if($row= $rs->fetch()){
			$karDex=$row['kardex'];
			$tot=$row["total_fac"]-$row["abono_anti"];
			$form_p=$row["tipo_venta"];
			$idCta=$row["id_cuenta"];
			up_cta($form_p,$idCta,$tot,"-","ANULA Venta $pre-$num_fac","Factura Venta",$hoy);
			$sql="SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$codSuc $fecha_lim_anulaVenta FOR UPDATE";
            //echo $sql."<br>";
            $rs= $linkPDO->query($sql);
			if($row=$rs->fetch()){
				$sql="SELECT * FROM cartera_mult_pago WHERE num_fac='$num_fac' AND pre='$pre' AND estado!='ANULADO' AND cod_su='$codSuc' FOR UPDATE";
				$rs=$linkPDO->query($sql);
				$row=$rs->fetch();
			if( !$row){


			//$NUM_EXP=$row['num_exp'];
			$sql="UPDATE fac_venta SET anulado='ANULADO', fecha_anula='$hoy', usu='$nomUsu',id_usu='$id_Usu',modifica='$nomUsu ANULA FAC. $pre-$num_fac' WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$codSuc";
			$linkPDO->exec($sql);

			//echo $qry."<br>";
		 $linkPDO->exec("SAVEPOINT A");
if($FLUJO_INV==1 && $karDex==1){

$sql="SELECT * FROM art_fac_ven WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$codSuc FOR UPDATE";
$rs=$linkPDO->query($sql);
$i=0;
while($row=$rs->fetch()){

		$i++;
		$linkPDO->exec("SAVEPOINT loop".$i);

		$ref=$row["ref"];
		$cod_bar=$row["cod_barras"];



		$cant=$row["cant"];
		$uni=$row["unidades_fraccion"];
		$frac=$row["fraccion"];
		if($frac==0)$frac=1;
		$feVenci=$row["fecha_vencimiento"];
		if(empty($feVenci)){$feVenci="0000-00-00";}
		$sql="UPDATE `inv_inter`  SET exist=(exist+$cant), unidades_frac=(unidades_frac+$uni) WHERE nit_scs='$codSuc' AND fecha_vencimiento='$feVenci' AND id_pro='$ref' AND id_inter='$cod_bar'";
		$linkPDO->exec($sql);






}


}
else{

}
	$HTML_antes="";
	$HTML_despues="<div style='font-size:24px;'>FACTURA <span style='color:red'>No. $num_fac - $pre</span> <b>ANULADA</b></div>";
	logDB($sql."<br>",$SECCIONES[5],$OPERACIONES[2],$HTML_antes,$HTML_despues,$num_fac);
			}
			else {echo "-445";}

			}// fin if cartera
			else {echo "-1";}

			}
			else {echo "0";}

}
else {echo "-2";}


if($all_query_ok){
$linkPDO->commit();
$rs=null;
$stmt=null;
$linkPDO= null;

}
else{echo "ERROR! Intente nuevamente";}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

};

function tot_cre_car($fechaI,$fechaF,$codSuc,$filtroHora,$filtroNOanuladas)
{
	global $linkPDO;
	$sql="SELECT SUM(tot_cre) as tc FROM fac_venta WHERE nit='$codSuc' AND tot_cre!=0 AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas";
	$rs=$linkPDO->query($sql);

	if($row=$rs->fetch())
	{
	return 	$row["tc"];
	}
	else return 0;
};
function selc_serv($funct)
{
	global $codSuc;
	global $linkPDO;
	$selc="<select style=\"width: 200px;\" name=\"servicios\" id=\"servicios\" onchange=\"$funct\" data-placeholder=\"Escriba un C&oacute;digo Servicio\" class=\"chosen-selectS\"><option value=\"\"  ></option>";
	$sql="SELECT * FROM servicios WHERE cod_su=$codSuc ORDER BY id_serv DESC";
	$rs=$linkPDO->query($sql);
	while($row=$rs->fetch()){
		$ID=$row["id_serv"];
		$serv=$row["servicio"];
		$COD=$row["cod_serv"];
		$iva=$row["iva"];
		$pvp=money($row["pvp"]);
		$VALS="$ID|$COD|$serv|$iva|$pvp";
		$val="$COD|$serv|$iva|$pvp";
		$selc.="<option value=\"$VALS\"  >$COD-$serv: $pvp</option>";
	}
	$selc.="</select>";
	return $selc;
};
function selc_form_pa($name,$ID,$funct,$opc,$selection="")
{
	global $codSuc;
	$class="uk-form-small uk-form-width-medium";
	//chosen-select form-control
	$selc="<select name=\"$name\" id=\"$ID\" onchange=\"$funct\" data-placeholder=\"Escriba algo..\" class=\" $class\">";
	$limit=count($opc);
	for($i=0;$i<$limit;$i++){
		$VALS=$opc[$i];
		$OPTselected="";
		if($selection==$VALS){$OPTselected="selected";}else{$OPTselected="";}

		$selc.="<option value=\"$VALS\"  $OPTselected>$VALS</option>";
	}
	$selc.="</select>";


	return $selc;
};

function vehi($placa)
{
	global $linkPDO;
	$resp[]="";
	$resp["color"]="";
	$resp["modelo"]="";
	$resp["cc"]="";
	$resp["id_pro"]="";
	$resp["km"]="";
	$resp["nom_pro"]="";
	$resp["tel_pro"]="";
	$sql="SELECT * FROM  vehiculo WHERE placa='$placa'";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{
		$resp["color"]=$row["color"];
		$resp["placa"]=$row["placa"];
		$resp["modelo"]=$row["modelo"];
		$resp["cc"]=$row["cc"];
		$resp["id_pro"]=$row["id_propietario"];
		$resp["km"]=$row["km"];
		$sql="SELECT * FROM  usuarios WHERE id_usu='$resp[id_pro]'";
		$rs=$linkPDO->query($sql);
		if($row=$rs->fetch())
		{
			$resp["nom_pro"]=$row["nombre"];
			$resp["tel_pro"]=$row["tel"];
		}
	}
	return $resp;
};

/*--------------------------------------------------------------------------------------------------------------------------------------------------------------*/
/* -------------------------------------------------------------CARTERA---------------------------------------------------------------------------------------- */
/*--------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function resta_dev_venta($idCli)
{
	global $MODULES;
	global $linkPDO;
	$resp=0;
	if($MODULES["cartera_restar_devoluciones"]==1){
	$sql="SELECT SUM(tot) as t FROM fac_dev_venta WHERE id_cli='$idCli' AND anulado!='ANULADO' AND tipo_venta='Credito'";
	$rs=$linkPDO->query($sql);

	if($row=$rs->fetch()){
		$resp=$row["t"];
	}else{

	}

	}
	else{$resp=0;}

	return $resp;

};
function resta_dev_venta2($idCli,$fecha)
{
	global $linkPDO;
	$OPCfecha=" AND (DATE(fecha)<=DATE('$fecha') )";
	$resp=0;
	$sql="SELECT SUM(tot) as t FROM fac_dev_venta WHERE id_cli='$idCli' AND anulado!='ANULADO' $OPCfecha";
	$rs=$linkPDO->query($sql);

	if($row=$rs->fetch()){
		$resp=$row["t"];
	}else{

	}

	return $resp;

};

function tot_abon_cre($idCli)
{
global $codSuc,$MODULES,$linkPDO;

$dev_ventas=resta_dev_venta($idCli);

$RESP[0]="";
$RESP["abono"]=0;
$RESP["tot"]=0;
$RESP["saldo"]=0;

//$sql="SELECT IFNULL(SUM(valor),0) AS t FROM comprobante_ingreso WHERE id_cli='$idCli' AND anulado!='ANULADO'";

$FAC_COD_SU="AND a.cod_su=b.nit AND b.nit='$codSuc'";$FAC_COD_SU2="AND nit='$codSuc'";
if($MODULES["MULTISEDES_UNIFICADAS"]){$FAC_COD_SU="";$FAC_COD_SU2="";}

$sql="SELECT IFNULL(SUM(abono),0) AS t FROM cartera_mult_pago a INNER JOIN fac_venta b ON a.num_fac=b.num_fac_ven AND a.pre=b.prefijo AND a.cod_su=b.nit WHERE   a.id_cli='$idCli' AND a.estado!='ANULADO' AND b.anulado!='ANULADO'   $FAC_COD_SU";
//echo $sql;
$rs=$linkPDO->query($sql);

if($rr=$rs->fetch()) {
	$abon=$rr['t'];
	$RESP["abono"]=$abon;
}


$sql="SELECT b.tot FROM fac_venta a
INNER JOIN
(SELECT prefijo,num_fac,SUM(valor) tot,a.cod_su FROM comp_anti a INNER JOIN exp_anticipo b ON a.num_exp=b.num_exp AND a.cod_su=b.cod_su AND  a.cod_su='$codSuc' AND a.estado='COBRADO' AND b.id_cli='$idCli' GROUP BY prefijo,num_fac) b
ON a.num_fac_ven=b.num_fac AND a.prefijo=b.prefijo AND a.nit=b.cod_su  AND anulado!='ANULADO' AND tipo_venta='Credito' AND num_exp!=0   ";
//echo $sql;
$rs=$linkPDO->query($sql);
if($rr=$rs->fetch()){
	$abon=$rr['tot'];
	$RESP["abono"]+=$abon;
}



$qry="SELECT SUM((tot-r_fte-r_ica-r_iva)) AS T FROM fac_venta WHERE  id_cli='$idCli' AND tipo_venta='Credito' AND ".VALIDACION_VENTA_VALIDA." $FAC_COD_SU2";
//echo $qry;
$rs=$linkPDO->query($qry);$saldo=0;
if($row=$rs->fetch())
{
$tot=$row['T']-$dev_ventas;$RESP["tot"]=$tot;
$saldo=$tot-$RESP["abono"];$RESP["saldo"]=$saldo;
//echo "--->[S: $saldo,T: $tot Abo: $abon ]<br>";
}
else {$RESP[2020]=0;}
return $RESP;
};

function tot_saldo2($idCli,$fecha,$numComp=0)
{


$dev_ventas=resta_dev_venta2($idCli,$fecha);


global $codSuc,$MODULES;
global $linkPDO;
$RESP[0]="";
$RESP["abono"]=0;
$RESP["tot"]=0;
$RESP["saldo"]=0;
//$OPCfecha=" AND (fecha<='$fecha')";
$OPCfecha=" AND (DATE(fecha) < DATE('$fecha') )";
$COD_SU_F="AND nit='$codSuc'";
$COD_SU_C=" AND cod_su='$codSuc'";
$COD_SU_C2=" AND a.cod_su='$codSuc'";
if($MODULES["MULTISEDES_UNIFICADAS"]){$COD_SU_F="";
$COD_SU_C=" ";
$COD_SU_C2="";}
$filtroLastOne="";
$numComp=0;// OJO ACA, ESTO NO SIRVE CUANDO LOS PAGOS SE HACEN  TARDE
if($numComp!=0){$filtroLastOne=" AND num_com<=$numComp";}

$TOT_RETE[]=0;
$sql="SELECT IFNULL(SUM(r_fte+r_ica),0) AS t,id_cli,cod_su FROM comprobante_ingreso WHERE id_cli='$idCli' AND anulado!='ANULADO' $filtroLastOne $COD_SU_C  $OPCfecha";
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);
$TOT_RETE[$idCli]=0;
while($row=$rs->fetch())
{$TOT_RETE[$row['id_cli']]=$row['t']*1;
	//echo "<li>".$row['t']."-".$row['id_cli']."</li>";
}

$filtroNumComp=" AND num_comp<=$numComp";
if($numComp==0){$filtroNumComp="";}

//$sql="SELECT IFNULL(SUM(valor),0) AS t FROM comprobante_ingreso WHERE id_cli='$idCli' AND anulado!='ANULADO' $filtroLastOne $COD_SU_C  $OPCfecha";
//$sql="SELECT IFNULL(SUM(abono),0) AS t FROM cartera_mult_pago a INNER JOIN fac_venta b ON a.num_fac=b.num_fac_ven AND a.pre=b.prefijo AND a.cod_su=b.nit WHERE  a.id_cli='$idCli' AND a.estado!='ANULADO' AND b.anulado!='ANULADO'  $COD_SU_F AND num_comp<=$numComp  $OPCfecha";



$sql="SELECT IFNULL(SUM(abono),0) AS t FROM cartera_mult_pago a INNER JOIN comprobante_ingreso b ON a.num_comp=b.num_com AND a.cod_su=b.cod_su WHERE  a.id_cli='$idCli' AND a.estado!='ANULADO' AND b.anulado!='ANULADO'  $COD_SU_C2  $filtroNumComp  $OPCfecha";
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);
if($rr=$rs->fetch()) {
	$abon=$rr['t'];
	$RESP["abono"]=$abon-$TOT_RETE[$idCli];
}


$sql="SELECT b.prefijo,b.num_fac,b.tot FROM fac_venta a
INNER JOIN
(SELECT prefijo,num_fac,SUM(valor) tot,a.cod_su FROM comp_anti a INNER JOIN exp_anticipo b ON a.num_exp=b.num_exp AND a.cod_su=b.cod_su AND  a.cod_su='$codSuc' AND a.estado='COBRADO' AND b.id_cli='$idCli' GROUP BY prefijo,num_fac) b
ON a.num_fac_ven=b.num_fac AND a.prefijo=b.prefijo AND a.nit=b.cod_su  AND anulado!='ANULADO' AND tipo_venta='Credito' AND num_exp!=0   $OPCfecha";
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);

if($rr=$rs->fetch()) {
	$abon=$rr['tot'];
	$RESP["abono"]+=$abon;
}


$qry="SELECT SUM(tot) AS T FROM fac_venta WHERE id_cli='$idCli' AND tipo_venta='Credito'  AND ".VALIDACION_VENTA_VALIDA." $COD_SU_F $OPCfecha";
//echo "<li>$qry</li>";
$rs=$linkPDO->query($qry);
$saldo=0;
if($row=$rs->fetch())
{
$tot=$row['T']*1-$dev_ventas-$TOT_RETE[$idCli];
$RESP["tot"]=$tot ;
$saldo=intval($tot-$RESP["abono"]);
$RESP["saldo"]=$saldo;
//echo "---> $saldo, $tot --$row[T] ($dev_ventas-$TOT_RETE[$idCli]) , abon:".$RESP["abono"];
}
else {$RESP[2020]=0;}


return $RESP;


}


function tot_saldo($idCli,$fecha,$numComp=0)
{

$dev_ventas=resta_dev_venta2($idCli,$fecha);


global $codSuc,$MODULES;
global $linkPDO;
$RESP[0]="";
$RESP["abono"]=0;
$RESP["tot"]=0;
$RESP["saldo"]=0;
//$OPCfecha=" AND (fecha<='$fecha')";
$OPCfecha=" AND (DATE(fecha)<=DATE('$fecha') )";
$COD_SU_F="AND nit='$codSuc'";
$COD_SU_C=" AND cod_su='$codSuc'";
$COD_SU_C2=" AND a.cod_su='$codSuc'";
if($MODULES["MULTISEDES_UNIFICADAS"]){$COD_SU_F="";
$COD_SU_C=" ";
$COD_SU_C2="";}
$filtroLastOne="";
if($numComp!=0){$filtroLastOne=" AND num_com<=$numComp";}


// SUM RETENCIONES
$TOT_RETE[]=0;
$sql="SELECT IFNULL(SUM(r_fte+r_ica),0) AS t,id_cli,cod_su FROM comprobante_ingreso WHERE id_cli='$idCli' AND anulado!='ANULADO' $filtroLastOne $COD_SU_C  $OPCfecha";
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);
$TOT_RETE[$idCli]=0;
while($row=$rs->fetch())
{$TOT_RETE[$row['id_cli']]=$row['t']*1;
	//echo "<li>".$row['t']."-".$row['id_cli']."</li>";
}

$filtroNumComp=" AND num_comp<=$numComp";
if($numComp==0){$filtroNumComp="";}

//$sql="SELECT IFNULL(SUM(valor),0) AS t FROM comprobante_ingreso WHERE id_cli='$idCli' AND anulado!='ANULADO' $filtroLastOne $COD_SU_C  $OPCfecha";
//$sql="SELECT IFNULL(SUM(abono),0) AS t FROM cartera_mult_pago a INNER JOIN fac_venta b ON a.num_fac=b.num_fac_ven AND a.pre=b.prefijo AND a.cod_su=b.nit WHERE  a.id_cli='$idCli' AND a.estado!='ANULADO' AND b.anulado!='ANULADO'  $COD_SU_F AND num_comp<=$numComp  $OPCfecha";



$sql="SELECT IFNULL(SUM(abono),0) AS t 
	  FROM cartera_mult_pago a 
	  INNER JOIN comprobante_ingreso b 
	  ON a.num_comp=b.num_com AND a.cod_su=b.cod_su 
	  WHERE  a.id_cli='$idCli' AND a.estado!='ANULADO' AND b.anulado!='ANULADO'  
	  AND a.num_fac IN (SELECT num_fac_ven FROM fac_venta WHERE 1  $COD_SU_F )
	  $COD_SU_C2  $filtroNumComp  $OPCfecha";
//echo "$sql";
$rs=$linkPDO->query($sql);
$rr=$rs->fetch();
$abon=$rr['t'];
$RESP["abono"]=$abon-$TOT_RETE[$idCli];

$sql="SELECT b.prefijo,b.num_fac,b.tot 
	  FROM fac_venta a
	  INNER JOIN
	  (SELECT prefijo,num_fac,SUM(valor) tot,a.cod_su 
	  	FROM comp_anti a 
		INNER JOIN exp_anticipo b 
		ON a.num_exp=b.num_exp AND a.cod_su=b.cod_su AND  a.cod_su='$codSuc' AND a.estado='COBRADO' AND b.id_cli='$idCli' 
		GROUP BY prefijo,num_fac
	   ) b
	   ON a.num_fac_ven=b.num_fac AND a.prefijo=b.prefijo AND a.nit=b.cod_su  AND ".VALIDACION_VENTA_VALIDA." AND tipo_venta='Credito' AND num_exp!=0   $OPCfecha";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($rr=$rs->fetch()) {
	$abon=$rr['tot'];
	$RESP["abono"]+=$abon;
}


$qry="SELECT SUM(tot) AS T FROM fac_venta WHERE id_cli='$idCli' AND tipo_venta='Credito'  AND ".VALIDACION_VENTA_VALIDA." $COD_SU_F $OPCfecha";
//echo $qry;
$rs=$linkPDO->query($qry);
$saldo=0;
if($row=$rs->fetch())
{
$tot=$row['T']*1-$dev_ventas-$TOT_RETE[$idCli];
$RESP["tot"]=$tot ;
$saldo=intval($tot-$RESP["abono"]);
$RESP["saldo"]=$saldo;
//echo "---> $saldo, $tot --$row[T] ($dev_ventas-$TOT_RETE[$idCli]) , abon:".$RESP["abono"];
}
else {$RESP[2020]=0;}


return $RESP;
};
function chk_abono($num_comp,$num_fac,$pre,$idCli,$abono,$tot_fac,$all_query_ok,$fecha)
{
global $codSuc,$MODULES,$usar_remision,$linkPDO;

$FILTRO_SEDES_FAC="AND nit=$codSuc";
$FILTRO_SEDES_COD_SU="AND cod_su=$codSuc";
$Cliente = new Clientes();

$conceptoFac="Factura";
if($usar_remision==1){$conceptoFac="Remision";}

if($MODULES["MULTISEDES_UNIFICADAS"]==1){$FILTRO_SEDES_FAC="";$FILTRO_SEDES_COD_SU="";}

	$concepto="";
	$T=$tot_fac;$Ab=0;$S=0;
	$resp=$abono;
	//$sql="SELECT SUM(abono) as abono FROM cartera_mult_pago WHERE num_fac='$num_fac' AND pre='$pre' AND id_cli='$idCli' AND estado!='ANULADO;
	$sql="SELECT IFNULL(SUM(abono),0) AS t FROM cartera_mult_pago a INNER JOIN fac_venta b ON a.num_fac=b.num_fac_ven  AND a.pre=b.prefijo AND (a.num_fac='$num_fac' AND a.pre='$pre') AND a.id_cli='$idCli' AND a.estado!='ANULADO' AND b.anulado!='ANULADO' AND a.cod_su=b.nit  $FILTRO_SEDES_FAC FOR UPDATE";
	//echo "<li>$sql</li>";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{$Ab=$row["t"];}
	$S=$T-$Ab;
	//echo "<li>S($S)=T($T)-AB($Ab) ABONO: $abono</li>";
		if($S>0){
			//echo "<br>S>0";
			if($abono>$S){
				$resp=$abono-$S;
				//echo "R:$resp = Ab:$abono- S:$S";
			$sql="INSERT INTO cartera_mult_pago(num_comp,num_fac,pre,id_cli,abono,cod_su) VALUES('$num_comp','$num_fac','$pre','$idCli','$S','$codSuc')";
			$linkPDO->exec($sql);


			$sql="UPDATE fac_venta SET estado='PAGADO',fecha_pago='$fecha',estado_pago=1 WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit='$codSuc'";
			$linkPDO->exec($sql);
			$Cliente->setActualizaPagoPlan($num_fac,$pre,$codSuc,"pagar");
			$concepto.=" ,$conceptoFac $pre-$num_fac PAGADA";
			}
			else {

				$sql="INSERT INTO cartera_mult_pago(num_comp,num_fac,pre,id_cli,abono,cod_su) VALUES('$num_comp','$num_fac','$pre','$idCli','$abono','$codSuc')";
				$linkPDO->exec($sql);


			if($abono==$S){
				$sql="UPDATE fac_venta SET estado='PAGADO',fecha_pago='$fecha',estado_pago=1 WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit='$codSuc'";
				$linkPDO->exec($sql);
				$Cliente->setActualizaPagoPlan($num_fac,$pre,$codSuc,"pagar");


				}
			$concepto.=" ,ABONO ".money3($abono)." $conceptoFac $pre-$num_fac";
			$resp=0;
			}
			}
if(!empty($concepto)){

$sql="UPDATE comprobante_ingreso SET concepto=CONCAT(concepto,' $concepto') WHERE num_com='$num_comp' AND cod_su='$codSuc'";
$linkPDO->exec($sql);

}
//echo "<li>Abono: $abono R:$resp, $concepto</li>";
$respuesta[0]=$resp;
$respuesta[1]=$all_query_ok;
return $respuesta;

};
/* ******************* *** ******* *********** *********  FIN CARTERA ******* ********* ****************** ******************** ************* *************  */
function show_proveedor($nit)
{
	global $linkPDO;
	$resp="";
	$sql="SELECT nom_pro FROM provedores WHERE nit='$nit'";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{
		$resp=$row["nom_pro"];
	}
	else $resp="TODOS";
	return $resp;
};
function serv_0_05_16($num_fac,$pre)
{
	global $codSuc;
	global $linkPDO;

	$RESP[0]=0;
	$RESP[5]=0;
	$RESP[10]=0;
	$RESP[16]=0;
	$RESP[19]=0;

	$excento_serv="(SELECT SUM(pvp) FROM serv_fac_ven WHERE iva=0 AND num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc)";

	$sub16_serv="(SELECT SUM(pvp/1.16) FROM serv_fac_ven WHERE iva=16 AND num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc)";

	$sub19_serv="(SELECT SUM(pvp/1.19) FROM serv_fac_ven WHERE iva=19 AND num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc)";

	$sub10_serv="(SELECT SUM(pvp/1.10) FROM serv_fac_ven WHERE iva=10 AND num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc)";

	$sub05_serv="(select SUM(pvp/1.05) from serv_fac_ven WHERE iva=5 AND num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc)";

	$sql="SELECT $excento_serv as ex, $sub05_serv as sub5, $sub16_serv as sub16, $sub19_serv as sub19, $sub10_serv as sub10 FROM serv_fac_ven WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc";
	//echo "<li>$sql</li>";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){

	$RESP[0]=$row["ex"];
	$RESP[5]=$row["sub5"];
	$RESP[16]=$row["sub16"];
	$RESP[19]=$row["sub19"];
	$RESP[10]=$row["sub10"];

	}
	return $RESP;
};
function serv_0_05_16PlanesInter($num_fac,$pre)
{
	global $codSuc;
	global $linkPDO;

	$RESP[0]=0;
	$RESP[-1]=0;
	$RESP[5]=0;
	$RESP[10]=0;
	$RESP[16]=0;
	$RESP[19]=0;

	$excento_serv="(SELECT SUM(pvp) FROM serv_fac_ven WHERE iva=0 AND num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc AND (estrato=1 OR estrato=2))";

	$excluido_serv="(SELECT SUM(pvp) FROM serv_fac_ven WHERE iva=0 AND num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc AND (estrato=3))";

	$sub16_serv="(SELECT SUM(pvp/1.16) FROM serv_fac_ven WHERE iva=16 AND num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc)";

	$sub19_serv="(SELECT SUM(pvp/1.19) FROM serv_fac_ven WHERE iva=19 AND num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc)";

	$sub10_serv="(SELECT SUM(pvp/1.10) FROM serv_fac_ven WHERE iva=10 AND num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc)";

	$sub05_serv="(select SUM(pvp/1.05) from serv_fac_ven WHERE iva=5 AND num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc)";

	$sql="SELECT $excluido_serv as excluido, $excento_serv as ex, $sub05_serv as sub5, $sub16_serv as sub16, $sub19_serv as sub19, $sub10_serv as sub10 FROM serv_fac_ven WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc";
	//echo "<li>$sql</li>";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){

	$RESP[0]=$row["ex"];
	$RESP[-1]=$row["excluido"];
	$RESP[5]=$row["sub5"];
	$RESP[16]=$row["sub16"];
	$RESP[19]=$row["sub19"];
	$RESP[10]=$row["sub10"];

	}
	return $RESP;
};

function getTec($idTec)
{
	global $linkPDO;
	$rs=$linkPDO->query("SELECT a.id_usu,a.nombre FROM usuarios a INNER JOIN tipo_usu b ON a.id_usu=b.id_usu WHERE des='Tecnico' OR des='Mecanico' AND a.id_usu='$idTec'");
	$resp="";
	if($row=$rs->fetch())
	{
		$resp=$row["nombre"];
	}
	return $resp;
};
function auto_ban_cli()
{
	global $DIAS_BAN_CLI;
	global $linkPDO;
	$sql="UPDATE usuarios a INNER JOIN (SELECT num_fac_ven,prefijo,nom_cli,id_cli,fecha,tot,DATEDIFF(CURRENT_DATE(),DATE(fecha) ) AS mora,DATEDIFF(DATE(fecha_pago),DATE(fecha) ) AS mora2 FROM fac_venta WHERE  ".VALIDACION_VENTA_VALIDA." AND tipo_venta='Credito' AND estado!='PAGADO' AND DATEDIFF(CURRENT_DATE(),DATE(fecha) )>$DIAS_BAN_CLI AND fecha_pago='0000-00-00 00:00:00' GROUP BY id_cli) b ON a.id_usu=b.id_cli SET a.auth_credito=0 ";
	$linkPDO->exec($sql);


};

function auto_unban_cli2()
{
	global $DIAS_BAN_CLI,$linkPDO;
	$sql="UPDATE usuarios a INNER JOIN (SELECT num_fac_ven,prefijo,nom_cli,id_cli,fecha,tot,DATEDIFF(CURRENT_DATE(),DATE(fecha) ) AS mora,DATEDIFF(DATE(fecha_pago),DATE(fecha) ) AS mora2 FROM fac_venta WHERE  ".VALIDACION_VENTA_VALIDA." AND tipo_venta='Credito' AND estado!='PAGADO' AND DATEDIFF(CURRENT_DATE(),DATE(fecha) )<$DIAS_BAN_CLI AND fecha_pago='0000-00-00 00:00:00' GROUP BY id_cli) b ON a.id_usu=b.id_cli SET a.auth_credito=1 ";
	$linkPDO->exec($sql);

};

function auto_unban_cli($idCli)
{
	global $DIAS_BAN_CLI,$linkPDO;

	try {


	$saldo=tot_abon_cre($idCli);

	if($saldo["saldo"]==0){
	$sql="UPDATE usuarios   SET auth_credito=1 WHERE  id_usu='$idCli'";
	$linkPDO->exec($sql);
	}

	}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}


};
function tot_nomina_tecnicos($fechaI,$fechaF,$codSuc)
{
	global $linkPDO;
	$sql="SELECT a.id_tec,SUM(a.pvp) as T FROM serv_fac_ven a 
	      INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven 
		  WHERE a.prefijo=b.prefijo AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' 
		  AND b.".VALIDACION_VENTA_VALIDA." GROUP BY  id_tec";

	$rs=$linkPDO->query($sql);
	$resp[]=0;
	while($row=$rs->fetch())
	{
			$idTec=$row["id_tec"];
			$resp[$idTec]=$row["T"];
	}
	return $resp;
};
function fix_cartera($idCli,$numFac,$pre,$codSuc,$abono,$tot,$estado,$fechaPago)
{
global $conex,$MODULES,$linkPDO;
try {
$linkPDO->beginTransaction();
$all_query_ok=true;

	$saldo=$tot-$abono;

	$filtroSedeFac="AND nit='$codSuc'";
	$filtroSedeComp="";

if($MODULES["MULTISEDES_UNIFICADAS"]==1){

$filtroSedeFac="";
	$filtroSedeComp="";
}

	if($saldo<=0 && ($estado!='PAGADO' || $fechaPago=="0000-00-00 00:00:00")){
	$s="SELECT MAX(b.fecha) fp FROM comprobante_ingreso b INNER JOIN cartera_mult_pago a ON a.num_comp=b.num_com AND a.cod_su=b.cod_su WHERE a.num_fac='$numFac' AND a.pre='$pre'";
	//echo "<li>$s</li>";
	$rs=$linkPDO->query($s);
	$row=$rs->fetch();
	$fechaPago=$row["fp"];

	$sql="UPDATE fac_venta SET estado='PAGADO',fecha_pago='$fechaPago' WHERE num_fac_ven='$numFac' AND prefijo='$pre' $filtroSedeFac";
	//echo "$sql";
	$linkPDO->exec($sql);
	auto_unban_cli($idCli);

	}

	if($saldo>0){
	$sql="UPDATE fac_venta SET estado='PENDIENTE',fecha_pago='0000-00-00' WHERE num_fac_ven='$numFac' AND prefijo='$pre' $filtroSedeFac";
	//echo "$sql";
	$linkPDO->exec($sql);

	}

	$sql="UPDATE `cartera_mult_pago` SET id_cli='$idCli' WHERE (num_fac = $numFac AND pre = '$pre') AND  (id_cli!='$idCli') ";
	$linkPDO->exec($sql);

	$sql="UPDATE `comprobante_ingreso` b INNER JOIN (SELECT * FROM cartera_mult_pago WHERE num_fac = $numFac AND pre = '$pre') a ON a.num_comp=b.num_com AND a.cod_su=b.cod_su SET b.id_cli='$idCli' WHERE b.cod_su=a.cod_su AND b.cod_su='$codSuc' AND (b.id_cli!='$idCli')";
	$linkPDO->exec($sql);

$linkPDO->commit();
if($all_query_ok){

}
else{eco_alert("ERROR! Intente nuevamente");}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

};

function inventario_sedes($codSuc,$conex,$fechaKardex,$fechaF)
{

global $NIT_FANALCA;
global $linkPDO;

$LIKE=" WHERE b.id_fab = '$NIT_FANALCA' ";$NOT_LIKE=" WHERE b.id_fab != '$NIT_FANALCA' ";
/*$COSTO="costo*(1+ a.iva/100)";*/
$COSTO="costo";
$COSTO_IVA="costo+(costo*((iva/100)) )";


$i=$codSuc;
$SUM_COSTOS[]=0;
$sede=array(1=>'2010-01-01','2010-01-01','2010-01-01','2010-01-01');
if($fechaF<$sede[$i])$fechaF=$sede[$i];
$sql="SELECT

(SELECT SUM(cant*($COSTO - $COSTO*dcto/100)) FROM (SELECT cod_su,nit_pro,num_fac_com,cant,costo,dcto,iva FROM art_fac_com) a INNER JOIN fac_com f ON a.num_fac_com=f.num_fac_com  WHERE a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$i AND estado='CERRADA' AND f.kardex=1 AND DATE(fecha)<='$fechaF') INcom,




(SELECT SUM(cant*$COSTO) FROM (SELECT cant,costo,nit_pro,cod_su,num_fac_com,iva FROM art_fac_dev) a INNER JOIN fac_dev f on a.num_fac_com=f.num_fac_com  WHERE a.cod_su=f.cod_su AND f.kardex=1 AND  a.nit_pro=f.nit_pro AND a.cod_su=$i AND DATE(fecha)<='$fechaF') OUTdev,




(SELECT sum(cant*$COSTO) cant FROM (SELECT cant,costo,num_fac_ven,prefijo,nit,iva FROM art_fac_ven) a INNER JOIN (SELECT * FROM fac_venta WHERE kardex=1) f ON a.num_fac_ven=f.num_fac_ven WHERE f.prefijo=a.prefijo AND f.nit=a.nit AND f.nit=$i AND f.anulado='ANULADO' AND DATE(fecha)<='$fechaF') INnull,



(SELECT SUM( a.cant * $COSTO ) cant
FROM art_fac_ven a
LEFT JOIN fac_venta fv ON fv.num_fac_ven = a.num_fac_ven
WHERE a.prefijo = fv.prefijo
AND a.nit = fv.nit
AND a.nit =$i
AND fv.kardex=1
AND fv.".VALIDACION_VENTA_VALIDA."
AND DATE( fecha ) <=  '$fechaF'
)OUTven,

(SELECT SUM( a.cant * $COSTO ) cant
FROM art_fac_remi a
LEFT JOIN fac_venta fv ON fv.num_fac_ven = a.num_fac_ven
WHERE a.prefijo = fv.prefijo
AND a.nit = fv.nit
AND a.nit =$i
AND fv.kardex=1
AND DATE( fecha ) <=  '$fechaF'
)OUTremi,


(SELECT sum(cant*$COSTO) cant FROM (SELECT cant,costo,ref,num_ajuste,cod_su,iva FROM art_ajuste  ) a INNER JOIN ajustes b ON a.num_ajuste=b.num_ajuste WHERE a.cod_su=b.cod_su AND b.cod_su=$i AND DATE(fecha)<='$fechaF')  AjustesFA,




(SELECT SUM(IFNULL(TotINFA,0)-IFNULL(TotOUTFA,0)+IFNULL(AjustesFA,0)-IFNULL(OUTdevFA,0)) ) InvIVA,
(SELECT SUM(IFNULL(TotINOT,0)-IFNULL(TotOUTOT,0)+IFNULL(AjustesOT,0)-IFNULL(OUTdevOT,0))) InvSIN
 ";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
		$SUM_COSTOS[0]=round($row['InvIVA']);
		$SUM_COSTOS[1]=round($row['InvSIN']);
		$SUM_COSTOS[2]=round($row['InvIVA']+$row['InvSIN']);
}

return $SUM_COSTOS;
};
function multiSelcSql($array,$col)
{
	$E="";
	if(isset($array)&&!empty($array))
{

	$E="AND (";
	foreach($array as $key=> $resultado)
	{
		$E.="$col='$resultado' OR ";
	}
	$E.=" $col='$resultado') ";

	if(empty($resultado))$E="";

	}
	return $E;
};
function tot_comp_ingreso($fechaI,$fechaF,$filtroHora,$filtroSEDE_cod_su,$tipoRS="valor")
{
global $FP_ingresos;
global $linkPDO;
$TOT_INGRESOS[]=0;
foreach($FP_ingresos as $key=> $result)
{$TOT_INGRESOS[$result]=0;}

if($tipoRS=="valor"){$sql="SELECT SUM(valor-r_fte-r_ica) as TOT,forma_pago FROM comprobante_ingreso  WHERE  id_cli!='' $filtroSEDE_cod_su AND  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) GROUP BY forma_pago";}

else {$sql="SELECT COUNT(*) as TOT,forma_pago FROM comprobante_ingreso  WHERE  id_cli!='' $filtroSEDE_cod_su AND  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) GROUP BY forma_pago";}

$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
	$formaPago=$row["forma_pago"];
	$sumTot=$row["TOT"];
	$TOT_INGRESOS[$formaPago]=$sumTot;
}

return $TOT_INGRESOS;
};



function inv_dias2($fechaI,$fechaF,$codSuc,$IVA_QUERY="all")
{
	global $linkPDO;

$TOT_INV=costo_now($codSuc,"con");
$costoC=$TOT_INV[0];
$costoS=$TOT_INV[1];
$inv_pvp=$TOT_INV[2];
$IVA=$costoC-$costoS;

$printRS=0;

//if($IVA_QUERY=="all"){$printRS=1;}



if($printRS){

echo "<li>Costo Hoy ".money($costoC)."</li>";

}

$FILTRO_IVA=" ";


if($IVA_QUERY!="all")
{
	$FILTRO_IVA=" AND a.iva=$IVA_QUERY";
}
else {$FILTRO_IVA=" ";}



////////// COMPRAS (-) ///////

$costo="a.costo";
$iva="a.iva";
$uni_fracc="a.unidades_fraccion";
$fracc="a.fraccion";
$cant="a.cant";


$descuento="a.costo*(a.dcto/100)";
$unidades="(a.unidades_fraccion+(a.cant*a.fraccion))/a.fraccion";
$SUB="ROUND(SUM(a.costo*($unidades) )) ";
$DCTO="ROUND(SUM( ( $unidades  )*($descuento))) ";
$IVA="ROUND(SUM( ($unidades  )*(a.costo - ($descuento))*(a.iva/100))) as IVA";
$stot="(a.costo*( $unidades ))";
$dcto="(( $unidades  )*($descuento))";
$Ivaflete="b.flete*0.19";
$iva="( ($unidades  )*(a.costo - ($descuento))*(a.iva/100) )";
$TOT="ROUND(SUM(  $stot + $iva - $dcto) ) ";
$TOT_FLETE="SUM(b.flete) as FLETE";

$sqlVars="IFNULL( ROUND(SUM(($costo*(1+($iva/100)))*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcostoIVA, IFNULL( ROUND(SUM($costo*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcosto";

$sqlVars="IFNULL( $TOT , 0 ) AS TOTcostoIVA, IFNULL( $SUB-$DCTO , 0 ) AS TOTcosto";

$tab_a="art_fac_com";
$tab_b="fac_com";
$sql="SELECT $sqlVars FROM $tab_a a INNER JOIN $tab_b f on a.num_fac_com=f.num_fac_com  WHERE (tipo_fac='Compra') $FILTRO_IVA AND f.estado='CERRADA' AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc  and (DATE(f.fecha)>='$fechaF' and DATE(f.fecha)<='$fechaI')";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$costoC-=$row["TOTcostoIVA"];
	$costoS-=$row["TOTcosto"];
	if($printRS==1)echo "<li>- COMPRAS: ".money2($row["TOTcostoIVA"])."</li>";
}

////////// COMPRAS 2 (-) ///////

$costo="a.costo";
$iva="a.iva";
$uni_fracc="a.unidades_fraccion";
$fracc="a.fraccion";
$cant="a.cant";


$descuento="a.costo*(a.dcto/100)";
$unidades="(a.unidades_fraccion+(a.cant*a.fraccion))/a.fraccion";
$SUB="ROUND(SUM(a.costo*($unidades) )) ";
$DCTO="ROUND(SUM( ( $unidades  )*($descuento))) ";
$IVA="ROUND(SUM( ($unidades  )*(a.costo - ($descuento))*(a.iva/100))) as IVA";
$stot="(a.costo*( $unidades ))";
$dcto="(( $unidades  )*($descuento))";
$Ivaflete="b.flete*0.19";
$iva="( ($unidades  )*(a.costo - ($descuento))*(a.iva/100) )";
$TOT="ROUND(SUM(  $stot + $iva - $dcto) ) ";
$TOT_FLETE="SUM(b.flete) as FLETE";

$sqlVars="IFNULL( ROUND(SUM(($costo*(1+($iva/100)))*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcostoIVA, IFNULL( ROUND(SUM($costo*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcosto";

$sqlVars="IFNULL( $TOT , 0 ) AS TOTcostoIVA, IFNULL( $SUB-$DCTO , 0 ) AS TOTcosto";

$tab_a="art_fac_com";
$tab_b="fac_com";
$sql="SELECT $sqlVars FROM $tab_a a INNER JOIN $tab_b f on a.num_fac_com=f.num_fac_com  WHERE (tipo_fac!='Compra' and tipo_fac!='Inventario Inicial' AND tipo_fac!='Corte Inventario' AND tipo_fac!='Ajuste Seccion') $FILTRO_IVA AND f.estado='CERRADA' AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc  and (DATE(f.fecha)>='$fechaF' and DATE(f.fecha)<='$fechaI')";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$costoC-=$row["TOTcostoIVA"];
	$costoS-=$row["TOTcosto"];
	if($printRS==1)echo "<li>- COMPRAS 2: ".money2($row["TOTcostoIVA"])."</li>";
}
/////////////////// VENTAS (+) ///////////////
$costo="a.costo";
$iva="a.iva";
$uni_fracc="a.unidades_fraccion";
$fracc="a.fraccion";
$cant="a.cant";

$sqlVars="IFNULL( ROUND(SUM(($costo*(1+($iva/100)))*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcostoIVA, IFNULL( ROUND(SUM($costo*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcosto";

$tab_a="art_fac_ven";
$tab_b="fac_venta";
$sql="SELECT $sqlVars FROM $tab_a a INNER JOIN $tab_b f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo $FILTRO_IVA and a.nit=f.nit and f.".VALIDACION_VENTA_VALIDA." and a.nit=$codSuc  and (DATE(f.fecha)>='$fechaF' and DATE(f.fecha)<='$fechaI')";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$costoC+=$row["TOTcostoIVA"];
	$costoS+=$row["TOTcosto"];
	if($printRS==1)echo "<li>+ VENTAS: ".money2($row["TOTcostoIVA"])."</li>";
}

/////////////////// DEVOLUCIONES PROVEEDORES (+)///////////////
$costo="a.costo";
$iva="a.iva";
$uni_fracc="a.unidades_fraccion";
$fracc="a.fraccion";
$cant="a.cant";

$sqlVars="IFNULL( ROUND(SUM(($costo*(1+($iva/100)))*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcostoIVA, IFNULL( ROUND(SUM($costo*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcosto";

$tab_a="art_fac_dev";
$tab_b="fac_dev";
$sql="SELECT $sqlVars FROM $tab_a a INNER JOIN $tab_b f ON a.num_fac_com=f.num_fac_com  WHERE a.serial_dev=f.serial_fac_dev $FILTRO_IVA AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc and (DATE(f.fecha)>='$fechaF' and DATE(f.fecha)<='$fechaI')";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$costoC+=$row["TOTcostoIVA"];
	$costoS+=$row["TOTcosto"];
	if($printRS==1)echo "<li>+ DEVOLUCIONES COMPRAS: ".money2($row["TOTcostoIVA"])."</li>";
}

/////////////////// AJUSTES (-) ///////////////
$costo="a.costo";
$iva="a.iva";
$uni_fracc="a.unidades_fraccion";
$fracc="a.fraccion";
$cant="a.cant";

$sqlVars="IFNULL( ROUND(SUM(($costo*(1+($iva/100)))*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcostoIVA, IFNULL( ROUND(SUM($costo*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcosto";

$tab_a="art_ajuste";
$tab_b="ajustes";
$sql="SELECT $sqlVars FROM $tab_a a INNER JOIN $tab_b f ON a.num_ajuste=f.num_ajuste  WHERE a.cod_su=f.cod_su $FILTRO_IVA  and a.cod_su=$codSuc and (DATE(f.fecha)>='$fechaF' and DATE(f.fecha)<='$fechaI')  ";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$costoC-=$row["TOTcostoIVA"];
	$costoS-=$row["TOTcosto"];
	if($printRS==1)echo "<li>- AJUSTES: ".money2($row["TOTcostoIVA"])."</li>";
}
//if($costoS>0){$costoS=0;$costoC=0;}
/////////////////// REMISIONES (+) ///////////////
$costo="a.costo";
$iva="a.iva";
$uni_fracc="a.unidades_fraccion";
$fracc="a.fraccion";
$cant="a.cant";

$sqlVars="IFNULL( ROUND(SUM(($costo*(1+($iva/100)))*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcostoIVA, IFNULL( ROUND(SUM($costo*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcosto";

$tab_a="art_fac_remi";
$tab_b="fac_remi";
$sql="SELECT $sqlVars FROM $tab_a a INNER JOIN $tab_b f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo $FILTRO_IVA and a.nit=f.nit and (f.anulado!='ANULADO' and f.anulado!='FACTURADA' and f.tipo_fac='remision') and a.nit=$codSuc  and (DATE(f.fecha)>='$fechaF' and DATE(f.fecha)<='$fechaI')";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$costoC+=$row["TOTcostoIVA"];
	$costoS+=$row["TOTcosto"];
	if($printRS==1)echo "<li>+ REMISIONES: ".money2($row["TOTcostoIVA"])."</li>";
}

/////////////////// TRASLADOS SALEN (+) ///////////////
$costo="a.costo";
$iva="a.iva";
$uni_fracc="a.unidades_fraccion";
$fracc="a.fraccion";
$cant="a.cant";

$sqlVars="IFNULL( ROUND(SUM(($costo*(1+($iva/100)))*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcostoIVA, IFNULL( ROUND(SUM($costo*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcosto";

$tab_a="art_fac_remi";
$tab_b="fac_remi";
$sql="SELECT $sqlVars FROM $tab_a a INNER JOIN $tab_b f ON a.num_fac_ven=f.num_fac_ven  where a.prefijo=f.prefijo $FILTRO_IVA and a.nit=f.nit and (f.anulado!='ANULADO' and f.anulado!='FACTURADA' and f.sede_destino!=0 and tipo_cli='Traslado') and a.nit=$codSuc  and (DATE(f.fecha)>='$fechaF' and DATE(f.fecha)<='$fechaI')";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$costoC+=$row["TOTcostoIVA"];
	$costoS+=$row["TOTcosto"];
	if($printRS==1)echo "<li>+ TRASLADOS ENVIADOS: ".money2($row["TOTcostoIVA"])."</li>";
}

/////////////////// TRASLADOS ENTRAN (-) ///////////////
$costo="a.costo";
$iva="a.iva";
$uni_fracc="a.unidades_fraccion";
$fracc="a.fraccion";
$cant="a.cant";

$sqlVars="IFNULL( ROUND(SUM(($costo*(1+($iva/100)))*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcostoIVA, IFNULL( ROUND(SUM($costo*(($uni_fracc+($cant*$fracc))/$fracc ) )) , 0 ) AS TOTcosto";

$tab_a="art_fac_remi";
$tab_b="fac_remi";
$sql="SELECT $sqlVars FROM $tab_a a INNER JOIN $tab_b f ON a.num_fac_ven=f.num_fac_ven  where a.prefijo=f.prefijo $FILTRO_IVA and a.nit=f.nit and (f.anulado!='ANULADO' and f.anulado!='FACTURADA' and f.sede_destino!=0 and f.tipo_cli='Traslado' and f.estado='Recibido') and f.sede_destino=$codSuc  and (DATE(f.fecha)>='$fechaF' and DATE(f.fecha)<='$fechaI')";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$costoC-=$row["TOTcostoIVA"];
	$costoS-=$row["TOTcosto"];
	if($printRS==1)echo "<li>- TRASLADOS RECIBIDOS: ".money2($row["TOTcostoIVA"])."</li>";
}

if($printRS==1)echo "<br><br><br><br>";
$RESP[0]=$costoS;
$RESP[1]=$costoC;
return $RESP;

};







function val_caja_gral($FP,$claseMov,$OP)
{
	global $MODULES,$codSuc;
	global $linkPDO;
	$idCta="0";
	/* || ($claseMov=="Factura Venta" && $OP=="-") */
	if( ( ($FP=="Contado" || $FP=="Contado-Caja General")   )   ){

		$sql="SELECT id_cuenta FROM cuentas_dinero WHERE tipo_cta='Ingresos Ventas' AND cod_su='$codSuc'";
		//echo "$sql";
		$rs=$linkPDO->query($sql);
		if($ro=$rs->fetch())
		{
			$idCta=$ro["id_cuenta"];
			//echo "<li>id: $idCta</li>";
		}else {$idCta="0";}

		}

	return $idCta;
};
function up_cta($FP,$idCta,$monto,$OP,$concepto,$claseMov,$fecha)
{
	global $MODULES,$codSuc,$linkPDO;


	if($FP!="Credito" && $MODULES["CUENTAS_BANCOS"]==1){

	if(empty($idCta))$idCta=val_caja_gral($FP,$claseMov,$OP);
		//echo "<li>id: $idCta</li>";

	$tipoOP="INGRESO";
	if($OP=="-")$tipoOP="EGRESO";

	$all_query_ok=true;



	$sql="UPDATE cuentas_dinero  SET saldo_cta=(saldo_cta $OP $monto) WHERE id_cuenta='$idCta'";
	$linkPDO->exec($sql);
	$sql="INSERT INTO cuentas_mvtos(id_cuenta,tipo_mov,concepto_mov,clase_mov,monto,fecha_mov,saldo)  VALUES('$idCta','$tipoOP','$concepto','$claseMov','$monto','$fecha',IFNULL((select saldo_cta FROM cuentas_dinero WHERE id_cuenta='$idCta'),0)  )";
	$linkPDO->exec($sql);



}



};
function selcCta($funct,$class,$selected="",$name="id_cuenta",$showAll=0)
{
	global $codSuc;
	global $linkPDO;

	$filterA="WHERE id_cuenta!=1";
	if($showAll==1)$filterA="";
	//echo "$funct <br>";
	$selc="<select name=\"$name\" id=\"$name\" onchange=\"$funct\"  class=\"$class\" style=\"width:100px;\"><option value=\"\"  >CUENTAS</option>";
	$sql="SELECT id_cuenta,nom_cta,cod_cta,clase_cta FROM cuentas_dinero  $filterA";
	//echo "$sql <br>";
	$rs=$linkPDO->query($sql);
	while($row=$rs->fetch()){
		$ID=$row["id_cuenta"];
		$Nom=$row["nom_cta"];
		$COD=$row["cod_cta"];
		$Type=$row["clase_cta"];


		$VAL="$ID";

		if($selected==$VAL)$sel=" selected ";
		else $sel="";

		$selc.="<option value=\"$VAL\"  $sel >$Nom / $Type / $COD</option>";
	}
	$selc.=" </select>";

	//echo $selc;
	return $selc;

};
function print_pro($url)
{
	/* width=\"100px\" height=\"100px\" */
	$img="<img src=\"$url\"  class=\"uk-thumbnail uk-thumbnail-mini\">";

	return $img;
};
function strip_codigo($codBarras)
{
	$mid=substr("$codBarras", 19, -10);

	$CUPON=substr("$mid", 0, -14);
	$VALOR=substr("$mid", 15);

	$RESPUESTA[0]=$CUPON;
	$RESPUESTA[1]=$VALOR;
	return $RESPUESTA;
};
function prettyPrint( $json )
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
};
function anula_dev_venta($num_fac,$pre,$codSuc)
{
$tableMain="fac_dev_venta";
$tableArt="art_devolucion_venta";
$tipoOp="-";

global $nomUsu,$id_Usu,$FLUJO_INV,$conex,$SECCIONES,$OPERACIONES,$fecha_lim_anulaVenta;
global $linkPDO;
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$qry="";
$sql="SELECT * FROM $tableMain WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc'";
//echo $sql."<br>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){

	        $sql="SELECT * FROM $tableMain WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND anulado!='ANULADO' AND anulado='CERRADA' AND nit=$codSuc";
            //echo $sql."<br>";
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch()){
			$karDex=$row['kardex'];
			$tot=$row["tot"]-$row["abono_anti"];
			$form_p="Contado";
			$idCta=$row["id_cuenta"];
			up_cta($form_p,$idCta,$tot,"-","DEVOLUCION Venta $pre-$num_fac","Factura Venta",$hoy);
			$sql="SELECT * FROM $tableMain WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$codSuc $fecha_lim_anulaVenta";
            //echo $sql."<br>";
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch()){

			if( 1){


			//$NUM_EXP=$row['num_exp'];
			$qryA="UPDATE $tableMain SET anulado='ANULADO', fecha_anula='$hoy', usu='$nomUsu',id_usu='$id_Usu',modifica='$nomUsu ANULA FAC. $pre-$num_fac' WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$codSuc";
			//echo $qry."<br>";

if($FLUJO_INV==1 && $karDex==1){
$qryB="UPDATE `inv_inter` i
INNER JOIN
(select ar.nit nitAr,sum(cant) cant,ref,cod_barras,fraccion,unidades_fraccion,fecha_vencimiento from $tableArt ar inner join (select * from $tableMain f WHERE num_fac_ven=$num_fac AND prefijo='$pre'  ) fv ON fv.num_fac_ven=ar.num_fac_ven WHERE fv.nit=ar.nit and fv.nit=$codSuc and fv.prefijo=ar.prefijo group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a
ON i.id_inter=a.cod_barras
SET i.exist=(i.exist $tipoOp a.cant), i.unidades_frac=(i.unidades_frac $tipoOp a.unidades_fraccion) WHERE i.nit_scs=a.nitAr and i.nit_scs=$codSuc AND i.fecha_vencimiento=a.fecha_vencimiento AND i.id_pro=a.ref";
t2($qryA,$qryB);
}
else{t1($qryA);
}

			}
			else {echo "-445";}

			}// fin if cartera
			else {echo "-1";}

			}
			else {echo "0";}

}
else {echo "-2";}


	}
function dev_ventas($fechaI,$fechaF,$codSuc,$filtroCaja="")
{
	global $linkPDO;
	$resp["tot"]=0;
	$resp["base"]=0;
	$resp["dcto"]=0;
	$resp["iva"]=0;
	///////////////////////////////////////////////// TOTAL DEVOCLUCIONES CONTADO////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_dev_venta  WHERE nit='$codSuc' AND tipo_venta!='Credito'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') AND anulado!='ANULADO' $filtroCaja";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$resp["tot"]=$row['TOT'];
	$resp["base"]=$row['BASE'];
	$resp["dcto"]=$row['D'];
	$resp["iva"]=$row['IVA'];

}

return $resp;
};
function tot_abon_cre_rango($feI,$feF)
{
	global $codSuc;
	global $linkPDO;
	$RESP[0]="";
	$RESP["abono"]=0;
	$RESP["tot"]=0;
	$RESP["saldo"]=0;

	//$sql="SELECT IFNULL(SUM(valor),0) AS t FROM comprobante_ingreso WHERE id_cli='$idCli' AND anulado!='ANULADO'";
	$sql="SELECT IFNULL(SUM(abono),0) AS t FROM cartera_mult_pago a 
	INNER JOIN fac_venta b ON a.num_fac=b.num_fac_ven AND a.pre=b.prefijo AND a.cod_su=b.nit  
	WHERE   a.estado!='ANULADO' AND b.anulado!='ANULADO'  AND b.nit='$codSuc' AND (DATE(b.fecha)>='$feI' 
	AND DATE(b.fecha)<='$feF') ";
	$rs=$linkPDO->query($sql);

	if($rr=$rs->fetch()) {
		$abon=$rr['t'];
		$RESP["abono"]=$abon;
	}



	$sql="SELECT b.tot FROM fac_venta a
	INNER JOIN
	(SELECT prefijo,num_fac,SUM(valor) tot,a.cod_su FROM comp_anti a 
	INNER JOIN exp_anticipo b ON a.num_exp=b.num_exp AND a.cod_su=b.cod_su 
	AND  a.cod_su='$codSuc' AND a.estado='COBRADO'  GROUP BY prefijo,num_fac) b
	ON a.num_fac_ven=b.num_fac AND a.prefijo=b.prefijo AND a.nit=b.cod_su  AND ".VALIDACION_VENTA_VALIDA." 
	AND tipo_venta='Credito' AND num_exp!=0   AND (DATE(b.fecha)>='$feI' AND DATE(b.fecha)<='$feF')";

	$rs=$linkPDO->query($sql);

	if($rr=$rs->fetch()) {
		$abon=$rr['tot'];
		$RESP["abono"]+=$abon;
	}



	$qry="SELECT SUM(tot) AS T FROM fac_venta WHERE   tipo_venta='Credito' AND ".VALIDACION_VENTA_VALIDA." AND nit='$codSuc' AND (DATE(fecha)>='$feI' AND DATE(fecha)<='$feF')";
	//echo $qry;
	$rs=$linkPDO->query($qry);$saldo=0;
	if($row=$rs->fetch())
	{
	$tot=$row['T'];$RESP["tot"]=$tot;
	$saldo=$tot-$RESP["abono"];$RESP["saldo"]=$saldo;
	//echo "---> $saldo, $tot";
	}
	else {$RESP[2020]=0;}
	return $RESP;
};
function tot_saldo_cre_rango($fecha,$feI,$feF,$numComp=0)
{
global $codSuc,$MODULES;
global $linkPDO;

$FILTRO_SEDES_FAC="AND nit=$codSuc";
$FILTRO_SEDES_COD_SU="AND cod_su=$codSuc";

if($MODULES["MULTISEDES_UNIFICADAS"]==1){$FILTRO_SEDES_FAC="";$FILTRO_SEDES_COD_SU="";}
$RESP[0]="";
$RESP["abono"]=0;
$RESP["tot"]=0;
$RESP["saldo"]=0;

$filtroLastOne="";
if($numComp!=0){$filtroLastOne=" AND num_comp<=$numComp";}

//$sql="SELECT IFNULL(SUM(valor),0) AS t FROM comprobante_ingreso WHERE id_cli='$idCli' AND anulado!='ANULADO'";
$sql="SELECT IFNULL(SUM(abono),0) AS t FROM cartera_mult_pago a INNER JOIN fac_venta b ON a.num_fac=b.num_fac_ven AND a.pre=b.prefijo  $filtroLastOne   WHERE   a.estado!='ANULADO' AND b.anulado!='ANULADO'  $FILTRO_SEDES_FAC AND (DATE(b.fecha)>='$feI' AND DATE(b.fecha)<='$feF') ";
$rs=$linkPDO->query($sql);

if($rr=$rs->fetch()) {
	$abon=$rr['t'];
	$RESP["abono"]=$abon;
}


$qry="SELECT SUM(tot) AS T FROM fac_venta WHERE   tipo_venta='Credito' AND ".VALIDACION_VENTA_VALIDA." AND nit='$codSuc' AND (DATE(fecha)>='$feI' AND DATE(fecha)<='$feF')";
//echo $qry;
$rs=$linkPDO->query($qry);$saldo=0;
if($row=$rs->fetch())
{
$tot=$row['T'];$RESP["tot"]=$tot;
$saldo=$tot-$RESP["abono"];$RESP["saldo"]=$saldo;
//echo "---> $saldo, $tot";
}
else {$RESP[2020]=0;}
return $RESP;};


function subirImagenFixedSize($photo, $folder) {
	$maxDimW = 100;
    $maxDimH = 50;
	$UploadDirectory = "Imagenes/$folder/"; //specify upload directory ends with / (slash)
	list($width, $height, $type, $attr) = getimagesize( $_FILES["$photo"]['tmp_name'] );
	
		$target_filename = $_FILES["$photo"]['tmp_name'];
		$fn = $_FILES["$photo"]['tmp_name'];
		$size = getimagesize( $fn );
		$ratio = $size[0]/$size[1]; // width/height
		if( $ratio > 1) {
			$width = $maxDimW;
			$height = $maxDimH/$ratio;
		} else {
			$width = $maxDimW*$ratio;
			$height = $maxDimH;
		}
		$src = imagecreatefromstring(file_get_contents($fn));
		$dst = imagecreatetruecolor( $width, $height );
		imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1] );

		imagejpeg($dst, $target_filename); // adjust format as needed
	
	move_uploaded_file($_FILES["$photo"]['tmp_name'], $UploadDirectory . $target_filename);
}


function subir_imagen($file, $folder) {
        if (isset($_FILES["$file"]) && $_FILES["$file"]["error"] == UPLOAD_ERR_OK) {
            $UploadDirectory = "Imagenes/$folder/"; //specify upload directory ends with / (slash)

            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
               // die();
				 //echo '1st IF!';
            }

            if ($_FILES["$file"]["size"] > 524288000) {
                die("File size is too big!");
            }

            switch (strtolower($_FILES["$file"]['type'])) {
                //allowed file types
                case 'image/png':
                case 'image/gif':
                case 'image/jpeg':
                case 'image/pjpeg':
                case 'text/plain':
				case 'text/csv':
                case 'text/html': //html file
                case 'application/x-zip-compressed':
                case 'application/pdf':
                case 'application/msword':
                case 'application/vnd.ms-excel':
                case 'video/mp4':
                    break;
                default:
                    echo 'Unsupported File!'; //output error
            }
            $File_Name = $_FILES["$file"]['name'];
            $File_Ext = substr($File_Name, strrpos($File_Name, '.')); //get file extention
            $Random_Number = rand(0, 9999999999); //Random number to be added to name.
            if (move_uploaded_file($_FILES["$file"]['tmp_name'], $UploadDirectory . $File_Name)) {
                //echo $_FILES["$file"]['tmp_name'];
                //echo "{'url':'/img/$folder/$File_Name'}";
                return  "Imagenes/$folder/$File_Name";
            } else {
                return  "0";
            }
        } else {
            return  "-2" ;
        }
    };


function subir_csv($file, $folder) {
        if (isset($_FILES["$file"]) && $_FILES["$file"]["error"] == UPLOAD_ERR_OK) {
            $UploadDirectory = "Imagenes/$folder/"; //specify upload directory ends with / (slash)

            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
               // die();
				 //echo '1st IF!';
            }

            if ($_FILES["$file"]["size"] > 524288000) {
                die("File size is too big!");
            }

            switch (strtolower($_FILES["$file"]['type'])) {
                //allowed file types
                case 'image/png':
                case 'image/gif':
                case 'image/jpeg':
                case 'image/pjpeg':
                case 'text/plain':
				case 'text/csv':
                case 'text/html': //html file
                case 'application/x-zip-compressed':
                case 'application/pdf':
                case 'application/msword':
                case 'application/vnd.ms-excel':
                case 'video/mp4':
                    break;
                default:
                    echo 'Unsupported File!'; //output error
            }
            $File_Name = $_FILES["$file"]['name'];
            $File_Ext = substr($File_Name, strrpos($File_Name, '.')); //get file extention
            $Random_Number = rand(0, 9999999999); //Random number to be added to name.
            if (move_uploaded_file($_FILES["$file"]['tmp_name'], $UploadDirectory . $File_Name)) {
                //echo $_FILES["$file"]['tmp_name'];
                //echo "{'url':'/img/$folder/$File_Name'}";
                return  "$File_Name";
            } else {
                return  "0";
            }
        } else {
            return  "-2" ;
        }
    };



function import_csv2($fileName,$colSet,$codSuc){

global $hoy,$fechaHoy,$munSuc,$linkPDO;

// path where your CSV file is located

/*
$tmpName = $_FILES['csv']['tmp_name'];
$csvAsArray = array_map('str_getcsv', file($tmpName));

*/

$csv_file = CSV_PATH . "$fileName"; // Name of your CSV file

$csvfile = fopen($csv_file, 'r');
$theData = fgets($csvfile);
$i = 0;

try {
$linkPDO->beginTransaction();
$all_query_ok=true;



$sql="INSERT IGNORE INTO `fac_com` (`nom_pro`, `nit_pro`, `ciudad`, `dir`, `tel`, `fax`, `mail`, `num_fac_com`, `fecha`, `cod_su`, `subtot`, `descuento`, `flete`, `iva`, `tot`, `val_letras`, `fecha_mod`, `fecha_crea`, `serial_fac_com`, `tipo_fac`, `estado`, `dcto2`, `pago`, `fecha_pago`, `id_banco`, `id_cuenta`, `kardex`, `r_fte`, `r_ica`, `r_iva`, `feVen`, `sede_origen`, `sede_destino`, `serial_tras`, `calc_dcto`, `perflete`) VALUES ('IMPORTACION BD', 'R-66Y', '$munSuc', '', '', '', '', '1122334455-IMP', '$hoy', $codSuc, '0.00', '0.00', '0.00', '0.00', '0.00', '', '$hoy', '$hoy', 1000000, 'Importar BD', 'ABIERTA', '0.00', 'PENDIENTE', '0000-00-00', 0, 0, 1, '0.00', '0.00', '0.00', '2016-03-29', 1, 0, 0, '', '0.00');";

$linkPDO->exec($sql);

while (!feof($csvfile)) {
$csv_data[] = fgets($csvfile, 1024);
$csv_array = explode(";", limpiarcampo($csv_data[$i]));


$insert_csv = array();
$tabla="art_fac_com";
if($colSet=="A"){
// A
// 0   1    2   3    4      5        6     7       8         9	   10	 	   11      12		13			14			15
//ref;des;costo;iva;pvp;cod_barras;color;talla;fabricante;clase;ubicacion;aplica_vehi;exist;cod_color;vigencia_ini;grupo_destino;


$csv_array[4]=limpianum2($csv_array[4]);
$csv_array[2]=limpianum2($csv_array[2]);

$csv_array[1]=limpiarcampo($csv_array[1]);
$csv_array[5]=limpiarcampo($csv_array[5]);
$csv_array[6]=limpiarcampo($csv_array[6]);
$csv_array[7]=limpiarcampo($csv_array[7]);
$csv_array[8]=limpiarcampo($csv_array[8]);
$csv_array[9]=limpiarcampo($csv_array[9]);
$csv_array[11]=limpiarcampo($csv_array[11]);
$csv_array[10]=limpiarcampo($csv_array[10]);
$csv_array[12]=limpiarcampo($csv_array[12]);

if(empty($csv_array[5]) )$csv_array[5]=$csv_array[0];
//$iva=$csv_array[3];
$iva=19;

$cols="num_fac_com,cant,ref,des,costo,dcto,iva,uti,pvp,tot,cod_su,nit_pro,tipo_dcto,dcto2,cod_barras,color,talla,fabricante,clase,presentacion,fecha_vencimiento,fraccion,unidades_fraccion,aplica_vehi,ubicacion";
$values="'1122334455-IMP','$csv_array[12]','$csv_array[0]','$csv_array[1]','$csv_array[2]','0','$iva','0','$csv_array[4]','0','1','R-66Y','','0','$csv_array[5]','$csv_array[6]','$csv_array[7]','$csv_array[8]','$csv_array[9]','UNIDAD','0000-00-00','1',	'0','$csv_array[11]','$csv_array[10]'    ";}


else if($colSet=="B"){
// B
// 0         1        2       3       4      5       6        7  		   8         9 				10			11				12
// REF	Descripcion	Clase	Talla   Color   costo	pvp	     fracc		fab/marca	cod_barras	cod_color	vigencia_ini	grupo_destino
$csv_array[5]=limpianum2($csv_array[5]);
$csv_array[6]=limpianum2($csv_array[6]);

$csv_array[1]=limpiarcampo($csv_array[1]);
$csv_array[2]=limpiarcampo($csv_array[2]);
$csv_array[3]=limpiarcampo($csv_array[3]);
$csv_array[4]=limpiarcampo($csv_array[4]);
$csv_array[8]=limpiarcampo($csv_array[8]);
$csv_array[9]=limpiarcampo($csv_array[9]);
$csv_array[10]=limpiarcampo($csv_array[10]);
$csv_array[11]=limpiarcampo($csv_array[11]);
$csv_array[12]=limpiarcampo($csv_array[12]);

if(empty($csv_array[7]) || $csv_array[7]<=0){$csv_array[7]=1;}

//echo "B <br> 0: $csv_array[0] , 1: ".$csv_array[1]."";
$cols="      num_fac_com,cant,      ref,             des,       costo,       dcto,iva,uti,      pvp,   tot,cod_su,nit_pro,tipo_dcto,dcto2,cod_barras,     color, talla,fabricante,clase,presentacion,fecha_vencimiento,fraccion,unidades_fraccion,aplica_vehi,cod_color,vigencia_inicial,grupo_destino";
$values="'1122334455-IMP','0','$csv_array[0]','$csv_array[1]','$csv_array[5]','0','19','0','$csv_array[6]','0','$codSuc','R-66Y','','0',    '$csv_array[9]','$csv_array[4]','$csv_array[3]','$csv_array[8]','$csv_array[2]','UNIDAD','0000-00-00','$csv_array[7]',	'0','','$csv_array[10]','$csv_array[11]','$csv_array[12]'";}

else{


// C
// 0   1     2    3    4      5        6     7       8         9	   10	 	   11
//des;costo;pvp;clase;
//string iconv ( string $in_charset , string $out_charset , string $str )
$ID=getId_art_com();

$ref=$ID;
$codBar=$ref;


$tab = array("UTF-8", "ASCII", "Windows-1252", "ISO-8859-15", "ISO-8859-1", "ISO-8859-6", "CP1256");


$csv_array[0]=iconv ($tab[2],$tab[0],$csv_array[0] );
$csv_array[3]=iconv ($tab[2],$tab[0],$csv_array[3] );

$csv_array[0]=limpiarcampo($csv_array[0]);
$csv_array[1]=limpianum2($csv_array[1]);
$csv_array[2]=limpianum2($csv_array[2]);
$csv_array[3]=limpiarcampo($csv_array[3]);


if(empty($csv_array[5]) )$csv_array[5]=$csv_array[0];
//$iva=$csv_array[3];
$iva=19;

//                      0     1       2                             3      4
$cols="num_fac_com,ref,des,costo,iva,pvp,cod_su,nit_pro,cod_barras,clase,cant";

$values="'1122334455-IMP','$ref','$csv_array[0]','$csv_array[1]','$iva','$csv_array[2]','1','R-66Y','$codBar','$csv_array[3]','$csv_array[4]'    ";



	}

$query = "INSERT IGNORE INTO $tabla($cols) VALUES($values)";
//echo "<li>$query</li>";
if(!empty($csv_array[0])){


	$linkPDO->exec($query);
	}
$i++;
}
$linkPDO->commit();
if($all_query_ok){

}
else{eco_alert("ERROR! Intente nuevamente");}
fclose($csvfile);

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

};

function mes_year($fecha)
{
	global $MESES;
	$mes=date("m",strtotime($fecha))*1;
	$year=date("Y",strtotime($fecha));
	$lastDay=date("t",strtotime($fecha));

	$resp=$MESES[$mes]." de $year";

	return $resp;
};
function saldo_mes_ant($fecha)
{
	global $linkPDO;
	$mes=date("m",strtotime($fecha));
	$year=date("Y",strtotime($fecha));
	$lastDay=date("t",strtotime($fecha));

	$first="$year-$mes-01";


	$lastMonth = strtotime ( '-1 day' , strtotime ( $first ) ) ;
	$lastMonth = date ( 'Y-m-j' , $lastMonth );

	$sql="SELECT * FROM cuentas_mvtos WHERE DATE(fecha_mov)<='$lastMonth' ORDER BY fecha_mov DESC LIMIT 1";
	//echo "$sql";
	//SELECT * FROM cuentas_mvtos WHERE DATE(fecha_mov)<='2017-08-31' ORDER BY fecha_mov DESC LIMIT 1
	$rs=$linkPDO->query($sql);
	$row=$rs->fetch();
	$resp=$row["saldo"];

	return $resp;

};

function limpianum2($num)
{
	global $PUNTUACION_DECIMALES;
	$T=(string)$num;
	$resp="";
   if(!empty($num)){
	$i = strlen($T) - 1;
	$ii = strlen($T);
   	$T=preg_split('//',$T,-1,PREG_SPLIT_NO_EMPTY);
	$long=count($T);

	for($j=0;$j<$long;$j++)
	{
		if($T[$j]=="0"||$T[$j]=="1"||$T[$j]=="2"||$T[$j]=="3"||$T[$j]=="4"||$T[$j]=="5"||$T[$j]=="6"||$T[$j]=="7"||$T[$j]=="8"||$T[$j]=="9"||$T[$j]=="-")
		{
			$resp.=$T[$j];
		}
	}
   }
   else $resp=0;


	return (float)$resp;
};

function limpianum3($num)
{
	global $PUNTUACION_DECIMALES;
	$T=(string)$num;
	$resp="";
   if(!empty($num)){
	$i = strlen($T) - 1;
	$ii = strlen($T);
   	$T=preg_split('//',$T,-1,PREG_SPLIT_NO_EMPTY);
	$long=count($T);

	for($j=0;$j<$long;$j++)
	{
		if($T[$j]=="0"||$T[$j]=="1"||$T[$j]=="2"||$T[$j]=="3"||$T[$j]=="4"||$T[$j]=="5"||$T[$j]=="6"||$T[$j]=="7"||$T[$j]=="8"||$T[$j]=="9")
		{
			$resp.=$T[$j];
		}
	}
   }
   else $resp=0;


	return (int)$resp;
};


function q_kardex($ref,$codBar,$feVen)
{
	global $codSuc,$fechaKardex,$usar_fecha_vencimiento;

	$ORDER_BY="fe";
	$filtroProducto="cod_barras='$codBar' AND ref='$ref' AND fecha_vencimiento='$feVen'";
	if($usar_fecha_vencimiento==0){$filtroProducto="cod_barras='$codBar' AND ref='$ref'";}


	$GROUP_BY=" GROUP BY ref,cod_barras,fecha_vencimiento,num";

	$sql="SELECT 1 as src,a.num_fac_com as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,pvp as precio,a.tot,
	             DATE(f.fecha) fecha,f.fecha as fe 
	      FROM art_fac_com a INNER JOIN fac_com f ON a.num_fac_com=f.num_fac_com  
		  WHERE f.estado='CERRADA' AND tipo_fac!='Corte Inventario' AND tipo_fac!='Ajuste Seccion' AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro 
		  and a.cod_su=$codSuc  and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )  $GROUP_BY
	UNION


	SELECT 12 as src,a.num_fac_com as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,pvp as precio,a.tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_com a INNER JOIN fac_com f ON a.num_fac_com=f.num_fac_com  WHERE (f.estado!='CERRADA' AND f.estado!='ABIERTA' AND tipo_fac!='Corte Inventario' AND tipo_fac!='Ajuste Seccion') AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc  and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )  $GROUP_BY
	UNION

	SELECT 2 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_ven a INNER JOIN fac_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit and f.".VALIDACION_VENTA_VALIDA." and f.anulado='CERRADA' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
	UNION

	SELECT 3 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli!='Traslado' AND anulado!='FACTURADA' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
	UNION

	SELECT 11 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli!='Traslado' AND anulado='FACTURADA' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
	UNION

	SELECT 4 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_ven a INNER JOIN fac_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo AND a.nit=f.nit and f.anulado='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
	UNION

	SELECT 5 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli='Traslado' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
	UNION

	SELECT 6 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli='Traslado' AND estado='Recibido' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and f.sede_destino=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
	UNION


	SELECT 7 as src,a.num_fac_com as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,pvp as precio,a.tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_dev a INNER JOIN fac_dev f ON a.num_fac_com=f.num_fac_com  AND anulado!='ANULADO' AND  a.serial_dev=f.serial_fac_dev AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
	UNION

	SELECT 8 as src,a.num_ajuste as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.precio,DATE(f.fecha) fecha,f.fecha as fe FROM art_ajuste a INNER JOIN ajustes f ON a.num_ajuste=f.num_ajuste  WHERE a.cod_su=f.cod_su  and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
	UNION

	SELECT 9 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_devolucion_venta a INNER JOIN fac_dev_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit and f.anulado!='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
	UNION

	SELECT 10 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_devolucion_venta a INNER JOIN fac_dev_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo AND a.nit=f.nit and f.anulado='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY

	ORDER BY $ORDER_BY
	";

	return $sql;
};
function getId_art_com()
{
	global $linkPDO;
$ID=0;
$sql="SELECT MAX(id) r FROM art_fac_com ";
$rs=$linkPDO->query($sql);

if($row=$rs->fetch()){$ID=$row["r"]+1;}
else {$ID=1;}

return $ID;
};
function retenciones($fechaI,$fechaF,$filtroHora,$filtroNOanuladas,$filtroSEDE_nit)
{
	global $linkPDO;
	$R["fte"]=0;
	$R["ica"]=0;
	$R["iva"]=0;

	$sql="SELECT SUM(r_fte) fte, SUM(r_ica) ica, SUM(r_iva) iva FROM fac_venta WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Contado' $filtroNOanuladas $filtroSEDE_nit";

	//echo "$sql";
	$rs=$linkPDO->query($sql);

	if($row=$rs->fetch()){

		$R["fte"]=$row["fte"];
		$R["ica"]=$row["ica"];
		$R["iva"]=$row["iva"];
	}

	return $R;
};

function fullBusq($text,$col)
{
	$T=trim($text);
	$T=explode(" ",$T);
	$ii = count($T);
	$search="(";
	for($i=0;$i<$ii;$i++){
		if($i==0){$search.=" $col LIKE '%$T[$i]%' ";}
		else{$search.=" AND $col LIKE '%$T[$i]%'";}


	}
	$search.=")";

	return $search;
};
function fecha_diff($desde,$hasta)
{
	$f1=date_create($desde);
	$f2=date_create($hasta);
	$interval=date_diff($f1,$f2);

	return $interval;
};
function execInBackground($cmd){
    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r"));
    }else{
        exec($cmd . " > /dev/null &");
    }
};
function ajustaFacturas2($fechaI,$fechaF)
{
	global $codSuc,$conex;
	global $linkPDO;
$tabla_fac="fac_venta";
$sql="SELECT * FROM $tabla_fac WHERE   (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') AND nit=$codSuc  ";
//echo "$sql";
$rs=$linkPDO->query($sql);

while($row=$rs->fetch())
{


$num_fac=$row['num_fac_ven'];
$pre=$row['prefijo'];
totFacVen2($num_fac,$pre,$codSuc);
echo "FAC $pre $num_fac ajustada<br>";
}// fin while facturas

};
function ajusFacVenta()
{
global $codSuc,$conex,$NIT_PUBLICO_GENERAL;
global $linkPDO;
if(1){
$tabla_fac="fac_venta";
$tab_art="art_fac_ven";
$tab_serv="serv_fac_ven";
}else{
$tabla_fac="fac_remi";
$tab_art="art_fac_remi";
$tab_serv="serv_fac_remi";
}
$SUBTOT=0;
$IVA=0;
$TOT=0;
$num_fac=0;
$i=0;
$facturas[]=0;
$prefijos[]=0;

//$soloCredito=" AND tipo_venta='Credito'";
$filtroCreditos=" AND ( id_cli = '000 000 000' or id_cli = '$NIT_PUBLICO_GENERAL'  )";

$sql="SELECT * FROM $tabla_fac WHERE   (DATE(fecha)>='2019-08-05' AND DATE(fecha)<='2019-08-05') AND nit=$codSuc $filtroCreditos";
//echo "$sql";
$rs=$linkPDO->query($sql);

while($row=$rs->fetch())
{


$num_fac=$row['num_fac_ven'];
$pre=$row['prefijo'];
$facturas[$i]=$num_fac;
$prefijos[$i]=$pre;
$i++;
}// fin while facturas

$limit=count($facturas);

//echo $limit."<br>";

for($ii=0;$ii<$limit;$ii++)
{

$IVA=0;
$SUBTOT=0;
$TOT=0;
$num_fac=$facturas[$ii];
$pre=$prefijos[$ii];
//echo "$num_fac - $pre<br>";
$ivaSql="(1+(iva/100))";
$SQL="SELECT 'art16' as src,num_fac_ven,prefijo,IFNULL(ROUND(SUM(sub_tot/$ivaSql)),0) as sub_tot,IFNULL(ROUND(SUM(sub_tot-sub_tot/$ivaSql)),0) as iva, IFNULL(ROUND(SUM(sub_tot)),0) as tot FROM $tab_art WHERE num_fac_ven=$num_fac AND prefijo='$pre'  AND nit=$codSuc

UNION

SELECT 'serv' as src,num_fac_ven,prefijo,IFNULL(ROUND(SUM( (pvp/$ivaSql) )),0) as sub_tot,IFNULL(ROUND(SUM((pvp-pvp/$ivaSql) )),0) as iva,IFNULL(ROUND(SUM(pvp)),0) as tot FROM $tab_serv WHERE  num_fac_ven=$num_fac AND prefijo='$pre' AND cod_su=$codSuc;";
$rs=$linkPDO->query($SQL);
$entrega=0;
$canje=0;
while($row=$rs->fetch())
{
	$SUBTOT+=$row['sub_tot'];
	$IVA+=$row['iva'];
	$TOT+=$row['tot'];
	$entrega=ceil($TOT/10000)*10000;
	$canje=$entrega-$TOT;
	/*if($canje<0){
	$entrega=round($TOT/1000)*1000;
	$canje=$entrega-$TOT;
	}*/

}
set_time_limit(60);

$sql="UPDATE $tabla_fac SET tot=$TOT, iva=$IVA,sub_tot=$SUBTOT,entrega=$entrega,cambio=$canje WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$codSuc;";
echo $sql."";
$linkPDO->exec($sql);

}////// FIN for
};


function pucSelc()
{
	global $linkPDO;
	$sql="SELECT * FROM puc_cuentas";
	$rs=$linkPDO->query($sql);
	$resp="";
	while($row = $rs->fetch())
         {
		 $resp.= "<option value=\"$row[subcuenta] $row[des]\">$row[subcuenta] $row[des]</option>  ";
		 }
		 return  minify_html($resp);
};

function egreGeneralSelc()
{	global $linkPDO;
	$sql="SELECT * FROM x_opt_egresos";
	$rs=$linkPDO->query($sql);
	$resp="";
	while($row = $rs->fetch())
         {
		 $resp.= "<option value=\"$row[des]\">$row[des]</option>  ";
		 }
		 return  minify_html($resp);
};

function ajuste_frac($existNew,$uniNew,$frac,$ref){

$resp[1]=0;
$resp[2]=0;

$i=0;

if($frac!=0){
while($uniNew>= $frac){

if( $uniNew>=$frac ){
$uniNew=$uniNew - $frac;
$existNew=$existNew +1;

//echo "<li>WHILE 1 : uniNew= $uniNew- I[$i]-uniNew=$uniNew - $frac;</li>";

}//END IF;
$i++;
//if($i>10)break;
}//END WHILE;


$i=0;
while( $uniNew < 0)
{

if( $uniNew < 0)
{
$uniNew=$uniNew+$frac;
$existNew=$existNew-1;
//echo "<li>WHILE 2 : uniNew= $uniNew- I[$i]-uniNew=$uniNew - $frac;</li>";
//set_time_limit(60);

//echo "<li>WHILE 2 : uniNew= $uniNew</li>";
}//END IF;
$i++;
//if($i>1000)break;
}//end while

}//END IF;
$resp[1]=$existNew;
$resp[2]=$uniNew;

return $resp;
};
function ajuste_neg()
{
global $nomUsu,$id_Usu,$hoy,$conex,$codSuc,$linkPDO;
	$fecha=$hoy;
	$nom=limpiarcampo($nomUsu);
	$cc=limpiarcampo($id_Usu);


try {
$linkPDO->beginTransaction();
$all_query_ok=true;


$num_ajus=serial_ajustes($conex);

$sql="INSERT INTO ajustes(num_ajuste,fecha,cod_su,nom_usu,id_usu) VALUES($num_ajus,'$fecha',$codSuc,'$nom','$cc')";
$sqlLog="<ul><li>$sql</li>";
$linkPDO->exec($sql);

$sql="SELECT fraccion,gana,presentacion,exist,id_pro,id_inter,a.fecha_vencimiento,unidades_frac,costo,precio_v,detalle,iva FROM (SELECT fraccion,gana,a.presentacion,exist,a.id_pro,id_inter,fecha_vencimiento,unidades_frac,costo,precio_v,detalle,iva FROM `inv_inter` a INNER JOIN ".tabProductos." b ON a.id_pro=b.id_pro AND nit_scs='$codSuc' WHERE (exist<0 OR unidades_frac<0)) a";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{

		$cant=$row['exist'];
		$ref=limpiarcampo($row['id_pro']);
		$cod_bar=limpiarcampo($row['id_inter']);
		$presentacion=limpiarcampo($row['presentacion']);
		$des=limpiarcampo($row['detalle']);
		$costo=limpiarcampo($row['costo']);
		$iva=$row['iva'];
		$util=$row['gana'];
		$fechaVenci=$row['fecha_vencimiento'];
		$pvp=$row['precio_v'];
		$frac=$row['fraccion'];
		$uni=$row['unidades_frac'];

		$exist=$row["exist"];
		$uniExist=$row["unidades_frac"];

		$ajusteCant=(-1)*($exist);
		$ajusteUni=(-1)*($uniExist);

		$cant_saldo=0;
		$uniSaldo=0;

		$motivo="Ajuste Negativos, Ajuste a Cero (0)  $hoy";

$sql="INSERT INTO art_ajuste(num_ajuste,ref,des,cant,costo,precio,util,iva,cod_su,motivo,cant_saldo,cod_barras,presentacion,fraccion,unidades_fraccion,unidades_saldo,fecha_vencimiento)
VALUES($num_ajus,'$ref','$des',$ajusteCant,'$costo','$pvp','$util','$iva',$codSuc,'$motivo','$cant_saldo','$cod_bar','$presentacion','$frac','$ajusteUni','$uniSaldo','$fechaVenci')";
echo "<li>$sql</li>";
$linkPDO->exec($sql);

}/////////// FIN WHILE SET TO CERO


$sql="UPDATE `inv_inter` i
INNER JOIN
(select ar.cod_su nitAr,sum(cant) cant,ref,cod_barras,unidades_fraccion,fecha_vencimiento from art_ajuste ar inner join (select * from ajustes f WHERE num_ajuste=$num_ajus and cod_su='$codSuc' ) fv ON fv.num_ajuste=ar.num_ajuste WHERE fv.cod_su=ar.cod_su and fv.cod_su=$codSuc group by ar.ref,ar.fecha_vencimiento,ar.cod_barras) a
ON i.id_inter=a.cod_barras
SET i.exist=(i.exist+a.cant),i.unidades_frac=(i.unidades_frac+a.unidades_fraccion) WHERE i.nit_scs=a.nitAr and i.nit_scs=$codSuc AND i.fecha_vencimiento=a.fecha_vencimiento AND i.id_pro=a.ref";
$linkPDO->exec($sql);

echo "AJUSTE NEG. COMPLETADO";
$linkPDO->commit();

if($all_query_ok){

}
else{eco_alert("ERROR! Intente nuevamente");}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

};
function multiSelcQuery($requestName,$tableColName )
{
$var="";
$E="";
$c=0;
//print_r( $_REQUEST[$requestName]."<br>");

//if(!isset($sessionVar))$_SESSION[$sessionName]=$var;
if(isset($_REQUEST[$requestName]) && !empty($_REQUEST[$requestName]))
{$var=array_filter($_REQUEST[$requestName]); 
	$c=count($var);
}



//print_r( $var);

$cc=0;
//$var2=array_filter($var);
if($var)
{
	//echo "<br> entra";
	$cols=$_REQUEST[$requestName];
	//$_SESSION[$sessionName]=$var;
	$E=" AND (";
	foreach($cols as $key=> $resultado)
	{
		//echo "$resultado<br>";
		$resultado=limpiarcampo($resultado);
		if($resultado==-1){$E="";break;}
		if(empty($resultado))$cc++;


		$E.="$tableColName='$resultado' OR ";



	}
	if($resultado!=-1)$E.=" $tableColName='$resultado') ";
	if($c==$cc)$E="";

	}


	//echo "e: $E";
	return $E;
};


function tareas_pro($OS,$Urlphp,$UrlSS,$param1)
{
global $conex;
global $linkPDO;
//echo "$OS -->";
if($OS=="linux"){


$rs=$linkPDO->query("SELECT * FROM x_material_query WHERE seccion='Ajuste Kardex' AND cod_su=$param1 AND DATEDIFF(NOW(),last)>reset_days");
if($row=$rs->fetch()){
t1("UPDATE x_material_query SET last=NOW() WHERE seccion='Ajuste Kardex' AND cod_su=$param1");
$command = "/usr/bin/php5 -f /var/www/html/FIX_INV.php $param1";
exec( "$command > /dev/null &" );
}

$rs=$linkPDO->query("SELECT * FROM x_material_query WHERE seccion='Rotacion Inventario' AND cod_su=$param1 AND DATEDIFF(NOW(),last)>reset_days");
if($row=$rs->fetch()){
t1("UPDATE x_material_query SET last=NOW() WHERE seccion='Rotacion Inventario' AND cod_su=$param1");
$command = "/usr/bin/php5 -f /var/www/html/ajax/material_sql/rotacion_inventario.php $param1";
exec( "$command > /dev/null &" );
}


}




else if($OS=="win"){

//echo "$OS -->";
$rs=$linkPDO->query("SELECT * FROM x_material_query WHERE seccion='Ajuste Kardex' AND cod_su=$param1 AND DATEDIFF(NOW(),last)>reset_days");
if($row=$rs->fetch()){
$sql="UPDATE x_material_query SET last=NOW() WHERE seccion='Ajuste Kardex' AND cod_su=$param1";
t1($sql);
//echo "$sql";
$WshShell = new COM("WScript.Shell");
$oExec = $WshShell->Run("$Urlphp/php.exe $UrlSS/FIX_INV.php $param1", 0, false);
}









	}

};

function crear_any_form($tabla,$columnas,$tipoCol,$default_col,$funct_validate,$funct_success,$nombre_form,$botonBusq,$SelOpt=""){
global $linkPDO;
$boton_01="";
if(!empty($botonBusq)){

$boton_01="<button class=\" uk-button  uk-button-large\"  type=\"button\" name=\"boton_01\" id=\"boton_01\"   onclick=\"$botonBusq\"><i class=\"uk-icon-search\"></i> &nbsp;Buscar</button>";

};
$cols=explode(",",$columnas);
$maxCols=count($cols);
$colSet[]="";
for($i=0;$i<$maxCols;$i++){$colSet[$i]=$cols[$i];}

$sql="SELECT a.COLUMN_NAME, a.COLUMN_COMMENT FROM information_schema.COLUMNS a WHERE a.TABLE_NAME =  '$tabla' AND a.COLUMN_COMMENT !=  ''";
$rs=$linkPDO->query($sql);
$LABEL_INPUT[]="";

while($row=$rs->fetch()){$LABEL_INPUT[$row["COLUMN_NAME"]]=$row["COLUMN_COMMENT"];}

$max=count($colSet);
$HTML="<div id=\"add_any\" class=\"uk-modal\">
<div class=\"uk-modal-dialog\">
<a class=\"uk-modal-close uk-close\"></a>

<h1 class=\"uk-text-primary uk-text-bold\">$nombre_form</h1>
<form class=\"uk-form uk-form-stacked\" id=\"frm_any\" name=\"frm_any\" action=\"javascript:save_any2(document.forms['frm_any'],$funct_validate,$funct_success);\">
<fieldset data-uk-margin>";
for($i=0;$i<$max;$i++){

	$HIDDEN="";
	if($tipoCol[$i]=="hidden"){$HIDDEN=" uk-hidden";}
	else{$HIDDEN="";}

	if($tipoCol[$i]!="select"){
		$HTML.=" <div class=\"uk-form-row\">
        <label class=\"uk-form-label $HIDDEN\" for=\"f$i\">".$LABEL_INPUT[$colSet[$i]]."</label>
        <input name=\"c$i\" id=\"f$i\" type=\"".$tipoCol[$i]."\" placeholder=\"\" value=\"$default_col[$i]\" >
		</div>";
	}
	else {
			$HTML.=" <div class=\"uk-form-row\">
			<label class=\"uk-form-label $HIDDEN\" for=\"f$i\">".$LABEL_INPUT[$colSet[$i]]."</label>
			<select name=\"c$i\" id=\"f$i\" required>".$SelOpt[$cols[$i]]."</select>
			</div>";
	}
}

$HTML.="
<div class=\"uk-form-row\">
     	$boton_01
		<input class=\" uk-button uk-button-primary uk-button-large\"  type=\"submit\" name=\"save\" id=\"save\" value=\"Guardar\">

        <input type=\"hidden\" name=\"numF\" id=\"numF\" value=\"$maxCols\">
        <input type=\"hidden\" name=\"Colset\" id=\"Colset\" value=\"$columnas\">
        <input type=\"hidden\" name=\"tab\" id=\"tab\" value=\"$tabla\">
        </div>
</fieldset>
</form>
</div>
</div>";

echo $HTML;
};

function lim_placa($placa){

	$resp=preg_replace("/\W|_/", "", $placa);
	return $resp;

	};

function tot_dev_ventas($fechaI,$fechaF,$codSuc,$horaI,$horaF,$CodCajero,$ventas_brutas=0,$RESOL=0,$PREFIJO="",$RANGO="")
{
global $MODULES;
global $linkPDO;
$filtroResol="";
$filtroCerradas=" AND anulado='CERRADA'";
$filtroNOanuladas="AND ( (anulado!='ANULADO' $filtroCerradas) OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) ";
if($ventas_brutas==1)$filtroNOanuladas=" AND anulado!='ANULADO' ";
$filtroSEDE_art="AND art_devolucion_venta.nit=$codSuc";
$filtroSEDE_serv="AND b.cod_su=$codSuc";

if($RESOL!=0)$filtroResol=" AND (fac_dev_venta.resolucion='$RESOL' AND fac_dev_venta.prefijo='$PREFIJO' AND fac_dev_venta.rango_resol='$RANGO') ";

if($codSuc=="all")
{
$filtroSEDE_art="";
$filtroSEDE_serv="";
}
$filtroHora="";
$filtroHoraAnula="";
if(!empty($horaI) && !empty($horaF))$filtroHora=" AND (fecha>='$fechaI $horaI' AND fecha<='$fechaF $horaF')";
if(!empty($horaI) && !empty($horaF))$filtroHoraAnula=" AND (fecha_anula>='$fechaI $horaI' AND fecha_anula<='$fechaF $horaF')";
$filtroCaja="";
if(!empty($CodCajero))$filtroCaja=" AND cod_caja=$CodCajero ";
$resp[][]=0;

//echo "filtro Resol $filtroResol AND (resolucion='$RESOL' AND prefijo='$PREFIJO' AND rango_resol='$RANGO')";

$cols="tipo_venta,cod_barras,nom_cli,art_devolucion_venta.num_fac_ven, precio,des,art_devolucion_venta.sub_tot,art_devolucion_venta.iva,cant,unidades_fraccion,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,fac_dev_venta.prefijo,art_devolucion_venta.prefijo,cod_caja,resolucion,rango_resol";

$sql="SELECT $cols  FROM fac_dev_venta INNER JOIN art_devolucion_venta ON fac_dev_venta.num_fac_ven=art_devolucion_venta.num_fac_ven WHERE fac_dev_venta.prefijo=art_devolucion_venta.prefijo AND fac_dev_venta.nit=art_devolucion_venta.nit $filtroSEDE_art $filtroCaja    AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas $filtroResol ";
//echo "$sql";
$rs=$linkPDO->query($sql );
$base5=0;
$iva5=0;
$tot5=0;

$base10=0;
$iva10=0;
$tot10=0;

$base16=0;
$iva16=0;
$tot16=0;

$base19=0;
$iva19=0;
$tot19=0;
$excentas=0;
$TOT=0;
$i=0;
$tot_tarjetaDeb=0;
$tot_contado=0;
$tot_Credito=0;
$tot_tarjetaCre=0;
$tot_cheque=0;
while($row=$rs->fetch())
{

$i++;
	$num_fac=$row['num_fac_ven'];
	$subTot=$row['sub_tot']*1;
	$IVA_art=$row['iva']*1;
	$des=$row['des'];
	$cant=$row['cant']*1;
	$uni=$row['unidades_fraccion']*1;
	$valor=$row['precio']*1;
	$ref=$row['cod_barras'];
	$vendedor=ucwords(strtolower($row["vendedor"]));
	$HORA=$row['hora'];
	$fecha=$row['fe'];
	$tipo_venta=$row['tipo_venta'];
	$tipoCli=$row['tipo_cli'];
	$nomCli=$row['nom_cli'];
	$formaPago=$row['tipo_venta'];
	if($formaPago=="Contado")$tot_contado+=$row['sub_tot'];
	else if($formaPago=="Credito")$tot_Credito+=$row['sub_tot'];
	else if($formaPago=="Tarjeta Debito")$tot_tarjetaDeb+=$row['sub_tot'];
	else if($formaPago=="Tarjeta Credito")$tot_tarjetaCre+=$row['sub_tot'];
	else if($formaPago=="Cheque")$tot_cheque+=$row['sub_tot'];
	if($IVA_art==0)$excentas+=$row['sub_tot']*1;

	if($IVA_art==19)
	{
		$base19+=round(($row['sub_tot']*1)/1.19);
		$iva19+=$row['sub_tot']*1-round(($row['sub_tot']*1)/1.19);
		$tot19=$row['sub_tot']*1;
		}

		if($IVA_art==16)
	{
		$base16+=round(($row['sub_tot']*1)/1.16);
		$iva16+=$row['sub_tot']*1-round(($row['sub_tot']*1)/1.16);
		$tot16=$row['sub_tot']*1;
		}

		if($IVA_art==10)
	{
		$base10+=round(($row['sub_tot']*1)/1.1);
		$iva10+=$row['sub_tot']*1-round(($row['sub_tot']*1)/1.1);
		$tot10=$row['sub_tot']*1;
		}


	if($IVA_art==5)
	{
		$base5+=round(($row['sub_tot']*1)/1.05);
		$iva5+=$row['sub_tot']*1-round(($row['sub_tot']*1)/1.05);
		$tot5=$row['sub_tot']*1;
		}
	$TOT+=$row['sub_tot']*1;
}


$resp[0][0]=$excentas;

$resp[5][1]=$base5;
$resp[5][2]=$base5*0.05;
$resp[5][3]=$tot5;

$resp[10][1]=$base10;
$resp[10][2]=$base10*0.10;
$resp[10][3]=$tot10;

$resp[16][1]=$base16;
$resp[16][2]=$base16*0.16;
$resp[16][3]=$tot16;

$resp[19][1]=$base19;
$resp[19][2]=$base19*0.19;
$resp[19][3]=$tot19;




$resp[1][1]=$TOT;

$resp["fp"][1]=$tot_contado;
$resp["fp"][2]=$tot_Credito;
$resp["fp"][11]=$tot_tarjetaCre;
$resp["fp"][12]=$tot_tarjetaDeb;
$resp["fp"][13]=$tot_cheque;
return $resp;
};


function tot_utilidad($AfiltroFecha,$BfiltroCliente,$CfiltroFabricante,$EfiltroCiudad,$FfiltroCli2,$filtroFormaPago,$codSuc)
{
global $CHAR_SET;
global $linkPDO;
$id_clientes[]="";
$nom_clientes[]="";
$abonos[][]=0;
$UTIL[]=0;
$TOT_UTIL=0;

$tot_saldo=0;
$tot_abono=0;
$tot_util=0;
$tot_venta_sin=0;

$TOT_COSTO_SIN[]=array();
$TOT_COSTO_IVA[]=array();
$TOT_VENTAS_COSTO_IVA=0;
$TOT_VENTAS_PVP=0;
$TOT_VENTAS_SERVICIOS=0;

$RESP[]=array();
$sql ="SELECT * from fac_venta 
       WHERE nit=$codSuc AND ".VALIDACION_VENTA_VALIDA."
	   $filtroFormaPago $BfiltroCliente $AfiltroFecha $EfiltroCiudad $FfiltroCli2 GROUP BY id_cli order by 3";
$rs=$linkPDO->query($sql);
$i=0;
while($row=$rs->fetch())
{
	$id_clientes[$i]=ucwords(strtolower(htmlentities($row["id_cli"], ENT_QUOTES,"$CHAR_SET")));
	//$nom_clientes[$i]=ucwords(strtolower(htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET")));
	$nom_clientes[$i]=ucwords(strtolower($row["nom_cli"]));
	$UTIL[$row["id_cli"]]=0;
	$TOT_COSTO_SIN[$row["id_cli"]]=0;
	$TOT_COSTO_IVA[$row["id_cli"]]=0;

	$abonos[$row['prefijo']][$row['num_fac_ven']]=0;

	$i++;

}
$subQuery = "SELECT nit,
                    ref, 
					cod_barras, 
					prefijo, 
					num_fac_ven, 
					des, 
					iva, 
					precio, 
					sub_tot, 
					b.fab, 
					cant, 
					fraccion, 
					unidades_fraccion, 
					dcto, 
					costo,
					b.id_clase,
					id_sub_clase,
					nit_proveedor
            FROM art_fac_ven a
            INNER JOIN productos b ON a.ref = b.id_pro"; 

$sql="SELECT b.anulado,
             b.ciudad,b.nit, 
			 b.id_cli,
			 b.nom_cli,
			 a.ref,
			 a.des,
			 a.cant,
			 a.fraccion,
			 a.unidades_fraccion,a.iva as iva_art,
			 a.precio,a.sub_tot as  art_sub,
			 a.dcto,a.costo,
			 b.num_fac_ven,b.prefijo,
			 b.sub_tot,b.iva,b.tot,
			 DATE(b.fecha) as fecha,
			 b.descuento,b.vendedor 
        FROM art_fac_ven a INNER JOIN fac_venta b 
        ON a.num_fac_ven=b.num_fac_ven  AND a.prefijo=b.prefijo AND b.nit=a.nit 
        AND b.nit=$codSuc AND ".VALIDACION_VENTA_VALIDA." $filtroFormaPago  $AfiltroFecha $CfiltroFabricante $EfiltroCiudad $BfiltroCliente $FfiltroCli2";



$rs=$linkPDO->query($sql);

//echo "$sql";
while($row=$rs->fetch()){
	
	$idCli=$row['id_cli'];
	
	
	
	$cant = $row["cant"]*1;
	$frac=$row['fraccion'];
	$uni=$row['unidades_fraccion'];
	if($frac==0){$frac=1;}
	//$factor=($uni+($cant*$frac))/$frac;
	$factor=(($uni/$frac)+$cant);
	$pvp = $row["precio"]*1;
	$iva=$row['iva_art'];
	$costo=$row['costo']*1;
	$costoIVA=$costo*(1+($iva/100));
	
	$utilidad=util($pvp*$factor,$costo*$factor,$iva,"tot");
	
	$UTIL[$idCli]+=$utilidad;
	
	$TOT_UTIL+=$utilidad;
	$TOT_VENTAS_COSTO_IVA+=$costoIVA*$factor;
	$TOT_VENTAS_PVP+=$pvp*$factor;
	
	$TOT_COSTO_IVA[$idCli]+=$costoIVA*$factor;
	$TOT_COSTO_SIN[$idCli]+=$costo*$factor;
}///////////////////////////////////// FIN WHILE UTILIDADES /////////////////////////////////////////////////////////////////////////////////////

$sql ="SELECT b.serv, b.iva, b.pvp, b.nota
FROM fac_venta a 
INNER JOIN serv_fac_ven b
ON  (a.num_fac_ven=b.num_fac_ven) 
AND (a.prefijo=b.prefijo) 
AND (a.nit=b.cod_su AND a.nit=$codSuc) 
AND a.".VALIDACION_VENTA_VALIDA." $filtroFormaPago  $AfiltroFecha ";
$rs=$linkPDO->query($sql);

//echo "$sql";
while($row=$rs->fetch()){
	
	$pvp = $row["pvp"]*1;
	$iva=$row['iva'];
	$costo= $row['pvp'] / (1+($iva/100));
	$costoIVA=$costo*(1+($iva/100));
	
	$TOT_VENTAS_SERVICIOS+=$costo;
		
}

$RESP["UTIL"]=$UTIL;
$RESP["TOT_VENTAS_COSTO_IVA"]=$TOT_VENTAS_COSTO_IVA;
$RESP["TOT_VENTAS_PVP"]=$TOT_VENTAS_PVP;
$RESP["TOT_VENTAS_SERVICIOS"]=$TOT_VENTAS_SERVICIOS;
$RESP["TOT_COSTO_IVA"]=$TOT_COSTO_IVA;
$RESP["TOT_COSTO_SIN"]=$TOT_COSTO_SIN;
$RESP["TOT_UTIL"]=$TOT_UTIL;

return $RESP;

};

function add_sede($codSuc)
{
global $conex;

$seriales="INSERT INTO `seriales` (`seccion`, `serial_inf`, `serial_sup`, `nit_sede`) VALUES
('factura venta', 1, 1000, $codSuc),
('comprobante ingreso', 1, 10000, $codSuc),
('factura taller', 1, 10000, $codSuc),
('factura compra', 1, 40000, $codSuc),
('factura dev', 1, 10000000, $codSuc),
('credito', 1, 1500, $codSuc),
('traslado', 1, 100000, $codSuc),
('expedientes', 1, 100000, $codSuc),
('comprobante anticipo', 1, 100000, $codSuc),
('ajustes', 1, 10000, $codSuc),
('Inventario Inicial', 1, 10000, $codSuc),
('remision', 2001, 100000, $codSuc),
('remision_com', 2001, 100000, $codSuc),
('fac_dev_ven', 1, 50000, $codSuc),
('comp_egreso', 1, 10000, $codSuc),
('ref', 1, 100000, $codSuc),
('resol_papel', 1, 1000, $codSuc),
('cartera_ant', 10014, 1000000, $codSuc),
('remision_com2', 1, 100000, $codSuc);";

$sede="INSERT INTO `sucursal` (`cod_su`, 
                              `nombre_su`, 
							  `dir_su`, 
							  `tel1`,
							  `tel2`, 
							  `email_su`, 
							  `representante_se`, 
							  `id_dep`, 
							  `id_mun`, 
							  `alas`, 
							  `cod_contado`, 
							  `cod_credito`, 
							  `cod_papel`, 
							  `resol_contado`, 
							  `fecha_resol_contado`, 
							  `resol_credito`, 
							  `fecha_resol_credito`, 
							  `rango_contado`, 
							  `rango_credito`, 
							  `resol_papel`, 
							  `fecha_resol_papel`, 
							  `rango_papel`, 
							  `resol_credito_ant`, 
							  `fecha_resol_credito_ant`, 
							  `rango_credito_ant`, 
							  `cod_credito_ant`, 
							  `precio_bsf`, 
							  `cod_remi_pos`, 
							  `resol_remi_pos`, 
							  `fecha_remi_pos`, 
							  `rango_remi_pos`, 
							  `cod_remi_com`, 
							  `resol_remi_com`, 
							  `fecha_remi_com`, 
							  `rango_remi_com`, 
							  `cod_remi_com2`, 
							  `resol_remi_com2`, 
							  `fecha_remi_com2`, 
							  `rango_remi_com2`, 
							  `licencia_key`, 
							  `nom_negocio`, 
							  `nit_negocio`, 
							  `id_responsable`, 
							  `placa_vehiculo`) 
					 VALUES ('$codSuc', 
					         'SUCURSAL $codSuc', 
							 'DIRECCION ', 
							 'TELEFONOS', 
							 '', 
							 '', 
							 'EM', 
							 '1', 
							 '2', 
							 '0', 
							 '- - -', 
							 '----', 
							 'PPL', 
							 '340000007451', 
							 '2024-01-02', 
							 '27022017', 
							 '2024-02-22', 
							 '(0001 - 3000)', 
							 '(1 - 5000)', 
							 '340000007451', 
							 '2024-02-27', 
							 '(0001 - 3000)', 
							 '11001000', 
							 '2024-01-01', 
							 '(1 - 10000)', 
							 'CRE', 
							 '0.000000', 
							 'REMI', 
							 '1000100', 
							 '0000-00-00', 
							 '(1 - 100000)', 
							 'RCOM', 
							 '1000200', 
							 '0000-00-00', 
							 '(1 - 100000)', 
							 'RE2', 
							 '18000002', 
							 '2017-07-10', 
							 '(1 - 100.000)', 
							 '', 
							 'NOMBRE EMPRESA', 
							 'NIT RESPONSABLE', 
							 '', 
							 '');";


echo "<li>$seriales</li>";
echo "<li>$sede</li>";
t2($seriales,$sede);

	eco_alert("SEDE $codSuc Creada!");
};
function tot_nomina_motos($fechaI,$fechaF,$codSuc,$horaI,$horaF,$CodCajero,$Clase,$fabricante)
{
	global $linkPDO;
$filtroCerradas=" AND anulado='CERRADA'";
$filtroNOanuladas="AND ( (".VALIDACION_VENTA_VALIDA." $filtroCerradas) OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) ";
$filtroHora="";
$filtroHoraAnula="";
if(!empty($horaI) && !empty($horaF))$filtroHora=" AND (fecha>='$fechaI $horaI' AND fecha<='$fechaF $horaF')";
if(!empty($horaI) && !empty($horaF))$filtroHoraAnula=" AND (fecha_anula>='$fechaI $horaI' AND fecha_anula<='$fechaF $horaF')";
$filtroCaja="";
if(!empty($CodCajero))$filtroCaja=" AND cod_caja=$CodCajero ";
$filtroClase=SQLfiltro_busquedaCampo_multiOpcion("id_clase",$Clase);

$filtroFab=SQLfiltro_busquedaCampo_multiOpcion("fab",$fabricante);

$total_vendedores["VENTAS MOSTRADOR"]="";
$lista_vendedores[]="";
$rs=$linkPDO->query("SELECT vendedor from fac_venta     GROUP BY vendedor");
while($row=$rs->fetch())
{
     $lista_vendedores[ucwords(strtolower($row["vendedor"]))]=ucwords(strtolower($row["vendedor"]));
	$total_vendedores[ucwords(strtolower($row["vendedor"]))]=0;
}


$cols="(ROUND(b.sub_tot/(1+ b.iva/100),0)) sub_tot,b.id_clase,b.fab,vendedor,cod_caja,tot";
$sql="SELECT $cols FROM fac_venta a INNER JOIN (SELECT sub_tot,id_clase,fab,cod_barras,iva,costo,cant,fraccion,prefijo,num_fac_ven,nit,unidades_fraccion FROM  art_fac_ven a INNER JOIN ".tabProductos." b ON a.ref=b.id_pro) b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND a.nit=b.nit   $filtroCaja $filtroClase $filtroFab   AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas";
//echo "$sql";
$rs=$linkPDO->query($sql );
$base5=0;
$iva5=0;
$tot5=0;
$base16=0;
$iva16=0;
$tot16=0;
$excentas=0;
$TOT=0;
$i=0;
while($row=$rs->fetch())
{
$i++;

	$subTot=$row['tot']*1;
	$vendedor=ucwords(strtolower($row["vendedor"]));
        if(in_array($vendedor,$lista_vendedores)){
        $total_vendedores[$vendedor]+=$row['tot'];

        }else {
            $total_vendedores["VENTAS MOSTRADOR"]+=$row['tot'];

        }

}
return $total_vendedores;
};
function tot_nomina_cajas($fechaI,$fechaF,$codSuc,$horaI,$horaF,$CodCajero,$Clase,$fabricante,$TIPO_INF)
{
	global $linkPDO;
$filtroCerradas=" AND anulado='CERRADA'";
$filtroNOanuladas="AND ".VALIDACION_VENTA_VALIDA." ";
if($TIPO_INF=="B"){$filtroNOanuladas="AND ".VALIDACION_VENTA_VALIDA." AND tipo_venta!='Credito'";}
if($TIPO_INF=="C"){$filtroNOanuladas="AND ".VALIDACION_VENTA_VALIDA." AND tipo_venta='Credito'";}
$filtroHora="";
$filtroHoraAnula="";
if(!empty($horaI) && !empty($horaF))$filtroHora=" AND (fecha>='$fechaI $horaI' AND fecha<='$fechaF $horaF')";
if(!empty($horaI) && !empty($horaF))$filtroHoraAnula=" AND (fecha_anula>='$fechaI $horaI' AND fecha_anula<='$fechaF $horaF')";
$filtroCaja="";
if(!empty($CodCajero))$filtroCaja=" AND cod_caja=$CodCajero ";

$filtroClase=SQLfiltro_busquedaCampo_multiOpcion("id_clase",$Clase);

$filtroFab=SQLfiltro_busquedaCampo_multiOpcion("fab",$fabricante);

$total_vendedores["VENTAS MOSTRADOR"]="";
$lista_vendedores[]="";
$rs=$linkPDO->query("SELECT vendedor from fac_venta WHERE nit=$codSuc GROUP BY vendedor");
while($row=$rs->fetch())
{
	$nomVende=nomTrim($row["vendedor"]);
    $lista_vendedores[$nomVende]=$nomVende;
	$total_vendedores[$nomVende]=0;
}
$cols="(ROUND(b.sub_tot/(1+ b.iva/100),0)) sub_tot,b.id_clase,b.fab,vendedor,cod_caja";
$sql="SELECT $cols FROM fac_venta a INNER JOIN (SELECT sub_tot,id_clase,fab,cod_barras,iva,costo,cant,fraccion,prefijo,num_fac_ven,nit,unidades_fraccion FROM  art_fac_ven a INNER JOIN ".tabProductos." b ON a.ref=b.id_pro) b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND a.nit=b.nit AND b.nit=$codSuc $filtroCaja $filtroClase $filtroFab   AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas";
//echo "$sql";
$rs=$linkPDO->query($sql );
$base5=0;
$iva5=0;
$tot5=0;
$base16=0;
$iva16=0;
$tot16=0;
$excentas=0;
$TOT=0;
$i=0;
while($row=$rs->fetch())
{
$i++;

	$subTot=$row['sub_tot']*1;
	$vendedor=nomTrim($row["vendedor"]);
        if(in_array($vendedor,$lista_vendedores)){
        $total_vendedores[$vendedor]+=$row['sub_tot'];

        }else {
            $total_vendedores["VENTAS MOSTRADOR"]+=$row['sub_tot'];

        }

}
return $total_vendedores;
};
function addHash()
{
	global $linkPDO;
	$sql="SELECT * FROM fac_venta";


	$rs=$linkPDO->query($sql);

	while($row=$rs->fetch()){
	$nit = $row["nit"];
	$num_fac=$row["num_fac_ven"];
	$pre=$row["prefijo"];
	$linkPDO->exec("UPDATE art_fac_ven   SET hash=b.hash WHERE  num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$nit");
	}
};
function ajusta_kardex_all($codSuc,$filter,$offset=0,$limit=20000)
{

global $conex,$usar_fecha_vencimiento;
global $linkPDO;
try{

$LIMIT_SQL=" LIMIT $offset, $limit";
//$LIMIT_SQL=" ";

$extraFilter=" AND id_inter='7702027416859'";
$extraFilter="";

ini_set('memory_limit', '2048M');
$filtros=" ";
switch($filter){

	case 1 :$filtros="AND (exist>0 OR unidades_frac>0)"; break;
	case 0 :$filtros="  $extraFilter"; break;

}
$saldo[][][]=0;
$REF_LIST[]="";

if($offset==0){

	$s="UPDATE inv_inter SET exist=0, unidades_frac=0 WHERE nit_scs=$codSuc $filtros";
	echo "<li>$s </li>";
	$linkPDO->exec($s);
}
set_time_limit(60);
$sql="SELECT *  FROM inv_inter WHERE nit_scs=$codSuc $filtros $LIMIT_SQL";
echo "<li>$sql </li>";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{


$ref=$row['id_pro'];
$cod=$row['id_inter'];
$fe=$row['fecha_vencimiento'];
if($usar_fecha_vencimiento==0){$fe="0000-00-00";}

$REF_LIST[$ref]=$ref;
$saldo[$ref][$cod][$fe][1]=0;
$saldo[$ref][$cod][$fe][2]=0;

}


$iii=0;
$sql="SELECT *  FROM inv_inter WHERE nit_scs=$codSuc $filtros $LIMIT_SQL";
$rsm=$linkPDO->query($sql);
echo "<li>$sql</li>";
while($rowm=$rsm->fetch())
{

set_time_limit(60);
$iii++;
$REF=$rowm["id_pro"];
if(in_array($REF,$REF_LIST,TRUE)){
$COD_BAR=$rowm["id_inter"];
$FE_VEN=$rowm["fecha_vencimiento"];
if($usar_fecha_vencimiento==0){$FE_VEN="0000-00-00";}
$FRAC=1;
$sql=q_kardex($REF,$COD_BAR,$FE_VEN);


//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);

//if($REF=="10975"){echo "$sql";}

$fuente=array(1=>"Compra","Venta","Remision","ANULADO","Envio Traslado","Recibo Traslado","Devolucion","Ajuste Inventario","Devolucion Venta","ANULA Devolucion Venta","Remision Facturada","Compra ANULADA");
//                12

//               1   2   3   4   5   6   7   8   9   10  11  12
$signo=array(1=>"+","-","-","~","-","+","-","+","+","~","~","~");
$UPDATE="";
while($row=$rs->fetch())
{
	$ref=$row['ref'];

	$num_fac=$row['num'];
	$subTot=money($row['tot']);
	$IVA=money($row['iva']);
	//$des=htmlentities($row['des'], ENT_QUOTES,"$CHAR_SET");
	$des=$row['des'];
	$cant=$row['cant']*1;
	$uni=$row["unidades_fraccion"];
	$FRAC=$row["fraccion"];
	if($FRAC<=0){$FRAC=1;}
	$valor=money($row['precio']);


$cod=$row['cod_barras'];
$fe=$row['fecha_vencimiento'];
if($usar_fecha_vencimiento==0){$fe="0000-00-00";}
	//$vendedor=htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET");
	//$HORA=$row['hora'];
	$fecha=$row['fecha'];
	$src=$row['src'];
	if($signo[$src]=="+"){
		$saldo[$ref][$cod][$fe][1]+=$cant;
		$saldo[$ref][$cod][$fe][2]+=$uni;
	}
	else if($signo[$src]=="-"){
		$saldo[$ref][$cod][$fe][1]-=$cant;
		$saldo[$ref][$cod][$fe][2]-=$uni;
	}

if(!empty($extraFilter))
{
echo "<li> $ref|$cod|$fe".$signo[$src]." $cant;$uni saldo:".$saldo[$ref][$cod][$fe][1]."; ".$saldo[$ref][$cod][$fe][2]."</li>";
}

}
$saldoFrac=ajuste_frac($saldo[$REF][$COD_BAR][$FE_VEN][1],$saldo[$REF][$COD_BAR][$FE_VEN][2],$FRAC,$REF);
$UPDATE="UPDATE inv_inter SET exist='$saldoFrac[1]', unidades_frac=$saldoFrac[2] 
WHERE id_pro='$REF' AND id_inter='$COD_BAR' AND fecha_vencimiento='$FE_VEN' AND nit_scs=$codSuc;";

if($usar_fecha_vencimiento==0){
$UPDATE="UPDATE inv_inter SET exist='$saldoFrac[1]', 
        unidades_frac=$saldoFrac[2] 
         WHERE id_pro='$REF' AND id_inter='$COD_BAR'  AND nit_scs=$codSuc;";
}

if(!empty($extraFilter)){echo "<br>$UPDATE";}
t1($UPDATE);

}

}
t1("UPDATE x_material_query SET state=CONCAT('Done At: ',NOW()),last=NOW() WHERE id=2 AND cod_su=$codSuc;");
echo "<li>UPDATE x_material_query SET state=CONCAT('Done At: ',NOW()),last=NOW() WHERE id=2 AND cod_su=$codSuc;</li>";
}catch(Throwable $e){
	echo "Failed: " . $e->getMessage();

}
};
function ajusta_kardex_ref($codSuc,$ref,$cod,$fe)
{
	global $conex,$usar_fecha_vencimiento;
	global $linkPDO;
	if($usar_fecha_vencimiento==0){$fe="0000-00-00";}
	$saldo[][][]=0;
	$REF_LIST[]="";
	if($usar_fecha_vencimiento==0){
		$s="UPDATE inv_inter SET exist=0, unidades_frac=0 WHERE nit_scs=$codSuc AND id_pro='$ref' AND id_inter='$cod'";
	}
	else
	{$s="UPDATE inv_inter SET exist=0, unidades_frac=0 WHERE nit_scs=$codSuc AND id_pro='$ref' AND id_inter='$cod' AND fecha_vencimiento='$fe'";}

	$linkPDO->exec($s);
	$REF_LIST[$ref]=$ref;
	$saldo[$ref][$cod][$fe][1]=0;
	$saldo[$ref][$cod][$fe][2]=0;


	$iii=0;
	if($usar_fecha_vencimiento==0){$rsm=$linkPDO->query("SELECT *  FROM inv_inter WHERE nit_scs=$codSuc AND id_pro='$ref' AND id_inter='$cod'");}
	else {$rsm=$linkPDO->query("SELECT *  FROM inv_inter WHERE nit_scs=$codSuc AND id_pro='$ref' AND id_inter='$cod' AND fecha_vencimiento='$fe'");}
	while($rowm=$rsm->fetch())
	{
		set_time_limit(60);
		$iii++;
		$REF=$rowm["id_pro"];
		if(in_array($REF,$REF_LIST,TRUE)){
		$COD_BAR=$rowm["id_inter"];
		$FE_VEN=$rowm["fecha_vencimiento"];
		if($usar_fecha_vencimiento==0){$FE_VEN="0000-00-00";}
		$FRAC=1;
		$sql=q_kardex($REF,$COD_BAR,$FE_VEN);
		//echo $sql;
		$rs=$linkPDO->query($sql);

		//if($REF=="10975"){echo "$sql";}

		$fuente=array(1=>"Compra","Venta","Remision","ANULADO","Envio Traslado","Recibo Traslado","Devolucion","Ajuste Inventario","Devolucion Venta","ANULA Devolucion Venta","Remision Facturada","Compra ANULADA");
	//                12

	//               1   2   3   4   5   6   7   8   9   10  11  12
		$signo=array(1=>"+","-","-","~","-","+","-","+","+","~","~","~");
		while($row=$rs->fetch())
		{
			$ref=$row['ref'];

			$num_fac=$row['num'];
			$subTot=money($row['tot']);
			$IVA=money($row['iva']);
			//$des=htmlentities($row['des'], ENT_QUOTES,"$CHAR_SET");
			$des=$row['des'];
			$cant=$row['cant']*1;
			$uni=$row["unidades_fraccion"];
			$FRAC=$row["fraccion"];
			if($FRAC<=0){$FRAC=1;}
			$valor=money($row['precio']);


		$cod=$row['cod_barras'];
		$fe=$row['fecha_vencimiento'];
		if($usar_fecha_vencimiento==0){$fe="0000-00-00";}
			//$vendedor=htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET");
			//$HORA=$row['hora'];
			$fecha=$row['fecha'];
			$src=$row['src'];
			if($signo[$src]=="+"){$saldo[$ref][$cod][$fe][1]+=$cant;$saldo[$ref][$cod][$fe][2]+=$uni;}
			else if($signo[$src]=="-"){$saldo[$ref][$cod][$fe][1]-=$cant;$saldo[$ref][$cod][$fe][2]-=$uni;}


		}
		$saldoFrac=ajuste_frac($saldo[$REF][$COD_BAR][$FE_VEN][1],$saldo[$REF][$COD_BAR][$FE_VEN][2],$FRAC,$REF);
		$UPDATE="UPDATE inv_inter SET exist='$saldoFrac[1]', unidades_frac=$saldoFrac[2] WHERE id_pro='$REF' AND id_inter='$COD_BAR' AND fecha_vencimiento='$FE_VEN' AND nit_scs=$codSuc";
		if($usar_fecha_vencimiento==0){	
			$UPDATE="UPDATE inv_inter SET exist='$saldoFrac[1]', unidades_frac=$saldoFrac[2] WHERE id_pro='$REF' AND id_inter='$COD_BAR'  AND nit_scs=$codSuc";
		}
		t1($UPDATE);

	}

	}


};
function valida_kardex_all($codSuc,$offset=0,$limit=20000)
{
	global $conex,$usar_fecha_vencimiento;
	global $linkPDO;
try{
ini_set('memory_limit', '2048M');
$saldo[][][]=0;
$saldo2[][][]=0;
$REF_LIST[]="";
echo "<table border=\"1\" rules=\"all\"><thead><td>PRODUCTO</td><td>REF</td><td>COD</td><td>FechaVen</td><td>CANT SYS</td><td>CANT CORRECTA</td></thead><tbody>";

$LIMIT_SQL=" LIMIT $offset, $limit";
$sql="SELECT *  FROM inv_inter WHERE nit_scs=$codSuc $LIMIT_SQL";
$rs=$linkPDO->query("$sql");
while($row=$rs->fetch())
{

$ref=$row['id_pro'];
$cod=$row['id_inter'];
$fe=$row['fecha_vencimiento'];
$cantidades=$row["exist"];
$fracciones=$row["unidades_frac"];
if($usar_fecha_vencimiento==0){$fe="0000-00-00";}

$REF_LIST[$ref]=$ref;
$saldo[$ref][$cod][$fe][1]=0;
$saldo[$ref][$cod][$fe][2]=0;

$saldo2[$ref][$cod][$fe][1]=$cantidades;
$saldo2[$ref][$cod][$fe][2]=$fracciones;

}


$iii=0;
$sql="SELECT *  FROM inv_inter WHERE nit_scs=$codSuc $LIMIT_SQL";
echo "<li>$sql</li>";
$rsm=$linkPDO->query($sql);
while($rowm=$rsm->fetch())
{
	//set_time_limit(60);
	$iii++;
$REF=$rowm["id_pro"];
if(in_array($REF,$REF_LIST,TRUE)){
$COD_BAR=$rowm["id_inter"];
$FE_VEN=$rowm["fecha_vencimiento"];
if($usar_fecha_vencimiento==0){$FE_VEN="0000-00-00";}
$FRAC=1;
$sql=q_kardex($REF,$COD_BAR,$FE_VEN);
//echo $sql;
$rs=$linkPDO->query($sql);

//if($REF=="10975"){echo "$sql";}

$fuente=array(1=>"Compra", //1
                 "Venta", //2
				 "Remision",
				 "ANULADO",
				 "Envio Traslado",
				 "Recibo Traslado",
				 "Devolucion",
				 "Ajuste Inventario",
				 "Devolucion Venta",
				 "ANULA Devolucion Venta",//10
				 "Remision Facturada",//11
				 "Compra ANULADA"); //12


//               1   2   3   4   5   6   7   8   9   10  11  12
$signo=array(1=>"+","-","-","~","-","+","-","+","+","~","~","~");

while($row=$rs->fetch())
{
	$ref=$row['ref'];

	$num_fac=$row['num'];
	$subTot=money($row['tot']);
	$IVA=money($row['iva']);
	//$des=htmlentities($row['des'], ENT_QUOTES,"$CHAR_SET");
	$des=$row['des'];
	$cant=$row['cant']*1;
	$uni=$row["unidades_fraccion"];
	$FRAC=$row["fraccion"];
	if($FRAC<=0){$FRAC=1;}
	$valor=money($row['precio']);


$cod=$row['cod_barras'];
$fe=$row['fecha_vencimiento'];
if($usar_fecha_vencimiento==0){$fe="0000-00-00";}
	//$vendedor=htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET");
	//$HORA=$row['hora'];
	$fecha=$row['fecha'];
	$src=$row['src'];
	if($signo[$src]=="+"){
		$saldo[$ref][$cod][$fe][1]+=$cant;
		$saldo[$ref][$cod][$fe][2]+=$uni;
	}
	else if($signo[$src]=="-"){
		$saldo[$ref][$cod][$fe][1]-=$cant;
		$saldo[$ref][$cod][$fe][2]-=$uni;
	}


}
$saldoFrac=ajuste_frac($saldo[$REF][$COD_BAR][$FE_VEN][1],$saldo[$REF][$COD_BAR][$FE_VEN][2],$FRAC,$REF);

if($saldoFrac[1]!=$saldo2[$REF][$COD_BAR][$FE_VEN][1] || $saldoFrac[2]!=$saldo2[$REF][$COD_BAR][$FE_VEN][2]){


if($saldo2[$REF][$COD_BAR][$FE_VEN][1]-$saldoFrac[1]>=1 || $saldoFrac[1]-$saldo2[$REF][$COD_BAR][$FE_VEN][1]>=1){
//echo "<li>$des $ref / $cod / $fe  Inv | kardex Cant:  ".$saldo2[$REF][$COD_BAR][$FE_VEN][1]." | <B>$saldoFrac[1]</B> ".$saldo2[$REF][$COD_BAR][$FE_VEN][2]." | <B>$saldoFrac[2]</B></li>";
echo "<tr><td>$des</td><td>$ref</td><td>$cod</td><td>$fe</td><td>".$saldo2[$REF][$COD_BAR][$FE_VEN][1].";".$saldo2[$REF][$COD_BAR][$FE_VEN][2]."</td><td><B>$saldoFrac[1] ; $saldoFrac[2]</B></td></tr>";
}
}

if($usar_fecha_vencimiento==0){}

}

}
echo "</tbody></table>";
}catch (Throwable $e) {


}
};
function import_csv($fileName,$colSet){

global $hoy,$fechaHoy,$munSuc,$linkPDO,$codSuc;


// path where your CSV file is located

/*
$tmpName = $_FILES['csv']['tmp_name'];
$csvAsArray = array_map('str_getcsv', file($tmpName));

*/

$csv_file = CSV_PATH . "$fileName"; // Name of your CSV file

$csvfile = fopen($csv_file, 'r');
$theData = fgets($csvfile);
$i = 0;

try {
$linkPDO->beginTransaction();
$all_query_ok=true;


$sql="DELETE FROM art_fac_com WHERE num_fac_com='1122334455-IMP'";
$linkPDO->exec($sql);


$sql="INSERT IGNORE INTO `fac_com` (`nom_pro`, `nit_pro`, `ciudad`, `dir`, `tel`, `fax`, `mail`, `num_fac_com`, `fecha`, `cod_su`, `subtot`, `descuento`, `flete`, `iva`, `tot`, `val_letras`, `fecha_mod`, `fecha_crea`, `serial_fac_com`, `tipo_fac`, `estado`, `dcto2`, `pago`, `fecha_pago`, `id_banco`, `id_cuenta`, `kardex`, `r_fte`, `r_ica`, `r_iva`, `feVen`, `sede_origen`, `sede_destino`, `serial_tras`, `calc_dcto`, `perflete`) VALUES ('IMPORTACION BD', 'R-66Y', '$munSuc', '', '', '', '', '1122334455-IMP', '$hoy', '$codSuc', '0.00', '0.00', '0.00', '0.00', '0.00', '', '$hoy', '$hoy', 1000000, 'Importar BD', 'ABIERTA', '0.00', 'PENDIENTE', '0000-00-00', 0, 0, 1, '0.00', '0.00', '0.00', '2016-03-29', 1, 0, 0, '', '0.00');";
$linkPDO->exec($sql);

//echo "OPT: $colSet <br>";

while (!feof($csvfile)) {
$csv_data[] = fgets($csvfile, 1024);
$csv_array = explode(";", ($csv_data[$i]));


$insert_csv = array();
$tabla="art_fac_com";

if($colSet=="A"){
// A
// 0         1        2       3       4      5       6        7  		   8          9    		10    	 	11			12			     13
// ref	Descripcion	Clase	Talla   Color   costo	pvp	     fracc		fab/marca	 CodBar    CodColor	   Vigencia	   grupoDest	 cantidades


$csv_array[5]=limpianum2($csv_array[5]);
$csv_array[6]=limpianum2($csv_array[6]);


$csv_array[0]=limpiarcampo($csv_array[0]);
$csv_array[1]=limpiarcampo($csv_array[1]);
$csv_array[2]=limpiarcampo($csv_array[2]);
$csv_array[3]=limpiarcampo($csv_array[3]);
$csv_array[4]=limpiarcampo($csv_array[4]);
$csv_array[8]=limpiarcampo($csv_array[8]);
$csv_array[9]=limpiarcampo($csv_array[9]);
$csv_array[10]=limpiarcampo($csv_array[10]);
$csv_array[11]=limpiarcampo($csv_array[11]);
$csv_array[12]=limpiarcampo($csv_array[12]);
$csv_array[13]=limpianum2($csv_array[13]);

if(empty($csv_array[7]) || $csv_array[7]<=0)$csv_array[7]=1;
if(empty($csv_array[0]) && !empty($csv_array[9]))$csv_array[0]=$csv_array[9];
//$iva=$csv_array[3];
$iva=19;



$cols="num_fac_com,cant,      ref,             des,       costo,       dcto,iva,uti,      pvp,   tot,cod_su,nit_pro,tipo_dcto,dcto2,cod_barras,     color, talla,fabricante,clase,presentacion,fecha_vencimiento,fraccion,unidades_fraccion,aplica_vehi,cod_color,vigencia_inicial,grupo_destino";
$values="'1122334455-IMP','$csv_array[13]','$csv_array[0]','$csv_array[1]','$csv_array[5]','0','$iva','0','$csv_array[6]','0','$codSuc','R-66Y','','0',    '$csv_array[9]','$csv_array[4]','$csv_array[3]','$csv_array[8]','$csv_array[2]','UNIDAD','0000-00-00','$csv_array[7]',	'0','','$csv_array[11]','$csv_array[12]','$csv_array[13]'";
}


else if($colSet=="B"){
	// B
	// 0         1        2       3       4      5       6        7  		   8          9    		10    	 	11			12		 	
	// ref	Descripcion	Clase	Talla   Color   costo	pvp	     fracc		fab/marca	 CodBar    CodColor	   Vigencia	   grupoDes
	$csv_array[5]=limpianum2($csv_array[5]);
	$csv_array[6]=limpianum2($csv_array[6]);

	$csv_array[0]=limpiarcampo($csv_array[0]);
	$csv_array[1]=limpiarcampo($csv_array[1]);
	$csv_array[2]=limpiarcampo($csv_array[2]);
	$csv_array[3]=limpiarcampo($csv_array[3]);
	$csv_array[4]=limpiarcampo($csv_array[4]);
	$csv_array[8]=limpiarcampo($csv_array[8]);
	$csv_array[9]=limpiarcampo($csv_array[9]);
	$csv_array[10]=limpiarcampo($csv_array[10]);
	$csv_array[11]=limpiarcampo($csv_array[11]);
	$csv_array[12]=limpiarcampo($csv_array[12]);

	$iva=19;

	if(empty($csv_array[7]) || $csv_array[7]<=0)$csv_array[7]=1;
	if(empty($csv_array[0]) && !empty($csv_array[9]))$csv_array[0]=$csv_array[9];


	$cols="num_fac_com,cant,      ref,             des,       costo,       dcto,iva,uti,      pvp,   tot,cod_su,nit_pro,tipo_dcto,dcto2,cod_barras,     color, talla,fabricante,clase,presentacion,fecha_vencimiento,fraccion,unidades_fraccion,aplica_vehi,cod_color,vigencia_inicial,grupo_destino";
	$values="'1122334455-IMP','0','$csv_array[0]','$csv_array[1]','$csv_array[5]','0','$iva','0','$csv_array[6]','0','$codSuc','R-66Y','','0',    '$csv_array[9]','$csv_array[4]','$csv_array[3]','$csv_array[8]','$csv_array[2]','UNIDAD','0000-00-00','$csv_array[7]',	'0','','$csv_array[10]','$csv_array[11]','$csv_array[12]'";
	/*echo "<br> <pre>";
	var_dump($csv_array);
	echo "</pre><br> $values <br>";*/
}


else if($colSet=="C"){

// B
	// 0         1        2       3       4      5       6        7  		   8          9    		10    	 	11			12		 	13
	// ref	Descripcion	Clase	Talla   Color   costo	pvp	     fracc		fab/marca	 CodBar    CodColor	   Vigencia	   grupoDest	IVA
	$csv_array[5]=limpianum2($csv_array[5]);
	$csv_array[6]=limpianum2($csv_array[6]);

	$csv_array[0]=limpiarcampo($csv_array[0]);
	$csv_array[1]=limpiarcampo($csv_array[1]);
	$csv_array[2]=limpiarcampo($csv_array[2]);
	$csv_array[3]=limpiarcampo($csv_array[3]);
	$csv_array[4]=limpiarcampo($csv_array[4]);
	$csv_array[8]=limpiarcampo($csv_array[8]);
	$csv_array[9]=limpiarcampo($csv_array[9]);
	$csv_array[10]=limpiarcampo($csv_array[10]);
	$csv_array[11]=limpiarcampo($csv_array[11]);
	$csv_array[12]=limpiarcampo($csv_array[12]);
	$csv_array[13]=limpianum2($csv_array[13]);


	$iva=!empty($csv_array[13])?$csv_array[13]:19;
	$iva=$csv_array[13];

	if(empty($csv_array[7]) || $csv_array[7]<=0)$csv_array[7]=1;
	if(empty($csv_array[0]) && !empty($csv_array[9]))$csv_array[0]=$csv_array[9];


	$cols="num_fac_com,cant,      ref,             des,       costo,       dcto,iva,uti,      pvp,   tot,cod_su,nit_pro,tipo_dcto,dcto2,cod_barras,     color, talla,fabricante,clase,presentacion,fecha_vencimiento,fraccion,unidades_fraccion,aplica_vehi,cod_color,vigencia_inicial,grupo_destino";
	$values="'1122334455-IMP','0','$csv_array[0]','$csv_array[1]','$csv_array[5]','0','$iva','0','$csv_array[6]','0','$codSuc','R-66Y','','0',    '$csv_array[9]','$csv_array[4]','$csv_array[3]','$csv_array[8]','$csv_array[2]','UNIDAD','0000-00-00','$csv_array[7]',	'0','','$csv_array[10]','$csv_array[11]','$csv_array[12]'";
	/*echo "<br> <pre>";
	var_dump($csv_array);
	echo "</pre><br> $values <br>";*/

}
else{


// C
// 0   1     2    3    4      5        6     7       8         9	   10	 	   11
//des;costo;pvp;clase;
//string iconv ( string $in_charset , string $out_charset , string $str )
$ID=getId_art_com();

$ref=$ID;
$codBar=$ref;


$tab = array("UTF-8", "ASCII", "Windows-1252", "ISO-8859-15", "ISO-8859-1", "ISO-8859-6", "CP1256");



$csv_array[0]=iconv ($tab[2],$tab[0],$csv_array[0] );
$csv_array[3]=iconv ($tab[2],$tab[0],$csv_array[3] );

$csv_array[0]=limpiarcampo($csv_array[0]);
$csv_array[1]=limpianum2($csv_array[1]);
$csv_array[2]=limpianum2($csv_array[2]);
$csv_array[3]=limpiarcampo($csv_array[3]);


if(empty($csv_array[5]) )$csv_array[5]=$csv_array[0];
//$iva=$csv_array[3];
$iva=19;

$cols="num_fac_com,ref,des,costo,iva,pvp,cod_su,nit_pro,cod_barras,clase";

$values="'1122334455-IMP','$ref','$csv_array[0]','$csv_array[1]','$iva','$csv_array[2]','$codSuc','R-66Y','$codBar','$csv_array[3]'    ";



	}

$query = "INSERT IGNORE INTO $tabla($cols) VALUES($values)";
//echo "<li>$query</li>";
if(!empty($csv_array[0])){


	$linkPDO->exec($query);
	}
$i++;
}
$linkPDO->commit();

if($all_query_ok){

//js_location("importar_lista.php");

fclose($csvfile);
}
else{eco_alert("ERROR! Intente nuevamente");}


}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

}
function multi_consulta($pack_query,$all_query_ok)
{
	global $conex;
	if(!empty($pack_query)){
	//echo "CONSULTAS: $pack_query";
	//$conex->multi_query($pack_query); // OK
	if (!$conex->multi_query($pack_query)) {
    echo "Falló la multiconsulta: (" . $conex->errno . ") " . $conex->error;
	$all_query_ok=false;
										   }
	do {
    if ($resultado = $conex->store_result()) {
        var_dump($resultado->fetch_all(MYSQLI_ASSOC));
        $resultado->free();
    }
} while ($conex->more_results() && $conex->next_result());


	}else {$all_query_ok=true;}
/* cerrar conexión */
return $all_query_ok;
};
function mes_anterior($fecha){
	$mes=date("m",strtotime($fecha));
	$year=date("Y",strtotime($fecha));
	$lastDay=date("t",strtotime($fecha));

	$first="$year-$mes-01";


	$lastMonth = strtotime ( '-1 day' , strtotime ( $first ) ) ;
	$lastMonth = date ( 'Y-m-j' , $lastMonth );

	return $lastMonth;
};





function fechaFAC($fecha)
{
	$aux=$fecha;
	$NEW=preg_split("[ ]",$fecha);
	if(count($NEW)>1){$resultDate=$NEW[0]."T".$NEW[1];}
	else{$resultDate=$NEW[0]."T00:00:00";}
	return $resultDate;
};
function SendFacDIAN($num_fac,$prefijo,$codSuc,$hash)
{
ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache

global $CLAVE_DIAN_FAC,$USUAR_DIAN_FAC,$NIT_DIAN_FAC;
global $linkPDO;

$NIT_EMPRESA=$NIT_DIAN_FAC;

$sql="SELECT * FROM art_fac_ven WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' AND hash='$hash' AND nit='$codSuc'";
//echo "$sql";
$rs=$linkPDO->query($sql);
$Nrows=$rs->rowCount();
$once=0;
$i=0;

//echo "NROWS: $Nrows";


$JSsededo = '{"DETDOC":[';
$JSsedepr = '{"DETALLEPRO":[';
$JSsedeim = '{"DETIMPDOC":[';
$JSseaddo = '{"ADIDOCUMENTO":[';
$excentas=0;
while($row=$rs->fetch()){
$i++;
$valUni=$row["precio"]/(1+$row["iva"]/100);
$valUni=round($valUni,2);
$iva=$row["iva"];
$cant=$row["cant"];
$frac=$row["fraccion"];
$uni=$row["unidades_fraccion"];
$factor2=round($uni/$frac + $cant,2);
$factor=$uni/$frac + $cant;
$pvp = $row["precio"]/(1+$iva/100);
$TEXADD="";
if($uni!=0){$TEXADD="Cantidad Completa: [$factor]";}


$subTotUni=round( $valUni*$factor,2);
$valorIVA=round($subTotUni*($iva/100),2);

$presentacion="";

if($frac==1){$presentacion="UNI";}
else{$presentacion="x$frac";}

	if($iva==0)
	{
		$excentas+=$pvp*$factor;

	}
//echo "<li>iva $iva, cant $cant, fraccion $frac,uni $uni,factor $factor   </li>";

//sededo
//LIST DETALLE DOCUMENTO
$comma=",";
if($i==$Nrows){$comma="";}
$JSsededo.= '
            {"IDEPOS":"'.$row["serial_art_fac"].'",
            "CODITE":"'.$row["cod_barras"].'",
            "NOMITE":"'.$row["des"].'",
            "UNIMED":"'.$presentacion.'",
            "CANITE":"'.$factor2.'",
            "VALITE":"'.$valUni.'",
            "TOTVAD":"'.$subTotUni.'"
            }'.$comma.'';



//sedepr
//LIST DETALLE PRODUCTOS
$JSsedepr .= '
            {"IDEPOS":"'.$row["serial_art_fac"].'",
            "CODITE":"'.$row["cod_barras"].'",
            "COPODP":"01",
            "NOPODP":"IVA",
            "VAPODP":"'.($row["precio"]).'",
            "POPODP":"'.$iva.'"
            }'.$comma.'';




//sedeim
//LIST DETALLE IMPUESTOS DOCUMENTO
$JSsedeim.= '
            {"IDEIMP":"'.$row["serial_art_fac"].'",
            "CODIMP":"01",
            "NOMIMP":"IVA TOTAL",
            "PORIMP":"'.$row["iva"].'",
            "VALIMP":"'.$valorIVA.'"
            }'.$comma.'';




//seaddo
//LIST ADICIONALES DOCUMENTO
$JSseaddo.= '
            {"IDEADD":"'.$row["serial_art_fac"].'",
            "TEXADD":"'.$TEXADD.'",
            "VALNDD":"",
            "VALTDD":""
			}'.$comma.'';



}// fin ArtVen while

$sql="SELECT * FROM serv_fac_ven WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' AND hash='$hash' AND cod_su='$codSuc'";
//echo "$sql";
$rs=$linkPDO->query($sql);
$Nrows=$rs->rowCount();
$once=0;
$i=0;

while($row=$rs->fetch())
{

$i++;
$valUni=$row["pvp"]/(1+$row["iva"]/100);
$valUni=round($valUni,2);
$iva=$row["iva"];
$cant=1;
$frac=1;
$uni=0;
$factor2=round($uni/$frac + $cant,2);
$factor=$uni/$frac + $cant;
$pvp = $row["pvp"]/(1+$iva/100);
$TEXADD="";
if($uni!=0){$TEXADD="Cantidad Completa: [$factor]";}


$subTotUni=round( $valUni*$factor,2);
$valorIVA=round($subTotUni*($iva/100),2);

$presentacion="";

if($frac==1){$presentacion="UNI";}
else{$presentacion="x$frac";}

	if($iva==0)
	{
		$excentas+=$pvp*$factor;

	}
//echo "<li>iva $iva, cant $cant, fraccion $frac,uni $uni,factor $factor   </li>";

//sededo
//LIST DETALLE DOCUMENTO
$comma=",";
if($i==$Nrows){$comma="";}
$JSsededo.= '
            {"IDEPOS":"'.$row["serial_art_fac"].'",
            "CODITE":"'.$row["cod_serv"].'",
            "NOMITE":"'.$row["serv"].'",
            "UNIMED":"'.$presentacion.'",
            "CANITE":"'.$factor2.'",
            "VALITE":"'.$valUni.'",
            "TOTVAD":"'.$subTotUni.'"
            }'.$comma.'';



//sedepr
//LIST DETALLE PRODUCTOS
$JSsedepr .= '
            {"IDEPOS":"'.$row["serial_art_fac"].'",
            "CODITE":"'.$row["cod_serv"].'",
            "COPODP":"01",
            "NOPODP":"IVA",
            "VAPODP":"'.($row["pvp"]).'",
            "POPODP":"'.$iva.'"
            }'.$comma.'';




//sedeim
//LIST DETALLE IMPUESTOS DOCUMENTO
$JSsedeim.= '
            {"IDEIMP":"'.$row["serial_art_fac"].'",
            "CODIMP":"01",
            "NOMIMP":"IVA TOTAL",
            "PORIMP":"'.$row["iva"].'",
            "VALIMP":"'.$valorIVA.'"
            }'.$comma.'';




//seaddo
//LIST ADICIONALES DOCUMENTO
$JSseaddo.= '
            {"IDEADD":"'.$row["serial_art_fac"].'",
            "TEXADD":"'.$TEXADD.'",
            "VALNDD":"",
            "VALTDD":""
			}'.$comma.'';




}

$JSsededo.= ']}';
$B64sededo = base64_encode($JSsededo);
//echo "$JSsededo";

$JSsedepr.= ']}';
$B64sedepr = base64_encode($JSsedepr);
//echo "$JSsedepr";

$JSsedeim.= ']}';
$B64sedeim = base64_encode($JSsedeim);
//echo "$JSsedeim";

$JSseaddo.= ']}';
$B64seaddo = base64_encode($JSseaddo);
//echo "$JSseaddo";


$sql="SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' AND hash='$hash' AND nit='$codSuc'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){

//secado
//CABECERA DOCUMENTOS

$JSsecado = '{"CABDOC":
    {"NITEMP":"'.$NIT_EMPRESA.'",
    "NUMRES":"'.$row["resolucion"].'",
    "TDOCU":"'.$row["TIPDOC"].'",
    "NUMERO":"'."$prefijo$num_fac".'",
    "FECDOC":"'.fechaFAC($row["fecha"]).'",
    "FECVEN":"'.fechaFAC($row["FECVEN"]).'",
    "NOMVEN":"'.$row["vendedor"].'",
    "MONEDA":"'.$row["MONEDA"].'",
    "TIPCRU":"'.$row["TIPCRU"].'",
    "CODSUC":"'.$row["CODSUC"].'",
    "NUMREF":"'.$row["NUMREF"].'",
    "FORIMP":"'.$row["FORIMP"].'",
    "CLADET":"'.$row["CLADET"].'",
    "FORPAG":"'.$row["tipo_venta"].'",
    "ORDENC":"'.$row["ORDENC"].'",
    "NUREMI":"'.$row["NUREMI"].'",
    "NORECE":"'.$row["NORECE"].'",
    "EANTIE":"'.$row["EANTIE"].'",
    "COMODE":"'.$row["COMODE"].'",
    "COMOHA":"'.$row["COMOHA"].'",
    "FACCAL":"'.$row["FACCAL"].'",
    "FETACA":"'.fechaFAC($row["FETACA"]).'",
    "FECREF":"'.fechaFAC($row["FECREF"]).'",
    "OBSREF":"'.$row["OBSREF"].'",
    "OBSERV":"'.$row["nota"].'",
    "TEXDOC":"'.$row["TEXDOC"].'",
    "MODEDI":"'.$row["MODEDI"].'",
    "NUMENT":"'.$row["serial_fac"].'",
    "NDIAN":"'.$row["NDIAN"].'",
    "SOCIED":"'.$row["SOCIED"].'",
    "CODVEN":"'.$row["id_vendedor"].'",
    "MOTDEV":"'.$row["MOTDEV"].'",
    "SUBTO":"'.round($row["sub_tot"]-$excentas,2).'",
    "TOTAL":"'.round($row["sub_tot"],2).'",
    "TOTFA":"'.round($row["tot"],2).'",
    "CLAVE":"'.$CLAVE_DIAN_FAC.'",
    "USUAR":"'.$USUAR_DIAN_FAC.'"
    }
    }';

//echo ($JSsecado);
$B64secado = base64_encode($JSsecado);


//secldo
//DATOS CLIENTE

$JSsecldo = '{"CLIDOC":
        {"CLAPER":"'.$row["claper"].'",
        "CODDOC":"'.$row["coddoc"].'",
        "NUMDOC":"'.$row["id_cli"].'",
        "PAICLI":"'.$row["paicli"].'",
        "DEPCLI":"'.$row["depcli"].'",
        "CIUCLI":"'.$row["ciudad"].'",
        "LOCCLI":"'.$row["loccli"].'",
        "DIRCLI":"'.$row["dir"].'",
        "TELCLI":"'.$row["tel_cli"].'",
        "NOMCON":"'.$row["nomcon"].'",
        "REGTRI":"'.$row["regtri"].'",
        "RAZSOC":"'.$row["razsoc"].'",
        "PNOMBR":"'.$row["nom_cli"].'",
        "SNOMBR":"'.$row["snombr"].'",
        "APELLI":"'.$row["apelli"].'",
        "MAICLI":"'.$row["mail"].'"
        }
    }';
$B64secldo = base64_encode($JSsecldo);
//echo "$JSsecldo";


}// fin IF facVen

$argume = array('secado' => $JSsecado, 'secldo' => $JSsecldo, 'sededo' => $JSsededo,'sedepr'=>$JSsedepr,'sedeim'=>$JSsedeim,'seaddo'=>$JSseaddo);
/*
echo "<pre>";
print_r($argume);
echo "</pre>";

*/

//$respEnvio=$arrayResp["estdoc"];
//$update="UPDATE fac_venta SET estado_factura_elec='$respEnvio' WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' AND nit='$codSuc'";
//echo "$update";
//t1($update);
if(0){

$argume = array('secado' => $B64secado, 'secldo' => $B64secldo, 'sededo' => $B64sededo,'sedepr'=>$B64sedepr,'sedeim'=>$B64sedeim,'seaddo'=>$B64seaddo);
$errcon = "";
$wsdlco = "http://www.ainix.com.co/wp/electronicinvoice/ws_electronic_invoice.wsdl";

$client = new SoapClient($wsdlco, array('wsdl_cache' => 0,'trace' => 1,'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP ));
try {
   set_time_limit(3000);
   $result =  $client->__soapCall('send_document', array('sendoc' => $argume));
   /* echo "<pre>";
   // print_r($client->__getLastResponse());
   print_r($result);
    echo "</pre>";
	*/
	$respEnvio="idedoc=>".$result["idedoc"]." / resfin=>".$result["resfin"]." / vacufe=>".$result["vacufe"];
	$estadoSend=$result["estdoc"];
$update="UPDATE fac_venta SET estado_factura_elec='$estadoSend' , resp_dian='$respEnvio' WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' AND nit='$codSuc'";
t1($update);
//echo "$update";

    $succes = "true";
} catch (exception $errcon) {
    echo "<pre>";
   // print_r($client->__getLastResponse());
   print_r($errcon);
    echo "</pre>";
    $succes = "false";
}
}

};

function SendNotaDebDIAN($num_fac,$nit_pro,$codSuc)
{
	/*
	
	
	
	

ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
global $CLAVE_DIAN_FAC,$USUAR_DIAN_FAC,$NIT_DIAN_FAC;
global $linkPDO;

$NIT_EMPRESA=$NIT_DIAN_FAC;



$sql="SELECT * FROM art_fac_dev WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su='$codSuc'";
//echo "$sql";
$rs=$linkPDO->query($sql);
$Nrows=$rs->rowCount();
$once=0;
$i=0;

//echo "NROWS: $Nrows";


$JSsededo = '{"DETDOC":[';
$JSsedepr = '{"DETALLEPRO":[';
$JSsedeim = '{"DETIMPDOC":[';
$JSseaddo = '{"ADIDOCUMENTO":[';
$excentas=0;
while($row=$rs->fetch()){
$i++;
$valUni=$row["pvp"]/(1+$row["iva"]/100);
$valUni=round($valUni,2);
$iva=$row["iva"];
$cant=$row["cant"];
$frac=$row["fraccion"];
$uni=$row["unidades_fraccion"];
$factor2=round($uni/$frac + $cant,2);
$factor=$uni/$frac + $cant;
$pvp = $row["pvp"]/(1+$iva/100);
$TEXADD="";
if($uni!=0){$TEXADD="Cantidad Completa: [$factor]";}


$subTotUni=round( $valUni*$factor,2);
$valorIVA=round($subTotUni*($iva/100),2);

$presentacion="";

if($frac==1){$presentacion="UNI";}
else{$presentacion="x$frac";}

	if($iva==0)
	{
		$excentas+=$pvp*$factor;

	}
//echo "<li>iva $iva, cant $cant, fraccion $frac,uni $uni,factor $factor   </li>";

//sededo
//LIST DETALLE DOCUMENTO
$comma=",";
if($i==$Nrows){$comma="";}
$JSsededo.= '
            {"IDEPOS":"'.$row["serial_art_fac"].'",
            "CODITE":"'.$row["cod_barras"].'",
            "NOMITE":"'.$row["des"].'",
            "UNIMED":"'.$presentacion.'",
            "CANITE":"'.$factor2.'",
            "VALITE":"'.$valUni.'",
            "TOTVAD":"'.$subTotUni.'"
            }'.$comma.'';



//sedepr
//LIST DETALLE PRODUCTOS
$JSsedepr .= '
            {"IDEPOS":"'.$row["serial_art_fac"].'",
            "CODITE":"'.$row["cod_barras"].'",
            "COPODP":"01",
            "NOPODP":"IVA",
            "VAPODP":"'.($row["pvp"]).'",
            "POPODP":"'.$iva.'"
            }'.$comma.'';




//sedeim
//LIST DETALLE IMPUESTOS DOCUMENTO
$JSsedeim.= '
            {"IDEIMP":"'.$row["serial_art_fac"].'",
            "CODIMP":"01",
            "NOMIMP":"IVA TOTAL",
            "PORIMP":"'.$row["iva"].'",
            "VALIMP":"'.$valorIVA.'"
            }'.$comma.'';




//seaddo
//LIST ADICIONALES DOCUMENTO
$JSseaddo.= '
            {"IDEADD":"'.$row["serial_art_fac"].'",
            "TEXADD":"'.$TEXADD.'",
            "VALNDD":"",
            "VALTDD":""
			}'.$comma.'';



}// fin ArtVen while


$JSsededo.= ']}';
$B64sededo = base64_encode($JSsededo);
//echo "$JSsededo";

$JSsedepr.= ']}';
$B64sedepr = base64_encode($JSsedepr);
//echo "$JSsedepr";

$JSsedeim.= ']}';
$B64sedeim = base64_encode($JSsedeim);
//echo "$JSsedeim";

$JSseaddo.= ']}';
$B64seaddo = base64_encode($JSseaddo);
//echo "$JSseaddo";


$sql="SELECT * FROM fac_dev WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro'   AND cod_su='$codSuc'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){

//secado
//CABECERA DOCUMENTOS

$JSsecado = '{"CABDOC":
    {"NITEMP":"'.$NIT_EMPRESA.'",
    "NUMRES":"'."".'",
    "TDOCU":"'.$row["TIPDOC"].'",
    "NUMERO":"'."$num_fac".'",
    "FECDOC":"'.fechaFAC($row["fecha"]).'",
    "FECVEN":"'.fechaFAC($row["FECVEN"]).'",
    "NOMVEN":"'.$row["vendedor"].'",
    "MONEDA":"'.$row["MONEDA"].'",
    "TIPCRU":"'.$row["TIPCRU"].'",
    "CODSUC":"'.$row["CODSUC"].'",
    "NUMREF":"'.$row["NUMREF"].'",
    "FORIMP":"'.$row["FORIMP"].'",
    "CLADET":"'.$row["CLADET"].'",
    "FORPAG":"'."Contado".'",
    "ORDENC":"'.$row["ORDENC"].'",
    "NUREMI":"'.$row["NUREMI"].'",
    "NORECE":"'.$row["NORECE"].'",
    "EANTIE":"'.$row["EANTIE"].'",
    "COMODE":"'.$row["COMODE"].'",
    "COMOHA":"'.$row["COMOHA"].'",
    "FACCAL":"'.$row["FACCAL"].'",
    "FETACA":"'.fechaFAC($row["FETACA"]).'",
    "FECREF":"'.fechaFAC($row["FECREF"]).'",
    "OBSREF":"'.$row["OBSREF"].'",
    "OBSERV":"'.$row["nota_dev"].'",
    "TEXDOC":"'.$row["TEXDOC"].'",
    "MODEDI":"'.$row["MODEDI"].'",
    "NUMENT":"'.$row["serial_fac"].'",
    "NDIAN":"'.$row["NDIAN"].'",
    "SOCIED":"'.$row["SOCIED"].'",
    "CODVEN":"'.$row["id_vendedor"].'",
    "MOTDEV":"'.$row["MOTDEV"].'",
    "SUBTO":"'.round($row["subtot"]-$excentas,2).'",
    "TOTAL":"'.round($row["subtot"],2).'",
    "TOTFA":"'.round($row["tot"],2).'",
    "CLAVE":"'.$CLAVE_DIAN_FAC.'",
    "USUAR":"'.$USUAR_DIAN_FAC.'"
    }
    }';

//echo ($JSsecado);
$B64secado = base64_encode($JSsecado);


//secldo
//DATOS CLIENTE

$JSsecldo = '{"CLIDOC":
        {"CLAPER":"'.$row["claper"].'",
        "CODDOC":"'.$row["coddoc"].'",
        "NUMDOC":"'.$row["nit_pro"].'",
        "PAICLI":"'.$row["paicli"].'",
        "DEPCLI":"'.$row["depcli"].'",
        "CIUCLI":"'.$row["ciudad"].'",
        "LOCCLI":"'.$row["loccli"].'",
        "DIRCLI":"'.$row["dir"].'",
        "TELCLI":"'.$row["tel"].'",
        "NOMCON":"'.$row["nomcon"].'",
        "REGTRI":"'.$row["regtri"].'",
        "RAZSOC":"'.$row["razsoc"].'",
        "PNOMBR":"'.$row["nom_pro"].'",
        "SNOMBR":"'.$row["snombr"].'",
        "APELLI":"'.$row["apelli"].'",
        "MAICLI":"'.$row["mail"].'"
        }
    }';
$B64secldo = base64_encode($JSsecldo);
//echo "$JSsecldo";


}// fin IF facVen

$argume = array('secado' => $JSsecado, 'secldo' => $JSsecldo, 'sededo' => $JSsededo,'sedepr'=>$JSsedepr,'sedeim'=>$JSsedeim,'seaddo'=>$JSseaddo);
 
if(0){
$argume = array('secado' => $B64secado, 'secldo' => $B64secldo, 'sededo' => $B64sededo,'sedepr'=>$B64sedepr,'sedeim'=>$B64sedeim,'seaddo'=>$B64seaddo);
$errcon = "";
$wsdlco = "http://www.ainix.com.co/wp/electronicinvoice/ws_electronic_invoice.wsdl";

$client = new SoapClient($wsdlco, array('wsdl_cache' => 0,'trace' => 1,'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP ));
try {
   $result =  $client->__soapCall('send_document', array('sendoc' => $argume));
 
	$respEnvio="idedoc=>".$result["idedoc"]." / resfin=>".$result["resfin"]." / vacufe=>".$result["vacufe"];
	$estadoSend=$result["estdoc"];
$update="UPDATE fac_dev SET estado_factura_elec='$estadoSend' , resp_dian='$respEnvio' WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro'   AND cod_su='$codSuc'";
 
t1($update);
    $succes = "true";
} catch (exception $errcon) {
    echo "<pre>";
   // print_r($client->__getLastResponse());
   print_r($errcon);
    echo "</pre>";
    $succes = "false";
}
}





*/};

function SendNotaCreDIAN($num_fac,$prefijo,$codSuc)
{
ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
global $CLAVE_DIAN_FAC,$USUAR_DIAN_FAC,$NIT_DIAN_FAC;
global $linkPDO;

$NIT_EMPRESA=$NIT_DIAN_FAC;



$sql="SELECT * FROM art_devolucion_venta WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' AND nit='$codSuc'";
//echo "$sql";
$rs=$linkPDO->query($sql);
$Nrows=$rs->rowCount();
$once=0;
$i=0;

//echo "NROWS: $Nrows";


$JSsededo = '{"DETDOC":[';
$JSsedepr = '{"DETALLEPRO":[';
$JSsedeim = '{"DETIMPDOC":[';
$JSseaddo = '{"ADIDOCUMENTO":[';
$excentas=0;
while($row=$rs->fetch()){
$i++;
$valUni=$row["precio"]/(1+$row["iva"]/100);
$valUni=round($valUni,2);
$iva=$row["iva"];
$cant=$row["cant"];
$frac=$row["fraccion"];
$uni=$row["unidades_fraccion"];
$factor2=round($uni/$frac + $cant,2);
$factor=$uni/$frac + $cant;
$pvp = $row["precio"]/(1+$iva/100);
$TEXADD="";
if($uni!=0){$TEXADD="Cantidad Completa: [$factor]";}


$subTotUni=round( $valUni*$factor,2);
$valorIVA=round($subTotUni*($iva/100),2);

$presentacion="";

if($frac==1){$presentacion="UNI";}
else{$presentacion="x$frac";}

	if($iva==0)
	{
		$excentas+=$pvp*$factor;

	}
//echo "<li>iva $iva, cant $cant, fraccion $frac,uni $uni,factor $factor   </li>";

//sededo
//LIST DETALLE DOCUMENTO
$comma=",";
if($i==$Nrows){$comma="";}
$JSsededo.= '
            {"IDEPOS":"'.$row["serial_art_fac"].'",
            "CODITE":"'.$row["cod_barras"].'",
            "NOMITE":"'.$row["des"].'",
            "UNIMED":"'.$presentacion.'",
            "CANITE":"'.$factor2.'",
            "VALITE":"'.$valUni.'",
            "TOTVAD":"'.$subTotUni.'"
            }'.$comma.'';



//sedepr
//LIST DETALLE PRODUCTOS
$JSsedepr .= '
            {"IDEPOS":"'.$row["serial_art_fac"].'",
            "CODITE":"'.$row["cod_barras"].'",
            "COPODP":"01",
            "NOPODP":"IVA",
            "VAPODP":"'.($row["precio"]).'",
            "POPODP":"'.$iva.'"
            }'.$comma.'';




//sedeim
//LIST DETALLE IMPUESTOS DOCUMENTO
$JSsedeim.= '
            {"IDEIMP":"'.$row["serial_art_fac"].'",
            "CODIMP":"01",
            "NOMIMP":"IVA TOTAL",
            "PORIMP":"'.$row["iva"].'",
            "VALIMP":"'.$valorIVA.'"
            }'.$comma.'';




//seaddo
//LIST ADICIONALES DOCUMENTO
$JSseaddo.= '
            {"IDEADD":"'.$row["serial_art_fac"].'",
            "TEXADD":"'.$TEXADD.'",
            "VALNDD":"",
            "VALTDD":""
			}'.$comma.'';



}// fin ArtVen while


$JSsededo.= ']}';
$B64sededo = base64_encode($JSsededo);
//echo "$JSsededo";

$JSsedepr.= ']}';
$B64sedepr = base64_encode($JSsedepr);
//echo "$JSsedepr";

$JSsedeim.= ']}';
$B64sedeim = base64_encode($JSsedeim);
//echo "$JSsedeim";

$JSseaddo.= ']}';
$B64seaddo = base64_encode($JSseaddo);
//echo "$JSseaddo";


$sql="SELECT * FROM fac_dev_venta WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo'   AND nit='$codSuc'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){

//secado
//CABECERA DOCUMENTOS

$JSsecado = '{"CABDOC":
    {"NITEMP":"'.$NIT_EMPRESA.'",
    "NUMRES":"'.$row["resolucion"].'",
    "TDOCU":"'.$row["TIPDOC"].'",
    "NUMERO":"'."$prefijo$num_fac".'",
    "FECDOC":"'.fechaFAC($row["fecha"]).'",
    "FECVEN":"'.fechaFAC($row["FECVEN"]).'",
    "NOMVEN":"'.$row["vendedor"].'",
    "MONEDA":"'.$row["MONEDA"].'",
    "TIPCRU":"'.$row["TIPCRU"].'",
    "CODSUC":"'.$row["CODSUC"].'",
    "NUMREF":"'.$row["NUMREF"].'",
    "FORIMP":"'.$row["FORIMP"].'",
    "CLADET":"'.$row["CLADET"].'",
    "FORPAG":"'.$row["tipo_venta"].'",
    "ORDENC":"'.$row["ORDENC"].'",
    "NUREMI":"'.$row["NUREMI"].'",
    "NORECE":"'.$row["NORECE"].'",
    "EANTIE":"'.$row["EANTIE"].'",
    "COMODE":"'.$row["COMODE"].'",
    "COMOHA":"'.$row["COMOHA"].'",
    "FACCAL":"'.$row["FACCAL"].'",
    "FETACA":"'.fechaFAC($row["FETACA"]).'",
    "FECREF":"'.fechaFAC($row["FECREF"]).'",
    "OBSREF":"'.$row["OBSREF"].'",
    "OBSERV":"'.$row["nota"].'",
    "TEXDOC":"'.$row["TEXDOC"].'",
    "MODEDI":"'.$row["MODEDI"].'",
    "NUMENT":"'.$row["serial_fac"].'",
    "NDIAN":"'.$row["NDIAN"].'",
    "SOCIED":"'.$row["SOCIED"].'",
    "CODVEN":"'.$row["id_vendedor"].'",
    "MOTDEV":"'.$row["MOTDEV"].'",
    "SUBTO":"'.round($row["sub_tot"]-$excentas,2).'",
    "TOTAL":"'.round($row["sub_tot"],2).'",
    "TOTFA":"'.round($row["tot"],2).'",
    "CLAVE":"'.$CLAVE_DIAN_FAC.'",
    "USUAR":"'.$USUAR_DIAN_FAC.'"
    }
    }';

//echo ($JSsecado);
$B64secado = base64_encode($JSsecado);


//secldo
//DATOS CLIENTE

$JSsecldo = '{"CLIDOC":
        {"CLAPER":"'.$row["claper"].'",
        "CODDOC":"'.$row["coddoc"].'",
        "NUMDOC":"'.$row["id_cli"].'",
        "PAICLI":"'.$row["paicli"].'",
        "DEPCLI":"'.$row["depcli"].'",
        "CIUCLI":"'.$row["ciudad"].'",
        "LOCCLI":"'.$row["loccli"].'",
        "DIRCLI":"'.$row["dir"].'",
        "TELCLI":"'.$row["tel_cli"].'",
        "NOMCON":"'.$row["nomcon"].'",
        "REGTRI":"'.$row["regtri"].'",
        "RAZSOC":"'.$row["razsoc"].'",
        "PNOMBR":"'.$row["nom_cli"].'",
        "SNOMBR":"'.$row["snombr"].'",
        "APELLI":"'.$row["apelli"].'",
        "MAICLI":"'.$row["mail"].'"
        }
    }';
$B64secldo = base64_encode($JSsecldo);
//echo "$JSsecldo";


}// fin IF facVen

$argume = array('secado' => $JSsecado, 'secldo' => $JSsecldo, 'sededo' => $JSsededo,'sedepr'=>$JSsedepr,'sedeim'=>$JSsedeim,'seaddo'=>$JSseaddo);
/*echo "<pre>";
 print_r($argume);
  echo "</pre>";
  */
if(0){
$argume = array('secado' => $B64secado, 'secldo' => $B64secldo, 'sededo' => $B64sededo,'sedepr'=>$B64sedepr,'sedeim'=>$B64sedeim,'seaddo'=>$B64seaddo);
$errcon = "";
$wsdlco = "http://www.ainix.com.co/wp/electronicinvoice/ws_electronic_invoice.wsdl";

$client = new SoapClient($wsdlco, array('wsdl_cache' => 0,'trace' => 1,'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP ));
try {
   $result =  $client->__soapCall('send_document', array('sendoc' => $argume));
    /*echo "<pre>";
   // print_r($client->__getLastResponse());
   print_r($result);
    echo "</pre>";*/
	$respEnvio="idedoc=>".$result["idedoc"]." / resfin=>".$result["resfin"]." / vacufe=>".$result["vacufe"];
	$estadoSend=$result["estdoc"];
$update="UPDATE fac_dev_venta SET estado_factura_elec='$estadoSend' , resp_dian='$respEnvio' WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo'   AND nit='$codSuc'";
//echo "$update";
t1($update);
    $succes = "true";
} catch (exception $errcon) {
    echo "<pre>";
   // print_r($client->__getLastResponse());
   print_r($errcon);
    echo "</pre>";
    $succes = "false";
}
}

};
function ajuste_cero($codSuc)
{
	global $nomUsu,$id_Usu,$hoy,$conex,$conex;
	global $linkPDO;
	$SQL_AJUSTE="";
	$SQL_UPDATES="";
	$fecha=$hoy;
	$nom=limpiarcampo($nomUsu);
	$cc=limpiarcampo($id_Usu);

	try {
$linkPDO->beginTransaction();
$all_query_ok=true;



	$num_ajus=serial_ajustes($conex);

	$SQL_AJUSTE="INSERT INTO ajustes(num_ajuste,fecha,cod_su,nom_usu,id_usu) VALUES($num_ajus,'$fecha',$codSuc,'$nom','$cc');";

	$linkPDO->exec($SQL_AJUSTE);

	//////////////////////// SET INV TO CERO///////////
$sq="SELECT * FROM inv_inter a INNER JOIN ".tabProductos." b ON a.id_pro=b.id_pro WHERE a.nit_scs='$codSuc'";
//echo "<br>$sq";
$rs=$linkPDO->query($sq);
while($row=$rs->fetch())
{

		$cant=$row['exist'];
		$ref=limpiarcampo($row['id_pro']);
		$cod_bar=limpiarcampo($row['id_inter']);
		$presentacion=limpiarcampo($row['presentacion']);
		$des=limpiarcampo($row['detalle']);
		$costo=limpiarcampo($row['costo']);
		$iva=$row['iva'];
		$util=$row['gana'];
		$fechaVenci=$row['fecha_vencimiento'];
		$pvp=$row['precio_v'];
		$frac=$row['fraccion'];
		$uni=$row['unidades_frac'];
		$exist=$row["exist"];
		$uniExist=$row["unidades_frac"];

		$ajusteCant=(-1)*($exist);
		$ajusteUni=(-1)*($uniExist);

		$cant_saldo=0;
		$uniSaldo=0;

		$motivo="Corte Inventario # Ajuste a Cero TODO (0)  $hoy";

$SQL_AJUSTE="INSERT INTO art_ajuste(num_ajuste,ref,des,cant,costo,precio,util,iva,cod_su,motivo,cant_saldo,cod_barras,presentacion,fraccion,unidades_fraccion,unidades_saldo,fecha_vencimiento)
VALUES($num_ajus,'$ref','$des',$ajusteCant,'$costo','$pvp','$util','$iva',$codSuc,'$motivo','$cant_saldo','$cod_bar','$presentacion','$frac','$ajusteUni','$uniSaldo','$fechaVenci');";



	$linkPDO->exec($SQL_AJUSTE);



$SQL_AJUSTE="";
$SQL_UPDATES="";
}/////////// FIN WHILE SET TO CERO


$linkPDO->commit();

if($all_query_ok){
eco_alert("Guardado con Exito!");
}
else{eco_alert("ERROR! Intente nuevamente");}
}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

};
function facturar_planes_mes($hashFac,$idCliente,$serviciosArr)
{

global $hora,$nomUsu,$id_Usu,$FLUJO_INV,$ResolCredito,$FechaResolCredito,$RangoCredito,$codSuc,$codCreditoSuc,$IP_CLIENTE,$MODULES,$codCaja,$OPERACIONES,$conex,$usar_posFac,$dia_limite_pago_factura ,$linkPDO,$conex,$rolLv,$Adminlvl;


try {


$linkPDO->beginTransaction();
$all_query_ok=true;
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));











$MesaID="";
if($MODULES["mesas_pedidos"]==1){
$MesaID=r("id_mesa");

}

$cotiza_a_fac=0;

$idCliente=r("idCliente");
$FechaI=r("FechaI");
$FechaF=r("FechaF");

$filtroA="";$filtroB="";$filtroC="";$filtroFacturaSeleccionada="";





$n_r=0;
$n_s=0;
$hh=0;
$cod_su=0;
$TC=tasa_cambio();
if(isset($_SESSION['cod_su']))$cod_su=$_SESSION['cod_su'];

if(isset($_REQUEST['exi_ref']))$n_r=$_REQUEST['exi_ref'];

if(isset($_REQUEST['exi_serv']))$n_s=$_REQUEST['exi_serv'];
$num_fac="";

$sql="SELECT * FROM usuarios WHERE id_usu='$idCliente'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
	$cliente=$row["nombre"];
	$ced=$row["id_usu"];
	//$ced = str_replace(array('.', ','), '' , $ced);
	$cuidad=$row["cuidad"];
	$dir=$row["dir"];
	$tel=$row["tel"];
	$mail=$row["mail_cli"];



	$claper=$row["claper"];
	$coddoc=$row["coddoc"];
	$paicli=$row["paicli"];
	$depcli=$row["depcli"];
	$loccli="";
	$nomcon=$row["nomcon"];
	$regtri=$row["regtri"];
	$razsoc=$row["razsoc"];
	$snombr=$row["snombr"];
	$apelli=$row["apelli"];


$num_art=1;
$num_serv=1;

	$nota_fac=rm("nota_fac");


	$form_p="Credito";
	$tipo_cli="Mostrador";


	$ven=$nomUsu;
	$idVen=$id_Usu;

	$meca="";
	$meca2="";
	$meca3="";
	$meca4="";
	$cajero=$nomUsu;
	$fecha=$hoy;


	$codComision="";
	$tipoComi="";

	if($FLUJO_INV!=1)$fecha.=" $hora";
	//$vlr_let=$_REQUEST['vlr_let'];
	$vlr_let="";

	$fecha=$hoy;


	$sub=0;
	$iva=0;
	$dcto=0;
	$tot=0;

	$IMP_CONSUMO=0;
	$NUM_BOLSAS=0;
	$IMP_BOLSAS=0;
	$totBsF=0;
	$entrega=0;
	$entrega2=0;
	$entrega3=0;
	$cambio=0;
	if(($entrega+$entrega2)>($tot*10000))
	{
		$entrega=$tot;
		$cambio=0;
		}


	if(($totBsF*1)<0)$totBsF=0;
	$pagoBsf=r("bsF_flag");
	if($pagoBsf!=1 || $entrega2<=0)$totBsF=0;

	$nit=s('cod_su');


	$RESOL="";
	$PRE="";
	$fechaRESOL="";
	$RANGO_RESOL="";

	$NUM_PAGARE=r("num_pagare");

	$anticipo=0;
	$num_exp=0;
	$confirm_bono="";
if(empty($confirm_bono))$confirm_bono="NO";

	$num_fac=serial_fac("factura venta","POS");
	$estado="";
	$tipoResol="COM";
	//$PRE=$codContadoSuc;
	if($form_p=="Credito"){
		$estado="PENDIENTE";
		//$num_fac=serial_credito($conex);

		//$PRE=$codContadoSuc;
	}
	else
	{

	}

	$RESOLUCION_DATOS=asigna_resol_facVen($tipoResol);

	$num_fac=$RESOLUCION_DATOS["num_fac"];
	$PRE=$RESOLUCION_DATOS["PRE"];
	$RESOL=$RESOLUCION_DATOS["RESOL"];
	$fechaRESOL=$RESOLUCION_DATOS["fechaRESOL"];
	$RANGO_RESOL=$RESOLUCION_DATOS["RANGO_RESOL"];
	

	$r_fte=quitacom(r("R_FTE"));
	$r_ica=quitacom(r("R_ICA"));
	$r_iva=quitacom(r("R_IVA"));

	$r_fte_per=quitacom(r("R_FTE_PER"));
	$r_ica_per=quitacom(r("R_ICA_PER"));
	$r_iva_per=quitacom(r("R_IVA_PER"));

	$tot=$tot-$r_fte-$r_ica-$r_iva;

 
	$fe_naci="";//$_REQUEST['fe_naci'];
	$tipoImp="";//$_REQUEST['tipo_imp'];;

	$PLACA=rm("placa");

	$KM=limpianum(r("km"));
	$aliasCli=rm("aliasCli");

	$marcaMoto=rm("marca_moto");


	$TIPDOC="01";
	$FECVEN="limite_pago_facturas 00:00:00";
	$MONEDA="COP";
	$TIPCRU="L";
	$CODSUC="$codSuc";
	$NUMREF="";
	$FORIMP="01";
	$CLADET="D";
	$ORDENC="";
	$NUREMI="";
	$NORECE="";
	$EANTIE="";
	$COMODE="COP";
	$COMOHA="COP";
	$FACCAL="";
	$FETACA="0000-00-00 00:00:00";
	$FECREF="0000-00-00 00:00:00";
	$OBSREF="";
	$TEXDOC="";
	$MODEDI="";
	$NDIAN="$RESOL";
	$SOCIED="";
	$MOTDEV="";
	$CODVEN=$idVen;
	$regFiscal="5";



$idCta=r("id_cuenta");
//if($form_p=="Contado")$idCta="1";
if(empty($idCta) || $form_p=="Contado"){$idCta=val_caja_gral($form_p,"Ingresos","+");}


$columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,ciudad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,descuento,tot,entrega,cambio,modificable,nit,estado,mail,fe_naci,prefijo,usu,id_usu,resolucion,fecha_resol,rango_resol,num_exp,abono_anti,anticipo_bono,cod_caja,tot_bsf,kardex,num_pagare,entrega_bsf,r_fte,r_iva,r_ica,per_fte,per_iva,per_ica,tasa_cam,placa,km,nota,tot_tarjeta,tec2,tec3,tec4,id_cuenta,cod_comision,tipo_comi,imp_consumo,num_bolsas,impuesto_bolsas,id_vendedor,hash,marca_moto,num_mesa, TIPDOC, FECVEN, MONEDA, regFiscal, CODSUC, NUMREF, FORIMP, CLADET, ORDENC, NUREMI, NORECE, EANTIE, COMODE, COMOHA, FACCAL, FETACA, FECREF, OBSREF, TEXDOC, MODEDI, NDIAN, SOCIED, MOTDEV, claper, coddoc, paicli, depcli, nomcon, regtri, razsoc, snombr, apelli";



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$sqlA="INSERT INTO fac_venta($columnas) VALUES($num_fac,
                                               '$ced',
											   '$cliente',
											   '$dir',
											   '$tel',
											   '$cuidad',
											   '$form_p',
											   '$tipo_cli',
											   '$ven',
											   '$meca',
											   '$cajero',
											   '$fecha',
											   '$vlr_let',
											   $sub,
											   $iva,
											   $dcto,
											   $tot,
											   $entrega,
											   $cambio,
											   'si',
											   '$nit',
											   '$estado',
											   '$mail',
											   '$fe_naci',
											   '$PRE',
											   '$nomUsu',
											   '$id_Usu',
											   '$RESOL',
											   '$fechaRESOL',
											   '$RANGO_RESOL',
											   '$num_exp',
											   '$anticipo',
											   '$confirm_bono',
											   '$codCaja','$totBsF','$FLUJO_INV','$NUM_PAGARE','$entrega2',
											   '$r_fte','$r_iva','$r_ica','$r_fte_per','$r_iva_per','$r_ica_per',
											   '$TC','$PLACA','$KM','$nota_fac','$entrega3','$meca2','$meca3',
											   '$meca4','$idCta','$codComision','$tipoComi','$IMP_CONSUMO',
											   '$NUM_BOLSAS','$IMP_BOLSAS','$idVen','$hashFac','$marcaMoto','$MesaID',
											   '$TIPDOC', '$FECVEN', '$MONEDA', '$regFiscal', '$CODSUC', '$NUMREF', '$FORIMP', 
											   '$CLADET', '$ORDENC', '$NUREMI', '$NORECE', '$EANTIE', '$COMODE', '$COMOHA', '$FACCAL', 
											   '$FETACA', '$FECREF', '$OBSREF', '$TEXDOC', '$MODEDI', '$NDIAN', '$SOCIED', '$MOTDEV', '$claper', 
											   '$coddoc', '$paicli', '$depcli', '$nomcon', '$regtri', '$razsoc', '$snombr', '$apelli')";
$linkPDO->exec($sqlA);

//echo "<li>$sqlA</li>";

//$sqlM="UPDATE mesas SET estado='Ocupada', num_fac_ven='$num_fac', prefijo='$PRE', hash='$hashFac' WHERE id_mesa='$MesaID'";
//$linkPDO->exec($sqlM);



$page="";
if(isset($_REQUEST['html']))$page=$_REQUEST['html'];






if($num_exp!=0)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$sql="UPDATE exp_anticipo SET num_fac=$num_fac, prefijo='$PRE', estado='COBRADO' WHERE num_exp=$num_exp AND cod_su=$codSuc";
$linkPDO->exec($sql);


$sql="UPDATE comp_anti SET estado='COBRADO' WHERE num_exp=$num_exp AND cod_su=$codSuc";
$linkPDO->exec($sql);


}




$id_fac=0;

$linkPDO->exec("SAVEPOINT A");
$sql="SELECT num_fac_ven FROM fac_venta WHERE num_fac_ven=$num_fac AND prefijo='$PRE' and nit='$codSuc' FOR UPDATE" ;
$stmt = $linkPDO->query($sql);
if ($row = $stmt->fetch()) {





	$update="";
	$II=0;
	$UPDATE[]="";
	$color="";
	$talla="";
	//eco_alert("$num_art");
	//echo "<li>$num_art </li>";


	for($i=0;$i<$num_art;$i++)
	{

if($MODULES["SERVICIOS"]==1){
if(1)
{


foreach($serviciosArr as $key => $value){
$sql="INSERT INTO serv_fac_ven(num_fac_ven,prefijo,id_serv,serv,iva,pvp,cod_serv,
cod_su,nota,id_tec,hash,anchobanda,estrato,tipo_cliente)
VALUES('$num_fac','$PRE','$value[idServ]','$value[serv]','$value[ivaServ]',
 '$value[pvpServ]','$value[codServ]','$codSuc','$value[nota]','$value[tec_serv]',
 '$hashFac','$value[anchobanda]', '$value[estratoPlan]','$value[tipo_cliente]')";
	//eco_alert("$sqlServ");
$linkPDO->exec($sql);
}

}

}
	}//FIN FOR ARTICULOS



totFacVen($num_fac,$PRE,$codSuc);
//echo "pre IF inv --";



if($usar_posFac==0 || ( val_secc($id_Usu,"caja_centro")  || $rolLv==$Adminlvl)){
	up_cta($form_p,$idCta,($tot-$anticipo),"+","Venta $PRE-$num_fac","Factura Venta",$fecha);
	$sql="UPDATE fac_venta SET anulado='CERRADA' WHERE num_fac_ven='$num_fac' AND prefijo='$PRE' AND nit=$codSuc";
	$linkPDO->exec($sql);

}
}



if($all_query_ok){
$linkPDO->commit();
echo 1;
//echo "<li>$num_fac,$PRE,$codSuc,$hashFac</li>";


$rs=null;
$stmt=null;
$linkPDO= null;


}

else{echo 0;}

}


}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

};
function ejeSql($SQL){


try{
set_time_limit(180);
t1($SQL);
echo "<li>1 >>$SQL</li>";
}
catch(Exception $e){}
};

function get_last_words($amount, $string)
{
    $amount+=1;
    $string_array = explode(' ', $string);
    $totalwords= str_word_count($string, 1, 'àáãç3');
    if($totalwords > $amount){
        $words= implode(' ',array_slice($string_array, count($string_array) - $amount));
    }else{
        $words= implode(' ',array_slice($string_array, count($string_array) - $totalwords));
    }

    return $words;
};
function asigna_resol_remi($tipoResol)
{
	global $codPapelSuc,$ResolPapel,$FechaResolPapel,$conex,$codCreditoSuc,$ResolCredito,$FechaResolCredito,$codContadoSuc,$ResolContado,$FechaResolContado,$codCreditoANTSuc,$ResolCreditoANT,$FechaResolCreditoANT,$codRemiPOS,$ResolRemiPOS,$FechaRemiPOS,$RangoRemiPOS,$codRemiCOM,$ResolRemiCOM,$FechaRemiCOM,$RangoRemiCOM,$codRemiCOM2,$ResolRemiCOM2,$FechaRemiCOM2,$RangoRemiCOM2,$cross_fac,$RangoCredito,$RangoPapel,$RangoCreditoANT;

		$num_fac=0;
		$PRE=0;
		$RESOL=0;
		$fechaRESOL=0;
		$RANGO_RESOL=0;

		$RESP=array();

	if($tipoResol=="POS")
	{
		$num_fac=serial_fac("remision","REM_POS","fac_remi");
		$PRE=$codRemiPOS;
		$RESOL=$ResolRemiPOS;
		$fechaRESOL=$FechaRemiPOS;
		$RANGO_RESOL=$RangoRemiPOS;

	}
	else if($tipoResol=="COM"){

		$num_fac=serial_fac("remision_com","REM_COM","fac_remi");
		$PRE=$codRemiCOM;
		$RESOL=$ResolRemiCOM;
		$fechaRESOL=$FechaRemiCOM;
		$RANGO_RESOL=$RangoRemiCOM;

	}

	else if($tipoResol=="COM2"){

		$num_fac=serial_fac("remision_com2","REM_COM2","fac_remi");
		$PRE=$codRemiCOM2;
		$RESOL=$ResolRemiCOM2;
		$fechaRESOL=$FechaRemiCOM2;
		$RANGO_RESOL=$RangoRemiCOM2;

	}

	else if($tipoResol=="CRE_ANT"){

		$num_fac=serial_fac("cartera_ant","CRE","fac_remi");
		$PRE=$codCreditoANTSuc;
		$RESOL=$ResolCreditoANT;
		$fechaRESOL=$FechaResolCreditoANT;
		$RANGO_RESOL=$RangoCreditoANT;

	}

		//$fechaFullRes= strtotime("+ 2 years", strtotime($fechaRESOL));
		$fechaFullRes= $fechaRESOL;
		$RESP["num_fac"]=$num_fac;
		$RESP["PRE"]=$PRE;
		$RESP["RESOL"]=$RESOL;
		$RESP["fechaRESOL"]=$fechaFullRes;
		$RESP["RANGO_RESOL"]=$RANGO_RESOL;

		return $RESP;



};
function asigna_resol_facVen($tipoResol)
{
	global $codPapelSuc,$ResolPapel,$FechaResolPapel,$conex,$codCreditoSuc,$ResolCredito,$FechaResolCredito,$codContadoSuc,$ResolContado,$FechaResolContado,$codCreditoANTSuc,$ResolCreditoANT,$FechaResolCreditoANT,$codRemiPOS,$ResolRemiPOS,$FechaRemiPOS,$RangoRemiPOS,$codRemiCOM,$ResolRemiCOM,$FechaRemiCOM,$RangoRemiCOM,$codRemiCOM2,$ResolRemiCOM2,$FechaRemiCOM2,$RangoRemiCOM2,$cross_fac,$RangoContado,$RangoCredito,$RangoPapel,$RangoCreditoANT;

		$num_fac=0;
		$PRE=0;
		$RESOL=0;
		$fechaRESOL=0;
		$RANGO_RESOL=0;

		$RESP=array();

	if($tipoResol=="POS")
	{
		$num_fac=serial_fac("factura venta","POS");
		$PRE=$codContadoSuc;
		$RESOL=$ResolContado;
		$fechaRESOL=$FechaResolContado;
		$RANGO_RESOL=$RangoContado;

	}
	else if($tipoResol=="COM"){

		$num_fac=serial_fac("credito","COM");
		$PRE=$codCreditoSuc;
		$RESOL=$ResolCredito;
		$fechaRESOL=$FechaResolCredito;
		$RANGO_RESOL=$RangoCredito;

	}

	else if($tipoResol=="PAPEL")
	{
		$num_fac=serial_fac("resol_papel","PAPEL");
		$PRE=$codPapelSuc;
		$RESOL=$ResolPapel;
		$fechaRESOL=$FechaResolPapel;
		$RANGO_RESOL=$RangoPapel;

	}

	else if($tipoResol=="COM_ANT"){

		$num_fac=serial_fac("credito_ant","COM");
		$PRE=$codCreditoSuc;
		$RESOL=$ResolCredito;
		$fechaRESOL=$FechaResolCredito;
		$RANGO_RESOL=$RangoCredito;

	}

	else if($tipoResol=="PAPEL_ANT")
	{
		$num_fac=serial_fac("resol_papel_ant","PAPEL");
		$PRE=$codPapelSuc;
		$RESOL=$ResolPapel;
		$fechaRESOL=$FechaResolPapel;
		$RANGO_RESOL=$RangoPapel;

	}

	else if($tipoResol=="CRE_ANT"){

		$num_fac=serial_fac("cartera_ant","CRE");
		$PRE=$codCreditoANTSuc;
		$RESOL=$ResolCreditoANT;
		$fechaRESOL=$FechaResolCreditoANT;
		$RANGO_RESOL=$RangoCreditoANT;

	}


		//$fechaFullRes= strtotime("+ 2 years", strtotime($fechaRESOL));
		$fechaFullRes=$fechaRESOL;
		$RESP["num_fac"]=$num_fac;
		$RESP["PRE"]=$PRE;
		$RESP["RESOL"]=$RESOL;
		$RESP["fechaRESOL"]=$fechaFullRes;
		$RESP["RANGO_RESOL"]=$RANGO_RESOL;

		return $RESP;



};
function credenciales_sms()
{
	global $linkPDO, $codSuc;

	$resp["usuario"]="";
	$resp["clave"]="";

	$sql="SELECT * FROM sucursal WHERE cod_su='$codSuc'";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{
		$resp["usuario"]=$row["usu_sms"];
		$resp["clave"]=$row["cla_sms"];
	}

	return $resp;

};
function SQLfiltro_busquedaCampo_multiOpcion($columnaSQL,$arrayOpciones)
{
	$filtroSQL="";
if(isset($arrayOpciones)&&!empty($arrayOpciones))
{
	$filtroSQL="AND (";
	foreach($arrayOpciones as $key=> $resultado)
	{
		$filtroSQL.="$columnaSQL='$resultado' OR ";
	}
	$filtroSQL.=" $columnaSQL='$resultado') ";
	if(empty($resultado))$filtroSQL="";
	}

	return $filtroSQL;

};



function imgBase64($urlImg)
{
    $path = $urlImg;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64Img = 'data:image/' . $type . ';base64,' . base64_encode($data);	
	return $base64Img;
}
function getQRcode($url){
    ob_start();
    $returnData = QRcode::pngString($url,false, "H", 3, 1);
    $imageString = base64_encode(ob_get_contents());
    ob_end_clean();
    $str = "data:image/png;base64," . $imageString;
    return $str;
}
function limpiaEspaciosVacios($string)
{
    $final = preg_replace('/\s+/', '', $string);
	
	return $final;	
}

function eliminar_acentos($cadena){
		
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}
	
	function invBuscaHistorial($ref, $codBar, $feVencimiento,$tipoDoc){
		global $usarInventarioLegacy,$inventarioLegacyInvInter,$inventarioLegacyProductos,$linkPDO,$codSuc,$Adminlvl,$id_Usu,$rolLv;
		
		if($usarInventarioLegacy){
			
			$filtroCant="";
			
			$refOPT="";
			$feVenOPT="";
			$rowsSql="ubicacion,exist,i.id_pro ref,p.detalle,precio_v,iva,costo,gana,color,talla,fab,id_inter,dcto3,fraccion,unidades_frac,fecha_vencimiento,p.presentacion as prese,pvp_credito";
			
			if(!empty($ref))$refOPT="id_pro='$ref' AND ";
			else {$refOPT="id_pro='$codBar' OR";}
			
			if(!empty($feVencimiento) && $feVencimiento!="0000-00-00")$sql="SELECT $rowsSql FROM ".$inventarioLegacyProductos." p INNER JOIN (SELECT * FROM $inventarioLegacyInvInter WHERE $refOPT $inventarioLegacyInvInter.id_inter='$codBar' AND fecha_vencimiento='$feVencimiento'  $filtroCant ) AS i ON i.id_pro=p.id_pro WHERE  nit_scs=$codSuc";
			
			else $sql="SELECT $rowsSql FROM ".$inventarioLegacyProductos." p INNER JOIN (SELECT * FROM $inventarioLegacyInvInter WHERE $refOPT  $inventarioLegacyInvInter.id_inter='$codBar'  $filtroCant ) AS i ON i.id_pro=p.id_pro WHERE  nit_scs=$codSuc";

			$rs=$linkPDO->query($sql);
			
			$resp=1;
			$filtro="";
			$filtroFEchaVEnci="";
			
			$nr2=$rs->rowCount();;
			if($nr2>1)$resp=2;
			$rs=$linkPDO->query($sql);
			
			$desc=0;
			$desc_per=0;
			if(($rolLv==$Adminlvl || val_secc($id_Usu,"descuento_fac")) && $codSuc>0){$desc=1;}
			
			if(($rolLv==$Adminlvl || val_secc($id_Usu,"dcto_per")) && $codSuc>0){$desc_per=1;}
			
			if($row=$rs->fetch()){
						
						$cant=100000000;
						$des = $row["detalle"];
						$cod_bar = $row["id_inter"];
						$color = $row["color"];
						$talla = $row["talla"]; 
						$dcto3 = $row["dcto3"]*1;
						$id = $row["ref"];
						$pvp =$row["precio_v"]*1;
						$iva = $row["iva"];
						$costo= $row["costo"]*(1+($iva/100));
						$ref=$row['ref'];
						$util=$row['gana'];
						$fab=$row['fab'];
						$frac=$row['fraccion'];
						$uni=$row['unidades_frac'];
						if($frac==0 || empty($frac))$frac=1;
						$factor=($uni/$frac)+$cant;
						$feVen=$row['fecha_vencimiento'];
						$presentacion=$row['prese'];
						$pvpCredito=$row['pvp_credito']*1;
						if($pvpCredito==0)$pvpCredito=$pvp;
						$ubica=$row["ubicacion"];
						if($util>=40)$util=($util-20)/100;
						else if ($util<40&&($util-20)>20)$util=($util-20)/100;
						else $util=(20)/100;
						
						if($util==1)$util=0.5;
						if($iva==0)$valBase=($costo/(1-$util));
						else $valBase=($costo/(1-$util))*(1+($iva/100));
						$util=$row['gana'];
						$valBase=$costo;
						if($dcto3==0)$dcto3="";
					    $html="$factor|$ref|$des|$iva|$pvp|$costo|$valBase|$util|$color|$talla|$fab|".
						"$cod_bar|$dcto3|$desc|$resp|$frac|$uni|$presentacion|$feVen|$desc_per|$pvpCredito|$filtroFEchaVEnci|$ubica";
			
						return $html;
			}
			else {
				  return '-2';
				 }
			
			
		} else {
				return '-2';
			   }
		
	}
	
	function invAlteraCantidades($params){
		global $linkPDO;
		$respuesta = 0;
		if($params['tipoOperacion']=='+'){
			if(invValidaRegistro($params)==0){
				
				$respuesta = invCreaRegistro($params)==1?1:0;
				
			} else {
				
				$sql="UPDATE `inv_inter`  
				SET exist=(exist+".$params['datosProducto']['cant']."), 
				unidades_frac=(unidades_frac+".$params['datosProducto']['unidades_frac'].") 
				WHERE nit_scs='".$params['codSuc']."' 
				AND fecha_vencimiento='".$params['datosProducto']['fecha_vencimiento']."' 
				AND id_pro='".$params['datosProducto']['ref']."' 
				AND id_inter='".$params['datosProducto']['cod_bar']."'";
				$linkPDO->exec($sql);
				$respuesta = 1;
			}
			
		} 
		
		
		return $respuesta;
	}
	
	/*
	 *Valida si un producto del inventario existe o no
	 **/
	function invValidaRegistro($params){
		global $linkPDO;
		$sql="SELECT * FROM inv_inter WHERE nit_scs='".$params['codSuc']."' AND fecha_vencimiento='".$params['datosProducto']['fecha_vencimiento']."' 
			AND id_pro='".$params['datosProducto']['ref']."' AND id_inter='".$params['datosProducto']['cod_bar']."' ";

		$rs=$linkPDO->query($sql);
		if($row=$rs->fetch()){
			return 1;
		} else {
			return 0;	
		}
		
	}
	
	function invConsultaDataProductoHistorial($ref, $codBar, $feVencimiento, $fuente = 'current'){
		global $usarInventarioLegacy,$inventarioLegacyInvInter,$inventarioLegacyProductos,$linkPDO,$codSuc,$Adminlvl,$id_Usu,$rolLv;
		
		if($fuente=='current'){
			$tablaInventarioInterno = 'inv_inter';
			$tablaProductos = 'productos';
		} else {
			$tablaInventarioInterno = $inventarioLegacyInvInter;
			$tablaProductos = $inventarioLegacyProductos;	
		}
		
		$sql = "SELECT inv.id_pro,   				inv.id_inter, 		inv.exist, inv.costo,
					   inv.precio_v, 				inv.fraccion, 		inv.iva,   inv.color,
					   inv.talla,    				inv.presentacion,	inv.fecha_vencimiento,
					   inv.campo_add_01,			inv.campo_add_02,	inv.unidades_frac,
					   inv.certificado_importacion,	inv.pvp_credito,	inv.marcas,
					   inv.envase,					inv.ubicacion,		inv.pvp_may,
					   inv.aplica_vehi,				inv.tipo_producto,	inv.cod_color,
					   inv.vigencia_inicial,		inv.grupo_destino,
					   pro.detalle,					pro.id_clase,		pro.fab,
					   pro.id_sub_clase,			pro.nit_proveedor,	pro.url_img,
					   pro.des_full
				FROM $tablaInventarioInterno inv LEFT JOIN $tablaProductos pro
				ON inv.id_pro=pro.id_pro WHERE inv.id_pro='$ref' AND inv.id_inter='$codBar' AND inv.fecha_vencimiento='$feVencimiento' LIMIT 1";
		$rs=$linkPDO->query($sql);
		if($row=$rs->fetch()){
			return $row;
		} else {
			return 0;	
		}
				
		
	}
	
	/*
	 *Valida si un producto del inventario existe o no
	 **/
	function invCreaRegistro($params){
		global $linkPDO;
		$datosProducto = invConsultaDataProductoHistorial($params['datosProducto']['ref'], 
														  $params['datosProducto']['cod_bar'], 
														  $params['datosProducto']['fecha_vencimiento'], 'legacy');
		
		/*
		'datosProducto'=>array(					   'cant'=>$cant,
												   'fraccion'=>$frac,
												   'unidades_frac'=>$uni,
												   'ref'=>$ref,
												   'cod_bar'=>$cod_bar,
												   'fecha_vencimiento'=>$feVenci,
												   'detalle'=>$det,
												   'iva'=>$iva,
												   'precio_v'=>$precio,
												   'costo'=>$costoRef,
												   'color'=>$color,
												   'talla'=>$talla,
												   'presentacion'=>$presen,
												   'codSuc'=>$codSuc)
		*/
		
		$insertProducto="INSERT IGNORE INTO productos (id_pro,detalle,id_clase,frac,fab,presentacion,nit_proveedor) 
		           
				   VALUES ('".$datosProducto['id_pro']."',
				           '".$datosProducto['detalle']."',
						   '".$datosProducto['id_clase']."',
						   '".$datosProducto['fraccion']."',
						   '".$datosProducto['fab']."',
						   '".$datosProducto['presentacion']."',
						   '".$datosProducto['nit_proveedor']."');";
		$linkPDO->exec($insertProducto);
		
		$insertInventarioSql="INSERT IGNORE INTO  inv_inter  (
					   id_pro,   				id_inter, 		exist, costo,
					   precio_v, 				fraccion, 		iva,   color,
					   talla,    				presentacion,	fecha_vencimiento,
					   campo_add_01,			campo_add_02,	unidades_frac,
					   certificado_importacion,	pvp_credito,	marcas,
					   envase,					ubicacion,		pvp_may,
					   aplica_vehi,				tipo_producto,	cod_color,
					   vigencia_inicial,		grupo_destino,	nit_scs) 
					   
					   VALUES (
					   '$datosProducto[id_pro]',   					'$datosProducto[id_inter]', 		'".$params['datosProducto']['cant']."', '$datosProducto[costo]',
					   '$datosProducto[precio_v]', 					'$datosProducto[fraccion]', 		'$datosProducto[iva]',   '$datosProducto[color]',
					   '$datosProducto[talla]',    					'$datosProducto[presentacion]',		'$datosProducto[fecha_vencimiento]',
					   '$datosProducto[campo_add_01]',				'$datosProducto[campo_add_02]',		'".$params['datosProducto']['unidades_frac']."',
					   '$datosProducto[certificado_importacion]',	'$datosProducto[pvp_credito]',		'$datosProducto[marcas]',
					   '$datosProducto[envase]',					'$datosProducto[ubicacion]',		'$datosProducto[pvp_may]',
					   '$datosProducto[aplica_vehi]',				'$datosProducto[tipo_producto]',	'$datosProducto[cod_color]',
					   '$datosProducto[vigencia_inicial]',			'$datosProducto[grupo_destino]',	'".$params['codSuc']."');";
			
			$linkPDO->exec($insertInventarioSql);
			
			return 1;
		
		
	}

	function buscaString($busca,$texto){

        $pos = strpos($texto, $busca);
		if($pos === false){
			return false;
		} else {
			return true;
		}
	}



	function imprimirProductosServicios($params){
        global $linkPDO;
        $sqlProductos = "SELECT des,cant,unidades_fraccion,sub_tot 
		                 FROM art_fac_ven 
					     WHERE num_fac_ven='".$params['numFac']."' 
						 AND prefijo='".$params['prefijo']."' 
						 AND nit='".$params['codSuc']."' ORDER BY orden_in";
		$rs=$linkPDO->query($sqlProductos);
		$lista = '';
		while($row=$rs->fetch()){
			$unit = $row['unidades_fraccion']!=0 ? $row['unidades_fraccion']:1;
			$existencias = $row['cant']/$unit;
			$lista.="
			<br>• ".$row['des']." x$existencias;
			";
            
		}
		return $lista;
	}

/**
* Nanimo Software
* consultaNumeroCuotas
* Descripcion: Valida estado de cada mesa, si tiene estado 'Ocupada' pero no tiene facturas relacionadas, se cambia el estado
* a 'Disponible'.
* @access public
* @author  Gustavo Bocanegra <gustavo_bocanegra@hotmail.com>
* @copyright: Copyright (c) 2023 Nanimo Software.
* @version 1.0 16/03/2023
* @return void
*
**/
	function validaMesas() {
        global $linkPDO, $codSuc;
		$i=0;
		$linkPDO->beginTransaction();
		$sqlConsultaMesas = "SELECT me.id_mesa,me.num_mesa,me.estado,me.num_fac_ven,me.prefijo,fac.anulado 
		                     FROM mesas me LEFT JOIN fac_venta fac 
							 ON me.num_fac_ven=fac.num_fac_ven AND me.prefijo=fac.prefijo AND me.cod_su=fac.nit 
		                     WHERE cod_su='".$codSuc."' FOR UPDATE";

		$rs=$linkPDO->query($sqlConsultaMesas);
		while($row=$rs->fetch()){
			$i++;
			$linkPDO->exec("SAVEPOINT loop".$i);
            $estado = $row["estado"];
			if($estado=='Ocupada') {
				if(empty($row["num_fac_ven"]) || empty($row["prefijo"]) || $row['anulado']=='CERRADA') {
                    $updateMesa = "UPDATE mesas SET estado='Disponible', 
					               num_fac_ven='', prefijo='', valor=0,
								   hash='' 
								   WHERE id_mesa='$row[id_mesa]'";
					$linkPDO->exec($updateMesa);
				}
			}

			if($estado=='Disponible') {
				$updateMesa = "UPDATE mesas 
				SET	num_fac_ven='', prefijo='', valor=0,
				hash='' 
				WHERE id_mesa='$row[id_mesa]'";
                $linkPDO->exec($updateMesa);
			}
		}
		$linkPDO->commit();
	}

/**
* Nanimo Software
* actualizaInventario
* Descripcion: Valida estado de cada mesa, si tiene estado 'Ocupada' pero no tiene facturas relacionadas, se cambia el estado
* a 'Disponible'.
* @access public
* @author  Gustavo Bocanegra <gustavo_bocanegra@hotmail.com>
* @copyright: Copyright (c) 2023 Nanimo Software.
* @version 1.0 20/03/2023
* @return void
*
**/
function actualizaInventario() {

}



function bloquearSistema() {
	
    global $linkPDO, $codSuc;

	$estadoPago = '';
	$sql="SELECT estado_pago FROM  sucursal WHERE cod_su= $codSuc ";
	//echo "$sql";
    $rs=$linkPDO->query($sql);
		if($row=$rs->fetch()){
			$estadoPago = $row['estado_pago'];
		}
	
	if($estadoPago=='BLOQUEADO') {
		header("location: bloqueoExcesoDepago.php");
	}
}

/**
* Nanimo Software
* conversorCantidadesFracciones
* Descripcion: Aplica fórmula para retornar cantidades y unidades despues de cualquier operacion (suma o resta de inventario).
* @access public
* @param $parametros
*        cant          : (integer) resultado de la operacion aritmética inicial de cantidad
*        unidades_frac : (integer) resultado de la operacion aritmética inicial de unidades fraccion, puede ser negativo
*        fraccion      : (integer) fracciones del producto de inventario. Valores entre 1 - x
* @author  Gustavo Bocanegra <gustavo_bocanegra@hotmail.com>
* @copyright: Copyright (c) 2023 Nanimo Software.
* @version 1.0 19/05/2023
* @return array
*
**/
function conversorCantidadesFracciones($params){
    $cantFinal = $params['cant'];
    $unidadesFinal = $params['unidades_frac'];

    // loop sumar  cantidades y deducir unidades
    while ($unidadesFinal >= $params['fraccion']) {
        $unidadesFinal = $unidadesFinal - $params['fraccion'];
        $cantFinal++;
    }

    // loop restar  cantidades y sumar unidades
    while ($unidadesFinal < 0) {
        $unidadesFinal = $unidadesFinal + $params['fraccion'];
        $cantFinal--;
    }

    return array('cant'=>$cantFinal, 'unidades_frac'=>$unidadesFinal);

}

/**
* Nanimo Software
* informeIvas
* Descripcion: Aplica fórmula para retornar cantidades y unidades despues de cualquier operacion (suma o resta de inventario).
* @access public
* @param $parametros
*        costo         : (integer) resultado de la operacion aritmética inicial de cantidad
*        iva           : (integer) resultado de la operacion aritmética inicial de cantidad
*        cant          : (integer) resultado de la operacion aritmética inicial de cantidad
*        unidades_frac : (integer) resultado de la operacion aritmética inicial de unidades fraccion, puede ser negativo
*        fraccion      : (integer) fracciones del producto de inventario. Valores entre 1 - x
* @author  Gustavo Bocanegra <gustavo_bocanegra@hotmail.com>
* @copyright: Copyright (c) 2023 Nanimo Software.
* @version 1.0 19/05/2023
* @return array
*
**/
function informeIvas($params){
    $cantFinal = $params['cant'];
    $unidadesFinal = $params['unidades_frac'];
    
	$iva19=0;
	$iva05=0;
	$iva10=0;

    

    //return array('cant'=>$cantFinal, 'unidades_frac'=>$unidadesFinal);

}


function quitaSaltosLinea($string){
	return str_replace(array("\r", "\n"), '', $string);
}


function creaPermisosUsuarioVentas($idCli){
	global $linkPDO;
	try {
		$sql="INSERT IGNORE INTO permisos(id_usu,id_secc,permite) VALUES('$idCli','adm_art','Si'),
																('$idCli','adm_serv','Si'),
																('$idCli','caja_centro','Si'),
																('$idCli','clientes','Si'),
																('$idCli','dcto_despues_iva','Si'),
																('$idCli','dcto_indi','Si'),
																('$idCli','descuento_fac','Si'),
																('$idCli','fac_crea','Si'),
																('$idCli','fac_lista','Si'),
																('$idCli','fac_mod','Si'),
																('$idCli','fac_serv','Si'),
																('$idCli','inventario','Si'),
																('$idCli','dcto_per','Si')";
//echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>Query: <br> $sql";
		$linkPDO->exec($sql);

		return 1;
	}
	catch(Exception $e){
		$sql="INSERT IGNORE INTO permisos(id_usu,id_secc,permite) VALUES('$idCli','adm_art','Si'),
		('$idCli','adm_serv','Si'),
		('$idCli','caja_centro','Si'),
		('$idCli','clientes','Si'),
		('$idCli','dcto_despues_iva','Si'),
		('$idCli','dcto_indi','Si'),
		('$idCli','descuento_fac','Si'),
		('$idCli','fac_crea','Si'),
		('$idCli','fac_lista','Si'),
		('$idCli','fac_mod','Si'),
		('$idCli','fac_serv','Si'),
		('$idCli','inventario','Si'),
		('$idCli','dcto_per','Si')";
		echo 'Error:'.$e.'<br>';
		echo "Query: <br> $sql";

	}
}



mysqli_close($conex);
