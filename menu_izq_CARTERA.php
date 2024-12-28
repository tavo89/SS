<li class="uk-parent"> <!--  Cartera -->

	<a href="#"><i class="uk-icon-credit-card <?php echo $uikitIconSize_menu ?>" ></i>Cartera</a> 

		<ul class="uk-nav-sub">
		
			


				<?php 
				if(($rolLv==$Adminlvl || val_secc($id_Usu,"creditos_publico")) && $codSuc>0){
				?>
				
				
					<li><a href="creditos_parti.php" ><i class="uk-icon-list <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Creditos</a></li>

					
				<?php 
				}
				?>




				<?php 
				if(($rolLv==$Adminlvl || val_secc($id_Usu,"crear_recibo_caja")) && $codSuc>0){
				?>
				
				
					<li><a href="abonos_creditos.php" ><i class="uk-icon-list <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Comprobantes</a></li>

					
				<?php 
				}
				?> 


			
		</ul>

</li> <!-- Final de Cartera -->