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
var Typobox = {
	preInit : function() {
		var url;

		tinyMCEPopup.requireLangPack();

		if (url = tinyMCEPopup.getParam("external_image_list_url"))
			document.write('<script language="javascript" type="text/javascript" src="' + tinyMCEPopup.editor.documentBaseURI.toAbsolute(url) + '"></script>');
	},

	init : function() {
		var f = document.forms[0], ed = tinyMCEPopup.editor, c = ed.selection.getContent();
		this.fillFileList('image_list', 'tinyMCEImageList');

		if (!c || c.indexOf("image::") == -1) {
			return;
		}

		selectByValue(f, 'rel', '');

		c = c.replace(/^.*\{\{image::/gi, '');
		c = c.replace(/\}\}.*$/i, '');
		c = c.replace(/\[&amp;\]|\[&\]|&amp;|&/gi, '?');

		var split = c.split('?')
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
				case 'rel':
					selectByValue(f, 'rel', sub[1]);
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
		if (f.rel.value) {
			tag += glue + 'rel=' + f.rel.value;
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
