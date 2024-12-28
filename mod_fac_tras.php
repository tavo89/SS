p´ñ{<?php
require_once('Conexxx.php');
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"compras_mod")){header("location: centro.php");}
valida_session();
$feVenRep=r("feVenR");
$pag="";
$limit = 50;
$url="mod_fac_tras.php";

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


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<?php require_once("HEADER.php"); ?>
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<script language="javascript1.5" type="text/javascript" src="JS/jquery-1.11.3.min.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script>
<script language='javascript' src="JS/fac_com.js?<?php echo "$LAST_VER" ?>"></script>
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>	


<div class="uk-width-9-10 uk-container-center">

<div id="sb-search" class="sb-search">
						<form id="frm_search">
							<input class="sb-search-input" placeholder="Ingrese su b&uacute;squeda..." type="text" value="" name="busq" id="search">
							<input class="sb-search-submit" type="submit" value="Buscar" name="opc">
							<span class="sb-icon-search"></span>
						</form>
					</div>

<form action="mod_fac_tras.php" name="form_fac" method="post" id="form_fac" class="uk-form" autocomplete="off">
  <input type="text" style="visibility: hidden" name="boton" id="boton" value="" >
  <?php
//$sql = "SELECT  $columns FROM fac_com  WHERE  cod_su=$codSuc ORDER BY $order DESC   LIMIT $offset, $limit"; 
$sql="SELECT  * FROM fac_tras WHERE num_fac_com='".$_SESSION['num_fac']."' AND cod_su='$codSuc' AND nit_pro='".$_SESSION['nit_pro']."'";


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

$subtot=$row['subtot'];
$descuento=$row['descuento'];
$descuento2=$row['dcto2'];
$iva=$row['iva'];
$flete=$row['flete'];
$tot=$row['tot'];
$val_letras=$row['val_letras'];
$num_fac_com=$row['serial_fac_com'];

$R_FTE=$row['r_fte'];
$R_IVA=$row['r_iva'];
$R_ICA=$row['r_ica'];
$TOT_PAGAR=$tot-($R_FTE+$R_ICA+$R_IVA);
	
}
?>
 <div id="salida">
 </div>
  <div class="loader"> <img id="loader" src="Imagenes/ajax-loader.gif" width="131" height="131" /> </div>
  <div id="fac_com">
<table frame="box" align="left" cellspacing="0" class="round_table_gray" id="tab_compra">
<tr>
<td height="80" colspan="5" align="center">

<table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
<tr>
<td width="450px"><div align="center"><img src="<?php echo $url_LOGO_B ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>"> </div>        
</td>
<td align="left" colspan="">
            <?php  echo $PUBLICIDAD  ?></td>
            <td align="center" style="font-size:18px; "><B>FACTURA DE COMPRA</B><br>
              <span style="color:#F00"># <?php echo $num_fac_com ?></span></td>
          </tr>
          
</table>
</td>
    </tr>
    <tr>
      <td colspan="14">
      
      <table frame="box" rules="rows" width="100%">
          <tr>
            <td  align="left" colspan="3">
            
            <table align="left" cellspacing="0">
                <tr>
                  <td align="right"  colspan="2">Fecha:</td>
                  <td  colspan="" >
                  <input  id="fecha2" type="date" value="<?php echo $fecha ?>" name="fecha"  class="save_fc" style="width:150px;" />
                  </td>
                  <td colspan="2" align="right">
                  <span style="color:#F00; font-size:24px;"><strong>No. </strong>&nbsp;&nbsp;&nbsp;&nbsp;
                  </span>
                  </td>
                  <td>
                    <input name="num_fac" type="text" id="num_fac" value="<?php echo $num_fac ?>" onChange="val_mod_fc($('#num_fac').val(),$('#nit').val(),'fac_com','num_fac_com','nit_pro',$('#resp'),'<?php echo $nit_proH ?>','<?php echo $num_facH?>');" onBlur="//nan($(this))" class="save_fc" style="width:120px;"/>
                    </td>
                    
                  <td>
                  <div id="resp" style="visibility:hidden; color: #F00; width:180px;"><img alt="" src="Imagenes/delete.png" width="20" height="20" />Este n&uacute;mero ya existe</div>
                 </td>
                </tr>
                <tr>
                <td colspan="2">
                Fecha Vencimiento:
                </td>
                <td>
                <input  id="fechaVen" type="date" value="<?php echo $fechaVen ?>" name="fechaVen"  class="save_fc" style="width:150px;" />
                </td>
                </tr>
                <tr>
                <td colspan="2">Costos y Pvp: </td>
             <td>
             <input type="hidden" value="" name="CNC" id="CNC" onChange="useDB($(this));">
             <!--<select name="CNC" id="CNC" onChange="useDB($(this));">
<option value="1" >Usar</option>
<option value="0" selected>NO usar</option>
</select>-->
<select name="tipo_op" id="tipo_op" onChange="">
<option value=""  selected>CALCULAR</option>
<option value="ganancia" >Ganancia</option>
<option value="costo" >Costo</option>
<option value="pvp" >PVP</option>
</select>
             </td>
                </tr>
</table>
 </td>
           
            <td colspan="" style="font-size:18px">Promediar Costos:</td>
            <td colspan="">
            <select name="confirma" class="save_fc" id="confirma">
                <option value="" ></option>
                <option value="Si" >Si</option>
                <option value="No" selected>No</option>
              </select></td>
            <td colspan="" style="font-size:18px">Tipo Facturaci&oacute;n:</td>
            <td colspan=""><select name="tipo_fac" style="width:150PX" class="save_fc" id="tipo_fac" onChange="cambioTFC($(this),$('#tab_compra'));">
                <option value="<?php echo $tipo_fac ?>"  selected><?php echo $tipo_fac ?></option>
                <option value="Compra" >Compra</option>
                 <option value="Remision" >Remision</option>
                 <option value="Traslado" >TRASLADO</option>
                <option value="Inventario Inicial" >Inventario Inicial</option>
              </select></td>
          </tr>
          <tr>
            <td width="">Proveedor:</td>
            <td colspan="2">
            <table border="0">
                <tr>
                  <td><input  name="provedor" type="text" id="provedor" value="<?php echo $provedor ?>" style="width:250px" class="save_fc"/></td>
                  <td><select name="n_pro" id="n_pro" onChange="nom_pro(this);val_mod_fc($('#num_fac').val(),$('#nit').val(),'fac_com','num_fac_com','nit_pro',$('#resp'),'<?php echo $nit_proH ?>');" >
                      <option value="" selected>Provedores Usuales</option>
                      <?php
		    $rs=$linkPDO->query("SELECT * FROM provedores ORDER BY nom_pro");
			while($row=$rs->fetch()){
			?>
                      <option value="<?php echo $row['nit'] ?>"><?php echo $row['nom_pro'] ?></option>
                      <?php
			}
			?>
                    </select></td>
                </tr>
              </table>
              </td>
              
            <td>NIT:</td>
            <td><input   style="width:100px;" name="nit" type="text" id="nit" value="<?php echo $nit_pro ?>" onChange="pro(this.value);" class="save_fc"/></td>
            <td align="">Ciudad:</td>
            <td colspan=""><input style="width:200px" name="ciudad" type="text" id="ciudad" value="<?php echo $ciudad ?>" class="save_fc"/></td>
            
          </tr>
          <tr>
            <td >Direcci&oacute;n:</td>
            <td ><input style="width:200px" name="dir" type="text" value="<?php echo $dir ?>" id="dir" class="save_fc"/></td>
            <td>Tel.:</td>
            <td colspan=""><input style="width:100px" name="tel"  type="text" value="<?php echo $tel ?>" id="tel" class="save_fc" /></td>
            <td>Fax:</td>
            <td align=""><input style="width:80px" name="fax" type="text" id="fax" value="<?php echo $fax ?>" onChange="//javascript:valida_doc('cliente',this.value);" class="save_fc"/></td>
            <td colspan="">&nbsp;&nbsp;&nbsp;Mail:
              <input style="width:150px" name="mail" type="text" id="mail" value="<?php echo $mail ?>" class="save_fc"></td>
          </tr>
          <!--
          <tr>
          <td>Calcular en productos:</td>
          <td>
             <select name="tipo_op" id="tipo_op" onChange="tipo_descuento($('#tipo_op'),$('#costo'),$('#pvp'),$('#ganancia'),$('#iva'),'<?php echo $redondear_pvp_costo ?>'  );">

<option value="ganancia"  selected>Ganancia</option>
<option value="costo" >Costo</option>
<option value="pvp" >PVP</option>
</select>
             </td>
          </tr>
          -->
        </table></td>
        
    </tr>
    
    
    <tr>
      <td colspan="14">
      <table id="articulos" width="100%" cellspacing="0" cellpadding="0" frame="box" rules="cols" style="border-width:1px" class="tabla_inv">
          <tr>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>#</strong></div></td>
<td colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Referencia</strong></div></td>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Cod. Barras</strong></div></td>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Cant.</strong></div></td>
<!--<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Cod.</strong></div></td>-->

<?php if($usar_fracciones_unidades==1){ ?>
<td>
<div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Fracc.</strong></div>
</td>
<td>
<div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Uni.</strong></div>
</td>
<?php } ?>

<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>&nbsp;Descripci&oacute;n&nbsp;</strong></div></td>
<?php if($usar_color==1){ ?>
<td>
<div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Color</strong></div>
</td>
<?php } ?>

<?php if($usar_talla==1){ ?>
<td>
<div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Talla</strong></div>
</td>
<?php } ?>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Presentaci&oacute;n</strong></div></td>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Fabricante</strong></div></td>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Clase</strong></div></td>

<?php
if($usar_fecha_vencimiento==1){
?>
<td><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Fecha Vencimiento</strong></div></td>

<?php
}
?>

<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Costo</strong> sin IVA</div></td>
<td  colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Dcto.(%)</strong></div></td>

<td  colspan=""><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>Util.(%)</strong></div></td>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>IVA (%)</strong></div></td>
<td ><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong>PVP</strong></div></td>
<td width="90px"  colspan="2"><div align="center" style=" border-bottom-style:solid; border-bottom-width:1px"><strong> Total</strong></div></td>
 </tr>
 

 <?php
$sql="SELECT  * FROM art_fac_tras WHERE num_fac_com='$num_fac' AND cod_su=$codSuc AND nit_pro='$nit_pro' ORDER BY cant,des ASC LIMIT $offset, $limit";
//$sql="SELECT  * FROM art_fac_tras WHERE num_fac_com='$num_fac' AND cod_su=$codSuc AND nit_pro='$nit_pro' ORDER BY ref";

/////////////////////////////////////////// PAGINACION PARTE 2/////////////////////////////////////////// 
$sqlTotal = "SELECT COUNT(*) as total FROM art_fac_tras WHERE cod_su='$codSuc'"; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"];
//////////////////////////////////////////////////////////////////////////////



$rs=$linkPDO->query($sql);
$boton="";
$busq="";
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['opc']))$boton= $_REQUEST['opc'];

if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT * FROM art_fac_tras WHERE num_fac_com='$num_fac' AND cod_su=$codSuc AND nit_pro='$nit_pro' AND (cod_barras = '$busq' OR des LIKE '$busq%' OR ref='$busq')";

//echo "$sql_busq";

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
		$s_tot=money($row['tot']*1);
		if($cant<=0 )$rgb="#990000";
		
		if($busq==$cod_bar && $feVenRep==$row['fecha_vencimiento'])$rgb="#000000";
?>
          <tr bgcolor="<?php echo $rgb ?>" tabindex="0" id="tr<?php echo $i ?>" onClick="click_ele(this);" onBlur="resetCss(this);">
            <td class="art<?php echo $i ?>" valign="top"><?php echo "<b>$No</b>" ?></td>
            
            
            
            <!-- ref -->
              
            <td class="art<?php echo $i ?>" align="center" valign="top" colspan="2">
            <input onkeyup="//cod(this,'add',0,0)" class="art<?php echo $i ?>" name="ref<?php echo $i ?>" id="ref<?php echo $i ?>" value="<?php echo $ref ?>" style="width:80px;top:10px" type="text"   onChange="save_fc(<?php echo $i ?>);dup_cam($('#ref<?php echo $i ?>'),$('#refH<?php echo $i ?>'));">
            <input   style="width:30px" class="art<?php echo $i ?>" name="refH<?php echo $i ?>" id="refH<?php echo $i ?>" value="<?php echo $ref ?>" type="hidden" readonly>
            </td>
            
            
            <!-- cod Barras -->
            <td class="art<?php echo $i ?>" align="center" valign="top" colspan="">
            <input onkeyup="" class="art<?php echo $i ?>" name="cod_bar<?php echo $i ?>" id="cod_bar<?php echo $i ?>" value="<?php echo $cod_bar?>" style="width:80px;top:10px" type="text" onChange="save_fc(<?php echo $i ?>);dup_cam($('#cod_bar<?php echo $i ?>'),$('#cod_barH<?php echo $i ?>'));"  >
            
            <input   style="width:50px" class="art<?php echo $i ?>" name="cod_barH<?php echo $i ?>" id="cod_barH<?php echo $i ?>" value="<?php echo $cod_bar ?>" type="hidden" readonly>
            </td>
            
    
    
    	<!-- cant -->
  		 <td class="art<?php echo $i ?>" align="center" valign="top">
<input style="width:40px" class="art<?php echo $i ?>" name="cant<?php echo $i ?>" id="cant<?php echo $i ?>" value="<?php echo $cant ?>" onkeyup="calc_uni($('#cant<?php echo $i ?>'),$('#fracc<?php echo $i ?>'),$('#unidades<?php echo $i ?>'));tot();" type="text" onBlur="cant_dcto_com($('#tipo_dcto<?php echo $i ?>'),$('#dct<?php echo $i ?>'),$('#cant<?php echo $i ?>'));save_fc(<?php echo $i ?>);">
              <input   style="width:30px" class="art<?php echo $i ?>" name="cantH<?php echo $i ?>" id="cantH<?php echo $i ?>" value="<?php echo $cant ?>" onkeyup="tot();" type="hidden" readonly>
              </td>
  
  
  <?php if($usar_fracciones_unidades==1){ ?>
<!-- fraccion -->
  <td class="art<?php echo $i ?>" align="center" valign="top" colspan="">
            <input onkeyup="calc_uni($('#cant<?php echo $i ?>'),$('#fracc<?php echo $i ?>'),$('#unidades<?php echo $i ?>'));tot();" class="art<?php echo $i ?>" name="fracc<?php echo $i ?>" id="fracc<?php echo $i ?>" value="<?php echo $row['fraccion']?>" style="width:40px;top:10px" type="text" onBlur="save_fc(<?php echo $i ?>);"></td>
            


<td class="art<?php echo $i ?>" align="center" valign="top" colspan="">
            <input onkeyup="calc_cant($('#cant<?php echo $i ?>'),$('#fracc<?php echo $i ?>'),$('#unidades<?php echo $i ?>'));tot();" class="art<?php echo $i ?>" name="unidades<?php echo $i ?>" id="unidades<?php echo $i ?>" value="<?php echo $row['unidades_fraccion'];?>" style="width:40px;top:10px" type="text" onChange="save_fc(<?php echo $i ?>);"></td>
<?php } ?>  
   
   
<!-- descripcion -->
<td class="art<?php echo $i ?>" align="center" valign="top">
<textarea style=" width:150px;" class="art<?php echo $i ?>" name="des<?php echo $i ?>" id="des<?php echo $i ?>" value=""  onChange="save_fc(<?php echo $i ?>);"><?php echo $des ?></textarea>
</td>
            
            
<?php if($usar_color==1){ ?>
<!-- color -->
  <td class="art<?php echo $i ?>" align="center" valign="top" colspan="">
            <input onkeyup="" class="art<?php echo $i ?>" name="color<?php echo $i ?>" id="color<?php echo $i ?>" value="<?php echo $color?>" style="width:40px;top:10px" type="text" onChange="save_fc(<?php echo $i ?>);"></td>
<?php } ?>     



<?php if($usar_talla==1){ ?> 
<!-- talla -->     
    <td class="art<?php echo $i ?>" align="center" valign="top" colspan="">
            <input onkeyup="" class="art<?php echo $i ?>" name="talla<?php echo $i ?>" id="talla<?php echo $i ?>" value="<?php echo $talla?>" style="width:40px;top:10px" type="text"  onChange="save_fc(<?php echo $i ?>);" ></td>
<?php } ?>


<!-- presentacion -->
 <td class="art<?php echo $i ?>" align="center" valign="top" colspan="">
            <input onkeyup="" class="art<?php echo $i ?>" name="presentacion<?php echo $i ?>" id="presentacion<?php echo $i ?>" value="<?php echo $presentacion?>" style="width:80px;top:10px" type="text"  onChange="save_fc(<?php echo $i ?>);" ></td>
            
            
<!-- fabricante -->
    <td class="art<?php echo $i ?>" align="center" valign="top" colspan="">
            <input onkeyup="" class="art<?php echo $i ?>" name="fabricante<?php echo $i ?>" id="fabricante<?php echo $i ?>" value="<?php echo $fabricante?>" style="width:80px;top:10px" type="text"  onChange="save_fc(<?php echo $i ?>);" ></td>
    
    
<!-- clase -->   		        
<td class="art<?php echo $i ?>" align="center" valign="top" colspan="">
<input onkeyup="" class="art<?php echo $i ?>" name="clase<?php echo $i ?>" id="clase<?php echo $i ?>" value="<?php echo $clase?>" style="width:50px;top:10px" type="text"  onChange="save_fc(<?php echo $i ?>);" >
</td>


<?php if($usar_fecha_vencimiento==1){ ?>                        
<!-- fecha vencimiento -->   		        
<td class="art<?php echo $i ?>" align="center" valign="top" colspan="">
<input onkeyup="" class="art<?php echo $i ?>" name="fecha_vencimiento<?php echo $i ?>" id="fecha_vencimiento<?php echo $i ?>" value="<?php echo $row['fecha_vencimiento']?>" style="width:150px;top:10px" type="date"  onBlur="save_fc(<?php echo $i ?>);dup_cam($('#fecha_vencimiento<?php echo $i ?>'),$('#fecha_vencimientoH<?php echo $i ?>'));" >

<input onkeyup="" class="art<?php echo $i ?>" name="fecha_vencimientoH<?php echo $i ?>" id="fecha_vencimientoH<?php echo $i ?>" value="<?php echo $row['fecha_vencimiento']?>" style="width:150px;top:10px" type="hidden" >

</td>
<?php } ?>                      
  
  
  <!-- costo -->                     
  <td class="art<?php echo $i ?>" align="center" valign="top">
            <input class="art<?php echo $i ?>" name="costo<?php echo $i ?>" id="costo<?php echo $i ?>" value="<?php echo $costo ?>" onkeyup="puntoa($(this));tot();tipo_descuento($('#tipo_op'),$('#costo<?php echo $i ?>'),$('#pvp<?php echo $i ?>'),$('#util<?php echo $i ?>'),$('#iva<?php echo $i ?>'),'<?php echo $redondear_pvp_costo ?>');" type="text"  onBlur="save_fc(<?php echo $i ?>);" style="width:90px;"></td>


            
                    
 <!-- dcto -->           
            <td class="art<?php echo $i ?>" align="center" valign="top">
            <input class="art<?php echo $i ?>" name="dcto<?php echo $i ?>" id="dct<?php echo $i ?>" value="<?php echo $dcto*1 ?>" style="width:30px;" type="text" onKeyUp="/*pvp_costo_com($('#pvp<?php echo $i ?>'),$('#dct<?php echo $i ?>'),$('#costo<?php echo $i ?>'),$('#iva<?php echo $i ?>'));*/tot();"  onChange="save_fc(<?php echo $i ?>);"></td>
         
 <!-- util -->        
         <td class="art<?php echo $i ?>" align="center" valign="top">
            <input class="art<?php echo $i ?>" name="util<?php echo $i ?>" id="util<?php echo $i ?>" value="<?php echo $uti*1 ?>" style="width:30px;" type="text" onKeyUp="tipo_descuento($('#tipo_op'),$('#costo<?php echo $i ?>'),$('#pvp<?php echo $i ?>'),$('#util<?php echo $i ?>'),$('#iva<?php echo $i ?>'),'<?php echo $redondear_pvp_costo ?>');tot();"  onChange="save_fc(<?php echo $i ?>);"></td>   
   
   
 <!-- iva -->         
 <td class="art<?php echo $i ?>" align="center" valign="top">
            <input onkeyup="tipo_descuento($('#tipo_op'),$('#costo<?php echo $i ?>'),$('#pvp<?php echo $i ?>'),$('#util<?php echo $i ?>'),$('#iva<?php echo $i ?>'),'<?php echo $redondear_pvp_costo ?>');tot();" class="art<?php echo $i ?>" name="iva<?php echo $i ?>" id="iva<?php echo $i ?>" value="<?php echo $Iva ?>" style="width:30px;" type="text" onChange="save_fc(<?php echo $i ?>);"></td>
          

<!-- pvp -->
<td class="art<?php echo $i ?>" align="center" valign="top">
            <input class="art<?php echo $i ?>" name="pvp<?php echo $i ?>" id="pvp<?php echo $i ?>" value="<?php echo $pvp ?>" type="text" onKeyUp="tipo_descuento($('#tipo_op'),$('#costo<?php echo $i ?>'),$('#pvp<?php echo $i ?>'),$('#util<?php echo $i ?>'),$('#iva<?php echo $i ?>'),'<?php echo $redondear_pvp_costo ?>');puntoa($(this));tot();" onBlur="save_fc(<?php echo $i ?>);" style="width:100px"></td>
   
   
                
            
 <!-- val_tot -->           
<td class="art<?php echo $i ?>" align="center" valign="top"><input class="art<?php echo $i ?>" name="v_tot<?php echo $i ?>" id="v_tot<?php echo $i ?>" value="<?php echo $s_tot ?>" type="text" readonly style="width:90px;"></td>
            
<!-- img BORRAR -->
<td class="art<?php echo $i?>" style="background-color: rgb(255, 255, 255);"><img onMouseUp="eli_fac_com($(this).prop('class'))" class="<?php echo $i ?>" src="Imagenes/delete.png" width="20px" heigth="20px"></td>
          </tr>
<script language="javascript1.5" type="text/javascript">
cont++;
ref_exis++;
$('#num_ref').prop('value',cont);
$('#exi_ref').prop('value',ref_exis);
</script>
          <?php
$i++;
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

?>



        </table>
        </td>
    </tr>
    <tr>
<td  colspan="20">
<?php require("PAGINACION.php"); ?>
</td>
</tr>
    <?php


?>
    <tr>
<td colspan="14" align="center">
<button id="addplus" class=" uk-button uk-button-primary uk-button-large uk-width-1-1 " onClick="addinv_mod();" type="button"><i class="uk-icon-plus-circle uk-icon-large"></i>&nbsp;Agregar Producto</button>
</td>
    </tr>
    <tr>
      <td colspan="14" align="center">
      </td>
    </tr>
    <tr id="desc">
      <td colspan="3" rowspan="10" align="left" width="700px" ><textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="40" style="width:400px" class="save_fc"><?php echo $val_letras ?></textarea>
        <br />
        <br />
        <br />
        <br />
        <br />
        <div align="left"></div></td>
      <th width="100px">VALOR TOTAL:</th>
      <td align="right"><input  id="SUB" type="text" name="SUBTOT" value="<?php echo money($subtot*1) ?>"  readonly  class="save_fc"/></td>
    </tr>
    <tr>
      <th  align="center" colspan="">DESCUENTOS: </th>
      <td align="right"><input name="DESCUENTO" id="DESCUENTO"   type="text" value="<?php echo money($descuento*1) ?>"  readonly class="save_fc"/></td>
    </tr>
    <tr>
      <th  align="center" colspan="">Dcto Pronto Pago: </th>
      <td align="right"><input name="DESCUENTO2" id="DESCUENTO2"   type="text" value="<?php echo $descuento2 ?>" maxlength="2" onKeyUp="tot();" class="save_fc"/></td>
    </tr>
    <tr>
      <th  align="center" colspan="">VALOR FLETES(sin IVA): </th>
      <td align="right"><input name="FLETE" id="FLETE"   type="text" value="<?php echo money($flete*1) ?>"  onKeyUp="puntoa($(this));tot();" onBlur="nan($(this))" class="save_fc"></td>
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
          <td  align="right" colspan="">R. FTE:<input placeholder="%" id="R_FTE_PER" type="text"  value="" name="R_FTE_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_FTE'));" class="save_fc"/></td>
          <td colspan="" align="right"><input id="R_FTE" type="text"  value="<?php echo money($R_FTE*1) ?>" name="R_FTE" class="save_fc" />
          </td>
          </tr>
          
          <tr>
          <td  align="right" colspan="">R. IVA:<input placeholder="%" id="R_IVA_PER" type="text"  value="" name="R_IVA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#IVA'),$('#R_IVA'));" class="save_fc"/></td>
          <td colspan="" align="right"><input id="R_IVA" type="text"  value="<?php echo money($R_IVA*1) ?>" name="R_IVA"  class="save_fc"/>
          </td>
          </tr>
          
          <tr>
          <td  align="right" colspan="">R. ICA:<input placeholder="%" id="R_ICA_PER" type="text"  value="" name="R_ICA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_ICA'));" class="save_fc"/></td>
          <td colspan="" align="right"><input id="R_ICA" type="text"  value="<?php echo money($R_ICA*1) ?>" name="R_ICA" class="save_fc"/>
          </td>
          </tr>
           <tr>
      <th  align="center" colspan="">VALOR A PAGAR:</th>
      <td colspan="" align="right"><input id="TOTAL_PAGAR" type="text" value="<?php echo money($TOT_PAGAR*1) ?>"  name="TOTAL_PAGAR"  readonly class="save_fc"/></td>
    </tr>
    <tr>
      <td colspan="14"><table align="center">
          <tr>
            <td colspan="" align="center">
            <input style="visibility:hidden" type="hidden" value="1" name="num_art" id="num_art" class="save_fc">
            </td>
            <td></td>
          </tr>
          <tr valign="middle">
       <td>
      
      <button type="button"  name="botonG" id="botonSave"   onMouseUp="save_fc(-1);" class=" uk-button uk-button-large"><i class=" uk-icon-floppy-o"></i>Guardar</button>
      </td>
       <td>
     
      
       <button type="button" name="botonG" id="botonSave"   onMouseUp="close_fc(-1);" class=" uk-button uk-button-large"><i class=" uk-icon-minus-square"></i>CERRAR</button>
      </td>
            <td>
            <button  type="button"  id="btn2" onMouseDown="javascript:location.assign('compras.php');" class="uk-button uk-button-large"><i class=" uk-icon-history"></i>Volver</button>
            </td>
          </tr>
        </table>
        </td>
    </tr>
    <tr>
      <td colspan="14" align="center"></td>
    </tr>

  </div>
  <div id="Qresp"> </div>
  <input type="hidden" name="num_ref" value="<?php echo $n_ref ?>" id="num_ref"  class="save_fc"/>
  <input type="hidden" name="exi_ref" value="<?php echo $i ?>" id="exi_ref" />
  <input type="hidden" name="verify" id="verify" value="">
  
      <input type="hidden" value="" name="html_antes" id="HTML_antes" class="save_fc">
    <input type="hidden" value="" name="html_despues" id="HTML_despues" class="save_fc">
      <input type="hidden" name="tipo_op" value="pvp" id="tipo_op" />
</form>
<?php require_once("js_global_vars.php"); ?>
<?php require_once("FOOTER.php"); ?>
	
<?php require_once("lib_compras.php"); ?>
<?php require_once("keyFunc_fac_com.php"); ?>
</body>
</html>