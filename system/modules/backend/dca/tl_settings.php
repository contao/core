<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
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
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_settings'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'File',
		'closed'                      => true
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('useSMTP', 'useFTP', 'enableSearch'),
		'default'                     => 'websiteTitle,adminEmail;dateFormat,timeFormat,datimFormat,timeZone;websitePath,characterSet,urlSuffix,encryptionKey,customSections,maxImageWidth,resultsPerPage;rewriteURL,disableAlias,displayErrors,debugMode,disableRefererCheck,useDompdf,enableGZip;editableFiles,validImageTypes,allowedDownload,uploadTypes,allowedTags;enableSearch;useSMTP;useFTP;uploadPath,maxFileSize,imageWidth,imageHeight,jpgQuality,uploadFields;backendTheme,inactiveModules,doNotCollapse,pNewLine;undoPeriod,versionPeriod,logPeriod,sessionTimeout,lockPeriod;defaultUser,defaultGroup,defaultChmod'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'useSMTP'                     => 'smtpHost,smtpPort,smtpUser,smtpPass',
		'useFTP'                      => 'ftpHost,ftpUser,ftpPass,ftpPath',
		'enableSearch'                => 'indexProtected'
	),

	// Fields
	'fields' => array
	(
		'websiteTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['websiteTitle'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true)
		),
		'adminEmail' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['adminEmail'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'email')
		),
		'websitePath' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['websitePath'],
			'inputType'               => 'text',
			'eval'                    => array('nospace'=>'true', 'trailingSlash'=>false)
		),
		'urlSuffix' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['urlSuffix'],
			'inputType'               => 'text',
			'eval'                    => array('nospace'=>'true', 'rgxp'=>'url')
		),
		'dateFormat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['dateFormat'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'helpwizard'=>true),
			'explanation'             => 'dateFormat'

		),
		'timeFormat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['timeFormat'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true)
		),
		'datimFormat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['datimFormat'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true)
		),
		'timeZone' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['timeZone'],
			'inputType'               => 'select',
			'options'                 => $this->getTimezones()
		),
		'characterSet' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['characterSet'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'alnum', 'nospace'=>true)
		),
		'encryptionKey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['encryptionKey'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'minlength'=>8)
		),
		'uploadPath' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['uploadPath'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'trailingSlash'=>false),
			'save_callback' => array
			(
				array('tl_settings', 'checkUploadPath')
			)
		),
		'maxFileSize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['maxFileSize'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'imageWidth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['imageWidth'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'imageHeight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['imageHeight'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'uploadFields' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['uploadFields'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'undoPeriod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['undoPeriod'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'versionPeriod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['versionPeriod'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'logPeriod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['logPeriod'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'sessionTimeout' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['sessionTimeout'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'lockPeriod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['lockPeriod'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'useSMTP' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['useSMTP'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'smtpHost' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['smtpHost'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'nospace'=>true)
		),
		'smtpPort' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['smtpPort'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'smtpUser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['smtpUser'],
			'inputType'               => 'text'
		),
		'smtpPass' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['smtpPass'],
			'inputType'               => 'text'
		),
		'useFTP' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['useFTP'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'ftpHost' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['ftpHost'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'nospace'=>true)
		),
		'ftpUser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['ftpUser'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true)
		),
		'ftpPass' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['ftpPass'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true)
		),
		'ftpPath' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['ftpPath'],
			'inputType'               => 'text',
			'eval'                    => array('trailingSlash'=>true, 'nospace'=>true)
		),
		'customSections' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['customSections'],
			'inputType'               => 'text'
		),
		'maxImageWidth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['maxImageWidth'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true)
		),
		'jpgQuality' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['jpgQuality'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'prcnt', 'nospace'=>true)
		),
		'validImageTypes' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['validImageTypes'],
			'inputType'               => 'text'
		),
		'allowedDownload' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['allowedDownload'],
			'inputType'               => 'text'
		),
		'allowedTags' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['allowedTags'],
			'inputType'               => 'text',
			'eval'                    => array('preserveTags'=>true)
		),
		'uploadTypes' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['uploadTypes'],
			'inputType'               => 'text'
		),
		'editableFiles' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['editableFiles'],
			'inputType'               => 'text'
		),
		'displayErrors' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['displayErrors'],
			'inputType'               => 'checkbox'
		),
		'debugMode' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['debugMode'],
			'inputType'               => 'checkbox'
		),
		'rewriteURL' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['rewriteURL'],
			'inputType'               => 'checkbox'
		),
		'disableRefererCheck' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['disableRefererCheck'],
			'inputType'               => 'checkbox'
		),
		'disableAlias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['disableAlias'],
			'inputType'               => 'checkbox'
		),
		'enableGZip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['enableGZip'],
			'inputType'               => 'checkbox'
		),
		'useDompdf' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['useDompdf'],
			'inputType'               => 'checkbox'
		),
		'resultsPerPage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['resultsPerPage'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'enableSearch' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['enableSearch'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'indexProtected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['indexProtected'],
			'inputType'               => 'checkbox',
			'save_callback' => array
			(
				array('tl_settings', 'clearSearchIndex')
			)
		),
		'pNewLine' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['pNewLine'],
			'inputType'               => 'checkbox'
		),
		'backendTheme' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['backendTheme'],
			'inputType'               => 'select',
			'options_callback'        => array('tl_settings', 'getThemes')
		),
		'doNotCollapse' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['doNotCollapse'],
			'inputType'               => 'checkbox'
		),
		'inactiveModules' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['inactiveModules'],
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_settings', 'getModules'),
			'eval'                    => array('multiple'=>true)
		),
		'defaultUser' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['defaultUser'],
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.username',
			'eval'                    => array('includeBlankOption'=>true)
		),
		'defaultGroup' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['defaultGroup'],
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user_group.name',
			'eval'                    => array('includeBlankOption'=>true)
		),
		'defaultChmod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['defaultChmod'],
			'inputType'               => 'chmod'
		)
	)
);


/**
 * Class tl_settings
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_settings extends Backend
{

	/**
	 * Return all modules except back end and front end as array
	 * @return array
	 */
	public function getModules()
	{
		$arrReturn = array();
		$arrModules = scan(TL_ROOT . '/system/modules');

		$arrInactiveModules = deserialize($GLOBALS['TL_CONFIG']['inactiveModules']);
		$blnCheckInactiveModules = is_array($arrInactiveModules);

		foreach ($arrModules as $strModule)
		{
			if (substr($strModule, 0, 1) == '.')
			{
				continue;
			}

			if ($strModule == 'backend' || $strModule == 'frontend' || !is_dir(TL_ROOT . '/system/modules/' . $strModule))
			{
				continue;
			}

			if ($blnCheckInactiveModules && in_array($strModule, $arrInactiveModules))
			{
				$strFile = sprintf('%s/system/modules/%s/languages/%s/modules.php', TL_ROOT, $strModule, $GLOBALS['TL_LANGUAGE']);

				if (file_exists($strFile))
				{
					include($strFile);
				}
			}

			$arrReturn[$strModule] = strlen(($label = is_array($GLOBALS['TL_LANG']['MOD'][$strModule]) ? $GLOBALS['TL_LANG']['MOD'][$strModule][0] : $GLOBALS['TL_LANG']['MOD'][$strModule])) ? $label : $strModule;
		}

		natcasesort($arrReturn);
		return $arrReturn;
	}


	/**
	 * Return all back end themes as array
	 * @return array
	 */
	public function getThemes()
	{
		$arrReturn = array();
		$arrThemes = scan(TL_ROOT . '/system/themes');

		foreach ($arrThemes as $strTheme)
		{
			if (substr($strTheme, 0, 1) == '.' || !is_dir(TL_ROOT . '/system/themes/' . $strTheme))
			{
				continue;
			}

			$arrReturn[$strTheme] = $strTheme;
		}

		return $arrReturn;
	}


	/**
	 * Remove protected search results if the feature is being disabled
	 * @param mixed
	 * @return array
	 */
	public function clearSearchIndex($varValue)
	{
		if (!$varValue)
		{
			$this->Database->execute("DELETE FROM tl_search WHERE protected=1");
		}

		return $varValue;
	}


	/**
	 * Check the upload path
	 * @param mixed
	 * @return array
	 */
	public function checkUploadPath($varValue)
	{
		$varValue = str_replace(array('../', '/..', '/.', './', '://'), '', $varValue);

		if ($varValue == '.' || $varValue == '..' || $varValue == '')
		{
			$varValue = 'tl_files';
		}

		return $varValue;
	}
}

?>