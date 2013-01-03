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
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class Upload
 *
 * Provide methods to use the FileUpload class in a back end widget. The widget
 * will only upload the files to the server. Use a submit_callback to process
 * the files or use the class as base for your own upload widget.
 * 
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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
	 * @param array
	 */
	public function __construct($arrAttributes=null)
	{
		parent::__construct($arrAttributes);

		$this->objUploader = new \FileUpload();
		$this->objUploader->setName($this->strName);
	}


	/**
	 * Trim values
	 * @param mixed
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

		$this->objUploader->uploadTo($strUploadTo);
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		return ltrim($this->objUploader->generateMarkup());
	}
}
