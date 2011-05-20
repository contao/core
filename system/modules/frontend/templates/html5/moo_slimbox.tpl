<?php

// Add slimbox style sheet
$GLOBALS['TL_CSS'][] = TL_PLUGINS_URL . 'plugins/slimbox/css/slimbox.css|screen';

?>

<script src="<?php echo TL_PLUGINS_URL; ?>plugins/slimbox/js/slimbox.js"></script>
<script>
Slimbox.scanPage = function() {
  $$(document.links).filter(function(el) {
    return el.rel && el.rel.test(/^lightbox/i);
  }).slimbox({}, null, function(el) {
    return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
  });
};
window.addEvent('domready', Slimbox.scanPage);
</script>
