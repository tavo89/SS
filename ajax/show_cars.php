<?php
require_once("../Conexxx.php");
$sql="SELECT * FROM camion";
$rs=$linkPDO->query($sql);
$style_no_cerradas="";
while ($row = $rs->fetch()) 
{ 
$ii++;
		    
			
}
 
$array= array( array( Name => "", 
                      Id => 0,
                     
                    ),
               array( Name => "United States", 
                      Id => 1,
                    ),
               array( Name => "Canada", 
                      Id => 2, 
                    )
             );



$s=   "
        { Name: 'United States', Id: 1 },
        { Name: 'Canada', Id: 2 },
        { Name: 'United Kingdom', Id: 3 },
        { Name: 'France', Id: 4 },
        { Name: 'Brazil', Id: 5 },
        { Name: 'China', Id: 6 },
        { Name: 'Russia', Id: 7 }";        
         
    echo $array;     
         

?>