 <?php
include_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");
$ref=limpiarcampo($_REQUEST['ref']);
$codBar=r("codBar");
$feVencimiento=limpiarcampo($_REQUEST['fe_ven']);
$tipoCli=r("tc");
$idCli=r("idCli");



$filtroCant="";

$refOPT="";
$feVenOPT="";
$rowsSql="ubicacion,exist,i.id_pro ref,p.detalle,precio_v,iva,costo,gana,color,talla,fab,id_inter,dcto3,fraccion,unidades_frac,fecha_vencimiento,p.presentacion as prese,pvp_credito";

if(!empty($ref))$refOPT="id_pro='$ref' AND ";
else {$refOPT="id_pro='$codBar' OR";}

if(!empty($feVencimiento) && $feVencimiento!="0000-00-00")$sql="SELECT $rowsSql FROM ".tabProductos." p INNER JOIN (SELECT * FROM inv_inter WHERE $refOPT inv_inter.id_inter='$codBar' AND fecha_vencimiento='$feVencimiento'  $filtroCant ) AS i ON i.id_pro=p.id_pro WHERE  nit_scs=$codSuc";

else $sql="SELECT $rowsSql FROM ".tabProductos." p INNER JOIN (SELECT * FROM inv_inter WHERE $refOPT  inv_inter.id_inter='$codBar'  $filtroCant ) AS i ON i.id_pro=p.id_pro WHERE  nit_scs=$codSuc";

$rs=$linkPDO->query($sql);

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
	        
	        $cant=100000000;
            $des = $row["detalle"];
			$cod_bar = $row["id_inter"];
			$color = $row["color"];
			$talla = $row["talla"]; 
			$dcto3 = $row["dcto3"]*1;
			$id = $row["ref"];
			$pvp =$row["precio_v"]*1;
			$iva = $row["iva"];
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
			if($pvpCredito==0)$pvpCredito=$pvp;
			$ubica=$row["ubicacion"];
			if($util>=40)$util=($util-20)/100;
			else if ($util<40&&($util-20)>20)$util=($util-20)/100;
			else $util=(20)/100;
			
			
			//$valBase=($costo/($util/((1-$util))))*((1+$iva)/100);
			if($util==1)$util=0.5;
			if($iva==0)$valBase=($costo/(1-$util));
			else $valBase=($costo/(1-$util))*(1+($iva/100));
			$util=$row['gana'];

			$valBase=$costo;
			
/*		
if(!empty($tipoCli) && ($tipoCli=="Traslado" || $tipoCli=="Mostrador." || (in_array($idCli,$ID_CLI_DCTO_ESPECIAL) && !empty($idCli)  ) )){

	if($usar_costo_remi==1 || ( in_array($idCli,$ID_CLI_DCTO_ESPECIAL) && !empty($idCli)  ) )
	{
		$pvp=$costo;
		$pvpCredito=$costo;}
$valBase=1;

			}*/
			$valBase=1;
			if($dcto3==0)$dcto3="";
$html="$factor|$ref|$des|$iva|$pvp|$costo|$valBase|$util|$color|$talla|$fab|$cod_bar|$dcto3|$desc|$resp|$frac|$uni|$presentacion|$feVen|$desc_per|$pvpCredito|$filtroFEchaVEnci|$ubica";
//$html=limpiarcampo($html);
			if($cant>0 || $uni>0)echo $html;
			else echo "0";
}
else {
	echo invBuscaHistorial($ref, $codBar, $feVencimiento,'devolucionFactura');
	
	}
   ?>