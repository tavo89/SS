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
$('<audio id="alertAudio"><source src="media/error1.mp3" type="audio/mpeg"></audio>').appendTo('body');
var refreshTime = 300000; // 300000 every 5 minutes in milliseconds
window.setInterval( function() {
    $.ajax({
        cache: false,
        type: "GET",
        url: "ajax/refreshSession.php",
        success: function(data) {
        },
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
    });
}, refreshTime );

 
</script>       

<script src="css/uikit-2.24.2/js/uikit.min.js"></script>
<script src="css/uikit-2.24.2/js/components/tooltip.min.js"></script>

<script src="css/uikit-2.24.2/js/components/sticky.min.js"></script>
