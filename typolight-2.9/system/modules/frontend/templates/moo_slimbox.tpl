<?php

// Add slimbox style sheet
$GLOBALS['TL_CSS'][] = 'plugins/slimbox/css/slimbox.css|screen';

?>

<script type="text/javascript" src="plugins/slimbox/js/slimbox.js"></script>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
Slimbox.scanPage = function() {
  $$(document.links).filter(function(el) {
    return el.rel && el.rel.test(/^lightbox/i);
  }).slimbox({}, null, function(el) {
    return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
  });
};
window.addEvent("domready", Slimbox.scanPage);
//--><!]]>
</script>
