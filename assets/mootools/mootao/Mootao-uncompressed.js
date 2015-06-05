/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/*
---

name: Request.Contao

description: Extends the MooTools Request.JSON class with Contao-specific routines.

license: LGPLv3

authors:
 - Leo Feyer

requires: [Request, JSON]

provides: Request.Contao

...
*/

Request.Contao = new Class(
{
	Extends: Request.JSON,

	options: {
		followRedirects: true,
		url: window.location.href
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
		var url = this.getHeader('X-Ajax-Location'),
			json;

		if (url && this.options.followRedirects) {
			location.replace(url);
			return;
		}

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
				Browser.exec(json.javascript);
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

authors:
 - Leo Feyer

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

		var size = window.getSize(),
			scroll = window.getScroll(),
			tip = {x: this.tip.offsetWidth, y: this.tip.offsetHeight},
			props = {x: 'left', y: 'top'},
			bounds = {y: false, x2: false, y2: false, x: false},
			obj = {};

		for (var z in props) {
			obj[props[z]] = event.page[z] + this.options.offset[z];
			if (obj[props[z]] < 0) bounds[z] = true;
			if ((obj[props[z]] + tip[z] - scroll[z]) > size[z] - this.options.windowPadding[z]) {
				if (z == 'x') // Ignore vertical boundaries
					obj[props[z]] = event.page[z] - this.options.offset[z] - tip[z];
				bounds[z+'2'] = true;
			}
		}

		var top = this.tip.getElement('div.tip-top');

		// Adjust the arrow on left/right aligned tips
		if (bounds.x2) {
			obj.left += 24;
			top.setStyles({'left': 'auto', 'right': '9px'});
		} else {
			obj.left -= 9;
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


/*
---

name: Drag

description: Extends the base Drag class with touch support.

license: LGPLv3

authors:
 - Andreas Schempp

requires: [Drag]

provides: Drag

...
*/

Class.refactor(Drag,
{
	attach: function() {
		this.handles.addEvent('touchstart', this.bound.start);
		return this.previous.apply(this, arguments);
	},

	detach: function() {
		this.handles.removeEvent('touchstart', this.bound.start);
		return this.previous.apply(this, arguments);
	},

	start: function() {
		document.addEvents({
			touchmove: this.bound.check,
			touchend: this.bound.cancel
		});
		this.previous.apply(this, arguments);
	},

	check: function(event) {
		if (this.options.preventDefault) event.preventDefault();
		var distance = Math.round(Math.sqrt(Math.pow(event.page.x - this.mouse.start.x, 2) + Math.pow(event.page.y - this.mouse.start.y, 2)));
		if (distance > this.options.snap) {
			this.cancel();
			this.document.addEvents({
				mousemove: this.bound.drag,
				mouseup: this.bound.stop
			});
			document.addEvents({
				touchmove: this.bound.drag,
				touchend: this.bound.stop
			});
			this.fireEvent('start', [this.element, event]).fireEvent('snap', this.element);
		}
	},

	cancel: function() {
		document.removeEvents({
			touchmove: this.bound.check,
			touchend: this.bound.cancel
		});
		return this.previous.apply(this, arguments);
	},

	stop: function() {
		document.removeEvents({
			touchmove: this.bound.drag,
			touchend: this.bound.stop
		});
		return this.previous.apply(this, arguments);
	}
});


/*
---

name: Sortables

description: Extends the base Sortables class with touch support.

license: LGPLv3

authors:
 - Andreas Schempp

requires: [Sortables]

provides: Sortables

...
*/

Class.refactor(Sortables,
{
	initialize: function(lists, options) {
		options.dragOptions = Object.merge(options.dragOptions || {}, { preventDefault: (options.dragOptions && options.dragOptions.preventDefault) || Browser.Features.Touch });
		return this.previous.apply(this, arguments);
	},

	addItems: function() {
		Array.flatten(arguments).each(function(element) {
			this.elements.push(element);
			var start = element.retrieve('sortables:start', function(event) {
				this.start.call(this, event, element);
			}.bind(this));
			(this.options.handle ? element.getElement(this.options.handle) || element : element).addEvents({
				mousedown: start,
				touchstart: start
			});
		}, this);
		return this;
	},

	removeItems: function() {
		return $$(Array.flatten(arguments).map(function(element) {
			this.elements.erase(element);
			var start = element.retrieve('sortables:start');
			(this.options.handle ? element.getElement(this.options.handle) || element : element).removeEvents({
				mousedown: start,
				touchend: start
			});
			return element;
		}, this));
	},

	getClone: function(event, element) {
		if (!this.options.clone) return new Element(element.tagName).inject(document.body);
		if (typeOf(this.options.clone) == 'function') return this.options.clone.call(this, event, element, this.list);
		var clone = this.previous.apply(this, arguments);
		clone.addEvent('touchstart', function(event) {
			element.fireEvent('touchstart', event);
		});
		return clone;
	}
});
