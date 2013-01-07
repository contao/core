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
 * Class PageTree
 *
 * Provide methods to handle input field "page tree".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class PageTree extends \Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Load the database object
	 * @param array
	 */
	public function __construct($arrAttributes=null)
	{
		$this->import('Database');
		parent::__construct($arrAttributes);
	}


	/**
	 * Return an array if the "multiple" attribute is set
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		if ($varInput == '')
		{
			if (!$this->mandatory)
			{
				return '';
			}
			else
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory'], $this->strLabel));
			}
		}
		elseif (strpos($varInput, ',') === false)
		{
			return $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['multiple'] ? array(intval($varInput)) : intval($varInput);
		}
		else
		{
			return array_map('intval', array_filter(explode(',', $varInput)));
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$strValues = '';
		$arrValues = array();

		if (!empty($this->varValue)) // Can be an array
		{
			$strValues = implode(',', array_map('intval', (array)$this->varValue));
			$objPages = $this->Database->execute("SELECT id, title, alias, type, hide, protected, published, start, stop FROM tl_page WHERE id IN($strValues) ORDER BY " . $this->Database->findInSet('id', $strValues));

			while ($objPages->next())
			{
				$arrValues[] = $this->generateImage($this->getPageStatusIcon($objPages)) . ' ' . $objPages->title . ' (' . $objPages->alias . $GLOBALS['TL_CONFIG']['urlSuffix'] . ')';
			}
		}

		return '<input type="hidden" name="'.$this->strName.'" id="ctrl_'.$this->strId.'" value="'.$strValues.'">
  <div class="selector_container" id="target_'.$this->strId.'">
    <ul><li>' . implode('</li><li>', $arrValues) . '</li></ul>
    <p><a href="contao/page.php?do='.\Input::get('do').'&amp;table='.$this->strTable.'&amp;field='.$this->strField.'&amp;act=show&amp;id='.\Input::get('id').'&amp;value='.$strValues.'&rt='.REQUEST_TOKEN.'" class="tl_submit" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\''.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])).'\',\'url\':this.href,\'id\':\''.$this->strId.'\'});return false">'.$GLOBALS['TL_LANG']['MSC']['changeSelection'].'</a></p>
  </div>';
	}
}
