 <?php
require_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");
$ref=limpiarcampo($_REQUEST['ref']);
$codBar=r("codBar");
$feVencimiento=r('fe_ven');
$tipoCli=r("tc");
$idCli=r("idCli");
$cotiza=r("co");



$filtroCant="";

$refOPT="";
$feVenOPT="";
$rowsSql="ubicacion,exist,i.id_pro ref,p.detalle,precio_v,iva,costo,gana,color,talla,fab,id_inter,dcto3,fraccion,unidades_frac,fecha_vencimiento,p.presentacion as prese,pvp_credito,pvp_may,id_clase";

if(!empty($ref))$refOPT="id_pro='$ref'  ";
else {$refOPT="id_pro='$codBar' ";}

if(!empty($feVencimiento) && $feVencimiento!="0000-00-00")$sql="SELECT $rowsSql FROM ".tabProductos." p INNER JOIN (SELECT * FROM inv_inter WHERE $refOPT  AND fecha_vencimiento='$feVencimiento'  $filtroCant ) AS i ON i.id_pro=p.id_pro WHERE  nit_scs=$codSuc";

else $sql="SELECT $rowsSql FROM ".tabProductos." p INNER JOIN (SELECT * FROM inv_inter WHERE $refOPT    $filtroCant ) AS i ON i.id_pro=p.id_pro WHERE  nit_scs=$codSuc";

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
			$clase = $row["id_clase"];
			$pvp =$row["precio_v"]*1;
			$pvp_may =$row["pvp_may"]*1;
			
			
			$iva = $row["iva"];
			$costo= $row["costo"];
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

			$valBase=round($pvp/(1+$lim_dcto/100));
			//       0    1    2   3      4      5     6      7     8     9    10      11         12      13		 14
			$html="$ref|$des|$iva|$pvp|$costo|$util|$color|$talla|$fab|$frac|$uni|$presentacion|$feVen|$pvpCredito|$clase";
			echo $html;
}
else {
	echo "-2";
	
	}
   ?>