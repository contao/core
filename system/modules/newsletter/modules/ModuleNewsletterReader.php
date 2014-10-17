<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Newsletter
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleNewsletterReader
 *
 * Front end module "newsletter reader".
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Newsletter
 */
class ModuleNewsletterReader extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_newsletter_reader';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['nl_reader'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && \Config::get('useAutoItem') && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
		}

		// Do not index or cache the page if no news item has been specified
		if (!\Input::get('items'))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

		$this->nl_channels = deserialize($this->nl_channels);

		// Do not index or cache the page if there are no channels
		if (!is_array($this->nl_channels) || empty($this->nl_channels))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		$this->Template->content = '';
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		$objNewsletter = \NewsletterModel::findSentByParentAndIdOrAlias(\Input::get('items'), $this->nl_channels);

		if ($objNewsletter === null)
		{
			// Do not index or cache the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send a 404 header
			header('HTTP/1.1 404 Not Found');
			$this->Template->content = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], \Input::get('items')) . '</p>';
			return;
		}

		// Overwrite the page title (see #2853 and #4955)
		if ($objNewsletter->subject != '')
		{
			$objPage->pageTitle = strip_tags(strip_insert_tags($objNewsletter->subject));
		}

		// Add enclosure
		if ($objNewsletter->addFile)
		{
			$this->addEnclosuresToTemplate($this->Template, $objNewsletter->row(), 'files');
		}

		// Support plain text newsletters (thanks to Hagen Klemp)
		if ($objNewsletter->sendText)
		{
			$nl2br = ($objPage->outputFormat == 'xhtml') ? 'nl2br_xhtml' : 'nl2br_html5';
			$strContent = $nl2br($objNewsletter->text);
		}
		else
		{
			$strContent = str_ireplace(' align="center"', '', $objNewsletter->content);
		}

		// Parse simple tokens and insert tags
		$strContent = $this->replaceInsertTags($strContent);
		$strContent = \String::parseSimpleTokens($strContent, array());

		// Encode e-mail addresses
		$strContent = \String::encodeEmail($strContent);

		$this->Template->content = $strContent;
		$this->Template->subject = $objNewsletter->subject;
	}
}
