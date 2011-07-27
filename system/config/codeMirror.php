<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Config
 * @license    LGPL
 * @filesource
 */


/**
 * This is the Code Mirror (code editor) configuration file. Please visit
 * http://codemirror.net for more information.
 */
if ($GLOBALS['TL_CONFIG']['useCE']):

	// Include the CodeMirror scripts
	$GLOBALS['TL_CSS'][] = 'plugins/codeMirror/codemirror.css';
	$GLOBALS['TL_JAVASCRIPT'][] = 'plugins/codeMirror/codemirror.js';

	foreach ($this->ceFields as $arrField):

		// Validate the syntax
		switch ($arrField['type'])
		{
			case 'clike';
			case 'css':
			case 'diff';
			case 'htmlmixed';
			case 'javascript';
			case 'php':
			case 'sql':
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
    enterMode: 'keep'
  });

  // Adjust the height and width
  myCodeMirror.getWrapperElement().
    getElement('.CodeMirror-scroll').
    setStyle('height', myField.getStyle('height')).
    setStyle('width', myField.getStyle('width'));

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
