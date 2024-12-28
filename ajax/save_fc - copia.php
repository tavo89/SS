<?php
require_once("../Conexxx.php");
$num_facH=0;
$nit_proH="";
$PKrowForm="cod_bar";
$PKrowQuery="cod_barras";
$respuestaProceso =1;
if(isset($_SESSION['num_fac'])){$num_fac=$_SESSION['num_fac'];$num_facH=$_SESSION['num_fac'];}
if(isset($_SESSION['nit_pro'])){$nit_pro=$_SESSION['nit_pro'];$nit_proH=$_SESSION['nit_pro'];}

$ff=0;
if(isset($_REQUEST['ff']))$ff=$_REQUEST['ff'];

$HTML_antes="";
$HTML_despues="";
if(isset($_REQUEST['html_antes']))$HTML_antes=$_REQUEST['html_antes'];
if(isset($_REQUEST['html_despues']))$HTML_despues=$_REQUEST['html_despues'];

$num_fac=r('num_fac');
$fecha=r('fecha');

$provedor=rm('provedor');
$nit_pro=r('nit');
$ciudad=rm('ciudad');
$dir=rm('dir');
$tel=r('tel');
$fax=r('fax');
$mail=r('mail');

$calc_dcto=r("calc_dcto");

$tipo_fac=r('tipo_fac');

$nota_fac=r("nota_fac");



$perFlete=quitacom($_REQUEST['per_flete']);
$subtot=quitacom($_REQUEST['SUBTOT']);
$descuento=quitacom($_REQUEST['DESCUENTO']);
$descuento2=quitacom($_REQUEST['DESCUENTO2']);
$flete=quitacom($_REQUEST['FLETE']);
$iva=quitacom($_REQUEST['IVA']);
$tot=quitacom($_REQUEST['TOTAL']);
$totPagar=quitacom($_REQUEST['TOTAL_PAGAR']);

$R_FTE=quitacom($_REQUEST['R_FTE']);
$R_ICA=quitacom($_REQUEST['R_ICA']);
$R_IVA=quitacom($_REQUEST['R_IVA']);


$CALC_PVP_COST_GANA=r('calc_pvp');

$confirm=r('confirma');
$val_letras=r('vlr_let');
$feVen=r("fechaVen");


$per_flete=($perFlete/100)+1;

try { 
$linkPDO->beginTransaction();

$all_query_ok=true;


$sql="SELECT * FROM fac_com WHERE num_fac_com='$num_facH' AND nit_pro='$nit_proH' AND cod_su=$codSuc FOR UPDATE";
$stmt = $linkPDO->query($sql);
if ($row = $stmt->fetch()) {


 

$sql="INSERT IGNORE INTO provedores (nit,nom_pro,dir,tel,mail,ciudad,fax) VALUES('$nit_pro', '$provedor','$dir','$tel','$mail','$ciudad','$fax')";
if(!empty($nit_pro)){
	
	
	$linkPDO->exec($sql);
	}

//}




//------------------------ARTICULOS------------------------------------------------------------

$iRow=r('i');
$i=r('i');
$color="";
$talla="";
//echo "i: $i -------";
if($iRow=-1)
{
//logDB($sqlLog,$SECCIONES[6],$OPERACIONES[2],$HTML_antes,$HTML_despues,$num_fac);
	}
$NumRef=limpiarcampo($_REQUEST['num_ref']);
//echo "Nr: $NumRef";
if(isset($_REQUEST['num_ref'])&&!empty($_REQUEST['num_ref']))
{
	$NumRef=limpiarcampo($_REQUEST['num_ref']);
	//echo "<br>Nr-IF: $NumRef";
	for($i=0;$i<$NumRef;$i++)
	{
		if($iRow>-1)$i=$iRow;
		//echo "<br>";
		if(isset($_REQUEST['id'.$i])&&!empty($_REQUEST['id'.$i]) )
{		//echo "<br>PKfrm cod_bar";


 
$updateFac="";
$sql="";
$updateInv="";

$linkPDO->exec("SAVEPOINT A");
		$ID=r('id'.$i);
		$cant=limpianum($_REQUEST['cant'.$i]);
		$fracc=limpianum($_REQUEST['fracc'.$i]);
		if(empty($fracc))$fracc=1;
		$uni=limpianum($_REQUEST['unidades'.$i]);

		$cod_bar=rm('cod_bar'.$i);
		$ref=rm('ref'.$i);
		$fechaVenci=r('fecha_vencimiento'.$i);
		if(empty($fechaVenci))$fechaVenci="0000-00-00";
		if(empty($ref))$ref=$cod_bar;
		if(isset($_REQUEST['refH'.$i]))$refH=limpiarcampo(mb_strtoupper($_REQUEST['refH'.$i],"$CHAR_SET"));
			else $refH=$ref;
		
		if(isset($_REQUEST['cod_barH'.$i]))$cod_barH=limpiarcampo($_REQUEST['cod_barH'.$i]);
			else $cod_barH=limpiarcampo($_REQUEST['cod_bar'.$i]);
			
		if(isset($_REQUEST['fecha_vencimientoH'.$i])&& !empty($_REQUEST['fecha_vencimientoH'.$i]))$fechaVenciH=r('fecha_vencimientoH'.$i);
		else $fechaVenciH=$fechaVenci;
			
		$des=rm('des'.$i);
		$des_full=rm('des_full'.$i);
		$clase=rm('clase'.$i);
		$color=rm('color'.$i);
		
		
		
		
		$ubicacion=rm('ubicacion'.$i);
		$talla=rm('talla'.$i);
		$fabricante=rm('fabricante'.$i);
		$clase=rm('clase'.$i);
		$presentacion=rm('presentacion'.$i);
		if(empty($presentacion))$presentacion="UNIDAD";
		$costo=quitacom($_REQUEST['costo'.$i]);
				//echo "<br><span style=\"color: #fff\"<b>$i costo SIN Dcto : $costo</b></span><br>";
		$dcto=limpianum(r('dcto'.$i));
		$tipoD="";//limpiarcampo($_REQUEST['tipo_dcto'.$i]);
		$iva=limpianum(r('iva'.$i));
		$uti=limpianum(r('util'.$i));
		
		$pvp=quitacom(r('pvp'.$i));
		$pvpCre=quitacom(r('pvpCre'.$i));
		$pvpMay=quitacom(r('pvpMay'.$i));
		$s_tot=quitacom(r('v_tot'.$i));
		$aplica_vehi=r('aplica_vehi'.$i);
		$impSaludable=r("impSaludable".$i);
		if(empty($dcto))$dcto=0;
		if(empty($iva))$iva=0;
		$costoDesc=$costo*$per_flete - $costo*($dcto/100);
		if($usar_costo_dcto==1)$costoDesc=$costo*$per_flete - $costo*($dcto/100);
		else $costoDesc=$costo*$per_flete;
		$uti=util($pvp,$costoDesc,$iva,"per",$impSaludable);
		
		$SERIAL=rm('serial_art'.$i);
		$certImport=rm('cert_import'.$i);
		//echo "<br><span style=\"color: #fff\"<b>$i costo con Dcto : $costoDesc</b> UTIL= $uti</span><br>";
		
		$linea=rm('linea'.$i);
		$modelo=rm('modelo'.$i);
		$num_motor=rm('num_motor'.$i);
		$num_chasis=rm('num_chasis'.$i);
		$cilindraje=rm('cilindraje'.$i);
		$consecutivo_proveedor=rm('consecutivo_proveedor'.$i);
		
		$campo_add_01=r("campo_add_01".$i);
		$campo_add_02=r("campo_add_02".$i);
		
		$cod_color=r("cod_color".$i);
		$vigencia_inicial=r("vigencia_inicial".$i);
		$grupo_destino=r("grupo_destino".$i);
		$impuesto_consumo=r("impuesto_consumo".$i);
		

		

if($MAIN_ID_BAR==0){$sql="SELECT * FROM art_fac_com WHERE num_fac_com='$num_facH' AND cod_su='$codSuc' AND nit_pro='$nit_proH'  AND cod_barras='$cod_bar' AND fecha_vencimiento='$fechaVenciH' AND cod_barras!='' AND ref='$ref' AND id!='$ID' FOR UPDATE";}

else {$sql="SELECT * FROM art_fac_com WHERE num_fac_com='$num_facH' AND cod_su='$codSuc' AND nit_pro='$nit_proH'  AND cod_barras='$cod_bar' AND fecha_vencimiento='$fechaVenciH' AND cod_barras!='' AND id!='$ID' FOR UPDATE";}

$stmt = $linkPDO->query($sql);
if ($row = $stmt->fetch()) {

	
	if($usar_datos_motos!=1){$respuestaProceso =1062;}
	
	}


$sql="UPDATE art_fac_com SET ubicacion='$ubicacion',
      num_fac_com='$num_fac',nit_pro='$nit_pro', 
	  cant=$cant, dcto='$dcto',tipo_dcto='$tipoD',
	  pvp='$pvp',iva='$iva',uti='$uti',tot='$s_tot',
	  costo='$costo',dcto2='$descuento2',ref='$ref',
	  cod_barras='$cod_bar',fabricante='$fabricante',
	  color='$color',talla='$talla',des='$des',clase='$clase',
	  presentacion='$presentacion',fecha_vencimiento='$fechaVenci',
	  fraccion='$fracc',unidades_fraccion='$uni',pvp_cre='$pvpCre',
	  pvp_may='$pvpMay',aplica_vehi='$aplica_vehi',des_full='$des_full',
	  serial_art='$SERIAL',cert_importacion='$certImport', linea='$linea', 
	  modelo='$modelo', num_motor='$num_motor', num_chasis='$num_chasis',
	  cilindraje='$cilindraje',consecutivo_proveedor='$consecutivo_proveedor', 
	  campo_add_01='$campo_add_01', campo_add_02='$campo_add_02',cod_color='$cod_color',
	  vigencia_inicial='$vigencia_inicial',grupo_destino='$grupo_destino', impuesto_consumo='$impuesto_consumo',
	  impuesto_saludable = '$impSaludable'
	  WHERE id='$ID'";
//echo "$updateFac";

$linkPDO->exec($sql);


	

		}
	if($iRow>-1)break;	
		
	}//fin FOR
}// fin IF///////////////////////////////////////////////////////////////////////////////////////////////
else {

}// fin else////////////////////////////////////////////////////////////////////////////////////////////

$linkPDO->exec("SAVEPOINT B");

$sql="UPDATE fac_com SET calc_dcto='$calc_dcto', num_fac_com='$num_fac',dcto2='$descuento2',fecha='$fecha',nom_pro='$provedor',nit_pro='$nit_pro',ciudad='$ciudad',dir='$dir',tel='$tel',fax='$fax',mail='$mail',subtot='$subtot',descuento='$descuento',flete='$flete',iva='$iva',tot='$tot',val_letras='$val_letras',fecha_mod=CURRENT_TIMESTAMP(), tipo_fac='$tipo_fac', feVen='$feVen',r_fte='$R_FTE',r_ica='$R_ICA',r_iva='$R_IVA',perflete='$perFlete',calc_pvp='$CALC_PVP_COST_GANA',nota='$nota_fac' WHERE num_fac_com='$num_facH' AND cod_su='$codSuc' AND nit_pro='$nit_proH'";
$linkPDO->exec($sql);
//echo "$sql<br>";
$sqlLog=$sql;

$sql="UPDATE art_fac_com SET num_fac_com='$num_fac',nit_pro='$nit_pro' WHERE num_fac_com='$num_facH' AND cod_su='$codSuc' AND nit_pro='$nit_proH'";
$linkPDO->exec($sql);

if($calc_dcto=="valor"){$descuento="dcto";}
else {$descuento="costo*(dcto/100)";}

$descuento="costo*(dcto/100)";

$unidades="(unidades_fraccion+(cant*fraccion))/fraccion";

$SUB="ROUND(SUM(costo*($unidades) )) as SUB";
$DCTO="ROUND(SUM( ( $unidades  )*($descuento))) as DCTO";
$IVA="ROUND(SUM( ($unidades  )*(costo - ($descuento))*(iva/100))) as IVA";
$IMPUESTO_SALUDABLE_QUERY="ROUND(SUM( ($unidades  )*(costo - ($descuento))*(impuesto_saludable/100))) as IMPUESTO_SALUDABLE";
$CONSUMO="ROUND(SUM( ($unidades  )*(costo - ($descuento))*(impuesto_consumo/100))) as CONSUMO";

$stot="(costo*( $unidades ))";
$dcto="(( $unidades  )*($descuento))";
$Ivaflete="flete*0.19";
$iva="( ($unidades  )*(costo - ($descuento))*(iva/100) )";
$consumo="( ($unidades  )*(costo - ($descuento))*(impuesto_consumo/100) )";


$TOT="ROUND(SUM(  $stot + $iva + $consumo - $dcto) ) as TOT";

$linkPDO->exec("SAVEPOINT C");
$sql="SELECT $SUB,$DCTO,$IVA,$TOT,$CONSUMO,$IMPUESTO_SALUDABLE_QUERY 
      FROM `art_fac_com` 
	  WHERE nit_pro='$nit_pro' AND num_fac_com='$num_fac' AND cod_su='$codSuc' FOR UPDATE";
//echo "$sql";
$IMPUESTO_SALUDABLE_TOT=0;
$rsFacCom = $linkPDO->query($sql);
if($row=$rsFacCom->fetch())
{
$subCom=$row['SUB'];
$dctoCom=$row['DCTO'];
$ivaCom=$row['IVA'];
$IMPUESTO_SALUDABLE_TOT=$row['IMPUESTO_SALUDABLE'];
$impConsumoCom=$row['CONSUMO'];
$totCom=$row['TOT'];	
}
$sql="UPDATE fac_com SET 
      subtot='$subCom',
	  descuento='$dctoCom',
	  iva=('$ivaCom' + flete*0.19),
	  tot=('$totCom' + flete*1.19), 
	  impuesto_consumo='$impConsumoCom',
	  impuesto_saludable = '$IMPUESTO_SALUDABLE_TOT'
	  WHERE nit_pro='$nit_pro' AND num_fac_com='$num_fac'";
$linkPDO->exec($sql);

$linkPDO->commit();

$return_array[]=array("respuesta"=>$respuestaProceso,
"SUB"=>$subCom,
"DCTO"=>$dctoCom,
"IVA"=>$ivaCom,
"IMPUESTO_SALUDABLE"=>$IMPUESTO_SALUDABLE_TOT,
"CONSUMO"=>$impConsumoCom,
"TOT"=>$totCom
);

echo json_encode($return_array);

$rs=null;
$stmt=null;
$linkPDO= null;


	$_SESSION['num_fac']=$num_fac;
	$_SESSION['nit_pro']=$nit_pro;


}//fin rs fac com
}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

?>