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
 * Provide methods to render content element "listing".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleListing extends \Module
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
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['listing'][0]) . ' ###';
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
		if (\Input::get('show') && $this->list_info == '')
		{
			return '';
		}

		// Fallback to the default template
		if ($this->list_layout == '')
		{
			$this->list_layout = 'list_default';
		}

		$this->strTemplate = $this->list_layout;

		$this->list_where = $this->replaceInsertTags($this->list_where, false);
		$this->list_info_where = $this->replaceInsertTags($this->list_info_where, false);

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		\System::loadLanguageFile($this->list_table);
		$this->loadDataContainer($this->list_table);

		// List a single record
		if (\Input::get('show'))
		{
			$this->listSingleRecord(\Input::get('show'));

			return;
		}


		/**
		 * Add the search menu
		 */
		$strWhere = '';
		$varKeyword = '';
		$strOptions = '';

		$this->Template->searchable = false;
		$arrSearchFields = trimsplit(',', $this->list_search);

		if (!empty($arrSearchFields) && is_array($arrSearchFields))
		{
			$this->Template->searchable = true;

			if (\Input::get('search') && \Input::get('for'))
			{
				$varKeyword = '%' . \Input::get('for') . '%';
				$strWhere = (!$this->list_where ? " WHERE " : " AND ") . \Input::get('search') . " LIKE ?";
			}

			foreach ($arrSearchFields as $field)
			{
				$strOptions .= '  <option value="' . $field . '"' . (($field == \Input::get('search')) ? ' selected="selected"' : '') . '>' . (strlen($label = $GLOBALS['TL_DCA'][$this->list_table]['fields'][$field]['label'][0]) ? $label : $field) . '</option>' . "\n";
			}
		}

		$this->Template->search_fields = $strOptions;


		/**
		 * Get the total number of records
		 */
		$strQuery = "SELECT COUNT(*) AS count FROM " . $this->list_table;

		if ($this->list_where)
		{
			$strQuery .= " WHERE (" . $this->list_where . ")";
		}

		$strQuery .=  $strWhere;
		$objTotal = $this->Database->prepare($strQuery)->execute($varKeyword);


		/**
		 * Validate the page count
		 */
		$id = 'page_l' . $this->id;
		$page = (\Input::get($id) !== null) ? \Input::get($id) : 1;
		$per_page = \Input::get('per_page') ?: $this->perPage;

		// Thanks to Hagen Klemp (see #4485)
		if ($per_page > 0 && ($page < 1 || $page > max(ceil($objTotal->count/$per_page), 1)))
		{
			/** @var \PageModel $objPage */
			global $objPage;

			/** @var \PageError404 $objHandler */
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($objPage->id);
		}


		/**
		 * Get the selected records
		 */
		$strQuery = "SELECT " . $this->strPk . "," . $this->list_fields;

		if ($this->list_info_where)
		{
			$strQuery .= ", (SELECT COUNT(*) FROM " . $this->list_table . " t2 WHERE t2." . $this->strPk . "=t1." . $this->strPk . " AND " . $this->list_info_where . ") AS _details";
		}

		$strQuery .= " FROM " . $this->list_table . " t1";

		if ($this->list_where)
		{
			$strQuery .= " WHERE (" . $this->list_where . ")";
		}

		$strQuery .= $strWhere;

		// Cast date fields to int (see #5609)
		$isInt = function ($field) {
			return $GLOBALS['TL_DCA'][$this->list_table]['fields'][$field]['eval']['rgxp'] == 'date' || $GLOBALS['TL_DCA'][$this->list_table]['fields'][$field]['eval']['rgxp'] == 'time' || $GLOBALS['TL_DCA'][$this->list_table]['fields'][$field]['eval']['rgxp'] == 'datim';
		};

		// Order by
		if (\Input::get('order_by'))
		{
			if ($isInt(\Input::get('order_by')))
			{
				$strQuery .= " ORDER BY CAST(" . \Input::get('order_by') . " AS SIGNED) " . \Input::get('sort');
			}
			else
			{
				$strQuery .= " ORDER BY " . \Input::get('order_by') . ' ' . \Input::get('sort');
			}
		}
		elseif ($this->list_sort)
		{
			if ($isInt($this->list_sort))
			{
				$strQuery .= " ORDER BY CAST(" . $this->list_sort . " AS SIGNED)";
			}
			else
			{
				$strQuery .= " ORDER BY " . $this->list_sort;
			}
		}

		$objDataStmt = $this->Database->prepare($strQuery);

		// Limit
		if (\Input::get('per_page'))
		{
			$objDataStmt->limit(\Input::get('per_page'), (($page - 1) * $per_page));
		}
		elseif ($this->perPage)
		{
			$objDataStmt->limit($this->perPage, (($page - 1) * $per_page));
		}

		$objData = $objDataStmt->execute($varKeyword);


		/**
		 * Prepare the URL
		 */
		$strUrl = preg_replace('/\?.*$/', '', \Environment::get('request'));
		$blnQuery = false;

		foreach (preg_split('/&(amp;)?/', \Environment::get('queryString')) as $fragment)
		{
			if ($fragment != '' && strncasecmp($fragment, 'order_by', 8) !== 0 && strncasecmp($fragment, 'sort', 4) !== 0 && strncasecmp($fragment, $id, strlen($id)) !== 0)
			{
				$strUrl .= ((!$blnQuery && !\Config::get('disableAlias')) ? '?' : '&amp;') . $fragment;
				$blnQuery = true;
			}
		}

		$this->Template->url = $strUrl;
		$strVarConnector = ($blnQuery || \Config::get('disableAlias')) ? '&amp;' : '?';


		/**
		 * Prepare the data arrays
		 */
		$arrTh = array();
		$arrTd = array();
		$arrFields = trimsplit(',', $this->list_fields);

		// THEAD
		for ($i=0, $c=count($arrFields); $i<$c; $i++)
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
			if (\Input::get('order_by') == $arrFields[$i])
			{
				$sort = (\Input::get('sort') == 'asc') ? 'desc' : 'asc';
				$class = ' sorted ' . \Input::get('sort');
			}

			$arrTh[] = array
			(
				'link' => $strField,
				'href' => (ampersand($strUrl) . $strVarConnector . 'order_by=' . $arrFields[$i]) . '&amp;sort=' . $sort,
				'title' => specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['list_orderBy'], $strField)),
				'class' => $class . (($i == 0) ? ' col_first' : '') //. ((($i + 1) == count($arrFields)) ? ' col_last' : '')
			);
		}

		$j = 0;
		$arrRows = $objData->fetchAllAssoc();

		// TBODY
		for ($i=0, $c=count($arrRows); $i<$c; $i++)
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

				if ($k == '_details')
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
					'url' => $strUrl . $strVarConnector . 'show=' . $arrRows[$i][$this->strPk],
					'details' => (isset($arrRows[$i]['_details']) ? $arrRows[$i]['_details'] : 1)
				);
			}
		}

		$this->Template->thead = $arrTh;
		$this->Template->tbody = $arrTd;


		/**
		 * Pagination
		 */
		$objPagination = new \Pagination($objTotal->count, $per_page, \Config::get('maxPaginationLinks'), $id);
		$this->Template->pagination = $objPagination->generate("\n  ");
		$this->Template->per_page = $per_page;
		$this->Template->total = $objTotal->count;


		/**
		 * Template variables
		 */
		$this->Template->action = \Environment::get('indexFreeRequest');
		$this->Template->details = ($this->list_info != '') ? true : false;
		$this->Template->search_label = specialchars($GLOBALS['TL_LANG']['MSC']['search']);
		$this->Template->per_page_label = specialchars($GLOBALS['TL_LANG']['MSC']['list_perPage']);
		$this->Template->fields_label = $GLOBALS['TL_LANG']['MSC']['all_fields'][0];
		$this->Template->keywords_label = $GLOBALS['TL_LANG']['MSC']['keywords'];
		$this->Template->search = \Input::get('search');
		$this->Template->for = \Input::get('for');
		$this->Template->order_by = \Input::get('order_by');
		$this->Template->sort = \Input::get('sort');
		$this->Template->col_last = 'col_' . $j;
	}


	/**
	 * List a single record
	 *
	 * @param integer $id
	 */
	protected function listSingleRecord($id)
	{
		// Fallback template
		if (!strlen($this->list_info_layout))
		{
			$this->list_info_layout = 'info_default';
		}

		/** @var \FrontendTemplate|object $objTemplate */
		$objTemplate = new \FrontendTemplate($this->list_info_layout);

		$this->Template = $objTemplate;
		$this->Template->record = array();
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		$this->list_info = deserialize($this->list_info);
		$this->list_info_where = $this->replaceInsertTags($this->list_info_where, false);

		$objRecord = $this->Database->prepare("SELECT " . $this->list_info . " FROM " . $this->list_table . " WHERE " . (($this->list_info_where != '') ? "(" . $this->list_info_where . ") AND " : "") . $this->strPk . "=?")
									->limit(1)
									->execute($id);

		if ($objRecord->numRows < 1)
		{
			return;
		}

		$arrFields = array();
		$arrRow = $objRecord->row();
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
	 *
	 * @param string  $k
	 * @param mixed   $value
	 * @param boolean $blnListSingle
	 *
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

		/** @var \PageModel $objPage */
		global $objPage;

		// Array
		if (is_array($value))
		{
			$value = implode(', ', $value);
		}

		// Date
		elseif ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['rgxp'] == 'date')
		{
			$value = \Date::parse($objPage->dateFormat, $value);
		}

		// Time
		elseif ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['rgxp'] == 'time')
		{
			$value = \Date::parse($objPage->timeFormat, $value);
		}

		// Date and time
		elseif ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['rgxp'] == 'datim')
		{
			$value = \Date::parse($objPage->datimFormat, $value);
		}

		// URLs
		elseif ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['rgxp'] == 'url' && preg_match('@^(https?://|ftp://)@i', $value))
		{
			$value = \Idna::decode($value); // see #5946
			$value = '<a href="' . $value . '"' . (($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"') . '>' . $value . '</a>';
		}

		// E-mail addresses
		elseif ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['rgxp'] == 'email')
		{
			$value = \String::encodeEmail(\Idna::decode($value)); // see #5946
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
