<?php
$totalPag = ceil($total/$limit); 
         $links = array(); 
		 $sig=$pag+1;
		 if($sig>$totalPag)$sig=$pag;
		 $ant=$pag-1;
		 if($ant<1)$ant=$pag;
        /* for( $i=1; $i<=$totalPag ; $i++) 
         { 
            $links[] = "<a href=\"?pag=$i\">$i</a>";  
         } 
*/


?>
<form action="" method="get" class=" uk-form">
<div class="uk-button-group uk-width-1-1 no-print">
<div class="uk-button-group uk-width-1-3">
<?php 
//<a href=\"$url?pag=".$sig."\"  class=\"uk-button uk-button-mini\"><i class=\"\">".$pag."/$totalPag</li></a>
echo "<a href=\"$url?pag=1\" class=\"uk-button uk-button-mini\"><i class=\"uk-icon-angle-double-left uk-icon-large\">Inicio</i></a>
<a href=\"$url?pag=".$ant."\" class=\"uk-button uk-button-mini\"><i class=\"uk-icon-angle-left uk-icon-large\"></i></a>
<a href=\"$url?pag=".$sig."\"class=\"uk-button uk-button-mini\"><i class=\"uk-icon-angle-right uk-icon-large\"></i></a>
<a href=\"$url?pag=$totalPag\" class=\"uk-button uk-button-mini\"><i class=\"uk-icon-angle-double-right uk-icon-large\">Fin</i></a>
<a href=\"#\" class=\"uk-button uk-button-mini\"><i class=\"uk-icon-print uk-icon-large\" onClick=\"window.print();\">IMPRIMIR</i></a>";  ?>
</div>
<div class="uk-width-1-3">
<input type="text" name="pag" value=""  placeholder="P&aacute;gina <?php echo $pag ?>" id="pag" class="uk-form-small">
</div>

</div>
</form>