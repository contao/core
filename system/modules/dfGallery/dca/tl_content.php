<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    DfGallery
 * @license    LGPL
 * @filesource
 */


/**
 * Add palette
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['dfGallery'] = '{type_legend},type,headline;{source_legend},singleSRC;{dfconfig_legend},alt,dfTitle,dfSize;{template_legend:hide},dfTemplate;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['dfTitle'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['dfTitle'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>64, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['dfSize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['dfSize'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['dfTemplate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['dfTemplate'],
	'default'                 => 'df_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => $this->getTemplateGroup('df_'),
	'save_callback' => array
	(
		array('tl_content_dfGallery', 'removeDfJsonFile')
	)
);


/**
 * Class tl_module_newsletter
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_content_dfGallery extends Backend
{

	/**
	 * Remove cached JSON files
	 * @param string
	 * @return string
	 */
	public function removeDfJsonFile($varValue)
	{
		$this->import('Files');

		$files = scan(TL_ROOT . '/system/html');
		$json = preg_grep('/\.json$/', $files);

		foreach ($json as $file)
		{
			$this->Files->delete('system/html/' . $file);
		}

		return $varValue;
	}
}

?>