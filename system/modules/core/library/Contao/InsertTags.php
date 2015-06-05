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
 * A static class to replace insert tags
 *
 * Usage:
 *
 *     $it = new InsertTags();
 *     echo $it->replace($text);
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class InsertTags extends \Controller
{
	/**
	 * Replace insert tags with their values
	 *
	 * @param string  $strBuffer The text with the tags to be replaced
	 * @param boolean $blnCache  If false, non-cacheable tags will be replaced
	 *
	 * @return string The text with the replaced tags
	 */
	public function replace($strBuffer, $blnCache=true)
	{
		/** @var \PageModel $objPage */
		global $objPage;

		// Preserve insert tags
		if (\Config::get('disableInsertTags'))
		{
			return \String::restoreBasicEntities($strBuffer);
		}

		$tags = preg_split('/\{\{(([^\{\}]*|(?R))*)\}\}/', $strBuffer, -1, PREG_SPLIT_DELIM_CAPTURE);

		$strBuffer = '';

		// Create one cache per cache setting (see #7700)
		static $arrItCache;
		$arrCache = &$arrItCache[$blnCache];

		for ($_rit=0, $_cnt=count($tags); $_rit<$_cnt; $_rit+=3)
		{
			$strBuffer .= $tags[$_rit];
			$strTag = $tags[$_rit+1];

			// Skip empty tags
			if ($strTag == '')
			{
				continue;
			}

			// Run the replacement again if there are more tags (see #4402)
			if (strpos($strTag, '{{') !== false)
			{
				$strTag = $this->replace($strTag, $blnCache);
			}

			$flags = explode('|', $strTag);
			$tag = array_shift($flags);
			$elements = explode('::', $tag);

			// Load the value from cache
			if (isset($arrCache[$strTag]) && !in_array('refresh', $flags))
			{
				$strBuffer .= $arrCache[$strTag];
				continue;
			}

			// Skip certain elements if the output will be cached
			if ($blnCache)
			{
				if ($elements[0] == 'date' || $elements[0] == 'ua' || $elements[0] == 'post' || $elements[0] == 'file' || $elements[1] == 'back' || $elements[1] == 'referer' || $elements[0] == 'request_token' || $elements[0] == 'toggle_view' || strncmp($elements[0], 'cache_', 6) === 0 || in_array('uncached', $flags))
				{
					$strBuffer .= '{{' . $strTag . '}}';
					continue;
				}
			}

			$arrCache[$strTag] = '';

			// Replace the tag
			switch (strtolower($elements[0]))
			{
				// Date
				case 'date':
					$arrCache[$strTag] = \Date::parse($elements[1] ?: \Config::get('dateFormat'));
					break;

				// Accessibility tags
				case 'lang':
					if ($elements[1] == '')
					{
						$arrCache[$strTag] = '</span>';
					}
					elseif ($objPage->outputFormat == 'xhtml')
					{
						$arrCache[$strTag] = '<span lang="' . $elements[1] . '" xml:lang="' . $elements[1] . '">';
					}
					else
					{
						$arrCache[$strTag] = $arrCache[$strTag] = '<span lang="' . $elements[1] . '">';
					}
					break;

				// Line break
				case 'br':
					$arrCache[$strTag] = '<br' . ($objPage->outputFormat == 'xhtml' ? ' />' : '>');
					break;

				// E-mail addresses
				case 'email':
				case 'email_open':
				case 'email_url':
					if ($elements[1] == '')
					{
						$arrCache[$strTag] = '';
						break;
					}

					$strEmail = \String::encodeEmail($elements[1]);

					// Replace the tag
					switch (strtolower($elements[0]))
					{
						case 'email':
							$arrCache[$strTag] = '<a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;' . $strEmail . '" class="email">' . preg_replace('/\?.*$/', '', $strEmail) . '</a>';
							break;

						case 'email_open':
							$arrCache[$strTag] = '<a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;' . $strEmail . '" title="' . $strEmail . '" class="email">';
							break;

						case 'email_url':
							$arrCache[$strTag] = $strEmail;
							break;
					}
					break;

				// Label tags
				case 'label':
					$keys = explode(':', $elements[1]);

					if (count($keys) < 2)
					{
						$arrCache[$strTag] = '';
						break;
					}

					$file = $keys[0];

					// Map the key (see #7217)
					switch ($file)
					{
						case 'CNT':
							$file = 'countries';
							break;

						case 'LNG':
							$file = 'languages';
							break;

						case 'MOD':
						case 'FMD':
							$file = 'modules';
							break;

						case 'FFL':
							$file = 'tl_form_field';
							break;

						case 'CACHE':
							$file = 'tl_page';
							break;

						case 'XPL':
							$file = 'explain';
							break;

						case 'XPT':
							$file = 'exception';
							break;

						case 'MSC':
						case 'ERR':
						case 'CTE':
						case 'PTY':
						case 'FOP':
						case 'CHMOD':
						case 'DAYS':
						case 'MONTHS':
						case 'UNITS':
						case 'CONFIRM':
						case 'DP':
						case 'COLS':
							$file = 'default';
							break;
					}

					\System::loadLanguageFile($file);

					if (count($keys) == 2)
					{
						$arrCache[$strTag] = $GLOBALS['TL_LANG'][$keys[0]][$keys[1]];
					}
					else
					{
						$arrCache[$strTag] = $GLOBALS['TL_LANG'][$keys[0]][$keys[1]][$keys[2]];
					}
					break;

				// Front end user
				case 'user':
					if (FE_USER_LOGGED_IN)
					{
						$this->import('FrontendUser', 'User');
						$value = $this->User->$elements[1];

						if ($value == '')
						{
							$arrCache[$strTag] = $value;
							break;
						}

						$this->loadDataContainer('tl_member');

						if ($GLOBALS['TL_DCA']['tl_member']['fields'][$elements[1]]['inputType'] == 'password')
						{
							$arrCache[$strTag] = '';
							break;
						}

						$value = deserialize($value);

						// Decrypt the value
						if ($GLOBALS['TL_DCA']['tl_member']['fields'][$elements[1]]['eval']['encrypt'])
						{
							$value = \Encryption::decrypt($value);
						}

						$rgxp = $GLOBALS['TL_DCA']['tl_member']['fields'][$elements[1]]['eval']['rgxp'];
						$opts = $GLOBALS['TL_DCA']['tl_member']['fields'][$elements[1]]['options'];
						$rfrc = $GLOBALS['TL_DCA']['tl_member']['fields'][$elements[1]]['reference'];

						if ($rgxp == 'date')
						{
							$arrCache[$strTag] = \Date::parse(\Config::get('dateFormat'), $value);
						}
						elseif ($rgxp == 'time')
						{
							$arrCache[$strTag] = \Date::parse(\Config::get('timeFormat'), $value);
						}
						elseif ($rgxp == 'datim')
						{
							$arrCache[$strTag] = \Date::parse(\Config::get('datimFormat'), $value);
						}
						elseif (is_array($value))
						{
							$arrCache[$strTag] = implode(', ', $value);
						}
						elseif (is_array($opts) && array_is_assoc($opts))
						{
							$arrCache[$strTag] = isset($opts[$value]) ? $opts[$value] : $value;
						}
						elseif (is_array($rfrc))
						{
							$arrCache[$strTag] = isset($rfrc[$value]) ? ((is_array($rfrc[$value])) ? $rfrc[$value][0] : $rfrc[$value]) : $value;
						}
						else
						{
							$arrCache[$strTag] = $value;
						}

						// Convert special characters (see #1890)
						$arrCache[$strTag] = specialchars($arrCache[$strTag]);
					}
					break;

				// Link
				case 'link':
				case 'link_open':
				case 'link_url':
				case 'link_title':
				case 'link_target':
				case 'link_name':
					$strTarget = null;

					// Back link
					if ($elements[1] == 'back')
					{
						$strUrl = 'javascript:history.go(-1)';
						$strTitle = $GLOBALS['TL_LANG']['MSC']['goBack'];

						// No language files if the page is cached
						if (!strlen($strTitle))
						{
							$strTitle = 'Go back';
						}

						$strName = $strTitle;
					}

					// External links
					elseif (strncmp($elements[1], 'http://', 7) === 0 || strncmp($elements[1], 'https://', 8) === 0)
					{
						$strUrl = $elements[1];
						$strTitle = $elements[1];
						$strName = str_replace(array('http://', 'https://'), '', $elements[1]);
					}

					// Regular link
					else
					{
						// User login page
						if ($elements[1] == 'login')
						{
							if (!FE_USER_LOGGED_IN)
							{
								break;
							}

							$this->import('FrontendUser', 'User');
							$elements[1] = $this->User->loginPage;
						}

						$objNextPage = \PageModel::findByIdOrAlias($elements[1]);

						if ($objNextPage === null)
						{
							break;
						}

						// Page type specific settings (thanks to Andreas Schempp)
						switch ($objNextPage->type)
						{
							case 'redirect':
								$strUrl = $this->replaceInsertTags($objNextPage->url); // see #6765

								if (strncasecmp($strUrl, 'mailto:', 7) === 0)
								{
									$strUrl = \String::encodeEmail($strUrl);
								}
								break;

							case 'forward':
								if ($objNextPage->jumpTo)
								{
									/** @var \PageModel $objNext */
									$objNext = $objNextPage->getRelated('jumpTo');
								}
								else
								{
									$objNext = \PageModel::findFirstPublishedRegularByPid($objNextPage->id);
								}

								if ($objNext !== null)
								{
									$strForceLang = null;
									$objNext->loadDetails();

									// Check the target page language (see #4706)
									if (\Config::get('addLanguageToUrl'))
									{
										$strForceLang = $objNext->language;
									}

									$strUrl = $this->generateFrontendUrl($objNext->row(), null, $strForceLang, true);
									break;
								}
								// DO NOT ADD A break; STATEMENT

							default:
								$strForceLang = null;
								$objNextPage->loadDetails();

								// Check the target page language (see #4706, #5465)
								if (\Config::get('addLanguageToUrl'))
								{
									$strForceLang = $objNextPage->language;
								}

								$strUrl = $this->generateFrontendUrl($objNextPage->row(), null, $strForceLang, true);
								break;
						}

						$strName = $objNextPage->title;
						$strTarget = $objNextPage->target ? (($objPage->outputFormat == 'xhtml') ? LINK_NEW_WINDOW : ' target="_blank"') : '';
						$strTitle = $objNextPage->pageTitle ?: $objNextPage->title;
					}

					// Replace the tag
					switch (strtolower($elements[0]))
					{
						case 'link':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s"%s>%s</a>', $strUrl, specialchars($strTitle), $strTarget, specialchars($strName));
							break;

						case 'link_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s"%s>', $strUrl, specialchars($strTitle), $strTarget);
							break;

						case 'link_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'link_title':
							$arrCache[$strTag] = specialchars($strTitle);
							break;

						case 'link_target':
							$arrCache[$strTag] = $strTarget;
							break;

						case 'link_name':
							$arrCache[$strTag] = specialchars($strName);
							break;
					}
					break;

				// Closing link tag
				case 'link_close':
					$arrCache[$strTag] = '</a>';
					break;

				// Insert article
				case 'insert_article':
					if (($strOutput = $this->getArticle($elements[1], false, true)) !== false)
					{
						$arrCache[$strTag] = $this->replaceInsertTags(ltrim($strOutput), $blnCache);
					}
					else
					{
						$arrCache[$strTag] = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], $elements[1]) . '</p>';
					}
					break;

				// Insert content element
				case 'insert_content':
					$arrCache[$strTag] = $this->replaceInsertTags($this->getContentElement($elements[1]), $blnCache);
					break;

				// Insert module
				case 'insert_module':
					$arrCache[$strTag] = $this->replaceInsertTags($this->getFrontendModule($elements[1]), $blnCache);
					break;

				// Insert form
				case 'insert_form':
					$arrCache[$strTag] = $this->replaceInsertTags($this->getForm($elements[1]), $blnCache);
					break;

				// Article
				case 'article':
				case 'article_open':
				case 'article_url':
				case 'article_title':
					if (($objArticle = \ArticleModel::findByIdOrAlias($elements[1])) === null || ($objPid = $objArticle->getRelated('pid')) === null)
					{
						break;
					}

					$strUrl = $this->generateFrontendUrl($objPid->row(), '/articles/' . ((!\Config::get('disableAlias') && strlen($objArticle->alias)) ? $objArticle->alias : $objArticle->id));

					// Replace the tag
					switch (strtolower($elements[0]))
					{
						case 'article':
							$strLink = specialchars($objArticle->title);
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, $strLink, $strLink);
							break;

						case 'article_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($objArticle->title));
							break;

						case 'article_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'article_title':
							$arrCache[$strTag] = specialchars($objArticle->title);
							break;
					}
					break;

				// FAQ
				case 'faq':
				case 'faq_open':
				case 'faq_url':
				case 'faq_title':
					if (($objFaq = \FaqModel::findByIdOrAlias($elements[1])) === null || ($objPid = $objFaq->getRelated('pid')) === null || ($objJumpTo = $objPid->getRelated('jumpTo')) === null)
					{
						break;
					}

					$strUrl = $this->generateFrontendUrl($objJumpTo->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/' : '/items/') . ((!\Config::get('disableAlias') && $objFaq->alias != '') ? $objFaq->alias : $objFaq->id));

					// Replace the tag
					switch (strtolower($elements[0]))
					{
						case 'faq':
							$strLink = specialchars($objFaq->question);
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, $strLink, $strLink);
							break;

						case 'faq_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($objFaq->question));
							break;

						case 'faq_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'faq_title':
							$arrCache[$strTag] = specialchars($objFaq->question);
							break;
					}
					break;

				// News
				case 'news':
				case 'news_open':
				case 'news_url':
				case 'news_title':
					if (($objNews = \NewsModel::findByIdOrAlias($elements[1])) === null)
					{
						break;
					}

					$strUrl = '';

					if ($objNews->source == 'external')
					{
						$strUrl = $objNews->url;
					}
					elseif ($objNews->source == 'internal')
					{
						if (($objJumpTo = $objNews->getRelated('jumpTo')) !== null)
						{
							$strUrl = $this->generateFrontendUrl($objJumpTo->row());
						}
					}
					elseif ($objNews->source == 'article')
					{
						if (($objArticle = \ArticleModel::findByPk($objNews->articleId, array('eager'=>true))) !== null && ($objPid = $objArticle->getRelated('pid')) !== null)
						{
							$strUrl = $this->generateFrontendUrl($objPid->row(), '/articles/' . ((!\Config::get('disableAlias') && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id));
						}
					}
					else
					{
						if (($objArchive = $objNews->getRelated('pid')) !== null && ($objJumpTo = $objArchive->getRelated('jumpTo')) !== null)
						{
							$strUrl = $this->generateFrontendUrl($objJumpTo->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/' : '/items/') . ((!\Config::get('disableAlias') && $objNews->alias != '') ? $objNews->alias : $objNews->id));
						}
					}

					// Replace the tag
					switch (strtolower($elements[0]))
					{
						case 'news':
							$strLink = specialchars($objNews->headline);
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, $strLink, $strLink);
							break;

						case 'news_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($objNews->headline));
							break;

						case 'news_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'news_title':
							$arrCache[$strTag] = specialchars($objNews->headline);
							break;
					}
					break;

				// Events
				case 'event':
				case 'event_open':
				case 'event_url':
				case 'event_title':
					if (($objEvent = \CalendarEventsModel::findByIdOrAlias($elements[1])) === null)
					{
						break;
					}

					$strUrl = '';

					if ($objEvent->source == 'external')
					{
						$strUrl = $objEvent->url;
					}
					elseif ($objEvent->source == 'internal')
					{
						if (($objJumpTo = $objEvent->getRelated('jumpTo')) !== null)
						{
							$strUrl = $this->generateFrontendUrl($objJumpTo->row());
						}
					}
					elseif ($objEvent->source == 'article')
					{
						if (($objArticle = \ArticleModel::findByPk($objEvent->articleId, array('eager'=>true))) !== null && ($objPid = $objArticle->getRelated('pid')) !== null)
						{
							$strUrl = $this->generateFrontendUrl($objPid->row(), '/articles/' . ((!\Config::get('disableAlias') && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id));
						}
					}
					else
					{
						if (($objCalendar = $objEvent->getRelated('pid')) !== null && ($objJumpTo = $objCalendar->getRelated('jumpTo')) !== null)
						{
							$strUrl = $this->generateFrontendUrl($objJumpTo->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/' : '/events/') . ((!\Config::get('disableAlias') && $objEvent->alias != '') ? $objEvent->alias : $objEvent->id));
						}
					}

					// Replace the tag
					switch (strtolower($elements[0]))
					{
						case 'event':
							$strLink = specialchars($objEvent->title);
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">%s</a>', $strUrl, $strLink, $strLink);
							break;

						case 'event_open':
							$arrCache[$strTag] = sprintf('<a href="%s" title="%s">', $strUrl, specialchars($objEvent->title));
							break;

						case 'event_url':
							$arrCache[$strTag] = $strUrl;
							break;

						case 'event_title':
							$arrCache[$strTag] = specialchars($objEvent->title);
							break;
					}
					break;

				// Article teaser
				case 'article_teaser':
					$objTeaser = \ArticleModel::findByIdOrAlias($elements[1]);

					if ($objTeaser !== null)
					{
						if ($objPage->outputFormat == 'xhtml')
						{
							$arrCache[$strTag] = \String::toXhtml($this->replaceInsertTags($objTeaser->teaser, $blnCache));
						}
						else
						{
							$arrCache[$strTag] = \String::toHtml5($this->replaceInsertTags($objTeaser->teaser, $blnCache));
						}
					}
					break;

				// News teaser
				case 'news_teaser':
					$objTeaser = \NewsModel::findByIdOrAlias($elements[1]);

					if ($objTeaser !== null)
					{
						if ($objPage->outputFormat == 'xhtml')
						{
							$arrCache[$strTag] = \String::toXhtml($this->replaceInsertTags($objTeaser->teaser, $blnCache));
						}
						else
						{
							$arrCache[$strTag] = \String::toHtml5($this->replaceInsertTags($objTeaser->teaser, $blnCache));
						}
					}
					break;

				// Event teaser
				case 'event_teaser':
					$objTeaser = \CalendarEventsModel::findByIdOrAlias($elements[1]);

					if ($objTeaser !== null)
					{
						if ($objPage->outputFormat == 'xhtml')
						{
							$arrCache[$strTag] = \String::toXhtml($this->replaceInsertTags($objTeaser->teaser, $blnCache));
						}
						else
						{
							$arrCache[$strTag] = \String::toHtml5($this->replaceInsertTags($objTeaser->teaser, $blnCache));
						}
					}
					break;

				// News feed URL
				case 'news_feed':
					$objFeed = \NewsFeedModel::findByPk($elements[1]);

					if ($objFeed !== null)
					{
						$arrCache[$strTag] = $objFeed->feedBase . 'share/' . $objFeed->alias . '.xml';
					}
					break;

				// Calendar feed URL
				case 'calendar_feed':
					$objFeed = \CalendarFeedModel::findByPk($elements[1]);

					if ($objFeed !== null)
					{
						$arrCache[$strTag] = $objFeed->feedBase . 'share/' . $objFeed->alias . '.xml';
					}
					break;

				// Last update
				case 'last_update':
					$strQuery = "SELECT MAX(tstamp) AS tc";

					if (in_array('news', \ModuleLoader::getActive()))
					{
						$strQuery .= ", (SELECT MAX(tstamp) FROM tl_news) AS tn";
					}

					if (in_array('calendar', \ModuleLoader::getActive()))
					{
						$strQuery .= ", (SELECT MAX(tstamp) FROM tl_calendar_events) AS te";
					}

					$strQuery .= " FROM tl_content";
					$objUpdate = \Database::getInstance()->query($strQuery);

					if ($objUpdate->numRows)
					{
						$arrCache[$strTag] = \Date::parse($elements[1] ?: \Config::get('datimFormat'), max($objUpdate->tc, $objUpdate->tn, $objUpdate->te));
					}
					break;

				// Version
				case 'version':
					$arrCache[$strTag] = VERSION . '.' . BUILD;
					break;

				// Request token
				case 'request_token':
					$arrCache[$strTag] = REQUEST_TOKEN;
					break;

				// POST data
				case 'post':
					$arrCache[$strTag] = \Input::post($elements[1]);
					break;

				// Mobile/desktop toggle (see #6469)
				case 'toggle_view':
					$strUrl = ampersand(\Environment::get('request'));
					$strGlue = (strpos($strUrl, '?') === false) ? '?' : '&amp;';

					if (\Input::cookie('TL_VIEW') == 'mobile' || (\Environment::get('agent')->mobile && \Input::cookie('TL_VIEW') != 'desktop'))
					{
						$arrCache[$strTag] = '<a href="' . $strUrl . $strGlue . 'toggle_view=desktop" class="toggle_desktop" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['toggleDesktop'][1]) . '">' . $GLOBALS['TL_LANG']['MSC']['toggleDesktop'][0] . '</a>';
					}
					else
					{
						$arrCache[$strTag] = '<a href="' . $strUrl . $strGlue . 'toggle_view=mobile" class="toggle_mobile" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['toggleMobile'][1]) . '">' . $GLOBALS['TL_LANG']['MSC']['toggleMobile'][0] . '</a>';
					}
					break;

				// Conditional tags (if)
				case 'iflng':
					if ($elements[1] != '' && $elements[1] != $objPage->language)
					{
						for (; $_rit<$_cnt; $_rit+=3)
						{
							if ($tags[$_rit+1] == 'iflng' || $tags[$_rit+1] == 'iflng::' . $objPage->language)
							{
								break;
							}
						}
					}
					unset($arrCache[$strTag]);
					break;

				// Conditional tags (if not)
				case 'ifnlng':
					if ($elements[1] != '')
					{
						$langs = trimsplit(',', $elements[1]);

						if (in_array($objPage->language, $langs))
						{
							for (; $_rit<$_cnt; $_rit+=3)
							{
								if ($tags[$_rit+1] == 'ifnlng')
								{
									break;
								}
							}
						}
					}
					unset($arrCache[$strTag]);
					break;

				// Environment
				case 'env':
					switch ($elements[1])
					{
						case 'host':
							$arrCache[$strTag] = \Idna::decode(\Environment::get('host'));
							break;

						case 'http_host':
							$arrCache[$strTag] = \Idna::decode(\Environment::get('httpHost'));
							break;

						case 'url':
							$arrCache[$strTag] = \Idna::decode(\Environment::get('url'));
							break;

						case 'path':
							$arrCache[$strTag] = \Idna::decode(\Environment::get('base'));
							break;

						case 'request':
							$arrCache[$strTag] = \Environment::get('indexFreeRequest');
							break;

						case 'ip':
							$arrCache[$strTag] = \Environment::get('ip');
							break;

						case 'referer':
							$arrCache[$strTag] = $this->getReferer(true);
							break;

						case 'files_url':
							$arrCache[$strTag] = TL_FILES_URL;
							break;

						case 'assets_url':
						case 'plugins_url':
						case 'script_url':
							$arrCache[$strTag] = TL_ASSETS_URL;
							break;
					}
					break;

				// Page
				case 'page':
					if ($elements[1] == 'pageTitle' && $objPage->pageTitle == '')
					{
						$elements[1] = 'title';
					}
					elseif ($elements[1] == 'parentPageTitle' && $objPage->parentPageTitle == '')
					{
						$elements[1] = 'parentTitle';
					}
					elseif ($elements[1] == 'mainPageTitle' && $objPage->mainPageTitle == '')
					{
						$elements[1] = 'mainTitle';
					}

					// Do not use specialchars() here (see #4687)
					$arrCache[$strTag] = $objPage->{$elements[1]};
					break;

				// User agent
				case 'ua':
					$ua = \Environment::get('agent');

					if ($elements[1] != '')
					{
						$arrCache[$strTag] = $ua->{$elements[1]};
					}
					else
					{
						$arrCache[$strTag] = '';
					}
					break;

				// Acronyms
				case 'acronym':
					if ($objPage->outputFormat == 'xhtml')
					{
						if ($elements[1] != '')
						{
							$arrCache[$strTag] = '<acronym title="'. $elements[1] .'">';
						}
						else
						{
							$arrCache[$strTag] = '</acronym>';
						}
						break;
					}
					// NO break;

				// Abbreviations
				case 'abbr':
					if ($elements[1] != '')
					{
						$arrCache[$strTag] = '<abbr title="'. $elements[1] .'">';
					}
					else
					{
						$arrCache[$strTag] = '</abbr>';
					}
					break;

				// Images
				case 'image':
				case 'picture':
					$width = null;
					$height = null;
					$alt = '';
					$class = '';
					$rel = '';
					$strFile = $elements[1];
					$mode = '';
					$size = null;
					$strTemplate = 'picture_default';

					// Take arguments
					if (strpos($elements[1], '?') !== false)
					{
						$arrChunks = explode('?', urldecode($elements[1]), 2);
						$strSource = \String::decodeEntities($arrChunks[1]);
						$strSource = str_replace('[&]', '&', $strSource);
						$arrParams = explode('&', $strSource);

						foreach ($arrParams as $strParam)
						{
							list($key, $value) = explode('=', $strParam);

							switch ($key)
							{
								case 'width':
									$width = $value;
									break;

								case 'height':
									$height = $value;
									break;

								case 'alt':
									$alt = specialchars($value);
									break;

								case 'class':
									$class = $value;
									break;

								case 'rel':
									$rel = $value;
									break;

								case 'mode':
									$mode = $value;
									break;

								case 'size':
									$size = (int) $value;
									break;

								case 'template':
									$strTemplate = preg_replace('/[^a-z0-9_]/i', '', $value);
									break;
							}
						}

						$strFile = $arrChunks[0];
					}

					if (\Validator::isUuid($strFile))
					{
						// Handle UUIDs
						$objFile = \FilesModel::findByUuid($strFile);

						if ($objFile === null)
						{
							$arrCache[$strTag] = '';
							break;
						}

						$strFile = $objFile->path;
					}
					elseif (is_numeric($strFile))
					{
						// Handle numeric IDs (see #4805)
						$objFile = \FilesModel::findByPk($strFile);

						if ($objFile === null)
						{
							$arrCache[$strTag] = '';
							break;
						}

						$strFile = $objFile->path;
					}
					else
					{
						// Check the path
						if (\Validator::isInsecurePath($strFile))
						{
							throw new \RuntimeException('Invalid path ' . $strFile);
						}
					}

					// Check the maximum image width
					if (\Config::get('maxImageWidth') > 0 && $width > \Config::get('maxImageWidth'))
					{
						$width = \Config::get('maxImageWidth');
						$height = null;
					}

					// Generate the thumbnail image
					try
					{
						// Image
						if (strtolower($elements[0]) == 'image')
						{
							$dimensions = '';
							$imageObj = \Image::create($strFile, array($width, $height, $mode));
							$src = $imageObj->executeResize()->getResizedPath();
							$objFile = new \File(rawurldecode($src), true);

							// Add the image dimensions
							if (($imgSize = $objFile->imageSize) !== false)
							{
								$dimensions = ' width="' . $imgSize[0] . '" height="' . $imgSize[1] . '"';
							}

							$arrCache[$strTag] = '<img src="' . TL_FILES_URL . $src . '" ' . $dimensions . ' alt="' . $alt . '"' . (($class != '') ? ' class="' . $class . '"' : '') . (($objPage->outputFormat == 'xhtml') ? ' />' : '>');
						}

						// Picture
						else
						{
							$picture = \Picture::create($strFile, array(0, 0, $size))->getTemplateData();
							$picture['alt'] = $alt;
							$picture['class'] = $class;
							$pictureTemplate = new \FrontendTemplate($strTemplate);
							$pictureTemplate->setData($picture);
							$arrCache[$strTag] = $pictureTemplate->parse();
						}

						// Add a lightbox link
						if ($rel != '')
						{
							if (strncmp($rel, 'lightbox', 8) !== 0 || $objPage->outputFormat == 'xhtml')
							{
								$attribute = ' rel="' . $rel . '"';
							}
							else
							{
								$attribute = ' data-lightbox="' . substr($rel, 8) . '"';
							}

							$arrCache[$strTag] = '<a href="' . TL_FILES_URL . $strFile . '"' . (($alt != '') ? ' title="' . $alt . '"' : '') . $attribute . '>' . $arrCache[$strTag] . '</a>';
						}
					}
					catch (\Exception $e)
					{
						$arrCache[$strTag] = '';
					}
					break;

				// Files (UUID or template path)
				case 'file':
					if (\Validator::isUuid($elements[1]))
					{
						$objFile = \FilesModel::findByUuid($elements[1]);

						if ($objFile !== null)
						{
							$arrCache[$strTag] = $objFile->path;
							break;
						}
					}

					$arrGet = $_GET;
					\Input::resetCache();
					$strFile = $elements[1];

					// Take arguments and add them to the $_GET array
					if (strpos($elements[1], '?') !== false)
					{
						$arrChunks = explode('?', urldecode($elements[1]));
						$strSource = \String::decodeEntities($arrChunks[1]);
						$strSource = str_replace('[&]', '&', $strSource);
						$arrParams = explode('&', $strSource);

						foreach ($arrParams as $strParam)
						{
							$arrParam = explode('=', $strParam);
							$_GET[$arrParam[0]] = $arrParam[1];
						}

						$strFile = $arrChunks[0];
					}

					// Check the path
					if (\Validator::isInsecurePath($strFile))
					{
						throw new \RuntimeException('Invalid path ' . $strFile);
					}

					// Include .php, .tpl, .xhtml and .html5 files
					if (preg_match('/\.(php|tpl|xhtml|html5)$/', $strFile) && file_exists(TL_ROOT . '/templates/' . $strFile))
					{
						ob_start();
						include TL_ROOT . '/templates/' . $strFile;
						$arrCache[$strTag] = ob_get_contents();
						ob_end_clean();
					}

					$_GET = $arrGet;
					\Input::resetCache();
					break;

				// HOOK: pass unknown tags to callback functions
				default:
					if (isset($GLOBALS['TL_HOOKS']['replaceInsertTags']) && is_array($GLOBALS['TL_HOOKS']['replaceInsertTags']))
					{
						foreach ($GLOBALS['TL_HOOKS']['replaceInsertTags'] as $callback)
						{
							$this->import($callback[0]);
							$varValue = $this->$callback[0]->$callback[1]($tag, $blnCache, $arrCache[$strTag], $flags, $tags, $arrCache, $_rit, $_cnt); // see #6672

							// Replace the tag and stop the loop
							if ($varValue !== false)
							{
								$arrCache[$strTag] = $varValue;
								break;
							}
						}
					}
					if (\Config::get('debugMode'))
					{
						$GLOBALS['TL_DEBUG']['unknown_insert_tags'][] = $strTag;
					}
					break;
			}

			// Handle the flags
			if (!empty($flags))
			{
				foreach ($flags as $flag)
				{
					switch ($flag)
					{
						case 'addslashes':
						case 'stripslashes':
						case 'standardize':
						case 'ampersand':
						case 'specialchars':
						case 'nl2br':
						case 'nl2br_pre':
						case 'strtolower':
						case 'utf8_strtolower':
						case 'strtoupper':
						case 'utf8_strtoupper':
						case 'ucfirst':
						case 'lcfirst':
						case 'ucwords':
						case 'trim':
						case 'rtrim':
						case 'ltrim':
						case 'utf8_romanize':
						case 'strrev':
						case 'urlencode':
						case 'rawurlencode':
							$arrCache[$strTag] = $flag($arrCache[$strTag]);
							break;

						case 'encodeEmail':
						case 'decodeEntities':
							$arrCache[$strTag] = \String::$flag($arrCache[$strTag]);
							break;

						case 'number_format':
							$arrCache[$strTag] = \System::getFormattedNumber($arrCache[$strTag], 0);
							break;

						case 'currency_format':
							$arrCache[$strTag] = \System::getFormattedNumber($arrCache[$strTag], 2);
							break;

						case 'readable_size':
							$arrCache[$strTag] = \System::getReadableSize($arrCache[$strTag]);
							break;

						// HOOK: pass unknown flags to callback functions
						default:
							if (isset($GLOBALS['TL_HOOKS']['insertTagFlags']) && is_array($GLOBALS['TL_HOOKS']['insertTagFlags']))
							{
								foreach ($GLOBALS['TL_HOOKS']['insertTagFlags'] as $callback)
								{
									$this->import($callback[0]);
									$varValue = $this->$callback[0]->$callback[1]($flag, $tag, $arrCache[$strTag], $flags, $blnCache, $tags, $arrCache, $_rit, $_cnt); // see #5806

									// Replace the tag and stop the loop
									if ($varValue !== false)
									{
										$arrCache[$strTag] = $varValue;
										break;
									}
								}
							}
							if (\Config::get('debugMode'))
							{
								$GLOBALS['TL_DEBUG']['unknown_insert_tag_flags'][] = $flag;
							}
							break;
					}
				}
			}

			$strBuffer .= $arrCache[$strTag];
		}

		return \String::restoreBasicEntities($strBuffer);
	}
}
