<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
		'__selector__'                => array('useSMTP'),
		'default'                     => '{title_legend},websiteTitle,adminEmail;{date_legend},dateFormat,timeFormat,datimFormat,timeZone;{global_legend:hide},websitePath,characterSet,customSections,disableCron,minifyMarkup,gzipScripts;{backend_legend},resultsPerPage,maxResultsPerPage,staticFiles,staticSystem,staticPlugins,doNotCollapse;{frontend_legend},urlSuffix,cacheMode,rewriteURL,disableAlias;{security_legend:hide},allowedTags,lockPeriod,encryptionKey,displayErrors,debugMode,disableRefererCheck,disableIpCheck;{files_legend:hide},allowedDownload,validImageTypes,editableFiles,templateFiles,maxImageWidth,jpgQuality,gdMaxImgWidth,gdMaxImgHeight;{uploads_legend:hide},uploadPath,uploadTypes,uploadFields,maxFileSize,imageWidth,imageHeight;{search_legend:hide},enableSearch,indexProtected;{smtp_legend:hide},useSMTP;{modules_legend},inactiveModules;{timeout_legend:hide},undoPeriod,versionPeriod,logPeriod,sessionTimeout,autologin;{chmod_legend:hide},defaultUser,defaultGroup,defaultChmod;{update_legend:hide},liveUpdateBase'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'useSMTP'                     => 'smtpHost,smtpUser,smtpPass,smtpEnc,smtpPort'
	),

	// Fields
	'fields' => array
	(
		'websiteTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['websiteTitle'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
		),
		'adminEmail' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['adminEmail'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'friendly', 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'dateFormat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['dateFormat'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'helpwizard'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'explanation'             => 'dateFormat'
		),
		'timeFormat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['timeFormat'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'datimFormat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['datimFormat'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'timeZone' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['timeZone'],
			'inputType'               => 'select',
			'options'                 => $this->getTimezones(),
			'eval'                    => array('tl_class'=>'w50')
		),
		'websitePath' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['websitePath'],
			'inputType'               => 'text',
			'eval'                    => array('nospace'=>'true', 'trailingSlash'=>false, 'tl_class'=>'w50')
		),
		'characterSet' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['characterSet'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'alnum', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'customSections' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['customSections'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50')
		),
		'disableCron' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['disableCron'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'minifyMarkup' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['minifyMarkup'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'gzipScripts' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['gzipScripts'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'resultsPerPage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['resultsPerPage'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_settings', 'checkResultsPerPage')
			)
		),
		'maxResultsPerPage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['maxResultsPerPage'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'staticFiles' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['staticFiles'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'trailingSlash'=>false, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_settings', 'checkStaticUrl')
			)
		),
		'staticSystem' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['staticSystem'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'trailingSlash'=>false, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_settings', 'checkStaticUrl')
			)
		),
		'staticPlugins' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['staticPlugins'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'trailingSlash'=>false, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_settings', 'checkStaticUrl')
			)
		),
		'doNotCollapse' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['doNotCollapse'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'urlSuffix' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['urlSuffix'],
			'inputType'               => 'text',
			'eval'                    => array('nospace'=>'true', 'tl_class'=>'w50')
		),
		'cacheMode' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['cacheMode'],
			'inputType'               => 'select',
			'options'                 => array('both', 'server', 'browser', 'none'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_settings'],
			'eval'                    => array('tl_class'=>'w50')
		),
		'rewriteURL' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['rewriteURL'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'disableAlias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['disableAlias'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'allowedTags' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['allowedTags'],
			'inputType'               => 'text',
			'eval'                    => array('preserveTags'=>true, 'tl_class'=>'long')
		),
		'lockPeriod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['lockPeriod'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'encryptionKey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['encryptionKey'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'minlength'=>12, 'tl_class'=>'w50')
		),
		'displayErrors' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['displayErrors'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'debugMode' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['debugMode'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_settings', 'regenerateScripts')
			)
		),
		'disableRefererCheck' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['disableRefererCheck'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'disableIpCheck' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['disableIpCheck'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'allowedDownload' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['allowedDownload'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50')
		),
		'validImageTypes' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['validImageTypes'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50')
		),
		'editableFiles' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['editableFiles'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50')
		),
		'templateFiles' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['templateFiles'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_settings', 'checkTemplateFiles')
			)
		),
		'maxImageWidth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['maxImageWidth'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'jpgQuality' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['jpgQuality'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'prcnt', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'gdMaxImgWidth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['gdMaxImgWidth'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'gdMaxImgHeight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['gdMaxImgHeight'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'uploadPath' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['uploadPath'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'trailingSlash'=>false, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_settings', 'checkUploadPath')
			)
		),
		'uploadTypes' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['uploadTypes'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50')
		),
		'uploadFields' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['uploadFields'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'maxFileSize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['maxFileSize'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'imageWidth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['imageWidth'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'imageHeight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['imageHeight'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'enableSearch' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['enableSearch'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'indexProtected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['indexProtected'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_settings', 'clearSearchIndex')
			)
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
			'eval'                    => array('mandatory'=>true, 'nospace'=>true, 'tl_class'=>'long')
		),
		'smtpUser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['smtpUser'],
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'smtpPass' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['smtpPass'],
			'inputType'               => 'textStore',
			'eval'                    => array('decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'smtpEnc' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['smtpEnc'],
			'inputType'               => 'select',
			'options'                 => array(''=>'-', 'ssl'=>'SSL', 'tls'=>'TLS'),
			'eval'                    => array('tl_class'=>'w50')
		),
		'smtpPort' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['smtpPort'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'inactiveModules' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['inactiveModules'],
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_settings', 'getModules'),
			'eval'                    => array('multiple'=>true)
		),
		'undoPeriod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['undoPeriod'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'versionPeriod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['versionPeriod'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'logPeriod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['logPeriod'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'sessionTimeout' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['sessionTimeout'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'autologin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['autologin'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'defaultUser' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['defaultUser'],
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.username',
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'defaultGroup' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['defaultGroup'],
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user_group.name',
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'defaultChmod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['defaultChmod'],
			'inputType'               => 'chmod',
			'eval'                    => array('tl_class'=>'clr')
		),
		'liveUpdateBase' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['liveUpdateBase'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'long')
		)
	)
);


/**
 * Class tl_settings
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
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

			$arrReturn[$strModule] = '<span style="color:#b3b3b3;">['. $strModule .']</span> ' . (is_array($GLOBALS['TL_LANG']['MOD'][$strModule]) ? $GLOBALS['TL_LANG']['MOD'][$strModule][0] : $GLOBALS['TL_LANG']['MOD'][$strModule]);
		}

		natcasesort($arrReturn);
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
	 * Make sure that resultsPerPage > 0
	 * @param mixed
	 * @return array
	 */
	public function checkResultsPerPage($varValue)
	{
		if ($varValue < 1)
		{
			$varValue = 30;
		}

		return $varValue;
	}


	/**
	 * Regenerate the CSS scripts when the debug mode changes
	 * @param mixed
	 * @return array
	 */
	public function regenerateScripts($varValue)
	{
		$GLOBALS['TL_CONFIG']['debugMode'] = $varValue;

		$this->import('Automator');
		$this->Automator->purgeScriptsFolder();

		return $varValue;
	}


	/**
	 * Make sure that "html5" is in the list of valid template
	 * files, so the back end works correctly (see #3398)
	 * @param mixed
	 * @return array
	 */
	public function checkTemplateFiles($varValue)
	{
		if (strpos($varValue, 'html5') === false)
		{
			$varValue .= (($varValue != '') ? ',' : '') . 'html5';
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


	/**
	 * Check a static URL
	 * @param mixed
	 * @return array
	 */
	public function checkStaticUrl($varValue)
	{
		if ($varValue != '' && !preg_match('@^https?://@', $varValue))
		{
			$varValue = ($this->Environment->ssl ? 'https://' : 'http://') . $varValue;
		}

		return $varValue;
	}
}

?>