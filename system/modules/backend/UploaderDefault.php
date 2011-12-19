<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class UploaderDefault
 *
 * Provider for the default uploader view
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch>
 * @package    Library
 */
class UploaderDefault extends Uploader
{
	/**
	 * True if images have been resized
	 * @var boolean
	 */
	protected $blnResized = false;
	
	
	/**
	 * Generate the HTML view
	 * @return string
	 */
	public function generate()
	{
		$fields = '';

		// Upload fields
		for ($i=0; $i<$GLOBALS['TL_CONFIG']['uploadFields']; $i++)
		{
			$fields .= '<input type="file" name="'.$i.'" class="tl_upload_field" onfocus="Backend.getScrollOffset();"><br>';
		}
		
		$objTemplate = new BackendTemplate('be_upload_default');
		$objTemplate->backLink = $this->getReferer(true);
		$objTemplate->backTitle = specialchars($GLOBALS['TL_LANG']['MSC']['backBT']);
		$objTemplate->back = $GLOBALS['TL_LANG']['MSC']['backBT'];
		$objTemplate->hl = sprintf($GLOBALS['TL_LANG'][$this->objDC->table]['uploadFF'], basename($this->strDestinationFolder));
		$objTemplate->messages = $this->getMessages(true);
		$objTemplate->action = ampersand($this->Environment->request, true);
		$objTemplate->formId = $this->objDC->table;
		$objTemplate->onsubmit = (count($this->objDC->onsubmit) ? ' onsubmit="'.implode(' ', $this->onsubmit).'"' : '');
		$objTemplate->maxFileSize = $GLOBALS['TL_CONFIG']['maxFileSize'];
		$objTemplate->fileUpload = $GLOBALS['TL_LANG'][$this->objDC->table]['fileupload'][0];
		$objTemplate->fields = $fields;
		$objTemplate->upload = specialchars($GLOBALS['TL_LANG'][$this->objDC->table]['upload']);
		$objTemplate->uploadNback = specialchars($GLOBALS['TL_LANG'][$this->objDC->table]['uploadNback']);

		// Display upload form
		return $objTemplate->parse();
	}

	
	/**
	 * The default uploader has no ajax actions
	 */
	public function generateAjax() {}
	
	
	/**
	 * Check whether the form has been submitted
	 * @return boolean
	 */
	public function invokeUpload()
	{
		return ($this->Input->post('FORM_SUBMIT') == 'tl_upload');
	}
	
	
	/**
	 * Upload process
	 */
	public function upload()
	{
		$arrFiles = $this->getFilesRomanized();
		
		foreach($arrFiles as $file)
		{
			// check if the file upload has been complete
			if (!$this->fileCorrectlyUploaded($file))
			{
				continue;
			}
			
			// file was uploaded correctly but still it can exceed the file size so we check that
			if (!$this->checkFileSize($file))
			{
				continue;
			}
			
			// now that the file is on the server we can check if it's a valid file type
			if (!$this->checkFileType($file))
			{
				continue;
			}
			
			// file is uploaded and allowed so we move it to its destination
			$strNewFile = $this->strDestinationFolder . '/' . $file['name'];
			
			if (!$this->moveUploadedFile($file, $strNewFile))
			{
				continue;
			}
			
			// file is at its place but maybe its too big so we have to resize it
			$arrResult = $this->resizeImageToSystemLimits($file, $strNewFile);
			if($arrResult['resized'])
			{
				// store this value because we need it in postUpload()
				$this->blnResized = true;
			}

			// now the file is correctly uploaded
			$this->addConfirmationMessage(sprintf($GLOBALS['TL_LANG']['MSC']['fileUploaded'], $file['name']));
			$this->log('File "'.$file['name'].'" uploaded successfully', __METHOD__, TL_FILES);
			
			// we add the final path to the uploaded files array for the postUpload hook
			$this->addUploadedFile($strNewFile);
		}
	}

	
	/**
	 * Redirect after uploading if the user clicked "saveNclose"
	 */
	public function postUpload()
	{
		// Do not purge the html folder (see #2898)

		if ($this->Input->post('uploadNback') && !$this->blnResized)
		{
			$this->resetMessages();
			$this->redirect($this->getReferer());
		}

		$this->reload();
	}
}

?>