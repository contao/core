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
 * Class TextArea
 *
 * Provide methods to handle textareas.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class TextArea extends \Widget
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
	protected $blnForAttribute = true;

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
			case 'maxlength':
				if ($varValue > 0)
				{
					$this->arrAttributes['maxlength'] = $varValue;
				}
				break;

			case 'mandatory':
				if ($varValue)
				{
					$this->arrAttributes['required'] = 'required';
				}
				else
				{
					unset($this->arrAttributes['required']);
				}
				parent::__set($strKey, $varValue);
				break;

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
		// Register the field name for rich text editor usage
		if (strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['rte']))
		{
			list ($file, $type) = explode('|', $this->rte);
			$key = 'ctrl_' . $this->strId;

			$GLOBALS['TL_RTE'][$file][$key] = array
			(
				'id'   => $key,
				'file' => $file,
				'type' => $type
			);
		}

		return sprintf('<textarea name="%s" id="ctrl_%s" class="tl_textarea%s" rows="%s" cols="%s"%s onfocus="Backend.getScrollOffset()">%s</textarea>%s',
						$this->strName,
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						$this->intRows,
						$this->intCols,
						$this->getAttributes(),
						specialchars($this->varValue),
						$this->wizard);
	}
}
