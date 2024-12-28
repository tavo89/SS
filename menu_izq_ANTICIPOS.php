<?php 
if(($rolLv==$Adminlvl || val_secc($id_Usu,"crear_anticipo"))){
?>


	<li class="uk-parent"> <!--  Anticipos -->

		<a  href="#"><i class="uk-icon-tags <?php echo $uikitIconSize_menu ?>"></i>Anticipos</a> 

								
            <ul class="uk-nav-sub">
				
				

					<li><a href="expedientes.php"  ><i class="uk-icon-briefcase <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Anticipos</a></li>
					<li><a href="abonos_anticipos.php" ><i class="uk-icon-list <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Comprobantes</a></li>          
				
				
				
			</ul>
                                    
    </li> <!-- Final de Anticipos -->
	

<?php 
}
?>