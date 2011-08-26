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
 * This is the FancyUpload configuration file. For more information
 * please visit http://digitarald.de/project/fancyupload/.
 */
?>

<script>
window.addEvent('domready', function() {
  (function() {
    var phrases = {
      'progressOverall': '<?php echo $fancy->progressOverall; ?>',
      'currentTitle': '<?php echo $fancy->currentTitle; ?>',
      'currentFile': '<?php echo $fancy->currentFile; ?>',
      'currentProgress': '<?php echo $fancy->currentProgress; ?>',
      'fileName': '{name}',
      'remove': '<?php echo $fancy->remove; ?>',
      'removeTitle': '<?php echo $fancy->removeTitle; ?>',
      'fileError': '<?php echo $fancy->fileError; ?>',
      'uploadCompleted': '<?php echo $fancy->uploadCompleted; ?>',
      'validationErrors': {
        'duplicate': '<?php echo $fancy->duplicate; ?>',
        'sizeLimitMin': '<?php echo $fancy->sizeLimitMin; ?>',
        'sizeLimitMax': '<?php echo $fancy->sizeLimitMax; ?>',
        'fileListMax': '<?php echo $fancy->fileListMax; ?>',
        'fileListSizeMax': '<?php echo $fancy->fileListSizeMax; ?>'
      },
      'errors': {
        'httpStatus': '<?php echo $fancy->httpStatus; ?>',
        'securityError': '<?php echo $fancy->securityError; ?>',
        'ioError': '<?php echo $fancy->ioError; ?>'
      }
    };
    Locale.define('en-US', 'FancyUpload', phrases);
  })();
  var up = new FancyUpload2($('fancy-status'), $('fancy-list'), {
    'data': {
      'isPopup': <?php echo $fancy->isPopup; ?>,
      'FORM_SUBMIT': 'tl_upload',
      'REQUEST_TOKEN': '<?php echo REQUEST_TOKEN; ?>',
      'action': 'fancyUpload'
    },
    'appendCookieData': true,
    'url': $('<?php echo $this->strTable; ?>').action.replace('<?php echo $fancy->script; ?>', 'upload.php'),
    'path': 'plugins/fancyupload/Swiff.Uploader.swf',
    'typeFilter': {
      'Images (*.<?php echo implode(', *.', $fancy->uploadTypes); ?>)': '*.<?php echo implode('; *.', $fancy->uploadTypes); ?>'
    },
    'target': 'fancy-browse',
    'onLoad': function() {
      $('fancy-status').removeClass('fancy-hide');
      $('fancy-list').removeClass('fancy-hide');
      $('fancy-fallback').destroy();
      $('fancy-submit').destroy();
      $('fancy-clear').addEvent('click', function() {
        up.remove();
        return false;
      });
      $('fancy-upload').addEvent('click', function() {
        up.start();
        return false;
      });
    },
    'onSelectFail': function(files) {
      files.each(function(file) {
        new Element('li', {
          'class': 'validation-error',
          'html': file.validationErrorMessage || file.validationError,
          'title': MooTools.lang.get('FancyUpload', 'removeTitle'),
          'events': {
            'click': function() {
              this.destroy();
            }
          }
        }).inject(this.list, 'top');
      }, this);
    },
    'onFileSuccess': function(file, response) {
      var json = new Hash(JSON.decode(response, true) || {});
      if (json.get('status') == '1') {
        file.element.addClass('file-success');
        file.info.set('html', json.get('message'));
      } else {
        file.element.addClass('file-failed');
        file.info.set('html', json.get('message'));
      }
    },
    'onFail': function(error) {
      switch (error) {
        case 'hidden':
          alert('To enable the embedded uploader, unblock it in your browser and refresh (see Adblock).');
          break;
        case 'blocked':
          alert('To enable the embedded uploader, enable the blocked Flash movie (see Flashblock).');
          break;
        case 'empty':
          alert('A required file was not found, please be patient and we fix this.');
          break;
        case 'flash':
          alert('To enable the embedded uploader, install the latest Adobe Flash plugin.');
          break;
      }
    }
  });
});
</script>
