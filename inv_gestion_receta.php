<?php 
require("Conexxx.php");

$serial_inv=r("serial_inv");
if(!empty($serial_inv)){$_SESSION['serial_inv']=$serial_inv;}
if(!empty($_SESSION['serial_inv'])){$serial_inv=$_SESSION['serial_inv'];}


$detalle=r("detalle");
if(!empty($detalle)){$_SESSION['detalle']=$detalle;}
if(!empty($_SESSION['detalle'])){$detalle=$_SESSION['detalle'];}

 //////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_vehi";
$PAG_fechaF="fechaF_vehi";
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button uk-button-success\" >";
$A="";
$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}

if(isset($_REQUEST['fechaI'])){$fechaI=limpiarcampo($_REQUEST['fechaI']); $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=limpiarcampo($_REQUEST['fechaF']);$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" class=\"uk-button uk-button-danger\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}




if($opc=="QUITAR")
{
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button uk-button-success\" >";
	$fechaI="";
	$fechaF="";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	$A="";
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------

$C="";
$nom_cli="";


$opc="";
$busq="";
$val="";
$boton="";
$tot_cuotas=0;
$val_cre=0;
$totCre=0;
$pre="";
if(isset($_GET['opc'])){$opc=r('opc');}
if(isset($_REQUEST['busq']))$busq=r('busq');
if(isset($_REQUEST['valor']))$val=r('valor');
if(isset($_REQUEST['boton']))$boton= r('boton');
if(isset($_REQUEST['pre']))$pre= $_REQUEST['pre'];





//-----------------------------------------------------------------------------------------------------------------------------------------------------


//////////////////////////////////////////////////////////////////////////////// CABECERA TABLA ////////////////////////////////////////////////////






$tabla="inv_lista_materia_prima";
$codSuCol="cod_su";
$col_id="id";
// columnas para mostrar y modificar
//CONCAT(prefijo,' ' ,num_fac_ven) as $new_str = str_replace(' ', '', $old_str);

$columns=str_replace(' ', '',"id,nombre_producto,ref,cod_barras,fecha_vencimiento,cant,unidades_frac,fraccion");


$hidColumns=str_replace(' ', '',"id");
$hidColumns=explode(",",$hidColumns);

$monedaColumns=str_replace(' ', '',"");
$monedaColumns=explode(",",$monedaColumns);

$fechaColumns=str_replace(' ', '',"");
$fechaColumns=explode(",",$fechaColumns);

$selecColumns=str_replace(' ', '',"");
$selecColumns=explode(",",$selecColumns);
//residencial-empresarial-comercial
$SelOpt1="<option value=\'Residencial\'>Residencial</option><option value=\'Empresarial\'>Empresarial</option><option value=\'Comercial\'>Comercial</option>";
if($fac_servicios_mensuales==1)
{$SelOpt2="<option value=\"Regimen Comun\">Regimen Comun</option><option value=\"Regimen Simplificado\">Regimen Simplificado</option>";}
else{$SelOpt2="<option value=\"Residencial\">Residencial</option><option value=\"Empresarial\">Empresarial</option><option value=\"Comercial\">Comercial</option>";}
$selecOPTColumns=array("tipo_cliente"=>$SelOpt1);
$selecOPTColumns2=array("tipo_cliente"=>$SelOpt2);

$modColumns=str_replace(' ', '',"cant,unidades_frac");

$modColumns=explode(",",$modColumns);

$estadoOkColumns=str_replace(' ', '',"habilitado");
$estadoOkColumns=explode(",",$estadoOkColumns);

$cols=explode(",",$columns);
$columnas=$cols;


$maxCols=count($cols);
$colSet[]="";
for($i=0;$i<$maxCols;$i++){$colSet[$i]=$cols[$i];}
$url="inv_gestion_receta.php";
$ORDER_BY="ORDER BY  nombre_producto";
 

/*
echo "<pre>";
//print_r($hidCols);
print_r($cols);
echo "</pre>";

*/
///////////////////////////////////////////////////////////////////// PAGINACION ///////////////////////////////////////////////////////////////////
$pag="";
$limit = 20; 

 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{$pag = 1;} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
 
 
 
$sql="SELECT a.COLUMN_NAME, a.COLUMN_COMMENT FROM information_schema.COLUMNS a WHERE a.TABLE_NAME =  '$tabla' AND a.COLUMN_COMMENT !=  ''";
$rs=$linkPDO->query($sql);
$HEADER_TABLE[]="";

while($row=$rs->fetch()){$HEADER_TABLE[$row["COLUMN_NAME"]]=$row["COLUMN_COMMENT"];}
$cols="";
$max=count($colSet);
for($i=0;$i<$max;$i++){
	//$HEADER_TABLE[$colSet[$i]]=="ID"
	//echo "<li>".$columnas[$i]."</li>";
	if(in_array($columnas[$i],$hidColumns) ){$HIDDEN=" uk-hidden";}
	else{$HIDDEN="";}

	$cols.="<td class=\" $HIDDEN \">".$HEADER_TABLE[$colSet[$i]]."</td>";
}

$sql = "SELECT  $columns FROM $tabla WHERE  $codSuCol=$codSuc AND id_producto_manufac='$serial_inv' $ORDER_BY DESC    LIMIT $offset, $limit "; 
//echo $sql;

$rs =$linkPDO->query($sql); 





//-----------------------------------------------------------------------------------------------------------------------------------------------------



//////////////////////////////////////////////////////////////// BUSCAR ///////////////////////////////////////////////////////////////////////////
//echo "bot: $boton ---->$busq";
if($boton=='Buscar' && isset($busq) && !empty($busq)){

//concepto LIKE '%$busq%' OR
$ND="";
for($i=0;$i<$max;$i++){

	if($i!=$max-1){$ND.="$colSet[$i] LIKE '%$busq%' OR ";}
	else{$ND.=" $colSet[$i] LIKE '%$busq%'";}
}


$sql_busq="SELECT $columns FROM $tabla WHERE $codSuCol=$codSuc  AND id_producto_manufac='$serial_inv' AND ( $ND    ) ";


$rs=$linkPDO->query($sql_busq);
	}
$sqlTotal = "SELECT COUNT(*) AS total FROM $tabla WHERE  $codSuCol=$codSuc  AND id_producto_manufac='$serial_inv'"; 

$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 


?>
<!DOCTYPE html>
<html  >
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

		<a class="uk-navbar-brand" href="centro.php"><img src="Imagenes/favSmart.png" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-center" style="width:630px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
<li class="ss-navbar-center"><a href="#add_any"  data-uk-modal ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Registrar Nuevo</a></li>

<li class=""><a href="inventario_inicial.php"  ><i class="uk-icon-list <?php echo $uikitIconSize ?>"></i>&nbsp;Productos</a></li>
 
 

				<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>
			</ul>

			
			<!-- Lado derecho del Navbar -->
				
		<div class="uk-navbar-content uk-hidden-small uk-navbar-flip">
			<form class="uk-form uk-margin-remove uk-display-inline-block">
				<div class="uk-form-icon">
						<i class="uk-icon-search"></i>
						<input type="text" name="busq" placeholder="Buscar..." class="">
				</div>
				<input type="submit" value="Buscar" name="boton" class="uk-button uk-button-primary">
			</form>
		</div>
			
		</nav>
<?php
//       		 0       1                      2        3        4              5        6     7         8      
$columnas2="cod_su,id_producto_manufac,nombre_producto,ref,cod_barras,fecha_vencimiento,cant,fraccion,unidades_frac";
$tipoCol=array(0=>"hidden","hidden","text","text","text","text","text","text","text" );
$default=array(0=>"$codSuc","$serial_inv","","","","","","","" );
 
 

crear_any_form("$tabla",$columnas2,$tipoCol,$default, " function(){ return true;}", "successAny","Receta $SEDES[$codSuc]","busq_planes();",$selecOPTColumns2);
?>
<h3 align="center"><i class="uk-icon-book     uk-icon-small"></i> RECETA: </h3>
<h1 align="center"><?php echo "$detalle";?> </h1>

<br><br><br>

<form autocomplete="off" action="<?php echo $url ?>" method="get" name="form">
 
<?php //echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>
<?php require("PAGINACION.php"); ?>

<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped" >
<thead>
<tr bgcolor="#595959" style="color:#FFF" valign="top"> 
<?php echo "<td>#</td><td></td>".$cols;   ?>
</tr>
</thead>      
<tbody>    
<?php 
while ($row = $rs->fetch()) 
{ 
$ii++;
$FunctPop=" ";	
$FunctDEL="DEL_any('".$row["$col_id"]."');";
?>
<tr  bgcolor="#FFF">
<th width="50px"><?php echo $ii ?></th>
<td width="70px">
<table cellpadding="0" cellspacing="0" width="70px">
<tr>
<td>
<a href="#" class="uk-icon-list uk-icon-button uk-icon-hover uk-icon-small" onClick="<?php echo $FunctPop ?>">
</a>
</td>
<td>
<a href="#" class="uk-icon-remove uk-icon-button uk-icon-hover uk-icon-small" onClick="<?php echo $FunctDEL ?>">
</a>
</td>
</tr>
</table>


</td> 

<?php

for($i=0;$i<$max;$i++){
	// esconder columnas
	if(in_array($columnas[$i],$hidColumns) ){$HIDDEN=" uk-hidden";}
	else{$HIDDEN="";}
	
	// habilitar funcion de modificar reg
	if(in_array($columnas[$i],$modColumns) ){
		
		if(in_array($columnas[$i],$fechaColumns) ){$funct="mod_tab_row('tabTD$ii".$i."','$tabla','$colSet[$i]','".$row[$colSet[$i]]."',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','date','','');";}
		
		else {$funct="mod_tab_row('tabTD$ii".$i."','$tabla','$colSet[$i]','".$row[$colSet[$i]]."',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','text','','');";}
		}
	else {$funct="";}
	

	
	//if($rolLv!=$Adminlvl){$funct="";}
	
	if($colSet[$i]=="nom_cli"){echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct\">".$row["nom_cli"]." ".$row["snombr"]." ".$row["apelli"]."</td>";}
	else if($colSet[$i]=="num_fac_ven"){echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct\">".$row["prefijo"]."".$row["num_fac_ven"]."</td>";}
	// cols formato estado 1 / 0
	else if(in_array($columnas[$i],$estadoOkColumns)){		
if($row[$colSet[$i]]==1){$showEstado='<div class="uk-button uk-button-success"><i class="uk-icon uk-icon-check uk-icon-large"></i></div>';}
else {$showEstado='<div class="uk-button uk-button-danger"><i class="uk-icon uk-icon-remove uk-icon-large"></i></div>';}
echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct\">".$showEstado."</td>";	
	}
	// columns formato moneda
	else if(in_array($columnas[$i],$monedaColumns)){
$funct2="mod_tab_row('tabTD$ii".$i."','$tabla','$colSet[$i]','".$row[$colSet[$i]]."',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','text','','num');";
echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct2\">$".money($row[$colSet[$i]])."</td>";
}
	// column con select para modificacion
	else if(in_array($columnas[$i],$selecColumns)){
$funct3="mod_tab_row('tabTD$ii".$i."','$tabla','$colSet[$i]','".$row[$colSet[$i]]."',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','select','".$selecOPTColumns[$colSet[$i]]."','');";

echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct3\">".$row[$colSet[$i]]."</td>";
}
	else{echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct\">".$row[$colSet[$i]]."</td>";}
}

?>
</tr> 
         
<?php  } ?>
</tbody>
</table>
</form>

</div>
<?php require("PAGINACION.php"); ?>
<?php require_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<!--<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php //echo "$LAST_VER" ?>"></script>-->
<script type="text/javascript" language="javascript1.5">
function DEL_any(id)
{
	var data="ID="+id;
	if(confirm("Eliminar registro?")){
	ajax_x('ajax/FORMS/DEL_materia_prima_receta.php',data,function(resp){
		
 		if(resp==1){
		//alert("ELIMINADO");
		waitAndReload();	
		}
		
		});
		
	}
};
function selc(f2,f3,f4,f5,f6,f7,f8)
{
	$("#f2").prop('value',f2);
	$("#f3").prop('value',f3);
	$("#f4").prop('value',f4);
	$("#f5").prop('value',f5);
	$("#f6").prop('value',f6);
	$("#f7").prop('value',f7);
	$("#f8").prop('value',f8);
	hide_pop('#modal');
	$('#save').click();
	
};
function busq_planes()
{//alert(n.val());
 
	
	if(1){
		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_materia_prima.php',
		data:{busq:""},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			//$('#Qresp').html(text);
			open_pop('Resultado Busqueda','',text);
			remove_pop($('#modal'));
			
			if($('#tab_art').lenght!=0){
				 
				$('#tab_art').focus();
			
				}
			/*$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);*/
			}
			else {warrn_pop('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
	
	}
	else {busq_all(n);}
};
</script>
</div>
</div><!--fin pag 1-->

</body>
</html>