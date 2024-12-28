<?php
include("Conexxx.php");
$num_comp=serial_comp_anti();
$NoEx="";
if(isset($_REQUEST['exp']))$NoEx=limpiarcampo($_REQUEST['exp']);
if(isset($_REQUEST['boton'])&&$_REQUEST['boton']=="Guardar")
{

$fecha=r("fecha_pago");
$numExp=limpiarcampo($_REQUEST['num_exp']);
$valor=limpiarcampo(quitacom($_REQUEST['valor']));	
$concepto=limpiarcampo($_REQUEST['concepto']);
$tipo_pago=limpiarcampo($_REQUEST['form_pago']);
$tipo_comp=limpiarcampo($_REQUEST['tipo_comp']);

if($tipo_comp=="A"){$tipo_comp="Anticipo";$num_comp=serial_comp_anti($conex,"Anticipo");}
else if($tipo_comp=="B"){$tipo_comp="Bono";$num_comp=serial_comp_anti();}
else {$tipo_comp="Bono";$num_comp=serial_comp_anti();}

$idCta=r("id_cuenta");
 
if(empty($idCta) || $tipo_pago=="Contado"){$idCta=val_caja_gral($tipo_pago,"Ingresos","+");}



$sqlA="INSERT INTO comp_anti(num_exp,num_com,valor,concepto,fecha,estado,cod_su,cajero,tipo_pago,tipo_comprobante,cod_caja,id_cuenta) VALUES('$numExp','$num_comp','$valor','$concepto','$fecha','SIN COBRAR','$codSuc','$nomUsu','$tipo_pago','$tipo_comp','$codCaja','$idCta')";	
//$linkPDO->exec($sql);

$sqlB="UPDATE exp_anticipo e INNER JOIN(SELECT SUM(valor) as tot_abon,ex.num_exp FROM comp_anti c INNER JOIN exp_anticipo ex ON c.num_exp=ex.num_exp WHERE ex.num_exp=$numExp AND c.estado!='ANULADO' AND c.cod_su=ex.cod_su AND ex.cod_su=$codSuc) comp ON e.num_exp=comp.num_exp SET e.tot=comp.tot_abon WHERE e.cod_su=$codSuc AND e.num_exp=$numExp";
//echo "<br><b>$sqlB</b>";
t2($sqlA,$sqlB);

up_cta($tipo_pago,$idCta,$valor,"+","Bono/Apartado #:$num_comp","Anticipos",$hoy);

$_SESSION['num_exp']=$numExp;
$_SESSION['num_comp_anti']=$num_comp;
$sql="SELECT id FROM comp_anti WHERE num_com='$num_comp' AND num_exp='$numExp' AND tipo_comprobante='$tipo_comp' AND cod_su='$codSuc'";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$ID_COMP=$row["id"];
$_SESSION['id_comp_anti']=$ID_COMP;
eco_alert("Operacion Exitosa");
popup("imp_comp_anti.php","Anticipo No.$num_comp","800","600");
}


?>
<!DOCTYPE >
<html  >
<head>
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<link href="JS/lightBox.css<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<?php require_once("HEADER.php"); ?>
 
</head>

<body>

	
			<div class="loader"> <img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" /> </div>
			
			<form action="comp_anticipo.php" method="post" name="frm" autocomplete="off" class="uk-form">
				
				<div id="comp_anticipo_grid" class=" uk-container-center round_table_white " align="center">
				
				<div id="Qresp"></div>
				
					<div class="uk-overflow-container uk-width-8-10 uk-container-center">
						
							<div  id="antiBox" style="font-size:32px;margin-bottom:15px;">
							ANTICIPO <span style="color:red;">No.<?php echo serial_comp_anti($conex,"Anticipo"); ?></span>
							</div>
							
							
							<div id="bonoBox" style="font-size:32px;margin-bottom:15px;">
							BONO <span style="color:red;">No.<?php echo serial_comp_anti($conex,"Bono"); ?></span>
							</div>
							
						
						
						 
						
					
					<div class="uk-grid" data-uk-grid-margin>	
						<div class="uk-width-1-3">
						<label>Tipo Comprobante</label>

						<select id="tipo_comp" name="tipo_comp" onChange="show_bono_anti($(this));">
											<option value="A"  selected>Anticipo</option>
											<option value="B">Bono</option>
										  </select>
						</div>				  
										  
										  
					
						
						<div class="uk-width-1-3">
						<label>Forma de Pago</label>
										  <select id="form_pago" name="form_pago" onChange="" class="uk-form-success">
											<option value="Contado"  selected>Contado</option>
											<option value="Tarjeta Credito">Tarjeta Credito</option>
											<option value="Tarjeta Debito">Tarjeta Debito</option>
										  </select>
						</div>		
                        
                          <div class="uk-width-1-3">
										<label class="uk-form-label" for="fecha_pago">Fecha:</label>
										<input type="date" name="fecha_pago" id="fecha_pago" class="uk-form uk-form-small" value="<?php echo $FechaHoy; ?>"> 
									</div>		  
					</div>					  
					
						
						<div class="uk-width-1-3">
						<?php if($MODULES["CUENTAS_BANCOS"]==1)echo "<label class=\"uk-form-label\" for=\"id_cuenta\">Cuenta</label>".selcCta("","uk-text-primary  uk-text-bold uk-form-success"); ?>
						</div>
                        
                        
                    
						
					
					<div class="uk-grid" data-uk-grid-margin>	
						<div class="uk-width-2-6" style="padding-right:20px;">
						<label>Cod. de Anticipo:<label>
						</div>
						
					
						
						<div class="uk-width-2-6">
						<input type="text" name="num_exp" id="num_exp" onKeyPress="get_exp(this,'add');" onChange="confirma_exp($(this));"  placeholder="" value="<?php echo $NoEx; ?>"/>
						</div>
						
					
						
						<div class="uk-width-2-6">
							<span style="color:red; font-size:21px; font-weight:bold;" id="confirma">SIN CONFIRMAR</span> 
						</div>
					</div>	
					
						
						<div>
							<span id="RESP" ></span>
						</div>
						
					
					<div class="uk-grid" data-uk-grid-margin>	
						<div class="uk-width-2-6" >
						<label>Valor Pago:<label>
						</div>
						
				
						
						<div class="uk-width-2-6">
							<input type="text" name="valor" id="valor"  value=""  onKeyUp="puntoa($(this))" placeholder="Valor a Pagar" />
						</div>
						
					
						
						
						<div class="uk-width-2-6">
							<i colspan="2" id="saldo" style="font-size:24px;" class="uk-text-warning"></i>
						</div>
					</div>	
						
					
						
						<div>
						<label>Por Concepto de:<label>
						<textarea name="concepto" id="concepto" cols="25" rows="3" style="width:300px;"></textarea>
						</div>
						
					
						
						
						<div>
						<input type="button" name="boton" style="border-radius:5px;" class="uk-button uk-button-primary uk-button-large uk-width-7-10"  value="Guardar" onClick="save_anti(this,'boton',document.forms['frm'])" />
						</div>
						
						
					
					</div>
				<input type="hidden" value="" name="verify" id="verify" />
				<input type="hidden" value="" name="boton" id="boton" />
			
				</div>
			</form>
			
			
		<?php require_once("FOOTER.php"); ?>
		<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
		<script language='javascript' src="JS/anticipos.js?<?php echo "$LAST_VER" ?>"></script> 
		<script language='javascript' >
		var usarCtas=<?php echo $MODULES["CUENTAS_BANCOS"]; ?>

		$(document).ready(function() {
			$('#bonoBox').hide();
		$('#loader').hide();
		$('#loader').ajaxStart(function(){
		$(this).show();
		$('input[type=button]').prop("disabled","disabled").css("color","red");}).ajaxSuccess(function(){$(this).hide();$('input[type=button]').removeAttr("disabled").css("color","black");});
		//$('#loader').ajaxError(function(){$('input[type=button]').prop("disabled","disabled").css("color","red");$(this).hide();});
		});
		$.ajaxSetup({
		'beforeSend' : function(xhr) {
		try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
		catch(e){}
		}});



		confirma_exp($('#num_exp'));


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
	</div>
</body>
</body>
</html>