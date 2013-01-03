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
 * Class MetaWizard
 *
 * Provide methods to handle file meta information.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class MetaWizard extends \Widget
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
    <li class="'.(($count%2 == 0) ? 'even' : 'odd').'" data-language="' . $lang . '">';

				$return .= '<span class="lang">' . $languages[$lang] . ' ' . $this->generateImage('delete.gif', '', 'class="tl_metawizard_img" onclick="Backend.metaDelete(this)" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['aw_delete']).'"') . '</span>';
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
			$options[] = '<option value="' . $k . '"' . (in_array($k, $taken) ? ' disabled' : '') . '>' . $v . '</option>';
		}

		$return .= '
  <div class="tl_metawizard_new">
    <select name="'.$this->strId.'[language]" class="tl_select tl_chosen" onchange="Backend.toggleAddLanguageButton(this)">'.implode('', $options).'</select> <input type="button" class="tl_submit" disabled value="'.specialchars($GLOBALS['TL_LANG']['MSC']['aw_new']).'" onclick="Backend.metaWizard(this,\'ctrl_'.$this->strId.'\')">
  </div>';

		return $return;
	}
}
