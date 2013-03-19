<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * This is the ACE (code editor) configuration file. Please visit
 * http://ace.ajax.org for more information.
 */
if ($GLOBALS['TL_CONFIG']['useCE']):

	// Include the ACE script
	$GLOBALS['TL_JAVASCRIPT'][] = 'assets/ace/'.ACE.'/ace.js" charset="utf-8';

	foreach ($this->ceFields as $arrField):

		// Validate the syntax
		switch ($arrField['type'])
		{
			case 'css':
			case 'diff':
			case 'html':
			case 'java':
			case 'json':
			case 'php':
			case 'sql':
			case 'xml':
			case 'yaml':
				// nothing to do
				break;

			case 'js':
				$arrField['type'] = 'javascript';
				break;

			case 'md':
			case 'markdown':
				$arrField['type'] = 'markdown';
				break;

			case 'cgi':
			case 'pl':
				$arrField['type'] = 'perl';
				break;

			case 'py':
				$arrField['type'] = 'python';
				break;

			case 'txt':
				$arrField['type'] = 'text';
				break;

			case 'c': case 'cc': case 'cpp': case 'c++':
			case 'h': case 'hh': case 'hpp': case 'h++':
				$arrField['type'] = 'c_cpp';
				break;

			case 'html5':
			case 'xhtml':
				$arrField['type'] = 'php';
				break;

			default:
				$arrField['type'] = 'text';
				break;
		}

?>
<script>
(function() {
  var ta = $('<?php echo $arrField['id']; ?>');

  var div = new Element('div', {
    'id':'<?php echo $arrField['id']; ?>_div',
    'class':ta.get('class')
  }).inject(ta, 'after');

  ta.setStyle('display', 'none');

  var editor = ace.edit('<?php echo $arrField['id']; ?>_div');
  editor.setTheme("ace/theme/clouds");
  editor.getSession().setValue(ta.value);
  editor.getSession().setMode("ace/mode/<?php echo $arrField['type']; ?>");
  editor.getSession().setUseSoftTabs(false);

  editor.commands.addCommand({
    name: 'Fullscreen',
    bindKey: 'F11',
    exec: function(editor) {
      editor.container.toggleClass('fullsize');
      editor.resize();
    }
  });

  var updateTextarea = function() {
    ta.value = editor.getValue();
  };

  editor.getSession().on('change', updateTextarea);

  var updateHeight = function() {
    var newHeight =
      editor.getSession().getScreenLength()
      * editor.renderer.lineHeight
      + editor.renderer.scrollBar.getWidth();
    editor.container.setStyle('height', newHeight.toString() + 'px');
    editor.resize();
  };

  updateHeight();
  editor.getSession().on('change', updateHeight);
})();
</script>
<?php endforeach; ?>
<?php endif; ?>
