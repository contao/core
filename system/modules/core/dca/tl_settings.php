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
		'default'                     => '{title_legend},websiteTitle,adminEmail;{date_legend},dateFormat,timeFormat,datimFormat,timeZone;{global_legend:hide},websitePath,characterSet,minifyMarkup,gzipScripts,disableCron,coreOnlyMode;{backend_legend},resultsPerPage,maxResultsPerPage,fileSyncExclude,doNotCollapse,staticFiles,staticPlugins;{frontend_legend},urlSuffix,cacheMode,rewriteURL,useAutoItem,addLanguageToUrl,doNotRedirectEmpty,folderUrl,disableAlias;{privacy_legend:hide},privacyAnonymizeIp,privacyAnonymizeGA;{security_legend:hide},allowedTags,debugMode,bypassCache,displayErrors,logErrors,disableRefererCheck,disableIpCheck;{files_legend:hide},allowedDownload,validImageTypes,editableFiles,templateFiles,maxImageWidth,jpgQuality,gdMaxImgWidth,gdMaxImgHeight;{uploads_legend:hide},uploadPath,uploadTypes,uploadFields,maxFileSize,imageWidth,imageHeight;{search_legend:hide},enableSearch,indexProtected;{smtp_legend:hide},useSMTP;{modules_legend},inactiveModules;{sections_legend:hide},customSections;{timeout_legend:hide},undoPeriod,versionPeriod,logPeriod,sessionTimeout,autologin,lockPeriod;{chmod_legend:hide},defaultUser,defaultGroup,defaultChmod;{update_legend:hide},liveUpdateBase'
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
			'eval'                    => array('chosen'=>true, 'tl_class'=>'w50')
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
		'coreOnlyMode' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['coreOnlyMode'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'disableCron' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['disableCron'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
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
		'fileSyncExclude' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['fileSyncExclude'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50')
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
		'rewriteURL' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['rewriteURL'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'addLanguageToUrl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['addLanguageToUrl'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'doNotRedirectEmpty' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['doNotRedirectEmpty'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'useAutoItem' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['useAutoItem'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'disableAlias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['disableAlias'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'folderUrl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['folderUrl'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'cacheMode' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['cacheMode'],
			'inputType'               => 'select',
			'options'                 => array('both', 'server', 'browser', 'none'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_settings'],
			'eval'                    => array('tl_class'=>'w50')
		),
		'privacyAnonymizeIp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['privacyAnonymizeIp'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'privacyAnonymizeGA' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['privacyAnonymizeGA'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'disableRefererCheck' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['disableRefererCheck'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'tokenWhitelist' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['tokenWhitelist'],
			'inputType'               => 'textarea',
			'eval'                    => array('decodeEntities'=>true, 'style'=>'height:60px'),
		),
		'allowedTags' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['allowedTags'],
			'inputType'               => 'text',
			'eval'                    => array('preserveTags'=>true, 'tl_class'=>'long')
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
		'bypassCache' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['bypassCache'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'displayErrors' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['displayErrors'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'logErrors' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['logErrors'],
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
			'eval'                    => array('multiple'=>true),
			'save_callback' => array
			(
				array('tl_settings', 'updateInactiveModules')
			)
		),
		'customSections' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['customSections'],
			'inputType'               => 'text'
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
		'lockPeriod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['lockPeriod'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'defaultUser' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['defaultUser'],
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.username',
			'eval'                    => array('chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'defaultGroup' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['defaultGroup'],
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user_group.name',
			'eval'                    => array('chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50')
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
			'inputType'               => 'text'
		)
	)
);


/**
 * Class tl_settings
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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

			if ($strModule == 'core' || !is_dir(TL_ROOT . '/system/modules/' . $strModule))
			{
				continue;
			}

			if ($blnCheckInactiveModules && in_array($strModule, $arrInactiveModules))
			{
				$strFile = sprintf('%s/system/modules/%s/languages/%s/modules.php', TL_ROOT, $strModule, $GLOBALS['TL_LANGUAGE']);

				if (file_exists($strFile))
				{
					include $strFile;
				}
			}

			$arrReturn[$strModule] = '<span style="color:#b3b3b3">['. $strModule .']</span> ' . (is_array($GLOBALS['TL_LANG']['MOD'][$strModule]) ? $GLOBALS['TL_LANG']['MOD'][$strModule][0] : $GLOBALS['TL_LANG']['MOD'][$strModule]);
		}

		natcasesort($arrReturn);
		return $arrReturn;
	}


	/**
	 * Update the inactive modules
	 * @param mixed
	 * @return mixed
	 */
	public function updateInactiveModules($varValue)
	{
		$arrModules = deserialize($varValue);

		if (!is_array($arrModules))
		{
			$arrModules = array();
		}

		foreach (scan(TL_ROOT . '/system/modules') as $strModule)
		{
			if (strncmp($strModule, '.', 1) === 0)
			{
				continue;
			}

			// Add the .skip file to disable the module
			if (in_array($strModule, $arrModules))
			{
				if (!file_exists(TL_ROOT . '/system/modules/' . $strModule . '/.skip'))
				{
					$objFile = new File('system/modules/' . $strModule . '/.skip');
					$objFile->write('As long as this file exists, the module will be ignored.');
					$objFile->close();
				}
			}
			// Remove the .skip if it exists
			else
			{
				if (file_exists(TL_ROOT . '/system/modules/' . $strModule . '/.skip'))
				{
					$objFile = new File('system/modules/' . $strModule . '/.skip');
					$objFile->delete();
				}
			}
		}

		// Purge the internal cache (see #5016)
		$this->import('Automator');
		$this->Automator->purgeInternalCache();

		return $varValue;
	}


	/**
	 * Remove protected search results if the feature is being disabled
	 * @param mixed
	 * @return mixed
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
	 * @return mixed
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
	 * @return mixed
	 */
	public function regenerateScripts($varValue)
	{
		if ($varValue != $GLOBALS['TL_CONFIG']['debugMode'])
		{
			$GLOBALS['TL_CONFIG']['debugMode'] = $varValue;

			$this->import('Automator');
			$this->Automator->purgeScriptCache();
		}

		return $varValue;
	}


	/**
	 * Make sure that "html5" is in the list of valid template
	 * files, so the back end works correctly (see #3398)
	 * @param mixed
	 * @return mixed
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
	 * @return mixed
	 * @throws \Exception
	 */
	public function checkUploadPath($varValue)
	{
		$varValue = str_replace(array('../', '/..', '/.', './', '://'), '', $varValue);

		if ($varValue == '.' || $varValue == '..' || $varValue == '')
		{
			$varValue = 'files';
		}
		elseif (preg_match('@^(assets|contao|plugins|share|system|templates)(/|$)@', $varValue))
		{
			throw new Exception($GLOBALS['TL_LANG']['ERR']['invalidName']);
		}

		return $varValue;
	}


	/**
	 * Check a static URL
	 * @param mixed
	 * @return mixed
	 */
	public function checkStaticUrl($varValue)
	{
		if ($varValue != '' && !preg_match('@^https?://@', $varValue))
		{
			$varValue = (Environment::get('ssl') ? 'https://' : 'http://') . $varValue;
		}

		return $varValue;
	}
}
