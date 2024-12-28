<?php
require("Conexxx.php");
$url="movimientosDB.php";
$opc="";
$busq="";
$val="";
$boton="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val=$_REQUEST['valor'];
if(isset($_REQUEST['opc']))$boton= $_REQUEST['opc'];
/////////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_logs";
$PAG_fechaF="fechaF_logs";
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" data-inline=\"true\" data-mini=\"true\" >";
$A="";
if(isset($_REQUEST['fechaI'])){$fechaI=limpiarcampo($_REQUEST['fechaI']); $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=limpiarcampo($_REQUEST['fechaF']);$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" data-inline=\"true\" data-mini=\"true\" data-icon=\"delete\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha_op)>='$fechaI' AND DATE(fecha_op)<='$fechaF') ";
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
//-----------------------------------------------------------------------------------------------------------------------------------------------------


///////////////////////////////////////////////////////////////////////// FILTRO USU //////////////////////////////////////////////////////////////
$ID_USU="";
$C="";

if(isset($_REQUEST['id_usuario'])){$ID_USU=$_REQUEST['id_usuario'];$_SESSION['id_usuario_logs']=$ID_USU;}
if(isset($_SESSION['id_usuario_logs'])){$ID_USU=$_SESSION['id_usuario_logs'];}

if($ID_USU=="all")$C="";
else if($ID_USU!="all" && !empty($ID_USU))$C=" AND id_usu='$ID_USU' ";

/////////////////////////////////////////////////////////////////////// FIN FILTRO USU //////////////////////////////////////////////////////////////








///////////////////////////////////////////////////////////////////////// FILTRO MODULO //////////////////////////////////////////////////////////////
$MOD="";
$E="";
$NomVarRequestE="modulos";
$NomVarSessionE="xer";
$colFiltro="seccion_afectada";

if(isset($_REQUEST[$NomVarRequestE])){$MOD=$_REQUEST[$NomVarRequestE];$_SESSION[$NomVarSessionE]=$MOD;}
if(isset($_SESSION[$NomVarSessionE])){$MOD=$_SESSION[$NomVarSessionE];}

if($MOD=="all")$E="";
else if($MOD!="all" && !empty($MOD))$E=" AND $colFiltro='$MOD' ";

/////////////////////////////////////////////////////////////////////// FIN FILTRO MODULO //////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////// FILTRO OPERACION////////////////////////////////////////////////////////////
$OPE="";
$F="";
$NomVarRequestF="operacion";
$NomVarSessionF="operacion";
$colFiltro="tipo_operacion";

if(isset($_REQUEST[$NomVarRequestF])){$OPE=$_REQUEST[$NomVarRequestF];$_SESSION[$NomVarSessionF]=$OPE;}
if(isset($_SESSION[$NomVarSessionF])){$OPE=$_SESSION[$NomVarSessionF];}

if($OPE=="all")$F="";
else if($OPE!="all" && !empty($OPE))$F=" AND $colFiltro='$OPE' ";

/////////////////////////////////////////////////////////////////////// FIN FILTRO OPERACION //////////////////////////////////////////////////////////





/////////////////////////////////////////////////////////////////////// PAGINACION ////////////////////////////////////////////////////
$pag="";
$limit = 10; 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) { $pag = 1; } 
$offset = ($pag-1) * $limit; 
$ii=$offset;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//--WHERE DATE(fecha_op)>='2014-08-01' AND DATE(fecha_op)<='2014-08-08'


	$stm="select * from auditoria WHERE id_sede=$codSuc $C $E $F $A LIMIT $offset, $limit";
	//echo $stm;
	$rs = $linkPDO->query($stm);

////////////////////////////////////////////////////////////////// PAGINACION PARTE 2//////////////////////////////////////////////////// 
$sqlTotal = "SELECT COUNT(*) as total FROM auditoria WHERE id_sede=$codSuc $C $E $F $A"; 

$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT * FROM auditoria 
WHERE id_sede=$codSuc $C $E $F 
AND (TRIM(reg_key)='$busq' OR tipo_operacion='$busq' OR TRIM(seccion_afectada)='$busq' OR ip_host='$busq' OR id_usu='$busq' OR usu='$busq') $A";

$rs=$linkPDO->query($sql_busq);

	
	}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once("HEADER.php"); ?>	
<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
<link href="css/tables-style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/jquery-ui.css">
<!--<link rel="stylesheet" href="css/mainA.css">
-->



</head>
<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">

<br><br><br>
<div id="sb-search" class="sb-search">
						<form>
							<input class="sb-search-input" placeholder="Ingrese su b&uacute;squeda..." type="text" value="" name="busq" id="search">
							<input class="sb-search-submit" type="submit" value="Buscar" name="opc">
							<span class="sb-icon-search"></span>
						</form>
					</div>
 <br><br><br><br>

     
<form name="form" method="post" class="uk-form">
<?php
//echo $stm;
?>
<div class="grid-40">
<table width=""  cellpadding="0" cellspacing="1" align="right"  frame="BOX">
<thead>
<TR bgcolor="#CCCCCC">

<TH colspan="5" align="center">FECHA </TH>
</TR>
</thead>
<tbody>
<tr>
<td>Inicia:
</td>
<td>
<input type="date" name="fechaI" id="date" value="<?php echo $fechaI ?>"  style="width:135px;">
</td>
<td>Termina:
</td>
<td>
<input type="date" name="fechaF" id="date" value="<?php echo $fechaF ?>" style="width:135px;">
</td>
<td><?php echo $botonFiltro ?></td>
</tr>
</tbody>
</table>
</div>
<div class="grid-30 push-20">
<table width=""  cellpadding="0" cellspacing="1" align="right"  frame="BOX">
<thead>
<TR bgcolor="#CCCCCC">
<th>Operaci&oacute;n<?PHP //echo "F:".$_SESSION[$NomVarSessionF] ?></th>
<th>M&oacute;dulos<?PHP //echo "F:".$_SESSION[$NomVarSessionE] ?></th>
<th>Usuarios<?php //echo "USU".$_SESSION['id_usuario_logs'] ?></th>
</TR>
</thead>
<tbody>
<tr>
<td>
<select name="operacion" onChange="submit()">
<option value="all"  selected>TODOS</option>
<?php echo operaciones($OPE) ?>
</select>
</td>
<td>
<select name="modulos" id="modulos" onChange="submit();" style="width:160px">
<option value="all"  selected>TODOS</option>
<?php echo modulos($MOD) ?>
</select>
</td>
<td>
<select name="id_usuario" id="id_usuario" onChange="submit();" style="width:160px">
<option value="all"  selected>TODOS</option>
<?php echo usuarios($ID_USU,"id_usuario","id_usuario","") ?>
</select>
</td>
</tr>
</tbody>
</table>
</div>
<br>
<br>
<?php require("PAGINACION.php"); ?>	
<table border="0" align="center" cellpadding="10px" id="box-table-a" width="100%" class="uk-table uk-table-hover uk-table-striped"> 
 <thead>
      <tr> 
      <th>#</th>
      <th>ID Reg.</th>
      <th>Operaci&oacute;n.</th>
      <th>M&oacute;dulo</th>
      <th>Antes</th>
      <th>Despu&eacute;s</th>
      <th>Usuario</th>
      <th>ID Usuario</th>
      <th>IP</th>
      <th width="100">Fecha</th>
      <th>Hora</th>
       </tr>
        
</thead>
<tbody>   
<?php

	$i=0;
	
	while($row = $rs->fetch())
	{
		$i++;
		$ii++;
		$regKey=$row['reg_key'];
		$operacion=$row['tipo_operacion'];
		$secc=$row['seccion_afectada'];
		$antes=htmlspecialchars_decode($row['reg_ant']);
		$new=htmlspecialchars_decode($row['reg_new']);
		
		$user=$row['usu'];
		$ID_user=$row['id_usu'];
		$ip=$row['ip_host'];
		
		$fecha=$row['fecha_op'];
		$horaReg=$row['hora_op'];
		
		
		$sql=$row['sql'];
		
		
		//echo "<li>$user, [$sql] , $fecha,<br> $new </li>";	
		?>
        <tr>
        <th><?php echo $ii ?></th>
        <td><?php echo $regKey ?></td> 
        <td><?php echo $operacion ?></td> 
        <td><?php echo $secc?></td>
         <td>
         <?php if($antes!="NO APLICA" && !empty($antes)){?>
         <input type="button" value="Ver Reg." onClick="$('#dialogA<?php echo $i ?>').dialog( 'open' );" >
        <div  class="dialog" id="dialogA<?php echo $i ?>"><?php echo $antes ?></div>
        <?php
		 }
		 else {
      echo "NO APLICA";
    }
		?>
        </td>
        <td><input type="button" value="Ver Reg." onClick="$('#dialogB<?php echo $i ?>').dialog( 'open' );" >
        <div  class="dialog" id="dialogB<?php echo $i ?>"><?php echo $new ?></div></td>
        <td><?php echo $user ?></td> 
        <td><?php echo $ID_user ?></td>
        <td><?php echo $ip ?></td>  
        <td align="center"><?php echo $fecha ?></td>
        <td><?php echo $horaReg ?></td> 
        
        	
        </tr>
        <?php
	}
	

	
?>
</tbody>
</table>
</form>
<?php require("PAGINACION.php"); ?>	
<?php require_once("FOOTER.php"); ?>	
 <script src="JS/jquery-ui.min.js"></script>
<script type="text/jscript">

  $(function() {
    $( ".dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "slide",
        duration: 1600
      },
	  height:800,
	  width:1000
	  
    });
  });
  </script>
</body>
</html>