<?php

/**
 * To use the script, replace UA-XXXXX-X in the code below with your Google
 * Analytics ID and then add it to a Contao page layout. Make sure to insert
 * it as the last moo_ script (!) and note that it will only be added to the
 * page if you are not logged into the back end.
 */
if (!BE_USER_LOGGED_IN && sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? $this->Environment->ip : '') . 'BE_USER_AUTH') != $this->Input->cookie('BE_USER_AUTH')):

?>

<script>
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-XXXXX-X']);
_gaq.push(['_trackPageview']);
(function() {
  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>
<?php endif; ?>
