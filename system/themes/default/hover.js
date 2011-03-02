/* Contao Open Source CMS :: Copyright (C) 2005-2011 Leo Feyer :: LGPL license */
var Theme =
{
	hoverRow: function(el, state)
	{
		var items = $(el).getChildren();

		for (var i=0; i<items.length; i++)
		{
			if (items[i].nodeName.toLowerCase() == 'td')
			{
				items[i].setStyle('background-color', (state ? '#ebfdd7' : ''));
			}
		}
	},

	hoverDiv: function(el, state)
	{
		$(el).setStyle('background-color', (state ? '#ebfdd7' : ''));
	}
};