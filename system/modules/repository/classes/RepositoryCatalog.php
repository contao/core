<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   Repository
 * @author    Peter Koch, IBK Software AG
 * @license   See accompaning file LICENSE.txt
 * @copyright Peter Koch 2008-2010
 */


/**
 * Implements the extension catalog
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @author Leo Feyer <https://github.com/leofeyer>
 * @package    Repository
 */
class RepositoryCatalog extends RepositoryBackendModule
{
	/**
	 * Generate module:
	 * - Display a wildcard in the back end
	 * - Declare actionlist with templates and compilers in the front end
	 * @return string
	 */
	public function generate()
	{
		if (!extension_loaded('soap')) {
			System::loadLanguageFile('tl_repository');
			return '<p class="tl_empty">'.$GLOBALS['TL_LANG']['tl_repository']['missingSoapModule'].'</p>';
		} // if

		$this->actions = array(
			//	  act[0]			strTemplate					compiler
			array('',				'repository_catlist',		'listExtensions' ),
			array('view',			'repository_catview',		'viewExtension' )
		);

		return parent::generate();
	} // generate

	/**
	 * List the extensions
	 */
	protected function listExtensions()
	{
		$rep = &$this->Template->rep;
		$rep->f_page = 0;

		// returning from submit?
		if ($this->filterPost('repository_action') == $rep->f_action) {
			// get url parameters
			$rep->f_tag 	= trim(Input::post('repository_tag'));
			$rep->f_type 	= trim(Input::post('repository_type'));
			$rep->f_category= trim(Input::post('repository_category'));
			$rep->f_state	= trim(Input::post('repository_state'));
			$rep->f_author	= trim(Input::post('repository_author'));
			$rep->f_order	= trim(Input::post('repository_order'));
			$rep->f_page	= trim(Input::post('repository_page'));
			$rep->f_find	= trim(Input::post('repository_find'));
			$this->Session->set(
				'repository_catalog_settings',
				array(
					'repository_tag'	=> $rep->f_tag,
					'repository_type'	=> $rep->f_type,
					'repository_category'=> $rep->f_category,
					'repository_state'	=> $rep->f_state,
					'repository_author'	=> $rep->f_author,
					'repository_order'	=> $rep->f_order,
					'repository_page'	=> $rep->f_page,
					'repository_find'	=> $rep->f_find
				)
			);
		} else {
			$stg = $this->Session->get('repository_catalog_settings');
			if (is_array($stg)) {
				$rep->f_tag 	= trim($stg['repository_tag']);
				$rep->f_type 	= trim($stg['repository_type']);
				$rep->f_category= trim($stg['repository_category']);
				$rep->f_state	= trim($stg['repository_state']);
				$rep->f_author	= trim($stg['repository_author']);
				$rep->f_order	= trim($stg['repository_order']);
				$rep->f_page	= trim($stg['repository_page']);
				$rep->f_find	= trim($stg['repository_find']);
			} // if
		} // if

		if ($rep->f_order=='') $rep->f_order = 'popular';

		$perpage = (int)trim(Config::get('repository_listsize'));
		if ($perpage < 0) $perpage = 0;

		// process parameters and build query options
		$options = array(
			'languages'		=> $this->languages,
			'sets'			=> 'sums,reviews',
		);
		if ($rep->f_page>=0 && $perpage>0) {
			$options['first'] = $rep->f_page * $perpage;
			$options['limit'] = $perpage;
		} // if
		if ($rep->f_tag		!= '') $options['tags']			= $rep->f_tag;
		if ($rep->f_type 	!= '') $options['types']		= $rep->f_type;
		if ($rep->f_category!= '') $options['categories'] 	= $rep->f_category;
		if ($rep->f_state	!= '') $options['states']		= $rep->f_state;
		if ($rep->f_author	!= '') $options['authors']		= $rep->f_author;
		if ($rep->f_find	!= '') $options['find']			= $rep->f_find;
		if (!Config::get('repository_unsafe_catalog'))
			$options['compatibility'] = Repository::encodeVersion(VERSION.'.'.BUILD);

		switch ($rep->f_order) {
			case 'name'		: break;
			case 'title'	: $options['order'] = 'title'; break;
			case 'author'	: $options['order'] = 'author'; break;
			case 'rating'	: $options['order'] = 'rating-'; break;
			case 'reldate'	: $options['order'] = 'releasedate-'; break;
			default			: $options['order'] = 'popularity-';
		} // switch

		// query extensions
		$rep->extensions = $this->getExtensionList($options);
		if ($rep->f_page>=0 && $perpage>0 && count($rep->extensions)==0) {
			$rep->f_page = 0;
			$options['first'] = 0;
			$rep->extensions = $this->getExtensionList($options);
		} // if

		// add view links
		$totrecs = 0;
		foreach ($rep->extensions as &$ext) {
			$ext->viewLink = $this->createUrl(array('view' => $ext->name.'.'.$ext->version.'.'.$ext->language));
			$ext->installLink = $this->createPageUrl('repository_manager',array('install'=>$ext->name.'.'.$ext->version));
			$totrecs = $ext->totrecs;
		} // foreach

		// create pages list
		$rep->pages = array();
		if ($perpage > 0) {
			$first = 1;
			while ($totrecs > 0) {
				$cnt = $totrecs > $perpage ? $perpage : $totrecs;
				$rep->pages[] = $first . ' - ' . ($first+$cnt-1);
				$first += $cnt;
				$totrecs -= $cnt;
			} // while
		} // if

		$rep->tags = $this->getTagList(array('languages'=>$this->languages, 'mode'=>'initcap'));
		$rep->authors = $this->getAuthorList(array('languages'=>$this->languages));
	} // listExtensions

	/**
	 * Detailed view of one extension.
	 * @param string $aParams
	 */
	protected function viewExtension($aParams)
	{
		$rep = &$this->Template->rep;

		// parse name[.version][.language]
		$matches = array();
		if (!preg_match('#^([a-zA-Z0-9_-]+)(\.([0-9]+))?(\.([a-z]{2,2}))?$#', $aParams, $matches))
			$this->redirect($rep->homeLink);
		$name = $matches[1];
		$version = (count($matches)>=4) ? $matches[3] : '';
		$language = count($matches)>=6 ? $matches[5] : $this->languages;

		// compose base options
		$options = array(
			'match' 	=> 'exact',
			'names' 	=> $name,
			'languages'	=> $language,
			'sets'  	=> 'details,pictures,languages,history,dependencies,dependents,sums'
		);
		if ($version!='') $options['versions'] = $version;

		$rep->extensions = $this->getExtensionList($options);
		if (count($rep->extensions)<1) $this->redirect($rep->homeLink);
		$ext = &$rep->extensions[0];

		// other versions links
		if (property_exists($ext, 'allversions'))
			foreach ($ext->allversions as &$ver)
				$ver->viewLink = $this->createUrl(array('view'=>$ext->name.'.'.$ver->version.'.'.$ext->language));

		// other languages links
		if (property_exists($ext, 'languages')) {
			$langs = explode(',', $ext->languages);
			$ext->languages = array();
			foreach ($langs as $lang) {
				$l = new stdClass();
				$l->language = $lang;
				$l->link = $this->createUrl(array('view' => $ext->name.'.'.$ext->version.'.'.$lang));
				$ext->languages[] = $l;
			} // for
		} // if

		// dependencies links
		if (property_exists($ext, 'dependencies'))
			foreach ($ext->dependencies as &$dep)
				$dep->viewLink = $this->createUrl(array('view'=>$dep->extension));

		// dependents links
		if (property_exists($ext, 'dependents'))
			foreach ($ext->dependents as &$dep)
				$dep->viewLink = $this->createUrl(array('view'=>$dep->extension));

		// install link
		$ext->installLink = $this->createPageUrl('repository_manager',array('install'=>$ext->name.'.'.$ext->version));

		if ($this->filterPost('repository_action') == $rep->f_action) {
			if (isset($_POST['repository_installbutton'])) $this->redirect($ext->installLink);
			if (isset($_POST['repository_manualbutton']) && property_exists($ext, 'manual')) $this->redirect($ext->manual);
			if (isset($_POST['repository_forumbutton']) && property_exists($ext, 'forum')) $this->redirect($ext->forum);
			if (isset($_POST['repository_shopbutton']) && property_exists($ext, 'shop')) $this->redirect($ext->shop);
		} // if
	} // viewExtension

	private function getAuthorList($aOptions)
	{
		switch ($this->mode) {
			case 'local':
				return $this->RepositoryServer->getAuthorList((object)$aOptions);
			case 'soap':
				return $this->client->getAuthorList($aOptions);
			default:
				return array();
		} // if
	} // getAuthorList

	private function getTagList($aOptions)
	{
		switch ($this->mode) {
			case 'local':
				return $this->RepositoryServer->getTagList((object)$aOptions);
			case 'soap':
				return $this->client->getTagList($aOptions);
			default:
				return array();
		} // if
	} // getTagList

} // class RepositoryCatalog
