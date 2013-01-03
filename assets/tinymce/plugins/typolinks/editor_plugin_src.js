/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Typolinks
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
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
					file : url + '/typobox.htm',
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
				infourl : 'https://contao.org',
				version : '3.4.6'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('typolinks', tinymce.plugins.TypolinksPlugin);
})();