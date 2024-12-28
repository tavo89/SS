<?php 
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"inventario_ajustes")){header("location: centro.php");}

//---------------------------VAR PAG---------------------------------------------------
$busq="";
$val="";
$boton="";
$idx="";
$url_dialog="#";
$url_mod="#";
$url_new="crear_ajuste.php";

if(isset($_REQUEST['valor']))$val= $_REQUEST['valor'];
$num_fac=$val;


$opc= r('opc');
$boton= r('opc');
$busq=r('busq');
$val= r('valor');
$idx=r('id');

/////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_ajus";
$PAG_fechaF="fechaF_ajus";
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" data-inline=\"true\" data-mini=\"true\" >";
$A="";
if(isset($_REQUEST['fechaI'])){$fechaI=$_REQUEST['fechaI']; $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=$_REQUEST['fechaF'];$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" data-inline=\"true\" data-mini=\"true\" data-icon=\"delete\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}





if($opc=="QUITAR")
{
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" data-inline=\"true\" data-mini=\"true\" >";
	$fechaI="";
	$fechaF="";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	$A="";
}
//---------------


///////////////////////////////////////////////////////////////FILTRO TIPO ///////////////////////////////////////////////////////////////////////////
$filtro_tipo="TODOS";
if(isset($_SESSION['tipo_ajus']))$filtro_tipo=$_SESSION['tipo_ajus'];
if(isset($_REQUEST['tipo_ajus'])){$filtro_tipo=$_REQUEST['tipo_ajus'];$_SESSION['tipo_ajus']=$filtro_tipo;};

$E="";
if($filtro_tipo=="TODOS")$E="";
else if($filtro_tipo=="fys")$E=" AND cant!=0";
else if($filtro_tipo=="f")$E=" AND cant<0";
else if($filtro_tipo=="s")$E=" AND cant>0";
else if($filtro_tipo=="cero")$E=" AND cant=0";


//------------------------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------- filtro ajus ----------------------------------------------

$NumAjus="";
$F="";
if(!empty($_REQUEST['num_ajus']) && isset($_REQUEST['num_ajus'])){
	$NumAjus=$_REQUEST['num_ajus'];$_SESSION['num_ajus']=$NumAjus;$F=" AND a.num_ajuste='$NumAjus' ";
	}

if(isset($_SESSION['num_ajus']) && !empty($_SESSION['num_ajus'])){$NumAjus=$_SESSION['num_ajus'];$F=" AND a.num_ajuste='$NumAjus' ";}

if($NumAjus=="TODOS")$F="";


//--------------------------------------------------------------------------------------------------------




//------------------------------SQL-----------------------------------------------------
$tabla="ajustes";
$col_id="num_ajuste";
$columns="a.num_ajuste,id_usu,nom_usu,ref,cant,fecha,anulado,estado,des,a.cod_su,ar.cod_su,motivo,cant_saldo,cod_barras,unidades_fraccion,unidades_saldo,fecha_vencimiento";
$url="ajustes.php";
//----------------------------ORDEN CONSULTA-------------------------------------------
$order="num_ajuste";
$sort="";
$colArray=array(0=>'num_ajuste','ref','des','nom_usu','id_usu','fecha');
$classActive=array(0=>'','','','','','');

if(isset($_REQUEST['sortAjus']))
{
	$sort=$_REQUEST['sortAjus'];
	$order= $colArray[$sort];
	$_SESSION['sortAjus']=$sort;
	$classActive[$sort]="ui-btn-active ui-state-persist";
}

if(isset($_SESSION['sortAjus']))
{        
        $sort=$_SESSION['sortAjus'];
		$order= $colArray[$sort];
		$classActive[$_SESSION['sortAjus']]="ui-btn-active ui-state-persist";
}


//--------------------------PAGINACION--------------------------------------------
$offset=0;
$pag="";
$limit = 20; 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
//--------------------------------------------------------------------------------

//-----------------------------------CABECERA TABLA RS------------------------------------
$cols="<th width=\"90px\">#</th>
<th></th>
<th width=\"100\">
<div style=\"display:inline-block;table-layout:fixed;width:80%;\">No. Ajuste</div>
<div style=\"display:inline-block;table-layout:fixed;width:20%; \"> 
<a data-role=\"button\" data-iconpos=\"notext\" href=\"$url?sortAjus=0&pag=$pag\" data-icon=\"arrow-d\" class=\"$classActive[0]\" data-inline=\"true\"> </a>
</div></th>

<th width=\"200\">
<div style=\"display:inline-block;table-layout:fixed;width:80%;\">Ref.</div>
<div style=\"display:inline-block; table-layout:fixed;width:20%;\"> 
<a data-role=\"button\" data-iconpos=\"notext\" href=\"$url?sortAjus=1&pag=$pag\" data-icon=\"arrow-d\" class=\"$classActive[1]\"> </a>
</div>
</th>

<th> Cod. Barras </th>

<th width=\"200\">
<div style=\"display:inline-block;width:80%;\">Descripci&oacute;n</div>
<div style=\"display:inline-block;width:20%; \"> <a data-role=\"button\" data-iconpos=\"notext\" href=\"$url?sortAjus=2&pag=$pag\" data-icon=\"arrow-d\" class=\"$classActive[2]\"> </a>
</div></th>

<th>Cant.</th>
<th>Cant. Saldo</th>

<th>Uni.</th>
<th>Uni. Saldo</th>
<th width=\"150\">Fecha Venci.</th>
<th width=\"200\">Motivo</th>

<th width=\"150\">
<div style=\"display:inline-block;width:80%;\">Usuario Responsable</div>
<div style=\"display:inline-block;width:20%; \"> <a data-role=\"button\" data-iconpos=\"notext\" href=\"$url?sortAjus=3&pag=$pag\" data-icon=\"arrow-d\" class=\"$classActive[3]\"> </a>
</div>
</th>

<th width=\"150\">
<div style=\"display:inline-block;width:80%;\">Fecha</div>
<div style=\"display:inline-block;width:20%; \"> <a data-role=\"button\" data-iconpos=\"notext\" href=\"$url?sortAjus=5&pag=$pag\" data-icon=\"arrow-d\" class=\"$classActive[5]\"> </a>
</div>
</th>";



//-------------------------------------CONSULTA CON PAGINACION---------------------------------------

$sql = "SELECT  $columns FROM ajustes a INNER JOIN art_ajuste ar ON a.num_ajuste=ar.num_ajuste WHERE a.cod_su=ar.cod_su AND a.cod_su=$codSuc   $F $E ORDER BY $order DESC  LIMIT $offset, $limit"; 

$sqlTotal = "SELECT COUNT(*) as total FROM ajustes a INNER JOIN art_ajuste ar ON a.num_ajuste=ar.num_ajuste WHERE a.cod_su=ar.cod_su AND a.cod_su=$codSuc  $F $E  "; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 



//---------------------------------------BUSQUEDAS---------------------------------------------------------------------------------------------
if($boton=='Buscar' && isset($busq) && !empty($busq)){
$busq=limpiarcampo($busq);
$sql_busq="SELECT $columns FROM ajustes a INNER JOIN art_ajuste ar ON a.num_ajuste=ar.num_ajuste WHERE  a.cod_su=ar.cod_su AND a.cod_su=$codSuc $F $E AND (a.num_ajuste = '$busq' OR ref = '$busq' OR des LIKE '%$busq%' OR nom_usu LIKE '$busq%' OR cod_barras='$busq')" ;

 



$rs=$linkPDO->query($sql_busq);

	
	}
	

 if($boton=="Ver Informe")
 {
	//js_location("ReporteAjustesExcel.php"); 
 }
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/style.css">
  <link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/prism.css">
  <link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/chosen.css">
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

		<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-center" style="width:530px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
				<li class="ss-navbar-center"><a href="<?php echo $url_new ?>" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Crear Ajuste</a></li>
				<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>
			
            <li class="ss-navbar-center"><a href="#filtro_ajus" data-uk-modal><i class="uk-icon-calendar <?php echo $uikitIconSize ?>"></i>&nbsp;Informes</a></li>
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
	
    

<div id="filtro_ajus" class="uk-modal">
<div class="uk-modal-dialog ">

<a class="uk-modal-close uk-close"></a>

    <h1 class="uk-text-primary uk-text-bold">Num. Ajuste</h1>
    <form class="uk-form  uk-display-inline-block uk-form-stacked">
	<div class="uk-grid">
	<div class="uk-width-1-1"> <b>Fecha</b></div> 
		<div class="uk-width-1-2"> 
			Inicio:<input type="date" name="fechaI" id="fechaI" value="<?php echo $fechaI ?>"  style="width:135px;">
		</div>
		<div class="uk-width-1-2"> 
		Fin: <input type="date" name="fechaF" id="fechaF" value="<?php echo $fechaF ?>" style="width:135px;">
		</div>
		<div class="uk-width-1-1"> <b>Filtro Numero Ajuste</b></div> 
		<div class="uk-width-1-5">
       # Ajuste:
	   <select data-placeholder="Buscar" class="chosen-select-" style="width:150px;" tabindex="2" name="num_ajus" id="num_ajus" >
		
		<option value="TODOS">TODOS</option>
		<?php 
		$sql="SELECT * FROM ajustes WHERE cod_su=$codSuc ORDER BY num_ajuste DESC";
		$rsNa=$linkPDO->query($sql );
		while($row=$rsNa->fetch())
		{
			$numAjus=$row['num_ajuste'];
		?>
		<option value="<?php  echo $numAjus ?>" <?php if($numAjus==$NumAjus)echo "selected"; ?>><?php  echo $numAjus ?></option>
		<?php

		}
		?>
		</select>
		</div>

		<div class="uk-width-1-5">
Tipo Ajuste:

<select name="tipo_ajus" onChange="submit();">
<option value="TODOS" <?php if($filtro_tipo=="TODOS")echo "selected";?>>TODOS</option>
<option value="fys" <?php if($filtro_tipo=="fys")echo "selected";?>>Faltante y Sobrante</option>
<option value="cero" <?php if($filtro_tipo=="cero")echo "selected";?>>Ajuste Cero</option>
<option value="f" <?php if($filtro_tipo=="f")echo "selected";?>>Faltantes</option>
<option value="s" <?php if($filtro_tipo=="s")echo "selected";?>>Sobrantes</option>
</select>   
</div>
<div class="uk-width-1-1">
       <input type="button" id="informeButton" value="Ver Informe" name="opc" class="uk-button uk-button-primary">  
	   </div>

	   </div>
       </form> 
    </div>
</div>	
		
<h1 align="center">AJUSTE INVENTARIO</h1>
 
 
<?php
$tipoImp="";
if(isset($_REQUEST['tipo_imp']))$tipoImp=$_REQUEST['tipo_imp'];
if($boton=="Imprimir" && $tipoImp=="post")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['num_fac']=$num_fac;
	popup("imp_fac_ven.php","Factura No. $val", "900px","650px");
};
if($boton=="Imprimir" && $tipoImp=="carta")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['num_fac']=$num_fac;
	popup("imp_fac_com.php","Factura de Venta No.$num_fac","950","600");
};
?>
<form action="<?php echo $url ?>" method="post" name="form" class="uk-form">
<?php require("PAGINACION.php"); ?>
<table border="0" align="center" cellpadding="6px" bgcolor="#000000"   class="uk-table uk-table-hover uk-table-striped"> 
 <thead>
      <tr bgcolor="#595959" style="color:#FFF;" valign="top"> 
      
<?php echo $cols;   ?>

       </tr>
  </thead>      
          
  <tbody>    
<?php 
$bgColor="";
//$columns="num_fac,fecha,nom_cli,id_cli,tel_cli,nom_ase,tipo_factura,subtotal,iva,total";
while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $num_ajus =$row["num_ajuste"];
            $nom_usu = $row["nom_usu"]; 
			$des = $row["des"];
			$motivo = $row["motivo"]; 
			$idUsu = $row["id_usu"];
			$fecha = $row["fecha"];
			$ref = $row["ref"];
			$ref = $row["ref"];
			$cant = $row["cant"]*1;
			$cant_saldo =$row["cant_saldo"]*1;
			
			$uni=$row["unidades_fraccion"];
			$uni_saldo=$row["unidades_saldo"];
			
			$fechaVenci=$row["fecha_vencimiento"];
			if(empty($fechaVenci)){$fechaVenci="0000-00-00";}
			
			$codBar=$row['cod_barras'];
			if($idx==$num_ajus)$bgColor="#999999";
			else $bgColor="#FFFFFF";
			
			 
			
         ?>
 
<tr  bgcolor="<?php echo $bgColor ?>" tabindex="0" id="tr<?php echo $ii ?>" onClick="click_ele(this);" onBlur="resetCss(this);">
<th><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<!--
<a href="<?php //echo $url ?>?opc=Imprimir&valor=<?php //echo $num_ajus ?>&tipo_imp=carta&pag=<?php //echo $pag ?>" data-ajax="false" data-role="button" data-inline="true" data-mini="true">

<img src="Imagenes/printer.png" width="20" height="20">
</a>
-->
</td>
</tr>
</table>
</td>             
<td><?php echo $num_ajus; ?></td>
<td><?php echo $ref; ?></td>
<td><?php echo $codBar; ?></td>
<td style="font-size:11px"><?php echo $des; ?></td>
<td><?php echo $cant; ?></td> 
<td><?php echo $cant_saldo; ?></td> 
<td><?php echo $uni; ?></td> 
<td><?php echo $uni_saldo; ?></td> 
<td><?php echo $fechaVenci; ?></td> 
<td><?php echo $motivo; ?></td> 
<td style="font-size:11px"><?php echo $nom_usu; ?></td>
<!--<td><?php echo $idUsu; ?></td>-->
<td><?php echo $fecha; ?></td>
</tr> 
         
<?php 
         } 
      ?>
 

 
             <?php 
         $totalPag = ceil($total/$limit); 
         $links = array(); 
		 $sig=$pag+1;
		 if($sig>$totalPag)$sig=$pag;
		 $ant=$pag-1;
		 if($ant<1)$ant=$pag;
        /* for( $i=1; $i<=$totalPag ; $i++) 
         { 
            $links[] = "<a href=\"?pag=$i\">$i</a>";  
         }*/ 
         //echo "<a href=\"?pag=1\">Inicio</a>-<a href=\"?pag=".$ant."\" data-icon=\"row-l\" data-role=\"button\">Ant</a>-".implode(" - ", $links)."-<a href=\"?pag=".$sig."\">Sig</a>-<a href=\"?pag=$totalPag\">Fin</a>"; 
      ?>
          
  </tbody>
</table>

</form>
<?php require("PAGINACION.php"); ?>
<?php require_once("FOOTER.php"); ?>

<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/chosen.jquery.min.js?<?php echo "$LAST_VER" ?>"></script>
<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/docsupport/prism.js?<?php echo "$LAST_VER" ?>"></script>

<script type="text/javascript">


$('#informeButton').on('click', function(){
	location.assign('ReporteAjustesExcel.php?fechaI='+$('#fechaI').val()+'&fechaF='+$('#fechaF').val());
});

var config = {
      '.chosen-select'           : {no_results_text:'Oops, NO se encontro nada!'},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, NO se encontro nada!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }



$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});



//$('html, body').animate({scrollTop: $("#<?php echo $idx ?>").offset().top-120}, 2000);
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
</script>
</body>
</html>