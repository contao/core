<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Comments
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Contao\Comments'            => 'system/modules/comments/classes/Comments.php',

	// Elements
	'Contao\ContentComments'     => 'system/modules/comments/elements/ContentComments.php',

	// Models
	'Contao\CommentsModel'       => 'system/modules/comments/models/CommentsModel.php',
	'Contao\CommentsNotifyModel' => 'system/modules/comments/models/CommentsNotifyModel.php',

	// Modules
	'Contao\ModuleComments'      => 'system/modules/comments/modules/ModuleComments.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'com_default'      => 'system/modules/comments/templates/comments',
	'ce_comments'      => 'system/modules/comments/templates/elements',
	'mod_comment_form' => 'system/modules/comments/templates/modules',
));
