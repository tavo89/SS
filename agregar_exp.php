<?php
include("Conexxx.php");
$ThisURL="agregar_exp.php";
$boton="";
if(isset($_REQUEST['boton']))$boton=$_REQUEST['boton'];

if($boton=="Guardar")
{
$fecha=r("fecha");
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


$tipo_pago=limpiarcampo($_REQUEST['form_pago']);
$tipo_comp=limpiarcampo($_REQUEST['tipo_comp']);

if($tipo_comp=="A"){$tipo_comp="Anticipo";$num_comp=serial_comp_anti($conex,"Anticipo");}
else if($tipo_comp=="B"){$tipo_comp="Bono";$num_comp=serial_comp_anti();}
else {$tipo_comp="Bono";$num_comp=serial_comp_anti();}

$nom=limpiarcampo($_REQUEST['nom_cli']);
$cc=limpiarcampo($_REQUEST['cc_cli']);
$tel=limpiarcampo($_REQUEST['tel_cli']);
$des=limpiarcampo($_REQUEST['des']);
$dir=r("dir");
$num_exp=serial_exp($conex);

$valor=quitacom(r('tot'));
$totPa=quitacom(r('tot2'));


$idCta=r("id_cuenta");

if(empty($idCta) || $tipo_pago=="Contado"){$idCta=val_caja_gral($tipo_pago,"Ingresos","+");}




$sql="INSERT IGNORE INTO usuarios(id_usu,nombre,tel,dir,cod_su) VALUES('$cc','$nom','$tel','$dir','$codSuc')";
$linkPDO->exec($sql);

$firmaOp="";
if($usar_firmas_cajas==1){
$sql="select firma_op FROM usuarios WHERE id_usu='$id_Usu'";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$firmaOp=$row["firma_op"];
}

$sql="INSERT INTO exp_anticipo(num_exp,id_cli,nom_cli,tel_cli,des,cod_su,estado,cajero,usu,tot,num_fac,tot_pa) VALUES('$num_exp','$cc','$nom','$tel','$des','$codSuc','ABIERTO','$nomUsu','$id_Usu','$valor',0,'$totPa')";
$linkPDO->exec($sql);



$sql="INSERT INTO comp_anti(num_exp,num_com,valor,concepto,fecha,estado,cod_su,cajero,tipo_pago,tipo_comprobante,cod_caja,id_cuenta,firma_cajero) VALUES('$num_exp','$num_comp','$valor','$des','$fecha','SIN COBRAR','$codSuc','$nomUsu','$tipo_pago','$tipo_comp','$codCaja','$idCta','$firmaOp')";	
$linkPDO->exec($sql);

$sql="UPDATE exp_anticipo e INNER JOIN(SELECT SUM(valor) as tot_abon,ex.num_exp FROM comp_anti c INNER JOIN exp_anticipo ex ON c.num_exp=ex.num_exp WHERE ex.num_exp=$num_exp AND c.estado!='ANULADO' AND c.cod_su=ex.cod_su AND ex.cod_su=$codSuc) comp ON e.num_exp=comp.num_exp SET e.tot=comp.tot_abon WHERE e.cod_su=$codSuc AND e.num_exp=$num_exp";
$linkPDO->exec($sql);


up_cta($tipo_pago,$idCta,$valor,"+","Bono/Apartado #:$num_comp","Anticipos",$fecha);

$_SESSION['num_exp']=$num_exp;
$_SESSION['num_comp_anti']=$num_comp;
$sql="SELECT id FROM comp_anti WHERE num_com='$num_comp' AND num_exp='$num_exp' AND tipo_comprobante='$tipo_comp' AND cod_su='$codSuc'";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$ID_COMP=$row["id"];
$_SESSION['id_comp_anti']=$ID_COMP;

$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;
if($all_query_ok){
eco_alert("Operacion Exitosa");
popup("imp_comp_anti.php","Anticipo No.$num_comp","800","600");
js_location("agregar_exp.php");
}
else {eco_alert("ERROR!!! Intentelo mas tarde");}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

}

?>
<!DOCTYPE>
<html >
<head>
<?php require_once("HEADER.php"); ?>
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
</head>
<body >

  
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php //require_once("menu_izq.php"); ?>
            <?php //require_once("menu_top.php"); ?>
			<?php //require_once("boton_menu.php"); ?>
<div class="uk-width-9-10 uk-container-center">
<!--<div class="grid-100 posicion_form">-->
		
				<form autocomplete="off" name="frm" action="<?php echo $ThisURL ?>" method="post" class="uk-form">
						
					<div id="agregar_exp_grid" class=" uk-container-center round_table_white " align="center">
						
									
										<div  id="antiBox" style="font-size:30px;">
											ANTICIPO <span style="color:red;">No.<?php echo serial_comp_anti($conex,"Anticipo"); ?></span>
										</div>
										
										<div  id="bonoBox" style="font-size:30px;">
											BONO <span style="color:red;">No.<?php echo serial_comp_anti($conex,"Bono"); ?></span>
										</div>
									
									
									<div class="uk-grid" data-uk-grid-margin>
										<div class="uk-width-1-3">
											<label>Tipo Comprobante</label>
										
											<select id="tipo_comp" name="tipo_comp" onChange="show_bono_anti($(this));" class="">
												<option value="A"  selected>Anticipo</option>
												<option value="B">Bono</option>
											</select>
										</div>
									
										
										<div class="uk-width-2-3">
											<label>Forma de Pago</label>
										
											  <select id="form_pago" name="form_pago" onChange="" class="uk-form-success">
												<option value=""></option>
												<option value="Contado"  selected>Contado</option>
												<option value="Tarjeta Credito">Tarjeta Credito</option>
												<option value="Tarjeta Debito">Tarjeta Debito</option>
											  </select>
										</div>
									</div>
									
										
										<div>
											<?php if($MODULES["CUENTAS_BANCOS"]==1)echo "<label class=\"uk-form-label\" for=\"id_cuenta\">Cuenta</label>".selcCta("","uk-text-primary  uk-text-bold uk-form-success"); ?>
										</div>
										
									
										
										<div>
											<label>Fecha:</label>
											<input style="width:280px;"  id="fecha" type="datetime-local" value="<?PHP echo $FECHA_HORA ?>" name="fecha"  class=""/>
										</div>
										
										
									<div class="uk-grid" data-uk-grid-margin>
										<div class="uk-width-1-2">	
										
											<label>Nombre Cliente:</label>
											<input type="text" value="" name="nom_cli" id="nom_cli"  style="" class=""/>
										</div>
										
									
										
										<div class="uk-width-1-2">
											<label>C.C.</label>
											<input type="text" name="cc_cli" id="cc_cli" value="" onChange="busq_cli_exp(this);"  class=""/>
										</div>
									</div>	
									
									<div class="uk-grid" data-uk-grid-margin>
										<div class="uk-width-1-2">		
											<label>Direcci&oacute;n:</label>
											<input type="text" name="dir" id="dir" value="" class="">
										</div>
										
										
										<div class="uk-width-1-2">
											<label>Tel&eacute;fono:</label>
											<input type="text" name="tel_cli" id="tel_cli" value="" class=""/>
										</div>
									</div>	
									
									
									<div class="uk-grid uk-width-2-3" style="margin-top:25px;margin-bottom:25px;" data-uk-grid-margin>
										<div class="uk-width-1-2">
											<label class="uk-text-warning" style="font-size:20px;">
											Valor Cuota:
											</label>
											<input type="text" value="" name="tot" id="tot"  style="" onKeyUp="puntoa($(this));" class="uk-form-success"/>
											<!--<div class="uk-badge uk-badge-notification">!</div>-->
										</div>
										
										
										
										<div class="uk-width-1-2">
											<label class="uk-text-danger" style="font-size:20px;">
											Valor TOTAL Pago:
											</label>
											<input type="text" value="" name="tot2" id="tot2"  style="" onKeyUp="puntoa($(this));" class=""/>
										</div>
									</div>	
									
										
										<div>
											<label>Motivo del Anticipo:</label>
											<textarea name="des" id="des" cols="20" rows="3" style="width:300px" class=""></textarea>
										</div>
										
										
										
										<div>
											<input type="button" value="Guardar" id="btn" name="boton" onClick="gexp(this,'genera',document.forms['frm'])" style="border-radius:5px;" class="addbtn uk-button uk-button-primary uk-button-large uk-width-7-10"/>
											<!--<input type="button" value="Volver" onClick="location.assign('expedientes.php')"  class="addbtn" />-->
										</div>
										
										
						  <input type="hidden" name="boton" value="genera" id="genera" />
				
					</div>
					
				</form>

		

<!--</div>-->


<?php require_once("FOOTER.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script language='javascript' src="JS/anticipos.js?<?php echo "$LAST_VER" ?>"></script> 
<script language='javascript'>
var usarCtas=<?php echo $MODULES["CUENTAS_BANCOS"]; ?>;
$('#bonoBox').hide();
function show_bono_anti($tipo)
{
	var tipo=$tipo.val();
	var $boxAnti=$('#antiBox');
	var $boxBono=$('#bonoBox');
	
	if(tipo=="A"){
		$boxAnti.show();
		$boxBono.hide();
	}
	else{
		$boxAnti.hide();
		$boxBono.show();
	}
};
</script> 

</body>
</html>