<?php
// query busca en inventario, luego carga en compra
		if(!empty($ref)){
			if(!empty($fe) && $fe!="0000-00-00")
			{
				$sql="SELECT $colsA 
					FROM productos 
					INNER JOIN $tablaListaInv ON $tablaListaInv.id_pro=".tabProductos.".id_pro 
					WHERE  ( $tablaListaInv.id_inter='$codBar' AND $tablaListaInv.id_pro='$ref' 
					AND $tablaListaInv.fecha_vencimiento='$fe') $filtroSedeA";
			}
			else {
				$sql="SELECT $colsA 
				FROM ".tabProductos." 
				INNER JOIN $tablaListaInv ON $tablaListaInv.id_pro=".tabProductos.".id_pro 
				WHERE  ( $tablaListaInv.id_inter='$codBar' AND $tablaListaInv.id_pro='$ref') $filtroSedeA";}
			}
		else
		{

			$addRefValidateA="$tablaListaInv.id_inter='$codBar' AND $tablaListaInv.id_pro='$ref' AND $tablaListaInv.fecha_vencimiento='$fe'";
			$addRefValidateB="$tablaListaInv.id_inter='$codBar' AND $tablaListaInv.id_pro='$ref'";

			if( $MODULES["QUICK_FAC_INPUT"]==1 || $MAIN_ID_BAR==1){
			$addRefValidateA="$tablaListaInv.id_inter='$codBar'  AND $tablaListaInv.fecha_vencimiento='$fe'";
			$addRefValidateB="$tablaListaInv.id_inter='$codBar' "; 
			}
			
			
			if(!empty($fe) && $fe!="0000-00-00"){$sql="SELECT $colsA FROM ".tabProductos." INNER JOIN $tablaListaInv ON $tablaListaInv.id_pro=".tabProductos.".id_pro WHERE  ($addRefValidateA) $filtroSedeA";}

			else {$sql="SELECT $colsA FROM ".tabProductos." INNER JOIN $tablaListaInv ON $tablaListaInv.id_pro=".tabProductos.".id_pro WHERE  ($addRefValidateB) $filtroSedeA";}			
			
		}
?>