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
 * Class UploaderFancyUpload
 *
 * Provider for the fancy upload script
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch>
 * @package    Library
 */
class UploaderFancyUpload extends Uploader
{
	/**
	 * True if images have been resized
	 * @var boolean
	 */
	protected $blnResized = false;
	
	/**
	 * True if images have exceeded the maximum file size
	 * @var boolean
	 */
	protected $blnExceeds = false;
	
	
	/**
	 * Generate the HTML view
	 * @return string
	 */
	public function generate()
	{
		$GLOBALS['TL_CSS'][] = TL_PLUGINS_URL . 'plugins/fancyupload/css/fancyupload.css|screen';
		$GLOBALS['TL_JAVASCRIPT'][] = TL_PLUGINS_URL . 'plugins/fancyupload/js/fancyupload.js';

		$fancy = new stdClass();

		// Add upload types and key
		$fancy->uploadTypes = $this->arrUploadTypes;
		$fancy->script = basename($this->Environment->script);
		$fancy->isPopup = ($fancy->script == 'files.php') ? 'true' : 'false';

		// Add labels
		foreach ($GLOBALS['TL_LANG'][$this->objDC->table] as $k=>$v)
		{
			list($prefix, $key) = explode('_', $k);

			if ($prefix == 'fancy')
			{
				$fancy->$key = $v;
			}
		}

		// Set upload script
		$uploadScript = sprintf('%s/system/config/%s.php', TL_ROOT, basename($GLOBALS['TL_DCA'][$this->objDC->table]['config']['uploadScript']));
		
		$strFancyUpload = '';
		ob_start();
		require($uploadScript);
		$strFancyUpload = ob_get_contents();
		ob_end_clean();
		
		
		$objTemplate = new BackendTemplate('be_upload_fancyupload');
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
		$objTemplate->browseFiles = $GLOBALS['TL_LANG'][$this->objDC->table]['browseFiles'];
		$objTemplate->clearList = $GLOBALS['TL_LANG'][$this->objDC->table]['clearList'];
		$objTemplate->startUpload = $GLOBALS['TL_LANG'][$this->objDC->table]['startUpload'];
		$objTemplate->fileUploadHint = $GLOBALS['TL_LANG'][$this->objDC->table]['fileupload'][1];		
		$objTemplate->fancyUploadScript = $strFancyUpload;

		// Display upload form
		return $objTemplate->parse();
	}
	
	
	/**
	 * Return information via ajax
	 */
	public function generateAjax()
	{
		if ($this->hasErrors())
		{
			echo json_encode(array('status'=>'0', 'message'=>$_SESSION['TL_ERROR'][0]));
		}
		elseif ($this->blnExceeds || $this->blnResized)
		{
			echo json_encode(array('status'=>'1', 'message'=>$_SESSION['TL_INFO'][0]));
		}
		else
		{
			echo json_encode(array('status'=>'1', 'message'=>$_SESSION['TL_CONFIRM'][0]));
		}

		$this->resetMessages();

		exit;
	}
	
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
			
			if($arrResult['exceeds'])
			{
				// store this value because we need it in generateAjax()
				$this->blnExceeds = true;
			}

			// now the file is correctly uploaded
			$this->addConfirmationMessage(sprintf($GLOBALS['TL_LANG']['MSC']['fileUploaded'], $file['name']));
			$this->log('File "'.$file['name'].'" uploaded successfully', __METHOD__, TL_FILES);
			
			// we add the final path to the uploaded files array for the postUpload hook
			$this->addUploadedFile($strNewFile);
		}
	}


	/**
	 * We don't need to do anything post upload
	 */
	public function postUpload() {}
}

?>