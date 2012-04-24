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
	'Contao\\Ajax'              => 'system/modules/backend/Ajax.php',
	'Contao\\Automator'         => 'system/modules/backend/Automator.php',
	'Contao\\Backend'           => 'system/modules/backend/Backend.php',
	'Contao\\BackendModule'     => 'system/modules/backend/BackendModule.php',
	'Contao\\BackendTemplate'   => 'system/modules/backend/BackendTemplate.php',
	'Contao\\BackendUser'       => 'system/modules/backend/BackendUser.php',
	'Contao\\CheckBox'          => 'system/modules/backend/CheckBox.php',
	'Contao\\CheckBoxWizard'    => 'system/modules/backend/CheckBoxWizard.php',
	'Contao\\ChmodTable'        => 'system/modules/backend/ChmodTable.php',
	'Contao\\DataContainer'     => 'system/modules/backend/DataContainer.php',
	'Contao\\FileSelector'      => 'system/modules/backend/FileSelector.php',
	'Contao\\FileTree'          => 'system/modules/backend/FileTree.php',
	'Contao\\FileUpload'        => 'system/modules/backend/FileUpload.php',
	'Contao\\ImageSize'         => 'system/modules/backend/ImageSize.php',
	'Contao\\InputUnit'         => 'system/modules/backend/InputUnit.php',
	'Contao\\KeyValueWizard'    => 'system/modules/backend/KeyValueWizard.php',
	'Contao\\ListWizard'        => 'system/modules/backend/ListWizard.php',
	'Contao\\LiveUpdate'        => 'system/modules/backend/LiveUpdate.php',
	'Contao\\Messages'          => 'system/modules/backend/Messages.php',
	'Contao\\MetaWizard'        => 'system/modules/backend/MetaWizard.php',
	'Contao\\ModuleMaintenance' => 'system/modules/backend/ModuleMaintenance.php',
	'Contao\\ModuleUser'        => 'system/modules/backend/ModuleUser.php',
	'Contao\\ModuleWizard'      => 'system/modules/backend/ModuleWizard.php',
	'Contao\\OptionWizard'      => 'system/modules/backend/OptionWizard.php',
	'Contao\\PageSelector'      => 'system/modules/backend/PageSelector.php',
	'Contao\\PageTree'          => 'system/modules/backend/PageTree.php',
	'Contao\\Password'          => 'system/modules/backend/Password.php',
	'Contao\\PurgeData'         => 'system/modules/backend/PurgeData.php',
	'Contao\\RadioButton'       => 'system/modules/backend/RadioButton.php',
	'Contao\\RadioTable'        => 'system/modules/backend/RadioTable.php',
	'Contao\\RebuildIndex'      => 'system/modules/backend/RebuildIndex.php',
	'Contao\\SelectMenu'        => 'system/modules/backend/SelectMenu.php',
	'Contao\\StyleSheets'       => 'system/modules/backend/StyleSheets.php',
	'Contao\\TableWizard'       => 'system/modules/backend/TableWizard.php',
	'Contao\\TextArea'          => 'system/modules/backend/TextArea.php',
	'Contao\\TextField'         => 'system/modules/backend/TextField.php',
	'Contao\\TextStore'         => 'system/modules/backend/TextStore.php',
	'Contao\\Theme'             => 'system/modules/backend/Theme.php',
	'Contao\\TimePeriod'        => 'system/modules/backend/TimePeriod.php',
	'Contao\\TrblField'         => 'system/modules/backend/TrblField.php',

	// Drivers
	'Contao\\DC_File'           => 'system/modules/backend/drivers/DC_File.php',
	'Contao\\DC_Folder'         => 'system/modules/backend/drivers/DC_Folder.php',
	'Contao\\DC_Table'          => 'system/modules/backend/drivers/DC_Table.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'be_changelog'     => 'system/modules/backend/templates',
	'be_confirm'       => 'system/modules/backend/templates',
	'be_diff'          => 'system/modules/backend/templates',
	'be_error'         => 'system/modules/backend/templates',
	'be_files'         => 'system/modules/backend/templates',
	'be_help'          => 'system/modules/backend/templates',
	'be_install'       => 'system/modules/backend/templates',
	'be_live_update'   => 'system/modules/backend/templates',
	'be_login'         => 'system/modules/backend/templates',
	'be_main'          => 'system/modules/backend/templates',
	'be_maintenance'   => 'system/modules/backend/templates',
	'be_navigation'    => 'system/modules/backend/templates',
	'be_password'      => 'system/modules/backend/templates',
	'be_picker'        => 'system/modules/backend/templates',
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
