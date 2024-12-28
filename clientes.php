<?php 
require("Conexxx.php");
$UserFilter="AND fecha_crea>='$fechaCreaUsu'";
if($rolLv==$Adminlvl || $separar_registros_por_usuarios==0)$UserFilter="";
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


//----------------------------------------------------- FILTRO GRUPOS --------------------------------------------

$filtroGrupos=0;
$B="";
if(isset($_REQUEST['filtroGrupos'])){
	$filtroGrupos=$_REQUEST['filtroGrupos'];
	$_SESSION['filtroGruposdifusion']=$filtroGrupos;
	if($filtroGrupos!=0){$B=" AND id_grupo_difusion='$filtroGrupos'";}
	else {$B="";}
}

if(isset($_SESSION['filtroGruposdifusion']))
{
	$filtroGrupos=$_SESSION['filtroGruposdifusion'];
	if($filtroGrupos!=0){$B=" AND id_grupo_difusion='$filtroGrupos'";}
	else {$B="";}
	
	
}

//----------------------------------------------------------------------------------------------------------------

//----------------------------------------------------- FILTRO RUTAS --------------------------------------------

$filtroRutas=-1;
$C="";
if(isset($_REQUEST['filtroRutas'])){
	$filtroRutas=$_REQUEST['filtroRutas'];
	$_SESSION['filtroRutas']=$filtroRutas;
	if($filtroRutas!=-1){$C=" AND id_ruta='$filtroRutas'";}
	else {$C="";}
}

if(isset($_SESSION['filtroRutas']))
{
	$filtroRutas=$_SESSION['filtroRutas'];
	if($filtroRutas!=-1){$C=" AND id_ruta='$filtroRutas'";}
	else {$C="";}
	
	
}

//----------------------------------------------------------------------------------------------------------------

//----------------------------------------------------- FILTRO NODOS --------------------------------------------

$filtroNodos=-1;
$E="";
if(isset($_REQUEST['filtroNodos'])){
	$filtroNodos=$_REQUEST['filtroNodos'];
	$_SESSION['filtroNodos']=$filtroNodos;
	if($filtroNodos!=-1){$E=" AND id_nodo='$filtroNodos'";}
	else {$E="";}
}

if(isset($_SESSION['filtroRutas']))
{
	$filtroNodos=$_SESSION['filtroNodos'];
	if($filtroNodos!=-1){$E=" AND id_nodo='$filtroNodos'";}
	else {$E="";}
	
	
}

//----------------------------------------------------------------------------------------------------------------

//$C="";
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




//////////////////////////////////////////////////////////// FUNCIONES ADICIONALES ////////////////////////////////////////////////////////////////
 
$val="";
$val2="";
$val3="";
$val4="";

$valFilter=r("filter");

 

 
if(isset($_REQUEST['valor']))$val= $_REQUEST['valor'];
if(isset($_REQUEST['valor2']))$val2= $_REQUEST['valor2'];
if(isset($_REQUEST['valor3']))$val3= $_REQUEST['valor3'];
if(isset($_REQUEST['valor4']))$val4= $_REQUEST['valor4'];

if($boton=="mod_cli"){
	
	$_SESSION['id_cli']=$val;
	$_SESSION['nombre_cli']=$val2;
	$_SESSION['snombr_cli']=$val3;
	$_SESSION['apelli_cli']=$val4;
	header("location: mod_clientes.php?pag=$pag");
};

// ------------------------------------------------------------- FIN FUNCIONES  -------------------------------------------------------------------



//////////////////////////////////////////////////////////////////////////////// CABECERA TABLA ////////////////////////////////////////////////////






$tabla="usuarios";
$codSuCol="cod_su";
$col_id="id";
// columnas para mostrar y modificar
//CONCAT(prefijo,' ' ,num_fac_ven) as $new_str = str_replace(' ', '', $old_str);
$columns=str_replace(' ', '',"id,id_usu,nombre,id_grupo_difusion,id_nodo,id_ruta,orden_ruta,tel,dir,fecha_corte_plan,apelli,snombr,auth_credito,cod_su");

$hidColumns=str_replace(' ', '',"id,apelli,snombr,cod_su,dir,auth_credito");
if($MODULES["modulo_planes_internet"]!=1 && $fac_servicios_mensuales!=1){
$hidColumns=str_replace(' ', '',"id,apelli,snombr,cod_su,id_grupo_difusion,id_ruta,fecha_corte_plan,orden_ruta,id_nodo");
}
$hidColumns=explode(",",$hidColumns);

$monedaColumns=str_replace(' ', '',"");
$monedaColumns=explode(",",$monedaColumns);

$fechaColumns=str_replace(' ', '',"fecha_corte_plan");
$fechaColumns=explode(",",$fechaColumns);

$modColumns=str_replace(' ', '',"fecha_corte_plan	,auth_credito,id_grupo_difusion,id_ruta,orden_ruta");
$modColumns=explode(",",$modColumns);

$estadoOkColumns=str_replace(' ', '',"auth_credito");
$estadoOkColumns=explode(",",$estadoOkColumns);

$cols=explode(",",$columns);
$columnas=$cols;

$selecColumns=str_replace(' ', '',"id_grupo_difusion,id_ruta");
$selecColumns=explode(",",$selecColumns);



//grupos difusion
$SelOpt1=$_SESSION["sms_grupos_difusion"];
$SelOpt2=$_SESSION["sms_grupos_difusion2"];
$listaGrupos=$_SESSION["sms_grupos_difusion_lista"];

// rutas

$SelOpt1a=$_SESSION["servicios_rutas"];
$SelOpt2a=$_SESSION["servicios_rutas2"];
$listaRutas=$_SESSION["servicios_rutas_lista"];


// nodos

$SelOpt1b=$_SESSION["servicios_nodos"];
$SelOpt2b=$_SESSION["servicios_nodos2"];
$listaNodos=$_SESSION["servicios_nodos_lista"];


//echo "SelOpt1: $SelOpt1";

$selecOPTColumns=array("id_grupo_difusion"=>$SelOpt1,"id_ruta"=>$SelOpt1a,"id_nodo"=>$SelOpt1b);
 



$maxCols=count($cols);
$colSet[]="";
for($i=0;$i<$maxCols;$i++){$colSet[$i]=$cols[$i];}
$url="clientes.php";
$url_new="registro_cli.php";
$ORDER_BY="ORDER BY nombre,orden_ruta ";

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

$sql = "SELECT  $columns FROM $tabla WHERE  $codSuCol=$codSuc AND cliente=1 $UserFilter $B $C $E $ORDER_BY ASC    LIMIT $offset, $limit "; 
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


$sql_busq="SELECT $columns FROM $tabla WHERE $codSuCol=$codSuc AND ( $ND    ) $UserFilter ";
//echo "$sql_busq";

$rs=$linkPDO->query($sql_busq);
	}
$sqlTotal = "SELECT COUNT(*) AS total FROM $tabla WHERE  $codSuCol=$codSuc $UserFilter"; 

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

		<a class="uk-navbar-brand" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-center" style="width:630px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
<!--		
<li class="ss-navbar-center"><a href="#add_any"  data-uk-modal ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Registrar Nuevo</a></li>-->
<?php if(($rolLv==$Adminlvl || val_secc($id_Usu,"clientes_add")) && $codSuc>0){?>
<li class="ss-navbar-center"><a href="<?php echo $url_new ?>"  ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Registrar Cliente</a></li>
<?php } ?>


<?php

if($MODULES["modulo_planes_internet"]==1 || $fac_servicios_mensuales==1){

?>
        
<li class="uk-active"><a href="<?php echo $url_new ?>"  ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Clientes con Plan</a></li>   

<?php }?> 
 

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

<h1 align="center">CLIENTES</h1>

<?php


if($fac_servicios_mensuales==1 && $rolLv==$Adminlvl ) {
	$infoPlanes = new Clientes();
	echo $infoPlanes->getEstadoCuentaServicios();
}
?>


<form autocomplete="off" action="<?php echo $url ?>" method="get" name="form">
<?php //echo "filtroGrupos: $filtroGrupos";?>
<div style="display:inline-block;">
	<table cellpadding="0" cellspacing="1" class="creditos_filter_table" >
	<thead>
	<TR bgcolor="#CCCCCC">

	<th colspan="" align="center">Filtro Grupos</th>
    <th colspan="" align="center">Filtro Rutas</th>
    <th colspan="" align="center">Filtro Nodos</th>

	</TR>
	</thead>
	<tr>
	<td>
	<select name="filtroGrupos" onChange="submit()">
	<option value="<?php echo $filtroGrupos;?>"  selected><?php echo $listaGrupos[$filtroGrupos];?></option>
	 <?php echo $SelOpt2;?>
	</select>
	</td>
    
	<td>
	<select name="filtroRutas" onChange="submit()">
	<option value="<?php echo $filtroRutas;?>"  selected><?php echo $listaRutas[$filtroRutas];?></option>
	 <?php echo $SelOpt2a;?>
	</select>
	</td>
    
    <td>
	<select name="filtroNodos" onChange="submit()">
	<option value="<?php echo $filtroNodos;?>"  selected><?php echo $listaNodos[$filtroNodos];?></option>
	 <?php echo $SelOpt2b;?>
	</select>
	</td>
 
	</tr>
	</tbody>
	</table>
</div>


<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped" style="font-size:12px;" >
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
$ID = $row["id"];
$id = $row["id_usu"]; 
$cod_su = $row["cod_su"]; 
$nom_su = $row["nombre"];
$snombr = $row["snombr"];
$apelli = $row["apelli"];
$nom_suShow="$nom_su $snombr $apelli";

$FunctPop=" ";	
$FunctDEL="DEL_any('".$row["$col_id"]."');";
?>
<tr  bgcolor="#FFF">
<th width=" "><?php echo $ii ?></th>
<td width="">
<table cellpadding="0" cellspacing="0" width="70px">
<tr>
<td>
<div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
<a class="uk-button uk-button-primary" style="width:100px;">Opciones <i class="uk-icon-caret-down"></i></a>
<div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom" style="top: 30px; left: 0px;">
<ul class="uk-nav uk-nav-dropdown">

<li>
<a href="<?php echo "$url?boton=mod_cli&valor=$id&valor2=".urlencode($nom_su)."&valor3=".urlencode($snombr)."&valor4=".urlencode($apelli)."&pag=$pag"; ?>" >
<i class="uk-icon-remove   uk-icon-pencil uk-icon-small"></i> Modificar Cliente 
</a>
</li>
 
<?php if($rolLv==$Adminlvl || val_secc($id_Usu,"clientes_eli")){?>
<li>
<a href="#"  onMouseUp="val_del_cli('<?php echo $id; ?>','<?php echo $nom_su; ?>','<?php echo $ID; ?>');">
<i class="uk-icon-remove   uk-icon-small"></i> Eliminar Cliente
</a>
</li>
<?php  }?>



<?php if($MODULES["modulo_planes_internet"]==1 || $fac_servicios_mensuales==1){?>
<li class="uk-nav-divider"></li>
<li>
<a href="<?php echo "servicios_gestion_planes_internet.php?id_cliente=$id"; ?>" >
<i class="uk-icon-globe   uk-icon-pencil uk-icon-small"></i> Planes Internet
</a>
</li>
<?php  }?>
</ul>
</div>
</div>
</td>
</tr>
</table>


</td> 

<?php

for($i=0;$i<$max;$i++){
	$Clientes = new Clientes($row['id_usu']);
	$listaServicios = $Clientes->getServiciosSuscripcion();
	// esconder columnas
	if(in_array($columnas[$i],$hidColumns) ){$HIDDEN=" uk-hidden";}
	else{$HIDDEN="";}
	
	// habilitar funcion de modificar reg
	if(in_array($columnas[$i],$modColumns) ){
		
		if(in_array($columnas[$i],$fechaColumns) ){
			$funct="mod_tab_row('tabTD$ii".$i."','$tabla','$colSet[$i]','".$row[$colSet[$i]]."',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','date','','');";
		}
		
		else {
			$funct="mod_tab_row('tabTD$ii".$i."','$tabla','$colSet[$i]','".$row[$colSet[$i]]."',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','text','','');";
		}
		}
	else {$funct="";}



		//if($rolLv!=$Adminlvl){$funct="";}


		if ($colSet[$i] == "nombre") {
			echo "<td class=\" $HIDDEN \" id=\"tabTD$ii" . $i . "\" onDblClick=\"$funct\">" . $row["nombre"] . " " . $row["snombr"] . " " . $row["apelli"] . "</td>";
		} else if ($colSet[$i] == "id_grupo_difusion") {
			$funct3 = "mod_tab_row('tabTD$ii" . $i . "','$tabla','$colSet[$i]','" . $row[$colSet[$i]] . "',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','select','" . $selecOPTColumns[$colSet[$i]] . "','');";

			echo "<td class=\" $HIDDEN \" id=\"tabTD$ii" . $i . "\" onDblClick=\"$funct3\">" . $listaGrupos[$row["id_grupo_difusion"]] . "</td>";
		} else if ($colSet[$i] == "id_ruta") {
			$funct3 = "mod_tab_row('tabTD$ii" . $i . "','$tabla','$colSet[$i]','" . $row[$colSet[$i]] . "',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','select','" . $selecOPTColumns[$colSet[$i]] . "','');";

			echo "<td class=\" $HIDDEN \" id=\"tabTD$ii" . $i . "\" onDblClick=\"$funct3\">" . $listaRutas[$row["id_ruta"]] . "</td>";
		} else if ($colSet[$i] == "id_nodo") {
			$funct3 = "mod_tab_row('tabTD$ii" . $i . "','$tabla','$colSet[$i]','" . $row[$colSet[$i]] . "',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','select','" . $selecOPTColumns[$colSet[$i]] . "','');";

			echo "<td class=\" $HIDDEN \" id=\"tabTD$ii" . $i . "\" onDblClick=\"$funct3\">" . $listaNodos[$row["id_nodo"]] . "</td>";
		} else if ($colSet[$i] == "num_fac_ven") {
			echo "<td class=\" $HIDDEN \" id=\"tabTD$ii" . $i . "\" onDblClick=\"$funct\">" . $row["prefijo"] . "" . $row["num_fac_ven"] . "</td>";
		} else if (in_array($columnas[$i], $estadoOkColumns)) {
			if ($row[$colSet[$i]] == 1) {
				$showEstado = '<div class="uk-button uk-button-success"><i class="uk-icon uk-icon-check uk-icon-large"></i></div>';
			} else {
				$showEstado = '<div class="uk-button uk-button-danger"><i class="uk-icon uk-icon-remove uk-icon-large"></i></div>';
			}
			echo "<td class=\" $HIDDEN \" id=\"tabTD$ii" . $i . "\" onDblClick=\"$funct\">" . $showEstado . "</td>";
		} else if (in_array($columnas[$i], $monedaColumns)) {
			$funct2 = "mod_tab_row('tabTD$ii" . $i . "','$tabla','$colSet[$i]','" . $row[$colSet[$i]] . "',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','text','','num');";
			echo "<td class=\" $HIDDEN \" id=\"tabTD$ii" . $i . "\" onDblClick=\"$funct2\">$" . money($row[$colSet[$i]]) . "</td>";
		}
		// column con select para modificacion
		else if (in_array($columnas[$i], $selecColumns)) {
			$funct3 = "mod_tab_row('tabTD$ii" . $i . "','$tabla','$colSet[$i]','" . $row[$colSet[$i]] . "',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','select','" . $selecOPTColumns[$colSet[$i]] . "','');";

			echo "<td class=\" $HIDDEN \" id=\"tabTD$ii" . $i . "\" onDblClick=\"$funct3\">" . $row[$colSet[$i]] . "</td>";
		} else if ($colSet[$i] == "fecha_corte_plan") {

			echo "<td class=\" $HIDDEN \" id=\"tabTD$ii" . $i . "\" onDblClick=\"\">" . $listaServicios . "</td>";

		} else {
			echo "<td class=\" $HIDDEN \" id=\"tabTD$ii" . $i . "\" onDblClick=\"$funct\">" . $row[$colSet[$i]] . "</td>";
		}
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
$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});

});

function val_del_cli(id,nom,ID)
{
	$.ajax({
		url:'ajax/val_del_cli.php',
		data:{id_cli:id,id:ID,nom:nom},
		type:'POST',
		dataType:'text',
		success:function(text){
			
			if(text==2)
			{
				if(confirm('Este Cliente Tiene FACTURAS en sistema, Realmente desea BORRARLO?'))
				{
					del_cli(ID,id,nom,1);	
				}
			}
			else if(text==3)
			{del_cli(ID,id,nom,1);}
			else simplePopUp(text);
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});
};
function del_cli(ID,id_cli,nom,r)
{
	if(confirm('Desea Borrar este Cliente? '+nom+' C.C: '+id_cli))
	{
	$.ajax({
		url:'ajax/del_cli.php',
		data:{id:ID,id_cli:id_cli,resp:r,nom:nom},
		type:'POST',
		dataType:'text',
		success:function(text){
			if(text==1)
			{
				simplePopUp('Cliente BORRADO');
				waitAndReload();
			}
			else if(text==-1){simplePopUp('Usuario no encontrado...');}
			else {simplePopUp(text);}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});
		}
};
</script>
</div>
</div><!--fin pag 1-->

</body>
</html>