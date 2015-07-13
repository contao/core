<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * This is the ACE (code editor) configuration file. Please visit
 * http://ace.ajax.org for more information.
 */
if ($GLOBALS['TL_CONFIG']['useCE']):

?>
<script>window.ace || document.write('<script src="<?php echo TL_ASSETS_URL; ?>assets/ace/<?php echo $GLOBALS['TL_ASSETS']['ACE']; ?>/ace.js" charset="utf-8">\x3C/script>')</script>
<script>
window.ace && window.addEvent('domready', function() {
  var ta = document.getElementById('<?php echo $selector; ?>'),
      dom = ace.require("ace/lib/dom");

  // Create a div to apply the editor to
  var div = document.createElement('div');
  div.id = '<?php echo $selector; ?>_div';
  div.className = ta.get('class');
  ta.parentNode.insertBefore(div, ta.nextSibling);

  // Hide the textarea
  ta.style['display'] = 'none';

  // Instantiate the editor
  var editor = ace.edit('<?php echo $selector; ?>_div');
  editor.setTheme("ace/theme/clouds");
  editor.renderer.setScrollMargin(3, 3, 0, 0);
  editor.renderer.scrollBy(0, -6);
  editor.getSession().setValue(ta.value);
  editor.getSession().setMode("ace/mode/<?php echo Backend::getAceType($type); ?>");
  editor.getSession().setUseSoftTabs(false);
  editor.setAutoScrollEditorIntoView(true);

  // Add the fullscreen command
  editor.commands.addCommand({
    name: 'Fullscreen',
    bindKey: 'F11',
    exec: function(editor) {
      dom.toggleCssClass(document.body, 'ace-fullsize');
      editor.resize();
    }
  });

  // Disable command conflicts with AltGr (see #5792)
  editor.commands.bindKey('Ctrl-alt-a|Ctrl-alt-e|Ctrl-alt-h|Ctrl-alt-l|Ctrl-alt-s', null);

  // Adjust the height of the editor
  var updateHeight = function() {
    var newHeight
      = editor.getSession().getScreenLength()
      * (editor.renderer.lineHeight || 14)
      + editor.renderer.scrollBar.getWidth();
    editor.container.style['height'] = Math.max(newHeight, editor.renderer.lineHeight) + 'px';
    editor.resize();
  };

  editor.on('focus', function() {
    Backend.getScrollOffset();
    updateHeight();
  });

  editor.getSession().on('change', function() {
    ta.value = editor.getValue();
    updateHeight();
  });

  updateHeight();
});
</script>
<?php endif; ?>
