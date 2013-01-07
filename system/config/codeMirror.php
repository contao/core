<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * This is the Code Mirror (code editor) configuration file. Please visit
 * http://codemirror.net for more information.
 */
if ($GLOBALS['TL_CONFIG']['useCE']):

	// Include the CodeMirror scripts
	$GLOBALS['TL_CSS'][] = 'assets/codemirror/'.CODEMIRROR.'/codemirror.css';
	$GLOBALS['TL_JAVASCRIPT'][] = 'assets/codemirror/'.CODEMIRROR.'/codemirror.js';

	foreach ($this->ceFields as $arrField):

		if ($arrField['type'] == 'sql')
		{
			$arrField['type'] = 'mysql';
		}

		// Validate the syntax
		switch ($arrField['type'])
		{
			case 'clike';
			case 'css':
			case 'diff';
			case 'htmlmixed';
			case 'javascript';
			case 'php':
			case 'mysql':
			case 'xml':
				// Supported
				break;

			default:
				$arrField['type'] = 'htmlmixed';
				break;
		}

?>
<script>
window.addEvent('domready', function() {
  var myField = $('<?php echo $arrField['id']; ?>');
  var myForm = myField.getParent('form');

  // Instantiate
  var myCodeMirror = CodeMirror.fromTextArea(myField, {
    mode: '<?php echo $arrField['type']; ?>',
    lineNumbers: true,
    form: null,
    enterMode: 'keep',
    onKeyEvent: function(i, e) {
      // Fullscreen mode (F11)
      if (e.keyCode == 122 && e.type == 'keydown') {
        myCodeMirror.getWrapperElement().
          getElement('.CodeMirror-scroll').
          toggleClass('fullscreen');
        e.preventDefault();
      }
    }
  });

  // Adjust the height and width
  myCodeMirror.getWrapperElement().
    getElement('.CodeMirror-scroll').
    setStyle('height', myField.getStyle('height')).
    setStyle('width', myField.getStyle('width'));

  // Unset the "required" attribute
  myField.erase('required');

  // Custom onsubmit logic
  myForm.addEvent('submit', function() {
    var hidden = new Element('input', {
      'type': 'hidden',
      'name': myField.name,
      'value': myCodeMirror.getValue()
    });
    hidden.inject(myForm);
  });
});
</script>
<?php endforeach; ?>
<?php endif; ?>
