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
 * @package    Plugins
 * @license    LGPL
 * @filesource
 */
(function() {
	tinymce.create('tinymce.plugins.TypolinksPlugin', {
		init : function(ed, url) {
			// Register command
			ed.addCommand('mceTypolinks', function() {
				ed.windowManager.open({
					file : url + '/typolinks.php',
					width : 360 + parseInt(ed.getLang('typolinks.delta_width', 0)),
					height : 230 + parseInt(ed.getLang('typolinks.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register button
			ed.addButton('typolinks', {
				title : 'typolinks.desc',
				cmd : 'mceTypolinks',
				image : url + '/images/link.gif'
			});

			// Add shortcut
			ed.addShortcut('ctrl+k', 'typolinks.desc', 'mceTypolinks');

			// Add a node change handler
			ed.onNodeChange.add(function(ed, cm, n, co) {
				cm.setDisabled('typolinks', co && n.nodeName != 'A');
				cm.setActive('typolinks', n.nodeName == 'A');
			});
		},

		getInfo : function() {
			return {
				longname : 'TYPOlight links plugin',
				author : 'Leo Feyer',
				authorurl : 'http://www.typolight.org',
				infourl : 'http://www.typolight.org',
				version : '1.1'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('typolinks', tinymce.plugins.TypolinksPlugin);
})();