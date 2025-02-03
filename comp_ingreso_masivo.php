<?php
require_once('Conexxx.php');
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
$sql="SELECT * FROM fac_venta WHERE id_cli='$id_cli' AND tipo_venta='Credito' AND estado='PENDIENTE' AND ".VALIDACION_VENTA_VALIDA." AND nit=$codSuc ORDER BY fecha ASC ";
//echo "<li>$sql/li>";
if($boton=="guardar"&&isset($_REQUEST['valor'])&&isset($_REQUEST['fecha_pago'])&&isset($_REQUEST['id_cli']))
{
	$forma_pago=r("form_pago");
	$idCli=r('id_cli');$fecha=r('fecha_pago');$fe_cu=r('fecha_pago');$valor=quitacom(r('valor'));$concepto=r('concepto');
	$html=r('htmlPag');$fecha.=" $hora";

$fechaI=r("fechaI");
$fechaF=r("fechaF");




try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


$STATS=tot_abon_cre_rango($fechaI,$fechaF);
$num_com=serial_comp_ingreso($conex);
$idCta=r("id_cuenta");
 
if(empty($idCta) || $forma_pago=="Contado"){$idCta=val_caja_gral($forma_pago,"Ingresos","+");}

if($STATS["abono"]<$STATS["tot"]){
		
if(($STATS["abono"]+$valor)<=$STATS["tot"])
{
	
$linkPDO->exec("SAVEPOINT inicio");
$sql="INSERT INTO comprobante_ingreso(num_com,cod_su,fecha,fecha_cuota,valor,cajero,concepto,cod_caja,id_banco,id_cuenta,id_cli,forma_pago,tipo_operacion) VALUES($num_com,'$codSuc','$fecha','$fe_cu',$valor,'$nomUsu','$concepto','$codCaja','','$idCta','$idCli','$forma_pago','Masivo')";
$linkPDO->exec($sql);

$HTML_antes="NO APLICA";$HTML_despues="$html";
logDB($sql,$SECCIONES[4],$OPERACIONES[1],"$HTML_antes",$HTML_despues,$num_com);

up_cta($forma_pago,$idCta,$valor,"+","Abono Cartera Comp:$num_com","Cartera Clientes",$fecha);

$TOT_SALDO_INGRESO=$valor;
$abono_fac=0;
$SALDO=0;
$num_fac=r("num_fac");

$filtro_fecha="";
if(!empty($fechaI) && !empty($fechaF)){$filtro_fecha=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'   )";}

if($TOT_SALDO_INGRESO>0){
$sql="SELECT * FROM fac_venta WHERE  tipo_venta='Credito' AND estado='PENDIENTE' AND ".VALIDACION_VENTA_VALIDA." AND nit=$codSuc AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'   ) ORDER BY fecha ASC FOR UPDATE";
//echo "<li>$sql/li>";
$rs1=$linkPDO->query($sql);
$i=0;
while($row1=$rs1->fetch())
{
	$i++;
	$linkPDO->exec("SAVEPOINT LoopA".$i);
	$tot_fac=$row1["tot"];
	$pre_fac=$row1["prefijo"];
	$num_fac=$row1["num_fac_ven"];
	$idCli=$row1["id_cli"];
	//echo "T_fac: $tot_fac, PRE: $pre NF: $num_fac, abon: $TOT_SALDO_INGRESO";
	$respuesta=chk_abono($num_com,$num_fac,$pre_fac,$idCli,$TOT_SALDO_INGRESO,$tot_fac,$all_query_ok,$fecha);
	$TOT_SALDO_INGRESO=$respuesta[0];
	$all_query_ok=$respuesta[1];
	
	if($TOT_SALDO_INGRESO>0){}
	else {break;}	
	$i++;
}
}
$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;
if($all_query_ok)
{
	$_SESSION['num_comp_ingre']=$num_com;
	imp_comp_ingre($num_com);
}
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
<html >
<head>
<?php require_once("HEADER.php"); ?>
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<!--<link href="JS/lightBox.css" rel="stylesheet" type="text/css" />-->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>Comprobante de Ingreso</title>
</head>

<body>
<div class="uk-width-1-1 uk-container-center">
		<form autocomplete="off" action="comp_ingreso_masivo.php" method="post" name="frm_comp" class="uk-form uk-form-stacked ">
				
				<div id="comp_ingreso_grid" class=" uk-container-center round_table_white " align="center">
				
				<div id="Qresp"></div>
				
				
							<div class="uk-grid" data-uk-grid-margin>
									
								
								
									<div class="uk-width-1-1" style="font-size:20px;">
										COMPROBANTE DE INGRESO <span style="color:red"><br><br>No. <?php echo serial_comp_ingreso($conex); ?></span>
										<input placeholder="Fecha" readonly size="20" name="fecha_pago2" value="<?php echo $hoy  ?>" type="hidden" id="fecha">
									</div>
							</div>	
                            
                            
                            
                            <div  class="uk-grid" data-uk-grid-margin>
							
								
								<div class="uk-width-1-3">								
									<label>Fecha Comprobante</label>
									<input type="date" name="fecha_pago" id="fecha_pago" value="<?php echo $FechaHoy; ?>"> 
								 </div>
								 <div class="uk-width-1-3">
									<label class="uk-text-warning">Forma de Pago</label>   
									<?php echo selc_form_pa("form_pago","form_pago","",$FP_ingresos,"uk-form-success");?>
								</div>
								
							</div>
							
                        <div class="uk-block uk-block-primary">
									<h2 class="">Filtros Pago</h2>
							<div class="uk-grid" data-uk-grid-margin>
									<div class="uk-width-1-3">
									<label>C.C/NIT Cliente</label>
									<input type="text" name="id_cli" id="id_cli" onKeyPress="codx(this,'add');"  style="width:140px"  placeholder="No. Documento" value="<?php echo "0000234-MASSIVO" ?>" readonly/>
								</div>
                                <div class="uk-width-1-3">								
									<label>Fecha Inicial</label>
									<input type="date" name="fechaI" id="fechaI" value="" onChange="confirm_cre_rango();"> 
								 </div>
                                 <div class="uk-width-1-3">								
									<label>Fecha Final</label>
									<input type="date" name="fechaF" id="fechaF" value="" onChange="confirm_cre_rango();"> 
								 </div>
                                 
							</div>
									
								</div>	
													  
									
									<div  class="uk-grid" data-uk-grid-margin>
										<div class="uk-width-1-3">
										<label class="uk-text-warning">Valor del Pago</label>
										<input type="text" name="valor" id="valor"  value=""  onKeyUp="puntoa($(this))" class="uk-form-success"/>
										</div>
										<div class="uk-width-1-3">
										<?php if($MODULES["CUENTAS_BANCOS"]==1)echo "<label class=\"uk-form-label\" for=\"id_cuenta\">Cuenta</label>".selcCta("","uk-text-primary  uk-text-bold uk-form-success"); ?>
										</div>
										<div class="uk-width-1-3 uk-text-danger" style="padding-top:22px;">
										<i id="saldo" style="font-size:24px; font-weight:bold;"></i>
										</div>
									</div>
									
									<!--
									<div>
										<?php if($MODULES["CUENTAS_BANCOS"]==1)echo "<label class=\"uk-form-label\" for=\"id_cuenta\">Cuenta</label>".selcCta("","uk-text-primary  uk-text-bold uk-form-success"); ?>
									</div>
									-->
									
									<div>
										<label>Por Concepto de:</label>
										<textarea id="concepto" name="concepto" cols="25"  rows="2" width="300px"style="font-size:20px;"></textarea>
									</div>
									
									<div>
										<input type="button" name="boton" style="border-radius:5px;" class="uk-button uk-button-primary uk-button-large uk-width-7-10"  value="Guardar" onClick="saveComp_mass($('#boton'),'guardar',document.forms['frm_comp']);" />
									</div>
			
				
				</div>
				
				<input type="hidden" value="" name="verify" id="verify" />
				<input type="hidden" value="<?php echo "$tot_fac_ven";  ?>" name="tot_fac" id="tot_fac" />
				<input type="hidden" value="" name="boton" id="boton" />
				<input type="hidden" value="" name="htmlPag" id="HTML_Pag">
		</form>

</div>
<?php require_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script language='javascript' src="JS/ingresos.js?<?php echo "$LAST_VER" ?>"></script>
<script language='javascript' >
$(document).ready(function(){
	//confirm_cre();
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