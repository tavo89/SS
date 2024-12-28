<li class="uk-parent"> <!-- Cargar Carros -->

	<a href="#"><i class="uk-icon-truck <?php echo $uikitIconSize_menu ?>"></i>Cargar</a> 


        <ul class="uk-nav-sub">
			
			
				
				
				<?php 
				if(($rolLv==$Adminlvl || val_secc($id_Usu,"ver_nota_credito"))){
				?>
				
				
					<li><a href="traslados.php"><i class="uk-icon-check-square-o <?php echo $uikitIconSize_menu ?>" ></i>&nbsp; Recibir Mercanc&iacute;a</a></li>

				
				<?php 
				}
				?>


				<?php 
				if(($rolLv==$Adminlvl || val_secc($id_Usu,"ver_nota_credito")) && $codSuc==1){
				?>

				
					<li><a href="traslados_salen.php" ><iclass="uk-icon-reply <?php echo $uikitIconSize_menu ?>" ></i>&nbsp; Despachar Mercanc&iacute;a</a></li>


				<?php 
				}
				?>                              
				
				
		
		
	</ul>

</li> <!-- Final de Cargar Carros -->