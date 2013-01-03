/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Mootao
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/*
---

name: Request.Contao

description: Extends the MooTools Request.JSON class with Contao-specific routines.

license: LGPLv3

requires: [Request, JSON]

provides: Request.Contao

...
*/

Request.Contao = new Class(
{
	Extends: Request.JSON,

	options: {
		'url': window.location.href
	},

	initialize: function(options) {
		if (options) {
			// Try to replace the URL with the form action
			try	{
				this.options.url = options.field.getParent('form').getAttribute('action');
			} catch(e) {}
		}
		this.parent(options);
	},

	success: function(text) {
		var json;

		// Support both plain text and JSON responses
		try	{
			json = this.response.json = JSON.decode(text, this.options.secure);
		} catch(e) {
			json = {'content':text};
		}

		// Empty response
		if (json == null) {
			json = {'content':''};
		}

		// Isolate scripts and execute them
		if (json.content != '') {
			json.content = json.content.stripScripts(function(script) {
				json.javascript = script.replace(/<!--|\/\/-->|<!\[CDATA\[\/\/>|<!\]\]>/g, '');
			});
			if (json.javascript && this.options.evalScripts) {
				$exec(json.javascript);
			}
		}

		this.onSuccess(json.content, json);
	}
});

// Backwards compatibility
Request.Mixed = Request.Contao;


/*
---

name: Tips.Contao

description: Extends the MooTools Tips class with Contao-specific routines.

license: LGPLv3

requires: [Tips]

provides: Tips.Contao

...
*/

Tips.Contao = new Class(
{
	Extends: Tips,

	options: {
		id: 'tip',
		onShow: function(){
			this.tip.setStyle('display', 'block');
		},
		onHide: function(){
			this.tip.setStyle('display', 'none');
		},
		title: 'title',
		text: '',
		showDelay: 1000,
		hideDelay: 100,
		className: 'tip-wrap',
		offset: {x:16, y:16},
		windowPadding: {x:0, y:0},
		fixed: true,
		waiAria: true
	},

	position: function(event) {
		if (!this.tip) document.id(this);

		var size = window.getSize(), scroll = window.getScroll(),
			tip = {x: this.tip.offsetWidth, y: this.tip.offsetHeight},
			props = {x: 'left', y: 'top'},
			bounds = {y: false, x2: false, y2: false, x: false},
			obj = {};

		for (var z in props){
			obj[props[z]] = event.page[z] + this.options.offset[z];
			if (obj[props[z]] < 0) bounds[z] = true;
			if ((obj[props[z]] + tip[z] - scroll[z]) > size[z] - this.options.windowPadding[z]){
				if (z == 'x') // Ignore vertical boundaries
					obj[props[z]] = event.page[z] - this.options.offset[z] - tip[z];
				bounds[z+'2'] = true;
			}
		}

		var top = this.tip.getElement('div.tip-top');

		// Adjust the arrow on left/right aligned tips
		if (bounds.x2) {
			obj['margin-left'] = '24px';
			top.setStyles({'left': 'auto', 'right': '9px'});
		} else {
			obj['margin-left'] = '-9px';
			top.setStyles({'left': '9px', 'right': 'auto'});
		}

		this.fireEvent('bound', bounds);
		this.tip.setStyles(obj);
	},

	hide: function(element) {
		if (!this.tip) document.id(this);
		this.fireEvent('hide', [this.tip, element]);
	}
});
