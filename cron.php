<?php

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class CronJob extends Frontend
{

	/**
	 * Initialize object (do not remove)
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
		$intWeekly = date('YW');
		$intDaily  = date('Ymd');
		$intHourly = date('YmdH');

		// Weekly jobs
		if (count($GLOBALS['TL_CRON']['weekly']) && (!$GLOBALS['TL_CONFIG']['cron_weekly'] || $GLOBALS['TL_CONFIG']['cron_weekly'] < $intWeekly))
		{
			foreach ($GLOBALS['TL_CRON']['weekly'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]();
			}

			$this->log('Running weekly cron jobs', 'CronJobs run()', TL_CRON);
			$this->Config->update("\$GLOBALS['TL_CONFIG']['cron_weekly']", $intWeekly);
		}

		// Daily jobs
		elseif (count($GLOBALS['TL_CRON']['daily']) && (!$GLOBALS['TL_CONFIG']['cron_daily'] || $GLOBALS['TL_CONFIG']['cron_daily'] < $intDaily))
		{
			foreach ($GLOBALS['TL_CRON']['daily'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]();
			}

			$this->log('Running daily cron jobs', 'CronJobs run()', TL_CRON);
			$this->Config->update("\$GLOBALS['TL_CONFIG']['cron_daily']", $intDaily);
		}

		// Hourly jobs
		elseif (count($GLOBALS['TL_CRON']['hourly']) && (!$GLOBALS['TL_CONFIG']['cron_hourly'] || $GLOBALS['TL_CONFIG']['cron_hourly'] < $intHourly))
		{
			foreach ($GLOBALS['TL_CRON']['hourly'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]();
			}

			$this->log('Running hourly cron jobs', 'CronJobs run()', TL_CRON);
			$this->Config->update("\$GLOBALS['TL_CONFIG']['cron_hourly']", $intHourly);
		}
	}
}


/**
 * Instantiate controller
 */
$objCronJob = new CronJob();
$objCronJob->run();

?>