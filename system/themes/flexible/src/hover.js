/* Contao Open Source CMS, (c) 2005-2014 Leo Feyer, LGPL license */
var Theme = {

	/**
	 * Check for WebKit
	 * @member {boolean}
 	 */
	isWebkit: (Browser.chrome || Browser.safari || navigator.userAgent.match(/(?:webkit|khtml)/i)),

	/**
	 * Autofocus the first text field or textarea
	 *
	 * @param {string} id The ID of the parent element
	 */
	focusInput: function(id) {
		if (id == '') return;
		var el = $$('#'+id+' input[class^="tl_text"],#'+id+' textarea');
		if (el && el.length > 0) el[0].focus();
	},

	/**
	 * Colorize a table row when hovering over it
	 *
	 * @param {object} el    The DOM element
	 * @param {int}    state The current state
	 */
	hoverRow: function(el, state) {
		var items = $(el).getChildren();
		for (var i=0; i<items.length; i++) {
			if (items[i].nodeName.toLowerCase() == 'td') {
				items[i].setStyle('background-color', (state ? '#ebfdd7' : ''));
			}
		}
	},

	/**
	 * Colorize a layer when hovering over it
	 *
	 * @param {object} el    The DOM element
	 * @param {int}    state The current state
	 */
	hoverDiv: function(el, state) {
		if (!state) {
			el.removeAttribute('data-visited');
		}
		$(el).setStyle('background-color', (state ? '#ebfdd7' : ''));
	},

	/**
	 * Toggle a group of checkboxes
	 *
	 * @param {object} el The DOM element
	 */
	toggleSelect: function(el) {
		var input = $(el).getElement('input');
		if (input) {
			if (input.checked) {
				if (input.get('type') != 'radio') {
					input.checked = '';
				}
			} else {
				input.checked = 'checked';
			}
			if (input.get('onclick') == 'Backend.toggleCheckboxes(this)') {
				Backend.toggleCheckboxes(input); // see #6399
			}
		}
	},

	/**
	 * Work around the missing :last-child support in IE7 and IE8 (see #4017)
	 */
	fixLabelLastChild: function() {
		if (Browser.ie7 || Browser.ie8) {
			$$('.tl_checkbox_container label:last-child').each(function(el) {
				el.setStyle('margin-bottom', 0);
			});
		}
	},

	/**
	 * Stop the propagation of click events of certain elements
	 */
	stopClickPropagation: function() {

		// Do not propagate the click events of the icons
		$$('.picker_selector').each(function(ul) {
			ul.getElements('a').each(function(el) {
				el.addEvent('click', function(e) {
					e.stopPropagation();
				});
			});
		});

		// Do not propagate the click events of the checkboxes
		$$('.picker_selector,.click2edit').each(function(ul) {
			ul.getElements('input[type="checkbox"]').each(function(el) {
				el.addEvent('click', function(e) {
					e.stopPropagation();
				});
			});
		});
	},

	/**
	 * Set up the [Ctrl] + click to edit functionality
	 */
	setupCtrlClick: function() {
		$$('.click2edit').each(function(el) {

			// Do not propagate the click events of the default buttons (see #5731)
			el.getElements('a').each(function(a) {
				a.addEvent('click', function(e) {
					e.stopPropagation();
				});
			});

			// Set up regular click events on touch devices
			if (Browser.Features.Touch) {
				el.addEvent('click', function() {
					if (!el.getAttribute('data-visited')) {
						el.setAttribute('data-visited', 1);
					} else {
						el.getElements('a').each(function(a) {
							if (a.hasClass('edit')) {
								document.location.href = a.href;
							}
						});
						el.removeAttribute('data-visited');
					}
				});
			} else {
				el.addEvent('click', function(e) {
					var key = Browser.Platform.mac ?
							e.event.metaKey : e.event.ctrlKey;
					if (key && e.event.shiftKey) {
						el.getElements('a').each(function(a) {
							if (a.hasClass('editheader')) {
								document.location.href = a.href;
							}
						});
					} else if (key) {
						el.getElements('a').each(function(a) {
							if (a.hasClass('edit')) {
								document.location.href = a.href;
							}
						});
					}
				});
			}
		});
	},

	/**
	 * Set up the textarea resizing
	 */
	setupTextareaResizing: function() {
		$$('.tl_textarea').each(function(el) {
			if (Browser.ie6 || Browser.ie7 || Browser.ie8) return;
			if (el.hasClass('noresize') || el.retrieve('autogrow')) return;

			// Set up the dummy element
			var dummy = new Element('div', {
				html: 'X',
				styles: {
					'position':'absolute',
					'top':0,
					'left':'-999em',
					'overflow-x':'hidden'
				}
			}).setStyles(
				el.getStyles('font-size', 'font-family', 'width', 'line-height')
			).inject(document.body);

			// Also consider the box-sizing
			if (el.getStyle('-moz-box-sizing') == 'border-box' || el.getStyle('-webkit-box-sizing') == 'border-box' || el.getStyle('box-sizing') == 'border-box') {
				dummy.setStyles({
					'padding': el.getStyle('padding'),
					'border': el.getStyle('border-left')
				});
			}

			// Single line height
			var line = dummy.clientHeight;

			// Respond to the "input" event
			el.addEvent('input', function() {
				dummy.set('html', this.get('value')
					.replace(/</g, '&lt;')
					.replace(/>/g, '&gt;')
					.replace(/\n|\r\n/g, '<br>X'));
				var height = Math.max(line, dummy.getSize().y);
				if (this.clientHeight != height) this.tween('height', height);
			}).set('tween', { 'duration':100 }).setStyle('height', line + 'px');

			// Fire the event
			el.fireEvent('input');
			el.store('autogrow', true);
		});
	},

	/**
	 * Set up the menu toggle
	 */
	setupMenuToggle: function() {
		$$('.collapsible').each(function(el) {
			el.getElement('h1').addEvent('click', function() {
				el.toggleClass('xpnd');
			});
		});
	}
};

// Initialize
window.addEvent('domready', function() {
	Theme.fixLabelLastChild();
	Theme.stopClickPropagation();
	Theme.setupCtrlClick();
	Theme.setupTextareaResizing();
	Theme.setupMenuToggle();
});

// Respond to Ajax changes
window.addEvent('ajax_change', function() {
	Theme.stopClickPropagation();
	Theme.setupCtrlClick();
	Theme.setupTextareaResizing();
});
