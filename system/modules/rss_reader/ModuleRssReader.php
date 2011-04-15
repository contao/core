<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    RssReader
 * @license    LGPL
 * @filesource
 */


/**
 * Include SimplePie classes
 */
require_once(TL_ROOT . '/plugins/simplepie/simplepie.inc');

if (!class_exists('idna_convert', false))
{
	require_once(TL_ROOT . '/plugins/simplepie/idna_convert.class.php');
}


/**
 * Class ModuleRssReader
 *
 * Front end module "rss reader".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleRssReader extends Module
{

	/**
	 * RSS feed
	 * @var object
	 */
	protected $objFeed;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'rss_default';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### RSS READER ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->objFeed = new SimplePie();
		$arrUrls = trimsplit('[\n\t ]', trim($this->rss_feed));

		if (count($arrUrls) > 1)
		{
			$this->objFeed->set_feed_url($arrUrls);
		}
		else
		{
			$this->objFeed->set_feed_url($arrUrls[0]);
		}

		$this->objFeed->set_output_encoding($GLOBALS['TL_CONFIG']['characterSet']);
		$this->objFeed->set_cache_location(TL_ROOT . '/system/tmp');
		$this->objFeed->enable_cache(false);

		if ($this->rss_cache > 0)
		{
			$this->objFeed->enable_cache(true);
			$this->objFeed->set_cache_duration($this->rss_cache);
		}

		if (!$this->objFeed->init())
		{
			$this->log('Error importing RSS feed "' . $this->rss_feed . '"', 'ModuleRssReader generate()', TL_ERROR);
			return '';
		}

		$this->objFeed->handle_content_type();
		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		if ($this->rss_template != 'rss_default')
		{
			$this->strTemplate = $this->rss_template;

			$this->Template = new FrontendTemplate($this->strTemplate);
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

		// Get items
		$arrItems = $this->objFeed->get_items(intval($this->skipFirst), $this->rss_numberOfItems);

		$limit = count($arrItems);
		$offset = 0;

		// Split pages
		if ($this->perPage > 0)
		{
			$page = $this->Input->get('page') ? $this->Input->get('page') : 1;
			$offset = (($page - 1) * $this->perPage);
			$limit = $this->perPage + $offset;

			$objPagination = new Pagination(count($arrItems), $this->perPage);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		$items = array();
		$last = min($limit, count($arrItems)) - 1;

		for ($i=$offset; $i<$limit && $i<count($arrItems); $i++)
		{
			$items[$i] = array
			(
				'link' => $arrItems[$i]->get_link(),
				'title' => $arrItems[$i]->get_title(),
				'permalink' => $arrItems[$i]->get_permalink(),
				'description' => str_replace(array('<?', '?>'), array('&lt;?', '?&gt;'), $arrItems[$i]->get_description()),
				'class' => (($i == 0) ? ' first' : (($i == $last) ? ' last' : '')) . ((($i % 2) == 0) ? ' even' : ' odd'),
				'pubdate' => $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrItems[$i]->get_date('U')),
				'category' => $arrItems[$i]->get_category(0)
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

?>