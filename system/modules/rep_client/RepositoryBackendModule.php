<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Repository
 * @license    LGPL
 * @filesource
 */


/**
 * Contao Repository :: Base back end module
 *
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @license    See accompaning file LICENSE.txt
 */
require_once(dirname(dirname(__FILE__)).'/rep_base/RepositorySettings.php');


/**
 * Implements the frontend interface
 */
class RepositoryBackendModule extends BackendModule
{
	protected $strTemplate;
	protected $actions = array();

	protected $rep;

	protected $tl_root;
	protected $tl_files;
	protected $languages;

	protected $mode = '';
	protected $client;

	private $compiler;
	private $action = '';
	private $parameter = '';


	/**
	 * Generate module:
	 * - Display a wildcard in the back end
	 * - Select the template and compiler in the front end
	 */
	public function generate()
	{
		$this->rep = new stdClass();
		$rep = &$this->rep;
		$rep->username	= $this->BackendUser->username;
		$rep->isadmin	= $this->BackendUser->isAdmin;
		$this->strTemplate = $this->actions[0][1];
		$this->compiler	= $this->actions[0][2];
		foreach ($this->actions as &$act) {
			if ($act[0]!='') {
				$this->parameter = $this->Input->get($act[0]);
				if ($this->parameter!='') {
					$this->action = $act[0];
					$this->strTemplate = $act[1];
					$this->compiler = $act[2];
					break;
				} // if
			} // if
		} // foreach
		return str_replace(array('{{', '}}'), array('[{]', '[}]'), parent::generate());
	} // generate

	/**
	 * Compile module: common initializations and forwarding to distinct function compiler
	 */
	protected function compile()
	{
		// hide module?
		$compiler = $this->compiler;
		if ($compiler=='hide') return;

		// load other helpers
		$this->tl_root = str_replace("\\",'/',TL_ROOT).'/';
		$this->tl_files = str_replace("\\",'/',$GLOBALS['TL_CONFIG']['uploadPath']).'/';
		$this->loadLanguageFile('tl_repository');
		$this->loadLanguageFile('languages');
		$this->Template->rep = $this->rep;
		$this->languages = rtrim($GLOBALS['TL_LANGUAGE'].','.trim($GLOBALS['TL_CONFIG']['repository_languages']),',');
		$this->languages = implode(',',array_unique(explode(',',$this->languages)));

		// complete rep initialization
		$rep = $this->rep;
		$rep->f_link	= $this->createUrl(array($this->action=>$this->parameter));
		$rep->f_action	= $this->compiler;
		$rep->f_mode	= $this->action;
		$rep->theme		= new RepositoryBackendTheme();
		$rep->backLink	= $this->getReferer(ENCODE_AMPERSANDS);
		$rep->homeLink	= $this->createUrl();

		// load soap client in case wsdl file is defined
		$wsdl = trim($GLOBALS['TL_CONFIG']['repository_wsdl']);
		if ($wsdl != '') {
			try {
				if (!REPOSITORY_SOAPCACHE) ini_set('soap.wsdl_cache_enabled', 0);
				// Buggy gzencode call in PHP 5.4.0-5.4.3 (thanks to borrible13th) (see #4087)
				if (version_compare(PHP_VERSION, '5.4.0', '>=') && version_compare(PHP_VERSION, '5.4.4', '<')) {
					define('SOAP_COMPRESSION_FIXED', SOAP_COMPRESSION_DEFLATE);
				} else {
					define('SOAP_COMPRESSION_FIXED', SOAP_COMPRESSION_GZIP);
				}
				// HOOK: proxy module
				if ($GLOBALS['TL_CONFIG']['useProxy']) {
					$proxy_uri = parse_url($GLOBALS['TL_CONFIG']['proxy_url']);
					$this->client = new SoapClient($wsdl, array(
						'soap_version' => SOAP_1_2,
						'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_FIXED | 1,
						'proxy_host' => $proxy_uri['host'],
						'proxy_port' => $proxy_uri['port'],
						'proxy_login' => $proxy_uri['user'],
						'proxy_password' => $proxy_uri['pass']
					));
				}
				// Default client
				else {
					$this->client = new SoapClient($wsdl, array(
						'soap_version' => SOAP_1_2,
						'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_FIXED | 1
					));
				}
				$this->mode = 'soap';
			} catch (Exception $e) {
				$rep->installLink = $this->createUrl(array());
				$rep->updateLink = $this->createUrl(array('update'=>'database'));
				$GLOBALS['TL_LANG']['tl_repository']['noextensionsfound'] = '<p class="tl_error">Could not connect to the repository server</p>';
				if ($compiler == 'update') $this->$compiler($this->parameter);
				return;
			}
		} else {
			// fallback to load RepositoryServer class if on central server
			if (file_exists($this->tl_root . 'system/modules/rep_server/RepositoryServer.php')) {
				$this->import('RepositoryServer');
				$this->RepositoryServer->enableLocal();
				$this->mode = 'local';
			} // if
		}

		// execute compiler
		$this->$compiler($this->parameter);

		// do not execute hooks upon installation/removal (see #2448)
		if ($compiler == 'install' || $compiler == 'upgrade' || $compiler == 'uninstall') {
			$GLOBALS['TL_HOOKS'] = array();
		} // if
	} // compile

	/**
	 * Create url for hyperlink to the current page.
	 * @param array $aParams Assiciative array with key/value pairs as parameters.
	 * @return string The create link.
	 */
	protected function createUrl($aParams = null)
	{
		return $this->createPageUrl($this->Input->get('do'), $aParams);
	} // createUrl

	/**
	 * Create url for hyperlink to an arbitrary page.
	 * @param string $aPage The page ID.
	 * @param array $aParams Assiciative array with key/value pairs as parameters.
	 * @return string The create link.
	 */
	protected function createPageUrl($aPage, $aParams = null)
	{
		$url = $this->Environment->script . '?do='.$aPage;
		if (is_array($aParams)) {
			foreach ($aParams as $key => $val)
				if ($val!='')
					$url .= '&amp;'.$key .'='.$val;
		}
		return $url;
	} // createPageUrl

	/**
	 * Get post parameter and filter value.
	 * @param string $aKey The post key. When filtering html, remove all attribs and
	 * keep the plain tags.
	 * @param string $aMode '': no filtering
	 *						'nohtml': strip all html
	 *						'text': Keep tags p br ul li em
	 * @return string The filtered input.
	 */
	protected function filterPost($aKey, $aMode = '')
	{
		$v = trim($this->Input->postRaw($aKey));
		if ($v == '' || $aMode=='') return $v;
		switch ($aMode) {
			case 'nohtml':
				$v = strip_tags($v);
				break;
			case 'text':
				$v = strip_tags($v, REPOSITORY_TEXTTAGS);
				break;
		} // switch
		$v = preg_replace('/<(\w+) .*>/U', '<$1>', $v);
		return $v;
	} // filterPost

	protected function getExtensionList($aOptions)
	{
		switch ($this->mode) {
			case 'local':
				return $this->RepositoryServer->getExtensionList((object)$aOptions);
			case 'soap':
				return $this->client->getExtensionList($aOptions);
			default:
				return array();
		} // if
	} // getExtensionList

} // class RepositoryBackendModule

?>
