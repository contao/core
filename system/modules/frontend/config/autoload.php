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
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'Contao\\ContentAccordion'    => 'system/modules/frontend/ContentAccordion.php',
	'Contao\\ContentAlias'        => 'system/modules/frontend/ContentAlias.php',
	'Contao\\ContentArticle'      => 'system/modules/frontend/ContentArticle.php',
	'Contao\\ContentCode'         => 'system/modules/frontend/ContentCode.php',
	'Contao\\ContentDownload'     => 'system/modules/frontend/ContentDownload.php',
	'Contao\\ContentDownloads'    => 'system/modules/frontend/ContentDownloads.php',
	'Contao\\ContentElement'      => 'system/modules/frontend/ContentElement.php',
	'Contao\\ContentGallery'      => 'system/modules/frontend/ContentGallery.php',
	'Contao\\ContentHeadline'     => 'system/modules/frontend/ContentHeadline.php',
	'Contao\\ContentHtml'         => 'system/modules/frontend/ContentHtml.php',
	'Contao\\ContentHyperlink'    => 'system/modules/frontend/ContentHyperlink.php',
	'Contao\\ContentImage'        => 'system/modules/frontend/ContentImage.php',
	'Contao\\ContentList'         => 'system/modules/frontend/ContentList.php',
	'Contao\\ContentModule'       => 'system/modules/frontend/ContentModule.php',
	'Contao\\ContentTable'        => 'system/modules/frontend/ContentTable.php',
	'Contao\\ContentTeaser'       => 'system/modules/frontend/ContentTeaser.php',
	'Contao\\ContentText'         => 'system/modules/frontend/ContentText.php',
	'Contao\\ContentToplink'      => 'system/modules/frontend/ContentToplink.php',
	'Contao\\Form'                => 'system/modules/frontend/Form.php',
	'Contao\\FormCaptcha'         => 'system/modules/frontend/FormCaptcha.php',
	'Contao\\FormCheckBox'        => 'system/modules/frontend/FormCheckBox.php',
	'Contao\\FormExplanation'     => 'system/modules/frontend/FormExplanation.php',
	'Contao\\FormFieldset'        => 'system/modules/frontend/FormFieldset.php',
	'Contao\\FormFileUpload'      => 'system/modules/frontend/FormFileUpload.php',
	'Contao\\FormHeadline'        => 'system/modules/frontend/FormHeadline.php',
	'Contao\\FormHidden'          => 'system/modules/frontend/FormHidden.php',
	'Contao\\FormHtml'            => 'system/modules/frontend/FormHtml.php',
	'Contao\\FormPassword'        => 'system/modules/frontend/FormPassword.php',
	'Contao\\FormRadioButton'     => 'system/modules/frontend/FormRadioButton.php',
	'Contao\\FormSelectMenu'      => 'system/modules/frontend/FormSelectMenu.php',
	'Contao\\FormSubmit'          => 'system/modules/frontend/FormSubmit.php',
	'Contao\\FormTextArea'        => 'system/modules/frontend/FormTextArea.php',
	'Contao\\FormTextField'       => 'system/modules/frontend/FormTextField.php',
	'Contao\\Frontend'            => 'system/modules/frontend/Frontend.php',
	'Contao\\FrontendTemplate'    => 'system/modules/frontend/FrontendTemplate.php',
	'Contao\\FrontendUser'        => 'system/modules/frontend/FrontendUser.php',
	'Contao\\Hybrid'              => 'system/modules/frontend/Hybrid.php',
	'Contao\\Module'              => 'system/modules/frontend/Module.php',
	'Contao\\ModuleArticle'       => 'system/modules/frontend/ModuleArticle.php',
	'Contao\\ModuleArticleList'   => 'system/modules/frontend/ModuleArticleList.php',
	'Contao\\ModuleArticlenav'    => 'system/modules/frontend/ModuleArticlenav.php',
	'Contao\\ModuleBooknav'       => 'system/modules/frontend/ModuleBooknav.php',
	'Contao\\ModuleBreadcrumb'    => 'system/modules/frontend/ModuleBreadcrumb.php',
	'Contao\\ModuleCustomnav'     => 'system/modules/frontend/ModuleCustomnav.php',
	'Contao\\ModuleFlash'         => 'system/modules/frontend/ModuleFlash.php',
	'Contao\\ModuleHtml'          => 'system/modules/frontend/ModuleHtml.php',
	'Contao\\ModuleLogin'         => 'system/modules/frontend/ModuleLogin.php',
	'Contao\\ModuleLogout'        => 'system/modules/frontend/ModuleLogout.php',
	'Contao\\ModuleNavigation'    => 'system/modules/frontend/ModuleNavigation.php',
	'Contao\\ModulePersonalData'  => 'system/modules/frontend/ModulePersonalData.php',
	'Contao\\ModuleQuicklink'     => 'system/modules/frontend/ModuleQuicklink.php',
	'Contao\\ModuleQuicknav'      => 'system/modules/frontend/ModuleQuicknav.php',
	'Contao\\ModuleRandomImage'   => 'system/modules/frontend/ModuleRandomImage.php',
	'Contao\\ModuleSearch'        => 'system/modules/frontend/ModuleSearch.php',
	'Contao\\ModuleSitemap'       => 'system/modules/frontend/ModuleSitemap.php',
	'Contao\\PageError403'        => 'system/modules/frontend/PageError403.php',
	'Contao\\PageError404'        => 'system/modules/frontend/PageError404.php',
	'Contao\\PageForward'         => 'system/modules/frontend/PageForward.php',
	'Contao\\PageRedirect'        => 'system/modules/frontend/PageRedirect.php',
	'Contao\\PageRegular'         => 'system/modules/frontend/PageRegular.php',
	'Contao\\PageRoot'            => 'system/modules/frontend/PageRoot.php',
	'Contao\\Pagination'          => 'system/modules/frontend/Pagination.php',

	// Models
	'Contao\\ArticleModel'        => 'system/modules/frontend/models/ArticleModel.php',
	'Contao\\ContentElementModel' => 'system/modules/frontend/models/ContentElementModel.php',
	'Contao\\FormFieldModel'      => 'system/modules/frontend/models/FormFieldModel.php',
	'Contao\\FormModel'           => 'system/modules/frontend/models/FormModel.php',
	'Contao\\MemberModel'         => 'system/modules/frontend/models/MemberModel.php',
	'Contao\\ModuleModel'         => 'system/modules/frontend/models/ModuleModel.php',
	'Contao\\PageModel'           => 'system/modules/frontend/models/PageModel.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_accordion'        => 'system/modules/frontend/templates',
	'ce_accordion_start'  => 'system/modules/frontend/templates',
	'ce_accordion_stop'   => 'system/modules/frontend/templates',
	'ce_code'             => 'system/modules/frontend/templates',
	'ce_download'         => 'system/modules/frontend/templates',
	'ce_downloads'        => 'system/modules/frontend/templates',
	'ce_gallery'          => 'system/modules/frontend/templates',
	'ce_headline'         => 'system/modules/frontend/templates',
	'ce_html'             => 'system/modules/frontend/templates',
	'ce_hyperlink'        => 'system/modules/frontend/templates',
	'ce_hyperlink_image'  => 'system/modules/frontend/templates',
	'ce_image'            => 'system/modules/frontend/templates',
	'ce_list'             => 'system/modules/frontend/templates',
	'ce_table'            => 'system/modules/frontend/templates',
	'ce_teaser'           => 'system/modules/frontend/templates',
	'ce_text'             => 'system/modules/frontend/templates',
	'ce_toplink'          => 'system/modules/frontend/templates',
	'fe_page'             => 'system/modules/frontend/templates',
	'form'                => 'system/modules/frontend/templates',
	'form_captcha'        => 'system/modules/frontend/templates',
	'form_checkbox'       => 'system/modules/frontend/templates',
	'form_explanation'    => 'system/modules/frontend/templates',
	'form_headline'       => 'system/modules/frontend/templates',
	'form_hidden'         => 'system/modules/frontend/templates',
	'form_html'           => 'system/modules/frontend/templates',
	'form_message'        => 'system/modules/frontend/templates',
	'form_password'       => 'system/modules/frontend/templates',
	'form_radio'          => 'system/modules/frontend/templates',
	'form_submit'         => 'system/modules/frontend/templates',
	'form_widget'         => 'system/modules/frontend/templates',
	'form_xml'            => 'system/modules/frontend/templates',
	'gallery_default'     => 'system/modules/frontend/templates',
	'mail_default'        => 'system/modules/frontend/templates',
	'member_default'      => 'system/modules/frontend/templates',
	'member_grouped'      => 'system/modules/frontend/templates',
	'mod_article'         => 'system/modules/frontend/templates',
	'mod_article_list'    => 'system/modules/frontend/templates',
	'mod_article_nav'     => 'system/modules/frontend/templates',
	'mod_article_plain'   => 'system/modules/frontend/templates',
	'mod_article_teaser'  => 'system/modules/frontend/templates',
	'mod_booknav'         => 'system/modules/frontend/templates',
	'mod_breadcrumb'      => 'system/modules/frontend/templates',
	'mod_flash'           => 'system/modules/frontend/templates',
	'mod_html'            => 'system/modules/frontend/templates',
	'mod_login_1cl'       => 'system/modules/frontend/templates',
	'mod_login_2cl'       => 'system/modules/frontend/templates',
	'mod_logout_1cl'      => 'system/modules/frontend/templates',
	'mod_logout_2cl'      => 'system/modules/frontend/templates',
	'mod_message'         => 'system/modules/frontend/templates',
	'mod_navigation'      => 'system/modules/frontend/templates',
	'mod_quicklink'       => 'system/modules/frontend/templates',
	'mod_quicknav'        => 'system/modules/frontend/templates',
	'mod_random_image'    => 'system/modules/frontend/templates',
	'mod_search'          => 'system/modules/frontend/templates',
	'mod_search_advanced' => 'system/modules/frontend/templates',
	'mod_search_simple'   => 'system/modules/frontend/templates',
	'mod_sitemap'         => 'system/modules/frontend/templates',
	'moo_accordion'       => 'system/modules/frontend/templates',
	'moo_analytics'       => 'system/modules/frontend/templates',
	'moo_chosen'          => 'system/modules/frontend/templates',
	'moo_mediabox'        => 'system/modules/frontend/templates',
	'moo_slimbox'         => 'system/modules/frontend/templates',
	'nav_default'         => 'system/modules/frontend/templates',
	'pagination'          => 'system/modules/frontend/templates',
	'search_default'      => 'system/modules/frontend/templates',
));

?>