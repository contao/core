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
 * Class Upload
 *
 * Provide methods to use the FileUpload class in a back end widget. The widget
 * will only upload the files to the server. Use a submit_callback to process
 * the files or use the class as base for your own upload widget.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Upload extends \Widget implements \uploadable
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Add a for attribute
	 * @var boolean
	 */
	protected $blnForAttribute = false;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';

	/**
	 * Uploader
	 * @var \FileUpload
	 */
	protected $objUploader;


	/**
	 * Initialize the FileUpload object
	 *
	 * @param array $arrAttributes
	 */
	public function __construct($arrAttributes=null)
	{
		parent::__construct($arrAttributes);

		$this->objUploader = new \FileUpload();
		$this->objUploader->setName($this->strName);
	}


	/**
	 * Trim values
	 *
	 * @param mixed $varInput
	 *
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		$strUploadTo = 'system/tmp';

		// Specify the target folder in the DCA (eval)
		if (isset($this->arrConfiguration['uploadFolder']))
		{
			$strUploadTo = $this->arrConfiguration['uploadFolder'];
		}

		return $this->objUploader->uploadTo($strUploadTo);
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string
	 */
	public function generate()
	{
		return ltrim($this->objUploader->generateMarkup());
	}
}
