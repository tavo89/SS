function esVacio(val) {
   if(val == null) {
      return true; 
      }
   for(var i = 0; i < val.length; i++) {
      if((val.charAt(i) != ' ') && (val.charAt(i) != "\t") && (val.charAt(i) != "\n") && (val.charAt(i) != "\r")) {
         return false; 
         }
      }
   return true; 
   }; 
function redondeo(numero)
{
var original=parseFloat(numero);
var result=Math.round(original*0.01)/0.01 ;
return result;
};

function quitap(T) {//alert(T);
	
   var n = T.split(","), 
   i = 0, h = ""; 
   for(i = 0; i < n.length; i++) {
      h = h + n[i]; 
      }
	 // alert(h);
   return h; 
   }; 


function puntoa(ve) {
   var T = ve.val().toString(); 
   //alert('T:'+T);
   T = quitap(T);
  // alert('quitap(T):'+T); 
   var i = T.length - 1, ii = T.length; 
   T = T.split(""); 
   var x, a, b, c, C = 0, h = '',ff=0; 
   while(i >= 0) {
	   
	  //alert('C:'+C+', ii:'+ii+'T[:'+i+']:'+T[i]);
	 
      if(C == 3 && ii != 3 && T[i] != '-') {
         h = T[i] + ',' + h; 
         C = 0; 
		//alert('IF\nh:['+h+']; C:'+C);
         }
      else {
         h = T[i] + h;
		 //alert('ELSE \nh:['+h+']; C:'+C);
         }
      //if(T[i] != '-'&&T[i]=='.')
	  
	  
	   if(T[i]=='.'){C=-1;h=quitap(h);}
	   C++; 
	   i--;
       
      }
   $(ve).prop('value', h); 
   }; 
function puntob(ve) {
   var T = ve.toString(); 
   //alert('T:'+T);
   T = quitap(T);
  // alert('quitap(T):'+T); 
   var i = T.length - 1, ii = T.length; 
   T = T.split(""); 
   var x, a, b, c, C = 0, h = '',ff=0; 
   while(i >= 0) {
	   
	  //alert('C:'+C+', ii:'+ii+'T[:'+i+']:'+T[i]);
	 
      if(C == 3 && ii != 3 && T[i] != '-') {
         h = T[i] + ',' + h; 
         C = 0; 
		//alert('IF\nh:['+h+']; C:'+C);
         }
      else {
         h = T[i] + h;
		 //alert('ELSE \nh:['+h+']; C:'+C);
         }
      //if(T[i] != '-'&&T[i]=='.')
	  
	  
	   if(T[i]=='.'){C=-1;h=quitap(h);}
	   C++; 
	   i--;
       
      }
   return h; 
   }; 


function puntoiva(n) {
   var T = n.value; 
   T = quitap(T); 
   var x, i = T.length - 1, a, b, c, C = 0, ii = T.length, h = ''; 
   T = T.split(""); 
   while(i >= 0) {
      if(C == 3 && ii != 3) {
         h = T[i] + '.' + h; 
         C = 0; 
         }
      else {
         h = T[i] + h; 
         }
      C++; 
      i--; 
      }
	
   $(n).prop('value', h);
   h= quitap(h);
   $('#' + n.id + "H").prop('value',h/1.16); 
   }; 
