 <?php
include_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");

// excepciones para ventas productos en COMBO
$SUB_DOMINIOS_COMBOS=array("127.0.0.1/SS/",//0
"testserver.nanimosoft.com",//1
"alimentosylicores.nanimosoft.com",//2
"elbutaco.nanimosoft.com",
"tiendaniko.nanimosoft.com");
$hostName=$_SERVER['HTTP_HOST'];
$setComboLas=0;
if(in_array($hostName,$SUB_DOMINIOS_COMBOS)){$setComboLas=1;}


// ------------------------------------------
$ref=limpiarcampo($_REQUEST['ref']);
$codBar=r("codBar");
$feVencimiento=limpiarcampo($_REQUEST['fe_ven']);
$tipoCli=r("tc");
$idCli=r("idCli");
$cotiza=r("co");
$form_pago=r('form_pago');
$tipoResol=r('tipoResol');
//$validaDiaSinIVA=($form_pago=='Tarjeta Credito' || $form_pago=='Tarjeta Debito' || $form_pago=='Transferencia Bancaria' || $hostName == 'elarauco.nanimosoft.com');

$validaDiaSinIVA=( $tipoResol=='PAPEL' );



$filtroCant=" AND (exist>0 OR unidades_frac>0) ";
if($FLUJO_INV==1 && $vender_sin_inv==0 && $vende_sin_cant==0 && $cotiza!=1)$filtroCant=" AND (exist>0 OR unidades_frac>0) ";
else $filtroCant="";

$refOPT="";
$feVenOPT="";
$rowsSql="ubicacion,exist,i.id_pro ref,p.detalle,precio_v,iva,costo,gana,color,talla,fab,id_inter,dcto3,fraccion,unidades_frac,fecha_vencimiento,p.presentacion as prese,pvp_credito,pvp_may";

if(!empty($ref))$refOPT="id_pro='$ref' AND ";
else {$refOPT="id_pro='$codBar' OR";}

if(!empty($feVencimiento) && $feVencimiento!="0000-00-00")$sql="SELECT $rowsSql FROM ".tabProductos." p INNER JOIN (SELECT * FROM inv_inter WHERE $refOPT inv_inter.id_inter='$codBar' AND fecha_vencimiento='$feVencimiento'  $filtroCant ) AS i ON i.id_pro=p.id_pro WHERE  nit_scs=$codSuc";

else $sql="SELECT $rowsSql FROM ".tabProductos." p INNER JOIN (SELECT * FROM inv_inter WHERE $refOPT  inv_inter.id_inter='$codBar'  $filtroCant ) AS i ON i.id_pro=p.id_pro WHERE  nit_scs=$codSuc";

//echo "$sql<br>";
$rs=$linkPDO->query($sql);
//$nr2=$rs->rowCount();;
$resp=1;
$filtro="";
$filtroFEchaVEnci="";

$nr2=$rs->rowCount();;
if($nr2>1)$resp=2;
$rs=$linkPDO->query($sql);

$desc=0;
$desc_per=0;
if(($rolLv==$Adminlvl || val_secc($id_Usu,"descuento_fac")) && $codSuc>0){$desc=1;}

if(($rolLv==$Adminlvl || val_secc($id_Usu,"dcto_per")) && $codSuc>0){$desc_per=1;}

if($row=$rs->fetch()){
	        
	        $cant = $row["exist"];
			if($FLUJO_INV==-1 || $vender_sin_inv==1 || $vende_sin_cant==1 || $cotiza==1)$cant=100000000;
            $des = $row["detalle"];
			$cod_bar = $row["id_inter"];
			$color = $row["color"];
			$talla = $row["talla"]; 
			$dcto3 = $row["dcto3"]*1;
			$id = $row["ref"];
			$pvp =$row["precio_v"]*1;
			$pvp_may =$row["pvp_may"]*1;
			
			$marcaFAB = $row["fab"];
			
			
			$iva = !empty($row["iva"])?$row["iva"]:0;
 
			if($dia_sin_iva == 1 && $validaDiaSinIVA){
				$pvp=redondeoF($pvp/(1+($iva/100)),-1);
				$iva=0;
				$desc_per=0;	
		    }
		
			$costo= $row["costo"]*(1+($iva/100));
			$ref=$row['ref'];
			$util=$row['gana'];
			$fab=$row['fab'];
			$frac=$row['fraccion'];
			$uni=$row['unidades_frac'];
			if($frac==0 || empty($frac))$frac=1;
			$factor=($uni/$frac)+$cant;
			$feVen=$row['fecha_vencimiento'];
			$presentacion=$row['prese'];
			$pvpCredito=$row['pvp_credito']*1;
			if($pvpCredito==0)$pvpCredito=redondeoF($pvp*($per_credito/100 +1),-1);
			
			$ubica=$row["ubicacion"];
			
			if($util>=40)$util=($util-20)/100;
			else if ($util<40&&($util-20)>20)$util=($util-20)/100;
			else $util=(20)/100;
			
			
			if($util==1)$util=0.5;
			if($iva==0)$valBase=($costo/(1-$util));
			else $valBase=($costo/(1-$util))*(1+($iva/100));
			$util=$row['gana'];
			$valBase= ($pvp_may!=0 && $MODULES['PVP_MAYORISTA']==1) ?$pvp_may:$costo*1.03;



			if($vende_sin_cant==1 || $dctos_ropa==1)$valBase=1;
			if($setComboLas){$valBase=1;}

			if($vende_a_costo==1) {
				$valBase= $costo;
			}
			
			if(!empty($tipoCli) && ($tipoCli=="Traslado" || $tipoCli=="Mostrador." 
			|| (in_array($idCli,$ID_CLI_DCTO_ESPECIAL) && !empty($idCli) && $idCli!="0" ) )){

					if( ($usar_costo_remi==1 && $tipoCli=="Traslado") || ( in_array($idCli,$ID_CLI_DCTO_ESPECIAL) && !empty($idCli)  ) )
					{
						$pvp=$costo;
						$pvpCredito=$costo;
					}
				$valBase=1;
			}

if($tipoCli=="Mayoristas" && $pvp_may!=0 ){$pvp=$pvp_may;}

if($impuesto_consumo==1 && $iva==0){$pvp=round($pvp/1.08);}


			if($dcto3==0)$dcto3="";
//	0     1    2	 3    4     5		 6	   7	  8	   9	 10    11      12     13    14     15   16        17 	   18 	    19        20	 	     21	
$html="$factor|$ref|$des|$iva|$pvp|$costo|$valBase|$util|$color|$talla|$fab|$cod_bar|$dcto3|$desc|$resp|$frac|$uni|$presentacion|$feVen|$desc_per|$pvpCredito|$filtroFEchaVEnci|$ubica|$marcaFAB";
			if($cant>0 || $uni>0)echo $html;
			else echo "0";
}
else {
	echo "-2";
	
	}
  ?>