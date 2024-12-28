<?php
require_once('Conexxx.php');
valida_session();

$num_com=serial_comp_ingreso($conex);
$boton="";
$fecha="";
$fe_cu="";
$valor="";
$num_fac="";
$response="";
$concepto="";
$pre="";

if(isset($_REQUEST['nf']))$num_fac=$_REQUEST['nf'];
if(isset($_REQUEST['pre']))$pre=$_REQUEST['pre'];

if(isset($_REQUEST['boton']))$boton=$_REQUEST['boton'];


if($boton=="guardar"&&isset($_REQUEST['valor'])&&isset($_REQUEST['fecha_pago']))
{
	//echo "<br><span style:\"color:#fff;\"><b>entra IF guarda</b></span>";	
	$num_fac=0;
	$pre="";
	$forma_pago=r("form_pago");
	$fecha=limpiarcampo($_REQUEST['fecha_pago']);
	$fe_cu=limpiarcampo($_REQUEST['fecha_pago']);
	$valor=quitacom(limpiarcampo($_REQUEST['valor']));
	$concepto=limpiarcampo($_REQUEST['concepto']);
	$html=$_REQUEST['htmlPag'];
	
	$vendedor=r("vendedor");
	
	$vendedorArr=explode("-",$vendedor);
	
	$ven=$vendedorArr[0];
	$idVen=$vendedorArr[1];
//	$response=$_REQUEST['response'];
	$tot_cuotas=0;
	$val_cre=0;
$fecha.=" $hora";


try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


$num_com=serial_comp_ingreso($conex);
$sql="INSERT INTO comprobante_ingreso(num_com,num_fac,cod_su,fecha,fecha_cuota,valor,anulado,cajero,concepto,pre,cod_caja,forma_pago,id_vendedor) VALUES($num_com,0,'$codSuc','$fecha','$fe_cu',$valor,'NO','$ven','$concepto','','$ven','$forma_pago','$idVen')";
$linkPDO->exec($sql);

$idCta=r("id_cuenta");
if($forma_pago=="Contado"&& $codSuc==1)$idCta="1";
if(empty($idCta) && $codSuc==1)$idCta="1";

if(empty($idCta) || $forma_pago=="Contado"){$idCta=val_caja_gral($forma_pago,"Ingresos","+");}

up_cta($forma_pago,$idCta,$valor,"+","Abono Cartera Anterior Comp:$num_com","Cartera Clientes",$fecha);



	
	$HTML_antes="NO APLICA";
	$HTML_despues="$html";
	//echo "$sql,$SECCIONES[4],'Crear Registro',$HTML_antes,$HTML_despues,$num_fac";
	logDB($sql,$SECCIONES[4],$OPERACIONES[1],"$HTML_antes",$HTML_despues,$num_fac);
		
$linkPDO->commit();
if($all_query_ok)
{
		
		imp_a("num_comp_ingre",$num_com,"imp_comp_ingre_var.php","Comprobante de Ingreso No. $num_com","800px","600px");
}else{eco_alert("ERROR! Intente nuevamente");}

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
<link href="JS/fac_ven.css?<?php echo "$LAST_VER" ?>" rel="stylesheet" type="text/css" />
<link href="JS/lightBox.css?<?php echo "$LAST_VER" ?>" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />


<title>Comprobante de Ingreso</title>
</head>

<body>
	<form autocomplete="off" action="comp_ingreso_vario.php" method="post" name="frm_comp" class="uk-form">
		

			
			<div id="comp_ingreso_vario_grid" class=" uk-container-center round_table_white uk-width-large-9-10 uk-width-small-1-1" align="center">
					
					<div id="Qresp"></div>
								
									<div>
										<?php echo $PUBLICIDAD2 ?>
									</div>
									
								
									<div style="font-size:20px;" class="uk-width-1-1">
										COMPROBANTE DE INGRESO VARIO <span style="color:red">No. <?php echo serial_comp_ingreso($conex); ?></span>
									</div>
									
								
									
									<div>
										<input placeholder="Fecha" readonly size="20" name="fecha_pago2" value="<?php echo $hoy  ?>" type="hidden" id="fecha">
									</div>
									
								<div class="uk-grid" data-uk-grid-margin>
								
									<div class="uk-width-1-2">
										<label class="uk-form-label" for="fecha_pago">Fecha:</label>
										<input type="date" name="fecha_pago" id="fecha_pago" class="uk-form uk-form-small" value="<?php echo $FechaHoy; ?>"> 
									</div>
									
								
									
									<div class="uk-width-1-2">
										<label class="uk-form-label uk-text-warning" for="form_pago">Forma de Pago</label>
										<?php echo selc_form_pa("form_pago","form_pago","",$FP_ingresos,"uk-form-success");?>
									</div> 
    <div class="uk-width-1-1">                             
 <?php if($MODULES["CUENTAS_BANCOS"]==1)echo "<label class=\"uk-form-label\" for=\"id_cuenta\">Cuenta</label>".selcCta("","uk-text-primary  uk-text-bold uk-form-success"); ?> 
 </div>
									
								</div>
								
<div>
<i class="uk-icon-user <?php echo "" ?>"></i>
<select name="vendedor" id="vendedor"  style="width:100px" class="<?php echo "" ?>"  >
<option value="" selected="selected"></option>
<option value="<?PHP echo "$nomUsu-$id_Usu" ?>" selected><?PHP echo $nomUsu ?></option>
<?php if($rolLv==$Adminlvl || $MODULES["RES_VEN"]==0){
$rs=$linkPDO->query("SELECT nombre,a.id_usu FROM usuarios a INNER JOIN (SELECT a.estado,b.id_usu,b.des FROM usu_login a INNER JOIN tipo_usu b ON a.id_usu=b.id_usu WHERE (des='Vendedor' OR des='Caja' OR des='Inventarios' OR des='Administrador' OR des='Conductor') AND a.estado!='SUSPENDIDO') b ON b.id_usu=a.id_usu AND a.cod_su='$codSuc'  ORDER BY nombre");
while($row=$rs->fetch()){$vendedor= $row["nombre"];
$idVendedor=$row["id_usu"];
?><option value="<?php echo "$vendedor-$idVendedor" ?>" <?php if($vendedor==$nomUsu && $MODULES["VENTA_VEHICULOS"]!=1){echo "selected"; }?> ><?php echo $vendedor?></option>
<?php }
}
?>
</select> 
</div>
									<div>
										<label class="uk-form-label" for="valor">Valor Pago:</label>

										<div class="uk-form-icon" style="margin:0px;">
											<i class="uk-icon-usd uk-icon-large "></i>
											<input  style="font-size:16px; height:40px;" placeholder="Valor Pago" type="text" name="valor" id="valor" class="uk-form uk-form-small" value=""  onKeyUp="puntoa($(this))"/> 
										</div>
																				
									</div>
									
							
										
										<i id="saldo" style="font-size:24px; font-weight:bold;"></i>
										
								
									<div style="margin-top:30px;">
										<label class="uk-form-label" for="concepto" >Por Concepto de:</label>
										<textarea id="concepto" name="concepto" cols="25"  rows="2" width="300px"style="font-size:20px;"></textarea>
									</div>
							
									<div>
										<input style="border-radius:5px;" type="button" name="boton" class="uk-button uk-button-primary uk-button-large uk-width-7-10"  value="Guardar" onClick="saveCompVar($('#boton'),'guardar',document.forms['frm_comp']);" />
									</div>
								
			</div>
		
		<input type="hidden" value="" name="verify" id="verify" />
		<input type="hidden" value="" name="boton" id="boton" />
		<input type="hidden" value="" name="htmlPag" id="HTML_Pag">
	</form>
<?php require_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script language='javascript' src="JS/ingresos.js?<?php echo "$LAST_VER" ?>"></script> 
<script language='javascript' >
$(document).ready(function(){
	//confirm_cre();
	});

</script> 
</body>
</html>