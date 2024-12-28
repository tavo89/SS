<?php

$linkPDO = null;

?>

</div><!-- grid cpntainer -->
</div>
</div><!-- /scroller-inner -->
				</div><!-- /scroller -->

			</div><!-- /pusher -->
		</div><!-- /container -->
<script type="text/javascript">
//$('<audio id="alertAudio"><source src="media/error1.mp3" type="audio/mpeg"></audio>').appendTo('body');
var refreshTime = 300000; // 300000 is every 5 minutes in milliseconds
window.setInterval( function() {
    $.ajax({
        cache: false,
        type: "GET",
        url: "ajax/refreshSession.php",
        success: function(data) {
        },
		error:function(xhr,status){simplePopUp('Error, xhr:'+xhr+' STATUS:'+status);}
    });
}, refreshTime );


 
</script>   
<script src="JS/jquery-2.1.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script src="css/uikit-2.24.2/js/uikit.min.js"></script>
<script src="css/uikit-2.24.2/js/components/tooltip.min.js"></script>
<script src="css/uikit-2.24.2/js/components/datepicker.min.js"></script>
<script src="css/uikit-2.24.2/js/components/lightbox.js"></script>

<!--<script language="javascript1.5" type="text/javascript" src="JS/cityBG.js"></script>-->
<script type="text/javascript">
//new UISearch( document.getElementById( 'sb-search' ) );
</script>