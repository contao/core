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
 * @package    Frontend
 * @license    LGPL
 */


/**
 * Register the module classes
 */
Autoloader::addClasses(array
(
	'ContentAccordion'   => 'system/modules/frontend/ContentAccordion.php',
	'ContentAlias'       => 'system/modules/frontend/ContentAlias.php',
	'ContentArticle'     => 'system/modules/frontend/ContentArticle.php',
	'ContentCode'        => 'system/modules/frontend/ContentCode.php',
	'ContentDownload'    => 'system/modules/frontend/ContentDownload.php',
	'ContentDownloads'   => 'system/modules/frontend/ContentDownloads.php',
	'ContentElement'     => 'system/modules/frontend/ContentElement.php',
	'ContentGallery'     => 'system/modules/frontend/ContentGallery.php',
	'ContentHeadline'    => 'system/modules/frontend/ContentHeadline.php',
	'ContentHtml'        => 'system/modules/frontend/ContentHtml.php',
	'ContentHyperlink'   => 'system/modules/frontend/ContentHyperlink.php',
	'ContentImage'       => 'system/modules/frontend/ContentImage.php',
	'ContentList'        => 'system/modules/frontend/ContentList.php',
	'ContentModule'      => 'system/modules/frontend/ContentModule.php',
	'ContentTable'       => 'system/modules/frontend/ContentTable.php',
	'ContentTeaser'      => 'system/modules/frontend/ContentTeaser.php',
	'ContentText'        => 'system/modules/frontend/ContentText.php',
	'ContentToplink'     => 'system/modules/frontend/ContentToplink.php',
	'Form'               => 'system/modules/frontend/Form.php',
	'FormCaptcha'        => 'system/modules/frontend/FormCaptcha.php',
	'FormCheckBox'       => 'system/modules/frontend/FormCheckBox.php',
	'FormExplanation'    => 'system/modules/frontend/FormExplanation.php',
	'FormFieldset'       => 'system/modules/frontend/FormFieldset.php',
	'FormFileUpload'     => 'system/modules/frontend/FormFileUpload.php',
	'FormHeadline'       => 'system/modules/frontend/FormHeadline.php',
	'FormHidden'         => 'system/modules/frontend/FormHidden.php',
	'FormHtml'           => 'system/modules/frontend/FormHtml.php',
	'FormPassword'       => 'system/modules/frontend/FormPassword.php',
	'FormRadioButton'    => 'system/modules/frontend/FormRadioButton.php',
	'FormSelectMenu'     => 'system/modules/frontend/FormSelectMenu.php',
	'FormSubmit'         => 'system/modules/frontend/FormSubmit.php',
	'FormTextArea'       => 'system/modules/frontend/FormTextArea.php',
	'FormTextField'      => 'system/modules/frontend/FormTextField.php',
	'Frontend'           => 'system/modules/frontend/Frontend.php',
	'FrontendTemplate'   => 'system/modules/frontend/FrontendTemplate.php',
	'FrontendUser'       => 'system/modules/frontend/FrontendUser.php',
	'Hybrid'             => 'system/modules/frontend/Hybrid.php',
	'Module'             => 'system/modules/frontend/Module.php',
	'ModuleArticle'      => 'system/modules/frontend/ModuleArticle.php',
	'ModuleArticleList'  => 'system/modules/frontend/ModuleArticleList.php',
	'ModuleArticlenav'   => 'system/modules/frontend/ModuleArticlenav.php',
	'ModuleBooknav'      => 'system/modules/frontend/ModuleBooknav.php',
	'ModuleBreadcrumb'   => 'system/modules/frontend/ModuleBreadcrumb.php',
	'ModuleCustomnav'    => 'system/modules/frontend/ModuleCustomnav.php',
	'ModuleFlash'        => 'system/modules/frontend/ModuleFlash.php',
	'ModuleHtml'         => 'system/modules/frontend/ModuleHtml.php',
	'ModuleLogin'        => 'system/modules/frontend/ModuleLogin.php',
	'ModuleLogout'       => 'system/modules/frontend/ModuleLogout.php',
	'ModuleNavigation'   => 'system/modules/frontend/ModuleNavigation.php',
	'ModulePersonalData' => 'system/modules/frontend/ModulePersonalData.php',
	'ModuleQuicklink'    => 'system/modules/frontend/ModuleQuicklink.php',
	'ModuleQuicknav'     => 'system/modules/frontend/ModuleQuicknav.php',
	'ModuleRandomImage'  => 'system/modules/frontend/ModuleRandomImage.php',
	'ModuleSearch'       => 'system/modules/frontend/ModuleSearch.php',
	'ModuleSitemap'      => 'system/modules/frontend/ModuleSitemap.php',
	'PageError403'       => 'system/modules/frontend/PageError403.php',
	'PageError404'       => 'system/modules/frontend/PageError404.php',
	'PageForward'        => 'system/modules/frontend/PageForward.php',
	'PageRedirect'       => 'system/modules/frontend/PageRedirect.php',
	'PageRegular'        => 'system/modules/frontend/PageRegular.php',
	'PageRoot'           => 'system/modules/frontend/PageRoot.php',
	'Pagination'         => 'system/modules/frontend/Pagination.php'
));

?>