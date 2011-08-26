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
 * @package    Plugins
 * @license    LGPL
 * @filesource
 */
(function() {
	tinymce.create('tinymce.plugins.TypolinksPlugin', {
		init : function(ed, url) {

			// Register typolinks command
			ed.addCommand('mceTypolinks', function() {
				ed.windowManager.open({
					file : url + '/typolinks.php',
					width : 360 + parseInt(ed.getLang('typolinks.delta_width', 0)),
					height : 256 + parseInt(ed.getLang('typolinks.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register button
			ed.addButton('typolinks', {
				title : 'typolinks.link_desc',
				cmd : 'mceTypolinks',
				image : url + '/img/link.gif'
			});

			// Add shortcut
			ed.addShortcut('ctrl+k', 'typolinks.desc', 'mceTypolinks');

			// Add a node change handler
			ed.onNodeChange.add(function(ed, cm, n, co) {
				cm.setDisabled('typolinks', co && n.nodeName != 'A');
				cm.setActive('typolinks', n.nodeName == 'A');
			});

			// Register lightbox image command
			ed.addCommand('mceTypobox', function() {
				ed.windowManager.open({
					file : url + '/typobox.html',
					width : 360 + parseInt(ed.getLang('typobox.delta_width', 0)),
					height : 256 + parseInt(ed.getLang('typobox.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register button
			ed.addButton('typobox', {
				title : 'typolinks.image_desc',
				cmd : 'mceTypobox',
				image : url + '/img/image.gif'
			});
		},

		getInfo : function() {
			return {
				longname : 'Contao plugin',
				author : 'Leo Feyer',
				authorurl : 'http://www.inetrobots.com',
				infourl : 'http://www.contao.org',
				version : '3.4.2'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('typolinks', tinymce.plugins.TypolinksPlugin);
})();