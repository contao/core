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
 * Class TableWizard
 *
 * Provide methods to handle table fields.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class TableWizard extends \Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Rows
	 * @var integer
	 */
	protected $intRows = 12;

	/**
	 * Columns
	 * @var integer
	 */
	protected $intCols = 80;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'rows':
				$this->intRows = $varValue;
				break;

			case 'cols':
				$this->intCols = $varValue;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$arrColButtons = array('ccopy', 'cmovel', 'cmover', 'cdelete');
		$arrRowButtons = array('rcopy', 'rup', 'rdown', 'rdelete');

		$strCommand = 'cmd_' . $this->strField;

		// Change the order
		if (\Input::get($strCommand) && is_numeric(\Input::get('cid')) && \Input::get('id') == $this->currentRecord)
		{
			$this->import('Database');

			switch (\Input::get($strCommand))
			{
					case 'ccopy':
					for ($i=0; $i<count($this->varValue); $i++)
					{
						$this->varValue[$i] = array_duplicate($this->varValue[$i], \Input::get('cid'));
					}
					break;

				case 'cmovel':
					for ($i=0; $i<count($this->varValue); $i++)
					{
						$this->varValue[$i] = array_move_up($this->varValue[$i], \Input::get('cid'));
					}
					break;

				case 'cmover':
					for ($i=0; $i<count($this->varValue); $i++)
					{
						$this->varValue[$i] = array_move_down($this->varValue[$i], \Input::get('cid'));
					}
					break;

				case 'cdelete':
					for ($i=0; $i<count($this->varValue); $i++)
					{
						$this->varValue[$i] = array_delete($this->varValue[$i], \Input::get('cid'));
					}
					break;

				case 'rcopy':
					$this->varValue = array_duplicate($this->varValue, \Input::get('cid'));
					break;

				case 'rup':
					$this->varValue = array_move_up($this->varValue, \Input::get('cid'));
					break;

				case 'rdown':
					$this->varValue = array_move_down($this->varValue, \Input::get('cid'));
					break;

				case 'rdelete':
					$this->varValue = array_delete($this->varValue, \Input::get('cid'));
					break;
			}

			$this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
						   ->execute(serialize($this->varValue), $this->currentRecord);

			$this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '', \Environment::get('request'))));
		}

		// Make sure there is at least an empty array
		if (!is_array($this->varValue) || empty($this->varValue))
		{
			$this->varValue = array(array(''));
		}

		// Begin the table
		$return = '<div id="tl_tablewizard">
  <table id="ctrl_'.$this->strId.'" class="tl_tablewizard">
  <tbody>
    <tr>';

		// Add column buttons
		for ($i=0; $i<count($this->varValue[0]); $i++)
		{
			$return .= '
      <td style="text-align:center; white-space:nowrap">';

			// Add column buttons
			foreach ($arrColButtons as $button)
			{
				$return .= '<a href="'.$this->addToUrl('&amp;'.$strCommand.'='.$button.'&amp;cid='.$i.'&amp;id='.$this->currentRecord).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['tw_'.$button]).'" onclick="Backend.tableWizard(this,\''.$button.'\',\'ctrl_'.$this->strId.'\');return false">'.$this->generateImage(substr($button, 1).'.gif', $GLOBALS['TL_LANG']['MSC']['tw_'.$button], 'class="tl_tablewizard_img"').'</a> ';
			}

			$return .= '</td>';
		}

		$return .= '
      <td></td>
    </tr>';

		$tabindex = 0;

		// Add rows
		for ($i=0; $i<count($this->varValue); $i++)
		{
			$return .= '
    <tr>';

			// Add cells
			for ($j=0; $j<count($this->varValue[$i]); $j++)
			{
				$return .= '
      <td class="tcontainer"><textarea name="'.$this->strId.'['.$i.']['.$j.']" class="tl_textarea" tabindex="'.++$tabindex.'" rows="'.$this->intRows.'" cols="'.$this->intCols.'"'.$this->getAttributes().'>'.specialchars($this->varValue[$i][$j]).'</textarea></td>';
			}

			$return .= '
      <td style="white-space:nowrap">';

			// Add row buttons
			foreach ($arrRowButtons as $button)
			{
				$return .= '<a href="'.$this->addToUrl('&amp;'.$strCommand.'='.$button.'&amp;cid='.$i.'&amp;id='.$this->currentRecord).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['tw_'.$button]).'" onclick="Backend.tableWizard(this,\''.$button.'\',\'ctrl_'.$this->strId.'\');return false">'.$this->generateImage(substr($button, 1).'.gif', $GLOBALS['TL_LANG']['MSC']['tw_'.$button], 'class="tl_tablewizard_img"').'</a> ';
			}

			$return .= '</td>
    </tr>';
		}

		$return .= '
  </tbody>
  </table>
  </div>
  <script>
  Backend.tableWizardResize();
  </script>';

		return $return;
	}


	/**
	 * Return a form to choose a CSV file and import it
	 * @param \DataContainer
	 * @return string
	 */
	public function importTable(\DataContainer $dc)
	{
		if (\Input::get('key') != 'table')
		{
			return '';
		}

		$this->import('BackendUser', 'User');
		$class = $this->User->uploader;

		// See #4086
		if (!class_exists($class))
		{
			$class = 'FileUpload';
		}

		$objUploader = new $class();

		// Import CSS
		if (\Input::post('FORM_SUBMIT') == 'tl_table_import')
		{
			$arrUploaded = $objUploader->uploadTo('system/tmp');

			if (empty($arrUploaded))
			{
				\Message::addError($GLOBALS['TL_LANG']['ERR']['all_fields']);
				$this->reload();
			}

			$this->import('Database');
			$arrTable = array();

			foreach ($arrUploaded as $strCsvFile)
			{
				$objFile = new \File($strCsvFile);

				if ($objFile->extension != 'csv')
				{
					\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension));
					continue;
				}

				// Get separator
				switch (\Input::post('separator'))
				{
					case 'semicolon':
						$strSeparator = ';';
						break;

					case 'tabulator':
						$strSeparator = "\t";
						break;

					default:
						$strSeparator = ',';
						break;
				}

				$resFile = $objFile->handle;

				while(($arrRow = @fgetcsv($resFile, null, $strSeparator)) !== false)
				{
					$arrTable[] = $arrRow;
				}
			}

			$this->createNewVersion($dc->table, \Input::get('id'));

			$this->Database->prepare("UPDATE " . $dc->table . " SET tableitems=? WHERE id=?")
						   ->execute(serialize($arrTable), \Input::get('id'));

			setcookie('BE_PAGE_OFFSET', 0, 0, '/');
			$this->redirect(str_replace('&key=table', '', \Environment::get('request')));
		}

		// Return form
		return '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=table', '', \Environment::get('request'))).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['MSC']['tw_import'][1].'</h2>
'.\Message::generate().'
<form action="'.ampersand(\Environment::get('request'), true).'" id="tl_table_import" class="tl_form" method="post" enctype="multipart/form-data">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_table_import">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">

<div class="tl_tbox">
  <h3><label for="separator">'.$GLOBALS['TL_LANG']['MSC']['separator'][0].'</label></h3>
  <select name="separator" id="separator" class="tl_select" onfocus="Backend.getScrollOffset()">
    <option value="comma">'.$GLOBALS['TL_LANG']['MSC']['comma'].'</option>
    <option value="semicolon">'.$GLOBALS['TL_LANG']['MSC']['semicolon'].'</option>
    <option value="tabulator">'.$GLOBALS['TL_LANG']['MSC']['tabulator'].'</option>
  </select>'.(($GLOBALS['TL_LANG']['MSC']['separator'][1] != '') ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['separator'][1].'</p>' : '').'
  <h3>'.$GLOBALS['TL_LANG']['MSC']['source'][0].'</h3>'.$objUploader->generateMarkup().(isset($GLOBALS['TL_LANG']['MSC']['source'][1]) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['source'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['tw_import'][0]).'">
</div>

</div>
</form>';
	}
}
