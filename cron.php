<?php

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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
require('system/initialize.php');


/**
 * Class CronJob
 *
 * Cron job controller.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class CronJob extends Frontend
{

	/**
	 * Initialize the object (do not remove)
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Run the controller
	 */
	public function run()
	{
		// Do not run if there is POST data or the last execution was less than five minutes ago 
		if (!empty($_POST) || $this->hasToWait())
		{
			return;
		}

		$intWeekly = date('YW');
		$intDaily  = date('Ymd');
		$intHourly = date('YmdH');

		// Weekly jobs
		if (count($GLOBALS['TL_CRON']['weekly']) && $GLOBALS['TL_CONFIG']['cron_weekly'] != $intWeekly)
		{
			$this->log('Running weekly cron jobs', 'CronJobs run()', TL_CRON);

			foreach ($GLOBALS['TL_CRON']['weekly'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]();
			}

			$this->log('Weekly cron jobs complete', 'CronJobs run()', TL_CRON);
			$this->Config->update("\$GLOBALS['TL_CONFIG']['cron_weekly']", $intWeekly);
		}

		// Daily jobs
		elseif (count($GLOBALS['TL_CRON']['daily']) && $GLOBALS['TL_CONFIG']['cron_daily'] != $intDaily)
		{
			$this->log('Running daily cron jobs', 'CronJobs run()', TL_CRON);

			foreach ($GLOBALS['TL_CRON']['daily'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]();
			}

			$this->log('Daily cron jobs complete', 'CronJobs run()', TL_CRON);
			$this->Config->update("\$GLOBALS['TL_CONFIG']['cron_daily']", $intDaily);
		}

		// Hourly jobs
		elseif (count($GLOBALS['TL_CRON']['hourly']) && $GLOBALS['TL_CONFIG']['cron_hourly'] != $intHourly)
		{
			$this->log('Running hourly cron jobs', 'CronJobs run()', TL_CRON);

			foreach ($GLOBALS['TL_CRON']['hourly'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]();
			}

			$this->log('Hourly cron jobs complete', 'CronJobs run()', TL_CRON);
			$this->Config->update("\$GLOBALS['TL_CONFIG']['cron_hourly']", $intHourly);
		}
	}


	/**
	 * Check whether the last script execution was less than five minutes ago
	 * @return boolean
	 */
	protected function hasToWait()
	{
		$time = time();

		// Lock the table
		$this->Database->lockTables(array('tl_lock'=>'WRITE'));

		// Get the last execution date
		$objCron = $this->Database->prepare("SELECT * FROM tl_lock WHERE name='cron'")
								  ->limit(1)
								  ->execute();

		// Add the cron entry
		if ($objCron->numRows < 1)
		{
			$this->updateCronTxt($time);
			$this->Database->query("INSERT INTO tl_lock (name, tstamp) VALUES ('cron', $time)");
			$this->Database->unlockTables();

			return false;
		}

		// Last execution was less than five minutes ago
		if ($objCron->tstamp > (time() - 300))
		{
			$this->Database->unlockTables();
			return true;
		}

		// Store the new value
		$this->updateCronTxt($time);
		$this->Database->query("UPDATE tl_lock SET tstamp=$time WHERE name='cron'");
		$this->Database->unlockTables();

		return false;
	}


	/**
	 * Update the cron.txt file
	 * @param integer
	 */
	protected function updateCronTxt($time)
	{
		$objFile = new File('system/html/cron.txt');
		$objFile->write($time);
		$objFile->close();
	}
}


/**
 * Instantiate controller
 */
$objCronJob = new CronJob();
$objCronJob->run();

?>