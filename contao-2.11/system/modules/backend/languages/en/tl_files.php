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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_files']['name']       = array('Name', 'Contao automatically adds the file extension.');
$GLOBALS['TL_LANG']['tl_files']['fileupload'] = array('File upload', 'Browse your local computer and select the files you want to upload to the server.');
$GLOBALS['TL_LANG']['tl_files']['editor']     = array('Source editor', 'Here you can edit the file source.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_files']['upload']      = 'Upload files';
$GLOBALS['TL_LANG']['tl_files']['uploadNback'] = 'Upload files and go back';
$GLOBALS['TL_LANG']['tl_files']['editFF']      = 'Edit a file or folder';
$GLOBALS['TL_LANG']['tl_files']['uploadFF']    = 'Upload files to folder "%s"';
$GLOBALS['TL_LANG']['tl_files']['editFile']    = 'Edit file "%s"';
$GLOBALS['TL_LANG']['tl_files']['browseFiles'] = 'Browse files';
$GLOBALS['TL_LANG']['tl_files']['clearList']   = 'Clear list';
$GLOBALS['TL_LANG']['tl_files']['startUpload'] = 'Start upload';


/**
 * FancyUpload
 */
$GLOBALS['TL_LANG']['tl_files']['fancy_progressOverall'] = 'Overall Progress ({total})';
$GLOBALS['TL_LANG']['tl_files']['fancy_currentTitle']    = 'File Progress';
$GLOBALS['TL_LANG']['tl_files']['fancy_currentFile']     = 'Uploading {name}';
$GLOBALS['TL_LANG']['tl_files']['fancy_currentProgress'] = 'Upload: {bytesLoaded} with {rate}, {timeRemaining} remaining.';
$GLOBALS['TL_LANG']['tl_files']['fancy_remove']          = 'Remove';
$GLOBALS['TL_LANG']['tl_files']['fancy_removeTitle']     = 'Click to remove this entry.';
$GLOBALS['TL_LANG']['tl_files']['fancy_fileError']       = 'Upload failed';
$GLOBALS['TL_LANG']['tl_files']['fancy_duplicate']       = 'File <em>{name}</em> is already added, duplicates are not allowed.';
$GLOBALS['TL_LANG']['tl_files']['fancy_sizeLimitMin']    = 'File <em>{name}</em> (<em>{size}</em>) is too small, the minimal file size is {fileSizeMin}.';
$GLOBALS['TL_LANG']['tl_files']['fancy_sizeLimitMax']    = 'File <em>{name}</em> (<em>{size}</em>) is too big, the maximal file size is <em>{fileSizeMax}</em>.';
$GLOBALS['TL_LANG']['tl_files']['fancy_fileListMax']     = 'File <em>{name}</em> could not be added, amount of <em>{fileListMax} files</em> exceeded.';
$GLOBALS['TL_LANG']['tl_files']['fancy_fileListSizeMax'] = 'File <em>{name}</em> (<em>{size}</em>) is too big, overall filesize of <em>{fileListSizeMax}</em> exceeded.';
$GLOBALS['TL_LANG']['tl_files']['fancy_httpStatus']      = 'Server returned HTTP-Status <code>#{code}</code>';
$GLOBALS['TL_LANG']['tl_files']['fancy_securityError']   = 'Security error occured ({text})';
$GLOBALS['TL_LANG']['tl_files']['fancy_ioError']         = 'Error caused a send or load operation to fail ({text})';
$GLOBALS['TL_LANG']['tl_files']['fancy_uploadCompleted'] = 'Upload completed';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_files']['new']       = array('New folder', 'Create a new folder');
$GLOBALS['TL_LANG']['tl_files']['cut']       = array('Move file or folder', 'Move file or folder "%s"');
$GLOBALS['TL_LANG']['tl_files']['copy']      = array('Duplicate file or folder', 'Duplicate file or folder "%s"');
$GLOBALS['TL_LANG']['tl_files']['edit']      = array('Rename file or folder', 'Rename file or folder "%s"');
$GLOBALS['TL_LANG']['tl_files']['delete']    = array('Delete file or folder', 'Delete file or folder "%s"');
$GLOBALS['TL_LANG']['tl_files']['source']    = array('Edit file', 'Edit file "%s"');
$GLOBALS['TL_LANG']['tl_files']['protect']   = array('Protect folder', 'Protect folder "%s"');
$GLOBALS['TL_LANG']['tl_files']['unlock']    = array('Remove protection', 'Unprotect folder "%s"');
$GLOBALS['TL_LANG']['tl_files']['move']      = array('File upload', 'Upload files to the server');
$GLOBALS['TL_LANG']['tl_files']['pasteinto'] = array('Paste into', 'Paste into this folder');

?>