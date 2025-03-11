<?php 
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"clientes")){header("location: centro.php");}
$busq="";
$val="";
$val2="";
$val3="";
$val4="";
$boton="";
$valFilter=r("filter");


if(!empty($valFilter))
{
	$_SESSION['valFilterCli']=$valFilter;
	
}
if(isset($_SESSION['valFilterCli']) && !empty($_SESSION['valFilterCli']))
{
	$valFilter=$_SESSION['valFilterCli'];
}

if($valFilter=="null")
{
unset($_SESSION['valFilterCli']);
$valFilter="";	
}


$filtroA="";
if(!empty($valFilter)&& $valFilter=="moro"){$filtroA=" AND auth_credito='NO'";}

$filtroB="";
if(!empty($valFilter)&& $valFilter=="comi"){$filtroB=" AND cod_comision!=''";}

if(isset($_REQUEST['opc']))$boton= $_REQUEST['opc'];

if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val= $_REQUEST['valor'];
if(isset($_REQUEST['valor2']))$val2= $_REQUEST['valor2'];
if(isset($_REQUEST['valor3']))$val3= $_REQUEST['valor3'];
if(isset($_REQUEST['valor4']))$val4= $_REQUEST['valor4'];


if($boton=="quitarFiltros"){$filtroA="";$filtroB="";unset($_SESSION['valFilterCli']);}



$cols="<th width=\"90px\">#</th>
<td></td>
<th width=\"50\">C.C</th>
<th width=\"200\">Nombre</th>
<th width=\"200\">Tel.</th>
<th width=\"150\">E-mail</th>
<th width=\"200\">Direcci&oacute;n</th>
<th width=\"150\">Ciudad</th>
<th width=\"150\">Tope Credito</th>
<th width=\"150\">Cartera Autorizada </th>

";

if($MODULES["COMI_VENTAS"]==1){$cols.="<th width=\"150\">Cod. Comisi&oacute;n </th>";}


$tabla="inv_inter";
$col_id="id_pro";
$columns="*";
$url="clientes.php";
$url_dialog="dialog_invIni.php";
$url_mod="mod_traslado.php";
$url_new="registro_cli.php";
$pag="";
$limit = 20; 
$order="fecha_envio";
 

if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
 

$sql = "SELECT  $columns FROM usuarios WHERE cod_su=$codSuc AND cliente=1 $filtroA $filtroB ORDER BY nombre LIMIT $offset, $limit"; 








if($boton=='mo00000000000000d'&& !empty($val) && $val2=="SIN CONFIRMAR"){// && $val3==$FechaHoy){
	
	$_SESSION['n_tras']=$val;
	$_SESSION['cod_su_tras']=$val4;
	$_SESSION['pag']=$pag;
	
	header("location: $url_mod");
	}

if($boton=="dcto"&&$rolLv>=5){
	
	$_SESSION['id_cli']=$val;
	$_SESSION['nombre_cli']=$val2;
	header("location: dcto_cli.php");
};
if($boton=="dcto2"){
	
	$_SESSION['id_cli']=$val;
	$_SESSION['nombre_cli']=$val2;
	$_SESSION['snombr_cli']=$val3;
	$_SESSION['apelli_cli']=$val4;
	header("location: mod_clientes.php?pag=$pag");
};

 
 
$sqlTotal = "SELECT COUNT(*) as total FROM usuarios WHERE cod_su='$codSuc'"; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM usuarios WHERE (nombre LIKE '%$busq%' OR id_usu LIKE '$busq%' OR mail_cli LIKE '%$busq%' OR cuidad LIKE '$busq%' OR alias LIKE '$busq%')  AND cod_su=$codSuc AND cliente=1";



$rs=$linkPDO->query($sql_busq);

	
	}
	

 
?>
<!DOCTYPE html>
<html  >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<?php require_once("HEADER.php"); ?>
<script src="JS/jquery-2.1.1.js"></script>
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">


<!-- Lado Izquierdo del Navbar -->

<nav class="uk-navbar">
<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/favSmart.png" class="icono_ss"> &nbsp;SmartSelling</a> 


<!-- Centro del Navbar -->

	<ul class="uk-navbar-nav uk-navbar-center" style="width:820px;">   
    <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
	
	
		<?php if(($rolLv==$Adminlvl || val_secc($id_Usu,"clientes_add")) && $codSuc>0){?>
		<li class="ss-navbar-center"><a href="<?php echo $url_new ?>"  ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Registrar Cliente</a></li>
		<?php } ?>
        
        
<!--<li><a href="<?php echo "$url?filter=comi" ?>" ><i class="uk-icon-refresh uk-icon-list <?php echo $uikitIconSize ?>"></i>&nbsp;Lista Comisi&oacute;n Ventas</a></li>
        
		<li><a href="<?php echo "$url?filter=moro" ?>" ><i class="uk-icon-refresh uk-icon-list <?php echo $uikitIconSize ?>"></i>&nbsp;Lista Morosos</a></li>-->
        

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

if($boton=="Imprimir")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_tras']=$val;
	$_SESSION['suc_orig']=$codSuc;
	$_SESSION['pag']=$pag;
	popup("imp_tras.php","Traslado No. $val", "900px","650px");
};


?>
<h1 align="center">CLIENTES</h1>


<form action="<?php echo $url ?>" method="post" name="form">
<br><br><br>
<?php
if(!empty($filtroA) || !empty($filtroB)){
?>
<div class="uk-alert uk-alert-warning" data-uk-alert>
    <a href="" class="uk-alert-close uk-close"></a>
    <p>HAY FILTROS APLICADOS &nbsp;&nbsp;&nbsp;
    <a style="color:#000;" href="<?php echo "$url?opc=quitarFiltros" ?>"><i class="uk-icon-rotate-left <?php echo $uikitIconSize ?>"></i>&nbsp;QUITAR Filtros</a>
    
   </p>
</div>
<?php
}

?>
<?php require("PAGINACION.php"); ?>	
<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped"> 
 <thead>
      <tr bgcolor="#595959" style="color:#FFF" valign="top"> 
      
<?php echo $cols;   ?>

       </tr>
 </thead>       
 <tbody> 
      
<?php 
$bgAuth="#FFFFFF";
while ($row = $rs->fetch()) 
{ 
$ii++;
		    $ID = $row["id"];
            $id = $row["id_usu"]; 
            $cod_su = $row["cod_su"]; 
			$nom_su = $row["nombre"];
			if($MODULES["FACTURACION_ELECTRONICA"]==1){
			$snombr = $row["snombr"];
			$apelli = $row["apelli"];
			}
			else{
			$snombr = "";
			$apelli = "";	
			}
			
			$nom_suShow="$nom_su $snombr $apelli";

			$tel = $row["tel"];
			$dir =$row["dir"];
			$ciudad = $row["cuidad"];
			$mail=$row['mail_cli'];
			$auth="SI";
			
			$codComi=$row["cod_comision"];
			$aliasCli=$row["alias"];
			
			if($row["auth_credito"]==0){$auth="NO";$bgAuth="#666666";}
			else $bgAuth="#FFFFFF";
			
			$ALIAS="";
			if(!empty($aliasCli))$ALIAS=" (<b>$aliasCli</b>)"
			 
			
         ?>
 
<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td >
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<a href="<?php echo $url ?>?opc=dcto2&valor=<?php echo $id ?>&valor2=<?php echo urlencode($nom_su) ?>&valor3=<?php echo urlencode($snombr) ?>&valor4=<?php echo urlencode($apelli) ?>&pag=<?php echo $pag ?>" class="uk-icon-pencil uk-icon-button uk-icon-hover uk-icon-small">

</a>
</td>
<?php if($rolLv==$Adminlvl || val_secc($id_Usu,"clientes_eli")){?>
<td>
<a href="#" class="uk-icon-remove uk-icon-button uk-icon-hover uk-icon-small" onMouseUp="val_del_cli('<?php echo $id; ?>','<?php echo $nom_su; ?>','<?php echo $ID; ?>');"></a>
</td>
<?php  }?>
</tr>
</table>


</td>             
<td width="200px"><?php echo $id; ?></td>
<td><?php echo $nom_suShow." $ALIAS"; ?></td>
<td><?php echo $tel; ?></td> 
<td><?php echo $mail; ?></td>
<td><?php echo $dir; ?></td>
<td><?php echo $ciudad; ?></td>
<td><?php echo money($row["tope_credito"]); ?></td>
<td bgcolor="<?php echo "$bgAuth" ?>"><?php echo $auth; ?></td>

<?php if($MODULES["COMI_VENTAS"]==1){?>
<td><?php echo $codComi; ?></td>
 <?php } ?>
</tr> 
         
<?php } ?>
 

 </tbody>
</table>

</form>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >

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
			
		}
		
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
			
		}
		
		});
		}
};
</script>
<?php require("PAGINACION.php"); ?>	
<?php require_once("FOOTER2.php"); ?>	
</body>
</html>