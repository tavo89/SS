<?php 
require("Conexxx.php");
//////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}



$filtro="";
$filtro_cant="";
$filtro_fab="";
//-----------------------------------------------------------------------------------------------------------------------------------------------------

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

$tabla="rotacion_inv";
$codSuCol="cod_su";
$col_id="ref";
$columns="ref,cod_bar,des,exist,min,max,CP,Pp,tot_ventas";
$colSet= array(0=>"ref","cod_bar","des","exist","min","max","CP","Pp","tot_ventas");
$url="ROTACION_INV.php";
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="#";

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
 
 
 
$sql="SELECT a.COLUMN_NAME, a.COLUMN_COMMENT FROM information_schema.COLUMNS a WHERE a.TABLE_NAME =  '$tabla' AND a.COLUMN_COMMENT !=  ''";
$rs=$linkPDO->query($sql);
$HEADER_TABLE[]="";

while($row=$rs->fetch()){
	$HEADER_TABLE[$row["COLUMN_NAME"]]=$row["COLUMN_COMMENT"];
	
}
$cols="";
$max=count($colSet);
for($i=0;$i<$max;$i++){

	$cols.="<td>".$HEADER_TABLE[$colSet[$i]]."</td>";
}

$sql = "SELECT  $columns FROM $tabla WHERE  $codSuCol=$codSuc ORDER BY tot_ventas DESC    LIMIT $offset, $limit "; 
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
$sqlTotal = "SELECT COUNT(*) AS total FROM $tabla WHERE  $codSuCol=$codSuc"; 

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

		<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/favSmart.png" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-center" style="width:630px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
				<li class="ss-navbar-center"><a href="<?php echo $url ?>?opc=new_comp&valor=<?php echo 0 ?>&pag=<?php echo $pag ?>" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Crear Comprobante</a></li>
 
 

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

<h1 align="center">ROTACI&Oacute;N INVENTARIO</h1>
<br>


<table width="700px"  cellpadding="0" cellspacing="0" align="" class="creditos_filter_table">
<tr>
<td>Rotaci&oacute;n</td>
<td>Existencias</td>
<td>Proveedores</td>

</tr>
<tr>
<td>
<select name="filtro" onChange="submit();">
<option value="all" <?php if($filtro=="all")echo "selected";?>>TODAS</option>
<option value="Pp" <?php if($filtro=="Pp")echo "selected";?> >Exist. en Punto de Pedido</option>
<option value="bad" <?php if($filtro=="bad")echo "selected";?>>Exist. Sin Rotaci&oacute;n</option>
</select>
</td>
<td>
<select name="filtro_cant" onChange="submit();">
<option value="ALL" <?php if($filtro_cant=="ALL")echo "selected";?>>Existencias-TODOS</option>
<option value="cero" <?php  if($filtro_cant=="cero")echo "selected";?>>Iguales  Cero</option>
<option value="noCero" <?php if($filtro_cant=="noCero")echo "selected";?>>En Stock</option>
</select>
</td>
<td>
           <select name="filtro_fab" onChange="submit();">
           <option value="ALL" <?php if($filtro_fab=="ALL")echo "selected";?>>Proveedor-TODOS</option>
       
         
       
            <?php
		    $rs2=$linkPDO->query("SELECT * FROM provedores WHERE nit!='$NIT_FANALCA' ORDER BY nom_pro");
			while($row=$rs2->fetch()){
			?>
            <option value="<?php echo $row['nit'] ?>" <?php if($filtro_fab==$row['nit'])echo "selected";?>><?php echo $row['nom_pro'] ?></option>
            <?php
			}
			?>
           
            </select>
</td>
<td style="padding-left:10px;">
</td>


</tr>
</table>
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
			

			$FunctPop=""; 
		//$FunctPop="ver_historial('$row[placa]','$row[modelo]','$row[color]','$row[km]','$row[id_propietario]');";	
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

</tr>
</table>


</td> 

<?php

for($i=0;$i<$max;$i++){
	//gettype($row[$colSet[$i]])."-".
	$funct="mod_tab_row('tabTD$ii".$i."','$tabla','$colSet[$i]','".$row[$colSet[$i]]."',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','text','','');";
	if($rolLv!=$Adminlvl){$funct="";}
	if(gettype($row[$colSet[$i]]*1)!="double"){echo "<td id=\"tabTD$ii".$i."\" onDblClick=\"$funct\">".$row[$colSet[$i]]."</td>";}
	else{echo "<td id=\"tabTD$ii".$i."\" onDblClick=\"\">".money($row[$colSet[$i]])."</td>";}
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
function ver_historial(placa,modelo,color,km,id_cli)
{
	var data='placa='+placa+'&id_cli='+id_cli;
	var URL='ajax/POP_UPS/historial_vehi.php';
	ajax_x(URL,data,function(resp){open_pop3('Mantenimientos '+modelo+'('+placa+')',' Km: '+puntob(km),resp,1)});
	
};
</script>
</div>
</div><!--fin pag 1-->

</body>
</html>