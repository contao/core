<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class DropZone
 *
 * Provide methods to handle file uploads in the back end.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class DropZone extends \FileUpload
{

	/**
	 * Generate the markup for the default uploader
	 * @return string
	 */
	public function generateMarkup()
	{
		// Maximum file size in MB
		$intMaxSize = ($this->getMaximumUploadSize() / 1024000);

		// String of accepted file extensions
		$strAccepted = implode(',', array_map(function($a) { return '.' . $a; }, trimsplit(',', strtolower(\Config::get('uploadTypes')))));

		// Add the scripts
		$GLOBALS['TL_CSS'][] = 'assets/dropzone/' . $GLOBALS['TL_ASSETS']['DROPZONE'] . '/css/dropzone.min.css';
		$GLOBALS['TL_JAVASCRIPT'][] = 'assets/dropzone/' . $GLOBALS['TL_ASSETS']['DROPZONE'] . '/js/dropzone.min.js';

		// Generate the markup
		return '
  <input type="hidden" name="action" value="fileupload">
  <div class="fallback">
    <input type="file" name="' . $this->strName . '[]" multiple>
  </div>
  <div class="dz-container">
    <div class="dz-default dz-message">
      <span>' . $GLOBALS['TL_LANG']['tl_files']['dropzone'] . '</span>
    </div>
    <div class="dropzone-previews"></div>
  </div>
  <script>
    window.addEvent("domready", function() {
      new Dropzone("#tl_files", {
        paramName: "' . $this->strName . '",
        maxFilesize: ' . $intMaxSize . ',
        acceptedFiles: "' . $strAccepted . '",
        previewsContainer: ".dropzone-previews",
        uploadMultiple: true
      }).on("processing", function() {
        $$(".dz-message").setStyle("padding", "12px 18px 0");
      });
      $$("div.tl_formbody_submit").setStyle("display", "none");
    });
  </script>';
	}
}
