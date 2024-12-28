<!-- Lado izquierdo del Nav -->
<nav class="uk-navbar">
<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 
<!-- Centro del Navbar  if(!empty($cod_origen)){echo "traslados_salen.php";}else-->
<ul class="uk-navbar-nav uk-navbar-center" style="width:100%; font-size:9px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
<li ><a style="font-size:10px;" href="<?php  if ($COTIZACION==1){echo "COTIZACIONES.php";}else if($tipoFAC=="remi"){echo "remisiones2.php";} else{ echo "remisiones.php";} ?>" >
<i class="uk-icon-list <?php echo $uikitIconSize ?>"></i>
&nbsp;<?php if($COTIZACION==1){ echo "Cotizaciones"; }else if($tipoFAC=="OT"){echo "$LABEL_REMISION";}else{ echo "Remisiones";} ?>
</a></li>
<li style="width:180px; font-size:10px;"><a style="font-size:10px;" href="<?php echo "fac_remi.php?tipoFAC=OT" ?>" ><i class="uk-icon-wrench <?php echo $uikitIconSize ?>"></i>&nbsp;<?php echo $LABEL_REMISION;?></a></li>

<li><a style="font-size:10px;" href="<?php echo "fac_remi.php?co=1" ?>" ><i class="uk-icon-file-text <?php echo $uikitIconSize ?>"></i>&nbsp;Cotiza</a></li>

<li><a style="font-size:10px;" href="<?php echo "fac_remi.php?tipoFAC=remi" ?>" ><i class="uk-icon-edit  <?php echo $uikitIconSize ?>"></i>&nbsp;Remisi&oacute;n</a></li>

<!--
<li><a href="<?php echo thisURL(); ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>
-->
</ul>
</nav>