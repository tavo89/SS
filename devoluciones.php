<?php 
require_once("Conexxx.php");
$UserFilter="AND fecha>='$fechaCreaUsu'";
if($rolLv==$Adminlvl || $separar_registros_por_usuarios==0)$UserFilter="";
$busq="";
$val="";
$boton="";
$fe="";
$val= r('valor');
$val2= r('valor2');
$val3=r("valor3");
$boton= r('opc');
$busq= r('busq');
$fe=r('fecha');

$cols="<th width=\"90px\">#</th>
<td></td>
<td width=\"100\">Cod. Nota Credito</td>
<th width=\"100\">No. Fac</th>
<th width=\"200\">Proveedor</th>
<th width=\"200\">NIT</th>

<th width=\"200\">Nota</th>
<th width=\"50\">TOT</th>
<th width=\"100\">Fecha</th>
<th width=\"150\">Estado</th>
<th>DIAN</th>";

$tabla="fac_dev";
$col_id="num_fac_com";
$columns="num_fac_com,nit_pro,nom_pro,fecha,tel,dir,ciudad,nota_dev,cod_su,tot,DATE(fecha_crea) as fecha_crea,fecha_mod,serial_fac_dev,anulado,estado_factura_elec";
$url="devoluciones.php";
$url_dialog="dialog_invIni.php";
$url_mod="mod_fac_dev.php";
$url_new="devolucion.php";
$pag="";
$limit = 20; 
$order="fecha_crea";

if(isset($_SESSION['order'])){

if($_SESSION['order']="1")$order="fecha";
else if($_SESSION['order']="2")$order="tot";
else if($_SESSION['order']="3")$order="nom_cli";

}
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;

$sql = "SELECT  $columns FROM fac_dev  WHERE  cod_su=$codSuc $UserFilter ORDER BY serial_fac_dev DESC   LIMIT $offset, $limit"; 

if($boton=='modx'&& !empty($val)){
	if($FechaHoy==$fe){
	$_SESSION['num_fac']=$val;
	$_SESSION['nit_pro']=$val2;
	$_SESSION['pag']=$pag;
	header("location: $url_mod");
	}
	else eco_alert("Fecha Limite excedida, Ya no se pueden hacer Modificaciones!");
	}

if($boton=='imp'&& !empty($val)){

	$_SESSION['num_fac']=$val;
	$_SESSION['nit_pro']=$val2;
	$_SESSION['serial_dev']=$val3;
	$_SESSION['pag']=$pag;
	header("location: imp_fac_dev.php");

	}

$sqlTotal = "SELECT COUNT(*) as total FROM fac_dev WHERE cod_su='$codSuc' $UserFilter"; 
$rs = $linkPDO->query($sql ); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM fac_dev  WHERE (nom_pro LIKE '$busq%' OR nota_dev LIKE '%$busq%' OR serial_fac_dev = '$busq' OR num_fac_com = '$busq')  AND cod_su=$codSuc $UserFilter";

$rs=$linkPDO->query($sql_busq );

	}

?>
<!DOCTYPE html>
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

		<!-- Lado izquierdo del Nav -->
		<nav class="uk-navbar">

		<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/favSmart.png" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-center" style="width:360px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->

				<li class="ss-navbar-center"><a href="<?php echo $url_new ?>" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Crear Devoluci&oacute;n</a></li>
				<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>
			</ul>

				<!-- Lado derecho del Navbar -->

		<div class="uk-navbar-content uk-hidden-small uk-navbar-flip">
			<form class="uk-form uk-margin-remove uk-display-inline-block">
				<div class="uk-form-icon">
						<i class="uk-icon-search"></i>
						<input type="text" name="busq" placeholder="Buscar..." class="">
				</div>
				<input type="submit" value="Buscar" name="opc" class="uk-button uk-button-primary">
			</form>
		</div>

		</nav>

<h1 align="center">DEVOLUCIONES DE COMPRAS</h1>

<form action="<?php echo $url ?>" method="get" name="form" class=" uk-form">

<br><br><br>
<?php require("PAGINACION.php"); ?>
<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped" >  
 <thead>
  <tr bgcolor="#595959" style="color:#FFF" valign="top"> 

<?php echo $cols;   ?>

       </tr>

 </thead>
 <tbody>         

<?php 

while ($row = $rs->fetch()) 
{ 
$ii++;

            $cod_fac = $row["num_fac_com"]; 
            $nom_pro =$row["nom_pro"]; 
			$dir = $row["dir"];
			$tel = $row["tel"];
			$ciudad = $row["ciudad"];
			$nit_pro =$row["nit_pro"]; 
			$fecha = $row["fecha"];
			$tot = money($row["tot"]);
			$flete =$row["nota_dev"];
			 $fecha_creacion=$row['fecha_crea'];
			 $fecha_mod=$row['fecha_mod'];
			 $serial_dev=$row["serial_fac_dev"];

			 $estado=$row["anulado"];

			 if($estado=="ANULADO")$style_ANULADA="color: #F00; font-weight:bold; font-size:18px;";
			else $style_ANULADA="";

			$EstadoDIAN=$row["estado_factura_elec"];

if($EstadoDIAN==200){$showDIAN='<div class="uk-button uk-button-success"><i class="uk-icon uk-icon-check uk-icon-large"></i></div>';}
else if($EstadoDIAN!=200 && !empty($EstadoDIAN)){$showDIAN='<div class="uk-button uk-button-danger"><i class="uk-icon uk-icon-remove uk-icon-large"></i></div>';}
else {$showDIAN='';}

         ?>

<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<!--<td>
<a href="<?php //echo $url ?>?opc=mod&valor=<?php //echo $cod_fac ?>&valor2=<?php //echo $nit_pro ?>&fecha=<?php //echo $fecha_creacion ?>&pag=<?php //echo $pag ?>" data-ajax="false" data-role="button" data-inline="true" data-mini="true">

<img src="Imagenes/b_edit.png">
</a>
</td>
-->
<td>
<a href="<?php echo "$url?opc=imp&valor=$cod_fac&valor2=$nit_pro&fecha=$fecha&pag=$pag&valor3=$serial_dev" ?>"  class="uk-icon-print uk-icon-button uk-icon-hover uk-icon-small">

</a>
</td>

<?php 
if($rolLv==$Adminlvl || val_secc($id_Usu,"fac_anula")){
?>
<td>
<a href="#"  onMouseUp="anular_dev_com('<?php echo $serial_dev; ?>');" class="uk-icon-remove uk-icon-button uk-icon-hover uk-icon-small">

</a>
</td>
<?php 
}
?>
<?php 
if($rolLv==$Adminlvl ){
?>
<td>
<a href="#"  onMouseUp="SEND_facElec('<?php echo $cod_fac; ?>','<?php echo $nit_pro; ?>','<?php echo $codSuc; ?>');" class="uk-icon-send  uk-icon-button uk-icon-hover uk-icon-small">

</a>
</td>
<?php 
}
?>
</tr>
</table>

</td>
<td><?php echo $serial_dev; ?></td>             
<td><?php echo $cod_fac; ?></td>
<td><?php echo $nom_pro ?></td>
<td><?php echo $nit_pro ?></td> 

<td><?php echo $flete ?></td>
<td><?php echo $tot ?></td>
<td><?php echo $fecha_creacion ?></td>
<td style="<?php echo $style_ANULADA;?>"><?php echo $estado ?></td>
<td ><b><?php echo $showDIAN ?></b></td>
</tr> 

<?php 
         } 
      ?>

</tbody>   
</table>
<?php include("PAGINACION.php"); ?>
</form>

</div>
<?php require_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 	
<script type="text/javascript" language="javascript1.5" >
function SEND_facElec(num_fac,nit_pro,cod_su)
{
	$.ajax({
		url:'ajax/FacElec/SEND_notaDeb.php',
		data:{num_fac:num_fac,nit_pro:nit_pro,codSuc:cod_su},
		type:'POST',
		dataType:'JSON',
		success:function(response){

			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}

		});

};
function anular_dev_com(serial)
{

	if(!esVacio(serial) ){
	if(confirm('Desea ANULAR Devolucion de Venta No.'+serial )){
	 $.ajax({
		url:'ajax/anula_dev_compra.php',
		data:{serial:$.trim(serial)},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		var r=text.split('|');

		if(resp==0)simplePopUp('Esta Devolucion YA esta Anulada  ');
		else if(resp!=-2&&resp!=-1&&resp!=-445)
		{
			simplePopUp('Devolucion. No.'+serial+' ANULADA');

		}
		else error_pop('Devolucion No.'+serial+' NO encontrada');

		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
	 });

	}

	}
else {simplePopUp('Complete los espacios! No. Factura y PREFIJO(MTRH,RH,RAC,etc.)')}
	};	

$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});

	});

</script>

</body>
</html>