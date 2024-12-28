<?php
// parametrizar variables tablas SQL
$addRefValidateA="cod_barras='$codBar' AND ref='$ref' AND fecha_vencimiento='$fe'";
$addRefValidateB="cod_barras='$codBar' AND ref='$ref' AND fecha_vencimiento='0000-00-00'";
if($MODULES["QUICK_FAC_INPUT"]==1 ){
$addRefValidateA="cod_barras='$codBar'  AND fecha_vencimiento='$fe'";
$addRefValidateB="cod_barras='$codBar'  AND fecha_vencimiento='0000-00-00'"; }

$colsA="pvp_may,
        pvp_credito,
        ubicacion,
        exist,
        dcto2,
        $tablaListaInv.id_pro ref,
        detalle,precio_v,
        costo,iva,gana,
        color,
        talla,
        id_inter,
        id_clase,
        fab,
        ".tabProductos.".presentacion,
        $tablaListaInv.fraccion,
        $tablaListaInv.unidades_frac,
        fecha_vencimiento,
        aplica_vehi,
        campo_add_01,
        campo_add_02,
        cod_color,
        vigencia_inicial,
        grupo_destino,
        impuesto_saludable";
$colsB="";

?>