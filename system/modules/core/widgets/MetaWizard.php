<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Widget;


/**
 * Class MetaWizard
 *
 * Provide methods to handle file meta information.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class MetaWizard extends Widget
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
	 * Trim the values and add new languages if necessary
	 * @param mixed
	 * @return mixed
	 */
	public function validator($varInput)
	{
		foreach ($varInput as $k=>$v)
		{
			if ($k != 'language')
			{
				$varInput[$k] = array_map('trim', $v);
			}
			else
			{
				if ($v != '')
				{
					$varInput[$v] = array('title'=>'', 'link'=>'', 'caption'=>'');
				}

				unset($varInput[$k]);
			}
		}

		return $varInput;
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		// Make sure there is at least an empty array
		if (!is_array($this->varValue) || empty($this->varValue))
		{
			$this->import('BackendUser', 'User');
			$this->varValue = array($this->User->language=>array()); // see #4188
		}

		$count = 0;
		$languages = $this->getLanguages();
		$taken = array();

		// Add the existing entries
		if (!empty($this->varValue))
		{
			$return = '<ul id="ctrl_'.$this->strId.'" class="tl_metawizard">';

			// Add the input fields
			foreach ($this->varValue as $lang=>$meta)
			{
				$return .= '
    <li class="'.(($count%2 == 0) ? 'even' : 'odd').'">';

				$return .= '<span class="lang">' . $languages[$lang] . ' ' . $this->generateImage('delete.gif', '', 'class="tl_metawizard_img" onclick="this.getParent(\'li\').destroy();$$(\'div.tip-wrap\').destroy()" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['aw_delete']).'"') . '</span>';
				$return .= '<label for="ctrl_title_'.$count.'">'.$GLOBALS['TL_LANG']['MSC']['aw_title'].'</label> <input type="text" name="'.$this->strId.'['.$lang.'][title]" id="ctrl_title_'.$count.'" class="tl_text" value="'.specialchars($meta['title']).'"><br>';
				$return .= '<label for="ctrl_link_'.$count.'">'.$GLOBALS['TL_LANG']['MSC']['aw_link'].'</label> <input type="text" name="'.$this->strId.'['.$lang.'][link]" id="ctrl_link_'.$count.'" class="tl_text" value="'.specialchars($meta['link']).'"><br>';
				$return .= '<label for="ctrl_caption_'.$count.'">'.$GLOBALS['TL_LANG']['MSC']['aw_caption'].'</label> <input type="text" name="'.$this->strId.'['.$lang.'][caption]" id="ctrl_caption_'.$count.'" class="tl_text" value="'.specialchars($meta['caption']).'">';

				$return .= '
    </li>';

				$taken[] = $lang;
				++$count;
			}

			$return .= '
  </ul>';
		}

		$options = array('<option value="">-</option>');

		// Add the remaining languages
		foreach ($languages as $k=>$v)
		{
			if (in_array($k, $taken))
			{
				continue;
			}

			$options[] = '<option value="'.$k.'">'.$v.'</option>';
		}

		$return .= '
  <div class="tl_metawizard_new">
    <select name="'.$this->strId.'[language]" class="tl_select tl_chosen" onchange="Backend.toggleAddLanguageButton(this)">'.implode('', $options).'</select> <input type="button" class="tl_submit" disabled value="'.specialchars($GLOBALS['TL_LANG']['MSC']['aw_new']).'" onclick="Backend.metaWizard(this,\'ctrl_'.$this->strId.'\')">
  </div>';

		return $return;
	}
}
