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
 * Reads and writes front end modules
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $name
 * @property string  $headline
 * @property string  $type
 * @property integer $levelOffset
 * @property integer $showLevel
 * @property boolean $hardLimit
 * @property boolean $showProtected
 * @property boolean $defineRoot
 * @property integer $rootPage
 * @property string  $navigationTpl
 * @property string  $customTpl
 * @property string  $pages
 * @property string  $orderPages
 * @property boolean $showHidden
 * @property string  $customLabel
 * @property boolean $autologin
 * @property integer $jumpTo
 * @property boolean $redirectBack
 * @property string  $cols
 * @property string  $editable
 * @property string  $memberTpl
 * @property boolean $tableless
 * @property integer $form
 * @property string  $queryType
 * @property boolean $fuzzy
 * @property integer $contextLength
 * @property integer $totalLength
 * @property integer $perPage
 * @property string  $searchType
 * @property string  $searchTpl
 * @property string  $inColumn
 * @property integer $skipFirst
 * @property boolean $loadFirst
 * @property string  $size
 * @property boolean $transparent
 * @property string  $flashvars
 * @property string  $altContent
 * @property string  $source
 * @property string  $singleSRC
 * @property string  $url
 * @property boolean $interactive
 * @property string  $flashID
 * @property string  $flashJS
 * @property string  $imgSize
 * @property boolean $useCaption
 * @property boolean $fullsize
 * @property string  $multiSRC
 * @property string  $orderSRC
 * @property string  $html
 * @property integer $rss_cache
 * @property string  $rss_feed
 * @property string  $rss_template
 * @property integer $numberOfItems
 * @property boolean $disableCaptcha
 * @property string  $reg_groups
 * @property boolean $reg_allowLogin
 * @property boolean $reg_skipName
 * @property string  $reg_close
 * @property boolean $reg_assignDir
 * @property string  $reg_homeDir
 * @property boolean $reg_activate
 * @property integer $reg_jumpTo
 * @property string  $reg_text
 * @property string  $reg_password
 * @property boolean $protected
 * @property string  $groups
 * @property boolean $guests
 * @property string  $cssID
 * @property string  $space
 * @property string  $cal_calendar
 * @property boolean $cal_noSpan
 * @property integer $cal_startDay
 * @property string  $cal_format
 * @property boolean $cal_ignoreDynamic
 * @property string  $cal_order
 * @property integer $cal_readerModule
 * @property integer $cal_limit
 * @property string  $cal_template
 * @property string  $cal_ctemplate
 * @property boolean $cal_showQuantity
 * @property string  $com_order
 * @property boolean $com_moderate
 * @property boolean $com_bbcode
 * @property boolean $com_requireLogin
 * @property boolean $com_disableCaptcha
 * @property string  $com_template
 * @property string  $faq_categories
 * @property integer $faq_readerModule
 * @property string  $list_table
 * @property string  $list_fields
 * @property string  $list_where
 * @property string  $list_search
 * @property string  $list_sort
 * @property string  $list_info
 * @property string  $list_info_where
 * @property string  $list_layout
 * @property string  $list_info_layout
 * @property string  $news_archives
 * @property string  $news_featured
 * @property string  $news_jumpToCurrent
 * @property integer $news_readerModule
 * @property string  $news_metaFields
 * @property string  $news_template
 * @property string  $news_format
 * @property integer $news_startDay
 * @property string  $news_order
 * @property boolean $news_showQuantity
 * @property string  $newsletters
 * @property string  $nl_channels
 * @property boolean $nl_hideChannels
 * @property string  $nl_subscribe
 * @property string  $nl_unsubscribe
 * @property string  $nl_template
 * @property string  $typePrefix
 * @property string  $classes
 *
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findOneByPid($val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneByName($val, $opt=array())
 * @method static $this findOneByHeadline($val, $opt=array())
 * @method static $this findOneByType($val, $opt=array())
 * @method static $this findOneByLevelOffset($val, $opt=array())
 * @method static $this findOneByShowLevel($val, $opt=array())
 * @method static $this findOneByHardLimit($val, $opt=array())
 * @method static $this findOneByShowProtected($val, $opt=array())
 * @method static $this findOneByDefineRoot($val, $opt=array())
 * @method static $this findOneByRootPage($val, $opt=array())
 * @method static $this findOneByNavigationTpl($val, $opt=array())
 * @method static $this findOneByCustomTpl($val, $opt=array())
 * @method static $this findOneByPages($val, $opt=array())
 * @method static $this findOneByOrderPages($val, $opt=array())
 * @method static $this findOneByShowHidden($val, $opt=array())
 * @method static $this findOneByCustomLabel($val, $opt=array())
 * @method static $this findOneByAutologin($val, $opt=array())
 * @method static $this findOneByJumpTo($val, $opt=array())
 * @method static $this findOneByRedirectBack($val, $opt=array())
 * @method static $this findOneByCols($val, $opt=array())
 * @method static $this findOneByEditable($val, $opt=array())
 * @method static $this findOneByMemberTpl($val, $opt=array())
 * @method static $this findOneByTableless($val, $opt=array())
 * @method static $this findOneByForm($val, $opt=array())
 * @method static $this findOneByQueryType($val, $opt=array())
 * @method static $this findOneByFuzzy($val, $opt=array())
 * @method static $this findOneByContextLength($val, $opt=array())
 * @method static $this findOneByTotalLength($val, $opt=array())
 * @method static $this findOneByPerPage($val, $opt=array())
 * @method static $this findOneBySearchType($val, $opt=array())
 * @method static $this findOneBySearchTpl($val, $opt=array())
 * @method static $this findOneByInColumn($val, $opt=array())
 * @method static $this findOneBySkipFirst($val, $opt=array())
 * @method static $this findOneByLoadFirst($val, $opt=array())
 * @method static $this findOneBySize($val, $opt=array())
 * @method static $this findOneByTransparent($val, $opt=array())
 * @method static $this findOneByFlashvars($val, $opt=array())
 * @method static $this findOneByAltContent($val, $opt=array())
 * @method static $this findOneBySource($val, $opt=array())
 * @method static $this findOneBySingleSRC($val, $opt=array())
 * @method static $this findOneByUrl($val, $opt=array())
 * @method static $this findOneByInteractive($val, $opt=array())
 * @method static $this findOneByFlashID($val, $opt=array())
 * @method static $this findOneByFlashJS($val, $opt=array())
 * @method static $this findOneByImgSize($val, $opt=array())
 * @method static $this findOneByUseCaption($val, $opt=array())
 * @method static $this findOneByFullsize($val, $opt=array())
 * @method static $this findOneByMultiSRC($val, $opt=array())
 * @method static $this findOneByOrderSRC($val, $opt=array())
 * @method static $this findOneByHtml($val, $opt=array())
 * @method static $this findOneByRss_cache($val, $opt=array())
 * @method static $this findOneByRss_feed($val, $opt=array())
 * @method static $this findOneByRss_template($val, $opt=array())
 * @method static $this findOneByNumberOfItems($val, $opt=array())
 * @method static $this findOneByDisableCaptcha($val, $opt=array())
 * @method static $this findOneByReg_groups($val, $opt=array())
 * @method static $this findOneByReg_allowLogin($val, $opt=array())
 * @method static $this findOneByReg_skipName($val, $opt=array())
 * @method static $this findOneByReg_close($val, $opt=array())
 * @method static $this findOneByReg_assignDir($val, $opt=array())
 * @method static $this findOneByReg_homeDir($val, $opt=array())
 * @method static $this findOneByReg_activate($val, $opt=array())
 * @method static $this findOneByReg_jumpTo($val, $opt=array())
 * @method static $this findOneByReg_text($val, $opt=array())
 * @method static $this findOneByReg_password($val, $opt=array())
 * @method static $this findOneByProtected($val, $opt=array())
 * @method static $this findOneByGroups($val, $opt=array())
 * @method static $this findOneByGuests($val, $opt=array())
 * @method static $this findOneByCssID($val, $opt=array())
 * @method static $this findOneBySpace($val, $opt=array())
 * @method static $this findOneByCal_calendar($val, $opt=array())
 * @method static $this findOneByCal_noSpan($val, $opt=array())
 * @method static $this findOneByCal_startDay($val, $opt=array())
 * @method static $this findOneByCal_format($val, $opt=array())
 * @method static $this findOneByCal_ignoreDynamic($val, $opt=array())
 * @method static $this findOneByCal_order($val, $opt=array())
 * @method static $this findOneByCal_readerModule($val, $opt=array())
 * @method static $this findOneByCal_limit($val, $opt=array())
 * @method static $this findOneByCal_template($val, $opt=array())
 * @method static $this findOneByCal_ctemplate($val, $opt=array())
 * @method static $this findOneByCal_showQuantity($val, $opt=array())
 * @method static $this findOneByCom_order($val, $opt=array())
 * @method static $this findOneByCom_moderate($val, $opt=array())
 * @method static $this findOneByCom_bbcode($val, $opt=array())
 * @method static $this findOneByCom_requireLogin($val, $opt=array())
 * @method static $this findOneByCom_disableCaptcha($val, $opt=array())
 * @method static $this findOneByCom_template($val, $opt=array())
 * @method static $this findOneByFaq_categories($val, $opt=array())
 * @method static $this findOneByFaq_readerModule($val, $opt=array())
 * @method static $this findOneByList_table($val, $opt=array())
 * @method static $this findOneByList_fields($val, $opt=array())
 * @method static $this findOneByList_where($val, $opt=array())
 * @method static $this findOneByList_search($val, $opt=array())
 * @method static $this findOneByList_sort($val, $opt=array())
 * @method static $this findOneByList_info($val, $opt=array())
 * @method static $this findOneByList_info_where($val, $opt=array())
 * @method static $this findOneByList_layout($val, $opt=array())
 * @method static $this findOneByList_info_layout($val, $opt=array())
 * @method static $this findOneByNews_archives($val, $opt=array())
 * @method static $this findOneByNews_featured($val, $opt=array())
 * @method static $this findOneByNews_jumpToCurrent($val, $opt=array())
 * @method static $this findOneByNews_readerModule($val, $opt=array())
 * @method static $this findOneByNews_metaFields($val, $opt=array())
 * @method static $this findOneByNews_template($val, $opt=array())
 * @method static $this findOneByNews_format($val, $opt=array())
 * @method static $this findOneByNews_startDay($val, $opt=array())
 * @method static $this findOneByNews_order($val, $opt=array())
 * @method static $this findOneByNews_showQuantity($val, $opt=array())
 * @method static $this findOneByNewsletters($val, $opt=array())
 * @method static $this findOneByNl_channels($val, $opt=array())
 * @method static $this findOneByNl_hideChannels($val, $opt=array())
 * @method static $this findOneByNl_subscribe($val, $opt=array())
 * @method static $this findOneByNl_unsubscribe($val, $opt=array())
 * @method static $this findOneByNl_template($val, $opt=array())
 *
 * @method static \Model\Collection|\ModuleModel findByPid($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByName($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByHeadline($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByType($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByLevelOffset($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByShowLevel($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByHardLimit($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByShowProtected($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByDefineRoot($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByRootPage($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNavigationTpl($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCustomTpl($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByPages($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByOrderPages($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByShowHidden($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCustomLabel($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByAutologin($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByRedirectBack($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCols($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByEditable($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByMemberTpl($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByTableless($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByForm($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByQueryType($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByFuzzy($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByContextLength($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByTotalLength($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByPerPage($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findBySearchType($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findBySearchTpl($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByInColumn($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findBySkipFirst($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByLoadFirst($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findBySize($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByTransparent($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByFlashvars($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByAltContent($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findBySource($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findBySingleSRC($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByUrl($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByInteractive($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByFlashID($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByFlashJS($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByImgSize($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByUseCaption($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByFullsize($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByMultiSRC($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByOrderSRC($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByHtml($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByRss_cache($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByRss_feed($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByRss_template($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNumberOfItems($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByDisableCaptcha($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByReg_groups($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByReg_allowLogin($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByReg_skipName($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByReg_close($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByReg_assignDir($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByReg_homeDir($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByReg_activate($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByReg_jumpTo($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByReg_text($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByReg_password($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByProtected($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByGroups($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByGuests($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCssID($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findBySpace($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCal_calendar($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCal_noSpan($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCal_startDay($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCal_format($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCal_ignoreDynamic($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCal_order($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCal_readerModule($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCal_limit($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCal_template($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCal_ctemplate($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCal_showQuantity($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCom_order($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCom_moderate($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCom_bbcode($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCom_requireLogin($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCom_disableCaptcha($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByCom_template($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByFaq_categories($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByFaq_readerModule($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByList_table($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByList_fields($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByList_where($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByList_search($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByList_sort($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByList_info($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByList_info_where($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByList_layout($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByList_info_layout($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNews_archives($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNews_featured($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNews_jumpToCurrent($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNews_readerModule($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNews_metaFields($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNews_template($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNews_format($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNews_startDay($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNews_order($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNews_showQuantity($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNewsletters($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNl_channels($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNl_hideChannels($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNl_subscribe($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNl_unsubscribe($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findByNl_template($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\ModuleModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByName($val, $opt=array())
 * @method static integer countByHeadline($val, $opt=array())
 * @method static integer countByType($val, $opt=array())
 * @method static integer countByLevelOffset($val, $opt=array())
 * @method static integer countByShowLevel($val, $opt=array())
 * @method static integer countByHardLimit($val, $opt=array())
 * @method static integer countByShowProtected($val, $opt=array())
 * @method static integer countByDefineRoot($val, $opt=array())
 * @method static integer countByRootPage($val, $opt=array())
 * @method static integer countByNavigationTpl($val, $opt=array())
 * @method static integer countByCustomTpl($val, $opt=array())
 * @method static integer countByPages($val, $opt=array())
 * @method static integer countByOrderPages($val, $opt=array())
 * @method static integer countByShowHidden($val, $opt=array())
 * @method static integer countByCustomLabel($val, $opt=array())
 * @method static integer countByAutologin($val, $opt=array())
 * @method static integer countByJumpTo($val, $opt=array())
 * @method static integer countByRedirectBack($val, $opt=array())
 * @method static integer countByCols($val, $opt=array())
 * @method static integer countByEditable($val, $opt=array())
 * @method static integer countByMemberTpl($val, $opt=array())
 * @method static integer countByTableless($val, $opt=array())
 * @method static integer countByForm($val, $opt=array())
 * @method static integer countByQueryType($val, $opt=array())
 * @method static integer countByFuzzy($val, $opt=array())
 * @method static integer countByContextLength($val, $opt=array())
 * @method static integer countByTotalLength($val, $opt=array())
 * @method static integer countByPerPage($val, $opt=array())
 * @method static integer countBySearchType($val, $opt=array())
 * @method static integer countBySearchTpl($val, $opt=array())
 * @method static integer countByInColumn($val, $opt=array())
 * @method static integer countBySkipFirst($val, $opt=array())
 * @method static integer countByLoadFirst($val, $opt=array())
 * @method static integer countBySize($val, $opt=array())
 * @method static integer countByTransparent($val, $opt=array())
 * @method static integer countByFlashvars($val, $opt=array())
 * @method static integer countByAltContent($val, $opt=array())
 * @method static integer countBySource($val, $opt=array())
 * @method static integer countBySingleSRC($val, $opt=array())
 * @method static integer countByUrl($val, $opt=array())
 * @method static integer countByInteractive($val, $opt=array())
 * @method static integer countByFlashID($val, $opt=array())
 * @method static integer countByFlashJS($val, $opt=array())
 * @method static integer countByImgSize($val, $opt=array())
 * @method static integer countByUseCaption($val, $opt=array())
 * @method static integer countByFullsize($val, $opt=array())
 * @method static integer countByMultiSRC($val, $opt=array())
 * @method static integer countByOrderSRC($val, $opt=array())
 * @method static integer countByHtml($val, $opt=array())
 * @method static integer countByRss_cache($val, $opt=array())
 * @method static integer countByRss_feed($val, $opt=array())
 * @method static integer countByRss_template($val, $opt=array())
 * @method static integer countByNumberOfItems($val, $opt=array())
 * @method static integer countByDisableCaptcha($val, $opt=array())
 * @method static integer countByReg_groups($val, $opt=array())
 * @method static integer countByReg_allowLogin($val, $opt=array())
 * @method static integer countByReg_skipName($val, $opt=array())
 * @method static integer countByReg_close($val, $opt=array())
 * @method static integer countByReg_assignDir($val, $opt=array())
 * @method static integer countByReg_homeDir($val, $opt=array())
 * @method static integer countByReg_activate($val, $opt=array())
 * @method static integer countByReg_jumpTo($val, $opt=array())
 * @method static integer countByReg_text($val, $opt=array())
 * @method static integer countByReg_password($val, $opt=array())
 * @method static integer countByProtected($val, $opt=array())
 * @method static integer countByGroups($val, $opt=array())
 * @method static integer countByGuests($val, $opt=array())
 * @method static integer countByCssID($val, $opt=array())
 * @method static integer countBySpace($val, $opt=array())
 * @method static integer countByCal_calendar($val, $opt=array())
 * @method static integer countByCal_noSpan($val, $opt=array())
 * @method static integer countByCal_startDay($val, $opt=array())
 * @method static integer countByCal_format($val, $opt=array())
 * @method static integer countByCal_ignoreDynamic($val, $opt=array())
 * @method static integer countByCal_order($val, $opt=array())
 * @method static integer countByCal_readerModule($val, $opt=array())
 * @method static integer countByCal_limit($val, $opt=array())
 * @method static integer countByCal_template($val, $opt=array())
 * @method static integer countByCal_ctemplate($val, $opt=array())
 * @method static integer countByCal_showQuantity($val, $opt=array())
 * @method static integer countByCom_order($val, $opt=array())
 * @method static integer countByCom_moderate($val, $opt=array())
 * @method static integer countByCom_bbcode($val, $opt=array())
 * @method static integer countByCom_requireLogin($val, $opt=array())
 * @method static integer countByCom_disableCaptcha($val, $opt=array())
 * @method static integer countByCom_template($val, $opt=array())
 * @method static integer countByFaq_categories($val, $opt=array())
 * @method static integer countByFaq_readerModule($val, $opt=array())
 * @method static integer countByList_table($val, $opt=array())
 * @method static integer countByList_fields($val, $opt=array())
 * @method static integer countByList_where($val, $opt=array())
 * @method static integer countByList_search($val, $opt=array())
 * @method static integer countByList_sort($val, $opt=array())
 * @method static integer countByList_info($val, $opt=array())
 * @method static integer countByList_info_where($val, $opt=array())
 * @method static integer countByList_layout($val, $opt=array())
 * @method static integer countByList_info_layout($val, $opt=array())
 * @method static integer countByNews_archives($val, $opt=array())
 * @method static integer countByNews_featured($val, $opt=array())
 * @method static integer countByNews_jumpToCurrent($val, $opt=array())
 * @method static integer countByNews_readerModule($val, $opt=array())
 * @method static integer countByNews_metaFields($val, $opt=array())
 * @method static integer countByNews_template($val, $opt=array())
 * @method static integer countByNews_format($val, $opt=array())
 * @method static integer countByNews_startDay($val, $opt=array())
 * @method static integer countByNews_order($val, $opt=array())
 * @method static integer countByNews_showQuantity($val, $opt=array())
 * @method static integer countByNewsletters($val, $opt=array())
 * @method static integer countByNl_channels($val, $opt=array())
 * @method static integer countByNl_hideChannels($val, $opt=array())
 * @method static integer countByNl_subscribe($val, $opt=array())
 * @method static integer countByNl_unsubscribe($val, $opt=array())
 * @method static integer countByNl_template($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_module';

}
