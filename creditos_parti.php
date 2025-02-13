<?php 
include_once("Conexxx.php");
$adminFilter="AND cod_caja='$codCaja'";
$adminFilter="";
if($MODULES["UN_BAN_CLI2"]==1){auto_unban_cli2();}
if($MODULES["AUTO_BAN_CLI"]==1){auto_ban_cli();}

if($rolLv==$Adminlvl)$adminFilter="";

/////////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_cre";
$PAG_fechaF="fechaF_cre";
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


///////////////////////////////////////////////////////////////// FILTRO POR CLIENTE //////////////////////////////////////////////////////////////////////

$C="";
$nom_cli="";
$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"Buscar Nombre\" class=\"uk-button uk-button-success\" >";

if(isset($_SESSION['nom_cli'])){
	$nom_cli=limpiarcampo($_SESSION['nom_cli']);
	$C=" AND (nom_cli='$nom_cli' OR id_cli='$nom_cli')";
	$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" class=\"uk-button uk-button-danger\" >";
};

if(isset($_REQUEST['nom_cli']) && !empty($_REQUEST['nom_cli'])){
	$nom_cli=limpiarcampo($_REQUEST['nom_cli']); 
	$_SESSION['nom_cli']=$nom_cli;
	$C=" AND (nom_cli='$nom_cli' OR id_cli='$nom_cli')";
	$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" class=\"uk-button uk-button-danger\" >";
}

/*
if(isset($_REQUEST['nom_cli'])){
	
	$nom_cli=limpiarcampo($_REQUEST['nom_cli']);
	$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" data-inline=\"true\" data-mini=\"true\" >";	
}
*/

if($opc=="QUITAR NOMBRE")
{
	$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"Buscar Nombre\" class=\"uk-button uk-button-success\" >";
	$nom_cli="";
	unset($_SESSION['nom_cli']);
	$C="";
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$busq="";
$val="";
$boton="";
$pre="";

if(isset($_REQUEST['pre']))$pre=$_REQUEST['pre'];

if(isset($_REQUEST['opc'])){$boton= $_REQUEST['opc'];}
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val= $_REQUEST['valor'];
$num_fac=$val;
if(isset($_REQUEST['boton']))$boton=$_REQUEST['boton'];



imp_fac($num_fac,$pre,$boton,1,"no",0,'');

/////////////////////////////////////////////////////////////////////// FILTRO ESTADO FAC. ///////////////////////////////////////////////////////////
$filtroB="";
$B="";
if(isset($_REQUEST['filtroB'])){
	$filtroB=$_REQUEST['filtroB'];
	$_SESSION['filtroB']=$filtroB;
	if($filtroB=="Pendientes")$B="AND estado='PENDIENTE'";
	else if($filtroB=="Pagados")$B="AND estado='PAGADO'";
	else if($filtroB=="Morosos"){$B=" AND DATEDIFF(CURRENT_DATE(),DATE(fecha) )>$DIAS_BAN_CLI AND fecha_pago='0000-00-00 00:00:00' ";}
	else $B="";
}

if(isset($_SESSION['filtroB']))
{
	$filtroB=$_SESSION['filtroB'];
	if($filtroB=="Pendientes")$B="AND estado='PENDIENTE'";
	else if($filtroB=="Pagados")$B="AND estado='PAGADO'";
	else if($filtroB=="Morosos"){$B=" AND DATEDIFF(CURRENT_DATE(),DATE(fecha) )>$DIAS_BAN_CLI AND fecha_pago='0000-00-00 00:00:00' ";}
	else $B="";
	
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////// FILTRO TIPO USUARIOS ///////////////////////////////////////////////////////////
$filtroE="";
$E="";
if(isset($_REQUEST['filtroE'])){
	$filtroE=$_REQUEST['filtroE'];
	$_SESSION['filtroE']=$filtroE;
	if($filtroE!="TODOS")$E="AND id_cli IN (SELECT id_usu FROM usuarios WHERE tipo_usu='$filtroE')";
	else $E="";
}

if(isset($_SESSION['filtroE']))
{
	$filtroE=$_SESSION['filtroE'];
	if($filtroE!="TODOS")$E="AND id_cli IN (SELECT id_usu FROM usuarios WHERE tipo_usu='$filtroE')";
	else $E="";
	
}
$E="";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$cols="<th width=\"90px\" class=\"uk-hidden-touch\">#</th>
 
<th width=\"250\">No. Factura</th>
<th width=\"200\" class=\"uk-hidden-touch\">Documento Cliente</th>
<th width=\"200\">Nombre</th>
<th width=\"200\" class=\"uk-hidden-touch\">Tel.</th>
<th width=\"200\" class=\"uk-hidden-touch\">Ciudad</th>
<th width=\"150\">Tot. Cr&eacute;dito</th>

<th width=\"200\">Estado</th>
<th width=\"200\" class=\"uk-hidden-touch\">Saldo</th>
<th width=\"200\">Fecha</th>
<th width=\"200\" class=\"uk-hidden-touch\">Fecha Pago</th>
<th width=\"200\">D&iacute;as Mora</th>
";


$tabla="inv_inter";
$col_id="id_pro";
$aliasCol="(select alias from usuarios WHERE usuarios.id_usu=fac_venta.id_cli AND alias!='' LIMIT 1)";
$columns="num_fac_ven,id_cli,nom_cli,tel_cli,ciudad,estado,DATE(fecha) AS fecha,anulado,prefijo,tot,DATEDIFF(CURRENT_DATE(),DATE(fecha) ) AS mora,DATEDIFF(DATE(fecha_pago),DATE(fecha) ) AS mora2,DATE(fecha_pago) AS fecha_pago,cod_caja,num_pagare,$aliasCol AS alias,snombr,apelli ";
$url="creditos_parti.php";
$url_dialog="dialog_invIni.php";
$url_mod="#";
$url_new="#";
$pag="";
$limit = 20; 
$order="num_fac_ven";
 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
 

$sql = "SELECT  $columns FROM fac_venta WHERE tipo_venta='Credito'  AND nit=$codSuc AND (".VALIDACION_VENTA_VALIDA." $B) $adminFilter $A $C $E ORDER BY fecha DESC  LIMIT $offset, $limit"; 






if($boton=='cuotas'&& !empty($val)){
	
	$_SESSION['num_fac_cuota']=$val;
	$_SESSION['prefijo']=$pre;
	$_SESSION['pag']=$pag;
	
	header("location: cuotas_cli.php");
	}
 
if($boton=='plan'&& !empty($val)&&$_REQUEST['plan']!='Si'){
	
	$_SESSION['cod_plan']=$val;
	$_SESSION['n_fac_ven']=$val;
	$_SESSION['prefijo']=$pre;
	$_SESSION['pag']=$pag;
	header("location: Plan_amortizacion.php");
	//header("location: imp_plan_amor.php");
	};

if($boton=='impPlan'&& !empty($val)&&$_REQUEST['plan']=='Si'){
	
	$_SESSION['cod_plan']=$val;
	$_SESSION['n_fac_ven']=$val;
	$_SESSION['pag']=$pag;
	$_SESSION['prefijo']=$pre;
	
	header("location: imp_plan_amor.php");
	};



 
 
$sqlTotal = "SELECT COUNT(*) as total FROM fac_venta WHERE nit='$codSuc' AND (".VALIDACION_VENTA_VALIDA." $B) $adminFilter $A $C $E"; 
$rs = $linkPDO->query($sql ) ; 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 




if(( isset($busq) && !empty($busq))){

$sql_busq="SELECT $columns FROM fac_venta  WHERE (id_cli LIKE '$busq'  OR nom_cli LIKE '$busq%' OR num_fac_ven='$busq' OR $aliasCol LIKE '$busq%')  AND (nit=$codSuc $adminFilter AND tipo_venta='Credito' AND ".VALIDACION_VENTA_VALIDA." $B $A $C $E)";


 
$rs=$linkPDO->query($sql_busq );
//echo "$sql_busq";
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




<nav class="uk-navbar"> <a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 

<ul class="uk-navbar-nav uk-navbar-center" style="width:850px;">
<?php if($MODULES["CARTERA_MASS"]==1){?>		
<li class="ss-navbar-center"><a href="#"  onClick="print_pop('comp_ingreso_masivo.php','EGRESO',600,620)"><i class="uk-icon-dollar <?php echo $uikitIconSize ?>"></i>&nbsp;Pago Masivo</a></li> 
  <?php }?>     

			<li class="uk-parent ss-navbar-center" data-uk-dropdown="{pos:'bottom-center',mode:'click'}" aria-haspopup="true" aria-expanded="false">
            
		
            
				<a href="#" style="cursor:pointer;"><i class="uk-icon-file-text-o <?php echo $uikitIconSize ?>"></i> Informes</a>

				<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" style="top: 40px; left: 0px;">
					<ul class="uk-nav uk-nav-navbar">

<li><a href="<?php  echo "$url?boton=Cuentas Publico&pag=$pag" ?>" ><i class="uk-icon-file-text-o"></i>&nbsp;Estados de Cuenta</a></li>
<li><a href="<?php  echo "$url?boton=Creditos Pendientes Publico&pag=$pag" ?>" ><i class="uk-icon-file-text-o  "></i>&nbsp;Resumen Cartera</a></li>
<li><a href="<?php  echo "$url?boton=Morosos&pag=$pag" ?>" ><i class="uk-icon-file-text-o"></i>&nbsp;MOROSOS</a></li>


						 




					</ul>

				</div>
			</li>

<!--<li class="ss-navbar-center"><a href="#filtro_mora" data-uk-modal><i class="uk-icon-calendar <?php echo $uikitIconSize ?>"></i>&nbsp;Morosos</a></li>-->

<?php if(($rolLv==$Adminlvl || val_secc($id_Usu,"crea_recibo_caja")) && $codSuc>0){?>
<li class=" "><a class=" uk-panel-box-secondary" href="<?php echo $url ?>?opc=new_comp&valor=<?php echo 0 ?>&pag=<?php echo $pag ?>" ><i class="uk-icon-plus-square"></i>&nbsp;VARIOS</a></li><?php }?>
<li class=" "><a href="<?php echo "abonos_creditos.php" ?>"><i class="uk-icon-list"></i>Comprobantes</a></li>
			

		</ul>
						   
		<!--
        
        <li><a   href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a> </li>
        
        <div class="uk-navbar-content">Some <a href="#">link</a>.</div>-->
									
				
		<div class="uk-navbar-content uk-hidden-small uk-navbar-flip">
			<form class="uk-form uk-margin-remove uk-display-inline-block">
				<div class="uk-form-icon">
						<i class="uk-icon-search"></i>
						<input type="text" name="busq" placeholder="Buscar..." class="">
				</div>
				<input type="submit" value="Buscar" name="boton" class="uk-button uk-button-primary">
			</form>
		</div>
		<div class="uk-navbar-content uk-navbar-flip  uk-hidden-small">
				
		<div class="uk-button-group"> 
		 
				  
					<!--<button class="uk-button uk-button-danger">Button</button>
					<a class="uk-button uk-button-danger " href="compras.php">Volver</a> 
					--> 
		</div>
		</div>
</nav>










<div id="filtro_mora" class="uk-modal">
<div class="uk-modal-dialog ">

<a class="uk-modal-close uk-close"></a>

    <h1 class="uk-text-primary uk-text-bold">Morosos a la Fecha</h1>
    <form class="uk-form uk-margin-remove uk-display-inline-block">
       <i class="uk-icon-calendar uk-icon-large"> </i><input type="date" value="" name="fecha_mora" id="fecha_mora" class="uk-form-large">
    
       <input type="submit" value="Morosos Fecha" name="boton" class="uk-button uk-button-primary" onClick="//submit();">  
       </form> 
    </div>
</div>
<?php
if($boton=="PAGAR"&& (($rolLv==$Adminlvl || val_secc($id_Usu,"creditos_empleados")) && $codSuc>0))
{
	$sql1="select * from fac_venta WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit='$codSuc' and estado='PAGADO'";
	$rs1=$linkPDO->query($sql1);
	
	$sql2="select * from comprobante_ingreso WHERE num_fac=$num_fac AND pre='$pre' AND cod_su='$codSuc'";
	$rs2=$linkPDO->query($sql2);
	
	if($row=$rs1->fetch())
	{
		eco_alert("FACURA $pre-$num_fac Ya fue Pagada, operacion ANULADA");
	}
	else if($row2=$rs2->fetch())
	{
		eco_alert("FACURA $pre-$num_fac Esta siendo pagada por CUOTAS, Remitase a COMPROBANTES DE INGRESO para realizar el pago");
	}
	else{
	$sql="UPDATE fac_venta SET estado='PAGADO',modifica='$nomUsu-$id_Usu',fecha_pago=CURRENT_TIMESTAMP() WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit='$codSuc' and estado!='PAGADO'";
	$linkPDO->exec($sql);
	$HTML_antes="";
	$HTML_despues="<div style='font-size:24px;'>FACTURA <span style='color:red'>No. $num_fac - $pre</span> <b>PAGADA</b></div>";
	logDB($sql,$SECCIONES[3],$OPERACIONES[2],$HTML_antes,$HTML_despues,$num_fac);
	eco_alert("FACURA $pre-$num_fac PAGADA");
	js_location($url);
	}
	
	
};
$tipoImp="";
if(isset($_REQUEST['tipo_imp']))$tipoImp=$_REQUEST['tipo_imp'];
//eco_alert("Tipo Imp: $tipoImp, Button: $boton");
if($boton=="Estados de Cuenta Creditos")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("arqueos_estados_cuenta.php","Factura No. $val", "900px","650px");
};
if($boton=="Creditos Por Cobrar")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("informe_creditos_pendientes.php","Factura No. $val", "900px","650px");
};


if($boton=="Cuentas Publico")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("estados_cuenta_publico.php","Factura No. $val", "900px","650px");
};

if($boton=="Creditos Pendientes Publico")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("estados_cuenta_publico.php?resumen=1","Factura No. $val", "900px","650px");
};

if($boton=="Morosos")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("estados_cuenta_publico.php?filtro=1","Factura No. $val", "900px","650px");
};

if($boton=="pagos")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("informe_creditos_pagos.php","Factura No. $val", "900px","650px");
};

if($boton=="Morosos Fecha" && !empty($_REQUEST["fecha_mora"]))
{
	//echo "ENTRA".$opc."<br>";
	$fechaMora=r("fecha_mora");
	popup("estados_cuenta_publico.php?fecha_mora=$fechaMora","Factura No. $val", "900px","650px");
};



if(($rolLv==$Adminlvl || val_secc($id_Usu,"creditos_publico")) && $codSuc>0){
if($opc=="new_comp")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	popup("comp_ingreso_vario.php","Comprobante de Ingreso", "600px","620px");
};
};


$bigList="";
//$MODULES["modulo_planes_internet"]==1
if(0){
$sqlAux="SELECT * FROM usuarios WHERE cod_su=$codSuc GROUP BY id_usu";
$rsAux=$linkPDO->query($sqlAux);
while($rowAux=$rsAux->fetch()){
$bigList.=$rowAux["id_usu"].";";	
	
}
}
?>
<h1 align="center">CARTERA </h1>
<!--
<div id="sb-search" class="sb-search">
						<form>
							<input class="sb-search-input" placeholder="Ingrese su b&uacute;squeda..." type="text" value="" name="busq" id="search">
							<input class="sb-search-submit" type="submit" value="Buscar" name="opc">
							<span class="sb-icon-search"></span>
						</form>
					</div>
                    -->
 

<form action="<?php echo $url ?>" method="get" name="form" class="uk-form">
<textarea id="listCli" name="listCli" rows="20" cols="20" class="uk-hidden"><?php echo $bigList;?></textarea>
<div class="uk-width-2-3">

	<table align="left" class="creditos_filter_table tabla-datos">
	<TR>

		<th style="background:rgba(0,0,0,0);"></th>
		<TH colspan="5" align="center">Fecha </TH>
		<th colspan="3">Nombre</th>
	</TR>
	
	<TR>
		<td>
			<div style="position:relative;top:-15px;padding-left:10px;padding-right:10px;" class="uk-text-center">
				FILTROS INFORMES:
			</div>
		</td>
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
		<td ></td>
		<td><input type="text" name="nom_cli" value="<?php echo $nom_cli ?>"  placeholder="Nombre Cliente" id="nom_cli"></td>
		<td><?php echo $botonFiltroNom ?></td>

	</TR>
	</table>
</DIV>


<div >
	
	<table class="creditos_filter_table tabla-datos" width=""  cellpadding="0" cellspacing="1" align="center" style="padding:10px 10px 9px 10px;position:relative;top:5px;">
		<TR >

			<TH colspan="" align="center">Filtro Estado Credito</TH><th>Tipo Cliente</th>
		</TR>
		
		<tr>
			<td>
				<select name="filtroB" onChange="submit()">
					<option value="TODOS" <?php if($filtroB=="TODOS")echo "selected" ?>>TODOS</option>
					<option value="Pendientes" <?php if($filtroB=="Pendientes")echo "selected" ?>>Pendientes</option>
					<option value="Pagados" <?php if($filtroB=="Pagados")echo "selected" ?>>Pagados</option>
					<option value="Morosos" <?php if($filtroB=="Morosos")echo "selected" ?>>MOROSOS</option>
				</select>
			</td>
			<td>
				<select name="filtroE" onChange="submit()">
					<?php
						$sql3="SELECT tipo_usu FROM usuarios GROUP BY tipo_usu";
						$rs3=$linkPDO->query($sql3);
						$selected="";
						while($row3=$rs3->fetch()){
						if($filtroE==$row3['tipo_usu'])$selected=" selected ";
						else $selected="";
						echo "<option value=\"$row3[tipo_usu]\" $selected>$row3[tipo_usu]</option>";	
							
						}
					?>
					</select>
			</td>
		</tr>
	</table>
	
</div>


<!--
<div class="uk-alert uk-alert-danger" data-uk-alert>Este Vagabundo no puede sacar mas Creditos!!!!<a href="#" class="uk-alert-close uk-close"></a></div>
-->

<?php include("PAGINACION.php"); ?>

<div class="uk-overflow-containerS">
<table border="0" align="center"  class="uk-table uk-table-hover uk-table-striped tabla-datos" style="" > 
 <thead>
      <tr valign="top" class="uk-block uk-block-secondary uk-contrast"> 
      
<?php echo $cols;   ?>

       </tr>
        
   </thead>
   <tbody>       
      
<?php 
$bgColor="#FFF";
while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $id_cli = $row["id_cli"]; 
            $nom = "$row[nom_cli] $row[snombr] $row[apelli]"; 
			$tel = $row["tel_cli"];
			$ciudad =$row["ciudad"];
			$num_fac = $row["num_fac_ven"];
			$fecha = $row["fecha"];
			$fecha_pago =$row["fecha_pago"];
			$estado = $row["estado"];
			$totFac=$row['tot']*1;
			$pre=$row['prefijo'];
			$mora=$row['mora'];
			$mora2=$row['mora2'];
			$numPagare=$row['num_pagare'];
			if($estado=="PAGADO")$mora=$mora2;
			$ANULADO=$row['anulado'];
			if($ANULADO=="ANULADO"){$estado="<b>$ANULADO</b>";$bgColor="#999999";}
			else {$bgColor="#FFF";} 
			
			$alias=$row['alias'];
			
			
			$printAlias="";
			if(!empty($alias))$printAlias="(<B>$alias</B>)";
			
			$cod_caja=$row['cod_caja'];

			$ConsultaSaldo=tot_abon_cre($id_cli);
			



$tot_cuotas=0;
$val_cre=0;
$tot_fac=0;

$sqlAux1="SELECT p.id_cli,(tot-r_fte-r_ica-r_iva) TOT 
          FROM fac_venta  p  
		  WHERE  p.prefijo='$pre' AND p.num_fac_ven=$num_fac AND nit='$codSuc'";	//echo "<br><b>$sql</b>";
	$rsAux1=$linkPDO->query($sqlAux1);
	
	if($rowAux1=$rsAux1->fetch())
	{
	   
		$ID_CLI=$rowAux1["id_cli"];
		$tot_fac=$rowAux1["TOT"]*1;
	}
$sqlAux2="SELECT SUM(a.abono) as tot,a.pre,a.num_fac FROM cartera_mult_pago a 
		  LEFT JOIN (SELECT cod_su,fecha,num_com,cajero,valor,num_fac,pre,anulado 
		  FROM comprobante_ingreso WHERE cod_su='$codSuc'  ) b ON a.num_comp=b.num_com 
		  WHERE  estado!='ANULADO' AND a.cod_su=b.cod_su AND a.cod_su='$codSuc' AND (a.num_fac='$num_fac' AND a.pre='$pre')";	
//echo "<br><b>$sql</b>";
$rsAux2=$linkPDO->query($sqlAux2);
	
if($rowAux2=$rsAux2->fetch())
{$tot_cuotas=$rowAux2['tot'];}		 
$saldo=$tot_fac-$tot_cuotas;			
         ?>
 
<tr  bgcolor="<?php echo $bgColor ?>">
<th class="uk-hidden-touch"><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<?php
if(($rolLv==$Adminlvl || val_secc($id_Usu,"creditos_publico")) && $codSuc>0){
?>
<td>
 

<div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
<button class="uk-button uk-button-primary" style="width:100px;">Opciones <i class="uk-icon-caret-down"></i></button>
<div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom" style="top: 30px; left: 0px;">
<ul class="uk-nav uk-nav-dropdown uk-text-large">
<?php
$saldoCreditoCliente = money2($ConsultaSaldo['saldo']);
if ($fac_servicios_mensuales==1)
{
	$codPais_telefono = 57;
	$conceptoCobro ="Facturación Electrónica Noviembre y Diciembre 2024";
	$numCelular =limpianum($tel);
	
	$fechaPagoCobro = "20-02-2025";
	$mensajeCobro = urlencode("Hola $nom, tu servicio de $conceptoCobro es de $saldoCreditoCliente, por favor pagar antes de $fechaPagoCobro");
	if(!empty($tel) ){
		echo <<<EOS
		<li><a href="https://wa.me/$codPais_telefono$numCelular/?text=$mensajeCobro" target="_blank"><i class="uk-icon-whatsapp     uk-icon-large"></i> &nbsp;Envia Cobro a cliente</a></li>
		EOS;
	}
	else {
		echo <<<EOS
		<li><a href="#" target="_blank"><i class="uk-icon-whatsapp     uk-icon-large uk-text-danger"></i> &nbsp;Tel. no disponible</a></li>
		EOS;
	}
}

?>
<li><a href="#" class="" onClick="print_pop('<?php echo "comp_ingreso.php?cc=$id_cli&nf=$num_fac&pre=$pre&tot_fac=$tot_fac&saldo=$saldo"; ?>','PAGOS',750,600);"><i class="uk-icon-dollar    uk-icon-large"></i> Pagar</a></li>
<li><a href="<?php echo $url ?>?opc=cuotas&valor=<?php echo $num_fac ?>&pag=<?php echo $pag ?>&pre=<?php echo $pre ?>" class="" ><i class="uk-icon-database     uk-icon-large"></i> Ver Cuotas</a></li>

<li><a href="<?php echo $url ?>?opc=Imprimir&valor=<?php echo $num_fac ?>&pre=<?php echo $row['prefijo'] ?>&tipo_imp=carta&pag=<?php echo $pag ?>" class="" ><i class="uk-icon-print     uk-icon-large"></i> Imprimir Factura</a></li>
<?php if($MODULES["PLAN_AMOR"]==1){?>
<li class="uk-nav-divider"></li>
<li><a href="#" onClick="print_pop('<?php echo "plan_amortizacion.php?n_fac_ven=$num_fac&prefijo=$pre"; ?>','Kardex',850,650);" class=""><i class="uk-icon-suitcase     uk-icon-large"></i> Crear Plan Amortizaci&oacute;n</a></li>
<?php }?>
</ul>
</div>
</div>


<!--<a href="<?php echo $url ?>?opc=cuotas&valor=<?php echo $num_fac ?>&pag=<?php echo $pag ?>&pre=<?php echo $pre ?>" class="uk-icon-money uk-icon-button uk-icon-hover uk-icon-large"></a>-->
</td>

<?php

}
?>

</tr>
<?php if($MODULES["PLAN_AMOR"]==1){?>
<tr>
<td><a href="#" onClick="print_pop('<?php echo "plan_amortizacion.php?n_fac_ven=$num_fac&prefijo=$pre"; ?>','Kardex',850,650);"><i class="uk-icon-calculator uk-icon-button uk-icon-hover uk-icon-large"></i></a></td>
</tr>
<?php }?>
</table>


</td>             
<td><?php echo $num_fac; ?></td>
<td class=""><a href="<?php echo "$url?nom_cli=$id_cli&boton=Cuentas Publico"; ?>"><?php echo "$id_cli"; ?></a> </td>
<td><?php echo "$nom  $printAlias"; ?></td> 
<td class="uk-hidden-touch"><?php echo $tel; ?></td>
<td class="uk-hidden-touch"><?php echo $ciudad; ?></td>
<td><?php echo money($totFac); ?></td>
<td><?php if($estado!="PAGADO"){echo "<div class=\"  uk-badge uk-badge-warning\">$estado</div>";}else {echo "<div class=\"  uk-badge uk-badge-success\">$estado</div>";} ?></td>
<td class="uk-hidden-touch"><b><?php echo $saldoCreditoCliente; ?></b></td>
<td><?php echo $fecha; ?></td>
<td class="uk-hidden-touch"><?php echo $fecha_pago; ?></td>
<td><?php echo $mora; ?></td>
</tr> 
         
<?php 
         } 
      ?>

   </tbody>       
   
</table>
</div>
</form>
<?php 

if(isset($_REQUEST['plan'])&&$_REQUEST['plan']=='Si'&& !empty($val))eco_alert("La Factura #$val requiere un PLan de Amortizacion");
//echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>

<?php include("PAGINACION.php"); ?>	
<?php include_once("FOOTER.php"); 
include('alertaPagoClienteSS.php');?>
<?php include_once("autoCompletePack.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5">
var cont_i=0;
var Global_txt='';

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
				simplePopUp("FIN");
				waitAndReload();
				}
			//hide_pop('#modal');
		
		});
		
		
		
		
	}
	
};


 
function ban_list_warn()
{
	var data='';
	ajax_b('ajax/WARNINGS/list_near_ban.php',data,function(resp){
		
		var msg1='<div class="uk-alert-close">MIRAR DE INMEDIATO!!!</div>',msg2='Lista Clientes a punto de entrar en MORA',msg3=resp;
		
		if(resp!=0)open_pop(msg1,msg2,msg3)
		
		});
	
};
function disable_ban_list_warn()
{
	var data='';
	ajax_b('ajax/WARNINGS/disable_ban_list.php',data,function(resp){
		hide_pop('#modal');
	});
};

 
 
$(document).ready( function() {
	ban_list_warn();
	call_autocomplete('ID',$('#nom_cli'),'ajax/busq_cli.php');
 
	
	
});


</script>
</div>
</div><!--fin pag 1-->
<?php 

?>
</body>
</html>