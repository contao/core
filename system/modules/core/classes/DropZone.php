<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Provide methods to handle file uploads in the back end.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class DropZone extends \FileUpload
{

	/**
	 * Generate the markup for the default uploader
     *
	 * @return string
	 */
	public function generateMarkup()
	{
		// Maximum file size in MB
		$intMaxSize = ($this->getMaximumUploadSize() / 1024 / 1024);

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
  </script>
  <p class="tl_help tl_tip">' . sprintf($GLOBALS['TL_LANG']['tl_files']['fileupload'][1], \System::getReadableSize($this->getMaximumUploadSize()), \Config::get('gdMaxImgWidth') . 'x' . \Config::get('gdMaxImgHeight')) . '</p>';
	}
}
