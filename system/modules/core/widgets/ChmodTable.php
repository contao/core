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
 * Class ChmodTable
 *
 * Provide methods to handle CHMOD tables.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ChmodTable extends \Widget
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
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$arrObjects = array('u'=>'cuser', 'g'=>'cgroup', 'w'=>'cworld');

		$return = '  <table id="ctrl_defaultChmod" class="tl_chmod">
    <tr>
      <th></th>
      <th scope="col">'.$GLOBALS['TL_LANG']['CHMOD']['editpage'].'</th>
      <th scope="col">'.$GLOBALS['TL_LANG']['CHMOD']['editnavigation'].'</th>
      <th scope="col">'.$GLOBALS['TL_LANG']['CHMOD']['deletepage'].'</th>
      <th scope="col">'.$GLOBALS['TL_LANG']['CHMOD']['editarticles'].'</th>
      <th scope="col">'.$GLOBALS['TL_LANG']['CHMOD']['movearticles'].'</th>
      <th scope="col">'.$GLOBALS['TL_LANG']['CHMOD']['deletearticles'].'</th>
    </tr>';

		// Build rows for user, group and world
		foreach ($arrObjects as $k=>$v)
		{
			$return .= '
    <tr>
      <th scope="row">'.$GLOBALS['TL_LANG']['CHMOD'][$v].'</th>';

			// Add checkboxes
			for ($j=1; $j<=6; $j++)
			{
				$return .= '
      <td><input type="checkbox" name="'.$this->strName.'[]" value="'.specialchars($k.$j).'"'.$this->optionChecked($k.$j, $this->varValue).' onfocus="Backend.getScrollOffset()"></td>';
			}

			$return .= '
    </tr>';
		}

		return $return.'
  </table>';
	}
}
