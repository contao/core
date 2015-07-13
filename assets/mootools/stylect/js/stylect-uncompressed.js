/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Replace select menus with a nicer, JavaScript based solution.
 *
 * @author Leo Feyer <http://contao.org>
 * @author Joe Ray Gregory <https://github.com/may17>
 */
var Stylect =
{
	/**
	 * Check for WebKit
	 * @member {boolean}
 	 */
	isWebkit: (Browser.chrome || Browser.safari || navigator.userAgent.match(/(?:webkit|khtml)/i)),

	/**
	 * Create the div template
	 * @member {string}
 	 */
	template: new Element('div', {
		'class': 'styled_select',
		'html': '<span></span><b><i></i></b>'
	}),

	/**
	 * Respond to the change event
	 *
	 * @param {object} el The DOM element
	 */
	update: function(el) {
		var div = el.retrieve('div'),
			opt = el.getElement('option[value="' + Slick.escapeRegExp(el.value) + '"]');

		if (div && opt) {
			div.getElement('span').set('text', opt.get('text'));
		}
	},

	/**
	 * Respond to the keydown event
	 *
	 * @param {object} el The DOM element
	 */
	keydown: function(el) {
		setTimeout(function() { Stylect.update(el); }, 100);
	},

	/**
	 * Respond to the focus event
	 *
	 * @param {object} div The DOM element
	 */
	focus: function(div) {
		div.addClass('focused');
	},

	/**
	 * Respond to the blur event
	 *
	 * @param {object} div The DOM element
	 */
	blur: function(div) {
		div.removeClass('focused');
	},

	/**
	 * Find all select menus and try to convert them
	 */
	convertSelects: function() {

		// Not supported in IE < 9
		if (Browser.ie6 || Browser.ie7 || Browser.ie8) return;

		$$('select').each(function(el) {

			// Multiple select
			if (el.get('multiple')) return;

			// Handled by chosen
			if (el.hasClass('tl_chosen')) return;

			// Initialize the variables
			var cls = el.get('class'),
				div = el.retrieve('div'),
				style = el.get('style');

			// Clone the template
			if (!div) {
				div = Stylect.template.clone();
				div.addClass(cls).inject(el, 'before');
			}

			// Hide the original select menu
			el.setStyle('opacity', 0);

			// Apply an extra bottom margin in WebKit
			if (Stylect.isWebkit) {
				el.setStyle('margin-bottom', '4px');
			}

			// Update the div onchange
			el.addEvents({
				'change':  function() { Stylect.update(el) },
				'keydown': function() { Stylect.keydown(el) },
				'focus':   function() { Stylect.focus(div) },
				'blur':    function() { Stylect.blur(div) }
			});

			// Mark disabled elements
			if (el.disabled) {
				div.addClass('disabled');
			}

			// Mark active elements
			if (el.hasClass('active')) {
				div.addClass('active');
			}

			// Apply the inline width if any (see #5487)
			if (style && style.test('(^width|[^-]width)')) {
				div.setStyle('width', el.getStyle('width'));
			}

			// Store the reference
			el.store('div', div);

			// Activate
			Stylect.update(el);
		});
	}
};

// Convert selects upon domready
window.addEvent('domready', function() {
	Stylect.convertSelects();
});

// Convert selects upon Ajax changes
window.addEvent('ajax_change', function() {
	Stylect.convertSelects();
});
