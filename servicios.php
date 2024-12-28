<?php 
require_once("Conexxx.php");
//////////////////////////////////////// OPC PAGINA ////////////////////////////////////
$TITULO_SECC="SERVICIOS";

$busq="";
$val="";
$val2=urlencode(r("valor2"));
$boton="";
$idx="";
$tabla="servicios";
$col_id="id_serv";
$columns="id_serv,servicio,iva,pvp,des_serv,km_revisa,cod_su,cod_serv";
$url=rawurlencode("servicios.php");

$url_mod="mod_serv.php";
$url_new="add_serv.php";
$pag="";
$limit = 20; 
$order="servicio";
$sort="";
$colArray=array(0=>'id_serv','servicio','des_serv','gana');
$classActive=array(0=>'','','','');
$offset=0;

$opc="";
$opc=r('opc');

//////////////////////////////////////////////////////// FILTROS TABLA ////////////////////////////////////////



//echo "FILTROS: $FILTROS_TABLA";

//-----------------------------------------------------------------------------------------------------------//

if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;

$idx=s('id');
$boton= urlencode(r('opc'));
$busq= urlencode(r('busq'));
$val= urlencode(r('valor'));



$sql = "SELECT  $columns FROM $tabla  WHERE  cod_su=$codSuc  LIMIT $offset, $limit";



if($boton=='mod'&& !empty($val) ){
	
	//$_SESSION['id']=$val;
	$_SESSION['pag']=$pag;
	$feVen=r('fe_ven');
	header("location: $url_mod?ID_SERV=$val");
	}
 


 
 
$sqlTotal = "SELECT COUNT(*) as total FROM $tabla WHERE cod_su=$codSuc"; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){


$sql_busq="SELECT $columns FROM $tabla  WHERE cod_su=$codSuc AND (servicio LIKE '%$busq%' OR id_serv='$busq' OR des_serv LIKE '%$busq%' OR cod_serv='$busq') ";	




$rs=$linkPDO->query($sql_busq);

	
	}
	
//echo "<li>$sql</li><li>$sql_busq</li>";
 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once("HEADER.php"); ?>
<link href="css/multi-select.css" rel="stylesheet" type="text/css" />	
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

		<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-center" style="width:350px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
				<li class="ss-navbar-center"><a href="<?php echo $url_new ?>" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Crear SERVICIO</a></li>
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
		
		
<h1 align="center"><?php echo $TITULO_SECC;  ?></h1>

<form action="<?php echo $url ?>" method="post" name="form" class="uk-form">

<div class="grid-100">
<?php 

//echo "FECHAS: $fechaVenciALERT, $fechaVenciMIN";
require("PAGINACION.php");


 ?>
<table border="0" align="center" cellpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped"> 
 <thead>
      <tr bgcolor="#595959" style="color:#FFF" valign="top"> 
     <th colspan="2"></th> 
<TH>COD. </TH>
<th>Servicio</th>
<th>Descripci&oacute;n</th>
<th>IVA</th>
<th>PVP</th>
<th>KM para Revisi&oacute;n</th>
       </tr>
        </thead>
        <tbody>
          
      
<?php 
$bgColor="#FFFFFF";

while ($row = $rs->fetch()) 
{ 
$ii++;
		    
         $ID=$row["id_serv"];
		 $COD=$row["cod_serv"];
		 $serv=$row["servicio"];
		 $des_serv=$row["des_serv"];
		 $iva=$row["iva"];
		 $pvp=$row["pvp"];
		 $km_rev=$row["km_revisa"];
		 
		
			
			 
			
         ?>
 
<tr  bgcolor="#FFFFFF" tabindex="0" id="tr<?php echo $ii ?>" onClick="click_ele(this);" onBlur="resetCss(this);">
<th><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<?php
if(($rolLv==$Adminlvl || val_secc($id_Usu,"mod_serv")) && $codSuc>0){
?>
<a href="<?php echo $url."?valor=".urlencode($ID)."&opc=mod" ?>" class="uk-icon-pencil uk-icon-button uk-icon-hover uk-icon-small"><input type="checkbox" id="<?php echo $ID ?>" style="visibility:hidden"></a>
</td>
<?php
}
if(($rolLv==$Adminlvl || val_secc($id_Usu,"del_serv")) && $codSuc>0){
?>
<td>
<a href="#" onClick="eli_row('<?php echo $ID ?>','<?php echo $serv ?>')" class="uk-icon-remove uk-icon-button uk-icon-hover uk-icon-small">
<input type="checkbox" id="<?php echo $ID ?>" style="visibility:hidden"></a>
<?php
}
?>
</td>
</tr>

</table>


</td>             
<td><?php echo $COD; ?></td>
<td><?php echo $serv; ?></td>
<td><?php echo $des_serv; ?></td> 
<td><?php echo $iva; ?>%</td>
<td><?php echo money($pvp); ?></td>
<td><?php echo $km_rev; ?></td>
</tr> 
         
<?php 
         } 
      ?>
 
          
   
</table>

<div id="filtros" class="uk-modal">
<div class="uk-modal-dialog">

<a class="uk-modal-close uk-close"></a>
<h1 style="color:#000">FILTRO POR CLASE</h1>
<table width="100%">
<tr>
<th>Filtro Clase:</th>
<td>
<?php
//echo multiSelc("","clases[]","clases","","clases","","des_clas","des_clas");

?>
</td>
</tr>


<tr>
<td colspan="2"><h1 style="color:#000">FILTRO SUB CLASE</h1></td>
</tr>
<tr>
<td colspan="2">
<?php
//echo multiSelc("","sub_clases[]","sub_clase","","sub_clase","","des_sub_clase","des_sub_clase");
?>
</td>
</tr>



<tr>
<td colspan="2"><h1 style="color:#000">FILTRO POR LABORATORIO</h1></td>
</tr>
<tr>
<th>Filtro Laboratorio:</th>
<td>
<?php
//echo multiSelc("","fabricantes[]","fabricantes","","fabricantes","","fabricante","fabricante");

?>
</td>
</tr>


<tr>
<td colspan="2"><h1 style="color:#000">FILTRO POR DESCRIPCI&Oacute;N</h1></td>
</tr>
<tr>
<td colspan="2"><input type="text" name="filtroDes" value="" placeholder="NOMBRE PRODUCTO"></td>
</tr>
<tr>
<td colspan="2"><h1 style="color:#000">FILTRO POR EXISTENCIAS</h1></td>
</tr>
<tr>
<td colspan="2"><select name="filtroExist">

<option value="">TODAS</option>
<option value="1">Mayores iguales a 1</option>
<option value="2">Iguales a 0</option>
<option value="-1">NEGATIVOS</option>
</select></td>
</tr>
<tr>
<td colspan="2"><h1 style="color:#000">FILTRO POR UTILIDAD</h1></td>
</tr>
<tr>
<td colspan="2"><select name="filtroUtil">

<option value="">TODAS</option>
<option value="1">Mayor a  menor</option>
<option value="2">Menor a mayor</option>
</select></td>
</tr>
<tr>
<td colspan="2" align="center"><input type="submit" value="Aplicar Filtros" name="filtro"  class="uk-button uk-button-large uk-button-primary uk-width-1-1"></td>
</tr>
</table>
    </div>
</div>
<?php require("PAGINACION.php"); ?>
</div>
</form>
	
<?php require_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script language="javascript1.5" type="text/javascript" src="JS/jquery.multi-select.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>	
<script language="javascript1.5" type="text/javascript">
$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=UTF-8');}
catch(e){}
}});
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=UTF-8');}
catch(e){}
}});

$('#clases').multiSelect();
$('#sub_clase').multiSelect();
$('#fabricantes').multiSelect();
//$('html, body').animate({scrollTop: $("#<?php //echo $idx ?>").offset().top-120}, 2000);
//$('body').scrollTo('#<?php //echo $idx ?>');
});
function resetCss(ele)
{
  $(ele).removeAttr('style');
};
function click_ele(ele)
{
	$(ele).css('background-color', '#FF0');
};
function eli_row(id,des)
{
	var datos='ID='+id;
if(confirm('Desea Borrar'+des+' ID:'+id+'?')){
ajax_a('ajax/del_row.php',datos,'Eliminado con Exito');
location.assign('servicios.php');
}

}
</script>
</div><!--fin pag 1-->

</body>
</html>