<?php 
include("Conexxx.php");
$UserFilter="AND fecha>='$fechaCreaUsu'";
if($rolLv==$Adminlvl || $separar_registros_por_usuarios==0)$UserFilter="";
//-----------------PAGE VARS--------------------------------------------------------------------------------------------------------------------------
$busq="";
$val="";
$boton="";
$num_fac=0;
$tabla="exp_anticipo";
$col_id="num_exp";
$url="expedientes.php";
$url_dialog="dialog_invIni.php";
$url_mod="#";
if(($rolLv==$Adminlvl || val_secc($id_Usu,"crear_anticipo")) && $codSuc>0)$url_new="agregar_exp.php";
else $url_new="#";

if(isset($_REQUEST['val2']))$val2= $_REQUEST['val2'];
if(isset($_REQUEST['val3']))$val3= $_REQUEST['val3'];
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['opc']))
{
$val= r('valor');
$num_fac=$val;

}

$boton= r('opc');
//-----------------PAGINACION--------------------------------------------------------------------------------------------------------------------------
$pag="";
$limit = 20; 
$order="num_exp";
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) { $pag = 1;} 
$offset = ($pag-1) * $limit; 
$ii=$offset;

//--------------------------------------------------------------ORDER QUERY------------------------------------------------------------------------------
$columns="*";
$ORDEN="DESC";
$sort="";
$colArray=array(0=>'num_exp','num_fac','nom_cli','id_cli','vendedor','estado','fecha','tot','tel_cli','des');
$classActive=array(0=>'','','','','','','','','','');

if(isset($_REQUEST['sort']))
{
	$sort=$_REQUEST['sort'];
	$order= $colArray[$sort];
	$_SESSION['sort_exp']=$sort;
	$_SESSION['ORDEN']="";
	$classActive[$sort]="ui-btn-active ui-state-persist";
}

if(isset($_SESSION['sort_exp']))
{        
        $sort=$_SESSION['sort_exp'];
		$order= $colArray[$sort];
		$classActive[$_SESSION['sort_exp']]="ui-btn-active ui-state-persist";
}

if(isset($_SESSION['ORDEN']))$ORDEN=$_SESSION['ORDEN'];

$col1=th("No. Anticipo",0,100,$url,$pag,$classActive);
$col2=th("No. Fac",1,100,$url,$pag,$classActive);
$col3=th("Cliente",2,100,$url,$pag,$classActive);
$col4=th("C.C.",3,100,$url,$pag,$classActive);
$col5=th("Cajero",4,100,$url,$pag,$classActive);
$col6=th("Abono",7,100,$url,$pag,$classActive);
$col7=th("Fecha",6,150,$url,$pag,$classActive);
$col8=th("Estado",5,100,$url,$pag,$classActive);
$col9=th("Tel.",8,100,$url,$pag,$classActive);
$col10=th("Descripci&oacute;n",9,100,$url,$pag,$classActive);

$col11=th("Tot. a Pagar",9,100,$url,$pag,$classActive);
$col12=th("Saldo",9,100,$url,$pag,$classActive);


$cols="<th width=\"90px\">#</th>
<td width=\"30px\"></td> $col1 $col2 $col3 $col4 $col9 $col10 $col5 $col6 $col11 $col12  $col7 $col8 ";


//---------------------------------------------------------CONSULTA--------------------------------------------------------------------------------------
$sql = "SELECT  $columns FROM $tabla  WHERE  cod_su=$codSuc $UserFilter  ORDER BY $order DESC  LIMIT $offset, $limit"; 
$sqlTotal = "SELECT COUNT(*) as total FROM $tabla WHERE cod_su='$codSuc' $UserFilter"; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 






//----------------------------------------------MENUS------------------------------------------------------------------------
if($boton=='cuotas'&& !empty($val)){
	
	$_SESSION['num_exp']=$val;
	$_SESSION['pag']=$pag;
	
	header("location: cuotas_anticipos.php");
	}
	
if($boton=='mod_ord'&& !empty($val)){
	
	//$_SESSION['n_fac_taller']=$val;
	$_SESSION['pag']=$pag;
	
	header("location: mod_fac_taller.php?nf=$val");
	}
	
if($boton=='open'&& !empty($val)){
	
	//$_SESSION['n_fac_taller']=$val;
	$sql="SELECT *,DATE(fecha) as fe FROM exp_anticipo WHERE num_exp=$val AND cod_su=$codSuc";
	$rsx=$linkPDO->query($sql);
	if($row=$rsx->fetch()){
		$fe=$row['fe'];
		$estado=$row['estado'];
		if($estado!='FAC. ANULADA'){
		if($estado!='COBRADO'){
			if($fe==$FechaHoy){
				$_SESSION['pag']=$pag;
				eco_alert("Anticipo No. $val ABIERTO");
				$rsC=$linkPDO->query("SELECT SUM(valor) as t FROM comp_anti WHERE num_exp=$val");
				$rowC=$rsC->fetch();
				$totAbon=$rowC['t'];
				$Q1="UPDATE exp_anticipo SET estado='ABIERTO',tot=$totAbon WHERE cod_su=$codSuc AND num_exp=$val";
				$Q2="UPDATE comp_anti SET estado='ABIERTO', fecha_anula='0000-00-00 00:00:00' WHERE cod_su=$codSuc AND num_exp=$val";
				t2($Q1,$Q2);
				js_location("expedientes.php?pag=$pag");
			}
			else echo eco_alert("NO se permiten Cambios");;
 
		}
		else eco_alert("Éste Anticipo YA fue COBRADO, no se permiten Cambios");
		}
		else eco_alert("La Factura de este Anticipo fue ANULADA, no se permiten Cambios");
	}
	else eco_alert("No se encontro este Anticipo...");
	}
if($boton=='close'&& !empty($val)){
	
	//$_SESSION['n_fac_taller']=$val;
	$sql="SELECT *,DATE(fecha) as fe FROM exp_anticipo WHERE num_exp=$val AND cod_su=$codSuc";
	$rsx=$linkPDO->query($sql);
	if($row=$rsx->fetch()){
		$fe=$row['fe'];
		$estado=$row['estado'];
		
		if($estado!='COBRADO'){
			if($fe=$FechaHoy)
				$_SESSION['pag']=$pag;
				$rsC=$linkPDO->query("SELECT SUM(valor) as t FROM comp_anti WHERE num_exp=$val");
				$rowC=$rsC->fetch();
				$totAbon=$rowC['t'];
				$Q1="UPDATE exp_anticipo SET estado='ANULADO',tot=0 WHERE cod_su=$codSuc AND num_exp=$val";
				$Q2="UPDATE comp_anti SET estado='ANULADO', fecha_anula=CURRENT_DATE() WHERE cod_su=$codSuc AND num_exp=$val";
				t2($Q1,$Q2);
				eco_alert("Anticipo No. $val ANULADO");
				js_location("expedientes.php?pag=$pag");
 
		}
		else eco_alert("Ésta Anticipo YA fue COBRADO, no se permiten Cambios");
	}
	else eco_alert("No se encontro este Anticipo...");
	}
 
if($boton=='ord_fac'&& !empty($val)){

	$_SESSION['n_fac_taller']=$val;
	$_SESSION['pag']=$pag;
	$_SESSION['tipoFac']="ORDEN DE SERVICIO";
	$_SESSION['backRemi']="ordenes_servicio.php";
	header("location: orden_a_fac.php?nf=$val");
	}

//----------------------------------------------------------------------------------------------------------------------------------------------- 
 

	

	

 
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

		<ul class="uk-navbar-nav uk-navbar-center" style="width:530px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
			<?php 
			if($MODULES["ANTICIPOS"]==1){
			if(($rolLv==$Adminlvl || val_secc($id_Usu,"crear_anticipo")) ){
			?>
			<li class="ss-navbar-center"><a style="cursor:pointer;" href="#"  onClick="print_pop('agregar_exp.php','EGRESO',800,600)"><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>" ></i>&nbsp;Agregar Anticipo</a></li>
			<?php
			}
			}
			?>

			<li ><a href="abonos_anticipos.php" ><i class="uk-icon-list <?php echo $uikitIconSize ?> "></i>&nbsp;Comprobantes</a></li>
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


<?php

$tipoImp="";
if(isset($_REQUEST['tipo_imp']))$tipoImp=$_REQUEST['tipo_imp'];
//eco_alert("Tipo Imp: $tipoImp, Button: $boton");
if($boton=="Imprimir" && $tipoImp=="post")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['prefijo']=$codContadoSuc;
	popup("imp_fac_ven.php","Factura No. $val", "900px","650px");
};
if($boton=="Imprimir" && $tipoImp=="carta")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['prefijo']=$codContadoSuc;
	popup("imp_fac_ven_cre.php","Factura No. $val", "900px","650px");
};



if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM exp_anticipo WHERE cod_su=$codSuc $UserFilter AND (nom_cli LIKE '%$busq%' OR id_cli LIKE '$busq' OR num_exp LIKE '$busq' OR num_fac LIKE '$busq'   )  ORDER BY num_exp ASC";
//echo "$sql_busq";


$rs=$linkPDO->query($sql_busq);

	
	}
?>
<h1 align="center">Anticipos</h1>
<br><br><br>


<form autocomplete="off" action="<?php echo $url ?>" method="post" name="form">

<?php require("PAGINACION.php"); ?>
<?php //echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>
<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped tabla-datos" style=" "> 
 <thead>
      <tr bgcolor="#595959" style="color:#FFF" valign="top"> 
      
<?php echo $cols;   ?>

       </tr>
     </thead>
<tbody>   
          
      
<?php 
//echo "$sql_busq";
while ($row = $rs->fetch()) 
{ 
$ii++;
			$ID=$row["id_anti"];
		    
            $cod_exp = $row["num_exp"]; 
            $nom_cli = $row["nom_cli"]; 
			$des = $row["des"]; 
		    $estado = $row["estado"]; 
			$tel = $row["tel_cli"];
			$vendedor = $row["cajero"];
			$fecha = $row["fecha"];
			
			$id_cli = $row["id_cli"];
			$numFac=$row['num_fac'];
			
			
			$s1="SELECT SUM(valor) as tot FROM comp_anti WHERE num_exp=$cod_exp AND cod_su='$codSuc'";
			$r1=$linkPDO->query($s1);
			$row1=$r1->fetch();
			
			$totPa=$row['tot_pa']*1;
			$tot = $row1["tot"]*1;
			$saldo=$totPa-$tot;
			if($saldo<0)$saldo=0;
			 $functValor="mod_tab_row('tabTD03$ii','exp_anticipo','tot_pa','$totPa',' id_anti=\'$ID\' AND cod_su=\'$codSuc\'','$ii','text','','');";
			
         ?>
 
<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td>

<table cellpadding="0" cellspacing="0">
<tr>
<td>
<div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
<button class="uk-button uk-button-primary" style="width:100px;">Opciones <i class="uk-icon-caret-down"></i></button>
<div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom" style="top: 30px; left: 0px;">
<ul class="uk-nav uk-nav-dropdown">
<li><a href="<?php echo $url ?>?opc=cuotas&valor=<?php echo $cod_exp ?>&pag=<?php echo $pag ?>" class="" ><i class="uk-icon-dollar     uk-icon-small"></i> Abono a la cuenta</a></li>

<li><a href="<?php echo $url ?>?opc=close&valor=<?php echo $cod_exp ?>&val2=<?php echo $fecha ?>&val3=<?php echo $estado  ?>&pag=<?php echo $pag ?>" class="" ><i class="uk-icon-remove     uk-icon-small"></i> Anular Anticipo y abonos</a></li>

</ul>
</div>
</div>


</td>
<!--<td>
<a href="<?php //echo $url_dialog ?>?des=<?php  ?>&id=<?php //echo $cod_exp ?>&pag=<?php //echo $pag ?>" data-rel="dialog">
<button data-inline="true" data-mini="true"><img src="Imagenes/b_drop.png"></button>
</a>
</td>-->
</tr>
</table>


</td>             
<td><?php echo $cod_exp; ?></td>
<td><a href="<?php echo "$url?opc=Imprimir&valor=".$row['num_fac'] ?>&tipo_imp=post&pag=<?php echo $pag ?>"><?php echo $row['num_fac'] ?></a></td>
<td><?php echo $nom_cli; ?></td>
<td><?php echo $id_cli; ?></td>
<td><?php echo $tel ?></td>
<td><?php echo $des ?></td>
<td><?php echo $vendedor ?></td>
<td><?php echo money($tot) ?></td>

<td id="tabTD03<?php echo $ii ?>" onDblClick="<?php echo $functValor; ?>"><?php echo money($totPa) ?></td>
<td><?php echo money($saldo) ?></td>

<td><?php echo $fecha ?></td>  
<td><?php echo $estado ?></td>

</tr> 
         
<?php 
         } 
      ?>
 


          
   
</table>
<script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>
</form>
<?php require("PAGINACION.php"); ?>	
<?php require_once("FOOTER.php"); ?>

</body>
</html>