<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ContentTable
 *
 * Front end content element "table".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentTable extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_table';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		global $objPage;

		$rows = deserialize($this->tableitems);
		$nl2br = ($objPage->outputFormat == 'xhtml') ? 'nl2br_xhtml' : 'nl2br_html5';

		$this->Template->id = 'table_' . $this->id;
		$this->Template->summary = specialchars($this->summary);
		$this->Template->useHeader = $this->thead ? true : false;
		$this->Template->useFooter = $this->tfoot ? true : false;
		$this->Template->useLeftTh = $this->tleft ? true : false;
		$this->Template->sortable = false;
		$this->Template->thousandsSeparator = $GLOBALS['TL_LANG']['MSC']['thousandsSeparator'];
		$this->Template->decimalSeparator = $GLOBALS['TL_LANG']['MSC']['decimalSeparator'];

		// Add the CSS and JavaScript files
		if ($this->sortable)
		{
			if ($objPage->hasMooTools)
			{
				$this->Template->sortable = true;
				$this->Template->hasMooTools = true;
				$GLOBALS['TL_CSS'][] = 'assets/mootools/tablesort/css/tablesort.css';
				$GLOBALS['TL_MOOTOOLS'][] = '<script' . (($objPage->outputFormat == 'xhtml') ? ' type="text/javascript"' : '') . ' src="' . TL_ASSETS_URL . 'assets/mootools/tablesort/js/tablesort.js"></script>';
			}
			elseif ($objPage->hasJQuery)
			{
				$this->Template->sortable = true;
				$this->Template->hasJQuery = true;
				$GLOBALS['TL_CSS'][] = 'assets/jquery/tablesorter/' . TABLESORTER . '/css/tablesorter.css';
				$GLOBALS['TL_JQUERY'][] = '<script' . (($objPage->outputFormat == 'xhtml') ? ' type="text/javascript"' : '') . ' src="' . TL_ASSETS_URL . 'assets/jquery/tablesorter/' . TABLESORTER . '/js/tablesorter.js"></script>';
			}
		}

		$arrHeader = array();
		$arrBody = array();
		$arrFooter = array();

		// Table header
		if ($this->thead)
		{
			foreach ($rows[0] as $i=>$v)
			{
				// Set table sort cookie
				if ($this->sortable && $i == $this->sortIndex)
				{
					$co = 'TS_TABLE_' . $this->id;
					$so = ($this->sortOrder == 'descending') ? 'desc' : 'asc';

					if (\Input::cookie($co) == '')
					{
						$this->setCookie($co, $i . '|' . $so, 0, '/');
					}
				}

				// Add cell
				$arrHeader[] = array
				(
					'class' => 'head_'.$i . (($i == 0) ? ' col_first' : '') . (($i == (count($rows[0]) - 1)) ? ' col_last' : ''),
					'content' => (($v != '') ? $nl2br($v) : '&nbsp;')
				);
			}

			array_shift($rows);
		}

		$this->Template->header = $arrHeader;
		$limit = $this->tfoot ? (count($rows)-1) : count($rows);

		// Table body
		for ($j=0; $j<$limit; $j++)
		{
			$class_tr = '';

			if ($j == 0)
			{
				$class_tr .= ' row_first';
			}

			if ($j == ($limit - 1))
			{
				$class_tr .= ' row_last';
			}

			$class_eo = (($j % 2) == 0) ? ' even' : ' odd';

			foreach ($rows[$j] as $i=>$v)
			{
				$class_td = '';

				if ($i == 0)
				{
					$class_td .= ' col_first';
				}

				if ($i == (count($rows[$j]) - 1))
				{
					$class_td .= ' col_last';
				}

				$arrBody['row_' . $j . $class_tr . $class_eo][] = array
				(
					'class' => 'col_'.$i . $class_td,
					'content' => (($v != '') ? $nl2br($v) : '&nbsp;')
				);
			}
		}

		$this->Template->body = $arrBody;

		// Table footer
		if ($this->tfoot)
		{
			foreach ($rows[(count($rows)-1)] as $i=>$v)
			{
				$arrFooter[] = array
				(
					'class' => 'foot_'.$i . (($i == 0) ? ' col_first' : '') . (($i == (count($rows[(count($rows)-1)]) - 1)) ? ' col_last' : ''),
					'content' => (($v != '') ? $nl2br($v) : '&nbsp;')
				);
			}
		}

		$this->Template->footer = $arrFooter;
	}
}
