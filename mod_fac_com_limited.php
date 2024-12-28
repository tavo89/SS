<?php
include_once('Conexxx.php');
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"compras_mod")){header("location: centro.php");}
valida_session();
$feVenRep=r("feVenR");
$pag="";
$limit = 50;
$url="mod_fac_com_limited.php";

$boton="";
$busq="";
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['boton']))$boton= $_REQUEST['boton'];

$ORDER_BY=" id ";
/////////////////////////////////////// FILTRO REP ////////////////////////////////////
$filtro_rep="";
if(isset($_SESSION['filtro_rep']))$filtro_rep=$_SESSION['filtro_rep'];
if(isset($_REQUEST['filtro_rep'])){$filtro_rep=$_REQUEST['filtro_rep'];$_SESSION['filtro_rep']=$filtro_rep;};



$filtroWhereSQL= $filtro_rep=='cero' ? ' AND cant=0 AND unidades_fraccion=0':'';

$E="";
if($filtro_rep=="rep")
{


$E=" INNER JOIN (SELECT  ref,cod_barras,fecha_vencimiento,COUNT(`ref`) c 
     FROM art_fac_com WHERE num_fac_com='$_SESSION[num_fac]' AND cod_su=$codSuc AND nit_pro='$_SESSION[nit_pro]' GROUP BY ref,cod_barras,fecha_vencimiento HAVING c>1 ) b 
     ON (a.ref=b.ref AND a.cod_barras=b.cod_barras AND a.fecha_vencimiento=b.fecha_vencimiento) ";

$ORDER_BY=" a.ref,a.des ";
}

else {$E="";$ORDER_BY=" id ";}
///////////////////////////////////////////////////////////////////////////////////////

//echo "$E";
//$E="";
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1)
{
   $pag = 1;
}
$offset = ($pag-1) * $limit;
$ii=$offset;

$sql="";

$num_ref=0;
$num_fac="";
$nit_pro="";
$num_facH=0;
$nit_proH="";
if(isset($_SESSION['num_fac'])){$num_fac=$_SESSION['num_fac'];$num_facH=$_SESSION['num_fac'];}
if(isset($_SESSION['nit_pro'])){$nit_pro=$_SESSION['nit_pro'];$nit_proH=$_SESSION['nit_pro'];}


$fecha="";
$provedor="";
$ciudad="";
$dir="";
$tel="";
$fax="";
$mail="";

$subtot=0;
$iva=0;
$tot=0;

$boton="";

if(isset($_REQUEST['boton'])&&!empty($_REQUEST['boton']))$boton=$_REQUEST['boton'];

if($boton=="AJUSTAR Costos y PvP" && $rolLv==$Adminlvl)
{

try {
$linkPDO->beginTransaction();
$all_query_ok=true;


	$sqlResto="UPDATE art_fac_com a INNER JOIN inv_inter b ON a.ref=b.id_pro SET a.costo=b.costo WHERE (a.num_fac_com='$num_fac' AND a.nit_pro='$nit_pro' AND a.cod_su='$codSuc') AND (a.cod_barras=b.id_inter) AND (a.costo=0)";

	$sqlResto2="UPDATE art_fac_com a INNER JOIN inv_inter b ON a.ref=b.id_pro SET a.pvp=b.precio_v WHERE (a.num_fac_com='$num_fac' AND a.nit_pro='$nit_pro' AND a.cod_su='$codSuc') AND (a.cod_barras=b.id_inter) AND (a.pvp=0)";


	$linkPDO->exec($sqlResto);
	$linkPDO->exec($sqlResto2);



	$utilResto=r("utilResto");
	if(empty($utilResto))$utilResto=25;
	if($tipo_utilidad=="A"){
		$gana=$utilResto*1/100;
		$costo="(pvp / (1+$gana) ) / (1+(iva/100))";
		$pvp="ROUND(costo*(1+iva/100)*((1+$utilResto/100)),-2)";

		$sqlA="UPDATE art_fac_com SET pvp=$pvp,uti='$utilResto' WHERE (num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su='$codSuc') AND (costo!=0 AND pvp=0)";
		$sqlB="UPDATE art_fac_com SET costo=$costo,uti='$utilResto' WHERE (num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su='$codSuc') AND (costo=0 AND pvp!=0)";


		$linkPDO->exec($sqlA);
		$linkPDO->exec($sqlB);

		}
	else {
		$gana=(100-$utilResto*1)/100;
		$costo="(pvp*gan)/(1+(iva/100))";
		$pvp="ROUND(costo*(1+iva/100)/ ( (100-$utilResto)/100 ) ,-2)";

		$sqlA="UPDATE art_fac_com SET pvp=$pvp,uti='$utilResto' WHERE (num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su='$codSuc') AND (costo!=0 AND pvp=0)";
		$sqlB="UPDATE art_fac_com SET costo=$costo,uti='$utilResto' WHERE (num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su='$codSuc') AND (costo=0 AND pvp!=0)";
$linkPDO->exec($sqlA);
$linkPDO->exec($sqlB);

		}



tot_com($nit_pro,$num_fac,"p1","per");
$linkPDO->commit();
if($all_query_ok){

}
else{eco_alert("ERROR! Intente nuevamente");}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

}

if($boton=="Eliminar Casillas Vacias"){

$sql="DELETE FROM art_fac_com  WHERE (num_fac_com='$num_fac' AND  nit_pro='$nit_pro' AND  cod_su='$codSuc')
AND (
( des='' OR  (ref='' OR  cod_barras=''))

)";
t1($sql);
}


?>

<!DOCTYPE html>
<html>
<head>
<style>
		/* Estilos Compras Emperatriz */
		/*
      #articulos tr{
          height: 36px !important;
          background-color: #DDD !important;
      }

      .fc_codBarras{
          width: 110px !important;
          margin: 0px !important;
          padding: 0px !important;
      }

      .fc_ref{
          width: 110px !important;
          margin: 0px !important;
          padding: 0px !important;
      }

      .fc_des{
          width: 250px !important;
          height: 30px !important;
          margin: 0px !important;
          padding: 0px !important;
          resize: none !important;
      }

      .fc_fab{
          width: 90px !important;
          margin: 0px !important;
          padding: 0px !important;
      }

      .fc_clase{
          width: 120px !important;
          margin: 0px !important;
          padding: 0px !important;
      }






	  */
	 @media (max-width: 576px) { 
body{
transform: scale(0.8);
transform-origin: top left;
-moz-transform: scale(0.8);
-moz-transform-origin: top left;
}
}

/* Medium devices (tablets, 768px and up)*/
@media (max-width: 768px) {

body{
transform: scale(0.8);
transform-origin: top left;
-moz-transform: scale(0.8);
-moz-transform-origin: top left;
}

 }
 
 @media (max-width: 992px) {

body{
transform: scale(0.9);
transform-origin: top left;
-moz-transform: scale(0.9);
-moz-transform-origin: top left;
}

 }

/*Extra large devices (large desktops, 1200px and up)*/
@media (min-width: 1200px) {

 


 }
  </style>
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<?php include_once("HEADER.php"); ?>
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/style.css">
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/prism.css">
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/chosen.css">



<script src="JS/jquery-2.1.1.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>

<script language='javascript' src="JS/fac_com.js?<?php echo "$LAST_VER" ?>"></script>
</head>

<body>
<div class="container ">
  <!-- Push Wrapper -->
  <div class="mp-pusher" id="mp-pusher">
    <?php //include_once("menu_izq.php"); ?>
    <?php include_once("menu_top.php"); ?>
    <?php include_once("boton_menu.php"); ?>
    <div class="uk-width-9-10 uk-container-center">
      <nav class="uk-navbar"> <a class="uk-navbar-brand uk-visible-large" href="#"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a>
      <ul class="uk-navbar-nav uk-navbar-center" style="">
       <li class="ss-navbar-center"><a href="#filtro_repetidos" data-uk-modal><i class="uk-icon-filter <?php echo $uikitIconSize ?>"></i>&nbsp;Filtros</a></li>
       <li class="ss-navbar-center"><a href="ReporteExcelCompra.php"  ><i class="uk-icon-list <?php echo $uikitIconSize ?>"></i>&nbsp;Listado Productos</a></li>
         </ul>

        <div class="uk-navbar-content uk-hidden-small">
          <form class="uk-form uk-margin-remove uk-display-inline-block">
            <input type="text" id="busq" name="busq" placeholder="Buscar..." class="" value="<?php echo $busq; ?>">
            <input type="submit" value="Buscar" name="boton" class="uk-button uk-button-primary">
          </form>
        </div>
        <div class="uk-navbar-content uk-navbar-flip  uk-hidden-small">
          <div class="uk-button-group"> <a class="uk-button uk-button-danger " href="compras.php">Volver</a>
            <!--<button class="uk-button uk-button-danger">Button</button>-->
          </div>
        </div>
      </nav>


      <div id="filtro_repetidos" class="uk-modal">
<div class="uk-modal-dialog ">
<div class="uk-grid">
<a class="uk-modal-close uk-close"></a>
<div class="uk-width-1-1">
    <h1 class="uk-text-primary uk-text-bold">Filtros Compra</h1>
    <form class="uk-form uk-margin-remove uk-display-inline-block">
Filtro Repetidos:

<select name="filtro_rep" onChange="submit();">
<option value="TODOS" <?php if($filtro_rep=="TODOS")echo "selected";?>>TODOS</option>
<option value="rep" <?php if($filtro_rep=="rep")echo "selected";?>>Repetidos</option>
<option value="cero" <?php if($filtro_rep=="cero")echo "selected";?>>Cantidad Cero</option>

</select>
       </form>
</div>

<form class="uk-form uk-margin-remove uk-display-inline-block">
<div class="uk-width-1-1">
<h1 class="uk-text-primary uk-text-bold">Ajustes Datos Compra</h1>
<?php

if($rolLv==$Adminlvl  ){
?>
<input type="text" name="utilResto" value="" placeholder="Utilidad Promedio"  class="uk-form">
<input type="submit" value="AJUSTAR Costos y PvP" name="boton" id="boton2" class="uk-button uk-button-primary">
<?php
}
?>
</div>
<div class="uk-width-1-1">
<input type="submit" value="Eliminar Casillas Vacias" name="boton" id="boton3" class="uk-button uk-button-danger">
</div>
</form>


       </div><!-- uk-grid -->

    </div>
</div>

      <form action="mod_fac_com.php" name="form_fac" method="post" id="form_fac" class="uk-form" autocomplete="off">
      <input type="text" style="visibility: hidden" name="boton" id="boton" value="" >
      <?php
$sql="SELECT  * FROM fac_com WHERE num_fac_com='".$_SESSION['num_fac']."' AND cod_su='$codSuc' AND nit_pro='".$_SESSION['nit_pro']."'";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
$fecha=$row['fecha'];
$fechaVen=$row['feVen'];
$provedor=$row['nom_pro'];
$ciudad=$row['ciudad'];
$dir=$row['dir'];
$tel=$row['tel'];
$fax=$row['fax'];
$mail=$row['mail'];
$tipo_fac=$row['tipo_fac'];

$calc_dcto=$row["calc_dcto"];

$subtot=$row['subtot'];
$descuento=$row['descuento'];
$descuento2=$row['dcto2'];
$iva=$row['iva'];
$flete=$row['flete'];
$perFlete=$row['perflete'];
$tot=$row['tot'];
$val_letras=$row['val_letras'];
$num_fac_com=$row['serial_fac_com'];
$numTras=$row['serial_tras'];

$CALC_PVP_COST_GANA=$row['calc_pvp'];

$R_FTE=$row['r_fte'];
$R_IVA=$row['r_iva'];
$R_ICA=$row['r_ica'];
$TOT_PAGAR=$tot-($R_FTE+$R_ICA+$R_IVA+$descuento2);

$NOTA=$row["nota"];

}
//echo "IP: ".get_ip();
?>
      <div id="salida"> </div>
      <div class="loader"> <img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" /> </div>
      <div id="fac_com">
      <table frame="box" align="left" cellspacing="0" class="round_table_gray" id="tab_compra">
      <thead>
        <tr>
          <td height="80" colspan="14" align="center"><table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
              <tr>
                <td width="300px"><div align="center"><img src="<?php echo $url_LOGO_B ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>"> </div></td>
                <td align="left" colspan=""><?php  echo $PUBLICIDAD  ?></td>
                <td align="center" style="font-size:18px; "><?php
			  if($tipo_fac!="Traslado"){
			  ?>
                  <B>FACTURA DE COMPRA</B><br>
                  <span style="color:#F00"># <?php echo $num_fac_com ?></span>
                  <?php
			  }
			  else{
			  ?>
                  <B>TRASLADO</B><br>
                  <span style="color:#F00"># <?php echo $numTras ?></span>
                  <?php
			  }
			  ?></td>
              </tr>
            </table></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="15"><table frame="box" rules="rows" width="100%">
              <tr>
                <td  align="left" colspan="3"><table align="left" cellspacing="0">
                    <tr>
                      <td   colspan="" class="uk-text-large">Fecha:</td>
                      <td  colspan="" >
                       <input readonly  id="fecha2" type="date" value="<?php echo $fecha ?>" name="fecha"  class="save_fc " style="width:150px;" /><i class="uk-icon-calendar uk-icon-medium"></i>

    </td>
                      <td colspan="" align="right" class="uk-text-large uk-text-bold" >
                      <h1 class="uk-text-danger uk-text-bold">No. FACTURA</h1></td>
                      <td valign="top">&nbsp;
                        <input readonly name="num_fac" type="text" id="num_fac" value="<?php echo $num_fac ?>" onChange="val_mod_fc($('#num_fac').val(),$('#nit').val(),'fac_com','num_fac_com','nit_pro',$('#resp'),'<?php echo $nit_proH ?>','<?php echo $num_facH?>');" onBlur="//nan($(this))" class="save_fc uk-form-danger uk-form-large" style="width:200px; font-size:28px;"/></td>
                    </tr>
                    <tr>
                      <td colspan="" class="uk-text-large"> Fecha Vencimiento: </td>
                      <td> <input readonly  id="fechaVen" type="date" value="<?php echo $fechaVen ?>" name="fechaVen"  class="save_fc" style="width:150px;" /> <i class="uk-icon-calendar uk-icon-medium"></i></td>
                      <td colspan="2"><div id="resp" style="visibility:hidden; color: #F00; width:180px;"><img alt="" src="Imagenes/delete.png" width="20" height="20" />Este n&uacute;mero ya existe</div></td>
                    </tr>
                    <tr style="font-weight:bold;"  >
                      <td colspan="" width="" class="destacar_cont">COSTOS Y PVP: </td>
                      <td colspan="3"><input type="hidden" value="" name="CNC" id="CNC" onChange="useDB($(this));" class="">
                        <select name="calc_pvp" id="tipo_op" onChange="" class="uk-form-large uk-form-success save_fc ">
                          <option value=""  <?php if($CALC_PVP_COST_GANA=="CALCULAR"){echo "selected";} ?>>CALCULAR</option>
                          <option value="ganancia" <?php if($CALC_PVP_COST_GANA=="ganancia"){echo "selected";} ?>>Ganancia</option>
                          <option value="costo" <?php if($CALC_PVP_COST_GANA=="costo"){echo "selected";} ?>>Costo</option>
                          <option value="pvp" <?php if($CALC_PVP_COST_GANA=="pvp"){echo "selected";} ?>>PVP</option>
                        </select></td>

                    </tr>
                  </table>
                  <!-- TAB 0.1--></td>
                <td colspan="" style="font-size:18px" class="uk-hidden">Promediar Costos:
                  <select name="confirma" class="save_fc" id="confirma">
                    <option value="" ></option>
                    <option value="Si" >Si</option>
                    <option value="No" selected>No</option>
                  </select></td>
                <td  colspan="2" style="font-size:18px">Tipo Facturaci&oacute;n:
                  <select   name="tipo_fac" style="width:150PX" class="save_fc" id="tipo_fac" onChange="cambioTFC($(this),$('#tab_compra'));">
                    <?php

					//if($num_fac=="1122334455-IMP")$tipo_fac="Importar BD";
					?>
                    <option value="<?php echo $tipo_fac ?>"  selected><?php echo $tipo_fac ?></option>
                    <?php

					if($num_fac!="1122334455-IMP"){
					?>
                    <option value="Compra" >Compra</option>
                    <option value="Remision" >Remision</option>
                    <!--<option value="Traslado" >TRASLADO</option>-->
                    <option value="Inventario Inicial" >Inventario Inicial</option>
                    <option value="Ajuste Seccion" >Ajuste Seccion</option>
                    <option value="Corte Inventario" >Corte Inventario</option>
                    <?php }?>
                  </select></td>
              </tr>
              <tr class="uk-text-bold">
                <td  colspan="" >Proveedor:
                  <table border="0" width="100%">
                    <tr>
                      <td ><input readonly  name="provedor" type="text" id="provedor" value="<?php echo $provedor ?>" style="width:250px" class="save_fc"/></td>
                      <td colspan="2">
 				</td>
                    </tr>
                  </table></td>
                <td>NIT:
                  <input readonly   style="width:100px;" name="nit" type="text" id="nit" value="<?php echo $nit_pro ?>" onChange="pro(this.value);" class="save_fc"/></td>
                <td colspan="2">&nbsp;&nbsp;&nbsp;Mail:
                  <input style="width:150px" name="mail" type="text" id="mail" value="<?php echo $mail ?>" class="save_fc"></td>
              </tr>
              <tr class="uk-text-bold">
                <td>Direcci&oacute;n:
                  <input style="width:200px" name="dir" type="text" value="<?php echo $dir ?>" id="dir" class="save_fc"/></td>
                <td width="24%" align="">Ciudad:
                  <input style="width:200px" name="ciudad" type="text" id="ciudad" value="<?php echo $ciudad ?>" class="save_fc"/></td>
                <td>Tel.:
                  <input style="width:100px" name="tel"  type="text" value="<?php echo $tel ?>" id="tel" class="save_fc" /></td>
                <td>Fax:
                  <input style="width:80px" name="fax" type="text" id="fax" value="<?php echo $fax ?>"  class="save_fc"/></td>
              </tr>
            </table>
            <!-- TAB 1 --></td>
        </tr>
        <tr >
          <td colspan="14">
          <table id="articulos" width="100%" cellspacing="0" cellpadding="0" frame="box" rules="cols" style="border-width:1px" class="tabla_inv">
          <thead>
              <tr class="data-uk-sticky">
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>#</strong></div></td>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px" class=" fc_codBarras"><strong>Cod. Barras</strong></div></td>
                <td colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Referencia</strong></div></td>

                <?php if($usar_serial==1){ ?><td colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Serial</strong></div></td> <?php } ?>

                <?php if($cert_import==1){ ?><td colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Cert. Import</strong></div></td> <?php } ?>

                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px" class="fc_cant"><strong>Cant.</strong></div></td>


                <?php if($usar_fracciones_unidades==1){ ?>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Fracc.</strong></div></td>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Uni.</strong></div></td>
                <?php } ?>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>&nbsp;Descripci&oacute;n&nbsp;</strong></div></td>

                <?php if($MODULES["DES_FULL"]==1){ ?>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>&nbsp;Uso</strong></div></td>
                <?php } ?>

                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Ubicacion</strong></div></td>
                <?php if($usar_color==1){ ?>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Color</strong></div></td>
                <?php } ?>
                <?php if($usar_talla==1){ ?>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Talla</strong></div></td>
                <?php } ?>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Presentaci&oacute;n</strong></div></td>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Fabricante</strong></div></td>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Clase</strong></div></td>

                <?php if($MODULES["APLICA_VEHI"]==1){?>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Aplicaci&oacute;n Vehi.</strong></div></td>
                <?php }?>

                <?php if($usar_fecha_vencimiento==1){?>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Fecha Vencimiento</strong></div></td>
                <?php }?>

                <td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Costo</strong></div></td>
                <td  colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Dcto</strong></div></td>
                <td  colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Util.</strong></div></td>
                <td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>IVA</strong></div></td>
                <td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>PVP</strong></div></td>
                <?php if($MODULES["PVP_CREDITO"]==1){ ?>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>PvpCre</strong></div></td>
                <?php } ?>
                <?php if($MODULES["PVP_MAYORISTA"]==1){ ?>
                <td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>PvpMayor</strong></div></td>
                <?php } ?>
                <td width="90px"  colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong> Total</strong></div></td>
              </tr>
              </thead>
              <tbody>
              <?php
$sql="SELECT  * FROM art_fac_com a $E WHERE a.num_fac_com='$num_fac'  AND a.cod_su=$codSuc AND a.nit_pro='$nit_pro' $filtroWhereSQL ORDER BY $ORDER_BY ASC LIMIT $offset, $limit";

//echo "$sql";

$sql="SELECT  * FROM art_fac_com a $E WHERE a.num_fac_com='$num_fac'  AND a.cod_su=$codSuc AND a.nit_pro='$nit_pro' $filtroWhereSQL ORDER BY $ORDER_BY ASC LIMIT $offset, $limit";
//$sql="SELECT  * FROM art_fac_com WHERE num_fac_com='$num_fac' AND cod_su=$codSuc AND nit_pro='$nit_pro' ORDER BY ref";
//echo "<LI>$sql</LI>";

/////////////////////////////////////////// PAGINACION PARTE 2///////////////////////////////////////////
$sqlTotal = "SELECT COUNT(*) as total,SUM(cant) as cant_tot FROM art_fac_com a $E WHERE a.num_fac_com='$num_fac'  AND a.cod_su=$codSuc AND a.nit_pro='$nit_pro' $filtroWhereSQL";
$rs = $linkPDO->query($sql);
$rsTotal = $linkPDO->query($sqlTotal);
$rowTotal = $rsTotal->fetch();
$total = $rowTotal["total"]*1;
$totCant=$rowTotal["cant_tot"]*1;

//////////////////////////////////////////////////////////////////////////////



$rs=$linkPDO->query($sql);


if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT * FROM art_fac_com WHERE num_fac_com='$num_fac' AND cod_su=$codSuc  AND nit_pro='$nit_pro' AND (cod_barras LIKE '$busq%' OR des LIKE '%$busq%' OR ref LIKE '$busq%' OR clase LIKE '$busq%') ";



$rs=$linkPDO->query($sql_busq );


	}




$i=0;
$No=$ii;
$rbg="background-color:rgba(200,200,200,1)";
while($row=$rs->fetch())
{
	$ii++;
	//set_time_limit(30);
	$No=$ii;
	if($No%2==0)$rgb="#999999";
	else $rgb="#FFFFFF";
		$id=$row['id'];
        $cant=$row['cant']*1;
		$ref=$row['ref'];
		$cod_bar=$row['cod_barras'];
		$des=$row['des'];
		$color=$row['color'];
		$talla=$row['talla'];
		$presentacion=$row['presentacion'];
		$fabricante=$row['fabricante'];
		$clase=$row['clase'];
		$costo=money($row['costo']*1);
				//echo "<br><span style=\"color: #fff\"<b>$i costo SIN Dcto : $costo</b></span><br>";
		$dcto=$row['dcto'];
		$Iva=$row['iva'];
		$uti=$row['uti'];
		$tipoD=$row['tipo_dcto'];
		$pvp=money($row['pvp']*1);
		$pvpCre=money($row['pvp_cre']*1);
		$pvpMay=money($row['pvp_may']*1);
		$s_tot=money($row['tot']*1);
		$ubicacion=$row["ubicacion"];
		$aplica_vehi=$row["aplica_vehi"];
		if($cant<=0 )$rgb="#990000";


		$des_full=$row["des_full"];
		$feCrea=strtotime($row["fe_crea"]);
		$diffFechaCrea= $feCrea-strtotime($hoy);

		$serial=$row["serial_art"];
		$certImport=$row["cert_importacion"];

		//echo "<tr><td>supera los segundos $diffFechaCrea $hoy-$feCrea</td></tr>";
		$timeOutValidate=$diffFechaCrea>300 && (empty($cod_bar));
		if(!$timeOutValidate){

		if($busq==$cod_bar && $feVenRep==$row['fecha_vencimiento'])$rgb="green";
?>
              <tr style="background-color:<?php echo $rgb ?>;" tabindex="0" id="tr<?php echo $i ?>" onClick="//click_ele(this);" onBlur="//resetCss(this);">
                <td class="art<?php echo $i ?>" valign="top"><?php echo "<b>$No</b>" ?></td>


                <!-- cod Barras -->
                <td class="art<?php echo $i ?>" align="center" valign="top" colspan=""><input onkeyup="" class="art<?php echo $i ?> fc_codBarras" name="cod_bar<?php echo $i ?>" id="cod_bar<?php echo $i ?>" value="<?php echo $cod_bar?>"  type="text" onChange="(<?php echo $i ?>);dup_cam($('#cod_bar<?php echo $i ?>'),$('#cod_barH<?php echo $i ?>'));"    >
                  <input    class="art<?php echo $i ?>" name="cod_barH<?php echo $i ?>" id="cod_barH<?php echo $i ?>" value="<?php echo $cod_bar ?>" type="hidden" readonly></td>

                <!-- ref -->

                <td class="art<?php echo $i ?>" align="center" valign="top" colspan="2"><input onkeyup="//cod(this,'add',0,0)" class="art<?php echo $i ?> fc_ref" name="ref<?php echo $i ?>" id="ref<?php echo $i ?>" value="<?php echo $ref ?>" type="text"   onChange="(<?php echo $i ?>);dup_cam($('#ref<?php echo $i ?>'),$('#refH<?php echo $i ?>'));"   >
                  <input   style="width:35px" class="art<?php echo $i ?>" name="refH<?php echo $i ?>" id="refH<?php echo $i ?>" value="<?php echo $ref ?>" type="hidden" readonly>
                  <input   style="width:30px" class="art<?php echo $i ?>" name="id<?php echo $i ?>" id="id<?php echo $i ?>" value="<?php echo $id ?>" type="hidden" readonly></td>

    <!--  SERIAL & CERT. IMPORT -->
     <?php if($usar_serial==1){ ?><td class="art<?php echo $i ?>" align="center" valign="top" colspan="2"><input onkeyup="" class="art<?php echo $i ?> fc_ref" name="serial_art<?php echo $i ?>" id="serial_art<?php echo $i ?>" value="<?php echo $serial ?>" type="text"   onChange="(<?php echo $i ?>);"   ></td> <?php } ?>

      <?php if($cert_import==1){ ?><td class="art<?php echo $i ?>" align="center" valign="top" colspan="2"><input onkeyup="" class="art<?php echo $i ?> fc_ref" name="cert_import<?php echo $i ?>" id="cert_import<?php echo $i ?>" value="<?php echo $certImport ?>" type="text"   onChange="(<?php echo $i ?>);"   ></td> <?php } ?>

                <!-- cant -->
                <td class="art<?php echo $i ?>" align="center" valign="top"><input  class="art<?php echo $i ?> fc_cant" name="cant<?php echo $i ?>" id="cant<?php echo $i ?>" value="<?php echo $cant ?>" onkeyup="calc_uni($('#cant<?php echo $i ?>'),$('#fracc<?php echo $i ?>'),$('#unidades<?php echo $i ?>'));tot();" type="text" onBlur="cant_dcto_com($('#tipo_dcto<?php echo $i ?>'),$('#dct<?php echo $i ?>'),$('#cant<?php echo $i ?>'));(<?php echo $i ?>);">
                  <input   style="width:30px" class="art<?php echo $i ?>" name="cantH<?php echo $i ?>" id="cantH<?php echo $i ?>" value="<?php echo $cant ?>" onkeyup="tot();" type="hidden" readonly></td>
                <?php if($usar_fracciones_unidades==1){ ?>
                <!-- fraccion -->
                <td class="art<?php echo $i ?>" align="center" valign="top" colspan=""><input onkeyup="calc_uni($('#cant<?php echo $i ?>'),$('#fracc<?php echo $i ?>'),$('#unidades<?php echo $i ?>'));tot();" class="art<?php echo $i ?> fc_frac" name="fracc<?php echo $i ?>" id="fracc<?php echo $i ?>" value="<?php echo $row['fraccion']?>"  type="text" onBlur="(<?php echo $i ?>);"></td>
                <td class="art<?php echo $i ?>" align="center" valign="top" colspan=""><input onkeyup="calc_cant($('#cant<?php echo $i ?>'),$('#fracc<?php echo $i ?>'),$('#unidades<?php echo $i ?>'));tot();" class="art<?php echo $i ?>" name="unidades<?php echo $i ?>" id="unidades<?php echo $i ?>" value="<?php echo $row['unidades_fraccion'];?>" style="width:40px;top:10px" type="text" onChange="(<?php echo $i ?>);"></td>
                <?php } ?>

                <!-- descripcion -->
                <td class="art<?php echo $i ?>" align="center" valign="top"><textarea  class="art<?php echo $i ?> fc_des" name="des<?php echo $i ?>" id="des<?php echo $i ?>" value=""  onBlur="(<?php echo $i ?>);"><?php echo $des ?></textarea></td>


                <?php if($MODULES["DES_FULL"]==1){ ?>

                         <!-- descripcion FUL -->
                <td class="art<?php echo $i ?>" align="center" valign="top"><textarea  class="art<?php echo $i ?> fc_des_full" name="des_full<?php echo $i ?>" id="des_full<?php echo $i ?>" value=""  onBlur="(<?php echo $i ?>);"><?php echo $des_full ?></textarea></td>
                <?php } ?>

                <!-- ubicacion -->
                <td class="art<?php echo $i ?>" align="center" valign="top" colspan=""><input onkeyup="" class="art<?php echo $i ?> fc_color" name="ubicacion<?php echo $i ?>" id="ubicacion<?php echo $i ?>" value="<?php echo $ubicacion?>"  type="text" onBlur="(<?php echo $i ?>);"></td>
                <?php if($usar_color==1){ ?>
                <!-- color -->
                <td class="art<?php echo $i ?>" align="center" valign="top" colspan=""><input onkeyup="" class="art<?php echo $i ?> fc_color" name="color<?php echo $i ?>" id="color<?php echo $i ?>" value="<?php echo $color?>"  type="text" onChange="(<?php echo $i ?>);"></td>
                <?php } ?>
                <?php if($usar_talla==1){ ?>
                <!-- talla -->
                <td class="art<?php echo $i ?>" align="center" valign="top" colspan=""><input onkeyup="" class="art<?php echo $i ?> fc_talla" name="talla<?php echo $i ?>" id="talla<?php echo $i ?>" value="<?php echo $talla?>" type="text"  onChange="(<?php echo $i ?>);" ></td>
                <?php } ?>

                <!-- presentacion -->
                <td class="art<?php echo $i ?>" align="center" valign="top" colspan=""><input onkeyup="" class="art<?php echo $i ?> fc_presentacion" name="presentacion<?php echo $i ?>" id="presentacion<?php echo $i ?>" value="<?php echo $presentacion?>"  type="text"  onChange="(<?php echo $i ?>);" ></td>

                <!-- fabricante -->
                <td class="art<?php echo $i ?>" align="center" valign="top" colspan=""><input onkeyup="" class="art<?php echo $i ?> fc_fab" name="fabricante<?php echo $i ?>" id="fabricante<?php echo $i ?>" value="<?php echo $fabricante?>"  type="text"  onChange="(<?php echo $i ?>);" ></td>

                <!-- clase -->
<td class="art<?php echo $i ?>" align="center" valign="top" colspan="">
	<input type="text" onkeyup="" class="art<?php echo $i ?> fc_clase"  name="clase<?php echo $i ?>" id="clase<?php echo $i ?>" value="<?php echo $clase?>"   onChange="(<?php echo $i ?>);" >


	<!--<select class="art<?php echo $i ?> fc_clase"  name="clase<?php echo $i ?>" id="clase<?php echo $i ?>" value="<?php echo $clase?>"   onChange="(<?php echo $i ?>);" >
		<?php
			$sqlClas="SELECT  des_clas FROM clases GROUP BY des_clas";
			$rsClas=$linkPDO->query($sqlClas);
			while($rowClas=$rsClas->fetch())
			{
				if($rowClas['des_clas'] == $clase){
					echo "<option selected>".$rowClas['des_clas']."</option>";
				}else{
					echo "<option>".$rowClas['des_clas']."</option>";
				}
			}
		?>
	</select> -->
</td>



                <?php if($MODULES["APLICA_VEHI"]==1){?>
                <!-- Aplica Vehi -->
                <td class="art<?php echo $i ?>" align="center" valign="top"><input type="text"  class="art<?php echo $i ?> fc_aplica_vehi" name="aplica_vehi<?php echo $i ?>" id="aplica_vehi<?php echo $i ?>" value="<?php echo $aplica_vehi ?>"  onBlur="(<?php echo $i ?>);"></td>

                <?php }?>


                <?php if($usar_fecha_vencimiento==1){ ?>
                <!-- fecha vencimiento -->
                <td class="art<?php echo $i ?>" align="center" valign="top" colspan=""><input onkeyup="" class="art<?php echo $i ?> fc_fechaVen" name="fecha_vencimiento<?php echo $i ?>" id="fecha_vencimiento<?php echo $i ?>" value="<?php echo $row['fecha_vencimiento']?>"type="date"  onBlur="(<?php echo $i ?>);dup_cam($('#fecha_vencimiento<?php echo $i ?>'),$('#fecha_vencimientoH<?php echo $i ?>'));" >
                  <input onkeyup="" class="art<?php echo $i ?>" name="fecha_vencimientoH<?php echo $i ?>" id="fecha_vencimientoH<?php echo $i ?>" value="<?php echo $row['fecha_vencimiento']?>" style="width:150px;top:10px" type="hidden" ></td>
                <?php } ?>

                <!-- costo -->
                <td class="art<?php echo $i ?>" align="center" valign="top"><input class="art<?php echo $i ?> fc_costo" name="costo<?php echo $i ?>" id="costo<?php echo $i ?>" value="<?php echo $costo ?>" onkeyup="puntoa($(this));tot();tipo_descuento($('#tipo_op'),$('#costo<?php echo $i ?>'),$('#pvp<?php echo $i ?>'),$('#util<?php echo $i ?>'),$('#iva<?php echo $i ?>'),'<?php echo $redondear_pvp_costo ?>',<?php echo $i ?>);" type="text"  onBlur="(<?php echo $i ?>);" ></td>

                <!-- dcto -->
                <td class="art<?php echo $i ?>" align="center" valign="top"><input class="art<?php echo $i ?> fc_dcto" name="dcto<?php echo $i ?>" id="dct<?php echo $i ?>" value="<?php echo $dcto*1 ?>"  type="text" onKeyUp="tipo_descuento($('#tipo_op'),$('#costo<?php echo $i ?>'),$('#pvp<?php echo $i ?>'),$('#util<?php echo $i ?>'),$('#iva<?php echo $i ?>'),'<?php echo $redondear_pvp_costo ?>',<?php echo $i ?>);tot();"  onChange="(<?php echo $i ?>);"></td>

                <!-- util -->
                <td class="art<?php echo $i ?>" align="center" valign="top"><input class="art<?php echo $i ?> fc_uti" name="util<?php echo $i ?>" id="util<?php echo $i ?>" value="<?php echo $uti*1 ?>"  type="text" onKeyUp="tipo_descuento($('#tipo_op'),$('#costo<?php echo $i ?>'),$('#pvp<?php echo $i ?>'),$('#util<?php echo $i ?>'),$('#iva<?php echo $i ?>'),'<?php echo $redondear_pvp_costo ?>',<?php echo $i ?>);tot();"  onChange="(<?php echo $i ?>);"></td>

                <!-- iva -->
                <td class="art<?php echo $i ?>" align="center" valign="top"><input onkeyup="tipo_descuento($('#tipo_op'),$('#costo<?php echo $i ?>'),$('#pvp<?php echo $i ?>'),$('#util<?php echo $i ?>'),$('#iva<?php echo $i ?>'),'<?php echo $redondear_pvp_costo ?>',<?php echo $i ?>);tot();" class="art<?php echo $i ?> fc_iva" name="iva<?php echo $i ?>" id="iva<?php echo $i ?>" value="<?php echo $Iva ?>"  type="text" onChange="(<?php echo $i ?>);"></td>

                <!-- pvp -->
                <td class="art<?php echo $i ?>" align="center" valign="top"><input class="art<?php echo $i ?> fc_pvp" name="pvp<?php echo $i ?>" id="pvp<?php echo $i ?>" value="<?php echo $pvp ?>" type="text" onKeyUp="tipo_descuento($('#tipo_op'),$('#costo<?php echo $i ?>'),$('#pvp<?php echo $i ?>'),$('#util<?php echo $i ?>'),$('#iva<?php echo $i ?>'),'<?php echo $redondear_pvp_costo ?>',<?php echo $i ?>);puntoa($(this));tot();" onBlur="(<?php echo $i ?>);" ></td>

 <?php if($MODULES["PVP_CREDITO"]==1){ ?>
                <!-- pvpCre -->
                <td class="art<?php echo $i ?>" align="center" valign="top"><input class="art<?php echo $i ?> fc_pvp" name="pvpCre<?php echo $i ?>" id="pvpCre<?php echo $i ?>" value="<?php echo $pvpCre ?>" type="text" onKeyUp="puntoa($(this));tot();" onBlur="(<?php echo $i ?>);" ></td>
 <?php } ?>

  <?php if($MODULES["PVP_MAYORISTA"]==1){ ?>

                <!-- pvpMayorista -->
                <td class="art<?php echo $i ?>" align="center" valign="top"><input class="art<?php echo $i ?> fc_pvp" name="pvpMay<?php echo $i ?>" id="pvpMay<?php echo $i ?>" value="<?php echo $pvpMay ?>" type="text" onKeyUp="puntoa($(this));tot();" onBlur="(<?php echo $i ?>);" ></td>
 <?php } ?>


                <!-- val_tot -->
                <td class="art<?php echo $i ?>" align="center" valign="top"><input class="art<?php echo $i ?> fc_stot" name="v_tot<?php echo $i ?>" id="v_tot<?php echo $i ?>" value="<?php echo $s_tot ?>" type="text" onKeyUp="/*add_row_com($(this),'<?php echo $i ?>');*/" readonly ></td>

                <!-- img BORRAR -->
              <!--   <td class="art<?php echo $i?>" style="background-color: rgb(255, 255, 255);"><?php echo "<span style=\"color:red\">$id</span>" ?><img onMouseUp="eli_fac_com($(this).prop('class'))" class="<?php echo $i ?>" src="Imagenes/delete.png" width="20px" heigth="20px"></td> -->
              </tr>
              <script language="javascript1.5" type="text/javascript">
cont++;
ref_exis++;
$('#num_ref').prop('value',cont);
$('#exi_ref').prop('value',ref_exis);
</script>
              <?php
$i++;
		}////// FIN TIMEOUT VALIDATION
}
$n_ref=$i;
/*
$totalPag = ceil($total/$limit);
         $links = array();
		 $sig=$pag+1;
		 if($sig>$totalPag)$sig=$pag;
		 $ant=$pag-1;
		 if($ant<1)$ant=$pag;
         for( $i=1; $i<=$totalPag ; $i++)
         {
            $links[] = "<a href=\"?pag=$i\">$i</a>";
         }
		 */
?>
            </tbody>
            </table></td>
        </tr>
        <tr class=" uk-text-large uk-block-default uk-text-bold">
        <td colspan="3"><h1 class="uk-text-primary">REFERENCIAS: <span class="uk-text-danger uk-text-bold"><?php echo "$total"; ?></span></h1></td>
        <td colspan="3"><h1 class="uk-text-primary">PRODUCTOS: <span id="totCantSpan" class="uk-text-danger uk-text-bold"><?php echo $totCant; ?></span></h1></td>

        </tr>
        <tr>
          <td  colspan="20"><?php include("PAGINACION.php"); ?>
      </td>
      </tr>
      <?php


?>
      <tr>
        <!-- <td colspan="14" align="center"><button id="addplus" class=" uk-button uk-button-primary uk-button-large uk-width-1-1 " onClick="addinv_mod();" type="button"><i class="uk-icon-plus-circle uk-icon-large"></i>&nbsp;Agregar Producto</button></td> -->
      </tr>
      <tr>
        <td colspan="14" align="center"></td>
      </tr>
      <tr id="desc">
        <td colspan="3" rowspan="11" align="left" width="700px" ><textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="40" style="width:400px" class=""><?php echo $val_letras ?></textarea>
          <br />
        <textarea class="save_fc" name="nota_fac" id="nota_fac"   cols="40" rows="6" placeholder="NOTAS" style="width:200px;-webkit-border-radius:19px;-moz-border-radius:19px;border-radius:19px;border:6px solid rgb(201, 38, 38);"><?php echo "$NOTA" ?></textarea>

          <br /></td>
        <th width="100px">VALOR TOTAL:</th>
        <td align="right"><input  id="SUB" type="text" name="SUBTOT" value="<?php echo money($subtot*1) ?>"  readonly  class="save_fc"/></td>
      </tr>
      <tr style="background-color:#09F">
        <th  align="center" colspan="">Dcto ANTES IVA: </th>
        <td align="right"><input name="DESCUENTO" id="DESCUENTO"   type="text" value="<?php echo money($descuento*1) ?>"  class="save_fc" onKeyUp="puntoa($(this));" onBlur="dcto_antes();"/></td>
      </tr>
      <tr style="background-color: #9C0">
        <th  align="center" colspan="" width="200px">Dcto DESPUES IVA:
          <input placeholder="%" id="R_FTE_PER" type="text"  value="" name="R_FTE_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#DESCUENTO2'));"/></th>
        <td align="right"><input name="DESCUENTO2" id="DESCUENTO2"   type="text" value="<?php echo $descuento2 ?>"  onKeyUp="tot();" class="save_fc"/></td>
      </tr>
      <tr>
        <th  align="center" colspan="">VALOR FLETES(sin IVA): </th>
        <td align="right"><input name="FLETE" id="FLETE"   type="text" value="<?php echo money($flete*1) ?>"  onKeyUp="puntoa($(this));tot();" onBlur="nan($(this))" class="save_fc"></td>
      </tr>
      <tr style="background-color: #FF0">
        <th  align="center" colspan=""><b>%</b> FLETE(Sumar a costo): </th>
        <td align="right"><input name="per_flete" id="per_flete"   type="text" value="<?php echo money($perFlete*1) ?>"  onKeyUp="" onBlur="nan($(this))" class="save_fc"></td>
      </tr>
      <tr>
        <th  align="center" colspan="">I.V.A TOTAL: </th>
        <td align="right"><input name="IVA" id="IVA"   type="text" value="<?php echo money($iva*1) ?>"  readonly class="save_fc"/></td>
      </tr>
      <tr>
        <th  align="center" colspan="">TOTAL:</th>
        <td colspan="" align="right"><input id="TOTAL" type="text"  name="TOTAL" value="<?php echo money($tot*1) ?>"  readonly class="save_fc"/></td>
      </tr>
      <tr>
        <td  align="right" colspan="">R. FTE:
          <input placeholder="%" id="R_FTE_PER" type="text"  value="" name="R_FTE_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_FTE'));" class="save_fc"/></td>
        <td colspan="" align="right"><input id="R_FTE" type="text"  value="<?php echo money($R_FTE*1) ?>" name="R_FTE" class="save_fc" /></td>
      </tr>
      <tr class="uk-hidden">
        <td  align="right" colspan="">R. IVA:
          <input placeholder="%" id="R_IVA_PER" type="text"  value="" name="R_IVA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#IVA'),$('#R_IVA'));" class="save_fc"/></td>
        <td colspan="" align="right"><input id="R_IVA" type="text"  value="<?php echo money($R_IVA*1) ?>" name="R_IVA"  class="save_fc"/></td>
      </tr>
      <tr>
        <td  align="right" colspan="">R. ICA:
          <input placeholder="%" id="R_ICA_PER" type="text"  value="" name="R_ICA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_ICA'));" class="save_fc"/></td>
        <td colspan="" align="right"><input id="R_ICA" type="text"  value="<?php echo money($R_ICA*1) ?>" name="R_ICA" class="save_fc"/></td>
      </tr>
      <tr>
        <th  align="center" colspan="">VALOR A PAGAR:</th>
        <td colspan="" align="right"><input id="TOTAL_PAGAR" type="text" value="<?php echo money($TOT_PAGAR*1) ?>"  name="TOTAL_PAGAR"  readonly class="save_fc"/></td>
      </tr>

      <tr>
        <td colspan="14" align="center"></td>
      </tr>
      <tr>
        <td colspan="14"><table align="center">
            <tr>
              <td colspan="" align="center"><input style="visibility:hidden" type="hidden" value="1" name="num_art" id="num_art" class="save_fc"></td>
              <td></td>
            </tr>
            <tr valign="middle">
              <td><button type="button"  name="botonG" id="botonSave"   onMouseUp="save_fc(-1);" class=" uk-button uk-button-large"><i class=" uk-icon-floppy-o"></i>Guardar</button></td>
              <td><!-- <button type="button" name="botonG" id="botonSave"   onMouseUp="close_fc(-1);" class=" uk-button uk-button-large"><i class=" uk-icon-minus-square"></i>CERRAR</button> --></td>
              <td><?php
			  if($tipo_fac!="Traslado"){
			  ?>
                <button  type="button"  id="btn2" onMouseDown="javascript:location.assign('compras.php');" class="uk-button uk-button-large"><i class=" uk-icon-history"></i>Volver</button>
                <?php
			  }
			  else{
			  ?>
                <button  type="button"  id="btn2" onMouseDown="javascript:location.assign('traslados.php');" class="uk-button uk-button-large"><i class=" uk-icon-history"></i>Volver</button>
                <?php
			  }
			  ?></td>
            </tr>
          </table></td>
      </tr>
      </tbody>
      </table>
    </div>
    <div id="Qresp"> </div>

    <input type="hidden" name="filtro_rep" value="<?php echo $filtro_rep ?>" id="filtro_rep"  class="save_fc"/>
    <input type="hidden" name="num_ref" value="<?php echo $n_ref ?>" id="num_ref"  class="save_fc"/>
    <input type="hidden" name="exi_ref" value="<?php echo $i ?>" id="exi_ref" />
    <input type="hidden" name="verify" id="verify" value="">
    <input type="hidden" value="" name="html_antes" id="HTML_antes" class="save_fc">
    <input type="hidden" value="" name="html_despues" id="HTML_despues" class="save_fc">
    <input type="hidden" name="tipo_op" value="pvp" id="tipo_op" />
    </form>
    <?php include_once("js_global_vars.php"); ?>
    <?php include_once("FOOTER2.php"); ?>

<?php include_once("lib_compras.php"); ?>
<?php include_once("keyFunc_fac_com.php");

$rs=null;
$stmt=null;
$linkPDO= null;
?>
<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/chosen.jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/docsupport/prism.js"></script>
<script language="javascript" type="text/javascript" src="JS/num_letras.js"></script>
<script type="text/javascript">
document.body.style.zoom="90%";
var filterRep=0;
$('#loader').hide();
if($('#filtro_rep').lenght!=0)
{
	//alert($('#filtro_rep').val());
filterRep=$('#filtro_rep').val();
}

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
$('body').ajaxStart(function(){

	$('input[type=button]').prop("disabled","disabled").css("color","red");
$('input[type=button]').removeAttr("disabled").css("color","black");
//alert("putas Harry, putas!");

		})

.ajaxSuccess(function(){
	$('input[type=button]').removeAttr("disabled").css("color","black");


		})

.ajaxError(function(){$('input[type=button]').prop("disabled","disabled").css("color","red");$(this).hide();});



/** Start of calculate products quantity live **/

	$('#articulos').on('change',".fc_cant",function() {
		var cantElements = $(".fc_cant").toArray();
		var totCantTemp = 0;

			for(var a=1;a<cantElements.length;a++){
				if(cantElements[a] != null)
				{
					totCantTemp = parseInt(totCantTemp) + parseInt(cantElements[a].value);
				}
			}
		  $("#totCantSpan").html(totCantTemp);

	  });

	$('#articulos').on("keydown","input,textarea,select", function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		var yMovement = $("#articulos > tbody > tr:eq(1) input:visible, #articulos > tbody > tr:eq(1) select:visible, #articulos > tbody > tr:eq(1) textarea:visible").toArray().length;

        if(key == 39) {
            e.preventDefault();
            var inputs = $(this).parents('#articulos').find(':input:enabled:visible:not("disabled"),textarea,select');

            inputs.eq( inputs.index(this)+ 1 ).focus();
            inputs.eq( inputs.index(this)+ 1 ).select();
        }else if(key == 37) {
            e.preventDefault();
            var inputs = $(this).parents('#articulos').find(':input:enabled:visible:not("disabled"),textarea,select');

            if(inputs.eq( inputs.index(this)- 1 )[0].className.includes("fc_stot")){
                inputs.eq( inputs.index(this)- 2 ).focus();
                inputs.eq( inputs.index(this)- 2 ).select();
            }
            else{
                inputs.eq( inputs.index(this)- 1 ).focus();
                inputs.eq( inputs.index(this)- 1 ).select();
            }
        }else if(key == 38) {
            e.preventDefault();
            var inputs = $(this).parents('#articulos').find(':input:enabled:visible:not("disabled"),textarea,select');

            inputs.eq( inputs.index(this)- yMovement ).focus();
            inputs.eq( inputs.index(this)- yMovement ).select();
        }else if(key == 40) {
            e.preventDefault();
            var inputs = $(this).parents('#articulos').find(':input:enabled:visible:not("disabled"),textarea,select');

            inputs.eq( inputs.index(this)+ yMovement ).focus();
            inputs.eq( inputs.index(this)+ yMovement ).select();
        }
    });

  /** End of calculate products quantity live **/

    /** Default values set near the end of the declaration of the function 'addinv_mod()' right after the replacement of the clase input for a select **/

    /** Start of hiding undesired inputs **/

    function hideFieldsPahoy(){
		var a=0;

		if(a == 1)
		{
	   $("#articulos > tbody > tr:eq(0) > td:eq(4)").addClass("hideComprasTd");
        $("input[name^='fracc']").parent().addClass("hideComprasTd");

        $("#articulos > tbody > tr:eq(0) > td:eq(5)").addClass("hideComprasTd");
        $("input[name^='unidades']").parent().addClass("hideComprasTd");

        $("#articulos > tbody > tr:eq(0) > td:eq(7)").addClass("hideComprasTd");
        $("input[name^='ubicacion']").parent().addClass("hideComprasTd");

        $("#articulos > tbody > tr:eq(0) > td:eq(8)").addClass("hideComprasTd");
        $("input[name^='presentacion']").parent().addClass("hideComprasTd");

        $("#articulos > tbody > tr:eq(0) > td:eq(13)").addClass("hideComprasTd");
        $("input[name^='util']").parent().addClass("hideComprasTd");

            /* Hide references count */
        $("#tab_compra > tbody > tr:eq(2) > td:eq(0) > h1").addClass("hideComprasTd");

            /* Hide Dcto ANTES IVA,Dcto DESPUES IVA,VALOR FLETES(sin IVA),% FLETE(Sumar a costo) */
        $("#tab_compra > tbody > tr:eq(7)").children().addClass("hideComprasTd");
        $("#tab_compra > tbody > tr:eq(8)").children().addClass("hideComprasTd");
        $("#tab_compra > tbody > tr:eq(9)").children().addClass("hideComprasTd");
        $("#tab_compra > tbody > tr:eq(10)").children().addClass("hideComprasTd");

            /* Hide retenciones */
        $("#tab_compra > tbody > tr:eq(13)").children().addClass("hideComprasTd");
        $("#tab_compra > tbody > tr:eq(14)").children().addClass("hideComprasTd");
        $("#tab_compra > tbody > tr:eq(15)").children().addClass("hideComprasTd");

            /* Hide Valor Pagar -- doen't update live -- */
        $("#tab_compra > tbody > tr:eq(16)").children().addClass("hideComprasTd");
		}
    }


  hideFieldsPahoy();

  /** End hiding undesired inputs || Call this function near the end of the function 'addinv_mod()' right after the declaration of the default values **/

  /**
   * All blocks of code were added to the head of this file, the end of this file and at the end of the declaration of the addinv_mod() function
   * in fac_com.js, also 2 classes (fc_codBarras and fc_cant) had their background-color property commentated in fac_ven.css
   */

   /** Add readonly to all producs inputs **/

	$('#articulos input,#articulos textarea').prop('readonly', true);

  /** End of add readonly **/

  </script>
</body>
</html>
