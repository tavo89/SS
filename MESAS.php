<?php 
include("Conexxx.php");
validaMesas();
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







$tabla="mesas";
$codSuCol="cod_su";
$col_id="id_mesa";
$columns="id_mesa,num_mesa,valor,estado,num_fac_ven,prefijo,hash,p_top,p_left";
$cols=explode(",",$columns);
$maxCols=count($cols);
$colSet[]="";
for($i=0;$i<$maxCols;$i++){$colSet[$i]=$cols[$i];}
$url="MESAS.php";
$ORDER_BY="ORDER BY num_mesa";


///////////////////////////////////////////////////////////////////// PAGINACION ///////////////////////////////////////////////////////////////////
$pag="";
$limit = 200; 
$order="fecha";
 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{$pag = 1;} 
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

$sql = "SELECT  $columns FROM $tabla WHERE  $codSuCol=$codSuc $ORDER_BY DESC    LIMIT $offset, $limit "; 
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
		
<li class="ss-navbar-center"><a href="#add_any"  data-uk-modal ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Registrar Nuevo</a></li>
 
 

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
//        0       1       2         3          4              5           6     7        8      9         10        11               12          13    14


$sqlMesa = "SELECT max(cast(num_mesa as UNSIGNED))+1 as nMesa FROM `mesas` WHERE num_mesa REGEXP '^-?[0-9]+$'";
$rsMesa = $linkPDO->query($sqlMesa);
if($rowMesa = $rsMesa->fetch()) {
	$ultimaMesa = $rowMesa['nMesa'];
}
else {
	$ultimaMesa = 1;
}

$opcionesNumMesas = "<option value=\"$ultimaMesa\">Mesa # $ultimaMesa</option>"
                   ."<option value=\"DOMICILIOS -VENTAS\">DOMICILIOS -VENTAS</option>";

$selecOPTColumns=array("num_mesa"=>$opcionesNumMesas);

$columnas="cod_su,num_mesa";
$tipoCol=array(0=>"hidden","select");
$default=array(0=>"$codSuc",$opcionesNumMesas);
crear_any_form("mesas",$columnas,$tipoCol,$default, " function(){ return true;}", "successAny","Mesas $SEDES[$codSuc]","",$selecOPTColumns);


?>
<!--<h1 align="center">MESAS</h1>-->


<form autocomplete="off" action="<?php echo $url ?>" method="get" name="form">
 
<?php //echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>

<div class="uk-panel-box-primary" style="width:100%; height:800px; color:#000;" id="mainBox">
 

   
<?php 
$classHidden='';
while ($row = $rs->fetch()) 
{ 
	$ii++;
	$FunctPop=" location.assign('fac_venta.php?id_mesa=$row[id_mesa]');";
	$FunctCambiarMesa="tranferirMesa('$row[id_mesa]','$row[num_mesa]')";
	$iconOpt="plus-square";
	$fontColor="";

	$classHidden=$row["estado"]!="Ocupada"?'uk-hidden':'';

if(!empty($row["hash"]) || ($row['num_fac_ven']!=0 && !empty($row['num_fac_ven']))){
	$iconOpt="pencil";
	$fontColor="color:red;font-weight: bold;font-size:24px;";
	$FunctPop=" location.assign('mod_fac_ven.php?num_fac_venta=$row[num_fac_ven]&pre=$row[prefijo]&id_mesa=$row[id_mesa]&hash=$row[hash]');";
	
	}
	

	$TITULO_OBJ='<i class="uk-icon-beer"></i> MESA';
	if(!is_numeric ($row["num_mesa"])){$TITULO_OBJ='';}
	if(!empty($row["num_mesa"])){
?>
<div id="drag<?php echo $ii ?>" class="uk-panel uk-text-large" style=" padding:5px;border: double; border-width:5px; width:140px; cursor:pointer;<?php echo "left:$row[p_left] ; top:$row[p_top]" ?>" onMouseUp="savePos($(this),'<?php echo $row["id_mesa"] ?>')">

<?php echo "$TITULO_OBJ $row[num_mesa]" ?> 
<?php if(is_numeric ($row["num_mesa"]) || buscaString('VENTAS',strtoupper($row["num_mesa"])) ){?>
<br>
<div align="" class="uk-text-large uk-badge uk-badge<?php if($row["estado"]=="Ocupada"){echo "-warning";}else{echo "-success";}?>"><?php echo $row["estado"];?></div> 
<div style=" font-size:28px;">
<?php echo money2($row["valor"]) ?>
</div>
<div class="uk-button-dropdown " data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
<a   class="uk-button uk-button-primary uk-button-mini " style="width:140px; font-size:20px;">Opciones <i class="uk-icon-caret-down"></i></a>
<div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom" style="top: 30px; left: 0px;">
<ul  class="uk-nav uk-nav-dropdown">
<li>
<a href="#" class="" onMouseUp="<?php echo $FunctPop ?>">
<i class="uk-text-large uk-icon-<?php echo $iconOpt ?>">PEDIDO</i>
</a>
</li>
<li class="<?php echo $classHidden; ?>">
<a href="#" class="" onMouseUp="<?php echo $FunctCambiarMesa; ?>">
<i class="uk-text-large uk-icon-refresh">Cambiar de Mesa</i>
</a>
</li>
</ul>
</div>
</div>

<?php }?>
</div>
<?php
}


?>
 
         
<?php  } ?>
 
 

</div>
</form>

</div>

<?php include_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<!--<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php //echo "$LAST_VER" ?>"></script>-->

<script language="javascript1.5" type="text/javascript">
function tranferirMesa(idMesaOrigen,numeroMesa) {

    var Data="id_mesa="+idMesaOrigen;
	ajax_x("ajax/RESTAURANTES/formularioTranferirMesa.php",Data,function(resp){
		open_pop('Transferencia de Mesas','Mesa actual: <i class="" style="color:black;">['+numeroMesa+']</i>',resp);
		
		});

}

function ejecutaTransferencia(idMesaOrigen,idMesaAtransferir) {

var Data='idMesaOrigen='+idMesaOrigen+'&idMesaAtransferir='+idMesaAtransferir;
ajax_x("ajax/RESTAURANTES/transferirMesasHandler.php",Data,function(resp){
	if(resp==1) {
		simplePopUp('Transferencia Completa!');
		waitAndReload();
	}
	
	});

}

function savePos($box,id_mesa){
 
	var left=$box.css('left');
	var top=$box.css('top');
	var Data="left="+left+"&top="+top+"&id_mesa="+id_mesa;
	ajax_x("ajax/savePosMesa.php",Data,function(resp){
		
		
		});
	
};

 
<?php for($i=1;$i<=$ii;$i++){?>

$( function() {
var Box = $("#drag<?php echo $i ?>");

//Box.text( "left: " + Box.css('left') + ", top: " + Box.css('top')  );

$( "#drag<?php echo $i ?>" ).draggable({containment: "parent",
  start: function() {
	
  },
  drag: function() {
 
  },
  stop: function() {
var Box = $("#drag<?php echo $i ?>");
//Box.text( "left: " + Box.css('left') + ", top: " + Box.css('top')  );
  }
});
  } );
<?php }?>
  </script>

<script type="text/javascript" language="javascript1.5">

</script>
</div>
</div><!--fin pag 1-->

</body>
</html>