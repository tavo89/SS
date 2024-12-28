
var icycle_active="";
var icycle_retract=1;



function show_icycle(ars){
		
		if(icycle_active!=""){
			document.getElementById(icycle_active).className = "no_showz";
			
			document.getElementById(icycle_active).className += " no_showz_position";

			
			remove_iconos_active(icycle_active);
			
		}
		
		
		if(icycle_active==ars && icycle_retract==2){
			
			document.getElementById(ars).className = "no_showz";
			
			document.getElementById(ars).className += " no_showz_position";
						
						
			remove_iconos_active(icycle_active);
			
			icycle_retract=1;
		}
		else
		{
			icycle_retract=2;
			
			icycle_active=ars;
			
			document.getElementById(ars).className = "showz_drop";
			
			
			document.getElementById(ars+'_icon').className += " iconos_s_active"; 
		}
		
}

function hide_icycle(arc){
	
			document.getElementById(arc).className = "no_showz";
			
			document.getElementById(arc).className += " no_showz_position";

			
			remove_iconos_active(icycle_active);
			
			icycle_retract=1;
			
			
}

function remove_iconos_active(icycle_active){

	var iconos_active = document.getElementById(icycle_active+"_icon").className;
	
			iconos_active = iconos_active.replace("iconos_s_active"," "); 
			
			document.getElementById(icycle_active+"_icon").className = iconos_active;

}
