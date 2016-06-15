<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Dynamically add the permission check and parent table
 */
if (Input::get('do') == 'calendar')
{
	$GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_calendar_events';
	$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('tl_content_calendar', 'checkPermission');
	$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('tl_content_calendar', 'generateFeed');
	$GLOBALS['TL_DCA']['tl_content']['list']['operations']['toggle']['button_callback'] = array('tl_content_calendar', 'toggleIcon');
}


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_content_calendar extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit table tl_content
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set the root IDs
		if (!is_array($this->User->calendars) || empty($this->User->calendars))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->calendars;
		}

		// Check the current action
		switch (Input::get('act'))
		{
			case 'paste':
				// Allow
				break;

			case '': // empty
			case 'create':
			case 'select':
				// Check access to the news item
				if (!$this->checkAccessToElement(CURRENT_ID, $root, true))
				{
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				// Check access to the parent element if a content element is moved
				if ((Input::get('act') == 'cutAll' || Input::get('act') == 'copyAll') && !$this->checkAccessToElement(Input::get('pid'), $root, (Input::get('mode') == 2)))
				{
					$this->redirect('contao/main.php?act=error');
				}

				$objCes = $this->Database->prepare("SELECT id FROM tl_content WHERE ptable='tl_calendar_events' AND pid=?")
										 ->execute(CURRENT_ID);

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objCes->fetchEach('id'));
				$this->Session->setData($session);
				break;

			case 'cut':
			case 'copy':
				// Check access to the parent element if a content element is moved
				if (!$this->checkAccessToElement(Input::get('pid'), $root, (Input::get('mode') == 2)))
				{
					$this->redirect('contao/main.php?act=error');
				}
				// NO BREAK STATEMENT HERE

			default:
				// Check access to the content element
				if (!$this->checkAccessToElement(Input::get('id'), $root))
				{
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Check access to a particular content element
	 *
	 * @param integer $id
	 * @param array   $root
	 * @param boolean $blnIsPid
	 *
	 * @return boolean
	 */
	protected function checkAccessToElement($id, $root, $blnIsPid=false)
	{
		if ($blnIsPid)
		{
			$objCalendar = $this->Database->prepare("SELECT a.id, n.id AS nid FROM tl_calendar_events n, tl_calendar a WHERE n.id=? AND n.pid=a.id")
										  ->limit(1)
										  ->execute($id);
		}
		else
		{
			$objCalendar = $this->Database->prepare("SELECT a.id, n.id AS nid FROM tl_content c, tl_calendar_events n, tl_calendar a WHERE c.id=? AND c.pid=n.id AND n.pid=a.id")
										  ->limit(1)
										  ->execute($id);
		}

		// Invalid ID
		if ($objCalendar->numRows < 1)
		{
			$this->log('Invalid event content element ID ' . $id, __METHOD__, TL_ERROR);

			return false;
		}

		// The calendar is not mounted
		if (!in_array($objCalendar->id, $root))
		{
			$this->log('Not enough permissions to modify article ID ' . $objCalendar->nid . ' in calendar ID ' . $objCalendar->id, __METHOD__, TL_ERROR);

			return false;
		}

		return true;
	}


	/**
	 * Check for modified calendar feeds and update the XML files if necessary
	 */
	public function generateFeed()
	{
		$session = $this->Session->get('calendar_feed_updater');

		if (!is_array($session) || empty($session))
		{
			return;
		}

		$this->import('Calendar');

		foreach ($session as $id)
		{
			$this->Calendar->generateFeedsByCalendar($id);
		}

		$this->import('Automator');
		$this->Automator->generateSitemap();

		$this->Session->set('calendar_feed_updater', null);
	}


	/**
	 * Return the "toggle visibility" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->hasAccess('tl_content::invisible', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;id='.Input::get('id').'&amp;tid='.$row['id'].'&amp;state='.$row['invisible'];

		if ($row['invisible'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['invisible'] ? 0 : 1) . '"').'</a> ';
	}


	/**
	 * Toggle the visibility of an element
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		// Set the ID and action
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');

		if ($dc)
		{
			$dc->id = $intId; // see #8043
		}

		$this->checkPermission();

		// Check the field access
		if (!$this->User->hasAccess('tl_content::invisible', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish content element ID "'.$intId.'"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// The onload_callbacks vary depending on the dynamic parent table (see #4894)
		if (is_array($GLOBALS['TL_DCA']['tl_content']['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}(($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$callback(($dc ?: $this));
				}
			}
		}

		// Check permissions to publish
		if (!$this->User->hasAccess('tl_content::invisible', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide content element ID "'.$intId.'"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objVersions = new Versions('tl_content', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_content']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_content']['fields']['invisible']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_content SET tstamp=". time() .", invisible='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
	}
}
