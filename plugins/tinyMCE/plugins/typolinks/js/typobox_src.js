/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Plugins
 * @license    LGPL
 * @filesource
 */
var Typobox = {
	preInit : function() {
		var url;

		tinyMCEPopup.requireLangPack();

		if (url = tinyMCEPopup.getParam("external_image_list_url"))
			document.write('<script language="javascript" type="text/javascript" src="' + tinyMCEPopup.editor.documentBaseURI.toAbsolute(url) + '"></script>');
	},

	init : function() {
		var f = document.forms[0], ed = tinyMCEPopup.editor;

		// Setup browse button
		document.getElementById('srcbrowsercontainer').innerHTML = getBrowserHTML('srcbrowser','src','image','theme_advanced_image');
		if (isVisible('srcbrowser'))
			document.getElementById('src').style.width = '180px';

		this.fillFileList('image_list', 'tinyMCEImageList');
		this.fillModeList('mode_list');
		this.fillRelList('rel_list');
		TinyMCE_EditableSelects.init();

		c = ed.selection.getContent();
		if (!c || c.indexOf("image::") == -1) {
			return;
		}

		c = c.replace(/^.*\{\{image::/gi, '');
		c = c.replace(/\}\}.*$/i, '');
		c = c.replace(/\[&amp;\]|\[&\]|&amp;|&/gi, '?');

		var split = c.split('?');
		f.src.value = split[0];

		for (i=1; i<split.length; i++) {
			sub = split[i].split('=');
			switch (sub[0]) {
				case 'width':
					f.width.value = sub[1];
					break;
				case 'height':
					f.height.value = sub[1];
					break;
				case 'alt':
					f.alt.value = sub[1];
					break;
				case 'class':
					f.cssClass.value = sub[1];
					break;
				case 'mode':
					selectByValue(f, 'mode_list', sub[1], true);
					break;
				case 'rel':
					selectByValue(f, 'rel_list', sub[1], true);
					break;
			}
		}
	},

	fillFileList : function(id, l) {
		var dom = tinyMCEPopup.dom, lst = dom.get(id), v, cl;

		l = window[l];

		if (l && l.length > 0) {
			lst.options[lst.options.length] = new Option('', '');

			tinymce.each(l, function(o) {
				lst.options[lst.options.length] = new Option(o[0], o[1]);
			});
		} else
			dom.remove(dom.getParent(id, 'tr'));
	},

	fillModeList : function(id) {
		var dom = tinyMCEPopup.dom, lst = dom.get(id), v;

		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_crop'), 'crop');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_proportional'), 'proportional');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_box'), 'box');
	},

	fillRelList : function(id) {
		var dom = tinyMCEPopup.dom, lst = dom.get(id), v;

		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('not_set'), '');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_rel_single'), 'lightbox');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_rel_multi'), 'lightbox[multi]');
	},

	update : function() {
		var f = document.forms[0], nl = f.elements, ed = tinyMCEPopup.editor, args = {}, el;

		tinyMCEPopup.restoreSelection();

		if (f.src.value == '') {
			tinyMCEPopup.close();
			return;
		}

		var tag = f.src.value;
		var glue = '?';

		if (f.width.value) {
			tag += glue + 'width=' + f.width.value;
			glue = '&amp;';
		}
		if (f.height.value) {
			tag += glue + 'height=' + f.height.value;
			glue = '&amp;';
		}
		if (f.alt.value) {
			tag += glue + 'alt=' + f.alt.value;
			glue = '&amp;';
		}
		if (f.cssClass.value) {
			tag += glue + 'class=' + f.cssClass.value;
			glue = '&amp;';
		}
		if (f.mode_list) {
			tag += glue + 'mode=' + getSelectValue(f, "mode_list");
		}
		if (f.rel_list) {
			tag += glue + 'rel=' + getSelectValue(f, "rel_list");
		}

		tag = '{{image::' + tag + '}}';

		el = ed.selection.getNode();
		ed.execCommand("mceInsertRawHTML", false, tag);
		ed.undoManager.add();

		tinyMCEPopup.close();
	}
};

Typobox.preInit();
tinyMCEPopup.onInit.add(Typobox.init, Typobox);
