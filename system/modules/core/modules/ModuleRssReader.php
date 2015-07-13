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
 * Front end module "rss reader".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleRssReader extends \Module
{

	/**
	 * RSS feed
	 * @var \SimplePie
	 */
	protected $objFeed;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'rss_default';


	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['rss_reader'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->objFeed = new \SimplePie();
		$arrUrls = trimsplit('[\n\t ]', trim($this->rss_feed));

		if (count($arrUrls) > 1)
		{
			$this->objFeed->set_feed_url($arrUrls);
		}
		else
		{
			$this->objFeed->set_feed_url($arrUrls[0]);
		}

		$this->objFeed->set_output_encoding(\Config::get('characterSet'));
		$this->objFeed->set_cache_location(TL_ROOT . '/system/tmp');
		$this->objFeed->enable_cache(false);

		if ($this->rss_cache > 0)
		{
			$this->objFeed->enable_cache(true);
			$this->objFeed->set_cache_duration($this->rss_cache);
		}

		if (!$this->objFeed->init())
		{
			$this->log('Error importing RSS feed "' . $this->rss_feed . '"', __METHOD__, TL_ERROR);

			return '';
		}

		$this->objFeed->handle_content_type();

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		if ($this->rss_template != 'rss_default')
		{
			$this->strTemplate = $this->rss_template;

			/** @var \FrontendTemplate|object $objTemplate */
			$objTemplate = new \FrontendTemplate($this->strTemplate);

			$this->Template = $objTemplate;
			$this->Template->setData($this->arrData);
		}

		$this->Template->link = $this->objFeed->get_link();
		$this->Template->title = $this->objFeed->get_title();
		$this->Template->language = $this->objFeed->get_language();
		$this->Template->description = $this->objFeed->get_description();
		$this->Template->copyright = $this->objFeed->get_copyright();

		// Add image
		if ($this->objFeed->get_image_url())
		{
			$this->Template->image = true;
			$this->Template->src = $this->objFeed->get_image_url();
			$this->Template->alt = $this->objFeed->get_image_title();
			$this->Template->href = $this->objFeed->get_image_link();
			$this->Template->height = $this->objFeed->get_image_height();
			$this->Template->width = $this->objFeed->get_image_width();
		}

		// Get the items (see #6107)
		$arrItems = array_slice($this->objFeed->get_items(0, intval($this->numberOfItems) + intval($this->skipFirst)), intval($this->skipFirst), (intval($this->numberOfItems) ?: null));

		$limit = count($arrItems);
		$offset = 0;

		// Split pages
		if ($this->perPage > 0)
		{
			// Get the current page
			$id = 'page_r' . $this->id;
			$page = (\Input::get($id) !== null) ? \Input::get($id) : 1;

			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max(ceil(count($arrItems)/$this->perPage), 1))
			{
				/** @var \PageError404 $objHandler */
				$objHandler = new $GLOBALS['TL_PTY']['error_404']();
				$objHandler->generate($objPage->id);
			}

			// Set limit and offset
			$offset = (($page - 1) * $this->perPage);
			$limit = $this->perPage + $offset;

			$objPagination = new \Pagination(count($arrItems), $this->perPage, \Config::get('maxPaginationLinks'), $id);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		$items = array();
		$last = min($limit, count($arrItems)) - 1;

		/** @var \SimplePie_Item[] $arrItems */
		for ($i=$offset, $c=count($arrItems); $i<$limit && $i<$c; $i++)
		{
			$items[$i] = array
			(
				'link' => $arrItems[$i]->get_link(),
				'title' => $arrItems[$i]->get_title(),
				'permalink' => $arrItems[$i]->get_permalink(),
				'description' => str_replace(array('<?', '?>'), array('&lt;?', '?&gt;'), $arrItems[$i]->get_description()),
				'class' => (($i == 0) ? ' first' : '') . (($i == $last) ? ' last' : '') . ((($i % 2) == 0) ? ' even' : ' odd'),
				'pubdate' => \Date::parse($objPage->datimFormat, $arrItems[$i]->get_date('U')),
				'category' => $arrItems[$i]->get_category(0),
				'object' => $arrItems[$i]
			);

			// Add author
			if (($objAuthor = $arrItems[$i]->get_author(0)) != false)
			{
				$items[$i]['author'] = trim($objAuthor->name . ' ' . $objAuthor->email);
			}

			// Add enclosure
			if (($objEnclosure = $arrItems[$i]->get_enclosure(0)) != false)
			{
				$items[$i]['enclosure'] = $objEnclosure->get_link();
			}
		}

		$this->Template->items = array_values($items);
	}
}
