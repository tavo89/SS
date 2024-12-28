<?php 
include("Conexxx.php");
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


$tabla="fac_venta";
$codSuCol="nit";
$col_id="serial_fac";
// columnas para mostrar y modificar
//CONCAT(prefijo,' ' ,num_fac_ven) as $new_str = str_replace(' ', '', $old_str);
$columns=str_replace(' ', '',"serial_fac,num_fac_ven,nom_cli,tel_cli,dir,ciudad,tot,fecha,prefijo,apelli,snombr,estado_pago");

$hidColumns=str_replace(' ', '',"serial_fac,prefijo,apelli,snombr");
$hidColumns=explode(",",$hidColumns);

$monedaColumns=str_replace(' ', '',"tot");
$monedaColumns=explode(",",$monedaColumns);

$fechaColumns=str_replace(' ', '',"fecha");
$fechaColumns=explode(",",$fechaColumns);

$modColumns=str_replace(' ', '',"estado_pago");
$modColumns=explode(",",$modColumns);

$estadoOkColumns=str_replace(' ', '',"estado_pago");
$estadoOkColumns=explode(",",$estadoOkColumns);

$cols=explode(",",$columns);
$columnas=$cols;


$maxCols=count($cols);
$colSet[]="";
for($i=0;$i<$maxCols;$i++){$colSet[$i]=$cols[$i];}
$url="ventas_mensuales_gestion.php";
$ORDER_BY="ORDER BY fecha ";

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

$sql = "SELECT  $columns FROM $tabla WHERE  $codSuCol=$codSuc AND tipo_venta='Credito' AND ".VALIDACION_VENTA_VALIDA." $ORDER_BY DESC    LIMIT $offset, $limit "; 
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


$sql_busq="SELECT $columns FROM $tabla WHERE $codSuCol=$codSuc AND ( $ND    ) ";


$rs=$linkPDO->query($sql_busq);
	}
$sqlTotal = "SELECT COUNT(*) AS total FROM $tabla WHERE  $codSuCol=$codSuc AND tipo_venta='Credito'"; 

$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 


$infoPlanes = new Clientes();
$arrayIdsClientes = $infoPlanes->getListaDeudoresPlanes();
$bigList="";
$sqlAux="SELECT * FROM servicio_internet_planes WHERE cod_su=$codSuc";
$rsAux=$linkPDO->query($sqlAux);
$contadorFacturasPendientes=0;



foreach($arrayIdsClientes as $key => $values){
	

	$contadorFacturasPendientes++;
	$bigList.="$values;";
	
}

?>
<!DOCTYPE html>
<html  >
<head>
<?php include_once("HEADER.php"); ?>
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
			<?php include_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">



		<!-- Lado izquierdo del Nav -->
		<nav class="uk-navbar">

		<a class="uk-navbar-brand" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-center" style="width:630px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
<li class="ss-navbar-center"><a href=""  data-uk-modal onClick="sumit($('#listCli'),';');" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Facturar MES <?php echo "($contadorFacturasPendientes)"?></a></li>

<!-- href="IMPRIMIR_cola_facs.php" -->
<li class=""><a   href="#filtros" data-uk-modal><i class="uk-icon-print <?php echo $uikitIconSize ?>"></i>&nbsp;Imprimir Facturas</a></li>

 

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

<h1 align="center">GESTION FACTURAS CORTE MENSUAL <?php //echo "modulo: ".$MODULES["modulo_planes_internet"];?></h1>
<?php
if($fac_servicios_mensuales==1 && $rolLv==$Adminlvl) {
	echo $infoPlanes->getEstadoCuentaServicios();
}

?>
<form autocomplete="off" action="<?php echo $url ?>" method="get" name="form" class="uk-form">
 
<?php //echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>
<?php include("PAGINACION.php"); ?>
<textarea id="listCli" name="listCli" rows="20" cols="20" class="uk-hidden"><?php echo $bigList;?></textarea>
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
	

	
	if($rolLv!=$Adminlvl){$funct="";}
	
if($colSet[$i]=="nom_cli"){echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct\">".$row["nom_cli"]." ".$row["snombr"]." ".$row["apelli"]."</td>";}
else if($colSet[$i]=="num_fac_ven"){echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct\">".$row["prefijo"]."".$row["num_fac_ven"]."</td>";}
else if(in_array($columnas[$i],$estadoOkColumns))
{		
if($row[$colSet[$i]]==1){$showEstado='<div class="uk-button uk-button-success"><i class="uk-icon uk-icon-check uk-icon-large"></i></div>';}
else {$showEstado='<div class="uk-button uk-button-danger"><i class="uk-icon uk-icon-remove uk-icon-large"></i></div>';}
echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct\">".$showEstado."</td>";	
}
else if(in_array($columnas[$i],$monedaColumns))
{
$funct2="mod_tab_row('tabTD$ii".$i."','$tabla','$colSet[$i]','".$row[$colSet[$i]]."',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','text','','num');";
echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct2\">$".money($row[$colSet[$i]])."</td>";
}
	else{echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct\">".$row[$colSet[$i]]."</td>";}
}

?>
</tr> 
         
<?php  } ?>
</tbody>
</table>






<?php include("PAGINACION.php"); ?>
</form>

<?php
$SelOpt2a=$_SESSION["servicios_rutas2"];
$listaRutas=$_SESSION["servicios_rutas_lista"];
?>
<form name="form_filtro" action="IMPRIMIR_cola_facs.php" class="uk-form" target="_blank">
<div id="filtros" class="uk-modal" >
<div class="uk-modal-dialog" style="width:800px;">

<a class="uk-modal-close uk-close"></a>
 <h1 class="uk-text-primary uk-text-bold">Opciones Impresi&oacute;n</h1>
<table width="100%" class="uk-table">
<thead>
</thead>
<tbody>
<tr>
<th align="left">Limite Facturas</th>
<td ><input type="text" id="limFacs" name="limFacs" value="" placeholder="50" class="uk-form uk-form-width-small" style="width:55px;"> </td>
</tr>
<tr>
<th align="left">Filtro Anuladas</th>
<td ><input type="checkbox" id="filtroAnuladas" name="filtroAnuladas" value="si"  class="uk-form" > </td>
</tr>

<tr>
<th align="left">Orden Impresi&oacute;n</th>
<td >
Rutas:
	<select name="filtroRutas" onChange="" style="width:100px;">
	 <?php echo $SelOpt2a;?>
	</select>

<select name="campo_orden_consulta">
<option value="alfabetico" selected>Alfab&eacute;tico - Clientes</option>
<option value="numeracion">Numeracion - Factura</option>
<option value="ruta">Orden Ruta</option>

</select>
ASCENDENTE <i class="uk-icon uk-icon-sort-alpha-asc"></i><input type="radio"  name="radio_orden" value="ASC"  class="uk-form" > 
&nbsp;&nbsp;&nbsp;
DESCENDENTE  <i class="uk-icon uk-icon-sort-alpha-desc"></i><input type="radio"  name="radio_orden" value="DESC"  class="uk-form" >
</td>
</tr>
 
<tr>
<th align="left">Fecha</th>
<td >
 <div class="uk-form-icon">
    <i class="uk-icon-calendar"></i>
<input size="10" name="fechaI" value="<?php echo "" ?>" type="date" id="f_ini"  placeholder="Fecha" class="uk-form-large">
</div>

<div class="uk-form-icon">
    <i class="uk-icon-calendar"></i> 
<!--                
<input size="15" value="<?php echo "" ?>" type="text" name="fechaF" id="f_fin"   readonly placeholder="Fecha Final" class="uk-form-large " data-uk-datepicker="{<?php echo $UKdatePickerFormat; ?>}">
-->
<input size="10" name="fechaF" value="<?php echo "" ?>" type="date" id="f_fin"  placeholder="Fecha" class="uk-form-large">
</div>
</td>

</tr>


<tr>
<td colspan="2" align="center"><input type="submit" value="IMPRIMIR" name="filtro"  class="uk-button uk-button-large uk-button-primary uk-width-1-1"></td>
</tr>
</tbody>
</table>
    </div>
</div>
</form>

</div>

<?php include_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<!--<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php //echo "$LAST_VER" ?>"></script>-->
<script type="text/javascript" language="javascript1.5">
var cont_i=0;
var Global_txt='';

$("a.link").on("click",function(){
         window.open('IMPRIMIR_cola_facs.php','_blank');
     });

function sumit($data,separador)
{
	var text=$data.val().trim();
	Global_txt=text.split(separador);
	var n=Global_txt.length;
	var i=0;
	var html='';
	
	html='<h1 class="uk-text-bold uk-text-primary">Facturando a todos los clientes autorizados</h1><h3 class="uk-text-bold uk-text-warning">Por favor ESPERE</h3>';
	html+= '<div class="uk-progress uk-progress-success"><div id="progress_bar" class="uk-progress-bar" style="width: 0%;">0%</div></div>';

	
	var modal = UIkit.modal.blockUI(html);
	cola_exe(n,modal);

		
		
//}
};
function cola_exe(n,modal)
{
	
	//block_modal('#modal');
	if(1){
	var data=('idCliente='+Global_txt[cont_i]);
	

	
	ajax_x('ajax/facturar_planes_mes.php',data,function(resp){
		var html='';
		var $html='';
		
		var r=parseInt(resp);
		
		
		
		if(r==1)
		{
			html='Ok '+cont_i+' '+resp;
			
			}else{html='<span style="color:white; font-size:12px;"><b>'+resp+'</b></span>';}
		
			
			$html=$('<li>'+html+'</li>');
			//$html.appendTo('#mensaje');
			
			
			cont_i++;
			
			var porcentaje=(cont_i/n)*100;
			porcentaje=redondeox(porcentaje,0);
			$('#progress_bar').css("width", porcentaje+"%").html(porcentaje+"%");
			if(cont_i<n){cola_exe(n);}
			else{
				//setTimeout(function(){ location.reload(); }, 2000);
				}
			//hide_pop('#modal');
		
		});
		
		
		
		
	}
	
};

var BigSQL=$('#listCli').val();
//sumit($('#listCli'),';');

 
</script>
</div>
</div><!--fin pag 1-->

</body>
</html>