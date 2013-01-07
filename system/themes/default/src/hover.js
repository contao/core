/* Contao Open Source CMS, (C) 2005-2013 Leo Feyer, LGPL license */
var Theme = {
	hoverRow: function(el, state) {
		var items = $(el).getChildren();
		for (var i=0; i<items.length; i++) {
			if (items[i].nodeName.toLowerCase() == 'td') {
				items[i].setStyle('background-color', (state ? '#ebfdd7' : ''));
			}
		}
	},
	hoverDiv: function(el, state) {
		$(el).setStyle('background-color', (state ? '#ebfdd7' : ''));
	},
	fixLabelLastChild: function() {
		if (Browser.ie7 || Browser.ie8) {
			$$('.tl_checkbox_container label:last-child').each(function(el) {
				el.setStyle('margin-bottom', 0);
			});
		}
	}
};

// Fix the :last-child issue (see #4017)
window.addEvent('domready', function() {
	Theme.fixLabelLastChild();
});