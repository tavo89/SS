<?php 
require("Conexxx.php");
//////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_orden_gar";
$PAG_fechaF="fechaF_orden_gar";
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


/////////////////////////////////////////////////////////////////////// FILTRO Pagos FAC Compras. ///////////////////////////////////////////////////////////
$filtroB="";
$D="";
if(isset($_REQUEST['filtroB'])){
	$filtroB=$_REQUEST['filtroB'];
	$_SESSION['filtroB_ventas']=$filtroB;
	if($filtroB=="Pendientes")$B="AND anulado=''";
	else if($filtroB=="Cerradas")$B="AND anulado='CERRADA'";
	else if($filtroB=="Anuladas"){$B=" AND anulado='ANULADO'";}
	else $B="";
}

if(isset($_SESSION['filtroB_ventas']))
{
	$filtroB=$_SESSION['filtroB_ventas'];
	if($filtroB=="Pagos")$D="AND serial_fac_com!=0";

	else $D="";
	
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////// FILTRO NOMBRE ///////////////////////////////////////////////////////////////////

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
if(isset($_GET['opc'])){$opc=$_REQUEST['opc'];}
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val=$_REQUEST['valor'];
if(isset($_REQUEST['boton']))$boton= $_REQUEST['boton'];
if(isset($_REQUEST['pre']))$pre= $_REQUEST['pre'];



//-----------------------------------------------------------------------------------------------------------------------------------------------------


//////////////////////////////////////////////////////////////////////////////// CABECERA TABLA ////////////////////////////////////////////////////

$badgeText="&nbsp;<div class=\"uk-badge\" data-uk-tooltip=\"{pos:'bottom-left'}\" title=\"Doble CLICK sobre las celdas de esta Columna para ACTUALIZAR los datos\"> !</div>";

$cols="<th width=\"90px\">#</th>
<td></td>
<th width=\" \">Orden </th>
<th width=\" \">Cliente</th>
<th width=\" \">CC/NIT</th>
<th width=\" \">Garant&iacute;a </th>
<th width=\" \">Ref.t</th>
<th width=\" \">Pr&eacute;stamo</th>
<th width=\" 100\">Fecha Venta</th>
<th width=\" \">#Gu&iacute;a</th>
<th width=\" \">Transportadora</th>
<th width=\" 100\">Fecha Env&iacute;o</th>
";



$tabla="orden_garantia";
$col_id="id";
$columns="*";
$url="lista_orden_garantia.php";
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="formato_garantia.php";

///////////////////////////////////////////////////////////////////// PAGINACION ///////////////////////////////////////////////////////////////////
$pag="";
$limit = 20; 
$order="fecha";
 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
 

$sql = "SELECT  $columns FROM $tabla WHERE  cod_su=$codSuc $A $C $D ORDER BY $col_id DESC  LIMIT $offset, $limit"; 
//echo $sql;

$rs =$linkPDO->query($sql); 





//-----------------------------------------------------------------------------------------------------------------------------------------------------



//////////////////////////////////////////////////////////////// BUSCAR ///////////////////////////////////////////////////////////////////////////
//echo "bot: $boton ---->$busq";
if($boton=='Buscar' && isset($busq) && !empty($busq)){

//concepto LIKE '%$busq%' OR
$ND="(id_cli LIKE '%$busq%' OR cod_garantia LIKE '%$busq%' OR id_pro LIKE '%$busq%' OR id_pro_prestamo LIKE '%$busq%' OR num_gui LIKE '%$busq%' )";

$sql_busq="SELECT $columns FROM $tabla WHERE  ( $ND    ) $A $C $D ";


$rs=$linkPDO->query($sql_busq);
	}
$sqlTotal = "SELECT COUNT(*) AS total FROM $tabla WHERE  cod_su=$codSuc $A $D"; 

$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 
 
 
 
 
 $num_fac=0;
if(isset($_REQUEST['num_fac']))$num_fac=$_REQUEST['num_fac']; 


if( val_secc($id_Usu,"crear_comp_egreso") || $rolLv==$Adminlvl){
if($opc=="new_comp")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	popup("$url_new","", "1100px","650px");
};
};
if($opc=="imp_comp")
{
	

imp_a("num_comp_ingre",$val,"imp_comp_egreso.php","Comprobante de Egreso No. $val","800px","600px");
};
?>
<!DOCTYPE html>
<html  >
<head>
<?php require_once("HEADER.php"); ?>
<script type="text/javascript">
var TipoEgresoOpt='<?php echo tipoEgresoOpt();?>';
var FPEgresoOpt='<?php echo egresoOpt($FP_egresos);?>';

</script>	
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

			<ul class="uk-navbar-nav uk-navbar-center" style="width:630px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
				<li class="ss-navbar-center"><a href="<?php echo $url ?>?opc=new_comp&valor=<?php echo 0 ?>&pag=<?php echo $pag ?>" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Crear ORDEN</a></li>
 
 

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

<h1 align="center">Ordenes de Garant&iacute;a</h1>
<br><br><br>

<form autocomplete="off" action="<?php echo $url ?>" method="get" name="form">
<div class=" ">
<table align="left" class="creditos_filter_table">
<thead>
<TR bgcolor="#CCCCCC">
<TH colspan="5" align="center">Fecha </TH>
<TH colspan="2" align="center">Filtro Pagos Compras</TH>
</TR>
</thead>
<TR>
<td>Inicia:</td>
<td>
<input type="date" name="fechaI" id="date" value="<?php echo $fechaI ?>"  style="width:135px;">
</td>
<td>Termina:
</td>
<td>
<input type="date" name="fechaF" id="date" value="<?php echo $fechaF ?>" style="width:135px;">
</td>
<td><?php echo $botonFiltro ?></td>
 <td>
 
<select name="filtroB" onChange="submit()">
<option value="TODOS" <?php if($filtroB=="TODOS")echo "selected" ?>>TODOS</option>
<option value="Pagos" <?php if($filtroB=="Pagos")echo "selected" ?>>Pagos Compras</option>

</select>
</td>
</thead>
 
</table>
</div>



 
<?php //echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>
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
			
			$ID=$row["id"];
			
			$sql="SELECT * FROM usuarios WHERE id_usu='$row[id_cli]'";
			$rs2=$linkPDO->query($sql);
			$row2=$rs2->fetch();
			
			if(!empty($row2))$nombreCli=$row2["nombre"];
			else $nombreCli="C.C. NO REGISTRADA";
		    

			
         ?>
 
<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<a href="#" onClick="print_pop('imp_garantia.php?ID=<?php echo "$ID";?>','Garantia',1100,650)" class="uk-icon-print uk-icon-button uk-icon-hover uk-icon-small"></a>
</td>
<?php 
if($rolLv==$Adminlvl || val_secc($id_Usu,"anula_comp_egreso")){
?>
<td>
<a href="#"  onMouseUp="anular_comp('<?php echo ""; ?>');" class="uk-icon-remove uk-icon-button uk-icon-hover uk-icon-small">
</a>
</td>
<?php 
}

$functNumCom="";

$cod_su_col="cod_su";
$COL_1="";	
$COL4="";	
$COL3="";	
$COL2="";
$COL5="";
$COL6="";
$COL7="";
$COL8="";
$COL9="";
$COL10="";

//if($rolLv==$Adminlvl || val_secc($id_Usu,"mod_comp_egreso")){
if(1){
//$functNumCom="mod_tab_row('tabTD08$ii','comp_egreso','num_com','$num_comp',' id=\'$ID\' AND cod_su=\'$codSuc\'','$ii','text','');";	

//$COL_1="mod_tab_row('tabTD01$ii','orden_garantia','tipo_pago','$valor',' id=\'$ID\' AND cod_su=\'$codSuc\' ','$ii','select',FPEgresoOpt);";

$COL3="mod_tab_row('tabTD03$ii','$tabla','id_cli','$row[id_cli]',' $col_id=\'$ID\' AND $cod_su_col=\'$codSuc\' ','$ii','text','','');";



$COL4="mod_tab_row('tabTD04$ii','$tabla','cod_garantia','$row[cod_garantia]',' $col_id=\'$ID\' AND $cod_su_col=\'$codSuc\'','$ii','text','','');";
//$COL5="mod_tab_row('tabTD05$ii','$tabla','cod_garantia','$row[cod_garantia]',' $col_id=\'$ID\' AND $cod_su_col=\'$codSuc\'','$ii','text','');";
//$COL6="mod_tab_row('tabTD06$ii','$tabla','id_beneficiario','$row[id_cli]',' $col_id=\'$ID\' AND $cod_su_col=\'$codSuc\'','$ii','text','');";

$COL7="mod_tab_row('tabTD07$ii','$tabla','fecha_venta','$row[fecha_venta]',' $col_id=\'$ID\' AND $cod_su_col=\'$codSuc\'','$ii','date','','');";
$COL8="mod_tab_row('tabTD08$ii','$tabla','num_gui','$row[num_gui]',' $col_id=\'$ID\' AND $cod_su_col=\'$codSuc\'','$ii','text','','');";
$COL9="mod_tab_row('tabTD09$ii','$tabla','transportadora','$row[transportadora]',' $col_id=\'$ID\' AND $cod_su_col=\'$codSuc\'','$ii','text','','');";
$COL10="mod_tab_row('tabTD10$ii','$tabla','fecha_envio','$row[fecha_envio]',' $col_id=\'$ID\' AND $cod_su_col=\'$codSuc\'','$ii','date','','');";
//$functNumComp="mod_tab_row('tabTD08$ii','comp_egreso','num_com','$num_comp',' id=\'$ID\' AND cod_su=\'$codSuc\'','$ii','text','');";
}
?>

</tr>
</table>


</td> 
<td id="tabTD01<?php echo $ii ?>" onDblClick="<?php //echo $COL_1; ?>"><?php echo $row["id"]; ?></td> 
<td id="tabTD02<?php echo $ii ?>" onDblClick="<?php echo $COL2; ?>"><?php echo $nombreCli; ?></td>
            
<td id="tabTD03<?php echo $ii ?>" onDblClick="<?php echo $COL3; ?>"><?php echo $row["id_cli"]; ?></td>
<td id="tabTD04<?php echo $ii ?>" onDblClick="<?php echo $COL4; ?>"><?php echo $row["cod_garantia"]; ?></td>
<td id="tabTD05<?php echo $ii ?>" onDblClick="<?php echo $COL5; ?>"><?php echo $row["id_pro"]; ?></td>
<td id="tabTD06<?php echo $ii ?>" onDblClick="<?php echo $COL6; ?>"><?php echo $row["id_pro_prestamo"]; ?></td>
<td id="tabTD07<?php echo $ii ?>" onDblClick="<?php echo $COL7; ?>"><?php echo $row["fecha_venta"]; ?></td>
<td id="tabTD08<?php echo $ii ?>" onDblClick="<?php echo $COL8; ?>"><?php echo $row["num_gui"] ?></td>
<td id="tabTD09<?php echo $ii ?>" onDblClick="<?php echo $COL9; ?>"><?php echo $row["transportadora"] ?></td>
<td id="tabTD10<?php echo $ii ?>" onDblClick="<?php echo $COL10; ?>"><?php echo $row["fecha_envio"]; ?></td> 
<!--<td><?php //echo $fecha_cuotas; ?></td>-->

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
function anular_comp(num_comp)
{
	if(!esVacio(num_comp)){
	if(confirm('Desea ANULAR Comprobante Egreso No.'+num_comp)){
	 $.ajax({
		url:'ajax/anula_comp_egreso.php',
		data:{num_comp:$.trim(num_comp)},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		var r=text.split('|');
		
		if(resp==0)simplePopUp('Este Comprobante YA esta Anulado!');
		else if(resp!=-2&&resp!=-1)
		{
			simplePopUp('Comprobante No.'+num_comp+' ANULADO');
			waitAndReload();
			
		}
		else if(resp==-1){simplePopUp('Este Comprobante supera el limite de tiempo permitido(1 dia) para modificaciones, accion cancelada.... ');}
		else simplePopUp('Comprobante No.'+num_comp+' NO encontrado');
		
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
	 });
	 
	}// if confirma
	
	}// if vacios
else {simplePopUp('Complete los espacios! No. Factura y PREFIJO(MTRH,RH,RAC,etc.)')}
	};
</script>
</div>
</div><!--fin pag 1-->

</body>
</html>