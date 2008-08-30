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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Front end modules
 */
$GLOBALS['FE_MOD'] = array
(
	'navigationMenu' => array
	(
		'navigation'     => 'ModuleNavigation',
		'navigationMain' => 'ModuleNavigationMain',
		'navigationSub'  => 'ModuleNavigationSub',
		'customnav'      => 'ModuleCustomnav',
		'breadcrumb'     => 'ModuleBreadcrumb',
		'quicknav'       => 'ModuleQuicknav',
		'quicklink'      => 'ModuleQuicklink',
		'sitemap'        => 'ModuleSitemap'
	),
	'user' => array
	(
		'login'          => 'ModuleLogin',
		'logout'         => 'ModuleLogout',
		'personalData'   => 'ModulePersonalData',
	),
	'application' => array
	(
		'form'           => 'Form',
		'search'         => 'ModuleSearch',
		'articleList'    => 'ModuleArticleList',
	),
	'miscellaneous' => array
	(
		'flash'          => 'ModuleFlash',
		'randomImage'    => 'ModuleRandomImage',
		'html'           => 'ModuleHtml'
	)
);


/**
 * Content elements
 */
$GLOBALS['TL_CTE'] = array
(
	'texts' => array
	(
		'headline'  => 'ContentHeadline',
		'text'      => 'ContentText',
		'html'      => 'ContentHtml',
		'list'      => 'ContentList',
		'table'     => 'ContentTable',
		'accordion' => 'ContentAccordion',
		'code'      => 'ContentCode'
	),
	'links' => array
	(
		'hyperlink' => 'ContentHyperlink',
		'toplink'   => 'ContentToplink'
	),
	'images' => array
	(
		'image'     => 'ContentImage',
		'gallery'   => 'ContentGallery'
	),
	'files' => array
	(
		'download'  => 'ContentDownload',
		'downloads' => 'ContentDownloads'
	),
	'includes' => array
	(
		'alias'     => 'ContentAlias',
		'article'   => 'ContentArticle',
		'teaser'    => 'ContentTeaser',
		'form'      => 'Form',
		'module'    => 'ContentModule'
	)
);


/**
 * Form fields
 */
$GLOBALS['TL_FFL'] = array
(
	'headline'    => 'FormHeadline',
	'explanation' => 'FormExplanation',
	'html'        => 'FormHtml',
	'text'        => 'FormTextField',
	'password'    => 'FormPassword',
	'textarea'    => 'FormTextArea',
	'select'      => 'FormSelectMenu',
	'radio'       => 'FormRadioButton',
	'checkbox'    => 'FormCheckBox',
	'upload'      => 'FormFileUpload',
	'hidden'      => 'FormHidden',
	'captcha'     => 'FormCaptcha',
	'submit'      => 'FormSubmit'
);

?>