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
 * @package    Listing
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleListing
 *
 * Provide methods to render content element "listing".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleListing extends Module
{

	/**
	 * Primary key
	 * @var string
	 */
	protected $strPk = 'id';

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'list_default';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### LISTING ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Return if the table or the fields have not been set
		if ($this->list_table == '' || $this->list_fields == '')
		{
			return '';
		}

		// Disable the details page
		if ($this->Input->get('show') && $this->list_info == '')
		{
			return '';
		}

		// Fallback to the default template
		if ($this->list_layout == '')
		{
			$this->list_layout = 'list_default';
		}

		$this->strTemplate = $this->list_layout;
		$this->list_where = $this->replaceInsertTags($this->list_where);

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->import('String');

		$this->loadLanguageFile($this->list_table);
		$this->loadDataContainer($this->list_table);

		// List a single record
		if ($this->Input->get('show'))
		{
			$this->listSingleRecord($this->Input->get('show'));
			return;
		}

		$page = $this->Input->get('page') ? $this->Input->get('page') : 1;
		$per_page = $this->Input->get('per_page') ? $this->Input->get('per_page') : $this->perPage;


		/**
		 * Add the search menu
		 */
		$strWhere = '';
		$varKeyword = '';
		$strOptions = '';

		$this->Template->searchable = false;
		$arrSearchFields = trimsplit(',', $this->list_search);

		if (is_array($arrSearchFields) && !empty($arrSearchFields))
		{
			$this->Template->searchable = true;

			if ($this->Input->get('search') && $this->Input->get('for'))
			{
				$varKeyword = '%' . $this->Input->get('for') . '%';
				$strWhere = (!$this->list_where ? " WHERE " : " AND ") . $this->Input->get('search') . " LIKE ?";
			}

			foreach ($arrSearchFields as $field)
			{
				$strOptions .= '  <option value="' . $field . '"' . (($field == $this->Input->get('search')) ? ' selected="selected"' : '') . '>' . (strlen($label = $GLOBALS['TL_DCA'][$this->list_table]['fields'][$field]['label'][0]) ? $label : $field) . '</option>' . "\n";
			}
		}

		$this->Template->search_fields = $strOptions;


		/**
		 * Get the total number of records
		 */
		$strQuery = "SELECT COUNT(*) AS count FROM " . $this->list_table;

		if ($this->list_where)
		{
			$strQuery .= " WHERE " . $this->list_where;
		}

		$strQuery .=  $strWhere;
		$objTotal = $this->Database->prepare($strQuery)->execute($varKeyword);


		/**
		 * Get the selected records
		 */
		$strQuery = "SELECT " . $this->strPk . "," . $this->list_fields . " FROM " . $this->list_table;

		if ($this->list_where)
		{
			$strQuery .= " WHERE " . $this->list_where;
		}

		$strQuery .=  $strWhere;

		// Order by
		if ($this->Input->get('order_by'))
		{
			$strQuery .= " ORDER BY " . $this->Input->get('order_by') . ' ' . $this->Input->get('sort');
		}
		elseif ($this->list_sort)
		{
			$strQuery .= " ORDER BY " . $this->list_sort;
		}

		$objDataStmt = $this->Database->prepare($strQuery);

		// Limit
		if ($this->Input->get('per_page'))
		{
			$objDataStmt->limit($this->Input->get('per_page'), (($page - 1) * $per_page));
		}
		elseif ($this->perPage)
		{
			$objDataStmt->limit($this->perPage, (($page - 1) * $per_page));
		}

		$objData = $objDataStmt->execute($varKeyword);


		/**
		 * Prepare the URL
		 */
		$strUrl = preg_replace('/\?.*$/', '', $this->Environment->request);
		$blnQuery = false;

		foreach (preg_split('/&(amp;)?/', $_SERVER['QUERY_STRING']) as $fragment)
		{
			if ($fragment != '' && strncasecmp($fragment, 'order_by', 8) !== 0 && strncasecmp($fragment, 'sort', 4) !== 0 && strncasecmp($fragment, 'page', 4) !== 0)
			{
				$strUrl .= ((!$blnQuery && !$GLOBALS['TL_CONFIG']['disableAlias']) ? '?' : '&amp;') . $fragment;
				$blnQuery = true;
			}
		}

		$this->Template->url = $strUrl;
		$strVarConnector = ($blnQuery || $GLOBALS['TL_CONFIG']['disableAlias']) ? '&amp;' : '?';


		/**
		 * Prepare the data arrays
		 */
		$arrTh = array();
		$arrTd = array();
		$arrFields = trimsplit(',', $this->list_fields);

		// THEAD
		for ($i=0; $i<count($arrFields); $i++)
		{
			// Never show passwords
			if ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$arrFields[$i]]['inputType'] == 'password')
			{
				continue;
			}

			$class = '';
			$sort = 'asc';
			$strField = strlen($label = $GLOBALS['TL_DCA'][$this->list_table]['fields'][$arrFields[$i]]['label'][0]) ? $label : $arrFields[$i];

			// Add a CSS class to the order_by column
			if ($this->Input->get('order_by') == $arrFields[$i])
			{
				$sort = ($this->Input->get('sort') == 'asc') ? 'desc' : 'asc';
				$class = ' sorted ' . $this->Input->get('sort');
			}

			$arrTh[] = array
			(
				'link' => $strField,
				'href' => (ampersand($strUrl) . $strVarConnector . 'order_by=' . $arrFields[$i]) . '&amp;sort=' . $sort,
				'title' => specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['list_orderBy'], $strField)),
				'class' => $class . (($i == 0) ? ' col_first' : '') //. ((($i + 1) == count($arrFields)) ? ' col_last' : '')
			);
		}

		$arrRows = $objData->fetchAllAssoc();

		// TBODY
		for ($i=0; $i<count($arrRows); $i++)
		{
			$j = 0;
			$class = 'row_' . $i . (($i == 0) ? ' row_first' : '') . ((($i + 1) == count($arrRows)) ? ' row_last' : '') . ((($i % 2) == 0) ? ' even' : ' odd');

			foreach ($arrRows[$i] as $k=>$v)
			{
				// Skip the primary key
				if ($k == $this->strPk && !in_array($this->strPk, $arrFields))
				{
					continue;
				}

				// Never show passwords
				if ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType'] == 'password')
				{
					continue;
				}

				$value = $this->formatValue($k, $v);

				$arrTd[$class][$k] = array
				(
					'raw' => $v,
					'content' => ($value ? $value : '&nbsp;'),
					'class' => 'col_' . $j . (($j++ == 0) ? ' col_first' : '') . ($this->list_info ? '' : (($j >= (count($arrRows[$i]) - 1)) ? ' col_last' : '')),
					'id' => $arrRows[$i][$this->strPk],
					'field' => $k,
					'url' => $strUrl . $strVarConnector . 'show=' . $arrRows[$i][$this->strPk]
				);
			}
		}

		$this->Template->thead = $arrTh;
		$this->Template->tbody = $arrTd;


		/**
		 * Pagination
		 */
		$objPagination = new Pagination($objTotal->count, $per_page);
		$this->Template->pagination = $objPagination->generate("\n  ");
		$this->Template->per_page = $per_page;


		/**
		 * Template variables
		 */
		$this->Template->action = $this->getIndexFreeRequest();
		$this->Template->details = strlen($this->list_info) ? true : false;
		$this->Template->search_label = specialchars($GLOBALS['TL_LANG']['MSC']['search']);
		$this->Template->per_page_label = specialchars($GLOBALS['TL_LANG']['MSC']['list_perPage']);
		$this->Template->fields_label = $GLOBALS['TL_LANG']['MSC']['all_fields'][0];
		$this->Template->keywords_label = $GLOBALS['TL_LANG']['MSC']['keywords'];
		$this->Template->search = $this->Input->get('search');
		$this->Template->for = $this->Input->get('for');
		$this->Template->order_by = $this->Input->get('order_by');
		$this->Template->sort = $this->Input->get('sort');
		$this->Template->col_last = 'col_' . $j;
	}


	/**
	 * List a single record
	 * @param integer
	 */
	protected function listSingleRecord($id)
	{
		// Fallback template
		if (!strlen($this->list_info_layout))
		{
			$this->list_info_layout = 'info_default';
		}

		$this->Template = new FrontendTemplate($this->list_info_layout);

		$this->Template->record = array();
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->list_info = deserialize($this->list_info);

		$objRecord = $this->Database->prepare("SELECT " . $this->list_info . " FROM " . $this->list_table . " WHERE " . (strlen($this->list_info_where) ? $this->list_info_where . " AND " : "") . $this->strPk . "=?")
									->limit(1)
									->execute($id);

		if ($objRecord->numRows < 1)
		{
			return;
		}

		$arrFields = array();
		$arrRow = $objRecord->fetchAssoc();
		$limit = count($arrRow);
		$count = -1;

		foreach ($arrRow as $k=>$v)
		{
			// Never show passwords
			if ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType'] == 'password')
			{
				--$limit;
				continue;
			}

			$class = 'row_' . ++$count . (($count == 0) ? ' row_first' : '') . (($count >= ($limit - 1)) ? ' row_last' : '') . ((($count % 2) == 0) ? ' even' : ' odd');

			$arrFields[$k] = array
			(
				'raw' => $v,
				'label' => (strlen($label = $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['label'][0]) ? $label : $k),
				'content' => $this->formatValue($k, $v, true),
				'class' => $class
			);
		}

		$this->Template->record = $arrFields;
	}


	/**
	 * Format a value
	 * @param string
	 * @param mixed
	 * @param boolean
	 * @return mixed
	 */
	protected function formatValue($k, $value, $blnListSingle=false)
	{
		$value = deserialize($value);

		// Return if empty
		if (empty($value))
		{
			return '';
		}

		// Array
		if (is_array($value))
		{
			$value = implode(', ', $value);
		}

		// Date
		elseif ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['rgxp'] == 'date')
		{
			$value = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $value);
		}

		// Time
		elseif ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['rgxp'] == 'time')
		{
			$value = $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $value);
		}

		// Date and time
		elseif ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['rgxp'] == 'datim')
		{
			$value = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $value);
		}

		// URLs
		elseif ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['rgxp'] == 'url' && preg_match('@^(https?://|ftp://)@i', $value))
		{
			global $objPage;
			$value = '<a href="' . $value . '"' . (($objPage->outputFormat == 'xhtml') ? ' onclick="window.open(this.href);return false"' : ' target="_blank"') . '>' . $value . '</a>';
		}

		// E-mail addresses
		elseif ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['rgxp'] == 'email')
		{
			$value = $this->String->encodeEmail($value);
			$value = '<a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;' . $value . '">' . $value . '</a>';
		}

		// Reference
		elseif (is_array($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['reference']))
		{
			$value = $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['reference'][$value];
		}

		// Associative array
		elseif ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['isAssociative'] || array_is_assoc($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['options']))
		{
			if ($blnListSingle)
			{
				$value = $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['options'][$value];
			}
			else
			{
				$value = '<span class="value">[' . $value . ']</span> ' . $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['options'][$value];
			}
		}

		return $value;
	}
}

?>