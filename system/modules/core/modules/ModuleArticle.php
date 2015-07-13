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
 * Provides methodes to handle articles.
 *
 * @property integer $tstamp
 * @property string  $title
 * @property string  $alias
 * @property string  $inColumn
 * @property boolean $showTeaser
 * @property boolean $multiMode
 * @property string  $teaser
 * @property string  $teaserCssID
 * @property string  $classes
 * @property string  $keywords
 * @property boolean $printable
 * @property boolean $published
 * @property integer $start
 * @property integer $stop
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleArticle extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_article';

	/**
	 * No markup
	 * @var boolean
	 */
	protected $blnNoMarkup = false;


	/**
	 * Check whether the article is published
	 *
	 * @param boolean $blnNoMarkup
	 *
	 * @return string
	 */
	public function generate($blnNoMarkup=false)
	{
		if (TL_MODE == 'FE' && !BE_USER_LOGGED_IN && (!$this->published || ($this->start != '' && $this->start > time()) || ($this->stop != '' && $this->stop < time())))
		{
			return '';
		}

		$this->type = 'article';
		$this->blnNoMarkup = $blnNoMarkup;

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		if ($this->blnNoMarkup)
		{
			/** @var \FrontendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('mod_article_plain');

			$this->Template = $objTemplate;
			$this->Template->setData($this->arrData);
		}

		$alias = $this->alias ?: 'article';

		if (in_array($alias, array('article', 'top', 'wrapper', 'header', 'container', 'left', 'main', 'right', 'footer')))
		{
			$alias .= '-' . $this->id;
		}

		$alias = standardize($alias);

		// Generate the cssID if it is not set
		if ($this->cssID[0] == '')
		{
			$this->cssID = array($alias, $this->cssID[1]);
		}

		$this->Template->column = $this->inColumn;

		// Add the modification date
		$this->Template->timestamp = $this->tstamp;
		$this->Template->date = \Date::parse($objPage->datimFormat, $this->tstamp);

		// Clean the RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$this->teaser = \String::toXhtml($this->teaser);
		}
		else
		{
			$this->teaser = \String::toHtml5($this->teaser);
		}

		// Show the teaser only
		if ($this->multiMode && $this->showTeaser)
		{
			/** @var \FrontendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('mod_article_teaser');

			$this->Template = $objTemplate;
			$this->Template->setData($this->arrData);

			$this->cssID = array($alias, '');
			$arrCss = deserialize($this->teaserCssID);

			// Override the CSS ID and class
			if (is_array($arrCss) && count($arrCss) == 2)
			{
				if ($arrCss[0] == '')
				{
					$arrCss[0] = $alias;
				}

				$this->cssID = $arrCss;
			}

			$article = (!\Config::get('disableAlias') && $this->alias != '') ? $this->alias : $this->id;
			$href = '/articles/' . (($this->inColumn != 'main') ? $this->inColumn . ':' : '') . $article;

			$this->Template->headline = $this->headline;
			$this->Template->href = $this->generateFrontendUrl($objPage->row(), $href);
			$this->Template->teaser = $this->teaser;
			$this->Template->readMore = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $this->headline), true);
			$this->Template->more = $GLOBALS['TL_LANG']['MSC']['more'];

			return;
		}

		// Get section and article alias
		list($strSection, $strArticle) = explode(':', \Input::get('articles'));

		if ($strArticle === null)
		{
			$strArticle = $strSection;
		}

		// Overwrite the page title (see #2853 and #4955)
		if (!$this->blnNoMarkup && $strArticle != '' && ($strArticle == $this->id || $strArticle == $this->alias) && $this->title != '')
		{
			$objPage->pageTitle = strip_tags(strip_insert_tags($this->title));

			if ($this->teaser != '')
			{
				$objPage->description = $this->prepareMetaDescription($this->teaser);
			}
		}

		$this->Template->printable = false;
		$this->Template->backlink = false;

		// Back link
		if (!$this->multiMode && $strArticle != '' && ($strArticle == $this->id || $strArticle == $this->alias))
		{
			$this->Template->backlink = 'javascript:history.go(-1)'; // see #6955
			$this->Template->back = specialchars($GLOBALS['TL_LANG']['MSC']['goBack']);
		}

		$arrElements = array();
		$objCte = \ContentModel::findPublishedByPidAndTable($this->id, 'tl_article');

		if ($objCte !== null)
		{
			$intCount = 0;
			$intLast = $objCte->count() - 1;

			while ($objCte->next())
			{
				$arrCss = array();

				/** @var \ContentModel $objRow */
				$objRow = $objCte->current();

				// Add the "first" and "last" classes (see #2583)
				if ($intCount == 0 || $intCount == $intLast)
				{
					if ($intCount == 0)
					{
						$arrCss[] = 'first';
					}

					if ($intCount == $intLast)
					{
						$arrCss[] = 'last';
					}
				}

				$objRow->classes = $arrCss;
				$arrElements[] = $this->getContentElement($objRow, $this->strColumn);
				++$intCount;
			}
		}

		$this->Template->teaser = $this->teaser;
		$this->Template->elements = $arrElements;

		if ($this->keywords != '')
		{
			$GLOBALS['TL_KEYWORDS'] .= (($GLOBALS['TL_KEYWORDS'] != '') ? ', ' : '') . $this->keywords;
		}

		// Backwards compatibility
		if ($this->printable == 1)
		{
			$this->Template->printable = true;
			$this->Template->pdfButton = true;
		}

		// New structure
		elseif ($this->printable != '')
		{
			$options = deserialize($this->printable);

			if (!empty($options) && is_array($options))
			{
				$this->Template->printable = true;
				$this->Template->printButton = in_array('print', $options);
				$this->Template->pdfButton = in_array('pdf', $options);
				$this->Template->facebookButton = in_array('facebook', $options);
				$this->Template->twitterButton = in_array('twitter', $options);
				$this->Template->gplusButton = in_array('gplus', $options);
			}
		}

		// Add syndication variables
		if ($this->Template->printable)
		{
			$request = \Environment::get('indexFreeRequest');

			$this->Template->print = '#';
			$this->Template->encUrl = rawurlencode(\Environment::get('base') . \Environment::get('request'));
			$this->Template->encTitle = rawurlencode($objPage->pageTitle);
			$this->Template->href = $request . ((strpos($request, '?') !== false) ? '&amp;' : '?') . 'pdf=' . $this->id;

			$this->Template->printTitle = specialchars($GLOBALS['TL_LANG']['MSC']['printPage']);
			$this->Template->pdfTitle = specialchars($GLOBALS['TL_LANG']['MSC']['printAsPdf']);
			$this->Template->facebookTitle = specialchars($GLOBALS['TL_LANG']['MSC']['facebookShare']);
			$this->Template->twitterTitle = specialchars($GLOBALS['TL_LANG']['MSC']['twitterShare']);
			$this->Template->gplusTitle = specialchars($GLOBALS['TL_LANG']['MSC']['gplusShare']);
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['compileArticle']) && is_array($GLOBALS['TL_HOOKS']['compileArticle']))
		{
			foreach ($GLOBALS['TL_HOOKS']['compileArticle'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this->Template, $this->arrData, $this);
			}
		}
	}


	/**
	 * Print an article as PDF and stream it to the browser
	 */
	public function generatePdf()
	{
		$this->headline = $this->title;
		$this->printable = false;

		// Generate article
		$strArticle = $this->replaceInsertTags($this->generate(), false);
		$strArticle = html_entity_decode($strArticle, ENT_QUOTES, \Config::get('characterSet'));
		$strArticle = $this->convertRelativeUrls($strArticle, '', true);

		// Remove form elements and JavaScript links
		$arrSearch = array
		(
			'@<form.*</form>@Us',
			'@<a [^>]*href="[^"]*javascript:[^>]+>.*</a>@Us'
		);

		$strArticle = preg_replace($arrSearch, '', $strArticle);

		// HOOK: allow individual PDF routines
		if (isset($GLOBALS['TL_HOOKS']['printArticleAsPdf']) && is_array($GLOBALS['TL_HOOKS']['printArticleAsPdf']))
		{
			foreach ($GLOBALS['TL_HOOKS']['printArticleAsPdf'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($strArticle, $this);
			}
		}

		// URL decode image paths (see #6411)
		$strArticle = preg_replace_callback('@(src="[^"]+")@', function ($arg) {
			return rawurldecode($arg[0]);
		}, $strArticle);

		// Handle line breaks in preformatted text
		$strArticle = preg_replace_callback('@(<pre.*</pre>)@Us', function ($arg) {
			return str_replace("\n", '<br>', $arg[0]);
		}, $strArticle);

		// Default PDF export using TCPDF
		$arrSearch = array
		(
			'@<span style="text-decoration: ?underline;?">(.*)</span>@Us',
			'@(<img[^>]+>)@',
			'@(<div[^>]+block[^>]+>)@',
			'@[\n\r\t]+@',
			'@<br( /)?><div class="mod_article@',
			'@href="([^"]+)(pdf=[0-9]*(&|&amp;)?)([^"]*)"@'
		);

		$arrReplace = array
		(
			'<u>$1</u>',
			'<br>$1',
			'<br>$1',
			' ',
			'<div class="mod_article',
			'href="$1$4"'
		);

		$strArticle = preg_replace($arrSearch, $arrReplace, $strArticle);

		// TCPDF configuration
		$l['a_meta_dir'] = 'ltr';
		$l['a_meta_charset'] = \Config::get('characterSet');
		$l['a_meta_language'] = substr($GLOBALS['TL_LANGUAGE'], 0, 2);
		$l['w_page'] = 'page';

		// Include library
		require_once TL_ROOT . '/system/config/tcpdf.php';

		// Create new PDF document
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

		// Set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(PDF_AUTHOR);
		$pdf->SetTitle($this->title);
		$pdf->SetSubject($this->title);
		$pdf->SetKeywords($this->keywords);

		// Prevent font subsetting (huge speed improvement)
		$pdf->setFontSubsetting(false);

		// Remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// Set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

		// Set auto page breaks
		$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

		// Set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// Set some language-dependent strings
		$pdf->setLanguageArray($l);

		// Initialize document and add a page
		$pdf->AddPage();

		// Set font
		$pdf->SetFont(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN);

		// Write the HTML content
		$pdf->writeHTML($strArticle, true, 0, true, 0);

		// Close and output PDF document
		$pdf->lastPage();
		$pdf->Output(standardize(ampersand($this->title, false)) . '.pdf', 'D');

		// Stop script execution
		exit;
	}
}
