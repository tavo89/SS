<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"inventario_ajustes")){header("location: centro.php");}
$ThisURL="crear_ajuste.php";
$boton="";
if(isset($_REQUEST['boton']))$boton=$_REQUEST['boton'];

if($boton=="Guardar")
{
$fecha=$hoy;
$nom=limpiarcampo($nomUsu);
$cc=limpiarcampo($id_Usu);

$num_ajus=serial_ajustes($conex);
$num_art=r('nref');

if($num_art>0)
{
try {
$linkPDO->beginTransaction();
$all_query_ok=true;

$i=0;
$flag=0;

$linkPDO->exec("SAVEPOINT A");

$sql="INSERT INTO ajustes(num_ajuste,fecha,cod_su,nom_usu,id_usu) VALUES($num_ajus,'$fecha',$codSuc,'$nom','$cc')";
//$sqlLog="<ul><li>$Insert1</li>";
$linkPDO->exec($sql);
	for($i=0;$i<$num_art;$i++)
	{
		$linkPDO->exec("SAVEPOINT LoopA".$i);
		if(isset($_REQUEST['ref_'.$i]))
		{
		$ref=limpiarcampo($_REQUEST['ref_'.$i]);
		$cod_bar=limpiarcampo($_REQUEST['cod_barras'.$i]);
		$presentacion=limpiarcampo($_REQUEST['presentacion'.$i]);
		$det=limpiarcampo($_REQUEST['det_'.$i]);
		$motivo=limpiarcampo($_REQUEST['motivo_'.$i]);
		$cant=limpianum($_REQUEST['cant_'.$i]);
		$cant_saldo=limpianum($_REQUEST['cant_saldo'.$i]);
		$cant_saldo=$cant_saldo+$cant;

		$uni=limpianum(r('unidades'.$i));
		$frac=limpianum(r('fracc'.$i));
		if($frac==0)$frac=1;
		$uniSis=limpianum(r('unidades_saldo'.$i));
		$uniSaldo=$uniSis+$uni;

		$feVen=r('fecha_vencimiento'.$i);
		if(empty($feVen))$feVen="0000-00-00";

		$iva=limpiarcampo(quitacom($_REQUEST['iva_'.$i]));
		$util=quitacom($_REQUEST['util_'.$i]);
		if(empty($iva))$iva=0;
		$precio=quitacom($_REQUEST['pvp_'.$i]);
		$costo=quitacom($_REQUEST['costo_'.$i]);

		$num_motor=r("num_motor".$i);

        $sql="INSERT INTO art_ajuste(num_ajuste,ref,des,cant,costo,precio,util,iva,cod_su,motivo,cant_saldo,cod_barras,presentacion,fraccion,unidades_fraccion,unidades_saldo,fecha_vencimiento,num_motor) VALUES($num_ajus,'$ref','$det',$cant,$costo,$precio,$util,$iva,$codSuc,'$motivo','$cant_saldo','$cod_bar','$presentacion','$frac','$uni','$uniSaldo','$feVen','$num_motor')";
		//$sqlLog.="<li>$sql</li>";
		if(!empty($cant)||!empty($uni)){$linkPDO->exec($sql);}
		else {$flag=1;break;}
		$update="UPDATE inv_inter set exist=exist+$cant WHERE id_inter='$cod_bar' AND nit_scs=$codSuc";
		//t3($Insert1,$Insert2,$update);
		}
	}

$linkPDO->exec("SAVEPOINT B");

$sql="UPDATE `inv_inter` i
INNER JOIN
(select ar.cod_su nitAr,sum(cant) cant,ref,cod_barras,unidades_fraccion,fecha_vencimiento from art_ajuste ar inner join (select * from ajustes f WHERE num_ajuste=$num_ajus and cod_su='$codSuc' ) fv ON fv.num_ajuste=ar.num_ajuste WHERE fv.cod_su=ar.cod_su and fv.cod_su=$codSuc group by ar.ref,ar.fecha_vencimiento,ar.cod_barras) a
ON i.id_inter=a.cod_barras
SET i.exist=(i.exist+a.cant),i.unidades_frac=(i.unidades_frac+a.unidades_fraccion) WHERE i.nit_scs=a.nitAr and i.nit_scs=$codSuc AND i.fecha_vencimiento=a.fecha_vencimiento AND i.id_pro=a.ref";
$linkPDO->exec($sql);


//$sqlLog.="<li>$sql</li></ul>";
	$HTML_antes="";
	$HTML_despues="";
	if(isset($_REQUEST['htmlPag']))$HTML_despues=$_REQUEST['htmlPag'];
	//logDB($sqlLog,$SECCIONES[8],$OPERACIONES[1],$HTML_antes,$HTML_despues,$num_ajus);

if($flag==0)
{

$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){
eco_alert("Operacion Exitosa");
js_location("crear_ajuste.php");
}
else{eco_alert("ERROR! Intente nuevamente");}

}
else eco_alert("Operacion Cancelada, FALTAN DATOS");

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

}//// fin if numref


}

?>
<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once("HEADER.php"); ?>
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">
<div class="posicion_form">

<h1 align="center">AJUSTE DE EXISTENCIAS</h1>

	<div class="ms_panels" align="center" style="width:100%;">
		<form name="formulario" action="<?php echo $ThisURL ?>" method="post" class="uk-form">
			<div class="loader">
			<img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" />
			</div>

			<table align="center" cellpadding="5" cellspacing="0" class="round_table_white">

					<thead style="font-size:34px;">
						<tr>
						<td colspan="3" align="center">Ajuste <span style="color:red;">No.<?php echo serial_ajustes($conex); ?></span></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="3">Fecha: <?php echo $hoy ?></td>
						</tr>
						<tr>
							<td colspan="5">



							<table id="articulos" cellpadding="0" cellspacing="0" width="60%" class="uk-table">

								<tr id="articulos_head" style="background-color: #444; color:#FFF;">

									  <td><div align="center"><strong>Referencia</strong></div></td>
									  <td><div align="center"><strong>Codigo</strong></div></td>
									  <td><div align="center"><strong>Producto</strong></div></td>


                                       <?php if($usar_datos_motos==1){ ?>


                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Motor</strong></div></td>


                <?php }?>

                                      <!--
									  <td height=""><div align="center"><strong>presentaci&oacute;n</strong></div></td>
									 -->
									  <td height="28"><div align="center"><strong>Cant.</strong></div></td>
									  <td height="28"><div align="center"><strong>Fracci&oacute;n</strong></div></td>

									  <td height=""><div align="center"><strong>Motivo</strong></div></td>
                                      <!--
									  <td><div align="center"><strong>Util.</strong></div></td>
									  <td><div align="center"><strong>I.V.A</strong></div></td>
									  <td><div align="center"><strong>Costo</strong></div></td>
									  <td><div align="center"><strong>PvP</strong></div></td>
                                      -->
									  <td><div align="center"><strong>Cant.<br>Sistema</strong></div></td>
									  <td><div align="center"><strong>Fracci&oacute;n<br>Sistema</strong></div></td>
									  <td height="28" colspan="2"><div align="center"><strong>Fecha<br>Vencimiento</strong></div></td>
								</tr>

							</table>
							</td>
						</tr>
						<tr valign="middle">
							<th colspan="7" align="center" style="padding-right:50px;">Cod. Art&iacute;culo:<input type="text" style="margin:3px 5px;" name="cod" value="" id="cod" onKeyUp="cod_ajus(this,'add');" />

						<input type="hidden" value="" name="feVen" id="feVen" />
						<input type="hidden" value="" id="Ref" name="Ref">

							<img style="cursor:pointer" title="Buscar" onMouseUp="busq_ajus($('#cod'));" src="Imagenes/search128x128.png" width="34" height="31" />
						<div id="Qresp"></div>
						</th>
						</tr>

						<tr>
						<td colspan="7" align="center" style="padding-left:20px;">
						<input type="button" value="Guardar" id="btn" name="boton" onClick="save_ajus(this,'genera',document.forms['formulario'])" class="addbtn uk-button uk-button-success" />
						<input type="button" value="Volver" onClick="location.assign('ajustes.php')"  class="addbtn uk-button uk-button-danger" />
						</td>
						</tr>
					</tbody>
			</table>


		  <input type="hidden" name="boton" value="genera" id="genera" />
		  <input type="hidden" name="nref" value="0" id="num_ref" />
		  <input type="hidden" name="exi_ref" value="0" id="exi_ref" />
		  <input type="hidden" value="" name="htmlPag" id="HTML_Pag">

		</form>
	</div>

</div>
<?php require_once("js_global_vars.php"); ?>
<?php require_once("FOOTER.php"); ?>
<?php require_once("keyFunc_fac_ven.php"); ?>
<?php require_once("autoCompletePack.php"); ?>

<script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>
<script language="javascript1.5" type="text/javascript" src="JS/fac_ven.js?<?php  echo "$LAST_VER"; ?>"></script>
<script language='javascript' >
var dcto_remi=0;
var HH=12000;
var iva_serv=0.16;
$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});

	$('#loader').hide();
	$('#loader').ajaxStart(function(){$(this).show();})
	.ajaxStop(function(){$(this).hide();});

	$(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});

$('input').on("change",function(){$(this).prop('value',this.value);});
$('textarea').on("change",function(){$(this).html(this.value);});
</script>
</body>
</html>
