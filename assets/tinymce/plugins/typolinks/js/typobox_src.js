/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Typolinks
 * @see     https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
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

		var c = ed.selection.getContent();
		if (!c || c.indexOf("image::") == -1) {
			return;
		}

		c = c.replace(/^.*\{\{image::/gi, '');
		c = c.replace(/\}\}.*$/i, '');
		c = c.replace(/\[&amp;\]|\[&\]|&amp;|&/gi, '?');

		var split = c.split('?'), sub, i;
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
		var dom = tinyMCEPopup.dom, lst = dom.get(id);

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
		var dom = tinyMCEPopup.dom, lst = dom.get(id);

		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_proportional'), 'proportional');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_box'), 'box');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_left_top'), 'left_top');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_center_top'), 'center_top');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_right_top'), 'right_top');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_left_center'), 'left_center');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_center_center'), 'center_center');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_right_center'), 'right_center');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_left_bottom'), 'left_bottom');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_center_bottom'), 'center_bottom');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_right_bottom'), 'right_bottom');
	},

	fillRelList : function(id) {
		var dom = tinyMCEPopup.dom, lst = dom.get(id);

		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('not_set'), '');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_rel_single'), 'lightbox');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_rel_multi'), 'lightbox[multi]');
	},

	update : function() {
		var f = document.forms[0], ed = tinyMCEPopup.editor;

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
			glue = '&amp;';
		}
		if (f.rel_list) {
			tag += glue + 'rel=' + getSelectValue(f, "rel_list");
		}

		tag = '{{image::' + tag + '}}';

		ed.selection.getNode();
		ed.execCommand("mceInsertRawHTML", false, tag);
		ed.undoManager.add();

		tinyMCEPopup.close();
	}
};

Typobox.preInit();
tinyMCEPopup.onInit.add(Typobox.init, Typobox);
