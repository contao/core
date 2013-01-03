/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Typolinks
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

tinyMCEPopup.requireLangPack();

var LinkDialog = {
	preInit : function() {
		var url;

		if (url = tinyMCEPopup.getParam("external_link_list_url"))
			document.write('<script language="javascript" type="text/javascript" src="' + tinyMCEPopup.editor.documentBaseURI.toAbsolute(url) + '"></script>');
	},

	init : function() {
		var f = document.forms[0], ed = tinyMCEPopup.editor;

		// Setup browse button
		document.getElementById('hrefbrowsercontainer').innerHTML = getBrowserHTML('hrefbrowser', 'href', 'file', 'theme_advanced_link');
		if (isVisible('hrefbrowser'))
			document.getElementById('href').style.width = '180px';

		this.fillFileList('link_list', 'tinyMCELinkList');
		this.fillRelList('rel_list');
		this.fillTargetList('target_list');
		this.fillClassList('class_list');

		if (e = ed.dom.getParent(ed.selection.getNode(), 'A')) {
			f.href.value = ed.dom.getAttrib(e, 'href');
			f.linktitle.value = ed.dom.getAttrib(e, 'title');
			f.insert.value = ed.getLang('update');
			selectByValue(f, 'link_list', f.href.value);
			selectByValue(f, 'target_list', ed.dom.getAttrib(e, 'target'));
			selectByValue(f, 'rel_list', ed.dom.getAttrib(e, 'rel'), true); // PATCH: rel attribute
			selectByValue(f, 'class_list', ed.dom.getAttrib(e, 'class'), true); // PATCH: add true to set custom values
		}

		TinyMCE_EditableSelects.init(); // PATCH: initialize editable select
	},

	update : function() {
		var f = document.forms[0], ed = tinyMCEPopup.editor, e, b;

		tinyMCEPopup.restoreSelection();
		e = ed.dom.getParent(ed.selection.getNode(), 'A');

		// Remove element if there is no href
		if (!f.href.value) {
			if (e) {
				tinyMCEPopup.execCommand("mceBeginUndoLevel");
				b = ed.selection.getBookmark();
				ed.dom.remove(e, 1);
				ed.selection.moveToBookmark(b);
				tinyMCEPopup.execCommand("mceEndUndoLevel");
				tinyMCEPopup.close();
				return;
			}
		}

		tinyMCEPopup.execCommand("mceBeginUndoLevel");

		// Create new anchor elements
		if (e == null) {
			ed.getDoc().execCommand("unlink", false, null);
			tinyMCEPopup.execCommand("CreateLink", false, "#mce_temp_url#", {skip_undo : 1});
			var t = this;

			tinymce.each(ed.dom.select("a"), function(n) {
				if (ed.dom.getAttrib(n, 'href') == '#mce_temp_url#') {
					e = n;

					ed.dom.setAttribs(e, {
						href : f.href.value,
						title : f.linktitle.value,
						target : f.target_list ? getSelectValue(f, "target_list") : null,
						'rel' : f.rel_list ? getSelectValue(f, "rel_list") : null,
						'class' : f.class_list ? getSelectValue(f, "class_list") : null
					});

					// PATCH: fix issues
					t.fixIssues(ed, e, f);
				}
			});
		} else {
			ed.dom.setAttribs(e, {
				href : f.href.value,
				title : f.linktitle.value,
				target : f.target_list ? getSelectValue(f, "target_list") : null,
				'rel' : f.rel_list ? getSelectValue(f, "rel_list") : null,
				'class' : f.class_list ? getSelectValue(f, "class_list") : null
			});

			// PATCH: fix issues
			this.fixIssues(ed, e, f);
		}

		// Don't move caret if selection was image
		if (e.childNodes.length != 1 || e.firstChild.nodeName != 'IMG') {
			ed.focus();
			ed.selection.select(e);
			ed.selection.collapse(0);
			tinyMCEPopup.storeSelection();
		}

		tinyMCEPopup.execCommand("mceEndUndoLevel");
		tinyMCEPopup.close();
	},

	// PATCH: add function fixIssues
	fixIssues : function(ed, e, f) {
		// Fix relative URLs
		if (f.href.value+'/' == ed.settings.document_base_url) {
			f.href.value += '/';
		}
		if (f.href.value == ed.settings.document_base_url) {
			e.setAttribute('mce_href', f.href.value);
		}
	},
	// PATCH EOF

	checkPrefix : function(n) {
		if (n.value && Validator.isEmail(n) && !/^\s*mailto:/i.test(n.value) && confirm(tinyMCEPopup.getLang('typolinks_dlg.link_is_email')))
			n.value = 'mailto:' + n.value;

		if (/^\s*www\./i.test(n.value) && confirm(tinyMCEPopup.getLang('typolinks_dlg.link_is_external')))
			n.value = 'http://' + n.value;

		if (n.value && /^#/.test(n.value))
			n.value = '{{env::request}}' + n.value;
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

	fillRelList : function(id) {
		var dom = tinyMCEPopup.dom, lst = dom.get(id), v;

		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('not_set'), '');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_rel_single'), 'lightbox');
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.image_rel_multi'), 'lightbox[multi]');
	},

	fillTargetList : function(id) {
		var dom = tinyMCEPopup.dom, lst = dom.get(id), v;

		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('not_set'), '');
		//lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.link_target_same'), '_self'); PATCH: hide
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('typolinks_dlg.link_target_blank'), '_blank');

		if (v = tinyMCEPopup.getParam('theme_advanced_link_targets')) {
			tinymce.each(v.split(','), function(v) {
				v = v.split('=');
				lst.options[lst.options.length] = new Option(v[0], v[1]);
			});
		}
	},

	fillClassList : function(id) {
		var dom = tinyMCEPopup.dom, lst = dom.get(id), v, cl;

		if (v = tinyMCEPopup.getParam('theme_advanced_styles')) {
			cl = [];

			tinymce.each(v.split(';'), function(v) {
				var p = v.split('=');

				cl.push({'title' : p[0], 'class' : p[1]});
			});
		} else
			cl = tinyMCEPopup.editor.dom.getClasses();

		// PATCH: always show not_set option
		lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('not_set'), '');

		if (cl.length > 0) {
			tinymce.each(cl, function(o) {
				lst.options[lst.options.length] = new Option(o.title || o['class'], o['class']);
			});
		}/* else
			dom.remove(dom.getParent(id, 'tr')); PATCH: do not remove */
	}
};

LinkDialog.preInit();
tinyMCEPopup.onInit.add(LinkDialog.init, LinkDialog);
