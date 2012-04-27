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
 * @package    Backend
 * @license    LGPL
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once '../system/initialize.php';


/**
 * Class Changelog
 *
 * Show the changelog to an authenticated user.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class Changelog extends Backend
{

	/**
	 * Initialize the controller
	 * 
	 * 1. Import the user
	 * 2. Call the parent constructor
	 * 3. Authenticate the user
	 * 4. Load the language files
	 * DO NOT CHANGE THIS ORDER!
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();

		$this->User->authenticate();
	}


	/**
	 * Run the controller
	 * @return void
	 */
	public function run()
	{
		$strBuffer = file_get_contents(TL_ROOT . '/system/docs/CHANGELOG.md');
		$strBuffer = str_replace("\r", '', $strBuffer); // see #4190

		$strBuffer = preg_replace(
			array(
				'/#([0-9]+)/',
				'/(---+\n)(?!\n)/',
				'/([^\n]+)\n===+\n/',
				'/([^\n]+)\n---+\n/',
				'/\n### ([^\n]+)\n/',
				'/ _(?!_)/', '/_ /',
				'/===+\n/'
			),
			array(
				'<a href="https://github.com/contao/core/issues/$1" target="_blank">#$1</a>',
				"$1\n",
				'<h2>$1</h2>',
				"<h3>\$1</h3>\n",
				"<h4>\$1</h4>\n",
				' <em>', '</em> ',
				''
			),
			$strBuffer
		);
		
		$strBuffer = str_replace(
			array(
				"\n\n```\n", "\n```\n\n",
				' `', '` ',
				'(`', '`)',
				"\n`", "`\n",
				'`.', '`,'
			),
			array(
				"\n\n<pre>", "</pre>\n\n",
				' <code>', '</code> ',
				'(<code>', '</code>)',
				"\n<code>", "</code>\n",
				'</code>.', '</code>,'
			),
			trim($strBuffer)
		);

		$this->Template = new BackendTemplate('be_changelog');

		// Template variables
		$this->Template->content = $strBuffer;
		$this->Template->theme = $this->getTheme();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];

		$GLOBALS['TL_CONFIG']['debugMode'] = false;
		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objChangelog = new Changelog();
$objChangelog->run();
