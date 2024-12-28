<?php 
require_once("Conexxx.php");
if($rolLv!=$Adminlvl ){header("location: centro.php");}
$busq="";
$val="";
$val2="";
$val3="";
$val4="";
$boton="";
if(isset($_REQUEST['opc']))$boton= $_REQUEST['opc'];
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val= $_REQUEST['valor'];
if(isset($_REQUEST['valor2']))$val2= $_REQUEST['valor2'];
if(isset($_REQUEST['valor3']))$val3= $_REQUEST['valor3'];
if(isset($_REQUEST['valor4']))$val4= $_REQUEST['valor4'];



$cols="<th width=\"90px\">#</th>
<td></td>
<th width=\"50\">C.C</th>
<th width=\"200\">Nombre</th>
<th width=\"200\">Tel.</th>
<th width=\"150\">E-mail</th>
<th width=\"200\">Direcci&oacute;n</th>
<th width=\"150\">Tipo Usuario</th>
<th width=\"150\">Estado Usu.</th>

";


$tabla="inv_inter";
$col_id="id_pro";
$columns="b.rol_lv,a.id_usu,a.nombre,a.dir,a.tel,a.mail_cli,a.cuidad,a.cod_su,a.cliente,b.estado,b.usu";
$url="usuarios.php";
$url_dialog="dialog_invIni.php";
$url_mod="mod_traslado.php";
$url_new="reg_usu.php";
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
 

$sql = "SELECT  $columns FROM usuarios a INNER JOIN usu_login b ON a.id_usu=b.id_usu WHERE cod_su=$codSuc AND cliente=0 AND a.id_usu!='$id_Usu' ORDER BY nombre LIMIT $offset, $limit"; 


//echo "$sql";





if($boton=='mo00000000000000d'&& !empty($val) && $val2=="SIN CONFIRMAR"){// && $val3==$FechaHoy){
	
	$_SESSION['n_tras']=$val;
	$_SESSION['cod_su_tras']=$val4;
	$_SESSION['pag']=$pag;
	
	header("location: $url_mod");
	}

if($boton=="dcto"&&($rolLv>=$Adminlvl|| val_secc($id_Usu,"usuarios_add"))){
	
	$_SESSION['id_cli']=$val;
	$_SESSION['nombre_cli']=$val2;
	header("location: dcto_cli.php");
};
if($boton=="dcto2"&&($rolLv>=$Adminlvl|| val_secc($id_Usu,"usuarios_add"))){
	
	$_SESSION['id_cli']=$val;
	$_SESSION['nombre_cli']=$val2;
	header("location: mod_clientes.php?pag=$pag");
};
if($boton=="adminUsu"&&($rolLv>=$Adminlvl|| val_secc($id_Usu,"usuarios_add"))){
	
	$_SESSION['id_cli']=$val;
	$_SESSION['nombre_cli']=$val2;
	header("location: admin_permisos_usu.php?pag=$pag");
};
if($boton=="denyUsu"&&($rolLv>=$Adminlvl|| val_secc($id_Usu,"usuarios_add"))){
	
	
	$rs=$linkPDO->query("SELECT * FROM usu_login WHERE id_usu='$val' AND usu='$val2'");
	if($row=$rs->fetch())
	{
		$state=$row['estado'];
		if($state=="ACTIVO")$linkPDO->exec("UPDATE usu_login SET estado='SUSPENDIDO' WHERE id_usu='$val' AND usu='$val2'");
		else $linkPDO->exec("UPDATE usu_login SET estado='ACTIVO' WHERE id_usu='$val' AND usu='$val2'");
		
	}
	else
	{
		eco_alert("Usuario NO encontrado");
	}
	//js_location($url);
};

 
 
$sqlTotal = "SELECT COUNT(*) as total FROM usuarios a INNER JOIN usu_login b ON a.id_usu=b.id_usu WHERE cod_su=$codSuc AND cliente=0 AND a.id_usu!='$id_Usu'"; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM usuarios a INNER JOIN usu_login b ON a.id_usu=b.id_usu WHERE (a.nombre LIKE '%$busq%' OR a.id_usu LIKE '%$busq%' OR a.mail_cli LIKE '%$busq%' OR a.cuidad LIKE '$busq%')  AND a.cod_su=$codSuc AND a.cliente=0";



$rs=$linkPDO->query($sql_busq);

	
	}
	

 
?>
<!DOCTYPE html>
<html  >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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


<!-- Lado izquierdo del navbar -->

<nav class="uk-navbar">
<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 


<!-- Centro del Navbar -->

	<ul class="uk-navbar-nav uk-navbar-center" style="width:350px;">
		<?php if(($rolLv==$Adminlvl || val_secc($id_Usu,"clientes_add")) && $codSuc>0){?>
		<li class="ss-navbar-center"><a href="<?php echo $url_new ?>"  ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Registrar Usuario</a></li>
		<?php } ?>
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

if($boton=="Imprimir")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_tras']=$val;
	$_SESSION['suc_orig']=$codSuc;
	$_SESSION['pag']=$pag;
	popup("imp_tras.php","Traslado No. $val", "900px","650px");
};

?>
<h1 align="center">USUARIOS</h1>
 
 <br><br><br>

<form action="<?php echo $url ?>" method="post" name="form">
<?php require("PAGINACION.php"); ?>	
<table border="0" align="center" claslpadding="6px"   style="background-color:#FFF"class="uk-table uk-table-hover uk-table-striped"> 
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
		    
            $id = htmlentities($row["id_usu"]); 
            $cod_su = $row["cod_su"]; 
			$nom_su = htmlentities($row["nombre"],ENT_QUOTES,"$CHAR_SET");

			$tel = $row["tel"];
			$dir =$row["dir"];
			$ciudad = htmlentities($row["cuidad"]);
			$mail=$row['mail_cli'];
			$ESTADO=$row['estado'];
			$usu=$row['usu'];
			$levelUsu=$row["rol_lv"];
			
			$sqlAux="SELECT des FROM tipo_usu WHERE id_usu='$id' LIMIT 1";
			$rsAux=$linkPDO->query($sqlAux);
			$rowAux=$rsAux->fetch();
			$tipoUsu=$rowAux["des"];
			
			if($tipoUsu=="Administrador"){$tipoUsu='<div style="font-weight:bold; font-size: 16px;" class="uk-badge uk-badge-success">Administrador</div>';}
			
			 
			
         ?>
 
<tr  <?php  if($ESTADO!="ACTIVO")echo "style=\"background-color:#CCCCCC\"";else echo "";?> >
<th><?php echo $ii ?></th>
<td>
<div class="uk-button-dropdown"  data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
<a class="uk-button uk-button-primary" style="width:100px;">Opciones <i class="uk-icon-caret-down"></i></a>
<div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom" style="top: 30px; left: 0px;">
<ul class="uk-nav uk-nav-dropdown">


<li><a href="<?php echo $url ?>?opc=dcto2&valor=<?php echo $id ?>&valor2=<?php echo $nom_su ?>&pag=<?php echo $pag ?>" class=""  >
<i class="uk-icon-pencil    uk-icon-small"></i> Modificar Usuario</a></li>

<li><a href="<?php echo $url ?>?opc=adminUsu&valor=<?php echo $id ?>&valor2=<?php echo $nom_su ?>&pag=<?php echo $pag ?>" class=""  >
<i class="uk-icon-eye    uk-icon-small"></i> Asignar Permisos</a></li>

<li><a href="#" onClick="deny('<?php echo $url."?opc=denyUsu&valor=$id&valor2=$usu&pag=$pag"?>','<?php echo $nom_su ?>','<?php echo $id ?>')"  >
<i class="uk-icon-<?php if($ESTADO=="ACTIVO")echo "lock";else echo "unlock"; ?>    uk-icon-small"></i> <?php if($ESTADO=="ACTIVO")echo "Suspender Usuario";else echo "ACTIVAR Usuario"; ?></a></li>

 


</td>             
<td><?php echo $id; ?></td>
<td><?php echo $nom_su; ?></td>
<td><?php echo $tel; ?></td> 
<td><?php echo $mail; ?></td>
<td><?php echo $dir; ?></td>
<td><?php echo $tipoUsu; ?></td>
<td><?php echo $ESTADO; ?></td>
</tr> 
         
<?php 
         } 
      ?>
 

       </tbody>   
   
</table>

</form>

<?php require("PAGINACION.php"); ?>	
<?php require_once("FOOTER.php"); ?>	
<script language="javascript1.5" type="text/javascript">
function deny(url,nom,id)
{
	if(confirm('Desea Denegar el Acceso a :'+nom+', C.C:'+id+' ??'))
	{
		location.assign(url);
	}
	else
	{
		simplePopUp('Operacion Cancelada');
	}
};
</script>


</body>
</html>