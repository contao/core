<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package News
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Add the dynamic parent table
 */
$GLOBALS['TL_DCA']['tl_content']['config']['dynamicPtable']['news'] = array('tl_news', array('tl_news', 'checkContentPermission'));
