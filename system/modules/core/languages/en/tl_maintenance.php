<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * Core translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 * 
 * @link http://help.transifex.com/intro/translating.html
 * @link https://www.transifex.com/projects/p/contao/language/en/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Purge data';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Please select the data you want to purge or rebuild.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Front end user';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'Automatically log in a front end user to index protected pages.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Job';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Description';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Purge data';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'The data has been purged';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'Live Update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Go to the Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Your Contao version %s is up to date';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'A newer Contao version %s is available';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Please enter your Live Update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'The temporary folder (system/tmp) is not writeable';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'View the change log';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Run the update';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Content of the update archive';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Backuped files';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Updated files';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Rebuild the search index';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Rebuild index';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'No searchable pages found';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Please wait for the page to load completely before you proceed!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Please wait while the search index is being rebuilt.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'The search index has been rebuilt. You can now proceed.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Please enter your %s here.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Purge the search index';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Truncates the tables <em>tl_search</em> and <em>tl_search_index</em>. Afterwards, you have to rebuild the search index (see above).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Purge the undo table';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Truncates the <em>tl_undo</em> table which stores the deleted records. This job permanently deletes these records.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Purge the version table';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Truncates the <em>tl_version</em> table which stores the previous versions of a record. This job permanently deletes these records.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Purge the image cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Removes the automatically generated images and then purges the page cache, so there are no links to deleted resources.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Purge the script cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Removes the automatically generated .css and .js files, recreates the internal style sheets and then purges the page cache.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Purge the page cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Removes the cached versions of the front end pages.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Purge the internal cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Removes the cached versions of the DCA and language files. You can permanently disable the internal cache in the back end settings.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Purge the temp folder';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Removes the temporary files.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'Recreate the XML files';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Recreates the XML files (sitemaps and feeds) and then purges the page cache, so there are no links to deleted resources.';
