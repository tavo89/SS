<?php

			$cod = limpiarcampo($row["cod"]); 

			$pvp = (integer)$row["precio_v"];
			$pvpMayorista = (integer)$row["pvp_may"];
			$pvpCredito = (integer)$row["pvp_credito"];
			$aplicaVehi=$row["aplica_vehi"];
			$iva = (integer)$row["iva"];
			$impuesto_saludable = $row["impuesto_saludable"];

			$des = $row["detalle"]; 
			$color = htmlentities($row["color"]);
			$talla = htmlentities($row["talla"]);
			$clase =$row["id_clase"];
			$id = $row["ref"];
			$cant = $row["exist"]*1;
			$presentacion=$row['prese'];
			$fab =htmlentities($row["fab"]); 
			$fe_ven=$row['fecha_vencimiento'];
			$frac=$row['fraccion'];
			$uni=$row['unidades_frac'];
			
			$IMG=$row['url_img'];
			
			$DES_FULL=$row["des_full"];
			if($frac==1)$uni=0;
			
			$tipoProducto=$row["tipo_producto"];



?>