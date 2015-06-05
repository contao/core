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
 * Provide methods to handle file meta information.
 *
 * @property array $metaFields
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 *
	 * @param mixed $varInput
	 *
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
	 *
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
		$return = '';
		$taken = array();

		$this->import('Database');

		// Only show the root page languages (see #7112, #7667)
		$objRootLangs = $this->Database->query("SELECT REPLACE(language, '-', '_') AS language FROM tl_page WHERE type='root'");
		$languages = array_intersect_key($languages, array_flip($objRootLangs->fetchEach('language')));

		// Add the existing entries
		if (!empty($this->varValue))
		{
			$return = '<ul id="ctrl_' . $this->strId . '" class="tl_metawizard">';

			// Add the input fields
			foreach ($this->varValue as $lang=>$meta)
			{
				$return .= '
    <li class="' . (($count%2 == 0) ? 'even' : 'odd') . '" data-language="' . $lang . '">';

				$return .= '<span class="lang">' . $languages[$lang] . ' ' . \Image::getHtml('delete.gif', '', 'class="tl_metawizard_img" onclick="Backend.metaDelete(this)"') . '</span>';

				// Take the fields from the DCA (see #4327)
				foreach ($this->metaFields as $field)
				{
					$return .= '<label for="ctrl_' . $field . '_' . $count . '">' . $GLOBALS['TL_LANG']['MSC']['aw_' . $field] . '</label> <input type="text" name="' . $this->strId . '[' . $lang . '][' . $field . ']" id="ctrl_' . $field . '_' . $count . '" class="tl_text" value="' . specialchars($meta[$field]) . '"><br>';
				}

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
    <select name="' . $this->strId . '[language]" class="tl_select tl_chosen" onchange="Backend.toggleAddLanguageButton(this)">' . implode('', $options) . '</select> <input type="button" class="tl_submit" disabled value="' . specialchars($GLOBALS['TL_LANG']['MSC']['aw_new']) . '" onclick="Backend.metaWizard(this,\'ctrl_' . $this->strId . '\')">
  </div>';

		return $return;
	}
}
