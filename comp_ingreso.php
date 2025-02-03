<?php
require_once('Conexxx.php');
include("ajax/SMS/SMS_lib.php");
valida_session();
$num_com=serial_comp_ingreso($conex);$boton="";$fecha="";$fe_cu="";$valor="";$id_cli="";$response="";$concepto="";$pre="";
$num_fac="";
$pre="";
$id_cli="";
if(isset($_REQUEST['nf']))$num_fac=$_REQUEST['nf'];
if(isset($_REQUEST['cc']))$id_cli=$_REQUEST['cc'];
if(isset($_REQUEST['pre']))$pre=$_REQUEST['pre'];
$tot_fac_ven=r("tot_fac");
$saldo=r("saldo");

if(isset($_REQUEST['boton']))$boton=$_REQUEST['boton'];
$sql="SELECT *,(tot-r_fte-r_ica-r_iva) TOT FROM fac_venta WHERE id_cli='$id_cli' AND tipo_venta='Credito' AND estado='PENDIENTE' AND ".VALIDACION_VENTA_VALIDA." AND nit=$codSuc ORDER BY fecha ASC";
//echo "<li>$sql/li>";
if($boton=="guardar"&&isset($_REQUEST['valor'])&&isset($_REQUEST['fecha_pago'])&&isset($_REQUEST['id_cli']))
{
	$forma_pago=r("form_pago");
	$idCli=r('id_cli');$fecha=r('fecha_pago');$fe_cu=r('fecha_pago');$valor=quitacom(r('valor'));$concepto=r('concepto');
	$html=r('htmlPag');$fecha.=" $hora";


try { 
$linkPDO->beginTransaction();
$all_query_ok=true;
$linkPDO->exec("SAVEPOINT inicio");

$STATS=tot_abon_cre($idCli);
$num_com=serial_comp_ingreso($conex);
$R_FTE=quitacom(r("R_FTE"));
$R_ICA=quitacom(r("R_ICA"));

$R_FTE_PER=quitacom(r("R_FTE_PER"));
$R_ICA_PER=quitacom(r("R_ICA_PER"));

$MULTI_OPT_FAC_FILTRO=multiSelcQuery("facturas","serial_fac" );

$idCta=r("id_cuenta");

if(empty($idCta) || $forma_pago=="Contado"){$idCta=val_caja_gral($forma_pago,"Ingresos","+");}


if($STATS["abono"]<$STATS["tot"]){
		
if(($STATS["abono"]+$valor)<=$STATS["tot"])
{
$sql="INSERT INTO comprobante_ingreso(num_com,cod_su,fecha,fecha_cuota,valor,cajero,concepto,cod_caja,id_banco,id_cuenta,id_cli,forma_pago,r_fte,r_ica) 
      VALUES($num_com,'$codSuc','$fecha','$fe_cu',$valor,'$nomUsu','$concepto','$codCaja','','$idCta','$idCli','$forma_pago','$R_FTE','$R_ICA')";
$linkPDO->exec($sql);

$valor=$valor-$R_FTE-$R_ICA;
$HTML_antes="NO APLICA";$HTML_despues="$html";
//logDB($sql,$SECCIONES[4],$OPERACIONES[1],"$HTML_antes",$HTML_despues,$num_com);

up_cta($forma_pago,$idCta,$valor,"+","Abono Cartera Comp:$num_com","Cartera Clientes",$fecha);

$TOT_SALDO_INGRESO=$valor;
$abono_fac=0;
$SALDO=0;
$num_fac=r("num_fac");

$linkPDO->exec("SAVEPOINT BODY1");
if(empty($MULTI_OPT_FAC_FILTRO)){
	$respuesta=chk_abono($num_com,$num_fac,$pre,$idCli,$TOT_SALDO_INGRESO,$tot_fac_ven,$all_query_ok,$fecha);
	$TOT_SALDO_INGRESO=$respuesta[0];
	$all_query_ok=$respuesta[1];
	
	}

$FILTRO_SEDE="AND nit=$codSuc";
$nombreCliente="";
$sql="SELECT * FROM usuarios WHERE id_usu='$idCli' FOR UPDATE";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
	$nombreCliente=$row["nombre"]." ".$row["snombr"]." ".$row["apelli"];
	$telCliente=limpianum3($row["tel"]);
	
	$telArray="{\"msisdn\":\"57$telCliente\"}";
}
if($MODULES["MULTISEDES_UNIFICADAS"]==1){$FILTRO_SEDE="";}
if($TOT_SALDO_INGRESO>0){
$sql="SELECT *,(tot-r_fte-r_ica-r_iva) as TOT FROM fac_venta 
      WHERE id_cli='$idCli' AND tipo_venta='Credito' AND estado='PENDIENTE' AND ".VALIDACION_VENTA_VALIDA." $MULTI_OPT_FAC_FILTRO $FILTRO_SEDE  
	  ORDER BY fecha ASC FOR UPDATE";
//echo "<li>$sql/li>";
$rs=$linkPDO->query($sql);
$i=0;
while($row1=$rs->fetch())
{
	$i++;
	
	$linkPDO->exec("SAVEPOINT LoopA".$i);
	$tot_fac=$row1["TOT"];
	$pre_fac=$row1["prefijo"];
	$num_fac=$row1["num_fac_ven"];
	//echo "T_fac: $tot_fac, PRE: $pre NF: $num_fac, abon: $TOT_SALDO_INGRESO";
	$respuesta=chk_abono($num_com,$num_fac,$pre_fac,$idCli,$TOT_SALDO_INGRESO,$tot_fac,$all_query_ok,$fecha);
	$TOT_SALDO_INGRESO=$respuesta[0];
	$all_query_ok=$respuesta[1];
	
	if($TOT_SALDO_INGRESO>0){}
	else {break;}	
}
}


if($TOT_SALDO_INGRESO>0 && !empty($MULTI_OPT_FAC_FILTRO)){
$sql="SELECT *,(tot-r_fte-r_ica-r_iva) as TOT FROM fac_venta WHERE id_cli='$idCli' AND tipo_venta='Credito' AND estado='PENDIENTE' AND ".VALIDACION_VENTA_VALIDA."   $FILTRO_SEDE  ORDER BY fecha ASC FOR UPDATE";
//echo "<li>$sql/li>";
$rs=$linkPDO->query($sql);
$i=0;
while($row=$rs->fetch())
{
	$i++;
	
	$linkPDO->exec("SAVEPOINT LoopB".$i);
	$tot_fac=$row1["TOT"];
	$pre_fac=$row1["prefijo"];
	$num_fac=$row1["num_fac_ven"];
	//echo "T_fac: $tot_fac, PRE: $pre NF: $num_fac, abon: $TOT_SALDO_INGRESO";
	$respuesta=chk_abono($num_com,$num_fac,$pre_fac,$idCli,$TOT_SALDO_INGRESO,$tot_fac,$all_query_ok,$fecha);
	$TOT_SALDO_INGRESO=$respuesta[0];
	$all_query_ok=$respuesta[1];
	
	if($TOT_SALDO_INGRESO>0){}
	else {break;}	
}
}


$linkPDO->exec("SAVEPOINT BODY2");
auto_unban_cli($idCli);
/*
$saldo=tot_abon_cre($idCli);

$saldoTXT="";
if($saldo["saldo"]>0){$saldoTXT=" Saldo pendiente:".money($saldo["saldo"]);}


$credenciales=credenciales_sms();
$auth_basic = base64_encode("$credenciales[usuario]:$credenciales[clave]");



$SMS="Pago realizado, gracias $nombreCliente por preferir a INTERPLUS S.A.S $saldoTXT";
envia_SMS($SMS,$telArray,$auth_basic);*/

$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;
//$all_query_ok=false;
if($all_query_ok)
{


$_SESSION['num_comp_ingre']=$num_com;

$URL_print="imp_comp_ingre.php";
if($tipo_imp_comprobantes=="POS"){$URL_print="imp_comp_ingre_pos.php";}
imp_comp_ingre($num_com);


echo "<script>window.close();</script>";
js_location("comp_ingreso.php");
js_close();

}else{eco_alert("ERROR! Intente nuevamente");}
}
else if(($STATS["abono"]+$valor)>$STATS["tot"])
{eco_alert("El monto pagado excede la Deuda!");}
	
}
else if($STATS["abono"]==$STATS["tot"]){eco_alert("La Deuda ya esta PAGADA!");}
	
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
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<!--<link href="JS/lightBox.css" rel="stylesheet" type="text/css" />-->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>Comprobante de Ingreso</title>
<link href="css/multi-select.css" rel="stylesheet" type="text/css" />	
</head>

<body>
<div class="uk-width-1-1 uk-container-center">
		<form autocomplete="off" action="comp_ingreso.php" method="post" name="frm_comp" class="uk-form uk-form-stacked ">
				
				<div id="comp_ingreso_grid" class=" uk-container-center round_table_white " align="center">
				
				<div id="Qresp"></div>
				
				
							<div class="uk-grid" data-uk-grid-margin>
									 
								
								
									<div class="uk-width-1-1" style="font-size:20px;">
										COMPROBANTE DE INGRESO <span style="color:red; font-size:28px;"> No. <?php echo serial_comp_ingreso($conex); ?></span>
										<input placeholder="Fecha" readonly size="20" name="fecha_pago2" value="<?php echo $hoy  ?>" type="hidden" id="fecha">
									</div>
							</div>	
									
							<div class="uk-grid uk-hidden" data-uk-grid-margin>
									<div class="uk-width-1-2">
										<label>N&uacute;mero de Factura:</label>
										<input type="text" name="pre" id="pre" value="<?php echo $pre ?>" style="width:60px" placeholder="Prefijo" readonly>
										<input type="text" name="num_fac" id="num_fac" onKeyPress="codx(this,'add');"  style="width:140px"  placeholder="Num. Factura" value="<?php echo $num_fac?>" readonly/>
									</div>
									<div class="uk-width-1-2 uk-text-warning" style="font-size:24px; font-weight:bold;padding-top:20px;">
										<i>Saldo Factura<br><?php echo money3($saldo) ?></i>
									</div>

							</div>
									
									
							<div  class="uk-grid" data-uk-grid-margin>
							
								<div class="uk-width-1-3 uk-hidden">
									<label>C.C/NIT Cliente</label>
									<input type="text" name="id_cli" id="id_cli" onKeyPress="codx(this,'add');"  style="width:140px"  placeholder="No. Documento" value="<?php echo $id_cli?>" readonly/>
								</div>
								<div class="uk-width-1-3">								
<label class="uk-text-warning">Fecha</label> 									 
<input type="date" name="fecha_pago" id="fecha_pago" value="<?php echo $FechaHoy; ?>" style="width:150px;"> 
								 </div>
								 <div class="uk-width-1-3">
									<label class="uk-text-warning">Forma de Pago</label>   
<?php echo selc_form_pa("form_pago","form_pago","",$FP_ingresos,"uk-form-success");?>
								</div>
								<div class="uk-width-1-3">
										<?php if($MODULES["CUENTAS_BANCOS"]==1)echo "<label class=\"uk-form-label\" for=\"id_cuenta\">Cuenta Banco</label>".selcCta("","uk-text-primary  uk-text-bold uk-form-success"); ?>
										</div>
							</div>
													  
									
<div  class="uk-grid" data-uk-grid-margin>
<div class="uk-width-2-5" style="font-size: 28px;">
<label class="uk-text-warning">Valor del Pago</label>
<input type="text" name="valor" id="valor"  value=""  onKeyUp="puntoa($(this))" class="uk-form-success uk-form-width-large uk-form-large" style="width:400px; height:70px;font-size: 28px;"/>
</div>
										
<div class="uk-width-1-5 uk-text-danger" style="padding-top:22px;">
<i id="saldo" style="font-size:24px; font-weight:bold;"></i>
</div>

<div class="uk-width-1-5 uk-text-danger" style="padding-top:42px;">
<input type="button" name="boton" style="border-radius:5px;" class="uk-button uk-button-primary uk-button-large uk-width-7-10"  value="Guardar" onClick="saveComp($('#boton'),'guardar',document.forms['frm_comp']);" />
</div>
</div>


<div  class="uk-grid" data-uk-grid-margin>
<div class="uk-width-1-5">
<label class="uk-form-label" for="R_FTE_PER">
 R. FTE:
 </label>
<input placeholder="%" id="R_FTE_PER" type="text"  value=" " name="R_FTE_PER"  style="width:50px" onKeyUp="calc_per_a($(this),$('#valor'),$('#R_FTE'));" class="uk-hidden"/> 
<input id="R_FTE" type="text"  value="0" name="R_FTE" class=""/>
</div>
<div class="uk-width-1-5">
<label class="uk-form-label" for="R_ICA_PER">
 R. ICA: 
 </label>
<input placeholder="%" id="R_ICA_PER" type="text"  value="" name="R_ICA_PER"  style="width:50px" onKeyUp="calc_per_a($(this),$('#valor'),$('#R_ICA'));" class="uk-hidden"/> 
<input id="R_ICA" type="text"  value="0" name="R_ICA" class=""/>
 </div>                                       

 <div class="uk-width-3-5">
<label>Por Concepto de:</label>
<textarea id="concepto" name="concepto" cols="25"  rows="2" width="300px"style="font-size:20px;">Pago</textarea>
</div>

                                        
</div>

<div  class="uk-grid uk-hiddens" data-uk-grid-margin>

 <div class="uk-width-1-1"id="filtroFacBox" >
                                        <!-- (idBox,selectedEle,nameEle,idEle,claseEle,table,where,idCol,desCol) -->
                                        <?php
                                        $whereFacturasFIltro="WHERE id_cli=\'$id_cli\' AND tipo_venta=\'Credito\' AND anulado!=\'ANULADO\' AND estado=\'PENDIENTE\' ORDER BY fecha DESC";
										
										?>
<input type="button" name="botonFiltro" value="Ver Lista" onClick="addMultiSelc('filtroFacBox','','facturas[]','facturas','','fac_venta','<?php echo "$whereFacturasFIltro";?>','serial_fac','SELECT concat(prefijo,num_fac_ven,\' TOT: $\',(SELECT FORMAT((tot-r_fte-r_ica-r_iva),0)))');" class="uk-button uk-button-success uk-button-large uk-width-1-2">
</div>
</div>
									
 
									

									

			
				
				</div>
				
				<input type="hidden" value="" name="verify" id="verify" />
				<input type="hidden" value="<?php echo "$tot_fac_ven";  ?>" name="tot_fac" id="tot_fac" />
				<input type="hidden" value="" name="boton" id="boton" />
				<input type="hidden" value="" name="htmlPag" id="HTML_Pag">
		</form>

</div>
<?php require_once("FOOTER.php"); ?>
<script language="javascript" type="text/javascript" src="JS/jquery.multi-select.js?<?php echo "$LAST_VER" ?>"></script>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script language='javascript' src="JS/ingresos.js?<?php echo "$LAST_VER" ?>"></script>
<script language='javascript' >
$(document).ready(function(){
	confirm_cre();
	});


function codx(n,op){

	if(!esVacio(n.value))
	{
		
	if(window.XMLHttpRequest){
    var entra=document.getElementById(n.id);
    entra.onkeyup=function(){
	var evento=arguments[0];
	key=parseInt(evento.keyCode);
	if(key==13&&op=='add'){confirm_cre()}
	//if(key==13&&op=='mod'){add_art_ven();}
	if(key==120){}
	
	}
	}
	else {
	var entra=document.getElementById(n.id);
    entra.onkeyup=function(){
	var evento=window.event;
	 key=parseInt(evento.keyCode);
	 if(key==13&&op=='add'){confirm_cre()}
	 if(key==120){}   
		}
	}
	
	}
	
	};

</script> 
<script type="text/javascript">
$('input').on("change",function(){$(this).prop('value',this.value);});
$('textarea').on("change",function(){$(this).html(this.value);});
</script>
</body>
</html>