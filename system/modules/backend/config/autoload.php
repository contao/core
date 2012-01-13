<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'Ajax'              => 'system/modules/backend/Ajax.php',
	'Automator'         => 'system/modules/backend/Automator.php',
	'Backend'           => 'system/modules/backend/Backend.php',
	'BackendModule'     => 'system/modules/backend/BackendModule.php',
	'BackendTemplate'   => 'system/modules/backend/BackendTemplate.php',
	'BackendUser'       => 'system/modules/backend/BackendUser.php',
	'CheckBox'          => 'system/modules/backend/CheckBox.php',
	'CheckBoxWizard'    => 'system/modules/backend/CheckBoxWizard.php',
	'ChmodTable'        => 'system/modules/backend/ChmodTable.php',
	'DataContainer'     => 'system/modules/backend/DataContainer.php',
	'DbInstaller'       => 'system/modules/backend/DbInstaller.php',
	'FileTree'          => 'system/modules/backend/FileTree.php',
	'FileUpload'        => 'system/modules/backend/FileUpload.php',
	'ImageSize'         => 'system/modules/backend/ImageSize.php',
	'InputUnit'         => 'system/modules/backend/InputUnit.php',
	'KeyValueWizard'    => 'system/modules/backend/KeyValueWizard.php',
	'ListWizard'        => 'system/modules/backend/ListWizard.php',
	'LiveUpdate'        => 'system/modules/backend/LiveUpdate.php',
	'Messages'          => 'system/modules/backend/Messages.php',
	'ModuleMaintenance' => 'system/modules/backend/ModuleMaintenance.php',
	'ModuleUser'        => 'system/modules/backend/ModuleUser.php',
	'ModuleWizard'      => 'system/modules/backend/ModuleWizard.php',
	'OptionWizard'      => 'system/modules/backend/OptionWizard.php',
	'PageTree'          => 'system/modules/backend/PageTree.php',
	'Password'          => 'system/modules/backend/Password.php',
	'PurgeData'         => 'system/modules/backend/PurgeData.php',
	'RadioButton'       => 'system/modules/backend/RadioButton.php',
	'RadioTable'        => 'system/modules/backend/RadioTable.php',
	'RebuildIndex'      => 'system/modules/backend/RebuildIndex.php',
	'SelectMenu'        => 'system/modules/backend/SelectMenu.php',
	'StyleSheets'       => 'system/modules/backend/StyleSheets.php',
	'TableWizard'       => 'system/modules/backend/TableWizard.php',
	'TextArea'          => 'system/modules/backend/TextArea.php',
	'TextField'         => 'system/modules/backend/TextField.php',
	'TextStore'         => 'system/modules/backend/TextStore.php',
	'Theme'             => 'system/modules/backend/Theme.php',
	'TimePeriod'        => 'system/modules/backend/TimePeriod.php',
	'TrblField'         => 'system/modules/backend/TrblField.php',

	// Drivers
	'DC_File'   => 'system/modules/backend/drivers/DC_File.php',
	'DC_Folder' => 'system/modules/backend/drivers/DC_Folder.php',
	'DC_Table'  => 'system/modules/backend/drivers/DC_Table.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'be_error'         => 'system/modules/backend/templates',
	'be_files'         => 'system/modules/backend/templates',
	'be_help'          => 'system/modules/backend/templates',
	'be_install'       => 'system/modules/backend/templates',
	'be_live_update'   => 'system/modules/backend/templates',
	'be_login'         => 'system/modules/backend/templates',
	'be_main'          => 'system/modules/backend/templates',
	'be_maintenance'   => 'system/modules/backend/templates',
	'be_merge'         => 'system/modules/backend/templates',
	'be_navigation'    => 'system/modules/backend/templates',
	'be_pagepicker'    => 'system/modules/backend/templates',
	'be_password'      => 'system/modules/backend/templates',
	'be_popup'         => 'system/modules/backend/templates',
	'be_preview'       => 'system/modules/backend/templates',
	'be_purge_data'    => 'system/modules/backend/templates',
	'be_rebuild_index' => 'system/modules/backend/templates',
	'be_referer'       => 'system/modules/backend/templates',
	'be_switch'        => 'system/modules/backend/templates',
	'be_welcome'       => 'system/modules/backend/templates',
	'be_widget'        => 'system/modules/backend/templates',
	'be_widget_chk'    => 'system/modules/backend/templates',
	'be_widget_pw'     => 'system/modules/backend/templates',
	'be_widget_rdo'    => 'system/modules/backend/templates',
	'be_wildcard'      => 'system/modules/backend/templates',
));

?>