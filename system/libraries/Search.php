<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Search
 *
 * Provide methods to handle indexing and searching.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Library
 */
class Search extends System
{

	/**
	 * Current object instance (Singleton)
	 * @var Search
	 */
	protected static $objInstance;


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Return the current object instance (Singleton)
	 * @return Search
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new self();
		}

		return self::$objInstance;
	}


	/**
	 * Index a single file
	 * @param array
	 * @return boolean
	 */
	public function indexPage($arrData)
	{
		$this->import('String');
		$this->import('Database');

		$arrSet['url'] = $arrData['url'];
		$arrSet['title'] = $arrData['title'];
		$arrSet['protected'] = $arrData['protected'];
		$arrSet['filesize'] = $arrData['filesize'];
		$arrSet['groups'] = $arrData['groups'];
		$arrSet['pid'] = $arrData['pid'];
		$arrSet['language'] = $arrData['language'];

		// Get filesize from raw content
		if (!$arrSet['filesize'])
		{
			$arrSet['filesize'] = number_format((strlen($arrData['content']) / 1024 ), 2, '.', '');
		}

		// Replace special characters
		$strContent = str_replace(array("\n", "\r", "\t", '&#160;', '&nbsp;'), ' ', $arrData['content']);
		unset($arrData['content']);

		$arrOuter = array();
		$arrInner = array();

		// Strip JavaScript (thanks to Pieter Dreef)
		while (preg_match('/<script[^>]*>/i', $strContent, $arrOuter, PREG_OFFSET_CAPTURE))
		{
			if (!preg_match('/<\/script>/i', $strContent, $arrInner, PREG_OFFSET_CAPTURE, (strlen($arrOuter[0][0]) + $arrOuter[0][1])))
			{
				break;
			}

			$strContent = substr($strContent, 0, $arrOuter[0][1]) . substr($strContent, (strlen($arrInner[0][0]) + $arrInner[0][1]));
		}

		// Strip non-indexable areas (thanks to Pieter Dreef)
		while (preg_match('/<!-- indexer::stop -->/', $strContent, $arrOuter, PREG_OFFSET_CAPTURE))
		{
			if (!preg_match('/<!-- indexer::continue -->/', $strContent, $arrInner, PREG_OFFSET_CAPTURE, (strlen($arrOuter[0][0]) + $arrOuter[0][1])))
			{
				break;
			}

			$strContent = substr($strContent, 0, $arrOuter[0][1]) . substr($strContent, (strlen($arrInner[0][0]) + $arrInner[0][1]));
		}

		// Calculate the checksum (see #4179)
		$arrSet['checksum'] = md5(preg_replace('/ +/', ' ', strip_tags($strContent)));

		// Return if the page is indexed and up to date
		$objIndex = $this->Database->prepare("SELECT id, checksum FROM tl_search WHERE url=? AND pid=?")
								   ->limit(1)
								   ->execute($arrSet['url'], $arrSet['pid']);

		if ($objIndex->numRows && $objIndex->checksum == $arrSet['checksum'])
		{
			return false;
		}

		$arrMatches = array();
		preg_match('/<\/head>/', $strContent, $arrMatches, PREG_OFFSET_CAPTURE);
		$intOffset = strlen($arrMatches[0][0]) + $arrMatches[0][1];

		// Split page in head and body section
		$strHead = substr($strContent, 0, $intOffset);
		$strBody = substr($strContent, $intOffset);

		unset($strContent);
		$tags = array();

		// Get description
		if (preg_match('/<meta[^>]+name="description"[^>]+content="([^"]*)"[^>]*>/i', $strHead, $tags))
		{
			$arrData['description'] = trim(preg_replace('/ +/', ' ', $this->String->decodeEntities($tags[1])));
		}

		// Get keywords
		if (preg_match('/<meta[^>]+name="keywords"[^>]+content="([^"]*)"[^>]*>/i', $strHead, $tags))
		{
			$arrData['keywords'] = trim(preg_replace('/ +/', ' ', $this->String->decodeEntities($tags[1])));
		}

		// Read title and alt attributes
		if (preg_match_all('/<* (title|alt)="([^"]*)"[^>]*>/i', $strBody, $tags))
		{
			$arrData['keywords'] .= ' ' . implode(', ', array_unique($tags[2]));
		}

		// Add a whitespace character before line-breaks and between consecutive tags (see #5363)
		$strBody = str_ireplace(array('<br', '><'), array(' <br', '> <'), $strBody);
		$strBody = strip_tags($strBody);

		// Put everything together
		$arrSet['text'] = $arrData['title'] . ' ' . $arrData['description'] . ' ' . $strBody . ' ' . $arrData['keywords'];
		$arrSet['text'] = trim(preg_replace('/ +/', ' ', $this->String->decodeEntities($arrSet['text'])));

		$arrSet['tstamp'] = time();

		// Update an existing old entry
		if ($objIndex->numRows)
		{
			$this->Database->prepare("UPDATE tl_search %s WHERE id=?")
						   ->set($arrSet)
						   ->execute($objIndex->id);

			$intInsertId = $objIndex->id;
		}

		// Add a new entry
		else
		{
			// Check for a duplicate record with the same checksum
			$objDuplicates = $this->Database->prepare("SELECT id, url FROM tl_search WHERE pid=? AND checksum=?")
											->limit(1)
											->execute($arrSet['pid'], $arrSet['checksum']);

			// Keep the existing record
			if ($objDuplicates->numRows)
			{
				// Update the URL if the new URL is shorter
				if (substr_count($arrSet['url'], '/') < substr_count($objDuplicates->url, '/') || preg_match('/page=[0-9]*$/', $objDuplicates->url))
				{
					$this->Database->prepare("UPDATE tl_search SET url=? WHERE id=?")
								   ->execute($arrSet['url'], $objDuplicates->id);
				}

				return false;
			}

			// Insert the new record if there is no duplicate
			else
			{
				$objInsertStmt = $this->Database->prepare("INSERT INTO tl_search %s")
												->set($arrSet)
												->execute();

				$intInsertId = $objInsertStmt->insertId;
			}
		}

		// Remove quotes
		$strText = str_replace(array('´', '`'), "'", $arrSet['text']);
		unset($arrSet);

		// Remove special characters
		if (function_exists('mb_eregi_replace'))
		{
			$strText = mb_eregi_replace('[^[:alnum:]\'\.:,_-]|- | -|\' | \'|\. |\.$|: |:$|, |,$', ' ', $strText);
		}
		else
		{
			$strText = preg_replace(array('/- /', '/ -/', "/' /", "/ '/", '/\. /', '/\.$/', '/: /', '/:$/', '/, /', '/,$/', '/[^\pN\pL\'\.:,_-]/u'), ' ', $strText);
		}

		// Split words
		$arrWords = preg_split('/ +/', utf8_strtolower($strText));
		$arrIndex = array();

		// Index words
		foreach ($arrWords as $strWord)
		{
			$strWord = trim($strWord);

			if (!strlen($strWord) || preg_match('/^[\.:,\'_-]+$/', $strWord))
			{
				continue;
			}

			if (preg_match('/^[\':,]/', $strWord))
			{
				$strWord = substr($strWord, 1);
			}

			if (preg_match('/[\':,\.]$/', $strWord))
			{
				$strWord = substr($strWord, 0, -1);
			}

			if (isset($arrIndex[$strWord]))
			{
				$arrIndex[$strWord]++;
				continue;
			}

			$arrIndex[$strWord] = 1;
		}

		// Remove existing index
		$this->Database->prepare("DELETE FROM tl_search_index WHERE pid=?")
					   ->execute($intInsertId);

		// Create new index
		$arrKeys = array();
		$arrValues = array();

		foreach ($arrIndex as $k=>$v)
		{
			$arrKeys[] = "(?, ?, ?, ?)";
			$arrValues[] = $intInsertId;
			$arrValues[] = $k;
			$arrValues[] = $v;
			$arrValues[] = $arrData['language'];
		}

		// Insert values
		$this->Database->prepare("INSERT INTO tl_search_index (pid, word, relevance, language) VALUES " . implode(", ", $arrKeys))
					   ->execute($arrValues);

		return true;
	}


	/**
	 * Search the database and return the result object
	 * @param string
	 * @param boolean
	 * @param array
	 * @param integer
	 * @param integer
	 * @param boolean
	 * @return Database_Result
	 * @throws Exception
	 */
	public function searchFor($strKeywords, $blnOrSearch=false, $arrPid=null, $intRows=0, $intOffset=0, $blnFuzzy=false)
	{
		$this->import('String');
		$this->import('Database');

		// Clean keywords
		$strKeywords = utf8_strtolower($strKeywords);
		$strKeywords = $this->String->decodeEntities($strKeywords);

		if (function_exists('mb_eregi_replace'))
		{
			$strKeywords = mb_eregi_replace('[^[:alnum:] \*\+\'"\.:,_-]|\. |\.$|: |:$|, |,$', ' ', $strKeywords);
		}
		else
		{
			$strKeywords = preg_replace(array('/\. /', '/\.$/', '/: /', '/:$/', '/, /', '/,$/', '/[^\pN\pL \*\+\'"\.:,_-]/iu'), ' ', $strKeywords);
		}

		// Check keyword string
		if (!strlen($strKeywords))
		{
			throw new Exception('Empty keyword string');
		}

		// Split keywords
		$arrChunks = array();
		preg_match_all('/"[^"]+"|[\+\-]?[^ ]+\*?/', $strKeywords, $arrChunks);

		$arrPhrases = array();
		$arrKeywords = array();
		$arrWildcards = array();
		$arrIncluded = array();
		$arrExcluded = array();

		foreach ($arrChunks[0] as $strKeyword)
		{
			if (substr($strKeyword, -1) == '*' && strlen($strKeyword) > 1)
			{
				$arrWildcards[] = str_replace('*', '%', $strKeyword);
				continue;
			}

			switch (substr($strKeyword, 0, 1))
			{
				// Phrases
				case '"':
					if (($strKeyword = trim(substr($strKeyword, 1, -1))) != false)
					{
						$arrPhrases[] = '[[:<:]]' . str_replace(array(' ', '*'), array('[^[:alnum:]]+', ''), $strKeyword) . '[[:>:]]';
					}
					break;

				// Included keywords
				case '+':
					if (($strKeyword = trim(substr($strKeyword, 1))) != false)
					{
						$arrIncluded[] = $strKeyword;
					}
					break;

				// Excluded keywords
				case '-':
					if (($strKeyword = trim(substr($strKeyword, 1))) != false)
					{
						$arrExcluded[] = $strKeyword;
					}
					break;

				// Wildcards
				case '*':
					if (strlen($strKeyword) > 1)
					{
						$arrWildcards[] = str_replace('*', '%', $strKeyword);
					}
					break;

				// Normal keywords
				default:
					$arrKeywords[] = $strKeyword;
					break;
			}
		}

		// Fuzzy search
		if ($blnFuzzy)
		{
			foreach ($arrKeywords as $strKeyword)
			{
				$arrWildcards[] = '%' . $strKeyword . '%';
			}

			$arrKeywords = array();
		}

		// Count keywords
		$intPhrases = count($arrPhrases);
		$intWildcards = count($arrWildcards);
		$intIncluded = count($arrIncluded);
		$intExcluded = count($arrExcluded);

		$intKeywords = 0;
		$arrValues = array();

		// Remember found words so we can highlight them later
		$strQuery = "SELECT tl_search_index.pid AS sid, GROUP_CONCAT(word) AS matches";

		// Get the number of wildcard matches
		if (!$blnOrSearch && $intWildcards)
		{
			$strQuery .= ", (SELECT COUNT(*) FROM tl_search_index WHERE (" . implode(' OR ', array_fill(0, $intWildcards, 'word LIKE ?')) . ") AND pid=sid) AS wildcards";
			$arrValues = array_merge($arrValues, $arrWildcards);
		}

		// Count the number of matches
		$strQuery .= ", COUNT(*) AS count";

		// Get the relevance
		$strQuery .= ", SUM(relevance) AS relevance";

		// Get meta information from tl_search
		$strQuery .= ", url, title, filesize, text, protected, groups";

		// Prepare keywords array
		$arrAllKeywords = array();

		// Get keywords
		if (!empty($arrKeywords))
		{
			$arrAllKeywords[] = implode(' OR ', array_fill(0, count($arrKeywords), 'word=?'));
			$arrValues = array_merge($arrValues, $arrKeywords);
			$intKeywords += count($arrKeywords);
		}

		// Get included keywords
		if ($intIncluded)
		{
			$arrAllKeywords[] = implode(' OR ', array_fill(0, $intIncluded, 'word=?'));
			$arrValues = array_merge($arrValues, $arrIncluded);
			$intKeywords += $intIncluded;
		}

		// Get keywords from phrases
		if ($intPhrases)
		{
			foreach ($arrPhrases as $strPhrase)
			{
				$arrWords = explode('[^[:alnum:]]+', utf8_substr($strPhrase, 7, -7));
				$arrAllKeywords[] = implode(' OR ', array_fill(0, count($arrWords), 'word=?'));
				$arrValues = array_merge($arrValues, $arrWords);
				$intKeywords += count($arrWords);
			}
		}

		// Get wildcards
		if ($intWildcards)
		{
			$arrAllKeywords[] = implode(' OR ', array_fill(0, $intWildcards, 'word LIKE ?'));
			$arrValues = array_merge($arrValues, $arrWildcards);
		}

		$strQuery .= " FROM tl_search_index LEFT JOIN tl_search ON(tl_search_index.pid=tl_search.id) WHERE (" . implode(' OR ', $arrAllKeywords) . ")";

		// Get phrases
		if ($intPhrases)
		{
			$strQuery .= " AND (" . implode(($blnOrSearch ? ' OR ' : ' AND '), array_fill(0, $intPhrases, 'tl_search_index.pid IN(SELECT id FROM tl_search WHERE text REGEXP ?)')) . ")";
			$arrValues = array_merge($arrValues, $arrPhrases);
		}

		// Include keywords
		if ($intIncluded)
		{
			$strQuery .= " AND tl_search_index.pid IN(SELECT pid FROM tl_search_index WHERE " . implode(' OR ', array_fill(0, $intIncluded, 'word=?')) . ")";
			$arrValues = array_merge($arrValues, $arrIncluded);
		}

		// Exclude keywords
		if ($intExcluded)
		{
			$strQuery .= " AND tl_search_index.pid NOT IN(SELECT pid FROM tl_search_index WHERE " . implode(' OR ', array_fill(0, $intExcluded, 'word=?')) . ")";
			$arrValues = array_merge($arrValues, $arrExcluded);
		}

		// Limit results to a particular set of pages
		if (is_array($arrPid) && !empty($arrPid))
		{
			$strQuery .= " AND tl_search_index.pid IN(SELECT id FROM tl_search WHERE pid IN(" . implode(',', array_map('intval', $arrPid)) . "))";
		}

		$strQuery .= " GROUP BY tl_search_index.pid";

		// Make sure to find all words
		if (!$blnOrSearch)
		{
			// Number of keywords without wildcards
			$strQuery .= " HAVING count >= " . $intKeywords;

			// Dynamically add the number of wildcard matches
			if ($intWildcards)
			{
				$strQuery .= " + IF(wildcards>" . $intWildcards . ", wildcards, " . $intWildcards . ")";
			}
		}

		// Sort by relevance
		$strQuery .= " ORDER BY relevance DESC";

		// Return result
		$objResultStmt = $this->Database->prepare($strQuery);

		if ($intRows > 0)
		{
			$objResultStmt->limit($intRows, $intOffset);
		}

		return $objResultStmt->execute($arrValues);
	}
}

?>